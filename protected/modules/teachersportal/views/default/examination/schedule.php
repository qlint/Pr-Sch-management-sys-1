<div style="width:800px; padding-left:20px;"><br/><br/>
	<?php
		/*echo 'Employee ID:'.$employee_id.'<br/>';
		echo 'Action ID:'.Yii::app()->controller->action->id.'<br/>';
		echo 'Batch ID:'.$batch_id.'<br/>';*/
	$batch=Batches::model()->findByAttributes(array('id'=>$batch_id));
        if($batch!=NULL)
		   { ?>
               <div class="formCon">
                   <div class="formConInner">
                   <strong> <?php echo Yii::t('app','Course'); ?>:</strong>
                        <?php $course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
                        if($course!=NULL)
                           {
                               echo $course->course_name; 
                           }?>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <strong> <?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?>: </strong><?php echo $batch->name; ?>
                    <?php if($exam_group_id!=NULL){ 
					$exam=ExamGroups::model()->findByAttributes(array('id'=>$exam_group_id,'batch_id'=>$batch_id));
					?>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <strong> <?php echo Yii::t('app','Exam'); ?>: </strong><?php echo $exam->name; ?>
                    <?php
                    $is_classteacher=Batches::model()->findByAttributes(array('id'=>$batch_id));
					$classteacher = Employees::model()->findByAttributes(array('id'=>$is_classteacher->employee_id));
					if(Yii::app()->controller->action->id=='classexam' and $classteacher->id != $employee_id){ // Redirecting if action ID is classexam and the employee is not classteacher
						$this->redirect(array('/teachersportal/default/examination'));
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
    <div class="edit_bttns" style="top:150px; right:50px;">
        <ul>
        	<?php
			if(Yii::app()->controller->action->id=='allexam'){
				$url = '/teachersportal/default/allexam';
				$urlExp = 'Yii::app()->createUrl("/teachersportal/default/allexam",array("bid"=>$data->batch_id,"exam_group_id"=>$data->id))';
			}
			else{
				$url = '/teachersportal/default/classexam';
				$urlExp = 'Yii::app()->createUrl("/teachersportal/default/classexam",array("bid"=>$data->batch_id,"exam_group_id"=>$data->id))';
			}
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
        	<?php echo CHtml::link('<span>'.Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>', array($url),array('id'=>'add_exam-groups','class'=>'addbttn')); ?>
        	</span></li>
        </ul>
        <div class="clear"></div>
    </div>
    <?php
    if($exam_group_id==NULL){ // If $exam_group_id == NULL, list of exams will be displayed
		$this->renderPartial('examination/exams',array('employee_id'=>$employee->id,'batch_id'=>$batch_id));
	}
	else{ //If $exam_group_id != NULL, details of the selected exam will be displayed
		//echo '<br/>Exam Group ID: '.$_REQUEST['exam_group_id'].'<br/>';
			
			$checkgroup = Exams::model()->findByAttributes(array('exam_group_id'=>$exam_group_id));
			if($checkgroup!=NULL)
			{?>
			<div >
			<div >
			<?php $model=new Exams('search');
				  $model->unsetAttributes();  // clear any default values
				  if(isset($_GET['exam_group_id']))
					$model->exam_group_id=$exam_group_id
				 
				  ?>
				  <h3> Scheduled Subjects</h3>
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
                <div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;">
                    <div class="y_bx_head">
                         <?php echo Yii::t('app','Exam details not yet set!'); ?>
                    </div>      
       			</div>
                <?php
		}
	}
   ?>
 
</div>