

<?php 
$this->pageTitle=Yii::app()->name . ' - '.Yii::t("app", "Profile");
$this->breadcrumbs=array(
	UserModule::t("Profile"),
);

$student_id= $_REQUEST['id'];
$model= Students::model()->findByPk($student_id);
$batch_id= $_REQUEST['bid'];
$batch=Batches::model()->findByAttributes(array('id'=>$batch_id));
$course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
$batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student_id, 'batch_id'=>$batch_id));
$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
$semester_enabled	= Configurations::model()->isSemesterEnabled();
$sem_enabled		= Configurations::model()->isSemesterEnabledForCourse($course->id);

$year_name="";
    if($batch->academic_yr_id!=NULL)
    {
        $academic_model= AcademicYears::model()->findByPk($batch->academic_yr_id);
        if($academic_model!=NULL)
        {
          $year_name=  $academic_model->name; 
        }        

    }

 $fa1_weightage = $fa2_weightage = $sa1_weightage =  $sa2_weightage = "";
    $weightage_settings= CbscExamSettings::model()->findByAttributes(array('academic_yr_id'=>1));
    if($weightage_settings!=NULL)
    {    
        
        $fa1_weightage= $weightage_settings->fa1_weightage;
        $fa2_weightage= $weightage_settings->fa2_weightage;
        $sa1_weightage= $weightage_settings->sa1_weightage;
        $sa2_weightage= $weightage_settings->sa2_weightage;
    }
?>
<?php echo $this->renderPartial('/default/leftside');?>

<div class="pageheader">
  <h2><i class="fa fa-pencil"></i> <?php echo Yii::t("app", "Exams");?> <span><?php echo Yii::t("app", "View Results");?></span></h2>
  <div class="breadcrumb-wrapper"> <span class="label"><?php echo Yii::t("app", "You are here:");?></span>
    <ol class="breadcrumb">
      <!--<li><a href="index.html">Home</a></li>-->
      <li class="active"><?php echo Yii::t("app", "Results");?></li>
    </ol>
  </div>
</div>
<div class="contentpanel">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo Yii::t('app', 'CBSE Grade Book'); ?></h3>
  </div>
  <div class="people-item">
    <div class="report-stu-dtls-table">
      <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tbody>
          <tr>
            <td><span><?php echo Yii::t('app','Student Name'); ?></span></td>
            <td><?php if(FormFields::model()->isVisible("fullname", "Students", 'forStudentProfile')){
										echo $model->studentFullName('forStudentProfile');
					  } 
               ?></td>
            <td><span><?php echo Yii::t('app','Admission No'); ?></span></td>
            <td><p><?php if(FormFields::model()->isVisible('admission_no','Students','forStudentProfile'))
										   {
											  echo $model->admission_no;         
										   }
										   ?></p></td>
            <td><span><?php echo Yii::t('app','Date Of Birth'); ?></span></td>
            <td><p><?php if(FormFields::model()->isVisible('date_of_birth','Students','forStudentProfile'))
								{
									if($settings!=NULL and $model->date_of_birth!=NULL)
									{	
											$date1 = date($settings->displaydate,strtotime($model->date_of_birth));
											echo $date1;										
									}else{
											echo '-';
									}
								}
								?></p></td>
          </tr>
          <tr>
                <td><span><?php echo Yii::t('app',"Mother's Name"); ?></span></td>
                <td><?php echo Students::model()->getMotherName($model->id); ?></td>
                <td><span><?php echo Yii::t('app',"Father's Name"); ?></span></td>
                <td colspan="2"><?php echo Students::model()->getFatherName($model->id); ?></td>
              </tr>
              <tr>
                <td><span><?php echo Yii::t('app',"Course"); ?></span></td>
                <td><p><?php echo $course->course_name;?></p></td>
                <td><span><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?></span></td>
                <td><p><?php echo $batch->name;?></p></td>
                <td><span><?php echo Yii::t('app',"Roll No"); ?></span></td>
                <td><p><?php echo $batch_student->roll_no;?></p></td>
              </tr>
	<?php  if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id != NULL){
				$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); ?>		  
			   <tr>
                <td><span><?php echo Yii::t('app',"Semester"); ?></span></td>
                <td><p><?php echo ucfirst($semester->name);?></p></td>
              </tr>
	<?php } ?>
        </tbody>
      </table>
    </div>
    <div class="os-table tablebx">
      <div class="tbl-grd"></div>
      
      <?php
	  $details=Students::model()->findByAttributes(array('id'=>$model->id,'is_deleted'=>0,'is_active'=>1));
	  $batch=Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid'],'is_deleted'=>0,'is_active'=>1));
	  $course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
	  $examgroups = CbscExamGroup17::model()->findAll('batch_id=:x',array(':x'=>$batch->id)); // Selecting exam groups in the batch of the student
	  if($examgroups!=NULL) // If exam groups present
	  {
		  $i = 1;
		  foreach($examgroups as $examgroup) 
		  {
			  $flag1=0; 
			  $exams = CbscExams17::model()->findAll('exam_group_id=:x',array(':x'=>$examgroup->id)); // Selecting exams(subjects) in an exam group
			  if($exams!=NULL)
			  {
					?> 
					<h4><?php echo $i.'. '.ucfirst($examgroup->name); $i++;?></h4> 
					<?php
					foreach($exams as $exam)
					{
						$subject = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
						if($subject!=NULL) // Checking if exam for atleast subject is created.
						{ 
							$score = CbscExamScores17::model()->findByAttributes(array('exam_id'=>$exam->id,'student_id'=>$model->id));
							if($score!=NULL)
							{
								$flag1=1;
							}
						}
					}


				  ?>
                   <?php
						 if($flag1==1)
				  { ?>
                <div class="opnsl_headerBox">
                    <div class="opnsl_actn_box"> </div>
                    <div class="opnsl_actn_box">
                   
						<span style="float:right"><?php echo CHtml::link(Yii::t('app', 'Generate PDF'), array('/teachersportal/exams/studentcbscpdf','id'=>$model->id, 'bid'=>$batch->id, 'exam_group_id'=>$examgroup->id),array('target'=>"_blank",'class'=>' btn btn-danger  pull-right')); ?></span>
						
                    </div>
                </div>
                <?php
				  }
					?>
                  <div class="table-responsive">
                  <table class="table table-bordered mb30" width="100%" cellpadding="0" cellspacing="0">
                    <thead>
                      	<tr>
                            <th class="header-td1"><?php echo Yii::t('app','Subject'); ?></th>
                            <th class="header-td1"><?php echo Yii::t('app','Score'); ?></th>
							<th class="header-td1"><?php echo Yii::t('app','Grade'); ?></th>
                            <th class="header-td1"><?php echo Yii::t('app','Remarks'); ?></th> 
                          </tr>
                     </thead>
                     <?php
					 $k=0;
                    $status	=0;
                    if($exams!=NULL)
					{
						foreach($exams as $exam)
						{
							$min 	 = $exam->minimum_marks;
							$subject = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
							if($subject!=NULL) // Checking if exam for atleast subject is created.
							{
								$score = CbscExamScores17::model()->findByAttributes(array('exam_id'=>$exam->id,'student_id'=>$model->id,));
								if($score!=NULL)                            
								{
									$k=1; ?>
                                    <tr>
                                    <td>
									<?php
										if($subject->name!=NULL){
											if($subject->elective_group_id==0){
												echo ($subject->name!=NULL)? ucfirst($subject->name):'-';
											}else{	 
												$electives 	= StudentElectives::model()->findByAttributes(array('student_id'=>$student, 'elective_group_id'=>$subject->elective_group_id));
												$elective	= Electives::model()->findByAttributes(array('id'=>$electives->elective_id));
												echo (($subject->name!=NULL)? ucfirst($subject->name).' (':'').$elective->name.(($subject->name!=NULL)?')':'');
											}
										}
										else
										continue;
										?> 
                                    </td>
                                    <td><?php
                                        if($score->total!=NULL)
                                        {
                                        	echo $score->total;
                                        }
                                        else
                                        {
                                       		echo '-';
                                        }
                                        ?></td>
									 <td><?php if($examgroup->class == 1){
											  	   echo CbscExamScores17::model()->getClass1Grade($score->total);
												  
											  }
											  else{
												   echo CbscExamScores17::model()->getClass2Grade($score->total);
												   
											  }
									 ?>
</td>
                                    <td>
                                    	<?php
                                        if($score->remarks!=NULL)
                                        {
                                   	     echo $score->remarks;
                                        }
                                        else
                                        {
                                     	   echo '-';
                                        }
                                        ?>
                                    </td>
                                    </tr>
                                    <?php
								
								}   								
								
							}
							
						}
					}
                    if($k==0){ 
						?>
						<tr>
							<td colspan="4"><?php echo Yii::t('app','No details available!') ;?></td>
						</tr>
						<?php
									
					}
                    ?>
                 </table>
                 </div>
                  <?php
				  
			  }
		  }
	  }
	  ?>