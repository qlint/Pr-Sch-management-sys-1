<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'food-info-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with');?> <span class="required">*</span> <?php echo Yii::t('app','are required.');?></p>

	<?php echo $form->errorSummary($model); ?>
<div class="formCon" >
<div class="formConInner">
<div class="text-fild-bg-block">           
<div class="text-fild-block inputstyle">
<?php echo $form->labelEx($model,'food_preference'); ?>
<?php echo $form->textField($model,'food_preference',array('size'=>40,'maxlength'=>120)); ?>
		<?php echo $form->error($model,'food_preference'); ?>
</div>
<div class="text-fild-block inputstyle">
<?php echo $form->labelEx($model,'amount'); ?>
<?php echo $form->textField($model,'amount',array('size'=>40,'maxlength'=>120)); ?>
		<?php echo $form->error($model,'amount'); ?>
</div>

</div>
<?php /*?><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo $form->labelEx($model,'food_preference'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'food_preference',array('size'=>40,'maxlength'=>120)); ?>
		<?php echo $form->error($model,'food_preference'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,'amount'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'amount',array('size'=>40,'maxlength'=>120)); ?>
		<?php echo $form->error($model,'amount'); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table><?php */?>


	<div >
		<?php echo CHtml::submitButton($model->isNewRecord ?  Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>
</div>
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->