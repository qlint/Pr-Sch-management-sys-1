<?php 
$this->pageTitle=Yii::app()->name . ' - '.Yii::t("app", "Profile");
$this->breadcrumbs=array(
	UserModule::t("Profile"),
);


$model= Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
$student_id = $model->id;
$batch_id= $_GET['bid'];
$batch=Batches::model()->findByAttributes(array('id'=>$batch_id));
$course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
$batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student_id, 'batch_id'=>$batch_id));
$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));

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
  <div class="row">
  </div>
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
                <td><?php echo Students::model()->getFatherName($model->id); ?></td>
                <td><span><?php echo Yii::t('app',"Course"); ?></span></td>
                <td><p><?php echo $course->course_name;?></p></td>
              </tr>
              <tr>
			  <?php
                  $semester_enabled		= Configurations::model()->isSemesterEnabled();   
				  $sem_enabled			= Configurations::model()->isSemesterEnabledForCourse($batch->course_id);
				  if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id!=NULL){ 
			   			$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));?>
							<td><span><?php echo Yii::t('app',"Semester"); ?></span></td>
							<td><p><?php echo $semester->name;?></p></td>
                <?php } ?>
                <td><span><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?></span></td>
                <td><p><?php echo $batch->name;?></p></td>
                <td><span><?php echo Yii::t('app',"Roll No"); ?></span></td>
                <td><p><?php echo $batch_student->roll_no;?></p></td>
              </tr>
        </tbody>
      </table>
    </div>
    <div class="os-table tablebx">
      <div class="tbl-grd"></div>
      <?php 
	  $examgroups = CbscExamGroup17::model()->findAllByAttributes(array('batch_id'=>$batch_id, 'result_published'=>1)); // Selecting exam groups in the batch of the student
	  if($examgroups!=NULL) // If exam groups present
	  {
		  $i = 1;
		  foreach($examgroups as $examgroup) 
		  {
			  $flag1=0; 
			  $exams = CbscExams17::model()->findAll('exam_group_id=:x',array(':x'=>$examgroup->id)); // Selecting exams(subjects) in an exam group
			  if($exams!=NULL)
		  	  {
				   	echo "<h4>".$i.'. '.ucfirst($examgroup->name)."</h4>"; $i++; 
					foreach($exams as $exam)
					{ 
						$subject = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
						if($subject!=NULL) // Checking if exam for atleast subject is created.
						{  
							$score = CbscExamScores17::model()->findByAttributes(array('exam_id'=>$exam->id,'student_id'=>$student_id));
							if($score!=NULL)
							{ 
								$flag1=1;
							}
						}
					} 
					if($flag1==1)
					{  
						  
                      ?>   
                      <div class="col-md-12">
					  	<?php  echo CHtml::link(Yii::t('app','Generate PDF'), array('/studentportal/default/cbsc17Pdf','exam_group_id'=>$examgroup->id,'id'=>$student_id, 'bid'=>$_REQUEST['bid']),array('class'=>'btn btn-danger pull-right','target'=>'_blank')); ?>
                       </div>
                        <table class="table" width="100%" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td class="header-td1"><?php echo Yii::t('app','SUBJECT'); ?></td>
                                <td class="header-td1"><?php echo Yii::t('app','SCORE'); ?></td>
								 <td class="header-td1"><?php echo Yii::t('app','GRADE'); ?></td>
                                <td class="header-td1"><?php echo Yii::t('app','REMARKS'); ?></td> 
                            </tr>
                            <?php
							$status	=0;
							if($exams!=NULL)
							{
								foreach($exams as $exam)
								{
									$min 	 = $exam->minimum_marks;
									$subject = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
									if($subject!=NULL) // Checking if exam for atleast subject is created.
									{
										 
										$score = CbscExamScores17::model()->findByAttributes(array('exam_id'=>$exam->id,'student_id'=>$student_id));
										if($score!=NULL)                            
										{ 
										?>
                                        <tr>
                                            <td class="header-td1">
                                            	<?php 
												if($subject->name!=NULL){
													if($subject->elective_group_id==0){
														echo ($subject->name!=NULL)? ucfirst($subject->name):'-';
													}else{	 
														$electives 	= StudentElectives::model()->findByAttributes(array('student_id'=>$student_id, 'elective_group_id'=>$subject->elective_group_id));
														$elective	= Electives::model()->findByAttributes(array('id'=>$electives->elective_id));
														echo (($subject->name!=NULL)? ucfirst($subject->name).' (':'').$elective->name.(($subject->name!=NULL)?')':'');
													}
												}
												else
												continue;
												?>
                                            </td>
                                            <td class="header-td1">
												<?php
                                                if($score->total!=NULL)
                                                {
                                               	 echo $score->total;
                                                }
                                                else
                                                {
                                              	  echo '-';
                                                }
                                                ?>
                                            </td>
											<td class="header-td1">
											  <?php if($examgroup->class == 1){
											  	   echo CbscExamScores17::model()->getClass1Grade($score->total);
												  
											  }
											  else{
												   echo CbscExamScores17::model()->getClass2Grade($score->total);
												   
											  }
									 		?>
											</td>
                                    		
                                            <td class="header-td1">
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
							}?>
                            
                         </tbody>
                       </table> 
                        <?php
						
					} 				  
			  }
		  }
	  }
      
      ?>
      
      
    </div>
  </div>
</div>
