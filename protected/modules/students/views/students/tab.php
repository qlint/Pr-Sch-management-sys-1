
<?php
if(isset($_REQUEST['student_id']) and $_REQUEST['student_id']!=NULL){
	$current_student = $_REQUEST['student_id'];
}
else{
	$current_student = $_REQUEST['id'];
}
$batchstudents = BatchStudents::model()->studentBatch($current_student);
/*if(count($batchstudents)>1){
	$batch = Batches::model()->findByAttributes(array('id'=>$batchstudents[0]['batch_id']));
}*/
if(count($batchstudents)==1){
	$batch_student = BatchStudents::model()->findByAttributes(array('student_id'=>$current_student,'result_status'=>0));
	$batch = Batches::model()->findByAttributes(array('id'=>$batch_student->batch_id));
}
?>

    <div class="button-bg">
    <div class="top-hed-btn-left"></div>
    <div class="top-hed-btn-right">
    <ul>                                    
    <li><?php echo CHtml::link('<span>'.Yii::t('app','Edit').'</span>', array('/students/students/update', 'id'=>$current_student, 'bid'=>$batch->id),array('class'=>'a_tag-btn')); ?> </li>
    <li><?php echo CHtml::link('<span>'.Yii::t('app','Students').'</span>', array('students/manage'),array('class'=>'a_tag-btn'));?> </li>                                
    </ul>
    </div> 
    
    
    </div>
<div class="clear"></div>	
<div class="pagetab-bg-tag-a">
    <ul>
    			
<?php             
			if(Yii::app()->controller->action->id=='view'){
				echo '<li class="active">'.CHtml::link(Yii::t('app','Profile'), array('/students/students/view', 'id'=>$current_student)).'</li>';
			}
			else{
				echo '<li>'.CHtml::link(Yii::t('app','Profile'), array('/students/students/view', 'id'=>$current_student)).'</li>';
			}
?>  
		
        

<?php     
			if(Yii::app()->controller->action->id=='courses'){
				echo '<li class="active">'.CHtml::link(Yii::t('app','Courses'), array('/students/students/courses', 'id'=>$current_student)).'</li>';
			}
			else{
				echo '<li>'.CHtml::link(Yii::t('app','Courses'), array('/students/students/courses', 'id'=>$current_student)).'</li>';
			}
?>

        

<?php     
			if(Yii::app()->controller->action->id=='assesments'){
				echo '<li class="active">'.CHtml::link(Yii::t('app','Assessments'), array('/students/students/assesments', 'id'=>$current_student));
			}
			else{
				echo '<li>'.CHtml::link(Yii::t('app','Assessments'), array('/students/students/assesments', 'id'=>$current_student)).'</li>';
			}
?>

        
 		
<?php
			$model = AttendanceSettings::model()->findByAttributes(array('config_key'=>'type'));
			if($model->config_value == 1){ 
				 if(Yii::app()->controller->action->id=='attentance' or Yii::app()->controller->id=='studentAttentance'){
					 if(Configurations::model()->studentAttendanceMode() == 2){
						echo '<li class="active">'.CHtml::link(Yii::t('app','Attendance'), array('/students/studentAttentance/subwiseattentance', 'id'=>$current_student),array('class'=>'active')).'</li>';
					 }
					 else{
						 echo '<li class="active">'. CHtml::link(Yii::t('app','Attendance'), array('/students/students/attentance', 'id'=>$current_student)).'</li>';
					 }
				}
				else{
					if(Configurations::model()->studentAttendanceMode() == 2){
						echo '<li>'.CHtml::link(Yii::t('app','Attendance'), array('/students/studentAttentance/subwiseattentance', 'id'=>$current_student)).'</li>';
					}
					else{
						echo '<li>'.CHtml::link(Yii::t('app','Attendance'), array('/students/students/attentance', 'id'=>$current_student)).'</li>';
					}
				}
			}
			else{
				if(Yii::app()->controller->id=='subjectAttendance'){
					echo  '<li class="active">'. CHtml::link(Yii::t('app','Attendance'), array('/attendance/subjectAttendance/individual', 'id'=>$current_student)).'</li>';
				}
				else{
					echo '<li>'.CHtml::link(Yii::t('app','Attendance'), array('/attendance/subjectAttendance/individual', 'id'=>$current_student)).'</li>';
				}
			}
?>

<?php     
			if(Yii::app()->controller->action->id=='document' or Yii::app()->controller->id=='studentDocument'){			
				echo '<li class="active">'. CHtml::link(Yii::t('app','Documents'), array('/students/students/document', 'id'=>$current_student)).'</li>';			
			}
			else{
				echo '<li>'.CHtml::link(Yii::t('app','Documents'), array('/students/students/document', 'id'=>$current_student)).'</li>';
			}
?>
		
<?php     
			if(Yii::app()->controller->action->id=='electives'){
				echo '<li class="active">'. CHtml::link(Yii::t('app','Electives'), array('/students/students/electives', 'id'=>$current_student)).'</li>';
			}
			else{
				echo '<li>'.CHtml::link(Yii::t('app','Electives'), array('/students/students/electives', 'id'=>$current_student)).'</li>';
			}
?>

        
<?php   
$model = Configurations::model()->findByAttributes(array('id'=>38));
	if($model->config_value == 1){ 
?>

<?php    
			if(Yii::app()->controller->action->id=='achievements' or (Yii::app()->controller->id=='achievements' and Yii::app()->controller->action->id=='update')){
				echo '<li class="active">'. CHtml::link(Yii::t('app','Achievements'), array('/students/students/achievements', 'id'=>$current_student)).'</li>';
			}
			else{
				echo '<li>'.CHtml::link(Yii::t('app','Achievements'), array('/students/students/achievements', 'id'=>$current_student)).'</li>';
			}
?>

<?php } ?>

 
<?php    
			if(Yii::app()->controller->action->id=='log'){
				echo '<li class="active">'.CHtml::link(Yii::t('app','Log'), array('/students/students/log', 'id'=>$current_student)).'</li>';
			}
			else{
				echo '<li>'.CHtml::link(Yii::t('app','Log'), array('/students/students/log', 'id'=>$current_student)).'</li>';
			}
?>
         
	</ul>
</div>