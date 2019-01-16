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
<?php
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
?>
	<!-- Header -->
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="first" width="100">
                           <?php $logo=Logo::model()->findAll();?>
                            <?php
                            if($logo!=NULL)
                            {
                                echo '<img src="uploadedfiles/school_logo/'.$logo[0]->photo_file_name.'" alt="'.$logo[0]->photo_file_name.'" class="imgbrder" height="100" />';
                            }
                            ?>
                </td>
                <td valign="middle" >
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
                            <td class="listbxtop_hdng first" >
                                <?php echo Yii::t('app','Phone: ').$college[2]->config_value; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <hr />
        <br />
	<!-- End Header -->

	<?php
    if(isset($_REQUEST['id']))
    {  ?>
   
    <div align="center" style="text-align:center; display:block;"><?php echo Yii::t('app','STUDENT SUBJECT WISE ATTENDANCE REPORT'); ?></div><br />
    <?php 
	$batch_stu  = BatchStudents::model()->findByAttributes(array('student_id'=>$_REQUEST['student'],'batch_id'=>$_REQUEST['id'],'result_status'=>0));
	if($batch_stu!=NULL){
		$individual = Students::model()->findByAttributes(array('id'=>$_REQUEST['student'],'is_active'=>1,'is_deleted'=>0));
	}
	//$individual = Students::model()->findByAttributes(array('id'=>$_REQUEST['student'],'batch_id'=>$_REQUEST['id']));
	$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id'],'is_active'=>1,'is_deleted'=>0));
	$course = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
	?>
   
    <!-- Individual Details -->
            <table width="685" style="font-size:13px; background:#DCE6F1; padding:10px 10px;border:#C5CED9 1px solid;">
        		<tr>
                	<?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                    <td width="120">
                        <?php echo Yii::t('app','Name'); ?>
                    </td>
                    <td width="10">
                        <strong>:</strong>
                    </td>
                    <td width="212">
                        <?php echo $individual->studentFullName("forStudentProfile");?>
                    </td>
                    <td width="120">
                        <?php echo Yii::t('app','Admission Number'); ?>
                    </td>
                   <td width="10">
                        <strong>:</strong>
                    </td>
                    <td width="212">
                        <?php echo $individual->admission_no; ?>
                    </td>
                    <?php
					}
					else{
					?>                    
                    <td width="120">
                        <?php echo Yii::t('app','Admission Number'); ?>
                    </td>
                   <td width="10">
                        <strong>:</strong>
                    </td>
                    <td width="212">
                        <?php echo $individual->admission_no; ?>
                    </td>
                    <td width="120">&nbsp;</td>
                    <td width="10">&nbsp;</td>
                    <td width="212">&nbsp;</td>
                    <?php
					}
					?>
                </tr>
                 <tr>
                    <td>
                       <?php echo Yii::t('app','Course'); ?>
                    </td>
                   <td>
                        <strong>:</strong>
                    </td>
                    <td>
                        <?php echo ucfirst($course->course_name); ?>
                        
                    </td>
                    <td>
                        <?php echo Yii::t('app','Batch'); ?>
                        <?php
						if($batch->semester_id!=NULL){
							 $sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);
							if($sem_enabled==1)
							{
								$semester=Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); 
								echo ' / '.Yii::t('app','Semester'); ?></strong>
						  <?php  }
						}
						?>
                    </td>
                   <td>
                        <strong>:</strong>
                    </td>
                    <td>
                        <?php echo ucfirst($batch->name);?>
                        <?php
						if($batch->semester_id!=NULL){
							 $sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);
							if($sem_enabled==1)
							{
								$semester=Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); 
								echo ' / '.$semester->name; ?></strong>
						  <?php  }
						}
						?>
                    </td>
                </tr>
                
                <tr>
                    <td>
                       <?php echo Yii::t('app','Admission Date'); ?>
                    </td>
                   <td>
                        <strong>:</strong>
                    </td>
                    <td>
                        <?php 
                        if($individual->admission_date!=NULL)
                        {
                            $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                            if($settings!=NULL)
                            {	
                                $individual->admission_date = date($settings->displaydate,strtotime($individual->admission_date));
                            }
                            echo $individual->admission_date; 
                        }
                        else
                        {
                            echo '-';
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo Yii::t('app','Leaves Taken'); ?>
                    </td>
                   <td>
                        <strong>:</strong>
                    </td>
                    <td>
                        <?php 
                       if($individual->admission_date>=$batch->start_date){ 
							$start_date  	= date('Y-m-d',strtotime($individual->admission_date));												
						}
						else{
							$start_date  	= date('Y-m-d',strtotime($batch->start_date));
						}													
			
						if($batch->end_date >= date('Y-m-d')){
							$end_date		= date('Y-m-d');												
						}
						else{
							$end_date		= date('Y-m-d', strtotime($batch->end_date));
						}
						$criteria 				= new CDbCriteria;
						$criteria->join			= 'JOIN `timetable_entries` `t1` ON `t1`.`id` = `t`.`timetable_id` JOIN `student_leave_types` `t2` ON (`t2`.`id` = `t`.`leavetype_id` OR `t`.`leavetype_id` = 0)'; 
						$criteria->condition 	= 't2.is_excluded=:is_excluded AND  t.date >=:z AND t.date <=:A AND t.student_id=:x AND t1.batch_id=:y';											
						$criteria->params 		= array(':is_excluded'=>0,':z'=>$start_date,':A'=>$end_date,':x'=>$individual->id, ':y'=>$_REQUEST['id']);
						$criteria->order		= 't.date DESC';
						$criteria->group 		= 't.id';													
						$subwise = StudentSubjectwiseAttentance::model()->findAll($criteria);
						echo count($subwise);?>
                    </td>
                </tr>
                        <?php 
                       $student_details=Students::model()->findByAttributes(array('id'=>$_REQUEST['student'])); 
									$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
									if($student_details->admission_date>=$batch->start_date)
									{ 
										$batch_start  = date('Y-m-d',strtotime($student_details->admission_date));
									
									}
									else
									{
										$batch_start  = date('Y-m-d',strtotime($batch->start_date));
									}
                        ?>
                </tr>
            </table>
    <!-- END Individual Details -->                            
    
    <!-- Individual Report Table -->
         <table width="700" cellspacing="0" cellpadding="0" class="attendance_table">
            <tr class="tablebx_topbg" style="background-color:#DCE6F1;">
                <td width="70"><?php echo Yii::t('app','Subject');?></td>
                <td width="205"><?php echo Yii::t('app','No Of Classes');?></td>
                <td width="450"><?php echo Yii::t('app','Leaves');?></td>
            </tr>
             <?php
			$leaves = StudentSubjectwiseAttentance::model()->findAllByAttributes(array('student_id'=>$_REQUEST['student']));
			if($leaves!=NULL)
			{ 
				$criteria = new CDbCriteria();
				$criteria->condition = 'batch_id = :batch_id';
				$criteria->group = 'subject_id';
				$criteria->params = array('batch_id' => $_REQUEST['id']);
				$times = TimetableEntries::model()->findAll($criteria);
				
				foreach($times as $time) // Displaying each leave row.
				{
					if($time->is_elective == 2){													
						$stude_elective	= StudentElectives::model()->findByAttributes(array('student_id'=>$_REQUEST['student'],'batch_id'=>$batch->id));
					}
					if((isset($stude_elective) and $stude_elective->elective_id == $time->subject_id) or $time->is_elective !=2){	
					?>
					<tr>
						<td width="205">
							<?php
								if($time->is_elective == 2){
									$elective	= Electives::model()->findByAttributes(array('id'=>$time->subject_id));
									if($time->is_elective == 2){
										$elective	= Electives::model()->findByAttributes(array('id'=>$time->subject_id));
										$elective_g	= ElectiveGroups::model()->findByAttributes(array('id'=>$elective->elective_group_id));
										if($elective){
											echo ucfirst($elective->name).' ( '.$elective_g->name.' )';
										}
										else{
											echo '-';
										}
									}
									else{
										echo '-';
									}
								}
								else{
									$subject=Subjects::model()->findByAttributes(array('id'=>$time->subject_id));
									
									if($subject!=NULL){	
										echo ucfirst($subject->name); 															
									}
									else{
										echo '-';
									}
								}
								
							?>
						</td>
						<td width="450">
								<?php
									$total_entry_count	= 0;
									for($w=1;$w<=7;$w++){
										$entries = TimetableEntries::model()->findAllByAttributes(array('subject_id'=>$time->subject_id, 'weekday_id'=>$w));
										if(count($entries)>0){														
											$entry_count	= count($entries);														
											$weekday 		= $w-1;
											if($weekday==0) $weekday=7;
											
											$start_date = $batch_start;
											$end_date 	= $batch_end;
											

											
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
													$ischeck = Configurations::model()->findByPk(43);														
													if($ischeck->config_value != 1)
													{
														if(!$is_holiday){
															
															$daycount++;
														}
													}else{
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
						<td width="450">
							<?php
							   $subwise = StudentSubjectwiseAttentance::model()->findAllByAttributes(array('student_id'=>$_REQUEST['student'], 'subject_id'=>$time->subject_id));
							   echo count($subwise);
								?>
						</td>
						<!-- End Individual Attendance row -->
					</tr>
				<?php
					}
				}
			}
			else
			{
			?>
				<tr>
					<td colspan="3" style="padding-top:10px; padding-bottom:10px;">
						<?php echo Yii::t('app','No leaves taken!'); ?>
					</td>
				</tr>
			<?php
			}
			?>
        </table>
    <!-- END Individual Report Table -->
   <?php
    }
	else
	{
     	echo Yii::t('app','No data available!'); 
    }
?>