<script language="javascript">
function getday()
{
		
		
		var day=  document.getElementById('day_id').value;
		if(day_id != '')
		{
			window.location= 'index.php?r=/teachersportal/default/daytimetable&department_id='+'&day_id='+day;
		}
}
</script>
<div id="parent_Sect">
	<?php $this->renderPartial('leftside');?> 
	<div class="right_col"  id="req_res123">
    <!--contentArea starts Here--> 
     <div id="parent_rightSect">
        <div class="parentright_innercon">
        <div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-calendar-o"></i><?php echo Yii::t("app", 'Time Table');?><span><?php echo Yii::t("app", 'View your Time Table here');?> </span></h2>
        </div>
        <div class="col-lg-2">
        
                </div>
    
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t("app", 'You are here:');?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t("app", 'Time Table');?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>
    <div class="contentpanel">
    
<div class="panel-heading">
            <h3 class="panel-title"><?php echo Yii::t('app','Day Wise Time Table'); ?></h3>           
        	</div>
            <div class="people-item">
             <?php $this->renderPartial('/default/employee_tab');?>
             <div> 
              <div class="form-group"> 
					<table style=" font-weight:normal;">
                    <tr>
                   <td>&nbsp;</td>
                                         <td style="width:150px;"><strong><?php echo Yii::t('app','Select Day');?></strong></td>                                            <td>&nbsp;</td>
                                         <td>
					<?php 
					 echo CHtml::dropDownList('day_id','',array('1'=>'Sunday','2'=>'Monday','3'=>'Tuesday','4'=>'Wednesday','5'=>'Thursday','6'=>'Friday','7'=>'Saturday'),array('prompt'=>Yii::t("app",'Select day'),'style'=>'width:190px;','onchange'=>'getday()','class'=>'form-control','id'=>'day_id','options'=>array($_REQUEST['day_id']=>array('selected'=>true))));
					
					  ?> 
                     </td>
                     </tr>
					 </table>  
                     </div>                
                </div>
                <!-- Search Result -->
              <?php 
			  if($_REQUEST['day_id']!=NULL)
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
			  
			  
			  $employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
			  $timetable = TimetableEntries::model()->findAllByAttributes(array('employee_id'=>$employee->id,'weekday_id'=>$_REQUEST['day_id']));
			
			  $current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
			  $ac_year=$current_academic_yr->config_value;
			  if($timetable!=NULL)
			  {
			  	echo CHtml::link(Yii::t('app','Generate PDF'), array('Default/daypdf','department_id'=>$_REQUEST['department_id'],'day_id'=>$_REQUEST['day_id']),array('class'=>'btn btn-danger pull-right','target'=>'_blank'));
			  }
			  ?>
              <div class="cleararea"></div>
              <br />
			  <?php
			  if(isset($_REQUEST['day_id']) and $_REQUEST['day_id']!=NULL){   
			  		if($timetable!=NULL){ ?>
                    	<div class="table-responsive">
                        	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered mb30">
                            	<thead>
                          			<tr class="pdtab-h">
                                        <th><?php echo Yii::t('app','Class Timing');?></th>
                                        <th><?php echo Yii::t('app','Course');?></th>
                                        <th><?php echo  Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name');?></td>
                                        <?php  $sem_enabled	=	Configurations::model()->isSemesterEnabled();
													if($sem_enabled	== 1){?>
                                                    <th><?php echo Yii::t('app','Semester');?></th>
											<?php }?>
                                        <th><?php echo Yii::t('app','Subject');?></th>
                         			</tr>
                                    <?php 
									
                          											
								foreach($timetable as $timetable_1) // check acadamic year
							       {
									   	$weekday_id = $_REQUEST['day_id'];
									    // checking if classtime is present for selected weekday
										  $class_time = ClassTimings::model()->findByAttributes(array('id'=>$timetable_1->class_timing_id));
										  $class_flag = 0;
										  
										  if($weekday_id == 1){ // if selected day is sunday, then in classtiming table on_sunday field should be 1 for displaying that timetable
											 if($class_time->on_sunday == 1){
											  $class_flag = 1;
											 }
										  }
										  if($weekday_id == 2){
											  if($class_time->on_monday == 1){
											  $class_flag = 1;
											 }
										  }
										  if($weekday_id == 3){ 
											  if($class_time->on_tuesday == 1){
											  $class_flag = 1;
											 }
										  }
										  if($weekday_id == 4){
											  if($class_time->on_wednesday == 1){
												$class_flag = 1;
											  }
										  }
										  if($weekday_id == 5){
											  if($class_time->on_thursday == 1){
											  $class_flag = 1;
											 }
										  }
										  if($weekday_id == 6){
											 if($class_time->on_friday == 1){
											  $class_flag = 1;
											 }
										  }
										  if($weekday_id == 7){
											 if($class_time->on_saturday == 1){
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
								 
					
						if($timetable!=NULL and $flag==1) // If class timing is set for the day and check acadamic year
                            { 
							  $flag_1=0;
							  foreach($timetable as $timetable_1) // check acadamic year
							      {
											   
								  $batch=Batches::model()->findByAttributes(array('id'=>$timetable_1->batch_id,'academic_yr_id'=>$current_academic_yr->config_value));
								  
								  
								  
								  $class_timing=ClassTimings::model()->findByAttributes(array('id'=>$timetable_1->class_timing_id)); 
								  if($timetable_1->is_elective==0)
								  {	
									$subject=Subjects::model()->findByAttributes(array('id'=>$timetable_1->subject_id));
								  }
								  else if($timetable_1->is_elective==2)
								  {
									  /*$elective=Electives::model()->findByAttributes(array('id'=>$timetable_1->subject_id));
									  $subject=ElectiveGroups::model()->findByAttributes(array('id'=>$elective->elective_group_id));*/
									  $subject=Electives::model()->findByAttributes(array('id'=>$timetable_1->subject_id));
								  }
								  $course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
								  
								  $is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$employee->id,'time_table_entry_id'=>$timetable_1->id));
								  
								  $is_assigned = TeacherSubstitution::model()->findByAttributes(array('substitute_emp_id'=>$employee->id,'date_leave'=>$date_between[$_REQUEST['day_id']-1],'batch'=>$batch->id));	
								  
								 	
								  	$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));	
									if($current_academic_yr->config_value == $batch->academic_yr_id){
								  
								    	if($batch!=NULL and $class_timing!=NULL and $subject!=NULL and $course!=NULL and !$is_substitute and !in_array($is_substitute->date_leave,$date_between))
										{
								    echo '<td>'.$class_timing->start_time.'-'.$class_timing->end_time.'</td>';                             echo '<td>'.ucfirst($course->course_name).'</td>';
								    echo '<td>'.ucfirst($batch->name).'</td>';
									
									 $sem_enabled_course = Configurations::model()->isSemesterEnabledForCourse($batch->course_id);
									 $semester = Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
										if($sem_enabled==1 and $sem_enabled_course == 1 and $semester->name != NULL){
											echo '<td>'.$semester->name.'</td>';
										}
										else if($semester->name == NULL){
											echo '<td>'.'-'.'</td>';
										}
										else
										{
											echo '<td>'.'-'.'</td>';
										}
									
									//.ucfirst($subject->name).
								    echo '<td>';
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
								 
							}
						else // If class timing is NOT set for the employee
                            {  
							echo '<tr>';
                            echo'<td colspan="5" align="center">' .'<i>'.Yii::t('app','No Timetable is set for you!').'</i>'.'</td>';                            echo '</tr>';
                            }
			  
									?>
                            </table>
						</div>
                  <?php }
				  else{
					  echo'<i>'.Yii::t('app','No Timetable is set for you!').'</i>'; 
				  }
				  }?> 
						<div class="atdn_div">
                            <div class="timetable_div">
                                <div class="table-responsive">
								</div>
                        	</div> <!-- End timetable div (timetable_div)-->
						</div> <!-- End entire div (atdn_div) -->
                
			</div>
		</div>
	</div>
	 <div class="clear"></div>
</div>
