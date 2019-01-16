<?php
$this->breadcrumbs=array(
	'Log Comments'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List LogComment', 'url'=>array('index')),
	array('label'=>'Create LogComment', 'url'=>array('create')),
	array('label'=>'Update LogComment', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete LogComment', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage LogComment', 'url'=>array('admin')),
);
?>

<h1>View LogComment #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'created_by',
		'student_id',
		'comment',
		'date',
	),
)); ?>
