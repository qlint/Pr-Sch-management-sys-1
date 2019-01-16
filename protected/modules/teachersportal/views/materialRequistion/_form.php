<style>
.required{
	color:#4a535e;
}
.required span{
	color:#F00;
}

.note span{
	color:#F00;
}
</style>

<div class="inner_new_form">
<div class="form">
<div class="inner_new_formCon">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'material-requistion-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

	<h5 class="note"><?php echo Yii::t('app', 'Fields with');?> <span class="required" >*</span> <?php echo Yii::t('app', 'are required.');?></h5>

	<?php echo $form->errorSummary($model); ?>
    
<div class="form-group">
	<label class="col-sm-3 control-label"><?php echo $form->labelEx($model,'department_id'); ?> </label>
    
    <div class="col-sm-6">
	<?php echo $form->dropDownList($model,'department_id',CHtml::listData(EmployeeDepartments::model()->findAll(),'id','name'),array('empty' => Yii::t('app','Select Department'))); ?>
		<?php echo $form->error($model,'department_id'); ?> </div>
  

	</div>

	<div class="form-group">
	<label class="col-sm-3 control-label"><?php echo $form->labelEx($model,'material_id'); ?></label>
    
    
    <div class="col-sm-6"><?php echo $form->dropDownList($model,'material_id',CHtml::listData(PurchaseItems::model()->findAll(),'id','item'),array('empty' => Yii::t('app','Select Material'))); ?>
		<?php echo $form->error($model,'material_id'); ?></div></div>
     
	<div class="form-group">
	<label class="col-sm-3 control-label"><?php echo $form->labelEx($model,'quantity'); ?></label>
    
   <div class="col-sm-6"><?php echo $form->textField($model,'quantity',array('size'=>20,'maxlength'=>20)); ?>
	<?php echo $form->error($model,'quantity'); ?> </div></div>
    
        
        <div class="form-group">
	<label class="col-sm-3 control-label"></label>
    
    <div class="col-sm-6">		
		
        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'btn btn-danger')); ?>
        </div></div>
       
	</div>

	<div class="inner_new_formCon_row row buttons">
		
	</div>

<?php $this->endWidget(); ?>
</div>	
</div><!-- form -->
</div>