<?php
$this->breadcrumbs=array(
	Yii::t('app','Sms Counts')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List SmsCount'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create SmsCount'), 'url'=>array('create')),
	array('label'=>Yii::t('app','View SmsCount'), 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>Yii::t('app','Manage SmsCount'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Update SmsCount');?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>