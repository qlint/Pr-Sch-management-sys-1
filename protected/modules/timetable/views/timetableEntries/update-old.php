<?php
$this->breadcrumbs=array(
	Yii::t('app','Timetable Entries')=>array('/timetable'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List TimetableEntries'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create TimetableEntries'), 'url'=>array('create')),
	array('label'=>Yii::t('app','View TimetableEntries'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Manage TimetableEntries'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Update TimetableEntries');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>