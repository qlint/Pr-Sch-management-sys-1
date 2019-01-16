<?php
$this->breadcrumbs=array(
	Yii::t('app','Borrow Books'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create BorrowBook'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage BorrowBook'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Borrow Books');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
