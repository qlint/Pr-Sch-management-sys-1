<?php

class PurchaseSupplyController extends RController
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

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'vendorname'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView()
	{
		Yii::app()->clientScript->scriptMap['jquery.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
		$this->renderPartial('view',array('id'=>$_REQUEST['id']),false,true);		
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new PurchaseSupply;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PurchaseSupply']))
		{
			
			$model->attributes=$_POST['PurchaseSupply'];
			
			if($model->save())
			{ 
				$vendor   =  PurchaseVendors::model()->findByAttributes(array('id'=>$model->vendor_id));
				$item 	  =  PurchaseItems::model()->findByAttributes(array('id'=>$model->item_id));
				$college  =  Configurations::model()->findByPk(1);
				$template =  EmailTemplates::model()->findByPk(40);
				if($template){
					$message  =  $template->template;
					$subject  =  $template->subject;	
								
					$subject = str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);
					
					$message = str_replace("{{EMAIL}}",$vendor->email,$message);
					$message = str_replace("{{NAME}}", ucfirst($vendor->first_name).' '.ucfirst($vendor->last_name), $message);
					$message = str_replace("{{ITEM NAME}}", ucfirst($item->name), $message);
					$message = str_replace("{{QUANTITY}}", $model->quantity, $message);				
					UserModule::sendMail($vendor->email,$subject,$message);
				}
				
					$this->redirect(array('index'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PurchaseSupply']))
		{
			$model->attributes=$_POST['PurchaseSupply'];
			if($model->save())
				$this->redirect(array('create'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$criteria = new CDbCriteria;		
		$criteria->order = 'id DESC';
		
		$total = PurchaseSupply::model()->count($criteria);
		$pages = new CPagination($total);
        $pages->setPageSize(Yii::app()->params['listPerPage']);
        $pages->applyLimit($criteria); 
		
		$posts = PurchaseSupply::model()->findAll($criteria);
		$this->render('index', array('lists'=>$posts, 'pages' => $pages, 'item_count'=>$total, 'page_size'=>Yii::app()->params['listPerPage']));
		
	}
	
	/*public function actionSupply()
	{	
		$total = PurchaseSupply::model()->count();
		$pages = new CPagination($total);
        $pages->setPageSize(Yii::app()->params['listPerPage']);
        $pages->applyLimit();  
		$posts = PurchaseSupply::model()->findAllByAttributes(array('status'=>1),array('order'=>'id DESC'));
		

		$this->render('supply',array(
		'lists'=>$posts,
		'page_size'=>Yii::app()->params['listPerPage'],)) ;
		
	}*/
	
	public function actionSupply()
	{		
		$criteria 				= new CDbCriteria;		
		$criteria->order 		= 'id DESC';
		$criteria->condition 	= 'status=:x';
		$criteria->params		= array(':x'=>1); 
		
		$total = PurchaseSupply::model()->count($criteria);
		$pages = new CPagination($total);
        $pages->setPageSize(Yii::app()->params['listPerPage']);
        $pages->applyLimit($criteria); 
		
		$posts = PurchaseSupply::model()->findAll($criteria);
		$this->render('supply', array('lists'=>$posts, 'pages' => $pages, 'item_count'=>$total, 'page_size'=>Yii::app()->params['listPerPage']));
		
	}
	
	


	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new PurchaseSupply('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PurchaseSupply']))
			$model->attributes=$_GET['PurchaseSupply'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=PurchaseSupply::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='purchase-supply-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	
	public function actionVendorname()
	{
		$items = PurchaseStock::model()->findAllByAttributes(array('item_code'=>$_REQUEST['item_code']));
		$vendor_arr = array();
		foreach($items as $item){	
			$vendor	= PurchaseVendors::model()->findByAttributes(array('id'=>$item->vendor_id));
			if($vendor){
				$vendor_arr[$vendor->id] = ucfirst($vendor->first_name).' '.ucfirst($vendor->last_name);
			}
		}
		
		$vendor_list 	= "<option value=''>".Yii::t('app','Select Vendor')."</option>";
		if(!empty($vendor_arr)){			
			foreach($vendor_arr as $value=>$vendorname){
				$vendor_list .= CHtml::tag('option', array('value'=>$value),CHtml::encode($vendorname),true);
			}						
		}		
				
		echo json_encode(array('vendor_list'=>$vendor_list));
	} 
		
		public function actionVerify()
		{
			$model = PurchaseSupply::model()->findByAttributes(array('id'=>$_REQUEST['id']));
			if($model)
			{
				$model->is_verify =1;
				if($model->save())
				{
					$stock = PurchaseStock::model()->findByAttributes(array('item_id'=>$model->item_id));
					if($stock!=NULL){
						$stock->quantity = $stock->quantity+$model->quantity;
					}
					else{
						$stock = new PurchaseStock;
						$stock->item_id = $model->item_id;
						$stock->quantity = $model->quantity;
					}
					if($stock->save()){
						$this->redirect(array('supply'));
					}
				}
			}
			
		}
		
		public function actionApprove()
		{
			$model = PurchaseSupply::model()->findByAttributes(array('id'=>$_REQUEST['id']));
			if($model)
			{
				$model->status =1;
				if($model->save())
				{
					$this->redirect(array('index'));
				}
			}
			
		}
		
		public function actionReject()
		{
			$model = PurchaseSupply::model()->findByAttributes(array('id'=>$_REQUEST['id']));
			if($model)
			{
				$model->status =2;
				if($model->save())
				{
					$this->redirect(array('index'));
				}
			}
			
		}
		
		public function actionSendmail()
		{
			$model = PurchaseSupply::model()->findByAttributes(array('id'=>$_REQUEST['id']));
			if($model)
			{
				$model->send_mail =1;
				$model->save();
					
				$vendor   		=  PurchaseVendors::model()->findByAttributes(array('id'=>$model->vendor_id));
				$item 	  		=  PurchaseItems::model()->findByAttributes(array('id'=>$model->item_id));
				$college  		=  Configurations::model()->findByPk(1);
				$notification 	=  NotificationSettings::model()->findByPk(22);
				//if($notification->mail_enabled == '1'){
					$template 	=  EmailTemplates::model()->findByPk(29);
					if($template){
						$message  =  $template->template;
						$subject  =  $template->subject;
									
						$subject = str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);
						$message = str_replace("{{EMAIL}}",$vendor->email,$message);
						$message = str_replace("{{NAME}}", ucfirst($vendor->first_name).' '.ucfirst($vendor->last_name), $message);
						$message = str_replace("{{ITEM NAME}}", ucfirst($item->name), $message);
						$message = str_replace("{{QUANTITY}}", $model->quantity, $message);	
						UserModule::sendMail($vendor->email,$subject,$message);
						Yii::app()->user->setFlash('successMessage', Yii::t('app','Order Send Successfully'));
						$model->send_mail =1;
						$model->save();
						
					}
				//}
			}
			$this->redirect(array('supply'));
			
		}
		
		public function actionVendorNames()
		{			
			$products 		= PurchaseProducts::model()->findAllByAttributes(array('item_id'=>$_REQUEST['item_id']));
			$vendor_list = "<option value=''>".Yii::t('app','Select Vendor')."</option>";
			foreach($products as $product){
					$vendor = PurchaseVendors::model()->findByAttributes(array('id'=>$product->vendor_id));
					$vendor_list .= CHtml::tag('option', array('value'=>$vendor->id),CHtml::encode(ucfirst($vendor->first_name).' '.ucfirst($vendor->last_name)),true);
				}
			echo json_encode(array('vendor_list'=>$vendor_list));
		}

}
