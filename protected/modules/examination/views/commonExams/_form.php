<style>
.formCon input[type="text"], input[type="password"], textArea, select{
	border-radius: 0px !important;
	border:1px #c2cfd8 solid;
	padding:7px 3px;
	background:#fff;
  	box-shadow:none !important;
	width:100%;
}

.bg_white .head{
	padding: 0px 0px 0px 0px;
    border-bottom: 1px solid #c2cfd8;
    background-color: white;
}
.bg_white h4, .bg_white select{
	padding:0px;
	margin:0px;
}
.bg_white h4{
	padding:8px;
}
/*.batch-block{
	width:100%;
	box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
	border:1px solid #c2cfd8;
	padding:5px;
	margin-top:5px;
	overflow:hidden;
}*/
.batch-block {
    width: 100%;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    border: 1px solid #c7c7d6;
    padding: 7px 11px 6px 11px;
    margin-top: 5px;
    overflow: hidden;
    background-color: #f1f1f9;
}
.batch-block .move_action{
	float:right;
	font-size:20px;
}
.batch-block .move_action .fa-arrow-right{
	color: #f38108;
	font-size:12px;
}
.action-cion .fa{
    color: #77798e;
    font-size: 11px;
    line-height: 17px;	
}
.batch-block .move_action .fa-times{
	color:#E81C30;
	font-size:12px;
}
</style>
<?php
	$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
	if(Yii::app()->user->year){
		$year = Yii::app()->user->year;
	}
	else{
		$year = $current_academic_yr->config_value;
	}
	
	$settings	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
	if($settings != NULL){
		$date			= $settings->dateformat;
		$displaydate	= $settings->displaydate;
	}
	else{
		$date 			= 'dd-mm-yy';
		$displaydate	= 'd M Y';
	}
?>
<?php
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'students-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); 	
?>	
    <p class="note"><?php echo Yii::t('app','Fields with'); ?><span class="required">*</span><?php echo Yii::t('app','are required.'); ?></p>    
    <?php //echo $form->errorSummary($model);?>
    <div class="formCon">
        <div class="formConInner">            
            <h3><?php echo Yii::t('app','Exam Details'); ?> </h3> 
            <div class="txtfld-col">
                <?php echo $form->labelEx($model,'name'); ?>
                <?php echo $form->textField($model,'name',array('size'=>30,'maxlength'=>255)); ?> 
                  <?php echo $form->error($model,'name'); ?>                   
            </div>
            
            <div class="txtfld-col">
				<?php echo $form->labelEx($model,'exam_type'); ?>
                <?php echo $form->dropDownList($model,'exam_type',array('Marks'=>Yii::t('app','Marks'),'Grades'=>Yii::t('app','Grades'),'Marks And Grades'=>Yii::t('app','Marks And Grades'))); ?>
                <?php echo $form->error($model,'exam_type'); ?>
            </div>
            
            <div class="txtfld-col">
                <?php echo $form->labelEx($model,'exam_date'); ?>                
                <?php
					if($model->exam_date!=NULL)
						$model->exam_date	= date($displaydate, strtotime($model->exam_date));
						
					$this->widget('zii.widgets.jui.CJuiDatePicker', array(                        
						'model'=>$model,
						'attribute'=>'exam_date',
						// additional javascript options for the date picker plugin
						'options'=>array(
							'showAnim'=>'fold',
							'dateFormat'=>$date,
							'changeMonth'=> true,
							'changeYear'=>true,
							'yearRange'=>'1900:'.(date('Y')+5)
						),
						'htmlOptions'=>array(								
							'readonly'=>true
						),
					));
				?>
                 <?php echo $form->error($model,'exam_date'); ?>
            </div>
            
			<div class="txtfld-col">
				<?php echo $form->checkBox($model,'is_published'); ?>
				<?php echo $form->labelEx($model,'is_published'); ?>
                 <?php echo $form->error($model,'is_published'); ?>
           	</div>
            
            <div class="txtfld-col">
				<?php echo $form->checkBox($model,'result_published'); ?>
				<?php echo $form->labelEx($model,'result_published'); ?>
                 <?php echo $form->error($model,'result_published'); ?>
           	</div>
            
            <div class="clear"></div>
            <h3><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></h3> 
            
            <table width="100%">
            	<tr>
                	<td width="48%" valign="top">
                    	<div class="bg_white bg_white-box">
                        	<div class="head">
								<?php
                                    $data		= array();
                                    $criteria	= new CDbCriteria;
                                    $criteria->condition	= '`academic_yr_id`=:year AND `is_deleted`=:delete';
                                    $criteria->params		= array(':year'=>$year, ':delete'=>0);
                                    $criteria->order		= '`course_name` ASC';
                                    $courses				= Courses::model()->findAll($criteria);
                                    if(count($courses)>0)
                                        $data					= CHtml::listData($courses, 'id', 'course_name');
                                    echo CHtml::dropDownList('course_id', '', $data, array('prompt'=>Yii::t('app', 'Select Course'),'encode'=>false));
                                ?>
                            </div>                            
                            <div id="batches"></div>
                        </div>
                    </td>
                    <td class="action-cion" align="center">
                    	<i class="fa fa-arrow-right" aria-hidden="true" style="font-size:12px;"></i>
                    </td>
                    <td width="48%" valign="top">
                    	<div class="bg_white bg_white-box">
                        	<div class="head">
                        		<h4><?php echo Yii::t('app', 'Selected ').Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></h4>
                            </div>
                            <div id="selected-batches">
                            	<?php
                                	if(isset($model->batches) and count($model->batches)>0){
										$batch_ids	= $model->batches;
										$criteria	= new CDbCriteria;
										$criteria->addInCondition('`id`', $batch_ids);
										$criteria->order	= 'FIELD(`id`, '.implode(',', $batch_ids).')';
										$batches	= Batches::model()->findAll($criteria);
										
										$this->renderPartial('_batch', array('model'=>$model, 'batches'=>$batches));
									}
								?>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            
            <div class="clear"></div>            
        </div>
    </div>
          
    <div class="clear"></div>
    <div style="padding:0px 0 0 0px; text-align:left">
    	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create Exam') : Yii::t('app','Update'),array('id'=>'submit_button_form','class'=>'formbut')); ?>
    </div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
$('select#course_id').change(function(e) {
	var course_id	= $(this).val();
	$.ajax({
		url:'<?php echo Yii::app()->createUrl('/examination/commonExams/batches');?>',
		data:{course_id:course_id},
		dataType:"json",
		beforeSend: function(){
		},
		success:function(response){
			if(response.status=="success"){
				$('#batches').html(response.data);
				move_action();
			}
		},
		error: function(){
		}
	});
});

var move_action	= function(){
	$('.move_action').unbind('click').click(function(e) {
		var batch	= $(this).closest('.batch-block');
		if($(this).closest('#batches').length>0){			
			var batch_id	= batch.data('id');
			if($('#selected-batches').find('.batch-block[data-id="' + batch_id + '"]').length==0){
				var clone	= batch.clone();
				var hidden	= $('<input name="batch_id[]" type="hidden" value="' + batch_id + '" />');
				clone.prepend(hidden);
				clone.find('.move_action').attr('title', '<?php echo Yii::t('app', 'Remove');?>').html('<i class="fa fa-times" aria-hidden="true"></i>');
				$('#selected-batches').append(clone);
				move_action();
			}
			else{
				$('#selected-batches').find('.batch-block[data-id="' + batch_id + '"]').effect('highlight', 1000);
			}
		}
		else if($(this).closest('#selected-batches').length>0){
			batch.remove();
		}
    });
};

$(document).ready(function(e) {
    move_action();
});
</script>