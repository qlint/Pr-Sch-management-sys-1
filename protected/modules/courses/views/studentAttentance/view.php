<?php
$this->breadcrumbs=array(
	Yii::t('attendance','Student Attentances')=>array('/courses'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List StudentAttentance', 'url'=>array('index')),
	array('label'=>'Create StudentAttentance', 'url'=>array('create')),
	array('label'=>'Update StudentAttentance', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete StudentAttentance', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage StudentAttentance', 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('attendance','View StudentAttentance').' #'.$model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'student_id',
		'date',
		'reason',
	),
)); ?>
