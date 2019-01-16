<div class="form">
  <?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'route-details-form',
	'enableAjaxValidation'=>false,
)); ?>
  <p><?php echo Yii::t('app','Fields with');?><span class="required">*</span><?php echo Yii::t('app','are required.');?></p>
  <?php echo $form->errorSummary($model); ?>
  <div class="formCon" >
    <div class="formConInner">
      <h3>Update Rout Details</h3>
      <div class="txtfld-col-btn">
        <div class="txtfld-col"> <?php echo $form->labelEx($model,'route_name'); ?> <?php echo $form->textField($model,'route_name'); ?> <?php echo $form->error($model,'route_name'); ?> </div>
        <div class="txtfld-col"> <?php echo $form->labelEx($model,'no_of_stops'); ?> <?php echo $form->textField($model,'no_of_stops'); ?> <?php echo $form->error($model,'no_of_stops'); ?> </div>
        <div class="txtfld-col"> <?php echo $form->labelEx($model,'vehicle_id'); ?> <?php echo $form->dropDownList($model,'vehicle_id',CHtml::listData(VehicleDetails::model()->findAll('status=:x',array(':x'=>'C')),'id','vehicle_code'),array('prompt'=>Yii::t('app','Select'))); ?> <?php echo $form->error($model,'vehicle_id'); ?> </div>
      </div>
    </div>
  </div>
  <div class="row buttons"> <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Update'),array('class'=>'formbut')); ?> </div>
  <?php $this->endWidget(); ?>
</div>
<!-- form -->