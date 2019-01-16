<div class="formCon">
<div class="formConInner">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'hr-leave-types-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with'); ?><span class="required">*</span> <?php echo Yii::t('app','are required.') ;?></p>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo $form->labelEx($model,'type'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'type',array('size'=>30,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'type'); ?></td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,'description'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textArea($model,'description',array('size'=>30,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?></td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,'category'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->radioButtonList($model, 'category', array('1'=>Yii::t('app','Per Quarter'), '2'=>Yii::t('app','Per Year') , '3'=>Yii::t('app','Whole Carrer')), array('labelOptions'=>array('style'=>'display:inline'),'separator'=>' ')); ?>
		<?php echo $form->error($model,'category'); ?></td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,'gender'); ?></td>
    <td>&nbsp;</td>
    <td><?php
echo $form->radioButtonList($model, 'gender', array('0'=>'All', '1'=>Yii::t('app','Male'), '2'=>Yii::t('app','Female')), array('labelOptions'=>array('style'=>'display:inline'),'separator'=>' &nbsp;&nbsp;')); ?>
		<?php echo $form->error($model,'gender'); ?></td>
  </tr>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,'count'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'count'); ?>
		<?php echo $form->error($model,'count'); ?></td>
  </tr>
</table>

	<div style="padding:20px 0 0 0px;">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>

<?php $this->endWidget(); ?>
</div>
</div><!-- form -->