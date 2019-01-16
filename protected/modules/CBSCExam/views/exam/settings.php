<?php
$this->breadcrumbs=array(
	Yii::t('app','Exam Grade Settings')=>array('/examination'),
	Yii::t('app','Create'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <?php $this->renderPartial('examination.views.default.left_side');?>    
    
    </td>
    <td valign="top">
    <div class="cont_right formWrapper">

<h1><?php echo Yii::t('app','Create CBSE Exam Settings');?></h1><br />

<?php echo $this->renderPartial('settings_form', array('model'=>$model)); ?>
</div>
    </td>
  </tr>
</table>