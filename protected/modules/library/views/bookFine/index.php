<?php
$this->breadcrumbs=array(
	Yii::t('app','Book Fines'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create BookFine'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage BookFine'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('app','Book Fines');?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
