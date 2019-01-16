<?php
$this->breadcrumbs=array(
	'Log Comments'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List LogComment', 'url'=>array('index')),
	array('label'=>'Manage LogComment', 'url'=>array('admin')),
);
?>

<h1>Create LogComment</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>