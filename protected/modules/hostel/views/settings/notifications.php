<?php
$this->breadcrumbs=array(
        Yii::t('app','Hostel')=>array('/hostel'),
	Yii::t('app','Notifications')=>array('/hostel/settings/notifications'),
	
);

?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <?php $this->renderPartial('/settings/hostel_left');?>
 </td>
    <td valign="top"> 
    <div class="cont_right">
      <div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;">
                    <div class="y_bx_head" style="width:90%">
    <h1><?php echo Yii::t('app','Notifications');?></h1>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
	<?php
			
			$request=Roomrequest::model()->findAll('status=:x order by created_at DESC',array(':x'=>'C'));
			
			if($request!=NULL)
			{
				$i=1;
				foreach($request as $request_1)
				{
					echo '<tr>';
					$student=Students::model()->findByAttributes(array('id'=>$request_1->student_id));
					$reg= Registration::model()->findByAttributes(array('student_id'=>$request_1->student_id));
					$allot=Allotment::model()->findByAttributes(array('id'=>$request_1->allot_id));
					//echo '<td align="left">'.$i.')&nbsp;';
					if($request_1->allot_id!=NULL and $allot!=NULL)
					{						
						
						$room = Room::model()->findByAttributes(array('id'=>$allot->room_no));
						$floor = Floor::model()->findByAttributes(array('id'=>$room->floor));
						if($student!=NULL)
						{
                                                        $name='-';
							if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                                                        {                                                            
                                                            $name=  $student->studentFullName('forStudentProfile');
                                                        }
							//echo '<strong>'.ucfirst($student->last_name.' '.$student->first_name);
						echo'<div><td align="left">'.$i.')&nbsp;'.$name.'</div><div>';
						echo Yii::t('app','has requested for changing room to').' '.$room->room_no;
						echo '&nbsp;&nbsp;';						
						echo CHtml::link(Yii::t('app','Allot'),array('/hostel/allotment/create','studentid'=>$request_1->student_id,'allotid'=>$request_1->allot_id,'floor_id'=>$floor->id));
						echo '</div><br></td>';
						
						}
					}
					else if($request_1->allot_id==NULL)
					{
						
						if($student!=NULL)
						{
						echo '&nbsp;<strong>'.ucfirst($student->first_name.' '.$student->last_name).'&nbsp;'.Yii::t('app','has been applied for hostel facility').'</strong>&nbsp;'.CHtml::link(Yii::t('app','Register'),array('/hostel/registration/update','studentid'=>$request_1->student_id,'id'=>$reg->id)) ;
						
						echo '&nbsp&nbsp'.CHtml::link(Yii::t('app','Reject'),array('/hostel/registration/reject','studentid'=>$request_1->student_id,'id'=>$reg->id),array('style'=>'color:red')) ;
						
						}
						
					}
				$i++;
			}
			echo '</tr>';
			}
			else
			{
				echo '<strong>'.Yii::t('app','No notifications').'</strong>';
			}
	
		?>
      
		<?php
	

?>
</table>
</div>
</div>
</div>
</td>
</tr>
</table>