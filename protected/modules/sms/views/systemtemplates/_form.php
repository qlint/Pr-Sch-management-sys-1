<style type="text/css">
	
.formCon input[type="text"], input[type="password"], textArea, select {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #C2CFD8;
    border-radius: 2px;
    box-shadow: -1px 1px 2px #D5DBE0 inset;
    padding: 6px;
    width: 260px !important;
}

textArea{ width:350px !important;}

.formbut_yellow button, input[type="submit"] {
    background: url("images/fbut-bg.png") repeat-x scroll 0 0 rgba(0, 0, 0, 0) !important;
    border: 1px solid #B58530 !important;
}


</style>

<div class="formCon" style="width:80%;">
<div class="formConInner">
<div class="form" >
<div class="class="listbxtop_hdng">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sms-templates-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app', "Fields with");?> <span class="required">*</span> <?php echo Yii::t('app', 'are required.');?></p>

	<?php echo $form->errorSummary($model); ?>
    
  
	<div class="row">
		<?php echo $form->labelEx($model,'template'); ?>
		<?php echo $form->textArea($model,'template',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'template'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>
</div>
</div>
</div>
</div><!-- form -->