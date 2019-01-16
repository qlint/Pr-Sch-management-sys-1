<?php $this->renderPartial('leftside');?> 

    <?php
    $student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$batchstudents=BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id, 'result_status'=>0));
    $batches= BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id));
    
    
    $exam = ExamScores::model()->findAllByAttributes(array('student_id'=>$student->id));
	//$electives = ElectiveScores::model()->findAll("student_id=:x", array(':x'=>$student->id));

	$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentPortal');
    $semester_enabled	= Configurations::model()->isSemesterEnabled();
	?>
    
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
        	<div class="people-item">
                          <div class="media">
                            <a href="#" class="pull-left">
                                <?php
                     if($student->photo_file_name!=NULL)
                     { 
					 	$path = Students::model()->getProfileImagePath($student->id);
                        echo '<img  src="'.$path.'" alt="'.$student->photo_file_name.'" width="100" height="103" />';
                    }
                    elseif($student->gender=='M')
                    {
                        echo '<img  src="images/portal/prof-img_male.png" alt='.$student->first_name.' width="100" height="103" />'; 
                    }
                    elseif($student->gender=='F')
                    {
                        echo '<img  src="images/portal/prof-img_female.png" alt='.$student->first_name.' width="100" height="103" />';
                    }
                    ?>                            
                            </a>
                            <div class="media-body">
					          <?php
					          if(FormFields::model()->isVisible("fullname", "Students", "forStudentPortal")){
					          ?>
					          <h4 class="person-name"><?php echo $student->studentFullName("forStudentPortal");?></h4>
					          <?php
					          }
					          ?>
					          <div class="text-muted"><strong><?php echo Yii::t('app','Admission No').' :';?></strong> <?php echo $student->admission_no; ?></div>
							  <?php if($batch_student!=NULL and $batch_student->roll_no!=0){ ?>
										<div class="text-muted"><strong><?php echo Yii::t('app','Roll No').' :';?></strong> <?php echo $batch_student->roll_no; ?></div>
								<?php } ?>
								<?php if(count($batchstudents)>1){ 
										echo CHtml::link('View Course Details', array('/studentportal/default/course'));
										}
										else{?>	
											  <?php if(in_array('batch_id', $student_visible_fields)){ ?>      
											  <div class="text-muted"><strong><?php echo Yii::t('app','Course').' :';?></strong>
												<?php 
												  $batch = Batches::model()->findByPk($batchstudents[0]['batch_id']);
												  $course	= Courses::model()->findByAttributes(array('id'=>$batch->course_id));
												  echo $batch->course123->course_name;
												  $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
												?>
											  </div>          
											  <div class="text-muted"> <strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' :';?></strong> <?php echo $batch->name;?></div>
											  <?php } ?>
											   <?php  
												$semester_enabled	= Configurations::model()->isSemesterEnabled(); 
												$sem_enabled		= Configurations::model()->isSemesterEnabledForCourse($course->id);
												$semester			= Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); 
												if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id!=NULL){ ?>
														<div class="text-muted"> <strong><?php echo Yii::t('app','Semester').' :';?></strong> <?php echo $semester->name;?></div>
												<?php } ?>
									<?php } ?>	
                             
                              <div class="text-muted"></div>
					        </div>
                          </div>
                        </div>
                         <!-- END div class="profile_top" -->
                         
                         <div class="panel-heading">
                          <!-- panel-btns -->
                          <h3 class="panel-title"><?php echo Yii::t('app','Assessment - Batch List');?></h3>
                            
                        </div>
                        
                        
                        <div class="people-item">
                            
<div class="button-bg button-bg-none">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
                                    <ul>
                                        <li>
                                            <?php echo CHtml::link('<span>'.Yii::t('app','View Timetable').'</span>',array('/studentportal/default/examTimetable'),array('class'=>'btn btn-primary'));?>
                                      <?php
									  $enabled	=	Configurations::model()->isSemesterEnabled();
									  if($enabled==1)
									  {
									  ?>
                                            <?php echo CHtml::link('<span>'.Yii::t('app','Semester Results').'</span>',array('/studentportal/default/semResult'),array('class'=>'btn btn-primary'));?>
                                            <?php
									  }
									  ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            
                         <div class="table-responsive">
                        
                        <table class="table table-bordered mb30">
                        <thead>
                    <tr>
                        <th><?php echo Yii::t('app','Batch Name');?></th>
                        <?php if($semester_enabled == 1){?>
                        <th><?php echo Yii::t('app','Semester');?></th>
                        <?php } ?>
                        <th><?php echo Yii::t('app','Course');?></th>
                        <th><?php echo Yii::t('app','Academic Year');?></th>
                        <th><?php echo Yii::t('app','Status');?></th>
                        <th><?php echo Yii::t('app','Manage');?></th>
                        <th><?php echo Yii::t('app','Online Exams');?></th>
                    </tr>
                    </thead>
                    <?php
                    if($batches==NULL)
                    {
                    	echo '<tr><td align="center" colspan="4"><i>'.Yii::t('app','No Assessments').'</i></td></tr>';	
                    }
                    else
                    {
			foreach ($batches as $batch)
                        {
                            echo "<tr>";
                            $batch_id= $batch->batch_id;
                            $batch_model= Batches::model()->findByPk($batch_id);
                            if($batch_model!=NULL)
                            {
                                if(in_array('batch_id', $student_visible_fields))
                                { 
                                    echo "<td>".$batch_model->name."</td>";
                                    $sem_name='-';
                                    if($batch_model->semester_id!=NULL){
                                        $sem_model  =   Semester::model()->findByPk($batch_model->semester_id);
                                        $sem_name   =   isset($sem_model)?$sem_model->name:'-';
                                    }
								    if($semester_enabled == 1){
                                    echo "<td>".ucfirst($sem_name)."</td>";
									}
                                    $batchs = Batches::model()->findByPk($batch_model->id);
                                    echo "<td>".$batchs->course123->course_name."</td>";
                                    $year_name="-";
                                    $academic_model= AcademicYears::model()->findByPk($batch_model->academic_yr_id);
                                    if($academic_model!=NULL)
                                    {
                                      $year_name=  $academic_model->name; 
                                    }
                                    echo "<td>".$year_name."</td>";
                                    if($batch->status==1)
                                    {
                                        echo "<td>".Yii::t('app','Current Batch')."</td>";
                                    }
                                    else
                                    {
                                        echo "<td>".Yii::t('app','Previous Batch')."</td>";
                                    }
                                    $level = Configurations::model()->findByPk(41);
                                    if($batch->status==1 && $level->config_value !=1 && ExamFormat::model()->getExamformat($batch_id)== 2)
                                    {
										$cbsc_format    = ExamFormat::getCbscformat($batch_id);
                    					if($cbsc_format){
											echo "<td>".CHtml::link(Yii::t('app','View Result'),array('default/cbsc17','bid'=>$batch_id), array('class'=>'view_Exmintn_atg Exm_aTgColor_y'))."</td>";
										}else{
                                       	  echo "<td>".CHtml::link(Yii::t('app','View Result'),array('default/cbsc'), array('class'=>'view_Exmintn_atg Exm_aTgColor_y'))."</td>";
										}
                                    }
                                    else
                                    {
										echo "<td>".CHtml::link(Yii::t('app','View Exam Groups'),array('default/exam', 'bid'=>$batch_id), array('class'=>'view_Exmintn_atg Exm_aTgColor_Exm_group'))."</td>";
                                    }
										
									 	echo "<td>".CHtml::link(Yii::t('app','View Online Exams'),array('/onlineexam/default/list', 'bid'=>$batch_id), array('class'=>'view_Exmintn_atg Exm_aTgColor_g'))."</td>";
                                    
                                }
                                
                            }
                            echo "</tr>";
                        }
                    }
                    ?>    
                </table>
            </div> 
            
            
            <!-- END div class="profile_details" -->
        </div> <!-- END div class="parentright_innercon" -->
    </div> <!-- END div id="parent_rightSect" -->
    <div class="clear"></div>
</div> <!-- END div id="parent_Sect" -->
