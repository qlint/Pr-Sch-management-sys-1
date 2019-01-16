<div class="form">
<div class="formConInner">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'material-requistion-form',
	'enableAjaxValidation'=>false,
)); ?>

<p class="note"><?php echo Yii::t('app','Fields with'); ?><span class="required">*</span> <?php echo Yii::t('app','are required.') ;?></p>

	<?php /*?><?php echo $form->errorSummary($model); ?><?php */?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  	<tr>
		<td><?php echo $form->labelEx($model,'department_id'); ?></td>
		<td><?php echo $form->dropDownList($model,'department_id',CHtml::listData(EmployeeDepartments::model()->findAll(),'id','name'),array('empty' => Yii::t('app','Select Department'))); ?>
		<?php echo $form->error($model,'department_id'); ?></td>
	</tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
  </tr>
    
   <tr>
		<td><?php echo $form->labelEx($model,'material_id'); ?></td>
		<td><?php echo $form->dropDownList($model,'material_id',CHtml::listData(PurchaseItems::model()->findAll(),'id','name'),array('empty' => Yii::t('app','Select Material'))); ?>
		<?php echo $form->error($model,'material_id'); ?></td>
	</tr>
     <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
 	 </tr>
     
     <tr>
		<td><?php echo $form->labelEx($model,'quantity'); ?></td>
     
		<td><?php echo $form->textField($model,'quantity',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'quantity'); ?></td>
	</tr>
     <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
 	 </tr>

    
  
</table>  

	<div style="padding:20px 0 0 0px;">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
		</div>

<?php $this->endWidget(); ?>

	</div><!-- form -->
</div>