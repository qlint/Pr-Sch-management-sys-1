<?php
/*$criteria= new CDbCriteria;
$criteria->join= 'JOIN cbsc_exams_17 `t1` ON t1.exam_group_id=`t2`.id JOIN cbsc_exam_group_17 `t2` ON t2.id=`t`.id';
$criteria->condition= '`t1`.course_id=:course_id AND t.academic_yr_id=:academic_yr_id';
$criteria->params= array(':course_id'=>$course->id, ':academic_yr_id'=>Yii::app()->user->year);
$fee_types = FeeTypes::model()->findAll($criteria);*/?>

<?php
$criteria = new CDbCriteria;			
$criteria->condition='student_id=:x';
$criteria->params = array(':x'=>$student->id);
$examscores = CbscExamScores17::model()->findAll($criteria);

?>
<div class="table-responsive scrollbox3" style="height:200px">
	<table class="table table-invoice">
		<tr>
			<th width="30%"  style="text-align:left" height="35"><?php echo Yii::t('app','Exam');?></th>
			<th  width="30%"  style="text-align:left" height="35"><?php echo Yii::t('app','Subject');?></th>
			<th  width="30%" style="text-align:left"><?php echo Yii::t('app','Mark');?></th>
		</tr>
		<?php foreach($examscores as $examscore)
			  {
				$exam=CbscExams17::model()->findByAttributes(array('id'=>$examscore->exam_id));
				$group=CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
				$subjects= Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));?>
				<tr>
					<td width="30%" style="text-align:left"><?php echo $group->name ; ?></td>
					<td width="30%" style="text-align:left"><?php echo $subjectname ; ?></td>
					<td width="30%" style="text-align:left"><?php echo $subjectname ; ?></td>
				</tr>
		<?php } ?>
	</table>
</div>
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
	