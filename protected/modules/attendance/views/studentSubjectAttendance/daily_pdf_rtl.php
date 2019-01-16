<style type="text/css">
.timetable{
	 border-collapse:collapse;	
}
.timetable td{
	padding:10px;
	border:1px solid #C5CED9 ;
	width:auto;
	font-size:10px;
	text-align:center;
}
hr{ 
	border-bottom:1px solid #C5CED9; 
	border-top:0px solid #fff;
}
</style>
<?php
 	$settings				= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
	if($settings != NULL){
		$timeformat		= $settings->timeformat;
		$displayformat	= $settings->displaydate;
		$pickerformat	= $settings->dateformat;
	}
	else{
		$timeformat		= 'h:i a';
		$displayformat	= 'M d Y';
		$pickerformat 	= 'dd-mm-yy';
	}
 
 	$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
	$yr = AcademicYears::model()->findByAttributes(array('id'=>$current_academic_yr->config_value));
		
	$date					= (isset($_REQUEST['date']))?date('Y-m-d', strtotime($_REQUEST['date'])):date("Y-m-d");
	$day 					= date('w', strtotime($date));
	$prev_day				= date('Y-m-d', strtotime('-1 days', strtotime($date)));
	$next_day				= date('Y-m-d', strtotime('+1 days', strtotime($date)));
	$this_date				= $date;
	
	$batch					= Batches::model()->findByPk($_REQUEST['batch']);
	$semester				= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
	$course 				= Courses::model()->findByAttributes(array('id'=>$batch->course_id)); 
	$sem_enabled			= Configurations::model()->isSemesterEnabledForCourse($course->id);
	
	$criteria				= new CDbCriteria;											
	$criteria->join			= "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`id`";											
	$criteria->condition	= "`t`.`is_active`=1 AND `t`.`is_deleted`=0 AND `bs`.`batch_id`=:batch_id AND `bs`.`status`=:status";
	$criteria->params		= array(":batch_id"=>$batch->id, ':status'=>1);
	$criteria->order		= "`t`.`first_name` ASC, `t`.`last_name` ASC";
	$students				= Students::model()->findAll($criteria);
	$bid					= $batch->id;
	

?>
<?php
if(isset($_REQUEST['batch']) and $_REQUEST['batch']!=NULL){
?>
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td class="first" width="100">
            <?php $logo=Logo::model()->findAll();?>
            <?php
                if($logo!=NULL){
                    echo '<img src="uploadedfiles/school_logo/'.$logo[0]->photo_file_name.'" alt="'.$logo[0]->photo_file_name.'" class="imgbrder" height="60" />';
                }
            ?>
            </td>
            <td valign="middle" >
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="listbxtop_hdng first"  style=" font-size:20px;" >
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
                            <?php echo 'Phone: '.$college[2]->config_value; ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
	<hr />
   
    <div align="center" style="display:block; text-align:center;">
		<h3>
			<?php echo Yii::t('app','Daily Subject Wise Attendance');?> 
			(<?php
			 if($sem_enabled==1 and $batch->semester_id!=NULL){  
				echo $batch->course123->course_name.' , '.$batch->name.' , '.$semester->name;
			 }
			 else{
				 echo $batch->course123->course_name.' , '.$batch->name;
			 }
		  ?>)
		</h3>
	</div>
 <?php
 	$month_1		=	date("M", strtotime($date));
	$month			=	Yii::t('app',$month_1);
	$year			=	date("Y", strtotime($date));
	$days			=	date("d", strtotime($date));	
	$display_date 	= $days.' '.$month.' '.$year;
 ?>   
 <div align="center" style="display:block; text-align:center;"><h5><?php echo $display_date;?></h5></div>
  <?php 											
	$weekday_attributes	= array(1=>'on_sunday',2=>'on_monday',3=>'on_tuesday',4=>'on_wednesday',5=>'on_thursday',6=>'on_friday',7=>'on_saturday');
	if(Configurations::model()->timetableFormat($bid) == 1){
		$criteria				= new CDbCriteria;
		$criteria->condition 	= "batch_id=:x";
		$criteria->params 		= array(':x'=>$bid);
		$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
		$timings 				= ClassTimings::model()->findAll($criteria);
	}
	else{
		$weekday_condition		= "`".$weekday_attributes[$day + 1]."`=:week_day_status";
		$criteria				= new CDbCriteria;
		$criteria->condition 	= "batch_id=:x AND ".$weekday_condition;
		$criteria->params 		= array(':x'=>$bid, ':week_day_status'=>1);
		$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
		$timings 				= ClassTimings::model()->findAll($criteria);														
	}
	$count_timing 			= count($timings);    if(isset($timings) and $timings!=NULL){
		if($date > date('Y-m-d')){
			echo '<div class="not-found-box"><i class="os_no_found">'.Yii::t("app", "Cannot mark attendance for this date").'</i></div>';
		}
		else{
		$is_holiday		= StudentAttentance::model()->isHoliday($date);
		if($is_holiday){
			echo Yii::t("app", "Selected day is a Holiday");
		}
		else{
			if($batch->start_date <= $date and $date <= $batch->end_date){
			$weekday		= Weekdays::model()->find("batch_id=:x AND weekday=:weekday", array(':x'=>$bid, ':weekday'=>($day + 1)));										
			if($weekday==NULL)
				$weekday	= Weekdays::model()->find("batch_id IS NULL AND weekday=:weekday", array(':weekday'=>($day + 1)));
				
			if($weekday==NULL){
				echo '<i>'.Yii::t('app','Timetable is not set for this date').'</i>';
			}
			else{
				?>
				<table  align="left" width="100%" id="table" cellspacing="0" cellpadding="0" class="timetable" >
					<tr style="background:#DCE6F1">
                   <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
                            <td  style="background:#DCE6F1;"><?php echo Yii::t('app', 'Roll No'); ?></td>
                          <?php } ?>
						<td  style="background:#DCE6F1;"><?php echo Yii::t('app', 'Name'); ?></td>
						<?php
							foreach($timings as $timing_1){
								if($settings != NULL){
									//traslate AM and PM 	
									$t1 = date('h:i', strtotime($timing_1->start_time));	
									$t2 = date('A', strtotime($timing_1->start_time));
									
									$t3	= date('h:i', strtotime($timing_1->end_time));	
									$t4	= date('A', strtotime($timing_1->end_time));	
									//end 
								}
								echo '<td style="font-size:11px;background:#E1EAEF;word-break:break-all;">';
								echo '( '.$t1.' '.Yii::t("app",$t2).' - '.$t3.' '.Yii::t("app",$t4).' )';
								?>
								<div class="subject">	
								<?php
								$set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$bid,'weekday_id'=>$weekday['weekday'],'class_timing_id'=>$timing_1->id));
								if($set->is_elective==0){
									$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));																	
									if($time_sub!=NULL){
										echo $time_sub->name;                                                                                		$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
										if($time_emp!=NULL){
											$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));
											
											if($is_substitute and in_array($is_substitute->date_leave,$date_between)){
												$employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
												echo '<div class="batch_name">'.ucfirst($employee->first_name).' '.ucfirst($employee->middle_name).' '.ucfirst($employee->last_name).'</div>';
											}
											else{
												if($time_sub!=NULL){
													echo '<div class="batch_name">'.ucfirst($time_emp->first_name).' '.ucfirst($time_emp->middle_name).' '.ucfirst($time_emp->last_name).'</div>';
												}
											}
										}
									}
									else{
										echo '';
									}
								}
								else{
									$elec_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
									$electname = ElectiveGroups::model()->findByAttributes(array('id'=>$elec_sub->elective_group_id,'batch_id'=>$bid));
									if($electname!=NULL){
										echo $electname->name;
									}
									$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
									if($time_emp!=NULL){
										$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));
										
										if($is_substitute and in_array($is_substitute->date_leave,$date_between)){
											$employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
											//echo '<div class="employee">'.ucfirst($employee->first_name).' '.ucfirst($employee->middle_name).' '.ucfirst($employee->last_name).'</div>';
										}
										else{
											if($electname!=NULL){
												//echo '<div class="employee">'.ucfirst($time_emp->first_name).' '.ucfirst($time_emp->middle_name).' '.ucfirst($time_emp->last_name).'</div>';
											}
										}
									}                                                                                
								}
								?>                            
								</div>
								<?php
								echo '</td>';
							}
						?> 
					</tr>            
			   
					<?php
					foreach($students as $student){
						$batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
					?>
						<tr>
                        	  <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
                                        <td><h3><?php if($batch_student!=NULL and $batch_student->roll_no!=0){
                                                        echo $batch_student->roll_no;
                                                    }
                                                    else{
                                                        echo '-';
                                                    }?>
                                            </h3>
                                        </td>
                                      <?php } ?> 
							<td>
								<h3><?php echo $student->studentFullName(); ?></h3>
							</td>              
                            <?php
							if($date < $student->admission_date){
							?>  
                            	<td class="td" colspan="<?php echo $count_timing;?>"><?php echo Yii::t('app', 'Student has not joined yet');?></td>
                            <?php
							}
							else{
								for($i=0;$i<$count_timing;$i++){							
									$set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$bid,'weekday_id'=>$weekday['weekday'],'class_timing_id'=>$timings[$i]['id'])); ?>	
									<td class="td"> 	
									<?php
									if($set->is_elective == 2){
										$elective			=	Electives::model()->findByAttributes(array('batch_id'=>$bid, 'id'=>$set->subject_id));  
										$student_elective	=	StudentElectives::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$bid, 'elective_group_id'=>$elective->elective_group_id)); 
										if($student_elective==NULL){
											$visible=1;
										}else{
											$visible=0;
										}															
									}else{
										$visible=0;
									} 
									if($date >= $student->admission_date and $date <= date("Y-m-d")){
										if($set == NULL  or $visible==1){
											
												$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timings[$i]['id'],'is_break'=>1));
												if($is_break!=NULL){	
													echo Yii::t('app', 'Break');
												}
												else{
													echo Yii::t('app', 'Not Assigned');
												}
										}
										else{
											$subjectwise =  StudentSubjectwiseAttentance::model()->findByAttributes(array('student_id'=>$student->id, 'timetable_id'=>$set->id, 'weekday_id'=>$set->weekday_id, 'date'=>$date));										
											if($subjectwise == NULL){
												if($batch->start_date <= $date and $date <= $batch->end_date ){											
													echo '<span style="color:#070;">'.Yii::t('app','Present').'</span>';
												}
											}
											else{
												$leave   = StudentLeaveTypes::model()->findByAttributes(array('id'=>$subjectwise->leavetype_id));
												echo '<span style="color:#F00;">'.Yii::t('app','Absent');
												if($leave!=NULL)
													echo ' ('.$leave->name.')';
												echo '</span>';
											}
										}
									}
								}
							}
						?>
						</tr>
					<?php			
                    }
				 ?>
			  </table>
			<?php
			}
		}
		else{
			echo '<i>'.Yii::t('app','No class on this date').'</i>';
		}
	}
}
}
else{
		echo '<div class="not-foundarea">';
		echo Yii::t('app', 'No Class Timings');
		echo '</div>';
}    

}
?>