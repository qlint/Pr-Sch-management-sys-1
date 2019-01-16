<?php
$this->breadcrumbs=array(
	Yii::t('app','Room Requests'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create Room Request'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage Room Request'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Room Requests');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
