<?php
$this->breadcrumbs=array(
	Yii::t('app','Driver Details')=>array('/transport'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create DriverDetails'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage DriverDetails'), 'url'=>array('admin')),
);
?>

 <h1><?php echo Yii::t('app','Driver Details');?></h1> 
 <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
