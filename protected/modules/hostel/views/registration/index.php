<?php
$this->breadcrumbs=array(
	Yii::t('app','Registrations')=>array('/hostel'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create Registration'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage Registration'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Registrations');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
