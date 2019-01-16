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
.attnd-holiday{
	color: #8be14f;
	font-size: 13px;
	font-weight: 600;
	padding-top:7px;	
}
</style>
 <?php 	$student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
 		$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
		$yr = AcademicYears::model()->findByAttributes(array('id'=>$current_academic_yr->config_value));
		
		$student = Students::model()->findByAttributes(array('id'=>$student->id));
		$batch 	= 	Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));
		$course	=	Courses::model()->findByAttributes(array('id'=>$batch->course_id));
		$semester = Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
		$sem_enabled = Configurations::model()->isSemesterEnabledForCourse($course->id);
		
		$date				= (isset($_REQUEST['date']) and $_REQUEST['date'] != NULL)?$_REQUEST['date']:date("Y-m-d");
		$day 				= date('w', strtotime($date));
		$week_start			= date('Y-m-d', strtotime('-'.$day.' days', strtotime($date)));
		$week_end 			= date('Y-m-d', strtotime('+'.(6-$day).' days', strtotime($date)));
		$prev_week_start	= date('Y-m-d', strtotime('-7 days', strtotime($week_start)));
		$next_week_start	= date('Y-m-d', strtotime('+1 days', strtotime($week_end)));
		$this_date			= $week_start;?>
<?php
if(isset($student->id) and $student->id!=NULL){
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
                        <td class="listbxtop_hdng first" style=" font-size:17px;" >
                            <?php $college=Configurations::model()->findAll(); ?>
                            <?php echo $college[0]->config_value; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="listbxtop_hdng first"style=" font-size:17px;"  >
                            <?php echo $college[1]->config_value; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="listbxtop_hdng first"style=" font-size:17px;" >
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
			<?php echo Yii::t('app','Subject Wise Attendance');?> - <?php echo $student->first_name.' '.$student->middle_name.' '.$student->last_name; ?>
			 (
			 	<?php
				 if($sem_enabled == 1 and $batch->semester_id!=NULL){
					echo ucfirst($course->course_name).' , '.ucfirst($batch->name).' , '.ucfirst($semester->name); 
				 }
				 else{ 
					 echo ucfirst($course->course_name).' , '.ucfirst($batch->name);
				 }
				?>
			 )
		</h3>
	</div>
    

  <?php   
    $times=Batches::model()->findAll("id=:x", array(':x'=>$batch->id));
    $weekdays=Weekdays::model()->findAll("batch_id=:x", array(':x'=>$batch->id));
    if(count($weekdays)==0)
    	$weekdays=Weekdays::model()->findAll("batch_id IS NULL");
    $criteria=new CDbCriteria;
    $criteria->condition = "batch_id=:x";
    $criteria->params = array(':x'=>$batch->id);
    $criteria->order = "STR_TO_DATE(start_time, '%h:%i %p')";    
    $timings = ClassTimings::model()->findAll($criteria);
    $count_timing = count($timings);
	$timetable = TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$batch->id)); 
    if(isset($timings) and $timings!=NULL){
		if(count($timetable)>0){?>
         <div align="center" style="display:block; text-align:center;"><h5><?php echo date("M d", strtotime($week_start))." - ".date("M d", strtotime($week_end));?></h5></div>
        <?php
		$sun = Yii::t('app','SUN');
		$mon = Yii::t('app','MON');
		$tue = Yii::t('app','TUE');
		$wed = Yii::t('app','WED');
		$thu = Yii::t('app','THU');
		$fri = Yii::t('app','FRI');
		$sat = Yii::t('app','SAT');
		$weekday_text = array($sun, $mon, $tue, $wed, $thu, $fri, $sat);
    	?>
    	<table  align="left" width="100%" id="table" cellspacing="0" cellpadding="0" class="timetable" >
                <tr style="background:#DCE6F1">
                    <td  style="background:#DCE6F1;">&nbsp;</td>
                    <?php
					foreach($timings as $timing_1){	
					$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));													
						 if($settings!=NULL){
							  $time1=date($settings->timeformat,strtotime($timing_1->start_time));
                              $time2=date($settings->timeformat,strtotime($timing_1->end_time));
							echo '<td style="font-size:11px;background:#E1EAEF;word-break:break-all;">'.$time1.' -<br> '.$time2.'</td>';
						}
					}
				?> 
            </tr>            
           
                <?php
				if($settings==NULL)
					$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
						$criteria				= new CDbCriteria;
						$criteria->condition 	= "batch_id=:x";
						$criteria->params 		= array(':x'=>$batch->id);
						$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
						$timings 				= ClassTimings::model()->findAll($criteria);
					
						$weekday_text = array('SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT');
						$weekday_count	= 0;			
							foreach($weekdays as $weekday){														
								if($weekday['weekday']!=0)
								{?>
									<tr>
										<td>
											<h3><?php echo Yii::t('app',$weekday_text[$weekday['weekday']-1]);?></h3>
											<p><?php echo date("d M Y", strtotime($this_date)); ?></p>
											<?php $weekday_count++; ?>
										</td>
										
						 <?php    for($i=0;$i<$count_timing;$i++)
									{ 
										$criteria				= new CDbCriteria;
										$criteria->join			= 'JOIN `class_timings` `t1` ON `t1`.`id` = `t`.`class_timing_id`';												
										$criteria->condition	= '`t`.`batch_id`=:batch_id AND `t`.`weekday_id`=:weekday_id AND `t`.`class_timing_id`=:class_timing_id';
										$criteria->params		= array(':batch_id'=>$batch->id, ':weekday_id'=>$weekday['weekday'], ':class_timing_id'=>$timings[$i]['id']);
										
										$set =  TimetableEntries::model()->find($criteria);
								?>	
									 <td class="td"> 
									 <?php
									 if($set == NULL){
										$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timings[$i]['id'],'is_break'=>1));
										if($is_break!=NULL)
										{	
											echo Yii::t('app','Break');
										}
									}      
									else
										{	
											$subjectwise =  StudentSubjectwiseAttentance::model()->findByAttributes(array('student_id'=>$student->id, 'timetable_id'=>$set->id, 'weekday_id'=>$set->weekday_id, 'date'=>$week_start)); 
											$is_holiday		= StudentAttentance::model()->isHoliday($week_start);
											if($is_holiday == NULL){
												if($subjectwise == NULL){
													if($batch->start_date <= $week_start and $week_start <= $batch->end_date ){
														if($week_start >= $student->admission_date and $week_start <= date("Y-m-d")){												
															echo '<span style="color:#077109; font-weight:600;">'.Yii::t('app',"Present").'</span><br/>';
														}
													}
												}
											}
											else{
												echo '<span  class="attnd-holiday">'.Yii::t('app','Holiday').'</span>';
											}
												if($subjectwise){
													$leave   = StudentLeaveTypes::model()->findByAttributes(array('id'=>$subjectwise->leavetype_id));
													echo '<span style="color:#F00;">'.Yii::t('app','Absent').' ('.$leave->name.')</span>';
													echo '<br>';
												}
														?>
												</div>
															<div  onclick="" style="position: relative; ">
																<div class="timtable-subjct-blk">
																	<div class="subject">	
																<?php
																if($set->is_elective==0)
																{
																	$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));																	
																	if($time_sub!=NULL)
																	{
																		echo $time_sub->name;
																		
																		$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
																	if($time_emp!=NULL)
																	{
																		$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));

																		
																		if($is_substitute and in_array($is_substitute->date_leave,$date_between))
																		{
																			$employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
																			echo '<div class="batch_name">'.ucfirst($employee->first_name).' '.ucfirst($employee->middle_name).' '.ucfirst($employee->last_name).'</div>';
																		}
																		else
																		{
																			if($time_sub!=NULL)
																			{
																				echo '<div class="batch_name">'.ucfirst($time_emp->first_name).' '.ucfirst($time_emp->middle_name).' '.ucfirst($time_emp->last_name).'</div>';
																			}
																		}
																	}
																	}
																	else
																	{
																		echo '';
																	}
																}
																else
																{
																	$elec_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
																	
																	
																	$electname = ElectiveGroups::model()->findByAttributes(array('id'=>$elec_sub->elective_group_id,'batch_id'=>$batch->id));
																	
																	if($electname!=NULL)
																	{
																		echo $electname->name;
																	}
																	$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
																	if($time_emp!=NULL)
																	{
																		$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));
																		
																		if($is_substitute and in_array($is_substitute->date_leave,$date_between))
																		{
																			$employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
																			//echo '<div class="employee">'.ucfirst($employee->first_name).' '.ucfirst($employee->middle_name).' '.ucfirst($employee->last_name).'</div>';
																		}
																		else
																		{
																			if($electname!=NULL)
																			{
																				//echo '<div class="employee">'.ucfirst($time_emp->first_name).' '.ucfirst($time_emp->middle_name).' '.ucfirst($time_emp->last_name).'</div>';
																			}
																		}
																	}
																	
																}?>
																 </div>
															</div>
														</div>
											<?php	
												}
												
										?>
										</td>
										<?php 
									}?>
				</tr>
			<?php
				}
				$this_date	= date("Y-m-d", strtotime("+1 days", strtotime($this_date))); 
				$week_start	= date("Y-m-d", strtotime("+1 days", strtotime($week_start))); 
			}?>
          </table>
    <?php
        }
        else{
             echo '<div class="not-foundarea">';
             echo Yii::t('app', 'No Timetables');
             echo '</div>';
        }
    }
    else{
            echo '<div class="not-foundarea">';
            echo Yii::t('app', 'No Class Timings');
            echo '</div>';
    }    
}
?>