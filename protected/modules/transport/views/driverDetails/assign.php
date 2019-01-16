<?php
$this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Driver - Vehicle Association')=>array('/transport/driverDetails/assign'),
	Yii::t('app','Assign'),
);

?>
<?php $form=$this->beginWidget('CActiveForm', array(
'id'=>'book-form',
'enableAjaxValidation'=>false,
)); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        <?php $this->renderPartial('/transportation/trans_left');?>
        </td>
        <td valign="top"> 
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Driver - Vehicle Association');?></h1>
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
                                echo Yii::t('app','To assign the driver to a vehicle, enable the Insert option in Previous Academic Year Settings.');	
                            ?>
                            </div>
                            <div class="y_bx_list" style="width:95%;">
                                <h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
                            </div>
                        </div>
                    </div>
                <?php	
				}
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))
				{
				?> 
                <div class="formCon">
                    <div class="formConInner">
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" class="s_search">
                            <tr>
                                <td><strong><?php echo Yii::t('app','Select Driver');?></strong></td>
                                <td>&nbsp;</td>
                                <td><strong><?php echo Yii::t('app','Select Vehicle');?></strong></td>
                                <td>&nbsp;</td>
                            </tr>
                            
                            <tr>
                                <td><?php echo CHtml::dropDownList('driver_id','',CHtml::listData(DriverDetails::model()->findAll('status IS NULL'),'id','first_name'),array('prompt'=>Yii::t('app','Select')));?></td>
                                <td>&nbsp;</td>
                                <td><?php echo CHtml::dropDownList('vehicle_id','',CHtml::listData(VehicleDetails::model()->findAll('status IS NULL or status=:x',array(':x'=>'')),'id','vehicle_code'),array('prompt'=>Yii::t('app','Select')));?></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                            	<td colspan="4">&nbsp;</td>
                            </tr>
                            <tr>
                            	<td><?php echo CHtml::submitButton( Yii::t('app','Assign'),array('name'=>'search','class'=>'formbut')); ?></td>
                            </tr>
                        </table>
                    </div> <!-- END div class="formConInner" -->
                </div> <!-- END div class="formCon" -->
                <?php
				}
				?>
                <div class="pdtab_Con" style="padding:0px;">
                 <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr class="pdtab-h">
                            
                            <td align="center"><?php echo Yii::t('app','Driver Name');?></td>
                            <td align="center"><?php echo Yii::t('app','Date Of Birth');?></td>
                            <td align="center"><?php echo Yii::t('app','Vehicle Code');?></td>
                            <td align="center"><?php echo Yii::t('app','Route');?></td>
                            <td align="center"><?php echo Yii::t('app','Action');?></td>
                            
                            </tr>
                <?php
				
				$driverinfo = DriverDetails::model()->findAll('status=:x',array(':x'=>'C'));
				
				if($driverinfo!=NULL)
				{
					foreach($driverinfo as $list)
					{
						$route=RouteDetails::model()->findByAttributes(array('vehicle_id'=>$list->vehicle_id));
						$vehicleinfo = VehicleDetails::model()->findByAttributes(array('id'=>$list->vehicle_id));
						?>
                        <tr>
                            <td align="center"><?php echo $list->last_name.' '.$list->first_name;?></td>
                            <td align="center"><?php 
                                $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                                if($settings!=NULL)
                                    {	
                                    $date1=date($settings->displaydate,strtotime($list->dob));
                                    
                            
                                }
                                echo $date1;?></td>
                            <td align="center"><?php echo $vehicleinfo->vehicle_code;?></td>
                            <td align="center"><?php if($route!=NULL){echo $route->route_name;} else {echo Yii::t('app','No Route Assigned');}?></td>
                            <td align="center"><?php echo CHtml::link(Yii::t('app','Reallot'),array('reallot','id'=>$list->id),array('onclick'=>'js:if(confirm("'.Yii::t('app','Are you sure you want to reallot ?').'")){}else{return false;}'));?></td>
                            </tr>
                        <?php
					}
				}
				?>
                </table>
				</div>
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				<?php /*?><?php
				
                if(isset($id) && $id!=NULL)
                {
					$drive=DriverDetails::model()->findByAttributes(array('id'=>$id));
					$route=RouteDetails::model()->findByAttributes(array('vehicle_id'=>$drive->vehicle_id));
					
					?>
					<div class="pdtab_Con" style="padding:0px;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr class="pdtab-h">
                            
                            <td align="center"><?php echo Yii::t('transport','Driver Name');?></td>
                            <td align="center"><?php echo Yii::t('transport','Date Of Birth');?></td>
                            <td align="center"><?php echo Yii::t('transport','Vehicle Code');?></td>
                            <td align="center"><?php echo Yii::t('transport','Route');?></td>
                            
                            
                            </tr>
                            <tr>
                            <td align="center"><?php echo $drive->last_name.' '.$drive->first_name;?></td>
                            <td align="center"><?php 
                                $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                                if($settings!=NULL)
                                    {	
                                    $date1=date($settings->displaydate,strtotime($drive->dob));
                                    
                            
                                }
                                echo $date1;?></td>
                            <td align="center"><?php echo $drive->vehicle_id;?></td>
                            <td align="center"><?php if($route!=NULL){echo $route->route_name;} else {echo Yii::t('transport','No Route Assigned');}?></td>
                            
                            </tr>
                        </table>
                    </div> <!-- END div class="pdtab_Con" -->
                <?php
                } // END if(isset($id) && $id!=NULL)
                
                ?><?php */?>
                
            </div> <!-- END div class="cont_right" -->
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>