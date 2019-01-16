<style>
.client-val-form input
{
	/*color:#633C15 !important;*/
}

.formbut{ width:89% !important}

.date_err
{
    border: 2px solid #EBA5A5 !important;
}
</style>

<!--
 * Ajax Crud Administration Form
 * StudentAttentance *
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.3
 -->
<div id="student-attentance_form_con" >
    <?php if ($model->isNewRecord) : ?>    <h3 id="create_header"><?php echo Yii::t('app','Student Attendance'); ?></h3>
    <?php  elseif (!$model->isNewRecord):  ?>    <h3 id="update_header"><?php echo Yii::t('app','Student Attendance'); ?> <?php  echo
        $model->id;  ?>  </h3>
    <?php   endif;  ?>
    <?php      $val_error_msg = Yii::t('app','Error.StudentAttentance was not saved.');
    $val_success_message = ($model->isNewRecord) ?
            Yii::t('app','Student Attendance was created successfully.') :
            Yii::t('app','Student Attendance  was updated successfully.');
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
<?php   $formId='student-attentance-form';
   $actionUrl =
   ($model->isNewRecord)?CController::createUrl('studentLeave/ajax_create')
                                                                 :CController::createUrl('studentLeave/ajax_update');


    $form=$this->beginWidget('CActiveForm', array(
     'id'=>'student-attentance-form',
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
    <?php //echo $form->errorSummary($model, '<div style="font-weight:bold">'.Yii::t('app','Please correct these errors:').'</div>', NULL, array('class' => 'errorsum notification errorshow png_bg')); ?>   
        
        
        <p class="note"><?php echo Yii::t('app','Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app','are required.'); ?></p>


<div class="fancy_box_form">
            <?php echo $form->hiddenField($model,'student_id',array('value'=>$_POST['id'])); ?>
        <?php 
        $join_date="";
        $student = Students::model()->findByAttributes(array('id'=>$_POST['id']));
        if($student!=NULL)
        {
            $join_date  =   $student->admission_date;
        }
        
        ?>
        <span id="success-StudentAttentance_student_id"
              class="hid input-notification-success  success png_bg"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'student_id'); ?>
</div>
<div class="fancy_box_form">
            <?php echo $form->labelEx($model,'date',array('style'=>'color:#444444;')); ?>
    <?php 
	$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
	if($settings!=NULL)
	{
		$date=$settings->dateformat;
		
		
	}
	else
	$date = 'dd-mm-yy';	
	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
		//'name'=>'Attendant[dob]',
		'model'=>$model,
		'attribute' => 'date',
		// additional javascript options for the date picker plugin
		'options'=>array(
			'showAnim'=>'fold',
			'dateFormat'=>$date,
			'changeMonth'=> true,
			'changeYear'=>true,
			
		),
		'htmlOptions'=>array(
			'readonly'=>'readonly',							
			
		),
	));?>
        <span id="success-StudentAttentance_date"
              class="hid input-notification-success  success png_bg"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'date'); ?>
            <div class="input-notification-error  error-simple png_bg" id="date_error" style="display:none"><?php echo "Date must be less than or equal to joining date"; ?></div>
             <div class="input-notification-error  error-simple png_bg" id="day_error" style="display:none"><?php echo "Date must be less than or equal to current date"; ?></div>
    </div>

<div class="fancy_box_form">
            <?php echo $form->labelEx($model,'reason',array('style'=>'color:#444444;')); ?>
            <?php echo $form->textField($model,'reason',array('maxlength'=>120)); ?>
        <span id="success-StudentAttentance_reason"
              class="hid input-notification-success  success png_bg right"></span>
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'reason'); ?>            
    </div>

    
    <input type="hidden" name="YII_CSRF_TOKEN"
           value="<?php echo Yii::app()->request->csrfToken; ?>"/>

    <?php  if (!$model->isNewRecord): ?>    <input type="hidden" name="update_id"
           value=" <?php echo $model->id; ?>"/>
    <?php endif; ?>
    <div class="fancy_box_form buttons">
        <?php   echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Submit') : Yii::t('app','Save'),array('class' =>
        'formsub')); ?>    </div>

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
    $('.formsub').click(function(e) 
    {     
		var leave_date  =   $('#StudentAttentance_date').val();
        var reason  =   $('#StudentAttentance_reason').val();
		$('#date_error').hide();
		$('#day_error').hide();
		
		var l_date	=new Date(leave_date);
		var l_day	= l_date.getFullYear()+"-"+l_date.getMonth() + 1+"-"+l_date.getDate();		  
		var date=new Date();
		var day	= date.getFullYear()+"-"+date.getMonth() + 1+"-"+date.getDate();
		if(l_day >day){
			 $('#StudentAttentance_date').attr('style', 'border: 2px solid #EBA5A5 !important');
                $('#day_error').show();
				return false;
		}  
        if(reason!="")
        {
			
            var selected_date    =   new Date(leave_date);
            var joining_date     =   new Date("<?php echo $join_date; ?>");
            if(selected_date < joining_date)
            {                              
                $('#StudentAttentance_date').attr('style', 'border: 2px solid #EBA5A5 !important');
                $('#date_error').show();
                e.preventDefault();                
            } 
            else{
                $('#StudentAttentance_date').attr('style', 'border: 2px solid #A1EDA0 !important');
                $('#date_error').hide();
			} 
        } 
    });
</script>


