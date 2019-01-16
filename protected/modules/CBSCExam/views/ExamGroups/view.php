<?php
$this->breadcrumbs=array(
	'Cbsc Exam Groups'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List CbscExamGroups', 'url'=>array('index')),
	array('label'=>'Create CbscExamGroups', 'url'=>array('create')),
	array('label'=>'Update CbscExamGroups', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CbscExamGroups', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CbscExamGroups', 'url'=>array('admin')),
);
?>

<h1>View CbscExamGroups #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'term_id',
		'name',
		'exam_type',
		'mark_type',
		'date_published',
		'result_published',
		'date',
	),
)); ?>
