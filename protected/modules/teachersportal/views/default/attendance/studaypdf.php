<?php
$this->breadcrumbs=array(
	Yii::t('app','Weekdays')=>array('index'),
	Yii::t('app','Manage'),
);
?>
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
</style>
<?php
	$settings		= UserSettings::model()->findByAttributes(array('id'=>1));	
	$day			= (isset($_REQUEST['date']))?date('Y-m-d', strtotime($_REQUEST['date'])):date("Y-m-d");
	$prev_day		= date('Y-m-d', strtotime('-1 days', strtotime($day)));
	$next_day		= date('Y-m-d', strtotime('+1 days', strtotime($day)));
	$this_date		= $day;	
	$employee		= Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));		 
	$batches		= Batches::model()->findAll("employee_id=:x AND is_active=:y AND is_deleted=:z", array(':x'=>$employee->id,':y'=>1,':z'=>0));		 
	$batch			= Batches::model()->findByAttributes(array('id'=>$_REQUEST['batch']));
	$course		 	= Courses::model()->findByAttributes(array('id'=>$batch->course_id));	
	$semester		= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
	$sem_enabled	= Configurations::model()->isSemesterEnabledForCourse($course->id);					 
	$begin 			= date('Y-m-d',strtotime($batch->start_date));
	$end			= date('Y-m-d',strtotime($batch->end_date)); 	
	
	if($settings != NULL){
		$displayformat	= $settings->displaydate;
		$pickerformat	= $settings->dateformat;
	}
	else{
		$displayformat	= 'd M Y';
		$pickerformat 	= 'dd-mm-yy';
	}
	
?>
<!-- Header -->
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td class="first" width="100">
               <?php $filename=  Logo::model()->getLogo();
                if($filename!=NULL)
                { 
                    //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                    echo '<img height="100" src="uploadedfiles/school_logo/'.$filename[2].'" alt="'.$filename[2].'" class="imgbrder" height="100" />';
                }
                ?>
    </td>
    <td valign="middle" >
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="listbxtop_hdng first" style="text-align:left; font-size:22px; padding-left:10px;">
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
                    <?php echo 'Phone: '.$college[2]->config_value; ?>
                </td>
            </tr>
        </table>
    </td>
</tr>
</table>
<hr />
<!-- End Header --> 
 <div align="center" style="display:block; text-align:center;">
         <h3> <?php echo Yii::t('app','Day Wise Student Attendance');
		   if($sem_enabled==1 and $batch->semester_id!=NULL){ ?>
			 	(<?php echo $batch->course123->course_name.' , '.$batch->name.' , '.$semester->name; ?>)
		<?php }
			  else{ ?>
			 	(<?php echo $batch->course123->course_name.' , '.$batch->name; ?>)
		<?php } ?>
		 </h3>
		 <h4><br /><?php echo date("$displayformat",strtotime($day)); ?></h4>
      </div>
                <?php
                if(isset($_GET['batch'])){
					
					$day = $_GET['date'];
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
                
                $batch_id		= $_GET['batch'];
                $is_week_day 	= StudentAttentance::model()->isWeekday($day, $batch_id);
                $is_holiday		= StudentAttentance::model()->isHoliday($day);	
                $students		= Yii::app()->getModule('students')->studentsOfBatch($_GET['batch']);
                ?>
                										
                <div class="subwis-tableposctn">
                    <div class="formWrapper formWrapper-subwis">
                        <div style="width:100%">  
                            <div>  
                            
								<?php
                                if(count($students) == 0){
								?>
								<div class="not-found-box">
								<?php
								echo '<i class="os_no_found">'.Yii::t("app", "No students in batch").'</i>';
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
                            ?>												
                            </div>
                        </div>
                    </div>
                </div>    
                <?php                                        
                }?>
                
             
                
                
                
                </div>
                </div>
              </div>
          <div class="clear"></div>
        </div>
    </div>
</div>