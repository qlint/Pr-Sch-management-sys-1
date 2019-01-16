<?php
$this->breadcrumbs=array(
	Yii::t('app','Settings')=>array('/library'),
	$model->name,
);

$this->menu=array(
	array('label'=>Yii::t('app','List Settings'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Settings'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Update Settings'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Delete Settings'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('app','Are you sure you want to delete this item?'))),
	array('label'=>Yii::t('app','Manage Settings'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','View Settings');?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'value',
	),
)); ?>
