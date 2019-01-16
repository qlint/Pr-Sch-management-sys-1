<?php echo $this->renderPartial('/default/leftside');?>
<div class="pageheader">
      <h2><i class="fa fa-pencil"></i> Exams <span>View your exams here</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">You are here:</span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
          <li class="active">Exams</li>
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
                            <div class="opnsl_actn_box1"><?php echo CHtml::link('<span>'.Yii::t('app','Tutor Classes').'</span>',array('/teachersportal/exams/allexam'),array('class'=>'btn btn-primary'));?></div>
                            <div class="opnsl_actn_box1"><?php echo CHtml::link('<span>'.Yii::t('app','My Class').'</span>',array('/teachersportal/exams/classexam'),array('class'=>'btn btn-primary'));?></div>
                               	</div>
                       		 	
                   	 </div>
    
    
<div >
	<?php
		/*echo 'Employee ID:'.$employee_id.'<br/>';
		echo 'Action ID:'.Yii::app()->controller->action->id.'<br/>';
		echo 'Batch ID:'.$batch_id.'<br/>';
		echo 'Exam Group ID:'.$exam_group_id.'<br/>';*/
	$batch=Batches::model()->findByAttributes(array('id'=>$batch_id));
	$course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
	$semester_enabled	= 	Configurations::model()->isSemesterEnabled(); 
	$sem_enabled		= 	Configurations::model()->isSemesterEnabledForCourse($course->id);
        if($batch!=NULL)
		   { ?>
               <table class="table table-bordered mb30"> 
               <tr>
               <td><?php echo Yii::t('app','Course'); ?>:</strong>
                        <?php 
                        if($course!=NULL)
                           {
                               echo $course->course_name; 
                           }?></td>
               <td> <strong> 
                   
                    <strong> <?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?>: </strong><?php echo $batch->name; ?>
                    <?php if($exam_group_id!=NULL){ 
					$exam=ExamGroups::model()->findByAttributes(array('id'=>$exam_group_id,'batch_id'=>$batch_id));
					?></td>
					
					
               <td>
                    <strong> <?php echo Yii::t('app','Exam'); ?>: </strong><?php echo $exam->name; 
					}?></td>
		 <?php if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id != NULL){
					 $semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); ?>	
				 <td><?php echo Yii::t('app','Semester'); ?>:</strong>
                        <?php echo ucfirst($semester->name);?></td>	
		<?php } ?>
               </tr>
               </table>
                  
                  
               
    <?php 
		   }?>
    
<div class="opnsl_headerBox">
<div class="opnsl_actn_box"></div>
<div class="opnsl_actn_box">
    <div class="opnsl_actn_box1">
    <?php	
			$checkgroup = CbscExams::model()->findByAttributes(array('exam_group_id'=>$exam_group_id));		
			$url = '/teachersportal/exams/classexams';
			if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 1)
			{
				$scoreUrlExp = 'Yii::app()->createUrl("/teachersportal/examScores/classexamscore",array("bid"=>'.$batch_id.',"exam_group_id"=>'.$exam_group_id.',"exam_id"=>$data->id))';
			}
			else
			{
				$scoreUrlExp = 'Yii::app()->createUrl("/teachersportal/examScores/cbscclassexamscore",array("bid"=>'.$batch_id.',"exam_group_id"=>'.$exam_group_id.',"exam_id"=>$data->id))';
			}
			
            if($exam_group_id!=NULL){
				echo CHtml::link('<span>'.Yii::t('app','View Exam List').'</span>', array($url,'bid'=>$batch_id,'employee_id'=>$employee_id),array('id'=>'add_exam-groups','class'=>'addbttn')); 
			}
			?>
    </div>
    <div class="opnsl_actn_box1">
    <?php echo CHtml::link('<span>'.Yii::t('examination','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>', array('/teachersportal/exams/classexam'),array('id'=>'add_exam-groups','class'=>'addbttn')); ?>
    </div>
        </div>
        
</div>
        
    
    
    <?php
    if($exam_group_id==NULL){ // If $exam_group_id == NULL, list of exams will be displayed
		$this->renderPartial('/teachersportal/exams/index',array('employee_id'=>$employee->id,'batch_id'=>$batch_id)); 
	}
	else{ //If $exam_group_id != NULL, details of the selected exam will be displayed
		//echo '<br/>Exam Group ID: '.$exam_group_id.'<br/>';
		
	if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 2) //check for cbsc examination format
	{	
		$checkgroup = CbscExams::model()->findByAttributes(array('exam_group_id'=>$exam_group_id));
		if($checkgroup!=NULL)
		{?>
		<div >
		<div >
		<?php 
			$model=new CbscExams('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($exam_group_id))
			$model->exam_group_id=$exam_group_id;
			
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
               
				array(
				'class'=>'CLinkColumn',
				'labelExpression'=>array($model,'scorelabel'),
				'urlExpression'=>$scoreUrlExp,
				'header'=>'Score',
				'headerHtmlOptions'=>array('style'=>'color:#FF6600')
				),
              
            ),
        )); echo '</div></div>';
		}
		else{
				?>
                <div class="clearfix"></div>
                    <div class="y_bx_head" style=" text-align:center;">
                         <?php echo Yii::t('app','Exam details not yet set!'); ?>
                    </div>             			
                <?php
		}
	}
	else //for default examination
	{
		$checkgroup = Exams::model()->findByAttributes(array('exam_group_id'=>$exam_group_id));
		if($checkgroup!=NULL)
		{?>
		<div >
		<div >
		<?php 
			$model=new Exams('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($exam_group_id))
			$model->exam_group_id=$exam_group_id;
			
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
				array(
				'class'=>'CLinkColumn',
				'labelExpression'=>array($model,'scorelabel'),
				'urlExpression'=>$scoreUrlExp,
				'header'=>'Score',
				'headerHtmlOptions'=>array('style'=>'color:#FF6600')
				),
                
            ),
        )); echo '</div></div>';
		}
		else{
				?>
                <div class="clearfix"></div>
                    <div class="y_bx_head" style=" text-align:center;">
                         <?php echo Yii::t('app','Exam details not yet set!'); ?>
                    </div>             			
                <?php
		}	
	}
			
	}
   ?>
 </div>
 </div>
</div>