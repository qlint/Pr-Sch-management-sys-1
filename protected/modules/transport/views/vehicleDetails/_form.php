  <div class="form">
  <?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'vehicle-details-form',
  'enableAjaxValidation'=>false,
  )); ?>
  <p><?php echo Yii::t('app','Fields with');?><span class="required">*</span><?php echo Yii::t('app','are required.');?></p>
  <?php //echo $form->errorSummary($model); ?>
  <div class="formCon" >
  <div class="formConInner">
  <div class="text-fild-bg-block">
  <div class="text-fild-block inputstyle"> 
  <?php echo $form->labelEx($model,'vehicle_no'); ?> 
  <?php echo $form->textField($model,'vehicle_no',array()); ?> 
  <?php echo $form->error($model,'vehicle_no'); ?> 
  </div>
  <div class="text-fild-block inputstyle"> 
  <?php echo $form->labelEx($model,'vehicle_code'); ?> 
  <?php echo $form->textField($model,'vehicle_code',array()); ?> 
  <?php echo $form->error($model,'vehicle_code'); ?> 
  </div>
  <div class="text-fild-block inputstyle"> <?php echo $form->labelEx($model,'no_of_seats'); ?> 
  <?php echo $form->textField($model,'no_of_seats',array()); ?>
  <?php echo $form->error($model,'no_of_seats'); ?>
  </div>
  </div>
  <div class="text-fild-bg-block">
  <div class="text-fild-block inputstyle"> 
  <?php echo $form->labelEx($model,Yii::t('app','maximum_capacity')); ?>
  <?php echo $form->textField($model,'maximum_capacity',array()); ?> 
  <?php echo $form->error($model,'maximum_capacity'); ?> 
  </div>
  <div class="text-fild-block inputstyle">
  <?php echo $form->labelEx($model,'vehicle_type'); ?>
  <?php echo $form->dropDownList($model,'vehicle_type',array('1'=>Yii::t('app','Contract'),'2'=>Yii::t('app','Ownership')),array('prompt'=>Yii::t('app','Select'))); ?> 
  <?php echo $form->error($model,'vehicle_type'); ?> 
  </div>
  <div class="text-fild-block inputstyle"> 
  <?php echo $form->labelEx($model,'address'); ?>
  <?php echo $form->textField($model,'address',array()); ?> 
  <?php echo $form->error($model,'address'); ?> 
  </div>
  </div>
  <div class="text-fild-bg-block">
  <div class="text-fild-block inputstyle"> 
  <?php echo $form->labelEx($model,'city'); ?>
  <?php echo $form->textField($model,'city',array()); ?> 
  <?php echo $form->error($model,'city'); ?>
  </div>
  <div class="text-fild-block inputstyle"> 
  <?php echo $form->labelEx($model,'state'); ?>
  <?php echo $form->textField($model,'state',array()); ?> 
  <?php echo $form->error($model,'state'); ?>
  </div>
  <div class="text-fild-block inputstyle"> 
  <?php echo $form->labelEx($model,'phone'); ?> 
  <?php echo $form->textField($model,'phone',array()); ?> 
  <?php echo $form->error($model,'phone'); ?>
  </div>
  </div>
  <div class="text-fild-bg-block">
  <div class="text-fild-block inputstyle"> 
  <?php echo $form->labelEx($model,'insurance'); ?>
  <?php echo $form->textField($model,'insurance',array()); ?>
  <?php echo $form->error($model,'insurance'); ?> 
  </div>
  <div class="text-fild-block inputstyle"> 
  <?php echo $form->labelEx($model,'tax_remitted'); ?> 
  <?php echo $form->textField($model,'tax_remitted',array()); ?> 
  <?php echo $form->error($model,'tax_remitted'); ?> 
  </div>
  <div class="text-fild-block inputstyle">
  <?php echo $form->labelEx($model,'permit'); ?>
  <?php echo $form->textField($model,'permit',array()); ?>
  <?php echo $form->error($model,'permit'); ?> 
  </div>
  </div>
  </div>
  </div>
  <div class="row buttons"> <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?> </div>
  <?php $this->endWidget(); ?>
  </div>
  <!-- form -->