<style>
.formCon td{
	vertical-align:middle;	
}
</style>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'category-form',
	'enableAjaxValidation'=>false,
)); ?>

	<!--<p class="note">Fields with <span class="required">*</span> are required.</p>-->

    <div class="formCon">
    <div class="formConInner">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" width="100"><?php echo $form->labelEx($model,'cat_name'); ?></td>    
    <td valign="top" width="250"><?php echo $form->textField($model,'cat_name',array('size'=>35,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'cat_name'); ?></td>
    <td valign="top"><?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?></td>
  </tr>

</table>

    </div>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->