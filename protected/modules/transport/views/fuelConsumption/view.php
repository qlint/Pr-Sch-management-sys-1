<?php $this->breadcrumbs=array(
    Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Bus Log')=>array('/transport/busLog/manage'),
	Yii::t('app','Fuel Consumption'),
	Yii::t('app','View'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
            <?php $this->renderPartial('/transportation/trans_left');?>
        </td>
        <td valign="top">
            <div class="cont_right">
               <h1><?php echo Yii::t('app','Fuel Consumption');?></h1>
                   
                <?php 
				$driver=FuelConsumption::model()->findByAttributes(array('id'=>$_REQUEST['id']));
            	$vehicle=VehicleDetails::model()->findByAttributes(array('id'=>$driver->vehicle_id));?>
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
								if($is_edit->settings_value==0 and $is_delete->settings_value!=0)
								{
									echo Yii::t('app','To edit fuel consumption, enable Edit option in Previous Academic Year Settings.');
								}
								elseif($is_edit->settings_value!=0 and $is_delete->settings_value==0)
								{
									echo Yii::t('app','To delete fuel consumption, enable Delete option in Previous Academic Year Settings.');
								}
								else
								{
									echo Yii::t('app','To manage fuel consumption, enable the required options in Previous Academic Year Settings.');	
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
				$edit_n_delete = 0;
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 and $is_delete->settings_value!=0)))
				{
					$edit_n_delete = 1;
				}
				
				$edit_or_delete = 0;
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 or $is_delete->settings_value!=0)))
				{
					$edit_or_delete = 1;
				}
				?>
              
                <div class="pdtab_Con">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr class="pdtab-h">
                           <td align="center">
                                <?php echo Yii::t('app','Vehicle Code');?>
                            </td>
                            <td align="center">
                                <?php echo Yii::t('app','Fuel Consumed');?>
                            </td>
                            <td align="center">
                                <?php echo Yii::t('app','Amount');?>
                            </td>
                            <td align="center">
                                <?php echo Yii::t('app','Date');?>
                            </td>
                            
                            <?php 
							if($edit_or_delete == 1)
							{
							?>
							<td align="center">
								<?php echo Yii::t('app','Action');?>
							</td>
							<?php
							}
							?> 
                        </tr>
                        <tr>
                            <td align="center">
                                <?php echo $vehicle->vehicle_code;?>
                            </td>
                            <td align="center">
                                <?php echo $driver->fuel_consumed;?>
                            </td>
                           <td align="center">
                                <?php echo $driver->amount;?>
                            </td>
                             <td align="center">
                                <?php $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                                if($settings!=NULL)
                                {	
                                    $date1=date($settings->displaydate,strtotime($driver->consumed_date));
                                }
                               
                                echo $date1;?>
                            </td>
                            <?php 
							if($edit_or_delete == 1)
							{
							?>
							<td align="center">
								<?php
								if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
								{
									echo CHtml::link(Yii::t('app','Edit'),array('/transport/FuelConsumption/update','id'=>$_REQUEST['id']));
								}
								if($edit_n_delete ==1)
								{
									echo ' | ';
								}
								if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
								{
									//echo CHtml::link(Yii::t('app','Delete'),array('/transport/FuelConsumption/delete','id'=>$_REQUEST['id']),array('confirm'=>Yii::t('app','Are you sure?')));
									
									echo CHtml::link(Yii::t('app','Delete'), "#", array("submit"=>array('/transport/FuelConsumption/delete','id'=>$_REQUEST['id']),'confirm' => Yii::t('app', 'Are you sure?'), 'csrf'=>true));
									
									
									
									
								}
								?>
							</td>
							<?php
							}
							?>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
    </tr>
</table>