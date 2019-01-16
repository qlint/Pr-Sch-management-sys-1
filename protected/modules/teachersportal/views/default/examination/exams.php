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
                   <strong> <?php echo Yii::t('examination','Course'); ?>:</strong>
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
                    <strong> <?php echo Yii::t('app','Exam'); ?>: </strong><?php echo $exam->name; 
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
				$scheduleUrlExp = 'Yii::app()->createUrl("/teachersportal/default/allexam",array("bid"=>$data->batch_id,"exam_group_id"=>$data->id))';
				$resultUrlExp = 'Yii::app()->createUrl("/teachersportal/default/allexam",array("bid"=>$data->batch_id,"exam_group_id"=>$data->id,"r_flag"=>1))';
			}
			else{
				$url = '/teachersportal/default/classexam';
				$scheduleUrlExp = 'Yii::app()->createUrl("/teachersportal/default/classexam",array("bid"=>$data->batch_id,"exam_group_id"=>$data->id))';
				$resultUrlExp = 'Yii::app()->createUrl("/teachersportal/default/classexam",array("bid"=>$data->batch_id,"exam_group_id"=>$data->id,"r_flag"=>1))';
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
		$criteria=new CDbCriteria(array('condition'=>'batch_id='.$batch_id.' AND is_published = 1'));
		$dataProvider=new CActiveDataProvider('ExamGroups', array('criteria'=>$criteria));
		$this->widget('zii.widgets.grid.CGridView', array(
			 'id' => 'exam-groups-grid',
			 'dataProvider' => $dataProvider,
			 'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
			 'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
			 
			 'htmlOptions'=>array('class'=>'grid-view clear'),
			  'columns' => array(	
			
			'name',
			
			'exam_type',
			/*array(
				'name'=>'is_published',
				'value'=>'$data->is_published ? "Yes" : "No"'
			),*/
			
			array(
			'class'=>'CLinkColumn',
			'labelExpression'=>'$data->is_published ? Yii::t("app", "View Schedule") : Yii::t("app", "Not scheduled")',
			'urlExpression'=>'$data->is_published ? '.$scheduleUrlExp.' : "#"',
			'header'=>'Is Published',
			'headerHtmlOptions'=>array('style'=>'color:#FF6600')
			),
			array(
			'class'=>'CLinkColumn',
			'labelExpression'=>'$data->result_published ? Yii::t("app", "View Results") : ($data->is_published ? Yii::t("app", "Enter Scores") : Yii::t("app","No Results Published"))',
			'urlExpression'=>'$data->result_published ? '.$resultUrlExp.' : ($data->is_published ? '.$resultUrlExp.' : "#")',
			'header'=>Yii::t("app", 'Result Published'),
			'headerHtmlOptions'=>array('style'=>'color:#FF6600')
			),
			
		),
			   'afterAjaxUpdate'=>'js:function(id,data){$.bind_crud()}'
	   ));
	}
	else{ //If $exam_group_id != NULL, details of the selected exam will be displayed
		//echo '<br/>Exam Group ID: '.$exam_group_id.'<br/>';
			
			$checkgroup = Exams::model()->findByAttributes(array('exam_group_id'=>$_REQUEST['exam_group_id']));
			if($checkgroup!=NULL)
			{?>
			<div >
			<div >
			<?php $model1=new Exams('search');
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
			echo '<div class="notifications nt_red"><i>'.Yii::t('app', 'Nothing Scheduled').'</i></div>'; 
			}
	}
   ?>
 
</div>