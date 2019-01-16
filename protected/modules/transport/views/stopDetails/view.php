<?php $this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','List All Routs')=>array('/transport/routeDetails/manage'),
	Yii::t('app','Stop Details'),
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
                    <?php echo Yii::t('app','Stop Details');?>
                </h1>
                <?php $driver=StopDetails::model()->findByAttributes(array('id'=>$_REQUEST['id']));
$vehicle=RouteDetails::model()->findByAttributes(array('id'=>$driver->route_id));
?>
<div class="pdtab_Con" >
                <table width="80%" border="0" cellspacing="0" cellpadding="0">
                    <tr class="pdtab-h">
                        <td>
                            <?php echo Yii::t('app','Route');?>
                        </td>
                        <td>
                            <?php echo Yii::t('app','Stop Name');?>
                        </td>
                        <td>
                            <?php echo Yii::t('app','Fare');?>
                        </td>
                        <td>
                            <?php echo Yii::t('app','Morning Arrival Time');?>
                        </td>
                        <td>
                            <?php echo Yii::t('app','Evening Arrival Time');?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $vehicle->route_name;?>
                        </td>
                        <td>
                            <?php echo $driver->stop_name;?>
                        </td>
                        <td>
                            <?php echo $driver->fare;?>
                        </td>
                        <td>
                            <?php echo $driver->arrival_mrng;?>
                        </td>
                        <td>
                            <?php echo $driver->arrival_evng;?>
                        </td>
                    </tr>
                </table>
            </div>
            </div>
        </td>
    </tr>
</table>