<style type="text/css">
table.table_tx tr td, th{ border:1px solid #ccc;
	padding:5px;
	font-size:12px;}
	
table{ border-collapse:collapse}

.head{ background-color:#DCE6F1}

.table_tx_br_none tr td{ border:0px solid}

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

</style>
<?php
$students = BatchStudents::model()->BatchStudent($_REQUEST['batchid']);
$batch_id = $_REQUEST['batchid'];
if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2){ // for cbsc format
	$exam_groups = CbscExamGroups::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['batchid']));
}
else{
	$exam_groups = ExamGroups::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['batchid']));
}
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
?>
<!-- Header -->
	
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="first " width="100">
                           <?php $filename=  Logo::model()->getLogo();
							if($filename!=NULL)
							{
                                //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                echo '<img src="uploadedfiles/school_logo/'.$filename[2].'" alt="'.$filename[2].'" class="imgbrder" height="100" />';
                            }
                            ?>
                </td>
                <td valign="middle" >
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:22px; width:300px;  padding-left:10px;">
                                <?php $college=Configurations::model()->findAll(); ?>
                                <?php echo $college[0]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo $college[1]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo Yii::t('app','Phone:')." ".$college[2]->config_value; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    <hr />
    <br />
    <!-- End Header -->
<div align="center" style="text-align:center; display:block;"><?php echo Yii::t('app','EXAM SCORES'); ?></div><br />

<?php 
	
	$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['batchid'],'is_active'=>1,'is_deleted'=>0));
	$course = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
	$subject = Subjects::model()->findByAttributes(array('id'=>$_REQUEST['subjectid']));
	$semester_enabled	= 	Configurations::model()->isSemesterEnabled(); 
	$sem_enabled		= 	Configurations::model()->isSemesterEnabledForCourse($course->id);
	$semester			= 	Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
	?>
    <!-- Batch details -->
    <table width="400" style="font-size:13px; background:#DCE6F1; padding:10px 10px;border:#C5CED9 1px solid;">
        	<tr>
			<?php if(in_array('batch_id', $student_visible_fields)){?>
            	<td  style="padding:0 10px;" width="60" height="30"><strong><?php echo Yii::t('app','Course');?></strong></td>
                <td width="10">:</td>
                <td width="260"><?php echo ucfirst($course->course_name);?></td>
                
                <td width="60"><strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></strong></td>
                <td width="10">:</td>
                <td width="260"><?php echo $batch->name;?></td>
				<?php if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id != NULL){ ?>
				<td width="60"><strong><?php echo Yii::t('app','Semester');?></strong></td>
                <td width="10">:</td>
                <td width="260"><?php echo ucfirst($semester->name);?></td>
				<?php } ?>
            <?php } ?>
                 <td width="60"><strong><?php echo Yii::t('app','Subject');?></strong></td>
                <td width="10">:</td>
                <td width="280"><?php echo $subject->name;?></td>
            </tr>
                    
                
        </table>
        <br />
        
<table class="table_tx" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr class="head">
		<th width="110" valign="middle"><?php echo Yii::t('app','Admission Number');?></th>
        <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
		<th width="80"><?php echo Yii::t('app','Student Name');?></th>
        <?php } ?>
        <?php if(in_array('date_of_birth', $student_visible_fields)){?>
		<th width="100"><?php echo Yii::t('app','Date Of Birth');?></th>
        <?php } ?>
         <?php foreach($exam_groups as $exam_group)
		{ ?>
		<th style="padding:0px;">
        <table class="table_tx_br_none" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td style="border-bottom:1px solid #ccc;"><?php echo $exam_group->name;?></td>
            </tr>
            <tr>
                <td><?php echo Yii::t('app','Score');?></td>
            </tr>
           
        </table>
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
				$status	=0;
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
				  }
			?>	
	</tr>
     <?php } ?>
	
</table>
