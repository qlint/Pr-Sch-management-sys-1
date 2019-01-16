<?php
$this->breadcrumbs=array(
	Yii::t('app','Floors')=>array('/hostel'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List Floor'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Floor'), 'url'=>array('create')),
	array('label'=>Yii::t('app','View Floor'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Manage Floor'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Update Floor ');?>/h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>