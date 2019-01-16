<?php $this->renderPartial('/default/leftside');?> 
<div class="right_col">
	<div id="parent_rightSect">
    	<div class="parentright_innercon">
            <div class="pageheader">
                <div class="col-lg-8">
                	<h2><i class="fa fa-file-text"></i><?php echo Yii::t("app", 'Attendance');?><span><?php echo Yii::t("app", 'View your subject wise attendance');?> </span></h2>
                </div>
                <div class="col-lg-2"></div>                
                <div class="breadcrumb-wrapper">
                    <span class="label"><?php echo Yii::t("app", 'You are here:');?></span>
                    <ol class="breadcrumb">                    
                    	<li class="active"><?php echo Yii::t("app", 'Attendance');?></li>
                    </ol>
                </div>                
                <div class="clearfix"></div>                
            </div>
            <div class="contentpanel">
            	<div class="panel-heading">
                	<h3 class="panel-title"><?php echo Yii::t('app','Subject Wise Attendance'); ?></h3>           
                </div>
                <div id="attendanceDialog"></div>
                <div class="people-item">
<?php                	
					$settings     		= UserSettings::model()->findByAttributes(array('user_id'=>1));
					$date				= (isset($_REQUEST['date']))?$_REQUEST['date']:date("Y-m-d");
					$day 				= date('w', strtotime($date));
					$week_start			= date('Y-m-d', strtotime('-'.$day.' days', strtotime($date)));
					$week_end 			= date('Y-m-d', strtotime('+'.(6-$day).' days', strtotime($date)));
					$prev_week_start	= date('Y-m-d', strtotime('-7 days', strtotime($week_start)));
					$next_week_start	= date('Y-m-d', strtotime('+1 days', strtotime($week_end)));
					$this_date			= $week_start;
					
					$daterange = new DatePeriod(new DateTime($week_start), new DateInterval('P1D'), new DateTime($week_end));
					
					foreach($daterange as $date){
						$date_arr[]	= $date->format("Y-m-d");
					}
					$date_arr[]			= $week_end;
					$employee			= Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
					$emp				= $employee->id;
					
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
							
                            <div class="row">
                            <div class="col-md-3">
                          
                                <div class="atnd_tnav-calender" align="center">                                    	
                                <?php
                                echo CHtml::link('<div class="atnd_arow_l"><img src="'.Yii::app()->request->baseUrl.'/images/atnd_arrow-l.png" height="13" width="7" border="0"></div>', array('/teachersportal/default/teachersubwise', 'emp'=>$emp, 'date'=>$prev_week_start), array('title'=>Yii::t('app', 'Previous Week')));											
                                echo date("M d", strtotime($week_start))." - ".date("M d", strtotime($week_end));                                        
                                echo CHtml::link('<div class="atnd_arow_r"><img src="'.Yii::app()->request->baseUrl.'/images/atnd_arrow.png" height="13" width="7" border="0"></div>', array('/teachersportal/default/teachersubwise', 'emp'=>$emp, 'date'=>$next_week_start), array('title'=>Yii::t('app', 'Next Week')));										
                                ?>                                    
                                </div>
                               
                            </div>
                            </div>
                            <br /> <br />
                            <div class="clearfix"></div>
                           <div class="row">
                            <div class="col-md-12">
                            <div class="attendance-ul-block">
                            	<ul>
                                	<?php echo '<li>'.CHtml::link(Yii::t("app","Generate PDF"), array("/teachersportal/default/teachersubwisePdf", 'date'=>$_REQUEST['date']), array("class"=>"btn btn-danger pull-right ", "target"=>"_blank")).'</li>';?>
                                </ul>
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
                                                        $time1	= date($settings->timeformat,strtotime($timing->start_time));
                                                        $time2	= date($settings->timeformat,strtotime($timing->end_time));
                                                    }
                                                    echo '<th width="130px" class="th"><div class="top">'.$time1.'&nbsp; to &nbsp;'.$time2.'</div></th>';	                                                    
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
	?>  																		  																		
																		<div class="mark-present-blk" >                                                                
																			<p><?php echo Yii::t('app','Present'); ?></p>
																		</div>                                                                            
	<?php    
																		}
																	}
																	else{
																		$leave   = LeaveTypes::model()->findByAttributes(array('id'=>$subjectwise->leavetype_id));																			
	?>    																		
																		<div class="mark-absent-blk" >                                                                
																			<p><?php echo Yii::t('app','Absent').' ( '.$leave->type.' )'; ?></p>
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
?>					
                </div>
            </div>
        </div>
    </div>
</div>