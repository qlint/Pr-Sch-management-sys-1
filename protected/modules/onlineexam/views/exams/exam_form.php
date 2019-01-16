<style>
td{
	padding-bottom:10px !important;
}
</style>
<h3><?php echo Yii::t('app', 'Fields with');?> <span class="required">*</span> <?php echo Yii::t('app', 'are required');?>.</h3>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'exam-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php echo $form->errorSummary($model); ?>
<div class="txtfld-col-bg">
<div class="qurdn-not qurdn-not2">
    <div class="head">
        <b><h2><?php echo Yii::t('app','Note').' :'; ?></h2></b>
    </div>
        <div class="not-bullet">
            <ul>
                <li><?php echo Yii::t('app','The start time and end time are to be defined as a range during which a student can attend the exam for the mentioned duration.'); ?></li>
                <li><?php echo Yii::t('app','Duration is not the difference between start and end time but the total time a student can take to complete the exam within the given time range..'); ?></li>
                <li><?php echo Yii::t('app','A student will only be able to attend an exam if he/she logs in within the given time range with full duration.'); ?></li>
            </ul>
        </div>
</div>
<div class="txtfld-col">
<?php echo $form->labelEx($model,'name'); ?>
<?php echo $form->textField($model,'name', array('placeholder'=>Yii::t('app', 'Name'))); ?>    
</div>
<div class="txtfld-col">
<?php echo $form->labelEx($model,'course'); ?>	
<?php  
if(Yii::app()->controller->action->id=='update' && isset($model->batch_id) && $model->batch_id!=NULL)
{
    $batches= Batches::model()->findByPk($model->batch_id);
    if($batches!=NULL)
    {
        $model->course= $batches->course_id;
        echo $model->course;
    }
}

$data 		= CHtml::listData(Courses::model()->findAll('is_deleted=:x',array(':x'=>'0'),array('order'=>'course_name DESC')),'id','course_name');
echo $form->dropDownList(
                        $model,
                        'course',
                        $data,
                        array(
                                'prompt'=> Yii::t('app', 'Select Course'),
								'encode'=>false,
                                'ajax' => array(
                                        'type'=>'POST',
                                        'url'=>CController::createUrl('/onlineexam/exams/batches'),
                                        'update'=>'#batch_id',
                                        'data'=>'js:{course:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',
                                )
                        )
                ); 
?>             
</div>
<div class="txtfld-col">
 <?php echo $form->labelEx($model,'batch_id'); ?>		
                <?php 
                $data1	= NULL;
                $data1	=	CHtml::listData(Batches::model()->findAll('is_active=:x AND is_deleted=:y AND course_id=:z',array(':x'=>'1',':y'=>0,':z'=>$model->course),array('order'=>'name DESC')),'id','name');
                echo CHtml::activeDropDownList(
					$model,
					'batch_id',
					$data1,
					array(
						'prompt'=> Yii::t('app', 'Select Batch'),
						'id'=>'batch_id'
					)
				);
                ?>                  
</div>
                   
<div class="txtfld-col time-block">
			<?php echo $form->labelEx($model,'start_time'); ?>	
            <?php 
				$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
				if($settings!=NULL){
					$date=$settings->dateformat;
				}
				else{
					$date = 'dd-mm-yy';	
				}
				if(isset($model->start_time) && $model->start_time!='')
				{
					$model->start_time = date("Y-m-d H:i", strtotime($model->start_time));
				}
					$this->widget('application.extensions.timepicker.timepicker', array(
					'model' => $model,
					'options'=>array(
					'dateFormat'=>$date,																															
					),
					'name'=>'start_time',
					//'tabularLevel' => "[1]",
					'id'=>'start_time'
				)); ?>  
                                     
</div>
<div class="txtfld-col time-block">
 			<?php echo $form->labelEx($model,'end_time'); ?>	
                <?php 
                    if(isset($model->end_time) && $model->end_time!='')
                    {
                        $model->end_time = date("Y-m-d H:i", strtotime($model->end_time));
                    }
					$this->widget('application.extensions.timepicker.timepicker', array(
						'model' => $model,
						'options'=>array(
						'dateFormat'=>$date,																															
						),
						'name'=>'end_time',
						//'tabularLevel' => "[1]",
						'id'=>'end_time'
					)); ?> 
</div>
<div class="txtfld-col">
	<?php echo $form->labelEx($model,'duration'); ?>
    <?php echo $form->textField($model,'duration', array('placeholder'=>Yii::t('app', 'Duration in Minutes'))); ?>                         
</div>
<div class="txtfld-col">
 <?php echo $form->labelEx($model,'choice_limit'); ?>
 <?php echo $form->textField($model,'choice_limit', array('placeholder'=>Yii::t('app', 'Choice Limit'))); ?>                        
</div>

</div>

<div class="txtfld-col-btn">
    <div class="txtfld-col-btn-block">
    	<?php 
        if(Yii::app()->controller->action->id=='update')
        {
            $submit= Yii::t('app','Submit');
        }
        else
            $submit= Yii::t('app','Add Questions');
        
        echo CHtml::submitButton($submit, array('class'=>'')); ?>
	</div>                       
</div>
<?php $this->endWidget(); ?>
