<?php
$this->breadcrumbs=array(
	Yii::t('app','Room Requests')=>array('index'),
	Yii::t('app','Create'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List Room Request'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Manage Room Request'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Create Room Request');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>