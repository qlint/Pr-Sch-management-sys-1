<?php
$this->breadcrumbs=array(
	Yii::t('app','Route')=>array('/transport'),
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
 echo '<strong>'.Yii::t('app','&nbsp;The student is already alloted for Hostel.').'</strong>&nbsp;';
 echo '<strong>'.Yii::t('app','Do you want to continue ? ').' </strong> &nbsp;&nbsp;'. CHtml::link(Yii::t('app','Yes '),array('/transport/transportation/allotstudent','student_id'=>$_REQUEST['student_id'],'transportation'=>$_REQUEST['transportation'])) .' | '. CHtml::link(Yii::t('app',' No'),array('/transport/transportation/studentsearch'));
 
 ?>
 </div>
 </div></div>
 </td>
 </tr>
 </table>