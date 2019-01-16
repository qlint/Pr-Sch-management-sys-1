<style type="text/css">
th{ text-align:center;}
</style>

<?php
	echo $this->renderPartial('/default/leftside');
	$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	// Get unique batch ID from Timetable. Checking if the employee is teaching.
	$criteria=new CDbCriteria;
	$criteria->select= 'id';
	$criteria->distinct = true;
	$criteria->condition='employee_id=:emp_id';
	$criteria->params=array(':emp_id'=>$employee->id);
	$class_teacher = Batches::model()->findAll($criteria);
	$class_count = count($class_teacher);
?>
<div class="pageheader">
      <h2><i class="fa fa-pencil"></i> <?php echo Yii::t('app', 'Exams');?> <span><?php echo Yii::t('app', 'View your exams here');?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app', 'You are here:');?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
          <li class="active"><?php echo Yii::t('app', 'Exams');?></li>
        </ol>
   </div>
</div>
<div class="contentpanel">    
	<div class="panel-heading">
    	
		<h3 class="panel-title"><?php echo Yii::t('app', 'All Exam Details'); ?></h3>
	</div>
    <div class="people-item">

    <div class="opnsl_headerBox">
    <div class="opnsl_actn_box"> </div>
    <div class="opnsl_actn_box">
<?php if($class_count>0){ ?>      
                	<?php echo CHtml::link('<span>'.Yii::t('app','My Class').'</span>',array('/teachersportal/exams/classexam'),array('class'=>'addbttn last'));?>               
                <?php } ?>
	</div>
</div>
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
		$criteria->params=array(':emp_id'=>$employee->id);
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
    	<div class="table-responsive">
           <table class="table table-bordered mb30">
                <thead>
                    <tr class="pdtab-h">
                        <th align="center"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name');?></th>
                        <th align="center"><?php echo Yii::t('app','Class Teacher');?></th>
                        <th align="center"><?php echo Yii::t('app','Actions');?></th>
                    </tr>
                    </thead>
                    <tbody>
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
						 $cbsc_format    = ExamFormat::getCbscformat($batch->id);
						 $exam_format	 = ExamFormat::model()->getExamformat($batch->id);// 1=>normal 2=>cbsc	
						if($exam_format == 1){
							if($exams_published > 0 or $result_published > 0){
								echo CHtml::link(Yii::t('app','View Examinations'), array('/teachersportal/exams/allexams','bid'=>$batch->id),array('class'=>'view_Exmintn_atg Exm_aTgColor_b'));
							}
							else{
								echo Yii::t('app','No Exam Scheduled');
							}
							}else if($cbsc_format){//cbsc							
								echo CHtml::link(Yii::t('app','View Examinations'), array('/teachersportal/exam17/allexams','bid'=>$batch->id),array('class'=>'view_Exmintn_atg Exm_aTgColor_b'));																	
							}
							echo "&nbsp;&nbsp;&nbsp;".CHtml::link(Yii::t('app','Online Exam'), array('/onlineexam/exam','bid'=>$batch->id),array('class'=>'view_Exmintn_atg Exm_aTgColor_g'));
							if(ExamFormat::model()->getExamformat($batch->id)==2 && ($teacher_id==Yii::app()->user->id))
							{
								echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;";
								echo CHtml::link(Yii::t('app','View Co-scholastic Skills'), array('/teachersportal/coScholastic/index','bid'=>$batch->id),array('class'=>'view_Exmintn_atg Exm_aTgColor_v'));
								echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;";
								echo CHtml::link(Yii::t('app','View Results'), array('/teachersportal/exams/results','bid'=>$batch->id),array('class'=>'view_Exmintn_atg Exm_aTgColor_y'));
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
</div>