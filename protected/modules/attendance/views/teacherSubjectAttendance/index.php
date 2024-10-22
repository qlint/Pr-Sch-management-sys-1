<style type="text/css">
/*.cb_left {
    padding: 8px 8px 8px 20px;
    margin: 0px;
    float: left;
    width: 926px;
}*/
</style>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Attendances')=>array('/attendance'),
	Yii::t('app','Teacher Attendances'),
	Yii::t('app','Subject Wise'),
);
?>
<script type="text/javascript">
function getValue(){
	var dept 	= $('#dept').val();
	var emp		= $('#emp').val();
	if(dept != '' && emp != ''){
		window.location= 'index.php?r=attendance/teacherSubjectAttendance&dept='+dept+'&emp='+emp;	
	}
	else if(dept != ''){
		window.location= 'index.php?r=attendance/teacherSubjectAttendance&dept='+dept;	
	}	
	else{
		window.location= 'index.php?r=attendance/teacherSubjectAttendance';	
	}
}
</script>
<?php
$settings     		= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
$date				= (isset($_REQUEST['date']))?$_REQUEST['date']:date("Y-m-d");
$day 				= date('w', strtotime($date));
$week_start			= date('Y-m-d', strtotime('-'.$day.' days', strtotime($date)));
$week_end 			= date('Y-m-d', strtotime('+'.(6-$day).' days', strtotime($date)));
$prev_week_start	= date('Y-m-d', strtotime('-7 days', strtotime($week_start)));
$next_week_start	= date('Y-m-d', strtotime('+1 days', strtotime($week_end)));
$this_date			= $week_start;

$dept				= (isset($_REQUEST['dept']))?$_REQUEST['dept']:'';
$emp				= (isset($_REQUEST['emp']))?$_REQUEST['emp']:'';

$daterange = new DatePeriod(new DateTime($week_start), new DateInterval('P1D'), new DateTime($week_end));

foreach($daterange as $date){
    $date_arr[]	= $date->format("Y-m-d");
}
$date_arr[]	= $week_end;
?>
<div style="background:#fff; min-height:800px;">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td valign="top" width="96%" align="left">
            	<div id="attendanceDialog"></div>
                <div class="full-formWrapper" id="demo">	
                    <div class="attendance-h1-bg">				
                        <h1><?php echo Yii::t('app','Teacher Subject Wise Attendances');?></h1>
                        <?php $this->renderPartial('/default/employee_tab');?>
                        </div>
                    <div class="formCon">  
                    <div class="formConInner">
                    <div class="c_batch_tbar-subwise">
                        <div class="filter-box-table">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>	
                                    <td width="13%"><?php echo Yii::t('app', 'Select Department'); ?></td>
                                    <td width="25%">
                                        <?php
                                            if(isset($_REQUEST['dept'])){
                                                $select_dept	= $_REQUEST['dept'];
                                            }
                                            else{
                                                $select_dept 	= '';
                                            }
                                            $departments = CHtml::listData(EmployeeDepartments::model()->findAll(),'id','name');
                                            $static = array('All' => Yii::t('app', 'All'));
                                            echo CHtml::dropDownList('dept','',$static+$departments,array('prompt'=>Yii::t('app','Select Department'),'onchange'=>'getValue()','id'=>'dept','options'=>array($select_dept=>array('selected'=>true))));
                                        ?>
                                    </td>
                                    <td width="2%"></td>
                                    <td width="10%"><?php echo Yii::t('app', 'Select Teacher'); ?></td>
                                    <td width="25%">
                                        <?php
                                            $employees = array();
                                            if(isset($_REQUEST['dept']) and $_REQUEST['dept'] != NULL){												
                                                $criteria	= new CDbCriteria;
                                                if($_REQUEST['dept'] == 'All'){
                                                    $criteria->condition 	= 'is_deleted=:is_deleted';
                                                    $criteria->params 		= array(':is_deleted'=>0); 
                                                }
                                                else{
                                                    $criteria->condition 	= 'employee_department_id=:dept_id and is_deleted=:is_deleted';
                                                    $criteria->params 		= array(':dept_id'=>$_REQUEST['dept'], ':is_deleted'=>0); 
                                                }
                                                $criteria->order			= 'first_name ASC'; 
                                                $employees					= Employees::model()->findAll($criteria);
                                                if($employees){
                                                    $employees = CHtml::listData($employees,'id','fullname');
                                                }
                                            }
                                            echo CHtml::dropDownList('emp','',$employees,array('prompt'=>Yii::t('app','Select Teacher'),'onchange'=>'getValue()','id'=>'emp','options'=>array($_REQUEST['emp']=>array('selected'=>true))));
                                        ?>
                                    </td>
                                    <td width="2%"></td>
                                    <td width="23%">
                                        <?php 
											if(Configurations::model()->teacherAttendanceMode() != 2){
												echo CHtml::link(Yii::t("app","Daily Attendance"), array("/attendance/employeeAttendances"), array("class"=>"formbut-n"));
											}
										?>										
                                    </td>
                                </tr>
                            </table>
                        </div>        
                    </div>
                    </div> </div>
    <?php                
                    if(isset($_REQUEST['dept']) and $_REQUEST['dept'] != NULL and isset($_REQUEST['emp']) and $_REQUEST['emp'] != NULL){   
                        $employee				= Employees::model()->findByPk($_REQUEST['emp']);
                        $current_academic_yr 	= Configurations::model()->findByAttributes(array('id'=>35));
                        if(Yii::app()->user->year)
                            $year 	= Yii::app()->user->year;
                        else
                            $year 	= $current_academic_yr->config_value;
                            
                        $is_insert 	= PreviousYearSettings::model()->findByAttributes(array('id'=>2));
                        $is_edit   	= PreviousYearSettings::model()->findByAttributes(array('id'=>3));
                        $is_delete 	= PreviousYearSettings::model()->findByAttributes(array('id'=>4));								
                        if($year != $current_academic_yr->config_value and ($is_insert->settings_value==0 or $is_edit->settings_value==0 or $is_delete->settings_value==0)){
                        ?>
                            <div>
                                <div class="yellow_bx" style="background-image:none;width:95%;padding-bottom:45px;">
                                    <div class="y_bx_head" style="width:95%;">
                                    <?php 
                                        echo Yii::t('app','You are not viewing the current active year. ');
                                        if($is_insert->settings_value==0 and $is_edit->settings_value!=0 and $is_delete->settings_value!=0)
                                            echo Yii::t('app','To mark the attendance, enable Create option in Previous Academic Year Settings.');
                                        elseif($is_insert->settings_value!=0 and $is_edit->settings_value==0 and $is_delete->settings_value!=0)
                                            echo Yii::t('app','To edit the attendance, enable Edit option in Previous Academic Year Settings.');
                                        elseif($is_insert->settings_value!=0 and $is_edit->settings_value!=0 and $is_delete->settings_value==0)
                                            echo Yii::t('app','To delete the attendance, enable Delete option in Previous Academic Year Settings.');
                                        else
                                            echo Yii::t('app','To manage the the attendance, enable the required options in Previous Academic Year Settings.');
                                    ?>
                                    </div>
                                    <div class="y_bx_list" style="width:650px;">
                                        <h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
                                    </div>
                                </div>
                            </div>
    <?php					
                        }
                        else{
                            $criteria 				= new CDbCriteria;
                            $criteria->condition 	= 'employee_id=:employee_id';
                            $criteria->params		= array(':employee_id'=>$emp);
                            $criteria->group		= 'weekday_id';	
                            $criteria->order		= 'weekday_id ASC';
                            $timetables				= TimetableEntries::model()->findAll($criteria);
                            if($timetables){							
                                $criteria 				= new CDbCriteria;		
                                $criteria->join 		= 'LEFT JOIN timetable_entries t1 ON t1.class_timing_id = t.id';
                                $criteria->group		= 't1.class_timing_id';
                                $criteria->condition 	= 't1.employee_id=:employee_id';
                                $criteria->params 		= array(':employee_id'=>$emp);
                                $criteria->order  		= "STR_TO_DATE(t.start_time, '%h:%i %p')";
                                $timings				= ClassTimings::model()->findAll($criteria);
                                if($timings){
                                    $weekday_arr			= array();
                                    foreach($timetables as $timetable){
                                        if(!in_array($timetable->weekday_id, $weekday_arr)){
                                            $weekday_arr[]	= $timetable->weekday_id;
                                        }
                                    }	
                                    $weekday_text = array(1=>'SUN', 2=>'MON', 3=>'TUE', 4=>'WED', 5=>'THU', 6=>'FRI', 7=>'SAT');		
    ?>
    
                                    <div class="pdf-box">
                                        <div class="box-one">
                                            <div class="atnd_tnav-calender" align="center">                                    	
                                                <?php
                                                    echo CHtml::link('<div class="atnd_arow_l"><img src="'.Yii::app()->request->baseUrl.'/images/atnd_arrow-l.png" height="13" width="7" border="0"></div>', array('/attendance/teacherSubjectAttendance', 'dept'=>$dept, 'emp'=>$emp, 'date'=>$prev_week_start), array('title'=>Yii::t('app', 'Previous Week')));
													$month1	=	date("M", strtotime($week_start));
													$month2	=	date("M", strtotime($week_end));
													$day1	=	date("d", strtotime($week_start));	
													$day2	=	date("d", strtotime($week_end));								
                                                    echo Yii::t("app",$month1).' '.$day1." - ".Yii::t("app",$month2).' '.$day2;                                        
                                                    echo CHtml::link('<div class="atnd_arow_r"><img src="'.Yii::app()->request->baseUrl.'/images/atnd_arrow.png" height="13" width="7" border="0"></div>', array('/attendance/teacherSubjectAttendance', 'dept'=>$dept, 'emp'=>$emp, 'date'=>$next_week_start), array('title'=>Yii::t('app', 'Next Week')));										
                                                ?>                                    
                                            </div>
                                        </div>
                                        <div class="box-two">
                                            <div class="pdf-div">  
                                            	<?php echo CHtml::link(Yii::t("app","Generate PDF"), array("/attendance/teacherSubjectAttendance/pdf", "dept"=>$dept, 'emp'=>$emp, 'date'=>$_REQUEST['date']), array("class"=>"pdf_but-input", "target"=>"_blank"));?>                                                                                                    
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="clearfix"></div>
                                    <div class="timetable-grid">
                                    <div class="timetable-grid-scroll">
                                        <table border="0" align="center" width="100%" id="table" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <th width="60px" class="loader">&nbsp;</th>
                                                   <!-- <th class="td-blank"></th>-->
                                                    <?php 
                                                    foreach($timings as $timing){                                                    
                                                        if($settings != NULL){
															//traslate AM and PM 	
															$t1 = date('h:i', strtotime($timing->start_time));	
															$t2 = date('A', strtotime($timing->start_time));
															
															$t3	= date('h:i', strtotime($timing->end_time));	
															$t4	= date('A', strtotime($timing->end_time));	
															//end 
															
                                                            $time1	= date($settings->timeformat,strtotime($timing->start_time));
                                                            $time2	= date($settings->timeformat,strtotime($timing->end_time));
                                                        }
                                                        echo '<th width="130px" class="th"><div class="top">'.$t1.' '.Yii::t("app",$t2).' - '.$t3.' '.Yii::t("app",$t4).'</div></th>';	                                                    
                                                    }
                                                    ?>
                                                </tr>                                                
    <?php
	
                                                foreach($weekday_arr as $weekday){	
                                                    $week_date	= $date_arr[$weekday - 1];
														
    ?>
                                                    <tr>
                                                        <td class="td daywise-block">
                                                            <h3><?php echo Yii::t('app',$weekday_text[$weekday]);?></h3>
                                                            <p><?php echo date($settings->displaydate, strtotime($week_date)) ?></p>
                                                        </td>
                                                     <!--   <td class="td-blank"></td-->
    <?php
                                                        for($i=0; $i < count($timings); $i++){ 
                                                            $set =  TimetableEntries::model()->findByAttributes(array('employee_id'=>$emp,'weekday_id'=>$weekday,'class_timing_id'=>$timings[$i]['id']));																																									
    ?>														
                                                                <td class="td"> 
    <?php
                                                                    if($set != NULL){
                                                                        $subjectwise 	=  TeacherSubjectwiseAttentance::model()->findByAttributes(array('employee_id'=>$emp, 'timetable_id'=>$set->id, 'weekday_id'=>$set->weekday_id, 'date'=>$week_date));
                                                                        $batch			= Batches::model()->findByPk($set->batch_id);
																		$is_holiday		= StudentAttentance::model()->isHoliday($week_date);
																		if($is_holiday == NULL){
																				if($subjectwise == NULL){
																					if($employee->joining_date <= $week_date and $week_date <= date("Y-m-d")){
																					echo CHtml::ajaxLink(
                                                                                    Yii::t('app','Mark Leave'),
                                                                                    $this->createUrl('teacherSubjectAttendance/mark'),
                                                                                    array(
                                                                                        'onclick'=>'$("#attendanceDialog").dialog("open");return false;',
                                                                                        'update'=>'#attendanceDialog',
                                                                                        'type' =>'GET',
                                                                                        'data' => array(
                                                                                            'timetable_id' =>$set->id,
                                                                                            'employee_id' =>$emp,
                                                                                            'subject_id'=>$set->subject_id,
                                                                                            'weekday_id' =>$set->weekday_id,
                                                                                            'date'=>$week_date
                                                                                        ),
                                                                                        'dataType' => 'text'
                                                                                    ),
                                                                                    array(
                                                                                        'id'=>'mak-attendance-'.$stud_id.'-'.$set->id,
                                                                                        'class'=>'mark_leave'
                                                                                    )
                                                                                );
                                                                                echo '';	
                                                                            }
                                                                        }
																		
                                                                        else{																			
    ?>
    																		<div class="action-box">
    <?php                                                                        
																				echo CHtml::ajaxLink(
																					'',
																					$this->createUrl('teacherSubjectAttendance/mark'),
																					array(
																						'onclick'=>'$("#attendanceDialog").dialog("open");return false;',
																						'update'=>'#attendanceDialog',
																						'type' =>'GET',
																						'data' => array(
																							'id' =>$subjectwise->id
																						),
																						'dataType' => 'text'
																					),
																					array(
																						'id'=>'edit-attendance-'.$subjectwise->id,
																						'class'=>'timtable-update'
																					)
																				);
																				
																				echo CHtml::link(
																					'',
																					"#",
																					array(
																						'submit'=>array(
																							'teacherSubjectAttendance/remove',
																							'id'=>$subjectwise->id,
																							'dept'=>$dept,
																							'emp'=>$emp,
																							'date'=>$week_start
																							
																							
																						),
																						'class'=>'timtable-delt',
																						'confirm'=>Yii::t('app','Are you sure?'),
																						'csrf'=>true,
																						
																					)
																				); 
?>
																			</div>
                                                                            <div class="mark-absent-blk" >                                                                
                                                                                <p>
                                                                                    <?php echo CHtml::ajaxLink(
                                                                                        Yii::t('app','Absent'),
                                                                                        $this->createUrl('teacherSubjectAttendance/view'),
                                                                                        array(
                                                                                            'onclick'=>'$("#attendanceDialog").dialog("open");return false;',
                                                                                            'update'=>'#attendanceDialog',
                                                                                            'type'=>'GET',
                                                                                            'data'=>array(
                                                                                                'id' =>$subjectwise->id
                                                                                            ),
                                                                                            'dataType' => 'text'
                                                                                        ),
                                                                                        array(
                                                                                            'id'=>'view-attendance-'.$subjectwise->id,
                                                                                            'class'=>'mark-absent',
                                                                                            'title'=>Yii::t('app','View')
                                                                                        )
                                                                                    );?>
                                                                                </p>
                                                                            </div>
                                                                            
<?php																			
                                                                            
                                                                        }
																		}
																		else{
																			echo '<div class="attnd-holiday">'.Yii::t('app','Holiday').'</div>';
																		}
    ?>																	                                                            	                                                               
                                                                        <div  onclick="" style="position: relative; ">
                                                                            <div class="timtable-subjct-blk">
                                                                                <div class="subject">	
    <?php                                                                            
                                                                                    if($set->is_elective == 0){
                                                                                        $time_sub 	= Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
                                                                                        if($time_sub!=NULL){
                                                                                            echo ucfirst($time_sub->name);
                                                                                        }
                                                                                    }
                                                                                    else{
                                                                                        $elec_sub 	= Electives::model()->findByAttributes(array('id'=>$set->subject_id));
                                                                                        $electname 	= ElectiveGroups::model()->findByAttributes(array('id'=>$elec_sub->elective_group_id,'batch_id'=>$elec_sub->batch_id));	
                                                                                        if($electname!=NULL){
                                                                                            echo ucfirst($electname->name);
                                                                                        }
                                                                                    }	
    ?>                                                                                																																					                                                           	
                                                                                </div>
                                                                                <div class="batch_name"><?php echo ucfirst($batch->name); ?></div>                                                                        
                                                                            </div>
                                                                        </div>
    <?php
																			
																		
																	}
    ?>																
                                                                 </td>           
    <?php          														
                                                        }
    ?>													
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
							else{
								echo '<div class="not-foundarea">';
								echo Yii::t('app', 'No Class Timings');
								echo '</div>';
							}							
						}
						else{
							echo '<div class="not-foundarea">';
							echo Yii::t('app', 'Timetable Not Assigned');
							echo '</div>';
						}
					}
				}
?>	
				</div>				
           </td>
        </tr>        
	</table>
</div>                
