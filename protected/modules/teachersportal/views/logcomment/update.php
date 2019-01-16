<?php
$this->breadcrumbs=array(
	'Log Comments'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List LogComment', 'url'=>array('index')),
	array('label'=>'Create LogComment', 'url'=>array('create')),
	array('label'=>'View LogComment', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage LogComment', 'url'=>array('admin')),
);
?>

<h1>Update LogComment <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>