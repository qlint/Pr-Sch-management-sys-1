<?php
$this->breadcrumbs=array(
	'Exams'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Exams', 'url'=>array('index')),
	array('label'=>'Create Exams', 'url'=>array('create')),
	array('label'=>'View Exams', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Exams', 'url'=>array('admin')),
);
?>

<h1>Update Exams <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>