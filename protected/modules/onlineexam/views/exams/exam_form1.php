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
                    <div>
                    	<a>** <?php echo Yii::t('app','Please read the instructions before Select');?>.</a> <a style="text-decoration:underline;" href="javascript:void(0);" id="read_t_instructions"><?php echo Yii::t('app','Click here');?></a>.
                    </div>
                    <br />
                    <div class="formCon" style="display:none;" id="t_instructions">
                        <div class="formConInner">
                            <h3 style="margin:0px; color:#396"><?php echo Yii::t('app','Read these instructions carefully before translating the application');?>.</h3>
                            <ol style="margin:10px 0px 0px; padding:0px 30px 0px 10px; line-height:17px; text-align:justify;">
                                <li><?php echo Yii::app()->params['app_name']; ?> <?php echo Yii::t('app','tranlate module helps you to convert the whole application to a language of your choice, given it is present in the list of languages available in the application (check the language dropdown to check availability of your language)');?>. <?php echo Yii::app()->params['app_name'] ?> <?php echo Yii::t('app','uses ENGLISH as the default language');?>.</li>
                                <li><?php echo Yii::t('app','Select the desired language your want to add translations for from the Language dropdown above');?>.</li>
                                <li><?php echo Yii::t('app','Once you have selcted your language Labels / Phrases in the application are listed in the table below. You can filter it using the "Filter by" dropdown above');?>.</li>
                                <li><?php echo Yii::t('app','Each label has a textbox next to it. Enter your translation in this box');?>.</li>
                                <li><?php echo Yii::t('app','Please do not translate the words inside the labels that look like {word} and :word. These are actually used for background label generations. Do not change such words as it might interfere in application processes');?>!!</li>
                                <li><?php echo Yii::t('app','After entering your translations for each label, click on the " Generate Translations" button given at the bottom of the current page to save them');?>.</li>
                                <li><?php echo Yii::t('app','Repeat steps 3-6 to complete all the translations');?>.</li>
                                <li><?php echo Yii::t('app','You can now import and export translations without having to enter them manually. Check the left side menu for these options');?></li>
                                <li><?php echo Yii::t('app','Once you have entered your translations, go to Settings - School Configurations select your language and hit save. Try refreshing the page if you do not see the translations take effect right away');?></li>
                            </ol>
                            <br />
                            <br />
                            <a href="javascript:void(0);" id="hide_t_instructions"><?php echo Yii::t('app','Hide instructions');?></a>
                        </div>
                    </div>


<div class="txtfld-col">
  <?php echo $form->labelEx($model,'start_time'); ?>	
                 <?php 
                    if(isset($model->start_time) && $model->start_time!='')
                    {
                        $model->start_time = date("Y-m-d H:i", strtotime($model->start_time));
                    }
                    echo $form->textField($model,'start_time', array('class'=>'form-control','id'=>'starts_at')); ?>                   
</div>

<div class="txtfld-col">
 <?php echo $form->labelEx($model,'end_time'); ?>	
                <?php 
                    if(isset($model->end_time) && $model->end_time!='')
                    {
                        $model->end_time = date("Y-m-d H:i", strtotime($model->end_time));
                    }
                    echo $form->textField($model,'end_time', array('class'=>'form-control','id'=>'expires_at')); ?>                         
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
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/css/datetimepicker/jquery.datetimepicker.css">
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/datetimepicker/jquery.datetimepicker.js"></script>
<script>
$('#starts_at').datetimepicker({
	mask:'9999-19-39 29:59',
	format:'Y-m-d H:i',
});

$('#expires_at').datetimepicker({
	mask:'9999-19-39 29:59',
	format:'Y-m-d H:i',
});
$("#read_t_instructions").click(function(e) {
    $("#t_instructions").slideDown();
});

$("#hide_t_instructions").click(function(e) {
    $("#t_instructions").slideUp();
});
</script>
