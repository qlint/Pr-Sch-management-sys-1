<?php $this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Vehicle Details')=>array('/transport/vehicleDetails/manage'),
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
                <h1>
                    <?php echo Yii::t('app','Vehicle Details');?>
                </h1>
                <?php $driver=VehicleDetails::model()->findByAttributes(array('id'=>$_REQUEST['id']));
?>
                <div class="pdtab_Con" >
                <table width="80%" border="0" cellspacing="0" cellpadding="0" style="text-align:center;">
                    <tr class="pdtab-h" style="font-weight:bold;">
                        <td>
                            <?php echo Yii::t('app','Vehicle No');?>
                        </td>
                        <td>
                            <?php echo Yii::t('app','Vehicle Code');?>
                        </td>
                        <td>
                            <?php echo Yii::t('app','No Of Seats');?>
                        </td>
                        <td>
                            <?php echo Yii::t('app','Maximum Capacity');?>
                        </td>
                        <td>
                            <?php echo Yii::t('app','Insurance');?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $driver->vehicle_no;?>
                        </td>
                        <td>
                            <?php echo $driver->vehicle_code;?>
                        </td>
                        <td>
                            <?php echo $driver->no_of_seats;?>
                        </td>
                        <td>
                            <?php echo $driver->maximum_capacity;?>
                        </td>
                        <td>
                            <?php echo $driver->insurance;?>
                        </td>
                    </tr>
                </table>
            </div></div>
        </td>
    </tr>
</table>