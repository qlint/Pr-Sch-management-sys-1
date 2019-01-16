<?php
$this->breadcrumbs=array(
	'Elective Exams'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ElectiveExams', 'url'=>array('index')),
	array('label'=>'Create ElectiveExams', 'url'=>array('create')),
	array('label'=>'Update ElectiveExams', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ElectiveExams', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ElectiveExams', 'url'=>array('admin')),
);
?>

<h1>View ElectiveExams #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'exam_group_id',
		'elective_id',
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
