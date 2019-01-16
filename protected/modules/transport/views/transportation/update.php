<?php
$this->breadcrumbs=array(
	Yii::t('app','Transportations')=>array('/transport'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List Transportation'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Transportation'), 'url'=>array('create')),
	array('label'=>Yii::t('app','View Transportation'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Manage Transportation'), 'url'=>array('admin')),
);
?>
 <h1><?php echo Yii::t('app','Update Transportation');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>