<?php

class SmsSettingsController extends Controller
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
			'accessControl', // perform access control for CRUD operations
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
		$model=new SmsSettings;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		/*if(isset($_POST['SmsSettings']))
		{
			$model->attributes=$_POST['SmsSettings'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}*/
		if(isset($_POST['SmsSettings'])){
			
			$posts = $_POST['SmsSettings'];
			
			if($posts['enable_app']==0){ // If sms is not enabled, set all values to 0.
				//$posts['enable_news'] = 0;
				$posts['enable_std_ad'] = 0;
				$posts['enable_std_atn'] = 0;
				$posts['enable_emp_apmt'] = 0;
				$posts['enable_exm_schedule'] = 0;
				$posts['enable_exm_result'] = 0;
				$posts['enable_fees'] = 0;
				$posts['enable_library'] = 0;
				
			}
			// Saving of SMS Settings
			
			$posts_1=SmsSettings::model()->findByAttributes(array('id'=>1));
			$posts_1->is_enabled = $posts['enable_app'];
			$posts_1->save();
			
			/*$posts_2=SmsSettings::model()->findByAttributes(array('id'=>2));
			$posts_2->is_enabled = $posts['enable_news'];
			$posts_2->save();*/
			
			$posts_3=SmsSettings::model()->findByAttributes(array('id'=>3));
			$posts_3->is_enabled = $posts['enable_std_ad'];
			$posts_3->save();
			
			$posts_4=SmsSettings::model()->findByAttributes(array('id'=>4));
			$posts_4->is_enabled = $posts['enable_std_atn'];
			$posts_4->save();
			
			$posts_5=SmsSettings::model()->findByAttributes(array('id'=>5));
			$posts_5->is_enabled = $posts['enable_emp_apmt'];
			$posts_5->save();
			
			$posts_6=SmsSettings::model()->findByAttributes(array('id'=>6));
			$posts_6->is_enabled = $posts['enable_exm_schedule'];
			$posts_6->save();
			
			$posts_7=SmsSettings::model()->findByAttributes(array('id'=>7));
			$posts_7->is_enabled = $posts['enable_exm_result'];
			$posts_7->save();
			
			$posts_8=SmsSettings::model()->findByAttributes(array('id'=>8));
			$posts_8->is_enabled = $posts['enable_fees'];
			$posts_8->save();
			
			$posts_9=SmsSettings::model()->findByAttributes(array('id'=>9));
			$posts_9->is_enabled = $posts['enable_library'];
			$posts_9->save();
		
			// Setting of successful message.
			 Yii::app()->user->setFlash('notification',Yii::t("app",'SMS Settings Saved Successfully!'));
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

		if(isset($_POST['SmsSettings']))
		{
			$model->attributes=$_POST['SmsSettings'];
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
		$dataProvider=new CActiveDataProvider('SmsSettings');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new SmsSettings('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['SmsSettings']))
			$model->attributes=$_GET['SmsSettings'];

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
		$model=SmsSettings::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='sms-settings-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
