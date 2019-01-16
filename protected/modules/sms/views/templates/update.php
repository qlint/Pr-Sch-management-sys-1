<?php
$this->breadcrumbs=array(
	Yii::t('app','Notify')=>array('/notifications/default/sendmail'),
	Yii::t('app','SMS Templates'),
	Yii::t('app','Update')
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top" id="port-left"><?php $this->renderPartial('/default/left_side');?></td>
    <td valign="top"><div class="cont_right formWrapper">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody>
            <tr>
              <td width="75%" valign="top">
              <div class="sms-block">
                  <h1><?php echo Yii::t('app','Update SMS Template');?></h1>
                  <div class="button-bg">
                    <div class="top-hed-btn-left"> </div>
                    <div class="top-hed-btn-right">
                      <ul>
                        <li> <?php echo CHtml::link('<span>'.Yii::t('app','All Templates').'</span>', array('index'), array('class'=>'a_tag-btn'));?> </li>
                        <li> <?php echo CHtml::link('<span>'.Yii::t('app','Create Template').'</span>', array('create'), array('class'=>'a_tag-btn'));?> </li>
                      </ul>
                    </div>
                  </div>
                  <?php $this->renderPartial('_tab');?>
                  <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
                  <div class="clear"></div>
                </div></td>
            </tr>
          </tbody>
        </table>
      </div></td>
  </tr>
</table>
