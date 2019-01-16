<div id="othleft-sidebar">
       
        <?php
		$roles = Rights::getAssignedRoles(Yii::app()->user->Id);
		
        $this->widget('zii.widgets.CMenu',array(
			'encodeLabel'=>false,
			'activateItems'=>true,
			'activeCssClass'=>'list_active',
			'items'=>array(
			
				array('label'=>''.'<h1>'.Yii::t('app', 'Purchase').'</h1>',
				'active'=> ((Yii::app()->controller->module->id=='Purchase') ? true : false)),
				
				array('label'=>Yii::t('app', 'Manage Vendors').'<span>'.Yii::t('app', 'Create and Manage Vendors').'</span>', 'url'=>array('vendorDetails/index'),
				'active'=> ((Yii::app()->controller->id=='default' or Yii::app()->controller->id=='vendorDetails' or Yii::app()->controller->id=='productDetails') ? true : false),'linkOptions'=>array('class'=>'purcs_ico'), 'visible'=>key($roles) == 'Admin' or key($roles) == 'pm'),	
				
				array('label'=>Yii::t('app', 'Manage Items').'<span>'.Yii::t('app', 'Create and Manage Items').'</span>', 'url'=>array('purchaseItems/admin'),
				'active'=> ((Yii::app()->controller->id=='purchaseItems' and Yii::app()->controller->action->id=='admin' or Yii::app()->controller->id=='purchaseItems' and Yii::app()->controller->action->id=='create' or Yii::app()->controller->id=='purchaseItems' and Yii::app()->controller->action->id=='update') ? true : false),'linkOptions'=>array('class'=>'purcsitem-stock_ico'),'visible'=>key($roles) == 'Admin' or key($roles) == 'pm'),	
				
				array('label'=>Yii::t('app', 'Material Requisition').'<span>'.Yii::t('app', 'Request for a Material').'</span>', 'url'=>array('materialRequistion/index'),
				'active'=> ((Yii::app()->controller->id=='materialRequistion' and Yii::app()->controller->action->id!='issue') ? true : false),'linkOptions'=>array('class'=>'purcs-reqst_ico')),
				
				array('label'=>Yii::t('app', 'Manage Stock').'<span>'.Yii::t('app', 'Manage Stock').'</span>', 'url'=>array('purchaseItems/stock'),
				'active'=> ((Yii::app()->controller->id=='purchaseItems' and Yii::app()->controller->action->id=='stock' or Yii::app()->controller->id=='purchaseItems' and Yii::app()->controller->action->id=='issue' or Yii::app()->controller->id=='purchaseItems' and Yii::app()->controller->action->id=='vendor') ? true : false),'linkOptions'=>array('class'=>'hr-report-icon'),'visible'=>key($roles) == 'Admin' or key($roles) == 'pm'),
				
				array('label'=>Yii::t('app', 'Issue Item').'<span>'.Yii::t('app', 'Issue the approved items').'</span>', 'url'=>array('materialRequistion/issue'),
				'active'=> ((Yii::app()->controller->id=='materialRequistion' and Yii::app()->controller->action->id=='issue' or Yii::app()->controller->id=='materialRequistion' and Yii::app()->controller->action->id=='cancelissue') ? true : false),'linkOptions'=>array('class'=>'purcs-issue_ico'), 'visible'=>key($roles) == 'Admin' or key($roles) == 'pm'),	
				
				array('label'=>Yii::t('app', 'Purchase Order').'<span>'.Yii::t('app', 'Send Purchase Orders').'</span>', 'url'=>array('purchaseSupply/index'),
				'active'=> ((Yii::app()->controller->id=='purchaseSupply' and ( Yii::app()->controller->action->id=='index' or Yii::app()->controller->action->id=='create' )) ? true : false),'linkOptions'=>array('class'=>'purcs-supply_ico'),'visible'=>key($roles) == 'Admin' or key($roles) == 'pm'),	
				
				array('label'=>Yii::t('app', 'Purchase Supply').'<span>'.Yii::t('app', 'Send Purchase Supply Order').'</span>', 'url'=>array('purchaseSupply/supply'),
				'active'=> ((Yii::app()->controller->id=='purchaseSupply' and Yii::app()->controller->action->id=='supply') ? true : false),'linkOptions'=>array('class'=>'hr-salry-dtls-icon'),'visible'=>key($roles) == 'Admin' or key($roles) == 'pm'),	
				
				array('label'=>'<h1>'.Yii::t('app', 'Sale').'</h1>', 'visible'=> 1 or key($roles) == 'pm'),
				
				array('label'=>Yii::t('app', 'Manage Sale').'<span>'.Yii::t('app', 'Create and Manage Sale').'</span>', 'url'=>array('sale/manage'),
				'active'=> ((Yii::app()->controller->id=='sale' and Yii::app()->controller->action->id!='saleReport') ? true : false),'linkOptions'=>array('class'=>'manage-sale_ico'), 'visible'=>1 or key($roles) == 'pm'),
				array('label'=>Yii::t('app', 'Sale Report').'<span>'.Yii::t('app', 'Manage Sale Report').'</span>', 'url'=>array('sale/saleReport'),
				'active'=> ((Yii::app()->controller->id=='sale' and Yii::app()->controller->action->id=='saleReport') ? true : false),'linkOptions'=>array('class'=>'sale-report_ico'), 'visible'=>1 or key($roles) == 'pm'),
									
			),

        )); ?>
        
	</div>
	
	
