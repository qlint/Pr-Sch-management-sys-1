<?php
$this->breadcrumbs=array(
	Yii::t('app','Book Fines')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List BookFine'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create BookFine'), 'url'=>array('create')),
	array('label'=>Yii::t('app','View BookFine'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Manage BookFine'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Update BookFine');?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>