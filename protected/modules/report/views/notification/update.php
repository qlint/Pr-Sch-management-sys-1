<?php
$this->breadcrumbs=array(
	Yii::t('app','Report'),
	Yii::t('app','Notification'),
	Yii::t('app','Update')
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top" id="port-left">    
        	<?php $this->renderPartial('/default/left_side');?>    
        </td>
        <td valign="top">  
             
        	<table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody><tr>
                    <td width="75%" valign="top">
                    	<div style="padding-left:20px;" class="sms-block">
    						<h1><?php echo Yii::t('app','Update Email Template');?></h1>
							<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
                            
                            <div class="clear"></div>
                                                        
                        </div>
                    </td>
                </tr>
            </tbody></table>
        </td>
    </tr>
</table>