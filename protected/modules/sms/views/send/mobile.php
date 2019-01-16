<?php
$this->breadcrumbs=array(
	Yii::t('app', 'Sms')=>array('index'),
	Yii::t('Create')
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
                    	<?php echo Yii::t('app', 'Contents here');?>
                    </td>
                </tr>
            </tbody></table>
        </td>
    </tr>
</table>