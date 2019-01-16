<?php
$this->breadcrumbs=array(
	Yii::t('app','Roomrequests')=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>Yii::t('app','List Room Request'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Room Request'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Update Room Request'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Delete Room Request'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>Yii::t('app','Manage Room Request'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Room Request');?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'student_id',
		'allot_id',
		'status',
	),
)); ?>
