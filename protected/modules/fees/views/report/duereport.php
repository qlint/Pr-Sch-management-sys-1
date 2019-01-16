<?php $this->breadcrumbs=array(
	Yii::t('app','Fees')=>array('/fees'),
	Yii::t('app','Due Report')=>array('/fees/report/duereport'),
	Yii::t('app','Generate Due Report'),
);
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
if(Yii::app()->user->year){
	$year 					= Yii::app()->user->year;
}
else{
	$current_academic_yr 	= Configurations::model()->findByAttributes(array('id'=>35));
	$year 					= $current_academic_yr->config_value;
}
if(isset($batch_id) and $batch_id!=NULL)
{
	$ba = $batch_id;
	
}

if(isset($course_id) and $course_id!=NULL)
{
	$co 	=$course_id;
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/default/left_side');?>  
        </td>
        <td valign="top">
            <div class="cont_right">
              <h1><?php echo Yii::t('app','Due Report');?></h1>  
                <div class="formCon">
                    <div class="formConInner">
                        <div class="txtfld-col-box">
                        <h3><?php echo Yii::t('app','Due Report');?></h3>
                             <table>
                                 <tr>
                                     <td><?php echo Yii::t("app", "Select Course");?></td>
                                     <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                      <td>
                                      <?php
                                            $criteria  = new CDbCriteria;                  
                                            $criteria ->condition = 'academic_yr_id =:academic_yr';
                                            $criteria->params = array(':academic_yr'=>$year);
                                            $criteria ->compare('is_deleted', 0); 
                                            $course_name =CHtml::listData(Courses::model()->findAll($criteria),'id','course_name'); 
                                            $course_list = CMap::mergeArray(array(0=>Yii::t('app','Select Course')),$course_name);
                                            echo CHtml::dropDownList('course_id','',$course_list,array(
                                            'ajax' => array(
                                            'type'=>'POST',
                                            'url'=>CController::createUrl('/fees/report/reportCourse'),
                                            'update'=>'#batch_id',
                                            'data'=>'js:{course:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',),'class'=>'form-control','encode'=>false,'options' => array($co=>array('selected'=>true))));
                        ?>
                                      <div id="course_error" class="required"></div>
                                      </td>
                                 </tr>
                                 <tr><td>&nbsp;&nbsp;</td></tr>
                                 <tr>
                                     <td><?php echo Yii::t("app", "Select Batch");?></td>
                                     <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                     <td><?php
                                        $batch_names =CHtml::listData(Batches::model()->findAll('is_active=:x AND is_deleted=:y AND course_id=:z',array(':x'=>1,':y'=>0,':z'=>$co)),'id','name'); 
										$batch_list = CMap::mergeArray(array(0=>Yii::t('app','Select Batch')),$batch_names);
                                        echo CHtml::dropDownList('batch_id','',$batch_list,array('options' => array($ba=>array('selected'=>true))));
										?>
                                        <div id="batch_error" class="required"></div>
                                     </td>
                                 </tr>
                                  <tr><td>&nbsp;&nbsp;</td></tr>
                                 <tr>
                                      <td> 
                                     	 <?php echo CHtml::submitButton(Yii::t('app','Submit'), array('class'=>'formbut', 'id'=>'search_btn', 'name'=>'submit')); ?>
                                      </td>
                                 </tr>
                             </table>
                        </div>
                     </div>
                </div>
                
                        <div class="pdtab_Con" style="padding-top:0px;">
                                 <div>
                                  <?php
								  if(isset($_REQUEST['submit'])){
									   if(isset($model) and $model == NULL){
										   ?>
										 <div class="not-found-box"> <?php echo Yii::t('app','No Dues Found!')?></div>
									  <?php
									   }
								  }
								 if(isset($model) and $model !=NULL){
									// var_dump($model);exit;
								 ?>
                                 <div>
								<?php 
                                $url_params1	= array('/fees/report/dueExcel');
                                if(isset($ba))
                                {
                                $url_params1['batch_id']= $ba;
                                $url_params1['course_id']= $co;                         
                                }                        
                                echo CHtml::link(Yii::t('app','Generate EXCEL'), $url_params1 ,array('target'=>"_blank",'class'=>'xl-but'));?>

	                            <?php echo CHtml::link(Yii::t("app",'Generate PDF'), array('/fees/report/reportDuePdf','batch_id'=>$ba, 'course_id'=>$co),array('target'=>'_blank','class'=>'pdf_but')); ?>
                                 </div>
                                 <br />
                                 <div>
                    			 <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                 	 <tr class="pdtab-h">
                                          <th align="center"><?php echo Yii::t('app','Id')?></th>
                                          <th align="center"><?php echo Yii::t('app','Student Name')?></th>
                                          <th align="center"><?php echo Yii::t('app','Admission No')?></th>
                                          <th align="center"><?php echo Yii::t('app','Category')?></th>
                                          <th align="center"><?php echo Yii::t('app','Due Date')?></th>
                                          <th align="center"><?php echo Yii::t('app','Amount')?></th>
                                          <th align="center"><?php echo Yii::t('app','Due')?></th>
                                      </tr>
                                      <?php 
									  $i=1;
									  foreach($model as $fees)
									  { 
									   $invoices =FeeInvoices::model()->findAllByAttributes(array('table_id'=>$fees->id,'user_type'=>'1','is_paid'=>'0','is_canceled'=>'0'));
									  // var_dump($invoices);exit;
									    foreach($invoices as $invoice){
									  ?>
                                      <tr>
                                         <td align="center"><?php echo $i;$i++; ?></td>
                                         <td align="center"><?php echo $fees->first_name.' '.$fees->middle_name.' '.$fees->last_name; ?></td>
                                         <td align="center"><?php echo $fees->admission_no; ?></td>
                                         <?php
										 if(isset($invoice->name) and $invoice->name!=NULL){ 	
										  ?>
                                          <td align="center"><?php echo $invoice->name; ?></td>
                                          <?php
										 }
										 else{
										 ?>
                                          <td align="center"><?php echo '-'; ?></td>
                                          <?php
										 }
										 ?>
                                          <?php
										 if(isset($invoice->due_date) and $invoice->due_date!=NULL){ 	
										  ?>
                                          <td align="center"><?php echo date("d M Y",strtotime($invoice->due_date)); ?></td>
                                          <?php
										 }
										 else{
										 ?>
                                          <td align="center"><?php echo '-'; ?></td>
                                          <?php
										 }
										 ?>
                                         
                                          <td align="center">
                                          <?php
											$invoice_amount = 0;
											$criteria       = new CDbCriteria;
											$criteria->compare("invoice_id", $invoice->id);
											$particulars    = FeeInvoiceParticulars::model()->findAll($criteria);
											foreach($particulars as $key=>$particular){
												$amount = $particular->amount;
												//apply discount
												if($particular->discount_type==1){  //percentage
													$idiscount          = (($particular->amount * $particular->discount_value)/100);
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
												$invoice_amount += $amount;                                                     
											}
											echo number_format($invoice_amount, 2);
										?>
                                          </td>
                                          <td>
                                          <?php
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
											echo number_format($amount_payable, 2);
										?>
                                          </td>
										  <?php
	
											$sum = $sum + $invoice_amount;
											$sum_due = $sum_due + $amount_payable;
											?>
                                        
										
                                      </tr>
									  
                                      <?php
										}
									  }
									  
                      				  ?>

										<tr>
                                        <td colspan="5" style=" font-weight:600" align="right"><?php echo Yii::t('app','Total')?></td>
                                      	<td align="center" style=" font-weight:600"><?php echo $sum; ?></td>
										  <td align="center" style=" font-weight:600"><?php echo $sum_due; ?></td>
									 </tr>
										
				
                                  </table>
								
                               </div>
							   
                               <?php
								 }
								
								 ?>
                        </div>
                     <?php $this->endWidget(); ?>
             </div> 
             </div> 
        </td>
		
    </tr>
	

</table>
<script type="text/javascript">
$('#search_btn').click(function(ev){
	$('#course_error').html('');
	$('#batch_error').html('');
	var course 			= $('#course_id').val();
	var batch 		= $('#batch_id').val();
	//alert(course);
	var flag = 0;
	if(course == '0'){
		$('#course_error').html('<?php echo Yii::t('app','Course cannot be blank'); ?>');
		flag = 1;
	}
	if(batch == '0'){
		$('#batch_error').html('<?php echo Yii::t('app','batch cannot be blank'); ?>');
		flag = 1;
	}
	
	if(flag == 1){
		return false;
	}
});
</script>
				