<?php

class MaterialRequistionController extends RController
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
				'actions'=>array('create','update', 'approve', 'disapprove'),
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
	public function actionView($id)
	{
		Yii::app()->clientScript->scriptMap['jquery.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
		$this->renderPartial('view',array('id'=>$_REQUEST['id']),false,true);	
		
	}
	public function actionRequest()
	{
		$roles = Rights::getAssignedRoles(Yii::app()->user->Id);
		if(key($roles) == 'Admin' or key($roles) == 'pm')
		{	
			$criteria 			= new CDbCriteria;		
			$criteria->order 	= 'id DESC';
			$criteria->condition = 'is_send = :is_send';
       		$criteria->params = array(":is_send" =>1);
			$criteria->join	 =	 'JOIN `students` AS `p` ON t.employee_id = p.uid';
			$students    	 =    PurchaseMaterialRequistion::model()->findAll($criteria);
			$total			 = 	  PurchaseMaterialRequistion::model()->count($criteria);
			$pages 			 = 	  new CPagination($total);
			$pages->setPageSize(Yii::app()->params['listPerPage']);
			$pages->applyLimit($criteria); 
			$material_requests = PurchaseMaterialRequistion::model()->findAll($criteria);
			$this->render('studentrequest', array('material_requests'=>$material_requests,'pages' => $pages, 'item_count'=>$total, 'page_size'=>Yii::app()->params['listPerPage']));
	     }

	}
	public function actionRequestApprove()
	{
		$model = PurchaseMaterialRequistion::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		if($model)
		{
			$model->status_pm =1;
			if($model->save())
			{
				$this->redirect(array('Request'));
			}
		}
		
	}
	public function actionRequestDisapprove()
	{
		$model = PurchaseMaterialRequistion::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		if($model)
		{
			$model->status_pm =2;
			if($model->save())
			{
				$this->redirect(array('Request'));
			}
		}
		
	}
	public function actionIssueRequest()
	{
		$model = PurchaseMaterialRequistion::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$stock = PurchaseStock::model()->findByAttributes(array('item_id'=>$model->material_id));
		if($stock->quantity >= $model->quantity){
			if($model)
			{ 
				$model->is_issued 	=1;
				$model->issued_date =date('Y-m-d');
				if($model->save())
				{
					$stock->quantity = $stock->quantity-$model->quantity;
					if($stock->save()){
						$this->redirect(array('Request'));
					}
				}
			}
		}
		else{
			Yii::app()->user->setFlash('successMessage', Yii::t('app','Insufficient Stock. So Cannot Issue'));
		}
		$this->redirect(array('Request'));
		
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new PurchaseMaterialRequistion;
		

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PurchaseMaterialRequistion']))
		{
			$model->attributes		=	$_POST['PurchaseMaterialRequistion'];
			$model->employee_id		=	Yii::app()->User->Id;
			$model->requested_date	=	date('Y-m-d');
			
			if($model->save())
				$this->redirect(array('index'));
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

		if(isset($_POST['PurchaseMaterialRequistion']))
		{
			$model->attributes=$_POST['PurchaseMaterialRequistion'];
			$model->status			=	0;
			$model->status			=	0;
			$model->is_issued		=	0;
			$model->requested_date	=	date('Y-m-d');
			if($model->save())
			{
			Yii::app()->user->setFlash('successMessage', Yii::t('app','Material Request Updated Successfully'));
				$this->redirect(array('index'));
			}
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
			$model = PurchaseMaterialRequistion::model()->findByPk($id);
			if($model){				
				$model->delete();			
				Yii::app()->user->setFlash('successMessage', Yii::t('app','Material Request Deleted Successfully'));
			}	
				//$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
				$this->redirect(array('index'));	
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$roles = Rights::getAssignedRoles(Yii::app()->user->Id);
		if(key($roles) == 'Admin' or key($roles) == 'pm')
		{	
			$criteria 			= 	 new CDbCriteria;		
			$criteria->order 	=	 'id DESC';
			$criteria->join	 	=	 'JOIN `employees` AS `p` ON t.employee_id = p.uid';
			$employee    	    =    PurchaseMaterialRequistion::model()->findAll($criteria);
			$total = PurchaseMaterialRequistion::model()->count($criteria);
			$pages = new CPagination($total);
			$pages->setPageSize(Yii::app()->params['listPerPage']);
			$pages->applyLimit($criteria); 
			
			$material_requests = PurchaseMaterialRequistion::model()->findAll($criteria);
			$this->render('index', array('material_requests'=>$material_requests, 'pages' => $pages, 'item_count'=>$total, 'page_size'=>Yii::app()->params['listPerPage']));
			
		}
		else
		{
			$criteria 				= 	new CDbCriteria();
			$criteria->condition	=   'employee_id=:employee_id';
			$criteria->params 		= 	array(':employee_id'=>Yii::app()->User->Id);
			$criteria->order 		= 	'id DESC';
			
			$total = PurchaseMaterialRequistion::model()->count($criteria);
			$pages = new CPagination($total);
			$pages->setPageSize(Yii::app()->params['listPerPage']);
			$pages->applyLimit($criteria); 
			
			$model = PurchaseMaterialRequistion::model()->findAll($criteria);
			$this->render('other_index', array('model'=>$model, 'pages' => $pages, 'item_count'=>$total, 'page_size'=>Yii::app()->params['listPerPage']));
		}
		
	}
	
	
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new PurchaseMaterialRequistion('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PurchaseMaterialRequistion']))
			$model->attributes=$_GET['PurchaseMaterialRequistion'];

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
		$model=PurchaseMaterialRequistion::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='material-requistion-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	
	
	public function actionApprove()
	{
		$model = PurchaseMaterialRequistion::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		if($model)
		{
			$model->status =1;
			if($model->save())
			{
				$this->redirect(array('index'));
			}
		}
		
	}
	
	public function actionDisapprove()
	{
		$model = PurchaseMaterialRequistion::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		if($model)
		{
			$model->status =2;
			if($model->save())
			{
				$this->redirect(array('index'));
			}
		}
		
	}
	
	public function actionIssue()
	{
		$criteria 				= 	new CDbCriteria();
		$criteria->join = 'JOIN employees e ON e.uid = t.employee_id';
		$criteria->condition = 't.status = :status';
        $criteria->params = array(":status" => 1);
		$criteria->order 		= 	't.id DESC';
		
		$total = PurchaseMaterialRequistion::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->setPageSize(Yii::app()->params['listPerPage']);
		$pages->applyLimit($criteria); 
		
		$issue_items = PurchaseMaterialRequistion::model()->findAll($criteria);
		
		$this->render('issue_item', array('issue_items'=>$issue_items, 'pages' => $pages, 'item_count'=>$total, 'page_size'=>Yii::app()->params['listPerPage']));
	}
	public function actionIssueitem()
	{ 
		$model = PurchaseMaterialRequistion::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$stock = PurchaseStock::model()->findByAttributes(array('item_id'=>$model->material_id));
		if($stock->quantity >= $model->quantity){
			if($model)
			{ 
				$model->is_issued 	=1;
				$model->issued_date =date('Y-m-d');
				if($model->save())
				{
					$stock->quantity = $stock->quantity-$model->quantity;
					if($stock->save()){
						$this->redirect(array('issue'));
					}
				}
			}
		}
		else{
			Yii::app()->user->setFlash('successMessage', Yii::t('app','Insufficient Stock. So Cannot Issue'));
		}
		$this->redirect(array('issue'));
	}
        
	public function actionRetrunitem($id)
	{ 	
               
                
		$model	= $this->loadModel($id);
		if(isset($_POST['PurchaseMaterialRequistion'])){
			$status	=	$model->status;
			
			$date_p	=	$_POST['PurchaseMaterialRequistion']['return_date'];

				if(strlen($date_p) != 0)
				{
					$model->return_date	= date("Y-m-d", strtotime($date_p));
				}else
					$model->return_date='';
				
				$model->is_issued		= 2;
				$model->return_reason	= $_POST['PurchaseMaterialRequistion']['return_reason'];
				$model->scenario 		= 'retrunitem';
				
				if($model->save())
				{ 
					$stock	=	PurchaseStock::model()->findByAttributes(array('item_id'=>$model->material_id));
					$stock->quantity	=	$stock->quantity+$model->quantity;
					$stock->save();
				 
					echo CJSON::encode(array('status'=>'success'));
					exit;
				}
				else
				{
					echo CJSON::encode(array('status'=>'error','errors'=>$model->getErrors()));
					exit;
				}
		
         }      
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
			$this->renderPartial('reject_item', array('id'=>$id,'model'=>$model), false, true);
	 	
	}
	
}
