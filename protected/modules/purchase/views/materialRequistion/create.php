<?php
$this->breadcrumbs=array(
	Yii::t('app','Purchase')=>array('/purchase/materialRequistion/index'),
	Yii::t('app','Material Requests')=>array('/purchase/materialRequistion/index'),
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
            <h1><?php echo Yii::t('app','Request Material');?></h1>
            <div class="formCon">
					<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
                    <div class="formConInner"></div>
            </div>
        </div>
    </td>
  </tr>
</table>
