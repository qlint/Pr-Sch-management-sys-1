<?php
$this->breadcrumbs=array(
	'Elective Scores'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ElectiveScores', 'url'=>array('index')),
	array('label'=>'Create ElectiveScores', 'url'=>array('create')),
	array('label'=>'Update ElectiveScores', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ElectiveScores', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ElectiveScores', 'url'=>array('admin')),
);
?>

<h1>View ElectiveScores #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'student_id',
		'exam_id',
		'marks',
		'grading_level_id',
		'remarks',
		'is_failed',
		'created_at',
		'updated_at',
	),
)); ?>
