<?php
$this->breadcrumbs=array(
	Yii::t('app','Notify')=>array('/notifications/default/sendmail'),
	Yii::t('app','System Generated Templates')=>array('index'),
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top" id="port-left">    
        	<?php $this->renderPartial('/default/left_side');?>    
        </td>
        <td valign="top"> 
        <?php $this->renderPartial('_tab');?>       
        	<table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody><tr>
                    <td width="75%" valign="top">
                    	<div class="sms-block cont_right">
    						<h1><?php echo Yii::t('app','System Generated Template');?></h1>
							<?php $this->widget('zii.widgets.CDetailView', array(
								'data'=>$model,
								'attributes'=>array(
									'id',									
									'template',
									array('name'=>'edited_at','label'=>Yii::t('app', 'Edited At')),
								),
							)); ?>
                            
                            <div class="clear"></div>
                            <div style="position:relative;">
                            <div class="contrht_bttns" style="left:3px; top:4px;">
								<ul>
                                <li>
								<?php echo CHtml::link('<span>'.Yii::t('app','Edit').'</span>', array('update', 'id'=>$model->id));?>                            
                            </li>
                            </ul>
                            </div>
                            </div>
                                                      
                        </div>
                    </td>
                </tr>
            </tbody></table>
        </td>
    </tr>
</table>