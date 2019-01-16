<?php
$this->breadcrumbs=array(
	Yii::t('app','Driver Details')=>array('/transport'),
	Yii::t('app','Manage'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List DriverDetails'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create DriverDetails'), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('driver-details-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

 <h1><?php echo Yii::t('app','Driver Details');?></h1> 
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
	'id'=>'driver-details-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'vehicle_id',
		'first_name',
		'last_name',
		'address',
		'dob',
		/*
		'age',
		'license_no',
		'expiry_date',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
