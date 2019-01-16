
<?php if(isset($_REQUEST['id']))
{?>

 
 
 <?php    $batch=Batches::model()->findByAttributes(array('id'=>$_REQUEST['id'])); 
          if($batch!=NULL)
		   {
			   $course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
		       if($course!=NULL)
			   {
				   $coursename = ucfirst($course->course_name); 
				   $batchname = ucfirst($batch->name);
			   }
			   else
			   {
				   $coursename = ''; 
				   $batchname = '';
			   }
           }?>
  <h1><?php echo Yii::t('app','Manage Attendance'); ?></h1>         


<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li>
		<?php echo CHtml::link('<span>'.Yii::t('app','Teacher Attendance').'</span>',array('/attendance/employeeAttendances'),array('class'=>'a_tag-btn'));?>
        </li>
       <li>
			<?php if((Yii::app()->controller->id=='studentAttentance' and (Yii::app()->controller->action->id=='index' or Yii::app()->controller->action->id=='daily' or Yii::app()->controller->action->id=='monthlyAttendance'))){
				echo CHtml::ajaxLink('<span>'.Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>',array('/site/explorer','widget'=>'2','rurl'=>'attendance/studentAttentance'),array('update'=>'#explorer_handler'),array('id'=>'explorer_change','class'=>'a_tag-btn')); 	
				} 
				if(Yii::app()->controller->action->id=='batchwise'){
				echo CHtml::ajaxLink('<span>'.Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>',array('/site/explorer','widget'=>'2','rurl'=>'/attendance/subjectAttendance/batchwise'),array('update'=>'#explorer_handler'),array('id'=>'explorer_change','class'=>'a_tag-btn'));
           		 }
				 
				if(Yii::app()->controller->id=='studentSubjectAttendance' and Yii::app()->controller->action->id=='index'){
					echo CHtml::ajaxLink('<span>'.Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>',array('/site/explorer','widget'=>'2','rurl'=>'/attendance/studentSubjectAttendance/index'),array('update'=>'#explorer_handler'),array('id'=>'explorer_change','class'=>'a_tag-btn'));
				}
				
				if(Yii::app()->controller->id=='studentSubjectAttendance' and Yii::app()->controller->action->id=='daily'){
					echo CHtml::ajaxLink('<span>'.Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>',array('/site/explorer','widget'=>'2','rurl'=>'/attendance/studentSubjectAttendance/daily'),array('update'=>'#explorer_handler'),array('id'=>'explorer_change','class'=>'a_tag-btn'));
				}
            ?>
        </li>
        <li><?php echo CHtml::link('<span>'.Yii::t('app','close').'</span>',array('/attendance'),array('class'=>'sb_but_close-atndnce'));?></li>                                    
</ul>
</div> 

</div>

      

	<div class="c_batch_tbar">
      <div class="edit_bttns">
            	<ul>
                <li>
                   
                   </li>
                 </ul>
    		</div>  
    
   
    	<div class="cb_left">
        	<ul>
            	<li><strong>
				<?php
				  $sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id);
			if($sem_enabled==1 and $batch->semester_id!=NULL){ 
					$semester=Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
					echo Yii::app()->getModule("students")->labelCourseBatch().' / '.Yii::t('app','Semester').' : '; ?></strong> <?php echo $coursename; ?> / <?php echo $batchname; ?> / <?php echo ucfirst($semester->name);  
				}
				else{
					echo Yii::app()->getModule("students")->labelCourseBatch().': '; ?></strong> <?php echo $coursename; ?> / <?php echo $batchname; 
				}
				?>
				</li>
                <li><strong><?php echo Yii::t('app','Class Teacher : '); ?></strong> <?php $employee=Employees::model()->findByAttributes(array('id'=>$batch->employee_id));
		    if($employee!=NULL)
		    {
			   echo Employees::model()->getTeachername($employee->id); 
		    }?></li>
            </ul>
        </div>
        <div class="cb_right" style="width:290px">
        	<div class="status_bx" style="width:290px">
    			<ul>
             		 <?php   ?>
        			<li><span><?php $students=Yii::app()->getModule('students')->studentsOfBatch($_REQUEST['id']); echo count($students); ?></span><?php echo Yii::t('app','Student(s)');?></li>
            		<li><span><?php echo count(Subjects::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id']))); ?></span><?php echo Yii::t('app','Subject(s)');?></li>
            		<li><span><?php echo count(TimetableEntries::model()->findAll(array('condition'=>'batch_id=:x', 'group'=>'employee_id','params'=>array(':x'=>$_REQUEST['id'])))); ?></span><?php echo Yii::t('app','Teacher(s)');?></li>
        		</ul>
     		<div class="clear"></div>
   			</div>
            
        </div>
        <div class="clear"></div>
    	
    </div>
   
    
<?php }?>
		   