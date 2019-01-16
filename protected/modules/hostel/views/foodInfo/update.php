<?php
$this->breadcrumbs=array(
	Yii::t('app','Hostel')=>array('/hostel'),
	Yii::t('app','Mess Details')=>array('/hostel/foodInfo/manage'),
	Yii::t('app','Update'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <?php $this->renderPartial('/settings/hostel_left');?>
 </td>
    <td valign="top"> 
    <div class="cont_right">
<h1><?php echo Yii::t('app','Food Details');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
		</div>
        </td>
        </tr>
        </table>