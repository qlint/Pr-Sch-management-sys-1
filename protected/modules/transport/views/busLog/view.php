<?php $this->breadcrumbs=array(
    Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Bus Log')=>array('/transport/buslog/manage'),
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
             
                     <h1><?php echo Yii::t('app','Bus Logs');?></h1> 

                <?php $driver=BusLog::model()->findByAttributes(array('id'=>$_REQUEST['id']));
$vehicle=VehicleDetails::model()->findByAttributes(array('id'=>$driver->vehicle_id));
?>
              <div class="pdtab_Con">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr class="pdtab-h">
                        <td align="center">
                            <?php echo Yii::t('app','Vehicle Code');?>
                        </td>
                       <td align="center">
                            <?php echo Yii::t('app','Start Time Reading');?>
                        </td>
                         <td align="center">
                            <?php echo Yii::t('app','End Time Reading');?>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <?php echo $vehicle->vehicle_code;?>
                        </td>
                        <td align="center">
                            <?php echo $driver->start_time_reading;?>
                        </td>
                         <td align="center">
                            <?php echo $driver->end_time_reading;?>
                        </td>
                    </tr>
                </table>
              </div>
            </div>
        </td>
    </tr>
</table>