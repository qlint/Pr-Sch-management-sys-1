<?php $this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Driver Details')=>array('/transport/driverDetails/manage'),
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
                    <?php echo Yii::t('app','Driver Details');?>
                </h1>
                <?php $driver=DriverDetails::model()->findByAttributes(array('id'=>$_REQUEST['id']));
?>
			<div class="pdtab_Con">
                <table width="80%" border="0" cellspacing="0" cellpadding="0" style="text-align:center;">
                    <tr class="pdtab-h" style="font-weight:bold;">
                        <td>
                            <?php echo Yii::t('app','First Name');?>
                        </td>
                        <td>
                            <?php echo Yii::t('app','Last Name');?>
                        </td>
                        <td>
                            <?php echo Yii::t('app','Address');?>
                        </td>
                        <td>
                            <?php echo Yii::t('app','Phone No. ');?>
                        </td>
                         <td>
                            <?php echo Yii::t('app','DOB');?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo $driver->first_name;?>
                        </td>
                        <td>
                            <?php echo $driver->last_name;?>
                        </td>
                        <td>
                            <?php echo $driver->address;?>
                        </td>
                        <td>
                            <?php echo $driver->phn_no;?>
                        </td>
                        <td>
                            <?php $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
							if($settings!=NULL)
							{	
								$date1=date($settings->displaydate,strtotime($driver->dob));
												
		
							}
							else
							{
							$date1 = $driver->dob;
							}
							echo $date1;?>
                        </td>
                    </tr>
                </table>
            </div>
            </div>
        </td>
    </tr>
</table>