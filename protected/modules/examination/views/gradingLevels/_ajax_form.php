<!--
 * Ajax Crud Administration Form
 * GradingLevels *
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.3
 -->
 <style>
.fancybox-inner{ width:auto !important; height:auto !important; overflow:hidden !important;}
.client-val-form input{ width:100% !important;  -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box ;
}
.client-val-form label{
	margin-bottom:5px;
	 display:inline-block !important;	
}
.client-val-form select{ width:100% !important;  
-webkit-box-sizing: border-box !important ;
    -moz-box-sizing: border-box !important ;
    box-sizing: border-box !important;
}
.fancybox-wrap{
	 width:350px !important;	
}
.client-val-form .input-checkbox input{ width:inherit !important; display:inline !important;}
 </style>
<div id="grading-levels_form_con" class="client-val-form">
    <?php if ($model->isNewRecord) : ?>    <h3 id="create_header"><?php echo Yii::t('app','Create New Grading Level');?></h3>
    <?php  elseif (!$model->isNewRecord):  ?>    <h3 id="update_header"><?php echo Yii::t('app','Update Grading Level');?> <?php  echo " : ".$model->name;  ?>  </h3>
    <?php   endif;  ?>
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui-style.css" />
    <?php      //$val_error_msg = Yii::t('app','Error.GradingLevels was not saved.');
	 			$val_error_msg = Yii::t('app','Already grade created for this minimum score');
    $val_success_message = ($model->isNewRecord) ?
            Yii::t('app','Grading Level was created successfully.') :
            Yii::t('app','Grading Level was updated successfully.');
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
<?php   $formId='grading-levels-form';
   $actionUrl =
   ($model->isNewRecord)?CController::createUrl('gradingLevels/ajax_create')
                                                                 :CController::createUrl('gradingLevels/ajax_update');


    $form=$this->beginWidget('CActiveForm', array(
     'id'=>'grading-levels-form',
    //  'htmlOptions' => array('enctype' => 'multipart/form-data'),
         'action' => $actionUrl,
     //'enableAjaxValidation'=>true,
      'enableClientValidation'=>true,
     'focus'=>array($model,'name'),
     'errorMessageCssClass' => 'input-notification-error  error-simple png_bg',
     'clientOptions'=>array('validateOnSubmit'=>true,
                                        'validateOnType'=>false,
                                       /* 'afterValidate'=>$js_afterValidate,*/
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
     <p class="note"><?php echo Yii::t('app','Fields with'); ?><span class="required">*</span> <?php echo Yii::t('app','are required.') ;?></p>
     <?php echo $form->errorSummary($model); ?><br />


    <div class="fancy-box-block">
            <?php echo $form->labelEx($model,'name',array('style'=>'color: #444444')); ?>
            <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>25)); ?>
        <span id="success-GradingLevels_name"
              class="hid input-notification-success"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'name'); ?>
    </div>

        <?php if($model->batch_id==NULL)
			{ 
            echo $form->hiddenField($model,'batch_id',array('value'=>$_POST['batch_id']));
			}?>

        <div class="fancy-box-block">
            <?php echo $form->labelEx($model,'min_score',array('style'=>'color: #444444')); ?>
            <?php echo $form->textField($model,'min_score',array('size'=>60,'maxlength'=>6)); ?>
        <span id="success-GradingLevels_min_score"
              class="hid input-notification-success" ></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'min_score');  ?>
    </div>

        <div class="fancy-box-block">
            <?php //echo $form->labelEx($model,'order'); ?>
            <?php echo $form->hiddenField($model,'order'); ?>
        <span id="success-GradingLevels_order"
              class="hid input-notification-success"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'order'); ?>
    </div>

        <div class="fancy-box-block">
            <?php //echo $form->labelEx($model,'is_deleted'); ?>
            <?php echo $form->hiddenField($model,'is_deleted'); ?>
        <span id="success-GradingLevels_is_deleted"
              class="hid input-notification-success"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'is_deleted'); ?>
    </div>

        <div class="fancy-box-block">
            <?php //echo $form->labelEx($model,'created_at'); ?>
            <?php echo $form->hiddenField($model,'created_at',array('value'=>date('m-d-Y'))); ?>
        <span id="success-GradingLevels_created_at"
              class="hid input-notification-success"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'created_at'); ?>
    </div>

        <div class="fancy-box-block">
            <?php //echo $form->labelEx($model,'updated_at'); ?>
            <?php echo $form->hiddenField($model,'updated_at',array('value'=>date('m-d-Y'))); ?>
        <span id="success-GradingLevels_updated_at"
              class="hid input-notification-success"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'updated_at'); ?>
    </div>

    
    <input type="hidden" name="YII_CSRF_TOKEN"
           value="<?php echo Yii::app()->request->csrfToken; ?>"/>

    <?php  if (!$model->isNewRecord): ?>    <input type="hidden" name="update_id"
           value=" <?php echo $model->id; ?>"/>
    <?php endif; ?>
    <div>
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


