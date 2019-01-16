
<?php
$this->breadcrumbs=array(
    Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Bus Log')=>array('/transport/buslog/manage'),
	Yii::t('app','Create'),
);

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <?php $this->renderPartial('/transportation/trans_left');?>
 </td>
    <td valign="top"> 
    <div class="cont_right">
 <h1><?php echo Yii::t('app','Bus Log');?></h1>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
</td>
</tr>
</table>