<style>
table.assessment_table{
	margin:30px 0px;
	font-size:8px;
	text-align:center;
	border-collapse:collapse;
	width:auto;
	/*max-width:600px;*/
}
table.assessment_table td{
	padding-top:10px; 
	padding-bottom:10px;
	border:1px  solid #C5CED9;
	width:auto;
	font-size:13px;
	
}

.assessment_table th{
	font-size:13px;
	padding:10px;
	border-left:1px  solid #C5CED9;
	border-bottom:1px  solid #C5CED9;
}

hr{ border-bottom:1px solid #C5CED9; border-top:0px solid #fff;}

</style>
<?php
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
if(isset($search_id) and $search_id=='1')
		{
			$flag = 0;
			
			if($course==0 and $search_id=='1' and $batch==0 and $group==0 and $exam==0)
			{   
			    
			    $flag=1;
				$criteria  = new CDbCriteria;
				
				$criteria->join		= ' JOIN `exams` `ee` ON `ee`.`id`=`t`.`exam_id`';
				$criteria->join		.= 'LEFT JOIN `exam_groups` `eg` ON `eg`.`id`=`ee`.`exam_group_id`';
				$criteria->condition="`eg`.is_published =:is_published";
	        	$criteria->params=array(':is_published'=>1);
				//$criteria->group = 'id';
				$criteria->order = 'student_id DESC';
				
				
				$lists=ExamScores::model()->findAll($criteria);
				
			}
			elseif($search_id=='1' and $course!=0 and $batch==0 and $group==0 and $exam==0)
			{ 			
				$flag=1;
				$course = $_GET['course'];
				//batc chek
				$batch_id  = array();
				$criteria  = new CDbCriteria;
				$criteria->condition    = '`is_active`=:is_active AND `is_deleted`=:is_deleted AND `course_id`=:course';
				$criteria->params		= array(':is_active'=>1,':is_deleted'=>0,':course'=>$course);
				$batches  = Batches::model()->findAll($criteria);
				foreach($batches as $batch){
					$batch_id[] = $batch->id;
				}
				
				$criteria  = new CDbCriteria;				
				$criteria->join		= ' JOIN `exams` `ee` ON `ee`.`id`=`t`.`exam_id`';
                $criteria->join		.= ' JOIN `students` `st` ON `st`.`id`=`t`.`student_id`';
				$criteria->join		.= 'LEFT JOIN `exam_groups` `eg` ON `eg`.`id`=`ee`.`exam_group_id`';
				$criteria->condition="`eg`.is_published =:is_published AND `st`.is_deleted=:is_deleted AND `st`.is_active=:is_active";
                $criteria->params=array(':is_published'=>1, ':is_deleted'=>0, ':is_active'=>1);
				$criteria->addInCondition('`eg`.batch_id',$batch_id);
				$criteria->order = 'student_id DESC';
				$lists = ExamScores::model()->findAll($criteria);
				
			}
			elseif($search_id=='1' and $batch!=0 and $group==0 and $exam==0)
			{ 
			   
				$flag=1;
				$batch = $batch;
				$students = Students::model()->findAllByAttributes(array('batch_id'=>$batch));
				
				$student_id = array();
				foreach($students as $student)
				{
				    $student_id[]= 	$student->id;
				}
				
				$criteria_1  = new CDbCriteria;
				$criteria_1->join		= ' JOIN `exams` `ee` ON `ee`.`id`=`t`.`exam_id`';
				$criteria_1->join		.= 'LEFT JOIN `exam_groups` `eg` ON `eg`.`id`=`ee`.`exam_group_id`';
				$criteria_1->condition="`eg`.is_published =:is_published";
	        	$criteria_1->params=array(':is_published'=>1);
				$criteria_1->addInCondition('student_id',$student_id);
				//$criteria_1->group = 'id';
				$criteria->order = 'student_id DESC';
				$lists=ExamScores::model()->findAll($criteria_1);
				
				
			}
			elseif($search_id =='1' and $batch!=0 and $group!=0 and $exam==0)
			{  
			    
			   
				$flag=1;
				$batch = $batch;
				$group = $group;
				
				$criteria_2  = new CDbCriteria;
				
				$criteria_2->join		= ' JOIN `subjects` `ss` ON `ss`.`id`=`t`.`subject_id`';
				$criteria_2->condition="`ss`.batch_id =:batch_id and exam_group_id = :exam_group_id";
				$criteria_2->params=array(':batch_id'=>$batch,':exam_group_id'=>$group);
				
				$exams = Exams::model()->findAll($criteria_2);
				
				$exams_id = array();
				foreach($exams as $exam)
				{
				    $exams_id[]= 	$exam->id;
				}
				$criteria_1  = new CDbCriteria;
				$criteria_1->join		= ' JOIN `exams` `ee` ON `ee`.`id`=`t`.`exam_id`';
				$criteria_1->join		.= 'LEFT JOIN `exam_groups` `eg` ON `eg`.`id`=`ee`.`exam_group_id`';
				$criteria_1->condition="`eg`.is_published =:is_published";
	        	$criteria_1->params=array(':is_published'=>1);
				
				$criteria_1->addInCondition('exam_id',$exams_id);
				//$criteria_1->group = 'id';
				$criteria_1->order = 'student_id DESC';
				$lists=ExamScores::model()->findAll($criteria_1);
				
				
			}
			elseif($search_id=='1' and $batch!=0 and $group!=0 and $exam!=0 and $course!=0)
			{  
			    
				$flag=1;
				
				$exams = Exams::model()->findAllByAttributes(array('exam_group_id'=>$group,'subject_id'=>$exam));
				
				$exams_id = array();
				foreach($exams as $exam)
				{
				    $exams_id[]= 	$exam->id;
				}
				
				$criteria_1  = new CDbCriteria;
				$criteria_1->join		= ' JOIN `exams` `ee` ON `ee`.`id`=`t`.`exam_id`';
				$criteria_1->join		.= 'LEFT JOIN `exam_groups` `eg` ON `eg`.`id`=`ee`.`exam_group_id`';
				$criteria_1->condition="`eg`.is_published =:is_published";
	        	$criteria_1->params=array(':is_published'=>1);
				$criteria_1->addInCondition('exam_id',$exams_id);
				//$criteria_1->group = 'id';
				$criteria_1->order = 'student_id DESC';
				
				$lists=ExamScores::model()->findAll($criteria_1);
				
				
			}
		}

?>
	<!-- Header -->
    
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="first" width="100">
                           <?php  $filename=  Logo::model()->getLogo();
							if($filename!=NULL)
                            {
                                //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                echo '<img src="uploadedfiles/school_logo/'.$filename[2].'" alt="'.$filename[2].'" class="imgbrder" height="100" />';
                            }
                            ?>
                </td>
                <td valign="middle">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:22px; padding-left:10px;">
                                <?php $college=Configurations::model()->findAll(); ?>
                                <?php echo $college[0]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo $college[1]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo Yii::t('app','Phone: ').$college[2]->config_value; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    <hr />
    <br />
	<!-- End Header -->

	<?php
    if(isset($lists))
    {  
	
   ?>
   
    <div align="center" style="text-align:center; display:block;"><?php echo Yii::t('app','EXAM RESULT REPORT'); ?></div>
    
    <!-- Single Exam Table -->
    <table width="100%" cellspacing="0" cellpadding="0" class="assessment_table">
        <tr class="tablebx_topbg" style="background-color:#DCE6F1;">
            <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>                                      
            <th style="text-align:center"><?php echo Yii::t('app','Student Name');?></th>
            <?php } ?>
            <th style="width:130px;"><?php echo Yii::t('app','Exam Name');?></th>
            <th style="width:130px;"><?php echo Yii::t('app','Course');?></th>
            <?php if(in_array('batch_id', $student_visible_fields)){ ?>
            <th align="center"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name');?></th>
            <?php } ?>
            <th style="width:150px;" colspan="2"><?php echo Yii::t('app','Subject');?></th>
            <th style="width:130px;"><?php echo Yii::t('app','Mark');?></th>
            <th><?php echo Yii::t('app','Status');?></th>
        </tr> 
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
				
				?>	 
				<?php if($subject->split_subject == 1){ 
				
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
				
				?>
                        <tr>
                        <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?> 
                            <td rowspan="3" ><?php
							
								if($student->id !=NULL)
								echo $student->studentFullName("forStudentProfile"); ?></td>
                             <?php }?>
                            <td rowspan="3" > <?php echo $group->name;?></td>
                            <td rowspan="3" ><?php echo $course->course_name;?> </td>
                            <td rowspan="3" ><?php echo $batch->name;?></td>
                            <td rowspan="3" ><?php
									if($subject->elective_group_id==0)
								     { echo $subject->name; 
									 }
									 else
									 {
						                 $electives = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id, 'elective_group_id'=>$subject->elective_group_id));
										$elective=Electives::model()->findByAttributes(array('id'=>$electives->elective_id));
									
										 
										 echo "Elective/".$elective->name;?>
                                   
									
							<?php	 }
									 ?></td>
                            <td><?php echo $sub_value[0];?></td>
                            <td><?php echo $mark_value[0];?></td>
                            <td rowspan="3" ><?php 
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
                        <tr>
                            <td><?php echo $sub_value[1];?></td>
                            <td><?php echo $mark_value[1];?></td>
                        </tr>
                        <tr>
                            <td><?php echo Yii::t('app','Total');?></td>
                            <td><?php 
										echo $mark_g;
									?></td>
                        </tr>
                    <?php }else{
						?>
                        <tr>
                       		<?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                            	<td><?php echo $student->studentFullName("forStudentProfile"); ?></td>
                             <?php }?>
                            <td> <?php echo $group->name;?></td>
                            <td><?php echo $course->course_name;?></td>
                            <td><?php echo $batch->name;?></td>
                            <td colspan="2"><?php
									if($subject->elective_group_id==0)
								     { echo $subject->name; 
									 }
									 else
									 {
						                 $electives = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id, 'elective_group_id'=>$subject->elective_group_id));
										$elective=Electives::model()->findByAttributes(array('id'=>$electives->elective_id));
									
										 
										 echo "Elective/".$elective->name;?>
                                   
									
							<?php	 }
									 ?></td>
                            <td><?php 
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
                            <td><?php 
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
					}
			}
		}?>  
        
    </table>
    <!-- END Single Exam Table -->
   
   <?php
    }
    ?>
    
    
    
