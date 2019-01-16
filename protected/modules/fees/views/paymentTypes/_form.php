<style>
#status input
{
    float:left;
}
#status label
{
    float:left;
}
</style>
<div class="formCon">
<div class="formConInner">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'fee-payment-types-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with');?> <span class="required"> * </span><?php echo Yii::t('app','are required');?>.</p>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><?php echo $form->labelEx($model,'type'); ?></td>
		<td><?php echo $form->textField($model,'type',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'type'); ?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><?php echo $form->labelEx($model,'is_active'); ?></td>
		<td id="status"><?php echo $form->radioButtonList($model,'is_active', array(1=>Yii::t("app", "Active"), 0=>Yii::t("app", "Inactive")), array('separator'=>'')); ?>
		<?php echo $form->error($model,'is_active'); ?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?></td>
	</tr>
</table>

	
<?php $this->endWidget(); ?>
</div>
</div><!-- form -->