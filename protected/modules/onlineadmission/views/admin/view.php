<style type="text/css">
.no-details{
	font-style:italic;
	text-align:center;
}

.notify-parent{
	float:right;
}
.pdtab_Con {
    margin: 7px;
    padding: 4px 0 0;
}
.max_student{ 
	border-left: 3px solid #fff;
    margin: 0 3px;
    padding: 6px 0 6px 3px;
    word-break: break-all;
}
.box-btn{
	float:right;
	float: right;
	margin-top: -5px;	
}

</style>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/woco.accordion.min.js"></script>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Students')=>array('/students/students/index'),
	Yii::t('app','Online Applicants'),
	Yii::t('app','View Profile'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/default/left_side');?>
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">
            	<div  class="page-header">
            	<h1>
					<?php echo Yii::t('app','Registered Student Profile').' : '; ?>
                    <?php						 
						if(FormFields::model()->isVisible("fullname", "Students", 'forOnlineRegistration')){
							echo $model->studentFullName('forOnlineRegistration');
						}
					?>
                </h1>
                </div>
<?php                
                $settings		= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                $status_data	= "";
				// Setting class for status label
				if($model->status == -1){
					$status_class 	= 'tag_disapproved';
                    $status_data	= Yii::t('app',"Disapproved");
				}
				elseif($model->status == 0){
					$status_class 	= 'tag_pending';
                    $status_data	= Yii::t('app',"Pending");
				}
				elseif($model->status == 1){
					$status_class 	= 'tag_approved';
                    $status_data	= Yii::t('app',"Approved");
				}
				elseif($model->status == -3){
					$status_class 	= 'tag_waiting';
                    $status_data	= Yii::t('app',"Waiting");
				}
?>	
			<div class="page-header-profl">
            <div class="student-pfl-bg">
            <div class="stdnt-profl-posct-left">
                <div class="student_pfofl_img">                            
                    <?php								
                        $student	= Students::model()->findByAttributes(array('id'=>$model->id));
                        if($model->photo_file_name){ 
                        $path = Students::model()->getProfileImagePath($model->id);
                        echo '<img  src="'.$path.'" alt="'.$model->photo_file_name.'"  />';
                        }
                        elseif($model->gender == 'M'){
                        echo '<img  src="images/s_prof_m_image.png" alt='.$model->first_name.' />'; 
                        }
                        elseif($model->gender == 'F'){
                        echo '<img  src="images/s_prof_fe_image.png" alt='.$model->first_name.' />';  
                        }
                    ?>                                
                </div>
              </div>
               <div class="stdnt-profl-posct-right"> 							
                <div class="online_but buttns-stdnt-p">
                	<?php /*?><div class="<?php echo $status_class; ?>" ><?php echo $status_data; ?></div><?php */?>
                    <ul class="tt-wrapper">
                        <li>
                            <div class="<?php echo $status_class; ?>" ><?php echo $status_data; ?></div>
                        </li>
                    	<li>
<?php                        
							if($model->status == 1){ 
								echo CHtml::link('<span>'.Yii::t('app','Approved').'</span>', array('#'),array('class'=>'tt-approved-disabled','onclick'=>'return false;'));
							}
							else{							
								echo CHtml::ajaxLink(
									'<span>'.Yii::t('app','Approve').'</span>',
									$this->createUrl('admin/approve'),
									array(
										'onclick'=>'$("#jobDialog'.$model->id.'").dialog("open"); return false;',
										'dataType'=>'json',
										'success'=>'js:function(data){
											if(data.status=="success"){
												$("#jobDialog123'.$model->id.'").html(data.content);
											}
										}',
										'error'=>'js:function(){
											alert("'.Yii::t("app", "Some problem found").'!");
											window.location.reload();
										}',
										'type' =>'GET',
										'data'=>array(
											'id' =>$model->id
										),
									),
									array(
										'id'=>'showJobDialog'.$model->id,
										'class'=>'tt-approved'
									)
								);
							}
?>                        
                        </li>
<?php					
                        if($model->status != 1){
?>                    
                        	<li>
<?php                         
								if($model->status == -1){								
									echo CHtml::link('<span>'.Yii::t('app','Disapproved').'</span>', array('#'),array('class'=>'tt-disapproved-disabled','onclick'=>'return false;')); 
								}
								else{
									echo CHtml::link('<span>'.Yii::t('app','Disapprove').'</span>', array('disapprove','id'=>$model->id),array('class'=>'tt-disapproved','confirm'=>Yii::t('app','Are you sure you want to disapprove this?'))); 
								}                        
?>                        
                        	</li>
<?php                   
						}

				   		if($model->status != 1){ 
?>
                            <li>
                                <?php echo CHtml::link('<span>'.Yii::t('app','Delete').'</span>', array('delete','id'=>$model->id),array('class'=>'tt-delete','confirm'=>Yii::t('app','Are you sure you want to delete this?'))); ?>
                            </li>
<?php                   
				  		} 
						
						if($model->status == 0){
?>								
                            <li>                        
                                <?php echo CHtml::link('<span>'.Yii::t('app','Waiting List').'</span>', array('WaitinglistStudents/create','id'=>$model->id),array('class'=>'tt-waiting',)); ?>                                                  
                            </li>
<?php                        
						}
						
						if($model->status == 0 or $model->status == -3){
?>						  
                            <li>                      		
                                <?php echo CHtml::link('<span>'.Yii::t('app','Edit').'</span>', array('profileedit','id'=>$model->id),array('class'=>'tt-edit',)); ?>
                            </li>   
<?php                           
						}
?>						                                                
                    </ul>
                    <div id="<?php echo 'jobDialog123'.$model->id;?>"></div>
                </div>
               </div> 
               </div>                
                <div class="clear"></div>                
                <div class="emp_right_contner">
                    <div class="emp_tabwrapper">
                    	<div class="emp_cntntbx">

                            <div class="cordn-h3">
                            	<span>
                                    <h3><?php echo Yii::t('app','STUDENT DETAILS');?></h3>                                       
                                </span>
                                
                                <div class="accordion">
                                	<h1><?php echo Yii::t('app','Personal details'); ?></h1>
                                    <div>	
										<?php if(FormFields::model()->isVisible('registration_id','Students','forOnlineRegistration')){?>	 
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('registration_id');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php echo $model->registration_id; ?>		
                                                </div>
                                            </div>
                                        <?php } ?> 
                                        <?php if(FormFields::model()->isVisible('registration_date','Students','forOnlineRegistration')){?>	 
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('registration_date');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($settings != NULL){	
															$model->registration_date = date($settings->displaydate,strtotime($model->registration_date));
														}
														echo $model->registration_date;
													?>		
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('national_student_id','Students','forOnlineRegistration')){?>	 
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('national_student_id');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model->national_student_id){                                                 
															echo $model->national_student_id;
														}
														else{
															echo '-';
														}													
													?>		
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('date_of_birth','Students','forOnlineRegistration')){?>	 
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('date_of_birth');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model->date_of_birth){ 
															if($settings!=NULL){	
																$model->date_of_birth = date($settings->displaydate,strtotime($model->date_of_birth));
															}
															echo $model->date_of_birth;
														}
														else{
															echo '-';
														}												
													?>		
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('gender','Students','forOnlineRegistration')){?>	 
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('gender');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model->gender == 'M') {
															echo Yii::t('app','Male');
														}
														elseif($model->gender == 'F'){
															echo Yii::t('app','Female');
														}
														else{
															echo '-';
														}												
													?>		
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('blood_group','Students','forOnlineRegistration')){?>	 
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('blood_group');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model->blood_group){                                                 
															echo $model->blood_group;
														}
														else{
															echo '-';
														}												
													?>		
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('birth_place','Students','forOnlineRegistration')){?>	 
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('birth_place');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model->birth_place){                                                 
															echo $model->birth_place;
														}
														else{
															echo '-';
														}												
													?>		
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('nationality_id','Students','forOnlineRegistration')){?>	 
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('nationality_id');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														$nationality	= Nationality::model()->findByAttributes(array('id'=>$model->nationality_id));
														if($nationality){                                                 
															echo $nationality->name;
														}
														else{
															echo '-';
														}												
													?>		
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('language','Students','forOnlineRegistration')){?>	 
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('language');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model->language){                                                 
															echo $model->language;
														}
														else{
															echo '-';
														}											
													?>		
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('batch_id','Students','forOnlineRegistration')){?>	 
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo Yii::app()->getModule("students")->labelCourseBatch(); ?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														$batch_name = Batches::model()->findByAttributes(array('id'=>$model->batch_id));
														if($batch_name){
															echo $batch_name->course123->course_name.' / '.$batch_name->name;						
														}
														else{
															echo "-";
														}											
													?>		
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('student_category_id','Students','forOnlineRegistration')){?>	 
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('student_category_id');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														$category = StudentCategories::model()->findByAttributes(array('id'=>$model->student_category_id));
														if($category != NULL){
															echo $category->name;
														}
														else{
															echo '-';
														}											
													?>		
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('religion','Students','forOnlineRegistration')){?>	 
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('religion');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model->religion){
															echo $model->religion;
														}
														else{
															echo "-";
														}											
													?>		
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <!-- DYNAMIC FIELDS START -->
                                    <?php 
                                    	$fields = FormFields::model()->getDynamicFields(1, 1, "forOnlineRegistration");
										if($fields){
											foreach($fields as $key => $field){							
												if($field->form_field_type!=NULL){
													if(FormFields::model()->isVisible($field->varname,'Students','forOnlineRegistration')){
									?>    
														<div class="tabl">		
															<div class="fist-sections"><?php echo $model->getAttributeLabel($field->varname);?></div>                                       						<div class="midl-sections">:</div>
															<div class="last-sections">															
									<?php
																$field_name = $field->varname;
															  	if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
																	echo FormFields::model()->getFieldValue($model->$field_name);
															  	}
															  	else if($field->form_field_type==6){  // date value
																	if($settings!=NULL and $model->$field_name!=NULL and $model->$field_name!="0000-00-00"){
															    		$date1  = date($settings->displaydate,strtotime($model->$field_name));
															    		echo $date1;
																	}
																	else{
																		if($model->$field_name!=NULL and $model->$field_name!="0000-00-00"){
																  			echo $model->$field_name;
																		}
																		else{
																			echo '-';
																		}
																	}
															  	}
															  	else{
																	echo (isset($model->$field_name) and $model->$field_name!="")?$model->$field_name:"-";
															  	}
									?>
															</div>
														</div>
									<?php
													} 
												} 				                                            
											}
										}
                                    ?>
                                    <!-- DYNAMIC FIELDS END -->	
                                    </div>
                                    
                                    <h1><?php echo Yii::t('app','Contact details'); ?></h1>	
                                    <div>
                                    	<?php if(FormFields::model()->isVisible('address_line1','Students','forOnlineRegistration')){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('address_line1');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
                                                        if($model->address_line1){
                                                            echo $model->address_line1;
                                                        }
                                                        else{ 
                                                            echo '-';
                                                        } 
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('address_line2','Students','forOnlineRegistration')){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('address_line2');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
                                                        if($model->address_line2){
                                                            echo $model->address_line2;
                                                        }
                                                        else{ 
                                                            echo '-';
                                                        } 
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('city','Students','forOnlineRegistration')){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('city');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
                                                        if($model->city){
                                                            echo $model->city;
                                                        }
                                                        else{ 
                                                            echo '-';
                                                        } 
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>	
                                        <?php if(FormFields::model()->isVisible('state','Students','forOnlineRegistration')){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('state');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
                                                        if($model->state){
                                                            echo $model->state;
                                                        }
                                                        else{ 
                                                            echo '-';
                                                        } 
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('pin_code','Students','forOnlineRegistration')){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('pin_code');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
                                                        if($model->pin_code){
                                                            echo $model->pin_code;
                                                        }
                                                        else{ 
                                                            echo '-';
                                                        } 
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('country_id','Students','forOnlineRegistration')){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('country_id');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
                                                        if($model->country_id){
                                                            $count = Countries::model()->findByAttributes(array('id'=>$model->country_id));
                                                            if(count($count)!=0)
                                                            echo $count->name;
                                                        }
                                                        else{
                                                            echo '-';
                                                        } 
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('phone1','Students','forOnlineRegistration')){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('phone1');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
                                                        if($model->phone1){
                                                            echo $model->phone1;
                                                        }
                                                        else{ 
                                                            echo '-';
                                                        } 
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('phone2','Students','forOnlineRegistration')){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('phone2');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
                                                        if($model->phone2){
                                                            echo $model->phone2;
                                                        }
                                                        else{ 
                                                            echo '-';
                                                        } 
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible('email','Students','forOnlineRegistration')){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model->getAttributeLabel('email');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
                                                        if($model->email){
                                                            echo $model->email;
                                                        }
                                                        else{ 
                                                            echo '-';
                                                        } 
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <!-- DYNAMIC FIELDS START -->
										<?php 
                                            $fields= FormFields::model()->getDynamicFields(1, 2, "forOnlineRegistration");
                                            if($fields){
                                                foreach ($fields as $key => $field){							
                                                    if($field->form_field_type!=NULL){
                                                        if(FormFields::model()->isVisible($field->varname,'Students','forOnlineRegistration')){
                                         ?>    
                                                            <div class="tabl">
                                                                <div class="fist-sections"><?php echo $model->getAttributeLabel($field->varname);?></div>                                       						<div class="midl-sections">:</div>
                                                                <div class="last-sections">
                                        <?php
                                                                    $field_name = $field->varname;
                                                                    if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
                                                                        echo FormFields::model()->getFieldValue($model->$field_name);
                                                                    }
                                                                    else if($field->form_field_type==6){  // date value
                                                                        if($settings!=NULL and $model->$field_name!=NULL and $model->$field_name!="0000-00-00"){
                                                                            $date1  = date($settings->displaydate,strtotime($model->$field_name));
                                                                            echo $date1;
                                                                        }
                                                                        else{
                                                                            if($model->$field_name!=NULL and $model->$field_name!="0000-00-00"){
                                                                                echo $model->$field_name;
                                                                            }
                                                                            else{
                                                                                echo '-';
                                                                            }
                                                                        }
                                                                    }
                                                                    else{
                                                                        echo (isset($model->$field_name) and $model->$field_name!="")?$model->$field_name:"-";
                                                                    }
                                        ?>
                                                            
                                                                </div>
                                                            </div>            
                                        <?php
                                                        } 
                                                    } 				                                            
                                                }
                                            }
                                        ?>
                                        <!-- DYNAMIC FIELDS END -->
                                    </div>
                                    <div class="section_div"></div>
                                    
                                    <div class="box-div"> 
                                        <h3><?php echo Yii::t('app','GUARDIAN DETAILS'); ?></h3>
                                    </div>
                                    <h1><?php echo Yii::t('app','Personal Details'); ?></h1>
                                    <div>
                                    	<?php if(FormFields::model()->isVisible("fullname", "Guardians", "forOnlineRegistration")){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo Yii::t('app','Full Name'); ?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
                                                        $name= "";
														if(FormFields::model()->isVisible('first_name','Guardians','forOnlineRegistration')){
															$name.= $model_1->first_name;
														}
														if(FormFields::model()->isVisible('last_name','Guardians','forOnlineRegistration')){
															$name.= " ".$model_1->last_name;
														}
														echo ucfirst($name);
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible("relation", "Guardians", "forOnlineRegistration")){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model_1->getAttributeLabel('relation');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model_1->relation){
															echo $model_1->relation;
														}
														else{
															echo "-";
														}
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible("dob", "Guardians", "forOnlineRegistration")){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model_1->getAttributeLabel('dob');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model_1->dob != NULL and $model_1->dob!= '0000-00-00'){
															if($settings!=NULL){	
																$model_1->dob = date($settings->displaydate,strtotime($model_1->dob));
															}
															echo $model_1->dob;
														}
														else{
															echo '-';
														}
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible("education", "Guardians", "forOnlineRegistration")){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model_1->getAttributeLabel('education');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model_1->education){
															echo $model_1->education;
														}
														else{
															echo "-";
														}
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible("occupation", "Guardians", "forOnlineRegistration")){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model_1->getAttributeLabel('occupation');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model_1->occupation){
															echo $model_1->occupation;
														}
														else{
															echo "-";
														}
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible("income", "Guardians", "forOnlineRegistration")){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model_1->getAttributeLabel('income');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model_1->income){
															echo $model_1->income;
														}
														else{
															echo "-";
														}
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <!-- DYNAMIC FIELDS START -->
										<?php 
                                            $fields = FormFields::model()->getDynamicFields(2, 1, "forOnlineRegistration");
                                            if($fields){
                                                foreach ($fields as $key => $field){							
                                                    if($field->form_field_type!=NULL){
                                                        if(FormFields::model()->isVisible($field->varname,'Guardians','forOnlineRegistration')){
                                         ?>    
                                                            <div class="tabl">
                                                                <div class="fist-sections"><?php echo $model_1->getAttributeLabel($field->varname);?></div>                                       						<div class="midl-sections">:</div>
                                                                <div class="last-sections">
                                        <?php
                                                                    $field_name = $field->varname;
                                                                    if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
                                                                        echo FormFields::model()->getFieldValue($model_1->$field_name);
                                                                    }
                                                                    else if($field->form_field_type==6){  // date value
                                                                        if($settings!=NULL and $model_1->$field_name!=NULL and $model_1->$field_name!="0000-00-00"){
                                                                            $date1  = date($settings->displaydate,strtotime($model_1->$field_name));
                                                                            echo $date1;
                                                                        }
                                                                        else{
                                                                            if($model_1->$field_name!=NULL and $model_1->$field_name!="0000-00-00"){
                                                                                echo $model_1->$field_name;
                                                                            }
                                                                            else{
                                                                                echo '-';
                                                                            }
                                                                        }
                                                                    }
                                                                    else{
                                                                        echo (isset($model_1->$field_name) and $model_1->$field_name!="")?$model_1->$field_name:"-";
                                                                    }
                                        ?>
                                                            
                                                                </div>
                                                            </div>            
                                        <?php
                                                        } 
                                                    } 				                                            
                                                }
                                            }
                                        ?>
                                        <!-- DYNAMIC FIELDS END -->
                                    </div>
                                    <h1><?php echo Yii::t('app','Contact details'); ?></h1>	
                                    <div>
                                    	<?php if(FormFields::model()->isVisible("office_address_line1", "Guardians", "forOnlineRegistration")){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model_1->getAttributeLabel('office_address_line1');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model_1->office_address_line1){
															echo $model_1->office_address_line1;
														}
														else{
															echo "-";
														}
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible("office_address_line2", "Guardians", "forOnlineRegistration")){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model_1->getAttributeLabel('office_address_line2');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model_1->office_address_line2){
															echo $model_1->office_address_line2;
														}
														else{
															echo "-";
														}
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible("city", "Guardians", "forOnlineRegistration")){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model_1->getAttributeLabel('city');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model_1->city){
															echo $model_1->city;
														}
														else{
															echo "-";
														}
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible("state", "Guardians", "forOnlineRegistration")){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model_1->getAttributeLabel('state');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model_1->state){
															echo $model_1->state;
														}
														else{
															echo "-";
														}
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible("country_id", "Guardians", "forOnlineRegistration")){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model_1->getAttributeLabel('country_id');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														$country = Countries::model()->findByAttributes(array('id'=>$model_1->country_id));
														if($country){
															echo $country->name;
														}
														else{
															echo '-';
														}
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible("office_phone1", "Guardians", "forOnlineRegistration")){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model_1->getAttributeLabel('office_phone1');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model_1->office_phone1){
															echo $model_1->office_phone1;
														}
														else{
															echo "-";
														}
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible("office_phone2", "Guardians", "forOnlineRegistration")){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model_1->getAttributeLabel('office_phone2');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model_1->office_phone2){
															echo $model_1->office_phone2;
														}
														else{
															echo "-";
														}
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible("mobile_phone", "Guardians", "forOnlineRegistration")){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model_1->getAttributeLabel('mobile_phone');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model_1->mobile_phone){
															echo $model_1->mobile_phone;
														}
														else{
															echo "-";
														}
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <?php if(FormFields::model()->isVisible("email", "Guardians", "forOnlineRegistration")){?>   
                                            <div class="tabl">
                                                <div class="fist-sections"><?php echo $model_1->getAttributeLabel('email');?></div>
                                                <div class="midl-sections">:</div>
                                                <div class="last-sections">
                                                    <?php 
														if($model_1->email){
															echo $model_1->email;
														}
														else{
															echo "-";
														}
                                                    ?>
                                                </div>
                                            </div>	
                                        <?php } ?>
                                        <!-- DYNAMIC FIELDS START -->
										<?php 
                                            $fields = FormFields::model()->getDynamicFields(2, 2, "forOnlineRegistration");
                                            if($fields){
                                                foreach ($fields as $key => $field){							
                                                    if($field->form_field_type!=NULL){
                                                        if(FormFields::model()->isVisible($field->varname,'Guardians','forOnlineRegistration')){
                                         ?>    
                                                            <div class="tabl">
                                                                <div class="fist-sections"><?php echo $model_1->getAttributeLabel($field->varname);?></div>                                       						<div class="midl-sections">:</div>
                                                                <div class="last-sections">
                                        <?php
                                                                    $field_name = $field->varname;
                                                                    if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
                                                                        echo FormFields::model()->getFieldValue($model_1->$field_name);
                                                                    }
                                                                    else if($field->form_field_type==6){  // date value
                                                                        if($settings!=NULL and $model_1->$field_name!=NULL and $model_1->$field_name!="0000-00-00"){
                                                                            $date1  = date($settings->displaydate,strtotime($model_1->$field_name));
                                                                            echo $date1;
                                                                        }
                                                                        else{
                                                                            if($model_1->$field_name!=NULL and $model_1->$field_name!="0000-00-00"){
                                                                                echo $model_1->$field_name;
                                                                            }
                                                                            else{
                                                                                echo '-';
                                                                            }
                                                                        }
                                                                    }
                                                                    else{
                                                                        echo (isset($model_1->$field_name) and $model_1->$field_name!="")?$model_1->$field_name:"-";
                                                                    }
                                        ?>
                                                            
                                                                </div>
                                                            </div>            
                                        <?php
                                                        } 
                                                    } 				                                            
                                                }
                                            }
                                        ?>
                                        <!-- DYNAMIC FIELDS END -->
                                    </div>   
                                    <div class="section_div"></div>
                                    
                                    <div class="box-div"> 
                                        <h3><?php echo Yii::t('app','DOCUMENT DETAILS'); ?></h3>
                                    </div>
                                    <div class="pdtab_Con">
                                    	<table width="100%" cellpadding="0" cellspacing="0">
                                            <tr class="pdtab-h">                                                
                                                <td align="center" width="150"><?php echo Yii::t('app','Document Name'); ?></td>
                                                <td align="center" width="75"><?php echo Yii::t('app','Document Type'); ?></td> 
                                                <?php
													$colspan = 2; 
													if($model->status != 1){
														$colspan = 3;
												?>                            
                                                		<td align="center" width="75"><?php echo Yii::t('app','Action'); ?></td>  
                                                <?php } ?>                                                                                  
                                            </tr> 
<?php
											$documents = StudentDocument::model()->findAllByAttributes(array('student_id'=>$model->id));
											if($documents){
												foreach($documents as $document){
													$studentDocumentList = StudentDocumentList::model()->findByAttributes(array('id'=>$document->title));
?>
													<tr>
                                                    	<td align="center"><?php echo ucfirst($studentDocumentList->name);?></td>
                                                        <td align="center"><?php echo ucfirst($document->file_type);?></td>
                                                        <?php if($model->status != 1){ ?> 
                                                        	<td align="center">
                                                            	<ul class="tt-wrapper tt-down-btn">                                                        	
                                                                    <li>
                                                                        <?php echo CHtml::link('<span>'.Yii::t('app','Download').'</span>', array('admin/download','id'=>$document->id,'student_id'=>$document->student_id),array('class'=>'tt-download')); ?>
                                                                    </li>
                                                                </ul>
                                                            </td>
                                                        <?php } ?>    
                                                    </tr>
<?php
												}
											}
											else{												
?> 
													<tr><td colspan="<?php echo $colspan; ?>" class="no-details"><?php echo Yii::t('app','No document(s) uploaded'); ?></td></tr>                                                    
<?php																							
											}
?>                                            
                                    	</table>        
                                    </div>  
<?php
								$criteria 		= new CDbCriteria;
								$criteria->join = 'LEFT JOIN student_document sd ON sd.title = t.id and sd.student_id = '.$model->id.'';
								$criteria->addCondition('sd.title IS NULL');								
								$missing_documents = StudentDocumentList::model()->findAll($criteria);
								if($missing_documents){
?>                                    
                                    <div class="section_div"></div>                                    
                                    <div class="box-div"> 
                                        <h3><?php echo Yii::t('app','MISSING DOCUMENT DETAILS'); ?>
                                        	<div class="notify-parent"><?php echo CHtml::link('<span>'.Yii::t('app','Notify Parent').'</span>', array('admin/notify','id'=>$_REQUEST['id']),array('class'=>'accordian-notify','confirm'=>'Notify parent?'));?></div>
                                        </h3>
                                    </div>
                                    <div class="pdtab_Con">
                                    	<table width="100%" cellpadding="0" cellspacing="0">
                                            <tr class="pdtab-h">                                                
                                                <td align="center" width="150"><?php echo Yii::t('app','Document Name'); ?></td>                                                
                                            </tr>
<?php
											foreach($missing_documents as $missing_document){
?>
												<tr>
                                                    <td align="center"><?php echo ucfirst($missing_document->name);?></td>
                                                   
												</tr>
<?php												
											}
?>                                            
                                        </table>
                                     </div>           
<?php
								}
?>                                                                                                                                            
                                </div>
                            </div>
                               
                        </div> <!-- END div class="emp_cntntbx" -->
                    </div> <!-- END div class="emp_tabwrapper" -->
                </div> <!-- END div class="emp_right_contner" --> 
                </div>                          
            </div> <!-- END div class="cont_right formWrapper" -->
        </td>
    </tr>
</table> 
<script type="text/javascript">
$(".accordion").accordion();		
</script>           