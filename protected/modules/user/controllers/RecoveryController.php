<?php

class RecoveryController extends Controller
{
	public $defaultAction = 'recovery';
	public $layout='//layouts/none';
	/**
	 * Recovery password
	 */
	public function actionRecovery () {
		$form = new UserRecoveryForm;
		if (Yii::app()->user->id) {
		    	$this->redirect(Yii::app()->controller->module->returnUrl);
		    } else {
				$email = ((isset($_GET['email']))?$_GET['email']:'');
				$activkey = ((isset($_GET['activkey']))?$_GET['activkey']:'');
				if ($email&&$activkey) {
					$form2 = new UserChangePassword;
		    		$find = User::model()->notsafe()->findByAttributes(array('email'=>$email));
		    		if(isset($find)&&$find->activkey==$activkey) {
			    		if(isset($_POST['UserChangePassword'])) {
							$form2->attributes=$_POST['UserChangePassword'];
							if($form2->validate()) {
								$salt				= User::model()->getSalt(); 
								$form2->password 	= $salt.$form2->password;								
								$find->password 	= Yii::app()->controller->module->encrypting($form2->password);
								$find->activkey		= Yii::app()->controller->module->encrypting(microtime().$form2->password);
								$find->salt			= $salt;
								if ($find->status==0) {
									$find->status = 1;
								}
								$find->save();								
								Yii::app()->user->setFlash('recoveryMessage',Yii::t('app',"New password is saved.").CHtml::link('Login',array('/user/login')));
								$this->redirect(Yii::app()->controller->module->recoveryUrl);
							}
						} 
						$this->render('changepassword',array('form'=>$form2));
		    		} else {
		    			Yii::app()->user->setFlash('recoveryMessage',Yii::t('app',"Incorrect recovery link."));
						$this->redirect(Yii::app()->controller->module->recoveryUrl);
		    		}
		    	} else {
			    	if(isset($_POST['UserRecoveryForm'])) {
			    		$form->attributes=$_POST['UserRecoveryForm'];											
			    		if($form->validate()) {												
			    			$user = User::model()->notsafe()->findbyPk($form->user_id);							
							$activation_url = Yii::app()->createAbsoluteUrl(implode(Yii::app()->controller->module->recoveryUrl),array("activkey" => $user->activkey, "email" => $user->email));
							
							$subject = Yii::t('app',"{site_name} Password reset request",
			    					array(
			    						'{site_name}'=>Yii::app()->name,
			    					));
							$message = Yii::t('app',"If you requested a password reset for {username}, click the link below. If you didn't make this request, ignore this email {activation_url}",
			    					array(
			    						'{site_name}'=>Yii::app()->name,
			    						'{activation_url}'=>$activation_url,
										'{username}'=>$user->email,
			    					));
							
			    			UserModule::sendMail($user->email,$subject,$message);
			    			
							Yii::app()->user->setFlash('recoveryMessage',Yii::t('app',"Please check your mail and follow the steps to reset your password."));
			    			$this->refresh();
			    		}
			    	}
		    		$this->render('recovery',array('form'=>$form));
		    	}
		    }
	}

}