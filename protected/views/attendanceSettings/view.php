<?php
$this->breadcrumbs=array(
	Yii::t('app','Attendance Settings')=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>Yii::t('app','List AttendanceSettings'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create AttendanceSettings'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Update AttendanceSettings'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Delete AttendanceSettings'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('app','Are you sure you want to delete this item?'))),
	array('label'=>Yii::t('app','Manage AttendanceSettings'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','View AttendanceSettings');?> #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'config_key',
		'config_value',
	),
)); ?>
