<script>
function displaytable() 
{
	var bid = document.getElementById('bid').value;
	if(bid == '')
	{
		$('#error').html('<?php echo Yii::t('app','select Batch'); ?>');
		window.location= 'index.php?r=teachersportal/default/daily';
		return false;
	}
	else
	{
	window.location= 'index.php?r=teachersportal/default/daily&bid='+bid;
	}
}
</script>

	<?php $this->renderPartial('/default/leftside');?> 
	<div class="right_col"  id="req_res123">
    <!--contentArea starts Here--> 
     <div id="parent_rightSect">
        <div class="parentright_innercon">
        <div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-file-text"></i><?php echo Yii::t("app", 'Daily Subject Wise Attendance');?><span><?php echo Yii::t("app", 'View Daily Subject Wise Attendance here');?> </span></h2>
        </div>
        <div class="col-lg-2">
        
                </div>
    
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t("app", 'You are here:');?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t("app", 'Attendance');?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>
    <div class="contentpanel">
    
<div class="panel-heading">
            <h3 class="panel-title"><?php echo Yii::t('app','Subject Wise Attendance'); ?></h3>           
        	</div>
            <div class="people-item">
            
             <div> 
<div class="attendance-block-bg">
             
                <div class="attnd-tab-sectn-blk">   
                <div class="tab-sectn">
                <ul>
                <?php if(Configurations::model()->studentAttendanceMode() != 2){ ?>
                                                <li><?php echo CHtml::link(Yii::t("app","DAY WISE"), array("/teachersportal/default/studentAttendance", "id"=>$_REQUEST['bid']), array("class"=>"sub-attnd-daily"));?> </li>
                                       <?php } ?>    
                                    <?php if(Configurations::model()->studentAttendanceMode() != 1){ ?>
                								<li><?php echo CHtml::link(Yii::t("app","SUBJECT WISE"), array("/teachersportal/default/daily", "bid"=>$_REQUEST['bid']), array("class"=>"active-attnd"));?> </li>
                <?php }
			  $employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
			  ?>     
                </ul>
                </div>
                </div>
                <div class="attndwise-head">
                <h3><?php echo Yii::t('app','Subject Wise Attendance');?></h3>
                </div>		
                               
                <div class="row">
                	<div class="pdf-box">
                    	<div class="col-md-10 col-4-reqst">
                         <div class="row">
                         	<div class="col-md-8 col-4-reqst">
                            <div class="row">
                            <div class="col-md-4 col-4-reqst"> 
                            <div class="attnd-selectbox">
                            	<?php
								$current_academic_yr = Configurations::model()->findByPk(35);
                                $data = Batches::model()->findAllByAttributes(array('employee_id'=>$employee->id, 'is_deleted'=>0, 'academic_yr_id'=>$current_academic_yr->config_value),array('order'=>'id DESC'));
								$batch_list = CHtml::listData($data,'id','name');
								echo CHtml::dropDownList('bid','',$batch_list,array('prompt'=>Yii::t('app','Select Batch'),'class'=>'form-control','id'=>'bid','style'=>'width:190px;', 'encode'=>false, 'onchange'=>'displaytable()', 'options'=>array($_REQUEST['bid']=>array('selected'=>true))));
                                ?>
                            </div>
                            </div>

                            <div class="col-md-4 col-4-reqst">             
                            <div class="subwise-blk box-one-lft-rght">
                            <ul>
                            <li><?php 
							 echo CHtml::link(Yii::t("app","Daily"), array("/teachersportal/default/daily", "bid"=>$_REQUEST['bid']), array("class"=>"sub-attnd-daily active-attnd"));?>  </li>
                            <li><?php echo CHtml::link(Yii::t("app","Weekly"), array("/teachersportal/default/subwiseattendance", "bid"=>$_REQUEST['bid']), array("class"=>" sub-attnd-weekly"));?>  </li>
                            </ul>
                            </div>
                            </div>
                            
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
             </div>
          </div>


<?php

if(isset($_REQUEST['bid']))
{
  
	$settings				= UserSettings::model()->findByAttributes(array('user_id'=>1));
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
	
	$batch					= Batches::model()->findByPk($_REQUEST['bid']);
	$course 				= Courses::model()->findByAttributes(array('id'=>$batch->course_id));
	$semester				= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
	$sem_enabled			= Configurations::model()->isSemesterEnabledForCourse($course->id); 
	
	$criteria				= new CDbCriteria;											
	$criteria->join			= "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`id`";											
	$criteria->condition	= "`t`.`is_active`=1 AND `t`.`is_deleted`=0 AND `bs`.`batch_id`=:batch_id AND `bs`.`status`=:status";
	$criteria->params		= array(":batch_id"=>$batch->id, ':status'=>1);
	$criteria->order		= "`t`.`first_name` ASC, `t`.`last_name` ASC";
	$students				= Students::model()->findAll($criteria);
	$bid					= $batch->id;
?>

						<div class="batch-block">
							<p><?php echo '<span>'.Yii::t('app','Course').'</span> '.':'.' '.ucfirst($course->course_name).''; ?></p>
							<p><?php echo '<span>'.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>  '.':'.' '.ucfirst($batch->name);?></p>
							<?php if($sem_enabled==1 and $batch->semester_id!=NULL){ ?>
									<p> <?php echo '<span>'.Yii::t('app','Semester').'</span> '.':'.' '.ucfirst($semester->name).''; ?></p>
							<?php } ?>
						</div>
     							<!-- Attendance table start -->   
                                   	<div class="subwis-tableposctn">
                                    <div class="formWrapper formWrapper-subwis">
                                        <div> 
												                                        
                                                <?php 
										 if(isset($bid) and $bid!=NULL){
												if(count($students)==0){ ?>
													<div class="not-found-box">          
														<?php echo '<i>'.Yii::t("app", "No students found").'</i>';?>
													</div>	
											<?php }
												else{
													$criteria				= new CDbCriteria;
													$criteria->condition 	= "batch_id=:batch_id";
													$criteria->params 		= array(':batch_id'=>$bid);
													$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
													$timings 				= ClassTimings::model()->findAll($criteria);
													                                                    
                                                    $count_timing 			= count($timings);
                                                    if($timings!=NULL){
													 ?>
														<div class="row row-pading">
                                                        <div class="col-md-10 col-4-reqst">
                                                        	<div class="row">
                                                            <div class="col-md-3 col-4-reqst">
                                                            <div class="display-block">
                                                        		<div class="atnd_table-calender-bg atnd_tnav-new box-one-lft-rght" align="center">
                                                                    <?php
																		echo CHtml::link('<div class="atnd-table-arow-left"><img src="'.Yii::app()->request->baseUrl.'/images/attnd-arow-left.png" height="13" width="7" border="0"></div>', array('/teachersportal/default/daily', 'bid'=>$batch->id, 'date'=>$prev_day), array('title'=>Yii::t('app', 'Previous Day')));											
																		$this->widget('zii.widgets.jui.CJuiDatePicker', array(                        
																			'name'=>'date',
																			'value' =>date($displayformat, strtotime($date)),
																			// additional javascript options for the date picker plugin
																			'options'=>array(
																				'showAnim'=>'fold',
																				'dateFormat'=>$pickerformat,
																				'changeMonth'=> true,
																				'changeYear'=>true,
																				'yearRange'=>'1900:'.(date('Y')),
																				'onSelect'=>'js:function(date){
																					window.location.href	=  "'.$this->createUrl('default/daily', array('bid'=>$bid)).'" + "&date=" + date;
																				}'
																			),
																			'htmlOptions'=>array(
																				'class'=>'atnd_tnav-date',
																				'style'=>'text-align:center; border:none; left:27px; top:-3px; cursor:pointer;',
																				'readonly'=>true
																			),
																		));
																		
																		if($next_day<=date('Y-m-d')){
																			echo CHtml::link('<div class="atnd-table-arow-right"><img src="'.Yii::app()->request->baseUrl.'/images/attnd-arow-right	.png" height="13" width="7" border="0"></div>', array('/teachersportal/default/daily', 'bid'=>$batch->id, 'date'=>$next_day), array('title'=>Yii::t('app', 'Next Day')));										
																		}
										?>                          
                                                                </div>
                                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>
                                                            
                                                            
                                                            <div class="col-md-2 col-4-reqst">
                                                            <?php  
															$is_holiday		= StudentAttentance::model()->isHoliday($date);
															if($date <= date('Y-m-d') and $is_holiday==0 and $batch->start_date <= $date and $date <= $batch->end_date){
															echo CHtml::link(Yii::t('app','Generate PDF'), array('Default/dailyPdf','batch'=>$batch->id, 'date'=>$date, 'id'=>$employee->id),array('class'=>'btn btn-danger  pull-right','target'=>'_blank')); 
															}?>
                                                           </div>
                                                           
                                                  		</div>
                                                        <?php
													if($date > date('Y-m-d')){
														echo '<div class="not-found-box"><i class="os_no_found">'.Yii::t("app", "Cannot mark attendance for this date").'</i></div>';
													}
													else{
														if($is_holiday){
															echo '<div class="not-found-box"><i class="os_no_found">'.Yii::t("app", "Selected day is a Holiday").'</i></div>';
														}
														else{
															$week = date('w', strtotime($date))+1;
															
															if($batch->start_date <= $date and $date <= $batch->end_date){
															$weekday		= Weekdays::model()->find("batch_id=:x AND weekday=:weekday", array(':x'=>$bid, ':weekday'=>($day + 1)));										
															if($weekday==NULL)
																$weekday	= Weekdays::model()->find("batch_id IS NULL AND weekday=:weekday", array(':weekday'=>($day + 1)));
															
															if($weekday!=NULL){
															?>
																													
															<div class="clearfix"></div>
															
															<div class="student-timetable-grid">
																<div class="timetable-grid-scroll">
																	<table border="0" align="center" width="100%" id="table" cellspacing="0">
																		<tbody>
																			<tr>
                                                                            <?php
                                                                             if(Configurations::model()->rollnoSettingsMode() != 2){?>
                                                                                <th width="80" class="loader"><?php echo Yii::t('app','Roll No');?></th>
																			<?php } ?>
																				<th width="80" class="loader"><?php echo Yii::t('app','Name');?></th>
																				<?php 
																				foreach($timings as $timing_1){
																					$time1=date($timeformat,strtotime($timing_1->start_time));
																					$time2=date($timeformat,strtotime($timing_1->end_time));
																					echo '<th width="130px" class="td">';
																					echo '<center><div class="top">'.$time1.' - '.$time2.'</div></center>';
																					?>
																					<div class="student-suject">	
																					<?php
																					$set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$bid,'weekday_id'=>$weekday['weekday'],'class_timing_id'=>$timing_1->id, 'employee_id'=>$employee->id));
																					if($set->is_elective==0){
																						$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));																	
																						if($time_sub!=NULL){
																							echo '<div class="batch_name">'.$time_sub->name,'</div>';                                                                                		$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
																							if($time_emp!=NULL){
																								$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));
																								
																								if($is_substitute and in_array($is_substitute->date_leave,$date_between)){
																									$employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
																									echo '<div class="employee">'.ucfirst($employee->first_name).' '.ucfirst($employee->middle_name).' '.ucfirst($employee->last_name).'</div>';
																								}
																								else{
																									if($time_sub!=NULL){
																										echo '<div class="employee">'.ucfirst($time_emp->first_name).' '.ucfirst($time_emp->middle_name).' '.ucfirst($time_emp->last_name).'</div>';
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
																							echo '<div class="batch_name">'.$electname->name.'</div>';
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
																				$batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
																			?>
																			<tr>
                                                                             <?php
                                                                             if(Configurations::model()->rollnoSettingsMode() != 2){
																				 ?>
                                                                             	<td><p><?php
																					if($batch_student!=NULL and $batch_student->roll_no!=0){
																							 echo $batch_student->roll_no;
																						}
																						else{
																							echo '-';
																						}
																						?>
                                                                                     </p>
																				</td> 
                                                                             <?php } ?>
																				<td class="td daywise-block">
																					<p><?php echo $student->studentFullName(); ?></p>
																				</td> 
                                                                                <?php
																			if($date < $student->admission_date){
																			?>
																				<td class="td" colspan="<?php echo $count_timing;?>"><?php echo '<div class="table-not-fount">'.Yii::t('app', 'Student has not joined yet'.'<div>');?></td>
																			<?php
																			}
																			else{
																				for($i=0;$i<$count_timing;$i++){ 
																					$set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$bid,'weekday_id'=>$weekday['weekday'],'class_timing_id'=>$timings[$i]['id'], 'employee_id'=>$employee->id));
																					 ?>	
																					<td class="td"> 
                                                                                 <div class=" mark-atnd-posin" >   	
																					<?php
																					if($set->is_elective == 2){ 
																							$elective			=	Electives::model()->findByAttributes(array('batch_id'=>$bid, 'id'=>$set->subject_id)); 
																							$student_elective	=	StudentElectives::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$batch->id, 'elective_group_id'=>$elective->elective_group_id)); 
																							if($student_elective==NULL){
																								$visible=1; // not assigned
																							}else{  
																								$visible=0; // assigned
																							}															
																						}else{
																							$visible=0;
																						} 
																					if($date >= $student->admission_date and $date <= date("Y-m-d") and $date >= $batch->start_date and $date <= $batch->end_date){
																						if($set == NULL or $visible==1){
																								$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timings[$i]['id'],'is_break'=>1));
																								if($is_break!=NULL){	
																									echo '<div class="not_joined">'.Yii::t('app', 'Break').'</div>';
																								}
																								else{ 
																									echo '<div class="not_joined">'.Yii::t('app', 'Not Assigned').'</div>';
																								}
																						
																						}
																						else{
																							$subjectwise =  StudentSubjectwiseAttentance::model()->findByAttributes(array('student_id'=>$student->id, 'timetable_id'=>$set->id, 'weekday_id'=>$set->weekday_id, 'date'=>$date));
																							
																							
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
																										'class'=>'mark_leave atnd_present',
																										'title'=>Yii::t('app', 'Mark as Absent')
																									)
																								);
																								?>
                                                                                             
																								<div class="student-action-box comn-tooltip">		
																									<?php // pop up for tick mark
																								
																										echo CHtml::link(
																											'<span>Mark Absent</span>',
																											'javascript:void(0);',
																											array(
																												'class'=>'student-timtable-update open_popup',
																												'data-ajax-url'=>$this->createUrl(
																													'/teachersportal/default/mark',
																													array(
																														'id' =>$subjectwise->id,
																														'timetable_id' =>$set->id,
																														'student_id' =>$student->id,
																														'weekday_id' =>$set->weekday_id,
																														'subject_id' =>$set->subject_id,
																														'date'=>$date
																													)
																												),
																												
																											'data-target'=>"#myModal",
																												'data-toggle'=>"modal",
																												'data-modal-label'=>Yii::t("app", "Mark Subject Wise Attendance"),
																												'data-modal-description'=>Yii::t("app", "Add Leave"),
																												'title'=>Yii::t('app','Edit'))
																										);?>
																									
																								</div>
																								<?php
																							}
																							else{
																							?>
                                                                                            </div>
                                                                                            <div class="mark-atnd-posin">
																								<div class="mark-absent-blk" >
																									
																										<?php // cross mark 
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
																												'class'=>'mark_leave atnd_absent',
																												'title'=>Yii::t('app','Mark as Present')
																											)
																										);
																										?>
																									       
																								</div>
																								<div class="student-action-box comn-tooltip">		
																									<?php //pop up for cross mark
																										echo CHtml::link(
																											'<span>Update Reason</span>',
																											'javascript:void(0);',
																											array(
																												'class'=>'student-timtable-update open_popup',
																												'data-ajax-url'=>$this->createUrl(
																													'/teachersportal/default/mark',
																													array(
																														'id' =>$subjectwise->id,
																														'timetable_id' =>$set->id,
																														'student_id' =>$student->id,
																														'weekday_id' =>$set->weekday_id,
																														'subject_id' =>$set->subject_id,
																														'date'=>$date
																													)
																												),
																												'data-target'=>"#myModal",
																												'data-toggle'=>"modal",
																												'data-modal-label'=>Yii::t("app", "Update Subject Wise Attendance"),
																												'data-modal-description'=>Yii::t("app", "Edit  Leave"),
																												'title'=>Yii::t('app','Edit')
																											)
																										);?>
																									
																								</div>
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
														</div> <!-- END div class="timetable" -->
														<?php 
															}
															else{
																echo '<i>'.Yii::t('app','Timetable is not set for this date').'</i>';
															}
														}
														else{
															echo '<i>'.Yii::t('app','No class on this date').'</i>';
														}
                                                    }
												}
											}
											else{
												echo '<i>'.Yii::t('app','No Class Timings').'</i>';
											}
										}
									}
                                        ?>                            
                                        </div>
                                        <div class="clear"></div>
                                   	</div> 
             						<!-- Attendance table end -->
							</div>
						</div>
					</div>
							
					<?php	}
					?>
				</div>
			</div>
		  <div class="clear"></div>
		</div>
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
				url:'<?php echo $this->createUrl('default/status');?>',
				data:datas,
				dataType:"json",
				beforeSend: function(){
					that.addClass('processing');
				},
				success: function(response){					
					that.removeClass('processing');
					if(response.status=="success"){						
						that.toggleClass('atnd_present atnd_absent');
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
					open_popup_links();
					//$('#myModal').modal();
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