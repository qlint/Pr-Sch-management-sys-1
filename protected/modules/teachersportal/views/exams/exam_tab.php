
<div class="opnsl_actn_box1">
    <?php 
			  if($teach_count > 0){	
				  if(Yii::app()->controller->action->id=='allexam' or Yii::app()->controller->action->id=='index' or $_REQUEST['allexam']==1)
				  {
				  echo CHtml::link('<span>'.Yii::t('app','Tutor Classes').'</span>',array('/teachersportal/exams/allexam'),array('class'=>'addbttn last active'));
				  }
				  else
				  {
				  echo CHtml::link('<span>'.Yii::t('app','Tutor Classes').'</span>',array('/teachersportal/exams/allexam'),array('class'=>'addbttn last'));
				  }
			  }
    ?>
</div>
<div class="opnsl_actn_box1">
    <?php     
			  if($class_count > 0){
				  if(Yii::app()->controller->action->id=='classexam' or $teach_count <= 0 or (Yii::app()->controller->action->id=='update' and $_REQUEST['allexam']!=1))
				  {
				 	echo CHtml::link('<span>'.Yii::t('app','My Class').'</span>',array('/teachersportal/exams/classexam'),array('class'=>'addbttn last active'));
				  }
				  else
				  {
				    echo CHtml::link('<span>'.Yii::t('app','My Class').'</span>',array('/teachersportal/exams/classexam'),array('class'=>'addbttn last'));
				  }
			  }
    ?>
 </div>   
    

    
