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
.timetable-pdf1 td{
	text-align:center;
	/*border:1px solid #ccc;*/
	font-size:12px;
		  
		   
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
    border: 1px solid #000000 !important;
}
.td1 {
    border: 1px solid #000000;
	background-color: #EAEAEA;
}
.timtable-inner{
	 font-size:13px;	
}
.table_holiday{
        background-color: #ff425454;
    vertical-align: middle;
    text-align: center;
    font-size: 15px;
    color: #f76472;
    font-weight: 600;
}
.table_teacher_swch{
   
        background-color: #28a74521;
    text-align: center;
    font-size: 15px;
    font-weight: 600;
}
.table_tmtbl_cancel{
    background-color: #d8d8d8;
    text-align: center;
    font-size: 15px;
    font-weight: 600;
}
</style>
<?php
if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL){
	//Getting dates in a week
	$date			= (isset($_REQUEST['date']))?$_REQUEST['date']:date("Y-m-d");
	$day 			= date('w', strtotime($date));
	$week_start		= date('Y-m-d', strtotime('-'.$day.' days', strtotime($date)));
	$week_end 		= date('Y-m-d', strtotime('+'.(6-$day).' days', strtotime($date)));
	$date_between 	= array();
	$begin 			= new DateTime($week_start);
	$end 			= new DateTime($week_end);
	$daterange 		= new DatePeriod($begin, new DateInterval('P1D'), $end);
	
	
	foreach($daterange as $date){
		$date_between[] = $date->format("Y-m-d");
	}
	if(!in_array($week_end,$date_between)){
		$date_between[] = date('Y-m-d',strtotime($week_end));
	}   
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
                        <td class="listbxtop_hdng first" style=" font-size:10px;" >
                            <?php $college=Configurations::model()->findAll(); ?>
                            <?php echo $college[0]->config_value; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="listbxtop_hdng first"style=" font-size:10px;"  >
                            <?php echo $college[1]->config_value; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="listbxtop_hdng first"style=" font-size:10px;" >
                            <?php echo 'Phone: '.$college[2]->config_value; ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
	<hr />
    <?php
		$batch 			= Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$student		= Students::model()->findByPk($_REQUEST['sid']); 
		$course_name 	= Courses::model()->findByAttributes(array('id'=>$batch->course_id));
		$class_teacher 	= Employees::model()->findByAttributes(array('id'=>$batch->employee_id));
     ?>
    <div align="center" style="display:block; text-align:center;"><?php echo Yii::t('app','CLASS TIME TABLE');?> - <?php echo html_entity_decode(ucfirst($course_name->course_name)); ?> (<?php echo html_entity_decode(ucfirst($batch->name)); ?>)</div>
	<?php if($flag == 1){ ?>
    	<div align="center" style="display:block; font-weight:bold; font-size:10px; text-align:center;"><?php echo date("M d", strtotime($week_start))." - ".date("M d", strtotime($week_end));?></div>
    <?php } ?>   
    <table style="font-size:14px; background:#DCE6F1; padding:10px 10px;border:#C5CED9 1px solid;">
            <?php $batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
                  $course_name = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
				  $class_teacher = Employees::model()->findByAttributes(array('id'=>$batch->employee_id));
				  $semester_enabled	= Configurations::model()->isSemesterEnabled(); 
				  $sem_enabled	= Configurations::model()->isSemesterEnabledForCourse($course_name->id); 
            
            if(FormFields::model()->isVisible('batch_id', 'Students', "forStudentPortal")){
            ?>
            <tr>
                <td style="width:130px;"><?php echo Yii::t('app','Course');?></td>
                <td style="width:10px;">:</td>
                <td style="width:550px;"><?php echo $course_name->course_name; ?></td>
            
                <td  style="width:130px;"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></td>
                <td style="width:10px;">:</td>
                <td><?php echo $batch->name; ?></td>
            </tr>
            <?php
			}
            ?>
            <tr>
                <td style="width:130px;"><?php echo Yii::t('app','Class Teacher');?></td>
                <td style="width:10px;">:</td>
                <td style="width:550px;">
					<?php 
					if($class_teacher!=NULL)
					{
						echo Employees::model()->getTeachername($class_teacher->id);
					}
					else
					{
						echo '-';
					}
					?>
				</td>
   				<?php
				$total_students = BatchStudents::model()->BatchStudent($batch->id); ;
				?>
                <td style="width:130px;"><?php echo Yii::t('app','Total students');?></td>
                <td style="width:10px;">:</td>
                <td width="195"><?php echo count($total_students); ?></td>
            </tr>
			<?php if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id!=NULL){ 
						$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); ?> 
			<tr>
                <td style="width:130px;"><?php echo Yii::t('app','Semester');?></td>
                <td style="width:10px;">:</td>
                <td style="width:550px;">
					<?php echo ucfirst($semester->name);?>
				</td>
            </tr>
          <?php } ?> 
        </table>

    
	<?php    
    $times=Batches::model()->findAll("id=:x", array(':x'=>$_REQUEST['id']));
    $weekdays=Weekdays::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id']));
    if(count($weekdays)==0)
    	$weekdays=Weekdays::model()->findAll("batch_id IS NULL");
    $criteria=new CDbCriteria;
    $criteria->condition = "batch_id=:x";
    $criteria->params = array(':x'=>$_REQUEST['id']);
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
    	<table border="0"  width="100%" id="table" cellspacing="0" cellpadding="0" class="timetable-pdf">
            <tr>
                <?php
					echo '<th width="50"><div style="width:50px;">&nbsp;</div></th>';
					$weekday_count	= 0;
					foreach($weekdays as $weekday){														
						if($weekday['weekday']!=0){ 
							if($flag == 1){ //In case of weekly changing
								echo '<th height="20">'.$weekday_text[$weekday['weekday']-1].'<p>'.date("d M Y", strtotime($date_between[$weekday['weekday']-1])).'</p></th>';
							}
							else{
								echo '<th height="18">'.$weekday_text[$weekday['weekday']-1].'</th>';
							}
														
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
				
				$weekday_attributes	= array(1=>'on_sunday',2=>'on_monday',3=>'on_tuesday',4=>'on_wednesday',5=>'on_thursday',6=>'on_friday',7=>'on_saturday');
				$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
				if($settings==NULL)
					$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
				foreach($weekdays as $weekday){
					if($weekday['weekday']!=0){
						$holiday_class	= '';
						$holiday_flag	= 0;
						if($flag == 1){
							$is_holiday 	= Configurations::model()->isHoliday($date_between[$weekday['weekday']-1]);																
							if($is_holiday == 1){	
								$holiday_flag	= 1;
								$holiday_class	= 'table_holiday';
							}
						}
						
					?>
                    <td class="td <?php echo $holiday_class; ?>" valign="top">
						<?php
						if($holiday_flag == 0){
							$weekday_condition		= "`".$weekday_attributes[$weekday->weekday]."`=:week_day_status";
							$criteria				= new CDbCriteria;
							$criteria->condition 	= "batch_id=:x AND ".$weekday_condition;
							$criteria->params 		= array(':x'=>$_REQUEST['id'], ':week_day_status'=>1);
							$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
							$timings 				= ClassTimings::model()->findAll($criteria);
						?>
							<table width="100%" id="table" border="0" cellspacing="0" cellpadding="0" class="timetable-pdf1 timetable-pdf2">
								<?php
								$from_time	= $time_intervals[0];
								if($timings==NULL){
								?>
								<tr><td width="250px">&nbsp;</td></tr>
								<?php
								}
								else{
									foreach($timings as $i=>$timing){
										$class	= '';
										if($flag == 1){
											$set =  TimetableEntriesChanging::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'class_timing_id'=>$timing->id, 'date'=>$date_between[$weekday['weekday']-1])); 
											/*if($set != NULL){ 
												if($set->status == 2){ //In case of teacher switching
													$class	= 'table_teacher_swch';
												}
												if($set->status == 1){ //In case of cancellation
													$class	= 'table_tmtbl_cancel';
												}
											}*/
										}
										else{
											$set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekday->weekday,'class_timing_id'=>$timing->id)); 			
										}
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
											<td class="td1 <?php echo $class; ?>" width="250px" height="<?php echo ( $timing_diff_minutes * $proportion );?>">
											<div class="timtable-inner"><!------------timtable-inner---------------->
											<?php 
												echo $timing->start_time.' - '.$timing->end_time.'<br />';											   
											   
												if(count($set)==0){		
													$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing->id,'is_break'=>1));
													if($is_break!=NULL){	
														echo  Yii::t('app','Break');	
													}	
												}
												else{
													$subject_name	= '';
													$employee_name	= '';
													if($set->is_elective == 0){
														$subject_name	= TimetableEntries::model()->getSubjectName($set->id);
														$employee_name	= ' - '.TimetableEntries::model()->getEmployeeName($set->id);		
													}
													else{
														$elective_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
														if($elective_sub){
															$is_exist_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$batch->id, 'elective_group_id'=>$elective_sub->elective_group_id));
															if($is_exist_elective){
																$subject_name	= TimetableEntries::model()->getSubjectName($set->id);
																$employee_name	= '';
															}
														}
													}
											?>
                                            		<span><?php echo $subject_name.$employee_name; ?></span>												
											<?php		
													/*if($set->status == 1){ //In case of cancelled situation
														echo '<p style="color:#F00;">'.Yii::t('app', 'Cancelled').'</p>';
													}*/
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
                    	<?php 
						}
						else{
							echo '<span style="color:#000;">'.Yii::t('app', 'Holiday').'</span>';
						}
						
						 ?>        
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
    	echo  '<i>'.Yii::t('app','No Class Timings is set for this').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</i>';
    }    
}
?>