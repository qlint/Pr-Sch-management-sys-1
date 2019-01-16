 
 	
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
                <li>
 <?php  echo CHtml::ajaxLink('<span>'.Yii::t('app','Student Attendance').'</span>',array('/site/explorer','widget'=>'s_a','rurl'=>'attendance/studentAttentance'),array('update'=>'#explorer_handler'),array('id'=>'explorer_change','class'=>'a_tag-btn','style'=>'')); ?>
  </li>
  <li>
    <div class="attendance-teacredit-bg">
  <?php if(Yii::app()->controller->id=='employeeLeaveTypes')
  { 
      echo CHtml::link('<span>'.Yii::t('app','Teacher Attendance').'</span>',array('/attendance/employeeAttendances'),array('class'=>'a_tag-btn','style'=>''));
  }
  else
  {
	  //echo CHtml::link('<span>'.Yii::t('app','Teacher Leave Types').'</span>',array('/attendance/employeeLeaveTypes'),array('class'=>'sb_but-atndnce','style'=>''));
  }
  ?>

    
    
            
            <?php echo CHtml::link('<span>'.Yii::t('app','close').'</span>',array('/attendance'),array('class'=>'sb_but_close-atndnce','style'=>''));?>
 </div></li>                                   
</ul>
</div> 

</div>