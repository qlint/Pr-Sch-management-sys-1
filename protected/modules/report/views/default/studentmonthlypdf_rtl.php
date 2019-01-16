<style>
table.attendance_table{ border-collapse:collapse}

.attendance_table{
	margin:30px 0px;
	font-size:8px;
	text-align:center;
	width:auto;
	/*max-width:600px;*/
	border-top:1px #CCC solid;
	border-right:1px solid #CCC;
}
.attendance_table td{
	border:1px solid #CCC;
	padding-top:10px; 
	padding-bottom:10px;
	width:auto;
	font-size:13px;
	
}

.attendance_table th{
	font-size:14px;
	padding:10px;
	border-left:1px #CCC solid;
	border-bottom:1px #CCC solid;
}

hr{ border-bottom:1px solid #C5CED9; border-top:0px solid #fff;}

</style>
<?php
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
?>


	<!-- Header -->
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="first" width="100">
                           <?php $filename=  Logo::model()->getLogo();
                            if($filename!=NULL)
                            { 
                                //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                echo '<img src="uploadedfiles/school_logo/'.$filename[2].'" alt="'.$filename[2].'" class="imgbrder" height="100" />';
                            }
                            ?>
                </td>
                <td valign="middle">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:22px; width:300px;  padding-left:10px;">
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
   <br />
	<!-- End Header -->

	<?php
    if(isset($_REQUEST['id']))
    {  
   ?>
   

    <div align="center" style="text-align:center; display:block;"><?php echo Yii::t('app','MONTHLY STUDENT ATTENDANCE REPORT'); ?></div><br />
    <?php 
	$students = Yii::app()->getModule('students')->studentsOfBatch($_REQUEST['id']);
	$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id'],'is_active'=>1,'is_deleted'=>0));
	$course = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
	?>
    <!-- Department details -->
   <table width="640" style="font-size:13px; background:#DCE6F1; padding:10px 10px;border:#C5CED9 1px solid;">
        	<tr>
            	<td width="120" height="30"><?php echo Yii::t('app','Course');?></td>
                <td width="10">:</td>
                <td width="190"><?php echo ucfirst($course->course_name);?></td>
                
                <td width="120"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?>
                <?php
				if($batch->semester_id!=NULL){
					 $sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);
					if($sem_enabled==1)
					{
						$semester=Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); 
						echo ' / '.Yii::t('app','Semester'); ?></strong>
                  <?php  }
				}
				?>
                </td>
                <td width="10">:</td>
                <td width="190"><?php echo $batch->name;?>
                <?php
				if($batch->semester_id!=NULL){
					 $sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);
					if($sem_enabled==1)
					{
						$semester=Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); 
						echo ' / '.$semester->name; ?></strong>
                  <?php  }
				}
				?>
                </td>
            </tr>
            <tr>
            	<td><?php echo Yii::t('app','Total Students');?></td>
                <td>:</td>
                <td><?php echo count($students);?></td>
                
                <td><?php echo Yii::t('app','Month');?></td>
                <td>:</td>
                <td width="240"><?php echo $_REQUEST['month'];?></td>
			</tr>                
                
        </table>
  
    <!-- END Department details -->
    
    <!-- Monthly Attendance Table -->
         <table width="100%" cellspacing="0" cellpadding="0" class="attendance_table">
            <tr class="tablebx_topbg" style="background-color:#DCE6F1;">
                <td width="60"><?php echo Yii::t('app','Sl No');?></td>
                <td width="120"><?php echo Yii::t('app','Adm No');?></td>
                <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                <td width="280"><?php echo Yii::t('app','Name');?></td>
                <?php } ?>
                <td width="158"><?php echo Yii::t('app','Working Days');?></td>
                <td width="100"><?php echo Yii::t('app','Leaves');?></td>
            </tr>
              <?php
				$monthly_sl = 1;
				$requiredmonth 	= date('m',strtotime($_REQUEST['month']));
				$requiredyear 	= date('Y',strtotime($_REQUEST['month']));
				$number 		= cal_days_in_month(CAL_GREGORIAN, $requiredmonth, $requiredyear);									
				$yr_start_date	= $requiredyear."-".$requiredmonth."-01";
				$yr_end_date	=  $requiredyear."-".$requiredmonth."-".$number;
				foreach($students as $student) // Displaying each employee row.
				{
				?>
				<tr>
					<td style="padding-top:10px; padding-bottom:10px;"><?php echo $monthly_sl; $monthly_sl++;?></td>
					<td><?php echo $student->admission_no; ?></td>
                    <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
					<td><?php echo $student->studentFullName("forStudentProfile");?></td>
                    <?php } ?>
                    <td>
                    	<?php
							$student_details=Students::model()->findByAttributes(array('id'=>$student->id)); 
							$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));								
							
							$start_date		= $yr_start_date;
							if($start_date < $batch->start_date){
								$start_date 	= date('Y-m-d',strtotime($batch->start_date));
							}
							if($start_date < $student->admission_date){
								$start_date	= date('Y-m-d',strtotime($student->admission_date));
							}
																																				
							$end_date		= $yr_end_date;
							if($end_date > $batch->end_date){
								$end_date  = date('Y-m-d',strtotime($batch->end_date));
							}
							if($end_date > date('Y-m-d')){
								$end_date	= date('Y-m-d');
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
								$holidays = Holidays::model()->findAll();
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
					 <!-- Monthly Attendance column -->
					<td>
						 <?php
						$leavedays 				= array();
						$criteria 				= new CDbCriteria;		
						$criteria->join 		= 'LEFT JOIN student_leave_types t1 ON (t.leave_type_id = t1.id OR t.leave_type_id = 0)'; 
						$criteria->condition 	= 't1.is_excluded=:is_excluded AND t.student_id=:x AND t.date >=:z AND t.date <=:A AND t.batch_id=:batch_id';											
						$criteria->params 		= array(':is_excluded'=>0,':x'=>$student->id,':z'=>$start_date,':A'=>$end_date, 'batch_id'=>$batch->id);
						$criteria->order		= 't.date DESC';
						$leaves    				= StudentAttentance::model()->findAll($criteria);
						$required_month			= date('Y-m',strtotime($_REQUEST['month']));
						$l = 0; 
						foreach($leaves as $leave){ 
							$attendance_month = date('Y-m',strtotime($leave->date));
							if($attendance_month == $required_month)
							{
								if(!in_array($leave->date,$leavedays)){
									array_push($leavedays,$leave->date);
										$l++; 
								}
							
							}
						}
						echo $l;
						?> 
					</td>
					<!-- End Monthly Attendance column -->
				</tr>
				<?php
				}
				?>
            
        </table>

    <!-- END Monthly Attendance Table -->
   
   <?php
    }
	else
	{
    ?>
    		<?php echo Yii::t('app','No data available!'); ?>
        
	<?php
    }
?>