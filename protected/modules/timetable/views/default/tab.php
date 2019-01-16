
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>
<li><?php
			
			$rurl = explode('index.php?r=',Yii::app()->request->getUrl());
			$rurl = explode('&id=',$rurl[1]);
			echo CHtml::ajaxLink(Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),array('/site/explorer','widget'=>'2','rurl'=>$rurl[0]),array('update'=>'#explorer_handler'),array('id'=>'explorer_change','class'=>'a_tag-btn','style'=>'right:80px;')); ?></li>                                    
<li><?php echo CHtml::link('<span>'.Yii::t('app','close').'</span>',array('/timetable'),array('class'=>'sb_but_close-atndnce','style'=>'right:40px;'));?></li>
                                    
</ul>
</div> 

</div>



<?php 

$batch=Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
$course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
$semester_enabled	= Configurations::model()->isSemesterEnabled(); 
$sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course->id); ?>


           <?php if($batch!=NULL)
		   {
			   ?>
               
<div class="view_sent_mail">
              
               <strong><?php echo Yii::t('app','Course:');?></strong>
        <?php 
		if($course!=NULL)
		   {
			   echo $course->course_name; 
		   }?>
          &nbsp;&nbsp;&nbsp;&nbsp; <strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.':'?> </strong><?php echo $batch->name; ?>
          
          <?php if((isset($_REQUEST['id']) and $_REQUEST['id']!=NULL) and ((Yii::app()->controller->id=='weekdays' and (Yii::app()->controller->action->id=='index' or Yii::app()->controller->action->id=='timetable')) or (Yii::app()->controller->id=='classTiming' and Yii::app()->controller->action->id=='index') or Yii::app()->controller->id=='flexible' and Yii::app()->controller->action->id=='timetable'))
			{
			} 
			?>
			<?php  if($semester_enabled == 1 and $sem_enabled == 1 and $batch->semester_id != NULL){
						$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));?>
						&nbsp;&nbsp;&nbsp;&nbsp; <strong><?php echo Yii::t('app','Semester').' '.':'?> </strong><?php echo ucfirst($semester->name);
			}?>
            
               </div> 
    <?php 
		   }?>