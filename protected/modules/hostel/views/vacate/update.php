<?php
$this->breadcrumbs=array(
	Yii::t('app','Vacate')=>array('/hostel'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List Vacate'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Vacate'), 'url'=>array('create')),
	array('label'=>Yii::t('app','View Vacate'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Manage Vacate'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Update Vacate');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>