<?php
$this->breadcrumbs=array(
	'Cbsc Exam Groups'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CbscExamGroups', 'url'=>array('index')),
	array('label'=>'Manage CbscExamGroups', 'url'=>array('admin')),
);
?>

<h1>Create CbscExamGroups</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>