<div id="othleft-sidebar">
<h1><?php echo Yii::t('app','Export');?></h1>          
<?php
	$this->widget('zii.widgets.CMenu',array(
		'encodeLabel'=>false,
		'activateItems'=>true,
		'activeCssClass'=>'list_active',
		'items'=>array(
			array(
				'label'			=> Yii::t('app','Export').'<span>'.Yii::t('app','Export the database').'</span>',
				'url'			=> array('/export'),
				'linkOptions'	=> array('class'=>'export_ico'),
				'active'		=> ((Yii::app()->controller->module->id=='export')) ? true : false
			),				  
		),
	));
?>
</div>	