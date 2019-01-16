<?php
if(isset($_REQUEST['format']) and $_REQUEST['format'] == 'tbl'){
?>
<style type="text/css">
	.pdtab_Con {
		margin: 0;
		padding: 8px 0 0;
	}
</style>


<div class="button-bg">
<div class="top-hed-btn-right">
<ul>
<li>
			
                            <?php echo CHtml::link("Calendar View", array("/timetable/teachersTimetable/index", "department_id"=>$department_id, "employee_id"=>$employee_id, "day_id"=>$weekday_id, "format"=>"cal"),array('class'=>'a_tag-btn'));?>            </li>
            
</ul></div>
<div class="top-hed-btn-right">
           <?php
                if($department_id!=NULL and $employee_id!=NULL and $weekday_id!=NULL)
                {
                echo CHtml::link(Yii::t('app','Generate PDF'), array('/timetable/teachersTimetable/fullTeacherPdf','department_id'=>$department_id,'employee_id'=>$employee_id, 'weekday_id'=>$weekday_id, "format"=>"tbl"),array('class'=>'a-tag-pdf-btn','target'=>'_blank')); 
                }
                    $current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
                    if(Yii::app()->user->year)
                    {
                        $year = Yii::app()->user->year;
                    }
                    else
                    {
                        $year = $current_academic_yr->config_value;
                    }
                ?></div>
</div>


	<div class="pdtab_Con" style="text-align:center">
       <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered mb30">
                    <tbody>
                    	
                        
                            <tr class="pdtab-h" >
                            	<td style="text-align:center"><strong><?php echo Yii::t('app','Course');?></strong></td>
                                <td style="text-align:center"><strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></strong></td>
                                <td style="text-align:center"><strong><?php echo Yii::t('app','Subject');?></strong></td>
                                 <td style="text-align:center"><strong><?php echo Yii::t('app','Weekday');?></strong></td>
                                <td style="text-align:center"><strong><?php echo Yii::t('app','Time');?></strong></td>
                                <td align="center"><strong><?php echo Yii::t('app','Teacher');?></strong></td>
                                <td align="center"><strong><?php echo Yii::t('app','No Of Students');?></strong></td>
                                        
                         	</tr>
                         <?php
						if($employee_id!=0 and $weekday_id!=0){
						 $timetable=TimetableEntries::model()->findAllByAttributes(array('employee_id'=>$employee_id,'weekday_id'=>$weekday_id));
						}
						if($employee_id!=0 and $weekday_id==0){
						 $timetable=TimetableEntries::model()->findAllByAttributes(array('employee_id'=>$employee_id));
						}
						if($employee_id==0 and $weekday_id!=0){
						 $timetable=TimetableEntries::model()->findAllByAttributes(array('weekday_id'=>$weekday_id));
						}
						if($employee_id==0 and $weekday_id==0){
						 $timetable=TimetableEntries::model()->findAll();
						}
					        if($timetable!=NULL) // If class timing is set for the day and check acadamic year
                             {
								
                                foreach($timetable as $timetable_1)
							     {
									
									$batch=Batches::model()->findByAttributes(array('id'=>$timetable_1->batch_id,academic_yr_id=>$year));
								   									
									$class_timing=ClassTimings::model()->findByAttributes(array('id'=>$timetable_1->class_timing_id));
									if($batch!=NULL and $class_timing!=NULL)
									{
										if($timetable_1->is_elective==0)
										{
											$subject=Subjects::model()->findByAttributes(array('id'=>$timetable_1->subject_id));
										}
										else
										{
											$subject=Electives::model()->findByPk($timetable_1->subject_id);
										}
										$course  = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
										$teacher = Employees::model()->findByAttributes(array('id'=>$timetable_1->employee_id));
										if($timetable_1->is_elective==0)
								        {
											$criteria                        = new CDbCriteria;
											$criteria->join		             = "JOIN `students` `s` ON `s`.`id`=`t`.`student_id`";
											$criteria->condition             = '`s`.`is_deleted`=:is_deleted AND `s`.`is_active`=:is_active AND `t`.`batch_id`=:batch_id AND `t`.`result_status`=:result';
											$criteria->params[':is_deleted'] = 0;
											$criteria->params[':is_active']  = 1;
											$criteria->params[':batch_id']   = $batch->id;
											$criteria->params[':result']     = 0;
											$students_count=count(BatchStudents::model()->findAll($criteria));
										}
										else if($timetable_1->is_elective==2)
										  {
											$subject=Electives::model()->findByAttributes(array('id'=>$timetable_1->subject_id));
											
											$criteria_sub                               = new CDbCriteria;
											$criteria_sub->join		                    = "JOIN `students` `s` ON `s`.`id`=`t`.`student_id`";
											$criteria_sub->join		                        .= "JOIN `batch_students` `bs` ON `bs`.`student_id`=`s`.`id`";
											$criteria_sub->condition                    = '`s`.`is_deleted`=:is_deleted AND `s`.`is_active`=:is_active AND `t`.`batch_id`=:batch_id AND `t`.`elective_id`=:elective_id AND `t`.`elective_group_id`=:elective_group_id AND `bs`.`batch_id`=:batch_id AND `bs`.`result_status`=:result';
											$criteria_sub->params[':is_deleted']        = 0;
											$criteria_sub->params[':is_active']         = 1;
											$criteria_sub->params[':batch_id']          = $batch->id;
											$criteria_sub->params[':elective_id']       = $subject->id;
											$criteria_sub->params[':elective_group_id'] = $subject->elective_group_id;
											$criteria_sub->params[':result']            = 0;
											$students_count=count(StudentElectives::model()->findAll($criteria_sub));
										  }
										$sun = Yii::t('app','SUN');
										$mon = Yii::t('app','MON');
										$tue = Yii::t('app','TUE');
										$wed = Yii::t('app','WED');
										$thu = Yii::t('app','THU');
										$fri = Yii::t('app','FRI');
										$sat = Yii::t('app','SAT');
										$weekday_text = array($sun, $mon, $tue, $wed, $thu, $fri, $sat);
										$course = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
										echo '<tr id="timetablerow'.$timetable_1->id.'">';
										echo '<td>'.$course->course_name.'</td>';
										echo '<td>'.$batch->name.'</td>';
										echo '<td>'.$subject->name.'</td>';
										echo '<td>'.$weekday_text[$timetable_1->weekday_id-1].'</td>';
										echo '<td style="text-align:center;">'.$class_timing->start_time.'-'.$class_timing->end_time.'</td>';
										echo '<td>'.$teacher->Concatened.'</td>';
										echo '<td>'.$students_count.'</td>';
										echo '</tr>';
										$flag=1;
								
							        }
							  }
							
							}
							
						   if($flag==0)
                            {
								  
								echo '<tr>';
								echo'<td colspan="5">' .'<i>'.Yii::t('app','No Timetable is set for this Day!').'</i>'.'</td>';                           	    echo '</tr>';
                             }
                            ?>
                        
					</tbody>
				</table>                                            
      	
	</div>
    <br /> <br />
<?php } ?>