<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sms-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app', 'Fields with');?> <span class="required">*</span> <?php echo Yii::t('app', 'are required.');?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'uid'); ?>
		<?php 
        
        if (Yii::app()->user->isSuperuser) {
       $all_roles=new RAuthItemDataProvider('roles', array( 
    'type'=>2,
    ));
      $data=$all_roles->fetchData();
?>
    
        <?php echo $form->dropDownList($model,'uid',CHtml::listData($data,'name','name'),array('id'=>'sms-to', 'prompt'=>Yii::t('app', 'Select')));?> 
        
        
		<?php echo $form->error($model,'uid');
		
		}?>
	</div>

	

	<div class="row">
		<?php echo $form->labelEx($model,'message'); ?>
		<?php echo $form->textArea($model,'message',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'message'); ?>
	</div>

	

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->