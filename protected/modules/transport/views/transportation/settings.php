<?php
$this->breadcrumbs=array(
	Yii::t('app','Transportations')=>array('/transport'),
	Yii::t('app','Settings'),
);?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'book-form',
	'enableAjaxValidation'=>false,
)); ?>
 
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">

 
 </td>
  <td valign="top">
   <div align="left">
   <?php
   echo CHtml::link(Yii::t('app','Add Vehicle Details'),array('/VehicleDetails/create')).'&nbsp;&nbsp'.CHtml::link(Yii::t('app','Add Route Details'),array('/RouteDetails/create')).'&nbsp;&nbsp'.CHtml::link(Yii::t('app','Add Driver Details'),array('/DriverDetails/create'));
   ?>
   </div>
  </td>
 </tr>
 </table>                     

 <?php $this->endWidget(); ?>