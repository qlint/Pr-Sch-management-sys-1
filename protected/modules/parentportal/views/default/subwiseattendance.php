<!-- Begin Coda Stylesheets -->
<script language="javascript">
function getstudent(){ // Function to see student profile
	var studentid = document.getElementById('studentid').value;
	if(studentid!='')
	{
		window.location= 'index.php?r=parentportal/default/subwiseattendance&id='+studentid;	
	}
	else
	{
		window.location= 'index.php?r=parentportal/default/subwiseattendance';
	}
}

function getmode(type){
	var student_id	= document.getElementById('studentid').value;
	var batch_id	= $('#batch_id').val();
	if(type	== 1){
		if(student_id != '' && batch_id != ''){
			window.location= 'index.php?r=parentportal/default/subwiseattendance&id='+student_id+'&bid='+batch_id;
		}
		else if(student_id != ''){
			window.location= 'index.php?r=parentportal/default/subwiseattendance&id='+student_id;
		}
		else{
			window.location= 'index.php?r=parentportal/default/subwiseattendance';
		}
	}	
}

$(document).ready(function(){
	$("#shbar").click(function(){
		$('#tpanel').toggle();
	});
});
</script>
<?php
	$guardian	= Guardians::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$student_list			= '';
	$academic_yr_list		= '';
	$batch_list				= '';
		
	$criteria 				= new CDbCriteria;		
	$criteria->join 		= 'LEFT JOIN guardian_list t1 ON t.id = t1.student_id'; 
	$criteria->condition 	= 't1.guardian_id=:guardian_id and t.is_active=:is_active and is_deleted=:is_deleted';
	$criteria->params 		= array(':guardian_id'=>$guardian->id,':is_active'=>1,'is_deleted'=>0);
	$students 				= Students::model()->findAll($criteria); 
	
	$date				= (isset($_REQUEST['date']))?$_REQUEST['date']:date("Y-m-d");
	$day 				= date('w', strtotime($date));
	$week_start			= date('Y-m-d', strtotime('-'.$day.' days', strtotime($date)));
	$week_end 			= date('Y-m-d', strtotime('+'.(6-$day).' days', strtotime($date)));
	$prev_week_start	= date('Y-m-d', strtotime('-7 days', strtotime($week_start)));
	$next_week_start	= date('Y-m-d', strtotime('+1 days', strtotime($week_end)));
	$this_date			= $week_start;
        
        $semester_enabled	= Configurations::model()->isSemesterEnabled(); 
	
?>

<?php $this->renderPartial('leftside');?>
 
<div class="pageheader">
    <div class="col-lg-8">
        <h2><i class="fa fa-file-text"></i> <?php echo Yii::t('app','Attendance'); ?> <span><?php echo Yii::t('app','View your attendance here'); ?></span></h2>
    </div>
    <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
        <ol class="breadcrumb">
            <li class="active"><?php echo Yii::t('app','Attendance'); ?></li>
        </ol>
    </div>
    <div class="clearfix"></div>
</div>
<div class="contentpanel">		
	<?php
    //if(count($students)>1){ 
		$student_list = CHtml::listData($students,'id','studentnameforparentportal');
    ?>
    <div class="people-item">
        <div class="row">
        <?php
        if($_REQUEST['id']!=NULL)
            $stdid = $_REQUEST['id'];
        else
            $stdid = $students[0]->id;
			
			$criteria				= new CDbCriteria();
			$criteria->join			= 'LEFT JOIN batch_students t1 ON t.id = t1.batch_id';
			$criteria->condition	= 't.is_active=:is_active AND t.is_deleted=:is_deleted AND t1.student_id=:student_id';
			$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':student_id'=>$_REQUEST['id']);
			$criteria->group		= 't1.batch_id';
			$batches				= Batches::model()->findAll($criteria);
			if($batches){
				foreach($batches as $batch){
					$coursename    = 	Courses::model()->findByAttributes(array('id'=>$batch->course_id)); 
					$batch_list[$batch->id]	= ucfirst($batch->name).' ( '.$coursename->course_name.' )';
				}
			}
			$student			= Students::model()->findByPk($_REQUEST['id']);
			$batch_name			= Batches::model()->findByPk($_REQUEST['bid']);
			$course 			= Courses::model()->findByPk($batch_name->course_id);
        ?>
        <div class="col-md-3">
        <?php echo Yii::t('app','').CHtml::dropDownList('sid','',$student_list,array('prompt'=>Yii::t('app','Select'),'id'=>'studentid','class'=>'input-form-control','options'=>array($stdid=>array('selected'=>true)),'onchange'=>'getstudent();'));?>
        </div>
        <div class="col-md-3">
        <?php echo CHtml::dropDownList('bid','',$batch_list,array('prompt'=>Yii::t('app','Select').' '.Students::model()->getAttributeLabel('batch_id'), 'encode'=>false, 'id'=>'batch_id','class'=>'input-form-control','options'=>array($_REQUEST['bid']=>array('selected'=>true)),'onchange'=>'getmode(1);'));?>
		</div>				     
		</div> <!-- END div class="student_dropdown" -->
		<div class="clearfix"></div>
    </div>
    <?php
    //}
    ?>					
        <div class="panel-heading">
        <!-- panel-btns -->
        <h3 class="panel-title"> <?php echo Yii::t('app','Student Subject Wise Attendance Report');?></h3>	
        <div class="clearfix"></div>
        </div>
        <div class="people-item">
         <?php $student=Students::model()->findByAttributes(array('id'=>$_REQUEST['id'],'is_deleted'=>0,'is_active'=>1));
	 ?>
    <!-- Batch details -->
<div class="opnsl_headerBox">

    <div class="opnsl_actn_box">
        <div class="opnsl_actn_box1">

    <?php 
				if(count($students)>1 and Configurations::model()->studentAttendanceMode() != 2){
					echo CHtml::link(Yii::t('app','Daily Attendance'), array('/parentportal/default/attendance', 'id'=>$stdid, 'yid'=>$stdid),array('class'=>'btn btn-primary'));
	}?>
    </div>
    	</div>
    <div class="opnsl_actn_box"> </div>        
</div>
<div class="table-responsive">
<table class="table table-bordered mb30">
	<thead>
    	<tr>
        <?php if(FormFields::model()->isVisible("fullname", "Students", "forParentPortal")){ ?>
        	<th><?php echo Yii::t('app','Student Name');?></th>
       <?php } ?>
           
            <th width="20%"> <?php echo Yii::t('app','Admission Number');?></th>
            <?php if(FormFields::model()->isVisible('batch_id','Students','forParentPortal')){?> 
        	<th><?php echo Yii::t('app','Course');?></th>
            <th><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></th>
            <?php if(isset($semester_enabled) and $semester_enabled==1){ ?>
            <th><?php echo Yii::t('app','Semester');?></th>
            <?php } ?>
            <?php } ?>            
            
        </tr>
    </thead>
	<tbody>
    	<tr>
        <?php if(FormFields::model()->isVisible("fullname", "Students", "forParentPortal")){ ?>
        	<td><?php echo $student->studentFullName("forParentPortal");?></td>
       	<?php } ?>     
            <td><?php echo $student->admission_no;?></td>
          <?php if(FormFields::model()->isVisible('batch_id','Students','forParentPortal')){?> 
        	<td> <?php 
                        $batch = Batches::model()->findByPk($_REQUEST['bid']);
                        echo $batch->course123->course_name;
                    ?></td>
            <td><?php echo $batch->name;?></td>
            <?php if(isset($semester_enabled) and $semester_enabled==1){  ?>
            <td><?php  
					$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
					if($batch->semester_id!=NULL){  
						 echo ($semester->name)?$semester->name:"-"; 
                       }?></td> 
            <?php } ?>
             <?php } ?>            
            
        </tr>
    </tbody>    
    </table>
</div>    

    
            <?php
			if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL and isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL){
				$timetable 		= TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['bid']));
				
				?>
      
            <div id="attendanceDialog"></div>
    <?php
    $criteria		= new CDbCriteria;
    $criteria->condition 	= "batch_id=:x";
    $criteria->params 		= array(':x'=>$_REQUEST['bid']);
    $criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";
    $timings 		= ClassTimings::model()->findAll($criteria);
    $count_timing 	= count($timings);    
    
    $weekdays		= Weekdays::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['bid']));
    if(count($weekdays)==0)
        $weekdays=Weekdays::model()->findAll("batch_id IS NULL");
    
    //check elective subject exist for a student
    $elective_exist_flag=0;
    $check_ele_model= StudentElectives::model()->findAllByAttributes(array('student_id'=>$student->id,'batch_id'=>$_REQUEST['bid']));
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
    $weekday_text = array($sun, $mon, $tue, $wed, $thu, $fri, $sat);
    
    if($timings!=NULL){
		if(count($timetable)>0){
		?>
    
                    <div class="opnsl_headerBox">
                        <div class="opnsl_actn_box"> 
                    
                            <div class="atnd_table-calender atnd_tnav-new " align="center">
                            <?php
                                echo CHtml::link('<div class="atnd_arow_l"><img src="'.Yii::app()->request->baseUrl.'/images/atnd_arrow-l.png" height="13" width="7" border="0"></div>', array('/parentportal/default/subwiseattendance', 'id'=>$_REQUEST['id'], 'bid'=>$_REQUEST['bid'], 'date'=>$prev_week_start), array('title'=>Yii::t('app', 'Previous Week')));											
                                          // $sdate_time		=	Configurations::model()->convertDateTime($week_start);
                                         //  $edate_time		=	Configurations::model()->convertDateTime($week_end);
                                           
                                            echo Yii::t('app',date("M", strtotime($week_start))).date(" d", strtotime($week_start))." - ".Yii::t('app',date("M", strtotime($week_end))).date(" d", strtotime($week_end));
                                        
                                            echo CHtml::link('<div class="atnd_arow_r"><img src="'.Yii::app()->request->baseUrl.'/images/atnd_arrow.png" height="13" width="7" border="0"></div>', array('/parentportal/default/subwiseattendance', 'id'=>$_REQUEST['id'], 'bid'=>$_REQUEST['bid'], 'date'=>$next_week_start), array('title'=>Yii::t('app', 'Next Week')));																						
                            ?>                                    
                            </div>
                    </div>
                    <div class="opnsl_actn_box">
                                    <?php  echo CHtml::link(Yii::t('app','Generate PDF'), array('Default/subwisepdf','id'=>$_REQUEST['id'], 'bid'=>$_REQUEST['bid'], 'date'=>$date),array('class'=>'btn btn-danger pull-right','target'=>'_blank')); ?>
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
                                            
											$time1		=	Configurations::model()->convertTime($timing_1->start_time);
											$time2		=	Configurations::model()->convertTime($timing_1->end_time); 
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

                                                <p><?php 
												echo date("d M Y", strtotime($this_date)); ?></p>
                                                <?php $weekday_count++; ?>
                                            </td>
                                        
                                        <?php
                                        for($i=0;$i<$count_timing;$i++)
                                        {
											$criteria				= new CDbCriteria;
											$criteria->join			= 'JOIN `class_timings` `t1` ON `t1`.`id` = `t`.`class_timing_id`';												
											$criteria->condition	= '`t`.`batch_id`=:batch_id AND `t`.`weekday_id`=:weekday_id AND `t`.`class_timing_id`=:class_timing_id';
											$criteria->params		= array(':batch_id'=>$_REQUEST['bid'], ':weekday_id'=>$weekday['weekday'], ':class_timing_id'=>$timings[$i]['id']);
											
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
                                                 $subjectwise =  StudentSubjectwiseAttentance::model()->findByAttributes(array('student_id'=>$_REQUEST['id'], 'timetable_id'=>$set->id, 'weekday_id'=>$set->weekday_id, 'date'=>$week_start));
                                                 $is_holiday		= StudentAttentance::model()->isHoliday($week_start);
												if($is_holiday == NULL) {
                                                    if($subjectwise == NULL){
														if($batch_name->start_date <= $week_start and $week_start <= $batch_name->end_date ){
															if($week_start >= $student->admission_date and $week_start <= date("Y-m-d")){												
																echo '<span style="color:#077109; font-weight:600;">'.Yii::t('app',"Present").'</span><br/>';
															}
														}
													}
												}
												else{
													echo '<div class="attnd-holiday">'.Yii::t('app','Holiday').'</div>';
												}
                                                   
                                               	if($subjectwise){ ?>	
                                                    <div class="mark-absent-blk" >                                                                
                                                        <p>
                                                            <?php 
                                                            echo CHtml::ajaxLink('Absent',$this->createUrl('default/viewsubwise'),
                                                            array('onclick'=>'$("#jobDialog_view").dialog("open"); return false;','update'=>'#jobDialog_view_div'.$subjectwise->id,'type' =>'GET','data' => array('id' =>$subjectwise->id),'dataType' => 'text',),array('id'=>'showJobDialog_view'.$subjectwise->id,'class'=>'mark-absent', 'title'=>Yii::t('app','View')));
                                                            ?>
                                                         </p>       
                                                    </div>
                                                <?php } ?>   
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
                                                                        
                                                                        
                                                                        $electname = ElectiveGroups::model()->findByAttributes(array('id'=>$elec_sub->elective_group_id,'batch_id'=>$_REQUEST['bid']));
																		$electname_sub = StudentElectives::model()->findByAttributes(array('student_id'=>$_REQUEST['id'],'status'=>1,'batch_id'=>$_REQUEST['bid']));
                                                                        if($electname!=NULL)
                                                                        {
																			//check student assign elective
																			$multi_elective =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['bid'],'weekday_id'=>$weekday['weekday'],'subject_id'=>$electname_sub->elective_id,'class_timing_id'=>$timings[$i]['id']));
																			//check student assign elective employee
																			$elective_employee = Employees::model()->findByAttributes(array('id'=>$multi_elective->employee_id));
                                                                            echo $electname->name;
																			
                                                                        }
																		
                                                                        $time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
																		if($elective_employee!=NULL) //for student assgn elective teacher
																		{
																			//echo '<div class="employee">'.ucfirst($elective_employee->first_name).' '.ucfirst($elective_employee->middle_name).' '.ucfirst($elective_employee->last_name).'</div>';
																		}else if($time_emp!=NULL)
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
						echo '<span class="table_nothingFound">'.Yii::t('app','No Class Timings').'</span>';
					}
				?>
			</div>
            </div>							
			<?php	
			}
			else{
				echo '<span class="table_nothingFound">'.Yii::t('app','No Results Found').'</span>';
			}
			?>        
