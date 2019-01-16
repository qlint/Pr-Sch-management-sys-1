<?php
$this->breadcrumbs=array(
	Yii::t('app','Notify')=>array('/notifications/default/sendmail'),
	Yii::t('app','SMS Templates'),
);
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top" id="port-left">    
        	<?php $this->renderPartial('/default/left_side');?>    
        </td>
        <td valign="top">
        <div class="cont_right"> 
          <h1><?php echo Yii::t('app','SMS Template');?></h1> 
          <?php $this->renderPartial('_tab');?> 
 			<div class="sms-block grid-table">			
				<?php $this->widget('zii.widgets.CDetailView', array(
                    'data'=>$model,
                    'attributes'=>array(
                        'id',
                        'name',
                        'template',
                        //'created_at',
                        array('name'=>'create_at','label'=>Yii::t('app', 'Created At')),
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
                    
            </div>
        </td>
    </tr>
</table>