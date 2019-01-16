<?php
$this->breadcrumbs=array(
	Yii::t('app','Bus Logs')=>array('/transport'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create BusLog'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage BusLog'), 'url'=>array('admin')),
);
?>

 <h1><?php echo Yii::t('app','Bus Logs');?></h1> 

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
