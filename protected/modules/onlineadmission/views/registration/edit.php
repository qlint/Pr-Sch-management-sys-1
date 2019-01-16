<!DOCTYPE html>

 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/online_register.css" />
    <link rel="icon" type="image/ico" href="<?php echo Yii::app()->request->baseUrl; ?>/uploadedfiles/school_logo/favicon.ico"/>
    <title><?php $college=Configurations::model()->findByPk(1); ?><?php echo $college->config_value ; ?></title>
<style type="text/css">
 input[type="text"], input[type="password"], input[type="textarea"]{  border-radius: 2px;
    padding: 10px;
    height: auto;
    font-size: 13px;
	display: block;
    width: 100%;
	border: 1px solid #ccc;
	background:#fff}
	
.form-control{ width:100% !important }
	
.formWrapper{ padding:40PX 35PX;}
.col-sm-4{
	height:90px;
}

</style>


<?php $logo=Logo::model()->findAll();?>
        	
<div class="loginboxWrapper">
<div class="logo">            
		<?php
			$settings = UserSettings::model()->findByAttributes(array('user_id'=>1)); 
			if($logo!=NULL){
				echo '<img src="'.Yii::app()->request->baseUrl.'/uploadedfiles/school_logo/'.$logo[0]->photo_file_name.'" alt="'.$logo[0]->photo_file_name.'" border="0" height="55" />';
			}
		?>
</div>

<div class="hed"><h1><?php echo Yii::t('app','Edit Profile'); ?></h1></div>
<div class="cont_right formWrapper">
    <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'registraion-form',
            'enableAjaxValidation'=>false,
            'htmlOptions'=>array('enctype'=>'multipart/form-data'),
        )); ?>
     
    <?php 
		if($form->errorSummary($model)){
				echo '<div class="notify-header-n">'.Yii::t('app','Student related errors').'</div>';
				echo $form->errorSummary($model); 
			}
			if($form->errorSummary($model_1)){
				echo '<div class="notify-header-n">'.Yii::t('app','Guardian related errors').'</div>';
				echo $form->errorSummary($model_1);
			}
	?>       
	<p class="note"><?php echo Yii::t('app','Fields with');?> <span class="required">*</span> <?php echo Yii::t('app','are required'); ?></p>
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
                        $academic_yr = OnlineRegisterSettings::model()->findByAttributes(array('id'=>2));
                        $studentslist = array();
                        $criteria=new CDbCriteria;
                        $criteria->condition = "is_deleted =:x AND is_active=:y AND academic_yr_id=:academic_yr_id";
                        $criteria->params = array(':x'=>'0',':y'=>'1',':academic_yr_id'=>$academic_yr->config_value);											
                        $batchlists = Batches::model()->findAll($criteria);						
                        $data = array();
                        foreach ($batchlists as $batchlist){                            
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
            
            <?php if(FormFields::model()->isVisible('date_of_birth','Students','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'date_of_birth',array('class'=>'control-label')); ?>
                    <?php                    
                    if($settings!=NULL){
                        $date=$settings->dateformat;
                        if($model->date_of_birth!=NULL){
                            $model->date_of_birth = date($settings->displaydate,strtotime($model->date_of_birth));
                        }
                    }
                    else{
                        $date = 'dd-mm-yy';
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
                            
        	<?php if(FormFields::model()->isVisible('birth_place','Students','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'birth_place',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'birth_place', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('birth_place'))); ?>                    
                </div>   
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('nationality_id','Students','forOnlineRegistration')){	?>             
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'nationality_id',array('class'=>'control-label')); ?>
                    <?php echo $form->dropDownList($model,'nationality_id',CHtml::listData(Nationality::model()->findAll(), 'id', 'name'), array('class'=>'form-control','prompt'=>Yii::t('app','Select').' '.$model->getAttributeLabel('nationality_id'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('language','Students','forOnlineRegistration')){	?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'language',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'language', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('language'))); ?>                    
                </div>
            <?php } ?>    
        	
            <?php if(FormFields::model()->isVisible('religion','Students','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'religion',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'religion', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('religion'))); ?>                    
                </div> 
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('blood_group','Students','forOnlineRegistration')){	?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'blood_group',array('class'=>'control-label')); ?><br />
                    <?php echo $form->dropDownList($model,'blood_group',
                            array('A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'O+' => 'O+', 'O-' => 'O-', 'AB+' => 'AB+', 'AB-' => 'AB-'),
                            array('empty' => Yii::t('app','Unknown'),'class'=>'form-control')); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('student_category_id','Students','forOnlineRegistration')){	?>               
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'student_category_id',array('class'=>'control-label')); ?>
                    <?php echo $form->dropDownList($model,'student_category_id',CHtml::listData(StudentCategories::model()->findAll(), 'id', 'name'), array('class'=>'form-control')); ?>                    
                </div> 
            <?php } ?>               
        	
            <?php if(FormFields::model()->isVisible('gender','Students','forOnlineRegistration')){	?>
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
        	<?php if(FormFields::model()->isVisible('address_line1','Students','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'address_line1',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'address_line1', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('address_line1'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('address_line2','Students','forOnlineRegistration')){	?>                
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'address_line2',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'address_line2', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('address_line2'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('city','Students','forOnlineRegistration')){	?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'city',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'city', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('city'))); ?>                    
                </div>
            <?php } ?>    
        	
            <?php if(FormFields::model()->isVisible('state','Students','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'state',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'state', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('state'))); ?>                    
                </div>   
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('pin_code','Students','forOnlineRegistration')){	?>             
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'pin_code',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'pin_code', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('pin_code'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('country_id','Students','forOnlineRegistration')){	?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'country_id',array('class'=>'control-label')); ?>
                    <?php echo $form->dropDownList($model,'country_id',CHtml::listData(Countries::model()->findAll(), 'id', 'name'), array('class'=>'form-control','prompt'=>Yii::t('app','Select').' '.$model->getAttributeLabel('country_id'))); ?>                    
                </div>
            <?php } ?>
                            
        	<?php if(FormFields::model()->isVisible('phone1','Students','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'phone1',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'phone1', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('phone1'))); ?>                    
                </div>  
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('phone2','Students','forOnlineRegistration')){	?>              
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'phone2',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'phone2', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('phone2'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('email','Students','forOnlineRegistration')){	?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'email',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'email', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('email'))); ?>                    
                </div>
            <?php } ?> 
            <?php
            $fields     = FormFields::model()->getDynamicFields(1, 2, "forOnlineRegistration");
            foreach ($fields as $key => $field) {                
                if($field->form_field_type!=NULL){
                    $this->renderPartial("application.modules.dynamicform.views.fields.online-form._field_".$field->form_field_type, array('model'=>$model, 'field'=>$field));                                                
                }                                               
            }
            ?>    
        </div>
        <br />
        <h4 class="text-success"><?php echo Yii::t('app','Upload Photo'); ?></h4> 
        <div class="row mb10">                       
            <div class="col-sm-12">
                <?php 					
					if($model->photo_file_name==NULL){
						echo $form->fileField($model,'photo_data'); 						 
					}else{
						 if(Yii::app()->controller->action->id=='edit'){								
							echo CHtml::link(Yii::t('app','Remove'), array('registration/remove','id'=>$model->id),array('confirm'=>Yii::t('app','Are you sure?'),'style'=>'display:block')); 
							if($model->photo_file_name!=NULL){
								$path = Students::model()->getProfileImagePath($model->id);
								echo '<img class="imgbrder" src="'.$path.'" alt="'.$model->photo_file_name.'" width="100" height="100" />';
							}
						}							
					}
					
				?>
                    <div class="row mb12">
                    	<div id="image_size_error" style="color:#F00;"></div>                
     	 				<div><?php echo Yii::t('app','Maximum file size is 1MB. Allowed file types are png,gif,jpeg,jpg'); ?></div>
                    </div>
            </div>
        </div>
		<br />
        <h4 style="color:#07398D"><?php echo Yii::t('app','GUARDIAN DETAILS'); ?></h4>
        <h4 class="text-success"><?php echo Yii::t('app','Personal Details'); ?></h4>
        <div class="row mb10">
        	<?php if(FormFields::model()->isVisible('first_name','Guardians','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model_1,'first_name',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model_1,'first_name', array('class'=>'form-control', 'placeholder'=>$model_1->getAttributeLabel('first_name'))); ?>                    
                </div>
            <?php } ?>    
    		
            <?php if(FormFields::model()->isVisible('last_name','Guardians','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model_1,'last_name',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model_1,'last_name', array('class'=>'form-control', 'placeholder'=>$model_1->getAttributeLabel('last_name'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('relation','Guardians','forOnlineRegistration')){	?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model_1,'relation',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model_1,'relation', array('class'=>'form-control', 'placeholder'=>$model_1->getAttributeLabel('relation'))); ?>                    
                </div>
            <?php } ?>    
        	
            <?php if(FormFields::model()->isVisible('dob','Guardians','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model_1,'dob',array('class'=>'control-label')); ?>
                    <?php				
                    if($settings!=NULL){
                        $date = $settings->dateformat;
                        if($model_1->dob!=NULL and $model_1->dob != '0000-00-00'){
                            $model_1->dob = date($settings->displaydate,strtotime($model_1->dob));
                        }else{
                            $model_1->dob = '';
                        }
                    }
                    else{
                        $date = 'dd-mm-yy';
                    }
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model'=>$model_1,
                        'attribute'=>'dob',
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
                            'placeholder'=>$model_1->getAttributeLabel('dob')
                        ),
                    ));
                    ?>                    
                </div>
    		<?php } ?>
            
            <?php if(FormFields::model()->isVisible('education','Guardians','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model_1,'education',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model_1,'education', array('class'=>'form-control', 'placeholder'=>$model_1->getAttributeLabel('education'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('occupation','Guardians','forOnlineRegistration')){	?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model_1,'occupation',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model_1,'occupation', array('class'=>'form-control', 'placeholder'=>$model_1->getAttributeLabel('occupation'))); ?>                    
                </div>
            <?php } ?>    
        	
            <?php if(FormFields::model()->isVisible('income','Guardians','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model_1,'income',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model_1,'income', array('class'=>'form-control', 'placeholder'=>$model_1->getAttributeLabel('income'))); ?>                    
                </div>
            <?php } ?> 
            <?php
				$fields     = FormFields::model()->getDynamicFields(2, 1, "forOnlineRegistration");
				foreach ($fields as $key => $field) {
					if($field->form_field_type!=NULL){
						$this->renderPartial("application.modules.dynamicform.views.fields.online-form._field_".$field->form_field_type, array('model'=>$model_1, 'field'=>$field));                                                
					}                                               
				}
			?>   
        </div>
        <br />
        <h4 class="text-success"><?php echo Yii::t('app','Contact Details'); ?></h4>
        <div class="row mb10">
        	<?php if(FormFields::model()->isVisible('email','Guardians','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model_1,'email',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model_1,'email', array('class'=>'form-control', 'placeholder'=>$model_1->getAttributeLabel('email'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('office_phone1','Guardians','forOnlineRegistration')){	?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model_1,'office_phone1',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model_1,'office_phone1', array('class'=>'form-control', 'placeholder'=>$model_1->getAttributeLabel('office_phone1'))); ?>                    
                </div>
            <?php } ?> 
            
            <?php if(FormFields::model()->isVisible('office_phone2','Guardians','forOnlineRegistration')){	?>   
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model_1,'office_phone2',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model_1,'office_phone2', array('class'=>'form-control', 'placeholder'=>$model_1->getAttributeLabel('office_phone2'))); ?>                    
                </div>
            <?php } ?>    
        	
            <?php if(FormFields::model()->isVisible('mobile_phone','Guardians','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model_1,'mobile_phone',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model_1,'mobile_phone', array('class'=>'form-control', 'placeholder'=>$model_1->getAttributeLabel('mobile_phone'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('office_address_line1','Guardians','forOnlineRegistration')){	?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model_1,'office_address_line1',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model_1,'office_address_line1', array('class'=>'form-control', 'placeholder'=>$model_1->getAttributeLabel('office_address_line1'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('office_address_line2','Guardians','forOnlineRegistration')){	?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model_1,'office_address_line2',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model_1,'office_address_line2', array('class'=>'form-control', 'placeholder'=>$model_1->getAttributeLabel('office_address_line2'))); ?>                    
                </div>
            <?php } ?>    
        	
            <?php if(FormFields::model()->isVisible('city','Guardians','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model_1,'city',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model_1,'city', array('class'=>'form-control', 'placeholder'=>$model_1->getAttributeLabel('city'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('state','Guardians','forOnlineRegistration')){	?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model_1,'state',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model_1,'state', array('class'=>'form-control', 'placeholder'=>$model_1->getAttributeLabel('state'))); ?>                    
                </div>
            <?php } ?>    
            
            <?php if(FormFields::model()->isVisible('country_id','Guardians','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model_1,'country_id',array('class'=>'control-label')); ?>
                    <?php echo $form->dropDownList($model_1,'country_id',CHtml::listData(Countries::model()->findAll(), 'id', 'name'), array('class'=>'form-control','prompt'=>Yii::t('app','Select').' '.$model_1->getAttributeLabel('country_id'))); ?>                    
                </div>
             <?php } ?> 
             <?php
				$fields     = FormFields::model()->getDynamicFields(2, 2, "forOnlineRegistration");
				foreach ($fields as $key => $field) {
					if($field->form_field_type!=NULL){
						$this->renderPartial("application.modules.dynamicform.views.fields.online-form._field_".$field->form_field_type, array('model'=>$model_1, 'field'=>$field));                                                
					}                                               
				}
			?>  
        </div>

        <div style="padding:0px 0 0 0px; text-align:left">
        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Register') : Yii::t('app','Save'),array('id'=>'submit_button_form','class'=>'formbut')); ?>
        <?php $this->endWidget(); ?>
        </div> <!-- END div class="cont_right formWrapper" -->
    <div class="clear"></div>
</div> <!-- END div class="loginboxWrapper" -->
</body>
</html>
<script type="application/javascript">
$(".control-label").removeClass("required");
$('#submit_button_form').click(function(ev) {
	
	var file_size 	= $('#Students_photo_data')[0].files[0].size;	
	if(file_size>1048576)//File upload size limit to 1mb
	{		   	
		$('#image_size_error').html('<?php echo Yii::t('app','File size is greater than 1MB'); ?>');	
		$('#RegisteredStudents_photo_data').focus();	
		return false;
	}		
});
</script>

