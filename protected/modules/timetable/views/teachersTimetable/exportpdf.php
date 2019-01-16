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
</style>
<!-- Header -->
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="first">
                               <?php 
							   $filename=  Logo::model()->getLogo();
								if($filename!=NULL)
                                {
                                    //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                    echo '<img src="uploadedfiles/school_logo/'.$filename[2].'" alt="'.$filename[2].'" class="imgbrder" height="100" />';
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
    <div align="center" style="display:block; text-align:center;"><?php echo Yii::t('app','CLASS TIME TABLE');?></div><br />
    <!-- Course details -->
<br />
<div class="table_area">
<?php
$employee = Employees::model()->findByAttributes(array('id'=>$_REQUEST['employee_id']));
//var_dump($employee_id);exit;
?>
<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td style="width:500px; min-width:200px;">
            <?php echo Yii::t('app','Teacher Name').' : '.Employees::model()->getTeachername($employee->id);?>
        </td>
        
                                            
                     
        <td style="width:500px; min-width:200px;">
        <?php echo Yii::t('app','Teacher Number').' : '.ucfirst($employee->employee_number);?>
        </td>
       
                                        
   </tr>
 </table>  
 
 <br />     
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered mb30">
   <tbody> 
   <?php
   $weekday_id	=	$_REQUEST['day_id'];
   $employee_id	=	$_REQUEST['employee_id'];
	if($weekday_id == 8){
		 for($i=1;$i<8;$i++)
		 {
			 $weekday_id=$i;
			 ?>
			  <tr class="pdtab-h"  width="100%" >
            	<td colspan="4" style="text-align:center">
						<?php
						if($i==1)
						{
							echo "<strong>".Yii::t('app','SUNDAY')."</strong>";
						}
						if($i==2)
						{
							echo "<strong>".Yii::t('app','MONDAY')."</strong>";
						}
						if($i==3)
						{
							echo "<strong>".Yii::t('app','TUESDAY')."</strong>";
						}
						if($i==4)
						{
							echo "<strong>".Yii::t('app','WEDNESDAY')."</strong>";
						}
						if($i==5)
						{
							echo "<strong>".Yii::t('app','THURSDAY')."</strong>";
						}
						if($i==6)
						{
							echo "<strong>".Yii::t('app','FRIDAY')."</strong>";
						}
						if($i==7)
						{
							echo "<strong>".Yii::t('app','SATURDAY')."</strong>";
						}
						?>
					</td>
				 </tr>
		<tr class="pdtab-h" >
              
                <th width="200"><strong><?php echo Yii::t('app','Class Timing');?></strong></th>
                <th width="255"><strong><?php echo Yii::t('app','Course');?></strong></th>
                <th width="225"><strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '. Yii::t('app','Name');?></strong></th>
                <th width="231"><strong><?php echo Yii::t('app','Subject');?></strong></th>
                
    </tr>
	
			 
				
			  
			 <?php
			 $flag=0;
									$criteria               = new CDbCriteria;
									$criteria->join 	= "JOIN `class_timings` `ct` ON `ct`.id = `t`.class_timing_id";
									$criteria->condition 	= "`t`.employee_id=:x AND `t`.weekday_id=:y";
									$criteria->params= array(':x'=>$employee_id, ':y'=>$weekday_id);
									$criteria->order= "STR_TO_DATE(`ct`.start_time, '%h:%i %p')";
									$timetable = TimetableEntries::model()->findAll($criteria); 
		//$timetable = TimetableEntries::model()->findAllByAttributes(array('employee_id'=>$employee_id,'weekday_id'=>$weekday_id)); 
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
				
				 // checking if classtime is present for selected weekday
				  $class_flag = 0;
				  if($i == 1){ // if selected day is sunday, then in classtiming table on_sunday field should be 1 for displaying that timetable
					 if($class_timing->on_sunday == 1){
					  $class_flag = 1;
					 }
				  }
				  if($i == 2){
					  if($class_timing->on_monday == 1){
					  $class_flag = 1;
					 }
				  }
				  if($i == 3){
					  if($class_timing->on_tuesday == 1){
					  $class_flag = 1;
					 }
				  }
				  if($i == 4){
					  if($class_timing->on_wednesday == 1){
						$class_flag = 1;
					  }
				  }
				  if($i == 5){
					  if($class_timing->on_thursday == 1){
					  $class_flag = 1;
					 }
				  }
				  if($i == 6){
					 if($class_timing->on_friday == 1){
					  $class_flag = 1;
					 }
				  }
				  if($i == 7){
					 if($class_timing->on_saturday == 1){
					  $class_flag = 1;
					 }
				  }
				  // end checking if classtime is present for selected weekday
				if($batch!=NULL and $class_timing!=NULL and $class_flag == 1)
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
					$start		=	Configurations::model()->convertTime($class_timing->start_time);
					$end		=	Configurations::model()->convertTime($class_timing->end_time);
					echo '<td style="text-align:center;">'.$start.'-'.$end.'</td>';                           
					echo '<td>'.$course->course_name.'</td>';
					echo '<td>'.$batch->name.'</td>';
					echo '<td>'.$subject->name.'</td>';
				
					echo '</tr>';
					$flag_1=1;
				}
		  }
		  if($flag_1 == 0)
		  {
			   echo '<tr>';
			   echo'<td colspan="4" style="text-align:center">' .'<i>'.Yii::t('app','No Timetable is set for this Teacher!').'</i>'.'</td>';                            
			   echo '</tr>';
		  }
		}
	else // If class timing is NOT set for the employee
		{
			  
			 echo '<tr>';
			 
		echo'<td colspan="4" style="text-align:center">' .'<i>'.Yii::t('app','No Timetable is set for this Teacher!').'</i>'.'</td>';                            echo '</tr>';
		 }
		 }
		
	 }
	 else
	 {?>
        <tr>
            <td colspan="4" style="text-align:center">
                <?php
                if($weekday_id==1)
                {
                    echo "<strong>".Yii::t('app','SUNDAY')."</strong>";
                }
                if($weekday_id==2)
                {
                    echo "<strong>".Yii::t('app','MONDAY')."</strong>";
                }
                if($weekday_id==3)
                {
                    echo "<strong>".Yii::t('app','TUESDAY')."</strong>";
                }
                if($weekday_id==4)
                {
                    echo "<strong>".Yii::t('app','WEDNESDAY')."</strong>";
                }
                if($weekday_id==5)
                {
                    echo "<strong>".Yii::t('app','THURSDAY')."</strong>";
                }
                if($weekday_id==6)
                {
                    echo "<strong>".Yii::t('app','FRIDAY')."</strong>";
                }
                ?>
            </td>
         </tr>                  
   		 <tr class="pdtab-h" >
              
                <th width="200"><strong><?php echo Yii::t('app','Class Timing');?></strong></th>
                <th width="255"><strong><?php echo Yii::t('app','Course');?></strong></th>
                <th width="225"><strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '. Yii::t('app','Name');?></strong></th>
                <th width="231"><strong><?php echo Yii::t('app','Subject');?></strong></th>
                
    </tr>
		 <?php 
            $flag=0;
			$criteria               = new CDbCriteria;
			$criteria->join 	= "JOIN `class_timings` `ct` ON `ct`.id = `t`.class_timing_id";
			$criteria->condition 	= "`t`.employee_id=:x AND `t`.weekday_id=:y";
			$criteria->params= array(':x'=>$_REQUEST['employee_id'], ':y'=>$_REQUEST['day_id']);
			$criteria->order= "STR_TO_DATE(`ct`.start_time, '%h:%i %p')";
			$timetable = TimetableEntries::model()->findAll($criteria); 
			
            //$timetable = TimetableEntries::model()->findAllByAttributes(array('employee_id'=>$_REQUEST['employee_id'],'weekday_id'=>$_REQUEST['day_id'])); 
            $current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
            $ac_year=$current_academic_yr->config_value;
            
            foreach($timetable as $timetable_1) // check acadamic year
            {
			  // checking if classtime is present for selected weekday
			  $class_time = ClassTimings::model()->findByAttributes(array('id'=>$timetable_1->class_timing_id));
			  $class_flag = 0;
			  
			  if($weekday_id == 1){ // if selected day is sunday, then in classtiming table on_sunday field should be 1 for displaying that timetable
				 if($class_time->on_sunday == 1){
				  $class_flag = 1;
				 }
			  }
			  if($weekday_id == 2){
				  if($class_time->on_monday == 1){
				  $class_flag = 1;
				 }
			  }
			  if($weekday_id == 3){
				  if($class_time->on_tuesday == 1){
				  $class_flag = 1;
				 }
			  }
			  if($weekday_id == 4){
				  if($class_time->on_wednesday == 1){
					$class_flag = 1;
				  }
			  }
			  if($weekday_id == 5){
				  if($class_time->on_thursday == 1){
				  $class_flag = 1;
				 }
			  }
			  if($weekday_id == 6){
				 if($class_time->on_friday == 1){
				  $class_flag = 1;
				 }
			  }
			  if($weekday_id == 7){
				 if($class_time->on_saturday == 1){
				  $class_flag = 1;
				 }
			  }
			  // end checking if classtime is present for selected weekday
              $batch=Batches::model()->findAllByAttributes(array('id'=>$timetable_1->batch_id,academic_yr_id=>$current_academic_yr->config_value));
              if($batch != NULL)
                {
                 $flag=1;
                }
            
             }
			
            if($timetable!=NULL and $flag==1  and $class_flag == 1) // If class timing is set for the day and check acadamic year
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
                 	$start		=	Configurations::model()->convertTime($class_timing->start_time);
					$end		=	Configurations::model()->convertTime($class_timing->end_time);
                    echo '<td style="text-align:center;" width="200">'.$start.'-'.$end.'</td>';  
                    echo '<td width="250">'.$course->course_name.'</td>';
                    echo '<td width="230">'.$batch->name.'</td>';
                    echo '<td width="230">'.$subject->name.'</td>';
                
                    echo '</tr>';
                    $flag_1=1;
                
                    }
              }
              if($flag_1 == 0)
              {
                   echo '<tr>';
                   echo'<td colspan="4" style="text-align:center">' .'<i>'.Yii::t('app','No Timetable is set for this Teacher!').'</i>'.'</td>';                            
                   echo '</tr>';
              }
            }
            else // If class timing is NOT set for the employee
            {
                  
                 echo '<tr>';
                 
            echo'<td colspan="4" style="text-align:center">' .'<i>'.Yii::t('app','No Timetable is set for this Teacher!').'</i>'.'</td>';                            
            echo '</tr>';
             }
	 }
            ?>           
	</tbody>
</table>
</div>	
 
