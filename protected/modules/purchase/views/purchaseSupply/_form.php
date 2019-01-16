<div class="form">
<div class="formConInner">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'purchase-items-form',
	'enableAjaxValidation'=>false,
)); ?>

<p class="note"><?php echo Yii::t('app','Fields with'); ?><span class="required">*</span> <?php echo Yii::t('app','are required.') ;?></p>

	<?php /*?><?php echo $form->errorSummary($model); ?><?php */?>
 <div class="txtfld-col-box">
<div class="txtfld-col">
<?php echo $form->labelEx($model,'item_id'); ?>
<?php echo $form->dropDownList($model,'item_id',CHtml::listData(PurchaseItems::model()->findAll(),'id','name'),array('options' => array($model->item_id=>array('selected'=>true)),'empty'=>Yii::t('app','Select Item'),'encode' => false,'ajax'=>array(
									'type'=>'POST', 
									'url'=>Yii::app()->createUrl('purchase/purchaseSupply/vendorNames'),
									'success' => 'function(data){										
										var json = $.parseJSON(data);
										$("#PurchaseSupply_vendor_id").html(json.vendor_list);						
									}',
								  	'data'=>array('item_id'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
								  )));?>
		<?php echo $form->error($model,'item_id'); ?>
</div>
<div class="txtfld-col">
<?php echo $form->labelEx($model,'vendor_id'); ?>
<?php  
					$vendor_arr = array();
					if($model->item_id!=NULL){
						$products = PurchaseProducts::model()->findAllByAttributes(array('item_id'=>$model->item_id));						
						foreach($products as $product){
							$vendor = PurchaseVendors::model()->findByAttributes(array('id'=>$product->vendor_id));
							if($vendor){
								$vendor_arr[$vendor->id] = ucfirst($vendor->first_name).' '.ucfirst($vendor->last_name);
							}
						}							
					}
					
		echo $form->dropDownList($model,'vendor_id',$vendor_arr,array('options' => array($model->vendor_id=>array('selected'=>true)),'empty'=>Yii::t('app','Select Vendor'))); ?>
		<?php echo $form->error($model,'vendor_id'); ?>
</div>
<div class="txtfld-col">
<?php echo $form->labelEx($model,'quantity'); ?>
<?php echo $form->textField($model,'quantity',array('maxlength'=>255, 'onKeyUp'=>'displayPrice();')); ?>
<?php echo $form->error($model,'quantity'); ?>

</div>
</div>
  

	<div class="txtfld-col-btn">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
		</div>

<?php $this->endWidget(); ?>

	</div><!-- form -->
</div>

