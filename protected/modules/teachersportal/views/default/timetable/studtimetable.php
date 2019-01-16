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
    	<h3 class="panel-title"><?php echo Yii::t('app','View Class Time Table'); ?></h3>
    </div>  
    	<div class="people-item">     
            <?php $this->renderPartial('/default/employee_tab');?>
        	
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
			   //If $list_flag = 1, table of batches will be displayed. If $list_flag = 0, attendance table will be displayed.
			   if($_REQUEST['id']!=NULL){
						$list_flag=0;   		
				?>
			   <?php }
				else{
					 $employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
					 $batch=Batches::model()->findAll("employee_id=:x AND is_active=:y AND is_deleted=:z", array(':x'=>$employee->id,':y'=>1,':z'=>0));
					 $course_name = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
					 if(count($batch)>1){
						 $list_flag = 1;
					 }
					 else{
						  $list_flag = 0;
						  $_REQUEST['id'] = $batch[0]->id;							 
					 }
				}
				?>
				<?php if($list_flag==1){ ?>
                <div class="cleararea"></div>
                 		<div class="table-responsive">
                        <table width="80%" border="0" cellspacing="0" cellpadding="0" class="table mb30">
                          <tbody>
                          <!--class="cbtablebx_topbg"  class="sub_act"-->
                          <tr class="pdtab-h">
                            <td align="center"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name');?></td>
                            <td align="center"><?php echo Yii::t('app','Class Teacher');?></td>
                            <td align="center"><?php echo Yii::t('app','Start Date');?></td>
                            <td align="center"><?php echo Yii::t('app','End Date');?></td>
                           
                          </tr>
                          <?php 
                          foreach($batch as $batch_1)
                                {
                                    echo '<tr id="batchrow'.$batch_1->id.'">';
                                    echo '<td style="text-align:left; padding-left:10px; font-weight:bold;">'.CHtml::link($batch_1->name, array('/teachersportal/default/studenttimetable','id'=>$batch_1->id)).'</td>';
                                    $settings=UserSettings::model()->findByAttributes(array('id'=>1));
										if($settings!=NULL)
										{	
											$date1=date($settings->displaydate,strtotime($batch_1->start_date));
											$date2=date($settings->displaydate,strtotime($batch_1->end_date));
		
										}
                                    $teacher = Employees::model()->findByAttributes(array('id'=>$batch_1->employee_id));					
                                    echo '<td align="center">';
                                    if($teacher){
                                        echo Employees::model()->getTeachername($teacher->id);
                                    }
                                    else{
                                        echo '-';
                                    }
                                    echo '</td>';					
                                    echo '<td align="center">'.$date1.'</td>';
                                    echo '<td align="center">'.$date2.'</td>';
                                    echo '</tr>';
                                }
                               ?>
                         </tbody>
                        </table>
                        </div> 
                         
                <?php } // End batch list table
				 if($list_flag==0 or isset($_REQUEST['id'])){ 
				 ?>
					<div class="atdn_div">
                    	<div class="name_div">
                        <br />
							<?php 
                            $batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
                            $course_name = Courses::model()->findByAttributes(array('id'=>$batch_name->course_id));
                            echo Yii::t('app','Course Name:').$course_name->course_name.'<br/>'; 	
                            echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name:').$batch_name->name; ?>
                        </div>
                        <div class="timetable_div">
						<?php $weekdays=Weekdays::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id'])); // Fetching weekdays
							if(count($weekdays)==0){
								$weekdays=Weekdays::model()->findAll("batch_id IS NULL"); // If weekdays are not set for a batch,fetch the default weekdays
							}
							$criteria = new CDbCriteria();
							$criteria->order = "STR_TO_DATE(start_time, '%h:%i %p')";
							$criteria->condition = 'batch_id=:x';
							$criteria->params = array(':x'=>$_REQUEST['id']);       
							$timing = ClassTimings::model()->findAll($criteria); // Fetching Class timings  
							$count_timing = count($timing);
							if($timing!=NULL) // If class timing is set
							{
							?>
                            
								<div class="table-responsive">
								<table border="0" align="center" width="90%" id="table" cellspacing="0" class="table mb30">
									<tbody>
                                    	<tr> <!-- timetable header tr -->
                                          <td class="loader">&nbsp;</td><!--timetable_td_tl -->
                                          <td class="td-blank"></td>
                                          <?php 
											foreach($timing as $timing_1)
											{
												
												$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
												if($settings!=NULL)
												{	
												  
													$time1=date($settings->timeformat,strtotime($timing_1->start_time));
													$time2=date($settings->timeformat,strtotime($timing_1->end_time));
													
																										
												}
											echo '<td class="td"><div class="top">'.$time1.' - '.$time2.'</div></td>';	
											//echo '<td class="td"><div class="top">'.$timing_1->start_time.' - '.$timing_1->end_time.'</div></td>';	
											}
                                           ?>
										</tr> <!-- End timetable header tr -->
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
									<?php if($weekdays[0]['weekday']!=0) // If sunday is working
                                   		 { ?>
                                         <tr>
                                            <td class="td"><div class="name"><?php echo 'SUN';?></div></td>
                                            <td class="td-blank"></td>
                                             <?php
                                                  for($i=0;$i<$count_timing;$i++)
                                                  {
                                                    echo '<td class="td">
															<div  onclick="" style="position: relative; ">
															  <div class="tt-subject">
																<div class="subject">'; ?>
                                                <?php
                                   					 $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[0]['weekday'],'class_timing_id'=>$timing[$i]['id'])); 
													 
                                                    if(count($set)==0)
                                                    {	
                                                        $is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
														if($is_break!=NULL)
														{	
															echo Yii::t('app','Break');	
														}
														else
														{
															$set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[0]['weekday'],'class_timing_id'=>$timing[$i]['id'],'employee_id'=>NULL));
															 $time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
															 if($time_sub->elective_group_id!=0) // Confirm that it is elective
															 {
																 $time_elective_group = ElectiveGroups::model()->findByAttributes(array('id'=>$time_sub->elective_group_id));
																 $time_elective_sub = Electives::model()->findAllByAttributes(array('elective_group_id'=> $time_elective_group->id));
																foreach($time_elective_sub as $elective_sub)
																{
																	$elective_timetable_entry = TimetableEntries::model()->findByAttributes(array('subject_id'=>$elective_sub->id,'is_elective'=>2));
																	
																	//$emp_elective_sub = EmployeeElectiveSubjects::model()->findByAttributes(array('elective_id'=>$elective_sub->id,'employee_id'=>$employee->id));
																	if(count($elective_timetable_entry)==1)
																	{
																		$is_sub = TeacherSubstitution::model()->findByAttributes(array('time_table_entry_id'=>$elective_timetable_entry->id));
																		if($is_sub)
																		{
																			$emp_name = Employees::model()->findByAttributes(array('id'=>$is_sub->substitute_emp_id));
																		}
																		else
																		{
																			$emp_name = Employees::model()->findByAttributes(array('id'=>$elective_timetable_entry->employee_id));
																		}
																		echo $time_elective_group->name.'<br>';
																		echo '('.$elective_sub->name.')<br>';
																		echo '<div class="employee">'.Employees::model()->getTeachername($emp_name->id).'</div>';
																		
																	}
																	else
																	{
																		continue;
																	}
																}
																//echo $time_elective_group->name.'<br>'; 
																 
															 }
														}
														
                                                    }
                                                    else
                                                    {	
														$time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
														if($time_sub!=NULL){echo $time_sub->name.'<br>';}
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
																echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';
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
                                            <td class="td"><div class="name"><?php echo 'MON';?></div></td>
                                            <td class="td-blank"></td>
                                                 <?php
                                                  for($i=0;$i<$count_timing;$i++)
                                                  {
                                                    echo ' <td class="td">
                                                            <div  onclick="" style="position: relative; ">
                                                              <div class="tt-subject">
                                                                <div class="subject">';
                                            $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[1]['weekday'],'class_timing_id'=>$timing[$i]['id'])); 			
                                                    if(count($set)==0)
                                                    {	
                                                        $is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
														if($is_break!=NULL)
														{	
															echo Yii::t('app','Break');	
														}	
                                                    }
                                                    else
                                                    {		
                                                    $time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
                                                    $time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
                                                    if($time_sub!=NULL){echo $time_sub->name.'<br>';}
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
															echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';
														}
													}
                                                    echo CHtml::link('',array('timetableEntries/remove','id'=>$set->id,'batch_id'=>$_REQUEST['id']),array('confirm'=>Yii::t("app",'Are you sure?'),'class'=>'delete'));
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
                                            <td class="td"><div class="name"><?php echo 'TUE';?></div></td>
                                            <td class="td-blank"></td>
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
														}	
                                                    }
                                                    else
                                                    {	
                                                    $time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
                                                    if($time_sub!=NULL){echo $time_sub->name.'<br>';}
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
															echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';
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
                                            <td class="td"><div class="name"><?php echo 'WED';?></div></td>
                                            <td class="td-blank"></td>
                                             <?php
                                                  for($i=0;$i<$count_timing;$i++)
                                                  {
                                                    echo ' <td class="td">
                                                            <div  onclick="" style="position: relative; ">
                                                              <div class="tt-subject">
                                                                <div class="subject">';
                                                                $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[3]['weekday'],'class_timing_id'=>$timing[$i]['id'])); 			
                                                    if(count($set)==0)
                                                    {	
                                                        $is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
														if($is_break!=NULL)
														{	
															echo Yii::t('app','Break');	
														}	
                                                    }
                                                    else
                                                    {	
                                                    $time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
                                                    if($time_sub!=NULL){echo $time_sub->name.'<br>';}
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
															echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';
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
                                            <td class="td"><div class="name"><?php echo 'THU';?></div></td>
                                            <td class="td-blank"></td>
                                              <?php
                                                  for($i=0;$i<$count_timing;$i++)
                                                  {
                                                    echo ' <td class="td">
                                                            <div  onclick="" style="position: relative; ">
                                                              <div class="tt-subject">
                                                                <div class="subject">';
                                                    $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[4]['weekday'],'class_timing_id'=>$timing[$i]['id'])); 			
                                                    if(count($set)==0)
                                                    {	
                                                        $is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
														if($is_break!=NULL)
														{	
															echo Yii::t('app','Break');	
														}	
                                                    }
                                                    else
                                                    {	
                                                    $time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
                                                    if($time_sub!=NULL){echo $time_sub->name.'<br>';}
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
															echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';
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
                                        <td class="td"><div class="name"><?php echo 'FRI';?></div></td>
                                        <td class="td-blank"></td>
                                         <?php
                                              for($i=0;$i<$count_timing;$i++)
                                              {
                                                echo ' <td class="td">
                                                        <div  onclick="" style="position: relative; ">
                                                          <div class="tt-subject">
                                                            <div class="subject">';
                                                $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[5]['weekday'],'class_timing_id'=>$timing[$i]['id'])); 			
                                                if(count($set)==0)
												{	
													$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
													if($is_break!=NULL)
													{	
														echo Yii::t('app','Break');	
													}	
												}
												else
												{	
                                                $time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
                                                if($time_sub!=NULL){echo $time_sub->name.'<br>';}
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
														echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';
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
                                    <td class="td"><div class="name"><?php echo 'SAT';?></div></td>
                                    <td class="td-blank"></td>
                                      <?php
                                          for($i=0;$i<$count_timing;$i++)
                                          {
                                            echo ' <td class="td">
                                                    <div  onclick="" style="position: relative; ">
                                                      <div class="tt-subject">
                                                        <div class="subject">';
                                                        $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday_id'=>$weekdays[6]['weekday'],'class_timing_id'=>$timing[$i]['id'])); 			
                                            if(count($set)==0)
											{	
												$is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
												if($is_break!=NULL)
												{	
													echo Yii::t('app','Break');	
												}	
											}
											else
											{	
                                            $time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
                                            if($time_sub!=NULL){echo $time_sub->name.'<br>';}
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
														echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';
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
								 ?>
								 <div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;">
                                    <div class="y_bx_head">
                                        <?php echo Yii::t('app','No timetable set for this batch!'); ?>
                                    </div>      
                                </div>
							<?php	 
							 }
                        ?>
                        </div> <!-- End timetable div (timetable_div)-->
					</div> <!-- End entire div (atdn_div) -->
				<?php } // End individual timetable
				?>	 
                
			</div>
		</div>
	</div>
	 <div class="clear"></div>
</div>
