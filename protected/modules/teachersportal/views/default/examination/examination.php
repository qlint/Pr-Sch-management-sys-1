<?php Yii::app()->clientScript->registerCoreScript('jquery');?>

<div id="parent_Sect">
	<?php $this->renderPartial('leftside');?> 
	<div class="right_col"  id="req_res123">
    <!--contentArea starts Here--> 
     <div id="parent_rightSect">
        <div class="parentright_innercon">
            <h1> <?php echo Yii::t('app','View Examination Details'); ?></h1>
            <?php //$this->renderPartial('/default/employee_tab');?>
        	<div>
			<?php 
            $employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
			// Get unique batch ID from Timetable. Checking if the employee is teaching.
			$criteria=new CDbCriteria;
			$criteria->select= 'batch_id';
			$criteria->distinct = true;
			$criteria->condition='employee_id=:emp_id';
			$criteria->params=array(':emp_id'=>$employee->id);
			$batches_id = TimetableEntries::model()->findAll($criteria);
			$teach_count = count($batches_id);
			//echo 'Employee ID: '.$employee->id.'<br/>Teaching in '.count($batches_id).' batch(es)<br/>';
			
			//Get unique batch ID from Batches. Checking if the employee is a class teacher.
			$criteria=new CDbCriteria;
			$criteria->select= 'id';
			$criteria->distinct = true;
			$criteria->condition='employee_id=:emp_id';
			$criteria->params=array(':emp_id'=>$employee->id);
			$class_teacher = Batches::model()->findAll($criteria);
			$class_count = count($class_teacher);
			//echo 'Class Teacher of '.count($class_teacher).' batch(es)';
			if($teach_count > 0 or $class_count > 0){
				 $this->renderPartial('examination/exam_tab',array('teach_count'=>$teach_count,'class_count'=>$class_count));
			}
			else{
				?>
                <div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;">
                    <div class="y_bx_head">
                         <?php echo Yii::t('app','No exam details are available now!'); ?>
                    </div>      
       			</div>
                <?php
			}
			
			// Displaying Default page
			if(Yii::app()->controller->action->id=='examination' and ($teach_count > 0 or $class_count > 0)){ 
				if($teach_count > 0){ // If employee is teaching in any batch, display all batches as list
					$this->renderPartial('examination/allexam',array('employee_id'=>$employee->id));
				}
				elseif($teach_count <= 0 and $class_count > 0){ // If employee is not teaching in any batch, but is a classteacher, display batches in charge.
					$this->renderPartial('examination/classexam',array('employee_id'=>$employee->id));
				}
			}
			
			// Displaying all batches when 'All Class' tab is clicked 
			elseif(Yii::app()->controller->action->id=='allexam' and $_REQUEST['bid'] == NULL){ // Displaying All Batches
				$this->renderPartial('examination/allexam',array('employee_id'=>$employee->id));
			}
			
			// Exam Details
			elseif((Yii::app()->controller->action->id=='allexam' or Yii::app()->controller->action->id=='classexam') and $_REQUEST['bid']!= NULL){ 
				if($_REQUEST['exam_group_id']== NULL){ // Displaying exam lists of a batch				
					$this->renderPartial('examination/exams',array('employee_id'=>$employee->id,'batch_id'=>$_REQUEST['bid'])); 
				}
				else{ // Displaying individual exam details of a batch
					if($_REQUEST['r_flag'] == NULL){ // Displaying Schedule
						$this->renderPartial('examination/schedule',array('employee_id'=>$employee->id,'batch_id'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id']));
					}
					elseif($_REQUEST['r_flag'] == 1){ // Displaying Results
						if($_REQUEST['exam_id'] == NULL){
							
							$this->renderPartial('examination/result',array('employee_id'=>$employee->id,'batch_id'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id']));
						}
						else{
							
							$this->renderPartial('examination/examScores/scores',array('employee_id'=>$employee->id,'batch_id'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id'],'exam_id'=>$_REQUEST['exam_id']));
						}
					}
				}
			}

			// Displaying batches in charge when 'My Class' tab is clicked 
			elseif(Yii::app()->controller->action->id=='classexam' and $_REQUEST['r_flag'] == NULL and $_REQUEST['bid'] == NULL){ // Displaying Class Batches
				$this->renderPartial('examination/classexam',array('employee_id'=>$employee->id));
			}
			
			// Displaying score update page
			if(Yii::app()->controller->action->id=='update'){
				$this->renderPartial('examination/examScores/update',array('employee_id'=>$employee->id,'batch_id'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id'],'exam_id'=>$_REQUEST['exam_id']));
			}
			
        	?>
			</div>
		</div>
	</div>
	 <div class="clear"></div>
</div>
