<style>
.popup-input input[type="text"], textArea, select{
	 width:100% !important;
	 box-sizing:border-box;

}
#jobDialog {
    height: auto !important;
}
</style>

<div class="popup-input">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student-attentance-form',
	//'enableAjaxValidation'=>true,
)); ?>

	<?php echo $form->errorSummary($model); ?>
		<?php echo $form->hiddenField($model,'student_id',array('value'=>$emp_id)); ?>
		<?php echo $form->hiddenField($model,'batch_id',array('value'=>$batch_id)); ?>
	<div>
		<?php //echo $form->labelEx($model,'date'); ?>
		<?php echo $form->hiddenField($model,'date',array('value'=>$year.'-'.$month.'-'.$day)); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div>
		<?php echo $form->labelEx($model,'reason'); ?>
		<?php echo $form->textField($model,'reason',array('maxlength'=>120)); ?>
		<?php echo $form->error($model,'reason'); ?>
	</div>
    <div>
		<?php echo $form->labelEx($model,'leave_type_id');
		$leave_type=CHtml::listData(StudentLeaveTypes::model()->findAllByAttributes(array('status'=>1)),'id','name');
		?>
		<?php echo $form->dropDownList($model,'leave_type_id',$leave_type,array('empty' => Yii::t('app','Select Leave Type'))); ?>
		<?php echo $form->error($model,'leave_type_id'); ?>
	</div>
	<div class="popup_btn">
		<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
         <?php echo CHtml::ajaxSubmitButton(Yii::t('app','Save'),CHtml::normalizeUrl(array('StudentAttentance/Addnew','render'=>false)),array('dataType'=>'json','success'=>'js: function(data) {
			 	$(".errorMessage").remove();
						if (data.status == "success")
					{
						$("#td'.$day.$emp_id.'").text("");
						$("#jobDialog123'.$day.$emp_id.'").html("<span class=\"abs\"></span>","");
						$("#jobDialog'.$day.$emp_id.'").dialog("close");
						window.location.reload();
					}else{
						var errors	= JSON.parse(data.errors);						
						$.each(errors, function(index, value){
							var err	= $("<div class=\"errorMessage\" />").text(value[0]);
							err.insertAfter($("#" + index));
						});
					}
                    }'),array('id'=>'closeJobDialog'.$day.$emp_id,'name'=>'save')); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->