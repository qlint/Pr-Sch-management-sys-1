<div class="contentpanel" id="payment-area"> 
    <div class="panel-heading">    
        <h3 class="panel-title"><?php echo Yii::t('app','Make a payment');?></h3>        
    </div> 
    <div class="people-item">
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'paypal-payment-form',
            'enableAjaxValidation'=>false,
			'action'=>'#paypal-payment-form'
        )); ?>
        <div class="row">
            <div class="col col-sm-1">
                <?php echo $form->labelEx($gateway, 'amount');?>
            </div>
            <div class="col col-sm-4">
                <?php echo $form->textField($gateway, 'amount', array('autocomplete'=>'off', 'class'=>'form-control', 'value'=>($gateway->amount)?$gateway->amount:$amount_payable)); ?>
                <?php echo $form->error($gateway, 'amount'); ?>
            </div>
            <div class="col col-sm-4">
                <?php echo CHtml::button(Yii::t("app", "Pay with Paypal"), array("class"=>"btn btn-primary", "id"=>"paypal-submit-button"));?>
            </div>
        </div>
        <?php $this->endWidget();?>
        <script type="text/javascript">
		$('#PaypalForm_amount').keydown(function(e) {
			if(e.keyCode==13){
				e.preventDefault();
			}
        });
		
        $('#paypal-submit-button').click(function(){
            if(confirm('<?php echo Yii::t('app', 'Are you sure ?');?>')){
                $("form#paypal-payment-form").submit();
            }
            return false;
        });
        </script>    
    </div>
</div>