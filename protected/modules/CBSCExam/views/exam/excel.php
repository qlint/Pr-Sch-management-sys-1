<?php
$students = Students::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['batchid']));
$exam_groups = ExamGroups::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['batchid']));
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
?>
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
               foreach($exam_groups as $exam_group)
                  { 
                    $exam = Exams::model()->findByAttributes(array('subject_id'=>$_REQUEST['subjectid'],'exam_group_id'=>$exam_group->id));
					
						$exam_score = ExamScores::model()->findByAttributes(array('student_id'=>$student->id,'exam_id'=>$exam->id));
						if(!($exam_score))
						{?>
							<th><?php echo '-';?></th>
				  <?php }
						else
						{?>
							<th><?php echo $exam_score->marks;?></th>
						<?php
						}
				  }
						?>  
		
	</tr>
     <?php } ?>  
     </table>
