<?php
$this->breadcrumbs=array(
	'Cbsc Exam Groups'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CbscExamGroups', 'url'=>array('index')),
	array('label'=>'Create CbscExamGroups', 'url'=>array('create')),
	array('label'=>'View CbscExamGroups', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CbscExamGroups', 'url'=>array('admin')),
);
?>

<h1>Update CbscExamGroups <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>