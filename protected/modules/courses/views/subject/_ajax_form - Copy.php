<style type="text/css">
.ui-dialog {
	background: #fff !important;
	color: #000;
}
.fancybox-inner{
	width:100% !important	
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
  
  $data = CHtml::listData(SubjectName::model()->findAll(),'id','name') ?>
 <?php $data1 = CHtml::listData(Subjects::model()->findAll($criteria),'id','name') ?>
<div id="subjects_form_con" class="fancy_box_form">
  <?php if ($model->isNewRecord) : ?>
  <h3 id="create_header"> <?php echo Yii::t('app','Add New Subject');?></h3>
  <?php  elseif (!$model->isNewRecord):  ?>
  <h3 id="update_header"><?php echo Yii::t('app','Update Subject : ').$model->name;?></h3>
  <?php   endif;  ?>
  <?php $val_error_msg = Yii::t('app','Error.Subjects was not saved.');
    $val_success_message = ($model->isNewRecord) ?
            Yii::t('app','Subject was added successfully.') :
            Yii::t('app','Subject  was updated successfully.');
  ?>
  <div id="success-note" class="notification success "
         style="display:none;"> <a href="#" class="close"> <img src="<?php echo Yii::app()->request->baseUrl.'/js_plugins/ajaxform/images/icons/cross_grey_small.png';  ?>"
                title="Close this notification" alt="close"/></a>
    <div>
      <?php   echo $val_success_message;  ?>
    </div>
  </div>
  <div id="error-note" class="notification errorshow "
         style="display:none;"> <a href="#" class="close"> <img src="<?php echo Yii::app()->request->baseUrl.'/js_plugins/ajaxform/images/icons/cross_grey_small.png';  ?>"
                title="Close this notification" alt="close"/></a>
    <div>
      <?php   echo $val_error_msg;  ?>
    </div>
  </div>
  <div id="ajax-form"  class='form'>
    <?php   $formId='subjects-form';
   $actionUrl =
   ($model->isNewRecord)?CController::createUrl('subject/ajax_create')
                                                                 :CController::createUrl('subject/ajax_update');


    $form=$this->beginWidget('CActiveForm', array(
     'id'=>'subjects-form',
    //  'htmlOptions' => array('enctype' => 'multipart/form-data'),
         'action' => $actionUrl,
    	'enableAjaxValidation'=>true,
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
    <p class="note"><?php echo Yii::t('app','Fields with');?><span class="required">*</span><?php echo Yii::t('app','are required'); ?>.</p>
    <?php
	if(isset($batch_id) and $batch_id==0) // for settings module
	{
	?>
    <div class="row"> <?php echo $form->labelEx($model,'batch_id', array('style'=>'color:#000000')); ?>
      <?php 
			$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
            if(Yii::app()->user->year)
			{
				$yr = Yii::app()->user->year;
				
			}
			else
			{
				$yr = $current_academic_yr->config_value;
			}
			 $criteria  = new CDbCriteria;
		     $criteria ->compare('is_deleted',0);
			 $criteria->condition = 'is_active=:is_active AND academic_yr_id=:yr';
			 $criteria->params = array(':is_active'=>1,':yr'=>$yr);  
			echo $form->dropDownList($model, 'batch_id', CHtml::listData(Batches::model()->findAll($criteria),'id','coursename'),array('encode'=>false,'prompt'=>Yii::t('app','Select')));
			?>
    
      <div> <small></small> </div>
      <?php echo $form->error($model,'batch_id'); ?> </div>
    <?php
	}
	?>
    <div class="row"> <?php echo $form->labelEx($model,'name', array('style'=>'color:#000000')); ?> 
					<?php echo $form->textField($model,'name',array('encode'=>false,'maxlength'=>'100')); ?>
     
     
      <div> <small></small> </div>
      <?php echo $form->error($model,'name'); ?> </div>
   
    <div class="row" > <?php echo $form->labelEx($model,'max_weekly_classes',array('style'=>'color:#000000')); ?> 
	<?php echo $form->textField($model,'max_weekly_classes',array('maxlength'=>'3')); ?> 
      
      <div> <small></small> </div>
      <?php echo $form->error($model,'max_weekly_classes'); ?> </div>
	  <?php if(ExamFormat::model()->getExamformat($_REQUEST['batch_id'])== 1){ ?>
      <div class="fancy-box-block fancy-box-block-checkd" >
         <label style="color: #444444" for="GradingLevels_is_special" class="required"><?php echo Yii::t('app','Split Subject'); ?></label>
         <?php
         echo $form->checkBox($model,'split_subject'); ?>
     </div>
       
      
       			<?php  
				if(!$model->isNewRecord){
					$common_cps	=	SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$model->id));
					if($common_cps  == NULL)
					{
					 $common_cps	=	SubjectCommonpoolSplit::model()->findAllByAttributes(array('subject_id'=>$model->id));
					}
					$k=1;
					foreach($common_cps as $common_cp){
						$att			=	'subject_tilte'.$k;
						$model->$att	=$common_cp->split_name; 
						$k++;
					}
				} 
				?>
                <div class="split" style="display:none">
					<?php
                    for($i=1;$i<=2;$i++){?>            
                        <div class="row"> <?php echo $form->labelEx($model,'subject_tilte'.$i,array('style'=>'color:#000000')); ?>&nbsp;<span class="required">*</span>
                            <?php echo $form->textField($model,'subject_tilte'.$i,array('encode'=>false)); ?> 
                            <div> <small></small> </div>
                            <?php if($i==1){?>
                            <div class="input-notification-error  error-simple png_bg" style=" display:none" id="error1"><?php echo Yii::t('app','First Sub Category cannot be blank.');?></div>
                            <?php }else{
								?>
                                 <div class="input-notification-error  error-simple png_bg" style=" display:none" id="error2"><?php echo Yii::t('app','First Sub Category cannot be blank.');?></div>
                                <?php
							}
								?>
                            </div>
                       
                    <?php
                    }?>
                 </div>
      <?php } ?>

    
      <?php echo $form->hiddenField($model,'is_deleted'); ?> 
     
    
      <?php  if($model->created_at == NULL)
			  {
				   //echo $form->labelEx($model,'created_at'); 
				   echo $form->hiddenField($model,'created_at',array('value'=>date('d-m-Y')));
				   if(!isset($batch_id))
				   {
				   	echo $form->hiddenField($model,'batch_id',array('value'=>$_POST['batch_id']));
				   }
			  }
			  else
			  {
				  //echo $form->labelEx($model,'updated_at');
				  echo $form->hiddenField($model,'updated_at',array('value'=>date('d-m-Y'))); 
			  }
			  
		  ?>
      <span id="success-Subjects_created_at"
              class="hid input-notification-success  success png_bg right"></span>
      <div> <small></small> </div>
      <?php echo $form->error($model,'created_at'); ?> 
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
<?php 
if(isset($model->split_subject) and $model->split_subject==1){
	$split_value	=	1;
}else{
	$split_value	=	0;
}?>  
</div>
<script type="text/javascript">
var split_subject	=	<?php echo $split_value;?>;
if(split_subject == 1){
	$(".split").show();
}
$("#Subjects_split_subject").click(function(){
	if($(this).is(":checked")){
		$(".split").show();
	}else{
		$("#Subjects_subject_tilte1").val('');
		$("#Subjects_subject_tilte2").val('');
		$(".split").hide();
	}
});

$(".formbut").click(function() {
	if($('#Subjects_split_subject').is(":checked")){
		if($("#Subjects_subject_tilte1").val() == ''){
			if($("#Subjects_subject_tilte2").val() == ''){
				$("#error2").show(); 
			}else{
				$("#error2").hide();
			}
			$("#error1").show();
				 return false;
		}else{
			$("#error1").hide();
			if($("#Subjects_subject_tilte2").val() == ''){
				$("#error2").show();
				return false;
			}else{
				$("#error2").hide();
			}
			
		}
		

	}else{
		$("#error1").hide();
		$("#error2").hide();
	}
	
});

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
<script>
	function checksplit(){ //alert('aa');exit;
	var batch_id = document.getElementById('batch_id').value;
	alert(batch_id);exit;
		if(is_half_day.checked==1){
			
			$('#halfday').show();
		}
		else {
		  $('#halfday').hide();
		}
	}
</script>
