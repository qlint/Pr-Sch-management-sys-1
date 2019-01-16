<?php
$this->breadcrumbs=array(
	Yii::t('app','Purchase')=>array('/purchase'),
	Yii::t('app','Purchase Order')=>array('/purchase/purchaseSupply/index'),
);


?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    	<?php $this->renderPartial('/default/leftside');?>
    </td>
    <td valign="top">
        <div class="cont_right formWrapper">
            <h1><?php echo Yii::t('app','New Purchase Order');?></h1>
            <div class="formCon">
					<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
                    <div class="formConInner"></div>
            </div>
        </div>
    </td>
  </tr>
</table>