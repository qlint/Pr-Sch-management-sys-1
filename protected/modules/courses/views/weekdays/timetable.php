<!--<script language="javascript">
<!--<script language="javascript">
function getid()
{
    var id= document.getElementById('drop').value;
    window.location = "index.php?r=weekdays/timetable&id="+id;
}
</script>-->

<style>
.container
{
	background:#FFF;
}

.tt-subject {
    width: 80px;
}
</style>
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

$batch=Batches::model()->findByAttributes(array('id'=>$_REQUEST['id'])); 
$this->breadcrumbs=array(
Yii::t('app','Courses')=>array('/courses'),
html_entity_decode($batch->name)=>array('/courses/batches/batchstudents','id'=>$_REQUEST['id']),
Yii::t('app','TimeTable'),
);
?>
<div style="background:#FFF;"> <!-- DIV 1 -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
        <td valign="top">
			<?php                                
			if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL)
			{ 
			?>
            	<div style="padding:20px;">
                    <div class="clear"></div>
                    <div class="emp_right_contner">
                        <div class="emp_tabwrapper">
                            <?php $this->renderPartial('/batches/tab');?>
                            <div class="clear"></div>
								<?php
                                $current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
                                if(Yii::app()->user->year)
                                {
                                    $year = Yii::app()->user->year;
                                }
                                else
                                {
                                    $year = $current_academic_yr->config_value;
                                }
                                $is_create = PreviousYearSettings::model()->findByAttributes(array('id'=>1));
								$is_insert = PreviousYearSettings::model()->findByAttributes(array('id'=>2));
                                $is_edit = PreviousYearSettings::model()->findByAttributes(array('id'=>3));
                                $is_delete = PreviousYearSettings::model()->findByAttributes(array('id'=>4));
								$yes_insert = 0;
								$yes_delete = 0;
								if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))
                                 {
									 $yes_insert = 1;
								 }
								 
								 if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
                                 {
									 $yes_delete = 1;
								 }
								 
                                ?>
                                <?php 
								$times=Batches::model()->findAll("id=:x", array(':x'=>$_REQUEST['id']));
								$weekdays=Weekdays::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id']));
								if(count($weekdays)==0)
									$weekdays=Weekdays::model()->findAll("batch_id IS NULL");
								
								$criteria=new CDbCriteria;
								$criteria->condition = "batch_id = :batch_id";
								$criteria->params=(array(':batch_id'=>$_REQUEST['id']));
								$criteria->order = "STR_TO_DATE(start_time, '%h:%i %p')";
								$timing = ClassTimings::model()->findAll($criteria);
								$count_timing = count($timing);
								?>
                            
                            	
<div class="button-bg">
<div class="top-hed-btn-left"><?php     
                                        
                                        if($timing!=NULL)
                                        {
                                        ?>
                                      
												<?php //echo CHtml::link(Yii::t('app','Publish Time Table'), array('Weekdays/Publish', 'id'=>$_REQUEST['id']),array('class'=>'cbut')); ?>&nbsp;
                                                <?php echo CHtml::link(Yii::t('app','Generate PDF'), array('Weekdays/pdf','id'=>$_REQUEST['id']),array('class'=>'pdf_but-input','target'=>'_blank')); ?>
                                      
                                            <?php } ?>
                                            
                                            </div>
<div class="top-hed-btn-right">
                                        <ul>
                                            <li>
                                            <?php echo CHtml::link('<span>'.Yii::t('app','Set Week Days').'</span>', array('/courses/weekdays','id'=>$_REQUEST['id']),array('class'=>'a_tag-btn'));?>
                                            </li>
                                            <li>
                                            <?php echo CHtml::link('<span>'.Yii::t('app','Set Class Timings').'</span>', array('/courses/classTiming','id'=>$_REQUEST['id']),array('class'=>'a_tag-btn'));?>
                                            </li>
                                        </ul>
                                        </div>

                                    </div> <!-- END div class="edit_bttns" -->
                              <div id="jobDialog_view_div"></div>	
                                <div  style="width:100%">
                                
										<?php     
                                        
                                        if($timing!=NULL)
                                        {
                                        ?>
                                        	
                                
                                            <div class="timetable" style="margin-top:10px; width:959px; overflow:scroll">
                                                <table border="0"  class="timetable-br1" align="center" width="100%" id="table" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="loader">&nbsp;</td><!--timetable_td_tl -->
                                                            <td class="td-blank"></td>
                                                            <?php 
                                                            foreach($timing as $timing_1)
                                                            {
																$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
																if($settings!=NULL)
																{	
																	$time1=date($settings->timeformat,strtotime($timing_1->start_time));
																	$time2=date($settings->timeformat,strtotime($timing_1->end_time));
																}
																echo '<td class="td"><div class="top">'.$time1.' -<br> '.$time2.'</div></td>';	
																//echo '<td class="td"><div class="top">'.$timing_1->start_time.' - '.$timing_1->end_time.'</div></td>';	
                                                            }
                                                            ?>
                                                        </tr> <!-- timetable_tr -->
                                                        <tr class="blank">
                                                            <td></td>
                                                            <td></td>
                                                            <?php
                                                            for($i=0;$i<$count_timing;$i++)
                                                            {
                                                            	echo '<td></td>';  
                                                            }
                                                            ?>
                                                        </tr>
                                                        
                                                        
                                                        <?php
														$weekday_text = array('SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT');													
														foreach($weekdays as $weekday){														
															if($weekday['weekday']!=0) // SUNDAY
															{
															?>
															<tr>
																<td class="td">
																	<div class="name"><?php echo Yii::t('app',$weekday_text[$weekday['weekday']-1]);?></div>
																</td>
																<td class="td-blank"></td>
																<?php
																for($i=0;$i<$count_timing;$i++)
																{
																	echo '<td class="td">
																	<div  onclick="" style="position: relative; ">
																	<div class="tt-subject">
																	<div class="subject">'; 
																	$set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekday['weekday'],'class_timing_id'=>$timing[$i]['id'])); 		
																	if(count($set)==0)
																	{
																		$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
																		if($is_break==NULL)
																		{	
																			if($yes_insert==1)
																			{
																		  echo CHtml::ajaxLink(Yii::t('app','Assign'),$this->createUrl('TimetableEntries/settime'),array('onclick'=>'$("#jobDialog'.$timing[$i]['id'].$weekday['weekday'].'").dialog("open"); return false;',														'update'=>'#jobDialog'.$timing[$i]['id'].$weekday['weekday'],'type' =>'GET','data'=>array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekday['weekday'],'class_timing_id'=>$timing[$i]['id']),'dataType'=>'text',),array('id'=>'showJobDialog'.$timing[$i]['id'].$weekday['weekday'],'class'=>'remove-form')) ;
																			}
																			else
																			{
																				echo CHtml::link('<span>'.Yii::t('app','Assign').'</span>', array('#'),array('class'=>'addbttn last','onclick'=>'alert("'.Yii::t('app','Enable Insert Option in Previous Academic Year Settings').'"); return false;'));
																			}
																			
																			
																		}
																		else
																		{
																			echo Yii::t('app','Break');
																		}	
																	}
																	else
																	{
																		if($set->is_elective==0)
																		{
																			
																			$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
																			if($time_sub!=NULL)
																			{  
																				if($set->split_subject!=0 and $set->split_subject!=NULL){ 
																					if($time_sub->split_subject){
																						$subject_splits	= SubjectSplit::model()->findByPk($set->split_subject);
																						$name_sub	=	$subject_splits->split_name."<br> (".$time_sub->name.")";
																					}
																					else{
																						$name_sub	=	$time_sub->name;
																					} 
																				}else{
																					$name_sub	=	$time_sub->name;
																				} 
																				echo $name_sub .'<br>';
																				$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
																			 
																			if($time_emp!=NULL)
																			{
																				$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));
																				
																				if($is_substitute and in_array($is_substitute->date_leave,$date_between))
																				{
																					$employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
																					echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';
																				}
																				else
																				{
																					if($time_sub!=NULL)
																					{
																						echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';
																					}
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
																			$elec_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
																			$electname = ElectiveGroups::model()->findByAttributes(array('id'=>$elec_sub->elective_group_id,'batch_id'=>$_REQUEST['id']));
																			
																			if($electname!=NULL)
																			{
																				echo '<p>'.ucfirst($electname->name).'</p>';
																				
																				echo CHtml::ajaxLink('',$this->createUrl('/timetable/weekdays/viewelective'),array('onclick'=>'$("#jobDialog_view").dialog("open"); return false;','update'=>'#jobDialog_view_div','type' =>'GET','data' => array('id' =>$electname->id, 'timing_id' =>$set->class_timing_id, 'weekday_id'=>$set->weekday_id, 'batch_id'=>$set->batch_id),'dataType' => 'text'),array('id'=>'showJobDialog_view'.$set->id,'class'=>'view view-tmtbl', 'title'=>Yii::t('app','View')));
																			}
																			
																		}
																		
																		if($yes_delete == 1)
																		{
																		echo CHtml::link('', "#", array('submit'=>array('timetableEntries/remove','id'=>$set->id,'batch_id'=>$_REQUEST['id'],), 'confirm'=>Yii::t('app','Are you sure?'), 'csrf'=>true,'class'=>'delete')); 
																		}
																		if($yes_insert == 1)
																		{ 
																		 echo CHtml::ajaxLink('',$this->createUrl('TimetableEntries/updatetime'),array('onclick'=>'$("#jobDialogupdate'.$set->id.$set->weekday_id.'").dialog("open"); return false;',														'update'=>'#jobDialogupdate'.$set->id.$set->weekday_id,'type' =>'GET','data'=>array('id'=>$set->id,'batch_id'=>$_REQUEST['id'],'weekday_id'=>$set->weekday_id,'class_timing_id'=>$set->class_timing_id),'dataType'=>'text',),array('id'=>'showJobDialogupdate'.$set->id.$set->weekday_id,'class'=>'edit')) ;
																		}
																		
																	}
																	?>
																	<?php echo 	'</div>
																	</div>
																	</div>
																	<div id="jobDialog'.$timing[$i]['id'].$weekday['weekday'].'"></div>
																	<div id="jobDialog'.$set->id.$set->weekday_id.'"></div>
																	<div id="jobDialogupdate'.$set->id.$set->weekday_id.'"></div>
																	</td>';  
																}
																?>
															</tr>
															<?php 
															}
														}
														?>
                                                    </tbody>
                                                </table>
                                            </div>
										<?php 
                                        }
                                        else
                                        { //echo '<i>'.Yii::t('app','No Class Timings').'</i>';
										?>
                                    <br />
												<div class="not-found-box">
													<?php echo '<i class="os_no_found">'.Yii::t('app','No Class Timings are set!').'</i>'; ?>
												</div>
											</div>
										<?php                                            
                                        }?>
                                    </div>                            
                                </div> <!-- END div  style="width:100%" -->
                            </div> <!-- END div class="emp_cntntbx" -->
                                

				<?php
				$batch = Weekdays::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id']));
				if(count($batch)==0)
					$batch = Weekdays::model()->findAll("batch_id IS NULL");
				?>            
           <?php
            }
            ?>
            
        </td>
    </tr>
</table>
</div> <!-- END DIV 1 -->
<script>
$(".assignbutton").click(function(e) {
    $('form#timetable-entries-form').remove();
	$('#elective_table').remove();
});
</script>
