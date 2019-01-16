<?php
$this->breadcrumbs=array(
	'Vendor Details'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List VendorDetails', 'url'=>array('index')),
	array('label'=>'Create VendorDetails', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('vendor-details-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Vendor Details</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'vendor-details-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'first_name',
		'last_name',
		'address_1',
		'address_2',
		'city',
		/*
		'state',
		'country_id',
		'email',
		'phone',
		'payment_term',
		'currency',
		'company_name',
		'vat_number',
		'cst_number',
		'office_phone',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
