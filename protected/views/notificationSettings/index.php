<?php
$this->breadcrumbs=array(
	Yii::t('app','Notification Settings'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create NotificationSettings'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage NotificationSettings'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Notification Settings');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
