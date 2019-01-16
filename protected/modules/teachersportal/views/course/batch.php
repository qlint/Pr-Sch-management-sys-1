       
<ul class="nav nav-tabs nav-dark">
<!--data-toggle="tab"-->
        <?php     
    if(Yii::app()->controller->action->id=='trainees')
    {
		
		
        echo '<li class="active">'.CHtml::link(Yii::t('app','Students'), array('trainees','id'=>$_REQUEST['id']),array('class'=>'active')).'</li>';
    }
    else
    {
        echo '<li>'.CHtml::link(Yii::t('app','Students'), array('trainees','id'=>$_REQUEST['id'])).'</li>';
    }
    ?>
    
        
    
        <?php     
    if(Yii::app()->controller->action->id=='subjects')
    {
        echo '<li class="active">'.CHtml::link(Yii::t('app','Subjects'), array('subjects','id'=>$_REQUEST['id']),array('class'=>'active')).'</li>';
    }
    else
    {
        echo '<li>'.CHtml::link(Yii::t('app','Subjects'), array('subjects','id'=>$_REQUEST['id'])).'</li>';
    }
    ?>
         
    <?php     
    /*if(Yii::app()->controller->action->id=='timetables'||Yii::app()->controller->action->id=='studenttimetables')
    {
        echo '<li class="active">'.CHtml::link(Yii::t('Batch','Timetable'), array('timetables','id'=>$_REQUEST['id']),array('class'=>'active')).'</li>';
    }
    else
    {
        echo '<li>'.CHtml::link(Yii::t('Batch','Timetable'), array('timetables','id'=>$_REQUEST['id'])).'</li>';
    }*/
    ?>
    
    
    
    
    
       
<!-- TAB -->


    
   
  
    <?php     
    if(Yii::app()->controller->action->id=='exams'||Yii::app()->controller->action->id=='create'||Yii::app()->controller->action->id=='update')
    {
        echo '<li class="active">'.CHtml::link(Yii::t('app','Assessments'), array('exams','id'=>$_REQUEST['id']),array('class'=>'active')).'</li>';
    }
    else
    {
        echo '<li>'.CHtml::link(Yii::t('app','Assessments'), array('exams','id'=>$_REQUEST['id'])).'</li>';
    }
    ?>
    
    <?php 
	$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$is_teacher = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id'],'employee_id'=>$employee->id));   
	if($is_teacher!=NULL and $is_teacher->exam_format==1)
	{ 
		if(Yii::app()->controller->action->id=='elective' || Yii::app()->controller->action->id=='electiveView')
		{
			echo '<li class="active">'.CHtml::link(Yii::t('app','Elective'), array('/teachersportal/course/electiveView','id'=>$_REQUEST['id']),array('class'=>'active')).'</li>';
		}
		else
		{
			echo '<li>'.CHtml::link(Yii::t('app','Elective'), array('/teachersportal/course/electiveView','id'=>$_REQUEST['id'])).'</li>';
		}
	}
    ?>
    <?php     
    if(Yii::app()->controller->action->id=='log' || Yii::app()->controller->action->id=='studentlog' || Yii::app()->controller->action->id=='logdetails')
    {
        echo '<li class="active">'.CHtml::link(Yii::t('app','Log'), array('studentlog','id'=>$_REQUEST['id']),array('class'=>'active')).'</li>';
    }
    else
    {
        echo '<li>'.CHtml::link(Yii::t('app','Log'), array('studentlog','id'=>$_REQUEST['id'])).'</li>';
    }
    ?>
   
   
    
    
    </ul>




<!-- END TAB -->
            
            
            
            
            
            
          