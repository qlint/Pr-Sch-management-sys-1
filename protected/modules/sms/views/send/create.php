<?php
$this->breadcrumbs=array(
	Yii::t('app', 'Sms')=>array('index'),
	Yii::t('Create'),
);
?>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top" id="port-left">
    
     <?php $this->renderPartial('/default/left_side');?>
    
    </td>
    <td valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top" width="75%">
        <div class="cont_right formWrapper" style="padding:0px; width:753px;">

        <h1><?php echo Yii::t('app', 'Create Sms');?></h1>
        
        <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
        
        </div>
         </td>
        
      </tr>
    </table>
   
    </td>
  </tr>
</table>