<?php
$this->breadcrumbs=array(
	Yii::t('app','Borrow Books')=>array('index'),
	Yii::t('app','Manage'),
);

$this->menu=array(
	array('label'=>Yii::t('app','List BorrowBook'), 'url'=>array('index')),
	array('label'=>Yii::t('app','Create BorrowBook'), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('borrow-book-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo Yii::t('app','Manage Borrow Books');?></h1>

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
	'id'=>'borrow-book-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'student_id',
		'book_id',
		'issue_date',
		'created',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
