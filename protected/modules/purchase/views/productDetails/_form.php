<style type="text/css">
<?php /*?>.formCon input[type="text"], input[type="password"], textArea, select {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #C2CFD8;
    border-radius: 2px;
    box-shadow: -1px 1px 2px #D5DBE0 inset;
    padding: 6px 3px;
    width: 175px !important;
}

.select-style select{ width:135% !important}

.formCon select{background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #C2CFD8;
    border-radius: 2px;
    box-shadow: -1px 1px 2px #D5DBE0 inset;
    padding: 6px 3px;
    width: 78% !important;}
	
	.formCon input[type="text"] {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #C2CFD8;
    border-radius: 2px;
    box-shadow: -1px 1px 2px #D5DBE0 inset;
    padding: 6px 3px;
    width: 175px !important;
}<?php */?>
</style>


<div class="page-tab-atag">
    <ul>
        <li><h2><?php if(isset($_REQUEST['id'])){ echo CHtml::link(Yii::t('app','Vendor Details'),array('vendorDetails/create','id'=>$_REQUEST['id'])); } else{ echo Yii::t('app','Vendor Details'); } ?></h2></li>
        <li class="cur"><h2><?php if(isset($_REQUEST['id'])){ echo CHtml::link(Yii::t('app','Product Details'),array('productDetails/create','id'=>$_REQUEST['id'])); } else{ echo Yii::t('app','Product Details'); } ?></h2></li>
    </ul>
</div>
<?php
$products = PurchaseProducts::model()->findAllByAttributes(array('vendor_id'=>$_REQUEST['id']));
if($products!=NULL)
{
 $this->renderPartial('product_list',array('id'=>$_REQUEST['id']));
}
?>

<?php $form=$this->beginWidget('CActiveForm', array(
'id'=>'product-details-form',
//'enableAjaxValidation'=>false,
)); ?>

	
	
    <p class="note"><?php echo Yii::t('app','Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app','are required.'); ?></p>
    
    <div class="formCon-block">
        <div class="formConInner-block">
            
            <h3><?php echo Yii::t('app','Product Details'); ?> </h3>   
            <div class="text-fild-bg-block">
			<div class="text-fild-block inputstyle">
				<?php echo $form->labelEx($model,'item_id'); ?>
				<?php 												
				$criteria = new CDbCriteria;
				$criteria->join = 'LEFT JOIN purchase_products t1 ON t1.item_id = t.id and t1.vendor_id = '.$_REQUEST['id'].'';
				$criteria->addCondition('t1.item_id IS NULL');
				
				 echo $form->dropDownList($model,'item_id',CHtml::listData(PurchaseItems::model()->findAll($criteria), 'id', 'name'),array('empty'=>Yii::t('app','Select Item')));?>
                <?php echo $form->error($model,'item_id'); ?>
			</div>
            
            
            <div class="text-fild-block inputstyle">
				<?php echo $form->labelEx($model,'price'); ?>
				<?php echo $form->textField($model,'price',array('maxlength'=>255)); ?>
                <?php echo $form->error($model,'price'); ?>
			</div>
            </div>
           <div class="text-fild-bg-block"> 
            <div class="text-fild-block-full inputstyle">
				<?php echo $form->labelEx($model,'description'); ?>
				<?php echo $form->textArea($model,'description',array('maxlength'=>255)); ?>
                <?php echo $form->error($model,'description'); ?>
			</div>
            </div>
			<div class="clear"></div>
            
        </div>
    </div>
    
    <?php echo $form->hiddenField($model,'which_button',array('id'=>'which_button')); ?> <!-- 1 => Add Another, 2 => Save --> 

<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li>    <?php
			if(Yii::app()->controller->action->id=='create'){
				echo CHtml::submitButton(Yii::t('app','Save and Add Another'),array('class'=>'','id'=>'add_another_btn')); 
			}
		?></li>
<li>    	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Save') : Yii::t('app','Save'),array('id'=>'submit_button_form','class'=>'')); ?>
      </li>
<li>        <?php 
		
		if($products!=NULL)
		{
			echo CHtml::link(Yii::t('app','Next'),array('/purchase/productDetails/view','id'=>$_REQUEST['id']),array('class'=>'formbut-a'));
		}?></li>                                    
</ul>
</div> 

</div>  
   
<?php $this->endWidget(); ?>

<script type="text/javascript">
$('#add_another_btn').click(function(ev) {
	$('#which_button').val('1');	
});
$('#submit_button_form').click(function(ev) {
	$('#which_button').val('2');
});
</script>
