<?php
$this->breadcrumbs=array(
	Yii::t('app','Floors')=>array('/hostel'),
	Yii::t('app','Manage'),
);

?>

<h1><?php echo Yii::t('app','Floors');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
