<style>
.attendance_table table{ border-collapse:collapse}

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
	font-size:13px;
	
}

.attendance_table th{
	font-size:14px;
	padding:15px;
	border:1px solid #CCC ;

}
.pdtab_Con h1{
		text-align:center;
		font-size:16px;
}
.toarea{
	 margin-right:10px;
	 margin-left:10px;
}

hr{ border-bottom:1px solid #C5CED9; border-top:0px solid #fff;}
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
$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL){
	$dateformat	= $settings->dateformat;
}
else
{
	$dateformat = 'dd-mm-yy';
}
?>
   <div class="pdtab_Con" style="padding-top:0px;">
   <h1><?php echo Yii::t('app','Daily Collection Report').'('.date("d/m/y", strtotime($start_date)).'&nbsp;&nbsp;'.'to'.'&nbsp;&nbsp;'.date("d/m/y", strtotime($end_date)).')';?></h1>
   <?php
	 if(isset($model) and $model !=NULL){
	 ?>
	 <div class="attendance_table">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr class="pdtab-h">
			  <th align="center"><?php echo Yii::t('app','Id')?></th>
			  <th align="center"><?php echo Yii::t('app','Invoice Id')?></th>
			  <th align="center"><?php echo Yii::t('app','Category')?></th>
			  <th align="center"><?php echo Yii::t('app','Date')?></th>
			  <th align="center"><?php echo Yii::t('app','Payment Type')?></th>
			  <th align="center"><?php echo Yii::t('app','Transaction Id')?></th>
			  <th align="center"><?php echo Yii::t('app','Description')?></th>
			  <th align="center"><?php echo Yii::t('app','Amount')?></th>
		  </tr>
		  <?php 
		  $i=1;
		  foreach($model as $fees)
		  { 
		  ?>
			  <tr>
				<td align="center"><?php echo $i;$i++; ?></td>
				<?php
				if(isset($fees->invoice_id) and $fees->invoice_id!=NULL){
				?>
					 <td align="center"><?php echo $fees->invoice_id; ?></td>
				<?php
				}
				else{
				?>
				<td align="center"><?php echo '-'; ?></td>
				<?php
				}
				$invoice = FeeInvoices::model()-> findByAttributes(array('id'=>$fees->invoice_id));
				$category =FeeCategories::model()-> findByAttributes(array('id'=>$invoice->fee_id));
				?>
				<td align="center"><?php echo  $category->name; ?></td>
				<td align="center"><?php echo date("d M Y", strtotime($fees->date)); ?></td>
				 <?php
				 $payment = FeePaymentTypes::model()-> findByAttributes(array('id'=>$fees->payment_type));
				if(isset($payment->type) and $payment->type!=NULL){
				?>
				<td align="center"><?php echo $payment->type; ?></td>
				 <?php
				}
				else{
				?>
				<td align="center"><?php echo '-'; ?></td>
				<?php
				}
				 if(isset($fees->transaction_id) and $fees->transaction_id!=NULL){
				?>
				<td align="center"><?php echo $fees->transaction_id; ?></td>
				  <?php
				}
				else{
				?>
				<td align="center"><?php echo '-'; ?></td>
				<?php
				}
				if(isset($fees->transaction_id) and $fees->transaction_id!=NULL){
				?>
				<td align="center"><?php echo $fees->description; ?></td>
				 <?php
				}
				else{
				?>
				<td align="center"><?php echo '-'; ?></td>
				<?php
				}
				?>
				<td align="center"><?php echo $fees->amount; ?></td>
			  </tr>
		  <?php
		  $sum = $sum+$fees->amount;
		  }
		  ?>
        <tr>
            <td colspan="7" align="right"><?php echo Yii::t('app','Total Amount')?></td>
            <td align="right"><?php echo $sum; ?></td>
        </tr>
	  </table>
	<?php 
	}
	
 ?>
</div>