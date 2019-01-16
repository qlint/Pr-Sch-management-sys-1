<style>
.infored_bx{
	padding:5px 20px 7px 20px;
	background:#e44545;
	color:#fff;
	-moz-border-radius:4px;
	-webkit-border-radius:4px;
	border-radius:4px;
	font-size:15px;
	font-style:italic;
	text-shadow: 1px -1px 2px #862626;
	text-align:left;
}
.mark1,.mark2,.total{ 
	idth: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.428571429;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}


input.disabled_field
{
	background-color:#EFEFEF !important;
}
</style>

<?php  echo $this->renderPartial('/default/leftside');

$tutor  = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));

?>
<div class="pageheader">
      <h2><i class="fa fa-pencil"></i> <?php echo Yii::t("app", "Exams");?> <span><?php echo Yii::t("app", "View your exams here");?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t("app", "You are here:");?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
          <li class="active"><?php echo Yii::t("app", "Exams");?></li>
        </ol>
   </div>
</div>
<div class="contentpanel">    
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('app', 'Exam Scores '); ?></h3>
	</div>
    <div class="people-item">
        <div class="opnsl_headerBox">
        	<div class="opnsl_actn_box"> </div>
            <div class="opnsl_actn_box">      
                <div class="opnsl_actn_box1"><?php echo CHtml::link('<span>'.Yii::t('app','All Classes').'</span>',array('/teachersportal/exams/allexam'),array('class'=>'btn btn-primary'));?></div>             
                <div class="opnsl_actn_box1"><?php echo CHtml::link('<span>'.Yii::t('app','My Class').'</span>',array('/teachersportal/exams/classexam'),array('class'=>'btn btn-primary'));?></div>                
            </div>
        </div>
<div>
<?php
	
	$batch				=	Batches::model()->findByAttributes(array('id'=>$batch_id));
	$course				=	Courses::model()->findByAttributes(array('id'=>$batch->course_id));
	$semester_enabled	= 	Configurations::model()->isSemesterEnabled(); 
	$sem_enabled		= 	Configurations::model()->isSemesterEnabledForCourse($course->id);
        if($batch!=NULL)
		   { ?>
              
                    	<table class="table table-bordered mb30">
                        	<tr>
                            	<td>
                       				<strong><?php echo Yii::t('app','Course');?>:</strong>
									<?php
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
							$criteria               	= new CDbCriteria;
							if($subject->elective_group_id == 0){   // for subjects        
								$criteria->join         = "JOIN `timetable_entries` `t1` ON `t1`.`employee_id`=`t`.`id`";           
								$criteria->condition    = "`t`.`is_deleted`=0 AND `t1`.`subject_id`=:subject_id";
								$criteria->params       = array(":subject_id"=>$subject->id);
							}
							else{                                   // for electives
								$criteria->join         = "JOIN `timetable_entries` `t1` ON `t1`.`employee_id`=`t`.`id` JOIN `electives` `t2` ON `t2`.`id`=`t1`.`subject_id`"; 
								$criteria->condition    = "`t`.`is_deleted`=0 AND `t2`.`elective_group_id`=:elective_group_id";
								$criteria->params       = array(":elective_group_id"=>$subject->elective_group_id);
							}
							$criteria->group			= 't1.employee_id';
							$employees              	= Employees::model()->findAll($criteria);
							?>
								<td>
                                	<strong><?php echo Yii::t('app','Subject Teacher'); ?>: </strong>
											<?php foreach($employees as $employee){
													echo Employees::model()->getTeachername($employee->id);
													echo ', ';
												  }?>
								</td>
							<?php
							$is_classteacher=Batches::model()->findByAttributes(array('id'=>$batch_id,'employee_id'=>$tutor->id));
							$classteacher = Employees::model()->findByAttributes(array('id'=>$is_classteacher->employee_id));
							
							
							if(Yii::app()->controller->action->id=='classexamscore' and $is_classteacher==NULL){ // Redirecting if action ID is classexam and the employee is not classteacher
								$this->redirect(array('/teachersportal/exams/index'));
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
					 <?php if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id != NULL){
					 		$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); ?>			
							 <tr>
								<td>
                                	<strong><?php echo Yii::t('app','Semester'); ?>: </strong><?php echo ucfirst($semester->name);?>
								</td>
							</tr>
					<?php } ?>
                        </table>
					  
    	
           <div class="edit_bttns" style=" float:right">
        <ul>
        	<?php
			
			if(Yii::app()->controller->action->id=='classexamscore')
			{
				$url = '/teachersportal/exams/classexamresult';				
			}
			if($exam_id!=NULL)
			{
			?>
            <li><span>
            <?php 
				echo CHtml::link(Yii::t('app','View Subject List'), array('/teachersportal/exams/classexamresult','bid'=>$batch_id,'exam_group_id'=>$exam_group_id),array('id'=>'add_exam-groups','class'=>'addbttn')); 
			
			?></span>
        	</li>
            <?php
			}
			if($exam_group_id!=NULL)
			{
			?>
            <li><span>
            <?php 
				echo CHtml::link(Yii::t('app','View Exam List'), array('/teachersportal/exams/classexams','bid'=>$batch_id),array('id'=>'add_exam-groups','class'=>'addbttn')); 
			
			?></span>
        	</li>
            <?php
			}
			?>
            <li><span>
        	<?php echo CHtml::link(Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"), array('/teachersportal/exams/classexam'),array('id'=>'add_exam-groups','class'=>'addbttn')); ?>
        	</span></li>
        </ul>
        <div class="clear"></div>
    </div>
    
    <?php
	$result_published = ExamGroups::model()->countByAttributes(array('id'=>$exam_group_id,'result_published'=>1));
	$is_teaching_subject = TimetableEntries::model()->countByAttributes(array('subject_id'=>$subject_id->subject_id,'employee_id'=>$tutor->id));
	$score_flag = 0; // If $score_flag == 0, form for entering scores will not be displayed. If $score_flag == 1, form will be displayed.
	if((Yii::app()->controller->action->id=='classexamscore' and ($is_classteacher!=NULL)) or (Yii::app()->controller->action->id=='allexam' and $is_teaching_subject >0))
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
    
        <?php
        $model=new ExamScores;
        if(isset($batch_id))
        {
                //$students=Students::model()->findAll("batch_id=:x and is_active=:y and is_deleted=:z", array(':x'=>$batch_id,':y'=>1,':z'=>0));
                $students   = BatchStudents::model()->batchStudent($batch_id);               
               
                if($students!=NULL)
    		{
				if(Yii::app()->controller->action->id=='classexamscore'){
					$actionUrl = CController::createUrl('/teachersportal/examScores/addscores',array("bid"=>$batch_id,"exam_group_id"=>$exam_group_id,"exam_id"=>$exam_id));
				}				
				$form=$this->beginWidget('CActiveForm', array(
				'id'=>'exam-scores-form',
				'action' => $actionUrl,
				'enableAjaxValidation'=>false,
				));
		?>
        
        
         <?php
                if(Yii::app()->user->hasFlash('success'))
                {
                ?>
                    <div class="infogreen_bx" style="margin:10px 0 10px 10px; width:575px;"><?php echo Yii::app()->user->getFlash('success');?></div>
                <?php
                }
                else if(Yii::app()->user->hasFlash('error'))
                {
                ?>
                    <div class="infored_bx" style="margin:10px 0 10px 10px; width:575px;"><?php echo Yii::app()->user->getFlash('error');?></div>
                <?php
                }
                ?>
				<h3><?php echo Yii::t("app", "Enter Exam Scores here:");?></h3>
    			<?php echo $form->hiddenField($model,'exam_id',array('value'=>$exam_id)); ?>
                <div class="table-responsive">
<table class="table table-bordered mb30">
                    <?php 
					$i=1;
	  				$j=0;
					$k=0;
	  				foreach($students as $student){ 
						$checksub = ExamScores::model()->findByAttributes(array('exam_id'=>$exam_id,'student_id'=>$student->id));
						 $exm = Exams::model()->findByAttributes(array('id'=>$exam_id));
                            $sub = Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
						if($checksub==NULL){ //No score entered for student with student_id '$student->id'.
							if($j==0)
							{
					?>
                    <thead>
                    			<tr>
                                <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
                                    <th width="350"><?php echo Yii::t('app','Roll No');?></th>
                                    <?php } ?>
                                    <th><?php echo Yii::t('app','Student Name');?></th>
                                    <th><?php echo Yii::t('app','Subject');?></th>
                                    <?php   
											$subject_cps	=	SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$sub->id)); 
											$r=1;
											if($subject_cps !=NULL){
												foreach($subject_cps as $subject_cp){ 
												?>
												<th><?php echo ucfirst($subject_cp->split_name);?></th> 
												<?php
												}
												?><th><?php echo Yii::t('app','Total Marks');?></th>
                                                <?php
											}else{ ?>
                                            <th><?php echo Yii::t('app','Marks');?></th> 
                                            <?php
											}?>
                                    <th><?php echo Yii::t('app','Remarks');?></th>
                             	</tr>
                                </thead>
                              	
                    <?php 
								$j++;
							} 
					?>
                    			<tr>
                                 <?php if(Configurations::model()->rollnoSettingsMode() != 2){
                                        $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$batch_id, 'status'=>1));?>
                                    	 <td align="center"><?php if($batch_student!=NULL and $batch_student->roll_no!=0){
                                                             echo $batch_student->roll_no;
                                                        }
                                                        else{
                                                            echo '-';
                                                        }
                                                        ?><br />
                                    	</td>
                                    <?php } ?>
                                	<td><?php 
                                        $name= $student->studentFullName('forTeacherPortal');
                                        echo $name;
                                       // echo ucfirst($student->first_name).' '.ucfirst($student->last_name);?>
                                    </td>
                                    <td>
                                    <?php 
                                        if($sub->elective_group_id!=0)
                                        {
                                            $studentelctive = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'elective_group_id'=>$sub->elective_group_id));
                                            if($studentelctive==NULL) 
                                            {
                                            ?>
                                                <?php echo '<i><span style="color:#E26214;">'.Yii::t('app','Elective not assigned').'</span></i>  '.CHtml::link(Yii::t('app','Add now'),array('/teachersportal/course/elective','id'=>$batch_id)); ?>
                                            <?php
											$flag=1;
                                            }
											else
											{
												$flag=0;
												$electiveid = Electives::model()->findByAttributes(array('id'=>$studentelctive->elective_id));
												if($electiveid!=NULL)
												{
													echo ucfirst($electiveid->name);
												}
											}
                                        }
										else
										{
											echo ucfirst($sub->name);
											$is_teaching = EmployeesSubjects::model()->findByAttributes(array('subject_id'=>$sub->id,'employee_id'=>$employee->id));
											if($is_teaching==NULL)
											{
												$flag=1;
											}
										}?>
										<?php echo $form->hiddenField($model,'student_id['.$k.']',array('value'=>$student->id)); ?>
									</td>
                                    <?php   
									if($subject_cps !=NULL){?>
                                    <td>
										<?php echo $form->textField($model,'sub_category1['.$k.']',array('size'=>7,'maxlength'=>7,'class'=>'mark1','style'=>'width:200px')); ?>
                                    </td>
                                    <td>
										<?php echo $form->textField($model,'sub_category2['.$k.']',array('size'=>7,'maxlength'=>7,'class'=>'mark2','style'=>'width:200px')); ?>
                                    </td>
                                    <?php
									}?>
                                    <td><?php 
									if($subject_cps !=NULL){
									echo $form->textField($model,'marks['.$k.']',array('size'=>7,'maxlength'=>7,'class'=>'total','style'=>'width:200px','readonly'=>true));
									}else{
										echo $form->textField($model,'marks['.$k.']',array('size'=>7,'maxlength'=>7,'class'=>'total','style'=>'width:200px'));
									}?>
                                    </td>
                                    <td><?php echo $form->textField($model,'remarks['.$k.']',array('encode'=>false,'size'=>60,'maxlength'=>255,'style'=>'width:200px','class'=>'form-control')); ?></td>
								</tr>
        						
                                <?php 
									echo $form->hiddenField($model,'grading_level_id');
									echo $form->hiddenField($model,'created_at',array('value'=>date('Y-m-d')));
		  							echo $form->hiddenField($model,'updated_at',array('value'=>date('Y-m-d'))); 
								?>
                    <?php 
							$i++;
							$k++;	
						}
					}
					?>
                    </table>
                  
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
						 echo '<div class="notifications nt_green">'.Yii::t('app','Class Average').' = '.$avg.' marks</div>';
						 /*echo '<div style="padding-left:10px;">';
						 echo CHtml::link('<img src="images/pdf-but.png" />', array('examScores/pdf','id'=>$_REQUEST['id'],'examid'=>$_REQUEST['examid']),array('target'=>"_blank"));
						 echo '</div>';*/
                    }
                    ?>
                </div>
                <div align="left">
					<?php if($i!=1) echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'btn btn-danger')); ?>
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
            <div class="edit_bttns" align="right" style="width:100%">
                <div class="c_cubbut" style="width:140px;">
                    <ul>
                        <li>
                        <?php 
						//if(Yii::app()->controller->action->id=='classexamscore'){
							echo CHtml::link(Yii::t('app','Clear All Scores'), "#", array('submit'=>array('/teachersportal/examScores/deleteall',"id"=>$_REQUEST['bid'],"exam_group_id"=>$_REQUEST['exam_group_id'],"exam_id"=>$_REQUEST['exam_id']), 'class'=>'addbttn last','confirm'=>Yii::t('app','Are You Sure? All Scores will be deleted'), 'csrf'=>true));
						//}						
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
                  <h3><?php echo Yii::t('app', 'Scores');?></h3>
                  <?php 
					$exm = Exams::model()->findByAttributes(array('id'=>$_REQUEST['exam_id']));  
					if($exm!=NULL)
					{
					$sub = Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
					}
					$subject_cps	=	SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$sub->id)); 
                 if($score_flag==0){// If $score_flag==0, score table without edit option will be displayed
				
				
				 	if($exam->exam_type == 'Marks') // Show only Marks
					{
                                            //   <!-- DYNAMIC FIELD ARRAY START -->    
						$new_array=array();
						
						if(Configurations::model()->rollnoSettingsMode() != 2){
							$new_array[]	= array(
								'header'=>Yii::t('app','Roll No'),
								'value'=>array($model,'studentRollno'),
								'name'=> 'roll_no',
								'sortable'=>true,
							);
						}
						if(FormFields::model()->isVisible("fullname", "Students", "forTeacherPortal"))
						{
							$new_array[]=array(
											'header'=>Yii::t('app','Student Name'),
											'value'=>'$data->gridStudentName(forTeacherPortal)',                                                                                        
											'name'=> 'firstname',
											'sortable'=>true,
									);
						}
						if($subject_cps !=NULL){					
							$t=1;
							foreach($subject_cps as $subject_cp){
								$new_array[]	= array(
									'header'=>ucfirst($subject_cp->split_name),
									'value'=>array($model,'category'.$t), 
								);
								$t++;
							}
						}
						$new_array[]= 'marks';
						$new_array[]= array(
										'value'=>'$data->remarks ? "$data->remarks" : Yii::t("app","No Remarks")',
										'name'=> 'remarks',
								);
						$new_array[]= array(
										'header'=>Yii::t('app','Status'),
										'value'=>'$data->is_failed == 1 ? Yii::t("app","Fail") : Yii::t("app","Pass")',
										'name'=> 'is_failed',
								);
																																									   
					 //   <!-- DYNAMIC FIELD ARRAY END -->  
                                                
						 $this->widget('zii.widgets.grid.CGridView', array(
						'id'=>'exam-scores-grid',
						'dataProvider'=>$model->search(),
						'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
						'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
						'columns'=>$new_array,
						));
					}
					elseif($exam->exam_type == 'Grades') // Show only Grades
					{
                                            //   <!-- DYNAMIC FIELD ARRAY START -->    
                                                $new_array=array();
												
												if(Configurations::model()->rollnoSettingsMode() != 2){
													$new_array[]	= array(
														'header'=>Yii::t('app','Roll No'),
														'value'=>array($model,'studentRollno'),
														'name'=> 'roll_no',
														'sortable'=>true,
													);
												}
                                                if(FormFields::model()->isVisible("fullname", "Students", "forTeacherPortal"))
                                                {
                                                    $new_array[]=array(
                                                                    'header'=>Yii::t('app','Student Name'),
                                                                    'value'=>'$data->gridStudentName(forTeacherPortal)',                                                                                        
                                                                    'name'=> 'firstname',
                                                                    'sortable'=>true,
                                                            );
                                                }
												if($subject_cps !=NULL){					
													$t=1;
													foreach($subject_cps as $subject_cp){
														$new_array[]	= array(
															'header'=>ucfirst($subject_cp->split_name),
															'value'=>array($model,'category'.$t), 
														);
														$t++;
													}
												}
                                                $new_array[]= array(
                                                                'header'=>'Grades',
                                                                'value'=>array($model,'getgradinglevelteacher'),
                                                                'name'=> 'grading_level_id',
                                                        );
                                                $new_array[]= array(
													'value'=>'$data->remarks ? "$data->remarks" : Yii::t("app","No Remarks")',
													'name'=> 'remarks',
												);
                                                $new_array[]= array(
                                                                    'header'=>'Status',
                                                                    'value'=>'$data->is_failed == 1 ? Yii::t("app","Fail") : Yii::t("app","Pass")',
                                                                    'name'=> 'is_failed',
                                                            );
                                                                                                                                                                                               
                                             //   <!-- DYNAMIC FIELD ARRAY END -->  
						$this->widget('zii.widgets.grid.CGridView', array(
						'id'=>'exam-scores-grid',
						'dataProvider'=>$model->search(),
						'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
						'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
						'columns'=> $new_array,
						));	
					}
					elseif($exam->exam_type == 'Marks And Grades') // Show both Marks and Grades
					{
                                            
                                            //   <!-- DYNAMIC FIELD ARRAY START -->    
                                                $new_array=array();
												
												if(Configurations::model()->rollnoSettingsMode() != 2){
													$new_array[]	= array(
														'header'=>Yii::t('app','Roll No'),
														'value'=>array($model,'studentRollno'),
														'name'=> 'roll_no',
														'sortable'=>true,
													);
												}
                                                if(FormFields::model()->isVisible("fullname", "Students", "forTeacherPortal"))
                                                {
                                                    $new_array[]=array(
                                                                    'header'=>Yii::t('app','Student Name'),
                                                                    'value'=>'$data->gridStudentName(forTeacherPortal)',                                                                                        
                                                                    'name'=> 'firstname',
                                                                    'sortable'=>true,
                                                            );
                                                }
												if($subject_cps !=NULL){					
													$t=1;
													foreach($subject_cps as $subject_cp){
														$new_array[]	= array(
															'header'=>ucfirst($subject_cp->split_name),
															'value'=>array($model,'category'.$t), 
														);
														$t++;
													}
												}
                                                $new_array[]= 'mark';
                                                $new_array[]= array(
                                                                'header'=>'Grades',
                                                                'value'=>array($model,'getgradinglevelteacher'),
                                                                'name'=> 'grading_level_id',
                                                        );
                                                $new_array[]= array(
											'value'=>'$data->remarks ? "$data->remarks" : Yii::t("app","No Remarks")',
											'name'=> 'remarks',
										);
                                                $new_array[]= array(
                                                                    'header'=>'Status',
                                                                    'value'=>'$data->is_failed == 1 ? Yii::t("app","Fail") : Yii::t("app","Pass")',
                                                                    'name'=> 'is_failed',
                                                            );
                                                                                                                                                                                               
                                             //   <!-- DYNAMIC FIELD ARRAY END -->  
						$this->widget('zii.widgets.grid.CGridView', array(
						'id'=>'exam-scores-grid',
						'dataProvider'=>$model->search(),
						'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
						'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
						'columns'=>$new_array,
						));	
					}
                 }
                 elseif($score_flag==1){ // If $score_flag==1, score table with edit option will be displayed
				 	//if(Yii::app()->controller->action->id=='classexamscore'){
							$updateUrl = 'Yii::app()->createUrl("/teachersportal/examScores/classexamupdate", array("id"=>$data->id,"bid"=>'.$batch_id.',"exam_group_id"=>'.$exam_group_id.',"exam_id"=>'.$exam_id.'))';
							$delUrl = 'Yii::app()->createUrl("/teachersportal/examScores/deleteexamscore", array("id"=>$data->id,"bid"=>'.$batch_id.',"exam_group_id"=>'.$exam_group_id.',"exam_id"=>'.$exam_id.'))';
						//}						
					if($exam->exam_type == 'Marks') // Show only Marks
					{
                                            //   <!-- DYNAMIC FIELD ARRAY START -->    
                                                $new_array=array();
												
												if(Configurations::model()->rollnoSettingsMode() != 2){
													$new_array[]	= array(
														'header'=>Yii::t('app','Roll No'),
														'value'=>array($model,'studentRollno'),
														'name'=> 'roll_no',
														'sortable'=>true,
													);
												}
                                                if(FormFields::model()->isVisible("fullname", "Students", "forTeacherPortal"))
                                                {
                                                    $new_array[]=array(
											'header'=>Yii::t('app','Student Name'),
											'value'=>'$data->gridStudentName(forTeacherPortal)',                                                                                        
											'name'=> 'firstname',
											'sortable'=>true,
										);
                                                }
												if($subject_cps !=NULL){					
													$t=1;
													foreach($subject_cps as $subject_cp){
														$new_array[]	= array(
															'header'=>ucfirst($subject_cp->split_name),
															'value'=>array($model,'category'.$t), 
														);
														$t++;
													}
												}
                                                $new_array[]= 'marks';
                                                    /*'grading_level_id',*/
                                                    /*array(
                                                            'header'=>'Grades',
                                                            'value'=>array($model,'getgradinglevel'),
                                                            'name'=> 'grading_level_id',
                                                    ),*/
                                                    $new_array[]= array(
                                                            'value'=>'$data->remarks ? "$data->remarks" : Yii::t("app","No Remarks")',
                                                            'name'=> 'remarks',
                                                    );
                                                    $new_array[]= array(
                                                            'header'=>'Status',
                                                            'value'=>'$data->is_failed == 1 ? Yii::t("app","Fail") : Yii::t("app","Pass")',
                                                            'name'=> 'is_failed',
                                                    );
                                                    $new_array[]=array(
                                                            'class'=>'CButtonColumn',
                                                            'buttons' => array(
                                                                    'update' => array(
                                                                    'label' => Yii::t('app','Update'), // text label of the button
                                                                    'url'=>$updateUrl, // a PHP expression for generating the URL of the button
                                                                    ),
                                                                    'delete' => array(
																	'deleteConfirmation'=>Yii::t('app', 'Are you sure you want to delete this score ?'),
                                                                    'label' => Yii::t('app','Delete1'), // text label of the button
                                                                    'url'=>$delUrl, // a PHP expression for generating the URL of the button
                                                                    ),

                                                            ),
                                                            'template'=>'{update} {delete}',
                                                            'afterDelete'=>'function(){window.location.reload();}',
                                                            'header'=>Yii::t('app','Manage'),
                                                            'headerHtmlOptions'=>array('style'=>'font-size:13px;')				
                                                    );
                                                
                                                
                                             //   <!-- DYNAMIC FIELD ARRAY END -->     
                                            
                                            
						$this->widget('zii.widgets.grid.CGridView', array(
						'id'=>'exam-scores-grid',
						'dataProvider'=>$model->search(),
						'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
						'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
						'columns'=>$new_array,
							
						));
					}
					elseif($exam->exam_type == 'Grades') // Show only Grades
					{
                                            
                                            //   <!-- DYNAMIC FIELD ARRAY START -->    
                                                $new_array=array();
												if(Configurations::model()->rollnoSettingsMode() != 2){
													$new_array[]	= array(
														'header'=>Yii::t('app','Roll No'),
														'value'=>array($model,'studentRollno'),
														'name'=> 'roll_no',
														'sortable'=>true,
													);
												}
                                                if(FormFields::model()->isVisible("fullname", "Students", "forTeacherPortal"))
                                                {
                                                    $new_array[]=array(
														'header'=>Yii::t('app','Student Name'),
														'value'=>'$data->gridStudentName(forTeacherPortal)',                                                                                        
														'name'=> 'firstname',
														'sortable'=>true,
													);
                                                }
												if($subject_cps !=NULL){					
													$t=1;
													foreach($subject_cps as $subject_cp){
														$new_array[]	= array(
															'header'=>ucfirst($subject_cp->split_name),
															'value'=>array($model,'category'.$t), 
														);
														$t++;
													}
												}
                                                $new_array[]= array(
                                                            'header'=>Yii::t('app','Grades'),
                                                            'value'=>array($model,'getgradinglevelteacher'),
                                                            'name'=> 'grading_level_id',
                                                    );
                                                   $new_array[]= array(
                                                            'value'=>'$data->remarks ? "$data->remarks" : Yii::t("app","No Remarks")',
                                                            'name'=> 'remarks',
                                                    );
                                                    $new_array[]= array(
                                                            'header'=>'Status',
                                                            'value'=>'$data->is_failed == 1 ? Yii::t("app","Fail") : Yii::t("app","Pass")',
                                                            'name'=> 'is_failed',
                                                    );
                                                    $new_array[]= array(
                                                            'class'=>'CButtonColumn',
                                                            'buttons' => array(
                                                                    'update' => array(
                                                                    'label' => Yii::t('app','Update'), // text label of the button
                                                                    'url'=>$updateUrl, // a PHP expression for generating the URL of the button
                                                                    ),
                                                                    'delete' => array(
                                                                    'label' => Yii::t('app','Delete2'), // text label of the button
                                                                    'url'=>$delUrl, // a PHP expression for generating the URL of the button
																	'options'=>array('class'=>'')
                                                                    
                                                                    ),

                                                            ),
                                                            'template'=>'{update} {delete}',
                                                            'afterDelete'=>'function(){window.location.reload();}',
                                                            'header'=>Yii::t('app','Manage'),
                                                            'headerHtmlOptions'=>array('style'=>'font-size:13px;')				
                                                    );
                                                        
						$this->widget('zii.widgets.grid.CGridView', array(
						'id'=>'exam-scores-grid',
						'dataProvider'=>$model->search(),
						'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
						'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
						'columns'=>$new_array,
							
						));
						
					}
					elseif($exam->exam_type == 'Marks And Grades') // Show both Marks and Grades
					{
                                            
                                            //   <!-- DYNAMIC FIELD ARRAY START -->    
                                                $new_array=array();
												if(Configurations::model()->rollnoSettingsMode() != 2){
													$new_array[]	= array(
														'header'=>Yii::t('app','Roll No'),
														'value'=>array($model,'studentRollno'),
														'name'=> 'roll_no',
														'sortable'=>true,
													);
												}
                                                if(FormFields::model()->isVisible("fullname", "Students", "forTeacherPortal"))
                                                {
                                                    $new_array[]=array(
														'header'=>Yii::t('app','Student Name'),
														'value'=>'$data->gridStudentName(forTeacherPortal)',                                                                                        
														'name'=> 'firstname',
														'sortable'=>true,
													);
                                                }
												if($subject_cps !=NULL){					
													$t=1;
													foreach($subject_cps as $subject_cp){
														$new_array[]	= array(
															'header'=>ucfirst($subject_cp->split_name),
															'value'=>array($model,'category'.$t), 
														);
														$t++;
													}
												}
                                                $new_array[]= 'marks';
                                                   
                                                $new_array[]=    array(
                                                            'header'=>Yii::t('app','Grades'),
                                                            'value'=>array($model,'getgradinglevelteacher'),
                                                            'name'=> 'grading_level_id',
                                                    );
                                                    $new_array[]= array(
                                                            'value'=>'$data->remarks ? "$data->remarks" : Yii::t("app","No Remarks")',
                                                            'name'=> 'remarks',
                                                    );
                                                    $new_array[]= array(
                                                            'header'=>'Status',
                                                            'value'=>'$data->is_failed == 1 ? Yii::t("app","Fail") : Yii::t("app","Pass")',
                                                            'name'=> 'is_failed',
                                                    );
                                                    $new_array[]= array(
                                                            'class'=>'CButtonColumn',
                                                            'buttons' => array(
                                                                    'update' => array(
                                                                    'label' => Yii::t('app','Update'), // text label of the button
                                                                    'url'=>$updateUrl, // a PHP expression for generating the URL of the button
                                                                    ),
                                                                    'delete' => array(
																	'deleteConfirmation'=>Yii::t('app', 'Are you sure you want to delete this score ?'),
                                                                    'label' => Yii::t('app','Delete3'), // text label of the button                                                                    																	
																	'data-method' => 'post',
																	'url'=>$delUrl, // a PHP expression for generating the URL of the button
																	'options'=>array('class'=>'')
                                                                    ),	

                                                            ),
                                                            'template'=>'{update} {delete}',
                                                            'afterDelete'=>'function(){window.location.reload();}',
                                                            'header'=>Yii::t('app','Manage'),
                                                            'headerHtmlOptions'=>array('style'=>'font-size:13px;')				
                                                    );
                                                        
						$this->widget('zii.widgets.grid.CGridView', array(
						'id'=>'exam-scores-grid',
						'dataProvider'=>$model->search(),
						'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
						'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
						'columns'=>$new_array,
							
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
               <?php echo Yii::t('app','No Scores Added'); ?>
           </i> </div>      
    	</div>
	<?php
	}?>
    <?php 
		   }?>
            
</div> 
</div></div>
<script>
$(document).ready(function(){
 $('.mark1').change(function(e) {
	var mark_val	= $(this).closest('tr').find('input[class=mark2]').val();
	var total		= parseInt($(this).val())+parseInt(mark_val);
	if(!isNaN(total)){
		$(this).closest('tr').find('input[class=total]').val(total);
	}
});

$('.mark2').change(function(e) {
  var mark_val 		= $(this).closest('tr').find('input[class=mark1]').val();
	var total		= parseInt($(this).val())+parseInt(mark_val);
	if(!isNaN(total)){
		$(this).closest('tr').find('input[class=total]').val(total);
	}
});
	$('.to_total').change(function(e) {
	var mark_val 		= $(this).closest('tr').find('input[class=mark1]').val(0);
	var mark_val 		= $(this).closest('tr').find('input[class=mark2]').val(0);
    });
});

$("form#exam-scores-form").submit(function(e) {
	var textBox = "";
	$("form#exam-scores-form").find('input[type=text]').each(function(){
		textBox += $(this).val();
	});
	
	if (textBox == "") {
		$(".errorMessage").remove();
		alert("<?php echo Yii::t("app", "Fill the Exam Scores ");?>");
	}
	else
	{
		var that	= this;
		var data	= $(that).serialize();
		$(that).find("input[type='submit']").attr("disabled", true);
		$.ajax({
			url:'<?php echo Yii::app()->createUrl("/teachersportal/examScores/addscores", array("id"=>$_REQUEST['id'], "examid"=>$_REQUEST['examid']));?>',
			type:'POST',
			data:data,
			dataType:"json",
			success: function(response){
				$(that).find("input[type='submit']").attr("disabled", false);
				$(".errorMessage").remove();
				if(response.status=="success"){                                    
					window.location.reload();
				}
				else if(response.hasOwnProperty("errors")){
					var errors	= response.errors;
					$.each(errors, function(attribute, earray){
						$.each(earray, function(index, error){
							var error_div	= $("<div class='errorMessage' style='font-weight:100;' />");
							error_div.text(error);
							$('#' + attribute).closest("td").append(error_div);
						});										
					});				
				}
				else if(response.hasOwnProperty("message")){
					alert(response.message);
				}
				else{
					alert("<?php echo Yii::t("app", "Some problem found while saving datass !!");?>");
				}
			},
            error:function(){
				$(that).find("input[type='submit']").attr("disabled", false);
				alert("<?php echo Yii::t("app", "Some problem found while saving data !!");?>");
			}
			
		});
	}
	return false;
});
</script>