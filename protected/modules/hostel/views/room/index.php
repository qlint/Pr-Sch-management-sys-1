<?php
$this->breadcrumbs=array(
	Yii::t('app','Rooms'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create Room'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage Room'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Rooms');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
