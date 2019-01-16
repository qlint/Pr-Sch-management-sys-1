<?php
$this->breadcrumbs=array(
	Yii::t('app','SMS'),
	Yii::t('app','Templates')=>array('index'),
	Yii::t('app','Create'),
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
    						<h1><?php echo Yii::t('app','Create SMS Template');?></h1>
							<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>                            
                            
                            <div class="clear"></div>
                            <div style="position:relative;">
                                <div class="contrht_bttns" style="left:3px; top:4px;">
                                    <ul>
                                        <li>
											<?php echo CHtml::link('<span>'.Yii::t('app','All Templates').'</span>', array('index'));?>
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