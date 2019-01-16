<?php

class DriverDetailsController extends RController
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
				'actions'=>array('index','view','assign','manage','deletedetails','reallot'),
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
		$model=new DriverDetails;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DriverDetails']))
		{
			$model->attributes=$_POST['DriverDetails'];
			$model->phn_no=$_POST['DriverDetails']['phn_no'];
			//echo $_POST['DriverDetails']['phn_no'];exit;
			if($model->dob)
			{
				$model->dob=date('Y-m-d',strtotime($model->dob));
			}
			if($model->expiry_date)
			{
				$model->expiry_date=date('Y-m-d',strtotime($model->expiry_date));
			}
			if($model->save())
				$this->redirect(array('manage'));
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

		if(isset($_POST['DriverDetails']))
		{
			$model->attributes=$_POST['DriverDetails'];
			if($model->save())
				$this->redirect(array('manage','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	public function actionAssign()
	{
		$model=new DriverDetails;
		if(isset($_POST['search']))
		{
			if(isset($_POST['driver_id']) and $_POST['driver_id']!=NULL)
			{
				$drive=DriverDetails::model()->findByAttributes(array('id'=>$_POST['driver_id']));
				if(isset($_POST['vehicle_id']) and $_POST['vehicle_id']!=NULL)
				{
					$vehicle=VehicleDetails::model()->findByAttributes(array('id'=>$_POST['vehicle_id']));
					if($vehicle!=NULL)
					{
						$vehicle->status='C';
						$vehicle->save();
					}
					$drive->vehicle_id=$_POST['vehicle_id'];
					$drive->status='C';
					$drive->save();
				}
				
			$this->render('assign',array('model'=>$model,'id'=>$drive->id));
			}
			else
			{
				
				$this->render('error',array('model'=>$model));
			}
			
		}
		else
		{
			$this->render('assign',array('model'=>$model));
		}
		
	}
	public function actionReallot()
	{
		$model=new DriverDetails;
		if(isset($_POST['search']))
		{
			if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL)
			{
				
				$drive=DriverDetails::model()->findByAttributes(array('id'=>$_REQUEST['id']));
				
				$vehicle = VehicleDetails::model()->findByAttributes(array('id'=>$drive->vehicle_id));
				$vehicle->status = '';
				$vehicle->save();
				if(isset($_POST['vehicle_id']) and $_POST['vehicle_id']!=NULL)
				{
					$vehicle=VehicleDetails::model()->findByAttributes(array('id'=>$_POST['vehicle_id']));
					if($vehicle!=NULL)
					{
						$vehicle->status='C';
						$vehicle->save();
					}
					$drive->vehicle_id=$_POST['vehicle_id'];
					$drive->status='C';
					$drive->save();
				}
			
			$this->redirect(array('assign'));	
			
			}
			else
			{
				
				$this->render('error',array('model'=>$model));
			}
			
		}
		else
		{
			$this->render('reallot',array('model'=>$model));
		}
		
	}
	public function actionManage()
	{
		$model=new DriverDetails;
		
		$this->render('manage',array('model'=>$model));
		
		
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
		$dataProvider=new CActiveDataProvider('DriverDetails');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	public function actionError()
	{
		$model=new DriverDetails;
		
		$this->render('error',array('model'=>$model));
		
		
	}
	public function actionDeletedetails($id)
	{
		if(Yii::app()->request->isPostRequest){
			DriverDetails::model()->deleteAllByAttributes(array('id'=>$id));
			$this->redirect(array('manage'));
		}
		else
		{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
		
		
	}
	
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new DriverDetails('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DriverDetails']))
			$model->attributes=$_GET['DriverDetails'];

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
		$model=DriverDetails::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='driver-details-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
