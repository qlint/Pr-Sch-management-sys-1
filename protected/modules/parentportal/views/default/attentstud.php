<style>
table.attendance_table{
	margin:30px 0px;
	font-size:12px;
	border-collapse:collapse;
}
table.attendance_table td{
	border:1px solid #C5CED9;
	padding:5px 6px;
	
}
hr{ 
	border-bottom:1px solid #C5CED9; border-top:0px solid #fff;
}
</style>

<table width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td class="first" width="100">
		   <?php $filename=  Logo::model()->getLogo();
            if($filename!=NULL){                 
                echo '<img src="uploadedfiles/school_logo/'.$filename[2].'" alt="'.$filename[2].'" class="imgbrder" height="100" />';
            }
            ?>
        </td>
        <td valign="middle" >
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="listbxtop_hdng first" style="text-align:left; font-size:22px;   padding-left:10px;">
                        <?php $college=Configurations::model()->findAll(); ?>
                        <?php echo $college[0]->config_value;  ?>
                    </td>
                </tr>
                <tr>
                    <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                        <?php echo $college[1]->config_value; ?>
                    </td>
                </tr>
                <tr>
                    <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                        <?php echo Yii::t('app','Phone:').' '.$college[2]->config_value; ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<hr />
<br />
<div align="center" style="text-align:center; display:block;"><?php echo Yii::t('app','STUDENT ATTENDANCE'); ?></div><br />
<?php 
	if($_REQUEST['id'] != NULL and $_REQUEST['bid'] != NULL){ 
		$student	= Students::model()->findByPk($_REQUEST['id']);
		$batch		= Batches::model()->findByPk($_REQUEST['bid']);
		$course		= Courses::model()->findByPk($batch->course_id);
?>
    <table style="font-size:14px; background:#DCE6F1; padding:10px 10px;border:#C5CED9 1px solid;">
        <tr>
			<?php if(FormFields::model()->isVisible("fullname", "Students", "forParentPortal")){ ?>
                <td style="width:150px;"><?php echo Yii::t('app','Name'); ?></td>
                <td style="width:10px;">:</td>
                <td style="width:350px;"><?php echo $student->studentFullName('forParentPortal'); ?></td>
            <?php }else{ ?> 
                <td style="width:150px;"><?php echo Yii::t('app','Name'); ?></td>
                <td style="width:10px;">:</td>
                <td style="width:350px;"><?php echo '-'; ?></td>   
            <?php } ?>
            <td width="150"><?php echo Students::model()->getAttributeLabel('admission_no'); ?></td>
            <td style="width:10px;">:</td>
            <td width="350"><?php echo $student->admission_no; ?></td>        	
        </tr>
        <?php if(FormFields::model()->isVisible('batch_id','Students','forParentPortal')){ ?>   
              <tr>  
                <td><?php echo Yii::t('app','Course'); ?></td>
                <td>:</td>
                <td>
                    <?php 
                    if($course->course_name != NULL)
                        echo ucfirst($course->course_name);
                    else
                        echo '-';
                    ?>
                </td>
                <td><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); 
				if($batch->semester_id!=NULL){
    				$semester=Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); 								
					$sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);
					if($sem_enabled==1)
					{
						echo ' / '.Yii::t('app','Semester');
					}
				}
				?></td>
                <td>:</td>
                <td>
                    <?php 
                    if($batch->name != NULL){
                        echo ucfirst($batch->name);
						if($batch->semester_id!=NULL){ 
						$semester=Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); 								
						$sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);
						if($sem_enabled==1)
						{ 
							echo ' / '.$semester->name;  
						}
					} 
					}
                    ?>
                </td>            
            </tr>
        <?php } ?> 
    </table>
    <br/>
 	<?php echo Yii::t('app','Student Attendance Report');?>
    
    <table width="100%" cellspacing="0" cellpadding="0" class="attendance_table">
    	<tr style="background:#DCE6F1;">            
            <td width="280"><?php echo Students::model()->getAttributeLabel('admission_no');?></td>
            <?php if(FormFields::model()->isVisible("fullname", "Students", "forParentPortal")){ ?>
            	<td width="320"><?php echo Yii::t('app','Name');?></td>
            <?php } ?>    
            <td width="190"><?php echo Yii::t('app','Working Days');?></td>
            <td width="185"><?php echo Yii::t('app','Leaves');?></td>
        </tr>
        <tr>           
            <td><?php echo $student->admission_no; ?></td>
            <?php if(FormFields::model()->isVisible("fullname", "Students", "forParentPortal")){ ?>
                <td>
                    <?php echo $student->studentFullName('forParentPortal');?>
                </td>
            <?php } ?> 
            <td>
				<?php																																									
				if($student->admission_date >= $batch->start_date){ 
					$start_date  	= date('Y-m-d',strtotime($student->admission_date));												
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
            <td>
				<?php
                $leavedays 				= array();
				$criteria 				= new CDbCriteria;		
				$criteria->join 		= 'LEFT JOIN student_leave_types t1 ON (t.leave_type_id = t1.id OR t.leave_type_id = 0)'; 
				$criteria->condition 	= 't1.is_excluded=:is_excluded AND t.student_id=:x AND t.date >=:z AND t.date <=:A AND t.batch_id=:batch_id';											
				$criteria->params 		= array(':is_excluded'=>0,':x'=>$student->id,':z'=>$start_date,':A'=>$end_date, 'batch_id'=>$batch->id);
				$criteria->order		= 't.date DESC';
				$criteria->group 		= 't.id'; 
                $leaves    				= StudentAttentance::model()->findAll($criteria);
                foreach($leaves as $leave){
                    if(!in_array($leave->date,$leavedays)){
                        array_push($leavedays,$leave->date);
                    }
                }
                echo count($leavedays);
                ?>
            </td>
        </tr>      
    </table>
    <?php echo Yii::t('app','Leave Details');?>
    <table width="100%" cellspacing="0" cellpadding="0" class="attendance_table">
        <tr style="background:#DCE6F1;">
           <td width="165"><?php echo '#';?></td>
            <td width="250"><?php echo Yii::t('app','Leave Type');?></td>
            <td width="280"><?php echo Yii::t('app','Leave Date');?></td>
            <td width="280"><?php echo Yii::t('app','Reason');?></td>
        </tr>
        <?php
			$i 						= 1;
			
			$criteria 				= new CDbCriteria;												
			$criteria->condition 	= 't.student_id=:x AND t.date >=:z AND t.date <=:A AND t.batch_id=:batch_id';											
			$criteria->params 		= array(':x'=>$student->id,':z'=>$start_date,':A'=>$end_date, 'batch_id'=>$batch->id);
			$criteria->order		= 't.date DESC';
			$student_leaves			= StudentAttentance::model()->findAll($criteria);
			if($student_leaves){
				$settings			= UserSettings::model()->findByAttributes(array('user_id'=>1));
				foreach($student_leaves as $student_leave){
					$leave_type		= StudentLeaveTypes::model()->findByAttributes(array('id'=>$student_leave->leave_type_id,'status'=>1));
		?>
        			<tr>
                        <td><?php echo $i; ?></td>
                        <td>	
                            <?php	
                                if($leave_type){
                                    echo ucfirst($leave_type->name);													
                                }
                                else{
                                    echo '-';
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                if($settings != NULL){ 
                                    echo date($settings->displaydate, strtotime($student_leave->date));
                                }
                                else{
                                    echo $student_leave->date;
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                if($student_leave->reason != NULL){
                                    echo ucfirst($student_leave->reason);
                                }
                                else{
                                    echo '-';
                                }
                            ?>
                        </td>
                    </tr>
        <?php		
					$i++;			
				}
			}
			else{
		?>
        		<tr>	
                    <td colspan="4" align="center"><strong><?php echo Yii::t('app', 'No leaves taken'); ?></strong></td>
                </tr>
        <?php		
			}		
		?>
    </table>    
<?php 
	}
	else{
?>		
		<div align="center" style="text-align:center; display:block;"><?php echo Yii::t('app','Check all your inputs'); ?></div>
<?php	
    }
?>    



