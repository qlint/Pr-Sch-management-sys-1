<?php
$this->breadcrumbs=array(
	Yii::t('app','Timetable Entries')=>array('/timetable'),
	$model->id,
);

$this->menu=array(
	array('label'=>Yii::t('app','List TimetableEntries'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create TimetableEntries'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Update TimetableEntries'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Delete TimetableEntries'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('app','Are you sure you want to delete this item?'))),
	array('label'=>Yii::t('app','Manage TimetableEntries'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','View TimetableEntries');?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'batch_id',
		'weekday_id',
		'class_timing_id',
		'subject_id',
		'employee_id',
	),
)); ?>
