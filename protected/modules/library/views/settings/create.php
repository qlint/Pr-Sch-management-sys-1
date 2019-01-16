<?php
$this->breadcrumbs=array(
	Yii::t('app','Settings')=>array('/library'),
	Yii::t('app','Create'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List Settings'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Manage Settings'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Create Settings');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>