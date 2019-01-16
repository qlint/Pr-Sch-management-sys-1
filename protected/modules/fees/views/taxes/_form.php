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
	'id'=>'fee-taxes-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with');?> <span class="required"> * </span><?php echo Yii::t('app','are required');?>.</p>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><?php echo $form->labelEx($model,'label'); ?></td>
		<td><?php echo $form->textField($model,'label',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'label'); ?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><?php echo $form->labelEx($model,'value'); ?></td>
		<td><?php echo $form->textField($model,'value',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'value'); ?></td>
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
		<td><?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class'=>'formbut')); ?></td>
	</tr>
	
	
</table>

	<div class="row">
		
		
	</div>

	<div class="row">
		
		
	</div>
    
	<div class="row">
		
        
	</div>

	<div class="row buttons">
		
	</div>

<?php $this->endWidget(); ?>
</div>
</div><!-- form -->