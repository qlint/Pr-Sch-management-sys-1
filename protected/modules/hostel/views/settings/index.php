<?php
$this->breadcrumbs=array(
	Yii::t('app','Settings'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create Settings'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage Settings'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Settings');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
