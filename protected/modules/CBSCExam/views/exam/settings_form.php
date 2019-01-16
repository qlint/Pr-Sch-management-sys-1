<div class="formCon">

<div class="formConInner">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'grade_settings-form',
	'enableAjaxValidation'=>false,
)); ?>

<p class="note"><?php echo Yii::t('app','Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app','are required.'); ?></p>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
      <td colspan="4"><h3><?php echo Yii::t('app','Weightage Settings'); ?></h3></td>
        
  </tr>
    <tr>
        <td><?php echo $form->labelEx($model,'fa1_weightage'); ?></td>
        <td><?php echo $form->textField($model,'fa1_weightage'); ?>
            <?php echo $form->error($model,'fa1_weightage'); ?></td>
        <td><?php echo $form->labelEx($model,'fa2_weightage'); ?></td>
        <td><?php echo $form->textField($model,'fa2_weightage'); ?>
        <?php echo $form->error($model,'fa2_weightage'); ?></td>
    
    </tr>
  <tr>
  	<td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
  </tr>
  <tr>
        <td><?php echo $form->labelEx($model,'sa1_weightage'); ?></td>
        <td><?php echo $form->textField($model,'sa1_weightage'); ?>
            <?php echo $form->error($model,'sa1_weightage'); ?></td>
        <td><?php echo $form->labelEx($model,'sa2_weightage'); ?></td>
        <td><?php echo $form->textField($model,'sa2_weightage'); ?>
        <?php echo $form->error($model,'sa2_weightage'); ?></td>
    </tr>
  <tr>
        
    
    </tr>
 
</table>

	<div style="padding:20px 0 0 0px;">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Submit') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>

<?php $this->endWidget(); ?>
</div>
</div><!-- form -->