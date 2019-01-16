<?php
class GatewaysController extends RController
{
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	
	public function actionSettings()
	{	
		$current_payment_gateway	= FeePaymentTypes::model()->paymentGateway();

		if($current_payment_gateway==1){
			$gateway 	= PaypalConfig::model()->find();
			if($gateway==NULL)
				$gateway 	= new PaypalConfig;
		}
		
		if(isset($_POST['PaypalConfig'])){
			$gateway->attributes 	= $_POST['PaypalConfig'];
			$gateway->created_by 	= Yii::app()->user->id;
			if($gateway->save()){
				$this->redirect(array('settings'));
			}
		}

		$this->render('settings', array('gateway'=>$gateway));
	}
}