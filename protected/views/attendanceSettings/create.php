<?php
$this->breadcrumbs=array(
	'Attendance Settings'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AttendanceSettings', 'url'=>array('index')),
	array('label'=>'Manage AttendanceSettings', 'url'=>array('admin')),
);
?>

<h1>Create AttendanceSettings</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>