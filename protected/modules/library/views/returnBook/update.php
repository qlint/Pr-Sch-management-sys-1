<?php
$this->breadcrumbs=array(
	Yii::t('app','Return Books')=>array('/library'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List ReturnBook'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create ReturnBook'), 'url'=>array('create')),
	array('label'=>Yii::t('app','View ReturnBook'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Manage ReturnBook'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Update ReturnBook'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>