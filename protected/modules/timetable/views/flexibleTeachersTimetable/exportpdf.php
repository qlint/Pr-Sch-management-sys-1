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

.table_area table{ border-collapse:collapse; padding-left:25px;}

.table_area table tr td{ border:1px solid #C5CED9;
	padding:10px;}
	
.table_area table tr th{ border:1px solid #C5CED9;
	padding:15px 10px;
	background:#DCE6F1;}
</style>
<!-- Header -->
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="first">
                               <?php $logo=Logo::model()->findAll();?>
                                <?php
                                if($logo!=NULL)
                                {
                                    //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                    echo '<img src="uploadedfiles/school_logo/'.$logo[0]->photo_file_name.'" alt="'.$logo[0]->photo_file_name.'" class="imgbrder" height="100" />';
                                }
                                ?>
                    </td>
                    <td align="center" valign="middle" class="first" style="width:300px;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="listbxtop_hdng first" style="text-align:left; font-size:22px; padding-left:10px;">
                                    <?php $college=Configurations::model()->findAll(); ?>
                                    <?php echo $college[0]->config_value; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                    <?php echo $college[1]->config_value; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                    <?php echo 'Phone: '.$college[2]->config_value; ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
      <hr />
        <!-- End Header --> 
    <div align="center" style="display:block; margin-top:20px; font-size:16px; text-align:center;">
    <strong><?php echo Yii::t('app','CLASS TIME TABLE');?></strong></div><br />
    <!-- Course details -->
<br />
<div class="table_area">
<?php
$employee = Employees::model()->findByAttributes(array('id'=>$_REQUEST['employee_id']));
//var_dump($employee_id);exit;
?>
<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td style="width:455px; min-width:400px;">
            <?php echo Yii::t('app','Teacher Name').' : '.ucfirst($employee->first_name." ".$employee->middle_name." ".$employee->last_name);?>
        </td>
        
                                            
                     
        <td style="width:485px; min-width:100px;">
        <?php echo Yii::t('app','Teacher Number').' : '.ucfirst($employee->employee_number);?>
        </td>
       
                                        
   </tr>
 </table>  
 
 <br />     
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered mb30">
   <tbody>                    
    <tr class="pdtab-h" >
              	<th width="90"><strong><?php echo Yii::t('app','Day');?></strong></th>
                <th width="90"><strong><?php echo Yii::t('app','Class Timing');?></strong></th>
                <th width="90"><strong><?php echo Yii::t('app','Course');?></strong></th>
                <th width="90"><strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '. Yii::t('app','Name');?></strong></th>
                <th width="90"><strong><?php echo Yii::t('app','Subject');?></strong></th>
				<th width="90"><strong><?php echo Yii::t('app','Classroom');?></strong></th>
                
    </tr>
		 <?php 
		 	$sun = Yii::t('app','Sunday');
			$mon = Yii::t('app','Monday');
			$tue = Yii::t('app','Tuesday');
			$wed = Yii::t('app','Wednesday');
			$thu = Yii::t('app','Thursday');
			$fri = Yii::t('app','Friday');
			$sat = Yii::t('app','Saturday');
			$weekday = array('',$sun,$mon,$tue,$wed,$thu,$fri,$sat);
            $flag=0;
			$criteria            = new CDbCriteria;	
			$criteria->join = 'LEFT JOIN class_timings t1 ON t.class_timing_id = t1.id'; 
			if($_REQUEST['day_id']!=0)
			{
				$criteria->condition = 'employee_id=:employee_id and weekday_id=:weekday_id';
				$criteria->params    = array(':employee_id'=>$_REQUEST['employee_id'],':weekday_id'=>$_REQUEST['day_id']);
			}
			else{
				$criteria->condition = 'employee_id=:employee_id';
				$criteria->params    = array(':employee_id'=>$_REQUEST['employee_id']);
				
			}
			$criteria->order  ="STR_TO_DATE(t1.start_time, '%h:%i %p')";
			$timetable = TimetableEntries::model()->findAll($criteria); 
			
            //$timetable = TimetableEntries::model()->findAllByAttributes(array('employee_id'=>$_REQUEST['employee_id'],'weekday_id'=>$_REQUEST['day_id'])); 
            $current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
            $ac_year=$current_academic_yr->config_value;
            
            foreach($timetable as $timetable_1) // check acadamic year
            {
              $batch=Batches::model()->findAllByAttributes(array('id'=>$timetable_1->batch_id,academic_yr_id=>$current_academic_yr->config_value));
              if($batch != NULL)
                {
                 $flag=1;
                }
            
             }
            if($timetable!=NULL and $flag==1) // If class timing is set for the day and check acadamic year
             {
                $flag_1=0;
                foreach($timetable as $timetable_1)
                 {
                      
                    $batch=Batches::model()->findByAttributes(array('id'=>$timetable_1->batch_id,'academic_yr_id'=>$current_academic_yr->config_value));
                    $class_room = ClassRooms::model()->findByAttributes(array('id'=>$timetable_1->class_room_id));                                   
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
                    echo '<tr id="timetablerow'.$timetable_1->id.'">';
					$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
					if($settings!=NULL)
					{
						$time1=date($settings->timeformat,strtotime($class_timing->start_time));
						$time2=date($settings->timeformat,strtotime($class_timing->end_time));
                   	}
					echo '<td width="70">'.$weekday[$timetable_1->weekday_id].'</td>';
                    echo '<td style="text-align:center;" width="120">'.$time1.'-'.$time2.'</td>';                           
                    echo '<td width="160">'.$course->course_name.'</td>';
                    echo '<td width="120">'.$batch->name.'</td>';
                    echo '<td width="140">'.$subject->name.'</td>';
					echo '<td width="140">'.$class_room->name.'</td>';
                
                    echo '</tr>';
                    $flag_1=1;
                
                    }
              }
              if($flag_1 == 0)
              {
                   echo '<tr>';
                   echo'<td colspan="6" style="text-align:center;" width="100%">' .'<i>'.Yii::t('app','No Timetable is set for this Teacher!').'</i>'.'</td>';                            
                   echo '</tr>';
              }
            }
            else // If class timing is NOT set for the employee
            {
                  
                 echo '<tr>';
                 
            echo'<td colspan="6" style="text-align:center;" width="100%">' .'<i>'.Yii::t('app','No Timetable is set for this Teacher!').'</i>'.'</td>';                            
            echo '</tr>';
             }
            ?>           
	</tbody>
</table>
</div>	
 
