<?php echo $this->renderPartial('/default/leftside');?>
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
                <li><?php echo CHtml::link('<span>'.Yii::t('app','All Classes').'</span>',array('/teachersportal/exams/allexam'),array('class'=>'addbttn last'));?></li>                
                <li><?php echo CHtml::link('<span>'.Yii::t('app','My Class').'</span>',array('/teachersportal/exams/classexam'),array('class'=>'addbttn last'));?></li>                
    		</ul>
    		<div class="clear"></div>
		</div>
	</div>
		<h3 class="panel-title"><?php echo Yii::t('app', 'Scheduled Subjects'); ?></h3>
	</div>
    <div class="people-item">
<div>
	<?php
		/*echo 'Employee ID:'.$employee_id.'<br/>';
		echo 'Action ID:'.Yii::app()->controller->action->id.'<br/>';
		echo 'Batch ID:'.$batch_id.'<br/>';*/
	$tutor  = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$batch=Batches::model()->findByAttributes(array('id'=>$batch_id));
	$course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
	$semester_enabled	= 	Configurations::model()->isSemesterEnabled(); 
	$sem_enabled		= 	Configurations::model()->isSemesterEnabledForCourse($course->id);
        if($batch!=NULL)
		   { ?>
               <div class="formCon">
                   <div class="formConInner">
                   <strong> <?php echo Yii::t('app','Course'); ?>:</strong>
                        <?php 
                        if($course!=NULL)
                           {
                               echo $course->course_name; 
                           }?>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <strong> <?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?>: </strong><?php echo $batch->name; ?>
                    <?php if($exam_group_id!=NULL){ 
					$exam=ExamGroups::model()->findByAttributes(array('id'=>$exam_group_id,'batch_id'=>$batch_id));
					?>
					 <?php if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id != NULL){
					 			$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); ?>	
							 	&nbsp;&nbsp;&nbsp;&nbsp;
								<strong> <?php echo Yii::t('app','Semester'); ?>: </strong><?php echo ucfirst($semester->name); 
					 		}?>
					
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <strong> <?php echo Yii::t('app','Exam'); ?>: </strong><?php echo $exam->name; ?>
                    <?php
                    $is_classteacher=Batches::model()->findByAttributes(array('id'=>$batch_id,'employee_id'=>$tutor->id));
					$classteacher = Employees::model()->findByAttributes(array('id'=>$is_classteacher->employee_id));
					if(Yii::app()->controller->action->id=='classexamschedule' and $is_classteacher==NULL){ // Redirecting if action ID is classexam and the employee is not classteacher
						$this->redirect(array('/teachersportal/exams/index'));
					}
					if(count($classteacher)>0){
					?>
                    <br />
					<strong> <?php echo Yii::t('app','Class Teacher'); ?>: </strong><?php echo Employees::model()->getTeachername($classteacher->id); ?>
					
					<?php
					}
					?>
                    <?php
					}?>
              </div>
          </div> 
               
    <?php 
		   }?>
    <div class="edit_bttns" style=" float:right">
        <ul>
        	<?php			
			$url = '/teachersportal/exams/classexams';
			//$urlExp = 'Yii::app()->createUrl("/teachersportal/default/classexam",array("bid"=>$data->batch_id,"exam_group_id"=>$data->id))';			
			if($exam_group_id!=NULL){
			?>
            <li><span>
            <?php 
				echo CHtml::link('<span>'.Yii::t('app','View Exam List').'</span>', array($url,'bid'=>$batch_id),array('id'=>'add_exam-groups','class'=>'addbttn')); 
			
			?>
        	</span></li>
            <?php
			}
			?>
            <li><span>
        	<?php echo CHtml::link('<span>'.Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>', array('/teachersportal/exams/classexam'),array('id'=>'add_exam-groups','class'=>'addbttn')); ?>
        	</span></li>
        </ul>
        <div class="clear"></div>
    </div>
    <?php
    if($exam_group_id==NULL){ // If $exam_group_id == NULL, list of exams will be displayed
		$this->renderPartial('/teachersportal/exams/index',array('employee_id'=>$employee_id,'batch_id'=>$batch_id));
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