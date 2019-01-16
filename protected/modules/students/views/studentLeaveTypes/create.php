<?php
$this->breadcrumbs=array(
	Yii::t('app','Student Leave Types')=>array('index'),
	Yii::t('app','Create'),
);

$this->menu=array(
	array('label'=>'List StudentLeaveTypes', 'url'=>array('index')),
	array('label'=>'Manage StudentLeaveTypes', 'url'=>array('admin')),
);
?>

<h1>Create StudentLeaveTypes</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>