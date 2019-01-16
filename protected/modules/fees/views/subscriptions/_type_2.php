<?php
$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL){
	$dateformat	= $settings->dateformat;
}
else
	$dateformat = 'dd-mm-yy';

$timeid	= time();
?>
<div class="white_bx">
<div class="triangle-up" style="left:110px;"></div>
<table>
    <tr>
        <td><label><?php echo Yii::t('app','Recurring Interval'); ?></label>&nbsp;&nbsp;</td>
        <td>
            <?php
            $subscription_types	= array(
                2=>Yii::t('app',"Half Yearly"),
                3=>Yii::t('app',"Quarterly"),
                4=>Yii::t('app',"Monthly"),
                5=>Yii::t('app',"Weekly"),
                6=>Yii::t('app',"Custom"),
            );					
            echo CHtml::activeDropDownList($category, 'subscription_type', $subscription_types, array('id'=>'subscription_type'));
            ?>
        </td>
    </tr>
</table>

<div id="payment_recurring_types">
    <?php $this->renderPartial('_recurring_2');?>
</div>
</div>
<script>
	$('select[id="subscription_type"]').unbind('change').change(function(e) {
        var that	= this;
		$.ajax({
			url:'<?php echo Yii::app()->createUrl("/fees/subscriptions/recurringType");?>',
			type:'GET',
			data:{id:$(that).val()},
			dataType:"json",
			success: function(response){
				if(response.status=="success"){
					var data	= $(response.data);
					$("#payment_recurring_types").html(data);
				}
				else{
					alert("<?php echo Yii::t("app", "Some error found !!");?>");
				}
			}
		});
    });
</script>