<?php
$this->breadcrumbs=array(
	Yii::t('app','Student Attentances')=>array('/courses'),
	$model->id,
);

$this->menu=array(
	array('label'=>Yii::t('app','List Student Attentance'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Student Attentance'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Update Student Attentance'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Delete Student Attentance'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('app','Are you sure you want to delete this item?'))),
	array('label'=>Yii::t('app','Manage Student Attentance'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','View Student Attentance'); ?> #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'student_id',
		'date',
		'reason',
	),
)); ?>
