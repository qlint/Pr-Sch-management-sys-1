<style type="text/css">
.edit_bttns ul li {
    float: right;
    list-style: outside none none;
    margin: 0 0 15px;
}
</style><?php $this->renderPartial('/default/leftside');?> 
 <div class="pageheader">
      <h2><i class="fa fa-list-alt"></i> <?php echo Yii::t('app', 'My Course');?> <span><?php echo Yii::t('app', 'View courses here');?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app', 'You are here:');?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
         <li class="active"><?php echo Yii::t('app', 'Course');?></li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
<div class="col-sm-9 col-lg-12">
<div class="panel panel-default">
         <?php $this->renderPartial('changebatch');?>
<div class="panel-body">
 
            <?php $this->renderPartial('batch');?>
            <div class="edit_bttns" style="top:100px; right:25px">
                <ul>
                    <li>
                    <?php //echo CHtml::link('<span>'.Yii::t('teachersportal','My Courses').'</span>', array('/teachersportal/course'),array('class'=>'addbttn last'));?>
                    </li>
                </ul>
            </div>
            
            
        	<div class="people-item">
            
             <?php //$this->renderPartial('/default/employee_tab');?>
             <div>
             <div class="edit_bttns" style="z-index:10000;">
    <ul>
   <li><span>
  			<?php
			$employee=Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
			$is_classteacher = Batches::model()->findAllByAttributes(array('employee_id'=>$employee->id)); 
			if(Yii::app()->controller->action->id=='studenttimetables')
  			{ 
     			echo CHtml::link(Yii::t('app', 'View My Timetable'),array('timetables','id'=>$_REQUEST['id'])); 
			}?>
		</span></li>
        
     <li><span>
        	<?php if($is_classteacher!=NULL){
					if(Yii::app()->controller->action->id=='timetables')
					{ 
						echo CHtml::link(Yii::t('app', 'View Class Timetable'),array('studenttimetable','id'=>$_REQUEST['id'])); 
					}
                } ?>
        </span></li>
    </ul>
    </div>
         
            
            
              <?php 
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
                 		<div class="table-responsive">
                        <table width="80%" border="0" cellspacing="0" cellpadding="0" class="table mb30">
                          
                          <thead>
                          <!--class="cbtablebx_topbg"  class="sub_act"-->
                          <tr class="pdtab-h">
                            <th><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name');?></th>
                            <th><?php echo Yii::t('app','Class Teacher');?></th>
                            <th><?php echo Yii::t('app','Start Date');?></th>
                            <th><?php echo Yii::t('app','End Date');?></th>
                           
                          </tr>
                          </thead>
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
                                    echo '<td >';
                                    if($teacher){
                                        echo Employees::model()->getTeachername($teacher->id);
                                    }
                                    else{
                                        echo '-';
                                    }
                                    echo '</td>';					
                                    echo '<td >'.$date1.'</td>';
                                    echo '<td >'.$date2.'</td>';
                                    echo '</tr>';
                                }
                               ?>
                         
                        </table>
                        </div> 
                         
                <?php } // End batch list table
				 if($list_flag==0 or isset($_REQUEST['id'])){ 
				 ?>
					<div class="atdn_div">
                    	<div class="name_div">
							<?php 
                            $batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
                            $course_name = Courses::model()->findByAttributes(array('id'=>$batch_name->course_id));
                            echo '<strong>'.Yii::t('app','Course Name').'</strong>:'.$course_name->course_name.'<br/>'; 	
                            echo '<strong>'.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name').'</strong>:'.$batch_name->name; ?>
                            
                        </div>
                        <br />
                        <div class="timetable_div">
						<?php $weekdays=Weekdays::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id'])); // Fetching weekdays
							if(count($weekdays)==0){
								$weekdays=Weekdays::model()->findAll("batch_id IS NULL"); // If weekdays are not set for a batch,fetch the default weekdays
							}
							$timing = ClassTimings::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id'])); // Fetching Class timings
	  						$count_timing = count($timing);
							if($timing!=NULL) // If class timing is set
							{
							?>
								<div class="table-responsive">
								<table border="0" align="center" width="90%" id="table" cellspacing="0" class="table mb30">
									<tbody>
                                    	<tr> <!-- timetable header tr -->
                                          <th class="loader">&nbsp;</td><!--timetable_td_tl -->
                                          <th class="td-blank"></th>
                                          <?php 
											foreach($timing as $timing_1)
											{
												
												$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
												if($settings!=NULL)
												{	
													$time1=date($settings->timeformat,strtotime($timing_1->start_time));
													$time2=date($settings->timeformat,strtotime($timing_1->end_time));
													
						
												}
											echo '<th class="td"><div class="top">'.$time1.' - '.$time2.'</div></th>';	
											//echo '<td class="td"><div class="top">'.$timing_1->start_time.' - '.$timing_1->end_time.'</div></td>';	
											}
                                           ?>
										</tr> <!-- End timetable header tr -->
                                        
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
                                                    }
                                                    else
                                                    {	
                                                    $time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
                                                    if($time_sub!=NULL){echo $time_sub->name.'<br>';}
                                                    $time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
                                                    if($time_emp!=NULL){echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';}
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
                                            <th class="td"><div class="name"><?php echo 'MON';?></div></th>
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
                                                    if($time_emp!=NULL){echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';}
                                                    echo CHtml::link('',array('timetableEntries/remove','id'=>$set->id,'batch_id'=>$_REQUEST['id']),array('confirm'=>Yii::t('app', 'Are you sure?'),'class'=>'delete'));
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
                                            <th class="td"><div class="name"><?php echo 'TUE';?></div></th>
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
                                                    if($time_emp!=NULL){echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';}
                                                    echo CHtml::link('',array('timetableEntries/remove','id'=>$set->id,'batch_id'=>$_REQUEST['id']),array('confirm'=>Yii::t('app', 'Are you sure?'),'class'=>'delete'));
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
                                            <th class="td"><div class="name"><?php echo 'WED';?></div></th>
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
                                                    if($time_emp!=NULL){echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';}
                                                    echo CHtml::link('',array('timetableEntries/remove','id'=>$set->id,'batch_id'=>$_REQUEST['id']),array('confirm'=>Yii::t('app', 'Are you sure?'),'class'=>'delete'));	
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
                                            <th class="td"><div class="name"><?php echo 'THU';?></div></th>
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
                                                    if($time_emp!=NULL){echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';}
                                                    echo CHtml::link('',array('timetableEntries/remove','id'=>$set->id,'batch_id'=>$_REQUEST['id']),array('confirm'=>Yii::t('app', 'Are you sure?'),'class'=>'delete'));
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
                                        <th class="td"><div class="name"><?php echo 'FRI';?></div></th>
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
														echo Yii::t('teachersportal','Break');	
													}	
												}
												else
												{	
                                                $time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
                                                if($time_sub!=NULL){echo $time_sub->name.'<br>';}
                                                $time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
                                                if($time_emp!=NULL){echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';}
                                                echo CHtml::link('',array('timetableEntries/remove','id'=>$set->id,'batch_id'=>$_REQUEST['id']),array('confirm'=>Yii::t('app', 'Are you sure?'),'class'=>'delete'));
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
                                    <th class="td"><div class="name"><?php echo 'SAT';?></div></th>
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
													echo Yii::t('teachersportal','Break');	
												}	
											}
											else
											{	
                                            $time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
                                            if($time_sub!=NULL){echo $time_sub->name.'<br>';}
                                            $time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
                                            if($time_emp!=NULL){echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';}
                                            echo CHtml::link('',array('timetableEntries/remove','id'=>$set->id,'batch_id'=>$_REQUEST['id']),array('confirm'=>Yii::t('app', 'Are you sure?'),'class'=>'delete'));
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
                                        <?php echo Yii::t('app', 'No timetable set for this batch!'); ?>
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
