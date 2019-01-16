<?php
class NotificationSettingsController extends RController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	/*function My_OB($str, $flags)
		{
			//remove UTF-8 BOM
			$str = preg_replace("/\xef\xbb\xbf/","",$str);
		 
			return $str;
		}
		
	public function init()
	{
		
		ob_start('My_OB');	
	}*/
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
		$model = new NotificationSettings;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		/*if(isset($_POST['NotificationSettings']))
		{
			$model->attributes=$_POST['NotificationSettings'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}*/
		
		if(isset($_POST['NotificationSettings']))
		{
			
			$posts = $_POST['NotificationSettings'];
			
			// Student Admission
			$posts_1 = NotificationSettings::model()->findByAttributes(array('id'=>3));
			$posts_1->sms_enabled = $posts['sms_std_ad'];
			$posts_1->mail_enabled 	 = $posts['mail_std_ad'];			
			$posts_1->msg_enabled 	 = $posts['msg_std_ad'];
			$posts_1->student = $posts['student_std_ad'];
			$posts_1->parent_1 = $posts['parent_1_std_ad'];				
			$posts_1->save();
			
			// Student Attendance
			$posts_2 = NotificationSettings::model()->findByAttributes(array('id'=>4));
			$posts_2->sms_enabled = $posts['sms_std_attnd'];
			$posts_2->mail_enabled 	 = $posts['mail_std_attnd'];
			$posts_2->msg_enabled 	 = $posts['msg_std_attnd'];
			$posts_2->parent_1 = $posts['parent_1_std_attnd'];			
			$posts_2->save();
			
			// Employee Appointment
			$posts_3 = NotificationSettings::model()->findByAttributes(array('id'=>5));
			$posts_3->sms_enabled = $posts['sms_emp_apmnt'];
			$posts_3->mail_enabled 	 = $posts['mail_emp_apmnt'];			
			$posts_3->msg_enabled 	 = $posts['msg_emp_apmnt'];
			$posts_3->employee = $posts['employee_emp_apmnt'];			
			$posts_3->save();
			
			// Exam Schedule
			$posts_4 = NotificationSettings::model()->findByAttributes(array('id'=>6));
			$posts_4->sms_enabled = $posts['sms_exm_schedule'];
			$posts_4->mail_enabled 	 = $posts['mail_exm_schedule'];
			$posts_4->msg_enabled 	 = $posts['msg_exm_schedule'];
			$posts_4->student = $posts['student_exm_schedule'];
			$posts_4->save();
			
			// Exam Result
			$posts_5 = NotificationSettings::model()->findByAttributes(array('id'=>7));
			$posts_5->sms_enabled = $posts['sms_exm_result'];
			$posts_5->mail_enabled 	 = $posts['mail_exm_result'];
			$posts_5->msg_enabled 	 = $posts['msg_exm_result'];
			$posts_5->student = $posts['student_exm_result'];
			$posts_5->save();
			
			// Fees
			$posts_6 = NotificationSettings::model()->findByAttributes(array('id'=>8));
			$posts_6->sms_enabled = $posts['sms_fees'];
			$posts_6->mail_enabled 	 = $posts['mail_fees'];
			$posts_6->msg_enabled 	 = $posts['msg_fees'];
			$posts_6->student = $posts['student_fees'];
			$posts_6->parent_1 = $posts['parent_1_fees'];			
			$posts_6->save();
			
			// Library
			$posts_7 = NotificationSettings::model()->findByAttributes(array('id'=>9));
			$posts_7->sms_enabled = $posts['sms_library'];
			$posts_7->mail_enabled 	 = $posts['mail_library'];
			$posts_7->msg_enabled 	 = $posts['msg_library'];
			$posts_7->student = $posts['student_library'];			
			$posts_7->save();
			
			//Student Log
			$posts_8 = NotificationSettings::model()->findByAttributes(array('id'=>11));
			$posts_8->sms_enabled = $posts['sms_student_log'];
			$posts_8->mail_enabled 	 = $posts['mail_student_log'];
			$posts_8->msg_enabled 	 = $posts['msg_student_log'];
			$posts_8->student = $posts['student_student_log'];
			$posts_8->parent_1 = $posts['parent_1_student_log'];			
			$posts_8->save();
			
			//User Creation
			$posts_9 = NotificationSettings::model()->findByAttributes(array('id'=>12));
			$posts_9->sms_enabled = $posts['sms_user'];
			$posts_9->mail_enabled	= $posts['mail_user'];
			$posts_9->msg_enabled = $posts['msg_user'];			
			$posts_9->student = $posts['student_user'];
			$posts_9->parent_1 = $posts['parent_1_user'];			
			$posts_9->employee = $posts['employee_user'];
			$posts_9->save();
			
			//Online Admission
			$posts_10 = NotificationSettings::model()->findByAttributes(array('id'=>13));
			$posts_10->sms_enabled = $posts['sms_online_admission'];
			$posts_10->mail_enabled = $posts['mail_online_admission'];			
			$posts_10->student = $posts['student_online_admission'];
			$posts_10->parent_1 = $posts['parent_1_online_admission'];			
			$posts_10->save();
			
			//Online Admission Approval
			$posts_11 = NotificationSettings::model()->findByAttributes(array('id'=>14));
			$posts_11->sms_enabled = $posts['sms_online_admission_approval'];
			$posts_11->mail_enabled 	 = $posts['mail_online_admission_approval'];
			$posts_11->msg_enabled 	 = $posts['msg_online_admission_approval'];
			$posts_11->student = $posts['student_online_admission_approval'];
			$posts_11->parent_1 = $posts['parent_1_online_admission_approval'];			
			$posts_11->save();
			
			//Application Status Change
			$posts_12 = NotificationSettings::model()->findByAttributes(array('id'=>15));
			$posts_12->sms_enabled = $posts['sms_application_status_change'];
			$posts_12->mail_enabled 	 = $posts['mail_application_status_change'];
			$posts_12->student = $posts['student_application_status_change'];
			$posts_12->parent_1 = $posts['parent_1_application_status_change'];			
			$posts_12->save();
			
			//Public Holidays
			$posts_13 = NotificationSettings::model()->findByAttributes(array('id'=>16));			
			$posts_13->sms_enabled = $posts['sms_public_holidays'];
			$posts_13->mail_enabled  = $posts['mail_public_holidays'];
			$posts_13->msg_enabled 	 = $posts['msg_public_holidays'];
			$posts_13->student = $posts['student_public_holidays'];
			$posts_13->parent_1 = $posts['parent_1_public_holidays'];			
			$posts_13->employee = $posts['employee_public_holidays'];
			$posts_13->save();
			
			//Hostel
			$posts_14 = NotificationSettings::model()->findByAttributes(array('id'=>17));			
			$posts_14->sms_enabled = $posts['sms_hostel'];
			$posts_14->mail_enabled  = $posts['mail_hostel'];
			$posts_14->msg_enabled 	 = $posts['msg_hostel'];
			$posts_14->student = $posts['student_hostel'];
			$posts_14->parent_1 = 0;			
			$posts_14->employee = 0;
			$posts_14->save();
			// Mising Documents
			$posts_19 = NotificationSettings::model()->findByAttributes(array('id'=>19));
			$posts_19->sms_enabled = $posts['sms_document'];
			$posts_19->mail_enabled 	 = $posts['mail_document'];
			$posts_19->msg_enabled 	 = 0;
			$posts_19->student = $posts['student_document'];
			$posts_19->parent_1 = $posts['parent_1_document'];	
			$posts_19->employee = 0;		
			$posts_19->save();
                        
            $posts_20 = NotificationSettings::model()->findByAttributes(array('id'=>20));
			$posts_20->sms_enabled = $posts['sms_userlogin'];
			$posts_20->mail_enabled 	 = $posts['mail_userlogin'];
			$posts_20->msg_enabled 	 = 0;
			$posts_20->student = 0;
			$posts_20->parent_1 = 0;	
			$posts_20->employee = 0;		
			$posts_20->save();
			
			// Setting of successful message.
			 Yii::app()->user->setFlash('notification',Yii::t('app','Notification Settings Saved Successfully!'));
			 
			 $this->redirect(array('create'));
			
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

		if(isset($_POST['NotificationSettings']))
		{
			$model->attributes=$_POST['NotificationSettings'];
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
		$dataProvider=new CActiveDataProvider('NotificationSettings');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new NotificationSettings('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['NotificationSettings']))
			$model->attributes=$_GET['NotificationSettings'];

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
		$model=NotificationSettings::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='notification-settings-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	public function actionHelp()
	{ 
		$model = Configurations::model()->findByAttributes(array('id'=>37)); 
		if(isset($_POST['Configurations']) and $_POST['Configurations']!=NULL) 
		{ 
			$model->config_value = 	$_POST['Configurations']['help_link'];
			if($model->save())
			{
				echo CJSON::encode(array(
						'status'=>'success',                                					
				));
				exit;
			}
		}				
        $this->renderPartial('help',array('model'=>$model),false,true);
	}
}
