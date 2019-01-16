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
.client-val-form  .fancy-box-block input{
	border: 1px solid #D5D5D5;
	border-radius: 0px;
	color: #333333;
	font-size: 13px;
	padding: 6px;
	width: 100%; 
	display: block;
	box-sizing: border-box;
}
.fancybox-inner{
	 width:100% !important;	
}
.fancy-box-block{
	 padding-bottom:15px;	
}
</style> 
<div id="exam-groups_form_con" class="client-val-form" >
    <?php if ($model->isNewRecord) : ?>    <h3 id="create_header"><?php echo Yii::t('app','Create New Exam');?></h3>
    <?php  elseif (!$model->isNewRecord):  ?>    <h3 id="update_header"><?php echo Yii::t('app','Update Exam');?>  <?php  echo " : ".$model->name;  ?>  </h3>
    <?php   endif;  ?>
    <?php      $val_error_msg = Yii::t('app','Error.ExamGroups was not saved.');
    $val_success_message = ($model->isNewRecord) ?
            Yii::t('app','Exam was created successfully.') :
            Yii::t('app','Exam was updated successfully.');
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
   ($model->isNewRecord)?CController::createUrl('exam/ajax_create')
                                                                 :CController::createUrl('exam/ajax_update');


    $form=$this->beginWidget('CActiveForm', array(
     'id'=>'exam-groups-form',
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
            'style'=>'height:auto; '),

));

     ?>
    <p class="note"><?php echo Yii::t('app','Fields with'); ?><span class="required">*</span> <?php echo Yii::t('app','are required.') ;?></p>



    	<div class="fancy-box-block">
    	<div>
            <?php echo $form->labelEx($model,'name',array('style'=>'color:#444444')); ?>
		</div>
		<span>
            <?php echo $form->textField($model,'name',array('maxlength'=>25)); ?>
		</span>
        <span id="success-ExamGroups_name" class="hid input-notification-success  success png_bg " style="margin:0px;"></span>
        <div style="clear:left">
            <small></small>
        </div>
            <?php echo $form->error($model,'name'); ?>
            </div>

    <?php if($model->batch_id==NULL)
			{ 
            echo $form->hiddenField($model,'batch_id',array('value'=>$_POST['batch_id']));
			}?>


        <div class="fancy-box-block">
            <?php echo $form->labelEx($model,'exam_type',array('style'=>'color:#444444')); ?>
            <?php echo $form->dropDownList($model,'exam_type',array('Marks'=>Yii::t('app','Marks'),'Grades'=>Yii::t('app','Grades'),'Marks And Grades'=>Yii::t('app','Marks And Grades'))); ?>
        <span id="success-ExamGroups_exam_type"
              class="hid input-notification-success  success png_bg "></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'exam_type'); ?>
      </div>      


        <div class="fancy-box-block">
        	<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="20"><?php echo $form->checkBox($model,'is_published'); ?></td>
                <td><?php echo $form->labelEx($model,'is_published'); ?></td>
                <td> <?php echo $form->error($model,'is_published'); ?></td>
              </tr>
            </table>

           
    </div>

        <div class="fancy-box-block">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="20" ><?php echo $form->checkBox($model,'result_published'); ?></td>
            <td><?php echo $form->labelEx($model,'result_published'); ?></td>
            <td><?php echo $form->error($model,'result_published'); ?></td>
          </tr>
        </table>
   
    </div>

        <div class="fancy-box-block">
        	<div>
            <?php echo $form->labelEx($model,'exam_date',array('style'=>'color:#444444')); ?>
            </div>
            
			<span style="float:left; display:inline;">
			<?php $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
			if($settings!=NULL)
			{
				$date=$settings->dateformat;
		
		
			}
   			else
			$date = 'dd-mm-yy';		
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				//'name'=>'Students[admission_date]',
				'model'=>$model,
				'attribute'=>'exam_date',
				// additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
					'dateFormat'=>$date,
					'changeMonth'=> true,
					'changeYear'=>true,
					'yearRange'=>'1900:'.(date('Y')+2),
				),
				'htmlOptions'=>array(
					'style'=>'height:30px; width:100%;',
					'readonly'=>true
				),
			));
			?>
			<?php //echo $form->textField($model,'exam_date'); ?>
            </span>
            
            
        <span id="success-ExamGroups_exam_date" class="hid input-notification-success  success png_bg "></span>
        <div style="clear:left;">
            <small></small>
        </div>
            <?php echo $form->error($model,'exam_date'); ?>
    </div>

    
    <input type="hidden" name="YII_CSRF_TOKEN"
           value="<?php echo Yii::app()->request->csrfToken; ?>"/>

    <?php  if (!$model->isNewRecord): ?>    <input type="hidden" name="update_id"
           value=" <?php echo $model->id; ?>"/>
    <?php endif; ?>
    <div class="fancy-box-block">
        <?php   echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Submit') : Yii::t('app','Save'),array('class' =>
        'formbut-n')); ?>    </div>

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


