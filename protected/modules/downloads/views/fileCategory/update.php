<?php
$this->breadcrumbs=array(
	Yii::t('app','Downloads')=>array('/downloads'),
	Yii::t('app','File Category')=>array('admin'),
	Yii::t('app','Update'),	
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="80" valign="top" id="port-left">
        	<?php $this->renderPartial('/default/left_side');?>                    
        </td>
        <td valign="top">        	
            <div class="cont_right">            	                        
            	<h1><?php echo Yii::t('app','Update File Category'); ?></h1>
            	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
            </div>    
        </td>
    </tr>
</table>


