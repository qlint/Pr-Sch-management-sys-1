<?php
$this->breadcrumbs=array(
	Yii::t('app','Hostel')=>array('/hostel'),
	Yii::t('app','Mess Manages')=>array('/hostel/messManage/messinfo'),
	Yii::t('app','Mess Info'),
);
?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'vacate-form',
	'enableAjaxValidation'=>false,
)); ?>	
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <?php $this->renderPartial('/settings/hostel_left');?>
 </td>
    <td valign="top"> 
    <div class="cont_right">
    <h1><?php echo Yii::t('app','Mess Manage');?></h1>
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
	$is_edit = PreviousYearSettings::model()->findByAttributes(array('id'=>3));
	if($year != $current_academic_yr->config_value and ($is_insert->settings_value==0 or $is_edit->settings_value==0))
	{
	?>
	<div>
		<div class="yellow_bx" style="background-image:none;width:95%;padding-bottom:45px;">
			<div class="y_bx_head" style="width:95%;">
			<?php 
				echo Yii::t('app','You are not viewing the current active year. ');
				if($is_insert->settings_value==0 and $is_edit->settings_value!=0)
				{
					echo Yii::t('app','To collect the mess fees, enable the Insert option in Previous Academic Year Settings.');	
				}
				elseif($is_insert->settings_value!=0 and $is_edit->settings_value==0)
				{
					echo Yii::t('app','To edit the food preference, enable the Edit option in Previous Academic Year Settings.');	
				}
				else
				{
					echo Yii::t('app','To manage the mess details, enable the required options in Previous Academic Year Settings.');	
				}
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
		$criteria = new CDbCriteria;
		//$criteria->select = 'student_id';
		$criteria->condition = 'status=:x AND student_id IS NOT NULL';
		$criteria->params = array(':x'=>'C');
		$criteria->distinct = true;
		$mess=MessFee::model()->findAll($criteria);
		
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
				<td align="center"><?php echo Yii::t('app','Food Preference' );?></td>
				<td align="center"><?php echo Yii::t('app','Amount');?></td>
				<td align="center"><?php echo Yii::t('app','Action');?></td>
			</tr>
		<?php
		
			 if($mess!=NULL)
			{
		
				foreach($mess as $mess_1)
				{
					
					
					$allot=Allotment::model()->findByAttributes(array('student_id'=>$mess_1->student_id,'status'=>'S'));
					if($allot!=NULL)
					{
						$stud=Students::model()->findByAttributes(array('id'=>$allot->student_id));
						$register=Registration::model()->findByAttributes(array('student_id'=>$allot->student_id));
						if($register!=NULL)
						{
						$food=FoodInfo::model()->findByAttributes(array('id'=>$register->food_preference));
						}
						$floor=Floor::model()->findByAttributes(array('id'=>$allot->floor));
		                $hostel=Hosteldetails::model()->findByAttributes(array('id'=>$floor->hostel_id));
						$room = Room::model()->findByAttributes(array('id'=>$allot->room_no));
					
	
					
		?>
			<tr>
                            <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                            { ?>
				<td align="center"><?php if($stud!=NULL){
                                    $name='';
                                    $name=  $stud->studentFullName('forStudentProfile');
                                    echo $name;
                               // echo $stud->last_name.' '. $stud->first_name; 
                               } ?></td>
                            <?php } ?>
                <td align="center"><?php echo $hostel->hostel_name;?></td>
				<td align="center"><?php echo $room->room_no;?></td>
				<td align="center"><?php echo $allot->bed_no;?></td>
				<td align="right" width="20%">
				<?php 
					echo $food->food_preference;
					if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
					{
						echo ' '.CHtml::link('<span style="padding:0 30px 0 5px; border-left:1px solid #7A7A7A; margin-left:3px; ">'.Yii::t('app','Change').'</span>',array('/hostel/Registration/Change','id'=>$allot->student_id));
					}
					
				?>
                </td>
				<td align="center"><?php echo $food->amount;?></td>
				<td align="center">
	<?php

					if($mess_1->is_paid=='0')
						{
							if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))
							{
								echo CHtml::link(Yii::t('app','Pay Fees'), "#", array('submit'=>array('/hostel/MessManage/Payfees','id'=>$allot->student_id,), 'confirm'=>Yii::t('app','Are you sure?'), 'csrf'=>true)); 
							}
							else
							{
								echo Yii::t('app','Not Paid');
							}
						}
					else
					{
						echo Yii::t('app','Paid').' | ';	
 					    echo CHtml::link(Yii::t('app',' Print Receipt'),array('/hostel/MessManage/print','id'=>$allot->student_id),array('target'=>'_blank')); 
        
					}
					}
		?>
				</td>
			</tr>
		<?php } ?>
			
           
		<?php

		}
		else
		
			{
				 echo '<tr><td align="center" colspan="7"><strong>'.Yii::t('app','No data available!').'</strong></td></tr>';
				
			}
			
	?>
    </div>
    </td>
    </tr>
    </table></table> </div>
<?php $this->endWidget(); ?>
