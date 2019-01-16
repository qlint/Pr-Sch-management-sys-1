<?php
	$configuration	= Configurations::model()->findByPk(5);
	$feeconfig 		= FeeConfigurations::model()->find();	//fee cofigurations
?>
<div class="fee-particular" id="fee-particular-<?php echo $ptrow;?>"  data-row="<?php echo $ptrow;?>">
	<div class="fee-particular-head">
		<table width="100%">
			<tr>
				<td width="22%"><label><?php echo $particular->getAttributeLabel('name'); ?><sup>*</sup></label></td>
				<td width="33%"><label><?php echo $particular->getAttributeLabel('description');?></label></td>
                <?php if($feeconfig==NULL or $feeconfig->tax_in_fee==1){?>
                <td width="17%"><label><?php echo $particular->getAttributeLabel('tax');?></label></td>
                <?php }?>
                <?php if($feeconfig==NULL or $feeconfig->discount_in_fee==1){?>
                <td width="13%"><label><?php echo Yii::t('app', 'Discount');?></label></td>
                <?php }?>
                <td width="45%"><label>&nbsp;</label></td>			
			</tr>
		</table>
		<a href="javascript:void(0);" title="<?php echo Yii::t("app", "Click to remove particular");?>" class="remove-particular fees-trash"><?php echo Yii::t("app", "");?></a>
	</div>
	<div class="feeParticular">
    <table width="100%">
        <tr>
            <td width="20%" valign="top">
                <?php echo CHtml::activeTextField($particular,'name['.$ptrow.']',array('class'=>'FeeParticulars_name particular-name', 'placeholder'=>Yii::t('app', 'Particular Name'), 'style'=>'width:120px !important;')); ?>
            </td>
            <td width="35%" valign="top">
                <?php echo CHtml::activeTextField($particular,'description['.$ptrow.']',array('class'=>'FeeParticulars_description', 'placeholder'=>Yii::t('app', 'Particular Description'), 'style'=>'width:200px !important;')); ?>
            </td>
			<?php if($feeconfig==NULL or $feeconfig->tax_in_fee==1){?>
            <td width="45%" valign="top">
                <?php echo CHtml::activeDropDownList($particular,'tax['.$ptrow.']',CHtml::listData(FeeTaxes::model()->findAllbyAttributes(array('is_active'=>1)), 'id', 'label'),array('class'=>'FeeParticulars_tax', 'prompt'=>Yii::t('app', 'No Tax'), 'style'=>'width:100px !important;')); ?>
            </td>
            <?php }?>
            <?php if($feeconfig==NULL or $feeconfig->discount_in_fee==1){?>
            <td width="45%" valign="top">
                <?php echo CHtml::activetextField($particular,'discount_value['.$ptrow.']',array('class'=>'FeeParticulars_tax', 'placeholder'=>Yii::t('app', 'Discount'), 'style'=>'width:70px !important;')); ?>
            </td>
            <td width="45%" valign="top">
                <?php echo CHtml::activeDropDownList($particular,'discount_type['.$ptrow.']',array(1=>'%', 2=>($configuration!=NULL)?$configuration->config_value:'Amount'),array('class'=>'FeeParticulars_discount_type', 'style'=>'width:100px !important;')); ?>
            </td>
            <?php }?>
            <td width="10%" valign="middle">                
            </td>
        </tr>
    </table>
	
	 <br /> 
   <h3><?php echo Yii::t("app", "Applicable to").":";?></h3>
   
    <div id="particular-accesses-<?php echo $ptrow;?>">
		<?php $this->renderPartial('_access',array('ptrow'=>$ptrow, 'acrow'=>0));?>    
    </div>
	
    <a href="javascript:void(0);" title="<?php echo Yii::t("app", "Click to add access to another group");?>" class="add-particular-access" style="font-size:12px;" data-row="<?php echo $ptrow;?>"><strong><?php echo Yii::t("app", "+ Add access");?></strong></a>
	</div>
</div>
