<div class="form-group">
    <p class="note"><?php echo Yii::t("app",'Fields with');?> <span class="required">*</span><?php echo Yii::t("app", 'are required.');?></p>
    <?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'online-exam-form',
	'enableAjaxValidation'=>false,
        )); ?>
    <?php //echo $form->errorSummary($model); ?>
        <div class="row">
            <div class="col-sm-4 col-4-reqst">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'name'); ?>
                    <?php echo $form->textField($model,'name', array('class'=>'form-control','placeholder'=>Yii::t('app', 'Name'))); ?>
                    <?php echo $form->error($model,'name'); ?>  
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-4 col-4-reqst">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'start_time'); ?>
                    <?php 
                    if(isset($model->start_time) && $model->start_time!='')
                    {
                        $model->start_time = date("Y-m-d H:i", strtotime($model->start_time));
                    }
                    echo $form->textField($model,'start_time', array('class'=>'form-control','id'=>'starts_at')); ?>                    
                    <?php echo $form->error($model,'start_time'); ?>  
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-4 col-4-reqst">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'end_time'); ?>
                    <?php 
                    if(isset($model->end_time) && $model->end_time!='')
                    {
                        $model->end_time = date("Y-m-d H:i", strtotime($model->end_time));
                    }
                    echo $form->textField($model,'end_time', array('class'=>'form-control','id'=>'expires_at')); ?>                    
                    <?php echo $form->error($model,'end_time'); ?> 
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-4 col-4-reqst">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'duration'); ?>
                    <?php echo $form->textField($model,'duration', array('class'=>'form-control','placeholder'=>Yii::t('app', 'Duration in Minutes'))); ?>                    
                    <?php echo $form->error($model,'duration'); ?> 
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-4 col-4-reqst">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'choice_limit'); ?>
                    <?php echo $form->textField($model,'choice_limit', array('class'=>'form-control','placeholder'=>Yii::t('app', 'Choice Limit'))); ?>                    
                    <?php echo $form->error($model,'choice_limit'); ?> 
                </div>
            </div>
        </div>
    <br>
        <?php 
        if(isset($_REQUEST['bid']) && $_REQUEST['bid']!=NULL)
        {
            $model->batch_id    =   $_REQUEST['bid'];
        }
        echo $form->hiddenField($model,'batch_id', array('class'=>'form-control')); ?>   
        <div class="buttons">
		<?php echo CHtml::submitButton(Yii::t("app",'Submit'),array('class'=>'btn btn-danger')); ?>
        
	</div>
    
    <?php $this->endWidget(); ?>
</div>





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
</script>