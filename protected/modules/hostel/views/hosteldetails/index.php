	<?php
$this->breadcrumbs=array(
	Yii::t('app','Hostel Details')=>array('/hostel'),
	Yii::t('app','Manage'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create Hostel Details'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage Hostel Details'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Hostel Details');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
