<?php
$this->breadcrumbs=array(
	Yii::t('app','Publications')=>array('/library'),
	$model->name,
);

$this->menu=array(
	array('label'=>Yii::t('app','List Publication'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Publication'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Update Publication'), 'url'=>array('update', 'id'=>$model->publication_id)),
	array('label'=>Yii::t('app','Delete Publication'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->publication_id),'confirm'=>Yii::t('app','Are you sure you want to delete this item?'))),
	array('label'=>'Manage Publication', 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','View Publication');?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'publication_id',
		'name',
		'location',
	),
)); ?>
