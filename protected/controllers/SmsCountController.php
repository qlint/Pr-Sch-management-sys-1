<?php

class SmsCountController extends RController
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
		$model=new SmsCount;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['SmsCount']))
		{
			$model->attributes=$_POST['SmsCount'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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

		if(isset($_POST['SmsCount']))
		{
			$model->attributes=$_POST['SmsCount'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
			throw new CHttpException(400,Yii::t("app",'Invalid request. Please do not repeat this request again.'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$criteria = new CDbCriteria;
		$filter = new FilterForm;
		
		if(isset($_POST['FilterForm']['date']) and $_POST['FilterForm']['date']!= NULL and $_POST['FilterForm']['type']==1)
		{	
			$filter->type = $_POST['FilterForm']['type'];
			$filter->date = $_POST['FilterForm']['date'];
			$criteria->condition = 'date=:date';
			$criteria->params = array(':date'=>date('Y-m-d',strtotime($_POST['FilterForm']['date'])));
		}
		if(isset($_POST['FilterForm']['month']) and $_POST['FilterForm']['month']!= NULL and $_POST['FilterForm']['type']==2)
		{
			//var_dump($_POST['FilterForm']);exit;
			$filter->type = $_POST['FilterForm']['type'];
			$filter->month = $_POST['FilterForm']['month'];
			$criteria->condition = 'DATE_FORMAT(`date`,"%Y-%m")=:currentMonth';
			$criteria->params= array(':currentMonth'=>date('Y-m',strtotime($_POST['FilterForm']['month'])));
		}
		if(isset($_POST['FilterForm']['range_from']) and $_POST['FilterForm']['range_from']!= NULL and 
			isset($_POST['FilterForm']['range_to']) and $_POST['FilterForm']['range_to']!= NULL
		 	and $_POST['FilterForm']['type']==3)
		{
			$filter->type = $_POST['FilterForm']['type'];
			$filter->range_from = $_POST['FilterForm']['range_from'];
			$filter->range_to = $_POST['FilterForm']['range_to'];
			$criteria->addBetweenCondition('date', date('Y-m-d',strtotime($_POST['FilterForm']['range_from'])), date('Y-m-d',strtotime($_POST['FilterForm']['range_to'])));
		}
		$criteria->order = 'date ASC';
		$list = SmsCount::model()->findAll($criteria);
		
		
		$this->render('index',array('list'=>$list,'filter'=>$filter));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new SmsCount('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SmsCount']))
			$model->attributes=$_GET['SmsCount'];

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
		$model=SmsCount::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t("app",'The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sms-count-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
