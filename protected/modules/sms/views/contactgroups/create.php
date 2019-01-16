<?php
$this->breadcrumbs=array(
	Yii::t('app','SMS'),
	Yii::t('app','Contact Groups')=>array('index'),
	Yii::t('app','Create')
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
    						<h1><?php echo Yii::t('app','Create Contact Group');?></h1>
                            <div style="position:relative;">
                                <div class="contrht_bttns" style="right:9px;">
                                    <ul>
                                        <li>
                                            <?php echo CHtml::link('<span>'.Yii::t('app','All Groups').'</span>', array('/sms/contactgroups'));?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div style="90%" class="formCon">
                            <div class="formConInner">
                            
                            <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
                            
                            </div>
                            </div>
                            
                            
                        </div>
                    </td>
                </tr>
            </tbody></table>
        </td>
    </tr>
</table>