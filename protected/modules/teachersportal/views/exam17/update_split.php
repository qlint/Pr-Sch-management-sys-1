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
    	<div class="btn-demo" style="position:relative; top:-8px; right:3px; float:right;">
        <div class="edit_bttns">
    		<ul>       
                <li></li>                
              
            </ul>
    		<div class="clear"></div>
		</div>
	</div>
		<h3 class="panel-title"><?php echo Yii::t('app', 'Update Exam Scores'); ?></h3>
	</div>
    <div class="people-item">
    
<div class="opnsl_headerBox">
            <div class="opnsl_actn_box"></div>
            <div class="opnsl_actn_box"> 
            <div class="opnsl_actn_box1">
			<?php echo CHtml::link('<span>'.Yii::t('app','All Classes').'</span>',array('/teachersportal/exams/allexam','employee_id'=>$employee_id),array('class'=>'btn btn-primary'));?>
            </div>
           	<div class="opnsl_actn_box1">
              <?php if($class_count>0){ ?>  
                <?php echo CHtml::link('<span>'.Yii::t('app','My Class').'</span>',array('/teachersportal/exams/classexam','employee_id'=>$employee_id),array('class'=>'btn btn-primary'));?>                
    		  <?php } ?>  
            </div>       
		</div>
	</div>
    
    
<div>
<?php
	$batch_id = $_REQUEST['bid'];
	$exam_group_id = $_REQUEST['exam_group_id'];
	$exam_id		= $_REQUEST['exam_id'];
	$tutor  = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$batch=Batches::model()->findByAttributes(array('id'=>$batch_id));
        if($batch!=NULL)
		   {
			   
			   ?>
             
               <div class="formCon"> <!-- Batch Details Tab -->
					<div class="formConInner">
                    	<table class="table table-bordered mb30">
                        	<tr>
                            	<td>
                       				<strong><?php echo Yii::t('app','Course'); ?>:</strong>
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
                                 <?php if($exam_group_id!=NULL)
                            { 
								$exam=CbscExamGroup17::model()->findByAttributes(array('id'=>$exam_group_id,'batch_id'=>$batch_id));
							?>
								<td>
									<strong><?php echo Yii::t('examination','Exam'); ?>: </strong><?php echo $exam->name; ?>
								</td>
                            <?php 
                            }?>
                            </tr>
                            <tr>
							<?php
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
                              <?php
							$empid = EmployeesSubjects::model()->findByAttributes(array('subject_id'=>$subject_id->subject_id));
							if(count($empid)>0){
								$subject_teacher = Employees::model()->findByAttributes(array('id'=>$empid->employee_id));
							?>
								<td>
                                	<strong><?php echo Yii::t('app','Subject Teacher'); ?>: </strong><?php echo Employees::model()->getTeachername($subject_teacher->id); ?>
								</td>
							<?php
							}?>
                        	</tr>
                            <tr>
                          
                            <?php
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
					</div>
          	</div>    
    	<?php 
		   }?>
           <div class="edit_bttns" style=" float:right">
        <ul>
        	<?php
			if(Yii::app()->controller->module->id=='teachersportal' and  Yii::app()->controller->id =='exam17' and  Yii::app()->controller->action->id =='updateSplit')
			{
				$url_subject_list = '/teachersportal/exam17/allexamresult';
				$url_exam_list = '/teachersportal/exam17/allexams';
				$url_change_batch = '/teachersportal/exams/index';	
		
			}
			else{
				if(isset($_REQUEST['allexam']) and $_REQUEST['allexam']=='1')
				{
					$url_subject_list = '/teachersportal/exams/allexamresult';
					$url_exam_list = '/teachersportal/exams/allexams';
					$url_change_batch = '/teachersportal/exams/allexam';				
				}
				else
				{
					$url_subject_list = '/teachersportal/exams/classexamresult';
					$url_exam_list = '/teachersportal/exams/classexams';
					$url_change_batch = '/teachersportal/exams/classexam';
					
				}
			}
			if($exam_id!=NULL)
			{
			?>
            <li><span>
            <?php 
				echo CHtml::link(Yii::t('app','View Subject List'), array($url_subject_list,'bid'=>$batch_id,'exam_group_id'=>$exam_group_id,'employee_id'=>$employee_id),array('id'=>'add_exam-groups','class'=>'addbttn')); 
			
			?></span>
        	</li>
            <?php
			}
			if($exam_group_id!=NULL)
			{
			?>
            <li><span>
            <?php 
				echo CHtml::link(Yii::t('app','View Exam List'), array($url_exam_list,'bid'=>$batch_id,'employee_id'=>$employee_id),array('id'=>'add_exam-groups','class'=>'addbttn')); 
			
			?></span>
        	</li>
            <?php
			}
			?>
            <li><span>
        	<?php echo CHtml::link(Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"), array($url_change_batch,'employee_id'=>$employee_id),array('id'=>'add_exam-groups','class'=>'addbttn')); ?>
        	</span></li>
        </ul>
        <div class="clear"></div>
    </div>
    
    <?php
	$result_published = CbscExamGroup17::model()->countByAttributes(array('id'=>$exam_group_id,'result_published'=>1));
	$is_teaching_subject = TimetableEntries::model()->countByAttributes(array('subject_id'=>$subject_id->subject_id,'employee_id'=>$employee_id));
	$score_flag = 0; // If $score_flag == 0, form for editing scores will not be displayed. If $score_flag == 1, form will be displayed.
	
	?>
	<!-- Start Edit Exam Scores -->
    
	<?php 
	//$model = ExamScores::model()->findByAttributes(array('id'=>$_REQUEST['id']));
    //$this->renderPartial('_form', array('model'=>$model,'batch_id'=>$batch_id,'exam_group_id'=>$exam_group_id,'exam_id'=>$exam_id,'employee_id'=>$employee_id)); // Rendering edit form

	$form=$this->beginWidget('CActiveForm', array(
	'id'=>'exam-scores-form',
	//'action' => $actionUrl,
	'enableAjaxValidation'=>false,
)); 
 ?>
<div>
	<table class="table table-bordered mb30">
        
        <thead>
            
        <tr>
        	<?php 
			$student = Students::model()->findByAttributes(array('id'=>$model->student_id));
			?>
            <th><?php echo Yii::t('app','Student Name');?></th>
            <th><?php 
            $name="";
            $name=  $student->studentFullName('forTeacherPortal');
                    if($name!="")
                    {
                        echo $name;
                    }
                    else
                        echo "-";
            //echo ucfirst($student->first_name).' '.ucfirst($student->last_name); ?></th>
        </tr>
        </thead>
           <?php 
    $subject_cps	=	CbscExamScoresSplit17::model()->findAllByAttributes(array('exam_scores_id'=>$model->id));
	$exm = CbscExams17::model()->findByAttributes(array('id'=>$_GET['exam_id']));
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
            <td><?php echo $form->textField($model,'sub_category'.$k,array('size'=>7,'style'=>'width:200px;','class'=>'mark'.$k)); ?>
            
            <div class="mark_err"></div>
            <?php echo $form->error($model,'sub_category'.$k); ?>
            </td>
        </tr>
         
        <?php
        $k++;
        }
    }else{
    ?><td style="display:none"><?php 
        echo $form->textField($model,'sub_category1',array('value'=>0,'size'=>7,'class'=>'mark1','style'=>'display:none')); 
        ?></td><td style="display:none"><?php
        echo $form->textField($model,'sub_category2',array('value'=>0,'size'=>7,'class'=>'mark2','style'=>'display:none')); ?></td><?php
    }?> 
     <tr>
        <tr>
            <td><?php echo $form->labelEx($model,'total'); ?></td>
            <?php
			 if(count($subject_cps) !=0){
				 ?>
            <td><?php echo $form->textField($model,'total',array('size'=>7,'maxlength'=>7,'class'=>'total','readonly'=>true,'style'=>'width:200px;')); ?></td>
            <?php
			 }else{ ?>
            <td><?php echo $form->textField($model,'total',array('size'=>7,'maxlength'=>7,'class'=>'total','style'=>'width:200px;')); ?></td>
            <?php
			 }?>
            <?php echo $form->error($model,'total'); ?>
		</tr>
        
		
        <tr>
         <td><?php echo $form->labelEx($model,'remarks'); ?></td>
         <td><?php echo $form->textField($model,'remarks',array('size'=>60,'maxlength'=>255,'class'=>'form-control','style'=>'width:200px;')); ?></td>
            <?php echo $form->error($model,'remarks'); ?>
        </tr>
    </table>

	<?php echo $form->hiddenField($model,'updated_at',array('value'=>date('Y-m-d'))); ?>
		
<div class="opnsl_headerBox">
            <div class="opnsl_actn_box"></div>
            <div class="opnsl_actn_box"> 
            <div class="opnsl_actn_box1">
            <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'),array('class'=>'btn btn-primary')); ?>
            </div>
            </div>
            </div>
	
</div> 
<?php $this->endWidget(); ?>

    
    <!-- End Edit Exam Scores -->

    
</div> 
</div>
</div>
<script>
$('.mark1').change(function(e) { 
	var mark_val	= $('.mark2').val();
	if(!isNaN($(this).val()) && !isNaN(mark_val)){
		var total		= parseFloat($(this).val())+parseFloat(mark_val);
		if(!isNaN(total)){
			$('.total').val(total.toFixed(1));
		}
	}else{
		$('#CbscExamScores17_total').val(" ");
	}
});
    
$('.mark2').change(function(e) {
  var mark_val 		= $('.mark1').val();
  if(!isNaN($(this).val()) && !isNaN(mark_val)){
	var total		= parseFloat($(this).val())+parseFloat(mark_val);
	if(!isNaN(total)){
		$('.total').val(total.toFixed(1));
	}
  }
  else{
		$('#CbscExamScores17_total').val(" ");
  }
});
</script>