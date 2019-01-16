<?php $feeconfig 	= FeeConfigurations::model()->find();	//fee cofigurations ?>
<tr class='invoice-particular-edit-bx' data-row-index="<?php echo $index;?>">
    <td align="center"><span class='particular-number'><?php echo $count + 1;?></span></td>
    <td>
        <?php echo CHtml::activeHiddenField($particular, "id[".$index."]", array('value'=>($particular->id)?$particular->id:""));?>
        <?php echo CHtml::activeTextField($particular, "name[".$index."]", array('style'=>'width:120px;', 'value'=>$particular->name));?>
    </td>
    <td><?php echo CHtml::activeTextField($particular, "description[".$index."]", array('style'=>'width:120px;', 'value'=>$particular->description));?></td>
    <td align="center"><?php echo CHtml::activeTextField($particular, "amount[".$index."]", array('style'=>'width:50px;', 'value'=>$particular->amount));?></td>
    <?php if($feeconfig==NULL or $feeconfig->discount_in_fee==1){?>
    <td align="center">
        <?php echo CHtml::activeTextField($particular, "discount_value[".$index."]", array('style'=>'width:40px; float:left;', 'value'=>$particular->discount_value));?>
        <?php echo CHtml::activeDropDownList($particular,"discount_type[".$index."]",array(1=>'%', 2=>($configuration!=NULL)?$configuration->config_value:'Amount'),array('style'=>'width:60px; float:left;', 'options'=>array($particular->discount_type=>array('selected'=>true)))); ?>
    </td>
    <?php }?>
    <?php if($feeconfig==NULL or $feeconfig->tax_in_fee==1){?>
    <td align="center">
        <?php echo CHtml::activeDropDownList($particular, "tax[".$index."]", CHtml::listData(FeeTaxes::model()->findAllByAttributes(array('is_active'=>1)), 'id', 'label'), array('style'=>'width:80px;', 'options'=>array($particular->tax=>array('selected'=>true)), 'prompt'=>Yii::t('app', 'No tax')));?>        
    </td>
    <?php }?>                              
    <td align="center">
        <a href="javascript:void(0);" class="remove-particular" id="remove-particular-<?php echo $index;?>"><?php echo Yii::t('app', 'Remove');?></a>
    </td>
</tr>

<script type="text/javascript">
$("#remove-particular-<?php echo $index;?>").click(function(){
    if(confirm('<?php echo Yii::t('app', 'Are you sure remove this particular ?');?>')){
        $(this).closest('.invoice-particular-edit-bx').remove();
        $('.particular-number').each(function(index, element){
            $(this).text(index + 1);
        });
    }        
});
</script>