<?php
$this->breadcrumbs=array(
	Yii::t('app','Book Fines')=>array('index'),
	Yii::t('app','Create'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List BookFine'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Manage BookFine'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Create BookFine');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>