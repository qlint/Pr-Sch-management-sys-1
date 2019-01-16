<?php
$this->breadcrumbs=array(
	Yii::t('app','Elective Exams')=>array('index'),
	Yii::t('app','Create'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List ElectiveExams'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Manage ElectiveExams'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Create ElectiveExams'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>