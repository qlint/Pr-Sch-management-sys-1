<?php
$this->breadcrumbs=array(
	Yii::t('app','Notify')=>array('/notifications/default/sendmail'),
	Yii::t('app','Contacts')=>array('index'),
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
    						<h1><?php echo Yii::t('app','View Contacts');?></h1>
							<?php $this->widget('zii.widgets.CDetailView', array(
								'data'=>$model,
								'attributes'=>array(
									'first_name',
									'last_name',
									'mobile',
									'email',
								),
							)); ?>
                            <?php echo CHtml::link(Yii::t('app','Edit'), array('/mailbox/contacts/update', 'id'=>$model->id));?>
                            <?php echo CHtml::link(Yii::t('app','All contacts'), array('/mailbox/contacts'));?>                            
                        </div>
                    </td>
                </tr>
            </tbody></table>
        </td>
    </tr>
</table>
