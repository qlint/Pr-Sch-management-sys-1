<?php
$this->breadcrumbs=array(
	UserModule::t('Profile Fields')=>array('admin'),
	UserModule::t('Create'),
);

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
<div id="othleft-sidebar">
<?php $this->renderPartial('//configurations/left_side');?>
  </div>
 </td>
 <td valign="top">
<div class="cont_right formWrapper">
<h1><?php echo UserModule::t('Create Profile Field'); ?></h1>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
 </td>
  </tr>
</table>