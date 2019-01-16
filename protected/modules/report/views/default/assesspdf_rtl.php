<style>
table.attendance_table{ border-collapse:collapse}

.attendance_table{
	margin:30px 0px;
	font-size:8px;
	text-align:center;
	width:auto;
	/*max-width:600px;*/
	border-top:1px #CCC solid;
	border-right:1px solid #CCC;
}
.attendance_table td{
	border:1px solid #CCC;
	padding-top:10px; 
	padding-bottom:10px;
	width:auto;
	font-size:13px;
	
}

.attendance_table th{
	font-size:14px;
	padding:10px;
	border-left:1px #CCC solid;
	border-bottom:1px #CCC solid;
}

hr{ border-bottom:1px solid #C5CED9; border-top:0px solid #fff;}
</style>
<?php
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
if(isset($_REQUEST['examid']))
{ 
?>

	<!-- Header -->

        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="first" width="100">
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
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:22px; padding-left:10px;">
                                <?php $college=Configurations::model()->findAll(); ?>
                                <?php echo @$college[0]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo @$college[1]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo Yii::t('app','Phone').': '.@$college[2]->config_value; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
<hr />
<br />
	<!-- End Header -->

	<?php
    if(isset($_REQUEST['id']))
    {  
   ?>
    <div align="center" style="text-align:center; font-size:18px; display:block;"><?php echo Yii::t('app','BATCH ASSESSMENT REPORT'); ?></div><br />
    <?php  $students	= 	BatchStudents::model()->BatchStudent($_REQUEST['id']);       ?>
    <!-- Batch details -->

        <table style="font-size:13px; background:#DCE6F1; padding:10px 10px;border:#C5CED9 1px solid;">
           
            <tr>
            	<?php 
				$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
				$course = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
				?>
                <td style="width:150px;"><?php echo Yii::t('app','Course'); ?></td>
                <td style="width:10px;">:</td>
                <td style="width:400px;">
					<?php 
					if($course->course_name!=NULL)
						echo ucfirst($course->course_name);
					else
						echo '-';
					?>
				</td>
                
                <td width="150"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?></td>
                <td>:</td>
                <td width="310">
					<?php 
					if($batch->name!=NULL)
						echo ucfirst($batch->name);
					else
						echo '-';
					?>
				</td>
            
            </tr>
            <tr>
            	<td><?php echo Yii::t('app','Total Students'); ?></td>
                <td>:</td>
                <td>
					<?php 
					if($students!=NULL)
						echo count($students);
					else
						echo '-';
					?>
				</td>
            	<td><?php echo Yii::t('app','Examination'); ?></td>
                <td>:</td>
                <td><?php echo ucfirst($model->name); ?></td>
            </tr>
           
        </table>

    <!-- END Batch details -->
   
   <?php
    }
    ?>
    
    <!-- Batch Assessment Report -->

    	<!-- Assessment Table -->
    	<table width="100%" cellspacing="0" cellpadding="0" class="attendance_table">
        	<!-- Table Headers -->
        	<tr class="tablebx_topbg" style="background-color:#DCE6F1;">
                <?php if(Configurations::model()->rollnoSettingsMode() != 1){?>
                <td style="width:40px;"><?php echo Yii::t('app','Admission Number');?></td>
                <?php } ?>
                <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
                <td style="width:40px;"><?php echo Yii::t('app','Roll No');?></td>
                <?php } ?>
                <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                <td style="width:80px;"><?php echo Yii::t('app','Name');?></td>
                <?php } ?>
                <?php
				$criteria = new CDbCriteria;		
				$criteria->condition='exam_group_id LIKE :match';
                $criteria->params = array(':match' => $_REQUEST['examid'].'%');
				$exams = Exams::model()->findAll($criteria);
				$examgrp = ExamGroups::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
				
				
				foreach($exams as $exam) // Creating subject column(s)
				{
                	$subject=Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
					
				?>
                	<td style="width:80px;"><?php
							if($subject->elective_group_id==0){
								echo ($subject->name!=NULL)? ucfirst($subject->name):'-';
							}
							else{	 
								$electives 	= StudentElectives::model()->findByAttributes(array('student_id'=>$student->id, 'elective_group_id'=>$subject->elective_group_id));
								$elective	= Electives::model()->findByAttributes(array('id'=>$electives->elective_id));
								echo ($subject->name!=NULL)? ucfirst($subject->name):'-';
							}
						?>
                    </td>
                <?php
				}
				?>
            </tr>
            <!-- End Table Headers -->
            <?php
			foreach($students as $student) // Creating row corresponding to each student.
			{
			?>
                <tr class=<?php echo $cls;?>>
                 <?php if(Configurations::model()->rollnoSettingsMode() != 1){?>
                	<td style="width:20px;padding-top:10px; padding-bottom:10px;">
                    	<?php echo $student->admission_no; ?>
                    </td>
                 <?php } ?>
			   <?php if(Configurations::model()->rollnoSettingsMode() != 2){
				   $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));?>
              		 <td style="width:20px;padding-top:10px; padding-bottom:10px;">
                    	<?php if($batch_student!=NULL and $batch_student->roll_no!=0){
								  				echo $batch_student->roll_no;
								  			}
											else{
												echo '-';
											}?>
                    </td>
                <?php } ?>
                    <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                	<td style="width:80px;padding-top:10px; padding-bottom:10px;word-break:break-all;">
						<?php echo $student->studentFullName("forStudentProfile"); ?>
					</td>
                    <?php } ?>
                    <?php
					
                    foreach($exams as $exam) // Creating subject column(s)
					{
					$score = ExamScores::model()->findByAttributes(array('student_id'=>$student->id,'exam_id'=>$exam->id));
					
					?>
                    
                    <td style="width:80px;padding-top:10px; padding-bottom:10px; word-break:break-all;">
                    <?php
					if($score->marks!=NULL or $score->remarks!=NULL)
					{
					?>
                    	<!-- Mark and Remarks Column -->
                    	<table align="center" width="60%" style="border:none;">
                        	<?php
							if($examgrp->exam_type=='Marks' || $examgrp->exam_type=='Marks And Grades')
					         {
							 ?>
                            <tr>
                            	<td style="border:none;<?php if($score->is_failed == 1){?>color:#F00;<?php }?>">
                                	<?php 
									if($score->marks!=NULL)
										echo $score->marks;
									else
										echo '-';
									?>
                                </td>
                            </tr>
                           <?php
							 }
							 ?>
                             <?php
							if($examgrp->exam_type=='Grades' || $examgrp->exam_type=='Marks And Grades')
					         {
							 ?>
                            <tr>
                            	<td style="border:none;<?php if($score->is_failed == 1){?>color:#F00;<?php }?>">
                                	<?php 
									$grade = GradingLevels::model()->findByAttributes(array('id'=>$exam->grading_level_id)); 
									
									
									 if($grade->name!=NULL)
									 {
										echo $grade->name;
									 }
									 else //No grading levels for $exam
									 {
										$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>NULL));
										$i = count($grades);
										foreach($grades as $grade)
										{
											if($grade->min_score <= $score->marks)
											{
												echo $grade->name;
												break;
											}
											else
											{
												$i--;
												continue;
											}
										}
										if($i<=0){
											echo Yii::t('app','No Grades');
										        }
										
									  }
									?>
                                </td>
                            </tr>
                           <?php
							 }
							 ?>
                            <tr>
                            	<td style="border:none;<?php if($score->is_failed == 1){?>color:#F00;<?php }?>">
                                	<?php 
									if($score->remarks!=NULL)
										echo $score->remarks;
									else
										echo '-';
									?>
                                </td>
                            </tr>
                        </table>
                        <!-- End Mark and Remarks Column -->
                    <?php
					}
					else
					{
						echo '-';
					}
					?>
                    </td>
                    <?php
					}
					?>
				</tr>
			<?php 
			}
			?>
        	
        </table>
        <!-- End Assessment Table -->
   
    <!-- End Batch Assessment Report -->
    
<?php
}
else
{
	echo '<td align="center" colspan="5"><strong>'.Yii::t('app','No Data Available!').'</td>';
}
?>
