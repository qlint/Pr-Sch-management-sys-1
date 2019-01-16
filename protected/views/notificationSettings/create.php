<?php
$this->breadcrumbs=array(
	Yii::t('app','Settings')=>array('/configurations'),
	Yii::t('app','Notification Settings')=>array('create'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    	<?php $this->renderPartial('/configurations/left_side');?>
    </td>
    <td valign="top">
    	<div class="cont_right formWrapper">
			<h1><?php echo Yii::t('app','Notification Settings');?></h1>
			<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
		<div>
    </td>
  </tr>
</table>