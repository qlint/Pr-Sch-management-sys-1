<div class="form">
<div class="formConInner">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'material-requistion-form',
	'enableAjaxValidation'=>false,
)); ?>

<p class="note"><?php echo Yii::t('app','Fields with'); ?><span class="required">*</span> <?php echo Yii::t('app','are required.') ;?></p>
<?php 
   $settings=UserSettings::model()->findByAttributes(array('user_id'=>1));   
   
   if($settings!=NULL)
    {
		if($settings->displaydate!=NULL){
       		$date = $settings->displaydate;
		}else{
			$date = 'd-m-Y';
		}
		if($settings->dateformat!=NULL){
			$datepick = $settings->dateformat;
		}else{
	   		$datepick = 'dd-mm-yy';
		}
	   if ($model->from_date!= NULL )
			$model->from_date=date($settings->displaydate,strtotime($model->from_date));
	   if ($model->to_date!= NULL )
		   $model->to_date=date($settings->displaydate,strtotime($model->to_date));
	   
    }
    else
	{
    	$date = 'd-m-Y';	
		$datepick = 'dd-mm-yy';	 
		 
		if ($model->from_date!= NULL )
   	   		$model->from_date=date($settings->displaydate,strtotime($model->from_date));
	   if ($model->to_date!= NULL )
		   $model->to_date=date($settings->displaydate,strtotime($model->to_date));
	}
	
$leave_types	= LeaveTypes::model()->findAllByAttributes(array('is_deleted'=>0)); 
?>
<strong></strong>
	<?php echo $form->errorSummary($model); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  	<tr>
		<td><?php echo $form->labelEx($model,'leave_type_id'); ?></td>
		<td><?php echo $form->dropDownList($model,'leave_type_id',CHtml::listData(leaveTypes::model()->findAll(),'id','type'),array('empty' => Yii::t('app','Select Leave Type'),'class'=>'form-control')); ?></td>
	</tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
  </tr>
    
   <tr>
		<td><?php echo $form->labelEx($model,'from_date'); ?></td>
		<td><?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'attribute'=>'from_date',
							'model'=>$model,
							'options'=>array(
							'showAnim'=>'fold',
							'dateFormat'=>$datepick,
							'changeMonth'=> true,
							'changeYear'=>true,
							'yearRange'=>'1970:'
							),
							'htmlOptions'=>array(
							'readonly'=>"readonly"
							),
						))?>
		</td>
	</tr>
     <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
 	 </tr>
     
    <tr>
		<td><?php echo $form->labelEx($model,'to_date'); ?></td>
		<td><?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'attribute'=>'to_date',
							'model'=>$model,
							'options'=>array(
							'showAnim'=>'fold',
							'dateFormat'=>$datepick,
							'changeMonth'=> true,
							'changeYear'=>true,
							'yearRange'=>'1970:'
							),
							'htmlOptions'=>array(
							'readonly'=>"readonly"
							),
						))?>
		</td>
	</tr>
     <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
 	 </tr>
     
     <tr>
		<td><?php echo $form->labelEx($model,'is_half_day'); ?></td>
		<td><?php echo $form->radioButton($model, 'is_half_day', array('value'=>'1','uncheckValue'=>null))."Fore Noon &nbsp";
 					  echo $form->radioButton($model, 'is_half_day', array('value'=>'2','uncheckValue'=>null))." After Noon"; ?>
		</td>
	</tr>
    
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
 	 </tr>
     <tr>
		<td><?php echo $form->labelEx($model,'reason'); ?></td>
		<td><?php echo $form->textArea($model,'reason',array('size'=>60,'maxlength'=>225,'class'=>'leave-textarea')	); ?></td>
	</tr>
    
     <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
 	 </tr>
     <tr>
		<td><?php echo $form->labelEx($model,'file_name'); ?></td>
		<td> <?php echo $form->fileField($model,'file_name'); ?></td>
	</tr>
  
</table>  

	<div style="padding:20px 0 0 0px;">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
		</div>

<?php $this->endWidget(); ?>

	</div><!-- form -->
</div>