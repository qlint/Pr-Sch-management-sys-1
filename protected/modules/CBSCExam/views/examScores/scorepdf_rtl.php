<?php
$this->breadcrumbs=array(
	Yii::t('app','Student Attentances')=>array('/courses'),
	Yii::t('app','Attendance'),
);
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
?>
<style>
/*.score_table{
	border-top:1px #CCC solid;
	margin:30px 0px;
	font-size:15px;
	border-right:1px #CCC solid;
	
}
.score_table td,th{
	border-left:1px #CCC solid;
	padding:5px 6px;
	border-bottom:1px #CCC solid;
	width: 150px;
	text-align:center;
}*/

table.score_table{
	margin:30px 0px;
	font-size:15px;
	border-collapse:collapse
}

table.score_table tr td,th{
	border:1px  solid #C5CED9;
	padding:5px 7px;
	
}

.score_table th { background:DCE6F1;
padding:10px 7px}

.heading{
	text-align:center;
	font-size:24px;
	font-weight:bold;
}
hr{ border-bottom:1px solid #C5CED9; border-top:0px solid #fff;}
</style>


<?php

  if(isset($_REQUEST['id']) && isset($_REQUEST['examid']))
  {
	?>
     <!-- Header -->
  
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="first" width="100">
                           <?php $logo=Logo::model()->findAll();?>
                            <?php
                            if($logo!=NULL)
                            {
                                //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                echo '<img src="uploadedfiles/school_logo/'.$logo[0]->photo_file_name.'" alt="'.$logo[0]->photo_file_name.'" class="imgbrder" height="100" />';
                            }
                            ?>
                </td>
                <td  valign="middle"  >
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:22px; padding-left:10px;">
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
                                <?php echo Yii::t('app','Phone: ').$college[2]->config_value; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    <hr />
    <br />
    <!-- End Header -->
  
    <div align="center" style="display:block; text-align:center !important;"><?php echo Yii::t('app','EXAM SCORES');?></div><br />
     <?php 
		$students=Students::model()->findAll("batch_id=:x and is_active=:y and is_deleted=:z", array(':x'=>$_REQUEST['id'],':y'=>1,':z'=>0)); 
		$scores = CbscExamScores::model()->findAllByAttributes(array('exam_id'=>$_REQUEST['examid']));	
		$exam = CbscExams::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
		$exam_group = CbscExamGroups::model()->findByAttributes(array('id'=>$exam->exam_group_id));
		$sub_name = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
		$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$course = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
		
		
	?>
    <!-- Course details -->
   
       <table style="font-size:14px;background:#DCE6F1;padding:10px 10px;border:#C5CED9 1px;">
           
            <tr>
                <td style="width:150px;"><?php echo Yii::t('app','Course');?></td>
                <td style="width:10px;">:</td>
                <td style="width:350px;">
					<?php 
					if($course->course_name!=NULL)
						echo ucfirst($course->course_name);
					else
						echo '-';
					?>
				</td>
                
                <td width="150"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></td>
                <td width="10">:</td>
                <td>
					<?php 
					if($batch->name!=NULL)
						echo ucfirst($batch->name);
					else
						echo '-';
					?>
				</td>
            
            </tr>
            <tr>
            	<td><?php echo Yii::t('app','Total Students');?></td>
                <td>:</td>
                <td >
					<?php 
					if($students!=NULL)
						echo count($students);
					else
						echo '-';
					?>
				</td>
            	<td><?php echo Yii::t('app','Examination');?></td>
                <td>:</td>
                <td width="350">
					<?php 
					if($exam_group->name!=NULL)
						echo ucfirst($exam_group->name);
					else
						echo '-';
					?>
				</td>
            </tr>
            
            <tr>
            	<td><?php echo Yii::t('app','Subject');?></td>
                <td>:</td>
                <td>
					<?php 
					if($sub_name->name!=NULL)
						echo $sub_name->name;
					else
						echo '-';
					?>
				</td>
            	<td><?php echo Yii::t('app','Date');?></td>
                <td>:</td>
                <td>
					<?php 
					if($exam->start_time!=NULL)
					{
						$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
						if($settings!=NULL)
						{	
							$exam->start_time = date($settings->displaydate,strtotime($exam->start_time));
							echo $exam->start_time;
						}
						else
						{
							echo $exam->start_time;
						}
					}
					else
					{
						echo '-';
					}
					?>
				</td>
            </tr>
           
        </table>
 
    <!-- END Course details -->
	 <!-- Score Table -->

    
    	<table style="font-size:13px;" class="score_table"  width="100%" cellspacing="0" >
        	<tr style="background:#DCE6F1; text-align:center;">
           <?php if(Configurations::model()->rollnoSettingsMode() != 2){ ?>
            	<td style="width:40px;"><?php echo Yii::t('app','Roll No.');?></td>
           <?php } ?>
                <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                <td style="width:250px;"><?php echo Yii::t('app','Name');?></td>
                <?php }?><?php				
				$exm = CbscExams::model()->findByAttributes(array('id'=>$_REQUEST['examid'])); 
				if($exm!=NULL)
				{
					$sub = Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
				}
				if($sub->split_subject!=NULL and $sub->split_subject == 1){
					$subject_cps	=	SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$sub->id));
					if($subject_cps !=NULL){ 
						foreach($subject_cps as $subject_cp){
							 ?><td style="width:150px;"><?php echo ucfirst($subject_cp->split_name);?></td>
						 <?php
						}
					}
                }
                ?>
                <td style="width:150px;"><?php echo Yii::t('app','Marks');?></td>
              
                <td style="width:250px;"><?php echo Yii::t('app','Remarks');?></td>
               
        	</tr>
         
            <?php 
			$i = 1;
			foreach($scores as $score)
			 {
			 $student  = Students::model()->findByAttributes(array('id'=>$score->student_id));
			 $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
			 echo "<tr>";
			 if(Configurations::model()->rollnoSettingsMode() != 2){ 
            	 echo "<td width='40'>".$batch_student->roll_no."</td>";
            } 
			 	
				 if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){
				 	echo "<td width='250'>".$student->studentFullName("forStudentProfile")."</td>";
                 }	
				 if($sub->split_subject == 1){		
					 $subject_marks	=	CbscexamScoresSplit::model()->findAllByAttributes(array('exam_scores_id'=>$score->id));
					 foreach($subject_marks as $subject_mark)
					 {
						echo "<td width='100'>".$subject_mark->mark."</td>";
					 }	
				 }
				 echo "<td width='100'>".$score->marks."</td>";
				 echo "<td width='250'>".$score->remarks."</td>";				 
			 echo "</tr>";
			 $i++;
			}
	 		?>
         
        </table>
	
    
     <!-- END Score Table -->


<?php  }?>
