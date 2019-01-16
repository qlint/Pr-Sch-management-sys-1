<?php $this->renderPartial('leftside');?>
    <?php
    $student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
    $batches= BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id));      
    $exam = ExamScores::model()->findAllByAttributes(array('student_id'=>$student->id));
    //$electives = ElectiveScores::model()->findAll("student_id=:x", array(':x'=>$student->id));
    $student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentPortal');
    
    if(isset($_REQUEST['bid']) && $_REQUEST['bid']!=NULL)
    {
        $batch_id= $_REQUEST['bid'];
        $ex_group= ExamGroups::model()->findAllByAttributes(array('batch_id'=>$batch_id,'result_published'=>1));                             
    }
    $settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
    if($settings!=NULL)
    {
        $date_format= $settings->displaydate;
        $time_format= $settings->timeformat;
           
    }
    else
    {
            $date_format = 'Y-m-d';	
            $time_format = "h:i A";
    }
    ?>
    <script language="javascript">
function list()
{
	var val=document.getElementById('batch_id').value;
	window.location = "index.php?r=studentportal/default/examTimetable&bid="+val;
}

</script>
    
   <div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-pencil"></i><?php echo Yii::t('app','Exams'); ?><span><?php echo Yii::t('app','View your Exam here'); ?></span></h2>
        </div>
        
    
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t('app','Exams'); ?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>
    
     <div class="contentpanel">
     	<!--<div class="col-sm-9 col-lg-12">-->
        <div>
        	
                         
                         <div class="panel-heading">
                          <!-- panel-btns -->
                          <h3 class="panel-title"><?php echo Yii::t('app','Exam Time Table');?></h3>
                            
                        </div>
                        
                        
                        <div class="people-item">
<div class="button-bg button-bg-none">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
                                    <ul>
                                        <li>
                                            <?php echo CHtml::link('<span>'.Yii::t('app','Back').'</span>',array('/studentportal/default/exams'),array('class'=>'btn btn-primary'));?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                           <?php 
                           $b_id="";
                           if(isset($_REQUEST['bid']) && $_REQUEST['bid']!=NULL)
                           {
                               $b_id= $_REQUEST['bid'];
                           }
                           $list= array();
                           $batches= BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id)); 
                           if($batches!=NULL)
                           {
                               foreach($batches as $batch)
                               {
                                    $batch_model			= 	Batches::model()->findByAttributes(array('id'=>$batch->batch_id,'is_deleted'=>0));
									$course    				= 	Courses::model()->findByAttributes(array('id'=>$batch_model->course_id));
                                    if($batch_model!=NULL)
                                    {
                                        $list[$batch_model->id] =  ucfirst($batch_model->name).' ( '.ucfirst($course->course_name).' )';
                                    }
                               }
                           }
						   
                           echo CHtml::dropDownList("batch_id", $b_id, $list,array('encode'=>false,'onchange'=>"javascript:list();",'class'=>"form-control",'empty'=>  Yii::t("app","Select Batch"),'style'=>'width: 200px'));
						   
						   $batch 				= Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid'],'is_active'=>1,'is_deleted'=>0));
						   $course 				= Courses::model()->findByAttributes(array('id'=>$batch->course_id));  
						   $semester_enabled	= Configurations::model()->isSemesterEnabled();
						   $sem_enabled			= Configurations::model()->isSemesterEnabledForCourse($course->id);
						   if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id != NULL){
								$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
								echo "<b>".Yii::t('app','Semester ')."</b> : ".ucfirst($semester->name); 
						   } 
                
						   ?>
                            <br><br>
                            <div class="table-responsive">                        
                                
                                <?php
                                //check it is cbsc format
                                if(ExamFormat::model()->getExamformat($b_id)==2 && $b_id!="")
                                {
                                    $cbsc_groups= CbscExamGroup17::model()->findAllByAttributes(array('batch_id'=>$b_id));
                                    
                                    if($cbsc_groups==NULL)
                                    {
                                        echo '<tr><td align="center" colspan="4">'.Yii::t('app','No Result Found').'</td></tr>';	
                                    }
                                    else
                                    {
                                        
                                        foreach ($cbsc_groups as $cbsc_exam_group)
                                        {                                           
                                            echo "<b>".Yii::t('app','Exam Group ')."</b> : ".ucfirst($cbsc_exam_group->name);
                                            echo "<br>";
                                            $exams= CbscExams17::model()->findAllByAttributes(array('exam_group_id'=>$cbsc_exam_group->id));    
                                            ?>
                                                <table class="table table-hover mb30">
                                                    <tr>
                                                        <th><?php echo Yii::t('app','Subject');?></th>
                                                        <th><?php echo Yii::t('app','Start Time');?></th>
                                                        <th><?php echo Yii::t('app','End Time');?></th>
														<th><?php echo Yii::t('app','Max  Mark');?></th>
														<th><?php echo Yii::t('app','Min Mark');?></th>
                                                                                                             
                                                    </tr>
                                                    <?php
                                                    if($exams!=NULL)
                                                    {
                                                        foreach($exams as $exam)
                                                        {
                                                                                                    
                                                         
                                                       
                                                                    $sub =Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));									
									if($sub->elective_group_id!=0 and $sub->elective_group_id!=NULL)
									{
                                                                           
										$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id, 'elective_group_id'=>$sub->elective_group_id));
										
										if($student_elective!=NULL)
										{
											$electname = Electives::model()->findByAttributes(array('id'=>$student_elective->elective_id));
											
											if($electname!=NULL)
											{
												echo "<tr>";          
													echo '<td>'.$sub->name."-".$electname->name.'</td>';
													$startdate= date($date_format,strtotime($exam->start_time));                                                                                                                                                                                                                      
													$starttime= date($time_format, strtotime($exam->start_time));
													echo '<td>'.$startdate." ".$starttime.'</td>';
													 
													$enddate= date($date_format,strtotime($exam->end_time));                                                                                                                                                                                                                      
													$endtime= date($time_format, strtotime($exam->end_time));
													echo '<td>'.$enddate." ".$endtime.'</td>';
													echo '<td>'.$exam->maximum_marks.'</td>';
													echo '<td>'.$exam->minimum_marks.'</td>';
												echo "</tr>";
											}
										}
									
										
									}
									else
									{
										echo "<tr>";          
											echo '<td>'.$sub->name.'</td>';
											$startdate= date($date_format,strtotime($exam->start_time));                                                                                                                                                                                                                      
											$starttime= date($time_format, strtotime($exam->start_time));
											echo '<td>'.$startdate." ".$starttime.'</td>';
											 
											$enddate= date($date_format,strtotime($exam->end_time));                                                                                                                                                                                                                      
											$endtime= date($time_format, strtotime($exam->end_time));
											echo '<td>'.$enddate." ".$endtime.'</td>';
											echo '<td>'.$exam->maximum_marks.'</td>';
													echo '<td>'.$exam->minimum_marks.'</td>';
										echo "</tr>";
									}
                                                                       
                                                                        
                                                                        
                                                                       
                                                                        
                                                                        
                                                        }
                                                    }
                                                    else
                                                    {
                                                      echo '<tr><td colspan="5" align="center">'.Yii::t("app", "Subjects Not Found 2").'</td></tr>';
                                                    }
                                                    
                                                    ?>
                                                    
                                                </table>
                                                <?php
                                        }
                                    }
                                }
                                else
                                {
                                ?>
                                
                                    <?php
                                    if($ex_group==NULL && $b_id!=NULL)
                                    {
                                        echo '<tr><td align="center" colspan="4">'.Yii::t('app','No Result Found').'</td></tr>';	
                                    }
                                    else
                                    {
                                        
                                        foreach ($ex_group as $exam_group)
                                        {                                           
                                            echo "<b>".Yii::t('app','Exam Group ')."</b> : ".ucfirst($exam_group->name);
                                            echo "<br>";
                                            $exams= Exams::model()->findAllByAttributes(array('exam_group_id'=>$exam_group->id));    
                                            ?>
                                                <table class="table table-hover mb30">
                                                    <tr>
                                                        <th><?php echo Yii::t('app','Subject');?></th>
                                                        <th><?php echo Yii::t('app','Start Time');?></th>
                                                        <th><?php echo Yii::t('app','End Time');?></th>
                                                        <th><?php echo Yii::t('app','Maximum Mark');?></th>
                                                        <th><?php echo Yii::t('app','Minimum Mark');?></th>                                                       
                                                    </tr>
                                                    <?php
                                                    if($exams!=NULL)
                                                    {
                                                        foreach($exams as $exam)
                                                        {
                                                                                                    
                                                         
                                                       
                                                                    $sub =Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));									
									if($sub->elective_group_id!=0 and $sub->elective_group_id!=NULL)
									{
                                          
										 // echo $student_elective->elective_id;
										                             
										$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id, 'elective_group_id'=>$sub->elective_group_id));
										   
										if($student_elective!=NULL)
										{
											$electname = Electives::model()->findByAttributes(array('id'=>$student_elective->elective_id));
											
											if($electname!=NULL)
											{
												echo "<tr>";          
													echo '<td>'.$sub->name."-".$electname->name.'</td>';
													$startdate= date($date_format,strtotime($exam->start_time));                                                                                                                                                                                                                      
													$starttime= date($time_format, strtotime($exam->start_time));
													echo '<td>'.$startdate." ".$starttime.'</td>';
													 
													$enddate= date($date_format,strtotime($exam->end_time));                                                                                                                                                                                                                      
													$endtime= date($time_format, strtotime($exam->end_time));
													echo '<td>'.$enddate." ".$endtime.'</td>';
													
													echo '<td>'.$exam->maximum_marks.'</td>';
													echo '<td>'.$exam->minimum_marks.'</td>';
												
												echo "</tr>";
											}
										}										
									}
									else
									{
										echo "<tr>";          
											echo '<td>'.$sub->name.'</td>';
											$startdate= date($date_format,strtotime($exam->start_time));                                                                                                                                                                                                                      
											$starttime= date($time_format, strtotime($exam->start_time));
											echo '<td>'.$startdate." ".$starttime.'</td>';
											 
											$enddate= date($date_format,strtotime($exam->end_time));                                                                                                                                                                                                                      
											$endtime= date($time_format, strtotime($exam->end_time));
											echo '<td>'.$enddate." ".$endtime.'</td>';
											
											echo '<td>'.$exam->maximum_marks.'</td>';
											echo '<td>'.$exam->minimum_marks.'</td>';										
										echo "</tr>";
									}
                                                                       
                                                                        
                                                        }
                                                    }
                                                    else
                                                    {
                                                      echo '<tr><td colspan="5" align="center">'.Yii::t("app", "Subjects Not Found 4").'</td></tr>';
                                                    }
                                                    
                                                    ?>
                                                    
                                                </table>
                                                <?php
                                        }
                                    }
                                    
                                }
                                    ?>    
                                
                            </div> 
                            
                            
                        
            
            
            <!-- END div class="profile_details" -->
        </div> <!-- END div class="parentright_innercon" -->
    </div> <!-- END div id="parent_rightSect" -->
    <div class="clear"></div>
</div> <!-- END div id="parent_Sect" -->
