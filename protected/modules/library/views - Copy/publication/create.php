<?php
$this->breadcrumbs=array(
	Yii::t('app','Publications')=>array('/library'),
	Yii::t('app','Create'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List Publication'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Manage Publication'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Create Publication');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>