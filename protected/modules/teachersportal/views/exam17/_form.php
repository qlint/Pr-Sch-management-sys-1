<style>
.mark,.total{ 
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
	
	$form=$this->beginWidget('CActiveForm', array(
	'id'=>'exam-scores-form', 
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
            <div class="opnsl_actn_box"></div>
            <div class="opnsl_actn_box"> 
            <div class="opnsl_actn_box1">
             <?php echo CHtml::link('<span>'.Yii::t('app','All Classes').'</span>',array('/teachersportal/exams/allexam'),array('class'=>'btn btn-primary'));?> 
            </div>
           	<div class="opnsl_actn_box1">
              <?php if($class_count>0){ ?> 
                
                <?php echo CHtml::link('<span>'.Yii::t('app','My Class').'</span>',array('/teachersportal/exams/classexam'),array('class'=>'btn btn-primary'));?>                
			  <?php } ?>  
            </div>       
              
                             
               		
            

		</div>
	</div>
    <?php $batch=Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));?>
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
                             <?php $sem_enabled	= 	Configurations::model()->isSemesterEnabledForCourse($course->id);
							  		$semester				=	Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
		 						 if($sem_enabled==1 and $batch->semester_id!=NULL){ ?>
                                  <td>
								  <strong><?php echo Yii::t('app','Semester');?>:</strong>
								 <?php    echo ucfirst($semester->name);?>
                                 </td>
                                 <?php
								 }
								 ?>
                              
                            </tr>
                            <tr>
							<?php if($_REQUEST['exam_group_id']!=NULL)
                            { 
								$exam=CbscExamGroup17::model()->findByAttributes(array('id'=>$_REQUEST['exam_group_id'],'batch_id'=>$batch->id));
							?>
								<td>
									<strong><?php echo Yii::t('app','Exam'); ?>: </strong><?php echo $exam->name; ?>
								</td>
                            <?php 
                            }
							if($_REQUEST['examid']!=NULL)
							{ 
								$subject_id=CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
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
							$is_classteacher=Batches::model()->findByAttributes(array('id'=>$batch_id,'employee_id'=>$tutor->id));
							$classteacher = Employees::model()->findByAttributes(array('id'=>$is_classteacher->employee_id));
							if(Yii::app()->controller->action->id=='classexamupdate' and $classteacher==NULL){ // Redirecting if action ID is classexam and the employee is not classteacher
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
                        </table>
    
<div>
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
        <tr>
            <td><?php echo $form->labelEx($model,'written_exam'); ?></td>
            <td><?php echo $form->textField($model,'written_exam',array('encode'=>false,'size'=>60,'maxlength'=>255,'class'=>'form-control','style'=>'width:200px;','class'=>'mark')); ?>
            <?php echo $form->error($model,'written_exam'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model,'periodic_test'); ?></td>
            <td><?php echo $form->textField($model,'periodic_test',array('encode'=>false,'size'=>60,'maxlength'=>255,'class'=>'form-control','style'=>'width:200px;','class'=>'mark')); ?>
            <?php echo $form->error($model,'periodic_test'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model,'note_book'); ?></td>
            <td><?php echo $form->textField($model,'note_book',array('encode'=>false,'size'=>60,'maxlength'=>255,'class'=>'form-control','style'=>'width:200px;','class'=>'mark')); ?>
            <?php echo $form->error($model,'note_book'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model,'subject_enrichment'); ?></td>
            <td><?php echo $form->textField($model,'subject_enrichment',array('encode'=>false,'size'=>60,'maxlength'=>255,'class'=>'form-control','style'=>'width:200px;','class'=>'mark')); ?>
            <?php echo $form->error($model,'subject_enrichment'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model,'total'); ?></td>
            <td><?php echo $form->textField($model,'total',array('encode'=>false,'size'=>60,'maxlength'=>255,'class'=>'form-control','style'=>'width:200px;','readOnly'=>true)); ?>
            <?php echo $form->error($model,'total'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($model,'remarks'); ?></td>
            <td><?php echo $form->textField($model,'remarks',array('encode'=>false,'size'=>60,'maxlength'=>255,'class'=>'form-control','style'=>'width:200px;')); ?>
            <?php echo $form->error($model,'remarks'); ?></td>
        </tr>
         <tr>
            <td><div class="row buttons" style="padding-top:0px; padding-left:10px;">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),array('class'=>'btn btn-danger')); ?>
	</div></td>
            <td></td>
        </tr>
    </table>

	<?php echo $form->hiddenField($model,'updated_at',array('value'=>date('Y-m-d'))); ?>
		

	

<?php $this->endWidget(); ?>

</div></div><!-- form -->
</div>
<script>
$('.mark').change(function(e) {
	var total	=	0;
	if($('#CbscExamScores17_written_exam').val() != ''){
		var m1	= $('#CbscExamScores17_written_exam').val();
		total 	= total+parseFloat(m1);
	}	
	if($('#CbscExamScores17_periodic_test').val() != ''){
		var m2	= $('#CbscExamScores17_periodic_test').val();
		total 	= total+parseFloat(m2);
	}
	if($('#CbscExamScores17_note_book').val() != ''){
		var m3	= $('#CbscExamScores17_note_book').val();
		total 	= total+parseFloat(m3);
	}
	if($('#CbscExamScores17_subject_enrichment').val() != ''){
		var m4	= $('#CbscExamScores17_subject_enrichment').val();
		total 	= total+parseFloat(m4);
	}  
	if(!isNaN(total)){
		$('#CbscExamScores17_total').val(total.toFixed(1));
	}
}); 
</script>