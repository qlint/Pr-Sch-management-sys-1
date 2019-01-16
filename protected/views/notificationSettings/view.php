<?php
$this->breadcrumbs=array(
	Yii::t('app','Notification Settings')=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>Yii::t('app','List NotificationSettings'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create NotificationSettings'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Update NotificationSettings'), 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Delete NotificationSettings'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('app','Are you sure you want to delete this item?'))),
	array('label'=>Yii::t('app','Manage NotificationSettings'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','View NotificationSettings').' #';?><?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'settings_key',
		'sms_enabled',
		'mail_enabled',
		'msg_enabled',
	),
)); ?>
