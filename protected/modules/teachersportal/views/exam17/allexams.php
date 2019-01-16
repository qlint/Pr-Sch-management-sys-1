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
    	
		<h3 class="panel-title"><?php echo Yii::t('app', 'Exam Details'); ?></h3>
	</div>
    <div class="people-item">
            <div class="opnsl_headerBox">
            	<div class="opnsl_actn_box"> </div>
                <div class="opnsl_actn_box">
                    <div class="opnsl_actn_box1"><?php echo CHtml::link('<span>'.Yii::t('app','Tutor Classes').'</span>',array('/teachersportal/exams/allexam'),array('class'=>'addbttn last'));?></div>
                    <?php if($class_count>0){ ?>  
                    <div class="opnsl_actn_box1">
                    <?php echo CHtml::link('<span>'.Yii::t('app','My Class').'</span>',array('/teachersportal/exams/classexam'),array('class'=>'addbttn last'));?>
                    </div>
                    <?php } ?> 
                    <div class="opnsl_actn_box1">
                    <?php echo CHtml::link('<span>'.Yii::t('examination','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>', array('/teachersportal/exams/index'),array('id'=>'add_exam-groups','class'=>'addbttn')); ?>
                    </div>
                </div>
            </div>
<div>
	<?php
		/*echo 'Employee ID:'.$employee_id.'<br/>';
		echo 'Action ID:'.Yii::app()->controller->action->id.'<br/>';
		echo 'Batch ID:'.$batch_id.'<br/>';*/
		
	$batch=Batches::model()->findByAttributes(array('id'=>$batch_id));
	$course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
	$semester_enabled	= Configurations::model()->isSemesterEnabled(); 
	$sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);
        if($batch!=NULL)
		   { ?>
                   
                   <div class="table-responsive">
                    	<table class="table table-bordered mb30">
                        	<tr>
                            	<td><strong> <?php echo Yii::t('app','Course'); ?>:</strong>
                        <?php 
                        if($course!=NULL)
                           {
                               echo $course->course_name; 
                           }?></td>
                                <td><strong> <?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?>: </strong><?php echo $batch->name; ?>
                    <?php if($exam_group_id!=NULL){ 
					$exam=ExamGroups::model()->findByAttributes(array('id'=>$exam_group_id,'batch_id'=>$batch_id));
					?>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <strong> <?php echo Yii::t('app','Exam'); ?>: </strong><?php echo $exam->name; 
					}?></td>
					<?php  if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id != NULL){ ?>
								<td><strong> <?php echo Yii::t('app','Semester'); ?>:</strong>
                        			<?php $semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
                               				echo ucfirst($semester->name); ?>
								</td>
                     <?php }?>
                            </tr>
                        </table>
                        </div>
                   
                
    <?php 
		   }?>
    <div class="edit_bttns" style=" float:right">
        <ul>
        	<?php
			if(Yii::app()->controller->action->id=='allexams'){
				$url = '/teachersportal/exams/allexam';
				$scheduleUrlExp = 'Yii::app()->createUrl("/teachersportal/exam17/allexamschedule",array("bid"=>$data->batch_id,"exam_group_id"=>$data->id))';
				$resultUrlExp = 'Yii::app()->createUrl("/teachersportal/exam17/allexamresult",array("bid"=>$data->batch_id,"exam_group_id"=>$data->id))';
			}			
			?>
           
        </ul>
        <div class="clear"></div>
    </div>
    <div class="table-responsive">
    <?php
    if($exam_group_id==NULL){ // If $exam_group_id == NULL, list of exams will be displayed
		$criteria=new CDbCriteria(array('condition'=>'batch_id='.$batch_id));
		$dataProvider=new CActiveDataProvider('CbscExamGroup17', array('criteria'=>$criteria));
		$this->widget('zii.widgets.grid.CGridView', array(
			 'id' => 'exam-groups-grid',
			 'dataProvider' => $dataProvider,
			 'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
			 'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
			 
			 'htmlOptions'=>array('class'=>'grid-view clear'),
			  'columns' => array(	
			
			'name',
			//'type',
			array(	
				'name'=>'type',
				'value'=>'CbscExamGroup17::Exam($data->type)',
				'filter'=>false
			), 
			array(	
				'name'=>'class',
				'value'=>'CbscExamGroup17::ClassTypeData($data->class)',
				'filter'=>false
			),
			
			array(
			'class'=>'CLinkColumn',
			'labelExpression'=>'$data->date_published ? Yii::t("app", "View Schedule") : Yii::t("app", "Not Published")',
			'urlExpression'=>'$data->date_published ? '.$scheduleUrlExp.' : "#"',
			'header'=>'Is Published',
			'headerHtmlOptions'=>array('style'=>'')
			), 
                         
			array(
			'class'=>'CLinkColumn',
			'labelExpression'=>'$data->result_published ? Yii::t("app", "View Results") : ($data->date_published ? Yii::t("app", "Enter Scores") : Yii::t("app", "No Results Published"))',
			'urlExpression'=>'$data->result_published ? '.$resultUrlExp.' : ($data->date_published ? '.$resultUrlExp.' : "#")',
			'header'=>'Result Published',
			'headerHtmlOptions'=>array('style'=>'')
			),
			
		),
			   'afterAjaxUpdate'=>'js:function(id,data){$.bind_crud()}'
	   ));
	}
	else{ //If $exam_group_id != NULL, details of the selected exam will be displayed
		//echo '<br/>Exam Group ID: '.$exam_group_id.'<br/>';
			
			$checkgroup = CbscExams17::model()->findByAttributes(array('exam_group_id'=>$_REQUEST['exam_group_id']));
			if($checkgroup!=NULL)
			{?>
			<div >
            </div>
			<div >
			<?php $model1=new CbscExams17('search');
				  $model1->unsetAttributes();  // clear any default values
				  if(isset($_GET['exam_group_id']))
					$model1->exam_group_id=$_GET['exam_group_id'];
				 
				 
				  ?>
				  <h3> <?php echo Yii::t('app','Scheduled Subjects'); ?></h3>
				  <?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'exams-grid',
			'dataProvider'=>$model1->search(),
			'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
			'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
			
			'columns'=>array(
				
				array(
					'name'=>'subject_id',
					'value'=>array($model1,'subjectname')
				
				),
				'start_time',
				'end_time',
				'maximum_marks',
				'minimum_marks',
			),
		)); echo '</div></div>';}
		else
		{
			echo '<div class="notifications nt_red"><i>'.Yii::t('app','Nothing Scheduled').'</i></div>'; 
			}
	}
   ?>
 
</div>
</div>
</div>