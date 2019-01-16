<?php
$this->breadcrumbs=array(
	Yii::t('app','Floors')=>array('/hostel'),
	$model->id,
);

$this->menu=array(
	array('label'=>Yii::t('app','List Floor'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Floor'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Update Floor'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Delete Floor'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>Yii::t('app','Manage Floor'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','View Floor');?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'floor_no',
		'created',
	),
)); ?>
