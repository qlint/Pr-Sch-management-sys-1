
<?php $this->renderPartial('leftside');?> 
    <?php
    $student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$batchstudents    = 	BatchStudents::model()->studentBatch($student->id); 
		$exam = ExamScores::model()->findAllByAttributes(array('student_id'=>$student->id));
	
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
							  <?php 
							  $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
							  	if(count($batchstudents) == 1){
								   $batchstudent	=	BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'result_status'=>0)); ?>
								   <?php if($batchstudent->roll_no != NULL) {?>
											<div class="text-muted"><strong><?php echo Yii::t('app','Roll No').' :';?></strong> <?php echo $batchstudent->roll_no;
								   		}?></div>
						  <?php } ?>
							   <?php if(count($batchstudents)>1){ 
										echo CHtml::link('View Course Details', array('/studentportal/default/course'));
										}
										if(count($batchstudents) == 1){ ?>
											  <?php if(in_array('batch_id', $student_visible_fields)){ ?>      
											  <div class="text-muted"><strong><?php echo Yii::t('app','Course').' :';?></strong>
												<?php 
												  $batch = Batches::model()->findByPk($batchstudent->batch_id);
												  $semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
												  echo $batch->course123->course_name;
												  
												?>
											  </div>          
											  <div class="text-muted"> <strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' :';?></strong> <?php echo $batch->name;?></div>
											  <?php } ?>
											   <?php  if($batch->semester_id!=NULL){ ?>
														<div class="text-muted"> <strong><?php echo Yii::t('app','Semester').' :';?></strong> <?php echo $semester->name;?></div>
												<?php } ?>
									<?php } ?>	
					          
					          
					        </div>
                          </div>
                        </div>
                         <!-- END div class="profile_top" -->
                         
                         <div class="panel-heading">
                          <!-- panel-btns -->
                          <h3 class="panel-title"><?php echo Yii::t('app','Semester Assessment Report');?></h3>
                             <?php $semester_enabled	= Configurations::model()->isSemesterEnabled();?>
                             <div class="table-responsive">
                              <table class="table table-bordered mb30">
                              <thead>
                                <tr>
                                    <th><?php echo Yii::t('app','Course');?></th>                        
                                    <th><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></th>
                                 
                                    <?php
									$batch = Batches::model()->findByPk($_GET['bid']);
                                      $semester	= Semester::model()->findByAttributes(array('id'=>$_REQUEST['sem']));
                                    if($semester_enabled == 1){
                                        if($batch->semester_id!=NULL){
                                        ?>
                                        <th><?php echo Yii::t('app','Semester');?></th>
                                        
                                        <?php
                                        }
                                    }
                                    ?>
                                </tr>
                                </thead>
                                <tbody>
                                	<tr>
                                    <td> <?php 
                                      
                                      echo ($batch->course123->course_name)?html_entity_decode(ucfirst($batch->course123->course_name)):"-"; 
                                    ?></td>
                                    <td><?php echo ($batch->name)?html_entity_decode(ucfirst($batch->name)):"-";?></td>
                                    <?php
                                    if($semester_enabled == 1){
                                        if($batch->semester_id!=NULL){
									?>		
                                    		<td><?php echo ($semester->name)?html_entity_decode(ucfirst($semester->name)):"-";?></td>
                                  <?php
                                        }
                                    }
                                    ?>
                                    </tr>
                                </tbody>
                                </table>
                                </div>
                        </div>
                        
                        
                        <div class="people-item">
                            
                           
                            <div class="btn-demo" style="position:relative; top:-8px; right:3px; float:right;">                    
                                <div class="edit_bttns" >
                                    <ul>
                                        <li>
                                            <?php echo CHtml::link('<span>'.Yii::t('app','Back').'</span>',array('/studentportal/default/SemResult','bid'=>$_REQUEST['bid'],'sem'=>$_REQUEST['sem']),array('class'=>'addbttn last'));?>
                                            <?php echo CHtml::link(Yii::t('app','Generate PDF'), array('/studentportal/default/SemResultpdf','id'=>$_REQUEST['id'],'sem'=>$semester->id, 'bid'=>$batch->id),array('target'=>"_blank",'class'=>'portal-pdf')); ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
  
    <div class="table-responsive">
                        <?php $grouptype=ExamGroups::model()->findByAttributes(array('id'=>$exam_group_id,'result_published'=>1)); ?>
                        <div class="table-responsive">
                            <table class="table table-bordered mb30">
                            <thead>
                    	<tr>
                            <th><?php echo Yii::t('app','Exam Group Name');?></th>
                            <th><?php echo Yii::t('app','Subject');?></th>
                            <th><?php echo $grouptype->exam_type;?></th>
                            <th><?php echo Yii::t('app','Remarks');?></th>
                            <th><?php echo Yii::t('app','Result');?></th>
                    	</tr>
                        </thead>
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
									
									//$group=ExamGroups::model()->findByAttributes(array('id'=>$exm->exam_group_id));
									echo '<td>'.$group->name.'</td>';
									$sub=Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
									
									if($sub->elective_group_id!=0 and $sub->elective_group_id!=NULL)
									{
										$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$_REQUEST['bid']));
										
										if($student_elective!=NULL)
										{
											$electname = Electives::model()->findByAttributes(array('id'=>$student_elective->elective_id));
											
											if($electname!=NULL)
											{
												echo '<td>'.$sub->name."-".$electname->name.'</td>';
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
								}
								if($exams->marks >= $exm->minimum_marks)
										echo '<td>'.Yii::t('app','Passed').'</td>';
									else
										echo '<td>'.Yii::t('app','Failed').'</td>';
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
    </div> <!-- END div id="parent_rightSect" -->
    <div class="clear"></div>
</div> <!-- END div id="parent_Sect" -->
