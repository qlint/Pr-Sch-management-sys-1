<?php
$this->breadcrumbs=array(
	Yii::t('app','Food Infos')=>array('/hostel'),
	Yii::t('app','Manage'),
);
$this->menu=array(
	array('label'=>Yii::t('app','Create FoodInfo'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage FoodInfo'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Food Infos');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
