<?php
$this->breadcrumbs=array(
	Yii::t('app','Room Details'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create RoomDetails'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage RoomDetails'), 'url'=>array('admin')),
);
?>

<h1>Room Details</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
