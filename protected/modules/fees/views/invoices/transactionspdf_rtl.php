<style>
table.attendance_table{ border-collapse:collapse}

.attendance_table{
	margin:30px 0px;
	font-size:8px;
	text-align:center;
	width:auto;
	/*max-width:600px;*/
	border-top:1px #CCC solid;
	border-right:1px solid #CCC;
}
.attendance_table td{
	border:1px solid #CCC;
	padding:8px;
	width:auto;
	font-size:12px;
	
}
hr{ border-bottom:1px solid #C5CED9; border-top:0px solid #fff;}
.tranferdetails{
	 border-collapse:collapse;

}
.tranferdetails td{
	border-collapse:collapse;
	padding:5px;
	border-bottom:1px solid #CCC;
	font-size:12px;
}
</style>
	<!-- Header -->
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="first" width="100">
                           <?php 
						   $filename=  Logo::model()->getLogo();
                            if($filename!=NULL)
                            { 
                                //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                echo '<img src="uploadedfiles/school_logo/'.$filename[2].'" alt="'.$filename[2].'" class="imgbrder" height="100" />';
                            }
                            ?>
                </td>
                <td valign="middle">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:22px; width:300px;  padding-left:10px;">
                                <?php $college=Configurations::model()->findAll(); ?>
                                <?php echo $college[0]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo $college[1]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo Yii::t('app','Phone: ').$college[2]->config_value; ?>
                            </td>
                        </tr>
                    </table>
                </td>
          </tr>
        </table>
   <hr />
<?php
$id				=	$_GET['id'];
$invoice	= FeeInvoices::model()->findByPk($id);
$criteria		= new CDbCriteria;
$criteria->compare("invoice_id", $id);
$particulars	= FeeInvoiceParticulars::model()->findAll($criteria);

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
<div align="center" style="text-align:center; display:block;"> <?php  echo Yii::t('app','TRANSACTIONS DETAILS');   ?> </div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tranferdetails"> 
    <tr>
        <td width="10%"><?php echo Yii::t("app", "Invoice ID");?> </td>
        <td width="3%">:</td>
        <td width="77%"><?php echo $invoice->id;?></td>
    </tr>                
    <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>              
    <tr>
       <td width="20%"><?php echo Yii::t("app", "Recipient");?> </td>
        <td width="3%">:</td>
        <td width="77%">
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
        <td  width="20%"><?php echo Yii::t("app", "Invoice Date");?> </td>
        <td  width="3%">:</td>
        <td  width="77%">
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
        <td width="20%"><?php echo Yii::t("app", "Invoice Amount");?></td>
        <td width="3%">:</td>
        <td width="77%"><?php echo number_format($invoice_amount, 2);?></td>
    </tr>
    <tr>
        <td width="20%"><?php echo Yii::t("app", "Amount Payable");?></td>
        <td width="3%">:</td>
        <td width="77%"><?php echo number_format($amount_payable, 2);?></td>
    </tr>
</table>
<?php
//take value
$id				=	$_GET['id'];
$criteria		= new CDbCriteria;
$criteria->compare('invoice_id', $id);
$alltransactions	= FeeTransactions::model()->findAll($criteria);

$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL and $settings->displaydate!=NULL){
	$dateformat	= $settings->displaydate;
}
else
	$dateformat = 'd M Y';
?>

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