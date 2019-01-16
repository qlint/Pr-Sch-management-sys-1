<style type="text/css">
.fb_iframe_widget{ width:220px;}
.col-sm-4{
	min-height:89px;
}
.note{
	 margin-top:15px;	
}
</style>

<div class="se_panel_formwrap">
    <div class="wiz_right">        
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'students-_step1-form',
        'enableAjaxValidation'=>false,
		'htmlOptions'=>array('enctype'=>'multipart/form-data'),
    )); ?>
    	<?php echo $form->errorSummary($model); ?>
       
    
        <h4 class="text-success"><?php echo Yii::t('app','Personal Details'); ?></h4>
        <div class="row mb10">
        	<?php if(FormFields::model()->isVisible('first_name','Students','forOnlineRegistration')){	?>											
                <div class="col-sm-4">            	
                    <?php echo $form->labelEx($model,'first_name',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'first_name', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('first_name'))); ?>                    
                </div>
            <?php } ?>
                
    		<?php if(FormFields::model()->isVisible('middle_name','Students','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'middle_name',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'middle_name', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('middle_name'))); ?>                    
                </div>
            <?php } ?> 
            
            <?php if(FormFields::model()->isVisible('last_name','Students','forOnlineRegistration')){	?>   
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'last_name',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'last_name', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('last_name'))); ?>                    
                </div>            
        	<?php } ?>
            <?php if(FormFields::model()->isVisible('national_student_id','Students','forOnlineRegistration')){	?>   
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'national_student_id',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'national_student_id', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('national_student_id'))); ?>                    
                </div>            
        	<?php } ?>
            
    		<?php if(FormFields::model()->isVisible('batch_id','Students','forOnlineRegistration')){ ?>
                <div class="col-sm-4">
                   <?php echo $form->labelEx($model,'batch_id',array('class'=>'control-label','id'=>'batch_label')); ?>
                   <?php 
                        $academic_yr	= OnlineRegisterSettings::model()->findByAttributes(array('id'=>2));
                        $studentslist 	= array();
						
                        $criteria				= new CDbCriteria;
                        $criteria->condition 	= "is_deleted =:x AND is_active=:y AND academic_yr_id=:academic_yr_id";
                        $criteria->params 		= array(':x'=>'0',':y'=>'1',':academic_yr_id'=>$academic_yr->config_value);											
                        $batchlists 			= Batches::model()->findAll($criteria);						
						
                        $data	= array();
                        foreach($batchlists as $batchlist){                            
                            $data[$batchlist->id] = ucfirst($batchlist->course123->course_name).' - '.ucfirst($batchlist->name);
                        }
                        if(isset($model->batch_id) and $model->batch_id!=NULL){													
                            echo $form->dropDownList($model,'batch_id', $data, array('options' => array($model->batch_id=>array('selected'=>true)),'class'=>'form-control','id'=>'batch_id', 'encode'=>false, 'prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id")));
                        }else{
                            echo $form->dropDownList($model,'batch_id', $data, array('class'=>'form-control', 'encode'=>false, 'id'=>'batch_id','prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id")));
                        }
                    ?>                                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('date_of_birth','Students','forOnlineRegistration')){ ?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'date_of_birth',array('class'=>'control-label')); ?>
                    <?php
                    $settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
                    if($settings != NULL){
                        $date	= $settings->dateformat;
                    }
                    else{
                        $date	= 'dd-mm-yy';
                    }
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model'=>$model,
                        'attribute'=>'date_of_birth',
                        // additional javascript options for the date picker plugin
                        'options'=>array(
                            'showAnim'=>'fold',
                            'dateFormat'=>$date,
                            'changeMonth'=> true,
                            'changeYear'=>true,
                            'yearRange'=>'1900:'                            
                        ),
                        'htmlOptions'=>array(
                            'class'=>'form-control',
                            'placeholder'=>$model->getAttributeLabel('date_of_birth')
                        ),
                    ));
                    ?>                    
                </div>
            <?php } ?>
                            
       		<?php if(FormFields::model()->isVisible('birth_place','Students','forOnlineRegistration')){ ?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'birth_place',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'birth_place', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('birth_place'))); ?>                    
                </div> 
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('nationality_id','Students','forOnlineRegistration')){ ?>               
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'nationality_id',array('class'=>'control-label')); ?>
                    <?php echo $form->dropDownList($model,'nationality_id',CHtml::listData(Nationality::model()->findAll(), 'id', 'name'), array('class'=>'form-control','prompt'=>Yii::t('app','Select').' '.$model->getAttributeLabel('nationality_id'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('language','Students','forOnlineRegistration')){ ?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'language',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'language', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('language'))); ?>                    
                </div>
            <?php } ?>    
       
       		<?php if(FormFields::model()->isVisible('religion','Students','forOnlineRegistration')){ ?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'religion',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'religion', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('religion'))); ?>                    
                </div> 
            <?php } ?> 
            
            <?php if(FormFields::model()->isVisible('blood_group','Students','forOnlineRegistration')){ ?>   
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'blood_group',array('class'=>'control-label')); ?><br />
                    <?php echo $form->dropDownList($model,'blood_group',
                            array('A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'O+' => 'O+', 'O-' => 'O-', 'AB+' => 'AB+', 'AB-' => 'AB-'),
                            array('empty' => Yii::t('app','Unknown'),'class'=>'form-control')); ?>                    
                </div>  
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('student_category_id','Students','forOnlineRegistration')){ ?>             
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'student_category_id',array('class'=>'control-label')); ?>
                    <?php echo $form->dropDownList($model,'student_category_id',CHtml::listData(StudentCategories::model()->findAll(), 'id', 'name'), array('class'=>'form-control')); ?>                    
                </div>
            <?php } ?>  
            
            <?php if(FormFields::model()->isVisible('gender','Students','forOnlineRegistration')){ ?>       
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'gender',array('class'=>'control-label')); ?><br />
                    <?php echo $form->radioButtonList($model,'gender', array('M'=>Yii::t('app','Male'), 'F'=>Yii::t('app','Female')),array('separator'=>' ')); ?>                    
                </div>
            <?php } ?> 
            
            <?php
            $fields 	= FormFields::model()->getDynamicFields(1, 1, "forOnlineRegistration");
			foreach ($fields as $key => $field) {							
				if($field->form_field_type!=NULL){
					$this->renderPartial("application.modules.dynamicform.views.fields.online-form._field_".$field->form_field_type, array('model'=>$model, 'field'=>$field));                                              
				} 				                                            
			}
			?> 
           
              
        </div>
        <br />
        <h4 class="text-success"><?php echo Yii::t('app','Contact Details'); ?></h4>
        <div class="row mb10">
        	<?php if(FormFields::model()->isVisible('address_line1','Students','forOnlineRegistration')){ ?> 
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'address_line1',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'address_line1', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('address_line1'))); ?>                    
                </div>   
            <?php } ?> 
            
            <?php if(FormFields::model()->isVisible('address_line2','Students','forOnlineRegistration')){ ?>             
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'address_line2',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'address_line2', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('address_line2'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('city','Students','forOnlineRegistration')){ ?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'city',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'city', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('city'))); ?>                    
                </div>
            <?php } ?>    
        	
            <?php if(FormFields::model()->isVisible('state','Students','forOnlineRegistration')){ ?> 
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'state',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'state', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('state'))); ?>                    
                </div>  
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('pin_code','Students','forOnlineRegistration')){ ?>              
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'pin_code',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'pin_code', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('pin_code'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('country_id','Students','forOnlineRegistration')){ ?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'country_id',array('class'=>'control-label')); ?>
                    <?php echo $form->dropDownList($model,'country_id',CHtml::listData(Countries::model()->findAll(), 'id', 'name'), array('class'=>'form-control','prompt'=>Yii::t('app','Select').' '.$model->getAttributeLabel('country_id'))); ?>                    
                </div>
            <?php } ?>    
        
        	<?php if(FormFields::model()->isVisible('phone1','Students','forOnlineRegistration')){ ?> 
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'phone1',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'phone1', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('phone1'))); ?>                    
                </div>  
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('phone2','Students','forOnlineRegistration')){ ?>              
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'phone2',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'phone2', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('phone2'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('email','Students','forOnlineRegistration')){ ?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'email',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'email', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('email'))); ?>                    
                </div>
            <?php } ?> 
           <div class="texarea-bottom"> 
            <?php
            $fields     = FormFields::model()->getDynamicFields(1, 2, "forOnlineRegistration");
            foreach ($fields as $key => $field) {                
                if($field->form_field_type!=NULL){
                    $this->renderPartial("application.modules.dynamicform.views.fields.online-form._field_".$field->form_field_type, array('model'=>$model, 'field'=>$field));                                                
                }                                               
            }
            ?> 
            </div>              
        </div> 
        
    <?php if(FormFields::model()->isVisible('photo_data','Students','forOnlineRegistration')){ ?>   
        <h4 class="text-success"><?php echo Yii::t('app','Upload Photo'); ?></h4> 
         <div class="row mb10">                       
            <div class="col-sm-12">
            <div class="custm_file">
                <?php 
					if($model->isNewRecord)
					{
				?>
                <label for="Students_photo_data" class="custom-file-upload"><i class="fa fa-cloud-upload"></i> <?php echo Yii::t('app', 'Upload File'); ?></label>
                <?php		
						echo $form->fileField($model,'photo_data', array('onChange'=>'readFileName()'));
					}
					else{
						if($model->photo_file_name == NULL){
				?>
                        <label for="Students_photo_data" class="custom-file-upload"><i class="fa fa-cloud-upload"></i> <?php echo Yii::t('app', 'Upload File'); ?></label>
                        <?php
							echo $form->fileField($model,'photo_data', array('class'=>'file-upload')); 							
						}
						else{
							if(Yii::app()->controller->action->id == 'step1' and isset($_REQUEST['token'])){																
								if($model->photo_file_name != NULL){
									$path = Students::model()->getProfileImagePath($model->id);
									echo '<img class="imgbrder" src="'.$path.'" alt="'.$model->photo_file_name.'" width="100" height="100" />';
								}
								echo CHtml::link(Yii::t('app','Remove'), array('registration/remove', 'token'=>$this->encryptToken($model->id)),array('confirm'=>Yii::t('app','Are you sure?'))); 
							}
							else if(Yii::app()->controller->action->id=='step1'){
								echo CHtml::hiddenField('photo_file_name',$model->photo_file_name);
								echo CHtml::hiddenField('photo_content_type',$model->photo_content_type);
								echo CHtml::hiddenField('photo_file_size',$model->photo_file_size);
								echo CHtml::hiddenField('photo_data',bin2hex($model->photo_data));
								echo '<img class="imgbrder" src="'.$this->createUrl('Registration/DisplaySavedImage&id='.$model->primaryKey).'" alt="'.$model->photo_file_name.'" width="100" height="100" />';
							}
						}
					}
					?>
                    	<span id="display-file-name"></span>
                    </div>
                    
                    <div class="row mb12">
                    	<div id="image_size_error" style="color:#F00;"></div>                
     	 				<div class="upload_file_not"><?php echo Yii::t('app','Maximum file size is 1MB. Allowed file types are png,gif,jpeg,jpg'); ?></div>
                    </div>
            </div>
        </div>
    <?php } ?>    
    <br />
         <div class="row mb10">
         	<div class="col-md-4">
                <?php echo CHtml::submitButton(Yii::t('app','Save').' & '.Yii::t('app','Continue'),array('id'=>'submit_button_form','class'=>"btn btn-success btn-block")); ?>
            </div>
        </div>
    
    <?php $this->endWidget(); ?>
    </div><!-- form -->
</div>
<script type="text/javascript">
$('#submit_button_form').click(function(ev) {	
	var file_size = $('#Students_photo_data')[0].files[0].size;	
	if(file_size>1048576)//File upload size limit to 1mb
	{		   	
		$('#image_size_error').html('<?php echo Yii::t('app','File size is greater than 1MB'); ?>');		
		return false;
	}		
});


function readFileName() {	
	var name	= $('#Students_photo_data')[0].files[0].name;
	$('#display-file-name').html(name);
}
</script>                
