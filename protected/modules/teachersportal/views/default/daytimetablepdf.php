<?php
$this->breadcrumbs=array(
	Yii::t('app','Weekdays')=>array('index'),
	Yii::t('app','Manage'),
);
?>
<style>
#table{
	border-top:1px #C5CED9 solid;
	/*margin:30px 30px;*/
	border-right:1px #C5CED9 solid;
}
.timetable td{
	border-left:1px #C5CED9 solid;
	padding:10px 3px 10px 3px;
	border-bottom:1px #C5CED9 solid;
	width:auto;
	/*min-width:30px;*/
	font-size:10px;
	text-align:center;
}

.table_area table{ border-collapse:collapse;}

.table_area table tr td{ border:1px solid #C5CED9;
	padding:10px;}
	
.table_area table tr th{ border:1px solid #C5CED9;
	padding:15px 10px;
	background:#DCE6F1;}



hr{ border-bottom:1px solid #C5CED9; border-top:0px solid #fff;}
</style>
<?php
	$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$timetable = TimetableEntries::model()->findAllByAttributes(array('employee_id'=>$employee->id,'weekday_id'=>$_REQUEST['day_id']));
	$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
	$ac_year=$current_academic_yr->config_value;
	if($timetable!=NULL)
	{
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
        <br />
        
        <div align="center" style="display:block; text-align:center;"><?php echo Yii::t('app','DAY WISE TIME TABLE');?></div><br />
    <!-- Course details -->
    <br />
    
        
         <table style="font-size:14px; background:#DCE6F1; padding:10px 10px;border:#C5CED9 1px solid;" width="100%" border="0" cellpadding="0" cellspacing="0">
            <?php $employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
			
                  if($_REQUEST['day_id']==1){
										$day ="Sunday";
									}
									elseif($_REQUEST['day_id']==2){
										$day ="Monday";
									}
									elseif($_REQUEST['day_id']==3){
										$day ="Tuesday";
									}
									elseif($_REQUEST['day_id']==4){
										$day ="Wednesday";
									}
									elseif($_REQUEST['day_id']==5){
										$day ="Thursday";
									}
									elseif($_REQUEST['day_id']==6){
										$day ="Friday";
									}
									elseif($_REQUEST['day_id']==7){
										$day ="Saturday";
									}
									else
									{
										$day ="-";
									}		   
            ?>
            <tr>
                <td style="width:130px;"><?php echo Yii::t('app','Teacher Name');?></td>
                <td style="width:10px;">:</td>
                <td style="width:550px;"><?php echo $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name; ?></td>
            
                <td  style="width:130px;"><?php echo Yii::t('app','Day');?></td>
                <td style="width:5px;">:</td>
                <td><?php echo $day; ?></td>
            </tr>
        </table>
       <br />

        <div class="table_area">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
                            	<tbody>
                          			<tr class="pdtab-h">
                                        <th align="center"><?php echo Yii::t('app','Class Timing');?></th>
                                        <th align="center"><?php echo Yii::t('app','Course');?></th>
                                        <th align="center"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name');?></th>
                                         <?php  $sem_enabled	=	Configurations::model()->isSemesterEnabled();
													if($sem_enabled	== 1){
														?>
                                         <th align="center"><?php echo Yii::t('app','Semester');?></th>
                                                        
                                                    <?php } ?>
                                        <th align="center"><?php echo Yii::t('app','Subject');?></th>
                         			</tr>
                                    <?php 
									
                          											
								foreach($timetable as $timetable_1) // check acadamic year
							       {
									   $weekday_id = $_REQUEST['day_id'];
									// checking if classtime is present for selected weekday
									  $class_timing=ClassTimings::model()->findByAttributes(array('id'=>$timetable_1->class_timing_id)); 
									  $class_flag = 0;
									  
									  if($weekday_id == 1){ // if selected day is sunday, then in classtiming table on_sunday field should be 1 for displaying that timetable
										 if($class_timing->on_sunday == 1){
										  $class_flag = 1;
										 }
									  }
									  if($weekday_id == 2){
										  if($class_timing->on_monday == 1){
										  $class_flag = 1;
										 }
									  }
									  if($weekday_id == 3){
										  if($class_timing->on_tuesday == 1){
										  $class_flag = 1;
										 }
									  }
									  if($weekday_id == 4){
										  if($class_timing->on_wednesday == 1){
											$class_flag = 1;
										  }
									  }
									  if($weekday_id == 5){
										  if($class_timing->on_thursday == 1){
										  $class_flag = 1;
										 }
									  }
									  if($weekday_id == 6){
										 if($class_timing->on_friday == 1){
										  $class_flag = 1;
										 }
									  }
									  if($weekday_id == 7){
										 if($class_timing->on_saturday == 1){
										  $class_flag = 1;
										 }
									  }
									  // end checking if classtime is present for selected weekday
											 
							  		$batch=Batches::model()->findAllByAttributes(array('id'=>$timetable_1->batch_id,academic_yr_id=>$current_academic_yr->config_value));
									if($batch != NULL)
									 {
									 	$flag=1;
									 }
							
						          }
								  
						if($timetable!=NULL and $flag==1 and $class_flag == 1) // If class timing is set for the day and check acadamic year
                            { 
							  $flag_1=0;
							  foreach($timetable as $timetable_1) // check acadamic year
							      {
									$class_timing=ClassTimings::model()->findByAttributes(array('id'=>$timetable_1->class_timing_id));
								  	$batch=Batches::model()->findByAttributes(array('id'=>$timetable_1->batch_id,'academic_yr_id'=>$current_academic_yr->config_value));
								 
								  if($timetable_1->is_elective==0)
								  {	
									$subject=Subjects::model()->findByAttributes(array('id'=>$timetable_1->subject_id));
								  }
								  else if($timetable_1->is_elective==2)
								  {
									  //$elective=Electives::model()->findByAttributes(array('id'=>$timetable_1->subject_id));
									 // $subject=ElectiveGroups::model()->findByAttributes(array('id'=>$elective->elective_group_id));
									 $subject=Electives::model()->findByAttributes(array('id'=>$timetable_1->subject_id));
								  }
								  $course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
								  
								  $is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$employee->id,'time_table_entry_id'=>$timetable_1->id));
								  
								  $is_assigned = TeacherSubstitution::model()->findByAttributes(array('substitute_emp_id'=>$employee->id,'date_leave'=>$date_between[$_REQUEST['day_id']-1],'batch'=>$batch->id));	
								  
								  	$current_date = date("Y-m-d");
								  	$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));	
									if($current_academic_yr->config_value == $batch->academic_yr_id){	
								  
								    if($batch!=NULL and $class_timing!=NULL and $subject!=NULL and $course!=NULL and !$is_substitute and !in_array($is_substitute->date_leave,$date_between))
									{
							        
										
								    echo '<tr><td style="text-align:center;" width="200">'.$class_timing->start_time.'-'.$class_timing->end_time.'</td>';                             echo '<td style="text-align:center;" width="200">'.ucfirst($course->course_name).'</td>';
								    echo '<td style="text-align:center;" width="260">'.ucfirst($batch->name).'</td>';
									
									 $sem_enabled_course = Configurations::model()->isSemesterEnabledForCourse($batch->course_id);
										if($sem_enabled==1 and $sem_enabled_course == 1){
											$semester = Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
											echo '<td style="text-align:center;" width="200">'.$semester->name.'</td>';
											
										}
										else{
											echo '<td>'.'-'.'</td>';
										}
										
								    echo '<td style="text-align:center;" width="250">';
									if($timetable_1->split_subject){ 
										if($subject->split_subject!=0 and $subject->split_subject!=NULL){ 
											if($timetable_1->split_subject){
												$subject_splits	= SubjectSplit::model()->findByPk($timetable_1->split_subject);
												$name_sub	=	$subject_splits->split_name."<br> (".$subject->name.")";
											}
											else{
												$name_sub	=	$subject->name;
											} 
										}else{
											$name_sub	=	$subject->name;
										}
									}else{
											$name_sub	=	$subject->name;
									} 
									echo '<b>'.$name_sub .'</b><br>'; 
									echo '</td>';
								    echo '</tr>';
									$flag_1=1;    
									 }
									}
										
								  }
								 if($flag_1 == 0) // check batch,classtiming,subject,course are not avilable
								 {
								   echo '<tr>';
								 
                                   echo'<td colspan="5" align="center">' .'<i>'.Yii::t('app','No Timetable is set for you!').'</i>'.'</td>';                   echo '</tr>';
								 }
							}
						else // If class timing is NOT set for the employee
                            {
								  
								 echo '<tr>';
								 
                            echo'<td colspan="5" align="center">' .'<i>'.Yii::t('app','No Timetable is set for you!').'</i>'.'</td>';                            echo '</tr>';
                            }
									?>
                  </tbody>
		</table>
        </div>
        
<?php
	}
?>
 
