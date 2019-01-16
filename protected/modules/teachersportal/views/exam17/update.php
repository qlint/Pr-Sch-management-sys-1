<?php
$this->breadcrumbs=array(
	'Exam Scores'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ExamScores', 'url'=>array('index')),
	array('label'=>'Create ExamScores', 'url'=>array('create')),
	array('label'=>'View ExamScores', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ExamScores', 'url'=>array('admin')),
);
?>

<?php /*?><h1>Update ExamScores</h1><?php */?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>