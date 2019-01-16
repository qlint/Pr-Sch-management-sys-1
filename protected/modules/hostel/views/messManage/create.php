<?php
$this->breadcrumbs=array(
	Yii::t('app','Mess Manages')=>array('/hostel'),
	Yii::t('app','Create'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List Mess Manage'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Manage Mess Manage'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Create Mess Manage');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>