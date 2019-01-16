<?php
$this->breadcrumbs=array(
	Yii::t('app','Weekdays')=>array('index'),
	Yii::t('app','Manage'),
);
?>
<style>
#table{
	border-top:1px #C5CED9 solid;
	/*margin:30px 30px;*/
	border-right:1px #C5CED9 solid;
}
.timetable td{
	border-left:1px #C5CED9 solid;
	padding:10px 3px 10px 3px;
	border-bottom:1px #C5CED9 solid;
	width:auto;
	/*min-width:30px;*/
	font-size:10px;
	text-align:center;
}
hr{ border-bottom:1px solid #C5CED9; border-top:0px solid #fff;}

.table_area table{ border-collapse:collapse;}

.table_area table tr td{ border:1px solid #C5CED9;
	padding:10px;}
	
.table_area table tr th{ border:1px solid #C5CED9;
	padding:15px 10px;
	background:#DCE6F1;}
.listbxtop_hdng first{
	text-align:left;
	 font-size:22px; 
	 padding-left:10px;
	}
</style>
<!-- Header -->
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="first" width="100">
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
							   $logo=Logo::model()->findAll();?>
                                <?php
                                if($logo!=NULL)
                                {
                                    //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                    echo '<img src="uploadedfiles/school_logo/'.$logo[0]->photo_file_name.'" alt="'.$logo[0]->photo_file_name.'" class="imgbrder" height="100" />';
                                }
                                ?>
                    </td>
                    <td valign="middle">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="listbxtop_hdng first">
                                    <?php $college=Configurations::model()->findAll(); ?>
                                    <?php echo $college[0]->config_value; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="listbxtop_hdng first">
                                    <?php echo $college[1]->config_value; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="listbxtop_hdng first">
                                    <?php echo 'Phone: '.$college[2]->config_value; ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
      <hr />
        <!-- End Header --> 
    <div align="center" style="display:block; text-align:center;"><?php echo Yii::t('app','TEACHER TIMETABLE');?></div><br />
    <!-- Course details -->
<br />
<div class="table_area"> 

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered mb30">
   <tbody>                    
    <tr class="pdtab-h" >
     	<th width="90"><strong><?php echo Yii::t('app','Course');?></strong></th>
        <th width="90"><strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '. Yii::t('app','Name');?></strong></th>
        <th width="90"><strong><?php echo Yii::t('app','Subject');?></strong></th>
         <th width="90"><strong><?php echo Yii::t('app','Weekday');?></strong></th>
        <th width="90"><strong><?php echo Yii::t('app','Time');?></strong></th>
         <th width="90"><strong><?php echo Yii::t('app','Teacher');?></strong></th>
        <th width="40"><strong><?php echo Yii::t('app','No Of Students');?></strong></th>
                
    </tr>
		 <?php 
            $flag=0;
			$employee_id = $_REQUEST['employee_id'];
			$weekday_id = $_REQUEST['weekday_id'];
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
						$course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
						$teacher = Employees::model()->findByAttributes(array('id'=>$timetable_1->employee_id));
						
						$criteria = new CDbCriteria;
						$criteria->join		= "JOIN `students` `s` ON `s`.`id`=`t`.`student_id`";
						$criteria->condition = '`s`.`is_deleted`=:is_deleted AND `s`.`is_active`=:is_active AND `t`.`batch_id`=:batch_id AND `t`.`result_status`=:result';
						$criteria->params[':is_deleted'] = 0;
						$criteria->params[':is_active'] = 1;
						$criteria->params[':batch_id'] = $batch->id;
						$criteria->params[':result'] = 0;
						$students_count=count(BatchStudents::model()->findAll($criteria));
						
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
						echo '<td>'.$class_timing->start_time.'-'.$class_timing->end_time.'</td>';
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
			
			echo'<td colspan="5" width="100%" align="center">' .'<i>'.Yii::t('app','No Timetable is set for this Day!').'</i>'.'</td>';                            
			echo '</tr>';
             }
            ?>           
	</tbody>
</table>
</div>	
 
