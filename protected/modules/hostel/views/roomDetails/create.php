<?php
$this->breadcrumbs=array(
	Yii::t('app','Room Details')=>array('index'),
	Yii::t('app','Create'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List Room Details'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Manage Room Details'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Create Room Details');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>