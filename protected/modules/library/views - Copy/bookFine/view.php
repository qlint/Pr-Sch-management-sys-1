<?php
$this->breadcrumbs=array(
	Yii::t('app','Book Fines')=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>Yii::t('app','List BookFine'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create BookFine'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Update BookFine'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Delete BookFine'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('app','Are you sure you want to delete this item?'))),
	array('label'=>Yii::t('app','Manage BookFine'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','View BookFine').' #';?><?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'student_id',
		'book_id',
		'amount',
	),
)); ?>
