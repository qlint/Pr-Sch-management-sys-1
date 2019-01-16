

<?php $batch=Batches::model()->findByAttributes(array('id'=>$_REQUEST['id'])); ?>


           <?php if($batch!=NULL)
		   {
			   ?>
               
               <div class="formCon" >
               <div class="formConInner">
              
               <strong><?php echo Yii::t('app','Course:');?></strong>
        <?php $course=Courses::model()->findByAttributes(array('id'=>$batch->course_id));
		if($course!=NULL)
		   {
			   echo $course->course_name; 
		   }?>
          &nbsp;&nbsp;&nbsp;&nbsp; <strong><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.':'?> </strong><?php echo $batch->name; ?>
          
          <?php if((isset($_REQUEST['id']) and $_REQUEST['id']!=NULL) and ((Yii::app()->controller->id=='weekdays' and (Yii::app()->controller->action->id=='index' or Yii::app()->controller->action->id=='timetable')) or (Yii::app()->controller->id=='classTiming' and Yii::app()->controller->action->id=='index') or Yii::app()->controller->id=='flexible' and Yii::app()->controller->action->id=='timetable'))
			{
				?>
            
           
            <?php
			
			$rurl = explode('index.php?r=',Yii::app()->request->getUrl());
			$rurl = explode('&id=',$rurl[1]);
			echo CHtml::ajaxLink(Yii::t('app','Change').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),array('/site/explorer','widget'=>'2','rurl'=>$rurl[0]),array('update'=>'#explorer_handler'),array('id'=>'explorer_change','class'=>'sb_but','style'=>'right:80px;')); ?>
            
            
<?php 
			} 
			?>
            
            <?php echo CHtml::link('<span>'.Yii::t('app','close').'</span>',array('/timetable'),array('class'=>'sb_but_close','style'=>'right:40px;'));?>
          
          <br>
               </div> 
               </div>
               
               
               
    <?php 
		   }?>