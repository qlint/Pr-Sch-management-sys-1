<style type="text/css">
.table .top{ width:75px;}
</style>

<?php Yii::app()->clientScript->registerCoreScript('jquery');?>

<div id="parent_Sect">
	<?php $this->renderPartial('leftside');?> 
	<div class="right_col"  id="req_res123">
    <!--contentArea starts Here--> 
     <div id="parent_rightSect">
        <div class="parentright_innercon">
        <div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-calendar-o"></i><?php echo Yii::t("app", 'Time Table');?><span><?php echo Yii::t("app", 'View your Time Table here');?> </span></h2>
        </div>
        <div class="col-lg-2">
        
                </div>
    
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t("app", 'You are here:');?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t("app", 'Time Table');?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>
    <div class="contentpanel">
    
<div class="panel-heading">
            <h3 class="panel-title"><?php echo Yii::t('app','My Class Time Table'); ?></h3>           
        	</div>
            <div class="people-item">
             <?php $this->renderPartial('/default/employee_tab');?>
             <?php
			 
			 	$employee=Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
				
				$employee_sub = EmployeesSubjects::model()->findByAttributes(array('employee_id'=>$employee->id));
				$subject_details = Subjects::model()->findByAttributes(array('id'=>$employee_sub->subject_id,'batch_id'=>$_REQUEST['id']));
				$timing = ClassTimings::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id'])); // Display pdf button only if there is class timings.
				if($timing!=NULL)
				{
					echo CHtml::link(Yii::t('app','Generate PDF'), array('Default/pdf','id'=>$_REQUEST['id']),array('class'=>'btn btn-danger pull-right','target'=>'_blank'));
				} 
			 ?>
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
			 
			  	$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
			  
			   //If $list_flag = 2, list of batches will be displayed. If $list_flag = 1, time table will be displayed. If $list_flag = 0, employee not assigned to any class.
			   if($_REQUEST['id']!=NULL){
						$list_flag=1;  
				 }
				else{
					
					// Get unique batch ID
					$criteria=new CDbCriteria;
					$criteria->select= 'batch_id';
					$criteria->distinct = true;
					// $criteria->order = 'batch_id ASC'; Uncomment if ID should be retrieved in ascending order
					$criteria->condition='employee_id=:emp_id';
					$criteria->params=array(':emp_id'=>$employee->id);
					$timetable_entries = TimetableEntries::model()->findAll($criteria);
				
					$batches_ids = array();
					if($timetable_entries){
						foreach($timetable_entries as $timetable_entrie){
							if(!in_array($timetable_entrie->batch_id,$batches_ids)){
								//check curent batch  
										//$today	=	date('Y-m-d');
										$b_id	=	$timetable_entrie->batch_id;
										$criteria1=new CDbCriteria;         
										$criteria1->condition = "`id`= '$b_id' AND `is_active`=1 AND `is_deleted`=0 AND employee_id=:emp_id";	
										$criteria1->params=array(':emp_id'=>$employee->id);					
										$batch		 =	Batches::model()->findAll($criteria1);
										if($batch!=NULL)							
											$batches_ids[] = $timetable_entrie->batch_id;
							}
						}
					}
					
			//Check whether the teacher have any substitution in any batch		
					$is_any_substitutes = TeacherSubstitution::model()->findAllByAttributes(array('substitute_emp_id'=>$employee->id));
					if($is_any_substitutes){
						foreach($is_any_substitutes as $is_any_substitute){
							$is_in_timetable = TimetableEntries::model()->findByAttributes(array('id'=>$is_any_substitute->time_table_entry_id));
							if($is_in_timetable){
								if(in_array($is_any_substitute->date_leave,$date_between)){																						
									if(!in_array($is_any_substitute->batch,$batches_ids)){	
										//check curent batch  
										$today	=	date('Y-m-d');
										$b_id	=	$is_any_substitute->batch; 
										$criteria2=new CDbCriteria;        
										$criteria2->condition = "`start_date` <= '$today' AND `end_date` >= '$today' AND `id`= '$b_id' AND `is_active`=1 AND `is_deleted`=0";						
										$batch		 =	Batches::model()->findAll($criteria2);
										if($batch!=NULL)	
											$batches_ids[] = $is_any_substitute->batch; 
									}
								}
							}
						}
					}
					
					if(count($batches_ids) > 1){ // List of batches is needed
						$list_flag = 2;	
					}
					elseif(count($batches_ids) <= 0){ // If not teaching in any batch
						$list_flag = 0;
					}
					else{ // If only one batch is found
						$list_flag = 1;
						$_REQUEST['id'] = $batches_ids[0];	
						
					}
					
				}
				
				if($list_flag == 0){ // If not teaching in any batch
					 ?>
                <div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;margin-top:60px;">
                    <div class="y_bx_head">
                       <?php echo Yii::t('app','No period is assigned to you now!'); ?>
                    </div>      
       			</div>
				<?php
				}
				if($list_flag==2){ // If list of batches is to be shown
						
					?><div class="cleararea"></div>
                    	<div class="table-responsive">
                        	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered mb30">
                            	<thead>
                          			<tr class="pdtab-h">
                                    <th><?php echo Yii::t('app','Course');?></th>
                                        <th><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name');?></th>
                                        <?php  $sem_enabled	=	Configurations::model()->isSemesterEnabled();
													if($sem_enabled	== 1){?>
                                                    <th><?php echo Yii::t('app','Semester');?></th>
											<?php }?>
                                        <th><?php echo Yii::t('app','Class Teacher');?></th>
                                        <th><?php echo Yii::t('app','Start Date');?></th>
                                        <th><?php echo Yii::t('app','End Date');?></th>
                         			</tr>
                                    </thead>
                                    <?php 
									//var_dump($batches_ids);exit;
                          			for($i = 0; $i <count($batches_ids); $i++)
                                	{										
										$batch		 =	Batches::model()->findByAttributes(array('id'=>$batches_ids[$i],'is_active'=>1,'is_deleted'=>0));
										$course_name =  Courses::model()->findByAttributes(array('id'=>$batch->course_id));
										echo '<tr id="batchrow'.$batch->id.'">';
										echo '<td>'.CHtml::link($course_name->course_name, array('/teachersportal/default/employeeClasstimetable','id'=>$batch->id)).'</td>';
										echo '<td>'.CHtml::link($batch->name, array('/teachersportal/default/employeeClasstimetable','id'=>$batch->id)).'</td>';
										$settings=UserSettings::model()->findByAttributes(array('id'=>1));
											if($settings!=NULL)
											{	
												$date1=date($settings->displaydate,strtotime($batch->start_date));
												$date2=date($settings->displaydate,strtotime($batch->end_date));
			
											}
											//semester check
										$sem_enabled_course = Configurations::model()->isSemesterEnabledForCourse($batch->course_id);
										if($sem_enabled==1 and $sem_enabled_course == 1){
											$semester = Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
											echo '<td>'.$semester->name.'</td>';
											
										}
										else{
											echo '<td>'.'-'.'</td>';
										}
										//end semester
										$teacher = Employees::model()->findByAttributes(array('id'=>$batch->employee_id));					
										echo '<td>';
										if($teacher){
											echo Employees::model()->getTeachername($teacher->id);
										}
										else{
											echo '-';
										}
										echo '</td>';					
										echo '<td>'.$date1.'</td>';
										echo '<td>'.$date2.'</td>';
										echo '</tr>';
									}
									?>
                            </table>
						</div>
                    <?php
					} // End list of batches	
					if($list_flag==1 or isset($_REQUEST['id'])){
						 $batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
                         $course_name = Courses::model()->findByAttributes(array('id'=>$batch_name->course_id)); //If batch ID is set or no list of batches
					 ?>
                     <table  width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="width:130px;"><?php echo Yii::t('app','Course');?></td>
                            <td style="width:10px;">:</td>
                            <td style="width:550px;"><?php echo ucfirst($course_name->course_name); ?></td>
                         </tr>
                         <tr>
                            <td style="width:130px;"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></td>
                            <td style="width:10px;">:</td>
                            <td style="width:550px;"><?php echo ucfirst($batch_name->name);?></td>
                          </tr>
                         <?php
							//semester check
								$sem_enabled 		= Configurations::model()->isSemesterEnabled();
								$sem_enabled_course = Configurations::model()->isSemesterEnabledForCourse($batch_name->course_id);
								if($sem_enabled == 1 and $sem_enabled_course == 1 and $batch_name->semester_id != NULL){
									$semester = Semester::model()->findByAttributes(array('id'=>$batch_name->semester_id));
									?>
                                        <tr>
                                            <td style="width:130px;"><?php echo Yii::t('app','Semester');?></td>
                                            <td style="width:10px;">:</td>
                                            <td style="width:550px;"><?php echo ucfirst($semester->name); ?></td>
                                        </tr>
                                        
                        	<?php } ?>
                           
                    </table>
                    
							<div class="clearfix"></div>
                            <div class="timetable_div">
                            	<?php $weekdays=Weekdays::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id'])); // Fetching weekdays
								if(count($weekdays)==0)
								{
									$weekdays=Weekdays::model()->findAll("batch_id IS NULL"); // If weekdays are not set for a batch,fetch the default weekdays
								}
								$criteria=new CDbCriteria;
								$criteria->condition = "batch_id=:x";
								$criteria->params = array(':x'=>$_REQUEST['id']);
								$criteria->order = "STR_TO_DATE(start_time, '%h:%i %p')";
								$timing = ClassTimings::model()->findAll($criteria); // Fetching Class timings
								
								$count_timing = count($timing);
								if($timing!=NULL) // If class timing is set
								{
								?>
                                <div class="table-responsive" style="overflow-x: scroll">
								<table border="0" align="center" width="90%" id="table" cellspacing="0" class="table table-bordered mb30">
									<tbody>
                                    	<tr> <!-- timetable header tr -->
                                          <td class="loader">&nbsp;</td><!--timetable_td_tl -->
                                          
                                          <?php 
											foreach($timing as $timing_1)
											{
												
												$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
												if($settings!=NULL)
												{	
													$time1=date($settings->timeformat,strtotime($timing_1->start_time));
													$time2=date($settings->timeformat,strtotime($timing_1->end_time));
													
						
												}
											echo '<td style="text-align:center;"class="td">'.$time1.' - '.$time2.'</div></td>';	
											//echo '<td class="td"><div class="top">'.$timing_1->start_time.' - '.$timing_1->end_time.'</div></td>';	
											}
                                           ?>
										</tr> <!-- End timetable header tr -->
                                       
									<?php if($weekdays[0]['weekday']!=0) // If sunday is working
                                   		 { ?>
                                         <tr>
                                            <td class="td" style="text-align:center"><div class="name"><?php echo 'SUN';?></div></td>
                                            
                                             <?php
                                                  for($i=0;$i<$count_timing;$i++)
                                                  {
													  
                                                    echo '<td class="td">
															<div  onclick="" style="position: relative; ">
															  <div class="tt-subject">
																<div class="subject">'; ?>
                                                <?php
													/*echo "weekday".$weekdays[0]['weekday'];
													echo "class timing".$timing[$i]['id'];
													echo "employee".$employee->id;exit;*/
                                   					 $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[0]['weekday'],'class_timing_id'=>$timing[$i]['id'],'employee_id'=>$employee->id));
                                                    if(count($set)==0)
                                                    {
                                                        $is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
														if($is_break!=NULL){	
															echo Yii::t('app','Break');
														}else{
															$criteria = new CDbCriteria;		
															$criteria->join = 'LEFT JOIN teacher_substitution t1 ON t.id = t1.time_table_entry_id'; 
															$criteria->condition = 't1.date_leave=:date_leave and t1.substitute_emp_id=:substitute_emp_id and t1.batch=:batch_id and t.weekday_id=:weekday_id and t.class_timing_id=:class_timing_id';
															$criteria->params = array(':date_leave'=>$date_between[0],':substitute_emp_id'=>$employee->id,':batch_id'=>$_REQUEST['id'],':weekday_id'=>$weekdays[0]['weekday'],':class_timing_id'=>$timing[$i]['id']);
															$is_substitution_exist = TimetableEntries::model()->find($criteria);
															if($is_substitution_exist){																	
																$time_sub = '';
																$elec_group = '';
																if($is_substitution_exist->is_elective==0){	
																	$time_sub = Subjects::model()->findByAttributes(array('id'=>$is_substitution_exist->subject_id));
																}else if($is_substitution_exist->is_elective==2){
																	$time_sub = Electives::model()->findByAttributes(array('id'=>$is_substitution_exist->subject_id));
																	$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
																}															
																echo '<b>'.ucfirst($elec_group->name).'</b><br>';
																echo '<b>'.ucfirst($time_sub->name).'</b><br>';
																echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';
															}
														}
															
                                                    }
                                                    else
                                                    {
														if($set->is_elective==0)
														{	
															$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
															$elec_group="";
														}
														else if($set->is_elective==2)
														{
															$time_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
															$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
															
														}
														//if($time_sub!=NULL){echo $time_sub->name.'<br>';}
														$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
														if($time_emp!=NULL)
														{
															$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));
																														
															if(!$is_substitute and !in_array($is_substitute->date_leave,$date_between))
															{
																echo '<b>'.ucfirst($elec_group->name).'</b><br>';
															if($set->is_elective==0){
																echo '<b>'.ucfirst($time_sub->name).'</b><br>';
															}
																echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';
															}else{
																echo 'Leave';
															}																														
														}
														
														
                                                    }
                                             		echo 	'</div> 
                                                          </div>
                                                        </div>
                                                        <div id="jobDialog'.$timing[$i]['id'].$weekdays[0]['weekday'].'"></div>
                                                      </td>';  
                                                  }
                                                  ?>
                                          </tr>
									<?php } // End If sunday is working 
										 if($weekdays[1]['weekday']!=0) // If monday is working.
										{ ?>
                                        <tr>
                                            <td class="td" style="text-align:center"><div class="name"><?php echo 'MON';?></div></td>
                                           
                                                 <?php
                                                  for($i=0;$i<$count_timing;$i++)
                                                  {
                                                    echo ' <td class="td">
                                                            <div  onclick="" style="position: relative; ">
                                                              <div class="tt-subject">
                                                                <div class="subject">';
																
                                            $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[1]['weekday'],'class_timing_id'=>$timing[$i]['id'],'employee_id'=>$employee->id)); 
											
                                                    if(count($set)==0)
                                                    {	
                                                        $is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
														if($is_break!=NULL)
														{	
															echo Yii::t('app','Break');	
														}else{
															$criteria = new CDbCriteria;		
															$criteria->join = 'LEFT JOIN teacher_substitution t1 ON t.id = t1.time_table_entry_id'; 
															$criteria->condition = 't1.date_leave=:date_leave and t1.substitute_emp_id=:substitute_emp_id and t1.batch=:batch_id and t.weekday_id=:weekday_id and t.class_timing_id=:class_timing_id';
															$criteria->params = array(':date_leave'=>$date_between[1],':substitute_emp_id'=>$employee->id,':batch_id'=>$_REQUEST['id'],':weekday_id'=>$weekdays[1]['weekday'],':class_timing_id'=>$timing[$i]['id']);
															$is_substitution_exist = TimetableEntries::model()->find($criteria);
															if($is_substitution_exist){																
																$time_sub = '';
																$elec_group = '';
																if($is_substitution_exist->is_elective==0){	
																	$time_sub = Subjects::model()->findByAttributes(array('id'=>$is_substitution_exist->subject_id));
																}else if($is_substitution_exist->is_elective==2){
																	$time_sub = Electives::model()->findByAttributes(array('id'=>$is_substitution_exist->subject_id));
																	$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
																}															
																echo '<b>'.ucfirst($elec_group->name).'</b><br>';
																echo '<b>'.ucfirst($time_sub->name).'</b><br>';
																echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';
															}
														}		
                                                    }
                                                    elseif(count($set)>0)
                                                    {	
														if($set->is_elective==0)
														{	
															$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
															$elec_group="";
														}
														else if($set->is_elective==2)
														{
															$time_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
															$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
														}
														$time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
														//if($time_sub!=NULL){echo $time_sub->name.'<br>';}
														if($time_emp!=NULL)
														{
															$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));																		
															if(!$is_substitute and !in_array($is_substitute->date_leave,$date_between))
															{
																echo '<b>'.ucfirst($elec_group->name).'</b><br>';
																if($set->is_elective==0){
																	echo '<b>'.ucfirst($time_sub->name).'</b><br>';
																}
																echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';
															}else{
																echo 'Leave';
															}															
														}
														echo CHtml::link('',array('timetableEntries/remove','id'=>$set->id,'batch_id'=>$_REQUEST['id']),array('confirm'=>Yii::t("app", 'Are you sure?'),'class'=>'delete'));
                                                    }
													
                                    
                                                            echo '</div>
                                                              </div>
                                                            </div>
                                                            <div id="jobDialog'.$timing[$i]['id'].$weekdays[1]['weekday'].'"></div>
                                                          </td>';  
                                                 }
                                                ?>
                                              <!--timetable_td -->
                                          </tr><!--timetable_tr -->
									<?php } // End If monday is working.
										if($weekdays[2]['weekday']!=0) // If tuesday is working.
										{ ?>
                                        <tr>
                                            <td class="td" style="text-align:center"><div class="name"><?php echo 'TUE';?></div></td>
                                           
                                             <?php
                                                  for($i=0;$i<$count_timing;$i++)
                                                  {
                                                    echo ' <td class="td">
                                                            <div  onclick="" style="position: relative; ">
                                                              <div class="tt-subject">
                                                                <div class="subject">';
                                                                $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[2]['weekday'],'class_timing_id'=>$timing[$i]['id']));
                                                    if(count($set)==0)
                                                    {	
                                                        $is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
														if($is_break!=NULL)
														{	
															echo Yii::t('app','Break');	
														}else{
															$criteria = new CDbCriteria;		
															$criteria->join = 'LEFT JOIN teacher_substitution t1 ON t.id = t1.time_table_entry_id'; 
															$criteria->condition = 't1.date_leave=:date_leave and t1.substitute_emp_id=:substitute_emp_id and t1.batch=:batch_id and t.weekday_id=:weekday_id and t.class_timing_id=:class_timing_id';
															$criteria->params = array(':date_leave'=>$date_between[2],':substitute_emp_id'=>$employee->id,':batch_id'=>$_REQUEST['id'],':weekday_id'=>$weekdays[2]['weekday'],':class_timing_id'=>$timing[$i]['id']);
															$is_substitution_exist = TimetableEntries::model()->find($criteria);
															if($is_substitution_exist){																	
																$time_sub = '';
																$elec_group = '';
																if($is_substitution_exist->is_elective==0){	
																	$time_sub = Subjects::model()->findByAttributes(array('id'=>$is_substitution_exist->subject_id));
																}else if($is_substitution_exist->is_elective==2){
																	$time_sub = Electives::model()->findByAttributes(array('id'=>$is_substitution_exist->subject_id));
																	$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
																}															
																echo '<b>'.ucfirst($elec_group->name).'</b><br>';
																echo '<b>'.ucfirst($time_sub->name).'</b><br>';
																echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';
															}
														}	
                                                    }
                                                    else
                                                    {
                                                    if($set->is_elective==0)
													{	
														$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
														$elec_group="";
													}
													else if($set->is_elective==2)
													{
														$time_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
														$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
													}
                                                    //if($time_sub!=NULL){echo $time_sub->name.'<br>';}
                                                    $time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
													
                                                    if($time_emp!=NULL)
													{
														$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));																		
														if(!$is_substitute and !in_array($is_substitute->date_leave,$date_between))
														{
															echo '<b>'.ucfirst($elec_group->name).'</b><br>';
															if($set->is_elective==0){
																	echo '<b>'.ucfirst($time_sub->name).'</b><br>';
																}
															echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';
														}else{
															echo 'Leave';
														}
													}
                                                    echo CHtml::link('',array('timetableEntries/remove','id'=>$set->id,'batch_id'=>$_REQUEST['id']),array('confirm'=>Yii::t("app",'Are you sure?'),'class'=>'delete'));
                                                    }
                                    
                                                                
                                                            echo	'</div>
                                                                
                                                              </div>
                                                            </div>
                                                            <div id="jobDialog'.$timing[$i]['id'].$weekdays[2]['weekday'].'"></div>
                                                          </td>';  
                                                 }
                                                ?><!--timetable_td -->
                                            
                                          </tr>
									<?php } // End If tuesday is working.
										if($weekdays[3]['weekday']!=0) // If wednesday is working.
	  									{ ?>
                                        <tr>
                                            <td class="td" style="text-align:center"><div class="name"><?php echo 'WED';?></div></td>
                                            
                                             <?php
                                                  for($i=0;$i<$count_timing;$i++)
                                                  {
                                                    echo ' <td class="td">
                                                            <div  onclick="" style="position: relative; ">
                                                              <div class="tt-subject">
                                                                <div class="subject">';
                                                                $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[3]['weekday'],'class_timing_id'=>$timing[$i]['id'],'employee_id'=>$employee->id)); 			
                                                    if(count($set)==0)
                                                    {	
                                                        $is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
														if($is_break!=NULL)
														{	
															echo Yii::t('app','Break');	
														}else{
															$criteria = new CDbCriteria;		
															$criteria->join = 'LEFT JOIN teacher_substitution t1 ON t.id = t1.time_table_entry_id'; 
															$criteria->condition = 't1.date_leave=:date_leave and t1.substitute_emp_id=:substitute_emp_id and t1.batch=:batch_id and t.weekday_id=:weekday_id and t.class_timing_id=:class_timing_id';
															$criteria->params = array(':date_leave'=>$date_between[3],':substitute_emp_id'=>$employee->id,':batch_id'=>$_REQUEST['id'],':weekday_id'=>$weekdays[3]['weekday'],':class_timing_id'=>$timing[$i]['id']);
															$is_substitution_exist = TimetableEntries::model()->find($criteria);
															if($is_substitution_exist){																	
																$time_sub = '';
																$elec_group = '';
																if($is_substitution_exist->is_elective==0){	
																	$time_sub = Subjects::model()->findByAttributes(array('id'=>$is_substitution_exist->subject_id));
																}else if($is_substitution_exist->is_elective==2){
																	$time_sub = Electives::model()->findByAttributes(array('id'=>$is_substitution_exist->subject_id));
																	$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
																}															
																echo '<b>'.ucfirst($elec_group->name).'</b><br>';
																echo '<b>'.ucfirst($time_sub->name).'</b><br>';
																echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';
															}
														}
                                                    }
                                                    else
                                                    {	
                                                    if($set->is_elective==0)
													{	
														$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
														$elec_group="";
													}
													else if($set->is_elective==2)
													{
														$time_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
														$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
													}
                                                    //if($time_sub!=NULL){echo $time_sub->name.'<br>';}
                                                    $time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
                                                    if($time_emp!=NULL)
													{
														$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));																		
														if(!$is_substitute and !in_array($is_substitute->date_leave,$date_between))
														{
															echo '<b>'.ucfirst($elec_group->name).'</b><br>';
															if($set->is_elective==0){
																	echo '<b>'.ucfirst($time_sub->name).'</b><br>';
																}
															echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';
														}else{
															echo 'Leave';
														}
													}
                                                    echo CHtml::link('',array('timetableEntries/remove','id'=>$set->id,'batch_id'=>$_REQUEST['id']),array('confirm'=>Yii::t("app",'Are you sure?'),'class'=>'delete'));	
                                                    }
                                                                echo '</div>
                                                                
                                                              </div>
                                                            </div>
                                                            <div id="jobDialog'.$timing[$i]['id'].$weekdays[3]['weekday'].'"></div>
                                                          </td>';  
                                                 }
                                                ?><!--timetable_td -->
                                            
                                          </tr>
									<?php } // End if wednesday is working.
										 if($weekdays[4]['weekday']!=0) // If thursday is working
	 									 {  ?>
                                         <tr>
                                            <td class="td" style="text-align:center"><div class="name"><?php echo 'THU';?></div></td>
                                           
                                              <?php
                                                  for($i=0;$i<$count_timing;$i++)
                                                  {
                                                    echo ' <td class="td">
                                                            <div  onclick="" style="position: relative; ">
                                                              <div class="tt-subject">
                                                                <div class="subject">';
                                                    $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[4]['weekday'],'class_timing_id'=>$timing[$i]['id'],'employee_id'=>$employee->id)); 			
                                                    if(count($set)==0)
                                                    {	
                                                        $is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
														if($is_break!=NULL)
														{	
															echo Yii::t('app','Break');	
														}else{
															$criteria = new CDbCriteria;		
															$criteria->join = 'LEFT JOIN teacher_substitution t1 ON t.id = t1.time_table_entry_id'; 
															$criteria->condition = 't1.date_leave=:date_leave and t1.substitute_emp_id=:substitute_emp_id and t1.batch=:batch_id and t.weekday_id=:weekday_id and t.class_timing_id=:class_timing_id';
															$criteria->params = array(':date_leave'=>$date_between[4],':substitute_emp_id'=>$employee->id,':batch_id'=>$_REQUEST['id'],':weekday_id'=>$weekdays[4]['weekday'],':class_timing_id'=>$timing[$i]['id']);
															$is_substitution_exist = TimetableEntries::model()->find($criteria);
															if($is_substitution_exist){																	
																$time_sub = '';
																$elec_group = '';
																if($is_substitution_exist->is_elective==0){	
																	$time_sub = Subjects::model()->findByAttributes(array('id'=>$is_substitution_exist->subject_id));
																}else if($is_substitution_exist->is_elective==2){
																	$time_sub = Electives::model()->findByAttributes(array('id'=>$is_substitution_exist->subject_id));
																	$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
																}															
																echo '<b>'.ucfirst($elec_group->name).'</b><br>';
																echo '<b>'.ucfirst($time_sub->name).'</b><br>';
																echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';
															}
														}	
                                                    }
                                                    else
                                                    {
                                                    if($set->is_elective==0)
													{	
														$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
														$elec_group="";
													}
													else if($set->is_elective==2)
													{
														$time_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
														$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
													}
                                                    //if($time_sub!=NULL){echo $time_sub->name.'<br>';}
                                                    $time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
                                                    if($time_emp!=NULL)
													{
														$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));	
																														
														if(!$is_substitute and !in_array($is_substitute->date_leave,$date_between)){
															echo '<b>'.ucfirst($elec_group->name).'</b><br>';
															if($set->is_elective==0){
																	echo '<b>'.ucfirst($time_sub->name).'</b><br>';
																}
															echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';
														}else{
															echo 'Leave';
														}
													}
                                                    echo CHtml::link('',array('timetableEntries/remove','id'=>$set->id,'batch_id'=>$_REQUEST['id']),array('confirm'=>Yii::t("app",'Are you sure?'),'class'=>'delete'));
                                                    }
                                                                
                                                            echo '</div>
                                                                
                                                              </div>
                                                            </div>
                                                            <div id="jobDialog'.$timing[$i]['id'].$weekdays[4]['weekday'].'"></div>
                                                          </td>';  
                                                 }
                                                ?><!--timetable_td -->
                                            
                                          </tr>
									<?php } // End If thursday is working
									if($weekdays[5]['weekday']!=0) // If friday is working
									{ ?>
                                    <tr>
                                        <td class="td" style="text-align:center"><div class="name"><?php echo 'FRI';?></div></td>
                                        
                                         <?php
                                              for($i=0;$i<$count_timing;$i++)
                                              {
                                                echo ' <td class="td">
                                                        <div  onclick="" style="position: relative; ">
                                                          <div class="tt-subject">
                                                            <div class="subject">';
                                                $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[5]['weekday'],'class_timing_id'=>$timing[$i]['id'],'employee_id'=>$employee->id)); 			
                                                if(count($set)==0)
												{	
													$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
													if($is_break!=NULL)
													{	
														echo Yii::t('app','Break');	
													}else{
															$criteria = new CDbCriteria;		
															$criteria->join = 'LEFT JOIN teacher_substitution t1 ON t.id = t1.time_table_entry_id'; 
															$criteria->condition = 't1.date_leave=:date_leave and t1.substitute_emp_id=:substitute_emp_id and t1.batch=:batch_id and t.weekday_id=:weekday_id and t.class_timing_id=:class_timing_id';
															$criteria->params = array(':date_leave'=>$date_between[5],':substitute_emp_id'=>$employee->id,':batch_id'=>$_REQUEST['id'],':weekday_id'=>$weekdays[5]['weekday'],':class_timing_id'=>$timing[$i]['id']);
															$is_substitution_exist = TimetableEntries::model()->find($criteria);
															if($is_substitution_exist){																	
																$time_sub = '';
																$elec_group = '';
																if($is_substitution_exist->is_elective==0){	
																	$time_sub = Subjects::model()->findByAttributes(array('id'=>$is_substitution_exist->subject_id));
																}else if($is_substitution_exist->is_elective==2){
																	$time_sub = Electives::model()->findByAttributes(array('id'=>$is_substitution_exist->subject_id));
																	$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
																}															
																echo '<b>'.ucfirst($elec_group->name).'</b><br>';
																echo '<b>'.ucfirst($time_sub->name).'</b><br>';
																echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';
															}
														}	
												}
												else
												{	
                                                if($set->is_elective==0)
												{	
													$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
													$elec_group="";
												}
												else if($set->is_elective==2)
												{
													$time_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
													$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
												}
                                                //if($time_sub!=NULL){echo $time_sub->name.'<br>';}
                                                $time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
                                                if($time_emp!=NULL)
												{
													$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));																		
													if(!$is_substitute and !in_array($is_substitute->date_leave,$date_between))
													{
														echo '<b>'.ucfirst($elec_group->name).'</b><br>';
														if($set->is_elective==0){
																	echo '<b>'.ucfirst($time_sub->name).'</b><br>';
																}
														echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';
													}else{
														echo 'Leave';
													}
													
												}
                                                echo CHtml::link('',array('timetableEntries/remove','id'=>$set->id,'batch_id'=>$_REQUEST['id']),array('confirm'=>Yii::t("app",'Are you sure?'),'class'=>'delete'));
                                                }
                                                            echo '</div>
                                                            
                                                          </div>
                                                        </div>
                                                        <div id="jobDialog'.$timing[$i]['id'].$weekdays[5]['weekday'].'"></div>
                                                      </td>';  
                                             }
                                            ?><!--timetable_td -->
                                        
                                      </tr>
								<?php } // End if friday is working
								if($weekdays[6]['weekday']!=0) // If Saturday is working
	  							{ ?>
                                <tr>
                                    <td class="td" style="text-align:center"><div class="name"><?php echo 'SAT';?></div></td>
                                   
                                      <?php
                                          for($i=0;$i<$count_timing;$i++)
                                          {
                                            echo ' <td class="td">
                                                    <div  onclick="" style="position: relative; ">
                                                      <div class="tt-subject">
                                                        <div class="subject">';
                                                        $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[6]['weekday'],'class_timing_id'=>$timing[$i]['id'],'employee_id'=>$employee->id)); 			
                                            if(count($set)==0)
											{	
												$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
												if($is_break!=NULL)
												{	
													echo Yii::t('app','Break');	
												}else{
													$criteria = new CDbCriteria;		
													$criteria->join = 'LEFT JOIN teacher_substitution t1 ON t.id = t1.time_table_entry_id'; 
													$criteria->condition = 't1.date_leave=:date_leave and t1.substitute_emp_id=:substitute_emp_id and t1.batch=:batch_id and t.weekday_id=:weekday_id and t.class_timing_id=:class_timing_id';
													$criteria->params = array(':date_leave'=>$date_between[6],':substitute_emp_id'=>$employee->id,':batch_id'=>$_REQUEST['id'],':weekday_id'=>$weekdays[6]['weekday'],':class_timing_id'=>$timing[$i]['id']);
													$is_substitution_exist = TimetableEntries::model()->find($criteria);
													if($is_substitution_exist){																	
														$time_sub = '';
														$elec_group = '';
														if($is_substitution_exist->is_elective==0){	
															$time_sub = Subjects::model()->findByAttributes(array('id'=>$is_substitution_exist->subject_id));
														}else if($is_substitution_exist->is_elective==2){
															$time_sub = Electives::model()->findByAttributes(array('id'=>$is_substitution_exist->subject_id));
															$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
														}															
														echo '<b>'.ucfirst($elec_group->name).'</b><br>';
														echo '<b>'.ucfirst($time_sub->name).'</b><br>';
														echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';
													}
												}	
											}
											else
											{	
                                            if($set->is_elective==0)
											{	
												$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
												$elec_group="";
											}
											else if($set->is_elective==2)
											{
												$time_sub = Electives::model()->findByAttributes(array('id'=>$set->subject_id));
												$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
											}
                                            //if($time_sub!=NULL){echo $time_sub->name.'<br>';}
                                            $time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
                                            if($time_emp!=NULL)
											{
												$is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));																		
												if(!$is_substitute and !in_array($is_substitute->date_leave,$date_between))
												{
													echo '<b>'.ucfirst($elec_group->name).'</b><br>';
													if($set->is_elective==0){
																	echo '<b>'.ucfirst($time_sub->name).'</b><br>';
																}
													echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';
												}else{
													echo 'Leave';
												}
											}
                                            echo CHtml::link('',array('timetableEntries/remove','id'=>$set->id,'batch_id'=>$_REQUEST['id']),array('confirm'=>Yii::t("app",'Are you sure?'),'class'=>'delete'));
                                            }
                                                        echo '</div>
                                                        
                                                      </div>
                                                    </div>
                                                    <div id="jobDialog'.$timing[$i]['id'].$weekdays[6]['weekday'].'"></div>
                                                  </td>';  
                                         }
                                        ?><!--timetable_td -->
                                    
                                  </tr>
								<?php } // End if Saturday is working
								?>
									</tbody>
								</table>
								</div>
                                <?php } // End if timing is set
								else // If no class timings set
								 {
									 echo '<span style="padding-left:230px;"><i>'.Yii::t('app','No timetable set for') .'<b>'.$course_name->course_name.'/'.$batch_name->name.'</b>'.Yii::t('app','batch').'</i></span>';
									 
								 }
								?>
                        	</div> <!-- End timetable div (timetable_div)-->
						</div> <!-- End entire div (atdn_div) -->

				
				<?php 
					}
				?>
                
			</div>
		</div>
	</div>
	 <div class="clear"></div>
</div>
