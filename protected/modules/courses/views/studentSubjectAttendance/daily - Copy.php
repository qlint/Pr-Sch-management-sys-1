
<script language="javascript">
function course(){
	var id = document.getElementById('bat').value;
	window.location= 'index.php?r=studentAttentance/index&id='+id;	
}
</script>

<style>
.mark_leave.tick .fa{
	    padding: 5px;	
}
.mark_leave.tick {
    color: #52b12b;
    display: block;
    text-align: center;
    background-color: #dff0d8;
    transition: 0.3s ease-in;
}
.mark_leave.cross {
	color: #c72e2e;
    display: block;
    text-align: center;
    background-color: #ffcdcd;
    transition: 0.3s ease-in;
}
.mark_leave.cross .fa{
	    padding: 5px;
}
/*.mark_leave.tick{
	color:#0bb17b;	
}
.mark_leave.cross{
	color:#F5293E;
}*/
</style>

<?php
$this->breadcrumbs=array(
	Yii::t('app','Attendances')=>array('/attendance'),
	Yii::t('app','Student Attendances'),
	Yii::t('app','Daily Subject Wise'),
);
?>
<?php
$settings				= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings != NULL){
	$timeformat		= $settings->timeformat;
	$displayformat	= $settings->displaydate;
	$pickerformat	= $settings->dateformat;
}
else{
	$timeformat		= 'h:i a';
	$displayformat	= 'M d Y';
	$pickerformat 	= 'dd-mm-yy';
}

$current_academic_yr 	= Configurations::model()->findByAttributes(array('id'=>35));
$yr 					= AcademicYears::model()->findByAttributes(array('id'=>$current_academic_yr->config_value));

$date					= (isset($_REQUEST['date']))?date('Y-m-d', strtotime($_REQUEST['date'])):date("Y-m-d");
$day 					= date('w', strtotime($date));
$prev_day				= date('Y-m-d', strtotime('-1 days', strtotime($date)));
$next_day				= date('Y-m-d', strtotime('+1 days', strtotime($date)));

$month_1		=	date("M", strtotime($date));
$month			=	Yii::t('app',$month_1);
$years			=	date("Y", strtotime($date));
$days			=	date("d", strtotime($date));	
$display_date 	= $month.' '.$years.' '.$days;

$batch					= Batches::model()->findByPk($_REQUEST['id']);

$criteria				= new CDbCriteria;											
$criteria->join			= "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`id`";											
$criteria->condition	= "`t`.`is_active`=1 AND `t`.`is_deleted`=0 AND `bs`.`batch_id`=:batch_id AND `bs`.`status`=:status";
$criteria->params		= array(":batch_id"=>$batch->id, ':status'=>1);
$criteria->order		= "`t`.`first_name` ASC, `t`.`last_name` ASC";
$students				= Students::model()->findAll($criteria);
$bid					= $batch->id;

?>
<div style="background:#fff; min-height:800px;">   
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td valign="top">
                    <div style="padding:20px;">
                    <div id="attendanceDialog"></div>
                    <?php 
                    if($batch!=NULL){
                    ?>                        
                        <div class="clear"></div>
                        <div class="emp_right_contner">
                            <div class="emp_tabwrapper">
								<?php $this->renderPartial('/batches/tab');?>
                                <div class="clear"></div>                                
                                <?php
								$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
								if(Yii::app()->user->year)
									$year 	= Yii::app()->user->year;
								else
									$year 	= $current_academic_yr->config_value;
									
								$is_insert 	= PreviousYearSettings::model()->findByAttributes(array('id'=>2));
								$is_edit   	= PreviousYearSettings::model()->findByAttributes(array('id'=>3));
								$is_delete 	= PreviousYearSettings::model()->findByAttributes(array('id'=>4));								
								if($year != $current_academic_yr->config_value and ($is_insert->settings_value==0 or $is_edit->settings_value==0 or $is_delete->settings_value==0)){
								?>
									<div>
										<div class="yellow_bx" style="background-image:none;width:95%;padding-bottom:45px;">
											<div class="y_bx_head" style="width:95%;">
											<?php 
												echo Yii::t('app','You are not viewing the current active year. ');
												if($is_insert->settings_value==0 and $is_edit->settings_value!=0 and $is_delete->settings_value!=0)
													echo Yii::t('app','To mark the attendance, enable Create option in Previous Academic Year Settings.');
												elseif($is_insert->settings_value!=0 and $is_edit->settings_value==0 and $is_delete->settings_value!=0)
													echo Yii::t('app','To edit the attendance, enable Edit option in Previous Academic Year Settings.');
												elseif($is_insert->settings_value!=0 and $is_edit->settings_value!=0 and $is_delete->settings_value==0)
													echo Yii::t('app','To delete the attendance, enable Delete option in Previous Academic Year Settings.');
												else
													echo Yii::t('app','To manage the the attendance, enable the required options in Previous Academic Year Settings.');
											?>
											</div>
											<div class="y_bx_list" style="width:650px;">
												<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
											</div>
										</div>
									</div>
								<?php
								}								
								else{
								?>
                                    <!-- Attendance table start -->   
                                   	<div class="subwis-tableposctn">
                                    <div class="formWrapper formWrapper-subwis">
                                        <div  style="width:100%">                            
                                            <div class="Nodata-bg">                                
                                                <?php 
												if(count($students)==0){
													echo '<div class="not-found-box"><i class="os_no_found">'.Yii::t("app", "No students found").'</i></div>';
												}
												
												else{
													$weekday_attributes	= array(1=>'on_sunday',2=>'on_monday',3=>'on_tuesday',4=>'on_wednesday',5=>'on_thursday',6=>'on_friday',7=>'on_saturday');
													if(Configurations::model()->timetableFormat($bid) == 1){
														$criteria				= new CDbCriteria;
														$criteria->condition 	= "batch_id=:x";
														$criteria->params 		= array(':x'=>$bid);
														$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
														$timings 				= ClassTimings::model()->findAll($criteria);
													}
													else{
														$weekday_condition		= "`".$weekday_attributes[$day + 1]."`=:week_day_status";
														$criteria				= new CDbCriteria;
														$criteria->condition 	= "batch_id=:x AND ".$weekday_condition;
														$criteria->params 		= array(':x'=>$bid, ':week_day_status'=>1);
														$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
														$timings 				= ClassTimings::model()->findAll($criteria);														
													}
                                                    $count_timing 			= count($timings);
                                                    if($timings!=NULL){
													 ?>
                                                     
                                                        <div class="attnd-tab-sectn-blk">
															<div class="tab-sectn">
																<ul>
																 <?php if(Configurations::model()->studentAttendanceMode() != 2){ ?>
																			<li><?php echo CHtml::link(Yii::t("app","DAY WISE"), array("/courses/studentAttentance", "id"=>$bid), array("class"=>"sub-attnd-daily"));?> </li>
																  <?php } ?>    
																<?php if(Configurations::model()->studentAttendanceMode() != 1){ ?>
																			<li><?php echo CHtml::link(Yii::t("app","SUBJECT WISE"), array("/courses/studentSubjectAttendance/daily", "id"=>$bid), array("class"=>"sub-attnd-daily active-attnd"));?></li>   
															  <?php } ?>     
																</ul>
															</div>
                                                        </div>
                                                        <div class="attnd-tab-inner-blk">
                                                        <div class="attndwise-head">
                                                        <h3><?php echo Yii::t('app','Subject Wise Attendance'); ?></h3>
                                                        </div>
														<div class="pdf-box">
                                                            <div class="box-one">
                                                        		<div class="atnd_table-calender-bg atnd_tnav-new box-one-lft-rght" align="center">
                                                                    <?php
																		echo CHtml::link('<div class="atnd-table-arow-left"><img src="'.Yii::app()->request->baseUrl.'/images/attnd-arow-left.png" height="13" width="7" border="0"></div>', array('/courses/studentSubjectAttendance/daily', 'id'=>$batch->id, 'date'=>$prev_day), array('title'=>Yii::t('app', 'Previous Day')));											
																		$this->widget('zii.widgets.jui.CJuiDatePicker', array(                        
																			'name'=>'date',
																			'value' =>$display_date,
																			// additional javascript options for the date picker plugin
																			'options'=>array(
																				'showAnim'=>'fold',
																				'dateFormat'=>$pickerformat,
																				'changeMonth'=> true,
																				'changeYear'=>true,
																				'yearRange'=>'1900:'.(date('Y')),
																				'onSelect'=>'js:function(date){
																					window.location.href	=  "'.$this->createUrl('studentSubjectAttendance/daily', array('id'=>$bid)).'" + "&date=" + date;
																				}'
																			),
																			'htmlOptions'=>array(
																				'class'=>'atnd_table-cal-input',
																				//'style'=>'text-align:center; border:none; left:27px; top:-3px; cursor:pointer;',
																				'readonly'=>true
																			),
																		));
																		
																		if($next_day<=date('Y-m-d')){
																			echo CHtml::link('<div class="atnd-table-arow-right"><img src="'.Yii::app()->request->baseUrl.'/images/attnd-arow-right.png" height="13" width="7" border="0"></div>', array('/courses/studentSubjectAttendance/daily', 'id'=>$batch->id, 'date'=>$next_day), array('title'=>Yii::t('app', 'Next Day')));										
																		}
										?>                          
                                                                </div>
                                                                <div class="subwise-blk box-one-lft-rght">
                                                                    <ul>
                                                                        <li> <?php echo CHtml::link(Yii::t("app","Daily"), array("/courses/studentSubjectAttendance/daily", "id"=>$bid), array("class"=>"sub-attnd-daily active-attnd"));?> </li>
                                                                         <li><?php echo CHtml::link(Yii::t("app","Weekly"), array("/courses/studentSubjectAttendance/index", "id"=>$bid), array("class"=>"sub-attnd-weekly"));?></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="box-two">
                                                            	<?php  
																$is_holiday		= StudentAttentance::model()->isHoliday($date);
																$is_week_day 	= StudentAttentance::model()->isWeekday($date, $bid); 
																
																if($date <= date('Y-m-d') and $is_holiday==0 and $is_week_day == 2 and $batch->start_date <= $date and $date <= $batch->end_date){																
																 echo CHtml::link(Yii::t("app",'Generate PDF'), array('/courses/studentSubjectAttendance/dailyPdf','batch'=>$batch->id, 'date'=>$date),array('target'=>'_blank','class'=>'pdf_but')); 
																}?>
                                                            </div>
                                                  		</div>
                                                        <?php
													if($date > date('Y-m-d')){
														echo '<div class="not-found-box"><i class="os_no_found">'.Yii::t("app", "Cannot mark attendance for this date").'</i></div>';
													}else if($is_holiday){
															echo '<div class="not-found-box"><i class="os_no_found">'.Yii::t("app", "Selected day is a Holiday").'</i></div>';	
												    }elseif($is_week_day != 2 ){														
														?>
														<div class="not-found-box">
														<?php
														echo '<i class="os_no_found">'.Yii::t("app", "Selected day is not a weekday").'</i>';
														?>
														</div>
														<?php
													
													}else{
															if($batch->start_date <= $date and $date <= $batch->end_date){
															$weekday		= Weekdays::model()->find("batch_id=:x AND weekday=:weekday", array(':x'=>$bid, ':weekday'=>($day + 1)));										
															if($weekday==NULL)
																$weekday	= Weekdays::model()->find("batch_id IS NULL AND weekday=:weekday", array(':weekday'=>($day + 1)));
															
															if($weekday!=NULL){ 
															?>
																													
															<div class="clearfix"></div>
															
															<div class="attendance-table-block">
																<div class="attendance-table-scroll">
                                                                <div class="attendance-table-block-tbl">
																	<table border="0" align="center" width="100%" id="table" cellspacing="0">
																		<tbody>
																			<tr>
                                                                              <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
                                                                            	<th width="80" class="loader"><?php echo Yii::t('app','Roll No'); ?></th>
                                                                               <?php } ?>
																				<th width="80" class="loader"><?php echo Yii::t('app','Name');?></th>
																				<?php 
																				foreach($timings as $timing_1){
																					
																					//traslate AM and PM 	
																					$t1 = date('h:i', strtotime($timing_1->start_time));	
																					$t2 = date('A', strtotime($timing_1->start_time));
																					
																					$t3	= date('h:i', strtotime($timing_1->end_time));	
																					$t4	= date('A', strtotime($timing_1->end_time));	
																					//end 
																					$time1	= date($settings->timeformat,strtotime($timing_1->start_time));
                                                            						$time2	= date($settings->timeformat,strtotime($timing_1->end_time));
																					echo '<th width="130px" class="td">';
																					echo '<center><div class="top">'.$t1.' '.Yii::t("app",$t2).' - '.$t3.' '.Yii::t("app",$t4).'</div></center>';
																					?>
																					<div class="student-subject">	
																					<?php
																					$set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$bid,'weekday_id'=>$weekday['weekday'],'class_timing_id'=>$timing_1->id));
																					if($set->is_elective==0){
																						$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));																	
																						if($time_sub!=NULL){
																							echo '<div class="suject_name">'.$time_sub->name.'</div>';                                                                                		$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
																							if($time_emp!=NULL){
																								$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));
																								
																								if($is_substitute and in_array($is_substitute->date_leave,$date_between)){
																									$employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
																									echo '<div class="batch_name">'.ucfirst($employee->first_name).' '.ucfirst($employee->middle_name).' '.ucfirst($employee->last_name).'</div>';
																								}
																								else{
																									if($time_sub!=NULL){
																										echo '<div class="batch_name">'.ucfirst($time_emp->first_name).' '.ucfirst($time_emp->middle_name).' '.ucfirst($time_emp->last_name).'</div>';
																									}
																								}
																							}
																						}
																						else{
																							echo '';
																						}
																					}
																					else{
																						
																						
																						$elec_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
																						$electname = ElectiveGroups::model()->findByAttributes(array('id'=>$elec_sub->elective_group_id,'batch_id'=>$bid));
																						if($electname!=NULL){
																							echo '<div class="suject_name">'.$electname->name.'</div>';
																						}
																						$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
																						if($time_emp!=NULL){
																							$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));
																							
																							if($is_substitute and in_array($is_substitute->date_leave,$date_between)){
																								$employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
																								//echo '<div class="employee">'.ucfirst($employee->first_name).' '.ucfirst($employee->middle_name).' '.ucfirst($employee->last_name).'</div>';
																							}
																							else{
																								if($electname!=NULL){
																									//echo '<div class="employee">'.ucfirst($time_emp->first_name).' '.ucfirst($time_emp->middle_name).' '.ucfirst($time_emp->last_name).'</div>';
																								}
																							}
																						}                                                                                
																					}
																					?>
																					</div>
																					<?php																				
																					echo '</th>';	
																				}
																				?>                                                                            
																			</tr> <!-- timetable_tr -->
																			<?php
																			foreach($students as $student){
																				$batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$bid, 'status'=>1));                                              
																			?>
																			<tr>
                                                                              <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
                                                                              	<td class="td daywise-block">
																					<p><?php if($batch_student!=NULL and $batch_student->roll_no!=0){
																					echo $batch_student->roll_no;
																				}
																				else{
																					echo '-';
																				}?></p>
																				</td>
                                                                              <?php } ?>
																				<td class="td daywise-block">
                                                                                
																					<p><?php echo $student->studentFullName(); ?></p>
																				</td>
                                                                                <?php
																					if($date < $student->admission_date){
																					?>
                                                                                    	<td class="td" colspan="<?php echo $count_timing;?>"><?php echo '<div class="not_joined">'.Yii::t('app', 'Student has not joined yet').'</div>';?></td>
                                                                                    <?php
																					}
																					else{
																				for($i=0;$i<$count_timing;$i++){
																					$set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$bid,'weekday_id'=>$weekday['weekday'],'class_timing_id'=>$timings[$i]['id'])); 
																					?>	
																					<td class="td"> 	
																					<?php
																					$subjectwise =  StudentSubjectwiseAttentance::model()->findByAttributes(array('student_id'=>$student->id, 'timetable_id'=>$set->id, 'weekday_id'=>$set->weekday_id, 'date'=>$date));
																							if($set->is_elective == 2){
																								$elective			=	Electives::model()->findByAttributes(array('batch_id'=>$bid, 'id'=>$set->subject_id)); 
																								$student_elective	=	StudentElectives::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$bid, 'elective_group_id'=>$elective->elective_group_id)); 
																								if($student_elective==NULL){
																									$visible=1;
																								}else{
																									$visible=0;
																								}															
																							}else{
																								$visible=0;
																							}  
																					if($date >= $student->admission_date and $date <= date("Y-m-d") ){
																						if($set == NULL or $visible==1){ 
																								$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timings[$i]['id'],'is_break'=>1));
																								if($is_break!=NULL){	
																									echo '<div class="attnd-break">'.Yii::t('app', 'Break').'</div>';
																								}
																								else{
																									echo '<div class="not_joined-inner">'.Yii::t('app', 'Not Assigned').'</div>';
																								} 
																						}
																						else{
																							
																							if($subjectwise == NULL){																				
																								echo CHtml::link(
																									'<i class="fa fa-toggle-on"></i>',
																									'javascript:void(0);',
																									array(
																										'data-timetable_id' =>$set->id,
																										'data-student_id' =>$student->id,
																										'data-subject_id'=>$set->subject_id,
																										'data-weekday_id' =>$set->weekday_id,
																										'data-date'=>$date,
																										'data-mode'=>'absent',
																										'class'=>'mark_leave tick',
																										'title'=>Yii::t('app', 'Mark as Absent')
																									)
																								);
																								?>
																								<div class="student-action-box comn-tooltip">		
																									<?php
																										echo CHtml::ajaxLink(
																											'<span>Mark Absent</span>',
																											$this->createUrl('studentSubjectAttendance/mark'),
																											array(
																												'onclick'=>'$("#attendanceDialog").dialog("open");return false;',
																												'update'=>'#attendanceDialog',
																												'type' =>'GET',
																												'data' => array(
																													'timetable_id' =>$set->id,
																													'student_id' =>$student->id,
																													'subject_id'=>$set->subject_id,
																													'weekday_id' =>$set->weekday_id,
																													'date'=>$date
																												),
																												'dataType' => 'text'
																											),
																											array(
																												'class'=>'student-timtable-update '
																											)
																										);
																									?>
																								</div>
																								<?php
																							}
																							else{
																							?>
																								<div class="mark-absent-blk">
																									<p>
																										<?php 
																										echo CHtml::link(
																											'<i class="fa fa-toggle-off"></i>',
																											'javascript:void(0);',
																											array(
																												'data-timetable_id' =>$set->id,
																												'data-student_id' =>$student->id,
																												'data-subject_id'=>$set->subject_id,
																												'data-weekday_id' =>$set->weekday_id,
																												'data-date'=>$date,
																												'data-mode'=>'present',
																												'class'=>'mark_leave cross',
																												'title'=>Yii::t('app','Mark as Present')
																											)
																										);
																										?>
																									 </p>       
																								</div>
																								<div class="student-action-box comn-tooltip">		
																									<?php
																										echo CHtml::ajaxLink(
																											'<span>Update Absent</span>',
																											$this->createUrl('studentSubjectAttendance/mark'),
																											array(
																												'onclick'=>'$("#attendanceDialog").dialog("open");return false;',
																												'update'=>'#attendanceDialog',
																												'type' =>'GET',
																												'data' => array(
																													'id' =>$subjectwise->id
																												),
																												'dataType' => 'text'
																											),
																											array(
																												'id'=>'edit-attendance-'.$subjectwise->id,
																												'class'=>'student-timtable-update '
																											)
																										);
																									?>
																								</div>
																							<?php
																								}	
																							}
																						}
																					}
																				}
																					?>
                                                                                   
																				</tr>
                                                                                
																				<?php 
																			}
																			?>
																		</tbody>
																	</table>
															</div>
                                                            </div>
														</div> <!-- END div class="timetable" -->
														<?php 
															}
															else{
																echo '<div class="not-found-box"><i class="os_no_found">'.Yii::t('app','Timetable is not set for this date').'</i></div>';
															}
														}
														else{
															echo '<div class="not-found-box"><i class="os_no_found">'.Yii::t('app','No class on this date').'</i></div>';
														}
													} 
                                              }
												else{
												 echo '<div class="not-found-box"><i class="os_no_found">'.Yii::t('app','No Class Timings').'</i></div>';
												}
											}
										?> 
                                         </div> 
                                            </div>                            
                                        </div>
                                        <div class="clear"></div>
                                   	</div> 
             						<!-- Attendance table end -->
                                </div>
                                <?php
								}
								?>
                            </div>
                        </div>
                    <?php
					}
					?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<script>
$('.abs').click(function(e) {
    $('form#student-attentance-form').remove();
});

var mark_attendance	= function (){
	$('.mark_leave').unbind('click').click(function(e) {
		var that	= $(this);
        if(!that.hasClass('processing')){	// previous request is under processing
			var	timetable_id	= that.attr('data-timetable_id'),
				student_id		= that.attr('data-student_id'),
				subject_id		= that.attr('data-subject_id'),
				weekday_id		= that.attr('data-weekday_id'),
				date			= that.attr('data-date'),
				mode			= that.attr('data-mode');
				
			var datas			= {'<?php echo Yii::app()->request->csrfTokenName;?>':'<?php echo Yii::app()->request->csrfToken;?>', timetable_id:timetable_id, student_id:student_id, subject_id:subject_id, weekday_id:weekday_id, date:date, mode:mode};
			
			$.ajax({
				type:'POST',
				url:'<?php echo $this->createUrl('studentSubjectAttendance/status');?>',
				data:datas,
				dataType:"json",
				beforeSend: function(){
					that.addClass('processing');
				},
				success: function(response){
					that.removeClass('processing');
					if(response.status=="success"){						
						that.toggleClass('tick cross');
						if(mode=="present"){
							that.attr({'title':'<?php echo Yii::t('app', 'Mark as Absent');?>', 'data-mode':'absent'}).html('<i class="fa fa-toggle-on"></i>');
						}
						else{
							that.attr({'title':'<?php echo Yii::t('app', 'Mark as Present');?>', 'data-mode':'present'}).html('<i class="fa fa-toggle-off"></i>');
						}
					}
					
					if(response.hasOwnProperty('link')){
						that.closest('td').find('.student-action-box').html(response.link);
					}
				},
				error: function(){
					that.removeClass('processing');
				}
			});			
		}
    });
};

mark_attendance();
</script>