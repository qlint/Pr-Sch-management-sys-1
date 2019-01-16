<!--
 * Ajax Crud Administration Form
 * Subjects *
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.3
 -->
 <style type="text/css">
.ui-dialog {
	background: #fff !important;
	color: #000;
}
</style>
 <?php

if (!$model->isNewRecord)
{
  $criteria = new CDbCriteria;
  $criteria->condition='batch_id=:bat_id';
	$criteria->params=array(':bat_id'=>$model->batch_id);
	$criteria->compare('is_deleted',0); 
}  
?>
 <?php $data1 = CHtml::listData(Subjects::model()->findAll($criteria),'id','name') ?>
<div id="subjects_form_con" class="client-val-form" style="width:350px">
  <?php if ($model->isNewRecord) : ?>
  <h3 id="create_header"> <?php echo Yii::t('app','Add New Co-Scholastic Skill');?></h3>
  <?php  elseif (!$model->isNewRecord):  ?>
  <h3 id="update_header"><?php echo Yii::t('app','Update Co-Scholastic Skill : ').$model->skill;?></h3>
  <?php   endif;  ?>
  <?php $val_error_msg = Yii::t('app','Error.Skill was not saved.');
    $val_success_message = ($model->isNewRecord) ?
            Yii::t('app','Skill was added successfully.') :
            Yii::t('app','Skill  was updated successfully.');
  ?>
  
  <div id="success-note" class="notification success " style="display:none;"> 
            <a href="#" class="close"> <img src="<?php echo Yii::app()->request->baseUrl.'/js_plugins/ajaxform/images/icons/cross_grey_small.png';  ?>" title="Close this notification" alt="close"/></a>                
            <div>
              <?php   echo $val_success_message;  ?>
            </div>
  </div>
  <div id="error-note" class="notification errorshow " style="display:none;">
        <a href="#" class="close"> <img src="<?php echo Yii::app()->request->baseUrl.'/js_plugins/ajaxform/images/icons/cross_grey_small.png';  ?>" title="Close this notification" alt="close"/></a>                
        <div>
          <?php   echo $val_error_msg;  ?>
        </div>
  </div>
  
  <div id="ajax-form"  class='form'>
    <?php   $formId='co_scholastic-form';
            $actionUrl = ($model->isNewRecord)?CController::createUrl('coScholastic/ajax_create') :CController::createUrl('coScholastic/ajax_update');
                            
                                                                 


    $form=$this->beginWidget('CActiveForm', array(
            'id'=>'co_scholastic-form',   
            'action' => $actionUrl,
            'enableAjaxValidation'=>true,
            'enableClientValidation'=>true,
			
            'focus'=>array($model,'skill'),
            'errorMessageCssClass' => 'input-notification-error  error-simple png_bg',
            'clientOptions'=>array('validateOnSubmit'=>true,
                                        'validateOnType'=>true,                                       
                                        'errorCssClass' => 'err',
                                        'successCssClass' => 'suc',
                                        'afterValidate' => 'js:function(form,data,hasError){ $.js_afterValidate(form,data,hasError);  }',
                                         'errorCssClass' => 'err',
                                        'successCssClass' => 'suc',
                                        'afterValidateAttribute' => 'js:function(form, attribute, data, hasError)
                                                                    {
                                                                        $.js_afterValidateAttribute(form, attribute, data, hasError);
                                                                    }'
                            ),
        ));

     ?>
      
    <p class="note"><?php echo Yii::t('app','Fields with');?><span class="required">*</span><?php echo Yii::t('app','are required'); ?>.</p>
    <?php
	if(isset($batch_id) and $batch_id==0)
	{
	?>
            <div class="row"> <?php echo $form->labelEx($model,'batch_id', array('style'=>'color:#000000')); ?>
            <?php 
			$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
                        if(Yii::app()->user->year)
			{
				$yr = Yii::app()->user->year;
				//echo Yii::app()->user->year;
			}
			else
			{
				$yr = $current_academic_yr->config_value;
			}
                        $criteria  = new CDbCriteria;
                        $criteria ->compare('is_deleted',0);
                        $criteria->condition = 'is_active=:is_active AND academic_yr_id=:yr';
                        $criteria->params = array(':is_active'=>1,':yr'=>$yr);  
			echo $form->dropDownList($model, 'batch_id', CHtml::listData(Batches::model()->findAll($criteria),'id','coursename'),array('prompt'=>Yii::t('app','Select')));
			?>
      <!--<span id="success-Subjects_name" class="hid input-notification-success  success png_bg right" style="float:right; margin:8px 122px 0px 0px;"></span>-->
      <div> <small></small> </div>
      <?php echo $form->error($model,'batch_id'); ?> </div>
    <?php
	}
        else
        {
            $model->batch_id= $batch_id;
            echo $form->hiddenField($model,'batch_id');
        }
	?>
    
        <div class="row"> 
            <?php echo $form->labelEx($model,'skill', array('style'=>'color:#000000')); ?> 
            <?php echo $form->textField($model,'skill',array('maxlength'=>'100', 'encode'=> false)); ?>     
            <div> <small></small> </div>
            <?php echo $form->error($model,'skill'); ?> 
        </div>
    
        <div class="row"> 
            <?php echo $form->labelEx($model,'description', array('style'=>'color:#000000')); ?> 
            <?php echo $form->textArea($model,'description',array('encode'=> false)); ?>       
            <div> <small></small> </div>
            <?php echo $form->error($model,'description'); ?> 
        </div>
                
    
              
    <input type="hidden" name="YII_CSRF_TOKEN"
           value="<?php echo Yii::app()->request->csrfToken; ?>"/>
    <?php  if (!$model->isNewRecord): ?>
            <input type="hidden" name="update_id"
           value=" <?php echo $model->id; ?>"/>
    <?php endif; ?>
            
    <div class="row buttons" style="width:30%">
        <?php   echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Submit') : Yii::t('app','Save'),array('class' =>'formbut')); ?>        
    </div>
            
    <?php  $this->endWidget(); ?>
  </div>
  <!-- form --> 
  
</div>
<script type="text/javascript">

    //Close button:

    $(".close").click(
            function () {
                $(this).parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
                    $(this).slideUp(600);
                });
                return false;
            }
    );


</script> 
