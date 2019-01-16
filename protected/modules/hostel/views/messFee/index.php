<?php
$this->breadcrumbs=array(
	Yii::t('app','Mess Fees')=>array('/hostel'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create Mess Fee'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage Mess Fee'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Mess Fees');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
