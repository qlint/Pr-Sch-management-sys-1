<?php
$this->breadcrumbs=array(
	'Student Portal Themes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List StudentPortalTheme', 'url'=>array('index')),
	array('label'=>'Manage StudentPortalTheme', 'url'=>array('admin')),
);
?>

<h1>Create StudentPortalTheme</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>