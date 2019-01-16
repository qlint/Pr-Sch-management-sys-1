<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,400italic' rel='stylesheet' type='text/css'>
<style>
.sp_col{
  border-bottom:1px #eee solid;
  padding-bottom:8px;
}
</style>
<?php $this->renderPartial('/default/leftside');
$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
$student=Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
$batchstudents=BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id,'status'=>1, 'result_status'=>0));
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
     <?php $semester_enabled	= Configurations::model()->isSemesterEnabled(); ?> 
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
                       echo CHtml::link($name,array('/teachersportal/course/students', 'student_id'=>$student->id));   
	             }
                    else
                        echo "-";
          ?></h4>
		  
		  <?php if(count($batchstudents) == 1){ ?>
				  <?php    if(FormFields::model()->isVisible('batch_id','Students','forTeacherPortal')){ ?>      
				  <div class="text-muted"><strong><?php echo Yii::t('app','Course').' :';?></strong>
					<?php 
					  $batch = Batches::model()->findByPk($student->batch_id);
					  $semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
					  echo $batch->course123->course_name;
					  $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
					?>
				  </div>          
				  <div class="text-muted"> <strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' :';?></strong> <?php echo $batch->name;?></div>
				  <?php } ?>
				   <?php  if($batch->semester_id!=NULL){ ?>
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
   
   <div class="panel-heading"> 
      <!-- panel-btns -->
      <h3 class="panel-title"><?php echo Yii::t('app','Course Details'); ?></h3>
    </div>

   <div class="people-item">
    	<div class="table-responsive">
        	<table class="table table-bordered table-striped" cellspacing="0" cellpadding="0">
            <thead>
                        <tr>
                            <th><?php echo Yii::t('app','Sl No');?></th>
                            <th><?php echo Yii::t('app','Academic Year');?></th>
                            <?php if(in_array('batch_id', $student_visible_fields)){ ?>
                            <th><?php echo Yii::app()->getModule("students")->labelCourseBatch();?></th>
                            <?php } ?>
                            <?php if($semester_enabled == 1){ ?>
							<th><?php echo Yii::t('app','Semester');?></th>
                            <?php } ?>
                            <th><?php echo Yii::t('app','Status');?></th>
                        </tr>
                        </thead>
                        <tbody>
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
                                <?php if($semester_enabled == 1){?>
								<td>
								<?php if($batch_name->semester_id!=NULL){ 
										$semester = Semester::model()->findByAttributes(array('id'=>$batch_name->semester_id));
										echo ucfirst($semester->name);
							          } 
									  else{
										  echo '-';
									  }?>
								</td>
                                <?php } ?>
                                <td>
                                    <?php
                                    $status = PromoteOptions::model()->findByAttributes(array('option_value'=>$batch->result_status));
                                    echo Yii::t('app',$status->option_name);
                                    ?>
                                </td>
                            </tr>
                        <?php
                            $sl_no++;
                        }
                        ?>
                        </tbody>
                    </table>
        </div>
    </div>
        </div>


           

           
            
           
          
           
          
          
          

            
          

            
      
    
   
    