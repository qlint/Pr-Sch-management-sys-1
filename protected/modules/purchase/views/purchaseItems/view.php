<?php
$this->breadcrumbs=array(
	'Purchase Items'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List PurchaseItems', 'url'=>array('index')),
	array('label'=>'Create PurchaseItems', 'url'=>array('create')),
	array('label'=>'Update PurchaseItems', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete PurchaseItems', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage PurchaseItems', 'url'=>array('admin')),
);
?>

<h1>View PurchaseItems #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'item_code',
		'name',
	),
)); ?>
