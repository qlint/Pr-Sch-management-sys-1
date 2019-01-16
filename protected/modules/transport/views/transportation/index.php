<?php
$this->breadcrumbs=array(
	Yii::t('app','Transportations')=>array('/transport'),
);

$this->menu=array(
	array('label'=>Yii::t('app','Create Transportation'), 'url'=>array('create')),
	array('label'=>Yii::t('app','Manage Transportation'), 'url'=>array('admin')),
);
?>


     <h1><?php echo Yii::t('app','Transportations');?></h1>



<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
