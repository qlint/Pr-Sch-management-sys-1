<?php
$this->breadcrumbs=array(
	Yii::t('app','Timetable')=>array('/timetable'),
	Yii::t('app','Weekdays'),
);

//
/*function getColor($num) {
    $hash = md5('color' . $num); // modify 'color' to get a different palette
    return "#".hexdec(substr($hash, 0, 2)). // r
        hexdec(substr($hash, 2, 1)). // g
        hexdec(substr($hash, 4, 2));	 //b
}*/
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui-style.css" />
<style>
.timetable{
	width:712px;
	overflow-x:auto;
	overflow-y: hidden;
}
.timetable table{
	overflow-x:auto;
}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/default/left_side');?>
        </td>
        <td valign="top">
        	<div class="cont_right formWrapper">
                <div class="clear"></div>
                <div class="emp_right_contner">
                    <div class="emp_tabwrapper">
                    
						<?php $this->renderPartial('/default/tab');?>                        
                        <div class="clear"></div>
                        <div class="emp_cntntbx" style="padding-top:20px;">
							<?php
                            $current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
                            if(Yii::app()->user->year)
                            	$year = Yii::app()->user->year;
                            else
                            	$year = $current_academic_yr->config_value;
                            $is_insert = PreviousYearSettings::model()->findByAttributes(array('id'=>2));
                            $is_delete = PreviousYearSettings::model()->findByAttributes(array('id'=>4));
                            $yes_insert = 0;
                            $yes_delete = 0;
                            if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))
                            	$yes_insert = 1;
                            
                            if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
                            	$yes_delete = 1;
                            
                            if($year != $current_academic_yr->config_value and ($is_insert->settings_value==0 or $is_delete->settings_value==0)){
							?>                        
                            <div>
                                <div class="yellow_bx" style="background-image:none;width:95%;padding-bottom:45px; margin-bottom:25px;">
                                    <div class="y_bx_head" style="width:95%;">
                                    <?php 
                                        echo Yii::t('app','You are not viewing the current active year. ');
                                        if($is_insert->settings_value==0 and $is_delete->settings_value!=0)
                                        { 
                                            echo Yii::t('app','To assign a subject, enable Insert option in Previous Academic Year Settings.');
                                        }
                                        elseif($is_insert->settings_value!=0 and $is_delete->settings_value==0)
                                        {
                                            echo Yii::t('app','To remove a subject, enable Delete option in Previous Academic Year Settings.');
                                        }
                                        else
                                        {
                                            echo Yii::t('app','To manage the timetable, enable the required options in Previous Academic Year Settings.');	
                                        }
                                    ?>
                                    </div>
                                    <div class="y_bx_list" style="width:95%;">
                                        <h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
                                    </div>
                                </div>
                            </div>
							<?php	
                            }
							?>
                            
                            <div class="button-bg">
                            <div class="top-hed-btn-right">
                                    <ul>                                    
                                        <li>
                                            <?php echo CHtml::link('<span>'.Yii::t('app','Set Week Days').'</span>',array('/timetable/weekdays','id'=>$_REQUEST['id']),array('class'=>'a_tag-btn'));?>
                                        </li>
                                        <li>
                                            <?php echo CHtml::link('<span>'.Yii::t('app','Set Class Timings').'</span>',array('/timetable/classTiming','id'=>$_REQUEST['id']),array('class'=>'a_tag-btn'));?>
                                        </li>                                    
                                    </ul>
                                </div> 
                                <div class="top-hed-btn-left">
                                    <?php
                                    
                                    $timing 	= ClassTimings::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id']));
									// Display pdf button only if there is class timings.
                                    if($timing!=NULL){
                                    echo CHtml::link(Yii::t('app','Generate PDF'), array('Weekdays/pdf','id'=>$_REQUEST['id']),array('class'=>'a-tag-pdf-btn','target'=>'_blank')); 
                                    }
                                    ?>   
                                </div>
                                
                            </div>                         

                            
                            
                                                    
                            <div  style="width:100%">                            
                                <div class="Nodata-bg">                                
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
															$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
															
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
																					if($yes_insert==1)
																					{
																				  echo CHtml::ajaxLink(Yii::t('app','Assign'),$this->createUrl('TimetableEntries/settime'),array('onclick'=>'$("#jobDialog'.$timing->id.$weekday['weekday'].'").dialog("open"); return false;',														'update'=>'#jobDialog'.$timing->id.$weekday['weekday'],'type' =>'GET','data'=>array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekday['weekday'],'class_timing_id'=>$timing->id),'dataType'=>'text',),array('id'=>'showJobDialog'.$timing->id.$weekday['weekday'],'class'=>'remove-form')) ;
																					}
																					else
																					{
																						echo CHtml::link('<span>'.Yii::t('app','Assign').'</span>', array('#'),array('class'=>'addbttn last','onclick'=>'alert("'.Yii::t('app','Enable Insert Option in Previous Academic Year Settings').'"); return false;'));
																					}
																					
																					
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
																					
																					//$is_teacher_exist = EmployeesSubjects::model()->findAllByAttributes(array('subject_id'=>$time_sub->id));
																					if($time_sub!=NULL)
																					{
																						echo $name_sub.'<br>';
																						
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
																					
																					/*$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
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
																							if($time_sub!=NULL)
																							{
																								//echo '<div class="employee">'.ucfirst($time_emp->first_name).' '.ucfirst($time_emp->middle_name).' '.ucfirst($time_emp->last_name).'</div>';
																							}
																						}
																					}*/
																					
																					
																				}
																				
																				if($yes_delete == 1)
																				{
																				//echo CHtml::link('',array('timetableEntries/remove','id'=>$set->id,'batch_id'=>$_REQUEST['id']),array('confirm'=>Yii::t('app','Are you sure?'),'class'=>'delete'));
																				echo CHtml::link('', "#", array('submit'=>array('timetableEntries/remove','id'=>$set->id,'batch_id'=>$_REQUEST['id'],), 'confirm'=>Yii::t('app','Are you sure?'), 'csrf'=>true,'class'=>'delete')); 
																				
																				}
																				if($yes_insert == 1)
																				{?>
																				
																				<?php
																				
																				 echo CHtml::ajaxLink('',$this->createUrl('TimetableEntries/updatetime'),array('onclick'=>'$("#jobDialogupdate'.$timing->id.$weekday['weekday'].'").dialog("open"); return false;',														'update'=>'#jobDialogupdate'.$timing->id.$weekday['weekday'],'type' =>'GET','data'=>array('id'=>$set->id,'batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekday['weekday'],'class_timing_id'=>$timing->id),'dataType'=>'text',),array('id'=>'showJobDialogupdate'.$timing->id.$weekday['weekday'],'class'=>'edit')) ;
																				}
																			}
																			?>
																			<?php echo 	'</div>
																			</div>
																			</div>
																			<div id="jobDialog'.$timing->id.$weekday['weekday'].'"></div>
																			<div id="jobDialogupdate'.$timing->id.$weekday['weekday'].'"></div>
																			
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
                                </div>                            
                            </div>                           
                        </div>
                    </div>                
                </div>
                <?php
                $batch = Weekdays::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id']));
                if(count($batch)==0)
                    $batch = Weekdays::model()->findAll("batch_id IS NULL");
                ?>
            </div>
        </td>
    </tr>
</table>
<script>
$('.remove-form,.ui-icon-closethick').click(function(){


	$('#timetable-entries-form').remove();	
});
</script>