<?php
$this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Driver Details')=>array('/transport/DriverDetails/manage'),
	Yii::t('app','Manage'),
);?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'vacate-form',
	'enableAjaxValidation'=>false,
)); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/transportation/trans_left');?>
        </td>
        <td valign="top"> 
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Driver Details');?></h1>
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
				$is_create = PreviousYearSettings::model()->findByAttributes(array('id'=>1));
				$is_edit = PreviousYearSettings::model()->findByAttributes(array('id'=>3));
				$is_delete = PreviousYearSettings::model()->findByAttributes(array('id'=>4));
				if($year != $current_academic_yr->config_value and ($is_create->settings_value==0 or $is_edit->settings_value==0 or $is_delete->settings_value==0))
				{
				?>
                	<div>
						<div class="yellow_bx" style="background-image:none;width:680px;padding-bottom:45px;">
							<div class="y_bx_head" style="width:650px;">
							<?php 
								echo Yii::t('app','You are not viewing the current active year. ');
								if($is_create->settings_value==0 and $is_edit->settings_value!=0 and $is_delete->settings_value!=0)
								{ 
									echo Yii::t('app','To enter the driver details, enable Create option in Previous Academic Year Settings.');
								}
								elseif($is_create->settings_value!=0 and $is_edit->settings_value==0 and $is_delete->settings_value!=0)
								{
									echo Yii::t('app','To edit the driver details, enable Edit option in Previous Academic Year Settings.');
								}
								elseif($is_create->settings_value!=0 and $is_edit->settings_value!=0 and $is_delete->settings_value==0)
								{
									echo Yii::t('app','To delete the driver details, enable Delete option in Previous Academic Year Settings.');
								}
								else
								{
									echo Yii::t('app','To manage the driver details, enable the required options in Previous Academic Year Settings.');	
								}
							?>
							</div>
							<div class="y_bx_list" style="width:650px;">
								<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
							</div>
						</div>
					</div><br />
                <?php
				}
				$edit_delete = 0;
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 and $is_delete->settings_value!=0)))
				{
					$edit_delete = 1;
				}
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
				{
				?>
<!-- END div class="edit_bttns" -->
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
                        <li> <?php echo CHtml::link('<span>'.Yii::t('app','Enter Driver Details').'</span>',array('/transport/DriverDetails/create'),array('class'=>'a_tag-btn')); ?></li>                                   
</ul>
</div> 

</div> 
                    
                <?php
				}
				?>
                
                <?php $drive=DriverDetails::model()->findAll();?>
                <div class="pdtab_Con" style="padding-top:0px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr class="pdtab-h">
                            <td align="center"><?php echo Yii::t('app','Name');?></td>
                            <td align="center"><?php echo Yii::t('app','DOB');?></td>
                            <td align="center"><?php echo Yii::t('app','Age');?></td>
                            <td align="center"><?php echo Yii::t('app','License No');?></td>
                            <td align="center"><?php echo Yii::t('app','Phone No');?></td>
                             <td align="center"><?php echo Yii::t('app','Expiry Date');?></td>
                            <?php
							if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 or $is_delete->settings_value!=0)))
							{
							?>
                            <td align="center"><?php echo Yii::t('app','Action');?></td>
                            <?php
							}
							?>
                        </tr>
                        <?php
                        if($drive!=NULL)
                        {
							foreach($drive as $drive_1)
							{
							?>
							<tr>
                                <td align="center"><?php echo $drive_1->first_name.' '.$drive_1->last_name;?></td>
                                <td align="center">
									<?php 
                                    $user=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                                    if($user!=NULL)
                                    {
                                            $date=date($user->displaydate,strtotime($drive_1->dob));
                                    }
                                    echo $date;
                                    ?>
                                </td>
                                <td align="center"><?php echo $drive_1->age;?></td>
                                <td align="center"><?php echo $drive_1->license_no ;?></td>
                                 <td align="center"><?php echo $drive_1->phn_no ;?></td>
                                  <td align="center">
									<?php 
                                    $user=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                                    if($user!=NULL)
                                    {
                                            $date=date($user->displaydate,strtotime($drive_1->expiry_date));
                                    }
                                    echo $date;
                                    ?>
                                </td>
                                <?php
								if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 or $is_delete->settings_value!=0)))
								{
								?>
                                <td align="center">
								<?php 
                                if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
                                {
                                    echo CHtml::link(Yii::t('app','Edit'),array('/transport/DriverDetails/update','id'=>$drive_1->id));
                                }
                                if($edit_delete == 1)
                                {
                                    echo ' | ';
                                }
                                if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
                                {
									
									echo CHtml::link(Yii::t('app','Delete'), "#", array("submit"=>array('/transport/DriverDetails/deletedetails','id'=>$drive_1->id),'confirm' => Yii::t('app', 'Are you sure ?'), 'csrf'=>true));
									
                                }
                                ?>
                                </td>
                                <?php
								}
								?>
							</tr>
							<?php
							}
                        }
                        else
                        {
                        	echo '<tr><td align="center" colspan="6"><strong>'.Yii::t('app','No data available').'</strong></td></tr>';
                        }
                        ?>
                    </table>
                </div> <!-- END div class="pdtab_Con" -->
            </div> <!-- END div class="cont_right" -->
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>