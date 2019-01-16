<style>
.attendance_table{
	margin:30px 0px;
	font-size:8px;
	text-align:center;
	width:100%;
	border-collapse:collapse;


}
.attendance_table td{
	padding-top:10px; 
	padding-bottom:10px;
	border:1px  solid #C5CED9;
	width:auto;
	font-size:13px;
	
}

.attendance_table th{
	font-size:13px;
	padding:10px;
	border:1px  solid #C5CED9;
}
.listbxtop_hdng first{
	text-align:left; 
	font-size:14px; 
	padding-left:10px;
}
hr{ border-bottom:1px solid #C5CED9; border-top:0px solid #fff;}

</style>

<?php 
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
?>
	<!-- Header -->
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="first" width="100">
                           <?php $logo=Logo::model()->findAll();?>
                            <?php
                            if($logo!=NULL)
                            { 
                                echo '<img src="uploadedfiles/school_logo/'.$logo[0]->photo_file_name.'" alt="'.$logo[0]->photo_file_name.'" class="imgbrder" height="100" />';
                            }
                            ?>
                </td>
                <td  valign="middle">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="listbxtop_hdng first" >
                                <?php $college=Configurations::model()->findAll(); ?>
                                <?php echo $college[0]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" >
                                <?php echo $college[1]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first">
                                <?php echo Yii::t('app','Phone: ').$college[2]->config_value; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    <hr />
	<!-- End Header -->
<br />

	<?php
    if(isset($_REQUEST['id']))
    {  ?>
   
    <div align="center" style="text-align:center; display:block;">
		<?php  if(isset($_REQUEST['mode']) and $_REQUEST['mode']==1){
					echo Yii::t('app','OVERALL STUDENT SUBJECT WISE ATTENDANCE REPORT'); 
			}
			elseif(isset($_REQUEST['mode']) and $_REQUEST['mode']==2){
					echo Yii::t('app','YEARLY STUDENT SUBJECT WISE ATTENDANCE REPORT').' - '.($_REQUEST['year']); 
			}
			elseif(isset($_REQUEST['mode']) and $_REQUEST['mode']==3){
					echo Yii::t('app','MONTHLY STUDENT SUBJECT WISE ATTENDANCE REPORT').' - '.($_REQUEST['month']); 
			}?>
      </div>
     <br />
    <?php 
	$students = Students::model()->findAll("batch_id=:x AND is_active=:y AND is_deleted=:z", array(':x'=>$_REQUEST['id'],':y'=>1,':z'=>0));
	$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id'],'is_active'=>1,'is_deleted'=>0));
	$course = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
	?>
    <!-- Batch details -->
    <table width="685" style="font-size:13px; background:#DCE6F1; padding:10px 10px;border:#C5CED9 1px solid;">
        	<tr>
            	<td width="130" height="30"><?php echo Yii::t('app','Course');?></td>
                <td width="10">:</td>
                <td width="212"><?php echo ucfirst($course->course_name);?></td>
                
                <td width="100"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?>
                 <?php
						if($batch->semester_id!=NULL){
							 $sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);
							if($sem_enabled==1)
							{
								$semester=Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); 
								echo ' / '.Yii::t('app','Semester'); ?></strong>
						  <?php  }
						}
						?>
                </td>
                <td width="10">:</td>
                <td width="100"><?php echo ucfirst($batch->name);?>
                <?php
					if($batch->semester_id!=NULL){
						 $sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);
						if($sem_enabled==1)
						{
							$semester=Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); 
							echo ' / '.$semester->name; ?></strong>
					  <?php  }
					}
					?>
                </td>
            </tr>
            <tr>
            	<td><?php echo Yii::t('app','Total Students');?></td>
                <td>:</td>
                <td><?php echo count($students);?> </td>
			</tr>                
                
        </table>
    <!-- END Batch details -->
    
    <!--Attendance Table -->
         <table width="100%" cellspacing="0" cellpadding="0" class="attendance_table">
            <tr class="tablebx_topbg" style="background-color:#DCE6F1;">
               <td width="50"><?php echo Yii::t('app','Sl No');?></td>
               <td width="100"><?php echo Yii::t('app','Adm No');?></td>
               <td width="120"><?php echo Yii::t('app','Admission Date');?></td>
               <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
               <td style="width:240px;"><?php echo Yii::t('app','Name');?></td>
               <?php }?>
               <td style="width:110px;"><?php echo Yii::t('app','Batch');?></td>
                <td style="width:110px;"><?php echo Yii::t('app','Subject');?></td>
               <td style="width:110px;"><?php echo Yii::t('app','No Of Classes');?></td>
               <td width="100"><?php echo Yii::t('app','Leaves');?></td>
            </tr>
             <?php
				$criteria = new CDbCriteria();
				$criteria->condition = 'batch_id = :batch_id';
				$criteria->group = 'subject_id';
				$criteria->params = array('batch_id' => $_REQUEST['id']);
				$times = TimetableEntries::model()->findAll($criteria);
									
				$overall_sl = 1;
				foreach($times as $time) // Displaying each employee row.
				{
					$subject = Subjects::model()->findByAttributes(array('id'=>$time->subject_id));
					$batchstudents = BatchStudents::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['id'], 'status'=>1));
						foreach($batchstudents as $batchstudent){ // Displaying each students row.
							$student = Students::model()->findByAttributes(array('id'=>$batchstudent->student_id));
							$flag	=	0;
							if($time->is_elective == 2){
								$stu_elective	=	StudentElectives::model()->findByAttributes(array('student_id'=>$batchstudent->student_id,'elective_id'=>$time->subject_id));
								if(isset($stu_elective) and $stu_elective!=NULL)
									$flag	=	1;
								else
									$flag	=	0;
							}else
							{
								$flag	=	1;											
							}
							if($flag ==	1)
							{
							
				?>
                                <tr>
                                    <td style="padding-top:10px; padding-bottom:10px;"><?php echo $overall_sl; $overall_sl++;?></td>
                                    <td><?php echo $student->admission_no; ?></td>
                                     <td>
                                        <?php 
                                        if($student->admission_date!=NULL)
                                        {
                                            $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                                            if($settings!=NULL)
                                            {	
                                                $student->admission_date = date($settings->displaydate,strtotime($student->admission_date));
                                            }
                                            echo $student->admission_date; 
                                        }
                                        else
                                        {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                    <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                    <td><?php echo $student->studentFullName("forStudentProfile");?></td>
                                    <?php } ?>
                                    <td><?php echo ucfirst($batch->name);?>
                                    </td>
                                    <td>
                                        <?php
                                            if($time->is_elective == 2){
                                                $elective	= Electives::model()->findByAttributes(array('id'=>$time->subject_id));
                                                if($elective){
                                                    echo ucfirst($elective->name);
                                                }
                                                else{
                                                    echo '-';
                                                }
                                            }
                                            else{
                                                $subject=Subjects::model()->findByAttributes(array('id'=>$time->subject_id));
                                                if($subject!=NULL){	
                                                    echo ucfirst($subject->name); 															
                                                }
                                                else{
                                                    echo '-';
                                                }
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $student_details=Students::model()->findByAttributes(array('id'=>$student->id)); 
                                            $batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));	
                                                                            
                                            if($student_details->admission_date>=$batch->start_date)
                                            { 
                                                $batch_start  = date('Y-m-d',strtotime($student_details->admission_date));
                                            
                                            }
                                            else
                                            {
                                                $batch_start  = date('Y-m-d',strtotime($batch->start_date));
                                            }
                                        
                                            $batch_end    = date('Y-m-d');
                                                        $total_entry_count	= 0;
                                                        for($w=1;$w<=7;$w++){
                                                            $entries = TimetableEntries::model()->findAllByAttributes(array('subject_id'=>$time->subject_id, 'weekday_id'=>$w));
                                                            if(count($entries)>0){														
                                                                $entry_count	= count($entries);														
                                                                $weekday 		= $w-1;
                                                                if($weekday==0) $weekday=7;
                                                                
                                                                if($_REQUEST['mode']==1){      //over all
                                                                    $start_date = $batch_start;
                                                                    $end_date 	= $batch_end;
                                                                }
                                                                
                                                                elseif($_REQUEST['mode']==2){  //yearly
                                                                    $year = $_REQUEST['year'];
                                                                            
                                                                    $yr_start = date('Y-m-d', mktime(0, 0, 0, 1, 1,  $year ));
                                                                    $yr_end = date('Y-m-d', mktime(0, 0, 0, 12, 31,  $year ));
                                                                    
                                                                    
                                                                    if($yr_start < $batch_start){
                                                                        $start_date = $batch_start;
                                                                    }
                                                                    else{
                                                                        $start_date = $yr_start;
                                                                    }
                                                                    if($yr_end > $batch_end){
                                                                        $end_date = $batch_end;
                                                                    }
                                                                    else{
                                                                        $end_date = $yr_end;
                                                                    }
                                                                }
                                                                elseif($_REQUEST['mode']==3){  //monthly
                                                                            $timestamp    = strtotime($_REQUEST['month']);
                                                                            
                                                                            $month_start = date('Y-m-01', $timestamp);
                                                                            $month_end  = date('Y-m-t', $timestamp); 
                                                                            
                                                                            
                                                                            if($month_start < $batch_start){
                                                                                $start_date = $batch_start;
                                                                            }
                                                                            else{
                                                                                $start_date = $month_start;
                                                                            }
                                                                            if($month_end > $batch_end){
                                                                                $end_date = $batch_end;
                                                                            }
                                                                            else{
                                                                                $end_date = $month_end;
                                                                            }
                                                                }
                                                                $daycount	= 0;
                                                                $start 		= new DateTime($start_date);
                                                                $end   		= new DateTime($end_date);
                                                                $end->modify('+1 day');
                                                                $interval 	= DateInterval::createFromDateString('1 day');
                                                                $period 	= new DatePeriod($start, $interval, $end);
                                                                foreach ($period as $dt){
																	if ($dt->format('N') == $weekday){
																		$day	=	$dt->format('Y-m-d');
																		
																		$is_holiday		= StudentAttentance::model()->isHoliday($day);	
																		$ischeck = Configurations::model()->findByPk(43);														
																		if($ischeck->config_value != 1)
																		{
																			if(!$is_holiday){
																				
																				$daycount++;
																			}
																		}else{
																			$daycount++;
																		}
																	}
																}
                                                                
                                                                $total_entry_count	+= ($daycount * $entry_count);														
                                                            }
                                                        }
                                                        echo $total_entry_count;?> 
                                    </td>
                                     <td>
                                        <?php
                                            if($_REQUEST['mode']==1){
                                                
                                                 $subwise = StudentSubjectwiseAttentance::model()->findAllByAttributes(array('student_id'=>$student->id, 'subject_id'=>$time->subject_id));
                                                    echo count($subwise);
                                            }
                                            elseif($_REQUEST['mode']==2){
                                                $year = $_REQUEST['year'];
                                                
                                                 $subwise = StudentSubjectwiseAttentance::model()->findAllByAttributes(array('student_id'=>$student->id, 'subject_id'=>$time->subject_id));
                                                 $leaves = 0;
                                                 foreach($subwise as $subwise_1)
                                                    {
                                                         $leave_year = date('Y', strtotime($subwise_1->date));
                                                        if($leave_year == $year)
                                                        {
                                                            $leaves++; 
                                                        }
                                                        
                                                    }
                                                    echo $leaves;
                                                
                                            }
                                            elseif($_REQUEST['mode']==3){
                                                $requiredmonth = date('m',strtotime($_REQUEST['month']));
                                                $requiredyear = date('Y',strtotime($_REQUEST['month']));
                                                
                                                 $subwise = StudentSubjectwiseAttentance::model()->findAllByAttributes(array('subject_id'=>$time->subject_id, 'student_id'=>$student->id)); 
                                                  $leaves = 0;
                                                    foreach($subwise as $subwise_1){
                                                      $leave_year = date('Y', strtotime($subwise_1->date));
                                                      $leave_month = date('m',strtotime($subwise_1->date));
                                                       
                                                    if($leave_year == $requiredyear and $leave_month == $requiredmonth){
                                                         
                                                        $leaves++; 
                                                    }
                                                 }
                                                 echo $leaves;
                                            }
                                        ?>
                                    </td>
                                </tr>
				<?php
					}
				}?>
                
   		<?php
   		}
		?>
         </table>
         <?php
    }
	else
	{	
		echo Yii::t('app','No data available!');
    }
?>
