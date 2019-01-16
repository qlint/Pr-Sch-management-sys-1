<?php
$this->breadcrumbs=array(
	Yii::t('app','Hostel')=>array('/hostel'),
	Yii::t('app','Vacate'),
);

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <?php $this->renderPartial('/settings/hostel_left');?>
 </td>
    <td valign="top">
    <div style="padding-left:10px;"> 
    <h1><?php echo Yii::t('app','Vacate');?></h1>
    <div class="cont_right">    
     <?php
		$student=Students::model()->findByAttributes(array('id'=>$model->student_id));
		$room = Room::model()->findByAttributes(array('id'=>$model->room_no));
	?>
	     <div class="pdtab_Con">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" >
<tr class="pdtab-h" height="32px">
    <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                                        { ?>
                                        <th align="center"><?php echo Yii::t('app','Student');?></th><?php } ?>
<th align="center"><?php echo Yii::t('app','Room No');?></th>
<th align="center"><?php echo Yii::t('app','Vacate Date');?></th>
</tr>
<tr>
    <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
    { ?>
<td align="center"><?php 
    $name='';
    $name=  $student->studentFullName('forStudentProfile');
    echo $name;
    //echo ucfirst($student->first_name).' '.ucfirst($student->last_name); ?></td>
                                        <?php } ?>
<td align="center"><?php echo $room->room_no;?></td>
    <td align="center"><?php 
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
		if($settings!=NULL)
		{	
			$date1=date($settings->displaydate,strtotime($model->vacate_date));
			echo $date1;		
		}?>
    </td>
</tr>
</table>
</div>

</div>
</div>
</td>
</tr>
</table>
