<?php
$this->breadcrumbs=array(
	'Exams'=>array('/examination'),
	$model->id,
);

$this->menu=array(
	array('label'=>Yii::t('examination','List Exams'), 'url'=>array('index')),
	array('label'=>Yii::t('examination','Create Exams'), 'url'=>array('create')),
	array('label'=>Yii::t('examination','Update Exams'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('examination','Delete Exams'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>Yii::t('examination','Manage Exams'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('examination','View Exams');?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'exam_group_id',
		'subject_id',
		'start_time',
		'end_time',
		'maximum_marks',
		'minimum_marks',
		'grading_level_id',
		'weightage',
		'event_id',
		'created_at',
		'updated_at',
	),
)); ?>
