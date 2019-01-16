<style>
.sub_type{
}
.sub_type label{
	display:inline-block;
	margin-right:10px;
}
.white_bx{
	border: 1px solid #c5ced9;
    padding: 15px;
    background-color: #fff;
    margin-bottom: 20px;
	width:91%;
	position:relative;
}
.white_bx input[type="text"]{
	width:146px;
}
.triangle-up {
	position:absolute;
	top:-11px;
	left:29px;
	width: 0;
	height: 0;
	border-left: 10px solid transparent;
	border-right: 10px solid transparent;
	border-bottom: 10px solid #c5ced9;
}
.cust_duedate{
	float:left;
	position:relative;
	width:190px;
	margin-bottom:10px;
}
.fees-trash{
	position:absolute;
	top:5px;
	right:13px;
	width:15px;
	height:19px;
	background:url(<?php echo Yii::app()->request->baseUrl; ?>/images/fees-trash.png) no-repeat;
	
}
.error-brd{
	border-color:#F30 !important;
}
</style>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'fee-subscription-form',
	'enableAjaxValidation'=>false,
)); 

$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL){
	$dateformat	= $settings->dateformat;
}
else
	$dateformat = 'dd-mm-yy';
?>
<div class="formCon">
	<div class="formConInner">
		<h3><?php echo Yii::t('app','Setup a Subscription Method');?></h3>
        <table width="90%">
        	<tr>
            	<td><?php echo $form->labelEx($category,'start_date'); ?></td>
                <td>
                	<?php                        
					$this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'model'=>$category,
						'attribute'=>'start_date',
						// additional javascript options for the date picker plugin
						'options'=>array(
							'showAnim'=>'fold',
							'dateFormat'=>$dateformat,
							'changeMonth'=> true,
							'changeYear'=>true,
							'yearRange'=>'1900:'.(date('Y')+5)
						),
						'htmlOptions'=>array(
							'readonly'=>true
						),
					));
					?>
               	</td>
            	<td><?php echo $form->labelEx($category,'end_date'); ?></td>
            	<td>
                	<?php                        
					$this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'model'=>$category,
						'attribute'=>'end_date',
						// additional javascript options for the date picker plugin
						'options'=>array(
							'showAnim'=>'fold',
							'dateFormat'=>$dateformat,
							'changeMonth'=> true,
							'changeYear'=>true,
							'yearRange'=>'1900:'.(date('Y')+5)
						),
						'htmlOptions'=>array(
							'readonly'=>true
						),
					));
					?>
               	</td>
            </tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
            <tr>
                <td colspan="4"><?php echo $form->checkBox($category,'amount_divided'); ?>&nbsp;<?php echo $form->labelEx($category,'amount_divided'); ?></td>
          	</tr>
            <tr>
            	<td colspan="4">&nbsp;</td>
            </tr>
			<tr>
				<td colspan="4"><h3><?php echo $form->labelEx($category,'subscription_type'); ?></h3></td>
			</tr>
        	<tr>    
            	
            	<td colspan="4" class="sub_type">
                	<?php
						$payment_type	= 1;
                    	echo CHtml::radioButtonList('payment_type', $payment_type, array(1=>Yii::t("app", "One Time") , 2=>Yii::t("app", "Repeat Every")), array("separator"=>'', 'labelOptions'=>array('style'=>'display:inline;')));
					?>
                </td>
				       
            </tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
        </table>
        <div id="payment_types">
        	<?php $this->renderPartial('_type_1', array('category'=>$category), false);?>
        </div>		
	</div>
</div>
<div class="row buttons">
	<?php echo CHtml::submitButton(Yii::t("app", 'Submit'), array('name'=>'','class'=>'formbut')); ?>
</div>
<?php $this->endWidget(); ?>
<script>
	$(':radio[name="payment_type"]').unbind('change').change(function(e) {
        var that	= this;
		$.ajax({
			url:'<?php echo Yii::app()->createUrl("/fees/subscriptions/type");?>',
			type:'GET',
			data:{id:$(that).val()},
			dataType:"json",
			success: function(response){
				if(response.status=="success"){
					var data	= $(response.data);
					$("#payment_types").html(data);
				}
				else{
					alert("<?php echo Yii::t("app", "Some error found !!");?>");
				}
			}
		});
    });
	
	$("form#fee-subscription-form").submit(function(e) {
		var that	= this;
		var data	= $(that).serialize();
		$.ajax({
			url:'',
			type:'POST',
			data:data,
			dataType:"json",
			success: function(response){
				$(that).find("input, select").attr('title', '');
				$(that).find("*").removeClass("error-brd");
				if(response.status=="success"){
					window.location.href	= response.redirect;
				}
				else if(response.hasOwnProperty("errors")){
					var errors	= response.errors;
					$.each(errors, function(attribute, earray){
						$.each(earray, function(index, error){
							$('#' + attribute).attr('title', error).addClass("error-brd");
						});										
					});				
				}
				else if(response.hasOwnProperty("message")){
					alert(response.message);
				}
				else{
					alert("<?php echo Yii::t("app", "Some problem found while saving data !!");?>");
				}
			}
		});
		
		return false;
	});
</script>