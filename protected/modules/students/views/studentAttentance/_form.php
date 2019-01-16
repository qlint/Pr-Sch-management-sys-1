<div class="formCon">
<div class="formConInner" style="width:50%; height:auto; min-height:150px;">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student-attentance-form',
	//'enableAjaxValidation'=>true,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php
		 //echo $form->labelEx($model,'student_id'); ?>
		<?php echo $form->hiddenField($model,'student_id',array('value'=>$emp_id)); ?>
		<?php echo $form->error($model,'student_id'); ?>
	</div>

    <div class="row">
		<?php
                /*
                $smodel= Students::model()->findByPk($emp_id);
                $batch_id= $smodel->batch_id;
                if($smodel->batch_id == 0)
                {
                    $last_batch = BatchStudents::model()->findByAttributes(array('student_id'=>$smodel->id,'result_status'=>2));                    
                    $model->batch_id= $last_batch->batch_id; 
                }
                else
                {
                   $model->batch_id= $smodel->batch_id;
                }
                 * 
                 */
            
                $batches    = 	BatchStudents::model()->studentBatch($emp_id); 
                if($batches){
                    foreach($batches as $batch){
                            $batch_list[$batch->id]	= ucfirst($batch->name);
                    }
                }
                if(count($batches) == 1){
                        $batch    	= 	BatchStudents::model()->findByAttributes(array('student_id'=>$emp_id, 'result_status'=>0));
                        $bid 		=  $batch->batch_id;		
                }
                elseif(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL){                   
                        $bid 		=  $_REQUEST['bid'];	
                }
                elseif(count($batches)>1){                     
                        $batch    	= 	BatchStudents::model()->findByAttributes(array('student_id'=>$emp_id, 'result_status'=>0, 'batch_id'=>$batches[0]->id));
                        $bid 		=  $batch->batch_id;
                }
                               
                $model->batch_id= $bid;
                
               
		 //echo $form->labelEx($model,'student_id'); ?>
		<?php echo $form->hiddenField($model,'batch_id',array('value'=>$batch_id)); ?>
		<?php echo $form->error($model,'batch_id'); ?>
	</div>
	<div class="row">
		<?php //echo $form->labelEx($model,'date'); ?>
		<?php echo $form->hiddenField($model,'date',array('value'=>$year.'-'.$month.'-'.$day)); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'reason'); ?>
		<?php echo $form->textField($model,'reason',array('size'=>60,'maxlength'=>120)); ?>
		<?php echo $form->error($model,'reason'); ?>
	</div>
	<br />
    <div class="row">
		<?php echo $form->labelEx($model,'leave_type_id');
		$leave_type=CHtml::listData(StudentLeaveTypes::model()->findAllByAttributes(array('status'=>1)),'id','name');
		?>
		<?php echo $form->dropDownList($model,'leave_type_id',$leave_type,array('empty' => Yii::t('app','Select Leave Type'))); ?>
		<?php echo $form->error($model,'leave_type_id'); ?>
	</div>

	<div class="row buttons">
		<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
         <?php echo CHtml::ajaxSubmitButton(Yii::t('app','Save'),CHtml::normalizeUrl(array('StudentAttentance/Addnew','render'=>false)),array('dataType'=>'json','success'=>'js: function(data) {
			 			if (data.status == "success")
                		{
							$("#td'.$day.$emp_id.'").text("");
							$("#jobDialog123'.$day.$emp_id.'").html("<span class=\"abs\"></span>","");
							$("#jobDialog'.$day.$emp_id.'").dialog("close");
							window.location.reload();
						}
						 else{
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
                    
                    }'),array('id'=>'closeJobDialog'.$day.$emp_id,'name'=>'save')); ?>
	</div>

<?php $this->endWidget(); ?>
</div>
</div><!-- form -->