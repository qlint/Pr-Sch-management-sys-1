<?php Yii::app()->clientScript->registerCoreScript('jquery');

         //IMPORTANT about Fancybox.You can use the newest 2.0 version or the old one
        //If you use the new one,as below,you can use it for free only for your personal non-commercial site.For more info see
		//If you decide to switch back to fancybox 1 you must do a search and replace in index view file for "beforeClose" and replace with 
		//"onClosed"
        // http://fancyapps.com/fancybox/#license
          // FancyBox2
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js_plugins/fancybox2/jquery.fancybox.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/js_plugins/fancybox2/jquery.fancybox.css', 'screen');
         // FancyBox
         //Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/fancybox/jquery.fancybox-1.3.4.js', CClientScript::POS_HEAD);
         // Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js_plugins/fancybox/jquery.fancybox-1.3.4.css','screen');
        //JQueryUI (for delete confirmation  dialog)
         Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/jqui1812/js/jquery-ui-1.8.12.custom.min.js', CClientScript::POS_HEAD);
         Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js_plugins/jqui1812/css/dark-hive/jquery-ui-1.8.12.custom.css','screen');
          ///JSON2JS
         Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/json2/json2.js');
       

           //jqueryform js
               Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/ajaxform/jquery.form.js', CClientScript::POS_HEAD);
              Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/ajaxform/form_ajax_binding.js', CClientScript::POS_HEAD);
              Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js_plugins/ajaxform/client_val_form.css','screen');  ?>
              <?php
Yii::app()->clientScript->registerScript(
   'myHideEffect',
   '$(".info").animate({opacity: 1.0}, 3000).fadeOut("slow");',
   CClientScript::POS_READY
);
?>
<script language="javascript">
function getid()
{
var id= document.getElementById('drop').value;
window.location = "index.php?r=batches/manage&id="+id;
}
</script>
<script>
$(document).ready(function() {
$(".act_but").click(function(){	$('.act_drop').hide();	
            	if ($("#"+this.id+'x').is(':hidden')){
					
                	$("#"+this.id+'x').show();
					
				}
            	else{
                	$("#"+this.id+'x').hide();
            	}
            return false;
       			 });
				  $('#'+this.id+'x').click(function(e) {
            		e.stopPropagation();
        			});
        		
});
$(document).click(function() {
					
            		$('.act_drop').hide();
					
        			});
</script>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Vehicle Details')=>array('/transport/vehicleDetails/manage'),
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
                <h1><?php echo Yii::t('app','Vehicle Details');?></h1>
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
									echo Yii::t('app','To enter the vehicle details, enable Create option in Previous Academic Year Settings.');
								}
								elseif($is_create->settings_value!=0 and $is_edit->settings_value==0 and $is_delete->settings_value!=0)
								{
									echo Yii::t('app','To edit the vehicle details, enable Edit option in Previous Academic Year Settings.');
								}
								elseif($is_create->settings_value!=0 and $is_edit->settings_value!=0 and $is_delete->settings_value==0)
								{
									echo Yii::t('app','To delete the vehicle details, enable Delete option in Previous Academic Year Settings.');
								}
								else
								{
									echo Yii::t('app','To manage the vehicle details, enable the required options in Previous Academic Year Settings.');	
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
                            <li> <?php echo CHtml::link('<span>'.Yii::t('app','Enter Vehicle Details').'</span>',array('/transport/vehicleDetails/create'),array('class'=>'a_tag-btn')); ?></li>                                   
</ul>
</div> 
</div>
                    
                <?php
				}
				?>
                <?php $vehicle = VehicleDetails::model()->findAll('is_deleted=:y' ,array(':y'=>0));?>
                <div class="pdtab_Con" style="padding-top:0px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr class="pdtab-h">
                            <td align="center"><?php echo Yii::t('app','Vehicle No.');?></td>
                            <td align="center"><?php echo Yii::t('app','No of Seats');?></td>
                            <td align="center"><?php echo Yii::t('app','Maximum Capacity');?></td>
                            <td align="center"><?php echo Yii::t('app','Driver');?></td>
                            <td align="center"><?php echo Yii::t('app','Route');?></td>
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
                        if($vehicle!=NULL)
                        {
							
							foreach($vehicle as $vehicle_1)
							{
								$route = RouteDetails::model()->findByAttributes(array('vehicle_id'=>$vehicle_1->id));
								$driver = DriverDetails::model()->findByAttributes(array('vehicle_id'=>$vehicle_1->id));
								
							?>
								<tr>
                                    <td align="center"><?php echo $vehicle_1->vehicle_no;?></td>
                                    <td align="center"><?php echo $vehicle_1->no_of_seats;?></td>
                                    <td align="center"><?php echo $vehicle_1->maximum_capacity;?></td>
                                    <td align="center"><?php 
                                    if($driver!=NULL)
                                    {
                                    	echo $driver->last_name.' '.$driver->first_name;
                                    }
									else
									{
										echo Yii::t('app','No Driver Assigned');
									}
									?>
                                    </td>
                                    <td align="center">
									<?php 
                                    if($route!=NULL)
                                    {
                                    	echo $route->route_name;
									}
									else
									{
										echo Yii::t('app','No Route Assigned');
									}
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
                                            echo CHtml::link(Yii::t('app','Edit'),array('/transport/vehicleDetails/update','id'=>$vehicle_1->id));
                                        }
                                        if($edit_delete == 1)
                                        {
                                            echo ' | ';
                                        }
                                        if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
                                        {
                                           
											
											echo CHtml::link(Yii::t('app','Delete'), "#", array("submit"=>array('/transport/vehicleDetails/deletedetails','id'=>$vehicle_1->id),'confirm' => Yii::t('app', 'Are you sure ?'), 'csrf'=>true));
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
                        	echo '<tr><td align="center" colspan="6"><strong>'.Yii::t('app','No data available.').'</strong></div>';
                        }
                        ?>
                    </table>
                </div> <!-- END div class="pdtab_Con" -->
                
            </div> <!-- END div class="cont_right" -->
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>