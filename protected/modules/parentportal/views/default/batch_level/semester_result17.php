<div class="table-responsive">                        
    <table class="table table-hover mb30">
    <?php 

	$ex_group= CbscExamGroup17::model()->findByPk($ex_group_id);
	if($ex_group->class ==4 ){
		?>
        <tr>   
        	<th><?php echo Yii::t('app','Sl no');?></th>         
            <th width="30%"><?php echo Yii::t('app','Subject');?></th>
            <th width="25%"><?php echo Yii::t('app','Score');?></th>
            <th><?php echo Yii::t('app','Remarks');?></th>
            <th><?php echo Yii::t('app','Grade');?></th>
        </tr>
        <?php
		}else{
			?>
            <tr>  
       		     <th><?php echo Yii::t('app','Sl no');?></th>           
                <th><?php echo Yii::t('app','Subjects');?></th>
                <th><?php echo Yii::t('app','Periodic Test');?></th>
                <th><?php echo Yii::t('app','Note Book');?></th>
                <th><?php echo Yii::t('app','Subject Enrichment');?></th>
                <th><?php echo Yii::t('app','Written Exam');?></th>
                <th><?php echo Yii::t('app','Mark Obtained');?></th> 
				<th><?php echo Yii::t('app','Remarks');?></th>  
                <th><?php echo Yii::t('app','Grade');?></th>     
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
				$i=1;
				foreach($exams as $exam)
				{
					$subject = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
					if($subject!=NULL) // Checking if exam for atleast subject is created.
					{
						$scores = CbscExamScores17::model()->findByAttributes(array('exam_id'=>$exam->id,'student_id'=>$student->id));
						if($scores!=NULL)                            
						{
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
							  }
								  ?>
                                  <td><?php echo $scores->total; ?></td>
                                  <td><?php 
								  if($scores->remarks!=NULL){
								  	echo ucfirst($scores->remarks);
								  }
								  else{
									  echo '-';
								  }
								   ?></td>
                               
							  
							
								<td><?php 
								 if($ex_group->class == 1){
											  	   echo CbscExamScores17::model()->getClass1Grade($scores->total);
												  
											  }
											  else{
												   echo CbscExamScores17::model()->getClass2Grade($scores->total);
												   
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
    </table>
</div> 