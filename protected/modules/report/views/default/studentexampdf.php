<style>
table.assessment_table {
	margin: 30px 0px;
	font-size: 8px;
	text-align: center;
	border-collapse: collapse;
	width: auto;/*max-width:600px;*/
}
.assessment_table td {
	padding-top: 10px;
	padding-bottom: 10px;
	border: 1px solid #C5CED9;
	width: auto;
	font-size: 13px;
}
.assessment_table th {
	font-size: 13px;
	padding: 10px;
	border-left: 1px #C5CED9 solid;
	border-bottom: 1px #C5CED9 solid;
}
hr {
	border-bottom: 1px solid #C5CED9;
	border-top: 0px solid #fff;
}
.table-exam table {
	border-collapse: collapse;
}
.table-exam table td {
	border: 1px solid #ccc;
	padding:5px;
}
.table-exam table th {
	border: 1px solid #ccc;
	text-align: left !important;
	 padding:5px;
	  font-size:13px;
}
</style>
<?php
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
if(isset($_REQUEST['exam_group_id']))
{ 
?>

<!-- Header -->

<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td class="first" width="100"><?php
						    $filename=  Logo::model()->getLogo();
							if($filename!=NULL)
                            {
                                //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                echo '<img src="uploadedfiles/school_logo/'.$filename[2].'" alt="'.$filename[2].'" class="imgbrder" height="100" />';
                            }
                            ?></td>
    <td valign="middle" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="listbxtop_hdng first" style="text-align:left; font-size:22px; padding-left:10px;"><?php $college=Configurations::model()->findAll(); ?>
            <?php echo $college[0]->config_value; ?></td>
        </tr>
        <tr>
          <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;"><?php echo $college[1]->config_value; ?></td>
        </tr>
        <tr>
          <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;"><?php echo Yii::t('app','Phone: ').$college[2]->config_value; ?></td>
        </tr>
      </table></td>
  </tr>
</table>
<hr />
<br />
<!-- End Header -->
<?php $student=Students::model()->findByAttributes(array('id'=>$_REQUEST['id'],'is_deleted'=>0,'is_active'=>1)); ?>
<?php
    if(isset($_REQUEST['id']))
    {  
	
	$exams = Exams::model()->findAll('exam_group_id=:x',array(':x'=>$_REQUEST['exam_group_id'])); // Selecting exams(subjects) in an exam group
	$allstatus	= 0;
	if($exams!=NULL){
		foreach($exams as $exam){
			$allmin 	 = $exam->minimum_marks;
			$subject = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
			if($subject!=NULL){ 
				$score = ExamScores::model()->findByAttributes(array('exam_id'=>$exam->id,'student_id'=>$student->id));
				if($score!=NULL){
					if($score->marks<$allmin)
						$allstatus = 1;
				}
			}
		}
	}
	else
		$allstatus = 2;
	
   ?>
<div align="center" style="text-align:center; display:block;"><?php echo Yii::t('app','EXAM RESULT REPORT') ; ?></div>
<br />

<!-- Batch details -->
   <table style="font-size:13px; background:#DCE6F1; padding:10px 10px;border:#C5CED9 1px solid;">
        	<tr>
            	<?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
            	<td style="width:150px;"><?php echo Yii::t('app','Student Name');?></td>
                <td style="width:10px;">:</td>
                <td style="width:200px;"><?php echo $student->studentFullName("forStudentProfile");?></td>
                
                <td><?php echo Yii::t('app','Admission Number');?></td>
                <td style="width:10px;">:</td>
                <td><?php echo $student->admission_no;?></td>
                <?php } 
				else{
				?>
                <td style="width:150px;"><?php echo Yii::t('app','Admission Number');?></td>
                <td style="width:10px;">:</td>
                <td style="width:200px;"><?php echo $student->admission_no;?></td>
                <td>&nbsp;</td>
                <td style="width:10px;">&nbsp;</td>
                <td>&nbsp;</td>
                <?php }?>
            </tr>
            <tr>
            	<?php 
				$batch = Batches::model()->findByAttributes(array('id'=>$student->batch_id));
				$course = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
				$semester_enabled	= Configurations::model()->isSemesterEnabled(); 
 				$sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);
				?>
    <?php if(in_array('batch_id', $student_visible_fields)){ ?>
    <td><?php echo Yii::t('app','Course');?></td>
    <td>:</td>
    <td ><?php 
					if($course->course_name!=NULL)
						echo ucfirst($course->course_name);
					else
						echo '-';
					?></td>
    <td><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></td>
    <td>:</td>
    <td><?php 
					if($batch->name!=NULL)
						echo ucfirst($batch->name);
					else
						echo '-';
					?></td>
    <?php } ?>
  </tr>
  <tr>
    <td><?php echo Yii::t('app','Examination');?></td>
    <td>:</td>
    <td><?php
					$exam = ExamGroups::model()->findByAttributes(array('id'=>$_REQUEST['exam_group_id']));
					if($exam->name!=NULL)
						echo ucfirst($exam->name);
					else
						echo '-';
					?></td>
    <td width="150"><?php echo Yii::t('app','Exam Date');?></td>
    <td>:</td>
    <td width="175"><?php
					if($exam->name!=NULL)
					{
						$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
						if($settings!=NULL)
						{	
							$exam->exam_date=date($settings->displaydate,strtotime($exam->exam_date));	
						}
						echo $exam->exam_date;
					}
					else
						echo '-';
					?></td>
  </tr>
  <tr>
    <td><?php echo Yii::t('app','Result');?></td>
    <td>:</td>
    <td><?php
				if($allstatus == 0)
					echo "<span style='color:#006600'>".Yii::t('app','Passed')."</span>";
				else if($allstatus ==1)
					echo "<span style='color:#F00'>".Yii::t('app','Failed')."</span>";?></td>
	<?php if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id != NULL){
			$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); ?>				
			 <td><?php echo Yii::t('app','Semester');?></td>
			 <td>:</td>
			 <td><?php echo ucfirst($semester->name);?></td>
	<?php } ?>
  </tr>
</table>

<!-- END Batch details -->
<div class="table-exam">
<!-- Single Exam Table -->
<table width="100%" cellspacing="0" cellpadding="0" class="assessment_table">
  <tr class="tablebx_topbg" style="background-color:#DCE6F1;">
      <th style="width:150px;" colspan="2"><?php echo Yii::t('app','Subject');?></th>
        <?php
		$examgrp = ExamGroups::model()->findByAttributes(array('id'=>$_REQUEST['exam_group_id']));?>
			<th style="width:130px;"><?php echo Yii::t('app','Score');?></th>
            <th><?php echo Yii::t('app','Status');?></th>
            <th><?php echo Yii::t('app','Remarks');?></th>
    </tr> 
	  <?php			
		$exams = Exams::model()->findAll('exam_group_id=:x',array(':x'=>$_REQUEST['exam_group_id'])); // Selecting exams(subjects) in an exam group
		if($exams!=NULL)
		{
			foreach($exams as $exam)
			{
				
				$min 	 = $exam->minimum_marks;
				$subject = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
				if($subject!=NULL) // Checking if exam for atleast subject is created.
				{ 
					$score = ExamScores::model()->findByAttributes(array('exam_id'=>$exam->id,'student_id'=>$student->id));
					if($score!=NULL)
					{
						 if($score->marks<$min){
							$status = 1;
						 }
						 else
							$status = 0; 
						 if($subject->split_subject == 1){
						?>
                          <tr>
                              <td rowspan="3" >
                              	<?php 
								if($subject->name!=NULL){
									if($subject->elective_group_id==0){ 
										echo ($subject->name!=NULL)? ucfirst($subject->name):'-';
									}else{
										$electives 	= StudentElectives::model()->findByAttributes(array('student_id'=>$student->id, 'elective_group_id'=>$subject->elective_group_id));
										$elective	= Electives::model()->findByAttributes(array('id'=>$electives->elective_id));
										echo (($subject->name!=NULL)? ucfirst($subject->name).' (':'').$elective->name.(($subject->name!=NULL)?')':'');
										}
								}
								else
								continue;
								
								$subject_cps	=	ExamScoresSplit::model()->findAllByAttributes(array('exam_scores_id'=>$score->id));
								$mark_value=array();
								foreach($subject_cps as $subject_cp){
									$mark_value[]=$subject_cp->mark;
								} 
								$subjects_splits	=	SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$exam->subject_id));  
								$sub_value=array();
								foreach($subjects_splits as $subjects_split){
									$sub_value[]=$subjects_split->split_name;
								}  
								
						?>
                              </td>
                              <td><?php echo $sub_value[0];?></td>
                              <td><?php echo $mark_value[0];?></td>
                              <td rowspan="3" ><span style="color:#006600"><?php  
																			if($status  == 0){
																				echo "<span style='color:#006600'>".Yii::t('app','Passed').$roles."</span>";
																			}else{
																				echo "<span style='color:#F00'>".Yii::t('app','Failed')."</span>";
																			}
																		?></span></td>
                              <td rowspan="3" ><span><?php
														   if($score->remarks!=NULL)
														   {
															   echo $score->remarks;
														   }
														   else
														   {
															   echo '-';
														   }
														   ?></span></td>
                            </tr>
                            <tr>
                              <td><?php echo $sub_value[1];?></td>
                              <td><?php echo $mark_value[1];?></td>
                            </tr>
                            <tr>
                              <td><?php echo Yii::t('app','Total');?></td>
                              <td><?php
									$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>$batch->id),array('order'=>'min_score DESC'));
									
				
									if(!$grades)
									{
										$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>NULL));	
									}
									 $t = count($grades);
									 if($examgrp->exam_type == 'Marks') {  
									 echo $score->marks; 
									 
									 } else if($examgrp->exam_type == 'Grades') {
										
									   foreach($grades as $grade)
											{
												
											 if($grade->min_score <= $score->marks)
												{	
													$grade_value =  $grade->name;
												}
												else
												{
													$t--;
													
													continue;
													
												} 
											echo $grade_value ;
											break;
											
											}
											if($t<=0) 
												{
													$glevel = " No Grades" ;
												} 
											
											} 
									   else if($examgrp->exam_type == 'Marks And Grades'){
										 foreach($grades as $grade)
											{
												
											 if($grade->min_score <= $score->marks)
												{	
													$grade_value =  $grade->name;
												}
												else
												{
													$t--;
													
													continue;
													
												}
											echo $score->marks . " & ".$grade_value ;
											break;
											
												
											} 
											if($t<=0) 
												{
													echo $score->marks." & ".Yii::t('app',"No Grades") ;
												}
											 } 
									?></td>
                            </tr>
                        <?php }else{
							?>                          
                            <tr>
                              <td colspan="2"><?php 
										if($subject->name!=NULL){
											
											if($subject->elective_group_id==0){
												echo ($subject->name!=NULL)? ucfirst($subject->name):'-';
											}else{
											 
												$electives 	= StudentElectives::model()->findByAttributes(array('student_id'=>$student->id, 'elective_group_id'=>$subject->elective_group_id));
												$elective	= Electives::model()->findByAttributes(array('id'=>$electives->elective_id));
												echo (($subject->name!=NULL)? ucfirst($subject->name).' (':'').$elective->name.(($subject->name!=NULL)?')':'');
												}
										}
										else
										continue;
								?></td>
                              <td> <?php
									$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>$batch->id),array('order'=>'min_score DESC'));
									
				
									if(!$grades)
									{
										$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>NULL));	
									}
									 $t = count($grades);
									 if($examgrp->exam_type == 'Marks') {  
									 echo $score->marks; 
									 
									 } else if($examgrp->exam_type == 'Grades') {
										
									   foreach($grades as $grade)
											{
												
											 if($grade->min_score <= $score->marks)
												{	
													$grade_value =  $grade->name;
												}
												else
												{
													$t--;
													
													continue;
													
												} 
											echo $grade_value ;
											break;
											
											}
											if($t<=0) 
												{
													$glevel = " No Grades" ;
												} 
											
											} 
									   else if($examgrp->exam_type == 'Marks And Grades'){
										 foreach($grades as $grade)
											{
												
											 if($grade->min_score <= $score->marks)
												{	
													$grade_value =  $grade->name;
												}
												else
												{
													$t--;
													
													continue;
													
												}
											echo $score->marks . " & ".$grade_value ;
											break;
											
												
											} 
											if($t<=0) 
												{
													echo $score->marks." & ".Yii::t('app',"No Grades") ;
												}
											 } 
									?></td>
                              <td><?php  
									if($status  == 0){
										echo "<span style='color:#006600'>".Yii::t('app','Passed').$roles."</span>";
									}else{
										echo "<span style='color:#F00'>".Yii::t('app','Failed')."</span>";
									}
								?></td>
                                 <td> <?php
                               if($score->remarks!=NULL)
                               {
                                   echo $score->remarks;
                               }
                               else
                               {
                                   echo '-';
                               }
                               ?></td> 
                            </tr>
                       <?php	
						}
					}
				}
			}
		}
		else //If no exam created
		{
			echo '<tr><td colspan="5" style="text-align:center; ">'.Yii::t('app','No exam created for any subject in this').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.'!'.'</td></tr>';
		}
		?>
   
  </table>
</div>





<!-- END Single Exam Table -->

<?php
    }
    ?>
<?php
}
else
{
	
	echo '<td align="center" colspan="5"><strong>'.Yii::t('app','No Data Available!').'</strong></td></tr>';
}
?>

