<?php
$this->breadcrumbs=array(
	Yii::t('app','Mess Manages')=>array('/hostel'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List Mess Manage'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Mess Manage'), 'url'=>array('create')),
	array('label'=>Yii::t('app','View Mess Manage'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Manage Mess Manage'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Update Mess Manage');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>