<?php
$this->breadcrumbs=array(
	Yii::t('app','Fuel Consumptions')=>array('/transport'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create FuelConsumption'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage FuelConsumption'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','FuelConsumption');?></h1>


<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
