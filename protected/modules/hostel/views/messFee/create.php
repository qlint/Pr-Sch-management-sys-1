<?php
$this->breadcrumbs=array(
	Yii::t('app','Mess Fees')=>array('/hostel'),
	Yii::t('app','Create'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List Mess Fee'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Manage Mess Fee'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Create Mess Fee');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>