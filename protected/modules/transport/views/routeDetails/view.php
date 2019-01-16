<?php
$this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Route Details')=>array('/transport'),
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
    <h1><?php echo Yii::t('app','Route Details');?></h1>

<?php
$driver=RouteDetails::model()->findByAttributes(array('id'=>$_REQUEST['id']));
?>
  <div class="pdtab_Con" >
<table width="80%" border="0" cellspacing="0" cellpadding="0">
<tr class="pdtab-h">
<td align="center"><?php echo Yii::t('app','Route Name');?></td>
<td align="center"><?php echo Yii::t('app','Vehicle ID');?></td>

</tr>
<tr>
<td align="center"><?php echo $driver->route_name;?></td>
<td align="center"><?php echo $driver->vehicle_id;?></td>

</tr>
</table>
</div>
</div>
</td>
</tr>
</table>