<style>
.invoice-table td {
	padding-left: 10px !important;
}
./*attendance_table table {
	border-collapse: collapse;
}
.attendance_table table tr td, th {
	border: 1px  solid #C5CED9;
	padding: 8px 9px;
	font-size: 13px;
}*/
table.attendance_table{ border-collapse:collapse}
.attendance_table{
	margin:5px 0px;
	font-size:8px;
	text-align:center;
	border-collapse:collapse;
}
.attendance_table td{
	border:1px solid #CCC;
	padding:8px;
	width:auto;
	font-size:12px;
	
}
.pdtab-h th {
	background: #F0F1F3;
	padding: 15px 5px;
	font-size: 16px;
	font-weight: 600;
	text-align: left;
	line-height: 25px;
}
.invoice_top table tr td {
	font-size: 18px;
	padding: 5px 0;
	font-weight: 600;
}
hr {
	border-bottom: 1px solid #ccc;
	border-top: 0px solid
}
.tranferdetails{
	 border-collapse:collapse;

}
.tranferdetails td{
	border-collapse:collapse;
	padding:5px;
	border-bottom:1px solid #CCC;
	font-size:12px;
}

.invoice_table table tr td{ padding:5px;}
</style>
<?php
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
<table width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td class="first" width="100">
            <?php
            $filename=  Logo::model()->getLogo();
            if($filename!=NULL)
            { 
                echo '<img src="uploadedfiles/school_logo/'.$filename[2].'" alt="'.$filename[2].'" class="imgbrder" height="100" />';
            }
            ?>
        </td>
        <td valign="middle">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="listbxtop_hdng first" style="text-align:left; font-size:22px; padding-left:10px;">
                        <?php $college=Configurations::model()->findAll();?>
                        <?php echo @$college[0]->config_value; ?>
                    </td>
                </tr>
                <tr>
                    <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                        <?php echo @$college[1]->config_value; ?>
                    </td>
                </tr>
                <tr>
                    <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                        <?php echo Yii::t('app','Phone').': '.@$college[2]->config_value; ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<hr />
<br />
<div align="center" style="display:none; text-align:center !important; font-size:25px; font-weight:600;"><?php echo Yii::t('app','INVOICE'); ?></div>
<br />


<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tranferdetails "> 
    <tr>
        <td width="100" height="25"><?php echo Yii::t("app", "Invoice ID");?> </td>
        <td width="20">:</td>
        <td width="300"><?php echo $invoice->id;?></td>
    </tr>                
    <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>              
    <tr>
        <td height="25"><?php echo Yii::t("app", "Recipient");?> </td>
        <td>:</td>
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
                echo $display_name;
            ?>
        </td>
    </tr>
    <?php } 
	
	$feecat	=	FeeCategories::model()->findByPk($invoice->fee_id);
	?>
	<tr>
		<td height="25"><?php echo Yii::t("app", "Fee Category");?></td>
        <td>:</td>
		<td>
			<?php echo $feecat->name;?>
		</td>
	</tr>
    <tr>
        <td height="25"><?php echo Yii::t("app", "Invoice Date");?> </td>
        <td>:</td>
        <td>
            <?php
                $settings	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                if($settings!=NULL)
                    echo date($settings->displaydate, strtotime($invoice->created_at));
                else
                    echo $invoice->created_at;
            ?>
        </td>
    </tr>
    <tr>
        <td height="25"><?php echo Yii::t("app", "Invoice Amount");?></td>
        <td>:</td>
        <td><?php echo number_format($invoice_amount, 2);?></td>
    </tr>
    <tr>
        <td height="25"><?php echo Yii::t("app", "Adjustments");?></td>
        <td>:</td>
        <td><?php echo number_format($adjustments, 2);?></td>
    </tr>
    <tr>
        <td height="25"><?php echo Yii::t("app", "Payment Details");?></td>
        <td>:</td>
        <td><?php echo number_format($payments, 2);?></td>
    </tr>
    <tr>
        <td height="25"><?php echo Yii::t("app", "Amount Payable");?></td>
        <td>:</td>
        <td><?php echo number_format($amount_payable, 2);?></td>
    </tr>
    <tr>
        <td height="25"><?php echo Yii::t("app", "Due Date");?> </td>
        <td>:</td>
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
    <tr>
    <td><?php echo Yii::t("app", " Last payment date");?></td>
    <td>:</td>
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
        <td colspan="3">
            <div style="font-size:18px; padding:20px 0;">
                <?php echo Yii::t("app", "Status");?> : 
                <?php 
                    if($invoice->is_canceled==1)
                        echo "<span style='color:#000;'>".Yii::t("app","Canceled")."</span>";
                    else
                        echo ($invoice->is_paid==1)?"<span style='color:#090;'>".Yii::t("app","Paid")."</span>":"<span style='color:#F00'>".Yii::t("app","Unpaid")."</span>";
                ?>
            </div>
        </td>
    </tr>
</table>
<?php $colspan	= 3;?>
<div class="attendance_table">
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="attendance_table">
        <tbody>
            <tr class="pdtab-h" bgcolor="#F0F1F3">
                <td width="30">#</td>
                <td width="100"><?php echo Yii::t('app','Particular'); ?></td>
                <td><?php echo Yii::t('app','Description'); ?></td>
                <?php if($feeconfig==NULL or $feeconfig->discount_in_invoice==1 or $feeconfig->tax_in_fee==1){$colspan++;?>
                <td><?php echo Yii::t('app','Unit Price'); ?></td>
                <?php }?>
                <?php if($feeconfig==NULL or $feeconfig->discount_in_invoice==1){$colspan++;?>
                <td><?php echo Yii::t('app','Discount'); ?></td>
                <?php }?>
                <?php if($feeconfig==NULL or $feeconfig->tax_in_fee==1){$colspan++;?>
                <td><?php echo Yii::t('app','Tax'); ?></td>                          
                <?php }?>                  
                <td><?php echo Yii::t('app','Amount'); ?></td>
            </tr>
            <?php
            $amount_total  = 0;
            $fine_total     = 0;

            $sub_total      = 0;
            $discount_total = 0;
            $tax_total      = 0;
            foreach($particulars as $key=>$particular){
            ?>
            <tr>
                <td width="30" ><?php echo $key+1;?></td>
                <td width="100"><?php echo $particular->name;?></td>
                <td width="105"><?php echo ($particular->description!=NULL)?$particular->description:'-';?></td>
                <?php if($feeconfig==NULL or $feeconfig->discount_in_invoice==1 or $feeconfig->tax_in_fee==1){?>
                <td width="100" ><?php echo number_format($particular->amount, 2);?></td>
                <?php }?>
               	<?php if($feeconfig==NULL or $feeconfig->discount_in_invoice==1){?>
                <td>
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
            <?php if($feeconfig==NULL or ($feeconfig->discount_in_invoice==1 or $feeconfig->tax_in_fee==1)){?>
            <tr>
                <td colspan="<?php echo $colspan;?>" align="right" style="padding-right:10px;"><?php echo Yii::t('app','Sub Total').(($configuration!=NULL)?" (".$configuration->config_value.")":''); ?></td>
                <td align="center"><?php echo number_format($sub_total, 2);?></td>
            </tr>
            <?php }?>
            <?php if($feeconfig==NULL or $feeconfig->discount_in_invoice==1){ ?>
            <tr>
                <td colspan="<?php echo $colspan;?>" align="right" style="padding-right:10px;"><?php echo Yii::t('app','Discount').(($configuration!=NULL)?" (".$configuration->config_value.")":''); ?></td>
                <td align="center"><?php echo ($discount_total!=0)?number_format($discount_total, 2):"-";?></td>
            </tr>
            <?php } ?>
            <?php if($feeconfig==NULL or $feeconfig->tax_in_fee==1){ ?>
            <tr>
                <td colspan="<?php echo $colspan;?>" align="right" style="padding-right:10px;"><?php echo Yii::t('app','Tax').(($configuration!=NULL)?" (".$configuration->config_value.")":''); ?></td>
                <td align="center"><?php echo ($tax_total!=0)?number_format($tax_total, 2):"-";?></td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="<?php echo $colspan;?>" align="right" style="padding-right:10px;"><?php echo Yii::t('app','Total').(($configuration!=NULL)?" (".$configuration->config_value.")":''); ?></td>
                <td align="center"><?php echo number_format($amount_total, 2);?></td>
            </tr>
            
        </tbody>
    </table>
    
</div>
<div class="clear"></div>
<?php
	$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
	if($settings!=NULL and $settings->displaydate!=NULL){
		$dateformat	= $settings->displaydate;
	}
	else
		$dateformat = 'd M Y';
?>
 <div style="display:block;"> <?php  echo Yii::t('app','Transactions');   ?> </div>
<div class="tablebx">
    <table width="100%" cellspacing="0" cellpadding="0" class="attendance_table">
        <tr class="tablebx_topbg" style="background-color:#DCE6F1;">
            <td width="8%"><?php echo Yii::t('app','Sl No');?></td>
            <td width="15%"><?php echo Yii::t('app','Date'); ?></td>                                            
            <td width="10%"><?php echo Yii::t('app','Type'); ?></td>
            <td width="15%"><?php echo Yii::t('app','Transaction ID'); ?></td>
            <td width="20%"><?php echo Yii::t('app','Description'); ?></td>                                            
            <td width="10%"><?php echo Yii::t('app','Amount'); ?></td>
            <td width="12%"><?php echo Yii::t('app','Proof'); ?></td>
            <td width="10%"><?php echo Yii::t('app','Status'); ?></td>
        </tr>
        
        <?php
		$i=1;
		 foreach($alltransactions as $index=>$transaction){
			if($transaction->is_deleted==1)
			{
				?>
                <tr>
                <td><?php echo "<strike>".$i++."</strike>";?></td>
                <td><?php echo "<strike>".date($dateformat,strtotime($transaction->date))."</strike>";?></td>
                <td><?php 
                    $fee_type=FeePaymentTypes::model()->findByPk($transaction->payment_type);
                    if($fee_type->type!=NULL)
                        echo "<strike>".$fee_type->type."</strike>";
                    else
                        echo "-";?></td>
                 <td><?php echo "<strike>".(($transaction->transaction_id !=NULL) ?$transaction->transaction_id:'-')."</strike>";;?></td>
                 <td><?php echo "<strike>".(($transaction->description !=NULL)?$transaction->description:"-")."</strike>";;?></td>
                 <td><?php echo "<strike>".(($transaction->amount !=NULL)?$transaction->amount:"-")."</strike>";;?></td>
                 <td><?php echo "<strike>".(($transaction->proof !=NULL)?Yii::t('app','Yes'):Yii::t('app','No'))."</strike>";?></td>
                 <td><?php 
                    if($transaction->status	==	'-1')
                        echo "<strike>".Yii::t('app','Error')."</strike>";
                    else if($transaction->status	==	'0')
                        echo "<strike>". Yii::t('app','Pending')."</strike>";
                    else if($transaction->status	==	'1')
                        echo "<strike>".Yii::t('app','completed')."</strike>";
                        ?></td>
            </tr>
                <?php
			}else
			{
			 ?>
            <tr>
                <td><?php echo $i++;?></td>
                <td><?php echo date($dateformat,strtotime($transaction->date));?></td>
                <td><?php 
                    $fee_type=FeePaymentTypes::model()->findByPk($transaction->payment_type);
                    if($fee_type->type!=NULL)
                        echo $fee_type->type;
                    else
                        echo "-";?></td>
                 <td><?php echo(($transaction->transaction_id !=NULL) ?$transaction->transaction_id:'-');?></td>
                 <td><?php echo (($transaction->description !=NULL)?$transaction->description:"-");?></td>
                 <td><?php echo (($transaction->amount !=NULL)?$transaction->amount:"-");?></td>
                 <td><?php echo (($transaction->proof !=NULL)?Yii::t('app','Yes'):Yii::t('app','No'));?></td>
                 <td><?php 
                    if($transaction->status	==	'-1')
                        echo Yii::t('app','Error');
                    else if($transaction->status	==	'0')
                        echo Yii::t('app','Pending');
                    else if($transaction->status	==	'1')
                        echo Yii::t('app','completed');
                        ?></td>
            </tr>
          <?php
			}
		 }
          if(count($alltransactions)==0){
            ?>
            <tr>
                <td align="center" colspan='9'><?php echo Yii::t('app', 'No transactions found');?></td>
            </tr>
            <?php
     	   }
        ?>
    </tbody>
</table>
  
</div>
