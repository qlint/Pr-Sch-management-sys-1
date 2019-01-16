<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'room-details-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with').'&nbsp;';?><span class="required">*</span> <?php echo '&nbsp;'.Yii::t('app','are required.');?></p>

	<?php echo $form->errorSummary($model); ?>
<?php
$room=Room::model()->findByAttributes(array('id'=>$_REQUEST['id']));


?>
		<div class="row">
		<?php echo $form->labelEx($model,'room_no'); ?>
          <?php echo $form->textField($model,'room_no',array('size'=>20,'value'=>$room->room_no)); ?>
		<?php echo $form->error($model,'room_no'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bed_no'); ?>
		<?php echo $form->textField($model,'bed_no',array('size'=>20)); ?>
		<?php echo $form->error($model,'bed_no'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'no_of_floors'); ?>
		<?php echo $form->textField($model,'no_of_floors',array('size'=>20,'value'=>$room->floor)); ?>
		<?php echo $form->error($model,'no_of_floors'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mode_of_allotment'); ?>
		<?php echo $form->dropDownList($model,'mode_of_allotment',array('1'=>'Yearwise','2'=>Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.'wise'),array('prompt'=>'Select')); ?>
		<?php echo $form->error($model,'mode_of_allotment'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'created'); ?>
		<?php echo $form->hiddenField($model,'created',array('value'=>date('Y-m-d'))); ?>
		<?php echo $form->error($model,'created'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->