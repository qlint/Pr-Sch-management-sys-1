<?php
$this->breadcrumbs=array(
	Yii::t('app','Vacate'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create Vacate'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage Vacate'),'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Vacates');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
