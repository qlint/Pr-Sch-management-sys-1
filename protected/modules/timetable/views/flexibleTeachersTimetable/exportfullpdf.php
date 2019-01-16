<?php
$this->breadcrumbs=array(
	Yii::t('app','Weekdays')=>array('index'),
	Yii::t('app','Manage'),
);
?>
<style>
.timetable-pdf {
	 border-collapse:collapse;
	 border:1px solid #ccc;
}
.timetable-pdf  th{
	 text-align:center;
	  border:1px solid #ccc;
	  background-color:#CCC;
	   
}
.timetable-pdf1 td{
	 text-align:center;
	  border:1px solid #ccc;
	   padding:5px;
		font-size:12px;
	  
	   
}
hr{ border-bottom:1px solid #C5CED9; border-top:0px solid #fff;}

.table_area table{ border-collapse:collapse;}

.table_area table tr td{ border:1px solid #C5CED9;
	padding:10px;}
	
.table_area table tr th{ border:1px solid #C5CED9;
	padding:15px 10px;
	background:#DCE6F1;}
.listbxtop_hdng first{
	text-align:left; 
	font-size:22px; 
	padding-left:10px;

}
.listbxtop_hdng firs{
	text-align:right; 
	font-size:22px; 
	padding-left:8px;	
}
.timetable-br-time {
    background-color: #DDD;
}
.time-box {
    top: 0px;
    text-align: center;
    background-color: #c5e2f1;
    right: 0px;
    font-family: "Open Sans", sans-serif;
    font-size: 13px;
    font-weight: 400;
    line-height: 19px;
    color: #0c5f5f;
    border-top: 2px solid #e8b730;

}
.td1 {
    outline: 1px solid #d6e9c6;
}
.td1 {
    outline: 1px solid #d6e9c6;
	background-color: #CCC;
}
.timtable-inner{
	 font-size:13px;	
}
</style>
<!-- Header -->
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="first" width="100">
                               <?php 
							   
								$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
								if(Yii::app()->user->year)
								{
									$year = Yii::app()->user->year;
								}
								else
								{
									$year = $current_academic_yr->config_value;
								}
							   $logo=Logo::model()->findAll();?>
                                <?php
                                if($logo!=NULL)
                                {
                                    //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                    echo '<img src="uploadedfiles/school_logo/'.$logo[0]->photo_file_name.'" alt="'.$logo[0]->photo_file_name.'" class="imgbrder" height="100" />';
                                }
                                ?>
                    </td>
                    <td  valign="middle" >
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="listbxtop_hdng first">
                                    <?php $college=Configurations::model()->findAll(); ?>
                                    <?php echo $college[0]->config_value; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="listbxtop_hdng first">
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
        <!-- End Header --> 
   
<?php
$department = EmployeeDepartments::model()->findByAttributes(array('id'=>$_REQUEST['department_id']));
$employee=Employees::model()->findByAttributes(array('id'=>$_REQUEST['employee_id']));
?>
<div align="center" style="display:block; text-align:center;"><?php echo Yii::t('app','TEACHER TIMETABLE');?> - <?php echo $department->name; ?> (<?php echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name; ?>)</div>
<?php
$weekday_id =  $_REQUEST['weekday_id'];  
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
		$criteria->join 		= "JOIN `timetable_entries` `te` ON `te`.`class_timing_id` = `t`.`id`";
		$criteria->condition 	= "`te`.`employee_id`=:x AND (".$weekday_condition.")";
		$criteria->params 		= array(':x'=>$_REQUEST['employee_id'], ':weekday_status'=>1);
		$criteria->order 		= "STR_TO_DATE(`t`.`start_time`, '%h:%i %p')";
		$criteria->distinct		= true;
		$timings 				= ClassTimings::model()->findAll($criteria);
		
?>

<?php	if(isset($weekday_id) and $weekday_id == 0){
				$width = '100%';
				$align = 'center';
			}
			else{
				$width = '10%';
				$align = 'left';
			}
?>

<table border="0" align="<?php echo $align; ?>" width="<?php echo $width; ?>" id="table" cellspacing="0" class="timetable-pdf">   
	<tr >
			<?php
					echo '<th width="50"><div style="width:50px;">&nbsp;</div></th>';
					$weekday_count	= 0;
					foreach($weekdays as $weekday){														
						if($weekday['weekday']!=0){
							echo '<th>'.$weekday_text[$weekday['weekday']-1].'</th>';
							$weekday_count++;
						}
					}
				?>          
	</tr>            
	<tr>
		<?php
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
				
				$proportion		= 1.3;
				foreach($time_intervals as $index=>$time_interval){																
					if(($index+1)==count($time_intervals)){	// last timing
						$to_time 		= strtotime(date("h:i A", strtotime($last_timing->end_time)));
						$from_time 		= strtotime(date("h:i A", strtotime($time_interval)));
						$diff_minutes	= round(abs($to_time - $from_time) / 60,2);
						echo '<tr><td width="70px" height="'.( $diff_minutes * $proportion ).'" style="position:relative;" valign="top">';
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
						echo '<tr><td width="70px" height="'.( $diff_minutes * $proportion ).'" style="position:relative;" valign="top">';
						if($total_minutes%$time_span==0)
							echo '<div  class="time-box">'.$time_interval.'</div>';
							
						echo '</td></tr>';														
					}
				}
																
				echo '</table></td>';
		$settings     			= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
		foreach($weekdays as $weekday){
			if($weekday['weekday']!=0){
			?>
			<td class="td" valign="top">
				<?php
				$weekday_condition		= "`t`.`".$weekday_attributes[$weekday->weekday]."`=:week_day_status";
									$criteria				= new CDbCriteria;
									$criteria->join 		= "JOIN `timetable_entries` `te` ON `te`.`class_timing_id` = `t`.`id`";
									$criteria->condition 	= "`te`.`weekday_id`=:week AND `te`.`employee_id`=:x AND ".$weekday_condition;
									$criteria->params 		= array(':week'=>$weekday->weekday, ':x'=>$employee->id, ':week_day_status'=>1);
									$criteria->order 		= "STR_TO_DATE(`t`.`start_time`, '%h:%i %p')";
									$criteria->group		= "`t`.`start_time`, `t`.`end_time`";
									$criteria->distinct		= true;
									$timings 				= ClassTimings::model()->findAll($criteria);	
				?>
				<table width="100%" id="table" border="0" cellspacing="0" cellpadding="0" class="timetable-pdf1">
					<?php
							$from_time	= $time_intervals[0];
							if($timings==NULL){
							?>
                            <tr><td width="250px" style="border:none;">&nbsp;</td></tr>
                            <?php
							}
							else{
								foreach($timings as $i=>$timing){
									if($settings!=NULL){	
										$time1=date($settings->timeformat,strtotime($timing->start_time));
										$time2=date($settings->timeformat,strtotime($timing->end_time));
									}
									
									//find height start
									$to_time		= $timing->start_time;																			
									$to_time 		= strtotime($to_time);
									$from_time 		= strtotime($from_time);
									
									$diff_minutes	= round(abs($to_time - $from_time) / 60,2);
									
									if($diff_minutes>0){
										echo '<tr><td style="" height="'.( $diff_minutes * $proportion ).'" valign="top"></td></tr>';
									}
									
									$from_time 		= $timing->end_time;
									
									$timing_diff_minutes	= round(abs(strtotime($timing->end_time) - strtotime($timing->start_time)) / 60,2);
									//find height end
								?>
									<tr>
										<td class="td1" width="250px" height="<?php echo ( $timing_diff_minutes * $proportion );?>">
										<div class="timtable-inner"><!------------timtable-inner---------------->
										<?php 
											echo $timing->start_time.' - '.$timing->end_time.'<br />';
							
								$conditions	= array('weekday_id'=>$weekday->weekday,'class_timing_id'=>$timing->id);
																
																$set 		= TimetableEntries::model()->findByAttributes($conditions); 
																if($set != NULL){
																	if($set->is_elective==0){
																		$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
																		if($time_sub!=NULL){
																			echo $time_sub->name.'<br>';
																			
																		}
																	}
																	else{ 
																		$elec_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
																		$electname = ElectiveGroups::model()->findByAttributes(array('id'=>$elec_sub->elective_group_id,'batch_id'=>$elec_sub->batch_id));	
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
                            								</div>
														</div>
													</div>
                                                </td>
                                            </tr>
										<?php
                                            }
							}
                                        ?>
                                    </table>
                                </td>
                                <?php
                                }
                            }
                            ?>
                        </tr>
                    </table>		
                   
