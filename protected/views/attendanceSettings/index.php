<?php
$this->breadcrumbs=array(
	'Attendance Settings',
);

$this->menu=array(
	array('label'=>'Create AttendanceSettings', 'url'=>array('create')),
	array('label'=>'Manage AttendanceSettings', 'url'=>array('admin')),
);
?>

<h1>Attendance Settings</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
