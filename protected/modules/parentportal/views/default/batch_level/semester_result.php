<div class="table-responsive">                        
    <table class="table table-hover mb30">
        <tr>            
            <th width="30%"><?php echo Yii::t('app','Subject');?></th>
            <th width="25%"><?php echo Yii::t('app','Score');?></th>
            <th><?php echo Yii::t('app','Remarks');?></th>
            <th><?php echo Yii::t('app','Result');?></th>
        </tr>
        <?php
        if($exam==NULL)
        {
            echo '<tr><td align="center" colspan="4">'.Yii::t('app','No Results Found').'</td></tr>';	
        }
        else
        {
			$batch_id = $batch->id;
            $displayed_flag = '';
            foreach($exam as $exams)
            {
				if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2){ //cbsc
					$exm=CbscExams17::model()->findByAttributes(array('id'=>$exams->exam_id));
					$group=CbscExamGroup17::model()->findByAttributes(array('id'=>$exm->exam_group_id,'result_published'=>1));
				}
				else{
					$exm=Exams::model()->findByAttributes(array('id'=>$exams->exam_id));
					$group=ExamGroups::model()->findByAttributes(array('id'=>$exm->exam_group_id,'result_published'=>1));
				}

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
                                    //echo '<td>'.$group->name.'</td>';
                                    $sub=Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));

                                    if($sub->elective_group_id!=0 and $sub->elective_group_id!=NULL)
                                    {
                                            $student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$batch->id));

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
									
if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2){ //cbsc
	if($exams->marks!=NULL){ 
				echo "<td>".$exams->marks."</td>"; 
			}
			else{
				echo '-';
			}
}
else{

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
				echo $exams->marks." & ".$grade_value ;
				break;
			}
            if($t<=0) 
                    {
                            echo $exams->marks." & ".Yii::t('app','No Grades');
                    }
            echo "</td>"; 
		} 
		
}                       
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