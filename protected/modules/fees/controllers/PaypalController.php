<?php
class PaypalController extends RController
{
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	
	public function actionConfirm(){
		$token 		= trim($_GET['token']);
		$payerId 	= trim($_GET['PayerID']);
		
		$result 				= Yii::app()->Paypal->GetExpressCheckoutDetails($token);
		$result['PAYERID'] 		= $payerId; 
		$result['TOKEN'] 		= $token;
		$result['ORDERTOTAL'] 	= Yii::app()->session['final_amount'];

		$error 					= NULL;
		$success 				= NULL;
		
		$invoice_id		= Yii::app()->session['invoice_id'];

		//Detect errors 
		if(!Yii::app()->Paypal->isCallSucceeded($result)){ 
			if(Yii::app()->Paypal->apiLive === true){
				//Live mode basic error message
				$error = Yii::t('app', 'We were unable to process your request. Please try again later');
			}else{
				//Sandbox output the actual error message to dive in.
				$error = Yii::t('app', $result['L_LONGMESSAGE0']);
			}
		}
		else{			
			$paymentResult = Yii::app()->Paypal->DoExpressCheckoutPayment($result);
			
			//Detect errors  
			if(!Yii::app()->Paypal->isCallSucceeded($paymentResult)){
				if(Yii::app()->Paypal->apiLive === true){
					//Live mode basic error message
					$error = Yii::t('app', 'We were unable to process your request. Please try again later');
				}
				else{
					//Sandbox output the actual error message to dive in.
					$error = Yii::t('app', $paymentResult['L_LONGMESSAGE0']);
				}
			}else{
				//payment was completed successfully
				$transaction 					= FeeTransactions::model()->findByPk(Yii::app()->session['transaction_id']);
				if($transaction!=NULL){
					$transaction->transaction_id 	= $paymentResult['TRANSACTIONID'];
					$transaction->amount 			= $paymentResult['AMT'];
					$transaction->date 				= $paymentResult['ORDERTIME'];
					
					if($paymentResult['PAYMENTSTATUS']=='Completed'){ // Purchase process is completed
						$transaction->status 	    	= 1;
					}

					if($transaction->save()){
						//check balance amount
						$amount_payable		= FeeInvoices::model()->getAmountPayable($invoice_id);
						if($amount_payable<=0){
							$invoice	= FeeInvoices::model()->findByPk($invoice_id);							
							if($invoice!=NULL){
								$invoice->is_paid	= 1; //paid
								$invoice->save();
							}
						}
						
						$success 	= Yii::t('app', 'Your transaction is completed successfully.');

						// Unset the sessions set during buy action
						unset(Yii::app()->session['final_amount']);
						unset(Yii::app()->session['transaction_id']);
						unset(Yii::app()->session['invoice_id']);
					}
				}				
			}			
		}

		if($error!=NULL)
			Yii::app()->user->setFlash('error', $error);
		if($success!=NULL)
			Yii::app()->user->setFlash('success', $success);

		$this->redirect(array('/fees/myInvoices/view', 'id'=>$invoice_id));
	}

	public function actionCancel(){
		$invoice_id		= Yii::app()->session['invoice_id'];

		//The token of the cancelled payment typically used to cancel the payment within your application
		if(Yii::app()->session['final_amount']!=NULL and $_GET['token']!=NULL)
		{
			$transaction 	= FeeTransactions::model()->findByPk(Yii::app()->session['transaction_id']);
			if($transaction!=NULL){
				$transaction->status 	    	= -1;			
				$transaction->save();
			}
			
			Yii::app()->user->setFlash('error', 'Your transaction is cancelled');

			unset(Yii::app()->session['final_amount']);
			unset(Yii::app()->session['transaction_id']);
			unset(Yii::app()->session['invoice_id']);
		}
		
		$this->redirect(array('/fees/myInvoices/view', 'id'=>$invoice_id));
	}
}