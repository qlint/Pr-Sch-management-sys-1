<style>
.formConInner{width:auto;}
</style>

<?php
$this->breadcrumbs=array(
	Yii::t('app','Allotments')=>array('/hostel'),
	//$model->id,
);

?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <?php $this->renderPartial('/settings/hostel_left');?>
 </td>
    <td valign="top"> 
    <div class="cont_right">
    <h1><?php echo Yii::t('app','View Details');?></h1>
 <div class="formCon" >
<div class="formConInner">
<?php
$allot=Allotment::model()->findByAttributes(array('id'=>$_REQUEST['id']));
//var_dump($allot);
$floor=Floor::model()->findByAttributes(array('id'=>$allot->floor));
$student=Students::model()->findByAttributes(array('id'=>$allot->student_id));

$hostel=Hosteldetails::model()->findByAttributes(array('id'=>$floor->hostel_id));
?>
 <div class="pdtab_Con" style="padding-top:0px;">
                             <table width="100%" cellpadding="0" cellspacing="0" border="0" >
							<tr class="pdtab-h">
                                                            <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                                                            { ?>
								<td align="center"><?php echo Yii::t('app','Student Name');?></td>
                                                            <?php } ?>
								<td align="center"><?php echo Yii::t('app','Admission Date');?></td>
                                <td align="center"><?php echo Yii::t('app','Hostel');?></td>
                                 <td align="center"><?php echo Yii::t('app','Floor');?></td>
								<td align="center"><?php echo Yii::t('app','Room');?></td>
								<td align="center"><?php echo Yii::t('app','Bed');?></td>

							</tr>
                            <tr>
                                <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                            { ?>
                           <td align="center"><?php 
                            $name='';
                            $name=  $student->studentFullName('forStudentProfile');
                            echo $name;
                           //echo $student->first_name.' '.$student->last_name; ?></td>
                            <?php } ?>
                            <td align="center"><?php 
												$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
								if($settings!=NULL)
								{	
									$date1=date($settings->displaydate,strtotime($allot->created));
									
		
								}
											echo $date1;?></td>
                           <?php $hostel = HostelDetails::model()->findByAttributes(array('id'=>$floor->hostel_id)); 
						   		 $room = Room::model()->findByAttributes(array('id'=>$allot->room_no));
						
							?>
                           
                           <td align="center"><?php echo $hostel->hostel_name;?></td>
						                                                                                                                   
                           <td align="center"><?php echo $floor->floor_no;?></td>
                           <td align="center"><?php echo $room->room_no;?></td>
                            <td align="center"><?php echo $allot->bed_no;?></td>
                            </tr>
                            </table>
                            </div>
</div>
</div>
</div>
</td>
</tr>
</table>