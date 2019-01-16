<?php
$this->breadcrumbs=array(
	Yii::t('app','Bus Logs')=>array('/transport'),
	Yii::t('app','Manage'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List BusLog'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create BusLog'), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('bus-log-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

 <h1><?php echo Yii::t('app','Manage Bus Log');?></h1>
<p>
<?php echo Yii::t('app','You may optionally enter a comparison operator').' (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) '.Yii::t('app','at the beginning of each of your search values to specify how the comparison should be done.');?>
</p>

<?php echo CHtml::link(Yii::t('app','Advanced Search'),'#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'bus-log-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'vehicle_id',
		'start_time_reading',
		'end_time_reading',
		'fuel_consumption',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
