<?php
$this->breadcrumbs=array(
	Yii::t('app','Publications')=>array('/library'),
	$model->name=>array('view','id'=>$model->publication_id),
	Yii::t('app','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List Publication'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create Publication'), 'url'=>array('create')),
	array('label'=>Yii::t('app','View Publication'), 'url'=>array('view', 'id'=>$model->publication_id)),
	array('label'=>Yii::t('app','Manage Publication'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Update Publication');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>