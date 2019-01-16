<?php
$this->breadcrumbs=array(
	Yii::t('app','Rooms')=>array('/hostel'),
	Yii::t('app','Manage'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <?php $this->renderPartial('/settings/hostel_left');?>
 </td>
    <td valign="top"> 
    <div class="cont_right">
<?php
 echo '<strong>'.Yii::t('app','Sorry!&nbsp;There is no space in this room.').'</strong>&nbsp;';
 echo '<strong>'.Yii::t('app','Click Here to enter the').' </strong> &nbsp;&nbsp;'. CHtml::link(Yii::t('app','Room details'),array('/Room/create'));
 
 ?>
 </div>
 </td>
 </tr>
 </table>