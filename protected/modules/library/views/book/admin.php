<?php
$this->breadcrumbs=array(
	Yii::t('app','Books')=>array('/library'),
	Yii::t('app','Manage'),
);

//$this->menu=array(
//	array('label'=>'List Book', 'url'=>array('index')),
//	array('label'=>'Create Book', 'url'=>array('create')),
//);

/*Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('book-grid', {
		data: $(this).serialize()
	});
	return false;
});
");*/
?>

<h1><?php echo Yii::t('app','Manage Books');?></h1>
<?php 
echo CHtml::link(Yii::t('app','Add book details'),array('/book/create')).'&nbsp;&nbsp;'.CHtml::link(Yii::t('app','Search Book'),array('/book/booksearch')).'&nbsp;&nbsp;'.CHtml::link(Yii::t('app','Add Book Category'),array('/category/create')).'&nbsp;&nbsp;'.CHtml::link(Yii::t('app','Add Subjects'),array('/subjects/create'));
?>

<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'book-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'isbn',
		'title',
		'subject',
		'category',
		'author',
		'copy',
		/*
		'edition',
		'publisher',
		'copy',
		'date',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
