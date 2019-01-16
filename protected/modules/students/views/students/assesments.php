<?php
$this->breadcrumbs=array(
	Yii::t('app','Students')=>array('index'),
	Yii::t('app','Assessments'),
);


?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <div class="emp_cont_left">
    <?php $this->renderPartial('profileleft');?>
    
    </div>
    </td>
    <td valign="top">
    <div class="cont_right formWrapper">
    <h1><?php echo Yii::t('app','Student Profile');?></h1>
    <div class="clear"></div>
    <div class="emp_right_contner">
    <div class="emp_tabwrapper">
    <?php $this->renderPartial('application.modules.students.views.students.tab');?>
    <div class="clear"></div>
    <div class="emp_cntntbx" >
    <?php
	$exam = ExamScores::model()->findAll("student_id=:x", array(':x'=>$_REQUEST['id']));
	$cbscexam = CbscExamScores17::model()->findAll("student_id=:x", array(':x'=>$_REQUEST['id']));
	?>
    <div class="tableinnerlist">
    <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
	<th><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></th>
    <th><?php echo Yii::t('app','Exam Group Name');?></th>
    <th><?php echo Yii::t('app','Subject');?></th>
    <th><?php echo Yii::t('app','Score');?></th>
    <th><?php echo Yii::t('app','Status');?></th>
    </tr>
    <?php
	
	if($cbscexam!=NULL)
	{
		foreach($cbscexam as $cbscexams)
		{
			?><tr><?php
            $cbexm=CbscExams17::model()->findByAttributes(array('id'=>$cbscexams->exam_id));
			$cbgroup=CbscExamGroup17::model()->findByAttributes(array('id'=>$cbexm->exam_group_id));
			$batch=Batches::model()->findByAttributes(array('id'=>$cbgroup->batch_id));
			if($cbgroup!=NULL)
			{
				$exam_format	 = ExamFormat::model()->getExamformat($cbgroup->batch_id);// 1=>normal 2=>cbsc	
				if($cbgroup->class == 1){
						$grade = CbscExamScores17::model()->getClass1Grade($cbscexams->total);
					}
					else{
						$grade = CbscExamScores17::model()->getClass2Grade($cbscexams->total);
					}
				if($exam_format == 2){
					?><td><?php echo $batch->name;?></td>
					  <td><?php echo $cbgroup->name;?></td><?php
					 $subject=Subjects::model()->findByAttributes(array('id'=>$cbexm->subject_id));
					?><td><?php echo $subject->name;?></td><?php
					?>
                      <td><?php echo $cbscexams->total." & ".$grade;?></td>
					  <?php
					if($cbscexams->total >= $cbexm->minimum_marks)
					echo '<td>'.Yii::t('app','Passed').'</td>';
					else
					echo '<td>'.Yii::t('app','Failed').'</td>';
				}
			}
			?>
		</tr>
        <?php
		}
	}
	if($exam!=NULL){
		foreach($exam as $exams)
		{
			echo '<tr>';
			$exm=Exams::model()->findByAttributes(array('id'=>$exams->exam_id));
			$group=ExamGroups::model()->findByAttributes(array('id'=>$exm->exam_group_id));
			$batch=Batches::model()->findByAttributes(array('id'=>$group->batch_id));
			if($group!=NULL)
			{
                            $criteria=new CDbCriteria;                          
                            $criteria->condition= 'batch_id=:batch_id';                                                        
                            $criteria->params= array(':batch_id'=>$group->batch_id);
                            $criteria->order='min_score DESC'; 
                            //$grades = GradingLevels::model()->findAllByAttributes(array('batch_id'=>$exams->grading_level_id));
			$grades = GradingLevels::model()->findAll($criteria);                        
			$t = count($grades);
			
            $exam_format	 = ExamFormat::model()->getExamformat($group->batch_id);// 1=>normal 2=>cbsc
				if($exam_format == 1){
					echo '<td>'.$batch->name.'</td>';
					echo '<td>'.$group->name.'</td>';
					$sub=Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
					if($sub->elective_group_id != 0)
					{
						$elective_name = Electives::model()->findByAttributes(array('elective_group_id'=>$sub->elective_group_id));
						$electives = StudentElectives::model()->findByAttributes(array('student_id'=>$_REQUEST['id'], 'elective_group_id'=>$sub->elective_group_id));
						$elct_group=Electives::model()->findByAttributes(array('id'=>$electives->elective_id));
						
						echo '<td>'.ucfirst($sub->name).'-'.$elct_group->name.' '.Yii::t('app','( Elective )').'</td>';
					}
					else
						echo '<td>'.ucfirst($sub->name).'</td>';
					if($group->exam_type == 'Marks') {
						$exams->marks=number_format($exams->marks);  
						  echo "<td>".$exams->marks."</td>"; } 
						  else if($group->exam_type == 'Grades') {
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
										$glevel = Yii::t('app',"No Grades") ;
									} 
								echo "</td>"; 
								} 
						   else if($group->exam_type == 'Marks And Grades'){
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
								echo $exams->marks . " & ".$grade_value ;
								break;
								
									
								} 
								if($t<=0) 
									{
										echo $exams->marks." & ".Yii::t('app',"No Grades") ;
									}
								echo "</td>"; } 
					if($exams->is_failed==NULL)
					echo '<td>'.Yii::t('app','Passed').'</td>';
					else
					echo '<td>'.Yii::t('app','Failed').'</td>';
					echo '</tr>';
					} // end if($examformat)
			}
		}
	}
        
        if($cbscexam==NULL && $exam==NULL){
            ?>
                <tr>
                    <td colspan="5">
                        <?php echo Yii::t('app','No Data Found'); ?>
                    </td>
                </tr>
            <?php            
        }
	?>
 </table>
    </div>
 
    <br />
    
    </div>
    </div>
    
    </div>
    </div>
   
    </td>
  </tr>
</table>