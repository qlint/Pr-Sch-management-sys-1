<?php
$this->breadcrumbs=array(
	'Teacher Attendances'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List TeacherAttendances', 'url'=>array('index')),
	array('label'=>'Create TeacherAttendances', 'url'=>array('create')),
	array('label'=>'Update TeacherAttendances', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete TeacherAttendances', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TeacherAttendances', 'url'=>array('admin')),
);
?>

<h1>View TeacherAttendances #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'attendance_date',
		'employee_id',
		'employee_leave_type_id',
		'reason',
		'is_half_day',
	),
)); ?>
