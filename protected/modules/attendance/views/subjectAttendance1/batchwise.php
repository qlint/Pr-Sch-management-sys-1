<style type="text/css">
.tt-subject{ min-height:inherit;
 padding: 0;}
 
 .timetable .td {
    font-size: 12px;
	padding:7px;
}
.name {
    padding: 5px;
}
</style>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Attendances')=>array('/attendance'),
	Yii::t('app','Student Attendances'),
);

?>
<div style="background:#fff; min-height:800px;">   
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td valign="top">
                    <div style="padding:20px;">
                    <?php 
                    if($batch!=NULL){
                    ?>
                       
                        <div class="clear"></div>
                        <div class="emp_right_contner">
                            <div class="emp_tabwrapper">
								<?php $this->renderPartial('/default/tab');?>
                                <div class="clear"></div>
                                
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
								$is_insert = PreviousYearSettings::model()->findByAttributes(array('id'=>2));
								$is_edit   = PreviousYearSettings::model()->findByAttributes(array('id'=>3));
								$is_delete = PreviousYearSettings::model()->findByAttributes(array('id'=>4));
								
								if($year != $current_academic_yr->config_value and ($is_insert->settings_value==0 or $is_edit->settings_value==0 or $is_delete->settings_value==0))
								{
								?>
									<div>
										<div class="yellow_bx" style="background-image:none;width:95%;padding-bottom:45px;">
											<div class="y_bx_head" style="width:95%;">
											<?php 
												echo Yii::t('app','You are not viewing the current active year. ');
												if($is_insert->settings_value==0 and $is_edit->settings_value!=0 and $is_delete->settings_value!=0)
												{ 
													echo Yii::t('app','To mark the attendance, enable Create option in Previous Academic Year Settings.');
												}
												elseif($is_insert->settings_value!=0 and $is_edit->settings_value==0 and $is_delete->settings_value!=0)
												{
													echo Yii::t('app','To edit the attendance, enable Edit option in Previous Academic Year Settings.');
												}
												elseif($is_insert->settings_value!=0 and $is_edit->settings_value!=0 and $is_delete->settings_value==0)
												{
													echo Yii::t('app','To delete the attendance, enable Delete option in Previous Academic Year Settings.');
												}
												else
												{
													echo Yii::t('app','To manage the the attendance, enable the required options in Previous Academic Year Settings.');	
												}
											?>
											</div>
											<div class="y_bx_list" style="width:650px;">
												<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
											</div>
										</div>
									</div>
								<?php
								}
								
								?>
                                
                                
                                
                                
                                
                                <div style="position:relative">
                <?php
	
  if($batch)
  {
	    $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
		
	  	if(isset($_REQUEST['date']))
			$curr_date = date('Y-m-d',strtotime($_REQUEST['date']));
		else
			$curr_date = date('Y-m-d');

		$date = mktime(0, 0, 0,date('m',strtotime($curr_date)),date('d',strtotime($curr_date)),date('Y',strtotime($curr_date))); 
		$week = date('w', $date);
		 
		
		//students from current batch.......
		$criteria = new CDbCriteria;
		$criteria->condition = 'is_deleted=:is_deleted AND is_active=:is_active';
		$criteria->params[':is_deleted'] = 0;
		$criteria->params[':is_active'] = 1;

		$batch_students = BatchStudents::model()->findAllByAttributes(array('batch_id'=>$batch->id,'status'=>1));
		if($batch_students)
		{
			$count = count($batch_students);
			$criteria->condition = $criteria->condition.' AND (';
			$i = 1;
			foreach($batch_students as $batch_student)
			{
				
				$criteria->condition = $criteria->condition.' id=:student'.$i;
				$criteria->params[':student'.$i] = $batch_student->student_id;
				if($i != $count)
				{
					$criteria->condition = $criteria->condition.' OR ';
				}
				$i++;
				
			}
			$criteria->condition = $criteria->condition.')';
		}
		else
		{
			$criteria->condition = $criteria->condition.' AND batch_id=:batch_id';
			$criteria->params[':batch_id'] = $_REQUEST['id'];
		}

		
		
		$students = Students::model()->findAll($criteria);
		$begin_date = date('Y-m-d',strtotime($batch->start_date)); 
		$end_date = date('Y-m-d',strtotime($batch->end_date));
		
		//holidays..........
		$holidays = Holidays::model()->findAll();
		$holiday_arr=array();
		foreach($holidays as $key=>$holiday)
		{
			if(date('Y-m-d',$holiday->start)!=date('Y-m-d',$holiday->end))
			{
				$date_range = StudentAttentance::model()->createDateRangeArray(date('Y-m-d',$holiday->start),date('Y-m-d',$holiday->end));
				foreach ($date_range as $value) {
					$holiday_arr[$value] = $holiday->id;
				}
			}
			else
			{
				$holiday_arr[date('Y-m-d',$holiday->start)] = $holiday->id;
			}
		}
		
		
		
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
		
		$weekdays=Weekdays::model()->findAll("batch_id=:x", array(':x'=>$batch->id));										
		if(count($weekdays)==0)
			$weekdays=Weekdays::model()->findAll("batch_id IS NULL");
		
		
		// find all weeks inside batch duration..............
		$weekArray = array();
		foreach($weekdays as $weekday)
		{
			$weekday->weekday = $weekday->weekday - 1;
			if($weekday->weekday <= 0)
				$weekday->weekday = 7;
			$weekArray[] = $weekday->weekday;
		}
		
		
		
		$days_name = array(0=>"SUN",1=>"MON",2=>"TUE",3=>"WED",4=>"THRU",5=>"FRI",6=>"SAT");
		
		$criteria=new CDbCriteria;
		$criteria->condition = "batch_id=:x";
		$criteria->params = array(':x'=>$batch->id);
		$criteria->order = "STR_TO_DATE(start_time, '%h:%i %p')";  
		$timing = ClassTimings::model()->findAll($criteria);
		$count_timing = count($timing);
		
		
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
		if($settings!=NULL)
			$date_format=$settings->displaydate;
		else
			$date_format = 'dd-mm-yy';	
		
		
		
		//find working days............
		$days = array();
		$batch_days = array();
		$batch_range = StudentAttentance::model()->createDateRangeArray($begin_date,$end_date);
		$batch_days = array_merge($batch_days,$batch_range);
		foreach($batch_days as $batch_day)
		{
			$week_number = date('N', strtotime($batch_day));
			if(in_array($week_number,$weekArray)) // If checking if it is a working day
				array_push($days,$batch_day);
			
		}
		
	?>
    
        <div align="center" class="atnd_tnav" style="top:10px;">
        <?php 
        if($curr_date > $begin_date)
        {   
            echo CHtml::link('<div class="atnd_arow_l"><img src="images/atnd_arrow-l.png" width="7" border="0"  height="13" /></div>',
                    array('/attendance/subjectAttendance/batchwise','date'=>date('Y-m-d',strtotime($curr_date . "-1 days")),
                        'id'=>$batch->id)); 
        }
            echo date($date_format,strtotime($curr_date)); 
        if($curr_date < $end_date)
         {  
            echo CHtml::link('<div class="atnd_arow_r"><img src="images/atnd_arrow.png" width="7" border="0"  height="13" /></div>',
            array('/attendance/subjectAttendance/batchwise','date'=>date('Y-m-d',strtotime($curr_date . "+1 days")),
                        'id'=>$batch->id)); 
         }
        
        ?>
        </div>
                <br />
                <br />
                <br />
                <div style="width:961px">
                <div class="timetable"  style="overflow-x:scroll;"> 
                <?php if(array_key_exists($curr_date, $holiday_arr)){ 
						$holiday_now = Holidays::model()->findByAttributes(array('id'=>$holiday_arr[$curr_date]));
				?>
                
                <span style="display:block; width:100%; height:40px; background:#D63535" class="holidays" title="<?php echo $holiday_now->title; ?>"></span>
                <?php }elseif(in_array($curr_date,$days)){ ?>
                <table border="0" align="center" width="100%" id="table" cellspacing="0">
                <tbody>
                    <tr>
                        <td class="td">
                            <div class="name"></div><?php echo Yii::t('app','Student Name'); ?></div>
                        </td>
                        
                        <?php
                        for($i=0;$i<$count_timing;$i++)
                        {
                            echo ' <td class="td">
                            <div  onclick="" style="position: relative;width:100px;">
                            <div class="tt-subject">
                            <div class="subject">';
                            $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$batch->id,'weekday_id'=>$weekdays[$week]['weekday'],'class_timing_id'=>$timing[$i]['id'])); 			
                            if(count($set)==0)
                            {
                                $is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
                                if($is_break==NULL)
                                    echo Yii::t('app','Not Assigned');
                                
                                else
                                    echo Yii::t('app','Break');
                            }
                            else
                            {
                                $time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
                                $time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
                                if($time_sub!=NULL)
                                {
                                    echo $time_sub->name.'<br>';
                                }
                                if($time_emp!=NULL)
                                {
                                    $is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));
                                    
                                    if($is_substitute and in_array($is_substitute->date_leave,$date_between))
                                    {
                                        $employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
                                        echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';
                                    }
                                    else
                                    {
                                        echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';
                                    }
                                }
                                
                                
                            }
                            
                            echo '</div>
                            </div>
                            </div>
                            </td>';  
                        }
                        ?>
                        <!--timetable_td -->
                    </tr>
                    
                    <?php foreach($students as $student){ ?>
                     <tr>
                       <td class="td">
                            <div class="name"><?php echo $student->getStudentname(); ?></div>
                        </td>
                       
                        <?php
						
						$today_day =	date('d');
						$today_month =	date('n');
						$today_year =	date('Y');
						$today_date = 	date('Y-m-d');
						$std_id		=	$student->id;
						
                        for($i=0;$i<$count_timing;$i++)
                        {
                            echo ' <td class="td">
                            <div  onclick="" style="position: relative;width:100px;">
                            <div class="tt-subject">
                            <div class="subject">';
                            $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$batch->id,'weekday_id'=>$weekdays[$week]['weekday'],'class_timing_id'=>$timing[$i]['id'])); 
									
                            if(count($set)==0)
                            {
                                $is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
                                if($is_break==NULL)
									echo Yii::t('app','Not Assigned');
								else
                                    echo Yii::t('app','Break');
                            }
                            else
                            {
								$timing_value = $timing[$i]['id']; 
								$sub_id = $set->subject_id;
								$subject = Subjects::model()->findByAttributes(array('id'=>$sub_id));
								$absent = StudentSubjectAttendance::model()->findByAttributes(array('date'=>$curr_date,'student_id'=>$std_id,'subject_id'=>$sub_id,'timing_id'=>$timing_value));
								if($absent){
									
									$label = '<span class="abs"></span>';
									echo CHtml::ajaxLink($label,$this->createUrl('/attendance/subjectAttendance/editLeave'),array(
									'type' =>'GET','data'=>array('date' =>$curr_date,'std_id'=>$std_id,'subject_id'=>$set->subject_id,'timing_id'=>$timing_value),
									'onclick'=>'$("#jobDialog'.$sub_id.$std_id.'").dialog("open"); return false;',				
									'update'=>'#jobDialogupdate'.$sub_id.$std_id,				
									),array('id'=>'showJobDialog'.$timing_value.'_'.$std_id.'_edit','class'=>'at_abs'));
									
								}
								else{
									if($curr_date < $today_date)	
										$label = '<i class="fa fa-check" style="color:#090"></i>';
									else
										$label = Yii::t('app','Mark');
									
									echo CHtml::ajaxLink($label,$this->createUrl('/attendance/subjectAttendance/addnew'),array(
									'type' =>'GET','data'=>array('date' =>$curr_date,'std_id'=>$std_id,'subject_id'=>$set->subject_id,'timing_id'=>$timing_value),
									'onclick'=>'$("#jobDialog'.$sub_id.$std_id.'").dialog("open"); return false;',				
									'update'=>'#jobDialog123'.$sub_id.$std_id,				
									),array('id'=>'showJobDialog'.$timing_value.'_'.$std_id.'_new','class'=>'at_abs'));
								}
							}
                            echo '</span><div  id="jobDialog123'.$sub_id.$std_id.'"></div></td>';
							echo '</span><div  id="jobDialogupdate'.$sub_id.$std_id.'"></div></td>';
                            echo '</div>
                            </div>
                            </div>
                            </td>';  
                        }
                        ?>
                        <!--timetable_td -->
                    </tr>
                    <?php } ?>
                                                      
                </tbody>
                </table>
               <?php }else{ ?>
 <span style="display:block; width:100%; height:40px; background:#F2F2F2"  title=""></span>
               <?php } ?> 
                
                
                </div>
                </div>
	<?php } ?>
              </div>
                                
                          <div class="ea_pdf" style="top:435px; right:18px; ">
							<?php
                             if(isset($_REQUEST['date']) && ($_REQUEST['date'] != NULL)){
                                echo CHtml::link('<img src="images/pdf-but.png" border="0">', array('/attendance/subjectAttendance/courseAttendancePdf','date'=>$_REQUEST['date'],'id'=>$_REQUEST['id']),array('target'=>'_blank')); 
                              }else{
                                echo CHtml::link('<img src="images/pdf-but.png" border="0">', array('/attendance/subjectAttendance/courseAttendancePdf','date'=>date('Y-m-d'),'id'=>$_REQUEST['id']),array('target'=>'_blank')); 
                              }
                            
                             ?>
                         </div>       
                                
                                
                                 <!-- END div class="formConInner" -->
                            </div> <!-- END div class="emp_tabwrapper" -->
                        </div> <!-- END div class="emp_right_contner" -->
                    <?php } // END $batch!=NULL ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
