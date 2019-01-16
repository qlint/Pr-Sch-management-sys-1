<style>

.error-brd{
	border-color:#F30 !important;
}

.invoice-table input[type="text"]{
    background: #fff none repeat scroll 0 0;
    border: 1px solid #c2cfd8;
    border-radius: 0 !important;
    box-shadow: none !important;
    margin: 0 2px;
    padding: 7px 3px;
}
.pdtab_Con table td{ padding:5px;}
</style>


<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js_plugins/flupload/js/jupload.js"></script>
<?php
	$this->breadcrumbs=array(
		Yii::t('app','Fees')=>array('/fees'),
	);
    
	$settings       = UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
	$configuration	= Configurations::model()->findByPk(5);
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">    
            <?php $this->renderPartial('/default/left_side');?>    
        </td>
        <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top" width="75%">
                        <div class="cont_right formWrapper">
                            <h1><?php echo Yii::t('app','Invoice')." - #".$invoice->id;; ?></h1>            
                                <div class="pdf-box">
                                <div class="box-one">
                                <div class="bttns_addstudent-n">
                                <ul>
                                <li></li>
                                <li></li>
                                </ul>
                                </div>
                                </div>
                                <div class="box-two">
                                <div class="pdf-div">
                                <?php echo CHtml::link(Yii::t("app", "Generate PDF"), array("/fees/invoices/print", "id"=>$invoice->id), array('class'=>"pdf_but", 'target'=>"_blank"));?>
                                &nbsp;
                                <?php echo CHtml::link('Generate RTF', array('/fees/invoices/rtf','id'=>$invoice->id),array('class'=>'expertcv')); ?>
                                </div>
                                </div>
                                </div>
                       
                            <div style="width:100%; padding-top:0px;" class="pdtab_Con" >
                                <div class="invoice-table">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td width="25%"><strong><?php echo Yii::t("app", "Invoice ID");?></strong></td>
                                            <td> <?php echo $invoice->id;?></td>
                                        </tr>
                                        <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                        <tr>
                                            <td><strong><?php echo Yii::t("app", "Recipient");?></strong></td>
                                            <td>
                                                <?php
                                                $display_name   = "-";
                                                if($invoice->table_id!=NULL and $invoice->table_id!=0){
                                                    if($invoice->user_type==1){ //student
                                                        $student        = Students::model()->findByPk($invoice->table_id);
                                                        if($student!=NULL)
                                                            $display_name   = $student->studentFullName("forStudentProfile");
                                                    }
                                                }
                                                //display name
                                                if($invoice->table_id!=NULL and $invoice->table_id!=0){
                                                    if($invoice->user_type==1)  //student
                                                        echo CHtml::link($display_name, array('/students/students/view', 'id'=>$invoice->table_id));
                                                    else
                                                        echo $display_name;
                                                }       
                                                else{
                                                    echo $display_name;
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php } 
										$feecat	=	FeeCategories::model()->findByPk($invoice->fee_id);
										?>
                                        <tr>
                                            <td><strong><?php echo Yii::t("app", "Fee Category");?></strong></td>
                                            <td>
                                                <?php echo $feecat->name;?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong><?php echo Yii::t("app", "Invoice Date");?></strong></td>
                                            <td>
                                                <?php 
												 	if($settings!=NULL)
                                                       $displaydate	=	$settings->displaydate;
                                                    else
                                                       $displaydate	=	date('d M Y');
													echo date($displaydate, strtotime($invoice->created_at));
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
                                            <td><strong><?php echo Yii::t("app", "Due Date");?></strong></td>
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
                                            <td><strong><?php echo Yii::t("app", " Last payment date");?></strong></td>
                                            <td>
                                                <?php
												    $criteria					= new CDbCriteria();
													$criteria->condition		= 'invoice_id=:id AND status=1';
													$criteria->params[':id'] 	= $invoice->id;
													$criteria->order = "id DESC";
													$criteria->limit = 1;
													$exemple = FeeTransactions::model()->findAll($criteria);
													if($exemple[0]['is_deleted']==0)
													{
                                                    if($exemple != NULL)
													   {
														 if($settings!=NULL)
														 {
                                                      	 echo date($settings->displaydate, strtotime($exemple[0]['date']));
														 }
													   }
                                                    else
													{
													   echo '-';
													}
												  }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bold; font-size:12px;"><strong><?php echo Yii::t("app", "Status");?></strong> </td>
                                            <td style="font-size:20px; font-weight:bold;">
                                                <?php
                                                    if($invoice->is_canceled==1)
                                                        echo Yii::t("app","Cancelled");
                                                    else
                                                        echo ($invoice->is_paid==1)?"<span style='color:#090'>".Yii::t("app","Paid")."</span>":"<span style='color:#F00'>".Yii::t("app","Unpaid")."</span>";
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                               	</div>
								<br />
                                <?php $form=$this->beginWidget('CActiveForm', array(
                                    'id'=>'fee-invoice-particulars-form',
                                    'enableAjaxValidation'=>false,
                                ));
								$colspan=4;
								?>
								<div class="invoice-table">                                    
                                    <?php echo CHtml::hiddenField('invoice_id', $invoice->id, array('id'=>'invoice_id')); ?>
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0" id="invoice-particualrs">
                                        <tbody>
                                            <tr class="pdtab-h">
                                                <td height="18" align="center">#</td>
                                                <td align="center"><?php echo Yii::t('app','Particulars'); ?></td>
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
                                            
                                            $sub_total		= 0;
                                            $discount_total	= 0;
                                            $tax_total		= 0;
                                            foreach($particulars as $key=>$particular){
												
                                            ?>
                                            <tr class='invoice-particular-box'>
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
                                                        $tax	= FeeTaxes::model()->findByPk($particular->tax);
                                                        if($tax!=NULL)
                                                            echo $tax->value." %";
                                                        else
                                                            echo "-";
                                                    ?>
                                                </td>
                                                <?php }?>                                                                                    
                                                <td align="center">
                                                    <?php
                                                        $sub_total	+= $particular->amount;
                                                        $amount	= $particular->amount;
														if($feeconfig->discount_in_fee==1){
															//apply discount
															if($particular->discount_type==1){	//percentage
																$idiscount	= (($particular->amount * $particular->discount_value)/100);
																$amount		= $amount - $idiscount;
																$discount_total	+= $idiscount;
															}
															else if($particular->discount_type==2){	//amount
																$amount	= $amount - $particular->discount_value;
																$discount_total	+= $particular->discount_value;
															}
														}
                                                        
														if($feeconfig->tax_in_fee==1){
															//apply tax
															if($particular->tax!=0){
																$tax	= FeeTaxes::model()->findByPk($particular->tax);
																if($tax!=NULL){
																	$itax	= (($amount * $tax->value)/100);
																	$amount	= $amount + $itax;
																	$tax_total	+= $itax;
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
                                <br />
                                <?php
                                if($invoice->is_canceled==0){
								?>
                                <a href="javascript:void(0);" class='formbut-n edit-particulars' style='height:15px; float:right;'><?php echo Yii::t("app", "Edit");?></a>
                                <?php echo CHtml::submitButton(Yii::t("app", "Save"), array('name'=>'save-particulars', 'class'=>'formbut save-particulars', 'style'=>'float:right; display:none;'));?>
                                <?php
								}
								?>
                                <?php $this->endWidget(); ?>

                                <div class="clear"></div>
                                <br />
                                <br />
								<br />
                                <h1><?php echo Yii::t("app", "Transactions");?></h1>
                                <?php if(count($alltransactions)!=0 and $alltransactions!=NULL)
								{?>

                                <div class="pdf-box">
                                <div class="box-one">
                                <div class="bttns_addstudent-n">
                                <ul>
                                <li></li>
                                <li></li>
                                </ul>
                                </div>
                                </div>
                                <div class="box-two">
                                <div class="pdf-div">
                                <?php echo CHtml::link(Yii::t("app", "Generate PDF "), array("/fees/invoices/transactionspdf", "id"=>$invoice->id), array('class'=>"pdf_but", 'target'=>"_blank"));?>
                                </div>
                                </div>
                                </div>
                                
                                 
                                <?php
								}?>
                                <div class="invoice-table">                       
                                	<?php $form=$this->beginWidget('CActiveForm', array(
										'id'=>'fee-transactions-form',
										'enableAjaxValidation'=>false,
									)); ?>
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
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
                                                <td align="center"><?php echo Yii::t('app','Actions'); ?></td>
                                            </tr>
                                            
                                            <?php
                                            foreach($alltransactions as $index=>$ctransaction){
                                                $this->renderPartial('application.modules.fees.views.transactions._transaction', array('transaction'=>$ctransaction, 'settings'=>$settings, 'count'=>$index + 1));											
											}

                                            if($invoice->is_canceled==0 and FeeInvoices::model()->getAmountPayable($_REQUEST['id'])!=0)                                                
                                                $this->renderPartial("application.modules.fees.views.transactions._new", array("alltransactions"=>$alltransactions, "transaction"=>$transaction, 'form'=>$form));
                                            else if(count($alltransactions)==0){
                                                ?>
                                                <tr>
                                                    <td align="center" colspan='9'><?php echo Yii::t('app', 'No transactions found');?></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                   	</table>
                                    <?php $this->endWidget(); ?>
                                </div>
                            </div>                            
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<script>
$("#FeeTransactions_proof").jupload({
    filename:"FeeTransactions[proof]"
});
$("form#fee-transactions-form").submit(function(){
	var that	= this;
	var datas	= new FormData($(that)[0]);
	$.ajax({
		url:"<?php echo Yii::app()->createUrl("/fees/transactions/add");?>",
		type:'POST',
        processData: false,
        contentType: false,
		data:datas,
		dataType:"json",
		success:function(response){            
			$(that).find("input, select, .input").attr('title', '');
			$(that).find("*").removeClass("error-brd");
			if(response.status=="success"){				
				if(response.hasOwnProperty("row")){
					var row	= response.row;
					$(row).insertBefore($(that).find("tr").last());
                    $(that)[0].reset();
                    $("#new-count").text(parseInt($("#new-count").text()) + 1);
				}
				window.location.reload();
			}
			else{
				if(response.hasOwnProperty("errors")){
					var errors	= response.errors;
					$.each(errors, function(attribute, error){
						$('#' + attribute).attr('title', error).addClass("error-brd");
					});
				}
			}
		},
        error:function(){
            alert("<?php echo Yii::t("app", "Some problem found while adding transaction")?>");
        }		
	});
	
	return false;
});

//remove transaction
$(".remove-transaction").click(function(){
    if(confirm("<?php echo Yii::t("app","Are you sure remove this transaction ?");?>")){
        var that    = this;
        var id      = $(that).attr("data-transaction-id");

        $.ajax({
            url:"<?php echo Yii::app()->createUrl("/fees/transactions/remove");?>",
            type:"POST",
            data:{id:id, "<?php echo Yii::app()->request->csrfTokenName;?>":"<?php echo Yii::app()->request->csrfToken;?>"},
            dataType:"json",
            success:function(response){
                if(response.status=="success"){
                    window.location.reload();
                }
                else{
                    alert("<?php echo Yii::t("app", "Some problem found while removing transaction")?>");
                }
            },
            error:function(){
                alert("<?php echo Yii::t("app", "Some problem found while removing transaction")?>");
            }
        });
    }    
});

//edit particulars
$('.edit-particulars').click(function(){
    $.ajax({
        url:"<?php echo Yii::app()->createUrl('/fees/invoices/edit');?>",
        type:'POST',
        data:{invoice_id:$("#invoice_id").val(), "<?php echo Yii::app()->request->csrfTokenName;?>":"<?php echo Yii::app()->request->csrfToken;?>"},
        dataType:'json',
        success:function(response){
            if(response.status=="success"){
                $('.invoice-particular-box').remove();
                $('.edit-particulars').hide();
                $('.save-particulars').show();
                $(response.data).insertAfter($("#invoice-particualrs").find("tr").first());
            }
            else{
                alert("<?php echo Yii::t("app", "Some problem found while saving the particulars")?>");
            }
        },
        error:function(){
            alert("<?php echo Yii::t("app", "Some problem found while saving the particulars")?>");
        }
    });
});

//save particulars
$("#fee-invoice-particulars-form").submit(function(){
    var that    = this;
    var datas   = $(that).serialize();
    $.ajax({
        url:"<?php echo Yii::app()->createUrl('/fees/invoices/edit');?>",
        type:'POST',
        data:datas,
        dataType:'json',
        success:function(response){
            if(response.status=="success"){
                window.location.reload();
            }
            else if(response.hasOwnProperty("errors")){
                var errors  = response.errors;
                $.each(errors, function(attribute, earray){
                    $.each(earray, function(index, error){
                        $('#' + attribute).attr('title', error).addClass("error-brd");
                    });                                     
                });             
            }
            else{
                alert("<?php echo Yii::t("app", "Some problem found while saving the particulars")?>");      
            }
        },
        error:function(){
            alert("<?php echo Yii::t("app", "Some problem found while saving the particulars")?>");
        }
    });

    return false;    
});
</script>