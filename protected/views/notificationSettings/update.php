<?php
$this->breadcrumbs=array(
	Yii::t('app','Notification Settings')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List NotificationSettings'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create NotificationSettings'), 'url'=>array('create')),
	array('label'=>Yii::t('app','View NotificationSettings'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Manage NotificationSettings'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Update NotificationSettings');?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>