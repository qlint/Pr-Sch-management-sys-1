<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css">

<style type="text/css">
.pdf_btn{
	position:absolute;
	right:20px;
	top:83px;
}
</style>
<script>
function getstudent(){
	var bid	= $('#bid').val();
		if(bid != ''){
			window.location= 'index.php?r=teachersportal/default/subwiseattendance&bid='+bid;
		}
		else{
			window.location= 'index.php?r=teachersportal/default/subwiseattendance';
		}
	
	
}
function displaytable() 
{
	var bid = document.getElementById('bid').value;
	var student_id = document.getElementById('student_id').value;
	if(bid == '')
	{
		$('#error').html('<?php echo Yii::t('app','select Cohort'); ?>');
		return false;
	}
	else
	{
	window.location= 'index.php?r=teachersportal/default/subwiseattendance&bid='+bid+'&student_id='+student_id;
	}
}
</script>

	<?php $this->renderPartial('/default/leftside');?> 
	<div class="right_col"  id="req_res123">
    <!--contentArea starts Here--> 
     <div id="parent_rightSect">
        <div class="parentright_innercon">
        <div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-file-text"></i><?php echo Yii::t("app", 'Weekly Subject Wise Attendance');?><span><?php echo Yii::t("app", 'View Weekly Subject Wise Attendance');?> </span></h2>
        </div>
        <div class="col-lg-2">
        
                </div>
    
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t("app", 'You are here:');?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t("app", 'Attendance');?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    </div>
    <div class="contentpanel">
    
<div class="panel-heading">
            <h3 class="panel-title"><?php echo Yii::t('app','Weekly Subject Wise Attendance'); ?></h3>           
        	</div>
            <div class="people-item">
             <?php $this->renderPartial('/default/employee_tab');?>
             <div> 
             
<div class="attendance-block-bg">
             
                <div class="attnd-tab-sectn-blk">   
                <div class="tab-sectn">
                <ul>
               <?php if(Configurations::model()->studentAttendanceMode() != 2){ ?>
                                                <li><?php echo CHtml::link(Yii::t("app","DAY WISE"), array("/teachersportal/default/studentattendance", "id"=>$_REQUEST['bid']), array("class"=>"sub-attnd-daily"));?> </li>
                                       <?php } ?>    
                                    <?php if(Configurations::model()->studentAttendanceMode() != 1){ ?>
                								<li><?php echo CHtml::link(Yii::t("app","SUBJECT WISE"), array("/teachersportal/default/daily", "bid"=>$_REQUEST['bid']), array("class"=>"active-attnd"));?> </li>
                <?php } ?>    
                </ul>
                </div>
                </div>
                <div class="attndwise-head">
                <h3><?php echo Yii::t('app','Subject Wise Attendance'); ?></h3>
                </div>		
                               
                <div class="row">
                	<div class="pdf-box">
                    	<div class="col-md-10 col-4-reqst">
                         <div class="row">
                         	<div class="col-md-11 col-4-reqst">
                            <div class="row">
							<div class="col-md-3 col-4-reqst"> 
                            <div class="attnd-selectbox">
                            	<?php
			  					$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
								$current_academic_yr = Configurations::model()->findByPk(35);
                                $data = Batches::model()->findAllByAttributes(array('employee_id'=>$employee->id, 'is_deleted'=>0, 'academic_yr_id'=>$current_academic_yr->config_value),array('order'=>'id DESC'));
								$batch_list = CHtml::listData($data,'id','name');
                                 echo CHtml::dropDownList('bid','',$batch_list,array('prompt'=>Yii::t('app','Select Batch'),'bid'=>'bid','class'=>'input-form-control','options'=>array($_REQUEST['bid']=>array('selected'=>true)),'onchange'=>'getstudent();'));
                                ?>
                            </div>
                            </div>
                            <div class="col-md-3 col-4-reqst"> 
                            <div class="attnd-selectbox">
                            	<?php  
                                            if(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL)
                                            {
												$criteria    = new CDbCriteria;           
												$criteria->join   = "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`id`";           
												$criteria->condition = "`t`.`is_active`=1 AND `t`.`is_deleted`=0 AND `bs`.`batch_id`=:batch_id AND `bs`.`status`=:status AND `bs`.`result_status`=:result_status";
												$criteria->params  = array(":batch_id"=>$_REQUEST['bid'], ':status'=>1, ':result_status'=>0);
												$criteria->order  = "`t`.`first_name` ASC, `t`.`last_name` ASC";
												$students    = Students::model()->findAll($criteria);
                                                $student_list = CHtml::listData($students,'id','stud');
                                            
                                                echo CHtml::dropDownList('student_id','',$student_list,array('prompt'=>Yii::t('app','Select Student'),'class'=>'form-control','id'=>'student_id','style'=>'width:190px;',  'onchange'=>'displaytable()', 'options'=>array($_REQUEST['student_id']=>array('selected'=>true)),));
                                            }
                                            else
                                            {
                                                echo CHtml::dropDownList('student_id','',array(),array('prompt'=>Yii::t('app','Select Student'),'class'=>'form-control','id'=>'student_id','style'=>'width:190px;', 'onchange'=>'displaytable()',
                                            ));
                                            }
                                            ?>
                            </div>
                            </div>      
                            <div class="col-md-4 col-4-reqst">             
                            <div class="subwise-blk box-one-lft-rght">
                            <ul>
                            <li>  <?php echo CHtml::link(Yii::t("app","Daily"), array("/teachersportal/default/daily", "bid"=>$_REQUEST['bid']), array("class"=>" sub-attnd-daily"));?>  </li>
                            <li><?php echo CHtml::link(Yii::t("app","Weekly"), array("/teachersportal/default/subwiseattendance", "bid"=>$_REQUEST['bid'], "student_id"=>$_REQUEST['student_id']), array("class"=>"sub-attnd-weekly active-attnd"));?>  </li>
                            </ul>
                            </div>
                            </div>
                                                  
                            
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
<div class="row"> 
<?php
	$batchname = Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));
	$course = Courses::model()->findByAttributes(array('id'=>$batchname->course_id));
	$semester=Semester::model()->findByAttributes(array('id'=>$batchname->semester_id));
	$sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id); 
?>
                	<div class="col-md-12 col-4-reqst">
                    	<div class="batch-block">
							<p><?php echo '<span>'.Yii::t('app','Course').'</span> '.':'.' '.ucfirst($course->course_name).''; ?></p>
							<p><?php echo '<span>'.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>  '.':'.' '.ucfirst($batchname->name);?></p>
							<?php if($sem_enabled==1 and $batchname->semester_id!=NULL){ ?>
									<p> <?php echo '<span>'.Yii::t('app','Semester').'</span> '.':'.' '.ucfirst($semester->name).''; ?></p>
							<?php } ?>
						</div>
                    </div>
                </div>
                
             </div>
              <div class="form-group"> 
			 
					

<?php
if(isset($_REQUEST['bid']) and  $_REQUEST['bid']!= NULL and isset($_REQUEST['student_id'])  and  $_REQUEST['student_id']!= NULL)
{
  
	$date				= (isset($_REQUEST['date']))?$_REQUEST['date']:date("Y-m-d");
	$day 				= date('w', strtotime($date));
	$week_start			= date('Y-m-d', strtotime('-'.$day.' days', strtotime($date)));
	$week_end 			= date('Y-m-d', strtotime('+'.(6-$day).' days', strtotime($date)));
	$prev_week_start	= date('Y-m-d', strtotime('-7 days', strtotime($week_start)));
	$next_week_start	= date('Y-m-d', strtotime('+1 days', strtotime($week_end)));
	$this_date			= $week_start;
?>

    
    <?php    
    $student				= Students::model()->findByAttributes(array('id'=>$_REQUEST['student_id']));
    $criteria				= new CDbCriteria;
    $criteria->condition 	= "batch_id=:x";
    $criteria->params 		= array(':x'=>$_REQUEST['bid']);
    $criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";
    $timings 				= ClassTimings::model()->findAll($criteria);
    $count_timing 			= count($timings);
	
	$timetable		 		= TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['bid'])); 
   
    $times					= Batches::model()->findAll("id=:x", array(':x'=>$_REQUEST['bid']));
    $weekdays				= Weekdays::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['bid']));
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
    

	<div class="row-pddng">
	<div class="col-md-3 col-4-reqst">
    <div class="display-block">
                              
        <div class="atnd_table-calender-bg atnd_tnav-new box-one-lft-rght" align="center">
        <?php
            echo CHtml::link('<div class="atnd-table-arow-left"><img src="'.Yii::app()->request->baseUrl.'/images/attnd-arow-left.png" height="13" width="7" border="0"></div>', array('/teachersportal/Default/subwiseattendance', 'student_id'=>$_REQUEST['student_id'], 'bid'=>$_REQUEST['bid'], 'date'=>$prev_week_start), array('title'=>Yii::t('app', 'Previous Week')));	
			?>

<?php  
                $week_value	=	date("M d", strtotime($week_start))." - ".date("M d", strtotime($week_end)); 
                ?>
              <input type="hidden" id="day" class="week-picker" value="<?php echo $date;?>"/>
             <input type="text" id="week"  value="<?php echo $week_value;?>"></input>

								
                                <?php
                echo CHtml::link('<div class="atnd-table-arow-right"><img src="'.Yii::app()->request->baseUrl.'/images/attnd-arow-right.png" height="13" width="7" border="0"></div>', array('/teachersportal/Default/subwiseattendance', 'student_id'=>$_REQUEST['student_id'], 'bid'=>$_REQUEST['bid'], 'date'=>$next_week_start), array('title'=>Yii::t('app', 'Next Week')));											
        ?>                                    
        </div>
         </div>
    </div>
	<div class="col-md-9 col-4-reqst">
    	<div class="row">
            <div class="col-md-3 pull-right">
                <?php  echo CHtml::link(Yii::t('app','Generate PDF'), array('Default/subwisepdf','student_id'=>$_REQUEST['student_id'], 'bid'=>$_REQUEST['bid'], 'date'=>$date),array('class'=>'btn btn-danger pull-right','target'=>'_blank')); ?>
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
									$weekday_attributes	= array(1=>'on_sunday', 2=>'on_monday', 3=>'on_tuesday', 4=>'on_wednesday', 5=>'on_thursday', 6=>'on_friday', 7=>'on_saturday');
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
                                    if($weekday['weekday']!=0) 
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
											$criteria->params		= array(':batch_id'=>$batchname->id, ':weekday_id'=>$weekday['weekday'], ':class_timing_id'=>$timings[$i]['id']);
											
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
												$subjectwise =  StudentSubjectwiseAttentance::model()->findByAttributes(array('student_id'=>$_REQUEST['student_id'], 'timetable_id'=>$set->id, 'weekday_id'=>$set->weekday_id, 'date'=>$week_start));
												if($set->is_elective == 2){ 
													$elective			=	Electives::model()->findByAttributes(array('batch_id'=>$batchname->id, 'id'=>$set->subject_id)); 
													$student_elective	=	StudentElectives::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$_REQUEST['bid'], 'elective_group_id'=>$elective->elective_group_id)); 
													if($student_elective==NULL){
														$visible=1;
													}else{
														$visible=0;
													}															
												}else{
													$visible=0;
												}
												$is_holiday		= StudentAttentance::model()->isHoliday($week_start);
												if($is_holiday == NULL){
                                                    if($subjectwise == NULL){ 
                                                            if($batchname->start_date <= $week_start and $week_start <= $batchname->end_date ){
																if($week_start >= $student->admission_date and $week_start <= date("Y-m-d") and $visible==0){
																	echo CHtml::link(
																		Yii::t('app','Mark Leave'),
																		'javascript:void(0);',
																		array(
																			'class'=>'mark_leave open_popup',
																			'data-ajax-url'=>$this->createUrl(
																				'/teachersportal/default/subjectwise',
																				array(
																					'timetable_id' =>$set->id,

																					'student_id' =>$_REQUEST['student_id'],
																					'weekday_id' =>$set->weekday_id,
																					'subject_id' =>$set->subject_id,
																					'date'=>$week_start
																				)
																			),
																			'data-target'=>"#myModal",
																			'data-toggle'=>"modal",
																			'data-modal-label'=>Yii::t("app", "Mark Leave"),
																			'data-modal-description'=>Yii::t("app", "Mark the reason for leave"),
																			'title'=>Yii::t('app','Mark Leave')
																		)
																	);
                                                                }
                                            
                                                            }
                                                    }
                                                    else{
                                                    ?>
                                                    <div class="action-box">		
                                                        <?php
															echo CHtml::link(
																'',
																'javascript:void(0);',
																array(
																	'class'=>'timtable-update open_popup',
																	'data-ajax-url'=>$this->createUrl(
																		'/teachersportal/default/subjectwise',
																		array(
																			'id' =>$subjectwise->id,
																			'timetable_id' =>$set->id,
																			'student_id' =>$_REQUEST['student_id'],
																			'weekday_id' =>$set->weekday_id,
																			'subject_id' =>$set->subject_id,
																			'date'=>$week_start
																		)
																	),
																	'data-target'=>"#myModal",
																	'data-toggle'=>"modal",
																	'data-modal-label'=>Yii::t("app", "Edit Leave"),
																	'data-modal-description'=>Yii::t("app", "Edit the reason for leave"),
																	'title'=>Yii::t('app','Edit')
																)
															);
                                                            
                                                            echo CHtml::link('', "#", array('submit'=>array('default/remove','id'=>$subjectwise->id, 'date'=>$week_start), 'confirm'=>Yii::t('app','Are you sure you want to remove absent ?'), 'csrf'=>true,'class'=>'timtable-delt','title'=>Yii::t('app','Remove')));
                                                            ?>
                                                    </div>	
                                                    <div class="mark-absent-blk" >                                                                
                                                        <p>
                                                            <?php
															echo CHtml::link(
																Yii::t('app', 'Absent'),
																'javascript:void(0);',
																array(
																	'class'=>'mark-absent open_popup',
																	'data-ajax-url'=>$this->createUrl(
																		'/teachersportal/default/viewsubwise',
																		array(
																			'id' =>$subjectwise->id
																		)
																	),
																	'data-target'=>"#myModal",
																	'data-toggle'=>"modal",
																	'data-modal-label'=>Yii::t("app", "Reason for Leave"),
																	'data-modal-description'=>Yii::t("app", "View the reason for leave"),
																	'title'=>Yii::t('app','View')
																)
															);
                                                            ?>
                                                         </p>       
                                                    </div>
                                                    <?php }
												}
												else{
														echo '<div class="attnd-holiday">'.Yii::t('app','Holiday').'</div>';
													}?>
                             <div id="jobDialog_view_div<?php echo $subjectwise->id; ?>"></div>	
                                                  <?php	
                                                                if(count($set)==0)
                                                                {
                                                                    $is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing->id,'is_break'=>1));
                                                                    if($is_break!=NULL)
                                                                    {	
                                                                        echo Yii::t('app','Break');
                                                                    }
                                                                        
                                                                }
                                                                else
                                                                {?>
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
                                                                        
                                                                        
                                                                        $electname = ElectiveGroups::model()->findByAttributes(array('id'=>$elec_sub->elective_group_id,'batch_id'=>$batchname->id));
                                                                        
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
                                                                                   // echo '<div class="employee">'.ucfirst($time_emp->first_name).' '.ucfirst($time_emp->middle_name).' '.ucfirst($time_emp->last_name).'</div>';
                                                                                }
                                                                            }
                                                                        }
                                                                        
                                                                    }?>
                                                                     </div>
                                                                   
                                                                </div>
                                                            </div>
                                                                    
                                                        <?php	}
                                                            }
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
                    echo '<strong>'.Yii::t('app','No Class Timings').'</strong>';
                }
							?>
							</div>
						</div>
					</div>
							
					<?php	} ?>
					
				</div>
			</div>
		  <div class="clear"></div>
		</div>
	  </div>
      <script type="text/javascript">
$(function() {   
    var startDate;
    var endDate;

    var selectCurrentWeek = function () {
        window.setTimeout(function () {
            $('.ui-weekpicker').find('.ui-datepicker-current-day a').addClass('ui-state-active').removeClass('ui-state-default');
        }, 1);
    }

    var setDates = function (input,test) {
     	var $input = $(input);
		var date	 = new Date($('#day').val());
        if (date !== null) { 
            var firstDay = $input.datepicker( "option", "firstDay" );
            var dayAdjustment = date.getDay() - firstDay;
            if (dayAdjustment < 0) {
                dayAdjustment += 7;
            }  
            startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - dayAdjustment);
            endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - dayAdjustment + 6);

            var inst = $input.data('datepicker');
            var dateFormat = 'M d'
			
			var start_date	=	$.datepicker.formatDate(dateFormat, startDate, inst.settings);
			var end_date	=	$.datepicker.formatDate(dateFormat, endDate, inst.settings);
			if(test == 1)
			$('#week').val($.datepicker.formatDate(dateFormat, startDate, inst.settings)+' - '+$.datepicker.formatDate(dateFormat, endDate, inst.settings));
			
			
        }
    }
$( "#week" ).click(function() {
	jQuery('.week-picker').datepicker("show");
   
})
    $('.week-picker').datepicker({
        beforeShow: function () {
            $('#ui-datepicker-div').addClass('ui-weekpicker');
            selectCurrentWeek();
        },
        onClose: function () {
            $('#ui-datepicker-div').removeClass('ui-weekpicker');
        },
        showOtherMonths: true,
        selectOtherMonths: true,
        onSelect: function (dateText, inst) {
            setDates(this,1); 
			<?php $url = Yii::app()->createUrl("/teachersportal/default/subwiseattendance",array('bid'=>$batchname->id, 'student_id'=>$student->id))?>
			window.location.href = "<?php echo $url;?>"+"&date="+dateText;
            selectCurrentWeek();
            $(this).change();
        },
        beforeShowDay: function (date) {
            var cssClass = '';
            if (date >= startDate && date <= endDate)
                cssClass = 'ui-datepicker-current-day';
            return [true, cssClass];
        },
        onChangeMonthYear: function (year, month, inst) {
            selectCurrentWeek();
        }
    });
 	setDates('.week-picker',0);
   

    var $calendarTR = $('.ui-weekpicker .ui-datepicker-calendar tr');
    $calendarTR.live('mousemove', function () {
        $(this).find('td a').addClass('ui-state-hover');
    });
    $calendarTR.live('mouseleave', function () {
        $(this).find('td a').removeClass('ui-state-hover');
    });
});
</script>
