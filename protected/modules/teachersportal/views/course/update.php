<?php /*?><?php
$this->breadcrumbs=array(
	'Exam Groups'=>array('/examination'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);
?>
<?php */?>

<!--<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    
<?php //$this->renderPartial('/default/leftside');?>
    
    </td>
    <td valign="top">
    <div class="cont_right formWrapper">

<h1><?php //echo Yii::t('examination','Update ExamGroups');?></h1>-->

<?php echo $this->renderPartial('create', array('model'=>$model)); ?>
<!--</div>
    </td>
  </tr>
</table>-->