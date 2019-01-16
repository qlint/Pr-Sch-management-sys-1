
<?php
$this->breadcrumbs=array(
	Yii::t('app','Allotments')=>array('/hostel'),
	Yii::t('app','RoomInfo'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  
  <td width="247" valign="top">

 <?php $this->renderPartial('/settings/hostel_left');?>
&nbsp;&nbsp;&nbsp;&nbsp; </td>
 <td valign="top" align="center"><h3>
<?php
 echo '<strong>'.Yii::t('app','Sorry! No rooms are  available now.').'</strong>&nbsp;';
 echo '<strong>'.Yii::t('app','Click Here to view the').' </strong> &nbsp;&nbsp;'. CHtml::link(Yii::t('hostel','Room Details'),array('/hostel/Room/manage'));
  //echo $this->renderPartial('_form', array('model'=>$model)); 
 ?>
</h3></center>
 <?php /*?><div class="cont_right">
<h1><?php echo Yii::t('hostel','Registration');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div><?php */?>
 </td>
 </tr>
 <table>