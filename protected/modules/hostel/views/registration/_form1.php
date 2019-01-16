<div class="form-horizontal">
  <?php
   $student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
   
   $register=Registration::model()->findByAttributes(array('student_id'=>$student->id));
  
   if($register==NULL)
   {
   ?>
  <?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'registration-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->hiddenField($model,'student_id',array('value'=>$student->id)); ?> 
  <div class="panel-body">
    <div class="form-group">
      <div class="col-sm-4 col-4-reqst">       
	   <?php echo $form->labelEx($model,Yii::t('hostel','food_preference'),array('class'=>'control-label')); ?> 
	   <?php
	   		$food_list = FoodInfo::model()->findAllByAttributes(array('is_deleted'=>0));
	   		echo $form->dropDownList($model,'food_preference',CHtml::listData($food_list,'id','food_preference'),array('prompt'=>Yii::t('app','Select'),'class'=>'form-control m15')); ?> 
	   <?php echo $form->error($model,'food_preference'); ?>
       <div id="food_preference_error" style="color:#F00"></div>
       </div>
    </div>
  </div>
  <div class="panel-footer"> <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('id'=>'submit_button_form','class'=>'btn btn-primary')); ?> </div>
  <?php $this->endWidget(); ?>
  <?php } else
{	
	?>
  <div align="center" style="padding:20px 0px;"><strong><?php echo Yii::t('app','Successfully registered for hostel facility');?></strong></div>
  <?php
}

?>
</div>
<!-- form -->
<script type="text/javascript">
$('#submit_button_form').click(function(ev) {
	var food_preference = $('#Registration_food_preference').val();
	if(food_preference == ''){
		$('#food_preference_error').html('Select Food Preference');
		return false;
	}
});
</script>