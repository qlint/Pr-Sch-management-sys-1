<!-- Begin Coda Stylesheets -->
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/coda-slider-2.0.css" type="text/css" media="screen" />
<script type='text/javascript' src='<?php echo Yii::app()->request->baseUrl; ?>/js/portal/fullcalendar/fullcalendar.js'></script>
<script language="javascript">
	function showsearch()
	{
		if ($("#seachdiv").is(':hidden'))
		{
			$("#seachdiv").show();
		}
		else
		{
			$("#seachdiv").hide();
		}
	}

	function getstudent() // Function to see student profile
	{
		var studentid = document.getElementById('studentid').value;
		var yearid = document.getElementById('yearid').value;
		if(studentid!='' && yearid!='')
		{
			window.location= 'index.php?r=parentportal/default/attendance&id='+studentid+'&yid='+yearid;	
		}
		else
		{
			window.location= 'index.php?r=parentportal/default/attendance';
		}
	}

</script>

<?php Yii::app()->clientScript->registerCoreScript('jquery');?>



<?php $this->renderPartial('leftside');?> 
    <?php
    $cal ='{
    title: "'.Yii::t('app','All Day Event').'",
    start: new Date(y, m, 1)
    },';
    $m='';
    $d='';
    $y='';
    
    $guardian = Guardians::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$students = Students::model()->findAllByAttributes(array('parent_id'=>$guardian->id));
	if(count($students)==1) // Single Student 
	{
		$attendances = StudentAttentance::model()->findAll('student_id=:x group by date',array(':x'=>$students[0]->id));
		
	}
	elseif(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL) // If Student ID is set
	{
		$attendances = StudentAttentance::model()->findAll('student_id=:x group by date',array(':x'=>$_REQUEST['id']));
		
	}
	elseif(count($students)>1) // Multiple Student
	{
    	$attendances = StudentAttentance::model()->findAll('student_id=:x group by date',array(':x'=>$students[0]->id));
	}
    foreach($attendances as $attendance)
    {
		$m=date('m',strtotime($attendance['date']))-1;
		$d=date('d',strtotime($attendance['date']));
		$y=date('Y',strtotime($attendance['date']));
		$cal .= "{
		title: '".'<div align="center" title="Reason: '.$attendance->reason.'"><img src="images/portal/atend_cross.png" width="26" border="0"  height="25" /></div>'."',
		start: new Date('".$y."', '".$m."', '".$d."')
		},";
    
    }
	
	$all_holidays = Holidays::model()->findAll();
	
	$holiday_arr=array();
	foreach($all_holidays as $key=>$holiday)
	{
		if(date('Y-m-d',$holiday->start)!=date('Y-m-d',$holiday->end))
		{
			$date_range = StudentAttentance::model()->createDateRangeArray(date('Y-m-d',$holiday->start),date('Y-m-d',$holiday->end));
			foreach ($date_range as $value) {
				
				$m=date('m',strtotime($value))-1;
				$d=date('d',strtotime($value));
				$y=date('Y',strtotime($value));
				$cal .= "{
				title: '".'<div align="center" title="Reason: '.$holiday->title.'"><img src="images/portal/holiday.png" width="40" border="0"  height="40" /></div>'."',
				start: new Date('".$y."', '".$m."', '".$d."')
				},";
				
				
			}
		}
		else
		{
			
				$m=date('m',strtotime(date('Y-m-d',$holiday->start)))-1;
				$d=date('d',strtotime(date('Y-m-d',$holiday->start)));
				$y=date('Y',strtotime(date('Y-m-d',$holiday->start)));
				$cal .= "{
				title: '".'<div align="center" title="Reason: '.$holiday->title.'"><img src="images/portal/holiday.png" width="40" border="0"  height="40" /></div>'."',
				start: new Date('".$y."', '".$m."', '".$d."')
				},";	
		}
	}
    ?>
 
<div class="pageheader">
    <div class="col-lg-8">
     <h2><i class="fa fa-file-text"></i> <?php echo Yii::t('app','Attendance'); ?> <span><?php echo Yii::t('app','View your attendance here'); ?></span></h2>
    </div>
    
   
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
         
          <li class="active"><?php echo Yii::t('app','Attendance'); ?></li>
        </ol>
      </div>
     
     <div class="clearfix"></div>
      
    </div>
<script type='text/javascript'>
$.noConflict();
jQuery( document ).ready(function( $ ) {
        $(document).ready(function(){
			var date = new Date();
			var d = date.getDate();
			var m = date.getMonth();
			var y = date.getFullYear();
			var calendar = $('#calendar').fullCalendar({
			header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
			},
			selectable: false,
			selectHelper: true,
			dayNames:["sun","mon","tue","wed","thu","fri","sat"],
			select: function(start, end, allDay) {
				var title = prompt('<?php echo Yii::t('app','Event Title:'); ?>');
				if (title) {
					calendar.fullCalendar('renderEvent',
					{
						title: title,
						start: start,
						end: end,
						allDay: allDay
					},
					true // make the event "stick"
					);
				}
				calendar.fullCalendar('unselect');
			},
			editable: false,
			events: [ <?php echo $cal; ?>]
			});
        });
        }); 
        </script>
        
        <script type="text/javascript">
        $(document).ready(function(){
			$("#shbar").click(function(){
			$('#tpanel').toggle();
			});
        });
        </script>
        <div class="contentpanel">
        	<div class="panel-heading">
<?php
		
			if($_REQUEST['id']!=NULL && $_REQUEST['yid']!=NULL)
			{
				//$yeardetails = AcademicYears::model()->findByPk($_REQUEST['yid']);
				$strtyear = $_REQUEST['yid'];
				//$endyear = date('Y',strtotime($yeardetails->end));
				$studentdetails = Students::model()->findByPk($_REQUEST['id']);
				$batch = Batches::model()->findByPk($studentdetails->batch_id);
						?>
                        	
							<h3  class="panel-title"><?php echo Yii::t('app','Student Attendance Report');?></h3>
                            <!-- Yearly PDF -->
                            
								
							
                            <div class="clearfix"></div>
                            </div>
                            <div class="people-item">
                            <div class="attendance-ul-block">
                                <ul>
                                    <?php echo '<li>'.CHtml::link(Yii::t('app','Generate PDF'), array('/studentportal/default/AttendancePdf','id'=>$_REQUEST['id'],'yid'=>$_REQUEST['yid']),array('target'=>"_blank",'class'=>'btn btn-danger')).'</li>'; ?>
                                </ul>
                            </div>                       
                            <!-- END Yearly PDF -->
                            <!-- Yearly Report Table -->
                            <div class="table-responsive">
                            	<table class="table table-hover mb30">
                                    <tr>
                                       <?php /*?> <th><?php echo Yii::t('app','Sl No');?></th><?php */?>
                                        <th><?php echo Yii::t('app','Adm No');?></th>
                                        <?php if($studentdetails->studentFullName("forStudentPortal")!=""){?>
                                        <th><?php echo Yii::t('app','Name');?></th>
                                        <?php } ?>
                                        <th><?php echo Yii::t('app','Working Days');?></th>
                                        <th><?php echo Yii::t('app','Leaves');?></th>
                                    </tr>
                                    <?php
									$yearly_sl = 1;
									//foreach($students as $student) // Displaying each employee row.
									//{
									?>
                                    <tr>
                                    	<?php /*?><td style="padding-top:10px; padding-bottom:10px;"><?php echo $yearly_sl; $yearly_sl++;?></td><?php */?>
                                        <td><?php echo $studentdetails->admission_no; ?></td>
                                        <?php if($studentdetails->studentFullName("forStudentPortal")!=""){?>
                                        <td>
											<?php echo $studentdetails->studentFullName("forStudentPortal");?>
										</td>
										<?php } ?>
                                        <td>
											<?php																																									
												if($studentdetails->admission_date>=$batch->start_date){ 
													$start_date  	= date('Y-m-d',strtotime($studentdetails->admission_date));												
												}
												else{
													$start_date  	= date('Y-m-d',strtotime($batch->start_date));
												}													
									
												if($batch->end_date >= date('Y-m-d')){
													$end_date		= date('Y-m-d');												
												}
												else{
													$end_date		= date('Y-m-d', strtotime($batch->end_date));
												}
												
									
												$batch_days_1  = array();
												$batch_range_1 = StudentAttentance::model()->createDateRangeArray($start_date,$end_date);  // to find total session
												$batch_days_1  = array_merge($batch_days_1,$batch_range_1);
												
												$days = array();
												$days_1 = array();
												$weekArray = array();
												
												$total_working_days_1 = array();
												$weekdays = Weekdays::model()->findAll("batch_id=:x AND weekday<>:y", array(':x'=>$batch->id,':y'=>"0"));
												if(count($weekdays)==0)
												{
													
													$weekdays = Weekdays::model()->findAll("batch_id IS NULL AND weekday<>:y",array(':y'=>"0"));
												}
												
												foreach($weekdays as $weekday)
												{
													
													$weekday->weekday = $weekday->weekday - 1;
													if($weekday->weekday <= 0)
													{
														$weekday->weekday = 7;
													}
													$weekArray[] = $weekday->weekday;
												}
									
									
									
												foreach($batch_days_1 as $batch_day_1)
												{
													$week_number = date('N', strtotime($batch_day_1));
													if(in_array($week_number,$weekArray)) // If checking if it is a working day
													{
														array_push($days_1,$batch_day_1);
													}
												}
												$holiday_arr[] =array();
												$ischeck = Configurations::model()->findByPk(43);
												
												if($ischeck->config_value != 1)
												{
													$holidays = Holidays::model()->findAllByAttributes(array('user_id'=>1));
													$holiday_arr=array();
													foreach($holidays as $key=>$holiday)
													{
														if(date('Y-m-d',$holiday->start)!=date('Y-m-d',$holiday->end))
														{
															$date_range = StudentAttentance::model()->createDateRangeArray(date('Y-m-d',$holiday->start),date('Y-m-d',$holiday->end));
															foreach ($date_range as $value) {
																$holiday_arr[] = date('Y-m-d',$date_range);
															}
														}
														else
														{
															$holiday_arr[] = date('Y-m-d',$holiday->start);
														}
													}
												}
												
												
												foreach($days_1 as $day_1)
												{
													
													if(!in_array($day_1,$holiday_arr)) // If checking if it is a working day
													{
														array_push($total_working_days_1,$day_1);
													}
												}
						  
												
												echo count($total_working_days_1);													
                                            ?>
                						</td>
                                         <!-- Yearly Attendance column -->
                                        <td>
                                        	<?php
											$leavedays 				= array();
											$criteria 				= new CDbCriteria;		
											$criteria->join 		= 'LEFT JOIN student_leave_types t1 ON (t.leave_type_id = t1.id OR t.leave_type_id = 0)'; 
											$criteria->condition 	= 't1.is_excluded=:is_excluded AND t.student_id=:x AND t.date >=:z AND t.date <=:A AND t.batch_id=:batch_id';											
											$criteria->params 		= array(':is_excluded'=>0,':x'=>$studentdetails->id,':z'=>$start_date,':A'=>$end_date, 'batch_id'=>$batch->id);
											$criteria->order		= 't.date DESC';
											$leaves    				= StudentAttentance::model()->findAll($criteria);
											
											foreach($leaves as $leave){
												if(!in_array($leave->date,$leavedays)){
													array_push($leavedays,$leave->date);
												}
											}
											echo count($leavedays);
											?>
                                        </td>
                                        <!-- End Yearly Attendance column -->
                                    </tr>
                                    <?php /*?><?php
									}
									?><?php */?>
								</table>
                            </div>
                            <h3 class="panel-title"><?php echo Yii::t('app','Leave Details');?></h3><br />

                            <div class="table-responsive">
                                    <table class="table table-hover mb30">
                                        <tr>
                                            <th><?php echo Yii::t('app','Sl No');?></th>
                                            <th><?php echo Yii::t('app','Leave Type');?></th>
                                            <th><?php echo Yii::t('app','Leave Date');?></th>
                                            <th><?php echo Yii::t('app','Reason');?></th>
                                        </tr>
                                        <?php
										$criteria 				= new CDbCriteria;												
										$criteria->condition 	= 't.student_id=:x AND t.date >=:z AND t.date <=:A AND t.batch_id=:batch_id';											
										$criteria->params 		= array(':x'=>$studentdetails->id,':z'=>$start_date,':A'=>$end_date, 'batch_id'=>$batch->id);
										$criteria->order		= 't.date DESC';
										$student_leaves			= StudentAttentance::model()->findAll($criteria);
										if($student_leaves!=NULL)
										{
											$individual_sl = 1;
											foreach($student_leaves as $studleave) // Displaying each leave row.
											{
												$exist_leave = StudentLeaveTypes::model()->findByAttributes(array('id'=>$studleave->leave_type_id));
												//if($exist_leave!=NULL)
												//{
											?>
											<tr>
												<td style="padding-top:10px; padding-bottom:10px;"><?php echo $individual_sl; $individual_sl++;?></td>
                                                <td>
                                                	<?php
														if($exist_leave!=NULL)
														{
															echo ucfirst($exist_leave->name);
														}
														else
															echo "-";
													?>
                                                </td>
												 <!-- Individual Attendance row -->
												<td>
													<?php 
													$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
													if($settings!=NULL)
													{	
														$studleave->date = date($settings->displaydate,strtotime($studleave->date));
													}
													echo $studleave->date; 
													?>
												</td>
												<td>
													<?php
													if($studleave->reason!=NULL)
													{
														echo ucfirst($studleave->reason);
													}
													else
													{
														echo '-';
													}
													?>
												</td>
												<!-- End Individual Attendance row -->
											</tr>
											<?php
												//}
											}
										}
										else
										{
										?>
                                        	<tr>
                                            	<td colspan="4" style="padding-top:10px; padding-bottom:10px;" align="center">
                                                	<strong><?php echo Yii::t('app','No leaves taken!'); ?></strong>
                                            
    </td>
                                            </tr>
                                        <?php
										}
										?>
                                    </table>
                                </div>
                            </div>
                            <!-- END Yearly Report Table -->
						<?php
						
			}
		?>
</div>
</div>
</div>