<?php
$this->breadcrumbs=array(
	Yii::t('app','Timetable Entries')=>array('/timetable'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create TimetableEntries'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage TimetableEntries'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Timetable Entries');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
