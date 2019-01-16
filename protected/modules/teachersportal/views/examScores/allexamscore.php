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
        <div class="opnsl_actn_box1"><?php echo CHtml::link('<span>'.Yii::t('app','All Classes').'</span>',array('/teachersportal/exams/allexam','employee_id'=>$employee_id),array('class'=>'btn btn-primary'));?></div>
        <?php if($class_count>0){ ?>
        <div class="opnsl_actn_box2"><?php echo CHtml::link('<span>'.Yii::t('app','My Class').'</span>',array('/teachersportal/exams/classexam','employee_id'=>$employee_id),array('class'=>'btn btn-primary'));?></div>
        <?php } ?> 
        </div>
        
        </div>
<div>
<?php
	$batch_id = $_GET['bid'];
	$batch=Batches::model()->findByAttributes(array('id'=>$batch_id));
        if($batch!=NULL)
		   { ?>
            <!-- Batch Details Tab -->
            	<div class="table-responsive">
                    	<table class="table table-bordered mb30">
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
									<strong><?php echo Yii::t('app','Subject'); ?>: </strong>
									<?php echo $subject->name;?>
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
							
							<?php
							$semester_enabled	= 	Configurations::model()->isSemesterEnabled();  
							$sem_enabled		= 	Configurations::model()->isSemesterEnabledForCourse($course->id);?>
							<?php if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id != NULL){ 
									$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));?>
									 <tr>
										<td>
											<strong><?php echo Yii::t('app','Semester'); ?>: </strong><?php echo ucfirst($semester->name); ?>
										</td>
									</tr>
							<?php } ?>
                        </table>
                        </div>
					    
    	<?php 
		   }?>
           
      
        	<?php
			if(Yii::app()->controller->action->id=='allexamscore')
			{
				$url = '/teachersportal/exams/allexamresult';
				
			}
			?>			

        
                 <div class="opnsl_headerBox">
                 <div class="opnsl_actn_box"> </div>
                        <div class="opnsl_actn_box">
                        
                            <div class="opnsl_actn_box1">
							<?php
                            if($exam_id!=NULL)
                            {
                            echo CHtml::link(Yii::t('app','View Subject List'), array('/teachersportal/exams/allexamresult','bid'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id']),array('id'=>'add_exam-groups','class'=>'btn btn-primary')); 
                            }
                            ?>
                           </div>
                            <div class="opnsl_actn_box1">
                             <?php 
			if($exam_group_id!=NULL)
			{
				echo CHtml::link(Yii::t('app','View Exam List'), array('/teachersportal/exams/allexams','bid'=>$_REQUEST['bid']),array('id'=>'add_exam-groups','class'=>'btn btn-primary')); 
			
			}
			?>
                            </div>
                            <div class="opnsl_actn_box1">
                            <?php echo CHtml::link(Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"), array('/teachersportal/exams/allexam'),array('id'=>'add_exam-groups','class'=>'btn btn-primary')); ?>
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
	$criteria->order	= 'first_name ASC, last_name ASC';
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
		$subjectdetails = Exams::model()->findByPk($_REQUEST['exam_id']);
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
		$subjectdetails = Exams::model()->findByPk($_REQUEST['exam_id']);
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
               
               
                    	<table class="table table-bordered mb30">
                        <?php 
                        $i=1;
                        $j=0;
						$k=0;
                        foreach($posts as $posts_1)
                        { 
							$sub=NULL;
							$student_elective=NULL;
                            $checksub = ExamScores::model()->findByAttributes(array('exam_id'=>$_REQUEST['exam_id'],'student_id'=>$posts_1->id));
							
                            $exm = Exams::model()->findByAttributes(array('id'=>$_REQUEST['exam_id']));
							
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
								
								if($student_elective==NULL){ //add the electives for the unassigned students
									$flag=0;
									$is_teaching = EmployeesSubjects::model()->findByAttributes(array('subject_id'=>$sub->id,'employee_id'=>$employee->id));
                                ?>
                                        <?php 
                                        /*if($sub->elective_group_id!=0)
                                        {
                                            $studentelctive = StudentElectives::model()->findByAttributes(array('student_id'=>$posts_1->id,'elective_group_id'=>$sub->elective_group_id));
                                            if($studentelctive==NULL) 
                                            {
                                            ?>
                                                <?php echo '<i><span style="color:#E26214;">'.Yii::t('app','Elective not assigned').'</span></i>'; ?>
                                            <?php
											$flag=1;
                                            }
                                        }*/
										//else
										//{
									if($is_teaching!=NULL)
									{  ?>
									<tr> 
                                     <?php if(Configurations::model()->rollnoSettingsMode() != 2){
										 $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$posts_1->id, 'batch_id'=>$posts_1->batch_id, 'status'=>1));
									?>
                                    	 <td align="center"><?php if($batch_student!=NULL and $batch_student->roll_no!=0){
                                                             echo $batch_student->roll_no;
                                                        }
                                                        else{
                                                            echo '-';
                                                        }
                                                        ?><br />
                                    	</td>
                                    <?php } ?>                                                 
                                    <td height="60">                       
                                        <?php 
                                        $name=  $posts_1->studentFullName('forTeacherPortal');
										 //$name=  $student->first_name.' '.$student->middle_name.' '.$student->last_name;
                                        if($name!="")
                                        {
                                            echo $name;
                                        }
                                        else
                                            echo "-";
                                        //echo $posts_1->first_name.' '.$posts_1->middle_name.' '.$posts_1->last_name;?><br />
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
                                    <td>
									<?php 
									if($subject_cps !=NULL){
									echo $form->textField($model,'marks['.$k.']',array('size'=>7,'maxlength'=>7,'class'=>'total','style'=>'width:200px','readonly'=>true));
									}else{
										echo $form->textField($model,'marks['.$k.']',array('size'=>7,'maxlength'=>7,'class'=>'total','style'=>'width:200px'));
									}?>
                                    </td>
                                    <td>
										<?php 
										if($insert_score == 1 and $flag==0)
										{
											echo $form->textField($model,'remarks['.$k.']',array('class'=>'form-control','size'=>7,'maxlength'=>255));
										}
										else
										{
											echo $form->textField($model,'remarks['.$k.']',array('size'=>30,'maxlength'=>255,'class'=>'form-control','disabled'=>'disabled'));
										}
										
										?>
									</td>
                                 </tr>
                                 <?php echo $form->hiddenField($model,'grading_level_id'); ?>
                                <?php //echo $form->hiddenField($model,'is_failed'); ?>
                                
                                
                                <?php 
                                echo $form->hiddenField($model,'created_at',array('value'=>date('Y-m-d')));
                                echo $form->hiddenField($model,'updated_at',array('value'=>date('Y-m-d')));?>  
								<?php
										}
								
								}
								else{
									$flag=0;
									
									//if($student_elective->elective_group_id==$sub->elective_group_id){
                                ?>
                                
                                        <?php 
                                        if($sub->elective_group_id!=0)
                                        {
                                            $studentelctive = StudentElectives::model()->findByAttributes(array('elective_group_id'=>$sub->elective_group_id,'student_id'=>$posts_1->id,'elective_group_id'=>$sub->elective_group_id));
											$electiveid = Electives::model()->findByAttributes(array('id'=>$studentelctive->elective_id));
											$is_teaching = EmployeeElectiveSubjects::model()->findByAttributes(array('subject_id'=>$sub->id,'elective_id'=>$electiveid->id,'employee_id'=>$employee->id));
											
                                            if($studentelctive!=NULL and $is_teaching!=NULL) 
                                            {
                                            ?>
                                            <tr>
												<?php if(Configurations::model()->rollnoSettingsMode() != 2){
                                               	 $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$posts_1->id, 'batch_id'=>$posts_1->batch_id, 'status'=>1));
                                                ?>
                                                <td align="center"><?php if($batch_student!=NULL and $batch_student->roll_no!=0){
                                               		 echo $batch_student->roll_no;
                                                }
                                                else{
                                               		echo '-';
                                                }
                                                ?><br />
                                                </td>
                                                <?php } ?> 
                                                <td height="60">                       
                                                    <?php echo $posts_1->studentFullName('forTeacherPortal');;?><br />
                                                </td>
                                                <td>
                                                <?php /*?><?php echo '<i><span style="color:#E26214;">'.Yii::t('app','Elective not assigned').'</span></i>'; ?><?php */?>
                                            <?php
												//$flag=1;
                                            //}
											//else
											//{
												
												//$electiveid = Electives::model()->findByAttributes(array('id'=>$studentelctive->elective_id));
												if($electiveid!=NULL)
												{
													echo ucfirst($electiveid->name).'';
													
												}
												
												//$is_teaching = EmployeeElectiveSubjects::model()->findByAttributes(array('subject_id'=>$sub->id,'elective_id'=>$electiveid->id,'employee_id'=>$employee->id));
												/*if($is_teaching==NULL)
												{
													$flag=1;
												}*/
											
											
                                       // }
										?>
                                        <?php echo $form->hiddenField($model,'student_id['.$k.']',array('value'=>$posts_1->id)); ?>
                                    </td>
                                    
                                    <td>
                                        <?php 
										if($insert_score == 1)
										{
											echo $form->textField($model,'marks['.$k.']',array('class'=>'form-control','size'=>3,'maxlength'=>3,'onclick'=>'alertmessage()'));
										}
										/*else
										{
											echo $form->textField($model,'marks[]',array('size'=>7,'maxlength'=>3,'class'=>'form-control','id'=>$posts_1->id,'disabled'=>'disabled'));
										}*/
										?>
                                    </td>                 
                                    <td>
										<?php 
										if($insert_score == 1)
										{
											echo $form->textField($model,'remarks['.$k.']',array('class'=>'form-control','size'=>7,'maxlength'=>255));
										}
										/*else
										{
											echo $form->textField($model,'remarks[]',array('size'=>30,'maxlength'=>255,'class'=>'form-control','id'=>$posts_1->id,'disabled'=>'disabled'));
										}*/
									
										?>
									</td>
                                </tr>	
                                
                                <?php echo $form->hiddenField($model,'grading_level_id'); ?>
                                <?php //echo $form->hiddenField($model,'is_failed'); ?>
                                
                                
                                <?php 
                                echo $form->hiddenField($model,'created_at',array('value'=>date('Y-m-d')));
                                echo $form->hiddenField($model,'updated_at',array('value'=>date('Y-m-d')));
									/*}
									else{
									}*/
									}
								}
							}
								
                               
                            $i++;
							$k++;
							
							 }  // if($checksub==NULL)
                        }// END foreach($posts as $posts_1)
                        ?>
                    </table>
                    <?php 
                    if($i==1 and $checksub!=NULL)
                    {
                    
                        echo '<div class="notifications nt_green">'.'<i>'.Yii::t('app','Exam Score Entered For All Students').'</i></div>'; 
                        $allscores = ExamScores::model()->findAllByAttributes(array('exam_id'=>$_REQUEST['exam_id']));
                        $sum=0;
                        foreach($allscores as $allscores1)
                        {
                            $sum=$sum+$allscores1->marks;
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
                            <div class="opnsl_actn_box1">
                            <?php 
					if($insert_score == 1)
					{
						if($i!=1)
						{ 
							echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Save') : Yii::t('app','Save'),array('class'=>'btn btn-primary')); 
						}
					}?>
                            </div>
                            
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
	$checkscores = ExamScores::model()->findByAttributes(array('exam_id'=>$_REQUEST['exam_id']));
	
	if($checkscores!=NULL)
	{
	?>
        
        
        <?php 
		$model1=new ExamScores('search');
        $model1->unsetAttributes();  // clear any default values
        if(isset($_GET['exam_id']))
        	$model1->exam_id=$_GET['exam_id'];
        ?>
     
        <?php
        if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
		{
		?>
       
                    <?php
						$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
						$subjectdetails = Exams::model()->findByPk($_REQUEST['exam_id']);
						$is_teaching = TimetableEntries::model()->findByAttributes(array('subject_id'=>$subjectdetails->subject_id,'employee_id'=>$employee->id,'is_elective'=>0));
						
					?>
                    <?php /*?><?php echo CHtml::link('<span>'.Yii::t('app','Clear All Scores').'</span>', array('examScores/deleteall','allexam'=>1,'id'=>$_REQUEST['bid'],'exam_id'=>$_REQUEST['exam_id']),array('class'=>'addbttn last','confirm'=>Yii::t('app','Are you sure you want to delete all scores ?.')));?><?php */?>
                    
             <div class="opnsl_headerBox">
             <div class="opnsl_actn_box"> </div>
                        <div class="opnsl_actn_box">
                        <?php 
						if($is_teaching!=NULL)
						{
							?>
                            <div class="opnsl_actn_box1">
                            <?php
							echo CHtml::link('<span>'.Yii::t('app','Clear All Scores').'</span>', array('examScores/deleteall','allexam'=>1,'id'=>$_REQUEST['bid'],'exam_id'=>$_REQUEST['exam_id']),array('class'=>'addbttn last','confirm'=>Yii::t('app','Are you sure you want to delete all scores ?.')));
							?>
                            </div>
                          <?php  
						  }							
						
                        else
						{
						?>
						<div class="opnsl_actn_box2">
                           <?php 
						   $is_assigned = count(EmployeeElectiveSubjects::model()->findByAttributes(array('subject_id'=>$subjectdetails->subject_id,'employee_id'=>$employee->id)));
							if($is_assigned>0)
							{
								echo CHtml::link('<span>'.Yii::t('app','Clear All Scores').'</span>', array('examScores/deleteall','allexam'=>1,'id'=>$_REQUEST['bid'],'exam_id'=>$_REQUEST['exam_id']),array('class'=>'addbttn last','confirm'=>Yii::t('app','Are you sure you want to delete all scores ?.')));
							}
						   ?> 
                            </div>
                            <?php
						}
						?>
                               	</div>
                       		 	
                   	 </div>
        
        
        <div class="clear"></div>
        <?php
		}
		?>
   <h5 class="subtitle"> <?php echo Yii::t('app','Scores');?></h3>
        <div class="table-responsive">
        <?php
	   $exm = Exams::model()->findByAttributes(array('id'=>$_REQUEST['exam_id']));
	   $examgroups = ExamGroups::model()->findByAttributes(array('id'=>$exm->exam_group_id)); 
					if($exm!=NULL)
					{
					$sub = Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
					}
					$subject_cps	=	SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$sub->id)); 
        if($examgroups->exam_type =='Marks') // Marks Only
        {
           $checkscores = ExamScores::model()->findByAttributes(array('exam_id'=>$_REQUEST['exam_id']));
            if($checkscores!=NULL)
            {
        
			$new_array		=	array();
			if(Configurations::model()->rollnoSettingsMode() != 2){
				$new_array[]	= array(
					'header'=>Yii::t('app','Roll No'),
					'value'=>array($model,'studentRollno'),
					'name'=> 'roll_no',
					'sortable'=>true,
				);
			}
			$new_array[]	=	array(
							'header'=>Yii::t('app','Student Name'),
						   // 'value'=>array($model,'studentname'),
							'value'=>'$data->gridStudentName(forTeacherPortal)',  
							'name'=> 'firstname',
							'sortable'=>true,);
			$subject_cps	=	SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$sub->id));
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
			$new_array[]	=	'marks';
			/*$new_array[]	=	array(
                        'header'=>Yii::t('app', 'Grades'),
                       'value'=>array($model,'getgradinglevelteacher'),
       					//'value' => $model->getgradinglevel($_REQUEST['id']),
					   
                        'name'=> 'grading_level_id',
                    );*/
			$new_array[]	=	array(
						'header'=>Yii::t('app','Remarks'),
						'value'=>array($model,'getRemarks'), 
					);
			$new_array[]	=	array(
						'header'=>Yii::t('app', 'Action'),
                        'class'=>'CButtonColumn',
						'deleteConfirmation'=>Yii::t('app', 'Are you sure you want to delete this score ?'),
                        'buttons' => array(
                                                                 
									'update' => array(
												'label' => Yii::t('app', 'update'), // text label of the button
												'url'=>'Yii::app()->createUrl("/teachersportal/examScores/update", array("id"=>$data->id,"examid"=>$data->exam_id,"bid"=>$_REQUEST["bid"],"exam_group_id"=>$_REQUEST["exam_group_id"]))', // a PHP expression for generating the URL of the button
												'visible'=>'((ExamScores::model()->checkAccess($data) == 1) and ($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 or $is_delete->settings_value!=0)))',
											),
									'delete'=>array(
										'label' => Yii::t('app', 'Delete'), // text label of the button
										'url'=>'Yii::app()->createUrl("/teachersportal/examScores/delete", array("id"=>$data->id))',
										'visible'=>'((ExamScores::model()->checkAccess($data) == 1) and ($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 or $is_delete->settings_value!=0)))',
									),
									
								),
								'template'=>$template,
								'afterDelete'=>'function(){window.location.reload();}',
                                                                
                    );
			
							
                $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'exam-scores-grid',
                'dataProvider'=>$model1->search(),
                'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
                'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
                'columns'=>$new_array,
            )); 
            }
        }
        else if($examgroups->exam_type =='Grades') // Grades Only
        {
            $checkscores = ExamScores::model()->findByAttributes(array('exam_id'=>$_REQUEST['exam_id']));
            if($checkscores!=NULL)
            {
				$new_array		=	array();
				if(Configurations::model()->rollnoSettingsMode() != 2){
				$new_array[]	= array(
					'header'=>Yii::t('app','Roll No'),
					'value'=>array($model,'studentRollno'),
					'name'=> 'roll_no',
					'sortable'=>true,
				);
			}
				$new_array[]	=	array(
							'header'=>Yii::t('app','Student Name'),
						   // 'value'=>array($model,'studentname'),
							'value'=>'$data->gridStudentName(forTeacherPortal)',  
							'name'=> 'firstname',
							'sortable'=>true,);
				$subject_cps	=	SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$sub->id));
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
				$new_array[]	=array(
                        'header'=>Yii::t('app', 'Grades'),
                        'value'=>array($model,'getgradinglevelteacher'),
                        'name'=> 'grading_level_id',
                    );	
				$new_array[]	=	 array(
						'header'=>Yii::t('app','Remarks'),
						'value'=>array($model,'getRemarks'), 
					);
				$new_array[]	=array(
						'header'=>Yii::t('app', 'Action'),
                        'class'=>'CButtonColumn',
						'deleteConfirmation'=>Yii::t('app', 'Are you sure you want to delete this scores ?'),
                        'buttons' => array(
										'update' => array(
												'label' => Yii::t('app', 'update'), // text label of the button
												'url'=>'Yii::app()->createUrl("/teachersportal/examScores/update", array("id"=>$data->id,"examid"=>$data->exam_id,"bid"=>$_REQUEST["bid"],"exam_group_id"=>$_REQUEST["exam_group_id"]))', // a PHP expression for generating the URL of the button
												'visible'=>'((ExamScores::model()->checkAccess($data) == 1) and ($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 or $is_delete->settings_value!=0)))',
											),
											'delete'=>array(
												'label' => Yii::t('app', 'Delete'), // text label of the button
												'url'=>'Yii::app()->createUrl("/teachersportal/examScores/delete", array("id"=>$data->id))',
												'visible'=>'((ExamScores::model()->checkAccess($data) == 1) and ($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 or $is_delete->settings_value!=0)))',
											),
										
									),
						'template'=>$template,
						'afterDelete'=>'function(){window.location.reload();}',
													
                    );	
                $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'exam-scores-grid',
                'dataProvider'=>$model1->search(),
                'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
                'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
                'columns'=>$new_array,
            )); 
            }
        
        }
        else  // Marks and Grades
        {
            $checkscores = ExamScores::model()->findByAttributes(array('exam_id'=>$_REQUEST['exam_id']));
            if($checkscores!=NULL)
            {				
				$new_array		=	array();
				if(Configurations::model()->rollnoSettingsMode() != 2){
				$new_array[]	= array(
					'header'=>Yii::t('app','Roll No'),
					'value'=>array($model,'studentRollno'),
					'name'=> 'roll_no',
					'sortable'=>true,
				);
			}
				$new_array[]	=	array(
							'header'=>Yii::t('app','Student Name'),
						   // 'value'=>array($model,'studentname'),
							'value'=>'$data->gridStudentName(forTeacherPortal)',  
							'name'=> 'firstname',
							'sortable'=>true,);
				$subject_cps	=	SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$sub->id));
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
				$new_array[]	=	'marks';
				$new_array[]	=array(
                        'header'=>Yii::t('app', 'Grades'),
                        'value'=>array($model,'getgradinglevelteacher'),
                        'name'=> 'grading_level_id',
                    );	
				$new_array[]	=	 array(
						'header'=>Yii::t('app','Remarks'),
						'value'=>array($model,'getRemarks'), 
					);
				$new_array[]	=	array(
						'header'=>Yii::t('app', 'Action'),
                        'class'=>'CButtonColumn',
						'deleteConfirmation'=>Yii::t('app', 'Are you sure you want to delete this scores ?'),
                        'buttons' => array(
                                                                 
											'update' => array(
												'label' => Yii::t('app', 'update'), // text label of the button
												'url'=>'Yii::app()->createUrl("/teachersportal/examScores/update", array("id"=>$data->id,"examid"=>$data->exam_id,"bid"=>$_REQUEST["bid"],"exam_group_id"=>$_REQUEST["exam_group_id"]))', // a PHP expression for generating the URL of the button
												'visible'=>'((ExamScores::model()->checkAccess($data) == 1) and ($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 or $is_delete->settings_value!=0)))',
											),
											'delete'=>array(
												'label' => Yii::t('app', 'Delete'), // text label of the button
												'url'=>'Yii::app()->createUrl("/teachersportal/examScores/delete", array("id"=>$data->id))',
												'visible'=>'((ExamScores::model()->checkAccess($data) == 1) and ($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 or $is_delete->settings_value!=0)))',
											),
										),
						'template'=>$template,
						'afterDelete'=>'function(){window.location.reload();}',
						);	
                $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'exam-scores-grid',
                'dataProvider'=>$model1->search(),
                'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
                'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
                'columns'=>$new_array,
            )); 
            }
        }
       
        echo '</div></div>';
        
        
	}
	else
	{
		echo '<div class="notifications nt_red">'.'<i>'.Yii::t('examination','No Scores Entered').'</i></div>'; 
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
			url:'<?php echo Yii::app()->createUrl("/teachersportal/examScores/allexamscore", array("id"=>$_REQUEST['id'], "exam_id"=>$_REQUEST['exam_id']));?>',
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
