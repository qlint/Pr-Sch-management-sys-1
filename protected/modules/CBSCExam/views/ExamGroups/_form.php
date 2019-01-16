<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cbsc-exam-groups-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with');?> <span class="required"> * </span><?php echo Yii::t('app','are required');?>.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'term_id'); ?>
		<?php echo $form->textField($model,'term_id'); ?>
		<?php echo $form->error($model,'term_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'exam_type'); ?>
		<?php echo $form->textField($model,'exam_type',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'exam_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mark_type'); ?>
		<?php echo $form->textField($model,'mark_type',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'mark_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date_published'); ?>
		<?php echo $form->textField($model,'date_published'); ?>
		<?php echo $form->error($model,'date_published'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'result_published'); ?>
		<?php echo $form->textField($model,'result_published'); ?>
		<?php echo $form->error($model,'result_published'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->