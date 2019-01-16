
                <h1><?php echo Yii::t('app','Exam Results');?></h1>
              
                
                <?php
                //if(isset($_REQUEST['flag']) and $_REQUEST['flag']==1)
				$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
				$details=Students::model()->findByAttributes(array('id'=>$student,'is_deleted'=>0,'is_active'=>1));
				
                if(isset($list) and $list!=NULL and $details!=NULL)
                {
					
					$batch=Batches::model()->findByAttributes(array('id'=>$details->batch_id,'is_deleted'=>0,'is_active'=>1));
					$course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
					?>
					<h3><?php echo Yii::t('app','Student Information');?></h3>
					<div class="tablebx">  
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <div class="tbl-grd"></div> 
                            <tr class="tablebx_topbg">
                             <?php if(Configurations::model()->rollnoSettingsMode() != 1){?>
                            	<td><?php echo Yii::t('app','Admission No');?></td>
                             <?php } ?>
                            <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
                            	<td><?php echo Yii::t('app','Roll No');?></td>
                            <?php } ?>
                                <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                <td><?php echo Yii::t('app','Student Name');?></td>
                                <?php } ?>
                                <?php if(in_array('batch_id', $student_visible_fields)){ ?>
                                <td><?php echo Yii::t('app','Course');?></td>
                                <td><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></td>
                                <?php } ?>
                            </tr>
                            <tr>
                              <?php if(Configurations::model()->rollnoSettingsMode() != 1){?>
                            	<td><?php echo $details->admission_no; ?></td>
                                 <?php } ?>
                             <?php if(Configurations::model()->rollnoSettingsMode() != 2){
								 $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$details->id, 'batch_id'=>$details->batch_id, 'status'=>1));?>
                            	<td><?php if($batch_student!=NULL and $batch_student->roll_no!=0){
								  				echo $batch_student->roll_no;
								  			}
											else{
												echo '-';
											}?></td>
                                 <?php } ?>
                                <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                <td style="padding:10px;"><?php echo CHtml::link($details->studentFullName("forStudentProfile"),array('/students/students/view','id'=>$details->id)); ?></td>
                                <?php } ?>
                                <?php if(in_array('batch_id', $student_visible_fields)){ ?>
                                <td>
                                	<?php 
									if($course->course_name!=NULL)
										echo $course->course_name;
									else
										echo '-';
									?>
                                </td>
                                <td>
									<?php 
									if($batch->name!=NULL)
										echo $batch->name;
									else
										echo '-';
									?>
								</td>
                                <?php } ?>
                            </tr>
                        </table>
					</div> <!-- END div class="tablebx" Student Information -->
					<h3><?php echo Yii::t('app','EXAM RESULT REPORT');?></h3>
                    <?php
					$examgroups = ExamGroups::model()->findAll('batch_id=:x',array(':x'=>$batch->id)); // Selecting exam groups in the batch of the student
					if($examgroups!=NULL) // If exam groups present
					{
						$i = 1;
						foreach($examgroups as $examgroup) 
						{
							$exams = Exams::model()->findAll('exam_group_id=:x',array(':x'=>$examgroup->id)); // Selecting exams(subjects) in an exam group	
							$allastatus	=0;
							if($exams!=NULL){
								foreach($exams as $exam){
									$allmin 	 = $exam->minimum_marks;
									$score = ExamScores::model()->findByAttributes(array('exam_id'=>$exam->id,'student_id'=>$student,));
									if($score!=NULL){
										if($score->marks<$allmin)
											$allastatus = 1;
									}
								}
							}else
								$allastatus = 2;
								
						?>
                       <div class="student-result-blk">
						<h4><?php echo $i.'. '.ucfirst($examgroup->name); $i++;?></h4>
                           </div>
                           <div class="pdf-box">
    <div class="box-one">
    	<div class="bttns_addstudent-n">
    <ul>
    	<li>
        <div class="result-show">
			<p><?php echo Yii::t('app','Result')." : ";?>
                           <?php
						   if($allastatus == 0)
								echo "<span style='color:#006600'>".Yii::t('app','Passed')."</span>";
							else if($allastatus == 1)
								echo "<span style='color:#F00'>".Yii::t('app','Failed')."</span>";
						   ?></p>
                           </div>
             </li>
             
            </ul>
</div>
    </div>
    <div class="box-two">
         <div class="pdf-div">
			<?php echo CHtml::link(Yii::t('app', 'Generate PDF'), array('/examination/result/studentexampdf','exam_group_id'=>$examgroup->id,'id'=>$student),array('target'=>"_blank",'class'=>'pdf_but')); ?>    
         </div>
    </div>
</div>
                            
                            <!-- Single Exam Table -->
                            <div class="tablebx result-table" style="clear:both"> 
                            	<div class="os-table"> 
									<div class="tbl-grd"></div> 
                                    <table class="table table-bordered2" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <thead>
                                         <tr>
                                                 <th rowspan="3" colspan="2" class="header-td1" width="400"><?php echo Yii::t('app','Subject');?></th>
                                                 <?php
												$examgrp = ExamGroups::model()->findByAttributes(array('id'=>$examgroup->id));
												if($examgrp->exam_type ==NULL or $examgrp->exam_type=='')
												{?>
                                                <th  width="100"class="header-td1"><?php echo Yii::t('app','Score');?></th>
                                               <?php }else{
												   ?>
                                                   <th  width="100"class="header-td1"><?php echo $examgrp->exam_type;?></th>
                                                   <?php
											   }?>
                                                <th width="100"><?php echo Yii::t('app','Status');?></th> 
                                                 <th width="100"><?php echo Yii::t('app','Remarks');?></th>            
                                         </tr>
                                         </thead>	
                                         <tbody>
                                        <?php
										$status	=0;
										if($exams!=NULL)
										{
											foreach($exams as $exam)
											{
												$min 	 = $exam->minimum_marks;
												$subject = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
												if($subject!=NULL) // Checking if exam for atleast subject is created.
												{
												$score = ExamScores::model()->findByAttributes(array('exam_id'=>$exam->id,'student_id'=>$student,));
												if($score!=NULL)
													{
														if($subject->split_subject == 1){
															$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>$batch->id),array('order'=>'min_score DESC'));
									
														if(!$grades)
														{
															$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>NULL));	
														}
							 							 $t = count($grades);
														 if($score->marks<$min){
															$status = 1;
														 }
														 else
														 	$status = 0;
															?>
                                                                 <tr>
                                                                         <td style="text-align:left;padding-left:50px" rowspan="3" class="header-td1">
                                                                          <?php 
																			if($subject->name!=NULL){
																				if($subject->elective_group_id==0){
																					echo ($subject->name!=NULL)? ucfirst($subject->name):'-';
																				}else{	 
																					$electives 	= StudentElectives::model()->findByAttributes(array('student_id'=>$student, 'elective_group_id'=>$subject->elective_group_id));
																					$elective	= Electives::model()->findByAttributes(array('id'=>$electives->elective_id));
																					echo (($subject->name!=NULL)? ucfirst($subject->name).' (':'').$elective->name.(($subject->name!=NULL)?')':'');
																					}
																			}
																			else
																			continue;
																			
																			$subject_cps	=	ExamScoresSplit::model()->findAllByAttributes(array('exam_scores_id'=>$score->id));
																			$mark_value=array();
																			foreach($subject_cps as $subject_cp){
																				$mark_value[]=$subject_cp->mark;
																			} 
																			$subjects_splits	=	SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$exam->subject_id));  
																			$sub_value=array();
																			foreach($subjects_splits as $subjects_split){
																				$sub_value[]=$subjects_split->split_name;
																			}  
																			
																	?>
                                                                         </td>
                                                                        <td style="width: 130px" class="header-td1"><?php echo $sub_value[0];?></td>
                                                                        <td><?php echo $mark_value[0];?></td> 
                                                                        <td rowspan="3"><span style="color:#006600"><?php  
																			if($status  == 0){
																				echo "<span style='color:#006600'>".Yii::t('app','Passed').$roles."</span>";
																			}else{
																				echo "<span style='color:#F00'>".Yii::t('app','Failed')."</span>";
																			}
																		?></span></td> 
                                                                        <td rowspan="3"><span><?php
														   if($score->remarks!=NULL)
														   {
															   echo $score->remarks;
														   }
														   else
														   {
															   echo '-';
														   }
														   ?></span></td>             
                                                                 </tr>
                                                                     <tr>           
                                                                        <td  class="header-td1"><?php echo $sub_value[1];?></td>
                                                                        <td><?php echo $mark_value[1];?></td>
                                                                        
                                                                 </tr>
                                                                 </tr>
                                                                     <tr>           
                                                                        <td  class="header-td1"><?php echo Yii::t('app','Total');?></td>
                                                                        <td><?php														
														 if($examgroup->exam_type == 'Marks') {  
														 echo $score->marks; } 
														  else if($examgroup->exam_type == 'Grades') {
														  	
														   foreach($grades as $grade)
																{
																	
																 if($grade->min_score <= $score->marks)
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
																		$glevel = " No Grades" ;
																	} 
																
																} 
														   else if($examgroup->exam_type == 'Marks And Grades'){
															 foreach($grades as $grade)
																{
																	
																 if($grade->min_score <= $score->marks)
																	{	
																		$grade_value =  $grade->name;
																	}
																	else
																	{
																		$t--;
																		
																		continue;
																		
																	}
																echo $score->marks . " & ".$grade_value ;
																break;
																
																	
																} 
																if($t<=0) 
																	{
																		echo $score->marks." & ".Yii::t('app',"No Grades") ;
																	}
																 } 
														?></td>
                                                                          
                                                                 </tr> 
                                                                 <?php
														}else{ ?>
                                                          <tr>
                                                             <td colspan="2" class="header-td1"  style="text-align:left;padding-left:50px">
                                                             <?php 
																if($subject->name!=NULL){
																	if($subject->elective_group_id==0){
																		echo ($subject->name!=NULL)? ucfirst($subject->name):'-';
																	}else{	 
																		$electives 	= StudentElectives::model()->findByAttributes(array('student_id'=>$student, 'elective_group_id'=>$subject->elective_group_id));
																		$elective	= Electives::model()->findByAttributes(array('id'=>$electives->elective_id));
																		echo (($subject->name!=NULL)? ucfirst($subject->name).' (':'').$elective->name.(($subject->name!=NULL)?')':'');
																		}
																}
																else
																continue;
														?>
                                                             </td>
                                                             <td>
                                                             <?php
														$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>$batch->id),array('order'=>'min_score DESC'));
									
														if(!$grades)
														{
															$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>NULL));	
														}
							 							 $t = count($grades);
														 if($score->marks<$min){
															$status = 1;
														 }
														 else
														 	$status = 0;
														 if($examgroup->exam_type == 'Marks') {  
														 echo $score->marks; } 
														  else if($examgroup->exam_type == 'Grades') {
														  	
														   foreach($grades as $grade)
																{
																	
																 if($grade->min_score <= $score->marks)
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
																		$glevel = " No Grades" ;
																	} 
																
																} 
														   else if($examgroup->exam_type == 'Marks And Grades'){
															 foreach($grades as $grade)
																{
																	
																 if($grade->min_score <= $score->marks)
																	{	
																		$grade_value =  $grade->name;
																	}
																	else
																	{
																		$t--;
																		
																		continue;
																		
																	}
																echo $score->marks . " & ".$grade_value ;
																break;
																
																	
																} 
																if($t<=0) 
																	{
																		echo $score->marks." & ".Yii::t('app',"No Grades") ;
																	}
																 } 
														?>
                                                             </td>
                                                             <td><?php  
                                                            if($status  == 0){
                                                                echo "<span style='color:#006600'>".Yii::t('app','Passed').$roles."</span>";
                                                            }else{
                                                                echo "<span style='color:#F00'>".Yii::t('app','Failed')."</span>";
                                                            }
                                                        ?></td>
                                                             <td> <?php
														   if($score->remarks!=NULL)
														   {
															   echo $score->remarks;
														   }
														   else
														   {
															   echo '-';
														   }
														   ?></td>
                                                          </tr> 
                                                        <?php
														}
													}
												}
											}
										}
									 ?>
                                          
                                            
                                    </tbody>
                                </table> 
                            	
                            </div>
                            <!-- END Single Exam Table -->	
						<?php
						
						} // END foreach($examgroups as $examgroup)
					}
					else // If no exam groups present in the batch of the student
					{
						echo '<div class="listhdg" align="center">'.Yii::t('app','No exam details available!').'</div>';	
					}
				
                }else{
					echo '<div class="listhdg" align="center">'.Yii::t('app','Nothing Found!').'</div>';
				}
			
				 //END isset($list)
                ?>
                <div class="clear"></div>
         