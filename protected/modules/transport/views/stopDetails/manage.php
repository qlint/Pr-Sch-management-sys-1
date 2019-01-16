<?php
$this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','List All Routs')=>array('/transport/routeDetails/manage'),
	Yii::t('app','Stop Details'),
	Yii::t('app','Manage'),
);?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/transportation/trans_left');?>
        </td>
        <td valign="top"> 
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Stop Details');?></h1>
                
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
									echo Yii::t('app','To enter the stop details, enable Create option in Previous Academic Year Settings.');
								}
								elseif($is_create->settings_value!=0 and $is_edit->settings_value==0 and $is_delete->settings_value!=0)
								{
									echo Yii::t('app','To edit the stop details, enable Edit option in Previous Academic Year Settings.');
								}
								elseif($is_create->settings_value!=0 and $is_edit->settings_value!=0 and $is_delete->settings_value==0)
								{
									echo Yii::t('app','To delete the stop details, enable Delete option in Previous Academic Year Settings.');
								}
								else
								{
									echo Yii::t('app','To manage the stop details, enable the required options in Previous Academic Year Settings.');	
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
				
				if($edit_or_delete == 1)
				{
				?>
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
                        <ul>
                        	<?php
							if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
							{
								$stops = StopDetails::model()->findAllByAttributes(array('route_id'=>$_REQUEST['id']));
								$count = count($stops);
							?>
                            <li> <?php echo CHtml::link('<span>'.Yii::t('app','Edit Stops').'</span>',array('update','id'=>$_REQUEST['id']), array('class'=>'a_tag-btn')); 
										//echo CHtml::link('<span>'.Yii::t('app','Edit Stops').'</span>',array('create','id'=>$_REQUEST['id'], 'stops'=>$count));?></li>
                            <?php
							}
							?>
                            <?php
							if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
							{
							?>
                            <li> <?php echo CHtml::link('<span>'.Yii::t('app','Remove All Stops').'</span>', "#", array('class'=>'a_tag-btn'), array('submit'=>array('removeall','id'=>$_REQUEST['id'],), 'confirm'=>Yii::t('app','Are you sure you want to delete this stop details?'), 'csrf'=>true));  ?></li>
                            <?php
							}
							?>
                        </ul>
                        </div>
                       </div>

                    
                    
                <?php
				}
				?>
                
 <?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'vacate-form',
	'enableAjaxValidation'=>false,
	//'htmlOptions'=>array(
		'action'=>Yii::app()->createUrl('transport/StopDetails/create'),
		'method'=>'get',
	//),
)); ?>

  <input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>" />
                <?php
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
				{
				?>
                    <table>
                        <tr>
                            <td> <?php echo Yii::t('app','Add');?> <input type="text" name="stops" id="noOfStops" style="width:40px;" />&nbsp;<?php echo Yii::t('app','stops');?></td>
                            <td><input type="submit" class="formbut" value="<?php echo Yii::t('app','GO');?>" onclick="return checkStops();" /></td>
                            <td>
                                    <?php
                                        Yii::app()->clientScript->registerScript(
                                           'myHideEffect',
                                           '$(".error").animate({opacity: 1.0}, 5000).fadeOut("slow");',
                                           CClientScript::POS_READY
                                        );
                                    ?>  
                                    <div class="error" id="error" style="background:#FFF; color:#C00; padding-left:10px; visibility:hidden;"> 
                                    </div>
                                    
                            </td>
                        </tr>
                    </table>
                <?php
				}
				?>
                
   <?php $this->endWidget(); ?>
  

                <?php //$stop=StopDetails::model()->findAll(array('order'=>'arrival_mrng','condition'=>'route_id=:x','params'=>array(':x'=>$_REQUEST['id'])));
					  $stop=StopDetails::model()->findAll(array('condition'=>'route_id=:x','params'=>array(':x'=>$_REQUEST['id'])));	
                if($stop!=NULL)
                {
                ?>
                <div class="pdtab_Con">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                     <tr class="pdtab-h">
                        <td align="center"><?php echo Yii::t('app','Stop Name');?></td>
                        <td align="center"><?php echo Yii::t('app','Fare');?></td>
                        <td align="center"><?php echo Yii::t('app','Arrival Time(Morning)');?></td>
                        <td align="center"><?php echo Yii::t('app','Arrival Time(Evening)');?></td>
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
                    foreach($stop as $stop_1)
                    {
                        $route=RouteDetails::model()->findByAttributes(array('id'=>$stop_1->route_id));
                        
						?>
						<tr>
							<td align="center"><?php echo $stop_1->stop_name;?></td>
							<td align="center"><?php echo $stop_1->fare;?></td>
							<td align="center"><?php echo $stop_1->arrival_mrng;?></td>
							<td align="center"><?php echo $stop_1->arrival_evng;?></td>
                            <?php 
							if($edit_or_delete == 1)
							{
							?>
							<td align="center">
								<?php
								if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
								{
								echo CHtml::link(Yii::t('app','Edit'),array('stopDetails/edit','id'=>$stop_1->id));
								}
								if($edit_n_delete ==1)
								{
									echo ' | ';
								}
								if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
								{
								echo CHtml::link(Yii::t('app','Remove'),"#",array("submit"=>array('stopDetails/remove','id'=>$stop_1->id),'confirm'=>Yii::t('app','Remove stop').' '.$stop_1->stop_name, 'csrf'=>true));
									
								}
                                ?>
							</td>
                            <?php
							}
							?>
						</tr>
						<?php
                    }
                    ?>
                    
                    <?php
                    }
                    else
                    {
                    	echo '<div align="center"><strong>'.Yii::t('app','No data available.').'</strong></div>';
                    }
                    ?>
                     
                    </table>
             </div> <!-- END div class="pdtab_Con" -->
              </div> <!-- END div class="cont_right" -->
   
    			</td>
   			</tr>
		</table>
   

                    
<script type="text/javascript">
function checkStops()
{
	
	var stops = document.getElementById("noOfStops").value;
	if(stops==""){
		document.getElementById("error").style.visibility = 'visible';
		document.getElementById("error").innerHTML = '<?php echo Yii::t('app','Add the number of stop(s)!');?>';
		return false;
	}
}
</script>