<style>
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
	if($_REQUEST['allexam']==1){
		$actionUrl = CController::createUrl('/teachersportal/examScores/classexamupdate',array("id"=>$model->id,"bid"=>$batch_id,"exam_group_id"=>$exam_group_id,"exam_id"=>$exam_id,'allexam'=>1,'employee_id'=>$employee_id));
	}
	else
	{
		$actionUrl = CController::createUrl('/teachersportal/examScores/classexamupdate',array("id"=>$model->id,"bid"=>$batch_id,"exam_group_id"=>$exam_group_id,"exam_id"=>$exam_id,'employee_id'=>$employee_id));
	}
	$form=$this->beginWidget('CActiveForm', array(
	'id'=>'exam-scores-form',
	//'action' => $actionUrl,
	'enableAjaxValidation'=>false,
)); ?>

<div class="pageheader">
      <h2><i class="fa fa-gear"></i> <?php echo Yii::t("app", "Exams");?> <span><?php echo Yii::t("app", "View your exams here");?></span></h2>
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
                            <div class="opnsl_actn_box1"><?php echo CHtml::link('<span>'.Yii::t('app','All Classes').'</span>',array('/teachersportal/exams/allexam'),array('class'=>'btn btn-primary'));?></div>
                            <?php if($class_count>0){ ?>  
                            <div class="opnsl_actn_box2"><?php echo CHtml::link('<span>'.Yii::t('app','My Class').'</span>',array('/teachersportal/exams/classexam'),array('class'=>'btn btn-primary'));?></div>
                             <?php } ?> 
                               	</div>
                       		 	
                   	 </div>	
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
							<?php
							$exam_group_id =$_REQUEST['exam_group_id'];
							 if($exam_group_id!=NULL)
                            { 
								$exam=ExamGroups::model()->findByAttributes(array('id'=>$exam_group_id,'batch_id'=>$batch_id));
							?>
								<td>
									<strong><?php echo Yii::t('app','Exam'); ?>: </strong><?php echo $exam->name; ?>
								</td>
                            <?php 
                            }
							$exam_id =$_REQUEST['examid'];
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
<div>
<h5 class="subtitle">Enter Exam Scores</h5>
	<table class="table table-bordered mb30">
        
        <thead>
        <tr>
        	<?php 
			$student = Students::model()->findByAttributes(array('id'=>$model->student_id));
			?>
            <th><?php echo Yii::t('app','Student Name');?></th>
            <th><?php echo ucfirst($student->first_name).' '.ucfirst($student->last_name); ?></th>
        </tr>
        </thead>
       
        <?php
    $subject_cps	=	ExamScoresSplit::model()->findAllByAttributes(array('exam_scores_id'=>$model->id));
	$exm = Exams::model()->findByAttributes(array('id'=>$_GET['examid']));
	$sub = Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
	$subject_splits	=	SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$sub->id)); 
	$subject_array	=	array();
	foreach($subject_splits as $subject_split){
		$subject_array[]=$subject_split->split_name;
	}
    if(count($subject_cps) !=0){
        $k=1;
        foreach($subject_cps as $subject_cp)
        {
            $att			=	'sub_category'.$k;
            $model->$att	=	$subject_cp->mark;
        ?>
       <tr>
            <td><label><?php echo $subject_array[$k-1]?></label></td>
            <td><?php echo $form->textField($model,'sub_category'.$k,array('size'=>7,'maxlength'=>3,'style'=>'width:200px;','class'=>'mark'.$k)); ?>
            
            <div class="mark_err"></div>
            <?php echo $form->error($model,'sub_category'.$k); ?>
            </td>
        </tr>
         
        <?php
        $k++;
        }
    }else{
    ?><td style="display:none"><?php 
        echo $form->textField($model,'sub_category1',array('value'=>0,'size'=>7,'maxlength'=>3,'class'=>'mark1','style'=>'display:none')); 
        ?></td><td style="display:none"><?php
        echo $form->textField($model,'sub_category2',array('value'=>0,'size'=>7,'maxlength'=>3,'class'=>'mark2','style'=>'display:none')); ?></td><?php
    }?> 
     <tr>
        <tr>
            <td><?php echo $form->labelEx($model,'marks'); ?></td>
            <?php
			 if(count($subject_cps) !=0){
				 ?>
            <td><?php echo $form->textField($model,'marks',array('size'=>7,'maxlength'=>7,'class'=>'total','readonly'=>true,'style'=>'width:200px;')); ?></td>
            <?php
			 }else{ ?>
            <td><?php echo $form->textField($model,'marks',array('size'=>7,'maxlength'=>7,'class'=>'total','style'=>'width:200px;')); ?></td>
            <?php
			 }?>
            <?php echo $form->error($model,'marks'); ?>
		</tr>
		<?php echo $form->hiddenField($model,'grading_level_id'); ?>
        <?php echo $form->error($model,'grading_level_id'); ?>
		
        <tr>
         <td><?php echo $form->labelEx($model,'remarks'); ?></td>
         <td><?php echo $form->textField($model,'remarks',array('encode'=>false,'size'=>60,'maxlength'=>255,'class'=>'form-control','style'=>'width:200px;')); ?></td>
            <?php echo $form->error($model,'remarks'); ?>
        </tr>
    </table>

	<?php echo $form->hiddenField($model,'updated_at',array('value'=>date('Y-m-d'))); ?>
		

<div class="opnsl_headerBox">
    <div class="opnsl_actn_box"> </div>
    <div class="opnsl_actn_box">
<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),array('class'=>'btn btn-primary')); ?>
	</div>
</div>

<?php $this->endWidget(); ?>

</div></div><!-- form -->
</div><!-- form -->
<script>
$('.mark1').change(function(e) {
	var mark_val	= $('.mark2').val();
	var total		= parseInt($(this).val())+parseInt(mark_val);
	if(!isNaN(total)){
		$('.total').val(total);
	}
});
    
$('.mark2').change(function(e) {
  var mark_val 		= $('.mark1').val();
	var total		= parseInt($(this).val())+parseInt(mark_val);
	if(!isNaN(total)){
		$('.total').val(total);
	}
});
</script>