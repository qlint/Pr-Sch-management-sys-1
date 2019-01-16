<?php
$this->breadcrumbs=array(
	'Terms'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Terms', 'url'=>array('index')),
	array('label'=>'Create Terms', 'url'=>array('create')),
	array('label'=>'Update Terms', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Terms', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Terms', 'url'=>array('admin')),
);
?>

<h1>View Terms #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'term_id',
		'academic_yr_id',
		'start_date',
		'end_date',
	),
)); ?>
