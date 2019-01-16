<style>
.invoice-table td {
	padding-left: 10px !important;
}
.attendance_table table {
	border-collapse: collapse;
}
.attendance_table table tr td, th {
	border: 1px  solid #C5CED9;
	padding: 8px 9px;
	font-size: 13px;
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

.invoice_table table tr td{ padding:5px;}
</style>
<?php
    $configuration  = Configurations::model()->findByPk(5);

    $invoice_amount = 0;
    foreach($particulars as $key=>$particular){
        $amount = $particular->amount;
        //apply discount
        if($particular->discount_type==1){  //percentage
            $idiscount  = (($particular->amount * $particular->discount_value)/100);
            $amount     = $amount - $idiscount;
        }
        else if($particular->discount_type==2){ //amount
            $amount = $amount - $particular->discount_value;
        }
        
        //apply tax
        if($particular->tax!=0){
            $tax    = FeeTaxes::model()->findByPk($particular->tax);
            if($tax!=NULL){
                $itax   = (($amount * $tax->value)/100);
                $amount = $amount + $itax;
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


<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
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
    <?php } ?>
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
    <td><?php echo Yii::t("app", " last payment date");?></td>
    <td>:</td>
        <td>
			 <?php
				$criteria					= new CDbCriteria();
				$criteria->condition		= 'invoice_id=:id AND status=1';
				$criteria->params[':id'] 	= $invoice->id;
				$criteria->order = "id DESC";
				$criteria->limit = 1;
				$exemple = FeeTransactions::model()->findAll($criteria);
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
			?>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <div style="font-size:26px; padding:20px 0;">
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



<div class="attendance_table">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody>
            <tr class="pdtab-h" bgcolor="#F0F1F3">
                <td width="30">#</td>
                <td width="100"><?php echo Yii::t('app','Particular'); ?></td>
                <td><?php echo Yii::t('app','Description'); ?></td>
                <td><?php echo Yii::t('app','Unit Price'); ?></td>
                <td><?php echo Yii::t('app','Discount'); ?></td>
                <td><?php echo Yii::t('app','Tax'); ?></td>                                            
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
                <td width="100" ><?php echo number_format($particular->amount, 2);?></td>
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
                <td align="center">
                    <?php 
                        $tax    = FeeTaxes::model()->findByPk($particular->tax);
                        if($tax!=NULL)
                            echo $tax->value." %";
                        else
                            echo "-";
                    ?>
                </td>                                                                                        
                <td align="center">
                    <?php
                        $sub_total  += $particular->amount;
                        $amount = $particular->amount;
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
                        
                        //apply tax
                        if($particular->tax!=0){
                            $tax    = FeeTaxes::model()->findByPk($particular->tax);
                            if($tax!=NULL){
                                $itax   = (($amount * $tax->value)/100);
                                $amount = $amount + $itax;
                                $tax_total  += $itax;
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
            <tr>
                <td colspan="6" align="right" style="padding-right:10px;"><?php echo Yii::t('app','Sub Total').(($configuration!=NULL)?" (".$configuration->config_value.")":''); ?></td>
                <td align="center"><?php echo number_format($sub_total, 2);?></td>
            </tr>
            <tr>
                <td colspan="6" align="right" style="padding-right:10px;"><?php echo Yii::t('app','Discount').(($configuration!=NULL)?" (".$configuration->config_value.")":''); ?></td>
                <td align="center"><?php echo ($discount_total!=0)?number_format($discount_total, 2):"-";?></td>
            </tr>
            <tr>
                <td colspan="6" align="right" style="padding-right:10px;"><?php echo Yii::t('app','Tax').(($configuration!=NULL)?" (".$configuration->config_value.")":''); ?></td>
                <td align="center"><?php echo ($tax_total!=0)?number_format($tax_total, 2):"-";?></td>
            </tr>
            <tr>
                <td colspan="6" align="right" style="padding-right:10px;"><?php echo Yii::t('app','Total').(($configuration!=NULL)?" (".$configuration->config_value.")":''); ?></td>
                <td align="center"><?php echo number_format($amount_total, 2);?></td>
            </tr>
            
        </tbody>
    </table>
    
</div>
<div class="clear"></div>
<br />
<br />
<br />
<div class="attendance_table"> 
	 <h1><?php echo Yii::t("app", "Transactions");?></h1>                      
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
               
            </tr>
            
            <?php
            foreach($alltransactions as $index=>$ctransaction){
                $this->renderPartial('application.modules.fees.views.transactions._transaction', array('transaction'=>$ctransaction, 'settings'=>$settings, 'count'=>$index + 1));											
            }

           ?>
        </tbody>
    </table>
  
</div>
