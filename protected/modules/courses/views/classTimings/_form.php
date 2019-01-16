
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'class-timings-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('app','Fields with'); ?><span class="required">*</span><?php echo Yii::t('app',' are required.'); ?></p>

	<?php //echo $form->errorSummary($model);?>

	<div class="row">
		<?php //echo $form->labelEx($model,'batch_id'); 
		//echo $id;?>
		<?php echo $form->hiddenField($model,'batch_id',array('value'=>$batch_id)); ?>
		<?php echo $form->error($model,'batch_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'start_time'); ?>
		
        
        <?php $this->widget('application.extensions.jui_timepicker.JTimePicker', array(
					'model'=>$model,
					'attribute'=>'start_time',
					'name'=>'ClassTimings[start_time]',
					'options'=>array(
					'showPeriod'=>true,
					'showPeriodLabels'=> true,
					'showCloseButton'=> true,       
					'closeButtonText'=> 'Done',     
					'showNowButton'=> true,        
					'nowButtonText'=> 'Now',        
					'showDeselectButton'=> true,   
					'deselectButtonText'=> 'Deselect' 
					),
				)); ?> 
        
		<?php echo $form->error($model,'start_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'end_time'); ?>
		<?php $this->widget('application.extensions.jui_timepicker.JTimePicker', array(
				'model'=>$model,
				'attribute'=>'end_time',
				'name'=>'ClassTimings[end_time]',
				'options'=>array(
				'showPeriod'=>true,
				'showPeriodLabels'=> true,
				'showCloseButton'=> true,       
				'closeButtonText'=> 'Done',     
				'showNowButton'=> true,        
				'nowButtonText'=> 'Now',        
				'showDeselectButton'=> true,   
				'deselectButtonText'=> 'Deselect' 
				),
			)); ?> 
		<?php echo $form->error($model,'end_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_break'); ?>
		<?php echo $form->checkBox($model,'is_break'); ?>
		<?php echo $form->error($model,'is_break'); ?>
	</div>

	<div class="row buttons">
		<?php  echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Save') : Yii::t('app','Save'),array('class'=>'formbut', 'id'=>'add-new-classtiming')); ?> 
         <?php 	/*if(Yii::app()->controller->action->id=='addnew')
		 		{
					 echo CHtml::ajaxSubmitButton(Yii::t('Timing','Create'),CHtml::normalizeUrl(array('classtimings/addnew','render'=>false)),array('success'=>'js: function(data) {
                     $("#jobDialog").dialog("close");
                    }'),array('id'=>'closeJobDialog'));
				}
				else
				{
					echo CHtml::ajaxSubmitButton(Yii::t('Timing','Save'),CHtml::normalizeUrl(array('classtimings/edit','render'=>false)),array('success'=>'js: function(data) {
                       $("#jobDialog1").dialog("close");
                    }'),array('id'=>'closeJobDialog'));
				} */?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
$('form#class-timings-form').unbind('submit').submit(function(){
	var datas	= $(this).serialize() + "&ajax=class-timings-form";
	$.ajax({
		url:'<?php echo Yii::app()->createUrl('/courses/classTimings/addnew');?>',
		type:"POST",
		data:datas,
		dataType:"json",
		success: function(response){
			$('.form-error').remove();
			if(response.status=="success"){				
				window.location.reload();
			}
			else{
				$.each(response, function(index, elem){
					console.log(index + ', ' + elem);
					var error	= $('<span class="form-error required" style="display:block;" />').html(elem[0]);
					error.insertAfter($('#' + index));
				});
			}
		}
	});
	return false;
});
</script>