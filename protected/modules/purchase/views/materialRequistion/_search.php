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
		<?php echo $form->label($model,'groupstaff_id'); ?>
		<?php echo $form->textField($model,'groupstaff_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pepartment_id'); ?>
		<?php echo $form->textField($model,'pepartment_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'material_id'); ?>
		<?php echo $form->textField($model,'material_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'quantity'); ?>
		<?php echo $form->textField($model,'quantity'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status_hod'); ?>
		<?php echo $form->textField($model,'status_hod'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status_pm'); ?>
		<?php echo $form->textField($model,'status_pm'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'is_issued'); ?>
		<?php echo $form->textField($model,'is_issued'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'is_deleted'); ?>
		<?php echo $form->textField($model,'is_deleted'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->