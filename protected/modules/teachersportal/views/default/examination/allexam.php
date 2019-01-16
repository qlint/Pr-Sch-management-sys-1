<div>
	<?php 
	/*If $flag = 1, list of batches will be displayed. 
	 *If $flag = 2, exam schedule page will be displayed.
	 *If $flag = 3, exam result page will be displayed.
	 *If $flag = 0, Employee not teaching in any batch. A message will be displayed.
	*/
    if($_REQUEST['id']!=NULL){
			
	 }
	else{
		// Get unique batch ID from Timetable
		$criteria=new CDbCriteria;
		$criteria->select= 'batch_id';
		$criteria->distinct = true;
		// $criteria->order = 'batch_id ASC'; Uncomment if ID should be retrieved in ascending order
		$criteria->condition='employee_id=:emp_id';
		$criteria->params=array(':emp_id'=>$employee_id);
		$batches_id = TimetableEntries::model()->findAll($criteria);
		if(count($batches_id) >= 1){ // List of batches is needed
			$flag = 1;
		}
		elseif(count($batches_id) <= 0){ // If not teaching in any batch
			$flag = 0;
			
		}
	}
	
	
	if($flag == 0){ // Displaying message
	?>
    <div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;margin-top:60px;">
        <div class="y_bx_head">
           <?php echo Yii::t('app','No period is assigned to you now!'); ?>
        </div>      
    </div>
    <?php
	}
	if($flag == 1){ // Displaying batches the employee is teaching.
	?>
    	<div class="pdtab_Con">
            <table width="80%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr class="pdtab-h">
                        <td align="center"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name');?></td>
                        <td align="center"><?php echo Yii::t('app','Class Teacher');?></td>
                        <td align="center"><?php echo Yii::t('app','Actions');?></td>
                    </tr>
                    <?php 
					foreach($batches_id as $batch_id)
					{
						$batch=Batches::model()->findByAttributes(array('id'=>$batch_id->batch_id,'is_active'=>1,'is_deleted'=>0));
						echo '<tr id="batchrow'.$batch->id.'">';
						/*echo '<td style="text-align:center; padding-left:10px; font-weight:bold;">'.CHtml::link($batch->name, array('/teachersportal/default/employeetimetable','id'=>$batch->id)).'</td>';*/
						echo '<td style="text-align:center; padding-left:10px; font-weight:bold;">'.$batch->coursename.'</td>';
						$teacher = Employees::model()->findByAttributes(array('id'=>$batch->employee_id));					
						echo '<td align="center">';
						if($teacher){
							echo Employees::model()->getTeachername($teacher->id);
						}
						else{
							echo '-';
						}
						// Count if any exam timetables are published in a batch.
						$exams_published = ExamGroups::model()->countByAttributes(array('batch_id'=>$batch->id,'is_published'=>1));
						// Count if any exam results are published in a batch.
						$result_published = ExamGroups::model()->countByAttributes(array('batch_id'=>$batch->id,'result_published'=>1));
						echo '<td align="center">';
						if($exams_published > 0 or $result_published > 0){
							echo CHtml::link(Yii::t('app','View Examinations'), array('/teachersportal/default/allexam','bid'=>$batch->id));
						}
						else{
							echo Yii::t('app','No Exam Scheduled');
						}
						echo '</td>';
						
						
						echo '</tr>';
					}
					?>
                </tbody>
            </table>
		</div>
	<?php
	}
	?>
</div>