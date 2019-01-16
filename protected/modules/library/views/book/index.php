<?php
$this->breadcrumbs=array(
	Yii::t('app','Books'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create Book'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage Book'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Books');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
