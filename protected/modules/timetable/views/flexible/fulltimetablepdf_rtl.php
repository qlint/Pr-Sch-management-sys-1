<style>
.timetable-pdf {
	 border-collapse:collapse;
	 border:1px solid #ccc;
	 margin-top:8px;
	}
.timetable-pdf  th{
	text-align:center;
	border:1px solid #ccc;
	background-color:#CCC;
	 font-size:13px;   
	}
.timetable-day {
	 border-collapse:collapse;
	 border:1px solid #ccc;
	 margin-top:8px ;
	}
.timetable-day  th{
	text-align:center;
	border:1px solid #ccc;
	background-color:#CCC !important;
	 font-size:8px !important;   
	}	
.timetable-pdf1 td{
	text-align:center;
		  
		   
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
 .time-box-day {
    top: 0px;
    text-align: center;
    background-color: #c5e2f1;
    right: 0px;
    font-family: "Open Sans", sans-serif;
    font-size: 8px;
    font-weight: 400;
    line-height: 19px;
    color: #0c5f5f;
    border-top: 2px solid #e8b730;

}
.td1 {
    border: 1px solid #000000 !important;
}
.td1 {
    border: 1px solid #000000;
	background-color: #EAEAEA;
}
.timtable-inner-day{
	 font-size:8px !important;	
}
.timtable-inner-week{
	 font-size:13px !important;	
}	
</style>

<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td class="first" width="100">
	<?php $logo=Logo::model()->findAll();?>
      <?php
		if($logo!=NULL)
		{
			//Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
			echo '<img src="uploadedfiles/school_logo/'.$logo[0]->photo_file_name.'" alt="'.$logo[0]->photo_file_name.'" class="imgbrder" height="60" />';
		}
		?></td>
    <td valign="middle" >
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="listbxtop_hdng first">
		  	<?php $college=Configurations::model()->findAll(); ?>
            <?php echo $college[0]->config_value; ?></td>
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
      </table></td>
  </tr>
</table>
<hr />
<br />

<!-- End Header -->

 <?php 
  $batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));			
  $course_name = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
  //$class_teacher = Employees::model()->findByAttributes(array('id'=>$batch->employee_id));
            ?>

 <div align="center" style="display:block; text-align:center;"><?php echo Yii::t('app','CLASS TIME TABLE');?> - <?php echo $course_name->course_name; ?> (<?php echo $batch->name; ?>)</div>
 
<?php
if(isset($_REQUEST['mode']) and $_REQUEST['mode']==1)
{ 
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

	$times=Batches::model()->findAll("id=:x", array(':x'=>$_REQUEST['id']));
	$weekdays=Weekdays::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id']));
	if(count($weekdays)==0)
		$weekdays=Weekdays::model()->findAll("batch_id IS NULL");	
	$criteria=new CDbCriteria;
	$criteria->condition = "batch_id=:x";
	$criteria->params = array(':x'=>$_REQUEST['id']);
	$criteria->order = "STR_TO_DATE(start_time, '%h:%i %p')";    
	$timing = ClassTimings::model()->findAll($criteria);
	$count_timing = count($timing);	
	if(isset($timing) and $timing!=NULL)
	{
		$sun = Yii::t('app','SUN');
		$mon = Yii::t('app','MON');
		$tue = Yii::t('app','TUE');
		$wed = Yii::t('app','WED');
		$thu = Yii::t('app','THU');
		$fri = Yii::t('app','FRI');
		$sat = Yii::t('app','SAT');
		$weekday_text = array($sun, $mon, $tue, $wed, $thu, $fri, $sat);
    ?>
    

	<table border="0"  width="100%" id="table" cellspacing="0" class="timetable-pdf timetable-week">
        <tr>
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
				$first_timing		= $timing[0];
				$last_timing		= end($timing);
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
					$time_in		=	Configurations::model()->convertTime($time_interval); 														
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
							echo '<div  class="time-box">'.$time_in.'</div>';
							
						echo '</td></tr>';														
					}
				}
														
				echo '</table></td>';
			
				$weekday_attributes	= array(1=>'on_sunday',2=>'on_monday',3=>'on_tuesday',4=>'on_wednesday',5=>'on_thursday',6=>'on_friday',7=>'on_saturday');
				$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
				if($settings==NULL)
					$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
				foreach($weekdays as $weekday){
					if($weekday['weekday']!=0){
					?>                    
                    <td class="td" valign="top">
                    	<?php
							$weekday_condition		= "`".$weekday_attributes[$weekday->weekday]."`=:week_day_status";
							$criteria				= new CDbCriteria;
							$criteria->condition 	= "batch_id=:x AND ".$weekday_condition;
							$criteria->params 		= array(':x'=>$_REQUEST['id'], ':week_day_status'=>1);
							$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
							$timings 				= ClassTimings::model()->findAll($criteria);
						?>
                       <table width="100%" id="table" border="0" cellspacing="0" cellpadding="0" class="timetable-pdf1 ">
							<?php
							$from_time	= $time_intervals[0];
							if($timings==NULL){
							?>
                            <tr><td width="250px">&nbsp;</td></tr>
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
										<td class="td1" width="250px"   height="<?php echo ( $timing_diff_minutes * $proportion );?>">
										<div class="timtable-inner-week"><!------------timtable-inner---------------->
										<?php 
										
											$start		=	Configurations::model()->convertTime($timing->start_time);
											$end		=	Configurations::model()->convertTime($timing->end_time);
											echo $start.' - '.$end.'<br />';
											//echo $timing->start_time.' - '.$timing->end_time.'<br />';
											
                                        	$set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekday->weekday,'class_timing_id'=>$timing->id)); 	
												
											if(count($set)==0){		
												$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing->id,'is_break'=>1));
												if($is_break!=NULL){	
													echo  Yii::t('app','Break');	
												}	
											}
											elseif($set->is_elective ==0){
												$time_sub   = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
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
															//echo '<span >(' .$employee->first_name.')</span>';
															 echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';
																									
														}
														else{
															//echo '<span >(' .$time_emp->first_name.')</span>';
															 echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';
														}							
														echo '<br />';		
													}
												}
												else{
													echo '-<br>';
												}
											}
											else{
												$elec_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
												$electname = ElectiveGroups::model()->findByAttributes(array('id'=>$elec_sub->elective_group_id,'batch_id'=>$_REQUEST['id']));        
												if($electname!=NULL){
													echo $electname->name.'<br>';
												}
											}
										?>
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
    <?php
	}
	else{
    ?>
        <?php echo  '<i>'.Yii::t('app','No Class Timings is set for this').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</i>'; ?>
        <?php
    }
}




else if(isset($_REQUEST['mode']) and $_REQUEST['mode']==2){
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

	$times=Batches::model()->findAll("id=:x", array(':x'=>$_REQUEST['id']));
	
	$weekdays = Weekdays::model()->findAll("batch_id=:x AND weekday=:y",array(':y'=>$_REQUEST['day'], ':x'=>$batch_id)); // Selecting weekdays of the batch
    if(count($weekdays) == 0){
        $weekdays = Weekdays::model()->findAll("batch_id IS NULL AND weekday=:y",array(':y'=>$_REQUEST['day']));
    }
	
	$weekday_attributes		= array(1=>'on_sunday',2=>'on_monday',3=>'on_tuesday',4=>'on_wednesday',5=>'on_thursday',6=>'on_friday',7=>'on_saturday');	
	$weekday_condition		= " AND `".$weekday_attributes[$_REQUEST['day']]."`=:weekday_status";
		
	$criteria=new CDbCriteria;
	$criteria->condition = "batch_id=:x".$weekday_condition;
	$criteria->params = array(':x'=>$_REQUEST['id'], ':weekday_status'=>1);
	$criteria->order = "STR_TO_DATE(start_time, '%h:%i %p')";    
	$timing = ClassTimings::model()->findAll($criteria);
	$count_timing = count($timing);	
	if(isset($timing) and $timing!=NULL)
	{
		$sun = Yii::t('app','SUN');
		$mon = Yii::t('app','MON');
		$tue = Yii::t('app','TUE');
		$wed = Yii::t('app','WED');
		$thu = Yii::t('app','THU');
		$fri = Yii::t('app','FRI');
		$sat = Yii::t('app','SAT');
		$weekday_text = array($sun, $mon, $tue, $wed, $thu, $fri, $sat);
    ?>
    

	<table border="0"  width="50" id="table" cellspacing="0" class=" timetable-day">
        <tr>
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
				$first_timing		= $timing[0];
				$last_timing		= end($timing);
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
				$time_in		=	Configurations::model()->convertTime($time_interval); 																	
					if(($index+1)==count($time_intervals)){	// last timing
						$to_time 		= strtotime(date("h:i A", strtotime($last_timing->end_time)));
						$from_time 		= strtotime(date("h:i A", strtotime($time_interval)));
						$diff_minutes	= round(abs($to_time - $from_time) / 60,2);
						echo '<tr><td width="40px" height="'.( $diff_minutes * $proportion ).'" style="position:relative; font-size:8px;" valign="top">';
						echo '<div  class="time-box-day">'.$time_interval.'</div>';																		
						echo '</td></tr>';
					}
					else{
						//calculate timespan diff
						$hours		= date("h", strtotime($time_interval));
						$minutes	= date("i", strtotime($time_interval));																
						$total_minutes	= ($hours*60) + $minutes;																																
						$diff			= $total_minutes%$time_span;
						$diff_minutes	= $time_span - $diff;
						echo '<tr><td width="40px" height="'.( $diff_minutes * $proportion ).'" style="position:relative; " valign="top">';
						if($total_minutes%$time_span==0)
							echo '<div  class="time-box-day">'.$time_in.'</div>';
							
						echo '</td></tr>';														
					}
				}
																
				echo '</table></td>';
			
				$weekday_attributes	= array(1=>'on_sunday',2=>'on_monday',3=>'on_tuesday',4=>'on_wednesday',5=>'on_thursday',6=>'on_friday',7=>'on_saturday');
				$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
				if($settings==NULL)
					$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
				foreach($weekdays as $weekday){
					if($weekday['weekday']!=0){
					?>                    
                    <td class="td" valign="top">
                    	<?php
							$weekday_condition		= "`".$weekday_attributes[$weekday->weekday]."`=:week_day_status";
							$criteria				= new CDbCriteria;
							$criteria->condition 	= "batch_id=:x AND ".$weekday_condition;
							$criteria->params 		= array(':x'=>$_REQUEST['id'], ':week_day_status'=>1);
							$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
							$timings 				= ClassTimings::model()->findAll($criteria);
						?>
                       <table width="100%" id="table" border="0" cellspacing="0" cellpadding="0" class="timetable-pdf1 ">
							<?php
							$from_time	= $time_intervals[0];
							if($timings==NULL){
							?>
                            <tr><td width="250px">&nbsp;</td></tr>
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
										<td class="td1" width="250px"   height="<?php echo ( $timing_diff_minutes * $proportion );?>">
										<div class="timtable-inner-day"><!------------timtable-inner---------------->
										<?php 
											$start		=	Configurations::model()->convertTime($timing->start_time);
											$end		=	Configurations::model()->convertTime($timing->end_time);
											echo $start.' - '.$end.'<br />';
											//echo $timing->start_time.' - '.$timing->end_time.'<br />';
                                        	$set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekday->weekday,'class_timing_id'=>$timing->id)); 			
											if(count($set)==0){		
												$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing->id,'is_break'=>1));
												if($is_break!=NULL){	
													echo  Yii::t('app','Break');	
												}	
											}
											elseif($set->is_elective ==0){
												$time_sub   = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
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
															//echo '<span >(' .$employee->first_name.')</span>';
															echo '<div class="employee">'.$employee->first_name.' '.$employee->middle_name.$employee->last_name.'</div>';										
														}
														else{
															//echo '<span >(' .$time_emp->first_name.')</span>';
															echo '<div class="employee">'.$time_emp->first_name.' '.$time_emp->middle_name.$time_emp->last_name.'</div>';										
															
														}							
														echo '<br />';		
													}  
												}
												else{
													echo '-<br>';
												}
											}
											else{
												$elec_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
												$electname = ElectiveGroups::model()->findByAttributes(array('id'=>$elec_sub->elective_group_id,'batch_id'=>$_REQUEST['id']));        
												if($electname!=NULL){
													echo $electname->name.'<br>';
												}
											}
										?>
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
    <?php
	}
	else{
    ?>
        <?php echo  '<i>'.Yii::t('app','No Class Timings is set for this').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</i>'; ?>
        <?php
    }
}
?>