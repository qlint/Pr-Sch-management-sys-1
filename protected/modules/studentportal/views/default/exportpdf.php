<?php
$this->breadcrumbs=array(
	Yii::t('app','Weekdays')=>array('index'),
	Yii::t('app','Manage'),
);
?>
<style>

table.timetable{ border-collapse:collapse}

table.timetable td{
	border:1px  solid #C5CED9;
	padding:10px 3px 10px 3px;
	width:auto;
	/*min-width:30px;*/
	font-size:10px;
	text-align:center;
}
hr{ border-bottom:1px solid #C5CED9; border-top:0px solid #fff;}
</style>
	
	<?php
    $student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentPortal');
	if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL)
	{
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
                    <td  valign="middle" >
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
    <div align="center" style="display:block; text-align:center;"><?php echo Yii::t('app','CLASS TIME TABLE');?></div><br />
    <!-- Course details -->
     <table style="font-size:14px; background:#DCE6F1; padding:10px 10px;border:#C5CED9 1px solid;">
            <?php $batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
                  $course_name = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
				  $class_teacher = Employees::model()->findByAttributes(array('id'=>$batch->employee_id));
				  $semester_enabled	= Configurations::model()->isSemesterEnabled(); 
				  $sem_enabled	= Configurations::model()->isSemesterEnabledForCourse($course_name->id); 
            
            if(FormFields::model()->isVisible('batch_id', 'Students', "forStudentPortal")){
            ?>
            <tr>
                <td style="width:130px;"><?php echo Yii::t('app','Course');?></td>
                <td style="width:10px;">:</td>
                <td style="width:550px;"><?php echo $course_name->course_name; ?></td>
            
                <td  style="width:130px;"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></td>
                <td style="width:10px;">:</td>
                <td><?php echo $batch->name; ?></td>
            </tr>
            <?php
			}
            ?>
            <tr>
                <td style="width:130px;"><?php echo Yii::t('app','Class Teacher');?></td>
                <td style="width:10px;">:</td>
                <td style="width:550px;">
					<?php 
					if($class_teacher!=NULL)
					{
						echo Employees::model()->getTeachername($class_teacher->id);
					}
					else
					{
						echo '-';
					}
					?>
				</td>
   				<?php
				$total_students = BatchStudents::model()->BatchStudent($batch->id); ;
				?>
                <td style="width:130px;"><?php echo Yii::t('app','Total students');?></td>
                <td style="width:10px;">:</td>
                <td width="195"><?php echo count($total_students); ?></td>
            </tr>
			<?php if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id!=NULL){ 
						$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); ?> 
			<tr>
                <td style="width:130px;"><?php echo Yii::t('app','Semester');?></td>
                <td style="width:10px;">:</td>
                <td style="width:550px;">
					<?php echo ucfirst($semester->name);?>
				</td>
            </tr>
          <?php } ?> 
        </table>
	<br />
    <!-- END Course details -->
     <?php    
	$times=Batches::model()->findAll("id=:x", array(':x'=>$_REQUEST['id']));
	$weekdays=Weekdays::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id']));
	if(count($weekdays)==0)
		$weekdays=Weekdays::model()->findAll("batch_id IS NULL");
	?>
    <br /><br />
    <?php 
	$criteria=new CDbCriteria;
	$criteria->condition = "batch_id=:x";
    $criteria->params = array(':x'=>$_REQUEST['id']);
    $criteria->order = "STR_TO_DATE(start_time, '%h:%i %p')";    
		$timing = ClassTimings::model()->findAll($criteria);
		$count_timing = count($timing);
		if(isset($timing) and $timing!=NULL)
		{
	?>

		
		<table  align="left" width="100%" id="table" cellspacing="0" cellpadding="0" class="timetable" >
			<tr style="background:#DCE6F1">
			  <td  style="background:#DCE6F1;">&nbsp;</td>
			  <?php 
					foreach($timing as $timing_1)
					{
						//echo $timing_1->start_time.'<br>';  ?>
					<?php echo '<td style="font-size:11px;background:#E1EAEF;word-break:break-all;">'.$timing_1->start_time .' -<br> '.$timing_1->end_time.'</td>';?>
				<?php 	}
			   ?>
			</tr> <!-- timetable_tr -->
			
			<?php if($weekdays[0]['weekday']!=0)
			{
			
			 ?>
			  <tr>
				<td><?php echo 	 Yii::t('app','SUN') ;?></td>
			  
					 <?php
					  for($i=0;$i<$count_timing;$i++)
					  {
						?> <td>
					   <?php 
								
				$sets =  TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[0]['weekday'],'class_timing_id'=>$timing[$i]['id'])); 		
				if(count($sets)==0)
				{
					$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
					if($is_break!=NULL)
					{
						echo Yii::t('app','Break');
					}
					else  // Checking if elective
					{

					 $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[0]['weekday'],'class_timing_id'=>$timing[$i]['id'],'employee_id'=>NULL));
					 if($set)
					 {
						$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
					 }
					 if($time_sub->elective_group_id!=0) // Confirm that it is elective
					 {
						 $time_elective_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
						 $time_elective_sub = Electives::model()->findAllByAttributes(array('elective_group_id'=> $time_elective_group->id));
						foreach($time_elective_sub as $elective_sub)
						{
							//echo $elective_sub->name;
							$emp_elective_sub = EmployeeElectiveSubjects::model()->findByAttributes(array('elective_id'=>$elective_sub->id,'employee_id'=>$employee->id));
							if(count($emp_elective_sub)==1)
							{
								echo $time_elective_group->name.'<br>';
								echo '('.$elective_sub->name.')<br>';
								//echo Employees::model()->getTeachername($employee->id);

							}
							else
							{
								continue;
							}
						}
						//echo $time_elective_group->name.'<br>';

					 }
				//In the case of any substitution for any subject
					 $substitute = TeacherSubstitution::model()->findByAttributes(array('substitute_emp_id'=>$employee->id,'date_leave'=>$date_between[0],'batch'=>$_REQUEST['id']));

					 if($substitute)
					 {
						$timetable_entry =  TimetableEntries::model()->findByAttributes(array('id'=>$substitute->time_table_entry_id));
						if($timetable_entry)
						{
							if($timing[$i]['id'] == $timetable_entry->class_timing_id)
							{
								$subject = Subjects::model()->findByAttributes(array('id'=>$timetable_entry->subject_id));
								echo ucfirst($subject->name).'<br>';
								echo Employees::model()->getTeachername($employee->id);

							}
						}
					 }


					}
				}else
				{
					
					$elec_group = "";
					$count=0;
					foreach($sets as $set)
					{
						if($set->is_elective==0)
						{							
							$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
						}
						else if($set->is_elective==2)
						{
							
							$time_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
							$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
						}

					$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));

					if($time_sub!=NULL){
						
					if($time_sub->elective_group_id!=0 and $time_sub->elective_group_id!=NULL)
					{

						$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id));
						if($student_elective!=NULL)
						{
							$electname = Electives::model()->findByAttributes(array('id'=>$student_elective->elective_id,'elective_group_id'=>$time_sub->elective_group_id));
							if($electname!=NULL)
							{
							}
						}


					}
					else
					{
						echo ucfirst($time_sub->name).'<br>';
                        echo Employees::model()->getTeachername($time_emp->id);
					}
					}
					if($time_emp!=NULL)
					{
						
						$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));

						if($is_substitute and in_array($is_substitute->date_leave,$date_between))
						{
							$employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
							$timetable_entry = TimetableEntries::model()->findByAttributes(array('id'=>$is_substitute->time_table_entry_id));
							if($timetable_entry){
								$subject = Subjects::model()->findByAttributes(array('id'=>$timetable_entry->subject_id));
								echo ucfirst($subject->name).'<br>';
							}
							echo Employees::model()->getTeachername($employee->id);
						}
						else
						{
							$is_exist_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$_REQUEST['id'],'elective_group_id'=>$elec_group->id,'elective_id'=>$time_sub->id));
                                                        
                                                        
                                                    if($elective_exist_flag==0 and $count==0)
                                                    {
														if($elec_group!=NULL){
                                                        echo ucfirst($elec_group->name).'<br>';
														}
                                                    }
                                                    
                                                    if($is_exist_elective!=NULL)
                                                    {
                                                        echo ucfirst($elec_group->name);
                                                        echo '<br>'.ucfirst($time_sub->name).'</b>';
                                                    }
                                                                ?><?php
						}
					}
						$count++;
					}


				
				}
																	
				
						?> </td>
						<?php  
					 }
					?>
				  <!--timetable_td -->
				
			  </tr><!--timetable_tr -->
			  <?php 	
			}  ?>
			  <?php   if($weekdays[1]['weekday']!=0)
			  { ?>
			  <tr>
				<td><?php echo 	 Yii::t('app','MON') ;?></td>
			  
					 <?php
					  for($i=0;$i<$count_timing;$i++)
					  {
						?> <td>
					   <?php 
								
				$sets =  TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[1]['weekday'],'class_timing_id'=>$timing[$i]['id'])); 		
				if(count($sets)==0)
				{
					$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
					if($is_break!=NULL)
					{
						echo Yii::t('app','Break');
					}
					else  // Checking if elective
					{

					 $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[0]['weekday'],'class_timing_id'=>$timing[$i]['id'],'employee_id'=>NULL));
					 if($set)
					 {
						$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
					 }
					 if($time_sub->elective_group_id!=0) // Confirm that it is elective
					 {
						 $time_elective_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
						 $time_elective_sub = Electives::model()->findAllByAttributes(array('elective_group_id'=> $time_elective_group->id));
						foreach($time_elective_sub as $elective_sub)
						{
							//echo $elective_sub->name;
							$emp_elective_sub = EmployeeElectiveSubjects::model()->findByAttributes(array('elective_id'=>$elective_sub->id,'employee_id'=>$employee->id));
							if(count($emp_elective_sub)==1)
							{
								echo $time_elective_group->name.'<br>';
								echo '('.$elective_sub->name.')<br>';
								//echo Employees::model()->getTeachername($employee->id);

							}
							else
							{
								continue;
							}
						}
						//echo $time_elective_group->name.'<br>';

					 }
				//In the case of any substitution for any subject
					 $substitute = TeacherSubstitution::model()->findByAttributes(array('substitute_emp_id'=>$employee->id,'date_leave'=>$date_between[0],'batch'=>$_REQUEST['id']));

					 if($substitute)
					 {
						$timetable_entry =  TimetableEntries::model()->findByAttributes(array('id'=>$substitute->time_table_entry_id));
						if($timetable_entry)
						{
							if($timing[$i]['id'] == $timetable_entry->class_timing_id)
							{
								$subject = Subjects::model()->findByAttributes(array('id'=>$timetable_entry->subject_id));
								echo ucfirst($subject->name).'<br>';
								echo Employees::model()->getTeachername($employee->id);

							}
						}
					 }


					}
				}else
				{
					
					$elec_group = "";
					$count=0;
					foreach($sets as $set)
					{
						if($set->is_elective==0)
						{							
							$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
						}
						else if($set->is_elective==2)
						{
							
							$time_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
							$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
						}

					$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));

					if($time_sub!=NULL){
						
					if($time_sub->elective_group_id!=0 and $time_sub->elective_group_id!=NULL)
					{

						$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id));
						if($student_elective!=NULL)
						{
							$electname = Electives::model()->findByAttributes(array('id'=>$student_elective->elective_id,'elective_group_id'=>$time_sub->elective_group_id));
							if($electname!=NULL)
							{
							}
						}


					}
					else
					{
						echo ucfirst($time_sub->name).'<br>';
                        echo Employees::model()->getTeachername($time_emp->id);
					}
					}
					if($time_emp!=NULL)
					{
						
						$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));

						if($is_substitute and in_array($is_substitute->date_leave,$date_between))
						{
							$employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
							$timetable_entry = TimetableEntries::model()->findByAttributes(array('id'=>$is_substitute->time_table_entry_id));
							if($timetable_entry){
								$subject = Subjects::model()->findByAttributes(array('id'=>$timetable_entry->subject_id));
								echo ucfirst($subject->name).'<br>';
							}
							echo Employees::model()->getTeachername($employee->id);
						}
						else
						{
							$is_exist_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$_REQUEST['id'],'elective_group_id'=>$elec_group->id,'elective_id'=>$time_sub->id));
                                                        
                                                        
                                                    if($elective_exist_flag==0 and $count==0)
                                                    {
														if($elec_group!=NULL){
                                                        echo ucfirst($elec_group->name).'<br>';
														}
                                                    }
                                                    
                                                    if($is_exist_elective!=NULL)
                                                    {
                                                        echo ucfirst($elec_group->name);
                                                        echo '<br>'.ucfirst($time_sub->name).'</b>';
                                                    }
                                                                ?><?php
						}
					}
						$count++;
					}


				
				}
																	
				
						?> </td>
						<?php  
					 }
					?>
				  <!--timetable_td -->
				
			  </tr><!--timetable_tr -->
			  <?php } ?>
			 <?php  if($weekdays[2]['weekday']!=0)
			  {
			
			 ?>
			  <tr>
				<td><?php echo 	 Yii::t('app','TUE') ;?></td>
			  
					 <?php
					  for($i=0;$i<$count_timing;$i++)
					  {
						?> <td>
					   <?php 
								
				$sets =  TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[2]['weekday'],'class_timing_id'=>$timing[$i]['id'])); 		
				if(count($sets)==0)
				{
					$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
					if($is_break!=NULL)
					{
						echo Yii::t('app','Break');
					}
					else  // Checking if elective
					{

					 $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[0]['weekday'],'class_timing_id'=>$timing[$i]['id'],'employee_id'=>NULL));
					 if($set)
					 {
						$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
					 }
					 if($time_sub->elective_group_id!=0) // Confirm that it is elective
					 {
						 $time_elective_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
						 $time_elective_sub = Electives::model()->findAllByAttributes(array('elective_group_id'=> $time_elective_group->id));
						foreach($time_elective_sub as $elective_sub)
						{
							//echo $elective_sub->name;
							$emp_elective_sub = EmployeeElectiveSubjects::model()->findByAttributes(array('elective_id'=>$elective_sub->id,'employee_id'=>$employee->id));
							if(count($emp_elective_sub)==1)
							{
								echo $time_elective_group->name.'<br>';
								echo '('.$elective_sub->name.')<br>';
								//echo Employees::model()->getTeachername($employee->id);

							}
							else
							{
								continue;
							}
						}
						//echo $time_elective_group->name.'<br>';

					 }
				//In the case of any substitution for any subject
					 $substitute = TeacherSubstitution::model()->findByAttributes(array('substitute_emp_id'=>$employee->id,'date_leave'=>$date_between[0],'batch'=>$_REQUEST['id']));

					 if($substitute)
					 {
						$timetable_entry =  TimetableEntries::model()->findByAttributes(array('id'=>$substitute->time_table_entry_id));
						if($timetable_entry)
						{
							if($timing[$i]['id'] == $timetable_entry->class_timing_id)
							{
								$subject = Subjects::model()->findByAttributes(array('id'=>$timetable_entry->subject_id));
								echo ucfirst($subject->name).'<br>';
								echo Employees::model()->getTeachername($employee->id);

							}
						}
					 }


					}
				}else
				{
					
					$elec_group = "";
					$count=0;
					foreach($sets as $set)
					{
						if($set->is_elective==0)
						{							
							$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
						}
						else if($set->is_elective==2)
						{
							
							$time_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
							$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
						}

					$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));

					if($time_sub!=NULL){
						
					if($time_sub->elective_group_id!=0 and $time_sub->elective_group_id!=NULL)
					{

						$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id));
						if($student_elective!=NULL)
						{
							$electname = Electives::model()->findByAttributes(array('id'=>$student_elective->elective_id,'elective_group_id'=>$time_sub->elective_group_id));
							if($electname!=NULL)
							{
							}
						}


					}
					else
					{
						echo ucfirst($time_sub->name).'<br>';
                        echo Employees::model()->getTeachername($time_emp->id);
					}
					}
					if($time_emp!=NULL)
					{
						
						$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));

						if($is_substitute and in_array($is_substitute->date_leave,$date_between))
						{
							$employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
							$timetable_entry = TimetableEntries::model()->findByAttributes(array('id'=>$is_substitute->time_table_entry_id));
							if($timetable_entry){
								$subject = Subjects::model()->findByAttributes(array('id'=>$timetable_entry->subject_id));
								echo ucfirst($subject->name).'<br>';
							}
							echo Employees::model()->getTeachername($employee->id);
						}
						else
						{
							$is_exist_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$_REQUEST['id'],'elective_group_id'=>$elec_group->id,'elective_id'=>$time_sub->id));
                                                        
                                                        
                                                    if($elective_exist_flag==0 and $count==0)
                                                    {
														if($elec_group!=NULL){
                                                        echo ucfirst($elec_group->name).'<br>';
														}
                                                    }
                                                    
                                                    if($is_exist_elective!=NULL)
                                                    {
                                                        echo ucfirst($elec_group->name);
                                                        echo '<br>'.ucfirst($time_sub->name).'</b>';
                                                    }
                                                                ?><?php
						}
					}
						$count++;
					}


				
				}
																	
				
						?> </td>
						<?php  
					 }
					?>
				  <!--timetable_td -->
				
			  </tr><!--timetable_tr -->
			  <?php 
			   } ?>
			  <?php
			  if($weekdays[3]['weekday']!=0)
			  {
				  
				  ?>
			  <tr>
				<td><?php echo 	 Yii::t('app','WED') ;?></td>
			  
					 <?php
					  for($i=0;$i<$count_timing;$i++)
					  {
						?> <td>
					   <?php 
								
				$sets =  TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[3]['weekday'],'class_timing_id'=>$timing[$i]['id'])); 		
				if(count($sets)==0)
				{
					$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
					if($is_break!=NULL)
					{
						echo Yii::t('app','Break');
					}
					else  // Checking if elective
					{

					 $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[0]['weekday'],'class_timing_id'=>$timing[$i]['id'],'employee_id'=>NULL));
					 if($set)
					 {
						$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
					 }
					 if($time_sub->elective_group_id!=0) // Confirm that it is elective
					 {
						 $time_elective_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
						 $time_elective_sub = Electives::model()->findAllByAttributes(array('elective_group_id'=> $time_elective_group->id));
						foreach($time_elective_sub as $elective_sub)
						{
							//echo $elective_sub->name;
							$emp_elective_sub = EmployeeElectiveSubjects::model()->findByAttributes(array('elective_id'=>$elective_sub->id,'employee_id'=>$employee->id));
							if(count($emp_elective_sub)==1)
							{
								echo $time_elective_group->name.'<br>';
								echo '('.$elective_sub->name.')<br>';
								//echo Employees::model()->getTeachername($employee->id);

							}
							else
							{
								continue;
							}
						}
						//echo $time_elective_group->name.'<br>';

					 }
				//In the case of any substitution for any subject
					 $substitute = TeacherSubstitution::model()->findByAttributes(array('substitute_emp_id'=>$employee->id,'date_leave'=>$date_between[0],'batch'=>$_REQUEST['id']));

					 if($substitute)
					 {
						$timetable_entry =  TimetableEntries::model()->findByAttributes(array('id'=>$substitute->time_table_entry_id));
						if($timetable_entry)
						{
							if($timing[$i]['id'] == $timetable_entry->class_timing_id)
							{
								$subject = Subjects::model()->findByAttributes(array('id'=>$timetable_entry->subject_id));
								echo ucfirst($subject->name).'<br>';
								echo Employees::model()->getTeachername($employee->id);

							}
						}
					 }


					}
				}else
				{
					
					$elec_group = "";
					$count=0;
					foreach($sets as $set)
					{
						if($set->is_elective==0)
						{							
							$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
						}
						else if($set->is_elective==2)
						{
							
							$time_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
							$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
						}

					$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));

					if($time_sub!=NULL){
						
					if($time_sub->elective_group_id!=0 and $time_sub->elective_group_id!=NULL)
					{

						$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id));
						if($student_elective!=NULL)
						{
							$electname = Electives::model()->findByAttributes(array('id'=>$student_elective->elective_id,'elective_group_id'=>$time_sub->elective_group_id));
							if($electname!=NULL)
							{
							}
						}


					}
					else
					{
						echo ucfirst($time_sub->name).'<br>';
                        echo Employees::model()->getTeachername($time_emp->id);
					}
					}
					if($time_emp!=NULL)
					{
						
						$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));

						if($is_substitute and in_array($is_substitute->date_leave,$date_between))
						{
							$employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
							$timetable_entry = TimetableEntries::model()->findByAttributes(array('id'=>$is_substitute->time_table_entry_id));
							if($timetable_entry){
								$subject = Subjects::model()->findByAttributes(array('id'=>$timetable_entry->subject_id));
								echo ucfirst($subject->name).'<br>';
							}
							echo Employees::model()->getTeachername($employee->id);
						}
						else
						{
							$is_exist_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$_REQUEST['id'],'elective_group_id'=>$elec_group->id,'elective_id'=>$time_sub->id));
                                                        
                                                        
                                                    if($elective_exist_flag==0 and $count==0)
                                                    {
														if($elec_group!=NULL){
                                                        echo ucfirst($elec_group->name).'<br>';
														}
                                                    }
                                                    
                                                    if($is_exist_elective!=NULL)
                                                    {
                                                        echo ucfirst($elec_group->name);
                                                        echo '<br>'.ucfirst($time_sub->name).'</b>';
                                                    }
                                                                ?><?php
						}
					}
						$count++;
					}


				
				}
																	
				
						?> </td>
						<?php  
					 }
					?>
				  <!--timetable_td -->
				
			  </tr><!--timetable_tr -->
			  <?php 
			  }
			  ?>
			  <?php
			  if($weekdays[4]['weekday']!=0)
			  {
				   ?>
			  <tr>
				<td><?php echo 	 Yii::t('app','THU') ;?></td>
			  
					 <?php
					  for($i=0;$i<$count_timing;$i++)
					  {
						?> <td>
					   <?php 
								
				$sets =  TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[4]['weekday'],'class_timing_id'=>$timing[$i]['id'])); 		
				if(count($sets)==0)
				{
					$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
					if($is_break!=NULL)
					{
						echo Yii::t('app','Break');
					}
					else  // Checking if elective
					{

					 $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[0]['weekday'],'class_timing_id'=>$timing[$i]['id'],'employee_id'=>NULL));
					 if($set)
					 {
						$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
					 }
					 if($time_sub->elective_group_id!=0) // Confirm that it is elective
					 {
						 $time_elective_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
						 $time_elective_sub = Electives::model()->findAllByAttributes(array('elective_group_id'=> $time_elective_group->id));
						foreach($time_elective_sub as $elective_sub)
						{
							//echo $elective_sub->name;
							$emp_elective_sub = EmployeeElectiveSubjects::model()->findByAttributes(array('elective_id'=>$elective_sub->id,'employee_id'=>$employee->id));
							if(count($emp_elective_sub)==1)
							{
								echo $time_elective_group->name.'<br>';
								echo '('.$elective_sub->name.')<br>';
								//echo Employees::model()->getTeachername($employee->id);

							}
							else
							{
								continue;
							}
						}
						//echo $time_elective_group->name.'<br>';

					 }
				//In the case of any substitution for any subject
					 $substitute = TeacherSubstitution::model()->findByAttributes(array('substitute_emp_id'=>$employee->id,'date_leave'=>$date_between[0],'batch'=>$_REQUEST['id']));

					 if($substitute)
					 {
						$timetable_entry =  TimetableEntries::model()->findByAttributes(array('id'=>$substitute->time_table_entry_id));
						if($timetable_entry)
						{
							if($timing[$i]['id'] == $timetable_entry->class_timing_id)
							{
								$subject = Subjects::model()->findByAttributes(array('id'=>$timetable_entry->subject_id));
								echo ucfirst($subject->name).'<br>';
								echo Employees::model()->getTeachername($employee->id);

							}
						}
					 }


					}
				}else
				{
					
					$elec_group = "";
					$count=0;
					foreach($sets as $set)
					{
						if($set->is_elective==0)
						{							
							$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
						}
						else if($set->is_elective==2)
						{
							
							$time_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
							$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
						}

					$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));

					if($time_sub!=NULL){
						
					if($time_sub->elective_group_id!=0 and $time_sub->elective_group_id!=NULL)
					{

						$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id));
						if($student_elective!=NULL)
						{
							$electname = Electives::model()->findByAttributes(array('id'=>$student_elective->elective_id,'elective_group_id'=>$time_sub->elective_group_id));
							if($electname!=NULL)
							{
							}
						}


					}
					else
					{
						echo ucfirst($time_sub->name).'<br>';
                        echo Employees::model()->getTeachername($time_emp->id);
					}
					}
					if($time_emp!=NULL)
					{
						
						$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));

						if($is_substitute and in_array($is_substitute->date_leave,$date_between))
						{
							$employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
							$timetable_entry = TimetableEntries::model()->findByAttributes(array('id'=>$is_substitute->time_table_entry_id));
							if($timetable_entry){
								$subject = Subjects::model()->findByAttributes(array('id'=>$timetable_entry->subject_id));
								echo ucfirst($subject->name).'<br>';
							}
							echo Employees::model()->getTeachername($employee->id);
						}
						else
						{
							$is_exist_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$_REQUEST['id'],'elective_group_id'=>$elec_group->id,'elective_id'=>$time_sub->id));
                                                        
                                                        
                                                    if($elective_exist_flag==0 and $count==0)
                                                    {
														if($elec_group!=NULL){
                                                        echo ucfirst($elec_group->name).'<br>';
														}
                                                    }
                                                    
                                                    if($is_exist_elective!=NULL)
                                                    {
                                                        echo ucfirst($elec_group->name);
                                                        echo '<br>'.ucfirst($time_sub->name).'</b>';
                                                    }
                                                                ?><?php
						}
					}
						$count++;
					}


				
				}
																	
				
						?> </td>
						<?php  
					 }
					?>
				  <!--timetable_td -->
				
			  </tr><!--timetable_tr -->
			  <?php 
			  } ?>
			  <?php
			  if($weekdays[5]['weekday']!=0)
			  {
				  
				   ?>
			  <tr>
				<td><?php echo 	 Yii::t('app','FRI') ;?></td>
			  
					 <?php
					  for($i=0;$i<$count_timing;$i++)
					  {
						?> <td>
					   <?php 
								
				$sets =  TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[5]['weekday'],'class_timing_id'=>$timing[$i]['id'])); 		
				if(count($sets)==0)
				{
					$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
					if($is_break!=NULL)
					{
						echo Yii::t('app','Break');
					}
					else  // Checking if elective
					{

					 $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[0]['weekday'],'class_timing_id'=>$timing[$i]['id'],'employee_id'=>NULL));
					 if($set)
					 {
						$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
					 }
					 if($time_sub->elective_group_id!=0) // Confirm that it is elective
					 {
						 $time_elective_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
						 $time_elective_sub = Electives::model()->findAllByAttributes(array('elective_group_id'=> $time_elective_group->id));
						foreach($time_elective_sub as $elective_sub)
						{
							//echo $elective_sub->name;
							$emp_elective_sub = EmployeeElectiveSubjects::model()->findByAttributes(array('elective_id'=>$elective_sub->id,'employee_id'=>$employee->id));
							if(count($emp_elective_sub)==1)
							{
								echo $time_elective_group->name.'<br>';
								echo '('.$elective_sub->name.')<br>';
								//echo Employees::model()->getTeachername($employee->id);

							}
							else
							{
								continue;
							}
						}
						//echo $time_elective_group->name.'<br>';

					 }
				//In the case of any substitution for any subject
					 $substitute = TeacherSubstitution::model()->findByAttributes(array('substitute_emp_id'=>$employee->id,'date_leave'=>$date_between[0],'batch'=>$_REQUEST['id']));

					 if($substitute)
					 {
						$timetable_entry =  TimetableEntries::model()->findByAttributes(array('id'=>$substitute->time_table_entry_id));
						if($timetable_entry)
						{
							if($timing[$i]['id'] == $timetable_entry->class_timing_id)
							{
								$subject = Subjects::model()->findByAttributes(array('id'=>$timetable_entry->subject_id));
								echo ucfirst($subject->name).'<br>';
								echo Employees::model()->getTeachername($employee->id);

							}
						}
					 }


					}
				}else
				{
					
					$elec_group = "";
					$count=0;
					foreach($sets as $set)
					{
						if($set->is_elective==0)
						{							
							$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
						}
						else if($set->is_elective==2)
						{
							
							$time_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
							$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
						}

					$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));

					if($time_sub!=NULL){
						
					if($time_sub->elective_group_id!=0 and $time_sub->elective_group_id!=NULL)
					{

						$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id));
						if($student_elective!=NULL)
						{
							$electname = Electives::model()->findByAttributes(array('id'=>$student_elective->elective_id,'elective_group_id'=>$time_sub->elective_group_id));
							if($electname!=NULL)
							{
							}
						}


					}
					else
					{
						echo ucfirst($time_sub->name).'<br>';
                        echo Employees::model()->getTeachername($time_emp->id);
					}
					}
					if($time_emp!=NULL)
					{
						
						$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));

						if($is_substitute and in_array($is_substitute->date_leave,$date_between))
						{
							$employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
							$timetable_entry = TimetableEntries::model()->findByAttributes(array('id'=>$is_substitute->time_table_entry_id));
							if($timetable_entry){
								$subject = Subjects::model()->findByAttributes(array('id'=>$timetable_entry->subject_id));
								echo ucfirst($subject->name).'<br>';
							}
							echo Employees::model()->getTeachername($employee->id);
						}
						else
						{
							$is_exist_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$_REQUEST['id'],'elective_group_id'=>$elec_group->id,'elective_id'=>$time_sub->id));
                                                        
                                                        
                                                    if($elective_exist_flag==0 and $count==0)
                                                    {
														if($elec_group!=NULL){
                                                        echo ucfirst($elec_group->name).'<br>';
														}
                                                    }
                                                    
                                                    if($is_exist_elective!=NULL)
                                                    {
                                                        echo ucfirst($elec_group->name);
                                                        echo '<br>'.ucfirst($time_sub->name).'</b>';
                                                    }
                                                                ?><?php
						}
					}
						$count++;
					}


				
				}
																	
				
						?> </td>
						<?php  
					 }
					?>
				  <!--timetable_td -->
				
			  </tr><!--timetable_tr -->
			  <?php 
			  }  ?>
			  <?php
			  if($weekdays[6]['weekday']!=0)
			  { ?>
			  <tr>
				<td><?php echo 	 Yii::t('app','SAT') ;?></td>
				
				  <?php
					  for($i=0;$i<$count_timing;$i++)
					  {
						?><td class="td">
						<?php	
									$set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[6]['weekday'],'class_timing_id'=>$timing[$i]['id'])); 			
						if(count($set)==0)
						{	
							$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
							if($is_break!=NULL)
							{	
								echo  Yii::t('app','Break');	
							}
						}
						elseif($set->is_elective==0)
						{
							$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
							if($time_sub!=NULL)
							{
								echo $time_sub->name.'<br>';
								$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
								if($time_emp!=NULL)
								{
									$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));																		
									if($is_substitute and in_array($is_substitute->date_leave,$date_between))
									{
										$employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
										echo '<span style="font-size:9px;">(' .Employees::model()->getTeachername($employee->id).')</span>';										
									}
									else
									{
										echo '<span style="font-size:9px;">(' .Employees::model()->getTeachername($time_emp->id).')</span>';
									}
								}
							}
							else
							{
								echo '-<br>';
							}
						}
						else
						{
							$student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
							$elec_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
							$electname = ElectiveGroups::model()->findByAttributes(array('id'=>$elec_sub->elective_group_id,'batch_id'=>$_REQUEST['id']));
							$is_exist_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$_REQUEST['id'],'elective_group_id'=>$electname->id,'elective_id'=>$elec_sub->id));
							$employee = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
							//}
																			
							if($electname!=NULL and $is_exist_elective!=NULL)
							{
								echo $electname->name.'<br>';
								//echo '<span style="font-size:9px;">(' .Employees::model()->getTeachername($employee->id).')</span>';	
							}
						}
						 ?>
							  </td>
				   <?php            
					 }
					?><!--timetable_td -->
				
			  </tr>
			<?php } ?>
		  </table>
		
	<?php
	 }
     else
	 {
	?>
		
        <?php echo  '<i>'.Yii::t('app','No Class Timings is set for this batch').'</i>'; ?>
       
    	
	<?php
	 }
	 ?>
     
       <?php /*?><?php
		$batch = Weekdays::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id']));
		if(count($batch)==0)
		$batch = Weekdays::model()->findAll("batch_id IS NULL");
		?><?php */?>
        
        <?php
		
	}
	?>
 
