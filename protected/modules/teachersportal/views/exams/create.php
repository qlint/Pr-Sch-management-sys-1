<?php
$this->breadcrumbs=array(
	'Exams'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Exams', 'url'=>array('index')),
	array('label'=>'Manage Exams', 'url'=>array('admin')),
);
?>

<h1>Create Exams</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>