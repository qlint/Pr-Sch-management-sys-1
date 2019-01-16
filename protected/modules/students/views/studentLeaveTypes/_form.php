<style>
    .errorSummary
    {
            padding: 5px 5px 6px 40px;
    }
</style>
<div class="formCon">

<div class="formConInner">

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student-leave-types-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with'); ?><span class="required">*</span> <?php echo Yii::t('app','are required.') ;?></p>

	<?php echo $form->errorSummary($model); ?>
        <br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="80"><?php echo $form->labelEx($model,'name',array('style'=>'float:left')); ?></td>
        <td><?php echo $form->textField($model,'name',array('size'=>30,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'name'); ?></td>
        <td><?php echo $form->labelEx($model,'code',array('style'=>'float:left')); ?></td>
        <td><?php echo $form->textField($model,'code',array('size'=>30,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'code'); ?></td>
    </tr>
  <tr>
  	<td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,'label'); ?></td>
    <td><?php echo $form->textField($model,'label',array('size'=>30,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'label'); ?></td>
      <td><?php echo $form->labelEx($model,'colour_code'); ?></td>
      <td>
	 	<?php
		$this->widget('ext.SMiniColors.SActiveColorPicker', array(
		'model' => $model,
		'attribute' => 'colour_code',
		'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
		'options' => array(), // jQuery plugin options
		'htmlOptions' => array(), // html attributes
		));
		?>
		<span id="success-EventsType_colour_code"
		class="hid input-notification-success  success png_bg right"></span>
		<div>
		<small></small>
		</div>
		<?php echo $form->error($model,'label'); ?>
      </td>  
        
   </tr>
    
   <tr>
  	<td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

   	<tr>
        <td><?php echo $form->labelEx($model,'status',array('style'=>'float:left')); ?>
        </td>
        <td class="cr_align"><?php echo $form->radioButtonList($model,'status',array('1'=>Yii::t('app','Active'),'2'=>Yii::t('app','Inactive')),array('separator'=>' ')); ?>
        <?php echo $form->error($model,'status'); ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        
      
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
 
   <tr>
       <td colspan="2"><?php echo $form->labelEx($model,'is_excluded',array('style'=>'float:left')); ?>  <div style="display: inline-block; margin: 0 0 -6px;"> <?php echo $form->checkBox($model,'is_excluded'); ?></div></td>
      <td colspan="2">
       <?php echo $form->error($model,'is_excluded'); ?>
       </td>
   </tr>
</table>
    
<div class="cr_align" >

</div>
	<div class="clear"></div>

	<div style="padding:20px 0 0 0px;">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
</div>
</div>