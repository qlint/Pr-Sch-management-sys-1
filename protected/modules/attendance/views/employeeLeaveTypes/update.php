<?php
$this->breadcrumbs=array(
Yii::t('app','Attendances')=>array('/attendance'),
	Yii::t('app','Teacher Leave Types')=>array('index'),
	Yii::t('app','Update'),
);


?>
<div style="background:#fff; min-height:800px;">  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  
    <td valign="top">
    <div style="padding:20px; position:relative;" >
    <h1><?php echo Yii::t('app','Update Teacher Leave Types');?></h1>
    
    <?php $this->renderPartial('/default/employee_tab');?>
    
  

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
</div>
    </td>
  </tr>
</table>
</div>