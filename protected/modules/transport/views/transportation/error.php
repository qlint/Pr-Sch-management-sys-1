<?php
$this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Allotment')=>array('/transport/transportation/create'),
	Yii::t('app','Manage'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <?php $this->renderPartial('/transportation/trans_left');?>
 </td>
    <td valign="top"> 
     <div class="cont_right" >
    <div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;">
                    <div class="y_bx_head" style="width:90%">
<?php
 echo '<strong>'.Yii::t('app','Sorry!!&nbsp;The student is already alloted.').'</strong>&nbsp;';
 echo '<strong>'.Yii::t('app','Click Here to view the').' </strong> &nbsp;&nbsp;'. CHtml::link(Yii::t('app','Details'),array('/transport/transportation/studentsearch'));
 
 ?>
 </div>
 </div>
 </div>
 </td>
 </tr>
 </table>