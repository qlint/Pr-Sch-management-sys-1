<?php
$this->breadcrumbs=array(
	'Sms Counts'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List SmsCount', 'url'=>array('index')),
	array('label'=>'Create SmsCount', 'url'=>array('create')),
	array('label'=>'Update SmsCount', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SmsCount', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SmsCount', 'url'=>array('admin')),
);
?>

<h1>View SmsCount #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'current',
		'date',
	),
)); ?>
