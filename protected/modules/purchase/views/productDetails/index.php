<?php
$this->breadcrumbs=array(
	'Product Details',
);

$this->menu=array(
	array('label'=>'Create ProductDetails', 'url'=>array('create')),
	array('label'=>'Manage ProductDetails', 'url'=>array('admin')),
);
?>

<h1>Product Details</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
