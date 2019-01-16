<?php

class LeavesController extends RController
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
				'actions'=>array('create','update', 'cancel'),
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
	
	public function beforeAction(){
		if(!ModuleAccess::model()->check('HR')){	// checking whether HR module is enabled
			throw new CHttpException(404, Yii::t('app', 'You are not authorized to perform this action'));
		}
		return true;
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
		$model=new LeaveRequests;
		if(isset($_POST['LeaveRequests']))
		{ 
			$model->attributes=$_POST['LeaveRequests'];
			$model->requested_by = Yii::app()->user->id;
			
			if($model->from_date)
    			 $model->from_date=date('Y-m-d',strtotime($model->from_date));
			if($model->to_date)
    			 $model->to_date=date('Y-m-d',strtotime($model->to_date));
				 
			 if($file=CUploadedFile::getInstance($model,'file_name')){
					$model->file_name=$file->name;				
			}	
			
			if($model->save()){
				$valid_file_types = array('jpeg','png', 'jpg', 'sql', 'pdf', 'msword','plain', 'txt', 'docx', 'xlsx','vnd.ms-excel','vnd.openxmlformats-officedocument.wordprocessingml.document'); // Creating the array of valid file types
				$files_not_saved = '';
				if($model->file_name!=NULL){ 
						if(!is_dir('uploadedfiles/')){
							mkdir('uploadedfiles/');
						}
						if(!is_dir('uploadedfiles/leave_images/')){
							mkdir('uploadedfiles/leave_images/');
						}
						if(!is_dir('uploadedfiles/leave_images/'.$model->requested_by)){
							mkdir('uploadedfiles/leave_images/'.$model->requested_by);
						}
						$temp_file_loc = $_FILES['LeaveRequests']['tmp_name']['file_name'];
								$destination_file = 'uploadedfiles/leave_images/'.$model->requested_by.'/'.$model->file_name;
								move_uploaded_file($temp_file_loc,$destination_file);
					
				}
				//Email
				$college			= Configurations::model()->findByPk(1);						
				$template			= EmailTemplates::model()->findByPk(34);
				$profile			= Profile::model()->findByAttributes(array('user_id'=>$model->requested_by)); //leave requested person
				$user				= User::model()->findByAttributes(array('id'=>1));	
				$profile_user		= Profile::model()->findByAttributes(array('user_id'=>1));	
				$subject 			= $template->subject;
				$message 			= $template->template;
				$subject 			= str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);
				$message 			= str_replace("{{SCHOOL NAME}}",$college->config_value,$message);								
				$message    		= str_replace("{{NAME}}", ucfirst($profile_user->firstname).' '.ucfirst($profile_user->lastname), $message);
				$message    		= str_replace("{{USER NAME}}", ucfirst($profile->firstname).' '.ucfirst($profile->lastname), $message);
				UserModule::sendMail($user->email,$subject,$message);
				
				//Email for admin level users, custom users like hr manager
				$criteria 				= 	new CDbCriteria();
				$criteria->join 		= 	'JOIN authassignment aa ON aa.userid = user.id JOIN authitemchild aui ON aui.parent = aa.itemname';
				$criteria->condition	= 	'aui.child = :child';
				$criteria->params 		= 	array(":child" => 'Hr.LeaveTypes.*');
				$users 					= 	User::model()->findAll($criteria);
				if($users){
					foreach($users as $user){
						UserModule::sendMail($user->email,$subject,$message);
					}
				}
				
				//sms
				$from 		= $college->config_value;
				$to 		= $user->mobile_number;
				$template	= SystemTemplates::model()->findByPk(38);
				$message 	= $template->template;
				$message 	= str_replace("<Name>",ucfirst($profile->firstname).' '.ucfirst($profile->lastname),$message);
				if($to){
					SmsSettings::model()->sendSms($to,$from,$message);
				}
				
				//sms for admin level users, custom users like hr manager
				if($users){
					foreach($users as $user){
						$to 		= $user->mobile_number;
						if($to){
							SmsSettings::model()->sendSms($to,$from,$message);
						}
					}
				}
				
				//Mobile App Notification
				//For Android App
				if(Configurations::model()->isAndroidEnabled()){
					$reg_arr					= array();				
					$criteria 					= new CDbCriteria();
					$criteria->condition 		= 'uid=:uid';
					$criteria->params[':uid'] 	= 1; 
					$reg_ids 					= UserDevice::model()->findAll($criteria);
					
					foreach($reg_ids as $reg_id){
						$reg_arr[] = $reg_id->device_id; 
					}
					
					//date range
					$settings 	= UserSettings::model()->findByAttributes(array('user_id'=>1));
					$date		= ($settings!=NULL)?(date($settings->displaydate, strtotime($model->from_date))):date('Y-m-d', $model->from_date);
					if($model->from_date!=$model->to_date){
						$date	.= " - ".(($settings!=NULL)?(date($settings->displaydate, strtotime($model->to_date))):date('Y-m-d', $model->to_date));
					}
					
					$message		= Yii::t('app', 'Recieved leave request from').' '.ucfirst($profile->firstname).' '.ucfirst($profile->lastname).' '.Yii::t('app', 'for').' '.$date;
					$argument_arr 	= array('message'=>  $message,'device_id'=>$reg_arr);                
					Configurations::model()->devicenotice($argument_arr, 'Leave Request',"leaves");
					
					//notification for admin level users, custom users like hr manager
					//Recieved leave request from $name for date;
					if($users){
						$reg_arr					= array();				
						$criteria 					= new CDbCriteria();
						$criteria->condition 		= 'uid=:uid';
						foreach($users as $user){
							$criteria->params[':uid'] 	= $user->id;
						}
						$reg_ids 					= UserDevice::model()->findAll($criteria);						
						foreach($reg_ids as $reg_id){
							$reg_arr[] = $reg_id->device_id; 
						}					
						$argument_arr = array('message'=>  $message,'device_id'=>$reg_arr);                
						Configurations::model()->devicenotice($argument_arr, 'Leave Request',"leaves");
					}
				}
				
				
						
				Yii::app()->user->setFlash('successMessage', Yii::t('app','Leave Request Send Successfully'));
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

		if(isset($_POST['LeaveRequests']))
		{
			$model->attributes=$_POST['LeaveRequests'];
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
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{      
		    $page_size	= 10;
		   	$criteria = new CDbCriteria;
			$criteria->condition = 'requested_by=:requested_by';
			$criteria->params = array(':requested_by'=>Yii::app()->user->id);
			$criteria->order = 'id DESC';			
		   	$count			= LeaveRequests::model()->count($criteria);
			$pages 			= new CPagination($count);
			$pages->setPageSize($page_size);
			$pages->applyLimit($criteria);		
			$leaves		= LeaveRequests::model()->findAll($criteria);
			
			$this->render('index',array(
		   	'pages' => $pages,
			'item_count'=>$count,
			'page_size'=>$page_size,
			'leaves'=>$leaves,
			));
		//$leaves = LeaveRequests::model()->findAllByAttributes(array('requested_by'=>Yii::app()->user->Id),array('order'=>'id DESC'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new LeaveRequests('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['LeaveRequests']))
			$model->attributes=$_GET['LeaveRequests'];

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
		$model=LeaveRequests::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='leave-requests-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionSalary()
	{
		$this->render('salary');
	}
	public function actionSalarydetails()
	{
		$this->render('salarydetails');
	}
	
	public function actionCancel() { 	
		if($_REQUEST['id']){ 
			$model =  LeaveRequests::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		}
		
		if(isset($_POST['LeaveRequests'])){			
			$model->cancel_reason	= $_POST['LeaveRequests']['cancel_reason'];
			$model->status			= 3; //cancelled
			$model->handled_by		= Yii::app()->user->id;
			$model->handled_at	= date("Y-m-d H:i:s");			
			if($model->save()){
				echo CJSON::encode(array(
					'status'=>'success',
					'flag'=>true,				
				));
				exit;
			}
			else{
				echo CJSON::encode(array(
					'status'=>'error',
					'errors'=>CActiveForm::validate($model),
				));
				exit;
			}
		}	
		else{		
			Yii::app()->clientScript->scriptMap	= array(
				'jquery.js'=>false,				
				'jquery.min.js'=>false					
			);						
			$this->renderPartial('cancel',array('model'=>$model),false,true);		
		}
	}
}
