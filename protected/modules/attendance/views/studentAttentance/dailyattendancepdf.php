<style type="text/css">
.timetable{
	 border-collapse:collapse;	
}
.timetable td{
	padding:10px;
	border:1px solid #C5CED9 ;
	width:auto;
	font-size:10px;
	text-align:center;
}
hr{ 
	border-bottom:1px solid #C5CED9; 
	border-top:0px solid #fff;
}
.timetable .loader{
	 padding:10px;	
}
.not-found-box{
	text-align:center !important;	
}
</style>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Attendances')=>array('/attendance'),
	Yii::t('app','Student Attendances'),
);
?>
<?php

	$settings	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
	$day			= (isset($_REQUEST['date']))?date('Y-m-d', strtotime($_REQUEST['date'])):date("Y-m-d");
	$prev_day		= date('Y-m-d', strtotime('-1 days', strtotime($day)));
	$next_day		= date('Y-m-d', strtotime('+1 days', strtotime($day)));
	$this_date		= $day;
	$batch			= Batches::model()->findByAttributes(array('id'=>$_REQUEST['batch']));
	$begin 			= date('Y-m-d',strtotime($batch->start_date));
	$end			= date('Y-m-d',strtotime($batch->end_date)); 
	$settings		= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
	$semester		= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
	$course			= Courses::model()->findByAttributes(array('id'=>$batch->course_id));
	$sem_enabled	= Configurations::model()->isSemesterEnabledForCourse($course->id);
	
	if($settings != NULL){
		$displayformat	= $settings->displaydate;
		$pickerformat	= $settings->dateformat;
	}
	else{
		$displayformat	= 'M d Y';
		$pickerformat 	= 'dd-mm-yy';
	}
	
?>
<?php
if(isset($_REQUEST['batch']) and $_REQUEST['batch']!=NULL){
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
                        <td class="listbxtop_hdng first"  style=" font-size:20px;" >
                            <?php $college=Configurations::model()->findAll(); ?>
                            <?php echo $college[0]->config_value; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="listbxtop_hdng first" >
                            <?php echo $college[1]->config_value; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="listbxtop_hdng first">
                            <?php echo 'Phone: '.$college[2]->config_value; ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
	<hr />
     <div align="center" style="display:block; text-align:center;">
         <h3><br /> 
			 <?php echo Yii::t('app','Daily Wise Student Attendance');?>(
			 <?php
			 if($sem_enabled==1 and $batch->semester_id!=NULL){ 
			 	echo $batch->course123->course_name.' , '.$batch->name.' , '.$semester->name; 
			 }
			 else{
				  echo $batch->course123->course_name.' , '.$batch->name;
			 }
			 ?>
			 )
		 </h3>
      </div>
     
			<?php 
            function getweek($day){
				$date   = date('d',strtotime($day));
				$month  = date('m',strtotime($day));
				$year 	= date('Y',strtotime($day));
				$date 	= mktime(0, 0, 0,$month,$date,$year); 
				$week 	= date('w', $date); 
				switch($week){
				case 0: 
				return 'Sunday';
				break;
				case 1: 
				return 'Monday';
				break;
				case 2: 
				return 'Tuesday';
				break;
				case 3: 
				return 'Wednesday';
				break;
				case 4: 
				return 'Thursday';
				break;
				case 5: 
				return 'Friday';
				break;
				case 6: 
				return 'Saturday';
				break;
				}
            }
			$batch_id		= $batch->id;
			$is_week_day 	= StudentAttentance::model()->isWeekday($day, $batch_id);
			$is_holiday		= StudentAttentance::model()->isHoliday($day);	
			$students		= Yii::app()->getModule('students')->studentsOfBatch($_REQUEST['batch']);
			if(count($students) == 0){
			?>
			<div class="not-found-box">
			<?php
			echo '<i class="os_no_found">'.Yii::t("app", "No students in batch").'</i>';
			?>
			</div>
			<?php
			}
			elseif($day < $begin){
			?>
			<div class="not-found-box">
			<?php
			echo '<i class="os_no_found">'.Yii::t("app", "Batch not started").'</i>';
			?>
			</div>
			<?php
			}
			
			elseif($day > $end){
			?>
			<div class="not-found-box">
			<?php
			echo '<i class="os_no_found">'.Yii::t("app", "Batch ended").'</i>';
			?>
			</div>
			<?php
			}
			elseif($day > date("Y-m-d")){
			?>
			<div class="not-found-box">
			<?php
			echo '<i class="os_no_found">'.Yii::t("app", "Cannot mark attendance for this date").'</i>';
			?>
			</div>
			<?php
			}
			elseif($is_week_day != 2 ){
			?>
			<div class="not-found-box">
			<?php
			echo '<i class="os_no_found">'.Yii::t("app", "Selected day is not a weekday").'</i>';
			?>
			</div>
			<?php
			}	
			elseif($is_holiday == 1){
			?>
			<div class="not-found-box">
			<?php
			echo '<i class="os_no_found">'.Yii::t("app", "Selected day is an annual holiday").'</i>';
			?>
			</div>
			<?php
			}
			else{
			?>
            <div class="timetable-grid">
                                                                <div class="timetable-grid-scroll">
                                                                   <table  align="left" width="100%" id="table" cellspacing="0" cellpadding="0" class="timetable" >
                                                                        <tbody>
                                                                        	<tr style="background:#DCE6F1">
                                                                              <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
                                                                              	<th width="80" class="loader"><?php echo Yii::t('app','Roll No');?></th>
                                                                              <?php } ?>
                                                                            	<th width="80" class="loader"><?php echo Yii::t('app','Name');?></th>
                                                                                <th width="80" class="loader"><?php echo getweek($day); ?></th>                                                                                
                                                                            </tr>
<?php
																			foreach($students as $student){
																				$batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
																				$admission_date	= date("Y-m-d", strtotime($student->admission_date));
																				
																				
																				$is_absent	= StudentAttentance::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$_REQUEST['batch'], 'date'=>$_REQUEST['date']));																				
																				
?>
																				<tr>
                                                                                 <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
                                                                                 	<td class="td daywise-block">
                                                                                        <p><?php if($batch_student!=NULL and $batch_student->roll_no!=0){
																									echo $batch_student->roll_no;
																								}
																								else{
																									echo '-';
																								}?>
                                                                                        </p>
                                                                                    </td> 
                                                                                  <?php } ?>
                                                                                    <td class="td daywise-block">
                                                                                        <p><?php echo $student->studentFullName(); ?></p>
                                                                                    </td> 
                                                                                    <td class="td">
<?php 
																						if($day >= $begin and $day <= $end){//Check current day in b/w batch start and end date 																																												
																							if($day >= $admission_date){// check the date is weekday or not and date is greater than student admission date
																							if($is_absent==NULL){
?>																							
																							<?php	echo '<span style="color:#070;">'.Yii::t('app','Present').'</span>';?>
                                                                                                
<?php																								
																							}
																							else
																							{
																								$leave_type = StudentLeaveTypes::model()->findByAttributes(array('id'=>$is_absent->leave_type_id));
																								echo '<span style="color:#F00;">'.Yii::t('app','Absent');
																								if($leave_type!=NULL)
																								echo ' ('.$leave_type->name.')';
																								echo '</span>';
																							}
																						}
																						 else
																						{
																							echo '<i class="not_joined">'.Yii::t("app", "Student not admitted").'</i>';
																						}
																					}
?>                                                                                    	
                                                                                    </td>
                                                                                </tr>                                                                                    
<?php																				
																			}
?>                                                                            
                                                                        </tbody>
                                                                    </table>
                                                                 </div>
                                                            </div>
            
     
     
     
     
    <?php
			}
    }
	?>