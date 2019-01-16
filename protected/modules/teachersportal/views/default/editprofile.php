<style>
.form-group{
	margin-bottom:15px !important;
}
label.error{
	margin-top:0 !important;	
}
.required{
	 color:#4a535e;	
}
</style>


<?php $this->renderPartial('leftside');?> 
<div class="pageheader">
    <div class="col-lg-8">
    	<h2><i class="fa fa-user"></i><?php echo Yii::t('app','Profile');?><span><?php echo Yii::t('app','Edit your profile here');?></span></h2>
    </div>
    <div class="col-lg-2"></div>    
    <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:');?></span>
        <ol class="breadcrumb">        
        	<li class="active"><?php echo Yii::t('app','Profile Edit');?></li>
        </ol>
    </div>    
    <div class="clearfix"></div>
</div>
<div class="contentpanel">
	<div class="people-item">
        <div class="profile_block">        
            <div class="proflImg_block img_Inner_stn">                         
                <a href="javascript:void(0);" class="pull-left">
					<?php
						$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));						
						if($employee->photo_file_name!=NULL){ 
							$path = Employees::model()->getProfileImagePath($employee->id);
							echo '<img class=" thumbnail"  src="'.$path.'" />';
						}
						elseif($employee->gender=='M'){
							echo '<img class="thumbnail"  src="images/portal/prof-img_male.png" alt='.$employee->first_name.' />'; 
						}
						elseif($employee->gender=='F'){
							echo '<img class="thumbnail"  src="images/portal/prof-img_female.png" alt='.$employee->first_name.' />';
						}
                    ?>                           
                </a>                                
            </div>              
             <div class="proflCnt_block">
                            <h4><?php echo ucfirst($employee->first_name).' '.ucfirst($employee->middle_name).' '.ucfirst($employee->last_name);?></h4>
                            <p><span><?php echo Yii::t('app','Job Title').' ';?></span>:<?php echo $employee->job_title; ?></p>
                            <p><span><?php echo Yii::t('app','Department').' ';?></span>:<?php $department = EmployeeDepartments::model()->findByAttributes(array('id'=>$employee->employee_department_id));
                            echo $department->name;?></p>
                            <p><span><?php echo Yii::t('app','Teacher No').' ';?></span>:<?php echo $employee->employee_number; ?></p>
                            
                        </div>
           	             <?php echo CHtml::link('<span>'.Yii::t('app','View Profile').'</span>',array('profile'),array('class'=>'addbttn btn_edit_prfl'));?>
        </div> 
        

        
        
               
	</div>
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title"><?php echo Yii::t('app','Edit Profile');?></h3></div>
        <?php
			$form=$this->beginWidget('CActiveForm', array(
				'id'=>'teacher-form',
				'enableAjaxValidation'=>false,					
			));
		?>
				<div class="panel-body">
                    <?php                         
						$settings = UserSettings::model()->findByAttributes(array('user_id'=>1));
						if($settings!=NULL){						
							$date = $settings->dateformat;
							if($model->date_of_birth != NULL){
								$model->date_of_birth=date($settings->displaydate,strtotime($model->date_of_birth));
							}
						}
						else{
							$date = 'dd-mm-yy';
						} 
                    ?>
                    <h4><?php echo Yii::t('app','Personal Details'); ?></h4> 
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'first_name',array('class'=>'control-label'));
                                    echo $form->textField($model,'first_name',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'first_name'); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'middle_name',array('class'=>'control-label'));
                                    echo $form->textField($model,'middle_name',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'middle_name'); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'last_name',array('class'=>'control-label'));
                                    echo $form->textField($model,'last_name',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'last_name'); 
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">	
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'gender',array('class'=>'control-label'));
                                    echo $form->dropDownList($model,'gender',array('M' => Yii::t('app','Male'), 'F' => Yii::t('app','Female')),array('class'=>'form-control mb15','empty' => Yii::t('app','Select Gender')));
                                    echo $form->error($model,'gender'); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'date_of_birth',array('class'=>'control-label'));
                                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(									
                                        'attribute'=>'date_of_birth',
                                        'model'=>$model,
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
											'readonly'=>true
                                        ),
                                    ));
                                    echo $form->error($model,'date_of_birth');
                                ?>
                            </div>
                        </div>   
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'blood_group',array('class'=>'control-label'));
                                    echo $form->dropDownList($model,'blood_group',array('A+' => 'A+', 'A-' => 'A-', 'B+' => 'B+', 'B-' => 'B-', 'O+' => 'O+', 'O-' => 'O-', 'AB+-' => 'AB+', 'AB-' => 'AB-'),array('class'=>'form-control mb15', 'empty' => Yii::t('app','Unknown')));
                                    echo $form->error($model,'blood_group'); 
                                ?>
                            </div>
                        </div>     
                    </div>
                    <div class="row">	
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'marital_status',array('class'=>'control-label'));
                                    echo $form->dropDownList($model,'marital_status',array('Single'=>Yii::t('app','Single'),'Married'=>Yii::t('app','Married'),'Divorced'=>Yii::t('app','Divorced')),array('class'=>'form-control mb15','empty' => Yii::t('app','Select Marital Status')));								
                                    echo $form->error($model,'marital_status'); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'children_count',array('class'=>'control-label'));
                                    echo $form->textField($model,'children_count',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'children_count'); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'father_name',array('class'=>'control-label'));
                                    echo $form->textField($model,'father_name',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'father_name'); 
                                ?>
                            </div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'mother_name',array('class'=>'control-label'));
                                    echo $form->textField($model,'mother_name',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'mother_name'); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'husband_name',array('class'=>'control-label'));
                                    echo $form->textField($model,'husband_name',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'husband_name'); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'nationality_id',array('class'=>'control-label'));
                                    echo $form->dropDownList($model,'nationality_id',CHtml::listData(Nationality::model()->findAll(),'id','name'),array('class'=>'form-control mb15','empty'=>Yii::t('app','Select Nationality')));
                                    echo $form->error($model,'nationality_id'); 
                                ?>
                            </div>
                        </div>
                    </div>   
                    <h4><?php echo Yii::t('app','Home Address'); ?></h4>    
                    <div class="row">	
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'home_address_line1',array('class'=>'control-label'));
                                    echo $form->textField($model,'home_address_line1',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'home_address_line1'); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'home_address_line2',array('class'=>'control-label'));
                                    echo $form->textField($model,'home_address_line2',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'home_address_line2'); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'home_city',array('class'=>'control-label'));
                                    echo $form->textField($model,'home_city',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'home_city'); 
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">	
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'home_state',array('class'=>'control-label'));
                                    echo $form->textField($model,'home_state',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'home_state'); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'home_country_id',array('class'=>'control-label'));
                                    echo $form->dropDownList($model,'home_country_id',CHtml::listData(Countries::model()->findAll(),'id','name'),array('class'=>'form-control mb15','empty'=>Yii::t('app','Select Country')));
                                    echo $form->error($model,'home_country_id'); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'home_pin_code',array('class'=>'control-label'));
                                    echo $form->textField($model,'home_pin_code',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'home_pin_code'); 
                                ?>
                            </div>
                        </div>
                    </div> 
                    <h4><?php echo Yii::t('app','Office Address'); ?></h4>    
                    <div class="row">	
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'office_address_line1',array('class'=>'control-label'));
                                    echo $form->textField($model,'office_address_line1',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'office_address_line1'); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'office_address_line2',array('class'=>'control-label'));
                                    echo $form->textField($model,'office_address_line2',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'office_address_line2'); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'office_city',array('class'=>'control-label'));
                                    echo $form->textField($model,'office_city',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'office_city'); 
                                ?>
                            </div>
                        </div>
                    </div>    
                    <div class="row">	
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'office_state',array('class'=>'control-label'));
                                    echo $form->textField($model,'office_state',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'office_state'); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'office_country_id',array('class'=>'control-label'));
                                    echo $form->dropDownList($model,'office_country_id',CHtml::listData(Countries::model()->findAll(),'id','name'),array('class'=>'form-control mb15','empty'=>Yii::t('app','Select Country')));
                                    echo $form->error($model,'office_country_id'); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'office_pin_code',array('class'=>'control-label'));
                                    echo $form->textField($model,'office_pin_code',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'office_pin_code'); 
                                ?>
                            </div>
                        </div>
                    </div> 
                    <h4><?php echo Yii::t('app','Contact Details'); ?></h4>    
                    <div class="row">	
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'office_phone1',array('class'=>'control-label'));
                                    echo $form->textField($model,'office_phone1',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'office_phone1'); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'office_phone2',array('class'=>'control-label'));
                                    echo $form->textField($model,'office_phone2',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'office_phone2'); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'mobile_phone',array('class'=>'control-label'));
                                    echo $form->textField($model,'mobile_phone',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'mobile_phone'); 
                                ?>
                            </div>
                        </div>
                    </div>   
                    <div class="row">	
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'home_phone',array('class'=>'control-label'));
                                    echo $form->textField($model,'home_phone',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'home_phone'); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'email',array('class'=>'control-label'));
                                    echo $form->textField($model,'email',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'email'); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?php 
                                    echo $form->labelEx($model,'fax',array('class'=>'control-label'));
                                    echo $form->textField($model,'fax',array('class'=>'form-control','size'=>30,'maxlength'=>255));
                                    echo $form->error($model,'fax'); 
                                ?>
                            </div>
                        </div>
                    </div>                                                  
				</div>
                <div class="panel-footer">
					<?php echo CHtml::submitButton(Yii::t('app','Save'),array('class'=>'btn btn-primary')); ?>
                </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
