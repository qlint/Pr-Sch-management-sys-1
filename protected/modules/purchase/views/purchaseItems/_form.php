<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'employee-departments-form',
	'enableAjaxValidation'=>false,
)); ?>
 <div class="clear"></div>
<p class="note"><?php echo Yii::t('app','Fields with'); ?><span class="required">*</span> <?php echo Yii::t('app','are required.') ;?></p>

<div class="formCon">


<div class="formConInner">
	

	
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="15%"><?php echo $form->labelEx($model,'name'); ?>
		</td>
    <td width="40%"><?php echo $form->textField($model,'name',array('size'=>30,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?></td>
        <td>		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?></td>
  </tr>
</table>


<?php $this->endWidget(); ?>
</div>
</div><!-- form -->