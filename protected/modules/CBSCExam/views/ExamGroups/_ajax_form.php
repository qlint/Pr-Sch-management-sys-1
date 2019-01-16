<!--
 * Ajax Crud Administration Form
 * ExamGroups *
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.3
 -->
<style type="text/css">
.ui-datepicker{
	top: 205.063px !important; 
}
.fancybox-inner{ width:auto !important; height:auto !important;}
.client-val-form input{ width:100% !important;  -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.fancybox-wrap{
	 width:350px !important;	
}
</style> 
<div id="exam-groups_form_con" class="client-val-form" >
    <?php if ($model->isNewRecord) : ?>    <h3 id="create_header"><?php echo Yii::t('app','Create New Exam');?></h3>
    <?php  elseif (!$model->isNewRecord):  ?>    <h3 id="update_header"><?php echo Yii::t('app','Update Exam');?>  <?php  echo " : ".$model->name;  ?>  </h3>
    <?php   endif;  ?>
    <?php      $val_error_msg = Yii::t('app','Error.ExamGroups was not saved.');
    $val_success_message = ($model->isNewRecord) ?
            Yii::t('app','Exam Group was created successfully.') :
            Yii::t('app','Exam Group was updated successfully.');
  ?>

    <div id="success-note" class="notification success png_bg"
         style="display:none;">
        <a href="#" class="close"><img
                src="<?php echo Yii::app()->request->baseUrl.'/js_plugins/ajaxform/images/icons/cross_grey_small.png';  ?>"
                title="Close this notification" alt="close"/></a>
        <div>
            <?php   echo $val_success_message;  ?>        </div>
    </div>

    <div id="error-note" class="notification errorshow png_bg"
         style="display:none;">
        <a href="#" class="close"><img
                src="<?php echo Yii::app()->request->baseUrl.'/js_plugins/ajaxform/images/icons/cross_grey_small.png';  ?>"
                title="Close this notification" alt="close"/></a>
        <div>
            <?php   echo $val_error_msg;  ?>        </div>
    </div>

    <div id="ajax-form"  class='form'>
<?php   $formId='exam-groups-form';
   $actionUrl =
   ($model->isNewRecord)?CController::createUrl('examgroups/ajax_create')
                                                                 :CController::createUrl('exam/ajax_update');


    $form=$this->beginWidget('CActiveForm', array(
     'id'=>'cbsc-exam-groups-grid',
    //  'htmlOptions' => array('enctype' => 'multipart/form-data'),
         'action' => $actionUrl,
    // 'enableAjaxValidation'=>true,
      'enableClientValidation'=>true,
     'focus'=>array($model,'name'),
     'errorMessageCssClass' => 'input-notification-error  error-simple png_bg',
     'clientOptions'=>array('validateOnSubmit'=>true,
                                        'validateOnType'=>false,
                                        'afterValidate'=>'js_afterValidate',
                                        'errorCssClass' => 'err',
                                        'successCssClass' => 'suc',
                                        'afterValidate' => 'js:function(form,data,hasError){ $.js_afterValidate(form,data,hasError);  }',
                                         'errorCssClass' => 'err',
                                        'successCssClass' => 'suc',
                                        'afterValidateAttribute' => 'js:function(form, attribute, data, hasError){
                                                                                                 $.js_afterValidateAttribute(form, attribute, data, hasError);
                                                                                                                            }'
                                                                             ),
	'htmlOptions'=>array(
            'style'=>'height:auto; width: 350px;'),

));

     ?>
    <?php echo $form->errorSummary($model, '
    <div style="font-weight:bold">'.Yii::t('app','Please correct these errors:').'</div>
    ', NULL, array('class' => 'errorsum notification errorshow png_bg')); ?>    <p class="note"><?php echo Yii::t('app','Fields with'); ?><span class="required">*</span> <?php echo Yii::t('app','are required.') ;?></p>


    <div class="row">
    	<div>
            <?php echo $form->labelEx($model,'name',array('style'=>'color:#444444')); ?>
		</div>
		<span style="float:left; display:inline;">
            <?php echo $form->textField($model,'name',array('size'=>35,'maxlength'=>25)); ?>
		</span>
        <span id="success-ExamGroups_name" class="hid input-notification-success  success png_bg right" style="margin:0px;"></span>
        <div style="clear:left">
            <small></small>
        </div>
            <?php echo $form->error($model,'name'); ?>
    </div>
   

        

        <div class="row">
            <?php echo $form->labelEx($model,'exam_type',array('style'=>'color:#444444')); ?>
            <?php echo $form->dropDownList($model,'exam_type',array('Marks'=>Yii::t('app','Marks'),'Grades'=>Yii::t('app','Grades'),'Marks And Grades'=>Yii::t('app','Marks And Grades'))); ?>
        <span id="success-ExamGroups_exam_type"
              class="hid input-notification-success  success png_bg right"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'exam_type'); ?>
    </div>

        <div class="row">
        	<table width="80%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><?php echo $form->checkBox($model,'result_published'); ?></td>
                <td><?php echo $form->labelEx($model,'result_published'); ?></td>
                <td> <?php echo $form->error($model,'result_published'); ?></td>
              </tr>
            </table>

           
    </div>

        <div class="row">
        <table width="80%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td ><?php echo $form->checkBox($model,'result_published'); ?></td>
            <td><?php echo $form->labelEx($model,'result_published'); ?></td>
            <td><?php echo $form->error($model,'result_published'); ?></td>
          </tr>
        </table>
   
    </div>

       
    
    <input type="hidden" name="YII_CSRF_TOKEN"
           value="<?php echo Yii::app()->request->csrfToken; ?>"/>

    <?php  if (!$model->isNewRecord): ?>    <input type="hidden" name="update_id"
           value=" <?php echo $model->id; ?>"/>
    <?php endif; ?>
    <div class="row buttons" style="width:30%">
        <?php   echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Submit') : Yii::t('app','Save'),array('class' =>
        'formbut')); ?>    </div>

  <?php  $this->endWidget(); ?></div>
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


