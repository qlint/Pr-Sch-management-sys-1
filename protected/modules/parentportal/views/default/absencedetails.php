<style type="text/css">
.nothing-found{
	text-align:center;
	font-style:italic;
}
</style>
<script language="javascript">
function showsearch(){
	if ($("#seachdiv").is(':hidden')){
		$("#seachdiv").show();
	}
	else{
		$("#seachdiv").hide();
	}
}
function getmode(type){
	var student_id	= $('#student_id').val();
	var batch_id	= $('#batch_id').val();
	
	if(type == 1){
		if(student_id != ''){
			window.location= 'index.php?r=parentportal/default/absenceDetails&id='+student_id;
		}
		else{
			window.location= 'index.php?r=parentportal/default/absenceDetails';
		}
	}
	if(type	== 2){
		if(student_id != '' && batch_id != ''){
			window.location= 'index.php?r=parentportal/default/absenceDetails&id='+student_id+'&bid='+batch_id;
		}
		else if(student_id != ''){
			window.location= 'index.php?r=parentportal/default/absenceDetails&id='+student_id;
		}
		else{
			window.location= 'index.php?r=parentportal/default/absenceDetails';
		}
	}	
}
</script>

<?php
	$this->renderPartial('leftside');
	$guardian 				= Guardians::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$student_list			= '';
	$batch_list				= '';
		
	$criteria 				= new CDbCriteria;		
	$criteria->join 		= 'LEFT JOIN guardian_list t1 ON t.id = t1.student_id'; 
	$criteria->condition 	= 't1.guardian_id=:guardian_id and t.is_active=:is_active and is_deleted=:is_deleted';
	$criteria->params 		= array(':guardian_id'=>$guardian->id,':is_active'=>1,'is_deleted'=>0);
	$students 				= Students::model()->findAll($criteria); 
	
	if(isset($_REQUEST['id']) and $_REQUEST['id'] != NULL){
			$batches    = 	BatchStudents::model()->studentBatch($_REQUEST['id']); 
			if($batches){
				foreach($batches as $batch){
					$course    = 	Courses::model()->findByAttributes(array('id'=>$batch->course_id)); 
					$batch_list[$batch->id]	= ucfirst($batch->name).' ( '.$course->course_name.' )';
				}
			}	
	}
	if($students){
		$student_list = CHtml::listData($students,'id','studentnameforparentportal');
	}
	
?>
<div class="pageheader">
    <div class="col-lg-8">
    	<h2><i class="fa fa-file-text"></i> <?php echo Yii::t('app','Attendance'); ?> <span><?php echo Yii::t('app','View your attendance here'); ?></span></h2>
    </div>    
    <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
        <ol class="breadcrumb">                  
        	<li class="active"><?php echo Yii::t('app','Attendance'); ?></li>
        </ol>
    </div>    
    <div class="clearfix"></div>
</div>
<div class="contentpanel">
	<div class="people-item">
    	<div class="row">
            <div class="col-md-4">
            	<label><?php echo Yii::t('app','Viewing Attendance of'); ?></label>
                <?php				
                    echo CHtml::dropDownList('sid','',$student_list,array('prompt'=>Yii::t('app','Select Student'),'id'=>'student_id','class'=>'input-form-control','options'=>array($_REQUEST['id']=>array('selected'=>true)),'onchange'=>'getmode(1);'));
                ?>
            </div> 
            <div class="col-md-4">
            	<label><?php echo Students::model()->getAttributeLabel('batch_id'); ?></label>
                <?php				
                    echo CHtml::dropDownList('bid','',$batch_list,array('prompt'=>Yii::t('app','Select').' '.Students::model()->getAttributeLabel('batch_id'), 'encode'=>false, 'id'=>'batch_id','class'=>'input-form-control','options'=>array($_REQUEST['bid']=>array('selected'=>true)),'onchange'=>'getmode(2);'));
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>  
    <div class="panel-heading">
            <h3 class="panel-title"><?php echo Yii::t('app','Student Attendance Report');?></h3>
        </div> 
    <div class="people-item">
<div class="attendance-ul-block">
                    <ul>
					<?php 
						if($_REQUEST['id'] != NULL){
							echo '<li>'.CHtml::link(Yii::t('app','Back'), array('/parentportal/default/attendance','id'=>$_REQUEST['id']),array('class'=>'btn btn-primary')).'</li>';            
						}
						else{
							echo '<li>'.CHtml::link(Yii::t('app','Back'), array('/parentportal/default/attendance'),array('class'=>'btn btn-primary')).'</li>';            
						}
						if($_REQUEST['id'] != NULL and $_REQUEST['bid'] != NULL){
							echo'<li>'. CHtml::link(Yii::t('app','Generate PDF'), array('/parentportal/default/attendancePdf','id'=>$_REQUEST['id'], 'bid'=>$_REQUEST['bid']),array('target'=>"_blank",'class'=>'btn btn-danger')).'</li>';
						}
					?>	
                    </ul> 	
                </div> 
    	<?php if($_REQUEST['id'] != NULL and $_REQUEST['bid'] != NULL){
				$student		= 	Students::model()->findByPk($_REQUEST['id']);
				$batch			= 	Batches::model()->findByPk($_REQUEST['bid']);
				$course			= 	Courses::model()->findByAttributes(array('id'=>$batch->course_id));
				$semester		=	Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); 
				$sem_enabled	= 	Configurations::model()->isSemesterEnabledForCourse($course->id);?>
        		<div class="table-responsive">
                    <table class="table table-hover mb30">
                        <tr>                            				
                            <th><?php echo Students::model()->getAttributeLabel('admission_no');?></th>
                            <?php
                                if(FormFields::model()->isVisible("fullname", "Students", "forParentPortal")){						
                            ?> 	
                                <th><?php echo Yii::t('app','Name');?></th>
                            <?php } ?> 
							<th>
									<?php 
									 if($sem_enabled==1 and $batch->semester_id!=NULL){		
										echo Yii::t('app','Course/Semester');
									 }
									 else{
										 echo Yii::t('app','Course');
									 }
									?>
								</th> 
                            <th><?php echo Yii::t('app','Working Days');?></th>
                            <th><?php echo Yii::t('app','Leaves');?></th>
                        </tr>
                        <tr>
                        	<td><?php echo $student->admission_no; ?></td>
							<?php
                                if(FormFields::model()->isVisible("fullname", "Students", "forParentPortal")){
                            ?> 	
                                <td>
                                    <?php echo $student->studentFullName('forParentPortal');?>
                                </td>
                            <?php } ?>
								<td>
                                    <?php 
									 if($sem_enabled==1 and $batch->semester_id!=NULL){		
										echo ucfirst($course->course_name).' / '.ucfirst($semester->name);
									 }
									 else{
										 echo ucfirst($course->course_name);
									 }
									?>
                                </td>
                            	<td>
								<?php																																									
									if($student->admission_date >= $batch->start_date){ 
										$start_date  	= date('Y-m-d',strtotime($student->admission_date));												
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
									$batch_days_1  = array();
									$batch_range_1 = StudentAttentance::model()->createDateRangeArray($start_date,$end_date);  // to find total session
									$batch_days_1  = array_merge($batch_days_1,$batch_range_1);
									
									$days = array();
									$days_1 = array();
									$weekArray = array();
									
									$total_working_days_1 = array();
									$weekdays = Weekdays::model()->findAll("batch_id=:x AND weekday<>:y", array(':x'=>$batch->id,':y'=>"0"));
									if(count($weekdays)==0)
									{
										
										$weekdays = Weekdays::model()->findAll("batch_id IS NULL AND weekday<>:y",array(':y'=>"0"));
									}
									foreach($weekdays as $weekday)
									{
										
										$weekday->weekday = $weekday->weekday - 1;
										if($weekday->weekday <= 0)
										{
											$weekday->weekday = 7;
										}
										$weekArray[] = $weekday->weekday;
									}
									foreach($batch_days_1 as $batch_day_1)
									{
										$week_number = date('N', strtotime($batch_day_1));
										if(in_array($week_number,$weekArray)) // If checking if it is a working day
										{
											array_push($days_1,$batch_day_1);
										}
									}
									$holiday_arr[] =array();
									$ischeck = Configurations::model()->findByPk(43);
									
									if($ischeck->config_value != 1)
									{
										$holidays = Holidays::model()->findAll();
										$holiday_arr=array();
										foreach($holidays as $key=>$holiday)
										{
											if(date('Y-m-d',$holiday->start)!=date('Y-m-d',$holiday->end))
											{
												$date_range = StudentAttentance::model()->createDateRangeArray(date('Y-m-d',$holiday->start),date('Y-m-d',$holiday->end));
												foreach ($date_range as $value) {
													$holiday_arr[] = date('Y-m-d',$date_range);
												}
											}
											else
											{
												$holiday_arr[] = date('Y-m-d',$holiday->start);
											}
										}
									}                                  
									foreach($days_1 as $day_1)
									{
										
										if(!in_array($day_1,$holiday_arr)) // If checking if it is a working day
										{
											array_push($total_working_days_1,$day_1);
										}
									}
									echo count($total_working_days_1);													
								?>
                            </td>
                            <td>
								<?php
                                $leavedays 				= array();
								$criteria 				= new CDbCriteria;		
								$criteria->join 		= 'LEFT JOIN student_leave_types t1 ON (t.leave_type_id = t1.id OR t.leave_type_id = 0)'; 
								$criteria->condition 	= 't1.is_excluded=:is_excluded AND t.student_id=:x AND t.date >=:z AND t.date <=:A AND t.batch_id=:batch_id';											
								$criteria->params 		= array(':is_excluded'=>0,':x'=>$student->id,':z'=>$start_date,':A'=>$end_date, 'batch_id'=>$batch->id);
								$criteria->order		= 't.date DESC';
								$criteria->group 		= 't.id'; 
                                $leaves    				= StudentAttentance::model()->findAll($criteria);
                                
                                foreach($leaves as $leave){
                                    if(!in_array($leave->date,$leavedays)){
                                        array_push($leavedays,$leave->date);
                                    }
                                }
                                echo count($leavedays);
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>	
                <h3 class="panel-title"><?php echo Yii::t('app','Leave Details');?></h3><br />
                <div class="table-responsive">
                    <table class="table table-hover mb30">
                        <tr>
                            <th><?php echo '#';?></th>
                            <th><?php echo Yii::t('app','Leave Type');?></th>
                            <th><?php echo Yii::t('app','Leave Date');?></th>
                            <th><?php echo Yii::t('app','Reason');?></th>
                        </tr>
                        <?php
							$i 						= 1;
							$criteria 				= new CDbCriteria;												
							$criteria->condition 	= 't.student_id=:x AND t.date >=:z AND t.date <=:A AND t.batch_id=:batch_id';											
							$criteria->params 		= array(':x'=>$student->id,':z'=>$start_date,':A'=>$end_date, 'batch_id'=>$batch->id);
							$criteria->order		= 't.date DESC';
							$student_leaves			= StudentAttentance::model()->findAll($criteria);
							if($student_leaves){
								$settings			= UserSettings::model()->findByAttributes(array('user_id'=>1));
								foreach($student_leaves as $student_leave){
									$leave_type		= StudentLeaveTypes::model()->findByAttributes(array('id'=>$student_leave->leave_type_id,'status'=>1));
						?>
                        			<tr>
                                    	<td><?php echo $i; ?></td>
                                        <td>	
                                        	<?php	
												if($leave_type){
													echo ucfirst($leave_type->name);													
												}
												else{
													echo '-';
												}
											?>
                                        </td>
                                        <td>
                                        	<?php
												if($settings != NULL){ 
													echo date($settings->displaydate, strtotime($student_leave->date));
												}
												else{
													echo $student_leave->date;
												}
											?>
                                        </td>
                                        <td>
                                        	<?php
												if($student_leave->reason != NULL){
													echo ucfirst($student_leave->reason);
												}
												else{
													echo '-';
												}
											?>
                                        </td>
                                    </tr>
                        <?php			
									$i++;
								}
							}
							else{
						?>
                        		<tr>	
                                	<td colspan="4" align="center"><?php echo Yii::t('app', 'No leaves taken'); ?></td>
                                </tr>
                        <?php		
							}
						?>
                    </table>
                </div>        
		<?php	
			}
			else{
				echo '<div class="nothing-found">'.Yii::t("app", "Check all your inputs").'</div>';
			}
		?>    	    
    	<div class="clearfix"></div>
    </div>  
</div>
