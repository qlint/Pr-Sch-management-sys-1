<?php
$this->breadcrumbs=array(
	Yii::t('app','Room Details')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List Room Details'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Room Details'), 'url'=>array('create')),
	array('label'=>Yii::t('app','View Room Details'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Manage Room Details'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Update Room Details');?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>