<?php
$this->breadcrumbs=array(
	$this->module->id,
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
  
    <?php $this->renderPartial('/transportation/trans_left');?>
    
    </td>
    <td valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top" width="75%">
        <div class="cont_right">
   <h1><?php echo Yii::t('app','Transport Dashboard');?></h1> 
<div class="pdtab_Con" style="padding-top:0px;">
<div style="font-size:13px; padding:5px 0px"><strong><?php echo Yii::t('transport','Bus Log');?></strong></div>
 <?php $route=BusLog::model()->findAll();
	
		?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr class="pdtab-h">
        	<td align="center"><?php echo Yii::t('app','Vehicle Code');?></td>
        	<td align="center"><?php echo Yii::t('app','Start Time Reading');?></td>
            <td align="center"><?php echo Yii::t('app','End Time Reading');?></td>
            <td align="center"><?php echo Yii::t('app','Action');?></td>
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
                    <td align="center"><?php if($fuel == NULL) {echo CHtml::link(Yii::t('app','Record Consumption Details'),array('/transport/FuelConsumption/create','id'=>$route_1->id,'vehicle_id'=>$route_1->vehicle_id)); }
					else{ echo CHtml::link(Yii::t('app','View Consumption Details'),array('/transport/FuelConsumption/view','id'=>$fuel->id,'vehicle_id'=>$route_1->vehicle_id));}?></td>
                </tr>
                <?php
			//}
			
		}
		?>
        
        <?php
		
		
	}
	else
	{
		  echo '<tr><td align="center" colspan="4"><strong>'.Yii::t('app','No data available').'</strong></td></tr>';
	}
	?>
    </table>
        </div>
<div class="clear"></div>
</div>
		</td>
       </tr>
     </table>
    </td>
   </tr>
</table>