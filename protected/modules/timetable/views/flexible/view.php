<?php
$this->breadcrumbs=array(
	Yii::t('app','Weekdays')=>array('/timetable'),
	$model->id,
);

$this->menu=array(
	array('label'=>Yii::t('app','List Weekdays'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Weekdays'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Update Weekdays'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Delete Weekdays'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('app','Are you sure you want to delete this item?'))),
	array('label'=>Yii::t('app','Manage Weekdays'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','View Weekdays');?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'batch_id',
		'weekday',
	),
)); ?>
