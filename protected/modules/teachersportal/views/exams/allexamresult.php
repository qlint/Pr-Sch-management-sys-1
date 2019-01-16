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
		<h3 class="panel-title"><?php echo Yii::t('app', 'Exam Results'); ?></h3>
	</div>
    <div class="people-item">
    
<div class="opnsl_headerBox">
	<div class="opnsl_actn_box"> </div>
    <div class="opnsl_actn_box">
        <div class="opnsl_actn_box1">
		<?php echo CHtml::link('<span>'.Yii::t('app','Tutor Classes').'</span>',array('/teachersportal/exams/allexam'),array('class'=>'btn btn-primary'));?>
        </div>
        
        <?php if($class_count>0){ ?>  
        <div class="opnsl_actn_box2">
		<?php echo CHtml::link('<span>'.Yii::t('app','My Class').'</span>',array('/teachersportal/exams/classexam'),array('class'=>'btn btn-primary'));?>
        </div>
        <?php } ?> 
	</div>
</div>    
    
    <div>
		<?php 
        $batch=Batches::model()->findByAttributes(array('id'=>$batch_id));
        if($batch!=NULL)
        { ?>
        <div class="table-responsive">
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
                <?php
                $semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
                if($batch->semester_id!=NULL){ ?>
                <td> <strong> <?php echo Yii::t('app','Semester'); ?>: </strong><?php echo ($semester->name)?$semester->name:"-";?> 
                <?php } ?>
                <td> <strong> <?php echo Yii::t('app','Exam'); ?>: </strong><?php echo $exam->name; 
                }?></td>
            </tr>
        </table>
    </div>
                   
                
    <?php 
		   }?>
    
<div class="opnsl_headerBox">
	<div class="opnsl_actn_box"> </div>
    <div class="opnsl_actn_box">
        <div class="opnsl_actn_box1">
		<?php
			if(Yii::app()->controller->action->id=='allexamresult'){
				$url = '/teachersportal/exams/allexams';
				$scoreUrlExp = 'Yii::app()->createUrl("/teachersportal/examScores/allexamscore",array("bid"=>'.$batch_id.',"exam_group_id"=>'.$exam_group_id.',"exam_id"=>$data->id))';
			}			
			if($exam_group_id!=NULL){
				echo CHtml::link('<span>'.Yii::t('app','View Exam List').'</span>', array($url,'bid'=>$batch_id,'employee_id'=>$employee_id),array('id'=>'add_exam-groups','class'=>'btn btn-primary')); 
			}
			?>
        </div>
        <div class="opnsl_actn_box2">
		
		<?php echo CHtml::link('<span>'.Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>', array('/teachersportal/exams/allexam','employee_id'=>$employee_id),array('id'=>'add_exam-groups','class'=>'btn btn-primary')); ?>
        </div>
	</div>
</div> 
    
    
    
    
    <?php
    if($exam_group_id==NULL){ // If $exam_group_id == NULL, list of exams will be displayed
		$this->renderPartial('/teachersportal/exams/index',array('employee_id'=>$employee->id,'batch_id'=>$batch_id)); 
	}
	else{ //If $exam_group_id != NULL, details of the selected exam will be displayed
		//echo '<br/>Exam Group ID: '.$exam_group_id.'<br/>';
		
		$checkgroup = Exams::model()->findByAttributes(array('exam_group_id'=>$exam_group_id));
		if($checkgroup!=NULL)
		{?>
		<div >
		
		<?php 
			$model=new Exams('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($exam_group_id))
			$model->exam_group_id=$exam_group_id;
			
		 ?>
         <br />
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
				array(

				'class'=>'CLinkColumn',

				'labelExpression'=>array($model,'scorelabel'),

				'urlExpression'=>$scoreUrlExp,

				'header'=>'Score',

				'headerHtmlOptions'=>array('style'=>'color:#FF6600')

				),
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
</div>
</div>