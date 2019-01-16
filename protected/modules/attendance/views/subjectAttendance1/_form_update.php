<style type="text/css">
#msg
{
	color:#F00;
}
</style>
<?php
$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
if(Yii::app()->user->year)
{
	$ac_year = Yii::app()->user->year;
}
else
{
	$ac_year = $current_academic_yr->config_value;
}
$is_edit = PreviousYearSettings::model()->findByAttributes(array('id'=>3));
$is_delete = PreviousYearSettings::model()->findByAttributes(array('id'=>4));
?>

<div class="formCon">
<div class="formConInner" style="width:99%">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student-attentance-form',
	//'enableAjaxValidation'=>true,
)); ?>

	<?php echo $form->errorSummary($model); ?>
   <div class="row">
		<?php
		 //echo $form->labelEx($model,'student_id'); ?>
		<?php echo $form->hiddenField($model,'student_id',array('value'=>$student_id)); ?>
        <?php echo $form->hiddenField($model,'subject_id',array('value'=>$subject_id)); ?>
        <?php echo $form->hiddenField($model,'timing_id',array('value'=>$timing_id)); ?>
        <?php echo $form->hiddenField($model,'date',array('value'=>$date)); ?>
        <?php 
			$batch = Subjects::model()->findByPk($subject_id);
			echo $form->hiddenField($model,'batch_id',array('value'=>$batch->id)); 
		?>
		
	</div>
    

	<div class="row">
		<?php echo $form->labelEx($model,'reason'); ?>
		<?php 
		if(($ac_year == $current_academic_yr->config_value) or ($ac_year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
		{
			echo $form->textField($model,'reason',array('size'=>60,'maxlength'=>120)); 
		}
		elseif($ac_year != $current_academic_yr->config_value and $is_edit->settings_value==0)
		{
			echo $form->textField($model,'reason',array('size'=>60,'maxlength'=>120,'disabled'=>true)); 
		}
		?>
		<?php echo $form->error($model,'reason'); ?>
	</div>
    <div id="msg"></div>
	<br /><br />
	<div class="row buttons">
		<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
         <?php 
		 if(($ac_year == $current_academic_yr->config_value) or ($ac_year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
		 {
		 
		 echo CHtml::ajaxSubmitButton(Yii::t('app','Save'),CHtml::normalizeUrl(array('/attendance/subjectAttendance/editLeave','render'=>false)),array('dataType'=>'json','success'=>'js: function(data) {
			  if(data.status == "success")
					{
						$("#td'.$timing_id.$student_id.'").text("");
						$("#jobDialog123'.$timing_id.$student_id.'").html("<span class=\"abs\"></span>","");
						$("#jobDialog'.$timing_id.$student_id.'").dialog("close");
						window.location.reload();
					}
					else{
						var errors	= JSON.parse(data.errors);
						$(".errorMessage").remove();
						$.each(errors, function(index, value){
							var id		= index + "_em_";
							var error	= $("<div class=\"errorMessage\" />");
							error.attr({
								id:id,
							});
							error.html(value[0]);
							error.insertAfter($("#"+ index));
						});
					}
                    }'),array('id'=>'closeJobDialog'.$timing_id.$student_id,'name'=>'save')); 
		}
		?>
      <?php 
	  if(($ac_year == $current_academic_yr->config_value) or ($ac_year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
	  {
	  echo CHtml::ajaxSubmitButton(Yii::t('app','Delete'),CHtml::normalizeUrl(array('/attendance/subjectAttendance/deleteLeave','render'=>false)),array('success'=>'js: function(data) {
		                $("#td'.$timing_id.$student_id.'").text(" ");
		                $("#jobDialog'.$timing_id.$student_id.'").dialog("close");
						window.location.reload();
                    }'),array('confirm'=>Yii::t('app',"Are you sure to want to delete this reason?"),'id'=>'closeJobDialog1'.$timing_id.$student_id,'name'=>'delete')); 
					
	  }
	  ?>
	</div>

<?php $this->endWidget(); ?>
</div>
</div><!-- form -->