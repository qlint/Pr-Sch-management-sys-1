<?php

class SettingsController extends RController
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
				'actions'=>array('index','view','settings','remind','notifications'),
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
		$model=new Settings;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Settings']))
		{
			$model->attributes=$_POST['Settings'];
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

		if(isset($_POST['Settings']))
		{
			$model->attributes=$_POST['Settings'];
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
			throw new CHttpException(400,Yii::t('app','Invalid request. Please do not repeat this request again.'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Settings');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	public function actionSettings()
	{
		
		$this->render('settings',array());
	}
	public function actionNotifications()
	{
		
		$this->render('notifications',array());
	}
	public function actionRemind()
	{
		$model=new MessFee;
		if(isset($_REQUEST['flag']) == 1)
		{
		$id=$_REQUEST['id'];
		
		$headers='';
		$loggeduser=User::model()->findByAttributes(array('id'=>Yii::app()->user->id));
		$student=Students::model()->findByAttributes(array('id'=>$id));
		$notification = NotificationSettings::model()->findByAttributes(array('id'=>17));
		$college=Configurations::model()->findByPk(1);
		if($notification->student == '1')
		{
		//Send mail	
			if($notification->mail_enabled=='1')
			{
				if(FormFields::model()->isVisible('email','Students','forAdminRegistration')){			
					$email = EmailTemplates::model()->findByPk(24);
					$subject = $email->subject;
					$message = $email->template;		
					$subject = str_replace("{{SCHOOL NAME}}",ucfirst($college->config_value),$subject);
					$message = str_replace("{{SCHOOL NAME}}",ucfirst($college->config_value),$message);		
					$message = str_replace("{{STUDENT NAME}}",ucfirst($student->first_name).' '.ucfirst($student->last_name),$message);		
					UserModule::sendMail($student->email,$subject,$message);
				}				
			}
		//Send sms	
			if($notification->sms_enabled=='1')
			{				
				$from = $college->config_value;				
				$sms_template = SystemTemplates::model()->findByAttributes(array('id'=>34));
				$sms_message = $sms_template->template;
				$message = str_replace("<School Name>",$college->config_value,$sms_message);
				SmsSettings::model()->sendSms($student->phone1,$from,$message);				
			}
		//Send internal message	
			if($notification->msg_enabled == '1')
			{						
				$to = $student->uid;
				$subject = 'Mess Due';
				$message = 'Your mess fee is pending. To avoid fine please pay  the amount';
				NotificationSettings::model()->sendMessage($to,$subject,$message);		
			}
			Yii::app()->user->setFlash('notification',Yii::t('app','Notification send Successfully!'));
		}
		
		}
		$this->render('settings',array('model'=>$model));
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Settings('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Settings']))
			$model->attributes=$_GET['Settings'];

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
		$model=Settings::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='settings-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
