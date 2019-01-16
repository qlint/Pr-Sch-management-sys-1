

<?php Yii::app()->clientScript->registerCoreScript('jquery');?>

<div id="parent_Sect">
	<?php $this->renderPartial('leftside');?> 
	<div class="right_col"  id="req_res123">
    <!--contentArea starts Here--> 
     <div id="parent_rightSect">
        <div class="parentright_innercon">
        <div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-calendar-o"></i><?php echo Yii::t("app", 'Time Table');?><span><?php echo Yii::t("app", 'View your Time Table here');?> </span></h2>
        </div>
        <div class="col-lg-2">
        
                </div>
    
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t("app", 'You are here:');?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t("app", 'Time Table');?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>
    <div class="contentpanel">
    
<div class="panel-heading">
            <h3 class="panel-title"><?php echo Yii::t('app','My Class Time Table '); ?></h3>           
        	</div>
            <div class="people-item">
             <?php $this->renderPartial('/default/employee_tab');?>
            
				<?php
				if($_REQUEST['id']){
					echo CHtml::link(Yii::t('app','Generate PDF'), array('Default/employeeflexibleClasstimetablepdf','id'=>$_REQUEST['id']),array('class'=>'btn btn-danger pull-right','target'=>'_blank'));
				}
				
			 ?>
              <?php 
					if(isset($_REQUEST['id'])){ //If batch ID is set or no list of batches
					 ?>
						<div class="atdn_div">
                            <div class="name_div">
                                <?php
								$criteria = new CDbCriteria;
								$criteria->join		= "JOIN `students` `s` ON `s`.`id`=`t`.`student_id`";
								$criteria->condition = '`s`.`is_deleted`=:is_deleted AND `s`.`is_active`=:is_active AND `t`.`batch_id`=:batch_id AND `t`.`result_status`=:result';
								$criteria->params[':is_deleted'] = 0;
								$criteria->params[':is_active'] = 1;
								$criteria->params[':batch_id'] = $_REQUEST['id'];
								$criteria->params[':result'] = 0;
								$students_count=count(BatchStudents::model()->findAll($criteria));
				               
                                $batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
                                $course_name = Courses::model()->findByAttributes(array('id'=>$batch_name->course_id));
								
								echo Yii::t('app','Course Name').' &nbsp; : &nbsp;'.$course_name->course_name.' '.' &nbsp'.' '.' &nbsp';								
								echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name').' &nbsp; :  &nbsp;'.$batch_name->name; 
								//semester check
								if(Configurations::model()->isSemesterEnabledForCourse($batch_name->course_id) == 1){
									$semester = Semester::model()->findByAttributes(array('id'=>$batch_name->semester_id));
									echo "&nbsp&nbsp&nbsp&nbsp".Yii::t('app','Semester Name').' &nbsp; : &nbsp;'.(($semester!=NULL)?$semester->name:'-');
								}
                           ?>
                            </div>
                            <br />
							<div class="clearfix"></div>
                            
                            <div class="timetable_div">
                            	<?php                                    
                                    if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL){
										
										//Getting dates in a week 
										$day 			= date('w');
										$week_start 	= date('Y-m-d', strtotime('-'.$day.' days'));
										$week_end 		= date('Y-m-d', strtotime('+'.(6-$day).' days'));
										$date_between 	= array();
										$begin 			= new DateTime($week_start);
										$end 			= new DateTime($week_end);
										
										$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
										
										foreach($daterange as $date)
											$date_between[] = $date->format("Y-m-d");
											
										if(!in_array($week_end,$date_between))
											$date_between[] = date('Y-m-d',strtotime($week_end));
										   
										$times		= Batches::model()->findAll("id=:x", array(':x'=>$_REQUEST['id']));
										$weekdays	= Weekdays::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id']));										
										if(count($weekdays)==0)
											$weekdays=Weekdays::model()->findAll("batch_id IS NULL");
											
										$sun = Yii::t('app','SUN');
										$mon = Yii::t('app','MON');
										$tue = Yii::t('app','TUE');
										$wed = Yii::t('app','WED');
										$thu = Yii::t('app','THU');
										$fri = Yii::t('app','FRI');
										$sat = Yii::t('app','SAT');
										$weekday_text = array($sun, $mon, $tue, $wed, $thu, $fri, $sat);
											
										$criteria				= new CDbCriteria;
								        $criteria->condition 	= "batch_id=:x";
								        $criteria->params 		= array(':x'=>$_REQUEST['id']);
								        $criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
										$timings 				= ClassTimings::model()->findAll($criteria);
										
										
										$count_timing = count($timings);
										
										if($timings!=NULL){                                        
										?>
                                            <div class="timetable-1" style="margin-top:10px;">
                                                <table border="0" align="center" width="100%" id="table" cellspacing="0" class="timetable-br1">
                                                    <tbody>
                                                        <tr>
                                                            <?php
																echo '<th width="80"><div style="width:50px;">&nbsp;</div></th>'; ##change - new calendar format
                                                                $weekday_count	= 0;
                                                                foreach($weekdays as $weekday){														
                                                                    if($weekday['weekday']!=0){
                                                                        echo '<th><div class="top">'.$weekday_text[$weekday['weekday']-1].'</div></th>';
                                                                        $weekday_count++;
                                                                    }
                                                                }
                                                            ?>
                                                        </tr>
                                                        
                                                        
                                                        <tr>
															<?php															
															##change - new calendar format start##
															
															echo '<td valign="top"><table class="timetable-br-time" width="100%" border="0" cellspacing="0" cellpadding="0">';
															
															$time_intervals	= array();
															$first_timing		= $timings[0];
															$last_timing		= end($timings);
															$time_span			= 60; 		// in minutes
																														
															$calendar_start_time	= strtotime(date("h:i A", strtotime($first_timing->start_time)));
															$calendar_end_time		= strtotime(date("h:i A", strtotime($last_timing->end_time)));
															$calendar_time			= $calendar_start_time;
															while($calendar_time<$calendar_end_time){
																$time_intervals[]	= date("h:i A", $calendar_time);
																
																//calculate timespan diff
																$hours		= date("h", $calendar_time);
																$minutes	= date("i", $calendar_time);																
																$total_minutes	= ($hours*60) + $minutes;
																$diff			= $total_minutes%$time_span;
																if($diff==0)
																	$calendar_time		= strtotime('+'.$time_span.' minutes', $calendar_time);
																else
																	$calendar_time		= strtotime('+'.($time_span - $diff).' minutes', $calendar_time);																	
															}
															
															$proportion		= 3;
															foreach($time_intervals as $index=>$time_interval){		
															$t_interval1 = date('h:i', strtotime($time_interval));	
															$t_interval2 = date('A', strtotime($time_interval));														
																if(($index+1)==count($time_intervals)){	// last timing
																	$to_time 		= strtotime(date("h:i A", strtotime($last_timing->end_time)));
																	$from_time 		= strtotime(date("h:i A", strtotime($time_interval)));
																	$diff_minutes	= round(abs($to_time - $from_time) / 60,2);
																	echo '<tr><td width="80" height="'.( $diff_minutes * $proportion ).'" style="position:relative;">';
																	 
																	echo '<div  class="time-box">'.$t_interval1.'-'.Yii::t("app",$t_interval2).'</div>';																		
																	echo '</td></tr>';
																}
																else{
																	//calculate timespan diff
																	$hours		= date("h", strtotime($time_interval));
																	$minutes	= date("i", strtotime($time_interval));																
																	$total_minutes	= ($hours*60) + $minutes;																																
																	$diff			= $total_minutes%$time_span;
																	$diff_minutes	= $time_span - $diff;
																	echo '<tr><td width="80" height="'.( $diff_minutes * $proportion ).'" style="position:relative;">';
																	if($total_minutes%$time_span==0)
																		echo '<div  class="time-box">'.$t_interval1.'-'.Yii::t("app",$t_interval2).'</div>';
																		
																	echo '</td></tr>';														
																}
															}															
															echo '</table></td>';
															
															##change - new calendar format end##
															
															$weekday_attributes	= array(1=>'on_sunday',2=>'on_monday',3=>'on_tuesday',4=>'on_wednesday',5=>'on_thursday',6=>'on_friday',7=>'on_saturday');
															$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
															
                                                            foreach($weekdays as $weekday){
																if($weekday['weekday']!=0){																	
																?>
                                                                    <td class="td " valign="top">
                                                                    <?php
                                                                        $weekday_condition		= "`".$weekday_attributes[$weekday['weekday']]."`=:week_day_status";
                                                                        $criteria				= new CDbCriteria;
                                                                        $criteria->condition 	= "batch_id=:x AND ".$weekday_condition;
                                                                        $criteria->params 		= array(':x'=>$_REQUEST['id'], ':week_day_status'=>1);
                                                                        $criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
                                                                        $timings 				= ClassTimings::model()->findAll($criteria);
																																				
																		echo '<table class="timetable-br-box" width="100%" border="0" cellspacing="0" cellpadding="0">';
																		
																		$from_time	= $time_intervals[0];		//set start time
																		
																		foreach($timings as $i=>$timing){
																			if($settings!=NULL){
																				$t1 = date('h:i', strtotime($timing->start_time));	
																				$t2 = date('A', strtotime($timing->start_time));
																				
																				$t3	= date('h:i', strtotime($timing->end_time));	
																				$t4	= date('A', strtotime($timing->end_time));	
																				$time1=date($settings->timeformat,strtotime($timing->start_time));
																				$time2=date($settings->timeformat,strtotime($timing->end_time));
																				
																			}
																			
																			##change - new calendar format start##
																			
																			//find height start
																			$to_time		= $timing->start_time;																			
																			$to_time 		= strtotime($to_time);
																			$from_time 		= strtotime($from_time);
																			
																			$diff_minutes	= round(abs($to_time - $from_time) / 60,2);
																			
																			if($diff_minutes>0){
																				echo '<tr><td height="'.( $diff_minutes * $proportion ).'" valign="top" style="background-color:#FFF;"></td></tr>';
																			}
																			
																			$from_time 		= $timing->end_time;
																			
																			$timing_diff_minutes	= round(abs(strtotime($timing->end_time) - strtotime($timing->start_time)) / 60,2);
																			//find height end
																			
																			echo '<tr><td class="td1" height="'.( $timing_diff_minutes * $proportion ).'" valign="top">																			
																			<div class="timtable-inner"><!------------timtable-inner---------------->'.
																			
																			##change - new calendar format end##
																			
																			'<div class=" time-area">'.$t1.' '.Yii::t("app",$t2).' - '.$t3.' '.Yii::t("app",$t4).'</div>
																			<div  onclick="">
																			<div class="tt-subject">
																			<div class="subject">'; 
																			
																			$set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekday['weekday'],'class_timing_id'=>$timing->id)); 
																			
																			
																			if(count($set)==0)
																			{
																				$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing->id,'is_break'=>1));
																				if($is_break==NULL)
																				{	
																					
																				}
																				else
																				{
																					echo Yii::t('app','Break');
																				}	
																			}
																			
																			else
																			{
																				if($set->is_elective==0)
																				{
																					$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
																					
																					//$is_teacher_exist = EmployeesSubjects::model()->findAllByAttributes(array('subject_id'=>$time_sub->id));
																					if($time_sub!=NULL)
																					{
																						if($set->split_subject!=0 and $set->split_subject!=NULL){ 
																							if($time_sub->split_subject){
																								$subject_splits	= SubjectSplit::model()->findByPk($set->split_subject);
																								$name_sub	=	$subject_splits->split_name."<br> (".$time_sub->name.")";
																							}
																							else{
																								$name_sub	=	$time_sub->name;
																							} 
																						}else{
																							$name_sub	=	$time_sub->name;
																						} 
																						echo $name_sub .'<br>'; 
																																
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
																						echo '-<br>';
																					}
																				}
																				else
																				{ 
																					$elec_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
																					$electname = ElectiveGroups::model()->findByAttributes(array('id'=>$elec_sub->elective_group_id,'batch_id'=>$_REQUEST['id']));
																					
																					if($electname!=NULL)
																					{
																						echo $electname->name.'<br>';
																					}
																				}
																				
																			}
																			?>
																			<?php echo 	'</div>
																			</div>
																			</div>
																			</div>
																			
																			</td></tr>'; 
																		}
																		echo '</table>';																
                                                                    ?>
                                                                    </td>
																<?php
																}
                                                            }
                                                            ?>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>                                        
                                        <?php 
										}
										else{
											echo '<i>'.Yii::t('app','No Class Timings').'</i>';
										}
                                    }                                    
                                    ?> 
                        	</div> <!-- End timetable div (timetable_div)-->
						</div> <!-- End entire div (atdn_div) -->
				<?php 
					}
				?>
                
			</div>
		</div>
	</div>
	 <div class="clear"></div>
</div>
