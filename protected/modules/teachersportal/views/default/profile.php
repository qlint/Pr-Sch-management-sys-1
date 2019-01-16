<style type="text/css">
.upload{
     height: 19px;
    left: 8px;
    position: absolute;
    top: 72px;
    width: 25px;
	background: url(images/camera_hover.png) no-repeat ;
	transition: 0.5s all ease;
	-webkit-transition: 0.5s all ease;
	-moz-transition: 0.5s all ease;
	-o-transition: 0.5s all ease;
	-ms-transition: 0.5s all ease;
}	
.upload:hover{ 
	text-decoration:none;
  	background-image: url(images/camera_hover.png);
	top: 69px;
}	
  
.document_table th{
	color:#333;
 	font-size:14px !important;
}
.loading_app{
	background-image:url(images/loading_app.gif);
	height:30px;
	float:left;
	width:30px;
	margin-left:10px;
	display:none
}		
.prof_img{
	margin:10px;
	position:relative;
	left: -8px;
}
.error{
	color:#F00;
	font-size: 12px;
}
</style>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js_plugins/jupload/jupload.js"></script>
<?php 
	$this->renderPartial('leftside');
	$employee		= Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$employee_id 	= $employee->id;			
	$settings		= UserSettings::model()->findByAttributes(array('user_id'=>1));
?>
        
<div class="pageheader">
    <div class="col-lg-8">
    	<h2>
        	<i class="fa fa-user"></i>
			<?php echo Yii::t('app','Profile');?>
            <span><?php echo Yii::t('app','View your profile here');?></span>
        </h2>
    </div>
    <div class="col-lg-2"></div>    
    <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:');?></span>
        <ol class="breadcrumb">
        	<li class="active"><?php echo Yii::t('app','Profile');?></li>
        </ol>
    </div>    
    <div class="clearfix"></div>
</div>
    
<div class="contentpanel">
    <div class="people-item">
        <div class="profile_block">
            <div class="proflImg_block img_Inner_stn">   
                <a href="javascript:void(0);">
					<?php
                    if($employee->photo_file_name != NULL){ 
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
                <a href="javascript:void(0)" id="emp_image" data-url=""><div class="upload"></div></a>
                <div id="displayPercentage" style="position:absolute;top:30px; left:30px">
                    <div class="loading_app"></div>
                    <div id="percentage" style="color:#FFF !important; font-size:14px; text-shadow:0px 0px 2px #000; color:#fff"></div>
                </div>
            </div>
            <div class="proflCnt_block ">
                <h4><?php echo ucfirst($employee->first_name).' '.ucfirst($employee->middle_name).' '.ucfirst($employee->last_name);?></h4>
                <p><span><?php echo Yii::t('app','Job Title').' ';?></span>: <?php echo $employee->job_title; ?></p>
                <p><span><?php echo Yii::t('app','Department').' ';?></span>: <?php $department = EmployeeDepartments::model()->findByAttributes(array('id'=>$employee->employee_department_id));
                echo $department->name;?></p>
                <p><span><?php echo Yii::t('app','Teacher No').' ';?></span>: <?php echo $employee->employee_number; ?></p>
                
            </div> 
            <?php echo CHtml::link('<span>'.Yii::t('app','Edit Profile').'</span>',array('editprofile'),array('class'=>'addbttn btn_edit_prfl'));?>               
        </div>
    </div>                
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel_block">
                            <div class="panel-heading"><h5 class="panel-title"><?php echo Yii::t('app', 'General Details'); ?></h5> </div>
                                <div class="people-item">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" cellspacing="0" cellpadding="0">
                                            <thead>
                                                <tr>
                                                    <th width="200"><?php echo $employee->getAttributeLabel('joining_date');?></th>
                                                    <td>
                                                    	<?php
															if($employee->joining_date != NULL and $employee->joining_date != '0000-00-00'){
																if($settings){
																	echo date($settings->displaydate, strtotime($employee->joining_date));
																}
																else{
																	echo $employee->joining_date;
																}
															}
															else{
																echo '-';
															}
														?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="200"><?php echo $employee->getAttributeLabel('gender');?></th>
                                                    <td>
                                                    	<?php
															if($employee->gender == 'M'){
																echo Yii::t('app','Male');
															}
															elseif($employee->gender == 'F') {
																echo Yii::t('app','Female');
															}
															else{
																echo '-';
															}
														?>
                                                    </td>
                                                </tr>                                            
                                                 <tr>
                                                    <th width="200"><?php echo $employee->getAttributeLabel('date_of_birth');?></th>
                                                    <td>
                                                    	<?php
															if($employee->date_of_birth != NULL and $employee->date_of_birth != '0000-00-00'){
																if($settings){
																	echo date($settings->displaydate, strtotime($employee->date_of_birth));
																}
																else{
																	echo $employee->date_of_birth;
																}
															}
															else{
																echo '-';
															}
														?>                                                    
                                                    </td>
                                                </tr>
                                                    <th width="200"><?php echo $employee->getAttributeLabel('employee_department_id');?></th>
                                                    <td>
                                                    	<?php 
															if($employee->employee_department_id){
																$department = EmployeeDepartments::model()->findByAttributes(array('id'=>$employee->employee_department_id));
																if($department){
																	echo ucfirst($department->name);
																}
																else{
																	echo '-';
																}
															}													
															else{
																echo '-';
															}
														?>	
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="200"><?php echo $employee->getAttributeLabel('employee_position_id');?></th>
                                                    <td>
                                                    	<?php 
															if($employee->employee_position_id){
																$position = EmployeePositions::model()->findByAttributes(array('id'=>$employee->employee_position_id));
																if($position){
																	echo ucfirst($position->name);
																}
																else{
																	echo '-';
																}
															}													
															else{
																echo '-';
															}
														?>	
                                                    </td>
                                                </tr>
                                                <tr>
                                                	<th width="200"><?php echo $employee->getAttributeLabel('employee_category_id');?></th>
                                                    <td>
                                                    	<?php 
															if($employee->employee_category_id){
																$category = EmployeeCategories::model()->findByAttributes(array('id'=>$employee->employee_category_id));
																if($category){
																	echo ucfirst($category->name);
																}
																else{
																	echo '-';
																}
															}													
															else{
																echo '-';
															}
														?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                	<th width="200"><?php echo $employee->getAttributeLabel('employee_grade_id');?></th>
                                                    <td>
                                                    	<?php 
															if($employee->employee_grade_id){
																$grade = EmployeeGrades::model()->findByAttributes(array('id'=>$employee->employee_grade_id));
																if($grade){
																	echo ucfirst($grade->name);
																}
																else{
																	echo '-';
																}
															}													
															else{
																echo '-';
															}
														?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                	<th width="200"><?php echo $employee->getAttributeLabel('job_title');?></th>
                                                    <td>
                                                    	<?php 
															if($employee->job_title){
																echo ucfirst($employee->job_title);
															}													
															else{
																echo '-';
															}
														?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                	<th width="200"><?php echo $employee->getAttributeLabel('qualification');?></th>
                                                    <td>
                                                    	<?php 
															if($employee->qualification){
																echo ucfirst($employee->qualification);
															}													
															else{
																echo '-';
															}
														?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="200"><?php echo Yii::t('app','Total Experience');?></th>
                                                    <td>
                                                    	<?php
															if($employee->experience_year and !$employee->experience_month)
																echo $employee->experience_year." ".Yii::t('app','year(s)');
															elseif(!$employee->experience_year and $employee->experience_month)
																echo ' '.$employee->experience_month." ".Yii::t('app','month(s)');
															elseif($employee->experience_year and $employee->experience_month)
																echo $employee->experience_year." ".Yii::t('app','year(s)')." ".Yii::t('app','and')." ".$employee->experience_month." ".Yii::t('app','month(s)');
															else
																echo '-';
														?>
                                                    </td>
                                                </tr>
    <tr>
                                                    <th colspan="2"><?php echo $employee->getAttributeLabel('experience_detail');?></th>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                    	<?php 
															if($employee->experience_detail){
																echo ucfirst($employee->experience_detail);
															}
															else{
																echo '-';
															}
														?>
                                                    </td>
                                                </tr>                                          
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>   
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel_block">
                            <div class="panel-heading"><h5 class="panel-title"><?php echo Yii::t('app', 'Personal Details'); ?></h5> </div>
                                <div class="people-item">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" cellspacing="0" cellpadding="0">
                                            <thead>
                                                <tr>
                                                    <th width="200"><?php echo $employee->getAttributeLabel('marital_status');?></th>
                                                    <td>
                                                    	<?php 
															if($employee->marital_status){
																echo ucfirst($employee->marital_status);
															}
															else{
																echo '-';
															}
														?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="200"><?php echo $employee->getAttributeLabel('children_count');?></th>
                                                    <td>
                                                    	<?php 
															if($employee->children_count){
																echo $employee->children_count;
															}
															else{
																echo '-';
															}
														?>	
                                                    </td>
                                                </tr>
                                                                                          
                                                <tr>
                                                    <th width="200"><?php echo $employee->getAttributeLabel('father_name');?></th>
                                                    <td>
                                                    	<?php 
															if($employee->father_name){
																echo ucfirst($employee->father_name);
															}
															else{
																echo '-';
															}
														?>	
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="200"><?php echo $employee->getAttributeLabel('mother_name');?></th>
                                                    <td>
                                                    	<?php 
															if($employee->mother_name){
																echo ucfirst($employee->mother_name);
															}
															else{
																echo '-';
															}
														?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="200"><?php echo $employee->getAttributeLabel('husband_name');?></th>
                                                    <td>
                                                    	<?php 
															if($employee->husband_name){
																echo ucfirst($employee->husband_name);
															}
															else{
																echo '-';
															}
														?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="200"><?php echo $employee->getAttributeLabel('blood_group');?></th>
                                                    <td>
                                                    	<?php 
															if($employee->blood_group){
																echo $employee->blood_group;
															}
															else{
																echo '-';
															}
														?>	
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="200"><?php echo $employee->getAttributeLabel('nationality_id');?></th>
                                                    <td>
                                                    	<?php 
															if($employee->nationality_id){
																$nationality = Nationality::model()->findByPk($employee->nationality_id);
																if($nationality){
																	echo ucfirst($nationality->name);
																}
																else{
																	echo '-';
																}
															}
															else{
																echo '-';
															}
														?>
                                                    </td>
                                                </tr>                                            
                                                
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                           
                    </div>	
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel_block">
                    <div class="panel-heading"><h5 class="panel-title"><?php echo Yii::t('app','Home Address'); ?></h5> </div>
                        <div class="people-item">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" cellspacing="0" cellpadding="0">
                                    <thead>
                                        <tr>
                                            <th width="200"><?php echo $employee->getAttributeLabel('home_address_line1');?></th>
                                            <td>
                                                <?php 
                                                    if($employee->home_address_line1){
                                                        echo ucfirst($employee->home_address_line1);
                                                    }
                                                    else{
                                                        echo '-';
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="200"><?php echo $employee->getAttributeLabel('home_address_line2');?></th>
                                            <td>
                                                <?php 
                                                    if($employee->home_address_line2){
                                                        echo ucfirst($employee->home_address_line2);
                                                    }
                                                    else{
                                                        echo '-';
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="200"><?php echo $employee->getAttributeLabel('home_city');?></th>
                                            <td>
                                                <?php 
                                                    if($employee->home_city){
                                                        echo ucfirst($employee->home_city);
                                                    }
                                                    else{
                                                        echo '-';
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="200"><?php echo $employee->getAttributeLabel('home_state');?></th>
                                            <td>
                                                <?php 
                                                    if($employee->home_state){
                                                        echo ucfirst($employee->home_state);
                                                    }
                                                    else{
                                                        echo '-';
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="200"><?php echo $employee->getAttributeLabel('home_country_id');?></th>
                                            <td>
                                                <?php 
                                                    if($employee->home_country_id){
                                                        $home_country = Countries::model()->findByPk($employee->home_country_id);
                                                        if($home_country){
                                                            echo ucfirst($home_country->name);
                                                        }
                                                        else{
                                                            echo '-';
                                                        }														
                                                    }
                                                    else{
                                                        echo '-';
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="200"><?php echo $employee->getAttributeLabel('home_pin_code');?></th>
                                            <td>
                                                <?php 
                                                    if($employee->home_pin_code){
                                                        echo $employee->home_pin_code;
                                                    }
                                                    else{
                                                        echo '-';
                                                    }
                                                ?>	
                                            </td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel_block">
                    <div class="panel-heading"><h5 class="panel-title"><?php echo Yii::t('app','Office Address'); ?></h5> </div>
                        <div class="people-item">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" cellspacing="0" cellpadding="0">
                                    <thead>
                                        <tr>
                                            <th width="200"><?php echo $employee->getAttributeLabel('office_address_line1');?></th>
                                            <td>
                                            	<?php 
													if($employee->office_address_line1){
														echo ucfirst($employee->office_address_line1);
													}
													else{
														echo '-';
													}
												?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="200"><?php echo $employee->getAttributeLabel('office_address_line2');?></th>
                                            <td>
                                            	<?php 
													if($employee->office_address_line2){
														echo ucfirst($employee->office_address_line2);
													}
													else{
														echo '-';
													}
												?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="200"><?php echo $employee->getAttributeLabel('office_city');?></th>
                                            <td>
                                            	<?php 
													if($employee->office_city){
														echo ucfirst($employee->office_city);
													}
													else{
														echo '-';
													}
												?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="200"><?php echo $employee->getAttributeLabel('office_state');?></th>
                                            <td>
                                            	<?php 
													if($employee->office_state){
														echo ucfirst($employee->office_state);
													}
													else{
														echo '-';
													}
												?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="200"><?php echo $employee->getAttributeLabel('office_country_id');?></th>
                                            <td>
                                            	<?php 
													if($employee->office_country_id){
														$office_country = Countries::model()->findByPk($employee->office_country_id);
														if($office_country){
															echo ucfirst($office_country->name);
														}
														else{
															echo '-';
														}														
													}
													else{
														echo '-';
													}
												?>	
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="200"><?php echo $employee->getAttributeLabel('office_pin_code');?></th>
                                            <td>
                                            	<?php 
													if($employee->office_pin_code){
														echo $employee->office_pin_code;
													}
													else{
														echo '-';
													}
												?>
                                            </td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel_block">
                            <div class="panel-heading"><h5 class="panel-title"><?php echo Yii::t('app','Contact Details'); ?></h5> </div>
                                <div class="people-item">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" cellspacing="0" cellpadding="0">
                                            <thead>
                                                <tr>
                                                    <th width="200"><?php echo $employee->getAttributeLabel('office_phone1');?></th>
                                                    <td>
                                                    	<?php 
															if($employee->office_phone1){
																echo $employee->office_phone1;
															}
															else{
																echo '-';
															}
														?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="200"><?php echo $employee->getAttributeLabel('office_phone2');?></th>
                                                    <td>
                                                    	<?php 
															if($employee->office_phone2){
																echo $employee->office_phone2;
															}
															else{
																echo '-';
															}
														?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="200"><?php echo $employee->getAttributeLabel('mobile_phone');?></th>
                                                    <td>
                                                    	<?php 
															if($employee->mobile_phone){
																echo $employee->mobile_phone;
															}
															else{
																echo '-';
															}
														?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="200"><?php echo $employee->getAttributeLabel('home_phone');?></th>
                                                    <td>
                                                    	<?php 
															if($employee->home_phone){
																echo $employee->home_phone;
															}
															else{
																echo '-';
															}
														?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="200"><?php echo $employee->getAttributeLabel('email');?></th>
                                                    <td>
                                                    	<?php 
															if($employee->email){
																echo $employee->email;
															}
															else{
																echo '-';
															}
														?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th width="200"><?php echo $employee->getAttributeLabel('fax');?></th>
                                                    <td>
                                                    	<?php 
															if($employee->fax){
																echo $employee->fax;
															}
															else{
																echo '-';
															}
														?>
                                                    </td>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>   
                    </div>	
                </div>
            </div>            
        </div>
        <div class="col-md-4">
            <div class="row">
            	<div class="col-md-12">
                    <div class="panel_block">
                    <div class="panel-heading"><h5 class="panel-title"><?php echo Yii::t('app','Upload Documents'); ?></h5> </div>
                        <div class="people-item">
                        	<?php 
							$model	= new EmployeeDocument;
							
							$form=$this->beginWidget('CActiveForm', array(
								'id'=>'employee-document-form',
								'enableAjaxValidation'=>false,
								'htmlOptions'=>array('enctype'=>'multipart/form-data'),
								'action'=>CController::createUrl('default/document')
							)); ?>
                        		<div id="file-block">
                                    <div class="Docmts_block_upload">
                                        <p>
                                            <?php
                                                echo $form->textField($model,'title[]',array ('class'=>'form-control title-field', 'placeholder'=>Yii::t('app', 'Document Name')));
                                            ?>
                                            <span class="title-error error"></span>
                                        </p>
                                        
                                        <p>
                                            <?php echo $form->fileField($model,'file[]', array('class'=>'custom-file-input file-field')); ?>
                                            <span class="file-error error"></span>
                                            <?php echo $form->hiddenField($model,'employee_id[]',array('value'=>$employee->id)); ?>                                            
                                        </p>
                                        <span class="docmnt_not"><?php echo Yii::t('app', 'File size must not exceed 5MB. Only files with these extensions are allowed: jpg, jpeg, png, pdf, doc, txt.'); ?></span>
                                        <a href="javascript::void(0);" class="remove"><?php echo Yii::t('app', 'Remove'); ?></a>
                                     </div>
                                 </div>    
                                 <div class="upload_btn">
                                 	<?php 
										echo CHtml::button(Yii::t('app','Add Another'), array('class'=>'btn','id'=>'addAnother')); 
										echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','SAVE') : Yii::t('app','Save'),array('class'=>'btn btn-success', 'id'=>'save-btn')); 
									?>                                                                       
                                 </div>
                        	<?php $this->endWidget(); ?>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel_block">
                    	<div class="panel-heading"><h5 class="panel-title"><?php echo Yii::t('app','Documents'); ?></h5> </div>
                        <div class="people-item">
							<?php
								$criteria				= new CDbCriteria();
								$criteria->condition	= 'employee_id=:employee_id';
								$criteria->params		= array(':employee_id'=>$employee->id);
								$criteria->order		= 'id DESC'; 
                                $documents 				= EmployeeDocument::model()->findAll($criteria);
                                if($documents){
                                    foreach($documents as $document){
										$status_data	= '';
										$class			= '';
										if($document->is_approved == -1){
											$class 			= 'tag_disapproved';											
											$status_data	= Yii::t('app',"Disapproved");
										}
										elseif($document->is_approved == 0){
											$class 			= 'tag_pending';
											$status_data	= Yii::t('app',"Pending");
										}
										elseif($document->is_approved == 1){
											$class 			= 'tag_approved';
											$status_data	= Yii::t('app',"Approved");
										}
                            ?>	
                                        <div class="Docmts_block">
                                            <p><?php echo ucfirst($document->title); ?></p>
                                            <div class="action_btnBlock">
                                                <ul class=" tt-wrapper prfl_actionbtn">
                                                    <li><div class="<?php echo $class; ?>"><?php echo $status_data; ?></div></li>
                                                    <li> 
                                                    	<?php
															if($document->is_approved == 1){
																echo CHtml::link('<span>'.Yii::t('app','You cannot edit an approved document.').'</span>', array('documentupdate','id'=>$document->employee_id,'document_id'=>$document->id),array('class'=>'tt-edit-disabled','onclick'=>'return false;')); 
															}
															else{
																echo CHtml::link('<span>'.Yii::t('app','Edit').'</span>', array('documentupdate','id'=>$document->employee_id,'document_id'=>$document->id),array('class'=>'tt-edit')); 
															}
														?>
                                                    <li>
                                                    	<?php 
                                                 			echo CHtml::link('<span>'.Yii::t('app','Download').'</span>', array('download','id'=>$document->id,'employee_id'=>$document->employee_id),array('class'=>'tt-download')); 
                                                 ?>
                                                    </li>
                                                    <li>
                                                    	<?php
															if($document->is_approved == 1){
																echo CHtml::link('<span>'.Yii::t('app','You cannot delete an approved document.').'</span>', array('deletes','id'=>$document->id,'employee_id'=>$document->employee_id),array('class'=>'tt-delete-disabled','onclick'=>'return false;')); 
															}
															else{
																echo CHtml::link('<span>'.Yii::t('app','Delete').'</span>', "#", array('submit'=>array('deletes','id'=>$document->id,'employee_id'=>$document->employee_id), 'class'=>'tt-delete','confirm'=>Yii::t('app','Are you sure you want to delete this?'), 'csrf'=>true));
															}
														?>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>		
                            <?php            
                                    }
                                }
                                else{
                                	echo '<p class="opnsl_nothing_found">'.Yii::t('app','No document(s) uploaded').'</p>';  
                                }
                            ?>                            
                        </div>                            
                    </div>
                </div>                                
            </div>
        </div>    
    </div>                    
</div>
<script type="text/javascript">
$('#save-btn').click(function(ev){
	var flag	= 0;
	$('.title-error').html('');
	$('.file-error').html('');
	var extenstion_arr	= ['jpg', 'jpeg','png', 'pdf', 'doc', 'txt'];
	$('.Docmts_block_upload').each(function(){
		var title 		= $(this).find('.title-field').val();
		var file		= $(this).find('.file-field').val();				
		if(title != '' || file != ''){			
			if(title == ''){
				$(this).find('.title-field').next('.title-error').html("<?php echo Yii::t('app', 'Document Name cannot be blank'); ?>");
				flag	= 1;
			}
			
			if(file == ''){				
				$(this).find('.file-field').next('.file-error').html("<?php echo Yii::t('app', 'File cannot be blank'); ?>");
				flag	= 1;
			}
			else{ 
				var fileName	= $(this).find('input[type=file]').val().split('\\').pop();
				var extension	= fileName.split('.').pop();				
				var file_size 	= $(this).find('.file-field')[0].files[0].size;	
				
				if(file_size > 5242880){
					$(this).find('.file-field').next('.file-error').html("<?php echo Yii::t('app', 'File size must not exceed 5MB!'); ?>");
					flag	= 1;
				}
				else if(jQuery.inArray(extension, extenstion_arr) == -1){
					$(this).find('.file-field').next('.file-error').html("<?php echo Yii::t('app', 'Only files with these extensions are allowed: jpg, jpeg, png, pdf, doc, txt.'); ?>");
					flag	= 1;
				}								
			}
		}
	});
	if(flag == 1){
		return false;
	}
});

$('#addAnother').click(function(ev){	
	var data	= $('#file-block').find('.Docmts_block_upload').html();
	data		= '<div class="Docmts_block_upload">'+data+'</div>';
	data		= $(data);
	data.find('.title-error').html('');
	data.find('.file-error').html('');
	data.find('a').show();			
	$("#file-block").append(data);
	
	callback();
	
});

function callback(){
	$(".remove").unbind('click').click(function(e) {		
		$(this).closest('.Docmts_block_upload').remove();
	});
}
$('.remove').hide();
callback();
$('#emp_image').jupload({	 
	url:<?php echo CJavaScript::encode(Yii::app()->createUrl('/teachersportal/default/employeepicupload',array('id'=>$employee_id)))?>,
	data:{"<?php echo Yii::app()->request->csrfTokenName;?>":"<?php echo Yii::app()->request->csrfToken;?>"},
	select:function(files){
		$.each(files, function(index, file){
			var reader = new FileReader();
			reader.onload = function (e) {      			
				//$('.prof_img img').attr('src', e.target.result);				
			}
			reader.readAsDataURL(file);
		});
	},
	uploadProgress: function(event, position, total, percentComplete){		 
		$('#displayPercentage').show();
		$('.loading_app').show();		
		$('#percentage').html(parseInt(percentComplete));
	},
	complete: function(response){  
		$('#displayPercentage').hide();
		alert('<?php echo Yii::t('app', 'Image will be changed only after approval from Administrator!'); ?>');		
	}	 	 
});
</script>
<script type="text/javascript">
	$('#displayPercentage').hide();
</script>

