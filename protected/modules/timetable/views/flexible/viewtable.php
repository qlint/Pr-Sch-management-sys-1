<?php
// ******************* WEEK TABLE *********************/
if($mode == 1){
?> 
<div class="button-bg button-bg-oneside">
<div class="top-hed-btn-right">
                 <?php echo CHtml::link(Yii::t('app','Generate PDF'), array('Weekdays/fullpdf','id'=>$batch_id,'yid'=>$_REQUEST['yid'],'cid'=>$_REQUEST['cid'],'mode'=>$_REQUEST['mode'],'day'=>$_REQUEST['day']),array('class'=>'a-tag-pdf-btn','target'=>'_blank')); ?>
</div>
</div>
<div class="timetable-1" style="text-align:center">
    <?php		
    $weekdays = Weekdays::model()->findAll("batch_id=:x", array(':x'=>$batch_id)); // Selecting weekdays of the batch
    if(count($weekdays) == 0){
        $weekdays = Weekdays::model()->findAll("batch_id IS NULL");
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
	
	foreach($weekday_attributes as $key=>$weekday_attribute){
		$weekday_condition	.= "`".$weekday_attribute."`=:weekday_status";
		if($key < count($weekday_attributes)){
			$weekday_condition	.= " OR ";
		}
	}
	
	$weekday_condition		= " AND (".$weekday_condition.")";
	
    $criteria				= new CDbCriteria;
    $criteria->condition 	= "batch_id=:x".$weekday_condition;
    $criteria->params 		= array(':x'=>$batch_id, ':weekday_status'=>1);
    $criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";
    $timings 				= ClassTimings::model()->findAll($criteria); // Selecting class timings of the batch
    $count_timing 			= count($timings);
    if($timings!=NULL){ // If class timing is set for the batch
    ?>
    


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
								$start_t		=	Configurations::model()->convertTime($time_interval);														
								if(($index+1)==count($time_intervals)){	// last timing
									$to_time 		= strtotime(date("h:i A", strtotime($last_timing->end_time)));
									$from_time 		= strtotime(date("h:i A", strtotime($time_interval)));
									$diff_minutes	= round(abs($to_time - $from_time) / 60,2);
									echo '<tr><td width="80" height="'.( $diff_minutes * $proportion ).'" style="position:relative;">';
									echo '<div  class="time-box">'.$start_t.'</div>';																		
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
										echo '<div  class="time-box">'.$start_t.'</div>';
										
									echo '</td></tr>';														
								}
							}															
							echo '</table></td>';
														
							##change - new calendar format end## ?>
            		                       
                <?php				
				$settings     			= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
			
				foreach($weekdays as $weekday){
					if($weekday->weekday!=0){
						$weekday_condition		= "`".$weekday_attributes[$weekday['weekday']]."`=:week_day_status";
						$criteria				= new CDbCriteria;
						$criteria->condition 	= "batch_id=:x AND ".$weekday_condition;
						$criteria->params 		= array(':x'=>$batch_id, ':week_day_status'=>1);
						$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
						$timings 				= ClassTimings::model()->findAll($criteria);									
						if($timings!=NULL){
						?>
						<td class="td" valign="top">
					<?php		echo '<table class="timetable-br-box" width="100%" border="0" cellspacing="0" cellpadding="0">';
																		
																		$from_time	= $time_intervals[0];		//set start time
																		
																		foreach($timings as $i=>$timing){
																			if($settings!=NULL){
																				$start		=	Configurations::model()->convertTime($timing->start_time);
																				$end		=	Configurations::model()->convertTime($timing->end_time);	
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
																			 
																			
																			'<div class="time-area">'.$start.' - '.$end.'</div>
																			<div  onclick="" style="position: relative; ">
																			<div class="tt-subject">
																			<div class="subject">'; 
												
													$set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$batch_id,'weekday_id'=>$weekday->weekday,'class_timing_id'=>$timing->id)); 			
													if($set != NULL){
														if($set->is_elective==0){
															$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
															if($time_sub!=NULL){
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
																//echo $time_sub->name.'<br>';
																$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
																if($time_emp!=NULL){
																	$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));
																	if($is_substitute and in_array($is_substitute->date_leave,$date_between)){
																		$employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
																		echo '<div class="employee">'.$employee->first_name.' '.$employee->middle_name.$employee->last_name.'</div>';
																	}
																	else{
																		if($time_sub!=NULL){
																			echo '<div class="employee">'.$time_emp->first_name.' '.$time_emp->middle_name.' '.$time_emp->last_name.'</div>';
																		}
																	}
																}
															}
															else{
																echo '-<br>';
															}
														}
														else{ 
															$elec_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
															$electname = ElectiveGroups::model()->findByAttributes(array('id'=>$elec_sub->elective_group_id,'batch_id'=>$batch_id));	
															if($electname!=NULL){
																echo $electname->name.'<br>';
															}
														}												
													}
													else{
														$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing->id,'is_break'=>1));
														if($is_break!=NULL){	
															echo Yii::t('app','Break');	
														}	
													}
												 echo 	'</div>
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
					} 
				}
                ?>
            </tr>
        </tbody>
    </table>
    <?php
    }
    else{ // If class timing is not set for the batch
        echo '<i>'.Yii::t('app','No Timetable is set for this').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</i>';
    }
    ?>
</div>
<br /><br />
<?php
}
// ******************* END WEEK TABLE *********************/

// ******************* DAY TABLE *********************/
elseif($mode == 2)
{
	//echo 'DAY';
?>

<div class="timetable-1" style="text-align:center; overflow:hidden;">
    <?php		
    $weekdays = Weekdays::model()->findAll("batch_id=:x AND weekday=:y",array(':y'=>$day, ':x'=>$batch_id)); // Selecting weekdays of the batch
    if(count($weekdays) == 0){
        $weekdays = Weekdays::model()->findAll("batch_id IS NULL AND weekday=:y",array(':y'=>$day));
    }
    
    $sun = Yii::t('app','SUN');
    $mon = Yii::t('app','MON');
    $tue = Yii::t('app','TUE');
    $wed = Yii::t('app','WED');
    $thu = Yii::t('app','THU');
    $fri = Yii::t('app','FRI');
    $sat = Yii::t('app','SAT');
    $weekday_text 			= array($sun, $mon, $tue, $wed, $thu, $fri, $sat);
    $weekday_attributes		= array(1=>'on_sunday',2=>'on_monday',3=>'on_tuesday',4=>'on_wednesday',5=>'on_thursday',6=>'on_friday',7=>'on_saturday');	
	$weekday_condition		= " AND `".$weekday_attributes[$day]."`=:weekday_status";	
    $criteria				= new CDbCriteria;
    $criteria->condition 	= "batch_id=:x".$weekday_condition;
    $criteria->params 		= array(':x'=>$batch_id, ':weekday_status'=>1);
    $criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";
    $timings 				= ClassTimings::model()->findAll($criteria); // Selecting class timings of the batch
    $count_timing 			= count($timings);
    if($timings!=NULL){ // If class timing is set for the batch
    ?>  
    
    <table>
        <tr>
            <td>

            </td>
         </tr>
         <tr>
            <td><br /></td>
         </tr>
    </table>
<div class="button-bg button-bg-oneside">
<div class="top-hed-btn-right">
                <?php echo CHtml::link(Yii::t('app','Generate PDF'), array('Weekdays/fullpdf','id'=>$batch_id,'yid'=>$_REQUEST['yid'],'cid'=>$_REQUEST['cid'],'mode'=>$_REQUEST['mode'],'day'=>$_REQUEST['day']),array('class'=>'a-tag-pdf-btn','target'=>'_blank')); ?></div>
</div>
    
    <table border="0"  width="10%" id="table" cellspacing="0" class="timetable-br1">
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
							$time_start		=	Configurations::model()->convertTime($time_interval); 														
								if(($index+1)==count($time_intervals)){	// last timing
									$to_time 		= strtotime(date("h:i A", strtotime($last_timing->end_time)));
									$from_time 		= strtotime(date("h:i A", strtotime($time_interval)));
									$diff_minutes	= round(abs($to_time - $from_time) / 60,2);
									echo '<tr><td width="80" height="'.( $diff_minutes * $proportion ).'" style="position:relative;">';
									echo '<div  class="time-box">'.$time_start.'</div>';																		
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
										echo '<div  class="time-box">'.$time_start.'</div>';
										
									echo '</td></tr>';														
								}
							}															
							echo '</table></td>';
														
							##change - new calendar format end## 
				$settings     			= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
			
				foreach($weekdays as $weekday){
					if($weekday->weekday!=0){
						$weekday_condition		= "`".$weekday_attributes[$weekday['weekday']]."`=:week_day_status";
						$criteria				= new CDbCriteria;
						$criteria->condition 	= "batch_id=:x AND ".$weekday_condition;
						$criteria->params 		= array(':x'=>$batch_id, ':week_day_status'=>1);
						$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
						$timings 				= ClassTimings::model()->findAll($criteria);									
						if($timings!=NULL){
					?>
                    <td class="td" valign="top">
                    <?php
						echo '<table class="timetable-br-box" width="100%" border="0" cellspacing="0" cellpadding="0">';
																		
																		$from_time	= $time_intervals[0];		//set start time
																		
																		foreach($timings as $i=>$timing){
																			if($settings!=NULL){	
																				$start		=	Configurations::model()->convertTime($timing->start_time);
																				$end		=	Configurations::model()->convertTime($timing->end_time);
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
																			 
																			'<div>'.$start.' - '.$end.'</div>
																			<div  onclick="" style="position: relative; ">
																			<div class="tt-subject">
																			<div class="subject">'; 
												
													$set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$batch_id,'weekday_id'=>$weekday->weekday,'class_timing_id'=>$timing->id)); 			
													if($set != NULL){
														if($set->is_elective==0){
															$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
															if($time_sub!=NULL){
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
																//echo $time_sub->name.'<br>';
																$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
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
															}
															else{
																echo '-<br>';
															}
														}
														else{ 
															$elec_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
															$electname = ElectiveGroups::model()->findByAttributes(array('id'=>$elec_sub->elective_group_id,'batch_id'=>$batch_id));	
															if($electname!=NULL){
																echo $electname->name.'<br>';
															}
														}												
													}
													else{
														$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing->id,'is_break'=>1));
														if($is_break!=NULL){	
															echo Yii::t('app','Break');	
														}	
													}
												
												 echo 	'</div>
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
					} 
				}
                ?>
            </tr>
        </tbody>
    </table>
    <?php
    }
    else{ // If class timing is not set for the batch
        echo '<i>'.Yii::t('app','No Timetable is set for this').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</i>';
    }
    ?>
</div>
<br /> <br />
<?php
}
// ******************* END DAY TABLE *********************/
?>   
   
