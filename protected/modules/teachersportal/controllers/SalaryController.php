<?php

class SalaryController extends RController
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
	public function actionView()
	{
		$this->render('view');
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
					//compress the image
					$info = getimagesize($_FILES['LeaveRequests']['tmp_name']['file_name']);
					if($info['mime'] == 'image/jpeg'){
						$image = imagecreatefromjpeg($_FILES['LeaveRequests']['tmp_name']['file_name']);
					}elseif($info['mime'] == 'image/gif'){
						$image = imagecreatefromgif($_FILES['LeaveRequests']['tmp_name']['file_name']);
					}elseif($info['mime'] == 'image/png'){
						$image = imagecreatefrompng($_FILES['LeaveRequests']['tmp_name']['file_name']);
					}
					
					$temp_file_name = $_FILES['LeaveRequests']['tmp_name']['file_name'];
					$destination_file = 'uploadedfiles/leave_images/'.$model->requested_by.'/'.$model->file_name;
					imagejpeg($image, $destination_file, 30);
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
		$this->render('index');
			
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
	
}
