
	<?php $this->renderPartial('leftside');?> 
    <?php 
	$semester_enabled	= Configurations::model()->isSemesterEnabled();    
	$student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$batchstudents=BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id,'status'=>1, 'result_status'=>0));
    $guard = Guardians::model()->findByAttributes(array('id'=>$student->parent_id));
    $settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
    $student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentPortal');
    ?>
  <div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-list-alt"></i><?php echo Yii::t('app','Courses');?><span><?php echo Yii::t('app','View your courses here'); ?></span></h2>
        </div>
        
    
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t('app','Courses'); ?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>
    <div class="contentpanel">
    	<!--<div class="col-sm-9 col-lg-12">-->
        <div>
        	<div class="people-item">
      <div class="media"> <a href="#" class="pull-left">
        <?php
                     if($student->photo_file_name!=NULL)
                     {
						$path = Students::model()->getProfileImagePath($student->id);	  
                        echo '<img  src="'.$path.'" alt="'.$student->photo_file_name.'" width="100" height="103" />';
                    }
                    elseif($student->gender=='M')
                    {
                        echo '<img  src="images/portal/prof-img_male.png" alt='.$student->first_name.' width="100" height="103" />'; 
                    }
                    elseif($student->gender=='F')
                    {
                        echo '<img  src="images/portal/prof-img_female.png" alt='.$student->first_name.' width="100" height="103" />';
                    }
                    ?></a>
        
        <div class="media-body">
          <?php
          if(FormFields::model()->isVisible("fullname", "Students", "forStudentPortal")){
          ?>
          <h4 class="person-name"><?php $name	= $student->studentFullName("forStudentPortal");
		  echo CHtml::link($name,array('/studentportal/default/profile', 'student_id'=>$student->id));
		  ?></h4>
          <?php
          }
          ?>
          
		<?php if(count($batchstudents) == 1){ ?>
				  <?php if(in_array('batch_id', $student_visible_fields)){ ?>      
				  <div class="text-muted"><strong><?php echo Yii::t('app','Course').' :';?></strong>
					<?php 
					  $batch = Batches::model()->findByPk($batchstudents[0]['batch_id']);
					  $course = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
					  $sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);
					  $semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
					  echo $batch->course123->course_name;
					  $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
					?>
				  </div>          
				  <div class="text-muted"> <strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' :';?></strong> <?php echo $batch->name;?></div>
				  <?php } ?>
				   
				    <?php  if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id!=NULL){ ?>
							<div class="text-muted"> <strong><?php echo Yii::t('app','Semester').' :';?></strong> <?php echo $semester->name;?></div>
					<?php } ?>
		<?php } ?>	
			
          <div class="text-muted"><strong><?php echo Yii::t('app','Admission No').' :';?></strong> <?php echo $student->admission_no; ?></div>
          <?php if($batch_student!=NULL and $batch_student->roll_no!=0){ ?>
           <div class="text-muted"><strong><?php echo Yii::t('app','Roll No').' :';?></strong> <?php echo $batch_student->roll_no; ?></div>
          <?php } ?>
          
        </div>

      </div>
      </div>
    </div>
    	<div class="panel-heading"> 
      <!-- panel-btns -->
      <h3 class="panel-title"><?php echo Yii::t('app','Course Details'); ?></h3>
    </div>
    <div class="people-item">
    	<div class="table-responsive">
         <h5 class="subtitle"><?php echo Yii::t('app', 'Course Details'); ?></h5>
		<p><?php echo Yii::t('app', 'View your courses here'); ?></p> 
        	<table width="100%" class="table table-bordered mb30" cellpadding="0" cellspacing="0">
            <thead>
                        <tr>
                            <th><?php echo Yii::t('app','Sl No');?></th>
                            <th><?php echo Yii::t('app','Academic Year');?></th>
                            <?php if(in_array('batch_id', $student_visible_fields)){ ?>
                            <th><?php echo Yii::app()->getModule("students")->labelCourseBatch();?></th>
                            <?php } ?>
							 <?php if($semester_enabled == 1){?>
							<th><?php echo Yii::t('app','Semester');?></th>
							<?php } ?>
                            <th><?php echo Yii::t('app','Status');?></th>
                        </tr>
                        </thead>
                        <?php
                        $batches = BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id));
                        $sl_no = 1;
                        foreach($batches as $batch)
                        {
                        ?>
                            <tr>
                                <td>
                                    <?php echo $sl_no; ?>
                                </td>
                                <td>
                                    <?php
                                    $academic_year = AcademicYears::model()->findByAttributes(array('id'=>$batch->academic_yr_id));
                                    echo $academic_year->name;
                                    ?>
                                </td>
                                <?php if(in_array('batch_id', $student_visible_fields)){ ?>
                                <td>
                                    <?php
                                    $batch_name = Batches::model()->findByAttributes(array('id'=>$batch->batch_id));
                                    echo $batch_name->course123->course_name.' / '.$batch_name->name;
                                    ?>
                                </td>
                                <?php } ?>
								 <?php if($semester_enabled == 1){
									 $course = Courses::model()->findByAttributes(array('id'=>$batch_name->course_id));
									 $sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);?>
								<td>
								<?php if($sem_enabled == 1 and $batch_name->semester_id!=NULL){ 
										$semester = Semester::model()->findByAttributes(array('id'=>$batch_name->semester_id));
										echo ucfirst($semester->name);
							          } 
									  else{
										  echo '-';
									  }?>
								</td>
							<?php } ?>
                                <td>
                                    <div class="opnsl_pending"><?php
                                    $status = PromoteOptions::model()->findByAttributes(array('option_value'=>$batch->result_status));
                                   // echo Yii::t('app',$status->option_name);
								   if($batch->result_status == 1 and $batch->status != 2)
										$status_print = '<span style="color:#006633">'.Yii::t('app',$status->option_name).'</span>';
									if($batch->result_status == -1 and $batch->status != 2)
										$status_print = '<span style="color:#FF0000">'.Yii::t('app',$status->option_name).'</span>';
									if($batch->result_status == 0 and $batch->status != 2)
										$status_print = '<span style="color:#006633">'.Yii::t('app',$status->option_name).'</span>';	
									if($batch->result_status == 2 and $batch->status != 2)
										$status_print = '<span style="color:#0000FF">'.Yii::t('app',$status->option_name).'</span>';
									if($batch->result_status == 3 and $batch->status != 2)
										$status_print = '<span style="color:#0000FF">'.Yii::t('app','Previous').'</span>';	
									if($batch->status == 2)
										$status_print = '<span style="color:#C09">'.Yii::t('app','Inactive').'</span>';	
									echo $status_print;
                                    ?>
                                    </div>
                                </td>
                            </tr>
                        <?php
                            $sl_no++;
                        }
                        ?>
                    </table>
        </div>
    </div>
        </div>
    </div>  
 

