<?php
$this->breadcrumbs=array(
	'Exam Scores'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ExamScores', 'url'=>array('index')),
	array('label'=>'Manage ExamScores', 'url'=>array('admin')),
);
?>

<h1>Create ExamScores</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>