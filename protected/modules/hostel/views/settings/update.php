<?php
$this->breadcrumbs=array(
	Yii::t('app','Settings')=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List Settings'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Settings'), 'url'=>array('create')),
	array('label'=>Yii::t('app','View Settings'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Manage Settings'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Update Settings');?> </h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>