<?php
$this->breadcrumbs=array(
	Yii::t('app','Hostel')=>array('/hostel'),
	Yii::t('app','Mess Manages')=>array('/hostel/MessManage/messinfo'),
	Yii::t('app','Mess Details'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/settings/hostel_left');?>
        </td>
        <td valign="top"> 
            <div class="cont_right">
            <h1><?php echo Yii::t('app','Student Details');?></h1>
            <?php
			$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
			if(Yii::app()->user->year)
			{
				$year = Yii::app()->user->year;
			}
			else
			{
				$year = $current_academic_yr->config_value;
			}
			$is_insert = PreviousYearSettings::model()->findByAttributes(array('id'=>2));
			if($year != $current_academic_yr->config_value and $is_insert->settings_value==0)
			{
			?>
			<div>
				<div class="yellow_bx" style="background-image:none;width:95%;padding-bottom:45px;">
					<div class="y_bx_head" style="width:95%;">
					<?php 
						echo Yii::t('app','You are not viewing the current active year. ');
						echo Yii::t('app','To collect mess fees, enable the Insert option in Previous Academic Year Settings.');	
					?>
					</div>
					<div class="y_bx_list" style="width:95%;">
						<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
					</div>
				</div>
			</div> <br />
			<?php
			}
			?>
            <?php
            $allot=Allotment::model()->findByAttributes(array('student_id'=>$studentid,'status'=>'S'));
            if($allot!=NULL)
            {
				$stud=Students::model()->findByAttributes(array('id'=>$allot->student_id));
				$register=Registration::model()->findByAttributes(array('student_id'=>$allot->student_id));
				$food=FoodInfo::model()->findByAttributes(array('id'=>$register->food_preference));
				$mess=MessFee::model()->findByAttributes(array('student_id'=>$allot->student_id));
				$floor=Floor::model()->findByAttributes(array('id'=>$allot->floor));
				$hostel=Hosteldetails::model()->findByAttributes(array('id'=>$floor->hostel_id));
				$room = Room::model()->findByAttributes(array('floor'=>$floor->id));
				
				?>
				<div class="pdtab_Con" style="padding:0px;">
				<table width="100%" cellpadding="0" cellspacing="0" border="0" >
				<tr class="pdtab-h">
                                     <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                                    { ?>
					<td align="center"><?php echo Yii::t('app','Student Name');?></td>
                                    <?php } ?>
					<td align="center"><?php echo Yii::t('app','Hostel');?></td>
					<td align="center"><?php echo Yii::t('app','Room No');?></td>
					<td align="center"><?php echo Yii::t('app','Bed No');?></td>
					<td align="center"><?php echo Yii::t('app','Food Preference');?></td>
					<td align="center"><?php echo Yii::t('app','Amount');?></td>
					<td align="center"><?php echo Yii::t('app','Action');?></td>
                    
				</tr>
				<tr>
                                     <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                                        { ?>
					<td align="center"><?php 
                                        $name='';
                                        $name=  $stud->studentFullName('forStudentProfile');
                                        echo $name;
                                        
                                        //echo $stud->last_name.' '. $stud->first_name;?></td>
                                        <?php } ?>
					<td align="center"><?php echo $hostel->hostel_name;?></td>
					<td align="center"><?php echo $room->room_no;?></td>
					<td align="center"><?php echo $allot->bed_no;?></td>
					<td align="center"><?php echo $food->food_preference;?></td>
					<td align="center"><?php echo $food->amount;?></td>
					<td align="center"><?php 
				//foreach($list as $list_1) { 
							if($mess->is_paid == 0)
							{
								if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))
								{
						
								 echo CHtml::link(Yii::t('app','Pay Fees'),array('/hostel/MessManage/Payfees','id'=>$allot->student_id),array('confirm'=>Yii::t('app','Are you sure?')));
								}
								else
								{
									echo Yii::t('app','Not Paid');		
								}
							}
							else
							{
								echo Yii::t('app','Paid');								
							}
					?></td>
				
				</tr>
				</table>
				</div>
				<?php
				// }
            }
            else
            {
            	echo Yii::t('app','No Data Available!');
            }?>
            </div>
        </td>
    </tr>
</table>