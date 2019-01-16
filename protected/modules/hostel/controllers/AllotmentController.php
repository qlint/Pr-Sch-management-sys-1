<?php

class AllotmentController extends RController
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
				'actions'=>array('index','view','roominfo','alloterror'),
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
		$model=new Allotment;
		$model_1=new MessFee;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		
		if(isset($_POST['Allotment']))
		{	
		
				
				$register=Registration::model()->findByAttributes(array('student_id'=>$_REQUEST['studentid']));
				 if($register!=NULL)
				 {
					 $request=Roomrequest::model()->findByAttributes(array('student_id'=>$register->student_id,'status'=>'C'));
					 if($request!=NULL)
					 {
						 $request->status='S';
						 $request->save();
					 }
					
					 $register->status='S';
					 $register->save();
				 }		
				$model_1=new MessFee;
				
				$data=Allotment::model()->findByAttributes(array('student_id'=>$_REQUEST['studentid']));
				$allot=Allotment::model()->findByAttributes(array('id'=>$_REQUEST['allotid']));
				 $criteria = new CDbCriteria();
				$criteria->condition = "student_id=:x and allot_id=:allot_id";
				$criteria->params = array(':x'=>$_REQUEST['studentid'],':allot_id'=>$_REQUEST['allotid']);
				$request = Roomrequest::model()->find($criteria);  
				
				if($data!=NULL)
				{
					
					if($allot->status=='S')
					{
						$this->redirect(array('alloterror'));
					}
					$vacate= new Vacate;
				 	/*$request=Roomrequest::model()->findByAttributes(array('student_id'=>$data->student_id,'status'=>'C'));
					if($request!=NULL)
					{
						$request->status='S';
					$request->save();
					}*/
					//$bed=RoomDetails::model()->findByAttributes(array('bed_no'=>$data->bed_no));
					$data->student_id=NULL;
					$data->status='C';
					$data->created=NULL;
					$vacate->student_id=$_REQUEST['studentid'];
					$vacate->room_no=$data->room_no;
					$vacate->allot_id=$data->id;
					$vacate->status='C';
					$vacate->admit_date=$data->created;
					$vacate->vacate_date=date('Y-m-d');
					
					$vacate->save();
					$data->save();
				}
				else
				{
				
						$model_1->student_id=$_REQUEST['studentid'];
						$model_1->created=date('Y-m-d');	
						$model_1->status='C';	
						$model_1->is_paid = 0;
						$model_1->save();
				}
					 if($request!=NULL){
						$request->status = 'S';
						$request->save();
					}		  			
					$allot->student_id=$_REQUEST['studentid'];
					$allot->status='S';
					$allot->floor = $_REQUEST['floor_id'];
					$allot->created=date('Y-m-d');
					$allot->save();
						
					$this->redirect(array('view','id'=>$allot->id));
				
					
				
					
					//$bed->save();
					
				
				//else
				//{
					
					//$model_1->student_id=$allot->id;
					
				//}
					//$model->attributes=$_POST['Allotment'];
					//$model->student_id=$_REQUEST['studentid'];
					//$model->status='S';
					//$bed_info=RoomDetails::model()->findByAttributes(array('bed_no'=>$_POST['Allotment']['bed_no'],'status'=>'C'));
					//if($bed_info==NULL)
					//{
						//$this->redirect(array('/allotment/roominfo/'));
					//}
					//else
					//{
					//$model->room_no=$bed_info->room_no;
			
					//if($model->save())
					
						
						
					
					
				//}
			
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

		if(isset($_POST['Allotment']))
		{
			$model->attributes=$_POST['Allotment'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
		public function actionRoominfo()
	{
		
		$this->render('roominfo');
		
	}
	public function actionAlloterror()
	{
		
	
		$this->render('alloterror');
		
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
		$dataProvider=new CActiveDataProvider('Allotment');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Allotment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Allotment']))
			$model->attributes=$_GET['Allotment'];

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
		$model=Allotment::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='allotment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
?>