<div class="formCon">
<div class="formConInner" style="width:50%">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student-attentance-form',
	
)); ?>

	<p class="note"><?php echo Yii::t('app','Specify Reason') ?></p>

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
		<?php echo $form->textField($model,'reason',array('size'=>60,'maxlength'=>120)); ?>
		<?php echo $form->error($model,'reason'); ?>
	</div>
<br />
	<div class="row buttons">
		<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
         <?php echo CHtml::ajaxSubmitButton(Yii::t('app','Save'),CHtml::normalizeUrl(array('/attendance/subjectAttendance/addnew','render'=>false)),array('dataType'=>'json','success'=>'js: function(data) {
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
                    }'),array('id'=>'closeJobDialog'.$timing_id.$student_id,'name'=>'save')); ?>
	</div>

<?php $this->endWidget(); ?>
</div>
</div><!-- form -->