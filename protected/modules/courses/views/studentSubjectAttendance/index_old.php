<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css">


<script language="javascript">
function course(){
	var id = document.getElementById('bat').value;
	window.location= 'index.php?r=studentAttentance/index&id='+id;	
}
</script>
<?php
$batch				= Batches::model()->findByPk($_REQUEST['id']);
$this->breadcrumbs=array(
	Yii::t('app','Courses')=>array('/courses'),
	html_entity_decode($batch->name)=>array('/courses/batches/batchstudents','id'=>$_REQUEST['id']),
	Yii::t('app','Attendances'),
	Yii::t('app','Subject Wise'),
);
?>
<?php
$current_academic_yr 	= Configurations::model()->findByAttributes(array('id'=>35));
$yr 					= AcademicYears::model()->findByAttributes(array('id'=>$current_academic_yr->config_value));
if(isset($_REQUEST['date']))
	$date		=	date('m/d/Y',strtotime($_REQUEST['date']));
else
	$date		=	date("m/d/Y");
//$date				= (isset($_REQUEST['date']))?$date:date("Y-m-d");

$day 				= date('w', strtotime($date));
$week_start			= date('Y-m-d', strtotime('-'.$day.' days', strtotime($date)));

$week_end 			= date('Y-m-d', strtotime('+'.(6-$day).' days', strtotime($date)));
$prev_week_start	= date('Y-m-d', strtotime('-7 days', strtotime($week_start)));
$next_week_start	= date('Y-m-d', strtotime('+1 days', strtotime($week_end)));
$this_date			= $week_start;

$criteria				= new CDbCriteria;											
$criteria->join			= "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`id`";											
$criteria->condition	= "`t`.`is_active`=1 AND `t`.`is_deleted`=0 AND `bs`.`batch_id`=:batch_id AND `bs`.`status`=:status";
$criteria->params		= array(":batch_id"=>$batch->id, ':status'=>1);
$criteria->order		= "`t`.`first_name` ASC, `t`.`last_name` ASC";
$students				= Students::model()->findAll($criteria);

$stud_id				= isset($_REQUEST['stud_id'])?$_REQUEST['stud_id']:((count($students)>0)?$students[0]->id:NULL);
$bid					= $batch->id;

$student				= ($stud_id!=NULL)?Students::model()->findByPk($stud_id):NULL;
?>
 
<div style="background:#fff; min-height:800px;">   
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td valign="top">
                    <?php 
                    if($batch!=NULL){
                    ?>                        
                        <div class="clear"></div>
                        <div class="ful-page-block">
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
                                    <div class="clear"></div>  
                                     <!-- Attendance table start -->   
                                   <div class="subwis-tableposctn">
                                    <div class="formWrapper formWrapper-subwis">
                                        <div  style="width:100%">                            
                                            <div class="Nodata-bg">
                                                <div class="attnd-tab-sectn-blk">
                                                <div class="tab-sectn">
                                                <ul>
													<?php if(Configurations::model()->studentAttendanceMode() != 2){ ?>
                                                                    <li><?php echo CHtml::link(Yii::t("app","DAY WISE"), array("/courses/studentAttentance", "id"=>$bid), array("class"=>"sub-attnd-daily"));?> </li>
                                                   <?php } ?>    
                                                    <?php if(Configurations::model()->studentAttendanceMode() != 1){ ?>
                                                                    <li><?php echo CHtml::link(Yii::t("app","SUBJECT WISE"), array("/courses/studentSubjectAttendance/daily", "id"=>$bid), array("class"=>"sub-attnd-daily active-attnd"));?>  </li>
                                                   <?php } ?>      
                                                </ul>
                                                </div>
                                                </div>
                                                <div class="attnd-tab-inner-blk">
                                                <div class="attndwise-head">
                                                <h3><?php echo Yii::t('app','Subject wise Attendance');?></h3>
                                                </div>
                                                                            
                                                <?php 
												if($student==NULL){
													echo '<i>'.Yii::t("app", "Student not found").'</i>';
												}
												else if(count($students)==0){
													echo '<i>'.Yii::t("app", "No students found").'</i>';
												}
												else{                                   
                                                if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL){
                                                    $times		= Batches::model()->findAll("id=:x", array(':x'=>$bid));
                                                    $weekdays	= Weekdays::model()->findAll("batch_id=:x", array(':x'=>$bid));										
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
                                                    $criteria->params 		= array(':x'=>$bid);
                                                    $criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
                                                    $timings 				= ClassTimings::model()->findAll($criteria);
                                                    $count_timing = count($timings);
                                                    
                                                    if($timings!=NULL){?>
                                                    
                                                   		 <div class="pdf-box">
                                                            <div class="box-one">
                                                                <div class="atnd_table-calender-bg atnd_tnav-new box-one-lft-rght" align="center">
																	<?php
                                                                    echo CHtml::link('<div class="atnd-table-arow-left"><img src="'.Yii::app()->request->baseUrl.'/images/attnd-arow-left.png" height="13" width="7" border="0"></div>', array('/courses/studentSubjectAttendance', 'id'=>$batch->id, 'stud_id'=>$stud_id, 'date'=>$prev_week_start), array('title'=>Yii::t('app', 'Previous Week')));											
                                                                    ?>
                                                                    <div class="fixed-datepik">
                                                                        <p><?php
																		$month1	=	date("M", strtotime($week_start));
																		$month2	=	date("M", strtotime($week_end));
																		$day1	=	date("d", strtotime($week_start));	
																		$day2	=	date("d", strtotime($week_end));	  
																		
																		?>
                                                                      <input type="hidden" id="day" class="week-picker" value="<?php echo $date;?>"/>
                                                                        <input type="text" id="week"  value="<?php echo Yii::t("app",$month1).' '.$day1." - ".Yii::t("app",$month2).' '.$day2;?>"></input>
                                                                        </p>
                                                                    </div>
																	<?php
                                                                    echo CHtml::link('<div class="atnd-table-arow-right"><img src="'.Yii::app()->request->baseUrl.'/images/attnd-arow-right.png" height="13" width="7" border="0"></div>', array('/courses/studentSubjectAttendance', 'id'=>$batch->id, 'stud_id'=>$stud_id, 'date'=>$next_week_start), array('title'=>Yii::t('app', 'Next Week')));										
                                                                    ?>                       
                                                                </div>
                                                                <div class="subwise-blk box-one-lft-rght">
                                                                    <ul>
                                                                         <li>  <?php echo CHtml::link(Yii::t("app","Daily"), array("/courses/studentSubjectAttendance/daily", "id"=>$bid), array("class"=>"sub-attnd-daily"));?> </li>
                                                                        <li><?php echo CHtml::link(Yii::t("app","Weekly"), array("/courses/studentSubjectAttendance/index", "id"=>$bid), array("class"=>"sub-attnd-weekly active-attnd"));?>  </li>
                                                                    </ul>
                                                                </div> 
                                                                <div class="box-one-lft-rght attnde-selectbox">
 															<?php if(count($students)>0){?>
                                                            <?php $form=$this->beginWidget('CActiveForm',array('action'=>Yii::app()->createUrl('/courses/studentSubjectAttendance'),'method'=>'GET')); ?>											
                                                            <?php echo CHtml::hiddenField('id', $_REQUEST['id']);?>
                                                            <?php
                                                            if(isset($_REQUEST['date'])){
                                                            echo CHtml::hiddenField('date', $_REQUEST['date']);
                                                            }
                                                            ?>
                                                            <?php echo CHtml::dropDownList(
                                                            'stud_id',
                                                            (isset($_REQUEST["stud_id"]))?$_REQUEST["stud_id"]:"",
                                                            CHtml::listData(
                                                            $students,
                                                            'id',
                                                            'studentnameforstudentprofile'
                                                            ),
                                                            array(
															'encode'=>false,
                                                            'onchange'=>'js:this.form.submit();'
                                                            )
                                                            );?>                            
                                                            <?php $this->endWidget(); ?>
                                                            <?php }?>
                                                                </div>
                                                            </div>
                                                            <div class="box-two">
                                                            <?php   echo CHtml::link('Generate PDF', array('/attendance/studentSubjectAttendance/pdf','id'=>$stud_id, 'bid'=>$_REQUEST['id'], 'date'=>$date),array('target'=>'_blank','class'=>'pdf_but')); ?>
                                                            </div>
                                                            </div>
                                                            
                                    <div class="clearfix"></div>
                                    <div class="attendance-table-block">
                                    <?php  if($batch->start_date <= $week_start and $week_start <= $batch->end_date ){?>
                                    <div class="attendance-table-scroll">
                                     <div id="attendanceDialog"></div>
                                     <div class="attendance-table-block-tbl">
                                    <table border="0" align="center" width="100%" id="table" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <th width="80" class="loader">&nbsp;</th><!--timetable_td_tl -->
                                                <?php 
												$weekday_attributes	= array(1=>'on_sunday', 2=>'on_monday', 3=>'on_tuesday', 4=>'on_wednesday', 5=>'on_thursday', 6=>'on_friday', 7=>'on_saturday');
                                                foreach($timings as $timing_1)
                                                {
                                                    $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                                                    if($settings!=NULL)
                                                    {
															//traslate AM and PM 	
															$t1 = date('h:i', strtotime($timing_1->start_time));	
															$t2 = date('A', strtotime($timing_1->start_time));
															
															$t3	= date('h:i', strtotime($timing_1->end_time));	
															$t4	= date('A', strtotime($timing_1->end_time));	
															//end 	
                                                        $time1=date($settings->timeformat,strtotime($timing_1->start_time));
                                                        $time2=date($settings->timeformat,strtotime($timing_1->end_time));
                                                    }
                                                    echo '<th width="130px" class="td"><center><div class="top">'.$t1.' '.Yii::t("app",$t2).' - '.$t3.' '.Yii::t("app",$t4).'</div></center></th>';	
                                                }
                                                ?>
                                            </tr> <!-- timetable_tr -->
                                            <?php
                                            $weekday_text = array('SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT');
											$weekday_count	= 0;													
                                            foreach($weekdays as $weekday){														
                                                if($weekday['weekday']!=0) // SUNDAY
                                                {
                                                ?>
                                                <tr>
                                                		<td class="td daywise-block">
                                                            <h3><?php echo Yii::t('app',$weekday_text[$weekday['weekday']-1]);?></h3>
                                                            <p><?php echo date("d M Y", strtotime($this_date)); ?></p>
                                                            <?php $weekday_count++; ?>
                                                        </td>
                                                    
                                                    <?php
                                                    for($i=0;$i<$count_timing;$i++)
                                                    {
														$criteria				= new CDbCriteria;
														$criteria->join			= 'JOIN `class_timings` `t1` ON `t1`.`id` = `t`.`class_timing_id`';												
														$criteria->condition	= '`t`.`batch_id`=:batch_id AND `t`.`weekday_id`=:weekday_id AND `t`.`class_timing_id`=:class_timing_id';
														$criteria->params		= array(':batch_id'=>$bid, ':weekday_id'=>$weekday['weekday'], ':class_timing_id'=>$timings[$i]['id']);
														
														$set =  TimetableEntries::model()->find($criteria);
														
														$is_timing = ClassTimings::model()->findByAttributes(array('id'=>$timings[$i]['id']));
														if($is_timing){
															$t1 = date('h:i', strtotime($is_timing->start_time));	
															$t2 = date('A', strtotime($is_timing->start_time));
															
															$t3	= date('h:i', strtotime($is_timing->end_time));	
															$t4	= date('A', strtotime($is_timing->end_time));
															//echo $t1;exit;
															$is_class_timing 	= StudentAttentance::model()->isClassTiming($t1, $t2,$t3,$t4,$this_date,$bid); 
														}
                                                    ?>	
                                                      <td class="td"> 	
                                                     <?php
													 if($batch->start_date <= $this_date and $this_date <= $batch->end_date ){
													 if($is_class_timing == 1){
													 if($set == NULL){
															$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timings[$i]['id'],'is_break'=>1));
															if($is_break!=NULL)
															{	
																echo Yii::t('app','Break');
															}
													 }   
													 else
                                                        {	
															$visible=0;
															$subjectwise =  StudentSubjectwiseAttentance::model()->findByAttributes(array('student_id'=>$stud_id, 'timetable_id'=>$set->id, 'weekday_id'=>$set->weekday_id, 'date'=>$week_start)); 
															if($set->is_elective == 2){ 
																$student_elective	=	StudentElectives::model()->findByAttributes(array('student_id'=>$stud_id, 'batch_id'=>$batch->id, 'elective_id'=>$set->subject_id)); 
																if($student_elective==NULL){
																	$visible=1;
																}else{
																	$visible=0;
																}															
															}else{
																$visible=0;
															} 
															$is_holiday		= StudentAttentance::model()->isHoliday($week_start);
															if($is_holiday == NULL){
																if($subjectwise == NULL){
																		if($batch->start_date <= $week_start and $week_start <= $batch->end_date ){
																		if($week_start >= $student->admission_date and $week_start <= date("Y-m-d") and $visible==0){ 
															echo CHtml::ajaxLink(
																	Yii::t('app','Mark Leave'),
																	$this->createUrl('studentSubjectAttendance/mark'),
																	array(
																		'onclick'=>'$("#attendanceDialog").dialog("open");return false;',
																		'update'=>'#attendanceDialog',
																		'type' =>'GET',
																		'data' => array(
																			'timetable_id' =>$set->id,
																			'student_id' =>$stud_id,
																			'subject_id'=>$set->subject_id,
																			'weekday_id' =>$set->weekday_id,
																			'date'=>$week_start
																		),
																		'dataType' => 'text'
																	),
																	array(
																		'id'=>'mak-attendance-'.$stud_id.'-'.$set->id,
																		'class'=>'mark_leave'
																	)
																);
															echo '';
																			}
														
																		}
																}
																else{
																?>
                                                                <div class="action-box">		
																	<?php
																		echo CHtml::ajaxLink(
																			'',
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
																				'class'=>'timtable-update',
																				 'title'=>Yii::t('app','Update')
																			)
																		);
															
																		echo CHtml::link(
																						'',
																						"#",
																						array(
																							'submit'=>array(
																								'studentSubjectAttendance/remove',
																								'id'=>$subjectwise->id,
																								'bid'=>$bid,
																								'stud_id'=>$stud_id,
																								'date'=>$week_start
																							),
																							'confirm'=>Yii::t('app','Are you sure you want to remove absent ?'),
																							'csrf'=>true,
																							'class'=>'timtable-delt',
																							 'title'=>Yii::t('app','Remove')
																						)
																					); 
																		
																		
																		?>
                                                                </div>	
                                                                <div class="mark-absent-blk" >                                                                
                                                                    <p>
																		<?php 
                                                                        echo CHtml::ajaxLink(
																							Yii::t('app','Absent'),
																							$this->createUrl('studentSubjectAttendance/view'),
																							array(
																								'onclick'=>'$("#attendanceDialog").dialog("open");return false;',
																								'update'=>'#attendanceDialog',
																								'type'=>'GET',
																								'data'=>array(
																									'id' =>$subjectwise->id
																								),
																								'dataType' => 'text'
																							),
																							array(
																								'id'=>'view-attendance-'.$subjectwise->id,
																								'class'=>'view',
																								'title'=>Yii::t('app','View')
																							)
																						);
																		?>
                                                                     </p>       
                                        						</div>
                                                                <?php }
																}
																else{
																		echo '<div class="attnd-holiday">'.Yii::t('app','Holiday').'</div>';
																	}?>
                                         								<div id="jobDialog_view_div<?php echo $subjectwise->id; ?>"></div>	
                                                                            <div  onclick="" style="position: relative; ">
                                                                            <div class="timtable-subjct-blk">
                                                                                <div class="subject">	
                                                                                <?php
																				
																				if($set->is_elective==0)
																				{
																					$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));																	
																					if($time_sub!=NULL)
																					{
																						echo $time_sub->name;
																						
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
																						echo '';
																					}
																				}
																				else
																				{
																					$elec_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
																					
																					
																					$electname = ElectiveGroups::model()->findByAttributes(array('id'=>$elec_sub->elective_group_id,'batch_id'=>$bid));
																					
																					
																					if($electname!=NULL and $visible == 0)
																					{
																						echo $electname->name;
																						
																					}
																					$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
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
																							if($electname!=NULL)
																							{
																								//echo '<div class="employee">'.ucfirst($time_emp->first_name).' '.ucfirst($time_emp->middle_name).' '.ucfirst($time_emp->last_name).'</div>';
																							}
																						}
																					}
																					
																				}
																				?>
                                                                                 </div>
                                                                               
                                                                            </div>
                                                                        </div>
																	<?php	
																		}
                                                        
                                                        ?>
                                                        <?php echo 	'</div>
                                                        </div>
                                                        </div>
                                                        <div id="jobDialog'.$timing[$i]['id'].$weekday['weekday'].'"></div>
                                                        </td>'; 
													 }
													 }
                                                    }
                                                    ?>
                                                </tr>
                                                <?php
													
                                                }
												$this_date	= date("Y-m-d", strtotime("+1 days", strtotime($this_date))); 
												$week_start	= date("Y-m-d", strtotime("+1 days", strtotime($week_start))); 
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                  </div> 
                                 
                                </div>
                                 <?php
								   }
								   else
								   {
									    echo '<div class="not-found-box"><i class="os_no_found">'.Yii::t('app','No class on this date').'</i></div>';
								   }
								   ?>
                                </div> <!-- END div class="timetable" -->
                                    </div>                       
                                                            
                                                        
                                                    <?php 
                                                    }
                                                    else{
                                                        echo '<i>'.Yii::t('app','No Class Timings').'</i>';
                                                    }
                                                }   
											}
                                                ?> 
                                            </div>                            
                                        </div>
                                        <div class="clear"></div>
                                        </div> 
             						 <!-- Attendance table end --> 
                                    
                                    <div class="ea_pdf" style="top:22px; ">
                                    	
                                    </div>
                                </div>
                                <?php
								}
								?>

                    <?php
					}
					?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

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
            startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - dayAdjustment);
            endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - dayAdjustment + 6);

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
		//dateFormat: 'dd/mm/yy',
        onSelect: function (dateText, inst) {
            setDates(this,1); 
			<?php $url = Yii::app()->createUrl("/courses/studentSubjectAttendance",array('id'=>$batch->id, 'stud_id'=>$stud_id))?>
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



<script>
$('.abs').click(function(e) {
    $('form#student-attentance-form').remove();
});
</script>