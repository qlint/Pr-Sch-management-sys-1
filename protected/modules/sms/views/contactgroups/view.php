<?php
$this->breadcrumbs=array(
	Yii::t('app','Notify')=>array('/notifications/default/sendmail'),
	Yii::t('app','Contact Groups')=>array('index'),
	Yii::t('app','view')
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top" id="port-left">    
        	<?php $this->renderPartial('/default/left_side');?>    
        </td>
        <td valign="top">
        <div class="cont_right formWrapper">          
        	<table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody><tr>
                    <td width="75%" valign="top">
                    	<div class="sms-block">
    						<h1><?php echo Yii::t('app','View Contact Group');?></h1>
							<?php $this->widget('zii.widgets.CDetailView', array(
								'data'=>$model,
								'attributes'=>array(
									//'id',
									'group_name',									
									array(
										'label'=>'Created By',
										'type'=>'raw',
										'value'=>$model->createdby
									),
									'created_at',
									//'status',
								),
							)); ?>
                            
                            <?php echo CHtml::link(Yii::t('app','Edit'), array('/sms/contactgroups/update', 'id'=>$model->id));?>
                            <?php echo CHtml::link(Yii::t('app','View All'), array('/sms/contactgroups'));?>                            
                        </div>
                    </td>
                </tr>
            </tbody></table>
            </div>
        </td>
    </tr>
</table>
