<style>
.table-responsive {
    border: 1px solid #ddd;
    margin-bottom: 15px;
    overflow-x: scroll;
    overflow-y: hidden;
    width: 100%;
}
.people-item{
	font-size: 14px;
}
</style>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css">
<div class="contentpanel">
    <div class="people-item"> 
     <h3 class="panel-title">Timetable</h3>
<?php
		$settings		= UserSettings::model()->findByAttributes(array('user_id'=>1));
		$date			= (isset($_REQUEST['date']))?$_REQUEST['date']:date("Y-m-d");		
		$day 			= date('w', strtotime($date));		
		$week_start		= date('Y-m-d', strtotime('-'.$day.' days', strtotime($date)));		
		$week_end 		= date('Y-m-d', strtotime('+'.(6-$day).' days', strtotime($date)));		
		$date_between 	= array();		
		$begin 			= new DateTime($week_start);		
		$end 			= new DateTime($week_end);
		
		$prev_week_start	= date('Y-m-d', strtotime('-7 days', strtotime($week_start)));		
		$next_week_start	= date('Y-m-d', strtotime('+1 days', strtotime($week_end)));		
		$this_date			= $week_start;		
		$daterange 			= new DatePeriod($begin, new DateInterval('P1D'), $end);
		
		foreach($daterange as $value){		
			$date_between[] = $value->format("Y-m-d");
		}
		
		if(!in_array($week_end,$date_between)){		
			$date_between[] = date('Y-m-d',strtotime($week_end));
		}
		//Batch
		if(isset($_REQUEST['bid']) and $_REQUEST['bid'] != NULL){
			$batch	= Batches::model()->findByPk($_REQUEST['bid']);
		}
		else{
			$batch	= Batches::model()->findByPk($batches[0]['id']);
		}
		
		$weekdays	= Weekdays::model()->findAll("batch_id=:x", array(':x'=>$batch->id));										
        if(count($weekdays) == 0){
        	$weekdays	= Weekdays::model()->findAll("batch_id IS NULL");		
		}
				
		//Weekday Text
		$weekday_text	= array(Yii::t('app','SUN'), Yii::t('app','MON'), Yii::t('app','TUE'), Yii::t('app','WED'), Yii::t('app','THU'), Yii::t('app','FRI'), Yii::t('app','SAT'));
		
		$criteria				= new CDbCriteria;		
		$criteria->condition 	= "batch_id=:x";		
		$criteria->params 		= array(':x'=>$batch->id);		
		$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  		
		$timings 				= ClassTimings::model()->findAll($criteria);
		$batch_arr	= array();
		foreach($batches as $value){
			$course    				= 	Courses::model()->findByAttributes(array('id'=>$value->course_id)); 
			$batch_arr[$value->id]	= 	ucfirst($value->name).' ( '.ucfirst($course->course_name).' )';
		}
?>		 
        <div class="opnsl_headerBox">
            <div class="opnsl_actn_box">
                <div class="opnsl_actn_box1">
                	<?php
                	 echo Yii::t('app','Viewing Timetable of').' '.Students::model()->getAttributeLabel('batch_id');
					  echo CHtml::dropDownList('batch_id','',$batch_arr,array('encode'=>false,'id'=>'batch_id','style'=>'width:100%;display: inline;','class'=>'input-form-control','options'=>array($batch->id=>array('selected'=>true)),'onchange'=>'getmode();'));
						
					?>
                </div>
                </div>
                <div class="opnsl_actn_box">
                	<?php
						if($timings != NULL){
									
								echo CHtml::link(Yii::t('app','Generate PDF'), array('/studentportal/default/pdf','id'=>$batch->id, 'sid'=>$student->id, 'date'=>$date, 'type'=>1),array('class'=>'btn btn-danger','target'=>'_blank'));
							
						}
					?>
                </div>		     
        	</div> 
        	<?php
                  $course_name = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
				  $semester_enabled	= Configurations::model()->isSemesterEnabled(); 
				  $sem_enabled	= Configurations::model()->isSemesterEnabledForCourse($course_name->id); 
        	?>
            <div class="row">
                <div class="col-md-12">
                        <?php if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id!=NULL){ 
                        $semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));?>
                    <div class="sem_dtls">
                        <p><?php echo Yii::t('app','Semester:');?>
                        <span> <?php echo ucfirst($semester->name);?></span></p>
                    </div>
                        <?php } ?>
                </div>
            </div>
            
<?php				
		if($timings != NULL){				
?>						
			<div class="table-responsive">
            	<table border="0" align="center" width="90%" id="table" cellspacing="0" class="table table-bordered mb30 teachr-timetble">
                	<tbody>
                    	<tr>
                        	<th width="60"><div style="width:60px;">&nbsp;</div></th>
<?php							                                                        
                            	foreach($weekdays as $weekday){
									if($weekday['weekday'] != 0){
?>                            
                           	 			<th class="attend-th">
                                        	<div class="top"><?php echo $weekday_text[$weekday['weekday']-1]; ?></div>
											<?php 
												/*if($timetable_type == 1){
													echo date("d M Y", strtotime($date_between[$weekday['weekday']-1])); 
												}*/
											?>
                                        </th>
<?php                            
									}
								}
?>                                                      
                        </tr>
                        <tr>
                        	<td valign="top">                            
                            	<table class="timetable-br-time" width="100%" border="0" cellspacing="0" cellpadding="0">
<?php
									$time_intervals	= array();									
									$first_timing	= $timings[0];									
									$last_timing	= end($timings);									
									$time_span		= 30; // in minutes
									
									$calendar_start_time	= strtotime(date("h:i A", strtotime($first_timing->start_time)));									
									$calendar_end_time		= strtotime(date("h:i A", strtotime($last_timing->end_time)));									
									$calendar_time			= $calendar_start_time;									
									while($calendar_time < $calendar_end_time){									
										$time_intervals[]	= date("h:i A", $calendar_time);
										
										//calculate timespan diff
										$hours			= date("h", $calendar_time);										
										$minutes		= date("i", $calendar_time);																										
										$total_minutes	= ($hours*60) + $minutes;										
										$diff			= $total_minutes%$time_span;										
										if($diff == 0){
											$calendar_time	= strtotime('+'.$time_span.' minutes', $calendar_time);		
										}
										else{										
											$calendar_time	= strtotime('+'.($time_span - $diff).' minutes', $calendar_time);
										}
									}
									$proportion	= 3;
									foreach($time_intervals as $index=>$time_interval){		
										if(($index + 1) == count($time_intervals)){	// last timing										
											$to_time 		= strtotime(date("h:i A", strtotime($last_timing->end_time)));											
											$from_time 		= strtotime(date("h:i A", strtotime($time_interval)));											
											$diff_minutes	= round(abs($to_time - $from_time) / 60,2);
?>											
											<tr>
                                            	<td width="80" height="<?php echo ($diff_minutes * $proportion ); ?>" style="position:relative;">											
													<div class="time-box"><?php echo $time_interval; ?></div>																		
											
												</td>
                                            </tr>
<?php                                            										
										}
										else{
											//calculate timespan diff
											$hours			= date("h", strtotime($time_interval));											
											$minutes		= date("i", strtotime($time_interval));																											
											$total_minutes	= ($hours*60) + $minutes;																																											
											$diff			= $total_minutes%$time_span;											
											$diff_minutes	= $time_span - $diff;
?>
											<tr>
                                            	<td width="80" height="<?php echo ($diff_minutes * $proportion ); ?>" style="position:relative;">
<?php
													if($total_minutes%$time_span==0){
?>	
														<div  class="time-box"><?php echo $time_interval; ?></div>
<?php													
													}
?>                                                
                                                </td>
                                            </tr>    										
<?php											
										}
									}
?>                                	
                                </table>
                            </td>    
<?php
							$weekday_attributes	= array(1=>'on_sunday', 2=>'on_monday', 3=>'on_tuesday', 4=>'on_wednesday', 5=>'on_thursday', 6=>'on_friday', 7=>'on_saturday');
							foreach($weekdays as $weekday){
								if($weekday['weekday'] != 0){
									$is_holiday 	= Configurations::model()->isHoliday($date_between[$weekday['weekday']-1]);																			
									$holiday_class	= '';									
									if($is_holiday == 1){										
										$holiday_class	= 'table_holiday';									
									}
?>
									<td class="td <?php echo $holiday_class; ?>" align="center">
<?php
										if($is_holiday == 0){
											$weekday_condition		= "`".$weekday_attributes[$weekday['weekday']]."`=:week_day_status";
											$criteria				= new CDbCriteria;											
											$criteria->condition 	= "batch_id=:x AND ".$weekday_condition;											
											$criteria->params 		= array(':x'=>$batch->id, ':week_day_status'=>1);											
											$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  											
											$timings 				= ClassTimings::model()->findAll($criteria);
?>
											<table class="timetable-br-box" width="100%" border="0" cellspacing="0" cellpadding="0">
<?php
												$from_time	= $time_intervals[0];
												foreach($timings as $i => $timing){
												 	$set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$batch->id,'weekday_id'=>$weekday['weekday'],'class_timing_id'=>$timing->id)); 
													$class	= '';
													/*if($set != NULL){ 
														if($set->status == 2){ //In case of teacher switching
															$class	= 'table_teacher_swch';
														}

														if($set->status == 1){ //In case of cancellation
															$class	= 'table_tmtbl_cancel';
														}
													}*/
													if($settings!=NULL){	
														$time1	= date($settings->timeformat,strtotime($timing->start_time));
														$time2	= date($settings->timeformat,strtotime($timing->end_time));
													}
													//find height start
													$to_time		= $timing->start_time;																			
													$to_time 		= strtotime($to_time);
													$from_time 		= strtotime($from_time);
													$diff_minutes	= round(abs($to_time - $from_time) / 60,2);													
													if($diff_minutes>0){
?>
														<tr><td height="<?php echo ($diff_minutes * $proportion); ?>" valign="top" style="background-color:#FFF;"></td></tr>
<?php														
													}
													$from_time 				= $timing->end_time;
													$timing_diff_minutes	= round(abs(strtotime($timing->end_time) - strtotime($timing->start_time)) / 60,2);
													//find height end
?>
													<tr>
                                                    	<td class="td1 <?php echo $class; ?>" height="<?php echo ($timing_diff_minutes * $proportion); ?>" valign="top">
                                                            <div class="timtable-inner">
                                                            	<div class=" time-area"><?php echo $time1.' - '.$time2; ?></div>
<?php
																	if($set == NULL){
																		if($timing->is_break == 1){
?>
																			<div class="subject"><?php echo Yii::t('app', 'Break'); ?></div>
<?php	
																		}
																	}
																	else{
																		$subject_name	= '';
																		$employee_name	= '';
																		if($set->is_elective == 0){
																			$subject_name	= TimetableEntries::model()->getSubjectName($set->id);
																			
																			
																			
																			$employee_name	= TimetableEntries::model()->getEmployeeName($set->id);		
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
																		<div class="subject"><?php echo $subject_name; ?></div>
																		<div class="employee"><?php echo $employee_name; ?></div>
<?php   
																		/*if($set->status == 1){
?>
																			<a href="javascript::void(0);" class="info-tooltip"><?php echo Yii::t('app', 'Cancelled'); ?><span><?php echo ucfirst($set->reason); ?></span></a>
<?php																			
																		}*/
																	}
?>                                                                
                                                                
                                                            </div>
                                                        
                                                        </td>
                                                    </tr>    
<?php													
												}
?>                                            	
                                            </table>										
<?php											
										}
										else{
											echo Yii::t('app', 'Holiday');
										}
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
?>
			<div class="nothing-found"><?php echo Yii::t('app', 'No Class Timings Found'); ?></div>
<?php			
		}
?>    	
    </div>
</div>
<?php if($timetable_type == 1){ ?>
	<script type="text/javascript">
    $(function() {   
        var startDate;
        var endDate;
        var selectCurrentWeek = function () {
            window.setTimeout(function () {
                $('.ui-weekpicker').find('.ui-datepicker-current-day a').addClass('ui-state-active').removeClass('ui-state-default');
            }, 1);
        }
        var setDates = function (input,test) {
            var $input = $(input);
            var date	 = new Date($('#day').val());
            if (date !== null) { 
                var firstDay = $input.datepicker( "option", "firstDay" );
                var dayAdjustment = date.getDay() - firstDay;
                if (dayAdjustment < 0) {
                    dayAdjustment += 7;
                }  
                startDate 	= new Date(date.getFullYear(), date.getMonth(), date.getDate() - dayAdjustment);
                endDate 	= new Date(date.getFullYear(), date.getMonth(), date.getDate() - dayAdjustment + 6);
    
                var inst = $input.data('datepicker');
                var dateFormat = 'M d'		
                var start_date	=	$.datepicker.formatDate(dateFormat, startDate, inst.settings);
                var end_date	=	$.datepicker.formatDate(dateFormat, endDate, inst.settings);
                if(test == 1)
                    $('#week').val($.datepicker.formatDate(dateFormat, startDate, inst.settings)+' - '+$.datepicker.formatDate(dateFormat, endDate, inst.settings));
            }
        }
    
        $( "#week" ).click(function() {
            jQuery('.week-picker').datepicker("show");
        });
        $('.week-picker').datepicker({
            beforeShow: function () {
                $('#ui-datepicker-div').addClass('ui-weekpicker');
                selectCurrentWeek();
            },
            onClose: function () {
                $('#ui-datepicker-div').removeClass('ui-weekpicker');
            },
            showOtherMonths: true,
            selectOtherMonths: true,
            onSelect: function (dateText, inst) {
                setDates(this,1); 
                <?php $url = Yii::app()->createUrl("/studentportal/default/timetable",array('bid'=>$batch->id))?>
                window.location.href = "<?php echo $url;?>"+"&date="+dateText;
                selectCurrentWeek();
                $(this).change();
            },
            beforeShowDay: function (date) {
                var cssClass = '';
                if (date >= startDate && date <= endDate)
                    cssClass = 'ui-datepicker-current-day';
                return [true, cssClass];
            },
            onChangeMonthYear: function (year, month, inst) {
                selectCurrentWeek();
            }
        });
    
        setDates('.week-picker',0);
    
        var $calendarTR = $('.ui-weekpicker .ui-datepicker-calendar tr');
        $calendarTR.live('mousemove', function () {
            $(this).find('td a').addClass('ui-state-hover');
        });
        $calendarTR.live('mouseleave', function () {
            $(this).find('td a').removeClass('ui-state-hover');
        });
    });
    </script>
<?php } ?> 

<script type="text/javascript">
$('#batch_id').change(function(ev){
	var batch_id	= $(this).val();
	var date		= '<?php echo $_REQUEST['date']; ?>';	
	if(batch_id != '' && date != ''){
		window.location= 'index.php?r=studentportal/default/timetable&bid='+batch_id+'&date='+date;
	}
	else if(batch_id != ''){
		window.location= 'index.php?r=studentportal/default/timetable&bid='+batch_id;
	}
	else{
		window.location= 'index.php?r=studentportal/default/timetable';
	}
});
</script>   
<script language="javascript">
function getmode(){
	//var student_id	= <?php //echo $student->id; ?>;
	var batch_id	= $('#batch_id').val();
		if(batch_id != ''){
			window.location= 'index.php?r=studentportal/default/timetable&bid='+batch_id;
		}
		else{
			window.location= 'index.php?r=studentportal/default/timetable';
		}
};
</script>