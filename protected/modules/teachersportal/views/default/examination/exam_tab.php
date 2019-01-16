<div class="emp_tab_nav">
    <ul style="width:740px;">
    <li>
    <?php 
			  if($teach_count > 0){	
				  if(Yii::app()->controller->action->id=='allexam' or Yii::app()->controller->action->id=='examination' or $_REQUEST['allexam']==1)
				  {
				  echo CHtml::link(Yii::t('app','All Classes'), array('/teachersportal/default/allexam'),array('class'=>'active'));
				  }
				  else
				  {
				  echo CHtml::link(Yii::t('app','All Classes'), array('/teachersportal/default/allexam'));
				  }
			  }
    ?>
    </li>
    <li>
    <?php     
			  if($class_count > 0){
				  if(Yii::app()->controller->action->id=='classexam' or $teach_count <= 0 or (Yii::app()->controller->action->id=='update' and $_REQUEST['allexam']!=1))
				  {
				  echo CHtml::link(Yii::t('app','My Class'), array('/teachersportal/default/classexam'),array('class'=>'active'));
				  }
				  else
				  {
				  echo CHtml::link(Yii::t('app','My Class'), array('/teachersportal/default/classexam'));
				  }
			  }
    ?>
    
    </li>
    </ul>
</div>
