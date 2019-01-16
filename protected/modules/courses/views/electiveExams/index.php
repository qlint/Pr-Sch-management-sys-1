<?php
$this->breadcrumbs=array(
	'Elective Exams',
);

$this->menu=array(
	array('label'=>'Create ElectiveExams', 'url'=>array('create')),
	array('label'=>'Manage ElectiveExams', 'url'=>array('admin')),
);
?>

<h1>Elective Exams</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
