<?php
$this->breadcrumbs=array(
	Yii::t('app','Rooms')=>array('/hostel'),
	$model->id,
);

$this->menu=array(
	array('label'=>Yii::t('app','List Room'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Room'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Update Room'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Delete Room'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('app','Are you sure you want to delete this item?'))),
	array('label'=>Yii::t('app','Manage Room'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','View Room');?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'room_no',
		'floor',
		'is_full',
		'no_of_bed',
		'created',
	),
)); ?>
