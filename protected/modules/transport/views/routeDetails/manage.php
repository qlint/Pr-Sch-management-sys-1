<?php
$this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Route Details')=>array('/transport/driverDetails/manage'),
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
                <h1><?php echo Yii::t('app','Route Details');?></h1>
                
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
									echo Yii::t('app','To enter the route details, enable Create option in Previous Academic Year Settings.');
								}
								elseif($is_create->settings_value!=0 and $is_edit->settings_value==0 and $is_delete->settings_value!=0)
								{
									echo Yii::t('app','To edit the route details, enable Edit option in Previous Academic Year Settings.');
								}
								elseif($is_create->settings_value!=0 and $is_edit->settings_value!=0 and $is_delete->settings_value==0)
								{
									echo Yii::t('app','To delete the route details, enable Delete option in Previous Academic Year Settings.');
								}
								else
								{
									echo Yii::t('app','To manage the route details, enable the required options in Previous Academic Year Settings.');	
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
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
				{
				?>
                     <!-- END div class="edit_bttns" -->
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
 <li> <?php echo CHtml::link('<span>'.Yii::t('app','Enter Route Details').'</span>',array('/transport/RouteDetails/create'),array('class'=>'a_tag-btn')); ?></li>
                                                           
</ul>
</div> 
</div>

                    
                    
                <?php
				}
				?>
                
                <?php $route=RouteDetails::model()->findAll(); ?>
                <div class="pdtab_Con" style="padding-top:0px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr class="pdtab-h">
                            <td align="center"><?php echo Yii::t('app','Route');?></td>
                            <td align="center"><?php echo Yii::t('app','No of stops');?></td>
                            <td align="center"><?php echo Yii::t('app','No of students');?></td>
                            <td align="center"><?php echo Yii::t('app','Vehicle Code');?></td>
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
							$connection = Yii::app()->db;
							$sql="SELECT t2.id FROM transportation AS t2 JOIN stop_details AS t1   WHERE t2.stop_id = t1.id AND t1.route_id= ".$route_1->id;							
							$command = $connection->createCommand($sql);
							$stop = $command->queryAll();
							$m=count($stop);
							$vehicle=VehicleDetails::model()->findByAttributes(array('id'=>$route_1->vehicle_id));							
							?>
							<tr>
                                <td align="center"><?php echo CHtml::link($route_1->route_name,array('/transport/StopDetails/manage','id'=>$route_1->id));?></td>
                                <td align="center"><?php  $stop=StopDetails::model()->findAll('route_id =:x',array(':x'=>$route_1->id));
								$c=count($stop);
								 echo CHtml::link($c,array('/transport/StopDetails/manage','id'=>$route_1->id)); ?></td>
                                <td align="center"><?php echo $m;?></td>
                                <td align="center"><?php echo CHtml::link($vehicle->vehicle_code,array('/transport/VehicleDetails/manage','id'=>$vehicle->id));?></td>
                                <?php 
								if($edit_or_delete == 1)
								{
								?>
								<td align="center">
									<?php
									if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
									{
										echo CHtml::link(Yii::t('app','Edit'),array('/transport/RouteDetails/update','id'=>$route_1->id));
									}
									if($edit_n_delete ==1)
									{
										echo ' | ';
									}
									if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
									{
									
									echo CHtml::link(Yii::t('app','Delete'), "#", array("submit"=>array('/transport/RouteDetails/deletedetails','id'=>$route_1->id),'confirm' => Yii::t('app', 'Are you sure?'), 'csrf'=>true));
									
									
									}
									echo ' | ';
									echo CHtml::link(Yii::t('app','Stops'),array('/transport/stopDetails/manage','id'=>$route_1->id));
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
                    <div class="pagecon">
						<?php 
                        $this->widget('CLinkPager', array(
                        'currentPage'=>$pages->getCurrentPage(),
                        'itemCount'=>$item_count,
                        'pageSize'=>$page_size,
                        'maxButtonCount'=>5,
                        //'nextPageLabel'=>'My text >',
                        'header'=>'',
                        'htmlOptions'=>array('class'=>'pages'),
                        ));?>
                    </div>               
                </div>
            </div> <!-- END div class="cont_right" -->
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>