<?php

class VacateController extends RController
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
				'actions'=>array('index','view','roomvacate','autocomplete'),
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
		$model=new Vacate;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['Vacate']))
		{			
			$allot=Allotment::model()->findByAttributes(array('student_id'=>$_REQUEST['id'],'room_no'=>$_POST['room_id'],'status'=>'S'));
			$reg=Registration::model()->DeleteAllByAttributes(array('student_id'=>$allot->student_id));
			$mess = MessFee::model()->DeleteAllByAttributes(array('student_id'=>$allot->student_id));
			$model->attributes=$_POST['Vacate'];
			$model->allot_id=$allot->id;
			$model->room_no = $allot->room_no;
			$allot->status='C';
			$allot->student_id=NULL;
			$allot->created=NULL;
		
			$allot->save();
			if($model->save())
			{
				
				$this->redirect(array('view','id'=>$model->id));
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

		if(isset($_POST['Vacate']))
		{
			$model->attributes=$_POST['Vacate'];
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
		$dataProvider=new CActiveDataProvider('Vacate');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	public function actionRoomvacate()
	{
		$model=new Vacate;
		if(isset($_POST['search']))
		{
			
				$criteria = new CDbCriteria;
				if(isset($_POST['student_id']) and $_POST['student_id']!=NULL)
				{
					
					$criteria->condition='student_id = :match';
		 			$criteria->params = array(':match' =>$_POST['student_id']);
					
			
				}
				
			$total = Allotment::model()->count($criteria);
			$pages = new CPagination($total);
      	    $pages->setPageSize(Yii::app()->params['listPerPage']);
			$pages->applyLimit($criteria);  // the trick is here!
			$posts = Allotment::model()->findAll($criteria);
			$this->render('roomvacate',array('model'=>$model,
			'list'=>$posts,
			'pages' => $pages,
			'item_count'=>$total,
			'page_size'=>Yii::app()->params['listPerPage'],
			));	
		}
		else
		{
		$this->render('roomvacate',array('model'=>$model));
		}
		
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Vacate('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Vacate']))
			$model->attributes=$_GET['Vacate'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	public function actionAutocomplete() 
	 {
	  if (isset($_GET['term'])) {
		$criteria=new CDbCriteria;
		$criteria->alias = "last_name";
		$criteria->condition = "last_name   like '%" . $_GET['term'] . "%'";
		 $userArray = Students::model()->findAll($criteria);
		
		$hotels = Students::model()->findAll($criteria);;
	
		$return_array = array();
		foreach($hotels as $hotel) {
		  $return_array[] = array(
						'label'=>$hotel->last_name.' '.$hotel->first_name  ,
						'value'=>$hotel->first_name,
						'id'=>$hotel->id,
						);
		}
		echo CJSON::encode($return_array);
	  }
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Vacate::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='vacate-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
