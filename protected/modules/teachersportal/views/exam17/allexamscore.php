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
.m1,.m2,.m3,.m4,.total,.remark{ 
	idth: 100%;
    height: 34px;
    padding: 6px 7px;
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
    		<h3 class="panel-title"><?php echo Yii::t('app', 'Exam Scores'); ?></h3>

    
    

	</div>
    <div class="people-item">
    <div class="opnsl_headerBox">
    <div class="opnsl_actn_box"> </div>
    <div class="opnsl_actn_box">
        <div class="opnsl_actn_box1"><?php echo CHtml::link('<span>'.Yii::t('app','All Classes').'</span>',array('/teachersportal/exam17/allexam'),array('class'=>'btn btn-primary'));?></div>
        <div class="opnsl_actn_box2">              <?php if($class_count>0){ ?>   
               <?php echo CHtml::link('<span>'.Yii::t('app','My Class').'</span>',array('/teachersportal/exams/classexam'),array('class'=>'btn btn-primary'));?>               
			  <?php } ?> </div>
	</div>
</div>
<div>
<?php 
	$batch_id = $_REQUEST['bid'];
	$batch=Batches::model()->findByAttributes(array('id'=>$batch_id));
	$course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
	$semester_enabled	= Configurations::model()->isSemesterEnabled(); 
	$sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);
	$exam_format	 = ExamFormat::model()->getExamformat($batch->id); // 1=>normal, 2=>cbsc
        if($batch!=NULL)
		   { ?>
            <!-- Batch Details Tab -->
            	<div class="table-responsive">
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
								$exam=CbscExamGroup17::model()->findByAttributes(array('id'=>$exam_group_id,'batch_id'=>$batch_id));
								if($exam_format == 1){
									$head = Yii::t('app','Exam');
									$val  = $exam->name;
								}
								else{
									$head = Yii::t('app','Exam / Class Level');
									$val  = $exam->name.' / '.$exam->class;
								}
							?>
								  <td><strong><?php echo $head;?>: </strong><?php echo $val;?></td>
                            <?php 
                            }
							if($exam_id!=NULL)
							{ 
								$subject_id=CbscExams17::model()->findByAttributes(array('id'=>$exam_id));
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
							
							$is_classteacher=Batches::model()->findByAttributes(array('id'=>$batch_id));
							$classteacher = Employees::model()->findByAttributes(array('id'=>$is_classteacher->employee_id));
							if(Yii::app()->controller->action->id=='classexamscore' and $classteacher->id != $employee_id){ // Redirecting if action ID is classexam and the employee is not classteacher
								$this->redirect(array('/teachersportal/exam17/index'));
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
							<?php  if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id != NULL){ ?>
							<tr>
								<td><strong> <?php echo Yii::t('app','Semester'); ?>:</strong>
                        			<?php $semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
                               				echo ucfirst($semester->name); ?>
								</td>
							</tr>
                     <?php }?>
                        </table>
                        </div>
					    
    	<?php 
		   }?>

        	<?php
			if(Yii::app()->controller->action->id=='allexamscore')
			{
				$url = '/teachersportal/exam17/allexamresult';
				
			}			
			
			?>
            

   <div class="opnsl_headerBox">
    <div class="opnsl_actn_box"> </div>
    <div class="opnsl_actn_box">
        <div class="opnsl_actn_box1">
        <?php
			if($exam_id!=NULL)
			{
				echo CHtml::link(Yii::t('app','View Subject List'), array('/teachersportal/exam17/allexamresult','bid'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id']),array('id'=>'add_exam-groups','class'=>'addbttn')); 
			}
		?>
        </div>
        <div class="opnsl_actn_box1">
        <?php
			if($exam_group_id!=NULL)
			{
				echo CHtml::link(Yii::t('app','View Exam List'), array('/teachersportal/exam17/allexams','bid'=>$_REQUEST['bid']),array('id'=>'add_exam-groups','class'=>'addbttn')); 
			}
		?>
        </div>
        <div class="opnsl_actn_box1">
        <?php echo CHtml::link(Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"), array('/teachersportal/exam17/allexam'),array('id'=>'add_exam-groups','class'=>'addbttn')); ?>
        </div>        
	</div>
</div> 
    
<?php    
if(isset($_REQUEST['bid']))
{
	
	$criteria = new CDbCriteria;
	$criteria->condition = 'is_deleted=:is_deleted AND is_active=:is_active';
	$criteria->params[':is_deleted'] = 0;
	$criteria->params[':is_active'] = 1;
	
	
	$batch_students = BatchStudents::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['bid'],'result_status'=>0));
	if($batch_students)
	{
		$count = count($batch_students);
		$criteria->condition = $criteria->condition.' AND (';
		$i = 1;
		foreach($batch_students as $batch_student)
		{
			
			$criteria->condition = $criteria->condition.' id=:student'.$i;
			$criteria->params[':student'.$i] = $batch_student->student_id;
			if($i != $count)
			{
				$criteria->condition = $criteria->condition.' OR ';
			}
			$i++;
			
		}
		$criteria->condition = $criteria->condition.')';
	}
	else
	{
		$criteria->condition = $criteria->condition.' AND batch_id=:batch_id';
		$criteria->params[':batch_id'] = $_REQUEST['bid'];
	}

	$posts=Students::model()->findAll($criteria);
	
	
	
	//$posts=Students::model()->findAll("batch_id=:x and is_active=:y and is_deleted=:z", array(':x'=>$_REQUEST['id'],':y'=>1,':z'=>0));
?>
	<?php
	$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
	if(Yii::app()->user->year)
	{
		$year = Yii::app()->user->year;
	}
	else
	{
		$year = $current_academic_yr->config_value;
	}
	$is_insert = PreviousYearSettings::model()->findByAttributes(array('id'=>2));
	$is_edit = PreviousYearSettings::model()->findByAttributes(array('id'=>3));
	$is_delete = PreviousYearSettings::model()->findByAttributes(array('id'=>4));
	
	
	$template = '';
	if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
	{
		$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		$subjectdetails = CbscExams17::model()->findByPk($_REQUEST['exam_id']);
		$is_teaching = TimetableEntries::model()->findByAttributes(array('subject_id'=>$subjectdetails->subject_id,'employee_id'=>$employee->id,'is_elective'=>0));
		if($is_teaching!=NULL)
		{
			$template = $template.'{update}';
		}
		else
		{
			$is_assigned = count(EmployeeElectiveSubjects::model()->findByAttributes(array('subject_id'=>$subjectdetails->subject_id,'employee_id'=>$employee->id)));
			if($is_assigned>0)
			{
				$template = $template.'{update}';
			}
			else
			{
				$template = $template;
			}
		}
		
		
	}
	
	if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
	{
		$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		$subjectdetails = CbscExams17::model()->findByPk($_REQUEST['exam_id']);
		$is_teaching = TimetableEntries::model()->findByAttributes(array('subject_id'=>$subjectdetails->subject_id,'employee_id'=>$employee->id,'is_elective'=>0));
		if($is_teaching!=NULL)
		{
			$template = $template.'{delete}';
		}
		else
		{
			$is_assigned = count(EmployeeElectiveSubjects::model()->findByAttributes(array('subject_id'=>$subjectdetails->subject_id,'employee_id'=>$employee->id)));
			if($is_assigned>0)
			{
				$template = $template.'{delete}';
			}
			else
			{
				$template = $template;
			}
		}
		//$template = $template.'{delete}';
	}
	
	
	$insert_score = 0;
	if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))
	{
		$insert_score = 1;
	}
	
	?>

	<?php 	
	if($year != $current_academic_yr->config_value and ($is_insert->settings_value==0 or $is_edit->settings_value==0 or $is_delete->settings_value==0))
	{
	?>
		<div>
			<div class="yellow_bx" style="background-image:none;width:680px;padding-bottom:45px;">
				<div class="y_bx_head" style="width:650px;">
				<?php 
					echo Yii::t('app','You are not viewing the current active year. ');
					if($is_insert->settings_value==0 and $is_edit->settings_value!=0 and $is_delete->settings_value!=0)
					{ 
						echo Yii::t('app','To enter the scores, enable Insert option in Previous Academic Year Settings.');
					}
					elseif($is_insert->settings_value!=0 and $is_edit->settings_value==0 and $is_delete->settings_value!=0)
					{
						echo Yii::t('app','To edit the scores, enable Edit option in Previous Academic Year Settings.');
					}
					elseif($is_insert->settings_value!=0 and $is_edit->settings_value!=0 and $is_delete->settings_value==0)
					{
						echo Yii::t('app','To delete the scores, enable Delete option in Previous Academic Year Settings.');
					}
					else
					{
						echo Yii::t('app','To manage the scores, enable the required options in Previous Academic Year Settings.');	
					}
				?>
				</div>
				<div class="y_bx_list" style="width:650px;">
					<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
				</div>
			</div>
		</div><br/>
	<?php
	}
	?>


    <div class="">
        <div>
			<?php 
            if($posts!=NULL)
            {
            ?>
                
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'exam-scores-form',
                    'enableAjaxValidation'=>false,
                )); ?>
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
                
                <?php echo $form->hiddenField($model,'exam_id',array('value'=>$_REQUEST['examid'])); ?>
                
               <div class="table-responsive">
                    	<table class="table table-bordered mb30 tableinput">
                        <?php 
                        $i=1;
                        $j=0;
						$k=0;
                        foreach($posts as $posts_1)
                        { 
							$sub=NULL;
							$student_elective=NULL;
                            $checksub = CbscExamScores17::model()->findByAttributes(array('exam_id'=>$_REQUEST['exam_id'],'student_id'=>$posts_1->id));
                            $exm = CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['exam_id']));
							if($exm!=NULL)
							{
                            	$sub = Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
							}
							
							if($sub!=NULL)
							{
								$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$posts_1->id, 'elective_group_id'=>$sub->elective_group_id));
							}
							$teachflag=0;
							$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
							$is_teaching = EmployeesSubjects::model()->findByAttributes(array('subject_id'=>$sub->id,'employee_id'=>$employee->id));
							if($is_teaching!=NULL)
							{
								$teachflag=1;
							}
							else
							{
								$is_assigned = count(EmployeeElectiveSubjects::model()->findByAttributes(array('subject_id'=>$sub->id,'elective_id'=>$student_elective->elective_id,'employee_id'=>$employee->id)));
								if($is_assigned!=NULL)
								{
									$teachflag=1;
								}
							}
                            if(($teachflag==1)and $checksub==NULL and (($sub->elective_group_id==0 and count($sub)!=0) or ($sub->elective_group_id!=0 and count($student_elective)!=0)))
                            {
                                if($j==0)
                                {
                                ?>
                                <h5 class="subtitle"><?php echo Yii::t('app','Enter Exam Scores here:');?></h5>
                                <thead>
                                    <tr>
                                    <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
                                    	<th width="20"><?php echo Yii::t('app','Roll No');?></th>
                                    <?php } ?>
                                        <th  width="20"><?php echo Yii::t('app','Student Name');?></th>
                                        <th width="80"><?php echo Yii::t('app','Subject');?></th> 
                                        <th width="50"><?php echo Yii::t('app','Written Exam');?></th> 
                                        <th width="50"><?php echo Yii::t('app','Periodic Test');?></th>
                                        <th width="50"><?php echo Yii::t('app','Note Book');?></th>
                                        <th width="50"><?php echo Yii::t('app','Subject Enrichment');?></th> 
                                        <th width="50"><?php echo Yii::t('app','Total');?></th>  
                                        <th  width="50"><?php echo Yii::t('app','Remarks');?></th>
                                    </tr>
                                    </thead>
                                    <?php 
                                    $j++;
                                } 
								$flag=0;
								$is_teaching = EmployeesSubjects::model()->findByAttributes(array('subject_id'=>$sub->id,'employee_id'=>$employee->id)); 
								if($is_teaching!=NULL)
								{	?>
										<tr> 
											<?php if(Configurations::model()->rollnoSettingsMode() != 2){
													$batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$posts_1->id, 'batch_id'=>$posts_1->batch_id, 'status'=>1));?>
													<td align="center">
													<?php 
													if($batch_student!=NULL and $batch_student->roll_no!=0){
														echo $batch_student->roll_no;
														
													}
													else{
														echo '-';
													}
													?><br />
													</td>
												<?php 
												} ?>                                                 
											<td height="60">                       
												<?php 
												$name=  $posts_1->studentFullName('forTeacherPortal'); 
												if($name!="")
												{
													echo $name;
												}
												else
												echo "-"; ?><br />
											</td>
											<td>
											<?php echo $form->hiddenField($model,'student_id['.$k.']',array('value'=>$posts_1->id)); ?>
											<?php
											echo ucfirst($sub->name);
											$is_teaching = EmployeesSubjects::model()->findByAttributes(array('subject_id'=>$sub->id,'employee_id'=>$employee->id));
											if($is_teaching==NULL)
											{
												$flag=1;
											}
											//}?>
                                            
											</td>
											<td>   <?php  echo $form->hiddenField($model,'exam_id',array('value'=>$_REQUEST['exam_id'])); ?>
											<?php  echo $form->textField($model,'written_exam['.$k.']',array('maxlength'=>4,'class'=>'m1'));?></td>
											<td> <?php  echo $form->textField($model,'periodic_test['.$k.']',array('maxlength'=>4,'class'=>'m2'));?></td>
											<td> <?php  echo $form->textField($model,'note_book['.$k.']',array('maxlength'=>4,'class'=>'m3'));?></td>
											<td> <?php  echo $form->textField($model,'subject_enrichment['.$k.']',array('maxlength'=>4,'class'=>'m4'));?></td>
											<td> <?php  echo $form->textField($model,'total['.$k.']',array('maxlength'=>4,'class'=>'total','readOnly'=>true));?></td>
											<td> <?php  echo $form->textField($model,'remarks['.$k.']',array('class'=>'remark'));?></td>
										</tr>  
									<?php 
									echo $form->hiddenField($model,'created_at',array('value'=>date('Y-m-d')));
									echo $form->hiddenField($model,'updated_at',array('value'=>date('Y-m-d')));?>  
									<?php
									} 
                            $i++;
							$k++;							
							 }  // if($checksub==NULL)
                        }// END foreach($posts as $posts_1)
                        ?>
                    </table>
                    
                    <br />
                    <?php 
                    if($i==1 and $checksub!=NULL)
                    {
                    
                        echo '<div class="notifications nt_green">'.'<i>'.Yii::t('app','Exam Score Entered For All Students').'</i></div>'; 
                        $allscores = CbscExamScores17::model()->findAllByAttributes(array('exam_id'=>$_REQUEST['exam_id']));
                        $sum=0;
                        foreach($allscores as $allscores1)
                        {
                            $sum=$sum+$allscores1->total;
                        }
                        $avg=$sum/count($allscores);
						 $avg=substr($avg,0,5);
                        echo '<div class="notifications nt_green">'.Yii::t('app','Class Average').' = '.$avg.'</div>';
                        echo '<div style="padding-left:10px;">';
                        //echo CHtml::link('<img src="images/pdf-but.png" />', array('examScores/pdf','id'=>$_REQUEST['id'],'examid'=>$_REQUEST['examid']),array('target'=>"_blank"));
                        
                        echo '</div>';
                    }
                    ?>
                </div> <!-- END div class="tableinnerlist" -->

<div class="opnsl_headerBox">
    <div class="opnsl_actn_box"> </div>
    <div class="opnsl_actn_box">
                    <?php 
					if($insert_score == 1)
					{
						if($i!=1)
						{ 
							echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'btn btn-primary')); 
						}
					}?>
	</div>
</div>
                
            
            <?php $this->endWidget(); ?>
            <?php 
            }// END if($posts!=NULL)
            else
            {
                echo '<i>'.Yii::t('app','No Students In This').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</i>';
            }
            ?>
         </div> <!-- END div class="formConInner" -->
    </div> <!-- END div class="formCon" -->
    <?php
	//}
	?>
    
    <?php
	$checkscores = CbscExamScores17::model()->findByAttributes(array('exam_id'=>$_REQUEST['exam_id']));
	if($checkscores!=NULL)
	{
	?>
        
        
        <?php 
		$model1=new CbscExamScores17('search');
        $model1->unsetAttributes();  // clear any default values
        if(isset($_GET['exam_id']))
        	$model1->exam_id=$_GET['exam_id'];
        ?>
        
        <?php
        if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
		{
		?>
        
<div class="opnsl_headerBox">
    <div class="opnsl_actn_box"> </div>
    <div class="opnsl_actn_box">
<?php
						$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
						$subjectdetails = CbscExams17::model()->findByPk($_REQUEST['exam_id']);
						$is_teaching = TimetableEntries::model()->findByAttributes(array('subject_id'=>$subjectdetails->subject_id,'employee_id'=>$employee->id,'is_elective'=>0));
						if($is_teaching!=NULL)
						{
							echo CHtml::link('<span>'.Yii::t('app','Clear All Scores').'</span>', array('exam17/deleteall','allexam'=>1,'id'=>$_REQUEST['bid'],'exam_id'=>$_REQUEST['exam_id']),array('class'=>'addbttn last','confirm'=>Yii::t('app','Are you sure you want to delete all scores ?.')));
						}
						else
						{
							$is_assigned = count(EmployeeElectiveSubjects::model()->findByAttributes(array('subject_id'=>$subjectdetails->subject_id,'employee_id'=>$employee->id)));
							if($is_assigned>0)
							{
								echo CHtml::link('<span>'.Yii::t('app','Clear All Scores').'</span>', array('examScores/deleteall','allexam'=>1,'id'=>$_REQUEST['bid'],'exam_id'=>$_REQUEST['exam_id']),array('class'=>'addbttn last','confirm'=>Yii::t('app','Are you sure you want to delete all scores ?.')));
							}
							
						}
					?> 
	</div>
</div>
        
        
        <div class="clear"></div>
        <?php
		}
		?>
        
        <div class="table-responsive">
        <h5 class="subtitle"> <?php echo Yii::t('app','Scores');?></h5>
        <?php
	
	   $exm = CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['exam_id']));
	   $checkscores = CbscExamScores17::model()->findByAttributes(array('exam_id'=>$_REQUEST['exam_id']));
       if($checkscores!=NULL)
       {
				$new_array=array();				
				if(Configurations::model()->rollnoSettingsMode() != 2){
					$new_array[]	= array(
                        'header'=>Yii::t('app','Roll No'),
                        'value'=>array($model,'studentRollno'),
                        'name'=> 'roll_no',
                        'sortable'=>true,
                    );
				}
				
				if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){
					$new_array[]	= array(
                        'header'=>Yii::t('app','Student Name'),
                        'value'=>array($model,'studentFullName'),
                        'name'=> 'firstname',
                        'sortable'=>true,
                    );
				}  
				$new_array[]	= 'written_exam'; 	
        		$new_array[]	= 'periodic_test';
				$new_array[]	= 'note_book'; 	
        		$new_array[]	= 'subject_enrichment';
				$new_array[]	= 'total';
				$new_array[]	= array(
						'header'=>Yii::t('app','Grade'),
						'value'=>array($model,'getGrade'), 
					);   
				 
				$new_array[]	= array(
						'header'=>Yii::t('app','Remarks'),
						'value'=>array($model,'getRemarks'), 
					); 
					$new_array[]= array(
								'header'=>Yii::t('app','Status'),
								'value'=>'$data->is_failed == 1 ? Yii::t("app","Fail") : Yii::t("app","Pass")',
								'name'=> 'is_failed',
						);
				$new_array[]	=array(
						'header'=>Yii::t('app', 'Action'),
                        'class'=>'CButtonColumn',
						'deleteConfirmation'=>Yii::t('app', 'Are you sure you want to delete this scores ?'),
                        'buttons' => array(                                                                 
										'update' => array(
										'label' => Yii::t('app', 'update'), // text label of the button
										'url'=>'Yii::app()->createUrl("/teachersportal/exam17/update", array("id"=>$data->id,"examid"=>$data->exam_id,"bid"=>$_REQUEST["bid"],"exam_group_id"=>$_REQUEST["exam_group_id"]))', // a PHP expression for generating the URL of the button
									  
										),
										
									),
						'template'=>$template,
						'afterDelete'=>'function(){window.location.reload();}',
						'visible'=>($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 or $is_delete->settings_value!=0)),
													
                    );	
				
				
                $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'exam-scores-grid',
                'dataProvider'=>$model1->search(),
                'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
                'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
                'columns'=>$new_array,
            )); 
         }
			
        echo '</div></div>';
        
        
	}
	else
	{
		echo '<div class="notifications nt_red">'.'<i>'.Yii::t('examination','No Scores Updated').'</i></div>'; 
	}
	?>
       
<?php
} // END if REQUEST['id'] 
else
{
	echo '<div class="notifications nt_red">'.'<i>'.Yii::t('examination','Nothing Found').'</i></div>'; 
}
?>
   
   </div>         
</div>
<script>
$(document).ready(function(){ 
$('.m1').change(function(e) {
	var m1	= $(this).closest('tr').find('input[class=m1]').val();
	var m2	= $(this).closest('tr').find('input[class=m2]').val();
	var m3	= $(this).closest('tr').find('input[class=m3]').val();
	var m4	= $(this).closest('tr').find('input[class=m4]').val();
	var total		= parseFloat(m1);
	if(m2!='')
		var total		= total+parseFloat(m2);
	if(m3!='')
		var total		= total+parseFloat(m3);
	if(m4!='')
		var total		= total+parseFloat(m4); 
	 	
	if(!isNaN(total)){
		$(this).closest('tr').find('input[class=total]').val(total.toFixed(1));
	}

});
$('.m2').change(function(e) {
	var m1	= $(this).closest('tr').find('input[class=m1]').val();
	var m2	= $(this).closest('tr').find('input[class=m2]').val();
	var m3	= $(this).closest('tr').find('input[class=m3]').val();
	var m4	= $(this).closest('tr').find('input[class=m4]').val();
	var total		= parseFloat(m2);
	if(m1!='')
		var total		= total+parseFloat(m1);
	if(m3!='')
		var total		= total+parseFloat(m3);
	if(m4!='')
		var total		= total+parseFloat(m4); 
	 	
	if(!isNaN(total)){
		$(this).closest('tr').find('input[class=total]').val(total.toFixed(1));
	}

}); 
$('.m3').change(function(e) {
	var m1	= $(this).closest('tr').find('input[class=m1]').val();
	var m2	= $(this).closest('tr').find('input[class=m2]').val();
	var m3	= $(this).closest('tr').find('input[class=m3]').val();
	var m4	= $(this).closest('tr').find('input[class=m4]').val();
	var total		= parseFloat(m3);
	if(m1!='')
		var total		= total+parseFloat(m1);
	if(m2!='')
		var total		= total+parseFloat(m2);
	if(m4!='')
		var total		= total+parseFloat(m4); 
	 	
	if(!isNaN(total)){
		$(this).closest('tr').find('input[class=total]').val(total.toFixed(1));
	}

});
$('.m4').change(function(e) {
	var m1	= $(this).closest('tr').find('input[class=m1]').val();
	var m2	= $(this).closest('tr').find('input[class=m2]').val();
	var m3	= $(this).closest('tr').find('input[class=m3]').val();
	var m4	= $(this).closest('tr').find('input[class=m4]').val();
	var total		= parseFloat(m4);
	if(m1!='')
		var total		= total+parseFloat(m1);
	if(m2!='')
		var total		= total+parseFloat(m2);
	if(m3!='')
		var total		= total+parseFloat(m3); 
	 	
	if(!isNaN(total)){
		$(this).closest('tr').find('input[class=total]').val(total.toFixed(1));
	}

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
			url:'<?php echo Yii::app()->createUrl("/teachersportal/exam17/allexamscore", array("id"=>$_REQUEST['id'], "exam_id"=>$_REQUEST['exam_id']));?>',
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
});
</script> 
