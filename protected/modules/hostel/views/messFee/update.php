<?php
$this->breadcrumbs=array(
	Yii::t('app','Mess Fees')=>array('/hostel'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List Mess Fee'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Mess Fee'), 'url'=>array('create')),
	array('label'=>Yii::t('app','View Mess Fee'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Manage Mess Fee'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Update Mess Fee');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>