
<?php
$this->breadcrumbs=array(
	Yii::t('app','Vendor Details')=>array('/purchase'),
	Yii::t('app','Create'),
);


?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    	<?php $this->renderPartial('/default/leftside');?>
    </td>
    <td valign="top">
        <div class="cont_right formWrapper">
            <h1><?php echo Yii::t('app','New Vendor');?></h1>
			 <?php	echo $this->renderPartial('_form', array('model'=>$model)); ?>
        </div>
    </td>
  </tr>
</table>