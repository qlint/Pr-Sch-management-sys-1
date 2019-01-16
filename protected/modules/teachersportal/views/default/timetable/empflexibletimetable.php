

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
            <h3 class="panel-title"><?php echo Yii::t('app','My Time Table '); ?></h3>           
        	</div>
            <div class="people-item">
             <?php $this->renderPartial('/default/employee_tab');?>
             <?php
			 	$employee=Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
				$login_employee=Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
				$employee_sub = EmployeesSubjects::model()->findByAttributes(array('employee_id'=>$employee->id));
				$subject_details = Subjects::model()->findByAttributes(array('id'=>$employee_sub->subject_id,'batch_id'=>$_REQUEST['id']));
				$timing = ClassTimings::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id'])); // Display pdf button only if there is class timings.
				$batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
				
				 if($_REQUEST['id']!=NULL){
						$list_flag=1;  
				 }
				else{
					
					// Get unique batch ID
					$criteria=new CDbCriteria;
					$criteria->select= 'batch_id';
					$criteria->distinct = true;
					// $criteria->order = 'batch_id ASC'; Uncomment if ID should be retrieved in ascending order
					$criteria->condition='employee_id=:emp_id';
					$criteria->params=array(':emp_id'=>$employee->id);
					$timetable_entries = TimetableEntries::model()->findAll($criteria);
					
					$batches_ids = array();
					if($timetable_entries){
						foreach($timetable_entries as $timetable_entrie){
							if(!in_array($timetable_entrie->batch_id,$batches_ids)){							
								$batches_ids[] = $timetable_entrie->batch_id;
							}
						}
					}
					
			//Check whether the teacher have any substitution in any batch		
					$is_any_substitutes = TeacherSubstitution::model()->findAllByAttributes(array('substitute_emp_id'=>$employee->id));
					if($is_any_substitutes){
						foreach($is_any_substitutes as $is_any_substitute){
							$is_in_timetable = TimetableEntries::model()->findByAttributes(array('id'=>$is_any_substitute->time_table_entry_id));
							if($is_in_timetable){
								if(in_array($is_any_substitute->date_leave,$date_between)){																						
									if(!in_array($is_any_substitute->batch,$batches_ids)){							
										$batches_ids[] = $is_any_substitute->batch;
									}
								}
							}
						}
					}
					
					
					if(count($batches_ids) > 1){ // List of batches is needed
						$list_flag = 2;	
					}
					elseif(count($batches_ids) <= 0){ // If not teaching in any batch
						$list_flag = 0;
					}
					else{ // If only one batch is found
						$list_flag = 1;
						$_REQUEST['id'] = $batches_ids[0];	
						
					}
					
				}
				
				
				if($_REQUEST['id']){
					echo CHtml::link(Yii::t('app','Generate PDF'), array('Default/employeeflexibletimetablepdf','id'=>$_REQUEST['id']),array('class'=>'btn btn-danger pull-right','target'=>'_blank'));
				}
				
			 ?>
              <?php 
			  	//Getting dates in a week
				$day = date('w');
				$week_start = date('Y-m-d', strtotime('-'.$day.' days'));
				$week_end = date('Y-m-d', strtotime('+'.(6-$day).' days'));
				$date_between = array();
				$begin = new DateTime($week_start);
				$end = new DateTime($week_end);
				
				$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
				
				foreach($daterange as $date){
					$date_between[] = $date->format("Y-m-d");
				}
				if(!in_array($week_end,$date_between))
				{
					$date_between[] = date('Y-m-d',strtotime($week_end));
				} 
			 
			  	$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
			  
			   //If $list_flag = 2, list of batches will be displayed. If $list_flag = 1, time table will be displayed. If $list_flag = 0, employee not assigned to any class.
			   
				
				if($list_flag == 0){ // If not teaching in any batch
					 ?>
                <div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;margin-top:60px;">
                    <div class="y_bx_head">
                       <?php echo Yii::t('app','No period is assigned to you now!'); ?>
                    </div>      
       			</div>
				<?php
				}
				if($list_flag==2){ // If list of batches is to be shown
						
					?><div class="cleararea"></div>
                    	<div class="table-responsive">
                        	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered mb30 ">
                            	<thead>
                          			<tr class="pdtab-h">
                                        <th><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name');?></td>
                                        <th><?php echo Yii::t('app','Class Teacher');?></th>
                                        <th><?php echo Yii::t('app','Start Date');?></th>
                                        <th><?php echo Yii::t('app','End Date');?></th>
                         			</tr>
                                    </thead>
                                    <?php 
                          			for($i = 0; $i <count($batches_ids); $i++)
                                	{
										
										$batch=Batches::model()->findByAttributes(array('id'=>$batches_ids[$i],'is_active'=>1,'is_deleted'=>0));
										$course_name = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
										echo '<tr id="batchrow'.$batch->id.'">';
										echo '<td  class="timtable-subject" style="font-weight:bold;">'.CHtml::link($batch->coursename, array('/teachersportal/default/employeetimetable','id'=>$batch->id)).'</td>';
										$settings=UserSettings::model()->findByAttributes(array('id'=>1));
											if($settings!=NULL)
											{	
												$date1=date($settings->displaydate,strtotime($batch->start_date));
												$date2=date($settings->displaydate,strtotime($batch->end_date));
			
											}
										$teacher = Employees::model()->findByAttributes(array('id'=>$batch->employee_id));					
										echo '<td align="center">';
										if($teacher){
											echo $teacher->first_name.' '.$teacher->last_name;
										}
										else{
											echo '-';
										}
										echo '</td>';					
										echo '<td align="center">'.$date1.'</td>';
										echo '<td align="center">'.$date2.'</td>';
										echo '</tr>';
									}
									?>
                            </table>
						</div>
                    <?php
					} // End list of batches	
					if($list_flag==1 or isset($_REQUEST['id'])){ //If batch ID is set or no list of batches
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
                            	<?php $weekdays=Weekdays::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id'])); // Fetching weekdays
								if(count($weekdays)==0)
								{
									$weekdays=Weekdays::model()->findAll("batch_id IS NULL"); // If weekdays are not set for a batch,fetch the default weekdays
								}
								$criteria				= new CDbCriteria;
								$criteria->join			= "JOIN `timetable_entries` `te` ON `te`.`class_timing_id`=`t`.`id`";
								$criteria->condition 	= "`t`.`batch_id`=:x AND `te`.`employee_id`=:employee_id";
								$criteria->params 		= array(':x'=>$_REQUEST['id'], ':employee_id'=>$employee->id);
								$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";
								$criteria->distinct		= true;
								$timings 				= ClassTimings::model()->findAll($criteria); // Fetching Class timings
								$count_timing 			= count($timings);
								
								$sun = Yii::t('app','SUN');
								$mon = Yii::t('app','MON');
								$tue = Yii::t('app','TUE');
								$wed = Yii::t('app','WED');
								$thu = Yii::t('app','THU');
								$fri = Yii::t('app','FRI');
								$sat = Yii::t('app','SAT');
								$weekday_text = array($sun, $mon, $tue, $wed, $thu, $fri, $sat);
								
								if($timings!=NULL) // If class timing is set
								{
								?>
                                <div class="table-responsive" style="overflow-x: scroll">
								<table border="0" align="center" width="90%" id="table" cellspacing="0" class="table table-bordered mb30 teachr-timetble">
									<tbody>
                                    	<tr>
											 <?php
												echo '<th width="60"><div style="width:60px;">&nbsp;</div></th>'; ##change - new calendar format
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
							
							echo '<td valign="top" ><table class="timetable-br-time" width="100%" border="0" cellspacing="0" cellpadding="0">';
							
							$time_intervals	= array();
							$first_timing		= $timings[0];
							$last_timing		= end($timings);
							$time_span			= 30; 		// in minutes
							
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
							
							$proportion		= 4;
							foreach($time_intervals as $index=>$time_interval){																
								if(($index+1)==count($time_intervals)){	// last timing
									$to_time 		= strtotime(date("h:i A", strtotime($last_timing->end_time)));
									$from_time 		= strtotime(date("h:i A", strtotime($time_interval)));
									$diff_minutes	= round(abs($to_time - $from_time) / 60,2);
									echo '<tr><td width="80" height="'.( $diff_minutes * $proportion ).'" style="position:relative;">';
									echo '<div  class="time-box">'.$time_interval.'</div>';																		
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
										echo '<div  class="time-box">'.$time_interval.'</div>';
										
									echo '</td></tr>';														
								}
							}															
							echo '</table></td>';
							
							##change - new calendar format end##  
                                        $weekday_attributes	= array(1=>'on_sunday',2=>'on_monday',3=>'on_tuesday',4=>'on_wednesday',5=>'on_thursday',6=>'on_friday',7=>'on_saturday');
                                        $settings			= UserSettings::model()->findByAttributes(array('user_id'=>1));                                        
                                        foreach($weekdays as $weekday){
											if($weekday->weekday!=0){
											?>
											<td class="td" align="center">
											<?php
												$weekday_condition		= "`".$weekday_attributes[$weekday->weekday]."`=:week_day_status";
												$criteria				= new CDbCriteria;
												$criteria->join			= "JOIN `timetable_entries` `te` ON `te`.`class_timing_id`=`t`.`id`";
												$criteria->condition 	= "`t`.`batch_id`=:x AND `te`.`employee_id`=:employee_id AND `te`.`weekday_id`=:weekday_id AND ".$weekday_condition;
												$criteria->params 		= array(':x'=>$_REQUEST['id'], ':week_day_status'=>1, ':employee_id'=>$employee->id, ':weekday_id'=>$weekday->weekday);
												$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
												$criteria->distinct		= true;
												$timings 				= ClassTimings::model()->findAll($criteria);
												
												echo '<table class="timetable-br-box" width="100%" border="0" cellspacing="0" cellpadding="0">';
																		
																		$from_time	= $time_intervals[0];		//set start time
																		
																		foreach($timings as $i=>$timing){
																			if($settings!=NULL){	
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
																			
																			'<div>'.$time1.' - '.$time2.'</div>
																			<div  onclick="" style="position: relative; ">
																			<div class="tt-subject">
																			<div class="subject">'; 
													$set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekday->weekday,'class_timing_id'=>$timing->id,'employee_id'=>$login_employee->id)); 			
													if(count($set)==0){
														$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing->id,'is_break'=>1));
														if($is_break!=NULL){	
															echo  Yii::t('app','Break');	
														}
													}
													elseif($set->is_elective==0){
														$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
														$emp_sub = EmployeesSubjects::model()->findByAttributes(array('employee_id'=>$employee->id,'subject_id'=>$time_sub->id));
														if($time_sub!=NULL and $emp_sub!=NULL){
															
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
															echo '<b>'.$name_sub .'</b><br>'; 
															$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
															if($time_emp!=NULL){
																$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));																		
																if($is_substitute and in_array($is_substitute->date_leave,$date_between)){
																	$employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
																	echo '<div class="employee">'.ucfirst($employee->first_name).'</div>';                                        
																}
																else{
																	echo '<div class="employee">'.ucfirst($time_emp->first_name).'</div>';                                        
																}
															}
														}
													}
													else{
														$employee=Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
														$elec_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
														$electname = ElectiveGroups::model()->findByAttributes(array('id'=>$elec_sub->elective_group_id,'batch_id'=>$_REQUEST['id']));
														$subject_id = Subjects::model()->findByAttributes(array('elective_group_id'=>$electname->id,'batch_id'=>$_REQUEST['id']));
														$is_employee_elective = EmployeeElectiveSubjects::model()->findByAttributes(array('employee_id'=>$employee->id,'elective_id'=>$elec_sub->id,'subject_id'=>$subject_id->id));											
														if($electname!=NULL and $is_employee_elective!=NULL){
															echo $electname->name.'<br>';
															echo '<div class="employee">'.$employee->first_name.'</div>';                                        
														}
													}
													
													echo '</div></div></div>
													<div id="jobDialog'.$timing->id.$weekday['weekday'].'"></div>
													<div id="jobDialogupdate'.$timing->id.$weekday['weekday'].'"></div>
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
									 echo '<span style="padding-left:230px;"><i>'.Yii::t('app','No timetable set for') .'<b>'.$course_name->course_name.'/'.$batch_name->name.'</b>'.Yii::t('app','batch').'</i></span>';									 
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
