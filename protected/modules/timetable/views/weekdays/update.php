<?php
$this->breadcrumbs=array(
	Yii::t('app','Weekdays')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List Weekdays'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Weekdays'), 'url'=>array('create')),
	array('label'=>Yii::t('app','View Weekdays'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Manage Weekdays'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Update Weekdays');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>