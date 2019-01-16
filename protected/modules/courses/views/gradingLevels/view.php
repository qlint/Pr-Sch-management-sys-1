<?php
$this->breadcrumbs=array(
	Yii::t('app','Grading Levels')=>array('/courses'),
	$model->name,
);

/**$this->menu=array(
	array('label'=>'List ', 'url'=>array('index')),
	array('label'=>'Create ', 'url'=>array('create')),
	array('label'=>'Update ', 'url'=>array('update', 'id'=>$model->)),
	array('label'=>'Delete ', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ', 'url'=>array('admin')),
);*/
?>

<h1><?php echo Yii::t('app','View Grading Level');?></h1>

<?php /*?><?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'batch_id',
		'min_score',
		'order',
		'is_deleted',
		'created_at',
		'updated_at',
	),
)); ?><?php */?>
<div class="tableinnerlist" style="padding-right:25px;">
<table width="100%" border="0" cellspacing="1" cellpadding="0">
  <tr>
    <td><?php echo Yii::t('app','Name');?></td>
    <td><?php echo $model->name; ?></td>
  </tr>
    <tr>
    <td><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name');?></td>
    <td><?php
    $posts=Batches::model()->findByAttributes(array('id'=>$model->batch_id));
	echo $posts->name;
	?></td>
  </tr>
    <tr>
    <td><?php echo Yii::t('app','Min Score');?></td>
    <td><?php echo $model->min_score; ?></td>
  </tr>
      <?php /*?><tr>
    <td><?php echo Yii::t('app','Order');?></td>
    <td><?php echo $model->order; ?></td>
  </tr><?php */?>
</table>
</div>
