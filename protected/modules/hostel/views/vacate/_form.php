<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'vacate-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with').'&nbsp;';?><span class="required">*</span> <?php echo '&nbsp;'.Yii::t('app','are required.');?></p>

	<?php echo $form->errorSummary($model); ?>
<?php

if((Yii::app()->controller->action->id)=='create')
{
	$student_details=Allotment::model()->findByAttributes(array('student_id'=>$_REQUEST['id'],'status'=>'S'));
	
	$room=Room::model()->findByAttributes(array('id'=>$student_details->room_no));
}
?>
<input type="hidden" value="<?php echo $student_details->room_no; ?>" name="room_id" />
<div class="formCon" >
<div class="formConInner">
<table width="80%" border="0" cellspacing="0" cellpadding="0">
 <tr>
    <td><?php //echo $form->labelEx($model,'Student'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->hiddenField($model,'student_id',array('size'=>20,'value'=>$student_details->student_id)); ?>
		<?php echo $form->error($model,'student_id'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td><?php echo $form->labelEx($model,'room_no'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'room_no',array('size'=>20,'value'=>$room->room_no,'readonly'=>true)); ?>
		<?php echo $form->error($model,'room_no'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
	 <tr>
    <td><?php echo $form->labelEx($model,'admit_date'); ?></td>
    <td>&nbsp;</td>
    <?php
	$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
								if($settings!=NULL)
								{	
									$date1=date($settings->displaydate,strtotime($student_details->created));
									
	
								}
	?>
    <td><?php echo $form->textField($model,'admit_date',array('size'=>20,'value'=>$date1)); ?>
		<?php echo $form->error($model,'admit_date'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

	<?php /*?><div class="row">
		<?php echo $form->labelEx($model,'allot_id'); ?>
		<?php echo $form->textField($model,'allot_id',array('size'=>20,$student_details->id)); ?>
		<?php echo $form->error($model,'allot_id'); ?>
	</div>
<?php */?>
</table>
	<div class="row">
		<?php //echo $form->labelEx($model,'status'); ?>
		<?php echo $form->hiddenField($model,'status',array('value'=>'C')); ?>
		<?php //echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'vacate_date'); ?>
		<?php echo $form->hiddenField($model,'vacate_date',array('value'=>date('Y-m-d'))); ?>
		<?php //echo $form->error($model,'vacate_date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Vacate') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>
    </div>
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->