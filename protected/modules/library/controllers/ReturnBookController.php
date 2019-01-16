<?php

class ReturnBookController extends RController
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
				'actions'=>array('index','view','returnbook','manage'),
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
	public function actionCreate($id)
	{
		
		$model=new ReturnBook;
	
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['ReturnBook']))
		{
			
			$model->attributes=$_POST['ReturnBook'];
			if($model->return_date)
			$model->return_date=date('Y-m-d',strtotime($model->return_date));
			if($model->issue_date)
			$model->issue_date=date('Y-m-d',strtotime($model->issue_date));
			if($_POST['ReturnBook']['borrow_book_id'])
			{
				$borrow=BorrowBook::model()->findByAttributes(array('id'=>$_POST['ReturnBook']['borrow_book_id']));
			}
			
			$status=Book::model()->findByAttributes(array('title'=>$borrow->book_name));
			//echo $model->borrow_book_id; exit;
			$model->book_id=$status->id;
			$model->validate();
			if($model->save())
				{
				
				//$borrow=BorrowBook::model()->findByAttributes(array('id'=>$model->borrow_book_id,'student_id'=>$id));
				$user=User::model()->findByAttributes(array('id'=>Yii::app()->user->id));
				
				//updating borrowbook table
				// echo count($borrow);
			
			       $borrow->status='R';
			      // $borrow->validate();
				  //var_dump($borrow->getErrors());
				 //  exit;
				   $borrow->save();	
				   $status->status='R';
				   //$status->copy=($status->copy)+1;
					if($status->copy_taken!='0')
					{
					$status->copy_taken=($status->copy_taken)-1;
					}
					
					$status->save();
					//if($model->return_date >= $borrow->due_date)
//					{
//					User::sendMail($user->email,'Due date expired','<html><body>Dear '.$profile->first_name.' '.$profile->last_name.' , 
//		Your due date has expired. And you have to pay the fine.</body></html>', 'Dear '.$profile->first_name.' '.$profile->last_name.' , 
//		Your due date has expired. And you have to pay the fine');
//					}
					$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,'bookid'=>$_POST['BookID'],
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

		if(isset($_POST['ReturnBook']))
		{
			$model->attributes=$_POST['ReturnBook'];
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
			throw new CHttpException(400,Yii::t('app','Invalid request.Please do not repeat this request again.'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('ReturnBook');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	public function actionReturnbook()
	{
		
		$model=new ReturnBook;
		$this->render('returnbook',array('model'=>$model));
	}
	public function actionManage()
	{
		$model=new ReturnBook;
		if(isset($_POST['BookID']))
		{
			$this->redirect(array('manage','id'=>$_POST['BookID']));
		}
		else
		{
			$this->render('manage',array('model'=>$model));
		}

	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ReturnBook('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ReturnBook']))
			$model->attributes=$_GET['ReturnBook'];

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
		$model=ReturnBook::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='return-book-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
