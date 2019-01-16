<div class="formCon">
<div class="formConInner" style="width:250px">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'attendance-settings-form',
	'enableAjaxValidation'=>false,
)); ?>

	
	<div class="row">
		<?php echo $form->labelEx($model,Yii::t('app','Attendance Type')); ?>
		<?php echo $form->dropDownList($model,'config_value',array(1=>Yii::t('app','Daily Attendance'),2=>Yii::t('app','Subjectwise Attendance'))); ?>
		<?php echo $form->error($model,'config_value'); ?>
	</div>

	<div class="row buttons">
		<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
         <?php echo CHtml::ajaxSubmitButton(Yii::t('app','Save'),CHtml::normalizeUrl(array('/attendanceSettings/changeType','render'=>false)),array('dataType'=>'json','success'=>'js: function(data) {
				    if(data.status == "success")
				    {
						$("#changeType_dialog").dialog("close");
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
                    }'),array('id'=>'closeJobDialog','name'=>'save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
</div>