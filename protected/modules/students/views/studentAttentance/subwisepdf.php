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
 <?php 	$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
		$yr = AcademicYears::model()->findByAttributes(array('id'=>$current_academic_yr->config_value));
		
		$student = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));
		$course 	= 	Courses::model()->findByAttributes(array('id'=>$batch->course_id));
		
		$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
		$semester_enabled= Configurations::model()->isSemesterEnabled();
	  	$sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);
		
		$date				= (isset($_REQUEST['date']) and $_REQUEST['date'] != NULL)?$_REQUEST['date']:date("Y-m-d");
		
		$day 				= date('w', strtotime($date));
		$week_start			= date('Y-m-d', strtotime('-'.$day.' days', strtotime($date)));
		$week_end 			= date('Y-m-d', strtotime('+'.(6-$day).' days', strtotime($date)));
		$prev_week_start	= date('Y-m-d', strtotime('-7 days', strtotime($week_start)));
		$next_week_start	= date('Y-m-d', strtotime('+1 days', strtotime($week_end)));
		$this_date			= $week_start;?>
<?php
if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL and isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL){
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
                        <td class="listbxtop_hdng first" style="font-size:20px;" >
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
                        <td class="listbxtop_hdng first" >
                            <?php echo 'Phone: '.$college[2]->config_value; ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
	<hr />
      <h2 align="center"><?php echo Yii::t('app','Subject Wise Attendance');?></h2>
      <!-- Student details -->
    <div style="border:#C5CED9 1px; width:1020px; padding:10px 10px; background:#DCE6F1;">
         
        <table style="font-size:14px;">
            <tr>
                <td style="width:150px;"><?php echo Yii::t('app','Name'); ?></td>
                <td style="width:10px;">:</td>
				<?php if(FormFields::model()->isVisible("fullname", "Students", 'forStudentProfile')){ ?>
                <td style="width:250px;"><?php echo $student->studentFullName('forStudentProfile');?></td>
				<?php }
				else{?>
				 <td style="width:250px;"></td>
				<?php }?>
                
                <td width="150"><?php echo Yii::t('app','Course'); ?></td>
                <td width="10">:</td>
                <td><?php echo $course->course_name;?></td> 
            </tr>
            <tr>
 <?php if(FormFields::model()->isVisible('batch_id','Students','forStudentProfile'))
				{ ?>   
                <td width="150"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?></td>
                <td width="10">:</td>
                <td><?php echo $batch->name; ?></td>
		  <?php }
				else
				{
					?><td width="150"></td><td  width="10"></td><td></td><?php
				}
				?>
				<?php  if($semester_enabled==1 and $sem_enabled==1 and $batch->semester_id!=NULL){ ?>	
							<td><?php echo Yii::t('app','Semester'); ?></td>
							<td>:</td>
							<td><?php echo ucfirst($semester->name); ?></td>
				<?php }?>
            </tr>
        </table>
    </div>
    <!-- END Student details -->
    <?php
	$month1	=	date("M", strtotime($week_start));
	$month2	=	date("M", strtotime($week_end));
	$day1	=	date("d", strtotime($week_start));	
	$day2	=	date("d", strtotime($week_end));	
	?>
    
 <div align="center" style="display:block; text-align:center;"><h5><?php echo Yii::t("app",$month1).' '.$day1." - ".Yii::t("app",$month2).' '.$day2;?></h5></div>
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
    if(isset($timings) and $timings!=NULL){
		
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
					$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));													
						 if($settings!=NULL){
							    //traslate AM and PM 	
								$t1 = date('h:i', strtotime($timing_1->start_time));	
								$t2 = date('A', strtotime($timing_1->start_time));
								
								$t3	= date('h:i', strtotime($timing_1->end_time));	
								$t4	= date('A', strtotime($timing_1->end_time));	
								//end 
								
								$time1	= date($settings->timeformat,strtotime($timing_1->start_time));
								$time2	= date($settings->timeformat,strtotime($timing_1->end_time));
							echo '<td style="font-size:11px;background:#E1EAEF;word-break:break-all;">'.$t1.' '.Yii::t("app",$t2).' -<br> '.$t3.' '.Yii::t("app",$t4).'</td>';
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
									{	$visible=0;
										$subjectwise =  StudentSubjectwiseAttentance::model()->findByAttributes(array('student_id'=>$_REQUEST['id'], 'timetable_id'=>$set->id, 'weekday_id'=>$set->weekday_id, 'date'=>$week_start));
										if($set->is_elective == 2){
											$elective			=	Electives::model()->findByAttributes(array('batch_id'=>$batch->id, 'id'=>$set->subject_id));  
											$student_elective	=	StudentElectives::model()->findByAttributes(array('student_id'=>$_REQUEST['id'], 'batch_id'=>$batch->id, 'elective_group_id'=>$elective->elective_group_id)); 
											if($student_elective==NULL){
												$visible=1;
											}else{
												$visible=0;
											}															
										}else{
											$visible=0;
										}  
										$is_holiday		= StudentAttentance::model()->isHoliday($week_start);
										if($is_holiday == NULL)	{
											if($subjectwise == NULL){
												if($batch->start_date <= $week_start and $week_start <= $batch->end_date ){
													if($student->admission_date <= $week_start and $week_start <= date("Y-m-d") and $visible==0){
														echo '<span style="color:#070;">'.Yii::t('app','Present').'</span>';
														echo '<br>';
													}	
												}
											}
											else{
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
																		echo '<div class="employee">'.ucfirst($employee->first_name).' '.ucfirst($employee->middle_name).' '.ucfirst($employee->last_name).'</div>';
																	}
																	else
																	{
																		if($time_sub!=NULL)
																		{
																			echo '<div class="employee">'.ucfirst($time_emp->first_name).' '.ucfirst($time_emp->middle_name).' '.ucfirst($time_emp->last_name).'</div>';
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
																
																if($electname!=NULL  and $visible==0)
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
											else{
												echo '<span  class="attnd-holiday">'.Yii::t('app','Holiday').'</span>';
												}	
											} //end time table entries present
									?>
									</td>
									<?php 
								}?>
					</tr>
		<?php
			}
			$this_date	= date("Y-m-d", strtotime("+1 days", strtotime($this_date))); 
			$week_start	= date("Y-m-d", strtotime("+1 days", strtotime($week_start))); 
		}
		?>
     </table>
<?php
	}
	else{
		echo '<div class="not-foundarea">';
		echo Yii::t('app', 'No Class Timings');
		echo '</div>';
	}    
}
?>