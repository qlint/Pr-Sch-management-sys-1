<!--
 * Ajax Crud Administration Form
 * ComplaintCategories *
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.3
 -->
 <style>
.fancybox-inner{ width:auto !important; height:auto !important;}
.client-val-form input{ width:100% !important;  -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.fancybox-wrap{
	 width:350px !important;	
}
 </style>
<div id="complaint-categories_form_con" class="client-val-form">
    <?php if ($model->isNewRecord) : ?>    <h3 id="create_header"><?php echo Yii::t('app','Create New Complaint Category');?> </h3>
    <?php  elseif (!$model->isNewRecord):  ?>    <h3 id="update_header"><?php echo Yii::t("app",'Update Complaint Category');?> </h3>
    <?php   endif;  ?>
    <?php      $val_error_msg = Yii::t("app",'Error.Complaint Category was not saved.');
    $val_success_message = ($model->isNewRecord) ?
            Yii::t("app",'Complaint Category was created successfuly.') :
            Yii::t("app",'Complaint Category  was updated successfuly.');
  ?>

    <div id="success-note" class="notification success-fancy"
         style="display:none;">
        <a href="#" class="close"><img
                src="<?php echo Yii::app()->request->baseUrl.'/js_plugins/ajaxform/images/icons/cross_grey_small.png';  ?>"
                title="Close this notification" alt="close"/></a>
        <div>
            <?php   echo $val_success_message;  ?>        </div>
    </div>

    <div id="error-note" class="notification errorshow-fancy"
         style="display:none;">
        <a href="#" class="close"><img
                src="<?php echo Yii::app()->request->baseUrl.'/js_plugins/ajaxform/images/icons/cross_grey_small.png';  ?>"
                title="Close this notification" alt="close"/></a>
        <div>
            <?php   echo $val_error_msg;  ?>        </div>
    </div>

    <div id="ajax-form"  class='form'>
<?php   $formId='complaint-categories-form';
   $actionUrl =
   ($model->isNewRecord)?CController::createUrl('complaints/ajax_create')
                                                                 :CController::createUrl('complaints/ajax_update');


    $form=$this->beginWidget('CActiveForm', array(
     'id'=>'complaint-categories-form',
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
));

     ?>
    <?php /*?><?php echo $form->errorSummary($model, '
    <div style="font-weight:bold"><?php echo Yii::t("app",Please correct these errors:);?></div>
    ', NULL, array('class' => 'errorsum notification errorshow png_bg')); ?> <?php */?>   <p class="note"><?php echo Yii::t("app",'Fields with')?> <span class="required">*</span> <?php echo Yii::t("app",'are required.');?></p>


    <div class="row">
            <?php echo $form->labelEx($model,'category'); ?>
            <?php echo $form->textField($model,'category',array('size'=>60,'maxlength'=>120)); ?>
        <span id="success-ComplaintCategories_category"
              class="hid input-notification-success  success-fancy right"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'category'); ?>
    </div>

    
    <input type="hidden" name="YII_CSRF_TOKEN"
           value="<?php echo Yii::app()->request->csrfToken; ?>"/>

    <?php  if (!$model->isNewRecord): ?>    <input type="hidden" name="update_id"
           value=" <?php echo $model->id; ?>"/>
    <?php endif; ?>
    <div class="row buttons">
        <?php   echo CHtml::submitButton($model->isNewRecord ? Yii::t("app",'Submit') : 'Save',array('class' =>
        '')); ?>    </div>

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


