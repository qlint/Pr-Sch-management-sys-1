<?php
$this->breadcrumbs=array(
	Yii::t('app','Alotment')=>array('/hostel'),
	Yii::t('app','Manage'),
);

?>
<h1><?php echo Yii::t('hostel','Allotments');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
