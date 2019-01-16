<?php
$this->breadcrumbs=array(
	Yii::t('app','Weekdays')=>array('/timetable'),
	Yii::t('app','Create'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List Weekdays'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Manage Weekdays'), 'url'=>array('admin')),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    
    <?php $this->renderPartial('/default/left_side');?>
    
    </td>
    <td valign="top">
    <div class="cont_right formWrapper">
<h1><?php echo Yii::t('app','Create Weekdays');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
    </td>
  </tr>
</table>