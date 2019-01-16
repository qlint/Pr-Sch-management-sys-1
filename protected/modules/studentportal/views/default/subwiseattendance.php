<style>
.table-responsive {
    border: 1px solid #ddd;
    margin-bottom: 15px;
    overflow-x: scroll;
    overflow-y: hidden;
    width: 100%;
}
</style>
<?php $student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id)); ?>
<script language="javascript">
function getmode(type){
	var batch_id	= $('#batch_id').val();
	if(type	== 1){
		if(batch_id != ''){
			window.location= 'index.php?r=studentportal/default/subwiseattendance&bid='+batch_id;
		}
		else{
			window.location= 'index.php?r=studentportal/default/subwiseattendance';
		}
	}	
};
</script>
<?php $this->renderPartial('leftside');?>
<div class="pageheader">
    <div class="col-lg-8">
        <h2><i class="fa fa-file-text"></i><?php echo Yii::t('app','Attendance'); ?><span><?php echo Yii::t('app','View Subject Wise Attendance'); ?> </span></h2>
    </div>
    <div class="col-lg-2"></div>
    <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
        <ol class="breadcrumb">
            <li class="active"><?php echo Yii::t('app','Attendance'); ?></li>
        </ol>
    </div>
    <div class="clearfix"></div>
</div>
<?php
 	$batches    = 	BatchStudents::model()->studentBatch($student->id);
	  if(count($batches) == 1){
			$batch    	= 	BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'result_status'=>0));
			$bid 		=  $batch->batch_id;		
		}
		elseif(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL){
			$bid 		=  $_REQUEST['bid'];	
		}
		elseif(count($batches)>1  or $_REQUEST['bid'] == NULL){
			$batch    	= 	BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'result_status'=>0, 'batch_id'=>$batches[0]->id));
			$bid 		=  $batch->batch_id;	
		}
		
	$batch=Batches::model()->findByAttributes(array('id'=>$bid));
	$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
	$yr = AcademicYears::model()->findByAttributes(array('id'=>$current_academic_yr->config_value));
	$course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
	$semester=Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); 
	$sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);
	
	$date				= (isset($_REQUEST['date']))?$_REQUEST['date']:date("Y-m-d");
	$day 				= date('w', strtotime($date));
	$week_start			= date('Y-m-d', strtotime('-'.$day.' days', strtotime($date)));
	$week_end 			= date('Y-m-d', strtotime('+'.(6-$day).' days', strtotime($date)));
	$prev_week_start	= date('Y-m-d', strtotime('-7 days', strtotime($week_start)));
	$next_week_start	= date('Y-m-d', strtotime('+1 days', strtotime($week_end)));
	$this_date			= $week_start;
	
	$batches    = 	BatchStudents::model()->studentBatch($student->id);
	if($batches){
		foreach($batches as $batch_1){
			$batch_list[$batch_1->id]	= ucfirst($batch_1->name);
		}
	}
	$timetable = TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$bid)); 
?>



<div class="contentpanel">
   <div class="people-item">
   <div class="row">
   <div class="col-md-4">
   <?php
echo Yii::t('app','Viewing Attendance of').' '.Students::model()->getAttributeLabel('batch_id');	
echo CHtml::dropDownList('bid','',$batch_list,array(Students::model()->getAttributeLabel('batch_id'),'id'=>'batch_id','class'=>'input-form-control','options'=>array($bid=>array('selected'=>true)),'encode'=>false,'onchange'=>'getmode(1);'));
echo '</br>';
echo Yii::t('app','Course').' : '.ucfirst($course->course_name);
echo '</br>';

  if($sem_enabled == 1 and $batch->semester_id != NULL){
	echo Yii::t('app','Semester').' : '.ucfirst($semester->name);
 }
   ?>
   </div>
   </div>
   </div>
    <div class="people-item">
    <?php
    $times			= Batches::model()->findAll("id=:x", array(':x'=>$bid));
    $weekdays		= Weekdays::model()->findAll("batch_id=:x", array(':x'=>$bid));
    if(count($weekdays)==0)
        $weekdays=Weekdays::model()->findAll("batch_id IS NULL");
    
    //check elective subject exist for a student
    $elective_exist_flag=0;
    $check_ele_model= StudentElectives::model()->findAllByAttributes(array('student_id'=>$student->id,'batch_id'=>$student->batch_id));
    if($check_ele_model){
        $elective_exist_flag=1;
    }
    
    $sun = Yii::t('app','SUN');
    $mon = Yii::t('app','MON');
    $tue = Yii::t('app','TUE');
    $wed = Yii::t('app','WED');
    $thu = Yii::t('app','THU');
    $fri = Yii::t('app','FRI');
    $sat = Yii::t('app','SAT');
    $weekday_text = array($sun, $mon, $tue, $wed, $thu, $fri, $sat);?>
    
   
                        <?php                                    
                        if($bid!=NULL){
							$batch 		= Batches::model()->findByAttributes(array('id'=>$bid));
                            $times		= Batches::model()->findAll("id=:x", array(':x'=>$bid));
                            $weekdays	= Weekdays::model()->findAll("batch_id=:x", array(':x'=>$bid));										
                            if(count($weekdays)==0)
                                $weekdays=Weekdays::model()->findAll("batch_id IS NULL");
                                
                            $sun = Yii::t('app','SUN');
                            $mon = Yii::t('app','MON');
                            $tue = Yii::t('app','TUE');
                            $wed = Yii::t('app','WED');
                            $thu = Yii::t('app','THU');
                            $fri = Yii::t('app','FRI');
                            $sat = Yii::t('app','SAT');
                            $weekday_text = array($sun, $mon, $tue, $wed, $thu, $fri, $sat);
                                
							$weekday_attributes	= array(1=>'on_sunday',2=>'on_monday',3=>'on_tuesday',4=>'on_wednesday',5=>'on_thursday',6=>'on_friday',7=>'on_saturday');
							
							$criteria				= new CDbCriteria;
							$criteria->condition 	= "batch_id=:x";
							$criteria->params 		= array(':x'=>$bid);
							$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
							$timings 				= ClassTimings::model()->findAll($criteria);
							$count_timing 			= count($timings);
							
                            if($timings!=NULL){
								if(count($timetable)>0){
								?>
							
<div class="row">
	<div class="row-pddng">
	<div class="col-md-3">
        <div class="atnd_table-calender atnd_tnav-new " align="center">
        <?php
            echo CHtml::link('<div class="atnd_arow_l"><img src="'.Yii::app()->request->baseUrl.'/images/atnd_arrow-l.png" height="13" width="7" border="0"></div>', array('/studentportal/Default/subwiseattendance', 'bid'=>$bid, 'date'=>$prev_week_start), array('title'=>Yii::t('app', 'Previous Week')));											
											echo date("M d", strtotime($week_start))." - ".date("M d", strtotime($week_end));
											
											echo CHtml::link('<div class="atnd_arow_r"><img src="'.Yii::app()->request->baseUrl.'/images/atnd_arrow.png" height="13" width="7" border="0"></div>', array('/studentportal/Default/subwiseattendance', 'bid'=>$bid, 'date'=>$next_week_start), array('title'=>Yii::t('app', 'Next Week')));																
        ?>                                    
        </div>
    </div>
	<div class="col-md-9">
    	<div class="row">
            <div class="col-md-3 pull-right">
                <?php  echo CHtml::link(Yii::t('app','Generate PDF'), array('Default/subwisepdf','bid'=>$bid,  'date'=>$date),array('class'=>'btn btn-danger pull-right','target'=>'_blank')); ?>
             </div>
        </div>
    </div>
    </div>    
</div>
   
   
                     <div class="clearfix"></div>
                     <div id="jobDialog"></div>
                        <div class="timetable-grid timetable-grid-twoside">
                        <div class="timetable-grid-scroll">
                        <table border="0" align="center" width="100%" id="table" cellspacing="0">
                            <tbody>
                                <tr>
                                    <th width="80" class="loader">&nbsp;</th><!--timetable_td_tl -->
                                    <?php 
                                    foreach($timings as $timing_1)
                                    {
                                        $settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
                                        if($settings!=NULL)
                                        {	
                                            $time1=date($settings->timeformat,strtotime($timing_1->start_time));
                                            $time2=date($settings->timeformat,strtotime($timing_1->end_time));
                                        }
                                        echo '<th width="130px" class="td"><center><div class="top">'.$time1.' - '.$time2.'</div></center></th>';	
                                    }
                                    ?>
                                </tr> <!-- timetable_tr -->
                                <?php
                                $weekday_text = array('SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT');
                                $weekday_count	= 0;					
                                foreach($weekdays as $weekday){														
                                    if($weekday['weekday']!=0) // SUNDAY
                                    {
                                    ?>
                                    <tr>
                                            <td class="td daywise-block">
                                                <h3><?php echo Yii::t('app',$weekday_text[$weekday['weekday']-1]);?></h3>

                                                <p><?php echo date("d M Y", strtotime($this_date)); ?></p>
                                                <?php $weekday_count++; ?>
                                            </td>
                                        
                                        <?php
                                        for($i=0;$i<$count_timing;$i++)
                                        {
                                        	$criteria				= new CDbCriteria;
											$criteria->join			= 'JOIN `class_timings` `t1` ON `t1`.`id` = `t`.`class_timing_id`';												
											$criteria->condition	= '`t`.`batch_id`=:batch_id AND `t`.`weekday_id`=:weekday_id AND `t`.`class_timing_id`=:class_timing_id';
											$criteria->params		= array(':batch_id'=>$bid, ':weekday_id'=>$weekday['weekday'], ':class_timing_id'=>$timings[$i]['id']);
											
											$set =  TimetableEntries::model()->find($criteria);
											
                                         ?>	
                                          <td class="td"> 
                                          <div class="posiction-table">	
                                         <?php
										  	if($set == NULL){
													$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timings[$i]['id'],'is_break'=>1));
													if($is_break!=NULL)
													{	
														echo Yii::t('app','Break');
													}
											 }       
										 else
                                            { 	
											$subjectwise =  StudentSubjectwiseAttentance::model()->findByAttributes(array('student_id'=>$student->id, 'timetable_id'=>$set->id, 'weekday_id'=>$set->weekday_id, 'date'=>$week_start));
											   $is_holiday		= StudentAttentance::model()->isHoliday($week_start);
												if($is_holiday == NULL){
                                                    if($subjectwise == NULL){
														if($batch->start_date <= $week_start and $week_start <= $batch->end_date ){
															if($week_start >= $student->admission_date and $week_start <= date("Y-m-d")){												
																echo '<span style="color:#077109; font-weight:600;">'.Yii::t('app',"Present").'</span><br/>';
															}
														}
													}
												}
												else{
													echo '<div class="attnd-holiday">'.Yii::t('app','Holiday').'</div>';
												}
                                              
                                               	if($subjectwise!=NULL){ ?>	
                                                    <div class="mark-absent-blk" >                                                                
                                                        <p>
                                                            <?php 
                                                            echo CHtml::ajaxLink('Absent',$this->createUrl('default/viewsubwise'),
                                                            array('onclick'=>'$("#jobDialog_view").dialog("open"); return false;','update'=>'#jobDialog_view_div'.$subjectwise->id,'type' =>'GET','data' => array('id' =>$subjectwise->id),'dataType' => 'text',),array('id'=>'showJobDialog_view'.$subjectwise->id,'class'=>'mark-absent', 'title'=>Yii::t('app','View')));
                                                            ?>
                                                         </p>       
                                                    </div>
                                                <?php } 
												//?>   
                             							<div id="jobDialog_view_div<?php echo $subjectwise->id; ?>"></div>	
                                                                <div  onclick="" style="position: relative; ">
                                                                <div class="timtable-subjct-blk">
                                                                    <div class="subject">	
                                                                    <?php
                                                                    if($set->is_elective==0)
                                                                    {
                                                                        $time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));																	
                                                                        if($time_sub!=NULL)
                                                                        {
                                                                            echo $time_sub->name;
                                                                            
                                                                            $time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
                                                                        if($time_emp!=NULL)
                                                                        {
                                                                            $is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));
                                                                            
                                                                            if($is_substitute and in_array($is_substitute->date_leave,$date_between))
                                                                            {
                                                                                $employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
                                                                                echo '<div class="employee">'.ucfirst($employee->first_name).' '.ucfirst($employee->middle_name).' '.ucfirst($employee->last_name).'</div>';
                                                                            }
                                                                            else
                                                                            {
                                                                                if($time_sub!=NULL)
                                                                                {
                                                                                    echo '<div class="employee">'.ucfirst($time_emp->first_name).' '.ucfirst($time_emp->middle_name).' '.ucfirst($time_emp->last_name).'</div>';
                                                                                }
                                                                            }
                                                                        }
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '';
                                                                        }
                                                                    }
                                                                    else
                                                                    {
                                                                        $elec_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
                                                                        
                                                                        
                                                                        $electname = ElectiveGroups::model()->findByAttributes(array('id'=>$elec_sub->elective_group_id,'batch_id'=>$bid));
                                                                        
                                                                        if($electname!=NULL)
                                                                        {
                                                                            echo $electname->name;
                                                                        }
                                                                        $time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
                                                                        if($time_emp!=NULL)
                                                                        {
                                                                            $is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));
                                                                            
                                                                            if($is_substitute and in_array($is_substitute->date_leave,$date_between))
                                                                            {
                                                                                $employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
                                                                                //echo '<div class="employee">'.ucfirst($employee->first_name).' '.ucfirst($employee->middle_name).' '.ucfirst($employee->last_name).'</div>';
                                                                            }
                                                                            else
                                                                            {
                                                                                if($electname!=NULL)
                                                                                {
                                                                                    //echo '<div class="employee">'.ucfirst($time_emp->first_name).' '.ucfirst($time_emp->middle_name).' '.ucfirst($time_emp->last_name).'</div>';
                                                                                }
                                                                            }
                                                                        }
                                                                        
                                                                    }?>
                                                                     </div>
                                                                   
                                                                </div>
                                                            </div> 
                                                        <?php	
                                                            }
                                                            
                                            ?>
                                            <?php echo 	'</div>
                                            </div>
                                            </div>
                                            <div id="jobDialog'.$timing[$i]['id'].$weekday['weekday'].'"></div>
                                           ';  
                                        }
                                        ?>
                                        </div>
                                        </td>
                                    </tr>
                                    <?php 
                                    }
                                    $this_date	= date("Y-m-d", strtotime("+1 days", strtotime($this_date))); 
                                    $week_start	= date("Y-m-d", strtotime("+1 days", strtotime($week_start))); 
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    </div> <!-- END div class="timetable" -->
                <?php
					}
					else{
						 echo '<div class="not-foundarea">';
						 echo Yii::t('app', 'No Timetables');
						 echo '</div>';
						}
                }
                            else{
                                echo '<i>'.Yii::t('app','No Class Timings').'</i>';
                            }
                        }
						else{
							echo '<i>'.Yii::t('app','No Results Found').'</i>';
						}
                        ?> 
                   
                <div class="clear"></div>
               
	</div>