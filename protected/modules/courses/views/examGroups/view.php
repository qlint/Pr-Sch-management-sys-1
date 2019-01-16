<?php
$this->breadcrumbs=array(
	Yii::t('app','Exam Groups')=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>Yii::t('app','List Exam Groups'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Exam Groups'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Update Exam Groups'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Delete Exam Groups'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('app','Are you sure you want to delete this Exam Group?'))),
	array('label'=>Yii::t('app','Manage Exam Groups'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','View Exam Groups');?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'batch_id',
		'exam_type',
		'is_published',
		'result_published',
		'exam_date',
	),
)); ?>
