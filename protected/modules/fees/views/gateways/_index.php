<?php
$current_payment_gateway	= FeePaymentTypes::model()->paymentGateway();
if($current_payment_gateway==1){	// check which payment gateway
	$paypalconfig	= PaypalConfig::model()->find();
	if($paypalconfig!=NULL){
		$this->renderPartial("application.modules.fees.views.gateways._paypal", array("invoice"=>$invoice, 'gateway'=>$gateway, 'amount_payable'=>$amount_payable));
	}		
}
?>