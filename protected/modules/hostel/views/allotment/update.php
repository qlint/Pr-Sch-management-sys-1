<?php
$this->breadcrumbs=array(
	Yii::t('app','Allotments')=>array('/hostel'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List Allotment'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Allotment'), 'url'=>array('create')),
	array('label'=>Yii::t('app','View Allotment'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Manage Allotment'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Update Allotment');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>