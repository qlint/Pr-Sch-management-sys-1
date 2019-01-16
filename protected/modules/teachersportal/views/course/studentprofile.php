<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,400italic' rel='stylesheet' type='text/css'>
<style>
.sp_col{
  border-bottom:1px #eee solid;
  padding-bottom:8px;
}
</style>
<?php $this->renderPartial('/default/leftside');
$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
$semester_enabled	= Configurations::model()->isSemesterEnabled();
$student=Students::model()->findByAttributes(array('id'=>$_REQUEST['student_id']));
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forTeacherPortal');
$guardian_visible_fields  = FormFields::model()->getVisibleFields('Guardians', 'forTeacherPortal');?> 
 <div class="pageheader">
      <h2><i class="fa fa-list-alt"></i><?php echo Yii::t('app', 'My Course');?> <span><?php echo Yii::t('app', 'View courses here');?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app', 'You are here:');?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
         <li class="active"><?php echo Yii::t('app', 'Course');?></li>
        </ol>
      </div>
    </div>
    <div class="contentpanel">

    <div class="people-item">
      <div class="media"> <a href="#" class="pull-left">
        <?php
                     if($student->photo_file_name!=NULL)
                     {
						$path = Students::model()->getProfileImagePath($student->id);		 
                        echo '<img  src="'.$path.'" alt="'.$student->photo_file_name.'" width="100" height="103" class="thumbnail media-object" />';
                    }
                    elseif($student->gender=='M')
                    {
                        echo '<img  src="images/portal/prof-img_male.png" alt='.$student->first_name.' width="100" height="103" class="thumbnail media-object" />'; 
                    }
                    elseif($student->gender=='F')
                    {
                        echo '<img  src="images/portal/prof-img_female.png" alt='.$student->first_name.' width="100" height="103" class="thumbnail media-object" />';
                    }
                    ?>
        </a>
        <div class="media-body">
          <h4 class="person-name"><?php 
            $name="";
            $name=  $student->studentFullName('forTeacherPortal');
                    if($name!="")
                    {
                        echo $name;
                    }
                    else
                        echo "-";
          //echo ucfirst($student->last_name).' '.ucfirst($student->first_name);?></h4>
          <?php
			$batchstudents=BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id,'status'=>1, 'result_status'=>0));
		 if(count($batchstudents)>1){
			  echo CHtml::link('View Course Details', array('/teachersportal/course/courses', 'id'=>$student->id));
		 }else{?>
            <div class="text-muted"><strong><?php echo Yii::t('app','Course').' :';?></strong>
            <?php 
                $batch = Batches::model()->findByPk($batchstudents[0]['batch_id']);
				$course = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
                echo $batch->course123->course_name;
                $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1)); ?>
                
            </div>
            <div class="text-muted"> <strong><?php 
				if(FormFields::model()->isVisible('batch_id','Students','forTeacherPortal'))
				{
					echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' :';?></strong> <?php echo $batch->name;
				}
            ?>
            </div>
			<?php $sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);
					if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id != NULL){
						$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));?>
							<div class="text-muted"> <strong><?php 
								if(FormFields::model()->isVisible('batch_id','Students','forTeacherPortal'))
								{
									echo Yii::t('app', 'Semester').' :';?></strong> <?php echo ucfirst($semester->name);
								}
							?>
							</div>
			<?php } 
		 }
		 ?>
          <div class="text-muted"><strong><?php echo Yii::t('app','Admission No').' :';?></strong> <?php echo $student->admission_no; ?></div>
           <?php if($batch_student!=NULL and $batch_student->roll_no!=0){ ?>
          <div class="text-muted"><strong><?php echo Yii::t('app','Roll No').' :';?></strong> <?php echo $batch_student->roll_no; ?></div>
          <?php } ?>
          
        </div>
      </div>
   </div>
   
   <div class="panel-heading"> 
      <!-- panel-btns -->
      <h3 class="panel-title"><?php echo Yii::t('app','Student Details'); ?></h3>
    </div>
    <div class="people-item">
    
    
    <?php if(in_array('date_of_birth', $student_visible_fields)){ ?>
            <div class="col-sm-6 clearfix sp_col">
            <div class="col col-sm-6">
              <strong><?php echo $student->getAttributeLabel('date_of_birth');?></strong>
            </div>
            <div class="col col-sm-6">
              <?php 
              if($settings!=NULL)
              {
              $date1=date($settings->displaydate,strtotime($student->date_of_birth));
              echo $date1;
              }
              else
              {
              echo $student->date_of_birth;
              }
              ?>
            </div>
            </div>
            <?php } ?>
            
             <?php if(in_array('national_student_id', $student_visible_fields)){ ?>
            <div class="col-sm-6 clearfix sp_col">
            <div class="col col-sm-6">
              <strong><?php echo $student->getAttributeLabel('national_student_id');?></strong>
            </div>
            <div class="col col-sm-6">
              <?php echo (isset($student->national_student_id) and $student->national_student_id!="")?$student->national_student_id:"-"; ?>
            </div>
            </div>
            <?php } ?>
            
            

            <?php if(in_array('gender', $student_visible_fields)){ ?>
            <div class="col-sm-6 clearfix sp_col">
            <div class="col col-sm-6">
              <strong><?php echo $student->getAttributeLabel('gender');?></strong>
            </div>
            <div class="col col-sm-6">
              <?php 
              if($student->gender=='M')
              echo Yii::t('app','Male');
              else 
              echo Yii::t('app','Female'); 
              ?>
            </div>
            </div>
            <?php } ?>

            <?php if(in_array('blood_group', $student_visible_fields)){ ?>
            <div class="col-sm-6 clearfix sp_col">
            <div class="col col-sm-6">
              <strong><?php echo $student->getAttributeLabel('blood_group');?></strong>
            </div>
            <div class="col col-sm-6">
              <?php echo (isset($student->blood_group) and $student->blood_group!="")?$student->blood_group:"-"; ?>
            </div>
            </div>
            <?php } ?>

            <?php if(in_array('birth_place', $student_visible_fields)){ ?>
            <div class="col-sm-6 clearfix sp_col">
            <div class="col col-sm-6">
              <strong><?php echo $student->getAttributeLabel('birth_place');?></strong>
            </div>
            <div class="col col-sm-6">
              <?php echo (isset($student->birth_place) and $student->birth_place!="")?$student->birth_place:'-'; ?>
            </div>
            </div>
            <?php } ?>

            <?php if(in_array('nationality_id', $student_visible_fields)){ ?>
            <div class="col-sm-6 clearfix sp_col">
            <div class="col col-sm-6">
              <strong><?php echo $student->getAttributeLabel('nationality_id');?></strong>
            </div>
            <div class="col col-sm-6">
              <?php 
              $natio_id=Nationality::model()->findByAttributes(array('id'=>$student->nationality_id));
              if($natio_id!=NULL)
                echo $natio_id->name; 
              else
                echo "-";
              ?>
            </div>
            </div>
            <?php } ?>

            <?php if(in_array('language', $student_visible_fields)){ ?>
            <div class="col-sm-6 clearfix sp_col">
            <div class="col col-sm-6">
              <strong><?php echo $student->getAttributeLabel('language');?></strong>
            </div>
            <div class="col col-sm-6">
              <?php echo (isset($student->language) and $student->language!="")?$student->language:"-"; ?>
            </div>
            </div>
            <?php } ?>

            <?php if(in_array('religion', $student_visible_fields)){ ?>
            <div class="col-sm-6 clearfix sp_col">
            <div class="col col-sm-6">
              <strong><?php echo $student->getAttributeLabel('religion');?></strong>
            </div>
            <div class="col col-sm-6">
              <?php echo (isset($student->religion) and $student->religion!="")?$student->religion:"-"; ?>
            </div>
            </div>
            <?php } ?>

            <?php if(in_array('student_category_id', $student_visible_fields)){ ?>
            <div class="col-sm-6 clearfix sp_col">
            <div class="col col-sm-6">
              <strong><?php echo $student->getAttributeLabel('student_category_id');?></strong>
            </div>
            <div class="col col-sm-6">
              <?php 
              $cat =StudentCategories::model()->findByAttributes(array('id'=>$student->student_category_id));
              if($cat!=NULL)
                echo $cat->name; 
              else
                echo "-";
              ?>
            </div>
            </div>
            <?php } ?>

            <?php
            // dynamic fields in personal details
            $fields   = FormFields::model()->getDynamicFields(1, 1, "forTeacherPortal");
            foreach ($fields as $key => $field) {
              $field_name = $field->varname;
            ?>
              <?php if(in_array($field_name, $student_visible_fields)){ ?>
              <div class="col-sm-6 clearfix sp_col">
              <div class="col col-sm-6">
                <strong><?php echo $student->getAttributeLabel($field_name);?></strong>
              </div>
              <div class="col col-sm-6">
                <?php
                  if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
                    echo FormFields::model()->getFieldValue($student->$field_name);
                  }
                  else if($field->form_field_type==6){  // date value
                    if($settings!=NULL){
                      $date1  = date($settings->displaydate,strtotime($student->$field_name));
                      echo $date1;
                    }
                    else{
                      echo $student->$field_name;
                    }
                  }
                  else{
                    echo (isset($student->$field_name) and $student->$field_name!="")?$student->$field_name:"-";
                  }
                ?>
              </div>
              </div>
              <?php } ?>
            <?php               
            }
            //dynamic fields end
            ?>

            <?php if(in_array('address_line1', $student_visible_fields)){ ?>
            <div class="col-sm-6 clearfix sp_col">
            <div class="col col-sm-6">
              <strong><?php echo $student->getAttributeLabel('address_line1');?></strong>
            </div>
            <div class="col col-sm-6">
              <?php echo (isset($student->address_line1) and $student->address_line1!="")?$student->address_line1:"-"; ?>
            </div>
            </div>
            <?php } ?>

            <?php if(in_array('address_line2', $student_visible_fields)){ ?>
            <div class="col-sm-6 clearfix sp_col">
            <div class="col col-sm-6">
              <strong><?php echo $student->getAttributeLabel('address_line2');?></strong>
            </div>
            <div class="col col-sm-6">
              <?php echo (isset($student->address_line2) and $student->address_line2!="")?$student->address_line2:"-"; ?>
            </div>
            </div>
            <?php } ?>

            <?php if(in_array('city', $student_visible_fields)){ ?>
            <div class="col-sm-6 clearfix sp_col">
            <div class="col col-sm-6">
              <strong><?php echo $student->getAttributeLabel('city');?></strong>
            </div>
            <div class="col col-sm-6">
              <?php echo (isset($student->city) and $student->city!="")?$student->city:'-'; ?>
            </div>
            </div>
            <?php } ?>

            <?php if(in_array('state', $student_visible_fields)){ ?>
            <div class="col-sm-6 clearfix sp_col">
            <div class="col col-sm-6">
              <strong><?php echo $student->getAttributeLabel('state');?></strong>
            </div>
            <div class="col col-sm-6">
              <?php echo (isset($student->state) and $student->state!="")?$student->state:"-"; ?>
            </div>
            </div>
            <?php } ?>

            <?php if(in_array('pin_code', $student_visible_fields)){ ?>
            <div class="col-sm-6 clearfix sp_col">
            <div class="col col-sm-6">
              <strong><?php echo $student->getAttributeLabel('pin_code');?></strong>
            </div>
            <div class="col col-sm-6">
              <?php echo (isset($student->pin_code) and $student->pin_code!="")?$student->pin_code:"-"; ?>
            </div>
            </div>
            <?php } ?>

            <?php if(in_array('country_id', $student_visible_fields)){ ?>
            <div class="col-sm-6 clearfix sp_col">
            <div class="col col-sm-6">
              <strong><?php echo $student->getAttributeLabel('country_id');?></strong>
            </div>
            <div class="col col-sm-6">
              <?php 
              $count = Countries::model()->findByAttributes(array('id'=>$student->country_id));
              if(count($count)!=0)
                echo $count->name;
              else
                echo "-";
              ?>
            </div>
            </div>
            <?php } ?>

            <?php if(in_array('phone1', $student_visible_fields)){ ?>
            <div class="col-sm-6 clearfix sp_col">
            <div class="col col-sm-6">
              <strong><?php echo $student->getAttributeLabel('phone1');?></strong>
            </div>
            <div class="col col-sm-6">
              <?php echo (isset($student->phone1) and $student->phone1!="")?$student->phone1:"-"; ?>
            </div>
            </div>
            <?php } ?>

            <?php if(in_array('phone2', $student_visible_fields)){ ?>
            <div class="col-sm-6 clearfix sp_col">
            <div class="col col-sm-6">
              <strong><?php echo $student->getAttributeLabel('phone2');?></strong>
            </div>
            <div class="col col-sm-6">
              <?php echo (isset($student->phone2) and $student->phone2!="")?$student->phone2:"-"; ?>
            </div>
            </div>
            <?php } ?>

            <?php if(in_array('email', $student_visible_fields)){ ?>
            <div class="col-sm-6 clearfix sp_col">
            <div class="col col-sm-6">
              <strong><?php echo $student->getAttributeLabel('email');?></strong>
            </div>
            <div class="col col-sm-6">
              <?php echo (isset($student->email) and $student->email!="")?$student->email:"-"; ?>
            </div>
            </div>
            <?php } ?>

            <?php
            // dynamic fields in contact details
            $fields   = FormFields::model()->getDynamicFields(1, 2, "forTeacherPortal");
            foreach ($fields as $key => $field) {
              $field_name = $field->varname;
            ?>
              <?php if(in_array($field_name, $student_visible_fields)){ ?>
              <div class="col-sm-6 clearfix sp_col">
              <div class="col col-sm-6">
                <strong><?php echo $student->getAttributeLabel($field_name);?></strong>
              </div>
              <div class="col col-sm-6">
                <?php
                  if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
                    echo FormFields::model()->getFieldValue($student->$field_name);
                  }
                  else if($field->form_field_type==6){  // date value
                    if($settings!=NULL){
                      $date1  = date($settings->displaydate,strtotime($student->$field_name));
                      echo $date1;
                    }
                    else{
                      echo $student->$field_name;
                    }
                  }
                  else{
                    echo (isset($student->$field_name) and $student->$field_name!="")?$student->$field_name:"-";
                  }
                ?>
              </div>
              </div>
              <?php } ?>
            <?php               
            }
            //dynamic fields end
            ?>

            <div class="col-sm-6 clearfix sp_col">
            <div class="col col-sm-6">
              <strong><?php echo Yii::t('app','Emergency Contact');?></strong>
            </div>
            <div class="col col-sm-6">
              <?php
			  $emergency_contact_detail = Guardians::model()->findByAttributes(array('id'=>$student->immediate_contact_id));			                  
              if($name!="" and $emergency_contact_detail!=NULL)
              {
				  $name = $emergency_contact_detail->parentFullName('forTeacherPortal');
                  echo $name;
              }
              else
                  echo "-";
              //echo $guard->fullname;
              ?>
            </div>
            </div>

            <?php
            // dynamic fields in personal details
            $fields   = FormFields::model()->getDynamicFields(3, 1, "forTeacherPortal");
            foreach ($fields as $key => $field) {
              $field_name = $field->varname;
            ?>
              <?php if(in_array($field_name, $student_visible_fields)){ ?>
              <div class="col-sm-6 clearfix sp_col">
              <div class="col col-sm-6">
                <strong><?php echo $student->getAttributeLabel($field_name);?></strong>
              </div>
              <div class="col col-sm-6">
                <?php
                  if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
                    echo FormFields::model()->getFieldValue($student->$field_name);
                  }
                  else if($field->form_field_type==6){  // date value
                    if($settings!=NULL){
                      $date1  = date($settings->displaydate, strtotime($student->$field_name));
                      echo $date1;
                    }
                    else{
                      echo $student->$field_name;
                    }
                  }
                  else{
                    echo (isset($student->$field_name) and $student->$field_name!="")?$student->$field_name:"-";
                  }
                ?>
              </div>
              </div>
              <?php } ?>
            <?php               
            }
            //dynamic fields end
            ?>

            <div class="clearfix"></div>
      </div>
       <div class="panel-heading"> 
      <!-- panel-btns -->
      <h3 class="panel-title"><?php echo Yii::t('app','Guardian Details'); ?></h3>
    </div>
    
    <div class="people-item">
        <div class="table-responsive">
           
                <?php 
  $guardian_list_data= GuardianList::model()->findAllByAttributes(array('student_id'=>$student->id));
  if($guardian_list_data)
  {
      foreach($guardian_list_data as $key=>$data)
      {
        $guardian_model= Guardians::model()->findByPk($data->guardian_id);
        if($guardian_model)
        {      
          ?>
            <div class="row">
              <div class="col-sm-12">
                <strong><?php echo Yii::t('app', 'Guardian');?> : <?php echo $key+1;?></strong>
              </div>
              <br />
              <br />
              <div class="col-sm-6 clearfix sp_col">
                <div class="col col-sm-6">
                  <strong><?php echo Yii::t('app','Name');?></strong>
                </div>
                <div class="col col-sm-6">
                  <?php 
                  $name="";
                  $name= $guardian_model->parentFullName('forTeacherPortal');
                  if($name!="")
                  {
                      echo $name;
                  }
                  else
                      echo "-";
                  //$guardian_model->fullname; ?>
                </div>
              </div>


              <?php if(in_array('relation', $guardian_visible_fields)){ ?>
              <div class="col-sm-6 clearfix sp_col">
                <div class="col col-sm-6">
                  <strong><?php echo $guardian_model->getAttributeLabel('relation');?></strong>
                </div>
                <div class="col col-sm-6">
                  <?php echo $guardian_model->relation; ?>
                </div>
              </div>
              <?php } ?>

              <?php if(in_array('dob', $guardian_visible_fields)){ ?>
              <div class="col-sm-6 clearfix sp_col">
                <div class="col col-sm-6">
                  <strong><?php echo $guardian_model->getAttributeLabel('dob');?></strong>
                </div>
                <div class="col col-sm-6">
                  <?php echo $guardian_model->dob; ?>
                </div>
              </div>
              <?php } ?>

              <?php if(in_array('education', $guardian_visible_fields)){ ?>
              <div class="col-sm-6 clearfix sp_col">
                <div class="col col-sm-6">
                  <strong><?php echo $guardian_model->getAttributeLabel('education');?></strong>
                </div>
                <div class="col col-sm-6">
                  <?php echo $guardian_model->education; ?>
                </div>
              </div>
              <?php } ?>

              <?php if(in_array('occupation', $guardian_visible_fields)){ ?>
              <div class="col-sm-6 clearfix sp_col">
                <div class="col col-sm-6">
                  <strong><?php echo $guardian_model->getAttributeLabel('occupation');?></strong>
                </div>
                <div class="col col-sm-6">
                    <?php echo $guardian_model->occupation;  ?>
                </div>
              </div>
              <?php } ?>

              <?php if(in_array('income', $guardian_visible_fields)){ ?>
              <div class="col-sm-6 clearfix sp_col">
                <div class="col col-sm-6">
                  <strong><?php echo $guardian_model->getAttributeLabel('income');?></strong>
                </div>
                <div class="col col-sm-6">
                  <?php echo $guardian_model->income; ?>
                </div>
              </div>
              <?php } ?>

              <?php
              // dynamic fields in personal details
              $fields   = FormFields::model()->getDynamicFields(2, 1, "forTeacherPortal");
              foreach ($fields as $key => $field) {
                $field_name = $field->varname;
              ?>
                <?php if(in_array($field_name, $guardian_visible_fields)){ ?>
                <div class="col-sm-6 clearfix sp_col">
                <div class="col col-sm-6">
                  <strong><?php echo $guardian_model->getAttributeLabel($field_name);?></strong>
                </div>
                <div class="col col-sm-6">
                  <?php
                    if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
                      echo FormFields::model()->getFieldValue($guardian_model->$field_name);
                    }
                    else if($field->form_field_type==6){  // date value
                      if($settings!=NULL){
                        $date1  = date($settings->displaydate, strtotime($guardian_model->$field_name));
                        echo $date1;
                      }
                      else{
                        echo $guardian_model->$field_name;
                      }
                    }
                    else{
                      echo (isset($guardian_model->$field_name) and $guardian_model->$field_name!="")?$guardian_model->$field_name:"-";
                    }
                  ?>
                </div>
                </div>
                <?php } ?>
              <?php               
              }
              //dynamic fields end
              ?>


              <?php if(in_array('email', $guardian_visible_fields)){ ?>
              <div class="col-sm-6 clearfix sp_col">
                <div class="col col-sm-6">
                  <strong><?php echo $guardian_model->getAttributeLabel('email');?></strong>
                </div>
                <div class="col col-sm-6">
                  <?php echo $guardian_model->email; ?>
                </div>
              </div>
              <?php } ?>

              <?php if(in_array('mobile_phone', $guardian_visible_fields)){ ?>
              <div class="col-sm-6 clearfix sp_col">
                <div class="col col-sm-6">
                  <strong><?php echo $guardian_model->getAttributeLabel('mobile_phone');?></strong>
                </div>
                <div class="col col-sm-6">
                  <?php echo $guardian_model->mobile_phone; ?>
                </div>
              </div>
              <?php } ?>


              <?php if(in_array('office_phone1', $guardian_visible_fields)){ ?>
              <div class="col-sm-6 clearfix sp_col">
                <div class="col col-sm-6">
                  <strong><?php echo $guardian_model->getAttributeLabel('office_phone1');?></strong>
                </div>
                <div class="col col-sm-6">
                  <?php echo $guardian_model->office_phone1;  ?>
                </div>
              </div>
              <?php } ?>



              <?php if(in_array('office_phone2', $guardian_visible_fields)){ ?>
              <div class="col-sm-6 clearfix sp_col">
                <div class="col col-sm-6">
                  <strong><?php echo $guardian_model->getAttributeLabel('office_phone2');?></strong>
                </div>
                <div class="col col-sm-6">
                  <?php echo $guardian_model->office_phone2;  ?>
                </div>
              </div>
              <?php } ?>
          

              <?php if(in_array('office_address_line1', $guardian_visible_fields)){ ?>
              <div class="col-sm-6 clearfix sp_col">
                <div class="col col-sm-6">
                  <strong><?php echo $guardian_model->getAttributeLabel('office_address_line1');?></strong>
                </div>
                <div class="col col-sm-6">
                  <?php echo $guardian_model->office_address_line1; ?>
                </div>
              </div>
              <?php } ?>


              <?php if(in_array('office_address_line2', $guardian_visible_fields)){ ?>
              <div class="col-sm-6 clearfix sp_col">
                <div class="col col-sm-6">
                  <strong><?php echo $guardian_model->getAttributeLabel('office_address_line2');?></strong>
                </div>
                <div class="col col-sm-6">
                  <?php echo $guardian_model->office_address_line2; ?>
                </div>
              </div>
              <?php } ?>


              <?php if(in_array('city', $guardian_visible_fields)){ ?>
              <div class="col-sm-6 clearfix sp_col">
                <div class="col col-sm-6">
                  <strong><?php echo $guardian_model->getAttributeLabel('city');?></strong>
                </div>
                <div class="col col-sm-6">
                  <?php echo $guardian_model->city;  ?>
                </div>
              </div>
              <?php } ?>


              <?php if(in_array('state', $guardian_visible_fields)){ ?>
              <div class="col-sm-6 clearfix sp_col">
                <div class="col col-sm-6">
                  <strong><?php echo $guardian_model->getAttributeLabel('state');?></strong>
                </div>
                <div class="col col-sm-6">
                  <?php echo $guardian_model->state;  ?>
                </div>
              </div>
              <?php } ?>


              <?php if(in_array('country_id', $guardian_visible_fields)){ ?>
              <div class="col-sm-6 clearfix sp_col">
                <div class="col col-sm-6">
                  <strong><?php echo $guardian_model->getAttributeLabel('country_id');?></strong>
                </div>
                <div class="col col-sm-6">
                  <?php 
                  $country_model= Countries::model()->findByPk($guardian_model->country_id);
                  if($country_model)
                  {
                      echo $country_model->name;
                  }                
                  ?>
                </div>
              </div>
              <?php
              }
              ?>

              <?php
              // dynamic fields in personal details
              $fields   = FormFields::model()->getDynamicFields(2, 2, "forTeacherPortal");
              foreach ($fields as $key => $field) {
                $field_name = $field->varname;
              ?>
                <?php if(in_array($field_name, $guardian_visible_fields)){ ?>
                <div class="col-sm-6 clearfix sp_col">
                <div class="col col-sm-6">
                  <strong><?php echo $guardian_model->getAttributeLabel($field_name);?></strong>
                </div>
                <div class="col col-sm-6">
                  <?php
                    if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
                      echo FormFields::model()->getFieldValue($guardian_model->$field_name);
                    }
                    else if($field->form_field_type==6){  // date value
                      if($settings!=NULL){
                        $date1  = date($settings->displaydate, strtotime($guardian_model->$field_name));
                        echo $date1;
                      }
                      else{
                        echo $guardian_model->$field_name;
                      }
                    }
                    else{
                      echo (isset($guardian_model->$field_name) and $guardian_model->$field_name!="")?$guardian_model->$field_name:"-";
                    }
                  ?>
                </div>
                </div>
                <?php } ?>
              <?php               
              }
              //dynamic fields end
              ?>

            </div>
            <br />
            <?php
        }       
      }
  } 
  ?>            
            <div class="clearfix"></div>
            
        </div>
    </div>

    