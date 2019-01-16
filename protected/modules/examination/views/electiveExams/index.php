<?php
$this->breadcrumbs=array(
	Yii::t('app','Elective Exams'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create ElectiveExams'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage ElectiveExams'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Elective Exams'); ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
