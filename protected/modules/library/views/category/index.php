<?php
$this->breadcrumbs=array(
	Yii::t('app','Categories'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create Category'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage Category'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Categories');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
