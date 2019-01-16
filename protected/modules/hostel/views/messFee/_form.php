<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'mess-fee-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with').'&nbsp;';?><span class="required">*</span> <?php echo '&nbsp;'.Yii::t('app','are required.');?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php //echo $form->labelEx($model,'student_id'); ?>
		<?php //echo $form->textField($model,'student_id',array('size'=>60,'maxlength'=>120)); ?>
		<?php //echo $form->error($model,'student_id'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'allotment_id'); ?>
		<?php //echo $form->textField($model,'allotment_id',array('size'=>60,'maxlength'=>120)); ?>
		<?php //echo $form->error($model,'allotment_id'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'is_paid'); ?>
		<?php //echo $form->textField($model,'is_paid',array('size'=>60,'maxlength'=>120)); ?>
		<?php //echo $form->error($model,'is_paid'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'created'); ?>
		<?php //echo $form->textField($model,'created'); ?>
		<?php //echo $form->error($model,'created'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->