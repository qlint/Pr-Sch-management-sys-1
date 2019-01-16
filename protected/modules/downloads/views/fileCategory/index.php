<?php
$this->breadcrumbs=array(
	Yii::t('app','File Categories')
);

$this->menu=array(
	array('label'=>Yii::t('app','Create FileCategory'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage FileCategory'), 'url'=>array('admin')),
);
?>

<h1>File Categories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
