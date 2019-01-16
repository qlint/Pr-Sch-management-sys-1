
<style>
.attendance_table{
	border-top:1px #C5CED9 solid;
	margin:30px 0px;
	font-size:12px;
	border-right:1px #C5CED9 solid;
}
.attendance_table td{
	border-left:1px #C5CED9 solid;
	padding:5px 6px;
	border-bottom:1px #C5CED9 solid;
	
}

hr{ border-bottom:1px solid #C5CED9; border-top:0px solid #fff;}
</style>

<?php

function getweek($date,$month,$year)
{
$date = mktime(0, 0, 0,$month,$date,$year); 
$week = date('w', $date); 
switch($week) {
case 0: 
return 'S<br>';
break;
case 1: 
return 'M<br>';
break;
case 2: 
return 'T<br>';
break;
case 3: 
return 'W<br>';
break;
case 4: 
return 'T<br>';
break;
case 5: 
return 'F<br>';
break;
case 6: 
return 'S<br>';
break;
}
}
?>

<?php

	if(isset($_REQUEST['id']))
  	{

		
				$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
		
				if(isset($_REQUEST['date']))
					$curr_date = date('Y-m-d',strtotime($_REQUEST['date']));
				else
					$curr_date = date('Y-m-d');
		
				$date = mktime(0, 0, 0,date('m',strtotime($curr_date)),date('d',strtotime($curr_date)),date('Y',strtotime($curr_date))); 
				$week = date('w', $date);
				 
				$studentdetails=Students::model()->findByPk($_REQUEST['id']);
				$batch=Batches::model()->findByPk($studentdetails->batch_id);
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
				
				
				
				//find working days.............
				?>
	
  	
    <!-- Header -->
    
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="first">
                           <?php $logo=Logo::model()->findAll();?>
                            <?php
                            if($logo!=NULL)
                            {
                                //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                echo '<img src="uploadedfiles/school_logo/'.$logo[0]->photo_file_name.'" alt="'.$logo[0]->photo_file_name.'" class="imgbrder" width="100%" />';
                            }
                            ?>
                </td>
                <td align="center" valign="middle" class="first" style="width:300px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:22px;   padding-left:10px;">
                                <?php $college=Configurations::model()->findAll(); ?>
                                <?php echo $college[0]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo $college[1]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo Yii::t('app','Phone: ').$college[2]->config_value; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
   
    
    <hr />
    <!-- End Header -->
    <br />
    <span align="center"><h4><?php echo Yii::t('app','STUDENT ATTENDANCE'); ?></h4></span>
    <!-- Student details -->
    
        <table style="font-size:14px; background:#DCE6F1; padding:10px 10px;border:#C5CED9 1px solid;">
            <?php 
				$student = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
            ?>
            <tr>
                <td style="width:150px;"><b><?php echo Yii::t('app','Name'); ?></b></td>
                <td style="width:10px;">:</td>
                <td style="width:350px;"><?php echo $student->first_name.' '.$student->last_name; ?></td>
                
                <td width="150"><b><?php echo Yii::t('app','Admission Number'); ?></b></td>
                <td style="width:10px;">:</td>
                <td width="350"><?php echo $student->admission_no; ?></td>
            
                <?php /*?><td><b>Month</b></td>
                <td style="width:10px;">:</td>
                <td><?php echo $mon.' '.$_REQUEST['year']; ?></td><?php */?>
            </tr>
            
            <tr>
            
                <td><b><?php echo Yii::t('app','Course'); ?></b></td>
                <td>:</td>
                <td>
					<?php 
					if($course->course_name!=NULL)
						echo ucfirst($course->course_name);
					else
						echo '-';
					?>
				</td>
                
                <td><b><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?></b></td>
                <td>:</td>
                <td>
					<?php 
					if($batch->name!=NULL)
						echo ucfirst($batch->name);
					else
						echo '-';
					?>
				</td>
            
            </tr>
            <tr>
            	<td><b><?php echo Yii::t('app','Month'); ?></b></td>
                <td>:</td>
                <td colspan="4"><?php echo $mon.' '.$_REQUEST['year']; ?></td>
            </tr>
           
        </table>
   
    <!-- END Student details -->
    
   <!-- Attendance table -->
 
        <?php if(array_key_exists($curr_date, $holiday_arr)){ 
						$holiday_now = Holidays::model()->findByAttributes(array('id'=>$holiday_arr[$curr_date]));
				?>
                
                <span style="display:block; width:100%; height:40px; background:#D63535" class="holidays" title="<?php echo $holiday_now->title; ?>"></span>
                <?php }elseif(in_array($curr_date,$days)){ ?>
                <table border="0" align="center" width="500" id="table" cellspacing="0" class="attendance_table">
                <tbody>
                    <tr>
                        <td class="td" rowspan="2" width="50">
                            <div class="name"><?php echo $days_name[$week];?></div>
                        </td>
                       
                        <?php
                        for($i=0;$i<$count_timing;$i++)
                        {
                            echo ' <td class="td" width="50">
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
                                        echo '<div class="employee">'.$employee->first_name.'</div>';
                                    }
                                    else
                                    {
                                        echo '<div class="employee">'.$time_emp->first_name.'</div>';
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
                     <tr>
                       
                        <td class="td-blank"></td>
                        <?php
						
						$today_day =	date('d');
						$today_month =	date('n');
						$today_year =	date('Y');
						$today_date = 	date('Y-m-d');
						$std_id		=	$_REQUEST['id'];
						
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
									echo $label;
									
								}
								else{
									if($curr_date < $today_date)	
										$label = '<i class="fa fa-check" style="color:#090"></i>';
									else
										$label = Yii::t('app','Mark');
									
									echo $label;
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
                                                      
                </tbody>
                </table>
               <?php }else{ ?>
 <span style="display:block; width:100%; height:40px; background:#F2F2F2"  title=""></span>
               <?php } ?> 
    
     <!-- END Attendance table -->
<?php } ?>
