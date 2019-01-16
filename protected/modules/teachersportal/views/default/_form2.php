<style type="text/css">
.ui-widget-content{ height:auto !important}
</style>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student-attentance-form',
	'enableAjaxValidation'=>false,
)); ?>
	<?php echo $form->errorSummary($model); ?>

	<?php if(isset($_REQUEST['id']) and $_REQUEST['id']){ ?>
		<input type="hidden" value="<?php echo $_REQUEST['id']; ?>" name="id" />
    <?php } ?>    
    <?php 
	
		echo $form->hiddenField($model,'weekday_id',array('value'=>$_REQUEST['weekday_id'])); 
		echo $form->hiddenField($model,'student_id',array('value'=>$_REQUEST['student_id'])); 
		echo $form->hiddenField($model,'timetable_id',array('value'=>$_REQUEST['timetable_id'])); 
		echo $form->hiddenField($model,'subject_id',array('value'=>$_REQUEST['subject_id'])); 
		echo $form->hiddenField($model,'date',array('value'=>$_REQUEST['date'])); 
		
	?>
    
    <div class="row">
    	<div class="col-md-12">
        <div class="model-popup-form">
    			<?php echo $form->labelEx($model,'leavetype_id');?>
                <?php echo $form->dropDownList($model,'leavetype_id',CHtml::listData(StudentLeaveTypes::model()->findAllByAttributes(array('status'=>1)),'id','name'),array('class'=>'form-control','empty'=>'Select Leave Type')); ?>
        		<?php echo $form->error($model,'leavetype_id'); ?>
        
        </div>
        </div>        
    </div>

	<div class="row">
        	<div class="col-md-12">
        <div class="model-popup-form">
		<?php echo $form->labelEx($model,Yii::t('app','reason')); ?>
		<?php echo $form->textField($model,'reason',array('class'=>'form-control','maxlength'=>120)); ?>
		<?php echo $form->error($model,'reason'); ?>
	</div>
   </div>
    </div>

	<div class="row buttons">
    <div class="col-md-12">
   <div class="model-popup-form-btn">
		<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
         <?php echo CHtml::ajaxSubmitButton(Yii::t('app','Save'),CHtml::normalizeUrl(array('/teachersportal/default/subjectwise','render'=>false)),array('dataType'=>'json','success'=>'js: function(data) {
			 if (data.status == "success")
             {
				window.location.reload();
			 }
			 else
			 {
				 var errors = JSON.parse(data.errors);
							  $(".errorMessage").remove();
							  $.each(errors, function(index, value){
							   var id  = index + "_em_";
							   var error = $("<div class=\"errorMessage\" />");
							   error.attr({
								id:id,
							   });
							   error.html(value[0]);
							   error.insertAfter($("#"+ index));
							  });
			 }
			 }'),array('id'=>'closeJobDialog'.$day.$emp_id,'name'=>'save','class'=>'btn model-save-btn')); ?>
	</div>
    </div>
    </div>

<?php $this->endWidget(); ?>
