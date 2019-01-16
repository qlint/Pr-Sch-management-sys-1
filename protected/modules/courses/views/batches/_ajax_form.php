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
<?php /*?><style>
    .ui-widget label{
        font-size: 14px !important;
    }
	.timetable_formats label{
		font-weight:300 !important;
		display:inline;
		font-size:12px !important;
	}
	.popup-input input[type="text"], textArea, select {
    width: 100% !important;
    box-sizing: border-box;
}
.poup-checkbox input[type="text"]{
	width:auto !important;
	 display:inline-block !important;	
}
.popup-box {
    background-color: #EAF5FD;
    border: 1px solid #a3c5e0;
    margin-top: 10px;
    padding: 9px;
	margin-bottom:10px;
}
.popup-input table td {
    width: 100%;
    font-size: 12px;
}

.timetable_formats label{
	display:inline;
}
</style><?php */?>
<style>
	.FncyBox_Form input[type="text"], select, .hasDatepicker {
	border: 1px #c2cfd8 solid;
    padding: 7px 3px;
    background: #fff;
    box-shadow: none !important;
    width: 100%;
    box-sizing: border-box;
    margin: 3px 0px;
	
	}
	.FncyBox_Form_OuterBox{
		margin:8px 0px;	
	}
	.popup-box {
    background-color:#e8e8e8;
    padding: 9px;
	margin:5px 0px;
}
</style> 

<div id="subjects_form_con" class="FncyBox_Form">
    <?php if ($model->isNewRecord) : ?>    
    <h3 id="create_header"><?php echo Yii::t('app','Add New').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></h3>
    <?php  elseif (!$model->isNewRecord):  ?>    <h3 id="update_header"><?php echo Yii::t('app','Update').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.$model->name;?></h3>
    <?php   endif;  ?>
    <?php      $val_error_msg = Yii::t('app','Error !').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app', 'was not saved.');
    $val_success_message = ($model->isNewRecord) ?
            Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','was added successfully.') :
            Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','was updated successfully.');
  ?>

    <div id="success-note" class="notification success "
         style="display:none;">
        <a href="#" class="close"><img
                src="<?php echo Yii::app()->request->baseUrl.'/js_plugins/ajaxform/images/icons/cross_grey_small.png';  ?>"
                title="Close this notification" alt="close"/></a>
        <div>
            <?php   echo $val_success_message;  ?>        </div>
    </div>

    <div id="error-note" class="notification errorshow "
         style="display:none;">
        <a href="#" class="close"><img
                src="<?php echo Yii::app()->request->baseUrl.'/js_plugins/ajaxform/images/icons/cross_grey_small.png';  ?>"
                title="Close this notification" alt="close"/></a>
        <div>
            <?php   echo $val_error_msg;  ?>        </div>
    </div>

    <div id="ajax-form"  class='form'>
<?php   $formId='batches-form';
   $actionUrl =
   ($model->isNewRecord)?CController::createUrl('batches/ajax_create')
                                                                 :CController::createUrl('batches/ajax_update');


    $form=$this->beginWidget('CActiveForm', array(
     'id'=>'batches-form',
    //  'htmlOptions' => array('enctype' => 'multipart/form-data'),
         'action' => $actionUrl,
    	//'enableAjaxValidation'=>true,
      'enableClientValidation'=>true,
     'focus'=>array($model,'name'),
     'errorMessageCssClass' => 'input-notification-error  error-simple png_bg',
     'clientOptions'=>array('validateOnSubmit'=>true,
                                        'validateOnType'=>true,
                                        //'afterValidate'=>'js_afterValidate',
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
    ', NULL, array('class' => 'errorsum notification errorshow png_bg')); ?>    <p class="note"><?php echo Yii::t('app','Fields with');?> <span class="required">*</span><?php echo Yii::t('app','are required.');?></p>
	
   
        <div class="FncyBox_Form_OuterBox">
			<?php echo $form->labelEx($model,'name', array('style'=>'color:#000000')); ?>
         
			 	<?php 
					
					echo $form->textField($model,'name',array('size'=>30,'maxlength'=>255, 'encode'=>false)); 
				?>
			
             <!--<span id="success-Subjects_name" class="hid input-notification-success  success png_bg right" style="float:right; margin:8px 122px 0px 0px;"></span>-->
            <div>
                <small></small>
            </div>
            <?php echo $form->error($model,'name'); ?>
   </div>

	
    <div class="FncyBox_Form_OuterBox">
    
    <?php echo $form->labelEx($model,'start_date',array('style'=>'color:#000000')); ?>
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
								'attribute'=>'start_date',
								// additional javascript options for the date picker plugin
								'options'=>array(
									'showAnim'=>'fold',
									'dateFormat'=>$date,
									'changeMonth'=> true,
									'changeYear'=>true,
									'yearRange'=>'1900:'
								),
								'htmlOptions'=>array(
								
									'readonly'=>true
								),
							));?>
		
        <div>
            <small></small>
        </div>
            <?php echo $form->error($model,'start_date'); ?>
    </div>
 <div class="FncyBox_Form_OuterBox">
 	<?php echo $form->labelEx($model,'end_date',array('style'=>'color:#000000')); ?>
   <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
								//'name'=>'Students[admission_date]',
								'model'=>$model,
								'attribute'=>'end_date',
								// additional javascript options for the date picker plugin
								'options'=>array(
									'showAnim'=>'fold',
									'dateFormat'=>$date,
									'changeMonth'=> true,
									'changeYear'=>true,
									'yearRange'=>'1900:'.(date('Y')+30),
								),
								'htmlOptions'=>array(
									
									'readonly'=>true
								),
							)); ?>
     <!--<span id="success-Subjects_name" class="hid input-notification-success  success png_bg right" style="float:right; margin:8px 122px 0px 0px;"></span>-->
    <div>
        <small></small>
    </div>
    <?php echo $form->error($model,'end_date'); ?>
 </div>
  

        <div class="FncyBox_Form_OuterBox" >
        <?php echo $form->labelEx($model,'employee_id',array('style'=>'color:#000000')); ?>
            
            <?php
		$criteria=new CDbCriteria;
		$criteria->condition='is_deleted=:is_del';
		$criteria->params=array(':is_del'=>0);
	?>
            <?php echo $form->dropDownList($model,'employee_id',CHtml::listData(Employees::model()->findAll($criteria),'id','concatened'),array('empty' => Yii::t('app','Select Class Teacher'))); ?>
        <!--<span id="success-Subjects_max_weekly_classes"
              class="hid input-notification-success  success png_bg right" ></span>-->
        <div>
            <small></small>
        </div>
           <?php echo $form->error($model,'employee_id'); ?>
    </div>
    <?php 
  $sem_enabled= Configurations::model()->isSemesterEnabledForCourse($model->course_id);
  if($sem_enabled==1){
  ?> 
    <div class="FncyBox_Form_OuterBox" >
    <?php echo $form->labelEx($model,'semester_id',array('style'=>'color:#000000')); ?> <span style="color:#F00">*</span>
		<?php
            $criteria=new CDbCriteria;
            $criteria->join= 'JOIN semester_courses `sc` ON t.id = `sc`.semester_id';
            $criteria->condition='`sc`.course_id =:course_id';
            $criteria->params=array(':course_id'=>$model->course_id);
            
        ?>
        <?php echo $form->dropDownList($model,'semester_id',CHtml::listData(Semester::model()->findAll($criteria),'id','name'),array('empty' => Yii::t('app','Select Semester'))); ?>
    	<span id="error_msg" style="color:#F00; display:none"> <?php echo Yii::t('app','Semester cannot be blank.');?></span>
        <div>
            <small></small>
        </div>
    </div>
     <?php } ?>
     <?php if(Configurations::model()->timetableConfig()==-2){ // timetable format is selected as course level ?>
      <div class="FncyBox_Form_OuterBox" >
      <div>
      
        <table class="popup-box" width="100%">
            <tbody>
                <tr>
                    <td><?php echo $form->labelEx($model,'timetable_format'); ?></td>
                </tr>
                <tr>
                    <td class="timetable_formats fancy-box-block-checkd">
                    <?php 
					if($model->timetable_format == NULL){
						$model->timetable_format = 1;
					} 
					echo $form->radioButton($model,'timetable_format', array('value'=>1, 'id'=>'timetable_format_1'))." ".CHtml::label(Yii::t('app', 'Fixed Class Timings'), 'timetable_format_1'); ?>
                    <br/>
                    <?php echo $form->radioButton($model,'timetable_format', array('value'=>2, 'uncheckValue'=>1, 'id'=>'timetable_format_2'))." ".CHtml::label(Yii::t('app', 'Flexible Class Timings'), 'timetable_format_2'); ?>
                    </td>
                </tr>
            </tbody>
        </table>
      
      
            <small></small>
        </div>
      </div>
      <?php }?>
   <?php $level = Configurations::model()->findByPk(41);
	 if($level->config_value == -2)
	 { ?> 

 	<div class="FncyBox_Form_OuterBox">
        <table class="popup-box poup-checkbox" width="100%">
            <tbody>
            <tr>
           	 <td ><?php echo $form->labelEx($model,'exam_format'); ?></td>
            </tr>
            <tr>                
                <td><div><?php echo $form->radioButton($model, 'exam_format', array('value'=>'1','uncheckValue'=>null))."Default ";
                echo $form->radioButton($model, 'exam_format', array('value'=>'2','uncheckValue'=>null))." CBSE"; ?>
                <?php echo $form->error($model,'exam_format'); ?></div></td>
            </tr>
            </tbody>
        </table>
    </div>

   <?php } ?>
        

        
    
    <input type="hidden" name="YII_CSRF_TOKEN"
           value="<?php echo Yii::app()->request->csrfToken; ?>"/>

    <?php  if (!$model->isNewRecord): ?>    <input type="hidden" name="update_id"
           value=" <?php echo $model->id; ?>"/>
    <?php endif; ?>
    <div class="FncyBox_Form_OuterBox">
        <?php   echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Submit') : Yii::t('app','Save'),array('class' =>'formbut')); ?>   
    </div>
  <?php  $this->endWidget(); ?></div>
    <!-- form -->

</div>
<script type="text/javascript">

    //Close button:
$('.formbut').click( function() {
	$('#error_msg').hide();
	var val	= $('#Batches_semester_id').val();
	 if(val == ''){
		 $('#error_msg').show();
		return false;
	 }else{
	 	$('#error_msg').hide();
	 }
});

    $(".close").click(
            function () {
                $(this).parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
                    $(this).slideUp(600);
                });
                return false;
            }
    );


</script>


