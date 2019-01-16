<style>
.ui-widget-content{ height:auto !important}	
}
</style>
            <?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'timetable-entries-form',	
			)); ?>
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
            
            <?php $data =StudentLeaveTypes::model()->findAll();
                $leave_type=CHtml::listData(StudentLeaveTypes::model()->findAllByAttributes(array('status'=>1)),'id','name');?>
            <div class="row">
                <div class="col-md-12">
                    <div class="model-popup-form">
						<?php echo $form->labelEx($model,'leavetype_id'); ?>
                        <?php echo $form->dropDownList($model,'leavetype_id',$leave_type,array('class'=>'form-control','empty' => Yii::t('app','Select Leave Type'))); ?>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="model-popup-form">
						<?php echo $form->labelEx($model,'reason'); ?>
                        <?php echo $form->textArea($model,'reason',array('class'=>'form-control')); ?>
                        <?php echo $form->error($model,'reason'); ?>
                    </div>
                </div>        
            </div>
				
	<div class="row buttons">
    <div class="col-md-12">
   <div class="model-popup-form-btn">
				<?php echo CHtml::ajaxSubmitButton(
                    Yii::t('app','Save',array('class'=>'btn model-save-btn')),
                    CHtml::normalizeUrl(array('default/mark','render'=>false)),
                    array(
                        'dataType'=>'json',
                        'success'=>'js: function(data) {
                            if (data.status == "success"){
                                $("#jobDialog").dialog("close");
                                if(data.flag==1){				
                                    window.location.reload();
                                }
                            }
                            else{
                                $(".errorMessage").remove();
                                var errors	= JSON.parse(data.errors);						
                                $.each(errors, function(index, value){
                                    var err	= $("<div class=\"errorMessage\" />").text(value[0]);
                                    err.insertAfter($("#" + index));
                                });
                            }
                        }'
                    ),
                    array('id'=>'closeJobDialog','name'=>'save','class'=>'btn model-save-btn')); ?>
   </div>
   </div>
   </div>

        	<?php $this->endWidget(); ?>

                              