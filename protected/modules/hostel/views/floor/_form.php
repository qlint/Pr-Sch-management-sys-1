<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'floor-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with');?> <span class="required">*</span> <?php echo Yii::t('app','are required.');?></p>

	<?php echo $form->errorSummary($model); ?>
    <div class="formCon">
<div class="formConInner">

<div class="text-fild-bg-block">           
<div class="text-fild-block inputstyle">
<?php echo $form->labelEx($model,'hostel_id'); ?>
<?php echo $form->dropDownList($model,'hostel_id',CHtml::listData(Hosteldetails::model()->findAll('is_deleted=:x',array(':x'=>'0')),'id','hostel_name'),array('prompt'=>Yii::t('app','Select'))); ?>
		<?php echo $form->error($model,'hostel_id'); ?>

</div>
<div class="text-fild-block inputstyle">
<?php echo $form->labelEx($model,'floor_no'); ?>
<?php echo $form->textField($model,'floor_no',array('size'=>20)); ?>
		<?php echo $form->error($model,'floor_no'); ?>
</div>
<div class="text-fild-block inputstyle">
<?php echo $form->labelEx($model,'no_of_rooms'); ?>

<?php echo $form->textField($model,'no_of_rooms',array('size'=>20)); ?>
		<?php //echo $form->error($model,'no_of_rooms'); ?>

</div>
</div>

	<?php /*?><table width="60%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo $form->labelEx($model,'hostel_id'); ?></td>
    <td>&nbsp; </td>
    <td><?php echo $form->dropDownList($model,'hostel_id',CHtml::listData(Hosteldetails::model()->findAll('is_deleted=:x',array(':x'=>'0')),'id','hostel_name'),array('prompt'=>Yii::t('app','Select'))); ?>
		<?php echo $form->error($model,'hostel_id'); ?></td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,'floor_no'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'floor_no',array('size'=>20)); ?>
		<?php echo $form->error($model,'floor_no'); ?></td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,'no_of_rooms'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'no_of_rooms',array('size'=>20)); ?>
		<?php //echo $form->error($model,'no_of_rooms'); ?></td>
  </tr>
 
</table><?php */?>

	<div class="row">
		<?php //echo $form->labelEx($model,'created'); ?>
		<?php echo $form->hiddenField($model,'created',array('value'=>date('Y-m-d'))); ?>
		<?php echo $form->error($model,'created'); ?>
	</div>

	<div style="padding-top:20px;">
		<?php echo CHtml::submitButton($model->isNewRecord ?  Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>
</div>
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->