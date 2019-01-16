<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'elective-exams-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with');?> <span class="required"> * </span><?php echo Yii::t('app','are required');?>.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'exam_group_id'); ?>
		<?php echo $form->textField($model,'exam_group_id'); ?>
		<?php echo $form->error($model,'exam_group_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'elective_id'); ?>
		<?php echo $form->textField($model,'elective_id'); ?>
		<?php echo $form->error($model,'elective_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'start_time'); ?>
		<?php echo $form->textField($model,'start_time'); ?>
		<?php echo $form->error($model,'start_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'end_time'); ?>
		<?php echo $form->textField($model,'end_time'); ?>
		<?php echo $form->error($model,'end_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'maximum_marks'); ?>
		<?php echo $form->textField($model,'maximum_marks',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'maximum_marks'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'minimum_marks'); ?>
		<?php echo $form->textField($model,'minimum_marks',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'minimum_marks'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'grading_level_id'); ?>
		<?php echo $form->textField($model,'grading_level_id'); ?>
		<?php echo $form->error($model,'grading_level_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'weightage'); ?>
		<?php echo $form->textField($model,'weightage'); ?>
		<?php echo $form->error($model,'weightage'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'event_id'); ?>
		<?php echo $form->textField($model,'event_id'); ?>
		<?php echo $form->error($model,'event_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created_at'); ?>
		<?php echo $form->textField($model,'created_at'); ?>
		<?php echo $form->error($model,'created_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'updated_at'); ?>
		<?php echo $form->textField($model,'updated_at'); ?>
		<?php echo $form->error($model,'updated_at'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->