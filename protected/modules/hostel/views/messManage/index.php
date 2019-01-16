<?php
$this->breadcrumbs=array(
	Yii::t('app','Mess Manages')=>array('/hostel'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create Mess Manage'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage Mess Manage'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Mess Manages');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
