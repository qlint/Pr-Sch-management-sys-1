<?php
	$leftside = 'mailbox.views.default.left_side';
	
	$roles=Rights::getAssignedRoles(Yii::app()->user->Id); 	
	if(sizeof($roles)==1 and key($roles) == 'parent')
		$leftside = 'application.modules.parentportal.views.default.leftside'; 
	
	$this->renderPartial($leftside);

    $settings       = UserSettings::model()->findByAttributes(array('user_id'=>1));
    $configuration  = Configurations::model()->findByPk(5);
	$feeconfig 	= FeeConfigurations::model()->find();	//fee cofigurations

    $invoice_amount = 0;
    foreach($particulars as $key=>$particular){
        $amount = $particular->amount;
		if($feeconfig->discount_in_fee==1){
			//apply discount		
			if($particular->discount_type==1){  //percentage
				$idiscount  = (($particular->amount * $particular->discount_value)/100);
				$amount     = $amount - $idiscount;
			}
			else if($particular->discount_type==2){ //amount
				$amount = $amount - $particular->discount_value;
			}
        }
        
		if($feeconfig->tax_in_fee==1){
			//apply tax
			if($particular->tax!=0){
				$tax    = FeeTaxes::model()->findByPk($particular->tax);
				if($tax!=NULL){
					$itax   = (($amount * $tax->value)/100);
					$amount = $amount + $itax;
				}
			}
		}
        $invoice_amount   += $amount;
    }
    
    $amount_payable = 0;
    $payments       = 0;
    $adjustments    = 0;

    $criteria       = new CDbCriteria;
    $criteria->compare('invoice_id', $invoice->id);
    $alltransactions    = FeeTransactions::model()->findAll($criteria);

    foreach($alltransactions as $index=>$ctransaction){
        if($ctransaction->is_deleted==0 and $ctransaction->status==1){
            if($ctransaction->amount<0){
                $adjustments    += $ctransaction->amount;
            }
            else{
                $payments       += $ctransaction->amount;
            }
        }
    }

    $amount_payable = $invoice_amount - ( $payments + $adjustments );
?>
<style>
table, th, td {
	border: 1px solid black;
	border-collapse: collapse;
}
</style>
<div class="pageheader">
    <div class="col-lg-8">
        <h2><i class="fa fa-money"></i><?php echo Yii::t("app",'Invoices');?><span><?php echo Yii::t("app",'View invoices here');?></span></h2>
    </div>
    <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t("app", "You are here");?>:</span>
        <ol class="breadcrumb">                
            <li class="active"><?php echo Yii::t("app", "Invoices")?></li>
        </ol>
    </div>    
    <div class="clearfix"></div>    
</div>

<div class="contentpanel">
    <?php
    if(Yii::app()->user->hasFlash('success')){
    ?>
    <div style="margin-top:18px;" class="alert alert-success fade in">
        <a title="close" aria-label="close" data-dismiss="alert" class="close" href="#">×</a>
        <strong><?php echo Yii::t('app', 'Success!');?></strong>
        <?php echo Yii::app()->user->getFlash('success');?>
    </div>
    <?php
    }
    ?>
    <?php
    if(Yii::app()->user->hasFlash('error')){
    ?>
    <div style="margin-top:18px;" class="alert alert-danger fade in">
        <a title="close" aria-label="close" data-dismiss="alert" class="close" href="#">×</a>
        <strong><?php echo Yii::t('app', 'Error!');?></strong>
        <?php echo Yii::app()->user->getFlash('error');?>
    </div>
    <?php
    }
    ?>
</div>

<div class="contentpanel"> 
    <div class="panel-heading">    
		<h3 class="panel-title"><?php echo Yii::t('app','Invoice')." - #".$invoice->id;?></h3>
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>
<li>
			<?php echo CHtml::link(Yii::t("app", "Generate PDF"), array("/fees/myInvoices/print", "id"=>$invoice->id), array('class'=>"btn btn-danger", 'target'=>"_blank"));?>
            </li>
            </ul>
            </div>
        </div>
    </div> 
    <div class="people-item">  
		<div class="table-responsive">
            <table class="table table-hover">
                <tr>
                    <td width="25%"><strong><?php echo Yii::t("app", "Invoice ID");?></strong></td>
                    <td> <?php echo $invoice->id;?></td>
                </tr>
                <?php
                    if(FormFields::model()->isVisible("fullname", "Students", "forParentPortal")){                      
                ?>
                    <tr>
                        <td><strong><?php echo Yii::t("app", "Recipient");?></strong></td>
                        <td>
                        <?php
                            $display_name   = "-";
                            if($invoice->table_id!=NULL and $invoice->table_id!=0){
                                if($invoice->user_type==1){ //student
                                    $student        = Students::model()->findByPk($invoice->table_id);
                                    if($student!=NULL)
                                        $display_name   = $student->studentFullName('forParentPortal');
                                }
                            }
                            //display name
                            if($invoice->table_id!=NULL and $invoice->table_id!=0){
                                if($invoice->user_type==1)  //student
                                    echo CHtml::link($display_name, array('/parentportal/default/studentprofile', 'id'=>$invoice->table_id));
                                else
                                    echo $display_name;
                            }       
                            else{
                                echo $display_name;
                            }
                        ?>
                        </td>
                    </tr>
                <?php } ?> 
                <tr>
                    <td><strong><?php echo Yii::t("app", "Invoice Date");?></strong>
                    </td>
                    <td> 
                        <?php
                            if($settings!=NULL)
                                echo date($settings->displaydate, strtotime($invoice->created_at));
                            else
                                echo $invoice->created_at;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo Yii::t("app", "Invoice Amount");?></strong></td>
                    <td><?php echo number_format($invoice_amount, 2);?></td>
                </tr>
                <tr>
                    <td><strong><?php echo Yii::t("app", "Adjustments");?></strong></td>
                    <td><?php echo number_format($adjustments, 2);?></td>
                </tr>
                <tr>
                    <td><strong><?php echo Yii::t("app", "Payment Details");?></strong></td>
                    <td><?php echo number_format($payments, 2);?></td>
                </tr>
                <tr>
                    <td><strong><?php echo Yii::t("app", "Amount Payable");?></strong></td>
                    <td><?php echo number_format($amount_payable, 2);?></td>
                </tr>             
                <tr>
                    <td><strong><?php echo Yii::t("app", "Due Date");?></strong> </td>                
                    <td>
                    <?php
                        if($settings!=NULL)
                            echo date($settings->displaydate, strtotime($invoice->due_date));
                        else
                            echo $invoice->due_date;
                    ?>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo Yii::t("app", "Status");?></strong> </td>
                    <td style="font-size:20px; font-weight:bold;">
                        <?php
                            if($invoice->is_canceled==1)
                                echo "<span style='color:#090'>".Yii::t("app","Canceled")."</span>";
                            else
                                echo ($invoice->is_paid==1)?"<span style='color:#090'>".Yii::t("app","Paid")."</span>":"<span style='color:#F00'>".Yii::t("app","Unpaid")."</span>";
                        ?>
                    </td>
                </tr>
            </table>          
		</div>
        <?php
        	$colspan=4;
		?>
        <div class="table-responsive">
            <table class="table table-hover">
                <tbody>
                    <tr class="pdtab-h">
                        <td height="18" align="center">#</td>
                        <td align="center"><?php echo Yii::t('app','Particular'); ?></td>
                        <td height="18"><?php echo Yii::t('app','Description'); ?></td>                                            
                        <td align="center"><?php echo Yii::t('app','Unit Price'); ?></td>
                        <?php if($feeconfig==NULL or $feeconfig->discount_in_fee==1){$colspan++;?>
                        <td align="center"><?php echo Yii::t('app','Discount'); ?></td>
                        <?php }?>
                		<?php if($feeconfig==NULL or $feeconfig->tax_in_fee==1){$colspan++;?>
                        <td align="center"><?php echo Yii::t('app','Tax'); ?></td>
                        <?php }?>                                       
                        <td align="center"><?php echo Yii::t('app','Amount'); ?></td>
                    </tr>
                    <?php
                    $amount_total	= 0;
                    $fine_total		= 0;
                    foreach($particulars as $key=>$particular){
                    ?>
                    <tr>
                        <td align="center"><?php echo $key+1;?></td>
                        <td><?php echo $particular->name;?></td>
                        <td><?php echo ($particular->description!=NULL)?$particular->description:'-';?></td>
                        <td align="center"><?php echo number_format($particular->amount, 2);?></td>
                        <?php if($feeconfig==NULL or $feeconfig->discount_in_fee==1){?>
                        <td align="center">
                            <?php
                                if($particular->discount_type==1)
                                    echo $particular->discount_value." %";
                                else if($particular->discount_type==2)
                                    echo number_format($particular->discount_value, 2).(($configuration!=NULL)?" ".$configuration->config_value:'');
                                else
                                    echo "-";
                            ?>
                        </td>
                        <?php }?>
                        <?php if($feeconfig==NULL or $feeconfig->tax_in_fee==1){?>
                        <td align="center">
                            <?php 
                                $tax    = FeeTaxes::model()->findByPk($particular->tax);
                                if($tax!=NULL)
                                    echo $tax->value." %";
                                else
                                    echo "-";
                            ?>
                        </td>
                        <?php }?>
                        <td align="center">
                            <?php
                                $sub_total  += $particular->amount;
                                $amount = $particular->amount;
								if($feeconfig->discount_in_fee==1){
									//apply discount
									if($particular->discount_type==1){  //percentage
										$idiscount  = (($particular->amount * $particular->discount_value)/100);
										$amount     = $amount - $idiscount;
										$discount_total += $idiscount;
									}
									else if($particular->discount_type==2){ //amount
										$amount = $amount - $particular->discount_value;
										$discount_total += $particular->discount_value;
									}
                                }
                                                        
								if($feeconfig->tax_in_fee==1){
									//apply tax
									if($particular->tax!=0){
										$tax    = FeeTaxes::model()->findByPk($particular->tax);
										if($tax!=NULL){
											$itax   = (($amount * $tax->value)/100);
											$amount = $amount + $itax;
											$tax_total  += $itax;
										}
									}
								}
                                
                                echo number_format($amount, 2);
                            ?>
                        </td>
                    </tr>
                    <?php
                        $amount_total	+= $amount;
                    }
                    ?>
                    <?php if($feeconfig==NULL or ($feeconfig->discount_in_fee==1 or $feeconfig->tax_in_fee==1)){?>
                    <tr>
                        <td colspan="<?php echo $colspan;?>" align="right" style="padding-right:10px;"><strong><?php echo Yii::t('app','Sub Total').(($configuration!=NULL)?" (".$configuration->config_value.")":''); ?></strong></td>
                        <td align="center"><?php echo number_format($sub_total, 2);?></td>
                    </tr>
                    <?php }?>
                    <?php if($feeconfig==NULL or $feeconfig->discount_in_fee==1){ ?>
                    <tr>
                        <td colspan="<?php echo $colspan;?>" align="right" style="padding-right:10px;"><strong><?php echo Yii::t('app','Discount').(($configuration!=NULL)?" (".$configuration->config_value.")":''); ?></strong></td>
                        <td align="center"><?php echo ($discount_total!=0)?number_format($discount_total, 2):"-";?></td>
                    </tr>
                    <?php } ?>
                    <?php if($feeconfig==NULL or $feeconfig->tax_in_fee==1){ ?>
                    <tr>
                        <td colspan="<?php echo $colspan;?>" align="right" style="padding-right:10px;"><strong><?php echo Yii::t('app','Tax').(($configuration!=NULL)?" (".$configuration->config_value.")":''); ?></strong></td>
                        <td align="center"><?php echo ($tax_total!=0)?number_format($tax_total, 2):"-";?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="<?php echo $colspan;?>" align="right" style="padding-right:10px;"><strong><?php echo Yii::t('app','Total').(($configuration!=NULL)?" (".$configuration->config_value.")":''); ?></strong></td>
                        <td align="center"><?php echo number_format($amount_total, 2);?></td>
                    </tr>                                       
                </tbody>
            </table>
        </div>        
	</div>
</div>


<div class="contentpanel"> 
    <div class="panel-heading">    
        <h3 class="panel-title"><?php echo Yii::t('app','Transactions');?></h3>        
    </div> 
    <div class="people-item">  
        <div class="table-responsive">
            <table class="table table-hover">
                <tbody>
                    <tr class="pdtab-h">
                        <td align="center">#</td>
                        <td height="18"><?php echo Yii::t('app','Date'); ?> *</td>                                            
                        <td align="center"><?php echo Yii::t('app','Type'); ?></td>
                        <td align="center"><?php echo Yii::t('app','Transaction ID'); ?></td>
                        <td align="center"><?php echo Yii::t('app','Description'); ?></td>                                            
                        <td align="center"><?php echo Yii::t('app','Amount'); ?> *</td>
                        <td align="center"><?php echo Yii::t('app','Proof'); ?></td>
                        <td align="center"><?php echo Yii::t('app','Status'); ?></td>
                    </tr>
                    
                    <?php
                    foreach($alltransactions as $index=>$ctransaction){
                    ?>
                    <tr <?php if($ctransaction->is_deleted==1 and $ctransaction->deleted_by!=NULL){?>style="text-decoration:line-through;" title="Removed by `<?php echo $ctransaction->deletedUser;?>`" <?php } ?>>
                        <td align="center"><?php echo $index + 1;?></td>
                        <td height="18">
                            <?php
                                if($settings!=NULL)
                                    echo date($settings->displaydate, strtotime($ctransaction->date));
                                else
                                    echo $ctransaction->date;
                            ?>
                        </td>                                            
                        <td align="center"><?php echo $ctransaction->transactionType;?></td>
                        <td align="center"><?php echo (($ctransaction->transaction_id!=NULL)?$ctransaction->transaction_id:"-");?></td>
                        <td align="center"><?php echo (($ctransaction->description!=NULL)?$ctransaction->description:"-");?></td>
                        <td align="center"><?php echo number_format($ctransaction->amount, 2);?></td>
                        <td align="center"><?php echo ($ctransaction->proof!=NULL)?Yii::t("app", "Yes"):Yii::t("app", "No");?></td>
                        <td align="center">
                            <?php
                                if($ctransaction->status==0){
                                    echo Yii::t('app', 'Pending');
                                }
                                else if($ctransaction->status==1){
                                    echo Yii::t('app', 'Completed');
                                }
                                else if($ctransaction->status==-1){
                                    echo Yii::t('app', 'Failed');
                                }
                                else{
                                    echo '-';
                                }
                            ?>
                        </td>
                    </tr>
                    <?php
                    }
					
					if(count($alltransactions)==0){
						?>
						<tr>
							<td align="center" colspan='8'><?php echo Yii::t('app', 'No transactions found');?></td>
						</tr>
						<?php
					}
                    ?>                    
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
	if($invoice->is_canceled==0 and FeeInvoices::model()->getAmountPayable($invoice->id)>0)
    	$this->renderPartial("application.modules.fees.views.gateways._index", array("invoice"=>$invoice, 'gateway'=>$gateway, 'amount_payable'=>$amount_payable));
?>

<?php
if($gateway!=NULL and $gateway->hasErrors()){
?>
<script type="text/javascript">
    $('html').animate({
        scrollTop: $("#payment-area").offset().top
    }, 'slow');
</script>
<?php
}
?>