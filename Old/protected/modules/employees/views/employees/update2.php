<?php
$this->breadcrumbs=array(
	Yii::t('app','Teachers')=>array('index'),
	//$model->id=>array('view','id'=>$model->id),
	Yii::t('app','Update'),
);


?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    
      <?php $this->renderPartial('/employees/profileleft');?>
    
    </td>
    <td valign="top">
    <div class="cont_right formWrapper">
<h1><?php echo Yii::t('app','Enrolment ');?></h1>

<?php echo $this->renderPartial('_form1', array('model'=>$model)); ?>
</div>
    </td>
  </tr>
</table>