<?php

class GatewayController extends RController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
        
        public function actionIndex()
        {
            $this->render('index');
        }
        
        public function actionCreate()
	{
            $first = SmsGateway::model()->find(array('order'=>'id ASC'));
            if($first)
            {
                $model= $first;               
            }
            else
            {
		$model=new SmsGateway;
            }
            
                $parameter= new SmsGatewayParameter;                		
		if(isset($_POST['SmsGateway']))
		{
                    $errors		= array();
                    $has_error	= false;
                    $model->attributes=$_POST['SmsGateway'];
                    if(!$model->validate())
                    {
					$has_error	= true;					
					foreach($model->getErrors() as $attribute=>$error){
						$key		= "SmsGateway_".$attribute;							
						$errors[$key][]	= $error[0];
					}
                    }
                                
                    if(isset($_POST['SmsGatewayParameter']['name']))
                    {
                        foreach($_POST['SmsGatewayParameter']['name'] as $i=>$name){
                                $parameter	= new SmsGatewayParameter;						
                                $parameter->name		= $name;
                                $parameter->value		= $_POST['SmsGatewayParameter']['value'][$i];						
                                $parameter->gateway_id          = 0;
                                if(!$parameter->validate()){
                                        $has_error	= true;							
                                        foreach($parameter->getErrors() as $attribute=>$error){
                                                $key		= "SmsGatewayParameter_".$attribute."_".$i;							
                                                $errors[$key][$i]	= $error[0];
                                        }			
                                }												
                        }
                    }
                    else{
                            $has_error	= true;
                    }
                    
                    if($has_error==true)
                    {                    
                            echo CJSON::encode(array('status'=>'error', 'errors'=>$errors));			
                            exit;
                    }
                    else{
                           
                            if($model->save())
                            {    
                                SmsGatewayParameter::model()->deleteAll();
                                foreach($_POST['SmsGatewayParameter']['name'] as $i=>$name)
                                {
                                    $parameter	= new SmsGatewayParameter;						
                                    $parameter->name		= $name;
                                    $parameter->value		= $_POST['SmsGatewayParameter']['value'][$i];	
                                    $parameter->gateway_id= $model->id;
                                    $parameter->save();
                                												
                                }
                                
                                //send success message
                                echo CJSON::encode(array('status'=>'success', 'redirect'=>Yii::app()->createUrl('/sms/gateway', array())));
                                exit;
                            }
                            else
                            {
                                echo CJSON::encode(array('status'=>'error', 'message'=>Yii::t("app", "Some problem found while saving data !!")));			
						exit;
                            }
                            
                            
                    }
                                                                        
			
		}

		$this->render('create',array(
			'model'=>$model,'parameter'=>$parameter
		));
	}
        
        public function actionAddParameter($ptrow=""){
		$parameter	= new SmsGatewayParameter;
		$data		= $this->renderPartial('new',array('parameter'=>$parameter, 'ptrow'=>$ptrow), true);
		echo CJSON::encode(array('status'=>'success', 'data'=>$data));
	}
        
}
?>