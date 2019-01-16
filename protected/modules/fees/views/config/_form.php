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
	'id'=>'fee-config-form',
	'enableAjaxValidation'=>false,
)); ?>

<h3><?php echo Yii::t('app', 'Setup Fees Configurations');?></h3>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="2"><?php echo $form->checkBox($model,'tax_in_fee',array('size'=>60,'maxlength'=>200)); ?>&nbsp;&nbsp;<?php echo $form->label($model,'tax_in_fee'); ?></td>
	</tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
    <tr>
		<td colspan="2"><?php echo $form->checkBox($model,'discount_in_fee',array('size'=>60,'maxlength'=>200)); ?>&nbsp;&nbsp;<?php echo $form->label($model,'discount_in_fee'); ?></td>
	</tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
    <tr>
		<td colspan="2"><?php echo $form->checkBox($model,'discount_in_invoice',array('size'=>60,'maxlength'=>200)); ?>&nbsp;&nbsp;<?php echo $form->label($model,'discount_in_invoice'); ?></td>
	</tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
    <tr>
    	<td width="20%"><?php echo $form->labelEx($model,'invoice_template'); ?></td>
		<td>
        	<?php
            	$templates			= array();
				$invoice_templates	= Yii::app()->getModule('fees')->invoice_templates;
				foreach($invoice_templates as $index=>$params){
					$templates[$index]	= $params['label'];
				}
			?>
			<?php echo $form->dropDownList($model,'invoice_template', $templates, array('style'=>'width:200px;')); ?>
			<?php echo $form->error($model,'invoice_template'); ?>
      	</td>
	</tr>
    <tr>
    	<td colspan="2">&nbsp;</td>
    </tr>
	<tr>
		<td colspan="2"><?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?></td>
	</tr>
</table>

	
<?php $this->endWidget(); ?>
</div>
</div><!-- form -->

<script>
$(':checkbox#FeeConfigurations_discount_in_fee').change(function(e) {
    if(!$(this).is(':checked')){
		$(':checkbox#FeeConfigurations_discount_in_invoice').attr('checked', false);
	}
});
</script>