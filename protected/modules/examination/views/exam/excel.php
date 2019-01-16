<?php
$students = BatchStudents::model()->BatchStudent($_REQUEST['batchid']);
$exam_groups = ExamGroups::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['batchid']));
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['batchid'],'is_active'=>1,'is_deleted'=>0));
$course = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
$subject = Subjects::model()->findByAttributes(array('id'=>$_REQUEST['subjectid']));
$semester_enabled	= 	Configurations::model()->isSemesterEnabled(); 
$sem_enabled		= 	Configurations::model()->isSemesterEnabledForCourse($course->id);
$semester			= 	Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
$batch_id = $_REQUEST['batchid'];
if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2){ // for cbsc format
	$exam_groups = CbscExamGroups::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['batchid']));
}
else{
	$exam_groups = ExamGroups::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['batchid']));
}
?>
	<table border="1">
        <tr>
			<?php if(in_array('batch_id', $student_visible_fields)){?>
				<td align="center"><font size="+2"><b><?php echo Yii::t('app','Course');?></b></font></td>           
				<td align="center"><?php echo ucfirst($course->course_name);?></td>            
				<td align="center"><font size="+2"><b><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></b></font></td>           
				<td align="center"><?php echo ucfirst($batch->name);?></td>
				<?php if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id != NULL){ ?>
				<td align="center"><font size="+2"><b><?php echo Yii::t('app','Semester');?></b></font></td>           
				<td align="center"><?php echo ucfirst($semester->name);?></td> 
				<?php } ?>  
			<?php } ?>
				 <td align="center"><font size="+2"><b><?php echo Yii::t('app','Subject');?></b></font></td>            
				<td align="center"><?php echo ucfirst($subject->name);?></td>
        </tr>
                
            
    </table>
    <br />
	<table border="1">
    <tr>
    <th align="center"><font size="+2"><b><?php echo Yii::t('app','Admission Number');?></b></font></th>
    <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
    <th align="center"><font size="+2"><b><?php echo Yii::t('app','Student Name');?></b></font></th>
    <?php } ?>
    <?php if(in_array('date_of_birth', $student_visible_fields)){?>
    <th align="center"><font size="+2"><b><?php echo Yii::t('app','Date Of Birth');?></b></font></th>
    <?php } ?>
<?php foreach($exam_groups as $exam_group)
		{ ?>    
    <th style="padding:0px;">
			<table class="table_tx_br_none" width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr> 
    <th style="border-bottom:1px solid #ccc;"align="center"><font size="+2"><b><?php echo $exam_group->name;?></b></font></th>
   </tr>
   <tr> 
    <th align="center"><font size="+2"><b><?php echo Yii::t('app','Score');?></b></font></th>
  </tr>
</table>
</th>    
   <?php } ?>
    </tr>
<?php foreach($students as $student)
		{ ?> 
	<tr>
		<th><?php echo $student->admission_no;?></th>
        <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
		<th><?php echo $student->studentFullName("forStudentProfile"); ?></th>
        <?php } ?>
		<?php if(in_array('date_of_birth', $student_visible_fields)){?>
        <th><?php 
								$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
								if($settings!=NULL)
								{	
									$date1=date($settings->displaydate,strtotime($student->date_of_birth));
									echo $date1;
		
								}
								else
								echo $student->date_of_birth;?></th>
                               <?php } ?>
                                <?php 
								$status =0;
               foreach($exam_groups as $exam_group)
			  {
				 if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2){ 
						$exam = CbscExams::model()->findByAttributes(array('subject_id'=>$_REQUEST['subjectid'],'exam_group_id'=>$exam_group->id));
						$exam_score = CbscExamScores::model()->findByAttributes(array('student_id'=>$student->id,'exam_id'=>$exam->id));
				 }
				 else{
					 	$exam = Exams::model()->findByAttributes(array('subject_id'=>$_REQUEST['subjectid'],'exam_group_id'=>$exam_group->id));
						$exam_score = ExamScores::model()->findByAttributes(array('student_id'=>$student->id,'exam_id'=>$exam->id));
				 }
				$min =$exam->minimum_marks;
					
					if(!($exam_score))
					{?>
						<th><?php echo '-';?></th>
			  <?php }
					else
					{?>
						<th><?php 
						if($exam_score->marks<$min){
							$status = 1;
						}
						echo $exam_score->marks.'('.ExamScores::model()->getDefaultgradinglevel($_REQUEST['batchid'],$exam_score->marks).')';?></th>
					<?php
					}
			  }?> 
	</tr>
     <?php } ?>  
     </table>
