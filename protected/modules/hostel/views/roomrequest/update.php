<?php
$this->breadcrumbs=array(
	Yii::t('app','Roomrequests')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List Room Request'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Room Request'), 'url'=>array('create')),
	array('label'=>Yii::t('app','View Room Request'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Manage Room Request'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Update Room Request');?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>