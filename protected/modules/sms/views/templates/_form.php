
<div class="formCon">
  <div class="formConInner">
    <div class="form">
      <?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sms-templates-form',
	'enableAjaxValidation'=>false,
)); ?>
<p class="note"><?php echo Yii::t('app', 'Fields with');?> <span class="required">*</span> <?php echo Yii::t('app', 'are required.');?></p>
<div class="txtfld-col-box">          
<div class="txtfld-col">
<?php echo $form->labelEx($model,'name'); ?>
<?php echo $form->textField($model,'name'); ?> <?php echo $form->error($model,'name'); ?>
</div>
</div>
<div class="txtfld-col-box">          
<div class="text-fild-block-full">
<?php echo $form->labelEx($model,'template'); ?>
<?php echo $form->textArea($model,'template',array('rows'=>6, 'cols'=>50)); ?> <?php echo $form->error($model,'template'); ?>
</div>
</div>
<div class="txtfld-col-btn">          
<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), array('class'=>'formbut')); ?>
</div>


      <?php $this->endWidget(); ?>
    </div>
  </div>
</div>
