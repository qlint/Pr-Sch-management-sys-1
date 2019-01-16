<?php
if(isset($department_id) and isset($employee_id) and isset($weekday_id)){
?>
<style>
    .formConInner {
        background: #f8fafb url("images/formcon_new-bg.png") repeat scroll 0 0;
        border: 1px solid #edbc3a;
        border-radius: 3px;
        width: auto;
    }
    .formConInner {
        background: #f8fafb url("images/formcon_new-bg.png") repeat scroll 0 0;
        border: 1px solid #edbc3a;
        border-radius: 3px;
        width: auto;
        font-weight:bold;
    }
    </style>
    
<div class="button-bg">
<div class="top-hed-btn-right">
<ul>
<li>
			<?php echo CHtml::link("Table View", array("/timetable/teachersTimetable/index", "department_id"=>$_REQUEST['department_id'], "employee_id"=>$employee_id,  "day_id"=>$_REQUEST['day_id'], "format"=>"tbl"), array('class'=>'a_tag-btn'));?>
            </li>
            </li>
</div>
</div>
    
    
<?php
if($department_id == 0){
	$employees = Employees::model()->findAll();
}
if($department_id != 0 and $employee_id==0){
	$employees = Employees::model()->findAllByAttributes(array('employee_department_id'=>$department_id));
}
if($department_id != 0 and $employee_id!=0){
	$employees = Employees::model()->findAllByAttributes(array('id'=>$employee_id));
}

foreach($employees as $employee){
	$department	= EmployeeDepartments::model()->findByAttributes(array('id'=>$employee->employee_department_id));
			?>			
			<div class="formConInner" style="margin-top:20px;">
				<table style="text-align:center;">
					<tbody>
						<tr>
							<td style="width:auto; min-width:200px;"><?php echo Yii::t("app", "Department")." : ".$department->name;?></td>
							<td width="20px"></td>
							<td style="width:auto; min-width:200px;"><?php echo Yii::t("app", "Employee")." : ".$employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;?></td>
							<td width="20px"></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="clear"></div><br />

 
<?php
if($weekday_id == 0){		
	 $weekdays = Weekdays::model()->findAll("batch_id IS NULL");
}
else{
	$weekdays = Weekdays::model()->findAll("batch_id IS NULL AND weekday=:y",array(':y'=>$weekday_id));
}
    
    $sun = Yii::t('app','SUN');
    $mon = Yii::t('app','MON');
    $tue = Yii::t('app','TUE');
    $wed = Yii::t('app','WED');
    $thu = Yii::t('app','THU');
    $fri = Yii::t('app','FRI');
    $sat = Yii::t('app','SAT');
    $weekday_text = array($sun, $mon, $tue, $wed, $thu, $fri, $sat);
    $weekday_attributes		= array(1=>'on_sunday',2=>'on_monday',3=>'on_tuesday',4=>'on_wednesday',5=>'on_thursday',6=>'on_friday',7=>'on_saturday');
	$weekday_condition		= "";
	
	if($weekday_id == 0){
		foreach($weekday_attributes as $key=>$weekday_attribute){
			$weekday_condition	.= "`t`.`".$weekday_attribute."`=:weekday_status";
			if($key < count($weekday_attributes)){
				$weekday_condition	.= " OR ";
			}
		}
	}
	else{
		$weekday_condition		= "`t`.`".$weekday_attributes[$weekday_id]."`=:weekday_status";
	}
	
	$weekday_condition		= $weekday_condition;
	
		$criteria				= new CDbCriteria;
		$criteria->join 		= "INNER JOIN `timetable_entries` `te` ON `te`.`class_timing_id` = `t`.`id`";
		$criteria->condition 	= "`te`.`employee_id`=:x AND (".$weekday_condition.")";
		$criteria->params 		= array(':x'=>$employee->id, ':weekday_status'=>1);
		$criteria->order 		= "STR_TO_DATE(`t`.`start_time`, '%h:%i %p') ASC, STR_TO_DATE(`t`.`end_time`, '%h:%i %p') ASC";
		$criteria->distinct		= true;
		$timings 				= ClassTimings::model()->findAll($criteria);
		if($timings!=NULL){ ?>
		

<?php /*?> <div class="button-bg">
    <div class="pdf-btn-posiction">
                                          <?php echo CHtml::link(Yii::t('app','Generate PDF'), array('teachersTimetable/fullteacherPdf','department_id'=>$department->id,'employee_id'=>$employee->id,'weekday_id'=>$weekday_id, "format"=>"cal"),array('class'=>'cbut','target'=>'_blank')); ?>
    </div>
</div>     

<div class="top-viewbtn-bg">
<div class="top-viewbtn">
    <ul>
		<li>
			<?php echo CHtml::link("Default View", array("/timetable/teachersTimetable/index", "department_id"=>$_REQUEST['department_id'], "employee_id"=>$_REQUEST['employee_id'],  "day_id"=>$_REQUEST['day_id'], "format"=>"tbl"), array('class'=>'btn-style-bg view-icon'));?>
		</li>

	</ul>
</div>
</div> <?php */?>

<?php /*?><div class="pdf-box">
        <div class="box-two">
        	<div class="box-btn-inner">
			<?php echo CHtml::link("Default View", array("/timetable/teachersTimetable/index", "department_id"=>$_REQUEST['department_id'], "employee_id"=>$employee->id,  "day_id"=>$_REQUEST['day_id'], "format"=>"tbl"), array('class'=>'btn-style-bg view-icon'));?>
            </div>
        </div>
    </div><?php */?>
<div class="button-bg button-bg-oneside">
<div class="top-hed-btn-right">
              <?php echo CHtml::link(Yii::t('app','Generate PDF'), array('teachersTimetable/fullteacherPdf','department_id'=>$department->id,'employee_id'=>$employee->id,'weekday_id'=>$weekday_id, "format"=>"cal"),array('class'=>'a-tag-pdf-btn','target'=>'_blank')); ?></div>
</div>  
    
		
	<?php	if(isset($_REQUEST['day_id']) and $_REQUEST['day_id'] == 0){
				$width = '100%';
				$align = 'center';
			}
			else{
				$width = '10%';
				$align = 'left';
			}
			?>
			
			<div class="timetable-1" style="text-align:center;">            	
				<table border="0" align="<?php /*?><?php echo $align; ?><?php */?>" width="<?php echo $width; ?>" id="table" cellspacing="0" class="timetable-br1">               
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
                                for($i=0;$i<=$weekday_count;$i++){ ##change - new calendar format
                                    echo '<td></td>';  
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
																		
								$settings     			= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
								
								foreach($weekdays as $weekday){
									if($weekday->weekday!=0){
										$weekday_condition		= "`t`.`".$weekday_attributes[$weekday->weekday]."`=:week_day_status";
										$criteria				= new CDbCriteria;
										$criteria->join 		= "INNER JOIN `timetable_entries` `te` ON `te`.`class_timing_id` = `t`.`id`";
										$criteria->condition 	= "`te`.`weekday_id`=:week AND `te`.`employee_id`=:x AND ".$weekday_condition;
										$criteria->params 		= array(':week'=>$weekday->weekday, ':x'=>$employee->id, ':week_day_status'=>1);
										$criteria->order 		= "STR_TO_DATE(`t`.`start_time`, '%h:%i %p')";
										$criteria->group		= "`t`.`start_time`, `t`.`end_time`";
										$criteria->distinct		= true;
										$timings 				= ClassTimings::model()->findAll($criteria);																							
										if($timings!=NULL){
										?>
										<td class="td" valign="top">
											<?php
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
													
													'<div class="time-area">'.$time1.' - '.$time2.'</div>
													<div  onclick="" style="position: relative; ">
													<div class="tt-subject">
													<div class="subject">'; 
												
													$conditions	= array('weekday_id'=>$weekday->weekday,'class_timing_id'=>$timing->id, 'employee_id'=>$employee->id);
													
													$set 		= TimetableEntries::model()->findByAttributes($conditions);
												
													if($set != NULL){
														if($set->is_elective==0){
															$time_sub 	= Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
															//$class_room = ClassRooms::model()->findByAttributes(array('id'=>$set->class_room_id));
															//$batch 		= Batches::model()->findByAttributes(array('id'=>$time_sub->batch_id));
															if($time_sub!=NULL){
																echo $time_sub->name.'<br>';
																/*$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
																if($time_emp!=NULL){
																	$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));
																	if($is_substitute and in_array($is_substitute->date_leave,$date_between)){
																		$employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
																		echo '<div class="employee">'.$employee->first_name.' '.$employee->middle_name.' '.$employee->last_name.'</div>';
																	}
																	else{
																		if($time_sub!=NULL){
																			echo '<div class="employee">'.$time_emp->first_name.' '.$time_emp->middle_name.' '.$time_emp->last_name.'</div>';
																		}
																	}
																}
																if($batch!=NULL){
																	echo '<div class="employee">'.$batch->name.'</div>';
																}																
																if($class_room!=NULL){																	
																	echo '<div class="employee">'."classroom : ".$class_room->name.'</div>';
																}*/
															}
															/*else{
																echo '-<br>';
															}*/
														}
														else{ 
															$elec_sub 	= Electives::model()->findByAttributes(array('id'=>$set->subject_id));
															$electname 	= ElectiveGroups::model()->findByAttributes(array('id'=>$elec_sub->elective_group_id,'batch_id'=>$elec_sub->batch_id));	
															if($electname!=NULL){
																echo $electname->name.'<br>';
															}
														}	
														
														//batches
														$criteria				= new CDbCriteria;
														$criteria->join 		= "INNER JOIN `class_timings` `ct` ON `t`.`class_timing_id` = `ct`.`id`";
														$criteria->condition 	= "`t`.`weekday_id`=:week AND `t`.`employee_id`=:x AND `ct`.`start_time`=:start_time AND `ct`.`end_time`=:end_time";
														$criteria->params 		= array(':week'=>$weekday->weekday, ':x'=>$employee->id, ':start_time'=>$timing->start_time, ':end_time'=>$timing->end_time);
														$criteria->distinct		= true;
														$entries 		= TimetableEntries::model()->findAll($criteria);
														
														echo '<div class="employee">';
														foreach($entries as $k=>$entry){
															if($set->is_elective==0){
																$subject 	= Subjects::model()->findByAttributes(array('id'=>$entry->subject_id));																
															}
															else{
																$subject 	= Electives::model()->findByAttributes(array('id'=>$entry->subject_id));
															}
															
															if($subject!=NULL and $subject->batch_id!=NULL){
																$batch 		= Batches::model()->findByAttributes(array('id'=>$subject->batch_id));
																if($batch!=NULL){
																	echo $batch->name;
																	if(count($entries)>$k+1){
																		echo ", ";
																	}
																}
															}
														}
														echo '</div>';
														//batches end											
													}
													else{
														$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing->id,'is_break'=>1));
														if($is_break!=NULL){	
															echo Yii::t('app','Break');	
														}	
													}
												?>
												<?php echo 	'</div>
																</div>
																</div>
																<div id="jobDialog'.$timing->id.$weekday['weekday'].'"></div>
																<div id="jobDialogupdate'.$timing->id.$weekday['weekday'].'"></div>
												</td></tr>'; 
											}																		
											echo '</table>';
										?>
									</td>
								<?php 
									}
									else{
									?>
                                    <td class="td" valign="top">&nbsp;
                                    
                                    </td>
                                    <?php
									}
								} 
							}
							?>
						</tr>
                   </tbody>
            </table>
  <?php } 
  		else{ // If class timing is not set
       	 echo '<i>'.Yii::t('app','No Timetable found').'</i>';
    	}?>
        </div>
<?php }
}?>
