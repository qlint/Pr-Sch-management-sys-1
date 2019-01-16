<style>
.emp_tab_nav{
	margin-bottom:25px;	
}
</style>
<script language="javascript">
function getmode(type){
	var student_id	= <?php echo $_REQUEST['id']; ?>;
	var batch_id	= $('#batch_id').val();
		if(student_id != '' && batch_id != ''){
			window.location= 'index.php?r=students/studentAttentance/subwiseattentance&id='+student_id+'&bid='+batch_id;
		}
		else if(student_id != ''){
			window.location= 'index.php?r=students/studentAttentance/subwiseattentance&id='+student_id;
		}
		else{
			window.location= 'index.php?r=students/studentAttentance/subwiseattentance';
		}
};
</script>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Students')=>array('/students'),
	Yii::t('app','Attendance'),
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
        <div class="emp_cont_left">
     		 <?php $this->renderPartial('/students/profileleft');?>
        </div>
    </td>
    <td valign="top">
    	<div class="cont_right">
    	<div class="formWrapper">
 			 <?php $model = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
			?>
    		<h1 style="margin-top:.67em;"><?php echo Yii::t('app','Subject Wise Attendance')?></h1>
    <div class="clear"></div>
    <div class="emp_right_contner">
    <div class="emp_tabwrapper">
     <?php $this->renderPartial('application.modules.students.views.students.tab');?>
    <div class="clear"></div>
    
<?php
$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
$yr = AcademicYears::model()->findByAttributes(array('id'=>$current_academic_yr->config_value));

$date				= (isset($_REQUEST['date']))?$_REQUEST


['date']:date("Y-m-d");
$day 				= date('w', strtotime($date));
$week_start			= date('Y-m-d', strtotime('-'.$day.' days', strtotime($date)));
$week_end 			= date('Y-m-d', strtotime('+'.(6-$day).' days', strtotime($date)));
$prev_week_start	= date('Y-m-d', strtotime('-7 days', strtotime($week_start)));
$next_week_start	= date('Y-m-d', strtotime('+1 days', strtotime($week_end)));
$this_date			= $week_start;

$batches    			= BatchStudents::model()->studentBatch($_REQUEST['id']); 
if($batches){
	foreach($batches as $batch){
		$batch_list[$batch->id]	= ucfirst($batch->name);
	}
}
if(count($batches) == 1){
	$batch    	= 	BatchStudents::model()->findByAttributes(array('student_id'=>$_REQUEST['id'], 'result_status'=>0));
	$bid 		=  $batch->batch_id;		
}
elseif(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL){
	$bid 		=  $_REQUEST['bid'];	
}
elseif(count($batches)>1){ 
	$batch    	= 	BatchStudents::model()->findByAttributes(array('student_id'=>$_REQUEST['id'], 'result_status'=>0, 'batch_id'=>$batches[0]->id));
	$bid 		=  $batch->batch_id;
}
?>
		 <div class="subwis-tableposctn">
            <div class="formWrapper formWrapper-subwis">
                <div  style="width:100%">                            
                    <div class="Nodata-bg">                                
                        <?php                                    
                         if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL and $bid!=NULL){
							$batch = Batches::model()->findByAttributes(array('id'=>$bid));
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
                                
                            $criteria				= new CDbCriteria;
                            $criteria->condition 	= "batch_id=:x";
                            $criteria->params 		= array(':x'=>$bid);
                            $criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
                            $timings 				= ClassTimings::model()->findAll($criteria);
							
                            
                            $count_timing = count($timings);
							
                            if($timings!=NULL){?>
                                <div class="opnsl_headerBox">
                                    <div class="opnsl_actn_box">
                                  <div class="opnsl_actn_box1">
  
                                <div class="opnsl_atnd_calender atnd_tnav-new " align="center">
                                            <?php
                                                echo CHtml::link('<div class="atnd_arow_l"><img src="'.Yii::app()->request->baseUrl.'/images/attnd-arow-left.png" height="13" width="7" border="0"></div>', array('/students/studentAttentance/subwiseattentance', 'id'=>$_REQUEST['id'], 'bid'=>$bid,'date'=>$prev_week_start), array('title'=>Yii::t('app', 'Previous Week')));											
                                                	$month1	=	date("M", strtotime($week_start));
													$month2	=	date("M", strtotime($week_end));
													$day1	=	date("d", strtotime($week_start));	
													$day2	=	date("d", strtotime($week_end));								
                                                    echo Yii::t("app",$month1).' '.$day1." - ".Yii::t("app",$month2).' '.$day2;                                        
                                                echo CHtml::link('<div class="atnd_arow_r"><img src="'.Yii::app()->request->baseUrl.'/images/attnd-arow-right.png" height="13" width="7" border="0"></div>', array('/students/studentAttentance/subwiseattentance', 'id'=>$_REQUEST['id'], 'bid'=>$bid, 'date'=>$next_week_start), array('title'=>Yii::t('app', 'Next Week')));										
                                            ?>                                    
                                        </div>
                                        </div>
                                        <div class="opnsl_actn_box1 opnsl_Field_wdth">
                                        <?php
										echo CHtml::dropDownList('bid','',$batch_list,array('id'=>'batch_id','style'=>'','class'=>'form-control input-sm mb14','options'=>array($bid=>array('selected'=>true)),'encode'=>false,'onchange'=>'getmode();'));
										?>
                                        </div>
                                    </div>
                                    <div class="opnsl_actn_box">
                                    <?php   echo CHtml::link('Generate PDF', array('studentAttentance/subwisepdf','id'=>$_REQUEST['id'], 'bid'=>$bid, 'date'=>$date),array('target'=>'_blank','class'=>'pdf_but')); ?>
                                    </div>
                                    </div>
                                		
                                 <div class="clearfix"></div>
                                  <?php //if($batch->start_date <= $week_start and $week_start <= $batch->end_date ){ ?>
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
                                                        //traslate AM and PM 	
															$t1 = date('h:i', strtotime($timing_1->start_time));	
															$t2 = date('A', strtotime($timing_1->start_time));
															
															$t3	= date('h:i', strtotime($timing_1->end_time));	
															$t4	= date('A', strtotime($timing_1->end_time));	
															//end 
															
                                                            $time1	= date($settings->timeformat,strtotime($timing_1->start_time));
                                                            $time2	= date($settings->timeformat,strtotime($timing_1->end_time));
                                                    }
                                                    echo '<th width="130px" class="td"><center><div class="top">'.$t1.' '.Yii::t("app",$t2).' - '.$t3.' '.Yii::t("app",$t4).'</div></center></th>';	
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
                                                     <?php 
													 if($batch->start_date <= $this_date and $this_date <= $batch->end_date ){ 
													  if($set == NULL){
															$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timings[$i]['id'],'is_break'=>1));
															if($is_break!=NULL)
															{	
																echo Yii::t('app','Break');
															}
													 }
													 else
                                                        {
															$visible=0;
															$subjectwise =  StudentSubjectwiseAttentance::model()->findByAttributes(array('student_id'=>$_REQUEST['id'], 'timetable_id'=>$set->id, 'weekday_id'=>$set->weekday_id, 'date'=>$week_start)); 
															if($set->is_elective == 2){
																$elective			=	Electives::model()->findByAttributes(array('batch_id'=>$batch->id, 'id'=>$set->subject_id)); 
																$student_elective	=	StudentElectives::model()->findByAttributes(array('student_id'=>$_REQUEST['id'], 'batch_id'=>$batch->id, 'elective_group_id'=>$elective->elective_group_id)); 
																if($student_elective==NULL){
																	$visible=1;
																}else{
																	$visible=0;
																}															
															}else{
																$visible=0;
															} 
															$subjectwise =  StudentSubjectwiseAttentance::model()->findByAttributes(array('student_id'=>$_REQUEST['id'], 'timetable_id'=>$set->id, 'weekday_id'=>$set->weekday_id, 'date'=>$week_start));
															$is_holiday		= StudentAttentance::model()->isHoliday($week_start);
															if($is_holiday == NULL){
																if($subjectwise == NULL){
																		if($batch->start_date <= $week_start and $week_start <= $batch->end_date ){
																		if($week_start >= $model->admission_date and $week_start <= date("Y-m-d")  and $visible==0){
															
																echo CHtml::ajaxLink(Yii::t('app','Mark Leave'),$this->createUrl('studentAttentance/subjectwise'),
														array('onclick'=>'$("#jobDialog").dialog("open"); return false;','update'=>'#jobDialog','type' =>'GET',
														'data' => array('timetable_id' =>$set->id, 'student_id' =>$_REQUEST['id'], 'weekday_id' =>$set->weekday_id,'subject_id' =>$set->subject_id,'date'=>$week_start),'dataType' => 'text',),
														array('id'=>'showJobDialog'.$set->id,'class'=>'mark_leave'));
														echo '';
																			}
														
																		}
																}
																else{
																?>
                                                                <div class="action-box">		
																	<?php
																		echo CHtml::ajaxLink('',$this->createUrl('studentAttentance/subjectwise'),
																		array('onclick'=>'$("#jobDialog2").dialog("open"); return false;','update'=>'#jobDialog','type' =>'GET',
																		'data' => array('id' =>$subjectwise->id,'timetable_id' =>$set->id, 'student_id' =>$_REQUEST['id'], 'weekday_id' =>$set->weekday_id, 'subject_id' =>$set->subject_id, 'date'=>$week_start),'dataType' => 'text',),
																		array('id'=>'showJobDialog2'.$set->id,'class'=>'timtable-update'));
																		
																		echo CHtml::link('', "#", array('submit'=>array('studentAttentance/remove','id'=>$subjectwise->id, 'date'=>$week_start), 'confirm'=>Yii::t('app','Are you sure you want to remove absent ?'), 'csrf'=>true,'class'=>'timtable-delt')); 
																		
																		
																		?>
                                                                </div>	
                                                                <div class="mark-absent-blk" >                                                                
                                                                    <p>
																		<?php 
                                                                        echo CHtml::ajaxLink('Absent',$this->createUrl('studentAttentance/viewsubwise'),
                                                                        array('onclick'=>'$("#jobDialog_view").dialog("open"); return false;','update'=>'#jobDialog_view_div'.$subjectwise->id,'type' =>'GET','data' => array('id' =>$subjectwise->id),'dataType' => 'text',),array('id'=>'showJobDialog_view'.$subjectwise->id,'class'=>'mark-absent', 'title'=>Yii::t('app','View')));
																		?>
                                                                     </p>       
                                        						</div>
                                                                <?php }
																?>
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
																							echo '<div class="batch_name">'.ucfirst($employee->first_name).' '.ucfirst($employee->middle_name).' '.ucfirst($employee->last_name).'</div>';
																						}
																						else
																						{
																							if($time_sub!=NULL)
																							{
																								echo '<div class="batch_name">'.ucfirst($time_emp->first_name).' '.ucfirst($time_emp->middle_name).' '.ucfirst($time_emp->last_name).'</div>';
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
																					
																					if($electname!=NULL and $visible == 0)
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
																							//echo '<div class="batch_name">'.ucfirst($employee->first_name).' '.ucfirst($employee->middle_name).' '.ucfirst($employee->last_name).'</div>';
																						}
																						else
																						{
																							if($electname!=NULL)
																							{
																								//echo '<div class="batch_name">'.ucfirst($time_emp->first_name).' '.ucfirst($time_emp->middle_name).' '.ucfirst($time_emp->last_name).'</div>';
																							}
																						}
																					}
																					
																				}?>
                                                                                 </div>
                                                                               
                                                                            </div>
                                                                        </div>
																	<?php
																	} // holiday
																	else{
																			echo '<div class="attnd-holiday">'.Yii::t('app','Holiday').'</div>';
																		}
																} //end time table entries present
													 }
														
                                                    }
                                                    ?>
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
                                <?php
								 ?>
                                
                                </div> <!-- END div class="timetable" -->
                            <?php 
							/*}
							else
							 {
								   echo '<div class="not-found-box"><i class="os_no_found">'.Yii::t('app','No class on this date').'</i></div>';
							 }*/
                            }
                            else{
                                echo '<i>'.Yii::t('app','No Class Timings').'</i>';
                            }
                        }
						else{
							echo '<i>'.Yii::t('app','No Results Found').'</i>';
						}                                    
                        ?> 
                    </div>                            
                </div>
                <div class="clear"></div>
                </div> 
        	</div>
    	</td>
    </div>
  </tr>
</table>

