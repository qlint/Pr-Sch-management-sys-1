<table width="100%" cellspacing="0" cellpadding="0" class="attendance_table">
    <?php 

	$ex_group= CbscExamGroup17::model()->findByPk($ex_group_id);
	if($ex_group->class ==4 ){
		?>
       <tr class="tablebx_topbg" style="background-color:#DCE6F1;"> 
        	 <td style="width:150px;"><?php echo Yii::t('app','Sl no');?></td>       
             <td style="width:150px;"><?php echo Yii::t('app','Subject');?></td>
             <td style="width:150px;"><?php echo Yii::t('app','Score');?></td>
             <td style="width:150px;"><?php echo Yii::t('app','Remarks');?></td>
             <td style="width:150px;"><?php echo Yii::t('app','Grade');?></td>
        </tr>
        <?php
		}else{
			?>
           <tr class="tablebx_topbg" style="background-color:#DCE6F1;"> 
                <td style="width:150px;"><?php echo Yii::t('app','Sl no');?></td>         
                <td style="width:150px;"><?php echo Yii::t('app','Subjects');?></td>
                <td style="width:150px;"><?php echo Yii::t('app','Periodic Test');?></td>
                <td style="width:150px;"><?php echo Yii::t('app','Note Book');?></td>
                <td style="width:150px;"><?php echo Yii::t('app','Subject Enrichment');?></td>
                <td style="width:150px;"><?php echo Yii::t('app','Third Term Exam');?></td>
                <td style="width:150px;"><?php echo Yii::t('app','Mark Obtained');?></td>  
				<td style="width:150px;"><?php echo Yii::t('app','Remarks');?></td> 
                <td style="width:150px;"><?php echo Yii::t('app','Grade');?></td>     
            </tr>
            <?php
        } 
        if($exams==NULL)
        {
            echo '<tr><td align="center" colspan="4">'.Yii::t('app','No Results Found').'</td></tr>';	
        }
        else
        {  
			$exams = CbscExams17::model()->findAll('exam_group_id=:x',array(':x'=>$ex_group->id)); // Selecting exams(subjects) in an exam group
			if($exams!=NULL)
			{
				$i=1;$k=0;
				foreach($exams as $exam)
				{
					$subject = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
					if($subject!=NULL) // Checking if exam for atleast subject is created.
					{
						$scores = CbscExamScores17::model()->findByAttributes(array('exam_id'=>$exam->id,'student_id'=>$student->id));
						if($scores!=NULL)                            
						{
							$k++;
							?>                        
							<tr>
								<td><?php echo $i++;?></td>
								<td>
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
									?>
								</td>
                              <?php
							  if($ex_group->class !=4 ){
								  ?>
								<td><?php echo $scores->periodic_test; ?></td>
								<td><?php echo $scores->note_book; ?></td>       
								<td><?php echo $scores->subject_enrichment; ?></td>
								<td><?php echo $scores->written_exam; ?></td>
                               <?php
							  }?>
                                  
								<td><?php echo $scores->total; ?></td>
                              	<td><?php echo ($scores->remarks)?$scores->remarks:"-"; ?></td> 
                               
								<td><?php 
								 if($ex_group->class == 1){
											  	   echo CbscExamScores17::model()->getClass1Grade($scores->total);
												  
											  }
											  else{
												   echo CbscExamScores17::model()->getClass2Grade($scores->total);
												   
											  }
									 ?>
								</td>                
							</tr>
							<?php
						}
					}
				 } 
		
		} 
	}
	if($k==0){
		if($ex_group->class !=4 ){
			echo '<tr><td align="center" colspan="8">'.Yii::t('app','No Results Found').'</td></tr>';
		}else{			
			 echo '<tr><td align="center" colspan="4">'.Yii::t('app','No Results Found').'</td></tr>';
		}
	}
        ?>    
    </table>
</div> 