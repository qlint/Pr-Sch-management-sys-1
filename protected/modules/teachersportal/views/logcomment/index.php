<?php
$this->breadcrumbs=array(
	'Log Comments',
);

$this->menu=array(
	array('label'=>'Create LogComment', 'url'=>array('create')),
	array('label'=>'Manage LogComment', 'url'=>array('admin')),
);
?>

<h1>Log Comments</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
