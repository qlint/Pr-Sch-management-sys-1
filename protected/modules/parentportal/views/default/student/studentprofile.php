<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,400italic' rel='stylesheet' type='text/css'>
<style type="text/css">
.sp_col{
	border-bottom:1px #eee solid;
	padding-bottom:8px;
}
.error{
	color:#F00;
	font-size: 12px;
}

</style>
<?php 
	$this->renderPartial('leftside');
	
	$settings					= UserSettings::model()->findByAttributes(array('user_id'=>1));
    $current_guardian			= Guardians::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$student_visible_fields		= FormFields::model()->getVisibleFields('Students', 'forParentPortal');
    $guardian_visible_fields  	= FormFields::model()->getVisibleFields('Guardians', 'forParentPortal');
	
	$criteria				= new CDbCriteria;		
	$criteria->join 		= 'LEFT JOIN guardian_list t1 ON t.id = t1.student_id'; 
	$criteria->condition 	= 't1.guardian_id=:guardian_id and t.is_active=:is_active and is_deleted=:is_deleted';
	$criteria->params 		= array(':guardian_id'=>$current_guardian->id,':is_active'=>1,'is_deleted'=>0);
	$students 				= Students::model()->findAll($criteria); 
		    
    $student_list 			= CHtml::listData($students,'id','studentnameforparentportal');
	$semester_enabled		= Configurations::model()->isSemesterEnabled();    
?>
<div class="pageheader">
	<div class="col-lg-8">
		<h2><i class="fa fa-male"></i><?php echo Yii::t('app','Student Profile'); ?> <span><?php echo Yii::t('app','View your profile here'); ?></span></h2>
	</div>
	<div class="col-lg-2">
		<?php
			// Show drop down only if more than 1 student present 
			if(isset($_REQUEST['id']) and $_REQUEST['id'] != NULL){         
        		echo CHtml::dropDownList('sid','',$student_list,array('prompt'=>Yii::t('app','Select Student'),'id'=>'studentid','class'=>'form-control studnt-form-control input-sm mb14','options'=>array($_REQUEST['id']=>array('selected'=>true)),'onchange'=>'getstudent();'));
			}
        ?>
	</div>
	<div class="breadcrumb-wrapper"> <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
		<ol class="breadcrumb">			
			<li class="active"><?php echo Yii::t('app','Student Profile'); ?></li>
		</ol>
	</div>
	<div class="clearfix"></div>
</div>
<?php 
	//In case of student profile view
	if(count($students)==1 or (isset($_REQUEST['id']) and $_REQUEST['id']!=NULL)){
		if(count($students) > 1){		
			$student	= Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		} 
		else{
			$student	= Students::model()->findByAttributes(array('id'=>$students[0]['id']));		
		}
		$batchstudents	= BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id, 'status'=>1, 'result_status'=>0));
?>		
		<div class="contentpanel"> 
        	<div class="people-item">
                <div class="profile_block"> 
                    <div class="proflImg_block img_Inner_stn">
                        <a href="javascript::void(0);">
        <?php        
                            if($student->photo_file_name != NULL){
                                $path = Students::model()->getProfileImagePath($student->id);		 
                                echo '<img  src="'.$path.'" alt="'.$student->photo_file_name.'class="thumbnail" />';
                            }
                            elseif($student->gender == 'M'){
                                echo '<img  src="images/portal/prof-img_male.png" alt='.$student->first_name.' class="thumbnail" />'; 
                            }
                            elseif($student->gender == 'F'){
                                echo '<img  src="images/portal/prof-img_female.png" alt='.$student->first_name.'class="thumbnail" />';
                            }
        ?>                    
                        </a>                                                
                    </div>
                    <div class="proflCnt_block">
                        <h4>
                            <?php 
                                if(FormFields::model()->isVisible("fullname", "Students", "forParentPortal")){
									echo $student->studentFullName("forParentPortal");
                                }
                            ?>
                        </h4>
                        <p><span><?php echo Students::model()->getAttributeLabel('admission_no');?></span><?php echo $student->admission_no; ?></p> 
                        <?php
                            if(count($batchstudents)>1){
                               echo '<p>'.CHtml::link('View Course Details', array('/parentportal/default/course', 'id'=>$student->id), array('title'=>Yii::t('app', 'View Active Courses'))).'</p>';		
								//echo CHtml::link('View Course Details', array('/parentportal/default/course', 'id'=>$student->id));
                            }
                            else{
                                $batch 			= Batches::model()->findByPk($batchstudents[0]['batch_id']);
								$sem_enabled	= Configurations::model()->isSemesterEnabledForCourse($batch->course_id);
                                $semester		= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));						
                                $batch_student	= BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
                                if(in_array('batch_id', $student_visible_fields)){
                        ?>
                                    <p><span><?php echo Yii::t('app','Course'); ?></span><?php echo html_entity_decode(ucfirst($batch->course123->course_name)); ?></p>
                                    <p><span><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?></span><?php echo html_entity_decode(ucfirst($batch->name)); ?></p>
                                    <?php if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id!=NULL){ ?>
                                        <p><span><?php echo Yii::t('app','Semester'); ?></span><?php echo html_entity_decode(ucfirst($semester->name)); ?></p>
                        <?php		
                                    }							
                                }
                            }
                        ?>   
                    </div>                    
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel_block">
                                <div class="panel-heading"><h5 class="panel-title"><?php echo Yii::t('app', 'Profile Details'); ?></h5> </div>
                                <div class="people-item">
                                    <div class="table-responsive">
                                        <h5 class="subtitle"><?php echo Yii::t('app', 'General Details'); ?></h5>
                                        <table class="table table-bordered table-striped" cellspacing="0" cellpadding="0">
                                            <thead>
                                                <tr>
                                                    <th width="200"><?php echo $student->getAttributeLabel('admission_date'); ?></th>
                                                    <td>
                                                        <?php 
                                                             if($student->admission_date != NULL and $student->admission_date != '0000-00-00'){
                                                                 echo date($settings->displaydate,strtotime($student->admission_date));
                                                             }
                                                             else{
                                                                echo '-';
                                                             }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php if(in_array('date_of_birth', $student_visible_fields)){ ?>
                                                    <tr>
                                                        <th width="200"><?php echo $student->getAttributeLabel('date_of_birth'); ?></th>
                                                        <td>
                                                            <?php 
                                                                 if($student->date_of_birth != NULL and $student->date_of_birth != '0000-00-00'){
                                                                     echo date($settings->displaydate,strtotime($student->date_of_birth));
                                                                 }
                                                                 else{
                                                                    echo '-';
                                                                 }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?> 
                                                <?php if(in_array('national_student_id', $student_visible_fields)){ ?>
                                                    <tr>
                                                        <th width="200"><?php echo $student->getAttributeLabel('national_student_id'); ?></th>
                                                        <td>
                                                            <?php 
                                                                 if($student->national_student_id != NULL){
                                                                     echo html_entity_decode($student->national_student_id);
                                                                 }
                                                                 else{
                                                                    echo '-';
                                                                 }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>  
                                                <?php if(in_array('gender', $student_visible_fields)){ ?>
                                                    <tr>
                                                        <th width="200"><?php echo $student->getAttributeLabel('gender'); ?></th>
                                                        <td>
                                                            <?php 
                                                                if($student->gender == 'M')
                                                                    echo Yii::t('app','Male');
                                                                else if($student->gender == 'F') 
                                                                    echo Yii::t('app','Female');
                                                                else
                                                                    echo '-';	
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>  
                                                <?php if(in_array('blood_group', $student_visible_fields)){ ?>
                                                    <tr>
                                                        <th width="200"><?php echo $student->getAttributeLabel('blood_group'); ?></th>
                                                        <td>
                                                            <?php 
                                                                 if($student->blood_group != NULL){
                                                                     echo html_entity_decode($student->blood_group);
                                                                 }
                                                                 else{
                                                                    echo '-';
                                                                 }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <?php if(in_array('birth_place', $student_visible_fields)){ ?>
                                                    <tr>
                                                        <th width="200"><?php echo $student->getAttributeLabel('birth_place'); ?></th>
                                                        <td>
                                                            <?php 
                                                                 if($student->birth_place != NULL){
                                                                     echo html_entity_decode(ucfirst($student->birth_place));
                                                                 }
                                                                 else{
                                                                    echo '-';
                                                                 }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>  
                                                <?php if(in_array('nationality_id', $student_visible_fields)){ ?>
                                                    <tr>
                                                        <th width="200"><?php echo $student->getAttributeLabel('nationality_id'); ?></th>
                                                        <td>
                                                            <?php 
                                                                $nationality	= Nationality::model()->findByAttributes(array('id'=>$student->nationality_id));
                                                                if($nationality != NULL){
                                                                    echo html_entity_decode(ucfirst($nationality->name));
                                                                }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>  
                                                <?php if(in_array('language', $student_visible_fields)){ ?>
                                                    <tr>
                                                        <th width="200"><?php echo $student->getAttributeLabel('language'); ?></th>
                                                        <td>
                                                            <?php 
                                                                 if($student->language != NULL){
                                                                     echo html_entity_decode(ucfirst($student->language));
                                                                 }
                                                                 else{
                                                                    echo '-';
                                                                 }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?> 
                                                <?php if(in_array('religion', $student_visible_fields)){ ?>
                                                    <tr>
                                                        <th width="200"><?php echo $student->getAttributeLabel('religion'); ?></th>
                                                        <td>
                                                            <?php 
                                                                 if($student->religion != NULL){
                                                                     echo html_entity_decode(ucfirst($student->religion));
                                                                 }
                                                                 else{
                                                                    echo '-';
                                                                 }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?> 
                                                <?php if(in_array('student_category_id', $student_visible_fields)){ ?>
                                                    <tr>
                                                        <th width="200"><?php echo $student->getAttributeLabel('student_category_id'); ?></th>
                                                        <td>
                                                            <?php 
                                                                $category	= StudentCategories::model()->findByAttributes(array('id'=>$student->student_category_id));
                                                                if($category != NULL){
                                                                    echo html_entity_decode(ucfirst($category->name));
                                                                }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>  
                                                
                                                
                                                <?php 
                                                    $fields	= FormFields::model()->getDynamicFields(1, 1, "forParentPortal");
                                                    if($fields){
                                                        foreach($fields as $key => $field){							
                                                            if($field->form_field_type!=NULL){
                                                                if(FormFields::model()->isVisible($field->varname,'Students','forParentPortal')){
                                                ?>    
                                                                    <tr>		
                                                                        <th width="200"><?php echo $student->getAttributeLabel($field->varname);?></th>                                     						
                                                                        <td>															
                                                <?php
                                                                            $field_name = $field->varname;
                                                                            if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
                                                                                echo FormFields::model()->getFieldValue($student->$field_name);
                                                                            }
                                                                            else if($field->form_field_type==6){  // date value
                                                                                if($settings!=NULL and $student->$field_name!=NULL and $student->$field_name!="0000-00-00"){
                                                                                    echo date($settings->displaydate,strtotime($student->$field_name));
                                                                                }
                                                                                else{
                                                                                    if($student->$field_name!=NULL and $student->$field_name!="0000-00-00"){
                                                                                        echo $student->$field_name;
                                                                                    }
                                                                                    else{
                                                                                        echo '-';
                                                                                    }
                                                                                }
                                                                            }
                                                                            else{
                                                                                echo (isset($student->$field_name) and $student->$field_name!="")?html_entity_decode(ucfirst($student->$field_name)):"-";
                                                                            }
                                                ?>
                                                                        </td>
                                                                    </tr>
                                                <?php
                                                                } 
                                                            } 				                                            
                                                        }
                                                    }
                                                ?>
                                                   
                                            </thead>
                                        </table>
                                    </div>
                                    <!-- Student Contact Details -->
                                    <div class="table-responsive">
                                        <h5 class="subtitle"><?php echo Yii::t('app', 'Contact Details'); ?></h5>
                                        <table class="table table-bordered table-striped" cellspacing="0" cellpadding="0">
                                            <thead>
                                                <?php if(in_array('address_line1', $student_visible_fields)){ ?>
                                                    <tr>
                                                        <th width="200"><?php echo $student->getAttributeLabel('address_line1'); ?></th>
                                                        <td>
                                                            <?php 
                                                                 if($student->address_line1 != NULL){
                                                                     echo html_entity_decode(ucfirst($student->address_line1));
                                                                 }
                                                                 else{
                                                                    echo '-';
                                                                 }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <?php if(in_array('address_line2', $student_visible_fields)){ ?>
                                                    <tr>
                                                        <th width="200"><?php echo $student->getAttributeLabel('address_line2'); ?></th>
                                                        <td>
                                                            <?php 
                                                                 if($student->address_line2 != NULL){
                                                                     echo html_entity_decode(ucfirst($student->address_line2));
                                                                 }
                                                                 else{
                                                                    echo '-';
                                                                 }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <?php if(in_array('city', $student_visible_fields)){ ?>
                                                    <tr>
                                                        <th width="200"><?php echo $student->getAttributeLabel('city'); ?></th>
                                                        <td>
                                                            <?php 
                                                                 if($student->city != NULL){
                                                                     echo html_entity_decode(ucfirst($student->city));
                                                                 }
                                                                 else{
                                                                    echo '-';
                                                                 }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <?php if(in_array('state', $student_visible_fields)){ ?>
                                                    <tr>
                                                        <th width="200"><?php echo $student->getAttributeLabel('state'); ?></th>
                                                        <td>
                                                            <?php 
                                                                 if($student->state != NULL){
                                                                     echo html_entity_decode(ucfirst($student->state));
                                                                 }
                                                                 else{
                                                                    echo '-';
                                                                 }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <?php if(in_array('pin_code', $student_visible_fields)){ ?>
                                                    <tr>
                                                        <th width="200"><?php echo $student->getAttributeLabel('pin_code'); ?></th>
                                                        <td>
                                                            <?php 
                                                                 if($student->pin_code != NULL){
                                                                     echo $student->pin_code;
                                                                 }
                                                                 else{
                                                                    echo '-';
                                                                 }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>   
                                                <?php if(in_array('country_id', $student_visible_fields)){ ?>
                                                    <tr>
                                                        <th width="200"><?php echo $student->getAttributeLabel('country_id'); ?></th>
                                                        <td>
                                                            <?php 
                                                                 $country = Countries::model()->findByAttributes(array('id'=>$student->country_id));
                                                                if($country != NULL){
                                                                    echo html_entity_decode(ucfirst($country->name));
                                                                }
                                                                else{
                                                                    echo '-';
                                                                }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <?php if(in_array('phone1', $student_visible_fields)){ ?>
                                                    <tr>
                                                        <th width="200"><?php echo $student->getAttributeLabel('phone1'); ?></th>
                                                        <td>
                                                            <?php 														
                                                                if($student->phone1 != NULL){
                                                                    echo $student->phone1;
                                                                }
                                                                else{
                                                                    echo '-';
                                                                }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <?php if(in_array('phone2', $student_visible_fields)){ ?>
                                                    <tr>
                                                        <th width="200"><?php echo $student->getAttributeLabel('phone2'); ?></th>
                                                        <td>
                                                            <?php 														
                                                                if($student->phone2 != NULL){
                                                                    echo $student->phone2;
                                                                }
                                                                else{
                                                                    echo '-';
                                                                }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <?php if(in_array('email', $student_visible_fields)){ ?>
                                                    <tr>
                                                        <th width="200"><?php echo $student->getAttributeLabel('email'); ?></th>
                                                        <td>
                                                            <?php 														
                                                                if($student->email != NULL){
                                                                    echo $student->email;
                                                                }
                                                                else{
                                                                    echo '-';
                                                                }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                
                                                <?php 
                                                    $fields= FormFields::model()->getDynamicFields(1, 2, "forParentPortal");
                                                    if($fields){
                                                        foreach ($fields as $key => $field){							
                                                            if($field->form_field_type!=NULL){
                                                                if(FormFields::model()->isVisible($field->varname,'Students','forParentPortal')){
                                                 ?>    
                                                                    <tr>
                                                                        <th width="200"><?php echo $student->getAttributeLabel($field->varname);?></th>                                       						
                                                                        <td>
                                                <?php
                                                                            $field_name = $field->varname;
                                                                            if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
                                                                                echo FormFields::model()->getFieldValue($student->$field_name);
                                                                            }
                                                                            else if($field->form_field_type==6){  // date value
                                                                                if($settings!=NULL and $student->$field_name!=NULL and $student->$field_name!="0000-00-00"){
                                                                                    echo date($settings->displaydate,strtotime($student->$field_name));																			
                                                                                }
                                                                                else{
                                                                                    if($student->$field_name!=NULL and $student->$field_name!="0000-00-00"){
                                                                                        echo $student->$field_name;
                                                                                    }
                                                                                    else{
                                                                                        echo '-';
                                                                                    }
                                                                                }
                                                                            }
                                                                            else{
                                                                                echo (isset($student->$field_name) and $student->$field_name!="")?html_entity_decode(ucfirst($student->$field_name)):"-";
                                                                            }
                                                ?>
                                                                    
                                                                        </td>
                                                                    </tr>      
                                                <?php
                                                                } 
                                                            } 				                                            
                                                        }
                                                    }
                                                ?>
                                                <tr>
                                                    <th width="200"><?php echo Yii::t('app', 'Emergency Contact'); ?></th>
                                                    <td>
                                                        <?php 	
                                                            $emergency_contact	= Guardians::model()->findByPk($student->immediate_contact_id);													
                                                            if($emergency_contact != NULL){
                                                                echo $emergency_contact->parentFullName("forParentPortal");
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
                            <!-- Guardian Details -->
                            <?php
                                $criteria				= new CDbCriteria();
                                $criteria->join			= 'JOIN `guardian_list` `t1` ON `t1`.`guardian_id` = `t`.`id`';
                                $criteria->condition	= '`t`.`is_delete`=:is_delete AND `t1`.`student_id`=:student_id';
                                $criteria->params		= array(':is_delete'=>0, ':student_id'=>$student->id); 	 
                                $guardians				= Guardians::model()->findAll($criteria);													
                            ?>
                            <div class="panel_block">
                                <div class="panel-heading"><h5 class="panel-title"><?php echo Yii::t('app', 'Guardian Details'); ?></h5> </div>
                                <div class="people-item">                        	
                                    <div class="table-responsive">
        <?php
                                        if(count($guardians) <= 0){
        ?>
                                            <p class="opnsl_nothing_found"><?php echo Yii::t('app', 'No Guardians Found'); ?></p>
        <?php									
                                        }
                                        else{
                                            $i = 1;
                                            foreach($guardians as $guardian){
                                                $relation	= GuardianList::model()->findByAttributes(array('student_id'=>$student->id, 'guardian_id'=>$guardian->id));
                                                if(count($guardians) > 1){
        ?>
                                                    <h5 class="subtitle"><?php echo '#'.$i; ?></h5>
<?php
												}
?>												
                                                <table class="table table-bordered table-striped" cellspacing="0" cellpadding="0">
                                                    <thead>
                                                        <?php if(FormFields::model()->isVisible("fullname", "Guardians", "forParentPortal")){ ?>
                                                            <tr>	
                                                                <th width="200"><?php echo Yii::t('app','Name');?></th>
                                                                <td><?php echo strtoupper($guardian->parentFullName("forParentPortal")); ?></td>
                                                            </tr>
                                                        <?php } ?> 
                                                        <?php if(in_array('relation', $guardian_visible_fields)){ ?>
                                                            <tr>	
                                                                <th width="200"><?php echo $guardian->getAttributeLabel('relation');?></th>
                                                                <td>
                                                                    <?php echo ($relation != NULL and $relation->relation != NULL)?html_entity_decode(ucfirst($relation->relation)):'-'; ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?> 
                                                        <?php if(in_array('dob', $guardian_visible_fields)){ ?>
                                                            <tr>
                                                                <th width="200"><?php echo $guardian->getAttributeLabel('dob'); ?></th>
                                                                <td>
                                                                    <?php 
                                                                         if($guardian->dob != NULL and $guardian->dob != '0000-00-00'){
                                                                             echo date($settings->displaydate,strtotime($guardian->dob));
                                                                         }
                                                                         else{
                                                                            echo '-';
                                                                         }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>   
                                                        <?php if(in_array('education', $guardian_visible_fields)){ ?>
                                                            <tr>
                                                                <th width="200"><?php echo $guardian->getAttributeLabel('education'); ?></th>
                                                                <td>
                                                                    <?php 
                                                                         if($guardian->education != NULL){
                                                                             echo html_entity_decode(ucfirst($guardian->education));
                                                                         }
                                                                         else{
                                                                            echo '-';
                                                                         }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <?php if(in_array('occupation', $guardian_visible_fields)){ ?>
                                                            <tr>
                                                                <th width="200"><?php echo $guardian->getAttributeLabel('occupation'); ?></th>
                                                                <td>
                                                                    <?php 
                                                                         if($guardian->occupation != NULL){
                                                                             echo html_entity_decode(ucfirst($guardian->occupation));
                                                                         }
                                                                         else{
                                                                            echo '-';
                                                                         }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?> 
                                                        <?php if(in_array('income', $guardian_visible_fields)){ ?>
                                                            <tr>
                                                                <th width="200"><?php echo $guardian->getAttributeLabel('income'); ?></th>
                                                                <td>
                                                                    <?php 
                                                                         if($guardian->income != NULL){
                                                                             echo html_entity_decode($guardian->income);
                                                                         }
                                                                         else{
                                                                            echo '-';
                                                                         }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <?php 
                                                            $fields	= FormFields::model()->getDynamicFields(2, 1, "forParentPortal");
                                                            if($fields){
                                                                foreach ($fields as $key => $field){							
                                                                    if($field->form_field_type!=NULL){
                                                                        if(FormFields::model()->isVisible($field->varname,'Guardians','forParentPortal')){
                                                                            ?>    
                                                                                <tr> 
                                                                                    <th width="200"><?php echo $guardian->getAttributeLabel($field->varname);?></th> 																				       
                                                                                <td>
                                                                                <?php
                                                                                 $field_name = $field->varname;
                                                                                  if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
                                                                                    echo FormFields::model()->getFieldValue($guardian->$field_name);
                                                                                  }
                                                                                  else if($field->form_field_type==6){  // date value
                                                                                        if($settings!=NULL and $guardian->$field_name!=NULL and $guardian->$field_name!="0000-00-00"){
                                                                                            echo date($settings->displaydate,strtotime($guardian->$field_name));
                                                                                        
                                                                                        }
                                                                                        else{
                                                                                            if($guardian->$field_name!=NULL and $guardian->$field_name!="0000-00-00"){
                                                                                                echo $guardian->$field_name;
                                                                                            }
                                                                                            else{
                                                                                                echo '-';
                                                                                            }
                                                                                        }
                                                                                  }
                                                                                  else{
                                                                                    echo (isset($guardian->$field_name) and $guardian->$field_name!="")?html_entity_decode(ucfirst($guardian->$field_name)):"-";
                                                                                  }
                                                                                ?>
                                                                                </td>
                                                                            </tr><?php
                                                                        } 
                                                                    } 				                                            
                                                                }
                                                            }
                                                        ?> 
                                                            
                                                        <?php if(in_array('email', $guardian_visible_fields)){ ?>
                                                            <tr>
                                                                <th width="200"><?php echo $guardian->getAttributeLabel('email'); ?></th>
                                                                <td>
                                                                    <?php 
                                                                         if($guardian->email != NULL){
                                                                             echo $guardian->email;
                                                                         }
                                                                         else{
                                                                            echo '-';
                                                                         }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <?php if(in_array('mobile_phone', $guardian_visible_fields)){ ?>
                                                            <tr>
                                                                <th width="200"><?php echo $guardian->getAttributeLabel('mobile_phone'); ?></th>
                                                                <td>
                                                                    <?php 
                                                                         if($guardian->mobile_phone != NULL){
                                                                             echo $guardian->mobile_phone;
                                                                         }
                                                                         else{
                                                                            echo '-';
                                                                         }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <?php if(in_array('office_phone1', $guardian_visible_fields)){ ?>
                                                            <tr>
                                                                <th width="200"><?php echo $guardian->getAttributeLabel('office_phone1'); ?></th>
                                                                <td>
                                                                    <?php 
                                                                         if($guardian->office_phone1 != NULL){
                                                                             echo $guardian->office_phone1;
                                                                         }
                                                                         else{
                                                                            echo '-';
                                                                         }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <?php if(in_array('office_phone2', $guardian_visible_fields)){ ?>
                                                            <tr>
                                                                <th width="200"><?php echo $guardian->getAttributeLabel('office_phone2'); ?></th>
                                                                <td>
                                                                    <?php 
                                                                         if($guardian->office_phone2 != NULL){
                                                                             echo $guardian->office_phone2;
                                                                         }
                                                                         else{
                                                                            echo '-';
                                                                         }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <?php if(in_array('office_address_line1', $guardian_visible_fields)){ ?>
                                                            <tr>
                                                                <th width="200"><?php echo $guardian->getAttributeLabel('office_address_line1'); ?></th>
                                                                <td>
                                                                    <?php 
                                                                         if($guardian->office_address_line1 != NULL){
                                                                             echo html_entity_decode(ucfirst($guardian->office_address_line1));
                                                                         }
                                                                         else{
                                                                            echo '-';
                                                                         }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <?php if(in_array('office_address_line2', $guardian_visible_fields)){ ?>
                                                            <tr>
                                                                <th width="200"><?php echo $guardian->getAttributeLabel('office_address_line2'); ?></th>
                                                                <td>
                                                                    <?php 
                                                                         if($guardian->office_address_line2 != NULL){
                                                                             echo html_entity_decode(ucfirst($guardian->office_address_line2));
                                                                         }
                                                                         else{
                                                                            echo '-';
                                                                         }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <?php if(in_array('city', $guardian_visible_fields)){ ?>
                                                            <tr>
                                                                <th width="200"><?php echo $guardian->getAttributeLabel('city'); ?></th>
                                                                <td>
                                                                    <?php 
                                                                         if($guardian->city != NULL){
                                                                             echo html_entity_decode(ucfirst($guardian->city));
                                                                         }
                                                                         else{
                                                                            echo '-';
                                                                         }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <?php if(in_array('state', $guardian_visible_fields)){ ?>
                                                            <tr>
                                                                <th width="200"><?php echo $guardian->getAttributeLabel('state'); ?></th>
                                                                <td>
                                                                    <?php 
                                                                         if($guardian->state != NULL){
                                                                             echo html_entity_decode(ucfirst($guardian->state));
                                                                         }
                                                                         else{
                                                                            echo '-';
                                                                         }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        <?php if(in_array('country_id', $guardian_visible_fields)){ ?>
                                                            <tr>
                                                                <th width="200"><?php echo $guardian->getAttributeLabel('country_id'); ?></th>
                                                                <td>
                                                                    <?php 
                                                                         $country	= Countries::model()->findByPk($guardian->country_id);
                                                                         if($country != NULL){
                                                                             echo html_entity_decode(ucfirst($country->name));
                                                                         }
                                                                         else{
                                                                            echo '-';
                                                                         }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        
                                                        <?php 
                                                            $fields	= FormFields::model()->getDynamicFields(2, 2, "forParentPortal");
                                                            if($fields){
                                                                foreach($fields as $key => $field){							
                                                                    if($field->form_field_type!=NULL){
                                                                        if(FormFields::model()->isVisible($field->varname,'Guardians','forParentPortal')){
                                                                            ?>    
                                                                                <tr> 
                                                                                <th width="200"><?php echo $guardian->getAttributeLabel($field->varname);?></th>                                      																			
                                                                                <td>
                                                                                <?php
                                                                                  $field_name = $field->varname;
                                                                                  if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
                                                                                    echo FormFields::model()->getFieldValue($guardian->$field_name);
                                                                                  }
                                                                                  else if($field->form_field_type==6){  // date value
                                                                                        if($settings!=NULL and $guardian->$field_name!=NULL and $guardian->$field_name!="0000-00-00"){
                                                                                            echo date($settings->displaydate,strtotime($guardian->$field_name));
                                                                                        }
                                                                                        else{
                                                                                            if($guardian->$field_name!=NULL and $guardian->$field_name!="0000-00-00"){
                                                                                                echo $guardian->$field_name;
                                                                                            }
                                                                                            else{
                                                                                                echo '-';
                                                                                            }
                                                                                        }																
                                                                                  }
                                                                                  else{
                                                                                    echo (isset($guardian->$field_name) and $guardian->$field_name!="")?html_entity_decode(ucfirst($guardian->$field_name)):"-";
                                                                                  }
                                                                                ?>
                                                                                </td>
                                                                            </tr><?php
                                                                        } 
                                                                    } 				                                            
                                                                }
                                                            }
                                                        ?>
                                                         
                                                    </thead>
                                                </table>    
        <?php										
                                                                                                
                                                $i++;
                                            }									
                                        }
        ?>                                    
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
                                    $model	= new StudentDocument;
                                    
                                    $form=$this->beginWidget('CActiveForm', array(
                                        'id'=>'student-document-form',
                                        'enableAjaxValidation'=>false,
                                        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
                                        'action'=>CController::createUrl('default/document')
                                    )); ?>
                                        <div id="file-block">
                                            <div class="Docmts_block_upload">
                                            	<p>
													<?php
                                                        $static 		= array('Others' => 'Others');
                                                        $document_arr	= array();
                                                        
                                                        $criteria			= new CDbCriteria();
                                                        $criteria->join		= 'LEFT JOIN student_document sd ON sd.doc_type = t.name and sd.student_id = '.$student->id.'';
                                                        $criteria->addCondition('sd.doc_type IS NULL');
                                                        $student_documents	= StudentDocumentList::model()->findAll($criteria);
                                                        
                                                        if($student_documents != NULL){
                                                            foreach($student_documents as $student_document){
                                                                $document_arr[$student_document->name]	= html_entity_decode(ucfirst($student_document->name));
                                                            }
                                                        }
                                                        echo $form->dropDownList($model,'doc_type[]',$document_arr + $static,array('prompt'=>Yii::t('app','Select Document'), 'class'=>'form-control document-list'));																								
                                                    ?>
                                                    <span class="type-error error"></span>
                                                </p>
                                                <p class="title-p" style="display:none;">
													<?php
                                                        echo $form->textField($model,'title[]',array ('class'=>'form-control title-field', 'placeholder'=>Yii::t('app', 'Document Name')));
                                                    ?>
                                                    <span class="title-error error"></span>
                                                </p>                                                
                                                <p>
                                                    <?php echo $form->fileField($model,'file[]', array('class'=>'custom-file-input file-field')); ?>
                                                    <span class="file-error error"></span>
                                                    <?php 														
														echo $form->hiddenField($model,'student_id',array('value'=>$student->id)); ?>                                            
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
										$criteria->condition	= 'student_id=:student_id';
										$criteria->params		= array(':student_id'=>$student->id);
										$criteria->order		= 'id DESC';
                                        $documents				= StudentDocument::model()->findAll($criteria);
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
                                                    <p><?php 
                                                            if($document->title != NULL){
                                                                echo ucfirst($document->title); 
                                                            }
                                                            else if($document->doc_type != NULL){
                                                                echo ucfirst($document->doc_type);
                                                            }
                                                            else{
                                                                echo '-';
                                                            }
                                                        ?>
                                                    </p>
                                                    <div class="action_btnBlock">
                                                        <ul class=" tt-wrapper prfl_actionbtn">
                                                            <li><div class="<?php echo $class; ?>"><?php echo $status_data; ?></div></li>
                                                            <li> 
                                                                <?php
                                                                    if($document->is_approved == 1){
                                                                        echo CHtml::link('<span>'.Yii::t('app','You cannot edit an approved document.').'</span>', array('documentupdate','id'=>$document->student_id,'document_id'=>$document->id),array('class'=>'tt-edit-disabled','onclick'=>'return false;')); 
                                                                    }
                                                                    else{
                                                                        echo CHtml::link('<span>'.Yii::t('app','Edit').'</span>', array('documentupdate','id'=>$document->student_id,'document_id'=>$document->id),array('class'=>'tt-edit')); 
                                                                    }
                                                                ?>
                                                            <li>
                                                                <?php 
                                                                    echo CHtml::link('<span>'.Yii::t('app','Download').'</span>', array('download','id'=>$document->id,'student_id'=>$document->student_id),array('class'=>'tt-download')); 
                                                         ?>
                                                            </li>
                                                            <li>
                                                                <?php
                                                                    if($document->is_approved == 1){
                                                                        echo CHtml::link('<span>'.Yii::t('app','You cannot delete an approved document.').'</span>', array('deletes','id'=>$document->id,'student_id'=>$document->student_id),array('class'=>'tt-delete-disabled','onclick'=>'return false;')); 
                                                                    }
                                                                    else{
                                                                        echo CHtml::link('<span>'.Yii::t('app','Delete').'</span>', "#", array('submit'=>array('deletes','id'=>$document->id,'student_id'=>$document->student_id), 'class'=>'tt-delete','confirm'=>Yii::t('app','Are you sure you want to delete this?'), 'csrf'=>true));
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
<?php				
	}
	elseif(count($students)>1 and !isset($_REQUEST['id'])){ // More than one Student. Display List
?>
		<div class="contentpanel"> 
<?php	
			foreach($students as $student){
				$batchstudents	= BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id, 'status'=>1, 'result_status'=>0));
?>
				<div class="people-item">
                <div class="profile_block"> 
                    <div class="proflImg_block img_Inner_stn">
                        <a href="javascript::void(0);">
        <?php        
                            if($student->photo_file_name != NULL){
                                $path = Students::model()->getProfileImagePath($student->id);		 
                                echo '<img  src="'.$path.'" alt="'.$student->photo_file_name.'class="thumbnail" />';
                            }
                            elseif($student->gender == 'M'){
                                echo '<img  src="images/portal/prof-img_male.png" alt='.$student->first_name.' class="thumbnail" />'; 
                            }
                            elseif($student->gender == 'F'){
                                echo '<img  src="images/portal/prof-img_female.png" alt='.$student->first_name.'class="thumbnail" />';
                            }
        ?>                    
                        </a>                                                
                    </div>
                    <div class="proflCnt_block">
                        <h4>
                            <?php 
                                if(FormFields::model()->isVisible("fullname", "Students", "forParentPortal")){ 
                                    echo CHtml::link($student->studentFullName('forParentPortal'), array('/parentportal/default/studentprofile', 'id'=>$student->id));									
                                }
                            ?>
                        </h4>
                        <p><span><?php echo Students::model()->getAttributeLabel('admission_no');?></span><?php echo $student->admission_no; ?></p> 
                        <?php
                            if(count($batchstudents)>1){
                                echo '<p>'.CHtml::link('View Course Details', array('/parentportal/default/course','id'=>$student->id), array('title'=>Yii::t('app', 'View Active Courses'))).'</p>';		
                            }
                            else{
                                $batch 			= Batches::model()->findByPk($batchstudents[0]['batch_id']);
								$sem_enabled	= Configurations::model()->isSemesterEnabledForCourse($batch->course_id);
                                $semester		= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));						
                                $batch_student	= BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
                                if(in_array('batch_id', $student_visible_fields)){
                        ?>
                                    <p><span><?php echo Yii::t('app','Course'); ?></span><?php echo html_entity_decode(ucfirst($batch->course123->course_name)); ?></p>
                                    <p><span><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?></span><?php echo html_entity_decode(ucfirst($batch->name)); ?></p>
                                     <?php if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id!=NULL){ ?>
                                        <p><span><?php echo Yii::t('app','Semester'); ?></span><?php echo html_entity_decode(ucfirst($semester->name)); ?></p>
                        <?php		
                                    }							
                                }
                            }
                        ?>   
                    </div>                    
                </div>
            </div>
<?php				
			}
?>
		</div>
<?php					
	}
	else{
?>
		<div class="contentpanel"> 
        	<div class="people-item">
            	<p class="opnsl_nothing_found"><?php echo Yii::t('app', 'No Students Found'); ?></p>
            </div>
        </div>
<?php		
	}
?>

<script type="text/javascript">
//File Upload
$('#save-btn').click(function(ev){
	var flag	= 0;
	$('.type-error').html('');
	$('.title-error').html('');
	$('.file-error').html('');
	var extenstion_arr	= ['jpg', 'jpeg','png', 'pdf', 'doc', 'txt'];
	$('.Docmts_block_upload').each(function(){
		var type		= $(this).find('.document-list').val();
		var title 		= $(this).find('.title-field').val();
		var file		= $(this).find('.file-field').val();				
		if(type != '' || file != ''){
			if(type == ''){
				$(this).find('.document-list').next('.type-error').html("<?php echo Yii::t('app', 'Document cannot be blank'); ?>");
				flag	= 1;	
			}
			if(type == 'Others' && title == ''){
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

$('.document-list').live('change', function(ev){
	var document_type	=  $(this).val();
	if(document_type == 'Others'){ 
		$(this).closest('.Docmts_block_upload').find('.title-p').removeAttr('style');
	}
	else{
		$(this).closest('.Docmts_block_upload').find('input:text').val('');
		$(this).closest('.Docmts_block_upload').find('input:text').next('.title-error').html('');
		$(this).closest('.Docmts_block_upload').find('.title-p').css('display', 'none');
	}
	if(document_type != '' && document_type != 'Others'){
		$('.document-list').not(this).each(function(){
			var value	= $(this).val();
			if(value == document_type){
				$(this).val('');
			}
		});		
	}
	
});

$('#addAnother').click(function(ev){	
	var i	= 0;
	var arr	= [];
	$(".document-list").each(function() {
		if($(this).val() != '' && $(this).val() != 'Others'){
			arr[i++] = $(this).val();
		}		
	});
		
	var custom_id		= $('#custom-id').val();
	var new_custom_id	= parseInt(custom_id) + 1;
	$('#custom-id').val(new_custom_id);
	
	var data	= $('#file-block').find('.Docmts_block_upload').html();
	data		= '<div class="Docmts_block_upload">'+data+'</div>';
	data		= $(data);
	data.find('.title-error').html('');
	data.find('.file-error').html('');
	data.find('.title-p').css('display', 'none');	
	data.find('input:text').attr("id","title_"+new_custom_id);
	data.find('select.document-list').attr("id","document_type_"+new_custom_id);	
	data.find('a').show();	
	$.each(arr, function( index, value ) {
		data.find(".document-list option[value="+value+"]").remove();
	});
				
	$("#file-block").append(data);	
	callback();	
});

function callback(){
	$(".remove").unbind('click').click(function(e) {	
		var document_type	= $(this).closest('.Docmts_block_upload').find('.document-list').val();			
		$(this).closest('.Docmts_block_upload').remove();
		if(document_type != '' && document_type != 'Others'){
			$(".document-list").each(function() {	
				if($(this).find("option[value="+document_type+"]").length == 0){					
					$(this).find("option:last").before("<option value="+document_type+">"+document_type[0].toUpperCase() + document_type.slice(1)+"</option>");								
				}
			});
		}
	});
}

$('.remove').hide();
callback();

// Function to select student
function getstudent(){ 
	var studentid = document.getElementById('studentid').value;
	if(studentid != ''){
		window.location= 'index.php?r=parentportal/default/studentprofile&id='+studentid;	
	}
	else{
		window.location= 'index.php?r=parentportal/default/studentprofile';
	}
}

</script>
