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
.ui-datepicker {
	top: 205.063px !important;
}
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
<div id="exam-groups_form_con" class="client-val-form" >
  <?php if ($model->isNewRecord) : ?>
  <h3 id="create_header"><?php echo Yii::t('app','Create New Exam');?></h3>
  <?php  elseif (!$model->isNewRecord):  ?>
  <h3 id="update_header"><?php echo Yii::t('app','Update Exam');?>
    <?php  echo " : ".$model->name;  ?>
  </h3>
  <?php   endif;  ?>
  <?php      $val_error_msg = Yii::t('app','Error.ExamGroups was not saved.');
    $val_success_message = ($model->isNewRecord) ?
            Yii::t('app','Exam was created successfully.') :
            Yii::t('app','Exam was updated successfully.');
  ?>
  <div id="success-note" class="notification success-fancy  png_bg"
         style="display:none;"> <a href="#" class="close"><img
                src="<?php echo Yii::app()->request->baseUrl.'/js_plugins/ajaxform/images/icons/cross_grey_small.png';  ?>"
                title="Close this notification" alt="close"/></a>
    <div>
      <?php   echo $val_success_message;  ?>
    </div>
  </div>
  <div id="error-note" class="notification errorshow-fancy png_bg"
         style="display:none;"> <a href="#" class="close"><img
                src="<?php echo Yii::app()->request->baseUrl.'/js_plugins/ajaxform/images/icons/cross_grey_small.png';  ?>"
                title="Close this notification" alt="close"/></a>
    <div>
      <?php   echo $val_error_msg;  ?>
    </div>
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
            'style'=>''),

));

     ?>
    <?php
			if(Yii::app()->user->year)
				{
					$year = Yii::app()->user->year;
				}
				else
				{
					$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
					$year = $current_academic_yr->config_value;
				}
				
				$terms=Terms::model()->findAll("academic_yr_id =:y", array(':y'=>$year));
				$term_arr = array();
				if($terms){
					foreach($terms as $term)
                                        {
						if($term->term_id == 1){
							$term_arr[1]= Yii::t('app','Term 1');
						}
						else if($term->term_id == 2){
							$term_arr[2]= Yii::t('app','Term 2');
						}
					}
				}
                                ksort($term_arr);
                               
				
				
			?>

		  <div class="fancy_box_form">
		  <?php echo $form->labelEx($model,'term_id',array('style'=>'color:#444444')); ?> <?php echo $form->dropDownList($model,'term_id',$term_arr,array('prompt'=>Yii::t('app','Select Term'),'options'=>array($_REQUEST['term_id']=>array('selected'=>true)),
                                    'ajax' => array(
                                    'type'=>'POST',
                                    'url'=>CController::createUrl('/CBSCExam/exam/examtype'),
									'success' => 'function(data){
										$("#CbscExamGroups_exam_type").html(data); 
									}',
                                    
									))); ?> <span id="success-ExamGroups_name" class="hid input-notification-success  success-fancy  png_bg right" style="margin:0px;"></span> <?php echo $form->error($model,'term_id'); ?>
                                    </div>

		  <div class="fancy_box_form">
		  <?php echo $form->labelEx($model,'name',array('style'=>'color:#444444')); ?> <?php echo $form->textField($model,'name',array('maxlength'=>25)); ?> <span id="success-ExamGroups_name" class="hid input-notification-success  success-fancy  png_bg right" style="margin:0px;"></span> <?php echo $form->error($model,'name'); ?>
        </div>

        <?php if($model->batch_id==NULL)
        { 
            echo $form->hiddenField($model,'batch_id',array('value'=>$_POST['batch_id']));
        }
        else
        {
            echo $form->hiddenField($model,'batch_id');
        }

	$data = array();
	if($model->term_id!=NULL)
	{
		
		$term_id = 	$model->term_id;
		$data =CbscExamGroups::model()->getExamtype($term_id,$model->batch_id,$model->id);
		
		//$data =  CHtml::listData($exam_row, 'exam_type', 'exam_type');
		//$data =  $exam_row->exam_type;
	}
	?>


		  <div class="fancy_box_form">
		  <?php echo $form->labelEx($model,'exam_type',array('style'=>'color:#444444')); ?>
            <?php //echo $form->dropDownList($model,'exam_type',$data,array('empty' => Yii::t('app','Select Exam Type'))); 
		   echo $form->dropDownList($model,'exam_type',$data,array('empty'=>Yii::t('app','Select Exam Type')));?>
            <span id="success-ExamGroups_exam_type"class="hid input-notification-success  success-fancy  png_bg right"></span> <?php echo $form->error($model,'exam_type'); ?>
            </div>
		  <div class="fancy_box_form input-checkbox">
                <?php echo $form->checkBox($model,'date_published'); ?>
                <?php echo $form->labelEx($model,'date_published'); ?>
                <?php echo $form->error($model,'date_published'); ?>
                </div>
 <div class="fancy_box_form input-checkbox">
                <?php echo $form->checkBox($model,'result_published'); ?>
               <?php echo $form->labelEx($model,'result_published'); ?>
               <?php echo $form->error($model,'result_published'); ?>
                </div>

		  <div class="fancy_box_form">
		  <?php echo $form->labelEx($model,'date',array('style'=>'color:#444444')); ?>
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
				'attribute'=>'date',
				// additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
					'dateFormat'=>$date,
					'changeMonth'=> true,
					'changeYear'=>true,
					'yearRange'=>'1900:'.(date('Y')+2),
				),
				'htmlOptions'=>array(
				
					'readonly'=>true
				),
			));
			?>
            <?php //echo $form->textField($model,'exam_date'); ?>
            <span id="success-ExamGroups_exam_date" class="hid input-notification-success  success-fancy  png_bg right"></span> <?php echo $form->error($model,'date'); ?>
            </div>
            </td>
        </tr>
                <tr><td height="15"></td></tr>
      </tbody>
    </table>
    <input type="hidden" name="YII_CSRF_TOKEN"
           value="<?php echo Yii::app()->request->csrfToken; ?>"/>
    <?php  if (!$model->isNewRecord): ?>
    <input type="hidden" name="update_id"
           value=" <?php echo $model->id; ?>"/>
    <?php endif; ?>
    <div class="row buttons">
      <?php   echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Submit') : Yii::t('app','Save'),array('class' =>
        'formbut')); ?>
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
