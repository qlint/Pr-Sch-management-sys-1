<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'employee-leave-types-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
<br />
<br /><br />
<div class="formCon">

<div class="formConInner">

<div class="form">

	<p class="note"><?php echo Yii::t('app','Fields with').' ';?><span class="required">*</span><?php echo Yii::t('app',' are required.');?></p>

<div class="txtfld-col-btn">            
    <div class="txtfld-col">
		<?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('size'=>30,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'name'); ?>	                   
	</div>       
    <div class="txtfld-col">
		<?php echo $form->labelEx($model,'code'); ?> 
        <?php echo $form->textField($model,'code',array('size'=>30,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'code'); ?>                 							                 
	</div> 

    <div class="txtfld-col">

		<?php echo $form->labelEx($model,'max_leave_count'); ?>
		<?php echo $form->textField($model,'max_leave_count',array('size'=>30,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'max_leave_count'); ?>                   
	</div>
	</div>
<div class="txtfld-col-btn">    
		<?php echo $form->labelEx($model,'status'); ?> 
        <?php echo $form->radioButtonList($model,'status',array('1'=>Yii::t('app','Active'),'2'=>Yii::t('app','Inactive')),array('separator'=>' ')); ?>
        <?php echo $form->error($model,'status'); ?>                  
	</div>	
<?php /*?><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo $form->labelEx($model,'name'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'name',array('size'=>30,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,'code'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'code',array('size'=>30,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'code'); ?></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,'status'); ?></td>
    <td>&nbsp;</td>
    <td> 
    	<div class="cr_align" >
			<?php echo $form->radioButtonList($model,'status',array('1'=>Yii::t('app','Active'),'2'=>Yii::t('app','Inactive')),array('separator'=>' ')); ?>
         	<?php echo $form->error($model,'status'); ?>
		</div>
	</td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,'max_leave_count'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'max_leave_count',array('size'=>30,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'max_leave_count'); ?></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr><?php */?>
  <?php /*?><tr>
    <td><?php echo $form->labelEx($model,'carry_forward'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'carry_forward'); ?>
		<?php echo $form->error($model,'carry_forward'); ?></td>
  </tr><?php */?>
<?php /*?></table><?php */?>

	<div style="padding:20px 0 0 0px;">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
</div>
</div>