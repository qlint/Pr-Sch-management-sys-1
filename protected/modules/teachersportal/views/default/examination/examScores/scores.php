<div style="width:800px; padding-left:20px;"><br/><br/>
<?php
	/*echo "Employee ID: ".$employee_id.'<br/>';
	echo "Batch ID: ".$batch_id.'<br/>';
	echo "Exam Group ID: ".$exam_group_id.'<br/>';
	echo "Exam(Subject) ID: ".$exam_id.'<br/>';*/
	$batch=Batches::model()->findByAttributes(array('id'=>$batch_id));
        if($batch!=NULL)
		   { ?>
               <div class="formCon"> <!-- Batch Details Tab -->
					<div class="formConInner">
                    	<table cellspacing="5px">
                        	<tr>
                            	<td>
                       				<strong><?php echo Yii::t('app','Course');?>:</strong>
									<?php $course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
                                    if($course!=NULL)
                                       {
                                           echo $course->course_name; 
                                       }?>
                               </td>
                               <td>
                                    <strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?>: </strong><?php echo $batch->name; ?>
                        		</td>
                        	</tr>
                            <tr>
							<?php if($exam_group_id!=NULL)
                            { 
								$exam=ExamGroups::model()->findByAttributes(array('id'=>$exam_group_id,'batch_id'=>$batch_id));
							?>
								<td>
									<strong><?php echo Yii::t('app','Exam'); ?>: </strong><?php echo $exam->name; ?>
								</td>
                            <?php 
                            }
							if($exam_id!=NULL)
							{ 
								$subject_id=Exams::model()->findByAttributes(array('id'=>$exam_id));
								$subject = Subjects::model()->findByAttributes(array('id'=>$subject_id->subject_id));
							?>
								<td>
									<strong><?php echo Yii::t('app','Subject'); ?>: </strong><?php echo $subject->name;  ?>
								</td>
							<?php
							}
							?>
                        	</tr>
                            <tr>
                            <?php
							$empid = EmployeesSubjects::model()->findByAttributes(array('subject_id'=>$subject_id->subject_id));
							if(count($empid)>0){
								$subject_teacher = Employees::model()->findByAttributes(array('id'=>$empid->employee_id));
							?>
								<td>
                                	<strong><?php echo Yii::t('app','Subject Teacher'); ?>: </strong><?php echo Employees::model()->getTeachername($subject_teacher->id); ?>
								</td>
							<?php
							}
							$is_classteacher=Batches::model()->findByAttributes(array('id'=>$batch_id));
							$classteacher = Employees::model()->findByAttributes(array('id'=>$is_classteacher->employee_id));
							if(Yii::app()->controller->action->id=='classexam' and $classteacher->id != $employee_id){ // Redirecting if action ID is classexam and the employee is not classteacher
								$this->redirect(array('/teachersportal/default/examination'));
							}
							if(count($classteacher)>0){
							?>
                            	<td>
                                	<strong><?php echo Yii::t('app','Class Teacher'); ?>: </strong><?php echo Employees::model()->getTeachername($classteacher->id); ?>
								</td>
                            <?php
							}
							?>
                            </tr>
                        </table>
					</div>
          	</div>    
    	<?php 
		   }?>
           <div class="edit_bttns" style="top:150px; right:50px;">
        <ul>
        	<?php
			if(Yii::app()->controller->action->id=='allexam')
			{
				$url = '/teachersportal/default/allexam';
				
			}
			elseif(Yii::app()->controller->action->id=='classexam')
			{
				$url = '/teachersportal/default/classexam';
				
			}
			if($exam_id!=NULL)
			{
			?>
            <li><span>
            <?php 
				echo CHtml::link(Yii::t('app','View Subject List'), array($url,'bid'=>$batch_id,'exam_group_id'=>$exam_group_id,'r_flag'=>1),array('id'=>'add_exam-groups','class'=>'addbttn')); 
			
			?></span>
        	</li>
            <?php
			}
			if($exam_group_id!=NULL)
			{
			?>
            <li><span>
            <?php 
				echo CHtml::link(Yii::t('app','View Exam List'), array($url,'bid'=>$batch_id),array('id'=>'add_exam-groups','class'=>'addbttn')); 
			
			?></span>
        	</li>
            <?php
			}
			?>
            <li><span>
        	<?php echo CHtml::link(Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"), array($url),array('id'=>'add_exam-groups','class'=>'addbttn')); ?>
        	</span></li>
        </ul>
        <div class="clear"></div>
    </div>
    
    <?php
	$result_published = ExamGroups::model()->countByAttributes(array('id'=>$exam_group_id,'result_published'=>1));
	$is_teaching_subject = TimetableEntries::model()->countByAttributes(array('subject_id'=>$subject_id->subject_id,'employee_id'=>$employee_id));
	$score_flag = 0; // If $score_flag == 0, form for entering scores will not be displayed. If $score_flag == 1, form will be displayed.
	if((Yii::app()->controller->action->id=='classexam' and ($classteacher->id == $employee_id)) or (Yii::app()->controller->action->id=='allexam' and $is_teaching_subject >0))
	{ // Class teacher can enter scores for all subjects in their batch.
		$score_flag = 1; 
	}
	if(Yii::app()->controller->action->id=='allexam' and $is_teaching_subject<=0)
	{
		$score_flag = 0;
	}
	/*echo 'Result Published: '.$result_published.'<br/>';
	echo 'Is Teaching Subject: '.$is_teaching_subject.'<br/>';
	echo 'Score Flag: '.$score_flag.'<br/>';*/
	if($score_flag==1)
	{
	?>
	<!-- Start Enter Exam Scores -->
    <div class="formCon">
        <div class="formConInner">
        <?php
		$model=new ExamScores;
        if(isset($batch_id))
		{
			$students=Students::model()->findAll("batch_id=:x and is_active=:y and is_deleted=:z", array(':x'=>$batch_id,':y'=>1,':z'=>0));
			if($students!=NULL)
    		{
				if(Yii::app()->controller->action->id=='classexam'){
					$actionUrl = CController::createUrl('/teachersportal/default/addscores',array("bid"=>$batch_id,"exam_group_id"=>$exam_group_id,"r_flag"=>1,"exam_id"=>$exam_id));
				}
				elseif(Yii::app()->controller->action->id=='allexam'){
					$actionUrl = CController::createUrl('/teachersportal/default/addscores',array("bid"=>$batch_id,"exam_group_id"=>$exam_group_id,"r_flag"=>1,"exam_id"=>$exam_id,"allexam"=>1));
				}
				$form=$this->beginWidget('CActiveForm', array(
				'id'=>'exam-scores-form',
				'action' => $actionUrl,
				'enableAjaxValidation'=>false,
				));
		?>
				<h3><?php echo Yii::t('app', 'Enter Exam Scores here:');?></h3>
    			<?php echo $form->hiddenField($model,'exam_id',array('value'=>$exam_id)); ?>
                <div>
                	<table width="100%" cellspacing="0" cellpadding="0">
                    <?php 
					$i=1;
	  				$j=0;
	  				foreach($students as $student){ 
						$checksub = ExamScores::model()->findByAttributes(array('exam_id'=>$exam_id,'student_id'=>$student->id));
						if($checksub==NULL){ //No score entered for student with student_id '$student->id'.
							if($j==0)
							{
					?>
                    			<tr>
                                    <th><?php echo Yii::t('app','Student Name');?></th>
                                    <th><?php echo Yii::t('app','Marks');?></th>
                                    <th><?php echo Yii::t('app','Remarks');?></th>
                             	</tr>
                              	<tr><td>&nbsp;</td></tr>
                    <?php 
								$j++;
							} 
					?>
                    			<tr>
                                	<td><?php echo ucfirst($student->first_name).' '.ucfirst($student->last_name);?>
										<?php echo $form->hiddenField($model,'student_id[]',array('value'=>$student->id,'id'=>$student->id)); ?>
									</td>
                                    <td><?php echo $form->textField($model,'marks[]',array('size'=>7,'maxlength'=>7,'id'=>$student->id)); ?>
                                    </td>
                                    <td><?php echo $form->textField($model,'remarks[]',array('size'=>60,'maxlength'=>255,'id'=>$student->id)); ?></td>
								</tr>
        						<tr><td>&nbsp;</td></tr>
                                <?php 
									echo $form->hiddenField($model,'grading_level_id');
									echo $form->hiddenField($model,'created_at',array('value'=>date('Y-m-d')));
		  							echo $form->hiddenField($model,'updated_at',array('value'=>date('Y-m-d'))); 
								?>
                    <?php 
							$i++;	
						}
					}
					?>
                    </table>
                    <br />
					<?php 
					if($i==1)
					{
						 echo '<div class="notifications nt_green"><i>'.Yii::t('app','Exam Score Entered For All Students').'</i></div>'; 
						 $allscores = ExamScores::model()->findAllByAttributes(array('exam_id'=>$exam_id));
						 $sum=0;
						 foreach($allscores as $allscore)
						 {
							$sum=$sum+$allscore->marks;
						 }
						 $avg=$sum/count($allscores);
						 echo '<div class="notifications nt_green">'.Yii::t('app', 'Class Average').' = '.$avg.' '.Yii::t('app', 'marks').'</div>';
						 /*echo '<div style="padding-left:10px;">';
						 echo CHtml::link('<img src="images/pdf-but.png" />', array('examScores/pdf','id'=>$_REQUEST['id'],'examid'=>$_REQUEST['examid']),array('target'=>"_blank"));
						 echo '</div>';*/
                    }
                    ?>
                </div>
                <div align="center">
					<?php if($i!=1) echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),array('class'=>'formbut')); ?>
				</div>
                <?php $this->endWidget(); ?>
    	<?php
			}
			else
			{
				echo '<i>'.Yii::t('app','No Students In This').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</i>';
		 	}
		?>
        <?php
		}
		?>
        </div>
    </div>
    <!-- End Enter Exam Scores -->
    <?php
	}
	?>
    <?php
    $checkscores = ExamScores::model()->findByAttributes(array('exam_id'=>$exam_id));
	if($checkscores!=NULL)
	{?>
    <div>
        <div>
        	<?php
			if($score_flag==1){ // If $score_flag==1, display clear all button
			?>
            <div class="c_subbutCon" align="right" style="width:100%">
                <div class="c_cubbut" style="width:140px;">
                    <ul>
                        <li>
                        <?php 
						if(Yii::app()->controller->action->id=='classexam'){
							echo CHtml::link(Yii::t('examination','Clear All Scores'), array('default/deleteall',"bid"=>$batch_id,"exam_group_id"=>$exam_group_id,"r_flag"=>1,"exam_id"=>$exam_id),array('class'=>'addbttn last','confirm'=>'Are You Sure? All Scores will be deleted.'));
						}
						elseif(Yii::app()->controller->action->id=='allexam'){
							echo CHtml::link(Yii::t('examination','Clear All Scores'), array('default/deleteall',"bid"=>$batch_id,"exam_group_id"=>$exam_group_id,"r_flag"=>1,"exam_id"=>$exam_id,"allexam"=>1),array('class'=>'addbttn last','confirm'=>'Are You Sure? All Scores will be deleted.'));
						}
						?>
                        </li>
                    
                    </ul>
                <div class="clear"></div>
                </div>
            </div>
            <?php
			}
			?>
            <!-- Start Score Table -->
            <?php $model=new ExamScores('search');
                  $model->unsetAttributes();  // clear any default values
                  if(isset($exam_id))
                    $model->exam_id=$exam_id;
                  ?>
                  <h3> Scores</h3>
                  <?php 
                 if($score_flag==0){ // If $score_flag==0, score table without edit option will be displayed
				 
				 	if($exam->exam_type == 'Marks') // Show only Marks
					{
						 $this->widget('zii.widgets.grid.CGridView', array(
						'id'=>'exam-scores-grid',
						'dataProvider'=>$model->search(),
						'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
						'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
						'columns'=>array(
										array(
											'header'=>'Student Name',
											'value'=>array($model,'studentname'),
											'name'=> 'firstname',
											'sortable'=>true,
										),
										'marks',
										/*'grading_level_id',*/
										/*array(
											'header'=>'Grades',
											'value'=>array($model,'getgradinglevel'),
											'name'=> 'grading_level_id',
										),*/
										array(
											'value'=>'$data->remarks ? "$data->remarks" : "No Remarks"',
											'name'=> 'remarks',
										),
										array(
											'header'=>'Status',
											'value'=>'$data->is_failed == 1 ? "Fail" : "Pass"',
											'name'=> 'is_failed',
										),
							),
						));
					}
					elseif($exam->exam_type == 'Grades') // Show only Grades
					{
						$this->widget('zii.widgets.grid.CGridView', array(
						'id'=>'exam-scores-grid',
						'dataProvider'=>$model->search(),
						'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
						'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
						'columns'=>array(
										array(
											'header'=>'Student Name',
											'value'=>array($model,'studentname'),
											'name'=> 'firstname',
											'sortable'=>true,
										),
										/*'marks',*/
										/*'grading_level_id',*/
										array(
											'header'=>'Grades',
											'value'=>array($model,'getgradinglevel'),
											'name'=> 'grading_level_id',
										),
										array(
											'value'=>'$data->remarks ? "$data->remarks" : "No Remarks"',
											'name'=> 'remarks',
										),
										array(
											'header'=>'Status',
											'value'=>'$data->is_failed == 1 ? "Fail" : "Pass"',
											'name'=> 'is_failed',
										),
							),
						));	
					}
					elseif($exam->exam_type == 'Marks Aand Grades') // Show both Marks and Grades
					{
						$this->widget('zii.widgets.grid.CGridView', array(
						'id'=>'exam-scores-grid',
						'dataProvider'=>$model->search(),
						'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
						'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
						'columns'=>array(
										array(
											'header'=>'Student Name',
											'value'=>array($model,'studentname'),
											'name'=> 'firstname',
											'sortable'=>true,
										),
										'marks',
										/*'grading_level_id',*/
										array(
											'header'=>'Grades',
											'value'=>array($model,'getgradinglevel'),
											'name'=> 'grading_level_id',
										),
										array(
											'value'=>'$data->remarks ? "$data->remarks" : "No Remarks"',
											'name'=> 'remarks',
										),
										array(
											'header'=>'Status',
											'value'=>'$data->is_failed == 1 ? "Fail" : "Pass"',
											'name'=> 'is_failed',
										),
							),
						));	
					}
                 }
                 elseif($score_flag==1){ // If $score_flag==1, score table with edit option will be displayed
				 	if(Yii::app()->controller->action->id=='classexam'){
							$updateUrl = 'Yii::app()->createUrl("/teachersportal/default/update", array("id"=>$data->id,"bid"=>'.$batch_id.',"exam_group_id"=>'.$exam_group_id.',"r_flag"=>1,"exam_id"=>'.$exam_id.'))';
							$delUrl = 'Yii::app()->createUrl("/teachersportal/default/delete", array("id"=>$data->id,"bid"=>'.$batch_id.',"exam_group_id"=>'.$exam_group_id.',"r_flag"=>1,"exam_id"=>'.$exam_id.'))';
						}
						elseif(Yii::app()->controller->action->id=='allexam'){
							$updateUrl = 'Yii::app()->createUrl("/teachersportal/default/update", array("id"=>$data->id,"bid"=>'.$batch_id.',"exam_group_id"=>'.$exam_group_id.',"r_flag"=>1,"exam_id"=>'.$exam_id.',"allexam"=>1))';
							$delUrl = 'Yii::app()->createUrl("/teachersportal/default/delete", array("id"=>$data->id,"bid"=>'.$batch_id.',"exam_group_id"=>'.$exam_group_id.',"r_flag"=>1,"exam_id"=>'.$exam_id.',"allexam"=>1))';
						}
					if($exam->exam_type == 'Marks') // Show only Marks
					{
						$this->widget('zii.widgets.grid.CGridView', array(
						'id'=>'exam-scores-grid',
						'dataProvider'=>$model->search(),
						'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
						'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
						'columns'=>array(
										array(
											'header'=>'Student Name',
											'value'=>array($model,'studentname'),
											'name'=> 'firstname',
											'sortable'=>true,
										),
										'marks',
										/*'grading_level_id',*/
										/*array(
											'header'=>'Grades',
											'value'=>array($model,'getgradinglevel'),
											'name'=> 'grading_level_id',
										),*/
										array(
											'value'=>'$data->remarks ? "$data->remarks" : "No Remarks"',
											'name'=> 'remarks',
										),
										array(
											'header'=>'Status',
											'value'=>'$data->is_failed == 1 ? "Fail" : "Pass"',
											'name'=> 'is_failed',
										),
										array(
											'class'=>'CButtonColumn',
											'buttons' => array(
												'update' => array(
												'label' => 'Update', // text label of the button
												'url'=>$updateUrl, // a PHP expression for generating the URL of the button
												),
												'delete' => array(
												'label' => 'Update', // text label of the button
												'url'=>$delUrl, // a PHP expression for generating the URL of the button
												),
												
											),
											'template'=>'{update} {delete}',
											'afterDelete'=>'function(){window.location.reload();}',
											'header'=>'Manage',
											'headerHtmlOptions'=>array('style'=>'font-size:13px;')				
										),
								),
							
						));
					}
					elseif($exam->exam_type == 'Grades') // Show only Grades
					{
						$this->widget('zii.widgets.grid.CGridView', array(
						'id'=>'exam-scores-grid',
						'dataProvider'=>$model->search(),
						'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
						'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
						'columns'=>array(
										array(
											'header'=>'Student Name',
											'value'=>array($model,'studentname'),
											'name'=> 'firstname',
											'sortable'=>true,
										),
										/*'marks',*/
										/*'grading_level_id',*/
										array(
											'header'=>'Grades',
											'value'=>array($model,'getgradinglevel'),
											'name'=> 'grading_level_id',
										),
										array(
											'value'=>'$data->remarks ? "$data->remarks" : "No Remarks"',
											'name'=> 'remarks',
										),
										array(
											'header'=>'Status',
											'value'=>'$data->is_failed == 1 ? "Fail" : "Pass"',
											'name'=> 'is_failed',
										),
										array(
											'class'=>'CButtonColumn',
											'buttons' => array(
												'update' => array(
												'label' => 'Update', // text label of the button
												'url'=>$updateUrl, // a PHP expression for generating the URL of the button
												),
												'delete' => array(
												'label' => 'Update', // text label of the button
												'url'=>$delUrl, // a PHP expression for generating the URL of the button
												),
												
											),
											'template'=>'{update} {delete}',
											'afterDelete'=>'function(){window.location.reload();}',
											'header'=>'Manage',
											'headerHtmlOptions'=>array('style'=>'font-size:13px;')				
										),
								),
							
						));
						
					}
					elseif($exam->exam_type == 'Marks And Grades') // Show both Marks and Grades
					{
						$this->widget('zii.widgets.grid.CGridView', array(
						'id'=>'exam-scores-grid',
						'dataProvider'=>$model->search(),
						'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
						'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
						'columns'=>array(
										array(
											'header'=>'Student Name',
											'value'=>array($model,'studentname'),
											'name'=> 'firstname',
											'sortable'=>true,
										),
										'marks',
										/*'grading_level_id',*/
										array(
											'header'=>'Grades',
											'value'=>array($model,'getgradinglevel'),
											'name'=> 'grading_level_id',
										),
										array(
											'value'=>'$data->remarks ? "$data->remarks" : "No Remarks"',
											'name'=> 'remarks',
										),
										array(
											'header'=>'Status',
											'value'=>'$data->is_failed == 1 ? "Fail" : "Pass"',
											'name'=> 'is_failed',
										),
										array(
											'class'=>'CButtonColumn',
											'buttons' => array(
												'update' => array(
												'label' => 'Update', // text label of the button
												'url'=>$updateUrl, // a PHP expression for generating the URL of the button
												),
												'delete' => array(
												'label' => 'Update', // text label of the button
												'url'=>$delUrl, // a PHP expression for generating the URL of the button
												),
												
											),
											'template'=>'{update} {delete}',
											'afterDelete'=>'function(){window.location.reload();}',
											'header'=>'Manage',
											'headerHtmlOptions'=>array('style'=>'font-size:13px;')				
										),
								),
							
						));
					}
                 }
            ?>
            <!-- End Score Table -->
		</div>
	</div>
    <?php
	} // End $checkscores
	else
	{
	?>
        <div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;margin-top:60px;">
            <div class="y_bx_head"><i>
               <?php echo Yii::t('examination','No Scores Added'); ?>
           </i> </div>      
    	</div>
	<?php
	}?>
            
</div> 