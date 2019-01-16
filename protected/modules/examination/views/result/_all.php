<style type="text/css">
	.pdtab_Con {
		margin: 0;
		padding: 8px 0 0;
	}
</style>
	<div class="pdtab_Con" style="text-align:center">
    <?php
	$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
	if($lists)
    { 
	
	?>                    
    <span style="float:right"><?php echo CHtml::link(Yii::t('app', 'Generate PDF'), array('/examination/result/resultpdf','lists'=>$lists,'search_id'=>$search_id,'course'=>$course,'batch'=>$batch,'group'=>$group,'exam'=>$exam),array('target'=>"_blank",'class'=>'pdf_but')); ?></span>
    <?php
	}
	?>
    	<h3><?php echo Yii::t('app','EXAM RESULT REPORT');?></h3>
         <div class="pagecon">
                        <?php                                          
                          $this->widget('CLinkPager', array(
                          'currentPage'=>$pages->getCurrentPage(),
                          'itemCount'=>$item_count,
                          'pageSize'=>$page_size,
                          'maxButtonCount'=>5,
                          //'nextPageLabel'=>'My text >',
                          'header'=>'',
                        'htmlOptions'=>array('class'=>'pages'),
                        ));?>
                        </div> <!-- END div class="pagecon" 2 -->
                        <div class="clear"></div><br />
                        <div class="os-table"> 
                    <div class="tbl-grd"></div>       
                    <table class="table table-bordered2" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="30"><?php echo Yii::t('app','Sl No');?></th> 
                                 <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
                                  <th width="50"><?php echo Yii::t('app','Roll No');?></th> 
                                 <?php } ?>
                                <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                <th width="100"><?php echo Yii::t('app','Student Name');?></th> 
                                <?php }?>
                                <th width="100"><?php echo Yii::t('app','Exam Name');?></th> 
                                <th width="100"><?php echo Yii::t('app','Course');?></th> 
                                <?php if(in_array('batch_id', $student_visible_fields)){ ?>
                                <th width="100"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name');?></th>
                                <?php }?> 
                                <th rowspan="3" colspan="2" class="header-td1" width="400"><?php echo Yii::t('app','Subject');?></th>
                                <th  width="100"class="header-td1"><?php echo Yii::t('app','Score');?></th>
                                <th width="50"><?php echo Yii::t('app','Status');?></th>            
                            </tr>
                        </thead>
                    <?php
                    if($lists)
                    {                    
						if(isset($_REQUEST['page']))
						{
							$j=($pages->pageSize*$_REQUEST['page'])-9;
						}
						else
						{
							$j=1;
						}						
						$elective= array();
						$status	=0;
						
						foreach($lists as $list)
						{
							$student = Students::model()->findByAttributes(array('id'=>$list->student_id));
							$exam    = Exams::model()->findByAttributes(array('id'=>$list->exam_id));
							$min 	 = $exam->minimum_marks;
							$subject = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
							$group   = ExamGroups::model()->findByAttributes(array('id'=>$exam->exam_group_id));
							$batch   = Batches::model()->findByAttributes(array('id'=>$group->batch_id));
							$course  = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
							$batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
							
							?>	
                            <tbody>  
                            <?php if($subject->split_subject == 1){ ?>                              
                            <tr>
                                <td rowspan="3"><span ><?php echo $j;?></span></td>
                                  <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
                                  <td rowspan="3"><span ><?php if($batch_student!=NULL and $batch_student->roll_no!=0){
								  				echo $batch_student->roll_no;
								  			}
											else{
												echo '-';
											}?></span></td>
                                  <?php } ?>
                                 <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?> 
                                	<td rowspan="3"><span><?php echo $student->studentFullName("forStudentProfile"); ?></span></td>
                                  <?php }?> 
                                <td rowspan="3"><span ><?php echo $group->name;?></span></td>
                                <td rowspan="3"><span><?php echo $course->course_name;?></span></td>
                                <td rowspan="3"><span><?php echo $batch->name;?></span></td>
                                <td style="text-align:left" rowspan="3" class="header-td12"> <?php
									if($subject->elective_group_id==0)
								     {
									 ?>
                                    <?php echo $subject->name;?>
                                    <?php
									 }
									 else
									 {
										 
										 
									$electives = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id, 'elective_group_id'=>$subject->elective_group_id));
									$elective=Electives::model()->findByAttributes(array('id'=>$electives->elective_id));
									?>
                                    
                                       <?php echo "Elective/".$elective->name;?>                                   
									
							<?php	 }
							
								$subject_cps	=	ExamScoresSplit::model()->findAllByAttributes(array('exam_scores_id'=>$list->id));
								$mark_value=array();
								foreach($subject_cps as $subject_cp){
									$mark_value[]=$subject_cp->mark;
								} 
								$subjects_splits	=	SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$exam->subject_id));  
								$sub_value=array();
								foreach($subjects_splits as $subjects_split){
									$sub_value[]=$subjects_split->split_name;
								}  
									$mark_g='-';
									if($list->marks!=NULL)
									{
										if($list->marks<$min){
											$status = 1;
										}else
										{
											$status	=0;
										}
									
										//$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>$batch->id,'order'=>'min_score DESC'));
										$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>$batch->id),array('order'=>'min_score DESC'));
										//var_dump($grades);exit;
										if(!$grades)
										{
											$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>NULL));	
										}
										$t = count($grades);
										if($group->exam_type == 'Marks'){  
											$mark_g	=	$list->marks; 
										}else if($group->exam_type == 'Grades') {
											foreach($grades as $grade)
											{
												if($grade->min_score<=$list->marks)
												{
													$grade_value =  $grade->name;
												}
												else
												{
													$t--;
													continue;
												}
												$mark_g	=	$grade_value ;
												break;
											}
											if($t<=0) 
											{
												$glevel = " No Grades" ;
											} 
										} 
										else if($group->exam_type == 'Marks And Grades'){
											foreach($grades as $grade)
											{
												if($grade->min_score <= $list->marks)
												{	
													$grade_value =  $grade->name;
												}
												else
												{
													$t--;
													continue;
												}
												$mark_g	= $list->marks . " & ".$grade_value ;
												break;
												} 
												if($t<=0) 
												{
													$mark_g	=	$list->marks." & ".Yii::t('app',"No Grades") ;
												}
											
											}
										}
									 ?></td>
                                <td style="width: 130px" class="header-td12"><?php echo $sub_value[0];?></td>
                                <td><?php echo $mark_value[0];?></td> 
                                
                                <td rowspan="3"><span style="color:#006600"><?php 
										if($mark_g=='-')
										{
											echo "-";
										}else{
											if($status  == 0){
												echo "<span style='color:#006600'>".Yii::t('app','Passed').$roles."</span>";
											}else{
												echo "<span style='color:#F00'>".Yii::t('app','Failed')."</span>";
											}
										}
											
                                        ?></span></td>               
                            </tr>
                            <tr>           
                                <td  class="header-td2"><?php echo $sub_value[1];?></td>
                                <td><?php echo $mark_value[1];?></td>
                                
                            </tr>
                            </tr>
                            <tr>           
                                <td  class="header-td12"><?php echo Yii::t('app','Total');?></td>
                                <td> <?php echo $mark_g; ?></td>
                                
                            </tr> 
                            <?php }else{ ?>
                            <tr>
                                <td rowspan="3"><span ><?php echo $j;?></span></td>
                                 <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
                                  <td rowspan="3"><span ><?php if($batch_student!=NULL and $batch_student->roll_no!=0){
								  				echo $batch_student->roll_no;
								  			}
											else{
												echo '-';
											}?></span></td>
                                  <?php } ?>
                                 <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?> 
                                	<td rowspan="3"><span><?php echo $student->studentFullName("forStudentProfile"); ?></span></td>
                                    <?php }?> 
                                <td rowspan="3"><span ><?php echo $group->name;?></span></td>
                                <td rowspan="3"><span ><?php echo $course->course_name;?></span></td>
                                <td rowspan="3"><span ><?php echo $batch->name;?></span></td>
                                <td colspan="2"  rowspan="3" class="header-td12"> <?php
									if($subject->elective_group_id==0)
								     {
									 ?>
                                    <?php echo $subject->name;?>
                                    <?php
									 }
									 else
									 {
										 
										 
									$electives = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id, 'elective_group_id'=>$subject->elective_group_id));
									$elective=Electives::model()->findByAttributes(array('id'=>$electives->elective_id));
									?>
                                    
                                       <?php echo "Elective/".$elective->name;?>
                                   
									
							<?php	 }
									 ?></td>
                                <td rowspan="3">
                                    <?php 
									$mark_g='-';
									if($list->marks!=NULL)
									{
										if($list->marks<$min){
											$status = 1;
										}else
										{
											$status	=0;
										}
									
										//$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>$batch->id,'order'=>'min_score DESC'));
										$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>$batch->id),array('order'=>'min_score DESC'));
										//var_dump($grades);exit;
										if(!$grades)
										{
											$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>NULL));	
										}
										$t = count($grades);
										if($group->exam_type == 'Marks'){  
											$mark_g	=	$list->marks; 
										}else if($group->exam_type == 'Grades') {
											foreach($grades as $grade)
											{
												if($grade->min_score<=$list->marks)
												{
													$grade_value =  $grade->name;
												}
												else
												{
													$t--;
													continue;
												}
												$mark_g	=	$grade_value ;
												break;
											}
											if($t<=0) 
											{
												$glevel = " No Grades" ;
											} 
										} 
										else if($group->exam_type == 'Marks And Grades'){
											foreach($grades as $grade)
											{
												if($grade->min_score <= $list->marks)
												{	
													$grade_value =  $grade->name;
												}
												else
												{
													$t--;
													continue;
												}
												$mark_g	= $list->marks . " & ".$grade_value ;
												break;
												} 
												if($t<=0) 
												{
													$mark_g	=	$list->marks." & ".Yii::t('app',"No Grades") ;
												}
											
											}
										}
										echo $mark_g;
									?></td> 
                                <td rowspan="3"><?php 
										if($mark_g=='-')
										{
											echo "-";
										}else{
											if($status  == 0){
												echo "<span style='color:#006600'>".Yii::t('app','Passed').$roles."</span>";
											}else{
												echo "<span style='color:#F00'>".Yii::t('app','Failed')."</span>";
											}
										}
											
                                        ?></td> 
							</tr>   
							<?php
							}?>
                          
                            <?php
							$j++;
						 } ?>
                         </tbody><?php
                    }
                    else
                    {?>
                    <tr> 
                    <td colspan="9"><strong><?php echo Yii::t('app',' NO RESULTS');?></strong></td>
                    </tr>
                    <?php }	
                    ?>
                   
                    </table>  
                    </div>
                    <br />
    
