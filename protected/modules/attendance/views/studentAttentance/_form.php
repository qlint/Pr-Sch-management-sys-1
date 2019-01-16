<style type="text/css">
.ui-widget input[type="submit"], button{
	margin-top:10px;
}
</style>
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
		<?php echo $form->hiddenField($model,'student_id',array('value'=>$emp_id)); ?>
		<?php echo $form->error($model,'student_id'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'date'); ?>
		<?php echo $form->hiddenField($model,'date',array('value'=>$year.'-'.$month.'-'.$day)); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>
    <?php echo $form->hiddenField($model,'batch_id',array('value'=>$_REQUEST['batch_id'])); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'reason'); ?>
		<?php echo $form->textField($model,'reason',array('size'=>60,'maxlength'=>120)); ?>
		<?php echo $form->error($model,'reason'); ?>
	</div>
     <div class="row">
		<?php echo $form->labelEx($model,'leave_type_id');
		$leave_type=CHtml::listData(StudentLeaveTypes::model()->findAllByAttributes(array('status'=>1)),'id','name');
		?>
		<?php echo $form->dropDownList($model,'leave_type_id',$leave_type,array('empty' => Yii::t('app','Select Leave Type'))); ?>
		<?php echo $form->error($model,'leave_type_id'); ?>
	</div>
<br />
	<div class="row buttons">
		<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
         <?php echo CHtml::ajaxSubmitButton(Yii::t('app','Save'),CHtml::normalizeUrl(array('StudentAttentance/Addnew','render'=>false)),array('dataType'=>'json','success'=>'js: function(data) {
			  if(data.status == "success")
					{
						$("#td'.$day.$emp_id.'").text("");
						$("#jobDialog123'.$day.$emp_id.'").html("<span class=\"abs\"></span>","");
						$("#jobDialog'.$day.$emp_id.'").dialog("close");
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
                    }'),array('id'=>'closeJobDialog'.$day.$emp_id,'name'=>'save')); ?>
	</div>

<?php $this->endWidget(); ?>
</div>
</div><!-- form -->