<?php
$this->breadcrumbs=array(
	'Material Requistions'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List MaterialRequistion', 'url'=>array('index')),
	array('label'=>'Create MaterialRequistion', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('material-requistion-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Material Requistions</h1>

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
	'id'=>'material-requistion-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'groupstaff_id',
		'pepartment_id',
		'material_id',
		'quantity',
		'status_hod',
		/*
		'status_pm',
		'is_issued',
		'is_deleted',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
