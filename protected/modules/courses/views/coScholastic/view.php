<style type="text/css">
.fancybox-outer h1{ font-size:19px;}

.tableinnerlist td{ text-align:left;}
</style>

<?php
$this->breadcrumbs=array(
	Yii::t('app','Co-Scholastic Skill')=>array('/courses'),
	$model->skill,
);

/**$this->menu=array(
	array('label'=>'List ', 'url'=>array('index')),
	array('label'=>'Create ', 'url'=>array('create')),
	array('label'=>'Update ', 'url'=>array('update', 'id'=>$model->)),
	array('label'=>'Delete ', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ', 'url'=>array('admin')),
);*/
?>

<h1><?php echo Yii::t('app','View Co-Scholastic Skill Details');?></h1>

<div class="tableinnerlist" style="padding-right:15px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo Yii::t('app','Skill');?></td>
    <td><?php echo $model->skill; ?></td>
  </tr>
    <tr>
    <td><?php echo Yii::t('app','Description');?></td>
    <td><?php echo $model->description; ?></td>
  </tr>        
</table>
</div>