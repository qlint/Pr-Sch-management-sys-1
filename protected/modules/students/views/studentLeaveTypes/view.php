<?php
$this->breadcrumbs=array(
	'Student Leave Types'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List StudentLeaveTypes', 'url'=>array('index')),
	array('label'=>'Create StudentLeaveTypes', 'url'=>array('create')),
	array('label'=>'Update StudentLeaveTypes', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete StudentLeaveTypes', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage StudentLeaveTypes', 'url'=>array('admin')),
);
?>

<h1>View StudentLeaveTypes #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'code',
		'is_excluded',
		'status',
		'label',
		'colour_code',
	),
)); ?>
