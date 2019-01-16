<style>
.table-responsive {

    overflow-x: scroll;
    overflow-y: hidden;
    width: 100%;
}
</style>
        <?php $this->renderPartial('leftside');?>
     <div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-dedent"></i><?php echo Yii::t('app','Timetable'); ?><span><?php echo Yii::t('app','View Timetable'); ?> </span></h2>
        </div>
        <div class="col-lg-2">

                </div>

        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->

                <li class="active"><?php echo Yii::t('app','Timetable'); ?></li>
            </ol>
        </div>

        <div class="clearfix"></div>

    </div>

         <div class="contentpanel">
    	<div class="people-item">
        <?php
$student	=	Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
if(isset($_GET['bid']) and $_GET['bid']!=NULL){
	$bid		=	$_GET['bid'];
}else{
	$bid		=	$student->batch_id;
}
$batches    = 	BatchStudents::model()->studentBatch($student->id);
if($batches){
	foreach($batches as $batch){
		$batch_list[$batch->id]	= ucfirst($batch->name);
	}
}
$batch_name			= Batches::model()->findByAttributes(array('id'=>$bid));
$course		 		= Courses::model()->findByAttributes(array('id'=>$batch_name->course_id));
$semester_enabled	= Configurations::model()->isSemesterEnabled(); 
$sem_enabled		= Configurations::model()->isSemesterEnabledForCourse($course->id); 
?>
<div class="col-md-4">
    <div class="bewt-attnd-filter">
		<?php
        echo Yii::t('app','Viewing Timetable of').' '.Students::model()->getAttributeLabel('batch_id');
        echo CHtml::dropDownList('bid','',$batch_list,array('id'=>'batch_id','style'=>'width:100%;display: inline;','class'=>'input-form-control','options'=>array($bid=>array('selected'=>true)),'onchange'=>'getmode();'));
        $cal ='{title: "'.Yii::t('app','All Day Event').'",
        start: new Date(y, m, 1)
        },';
        ?>
    </div>
</div> 
<?php if($semester_enabled == 1 and $sem_enabled == 1 and $batch_name->semester_id!=NULL){ 
			$semester	= Semester::model()->findByAttributes(array('id'=>$batch_name->semester_id));?>
				   <?php echo Yii::t('app','Semester :');?>
				   <?php echo ucfirst($semester->name);?>
<?php } ?>
       		<?php
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
			$timing = ClassTimings::model()->findAll("batch_id=:x", array(':x'=>$bid)); // Display pdf button only if there is class timings.
			if($timing!=NULL){
				echo CHtml::link(Yii::t('app','Generate PDF'), array('default/pdf','id'=>$bid),array('class'=>'btn btn-danger pull-right','target'=>'_blank'));
			} 
			$times=Batches::model()->findAll("id=:x", array(':x'=>$bid));
			$weekdays=Weekdays::model()->findAll("batch_id=:x", array(':x'=>$bid));
			if(count($weekdays)==0)
			$weekdays=Weekdays::model()->findAll("batch_id IS NULL");
                        
                        
                        //check elective subject exist for a student
                        $elective_exist_flag=0;
                        $check_ele_model= StudentElectives::model()->findAllByAttributes(array('student_id'=>$student->id,'batch_id'=>$bid));
                        if($check_ele_model)
                        {
                            $elective_exist_flag=1;
                        }
                        
			$criteria=new CDbCriteria;
			$criteria->condition = "batch_id=:x";
			$criteria->params = array(':x'=>$bid);
			$criteria->order = "STR_TO_DATE(start_time, '%h:%i %p')";
			 $timing = ClassTimings::model()->findAll($criteria);
	  		$count_timing = count($timing);
			if($timing!=NULL)
			{
			?>
            <br /><br />
       <div class="row">
       	<div class="col-md-12">
        <div class="table-responsive">
         <table  class="table table-bordered mb30" >
    <tbody><tr>

      <td class="loader">&nbsp;

        </td><!--timetable_td_tl -->

      <?php

			foreach($timing as $timing_1)
			{
				 $settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
								if($settings!=NULL)
								{
									$time1=date($settings->timeformat,strtotime($timing_1->start_time));
									$time2=date($settings->timeformat,strtotime($timing_1->end_time));


								}
			echo '<td class="td"><div class="top" style="font-weight:bold;">'.$time1.' - '.$time2.'</div></td>';
			//echo '<td class="td"><div class="top">'.$timing_1->start_time.' - '.$timing_1->end_time.'</div></td>';
			}
	   ?>


    </tr> <!-- timetable_tr -->

    <?php if($weekdays[0]['weekday']!=0)
	{ ?>
    <tr>
        <td class="td"><div class="name"><?php echo '<strong>'.Yii::t('app','SUN').'</strong>' ;?></div></td>

         <?php
			  for($i=0;$i<$count_timing;$i++)
			  {
				echo '<td class="td">
					<div  onclick="" style="position: relative; ">

					  <div class="tt-subject">
						<div class="subject">'; ?>
			<?php
			/*echo "weekday".$weekdays[0]['weekday'];
			  echo "class timing".$timing[$i]['id'];
			echo "employee".$employee->id;exit;*/
$sets =  TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$bid,'weekday_id'=>$weekdays[0]['weekday'],'class_timing_id'=>$timing[$i]['id']));
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
								echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';

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
								echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';

							}
						}
					 }


					}
				}
				else
				{
					$elec_group="";
					$count=0;
					$electivedetails="";
					foreach($sets as $set)
					{
					if($set->is_elective==0)
					{
						$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
					}
					else if($set->is_elective==2)
					{
						$studid = Yii::app()->user->id;
						$studentdetails = Students::model()->findByAttributes(array('uid'=>$studid));
						$electivedetails = StudentElectives::model()->findAllByAttributes(array('student_id'=>$studentdetails->id,'elective_id'=>$set->subject_id));
						if(count($electivedetails)!=0)
						{
							foreach($electivedetails as $electivedetail)
							{
								$time_sub = Electives::model()->findByAttributes(array('id'=>$electivedetail->elective_id));
								$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
							}
						}
						//$time_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
						//$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
					}
				//$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
                                        $time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
				if($time_sub!=NULL){
				if($time_sub->elective_group_id!=0 and $time_sub->elective_group_id!=NULL)
				{
					$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id));
					if($student_elective!=NULL)
					{
						$electname = Electives::model()->findByAttributes(array('id'=>$student_elective->elective_id,'elective_group_id'=>$time_sub->elective_group_id));
						/*if($electname!=NULL)
						{
							echo $electname->name.'<br>';
						}*/
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
						echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';
					}
					else
					{
						?><div class="employee"><?php
                                                     
							//echo '<div class="employee">'.'<b>'.ucfirst($elec_group->name).'<br>'.ucfirst($time_sub->name).'</b><br>'.$time_emp->first_name.'</div>';
                                                    $is_exist_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$bid,'elective_group_id'=>$elec_group->id,'elective_id'=>$time_sub->id));
                                                        
                                                        
                                                    if($elective_exist_flag==0 and $count==0)
                                                    {
                                                        if($elec_group!=NULL){
															echo '<b>'.ucfirst($elec_group->name).'</b>'. '<br>'.Employees::model()->getTeachername($time_emp->id);
														}
                                                    }
                                                    
                                                    if($is_exist_elective!=NULL)
                                                    {
                                                        echo '<b>'.ucfirst($elec_group->name).'</b>';
                                                        echo '<br>'.ucfirst($time_sub->name).'</b>'
                                                                . '<br>'.Employees::model()->getTeachername($time_emp->id);
                                                    }
                                                                ?></div><?php
					}
				}
					$count++;
				}
				}
		 ?>
					<?php echo 	'</div>

					  </div>
					</div>
					<div id="jobDialog'.$timing[$i]['id'].$weekdays[0]['weekday'].'"></div>
				  </td>';
			  }
			  ?>



      </tr>
      <?php }
	  if($weekdays[1]['weekday']!=0)
	  { ?>
      <tr>
        <td class="td"><div class="name"><?php echo '<strong>'.Yii::t('app','MON').'</strong>';?></div></td>

        	 <?php
			  for($i=0;$i<$count_timing;$i++)
			  {
				echo ' <td class="td">
						<div  onclick="" style="position: relative; ">
						  <div class="tt-subject">
							<div class="subject">';
		$sets =  TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$bid,'weekday_id'=>$weekdays[1]['weekday'],'class_timing_id'=>$timing[$i]['id']));           
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
								echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';

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
								echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';

							}
						}
					 }


					}
				}
				else
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

					//$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));

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
								//echo $electname->name.'<br>';
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
							echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';
						}
						else
						{
							?><div class="employee"><?php
                                                     
							//echo '<div class="employee">'.'<b>'.ucfirst($elec_group->name).'<br>'.ucfirst($time_sub->name).'</b><br>'.$time_emp->first_name.'</div>';
                                                    $is_exist_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$bid,'elective_group_id'=>$elec_group->id,'elective_id'=>$time_sub->id));
                                                        
                                                        
                                                    if($elective_exist_flag==0 and $count==0)
                                                    {
														if($elec_group!=NULL){
                                                        echo '<b>'.ucfirst($elec_group->name).'</b>'. '<br>'.Employees::model()->getTeachername($time_emp->id);
														}
                                                    }
                                                    
                                                    if($is_exist_elective!=NULL)
                                                    {
                                                        echo '<b>'.ucfirst($elec_group->name).'</b>';
                                                        echo '<br>'.ucfirst($time_sub->name).'</b>'
                                                                . '<br>'.Employees::model()->getTeachername($time_emp->id);
                                                    }
                                                                ?></div><?php
						}
					}
						$count++;
					}


				}

						echo '</div>
						  </div>
						</div>
						<div id="jobDialog'.$timing[$i]['id'].$weekdays[1]['weekday'].'"></div>
					  </td>';
			 }
			?>
          <!--timetable_td -->

      </tr><!--timetable_tr -->
      <?php }
	  if($weekdays[2]['weekday']!=0)
	  {
	  ?>
          <tr>
        <td class="td"><div class="name"><?php echo '<strong>'.Yii::t('app','TUE').'</strong>';?></div></td>

         <?php
			  for($i=0;$i<$count_timing;$i++)
			  {
				echo ' <td class="td">
						<div  onclick="" style="position: relative; ">
						  <div class="tt-subject">
							<div class="subject">';
							$sets =  TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$bid,'weekday_id'=>$weekdays[2]['weekday'],'class_timing_id'=>$timing[$i]['id']));

				if(count($sets)==0)
				{
					$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
					if($is_break!=NULL)
					{
						echo Yii::t('app','Break');
					}
					else
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
									echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';

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
									echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';

								}
							}
						 }



					}
				}
				else
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
					//$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
					if($time_sub!=NULL){
					if($time_sub->elective_group_id!=0 and $time_sub->elective_group_id!=NULL)
					{

						$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id));
						if($student_elective!=NULL)
						{
							$electname = Electives::model()->findByAttributes(array('id'=>$student_elective->elective_id,'elective_group_id'=>$time_sub->elective_group_id));
							if($electname!=NULL)
							{
								//echo $electname->name.'<br>';
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
							echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';
						}
						else
						{
							?><div class="employee"><?php
                                                     
							//echo '<div class="employee">'.'<b>'.ucfirst($elec_group->name).'<br>'.ucfirst($time_sub->name).'</b><br>'.$time_emp->first_name.'</div>';
                                                    $is_exist_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$bid,'elective_group_id'=>$elec_group->id,'elective_id'=>$time_sub->id));
                                                        
                                                        
                                                    if($elective_exist_flag==0 and $count==0)
                                                    {
                                                       if($elec_group!=NULL){
                                                        echo '<b>'.ucfirst($elec_group->name).'</b>'. '<br>'.Employees::model()->getTeachername($time_emp->id);
														}
                                                    }
                                                    
                                                    if($is_exist_elective!=NULL)
                                                    {
                                                        echo '<b>'.ucfirst($elec_group->name).'</b>';
                                                        echo '<br>'.ucfirst($time_sub->name).'</b>'
                                                                . '<br>'.Employees::model()->getTeachername($time_emp->id);
                                                    }
                                                                ?></div><?php
						}
					}
						$count++;
				}

				}


						echo	'</div>

						  </div>
						</div>
						<div id="jobDialog'.$timing[$i]['id'].$weekdays[2]['weekday'].'"></div>
					  </td>';

			 }
			?><!--timetable_td -->

      </tr><!--timetable_tr -->
      <?php }
	  if($weekdays[3]['weekday']!=0)
	  {
	  ?>
          <tr>
        <td class="td"><div class="name"><?php echo '<strong>'.Yii::t('app','WED').'</strong>';?></div></td>

         <?php
			  for($i=0;$i<$count_timing;$i++)
			  {
				echo '<td class="td">
					<div  onclick="" style="position: relative; ">

					  <div class="tt-subject">
						<div class="subject">'; ?>
			<?php
			/*echo "weekday".$weekdays[3]['weekday'];
			  echo "class timing".$timing[$i]['id'];
			echo "employee".$employee->id;exit;*/
				$sets =  TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$bid,'weekday_id'=>$weekdays[3]['weekday'],'class_timing_id'=>$timing[$i]['id']));
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
								echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';

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
								echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';

							}
						}
					 }


					}
				}
				else
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
					//$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
					if($time_sub!=NULL){
					if($time_sub->elective_group_id!=0 and $time_sub->elective_group_id!=NULL)
					{

						$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id));
						if($student_elective!=NULL)
						{
							$electname = Electives::model()->findByAttributes(array('id'=>$student_elective->elective_id,'elective_group_id'=>$time_sub->elective_group_id));
							if($electname!=NULL)
							{
								//echo $electname->name.'<br>';
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
							echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';
						}
						else
						{
                                                    ?><div class="employee"><?php
                                                     
							//echo '<div class="employee">'.'<b>'.ucfirst($elec_group->name).'<br>'.ucfirst($time_sub->name).'</b><br>'.$time_emp->first_name.'</div>';
                            $is_exist_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$bid,'elective_group_id'=>$elec_group->id,'elective_id'=>$time_sub->id));
                                                        
                                                        
							if($elective_exist_flag==0 and $count==0)
							{
												if($elec_group!=NULL){
                                                        echo '<b>'.ucfirst($elec_group->name).'</b>'. '<br>'.Employees::model()->getTeachername($time_emp->id);
														}
							}
							
							if($is_exist_elective!=NULL)
							{
								echo '<b>'.ucfirst($elec_group->name).'</b>';
								echo '<br>'.ucfirst($time_sub->name).'</b>'
										. '<br>'.Employees::model()->getTeachername($time_emp->id);
							}
										?></div><?php

						}
					}
                                        $count++;
				}

				}
		 ?>
					<?php echo 	'</div>

					  </div>
					</div>
					<div id="jobDialog'.$timing[$i]['id'].$weekdays[0]['weekday'].'"></div>
				  </td>';
			  }
			?><!--timetable_td -->

      </tr><!--timetable_tr -->
      <?php }
	  if($weekdays[4]['weekday']!=0)
	  {  ?>
          <tr>
        <td class="td"><div class="name"><?php echo '<strong>'.Yii::t('app','THU').'</strong>';?></div></td>

          <?php
			  for($i=0;$i<$count_timing;$i++)
			  {
				echo ' <td class="td">
						<div  onclick="" style="position: relative; ">
						  <div class="tt-subject">
							<div class="subject">';
				$sets =  TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$bid,'weekday_id'=>$weekdays[4]['weekday'],'class_timing_id'=>$timing[$i]['id']));
				if(count($sets)==0)
				{
					$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
					if($is_break!=NULL)
					{
						echo Yii::t('app','Break');
					}
					else
					{
						 $set =  TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[0]['weekday'],'class_timing_id'=>$timing[$i]['id'],'employee_id'=>NULL));
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
									echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';

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
									echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';

								}
							}
						 }
					}
				}
				else
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
					//$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
					if($time_sub!=NULL){
					if($time_sub->elective_group_id!=0 and $time_sub->elective_group_id!=NULL)
					{

						$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id));
						if($student_elective!=NULL)
						{
							$electname = Electives::model()->findByAttributes(array('id'=>$student_elective->elective_id,'elective_group_id'=>$time_sub->elective_group_id));
							if($electname!=NULL)
							{
								//echo $electname->name.'<br>';
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
							echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';
						}
						else
						{
							?><div class="employee"><?php
                                                     
							//echo '<div class="employee">'.'<b>'.ucfirst($elec_group->name).'<br>'.ucfirst($time_sub->name).'</b><br>'.$time_emp->first_name.'</div>';
                            $is_exist_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$bid,'elective_group_id'=>$elec_group->id,'elective_id'=>$time_sub->id));
                                                        
                                                        
							if($elective_exist_flag==0 and $count==0)
							{
								if($elec_group!=NULL){
									echo '<b>'.ucfirst($elec_group->name).'</b>'. '<br>'.Employees::model()->getTeachername($time_emp->id);
									}
							}
							
							if($is_exist_elective!=NULL)
							{
								echo '<b>'.ucfirst($elec_group->name).'</b>';
								echo '<br>'.ucfirst($time_sub->name).'</b>'
										. '<br>'.Employees::model()->getTeachername($time_emp->id);
							}
										?></div><?php
						}
					}
						$count++;
				}

				}

						echo '</div>

						  </div>
						</div>
						<div id="jobDialog'.$timing[$i]['id'].$weekdays[4]['weekday'].'"></div>
					  </td>';
			 }
			?><!--timetable_td -->

      </tr><!--timetable_tr -->
      <?php }
	  if($weekdays[5]['weekday']!=0)
	  { ?>

          <tr>
        <td class="td"><div class="name"><?php echo '<strong>'.Yii::t('app','FRI').'</strong>';?></div></td>

         <?php
			  for($i=0;$i<$count_timing;$i++)
			  {
				echo ' <td class="td">
						<div  onclick="" style="position: relative; ">
						  <div class="tt-subject">
							<div class="subject">';
				$sets =  TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$bid,'weekday_id'=>$weekdays[5]['weekday'],'class_timing_id'=>$timing[$i]['id']));
				if(count($sets)==0)
				{
					$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
					if($is_break!=NULL)
					{
						echo Yii::t('app','Break');
					}
					else
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
									echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';

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
									echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';

								}
							}
						 }
					}
				}
				else
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
					//$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
					if($time_sub!=NULL){
					if($time_sub->elective_group_id!=0 and $time_sub->elective_group_id!=NULL)
					{
						$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id));
						if($student_elective!=NULL)
						{
							$electname = Electives::model()->findByAttributes(array('id'=>$student_elective->elective_id,'elective_group_id'=>$time_sub->elective_group_id));
							if($electname!=NULL)
							{
								//echo $electname->name.'<br>';
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
							echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';
						}
						else
						{
							?><div class="employee"><?php
                                                     
							//echo '<div class="employee">'.'<b>'.ucfirst($elec_group->name).'<br>'.ucfirst($time_sub->name).'</b><br>'.$time_emp->first_name.'</div>';
                                                    $is_exist_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$bid,'elective_group_id'=>$elec_group->id,'elective_id'=>$time_sub->id));
                                                        
                                                        
                                                    if($elective_exist_flag==0 and $count==0)
                                                    {
                                                        if($elec_group!=NULL){
															echo '<b>'.ucfirst($elec_group->name).'</b>'. '<br>'.Employees::model()->getTeachername($time_emp->id);
														}
                                                    }
                                                    
                                                    if($is_exist_elective!=NULL)
                                                    {
                                                        echo '<b>'.ucfirst($elec_group->name).'</b>';
                                                        echo '<br>'.ucfirst($time_sub->name).'</b>'
                                                                . '<br>'.Employees::model()->getTeachername($time_emp->id);
                                                    }
                                                                ?></div><?php
						}
					}
						$count++;
				}

				}
							echo '</div>

						  </div>
						</div>
						<div id="jobDialog'.$timing[$i]['id'].$weekdays[5]['weekday'].'"></div>
					  </td>';
			 }
			?><!--timetable_td -->

      </tr><!--timetable_tr -->
      <?php }
	  if($weekdays[6]['weekday']!=0)
	  { ?>
      <tr>
        <td class="td"><div class="name"><?php echo '<strong>'.Yii::t('app','SAT').'</strong>';?></div></td>

          <?php
			  for($i=0;$i<$count_timing;$i++)
			  {
				echo ' <td class="td">
						<div  onclick="" style="position: relative; ">
						  <div class="tt-subject">
							<div class="subject">';
							$sets =  TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$bid,'weekday_id'=>$weekdays[6]['weekday'],'class_timing_id'=>$timing[$i]['id']));
				if(count($sets)==0)
				{
					$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
					if($is_break!=NULL)
					{
						echo Yii::t('app','Break');
					}
					else
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
									echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';

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
									echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';

								}
							}
						 }
					}
				}
				else
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
					//$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
					if($time_sub!=NULL){
					if($time_sub->elective_group_id!=0 and $time_sub->elective_group_id!=NULL)
					{

						$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id));
						if($student_elective!=NULL)
						{
							$electname = Electives::model()->findByAttributes(array('id'=>$student_elective->elective_id,'elective_group_id'=>$time_sub->elective_group_id));
							if($electname!=NULL)
							{
								//echo $electname->name.'<br>';
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
							echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';
						}
						else
						{
							?><div class="employee"><?php
                                                     
							//echo '<div class="employee">'.'<b>'.ucfirst($elec_group->name).'<br>'.ucfirst($time_sub->name).'</b><br>'.$time_emp->first_name.'</div>';
                                                    $is_exist_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$bid,'elective_group_id'=>$elec_group->id,'elective_id'=>$time_sub->id));
                                                        
                                                        
                                                    if($elective_exist_flag==0 and $count==0)
                                                    {
                                                        if($elec_group!=NULL){
															echo '<b>'.ucfirst($elec_group->name).'</b>'. '<br>'.Employees::model()->getTeachername($time_emp->id);
														}
                                                    }
                                                    
                                                    if($is_exist_elective!=NULL)
                                                    {
                                                        echo '<b>'.ucfirst($elec_group->name).'</b>';
                                                        echo '<br>'.ucfirst($time_sub->name).'</b>'
                                                                . '<br>'.Employees::model()->getTeachername($time_emp->id);
                                                    }
                                                                ?></div><?php
						}
					}
						$count++;
				}

				}
							echo '</div>

						  </div>
						</div>
						<div id="jobDialog'.$timing[$i]['id'].$weekdays[6]['weekday'].'"></div>
					  </td>';
			 }
			?><!--timetable_td -->

      </tr>
    <?php } ?>
  </tbody></table>

         <?php
			}
			 else
	 {
		 echo '<strong>'.Yii::t('app','No Class Timings').'</strong>';
	 }?>
	</div>
        </div>
       </div>
       

            </div>
        </div>
        
<script language="javascript">
function getmode(){
	//var student_id	= <?php //echo $student->id; ?>;
	var batch_id	= $('#batch_id').val();
		if(batch_id != ''){
			window.location= 'index.php?r=studentportal/default/timetable&bid='+batch_id;
		}
		else{
			window.location= 'index.php?r=studentportal/default/attendance';
		}
};
</script>
