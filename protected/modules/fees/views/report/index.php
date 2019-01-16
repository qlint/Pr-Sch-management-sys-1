<?php $this->breadcrumbs=array(
	Yii::t('app','Fees')=>array('/fees'),
	Yii::t('app','Daily Collection Report')=>array('/fees/report'),
	Yii::t('app','Generate Daily Collection Report'),
);
$settings	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL){ 
	$date			=	str_ireplace("d","",$settings->dateformat); 
}else{ 
	$date			=	str_ireplace("d","",$settings->dateformat); 	
}
 $start=$end='';
if($start_date!=NULL)
{
$start	=	date("d M Y", strtotime($start_date));
}
if($end_date!=NULL)
{
$end	=	date("d M Y", strtotime($end_date));
}
?>
<?php 
$form=$this->beginWidget('CActiveForm', array(
'id'=>'invoiceall-form',
'enableAjaxValidation'=>false,
)); 
$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL){
	$dateformat	= $settings->dateformat;
}
else
{
	$dateformat = 'dd-mm-yy';
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/default/left_side');?>  
        </td>
        <td valign="top">
            <div class="cont_right">
              <h1><?php echo Yii::t('app','Daily Collection Report');?></h1>  
                <div class="formCon">
                    <div class="formConInner">
                        <div class="txtfld-col-box">
                        <h3><?php echo Yii::t('app','Daily Collection Report');?></h3>
                             <table>
                                 <tr>
                                    <td><?php echo Yii::t('app','Start Date');?></td>
                                    <td>
										<?php                        
                                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
										'value'=>$start,
                                        'name' => 'star_date',
                                        // additional javascript options for the date picker plugin
                                        'options'=>array(
                                        'showAnim'=>'fold',
                                        'dateFormat'=>$dateformat,
                                        'changeMonth'=> true,
                                        'changeYear'=>true,
                                        'yearRange'=>'1900:'.(date('Y')+5)
                                        ),
                                        'htmlOptions'=>array(
                                        'readonly'=>true,
										'id'=>'from_date',
                                        ),
                                        ));?>
                                        
                                    </td>
                                    <td>&nbsp;&nbsp;&nbsp;</td>
                                    <td><?php echo Yii::t('app','End Date');?></td>
                                    <td>
										<?php                        
                                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
										'value'=>$end,
                                        'name' => 'end_date',
                                        // additional javascript options for the date picker plugin
                                        'options'=>array(
                                        'showAnim'=>'fold',
                                        'dateFormat'=>$dateformat,
                                        'changeMonth'=> true,
                                        'changeYear'=>true,
                                        'yearRange'=>'1900:'.(date('Y')+5)
                                        ),
                                        'htmlOptions'=>array(
                                        'readonly'=>true,
										'id'=>'to_date'
                                        ),
                                        ));?>
                                        
                                    </td>
                                        <td>&nbsp;&nbsp;&nbsp;</td>
                                        <td><?php echo CHtml::submitButton(Yii::t('app','Submit'),array('name'=>'submit_button_form','class'=>'formbut', 'id'=>'search_btn')); ?></td>
                                    </tr>
                                    <tr>
                                    	<td>&nbsp;</td>
                                        <td><div id="start_error" class="required"></div></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td><div id="end_error" class="required"></div></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                             </table>
                       		 </div>
                            </div>
                </div>
                        <div class="pdtab_Con" style="padding-top:0px;">
                       
                             <?php
							 if(isset($_REQUEST['submit_button_form'])){
								 if($start_date > $end_date){
									 ?>
                                      <div class="not-found-box"> <?php echo Yii::t('app','Invalid data!')?></div>
                                  <?php   
								 }
								 else if(isset($model) and $model ==NULL){
									 ?>
                                      <div class="not-found-box"> <?php echo Yii::t('app','No payments received!')?></div>
                                  <?php   
								 }
								 
							 }
								 if(isset($model) and $model !=NULL){
								 ?>
                                 <div>
                                 <?php
								  $sdate	=	date("Y-m-d", strtotime($start));
								  $edate	=	date("Y-m-d", strtotime($end));
								 ?>
								<?php 
                                $url_params1	= array('/fees/report/excel');
                                if(isset($start_date))
                                {
                                $url_params1['from_date']= $start_date;
                                $url_params1['to_date']= $end_date;                         
                                }                        
                                echo CHtml::link(Yii::t('app','Generate EXCEL'), $url_params1 ,array('target'=>"_blank",'class'=>'xl-but'));?>

	                             	<?php echo CHtml::link(Yii::t("app",'Generate PDF'), array('/fees/report/reportPdf','startdate'=>$sdate, 'enddate'=>$edate),array('target'=>'_blank','class'=>'pdf_but')); ?>
                                 </div>
                                 <br />
                                 <div>
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
                                        <td colspan="7" style=" font-weight:600" align="right"><?php echo Yii::t('app','Total Amount')?></td>
                                      	<td align="center" style=" font-weight:600"><?php echo $sum; ?></td>
									 </tr>
                                  </table>
								<?php
									 
							    }
							 ?>
                            </div>
                        </div>
                     <?php $this->endWidget(); ?>
                
             </div>  
        </td>
    </tr>
</table>
<script type="text/javascript">
$('#search_btn').click(function(ev){
	$('#start_error').html('');
	$('#end_error').html('');
	var start 			= $('#from_date').val();
	var end 			= $('#to_date').val();
	var flag = 0;
	if(start ==''){
		$('#start_error').html('<?php echo Yii::t('app','Start Date cannot be blank'); ?>');
		flag = 1;
	}
	if(end == ''){
		$('#end_error').html('<?php echo Yii::t('app','End Date cannot be blank'); ?>');
		flag = 1;
	}
	if(flag == 1){
		return false;
	}
});

</script>

				