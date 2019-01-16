<style>
.attendance_table{
	margin:30px 0px;
	font-size:8px;
	text-align:center;
	width:100%;
	border-collapse:collapse;


}
.attendance_table td{
	padding-top:10px; 
	padding-bottom:10px;
	border:1px  solid #C5CED9;
	width:auto;
	font-size:13px;
	
}

.attendance_table th{
	font-size:13px;
	padding:10px;
	border:1px  solid #C5CED9;
}
.listbxtop_hdng first{
	text-align:left; 
	font-size:14px; 
	padding-left:10px;
}
hr{ border-bottom:1px solid #C5CED9; border-top:0px solid #fff;}

</style>

	<!-- Header -->
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="first" width="100">
                           <?php $logo=Logo::model()->findAll();?>
                            <?php
                            if($logo!=NULL)
                            {
                                //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                echo '<img src="uploadedfiles/school_logo/'.$logo[0]->photo_file_name.'" alt="'.$logo[0]->photo_file_name.'" class="imgbrder" height="100" />';
                            }
                            ?>
                </td>
                <td  valign="middle">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="listbxtop_hdng first" >
                                <?php $college=Configurations::model()->findAll(); ?>
                                <?php echo $college[0]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" >
                                <?php echo $college[1]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first">
                                <?php echo Yii::t('app','Phone: ').$college[2]->config_value; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    <hr />
	<!-- End Header -->
<br />

	<?php
    if(isset($_REQUEST['department_id']) and isset($_REQUEST['employee_id']) and isset($_REQUEST['start']) and isset($_REQUEST['end']) and $_REQUEST['department_id']!=NULL and $_REQUEST['employee_id']!=NULL and $_REQUEST['start']!=NULL and $_REQUEST['end']!=NULL) { 
   ?>
   
    <div align="center" style="text-align:center; display:block;">
			<?php echo Yii::t('app','TEACHER SUBJECT WISE ATTENDANCE REPORT'); ?>
      </div>
      <br />
    <?php 
	$settings	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
	if($_REQUEST['employee_id'] == 0){
		$employees = Employees::model()->findAllByAttributes(array('is_deleted'=>0, 'employee_department_id'=>$_REQUEST['department_id']));	
	 }else{
		 $employees = Employees::model()->findAllByAttributes(array('id'=>$_REQUEST['employee_id'],'is_deleted'=>0));	
	 }
	?>
    
    <table width="685" style="font-size:13px; background:#DCE6F1; padding:10px 10px;border:#C5CED9 1px solid;">
        <tr>
            <td width="120"><?php echo Yii::t('app','Start Date');?></td>
            <td width="10">:</td>
            <td width="212"><?php echo date($settings->displaydate, strtotime($_REQUEST['start']));?></td>            
            <td width="120"><?php echo Yii::t('app', 'End Date');?></td>
            <td width="10">:</td>
            <td width="212"><?php echo date($settings->displaydate, strtotime($_REQUEST['end']));?></td>
        </tr>            
    </table>
  
    
    <!--  Attendance Table -->
         <table width="100%" cellspacing="0" cellpadding="0" class="attendance_table">
            <tr class="tablebx_topbg" style="background-color:#DCE6F1;">
               <td width="50"><?php echo Yii::t('app','Sl No');?></td>
               <td width="100"><?php echo Yii::t('app','Teacher No');?></td>
               <td width="120"><?php echo Yii::t('app','Joining Date');?></td>
               <td style="width:240px;"><?php echo Yii::t('app','Name');?></td>
               <td style="width:110px;"><?php echo Yii::t('app','Job Title');?></td>
               <td style="width:110px;"><?php echo Yii::t('app','Total leaves per classes');?></td>
               <td style="width:110px;"><?php echo Yii::t('app','Total leaves per hours');?></td>
            </tr>
             <?php
				$overall_sl = 1;
				foreach($employees as $employee) // Displaying each employee row.
				{
				?>
				<tr>
					<td style="padding-top:10px; padding-bottom:10px;"><?php echo $overall_sl; $overall_sl++;?></td>
					<td><?php echo $employee->employee_number; ?></td>
					 <td>
						<?php if($employee->joining_date!=NULL)
								{									
									if($settings!=NULL)
									{	
										$employee->joining_date = date($settings->displaydate,strtotime($employee->joining_date));
									}
									echo $employee->joining_date; 
								}
								else
								{
									echo '-';
								}
						 ?>
					</td>
                    <td>
                   <?php echo ucfirst($employee->first_name).' '.ucfirst($employee->middle_name).' '.ucfirst($employee->last_name);?>
                   </td>
                    <td><?php if($employee->job_title!=NULL){ echo $employee->job_title; } else{ echo '-';}?>
                    </td>
					<td>
						<?php
											$subwises = TeacherSubjectwiseAttentance::model()->findAllByAttributes(array('employee_id'=>$employee->id));
											$start_date  = date('Y-m-d',strtotime($_REQUEST['start']));
											if($start_date < date('Y-m-d', strtotime($employee->joining_date))){
												$start_date	= date('Y-m-d', strtotime($employee->joining_date));
											}
											$end_date  = date('Y-m-d',strtotime($_REQUEST['end']));
											$leave_count = 0;
											foreach($subwises as $subwise){
												if($start_date <= $subwise->date and $subwise->date <= $end_date){
													$leave_count  = 	$leave_count+1;
													
												}
											}
											echo $leave_count.'/';
											
												$total_entry_count	= 0;
												for($w=1;$w<=7;$w++){
													$criteria				= new CDbCriteria();
													$criteria->condition	= 'employee_id=:employee_id AND weekday_id=:weekday_id';
													$criteria->params		= array(':employee_id'=>$employee->id, ':weekday_id'=>$w); 
													$criteria->group		= 'class_timing_id';
													$entries = TimetableEntries::model()->findAll($criteria);
													
													if(count($entries)>0){														
														$entry_count	= count($entries);														
														$weekday 		= $w-1;
														if($weekday==0) $weekday=7;
														
														$start_date  = date('Y-m-d',strtotime($_REQUEST['start']));
														if($start_date < date('Y-m-d', strtotime($employee->joining_date))){
															$start_date	= date('Y-m-d', strtotime($employee->joining_date));
														}
														$end_date  = date('Y-m-d',strtotime($_REQUEST['end']));
															
														$daycount	= 0;
														$start 		= new DateTime($start_date);
														$end   		= new DateTime($end_date);
														$end->modify('+1 day');
														$interval 	= DateInterval::createFromDateString('1 day');
														$period 	= new DatePeriod($start, $interval, $end);
														foreach ($period as $dt){
															if ($dt->format('N') == $weekday){
																$day	=	$dt->format('Y-m-d');
																$is_holiday		= StudentAttentance::model()->isHoliday($day);	
																if(!$is_holiday){
																	$daycount++;
																}
															}
														}
														
														$total_entry_count	+= ($daycount * $entry_count);														
													}
												}
												echo $total_entry_count;
											?>                         
					
					</td>
                    <td>	
					<?php
											$subwises = TeacherSubjectwiseAttentance::model()->findAllByAttributes(array('employee_id'=>$employee->id));
											$start_date  = date('Y-m-d',strtotime($_REQUEST['start']));
											if($start_date < date('Y-m-d', strtotime($employee->joining_date))){
												$start_date	= date('Y-m-d', strtotime($employee->joining_date));
											}
											$end_date  = date('Y-m-d',strtotime($_REQUEST['end']));
											$leave_minutes = 0;
											foreach($subwises as $subwise){
												if($start_date <= $subwise->date and $subwise->date <= $end_date){
													$timetable = TimetableEntries::model()->findByAttributes(array('id'=>$subwise->timetable_id));
													if($timetable){
														$class_timing = ClassTimings::model()->findByAttributes(array('id'=>$timetable->class_timing_id));
													}
													if($class_timing!=NULL){
														$minute_diff = (strtotime($class_timing->end_time) - strtotime($class_timing->start_time))/60;
														$leave_minutes	+= $minute_diff;
													}
												}
											}
											$leave_hour = floor($leave_minutes/60);
											$leave_mins = $leave_minutes%60;	
												$total_minutes	= 0;
												for($w=1;$w<=7;$w++){
													$criteria				= new CDbCriteria();
													$criteria->condition	= 'employee_id=:employee_id AND weekday_id=:weekday_id';
													$criteria->params		= array(':employee_id'=>$employee->id, ':weekday_id'=>$w); 
													$criteria->group		= 'class_timing_id';
													$time_tables = TimetableEntries::model()->findAll($criteria);
													if($time_tables){
														foreach($time_tables as $time_table)
														{
															$class_timing = ClassTimings::model()->findByAttributes(array('id'=>$time_table->class_timing_id));
															if($class_timing!=NULL){
																$minute_diff		= (strtotime($class_timing->end_time) - strtotime($class_timing->start_time))/60;
															}
															$weekday 		= $w-1;
															if($weekday==0) $weekday=7;
																$start_date  = date('Y-m-d',strtotime($_REQUEST['start']));
																if($start_date < date('Y-m-d', strtotime($employee->joining_date))){
																	$start_date	= date('Y-m-d', strtotime($employee->joining_date));
																}
																$end_date  = date('Y-m-d',strtotime($_REQUEST['end']));
															$daycount	= 0;
															$start 		= new DateTime($start_date);
															$end   		= new DateTime($end_date);
															$end->modify('+1 day');
															$interval 	= DateInterval::createFromDateString('1 day');
															$period 	= new DatePeriod($start, $interval, $end);													
															foreach ($period as $dt){
																if ($dt->format('N') == $weekday){
																	$day	=	$dt->format('Y-m-d');
																	$is_holiday		= StudentAttentance::model()->isHoliday($day);	
																	if(!$is_holiday){
																		$daycount++;
																	}
																}
															}
															$days = $daycount;
															$total_minutes += ($daycount * $minute_diff);
														}
													}
												}
												$hour = floor($total_minutes/60);
												$mins = $total_minutes%60;
												echo $leave_hour.'h'.$leave_mins.' / '.$hour.'h'.$mins;	
											?>                 
					</td>
				</tr>
				
    <!-- END Overall Attendance Table -->
   
   <?php
   		}
		?>
         </table>
         <?php
    }
	else
	{
    ?>
    		<?php echo Yii::t('app','No data available!'); ?>
       
	<?php
    }
?>
