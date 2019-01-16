<?php

class RouteDetailsController extends RController
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
				'actions'=>array('index','view','routedetails','manage'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
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
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new RouteDetails;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['RouteDetails']))
		{
			$model->attributes=$_POST['RouteDetails'];
			if($model->save())
				 $this->redirect(array('/transport/stopDetails/create/','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	/* 
	it will delete the route details
	*/
        public function actionDeletedetails($id)
	{
		if(Yii::app()->request->isPostRequest){
			$route = RouteDetails::model()->deleteAllByAttributes(array('id'=>$id));
			$stops = StopDetails::model()->deleteAllByAttributes(array('route_id'=>$id));
			$this->redirect(array('manage'));
		}
		else
		{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
	
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
		if(isset($_POST['RouteDetails']))
		{
			$model->attributes=$_POST['RouteDetails'];
			if($model->save())
			 	$this->redirect(array('manage','id'=>$model->id));
				
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
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,Yii::t('app','Invalid request. Please do not repeat this request again.'));
	}
	public function actionRoutedetails()
	{
		$model=new RouteDetails;
		if(isset($_POST['routeid']))
		{
			
			$this->render('routedetails',array('model'=>$model,'routeid'=>$_POST['routeid']));
			
		}
		else
		{
		$this->render('routedetails',array('model'=>$model));
		}
		
	}
	public function actionManage()
	{
		$model=new RouteDetails;
		$criteria = new CDbCriteria;
		$criteria->order = 'id DESC';
		
				$total = RouteDetails::model()->count($criteria);
				$pages = new CPagination($total);
       			$pages->setPageSize(Yii::app()->params['listPerPage']);
       		 	$pages->applyLimit($criteria);  // the trick is here!
				$posts = RouteDetails::model()->findAll($criteria);
				$this->render('manage',array('model'=>$model,
					'list'=>$posts,
					'pages' => $pages,
					'item_count'=>$total,
					'page_size'=>Yii::app()->params['listPerPage'],)) ;

		//$this->render('manage',array('model'=>$model));
		
		
	}
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('RouteDetails');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new RouteDetails('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['RouteDetails']))
			$model->attributes=$_GET['RouteDetails'];

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
		$model=RouteDetails::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='route-details-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
