<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'room-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with').'&nbsp;';?><span class="required">*</span> <?php echo '&nbsp;'.Yii::t('app','are required.');?></p>

	<?php echo $form->errorSummary($model); ?>
     <div class="formCon" style="margin-bottom:8px; border:1px #e6e8e9 solid;">
<div class="formConInner"> 
       <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo $form->labelEx($model,'room_no'); ?></td>
   
    <td><?php echo $form->textField($model,'room_no',array('size'=>20,'value'=>$model->room_no)); ?>
		<?php //echo $form->error($model,'room_no'); ?></td>
  
    <td><?php echo $form->labelEx($model,'no_of_bed'); ?></td>
    <td>&nbsp;</td>
    <td><?php echo $form->textField($model,'no_of_bed',array('size'=>20,'value'=>$model->no_of_bed)); ?>
		<?php //echo $form->error($model,'no_of_bed'); ?></td>
  </tr>
   
</table>
  </div></div>

        <?php //echo $form->labelEx($model,'created'); ?>
	<?php
	if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL)
	{
		$floor = Floor::model()->findByAttributes(array('id'=>$model->floor));
		
	
	?>	
	
 <div class="formCon" style="border:1px #e6e8e9 solid;">
<div class="formConInner">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td width="12%"><?php echo $form->labelEx($model,'floor'); ?></td>
    <td width="85%"><?php echo $form->textField($model,'floor',array('value'=>$floor->floor_no,'readonly'=>true)); ?>
    <?php echo $form->hiddenField($model,'hostel_id',array('value'=>$_REQUEST['hostel_id'],'readonly'=>true)); ?>
		<?php //echo $form->error($model,'floor'); ?></td>
    
  </tr>

    </table>
 </div></div> 
 <?php } ?> 
<div>

	<div >
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>
 <?php $this->endWidget(); ?>
</div><!-- form -->