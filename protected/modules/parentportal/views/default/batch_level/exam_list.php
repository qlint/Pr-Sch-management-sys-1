<?php $this->renderPartial('leftside');?> 

    <?php
	$semester_enabled	= Configurations::model()->isSemesterEnabled();    
    $student_id="";
    if(isset($_REQUEST['sid']) && $_REQUEST['sid']!=NULL)
    {
        $student_id= $_REQUEST['sid'];
    }
    $student=Students::model()->findByAttributes(array('id'=>$student_id));
    $exam = ExamScores::model()->findAllByAttributes(array('student_id'=>$student->id));
    $guardian = Guardians::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
    $exam_group_id="";
    $exam_arr=array();
    if(isset($_REQUEST['id']) && $_REQUEST['id']!=NULL)
    {
        $exam_group_id= $_REQUEST['id'];
        $exams= Exams::model()->findAllByAttributes(array('exam_group_id'=>$exam_group_id));    
        foreach ($exams as $exam)
        {
            $exam_arr[]=$exam->id;
        }
    }
    
    
    $criteria= new CDbCriteria;
    $criteria->condition= "student_id=:student_id";
    $criteria->params= array(':student_id'=>$student->id);
    $criteria->addInCondition('exam_id', $exam_arr);
    $exam= ExamScores::model()->findAll($criteria);
    
    
    
    
	//$electives = ElectiveScores::model()->findAll("student_id=:x", array(':x'=>$student->id));

	$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forParentPortal');
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
            
            <?php 
            
                if(isset($student_id) && GuardianList::model()->checkRelation($student_id,$guardian->id))
                { 
                ?> 
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
							  <?php
							  $batchstudents    = 	BatchStudents::model()->studentBatch($student->id); 
							  ?>
					          <?php if(in_array('batch_id', $student_visible_fields)){ ?> 
									<?php if(count($batchstudents)>1){
											echo CHtml::link('View Course Details', array('/parentportal/default/course', 'id'=>$student->id));
										  }     
										  ?>
								<?php  if(count($batchstudents) == 1){ 
											$batchstudent		=	BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'result_status'=>0));?>
								  <div class="text-muted"><strong><?php echo Yii::t('app','Course').' :';?></strong>
									<?php 
									  $batch = Batches::model()->findByPk($batchstudent->batch_id);
									  echo $batch->course123->course_name;
									?>
								  </div>          
								  <div class="text-muted"> <strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' :';?></strong> <?php echo $batch->name;?></div>
								  <?php }
					          } ?>
					          <div class="text-muted"><strong><?php echo Yii::t('app','Admission No').' :';?></strong> <?php echo $student->admission_no; ?></div>
					          
					        </div>
                          </div>
                        </div>
                         <!-- END div class="profile_top" -->
                         
                         <div class="panel-heading">
                          <!-- panel-btns -->
                          <h3 class="panel-title"><?php echo Yii::t('app','Assessment');?></h3>
                            
                        </div>
                        
                        
                        <div class="people-item">
						 <?php
							 $current_batch 	= Batches::model()->findByPk($_REQUEST['bid']);
							 $current_course  	= Courses::model()->findByAttributes(array('id'=>$current_batch->course_id));
							 $sem_enabled		= Configurations::model()->isSemesterEnabledForCourse($current_course->id);
							 if(in_array('batch_id', $student_visible_fields)){ ?>      
								<div class="text-muted"><strong><?php echo Yii::t('app','Course').' :';?></strong>
								<?php echo ucfirst($current_course->course_name); ?>
								<div class="text-muted"><strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' :';?></strong>
								<?php echo ucfirst($current_batch->name); ?>
								<?php  if($semester_enabled == 1 and $sem_enabled == 1 and $current_batch->semester_id!=NULL){ 
										$semester	= Semester::model()->findByAttributes(array('id'=>$current_batch->semester_id));?>
										<div class="text-muted"><strong><?php echo Yii::t('app', 'Semester').' :';?></strong>
										<?php echo ucfirst($semester->name); ?>
								<?php } ?>
							<?php } ?>
                            
                           
                            <div class="btn-demo" style="position:relative; top:-8px; right:3px; float:right;">                    
                                <div class="edit_bttns" >
                                    <ul>
                                        <li>
                                            <?php echo CHtml::link('<span>'.Yii::t('app','Back').'</span>',array('/parentportal/default/exam','bid'=>$_REQUEST['bid'],'sid'=>$student_id),array('class'=>'addbttn last'));?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            
                            
                         <div class="table-responsive">
                        
                        <table class="table table-hover mb30">
                    <tr>
                        <th><?php echo Yii::t('app','Exam Group Name');?></th>
                        <th><?php echo Yii::t('app','Subject');?></th>
                        <th><?php echo Yii::t('app','Score');?></th>
                        <th><?php echo Yii::t('app','Remarks');?></th>
                        <th><?php echo Yii::t('app','Result');?></th>
                    </tr>
                    <?php
                    if($exam==NULL)
                    {
                    	echo '<tr><td align="center" colspan="4"><i>'.Yii::t('app','No Assessments').'</i></td></tr>';	
                    }
                    else
                    {
						$displayed_flag = '';
						foreach($exam as $exams)
						{
							$exm=Exams::model()->findByAttributes(array('id'=>$exams->exam_id));
							$group=ExamGroups::model()->findByAttributes(array('id'=>$exm->exam_group_id,'result_published'=>1));
							
							 $criteria = new CDbCriteria;
							 $criteria->condition = 'batch_id=:x';
							 $criteria->params = array(':x'=>$group->batch_id);	
							 $criteria->order = 'min_score DESC';
							 $grades = GradingLevels::model()->findAll($criteria);
							 
                                                        $t = count($grades); 
							if($group!=NULL and count($group) > 0)
							{
								echo '<tr>';
								if($exm!=NULL)
								{
									$displayed_flag = 1;
									
									$sub=Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
									echo '<td>'.$group->name.'</td>';
									if($sub->elective_group_id!=0 and $sub->elective_group_id!=NULL)
									{ 
										$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$_REQUEST['bid'], 'elective_group_id'=>$sub->elective_group_id));
									
										if($student_elective!=NULL)
										{
											$electname = Electives::model()->findByAttributes(array('id'=>$student_elective->elective_id));
											
											if($electname!=NULL)
											{
												echo '<td>'.$sub->name."-".$electname->name.'</td>';
											}
											else{
												echo '<td>'.'-'.'</td>';
											}
										}
									
										
									}
									else
									{
										echo '<td>'.$sub->name.'</td>';
									}
                                                                       
					if($group->exam_type == 'Marks') 
                                        {  
                                            echo "<td>".$exams->marks."</td>"; 

                                        } 
                                        else if($group->exam_type == 'Grades') 
                                        {
                                                echo "<td>";
				        
                                                foreach($grades as $grade)
						{
							
						 if($grade->min_score <= $exams->marks)
							{	
								$grade_value =  $grade->name;
							}
							else
							{
								$t--;
								
								continue;
								
							}
						echo $grade_value ;
						break;
						
						}
						if($t<=0) 
							{
								$glevel = Yii::t('app'," No Grades");;
							} 
						echo "</td>"; 
						} 
				   else if($group->exam_type == 'Marks And Grades'){
					echo "<td>"; foreach($grades as $grade)
						{
							
						 if($grade->min_score <= $exams->marks)
							{	
								$grade_value =  $grade->name;
							}
							else
							{
								$t--;
								
								continue;
								
							}
						echo $exams->marks." & ".$grade_value ;
						break;
						
							
						} 
						if($t<=0) 
							{
								echo $exams->marks." & ".Yii::t('app','No Grades');
							}
						echo "</td>"; } 
									echo '<td>';
									if($exams->remarks!=NULL)
									{
										echo $exams->remarks;
									}
									else
									{
										echo '-';
									}
									echo '</td>';
									if($exams->marks >= $exm->minimum_marks)
										echo '<td>'.Yii::t('app','Passed').'</td>';
									else
										echo '<td>'.Yii::t('app','Failed').'</td>';
								}
								echo '</tr>';
							}
							/*else{
							continue;
							}*/	
						}
						if($displayed_flag==NULL)
						{	
							echo '<tr><td align="center" colspan="5"><i>'.Yii::t('app','No Result Published').'</i></td></tr>';
						}
                    }
                    ?>    
                </table>
            </div> 
            
            
            <!-- END div class="profile_details" -->
        </div> <!-- END div class="parentright_innercon" -->
                <?php }
                else
                            {
                                ?>
                                <div class="people-item">
                                        <div class="formCon">
                                            <div class="formConInner">
                                                <center><?php echo Yii::t("app", "No Result Found") ?></center>
                                            </div>
                                        </div>
                                </div>
                                    <?php
                            } ?>
    </div> <!-- END div id="parent_rightSect" -->
    <div class="clear"></div>
</div> <!-- END div id="parent_Sect" -->
