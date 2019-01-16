<?php
$this->breadcrumbs=array(
    Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Bus Log'),
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
                <h1><?php echo Yii::t('app','Bus Log');?></h1>
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
									echo Yii::t('app','To enter bus log, enable Create option in Previous Academic Year Settings.');
								}
								elseif($is_create->settings_value!=0 and $is_edit->settings_value==0 and $is_delete->settings_value!=0)
								{
									echo Yii::t('app','To edit the bus log, enable Edit option in Previous Academic Year Settings.');
								}
								elseif($is_create->settings_value!=0 and $is_edit->settings_value!=0 and $is_delete->settings_value==0)
								{
									echo Yii::t('app','To delete the bus log, enable Delete option in Previous Academic Year Settings.');
								}
								else
								{
									echo Yii::t('app','To manage the bus log, enable the required options in Previous Academic Year Settings.');	
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
                <?php
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
				{
				?>
               
                
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li> <?php echo CHtml::link('<span>'.Yii::t('app','Create New Bus Log').'</span>',array('/transport/BusLog/create'),array('class'=>'a_tag-btn')); ?></li>                                   
</ul>
</div> 
</div>
                 <!-- END div class="edit_bttns" -->
                
                
                
                <?php
				}
				?>
                <?php $route=BusLog::model()->findAll();?>
                <div class="pdtab_Con" style="padding-top:0px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr class="pdtab-h">
                            <td align="center"><?php echo Yii::t('app','Vehicle Code');?></td>
                            <td align="center"><?php echo Yii::t('app','Start Time Reading');?></td>
                            <td align="center"><?php echo Yii::t('app','End Time Reading');?></td>
                            <td align="center"><?php echo Yii::t('app','Fuel Consumption');?></td>
                            <?php 
							if($edit_or_delete == 1)
							{
							?>
							<td align="center"><?php echo Yii::t('app','Action');?></td>
							<?php
							}
							?>
                        </tr>
                        <?php
                        if($route!=NULL)
                        {
							foreach($route as $route_1)
							{
							$vehicle=VehicleDetails::model()->findByAttributes(array('id'=>$route_1->vehicle_id));
							$fuel =FuelConsumption::model()->findByAttributes(array('vehicle_id'=>$route_1->vehicle_id));
							
							?>
							<tr>
                                <td align="center"><?php echo $vehicle->vehicle_code;?></td>
                                <td align="center"><?php echo $route_1->start_time_reading;?></td>
                                <td align="center"><?php echo $route_1->end_time_reading ;?></td>
                                <td align="center">
								<?php 
								if($fuel == NULL) 
								{
									if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
									{
										echo CHtml::link(Yii::t('app','Record Consumption Details'),array('/transport/FuelConsumption/create','id'=>$route_1->id,'vehicle_id'=>$route_1->vehicle_id)); 
									}
									else
									{
										echo Yii::t('app','No Details Recorded');
									}
								}
                                else
								{ 
									echo CHtml::link(Yii::t('app','View Consumption Details'),array('/transport/FuelConsumption/view','id'=>$fuel->id,'vehicle_id'=>$route_1->vehicle_id));
								}
								?>
                                </td>
                                <?php 
								if($edit_or_delete == 1)
								{
								?>
								<td align="center">
									<?php
									if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
									{
									echo CHtml::link(Yii::t('app','Edit'),array('/transport/BusLog/update','id'=>$route_1->id));
									}
									if($edit_n_delete ==1)
									{
										echo ' | ';
									}
									if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
									{
									
									echo CHtml::link(Yii::t('app','Delete'), "#", array("submit"=>array('/transport/BusLog/delete','id'=>$route_1->id),'confirm' => Yii::t('app', 'Are you sure, you want to delete this bus logo?'), 'csrf'=>true));
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
                        	echo '<tr><td align="center" colspan="5"><strong>'.Yii::t('app','No data available').'</strong></td></tr>';
                        }
                        ?>
                    </table>
                </div> <!-- END div class="pdtab_Con" -->
            </div> <!-- END div class="cont_right" -->
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>