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

.table_area table{ border-collapse:collapse;
	margin:0px;
	padding:0px;}

.table_area table tr td{ border:1px solid #C5CED9;
	padding:10px;}
	
.table_area table tr th{ border:1px solid #C5CED9;
	padding:15px 10px;
	background:#DCE6F1;}
</style>
<!-- Header -->
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="first" width="100">
                               <?php 
							   $filename=  Logo::model()->getLogo();
								if($filename!=NULL)
                                {
                                    //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                    echo '<img src="uploadedfiles/school_logo/'.$filename[2].'" alt="'.$filename[2].'" class="imgbrder" height="100" />';
                                }
                                ?>
                    </td>
                    <td valign="middle">
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
    <div align="center" style="display:block; text-align:center;"><?php echo Yii::t('app','TEACHER`S TIME TABLE');?></div><br />
    <!-- Course details -->
<br />
<?php
$employee = Employees::model()->findAllByAttributes(array('employee_department_id'=>$_REQUEST['department_id']));
foreach($employee as $employee_1) // Each employee
{ 
?>
<div class="table_area"> 
 <table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td style="width:500px; min-width:200px;">
            <?php echo Yii::t('app','Name').' : '.Employees::model()->getTeachername($employee_1->id);?>
        </td>
        
                                            
                     
        <td style="width:500px; min-width:200px;">
        <?php echo Yii::t('app','Teacher Number').' : '.ucfirst($employee_1->employee_number);?>
        </td>
       
                                        
   </tr>
 </table>  
 
 <br />
 
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered mb30">
   <tbody>                    
    <tr class="pdtab-h" >
              
                <th  width="200"><strong><?php echo Yii::t('app','Class Timing');?></strong></th>
                <th  width="255"><strong><?php echo Yii::t('app','Course');?></strong></th>
                <th  width="225"><strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name');?></strong></th>
                <th  width="231"><strong><?php echo Yii::t('app','Subject');?></strong></th>
                
    </tr>
		 <?php 
            $flag=0;
            $timetable = TimetableEntries::model()->findAllByAttributes(array('employee_id'=>$employee_1->id,'weekday_id'=>$_REQUEST['day_id'])); 
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
                    echo '<td width="200">'.$class_timing->start_time.'-'.$class_timing->end_time.'</td>';                           
                    echo '<td width="255">'.$course->course_name.'</td>';
                    echo '<td width="225">'.$batch->name.'</td>';
                    echo '<td width="230">'.$subject->name.'</td>';
                
                    echo '</tr>';
                    $flag_1=1;
                
                    }
              }
              if($flag_1 == 0)
              {
                   echo '<tr>';
                   echo'<td colspan="4" width="100%" align="center">' .'<i>'.Yii::t('app','No Timetable is set for this Teacher!').'</i>'.'</td>';		                            
                   echo '</tr>';
				   
              }
            }
            else // If class timing is NOT set for the employee
            {
                  
                 echo '<tr>';
                 
            echo'<td colspan="4" width="100%" align="center">' .'<i>'.Yii::t('app','No Timetable is set for this Teacher!').'</i>'.'</td>';                            
            echo '</tr>';
			
             }
            ?> 
         
	</tbody>
</table>
 <br /> 
</div>	
<?php
}
?>
 
