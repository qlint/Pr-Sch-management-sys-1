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
    		<h3 class="panel-title"><?php echo Yii::t('app', 'Scheduled Subjects'); ?></h3>
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>
<li><?php echo CHtml::link('<span>'.Yii::t('app','All Classes').'</span>',array('/teachersportal/exams/allexam'),array('class'=>'btn btn-primary'));?></li>                
             <?php if($class_count>0){ ?>    
                <li><?php echo CHtml::link('<span>'.Yii::t('app','My Class').'</span>',array('/teachersportal/exams/classexam'),array('class'=>'btn btn-primary'));?></li>                
    		 <?php } ?>
            </ul>

		</div>
	</div>

	</div>
    <div class="people-item">
<div>
	<?php
		/*echo 'Employee ID:'.$employee_id.'<br/>';
		echo 'Action ID:'.Yii::app()->controller->action->id.'<br/>';
		echo 'Batch ID:'.$batch_id.'<br/>';*/
	$batch=Batches::model()->findByAttributes(array('id'=>$batch_id));
        if($batch!=NULL)
		   { ?>
             
                   <table class="table table-bordered mb30">
                   	<tr>
                    	<td><strong> <?php echo Yii::t('app','Course'); ?>:</strong>
                        <?php $course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
                        if($course!=NULL)
                           {
                               echo $course->course_name; 
                           }?></td>
                        <td> <strong> <?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?>: </strong><?php echo $batch->name; ?>
                    <?php if($exam_group_id!=NULL){ 
					$exam=ExamGroups::model()->findByAttributes(array('id'=>$exam_group_id,'batch_id'=>$batch_id));
					?></td>
                    
                    </tr>
                    <tr>
                    	<td><strong> <?php echo Yii::t('app','Exam'); ?>: </strong><?php echo $exam->name; ?>
                         <?php
                    $is_classteacher=Batches::model()->findByAttributes(array('id'=>$batch_id));
					$classteacher = Employees::model()->findByAttributes(array('id'=>$is_classteacher->employee_id));					
					if(Yii::app()->controller->action->id=='classexamschedule' and $classteacher->id != $employee_id){ // Redirecting if action ID is classexam and the employee is not classteacher
						exit;$this->redirect(array('/teachersportal/exams/index'));
					}
					if(count($classteacher)>0){
					?>
                        </td>
                        <td><strong> <?php echo Yii::t('app','Class Teacher'); ?>: </strong><?php echo Employees::model()->getTeachername($classteacher->id); ?>
					
					<?php
					}
					?>
                    <?php
					}?></td>
                    </tr>
					<?php
					$semester_enabled	= 	Configurations::model()->isSemesterEnabled();  
					$sem_enabled		= 	Configurations::model()->isSemesterEnabledForCourse($course->id);?>
					<?php if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id != NULL){ 
							$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));?>
							 <tr>
								<td><strong> <?php echo Yii::t('app','Semester'); ?>: </strong><?php echo ucfirst($semester->name); ?></td>
							</tr>
					<?php } ?>
                   </table> 
              
               
    <?php 
		   }?>
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>

        	<?php			
			if($exam_group_id!=NULL){
			?>
            <li><span>
            <?php 
				echo CHtml::link('<span>'.Yii::t('app','View Exam List').'</span>', array('/teachersportal/exams/allexams','bid'=>$batch_id),array('id'=>'add_exam-groups','class'=>'btn btn-primary')); 
			
			?>
        	</span></li>
            <?php
			}
			?>
            <li><span>
        	<?php echo CHtml::link('<span>'.Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>', array('/teachersportal/exams/allexam'),array('id'=>'add_exam-groups','class'=>'btn btn-primary')); ?>
        	</span></li>
        </ul>
        </div>
    </div>
    <?php	
    if($exam_group_id==NULL){ // If $exam_group_id == NULL, list of exams will be displayed
	
		$this->renderPartial('/teachersportal/exams/allexams',array('batch_id'=>$batch_id));
	}
	else{ //If $exam_group_id != NULL, details of the selected exam will be displayed
		//echo '<br/>Exam Group ID: '.$_REQUEST['exam_group_id'].'<br/>';
			
			$checkgroup = Exams::model()->findByAttributes(array('exam_group_id'=>$exam_group_id));
			if($checkgroup!=NULL)
			{?>
			<div >
			
			<?php $model=new Exams('search');
				  $model->unsetAttributes();  // clear any default values
				  if(isset($_GET['exam_group_id']))
					$model->exam_group_id=$exam_group_id
				 
				  ?>
                
                  <div class="table-responsive">
				  <?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'exams-grid',
			'dataProvider'=>$model->search(),
			'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
			'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
			
			'columns'=>array(
				
				array(
					'name'=>'subject_id',
					'value'=>array($model,'subjectname')
				
				),
				'start_time',
				'end_time',
				'maximum_marks',
				'minimum_marks',
			),
		)); echo '</div></div>';}
		else{
				?>
                <div class="clearfix"></div>
                    <div class="y_bx_head" style=" text-align:center;">
                         <?php echo Yii::t('app','Exam details not yet set!'); ?>
                    </div>      
       			
                <?php
		}
	}
   ?>
 
</div>
</div>
</div>