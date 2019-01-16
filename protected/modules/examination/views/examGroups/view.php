<?php
$this->breadcrumbs=array(
	'Exam Groups'=>array('/examination'),
	$model->name,
);

$this->menu=array(
	array('label'=>Yii::t('examination','List ExamGroups'), 'url'=>array('index')),
	array('label'=>Yii::t('examination','Create ExamGroups'), 'url'=>array('create')),
	array('label'=>Yii::t('examination','Update ExamGroups'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('examination','Delete ExamGroups'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>Yii::t('examination','Manage ExamGroups'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('examination','View ExamGroups');?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'batch_id',
		'exam_type',
		'is_published',
		'result_published',
		'exam_date',
	),
)); ?>
