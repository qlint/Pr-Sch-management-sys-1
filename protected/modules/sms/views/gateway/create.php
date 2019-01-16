<?php
$this->breadcrumbs=array(
	Yii::t('app', 'Notify')=>array('/notifications/default/sendmails'),
	Yii::t('app', 'Gateway'),
	Yii::t('app','Gateway Settings')
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top"><?php $this->renderPartial('/default/left_side');?></td>
    <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top" width="247"><div class="cont_right formWrapper">
              <h1><?php echo Yii::t('app','Gateway Settings'); ?></h1>
              <div class="button-bg">
                <div class="top-hed-btn-left"> </div>
                <div class="top-hed-btn-right">
                  <ul>
                    <li><?php echo CHtml::link('<span>'.Yii::t('app','Back').'</span>', array('index'),array('class'=>'a_tag-btn')); ?></li>
                  </ul>
                </div>
              </div>
              <?php echo $this->renderPartial('_form', array('model'=>$model,'parameter'=>$parameter)); ?> </div></td>
        </tr>
      </table></td>
  </tr>
</table>
