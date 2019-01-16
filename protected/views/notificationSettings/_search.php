<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'settings_key'); ?>
		<?php echo $form->textField($model,'settings_key',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sms_enabled'); ?>
		<?php echo $form->textField($model,'sms_enabled'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'mail_enabled'); ?>
		<?php echo $form->textField($model,'mail_enabled'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'msg_enabled'); ?>
		<?php echo $form->textField($model,'msg_enabled'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('app','Search')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->