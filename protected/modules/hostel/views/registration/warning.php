<?php
$this->breadcrumbs=array(
	Yii::t('app','Hostel')=>array('/hostel'),
	Yii::t('app','Manage'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <?php $this->renderPartial('/settings/hostel_left');?>
 </td>
    <td valign="top"> 
     <div class="cont_right" >
    <div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;">
                    <div class="y_bx_head" style="width:90%">
<?php
 echo '<strong>&nbsp;'.Yii::t('app','The student is already alloted for Transportation.').'</strong>&nbsp;';
 echo '<strong>'.Yii::t('app','Do you want to continue?').' </strong> &nbsp;&nbsp;'. CHtml::link(Yii::t('app','Yes'),array('/hostel/registration/save','student_id'=>$_REQUEST['student_id'],'registration'=>$_REQUEST['registration'],'floor_id'=>$_REQUEST['floor_id'],'hostel'=>$_REQUEST['hostel'])) .' | '. CHtml::link(Yii::t('app','No'),array('/hostel/registration/create'));
 
 ?>
 </div>
 </div>
 </div>
 </td>
 </tr>
 </table>