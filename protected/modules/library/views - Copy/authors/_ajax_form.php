
<style>
.client-val-form input
{
	color:#633C15 !important;
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

<!--
 * Ajax Crud Administration Form
 * Author *
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.3
 -->

<div id="author_form_con" class="client-val-form">
    <?php if ($model->isNewRecord) : ?>    <h3 id="create_header"><?php echo Yii::t('app','Create New Author');?></h3>
    <?php  elseif (!$model->isNewRecord):  ?>    <h3 id="update_header"><?php echo Yii::t('app','Update Author : ');?><?php  echo
        $model->author_name;  ?>  </h3>
    <?php   endif;  ?>
    <?php      $val_error_msg = Yii::t('app','Error.Author was not saved.');
    $val_success_message = ($model->isNewRecord) ?
            Yii::t('app','Author was created successfuly.') :
            Yii::t('Author  was updated successfuly.');
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
<?php   $formId='author-form';
   $actionUrl =
   ($model->isNewRecord)?CController::createUrl('authors/ajax_create')
                                                                 :CController::createUrl('authors/ajax_update');


    $form=$this->beginWidget('CActiveForm', array(
     'id'=>'author-form',
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
    <?php echo $form->errorSummary($model, '
    <div style="font-weight:bold">'.Yii::t('app','Please correct these errors:').'</div>
    ', NULL, array('class' => 'errorsum notification errorshow-fancy')); ?>   
    <p class="note"><?php echo Yii::t('app','Fields with');?><span class="required">*</span><?php echo Yii::t('app','are required.');?></p>


    <div class="row">
            <?php echo $form->labelEx($model,Yii::t('app','author_name'),array('style'=>'color:#444444;')); ?>
            <?php echo $form->textField($model,'author_name',array('size'=>60,'maxlength'=>120)); ?>
        <span id="success-Author_author_name"
              class="hid input-notification-success  success-fancy right"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'author_name'); ?>
    </div>

        <div class="row">
            <?php echo $form->labelEx($model,Yii::t('app','desk')); ?>
            <?php echo $form->textField($model,'desc',array('size'=>60,'maxlength'=>120)); ?>
        <span id="success-Author_desc"
              class="hid input-notification-success  success-fancy right"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'desc'); ?>
    </div>

    
    <input type="hidden" name="YII_CSRF_TOKEN"
           value="<?php echo Yii::app()->request->csrfToken; ?>"/>

    <?php  if (!$model->isNewRecord): ?>    <input type="hidden" name="update_id"
           value=" <?php echo $model->auth_id; ?>"/>
    <?php endif; ?>
    <div class="row buttons">
        <?php   echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Submit'): Yii::t('app','Save'),array('class' =>
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


