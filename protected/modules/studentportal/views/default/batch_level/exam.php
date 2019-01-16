<?php $this->renderPartial('leftside');?> 

    <?php
    $batch_id="";
    if(isset($_REQUEST['bid']) && $_REQUEST['bid']!=NULL)
    {
        $batch_id= $_REQUEST['bid'];
		$cbsc_format    = ExamFormat::getExamformat($batch_id);
        if($cbsc_format == 2){ 
			$ex_group= CbscExamGroup17::model()->findAllByAttributes(array('batch_id'=>$batch_id,'date_published'=>1));
		}
		else{ 
        	$ex_group= ExamGroups::model()->findAllByAttributes(array('batch_id'=>$batch_id,'is_published'=>1)); 
		}
		                           
    }
    
    
    
    $student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$batchstudents=BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id,'status'=>1, 'result_status'=>0));
    $exam = ExamScores::model()->findAllByAttributes(array('student_id'=>$student->id));
	//$electives = ElectiveScores::model()->findAll("student_id=:x", array(':x'=>$student->id));
  	$semester_enabled	= Configurations::model()->isSemesterEnabled();    
	$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentPortal');
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
												  $semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
												  echo $batch->course123->course_name;
												  $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
												  $sem_enabled	= Configurations::model()->isSemesterEnabledForCourse($course->id);
												?>
											  </div>          
											  <div class="text-muted"> <strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' :';?></strong> <?php echo $batch->name;?></div>
											  <?php } ?>
											   <?php  if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id!=NULL){ ?>
														<div class="text-muted"> <strong><?php echo Yii::t('app','Semester').' :';?></strong> <?php echo $semester->name;?></div>
												<?php } ?>
									<?php } ?>	
					          
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
                                            <?php echo CHtml::link('<span>'.Yii::t('app','Back').'</span>',array('/studentportal/default/exams'),array('class'=>'addbttn last'));?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                           
                            
                            
                         <div class="table-responsive">
                        
                        <table class="table table-hover mb30">
                    <tr>
                        <th><?php echo Yii::t('app','Exam Group Name');?></th>                        
                        <th><?php echo Yii::t('app','Action');?></th>
                    </tr>
                    <?php
                    if($ex_group==NULL)
                    {
                    	echo '<tr><td align="center" colspan="4"><i>'.Yii::t('app','No Exam Goups').'</i></td></tr>';	
                    }
                    else
                    {
                        foreach ($ex_group as $exam_group)
                        {
                            echo "<tr>";
                            echo "<td>".ucfirst($exam_group->name)."</td>";
                            echo "<td>";
							if($exam_group->result_published){
								echo CHtml::link(Yii::t('app','View Result'),array('default/examList', 'id'=>$exam_group->id,'bid'=>$batch_id));
							}else{
								echo "-";
							}"</td>";
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
