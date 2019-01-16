<?php
$this->breadcrumbs=array(
	Yii::t('app','Registrations')=>array('/hostel'),
	$model->id,
);

?>

<h1><?php echo Yii::t('app','View Registration');?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'student_id',
		'food_preference',
		'desc',
	),
)); ?>
