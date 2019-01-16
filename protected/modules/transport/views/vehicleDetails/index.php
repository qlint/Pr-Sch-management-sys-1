<?php
$this->breadcrumbs=array(
	Yii::t('app','Vehicle Details')=>array('/transport'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create VehicleDetails'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage VehicleDetails'), 'url'=>array('admin')),
);
?>

         <h1><?php echo Yii::t('app','Vehicle Details');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
