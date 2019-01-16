<style type="text/css">
a{ margin:0 2px;}
</style>

<div class="attendance-ul-block">
<ul>

  			<?php if(Yii::app()->controller->action->id=='attendance' or Yii::app()->controller->action->id=='studentattendance')
  			{
				if(Configurations::model()->teacherAttendanceMode() != 1){
					echo "<li>".CHtml::link('<span>'.Yii::t("app",'My Subject Wise Attendance').'</span>',array('/teachersportal/default/teachersubwise'),array('class'=>'btn btn-primary pull-right'))."</li>"; 
				}
				
				if(Configurations::model()->teacherAttendanceMode() != 2){ 
     				echo "<li>".CHtml::link('<span>'.Yii::t("app",'View My Attendance').'</span>',array('/teachersportal/default/employeeattendance'),array('class'=>'btn btn-primary pull-right'))."</li>"; 
				}
				/*if(Configurations::model()->studentAttendanceMode() != 1){
					echo "<li>".CHtml::link('<span>'.Yii::t("app",'Subject Wise Attendance').'</span>',array('/teachersportal/default/daily'),array('class'=>'btn btn-primary pull-right'))."</li>";
					
					
				 }*/		
			}?>
 			<?php  
			
				if(Yii::app()->controller->action->id=='attendance' or Yii::app()->controller->action->id=='employeeattendance' or Yii::app()->controller->action->id=='tpAttendance' or Yii::app()->controller->action->id=='tpBatches' or Yii::app()->controller->action->id=='day' or Yii::app()->controller->action->id=='StudentDayAttendance'or Yii::app()->controller->action->id=='studentdayattendance')
				{
					if(Configurations::model()->teacherAttendanceMode() != 1 and Yii::app()->controller->action->id != 'attendance'){
						echo "<li>".CHtml::link('<span>'.Yii::t("app",'My Subject Wise Attendance').'</span>',array('/teachersportal/default/teachersubwise'),array('class'=>'btn btn-primary pull-right'))."</li>"; 
					}
					if(Configurations::model()->studentAttendanceMode() == 2){
						$link = "<li>".CHtml::link('<span>'.Yii::t("app",'Manage Student Attendance').'</span>', array('/teachersportal/default/daily'),array('class'=>'btn btn-primary pull-right'))."</li>";
					}
					else{
						$link = "<li>".CHtml::link('<span>'.Yii::t("app",'Manage Student Attendance').'</span>', array('/teachersportal/default/studentattendance'),array('class'=>'btn btn-primary pull-right'))."</li>";
					}
					
						echo $link;
				}
			?>
        	<?php if(count($is_classteacher)>1 and Yii::app()->controller->action->id=='studentattendance' and isset($_REQUEST['id'])){
						echo "<li>".CHtml::link('<span>'.Yii::t("app",'Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>',array('/teachersportal/default/studentattendance'),array('class'=>'btn btn-primary pull-right'))."</li>";	
			}?>
       
       
       
       
  			<?php 
			if(Yii::app()->controller->action->id=='timetable' or Yii::app()->controller->action->id=='studenttimetable' or strtolower(Yii::app()->controller->action->id)=='employeetimetable' or Yii::app()->controller->action->id=='employeeFlexibleTimetable' or strtolower(Yii::app()->controller->action->id)=='employeeclasstimetable' or Yii::app()->controller->action->id=='employeeFlexibleclassTimetable')
  			{ 
     			echo "<li>".CHtml::link('<span>'.Yii::t("app",'View Day Timetable').'</span>',array('/teachersportal/default/daytimetable'),array('class'=>'btn btn-primary pull-right'))."</li>"; 
			}?>
		
        
        	
        
        
  			<?php 
			if(Yii::app()->controller->action->id=='daytimetable' or Yii::app()->controller->action->id=='timetable' or Yii::app()->controller->action->id=='studenttimetable'or Yii::app()->controller->action->id=='day' or strtolower(Yii::app()->controller->action->id)=='employeeclasstimetable' or Yii::app()->controller->action->id=='employeeFlexibleclassTimetable')
  			{ 
     			echo "<li>".CHtml::link('<span>'.Yii::t("app",'View My Timetable').'</span>',array('/teachersportal/default/employeetimetable'),array('class'=>'btn btn-primary pull-right'))."</li>"; 
			}?>
            <?php 
			if(Yii::app()->controller->action->id=='timetable' or Yii::app()->controller->action->id=='daytimetable' or strtolower(Yii::app()->controller->action->id)=='employeetimetable' or Yii::app()->controller->action->id=='employeeFlexibleTimetable' )
  			{ 
     			echo "<li>".CHtml::link('<span>'.Yii::t("app",'View My Class Timetable').'</span>',array('/teachersportal/default/employeeClassTimetable'),array('class'=>'btn btn-primary pull-right'))."</li>"; 
			}?>
		
			<?php 
              if( Yii::app()->controller->action->id=='day' or Yii::app()->controller->action->id=='studentattendance'){
                echo "<li>".CHtml::link('<span>'.Yii::t("app",'View Monthly Attendance').'</span>',array('/teachersportal/default/studentDayAttendance'),array('class'=>'btn btn-primary pull-right'))."</li>";   
              }?>
        
        
        
        	<?php if($is_classteacher!=NULL){
					if(Yii::app()->controller->action->id=='daytimetable' or Yii::app()->controller->action->id=='timetable' or Yii::app()->controller->action->id=='employeetimetable' )
					{ 
						echo "<li>".CHtml::link('<span>'.Yii::t("app",'View Class Timetable').'</span>',array('/teachersportal/default/studenttimetable'),array('class'=>'btn btn-primary pull-right'))."</li>"; 
					}
                } ?>
        
        
        
        
        
        	<?php if(count($is_classteacher)>1 and Yii::app()->controller->action->id=='studenttimetable' and isset($_REQUEST['id'])){
						echo "<li>".CHtml::link(Yii::t("app",'Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),array('/teachersportal/default/employeetimetable'),array('class'=>'btn btn-primary pull-right'))."</li>";	
			}?>
        
        
        
        
        	<?php
				$criteria=new CDbCriteria;
				$criteria->select= 'batch_id';
				$criteria->distinct = true;
				// $criteria->order = 'batch_id ASC'; Uncomment if ID should be retrieved in ascending order
				$criteria->condition='employee_id=:emp_id';
				$criteria->params=array(':emp_id'=>$employee->id);
				$batches_id = TimetableEntries::model()->findAll($criteria); 
				if(count($batches_id) > 1){
					if(Yii::app()->controller->action->id=='employeetimetable' and isset($_REQUEST['id'])){
							echo "<li>".CHtml::link(Yii::t("app",'Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),array('/teachersportal/default/employeetimetable'),array('class'=>'btn btn-primary pull-right'))."</li>";	
					}
				}?>
        
        
        
        
        <?php /*?><li>
        	<?php if(Yii::app()->controller->action->id=='examination')
  			{ 
     			echo CHtml::link('<span>View Exam Timetable</span>',array('/teachersportal/default/exams')); 
			}?>
        </li>
        <li>
        	<?php if(Yii::app()->controller->action->id=='examination')
  			{ 
     			echo CHtml::link('<span>View Exam Results</span>',array('/teachersportal/default/exams')); 
			}?>
        </li><?php */?>
</ul>

</div>

 
