<?php

class BorrowBookController extends RController
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
				'actions'=>array('index','view','autocomplete','studentdetails','listbook','error','remind'),
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
		$model=new BorrowBook;
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

        if($_POST['BorrowBook'])
		{
			$model->attributes=$_POST['BorrowBook'];
			$model->validate();
			
		if((isset($_POST['BorrowBook']) and $_POST['BorrowBook']['student_id']!=NULL and $_POST['BorrowBook']['subject']!=NULL and $_POST['BorrowBook']['book_name']!=NULL and $_POST['BorrowBook']['issue_date']!=NULL and $_POST['BorrowBook']['due_date']!=NULL))
		{
			
		
			$model->attributes=$_POST['BorrowBook'];
			if($model->validate()) {
			$student = Students::model()->findByAttributes(array('id'=>$_POST['BorrowBook']['student_id'],'is_deleted'=>0,'is_active'=>1));
			if($student==NULL)
			{
				Yii::app()->user->setFlash('errorMessage',Yii::t("app","Enter valid Student Admission Number!").' <br/>'.Yii::t('app',"The entered Admission Number is deleted or inactive"));
					$this->redirect(array('create','id'=>$_POST['BorrowBook']['student_admission_no']));
			}
			
			$status=Book::model()->findByAttributes(array('id'=>$_POST['BorrowBook']['book_name']));
		
			if($status!=NULL)
			{ 
			if($status->copy == 0)
			{
				$this->redirect(array('/library/BorrowBook/listbook/','id'=>$_POST['BorrowBook']['book_name']));
			}
			else if($status->copy==$status->copy_taken)
			{
				Yii::app()->user->setFlash('errorMessage',Yii::t("app","Copies are not available now! "));
				$this->redirect(array('/library/BorrowBook/listbook/','id'=>$_POST['BorrowBook']['book_name']));
			}
			else
			{
			if($model->issue_date)
			//$currdate=$model->issue_date;
			//$model->due_date =date('Y-m-d',strtotime("+1 months", strtotime($currdate))); 
			$model->issue_date=date('Y-m-d',strtotime($model->issue_date));
			$model->due_date=date('Y-m-d',strtotime($model->due_date));
			$model->status='C';
			$model->book_id=$status->id;
			$model->subject=$status->subject;
			$model->student_id=$student->id;
			$model->book_name=$status->title;
			if($model->save())
			{
			
				//echo $status->copy; exit;
				$status->saveAttributes(array('status'=>'S','copy_taken'=>($status->copy_taken)+1));
				$this->redirect(array('view','id'=>$model->id));
			}
					
				
			}
					
			}
			else
			{
				$this->redirect(array('/library/BorrowBook/error/'));
			}
			}
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
	 public function actionRemove($id)
	{
		$this->loadModel($id)->delete();
		$this->redirect(array('create'));
	}
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		//echo $_POST['BorrowBook']['issue_date'];exit;
		if((isset($_POST['BorrowBook'])) and (isset($_POST['BorrowBook']['student_admission_no'])) and (isset($_POST['BorrowBook'])) and (isset($_POST['BorrowBook']['book_id'])and (isset($_POST['BorrowBook']['issue_date'])) and (isset($_POST['BorrowBook']['due_date']))))
		{
			if(isset($_POST['BorrowBook']['student_admission_no']) and $_POST['BorrowBook']['student_admission_no']!=NULL )
				$model->student_admission_no=$_POST['BorrowBook']['student_admission_no'];
			else
				$model->student_id=NULL;
			if(isset($_POST['BorrowBook']['subject']) and $_POST['BorrowBook']['subject']!=NULL )
				$model->subject=$_POST['BorrowBook']['subject'];
			else
				$model->subject=NULL;
			if(isset($_POST['BorrowBook']['book_id']) and $_POST['BorrowBook']['book_id']!=NULL )
				$model->book_id=$_POST['BorrowBook']['book_id'];
			else
				$model->book_id=NULL;
				
			if(isset($_POST['BorrowBook']['issue_date']) and $_POST['BorrowBook']['issue_date']!=NULL  and  $_POST['BorrowBook']['issue_date'] !='')
				$model->issue_date=date('Y-m-d',strtotime($_POST['BorrowBook']['issue_date']));
			else
				$model->issue_date='';
			if(isset($_POST['BorrowBook']['due_date']) and $_POST['BorrowBook']['due_date']!=NULL )
				$model->due_date=date('Y-m-d',strtotime($_POST['BorrowBook']['due_date']));
			else
				$model->due_date=NULL;
			$book=Book::model()->findByAttributes(array('id'=>$_POST['BorrowBook']['book_id']));
			$model->book_name=$book->title;
			
			if($model->validate()) {
				
				$student = Students::model()->findByAttributes(array('admission_no'=>$_POST['BorrowBook']['student_admission_no'],'is_deleted'=>0,'is_active'=>1));
				$model->student_id=$student->id;
				//echo $_POST['BorrowBook']['student_admission_no'];var_dump($student);exit;
				if($student==NULL)
				{
					Yii::app()->user->setFlash('errorMessage',Yii::t("app","Enter valid Student Admission Number!").' <br/>'.Yii::t('app',"The entered Admission Number is deleted or inactive"));
						$this->redirect(array('create','id'=>$_POST['BorrowBook']['student_admission_no']));
				}
				
				$status=Book::model()->findByAttributes(array('id'=>$_POST['BorrowBook']['book_id']));
			
				if($status!=NULL)
				{ 
					if($status->copy == 0)
					{
						$this->redirect(array('/library/BorrowBook/listbook/','id'=>$_POST['BorrowBook']['book_name']));
					}
					else if($status->copy==$status->copy_taken)
					{
						Yii::app()->user->setFlash('errorMessage',Yii::t("app","Copies are not available now! "));
						$this->redirect(array('/library/BorrowBook/listbook/','id'=>$_POST['BorrowBook']['book_name']));
					}
					else
					{
						if($model->issue_date)
							$model->issue_date=date('Y-m-d',strtotime($model->issue_date));
						$model->due_date=date('Y-m-d',strtotime($model->due_date));
						$model->status='C';
						$model->book_id=$status->id;
						$model->subject=$status->subject;
						$model->student_id=$student->id;
						$model->book_name=$status->title;
						if($model->save())
						{
						
							//echo $status->copy; exit;
							$status->saveAttributes(array('status'=>'S','copy_taken'=>($status->copy_taken)+1));
							$this->redirect(array('view','id'=>$model->id));
						}						
					}
							
					}
				else
				{
					$this->redirect(array('/library/BorrowBook/error/'));
				}
			
			}else
			{
				
			}
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
	public function actionAutocomplete() 
	 {
	  if (isset($_GET['term'])) {
		$criteria=new CDbCriteria;
		$criteria->alias = "first_name";
		//$criteria->condition = "title   like '%" . $_GET['term'] . "%'";
		$criteria->condition='first_name LIKE :match';
		 $criteria->params = array(':match' => $_GET['term'].'%');
		 $userArray = Students::model()->findAll($criteria);
		
		$hotels = Students::model()->findAll($criteria);
	
		$return_array = array();
		foreach($hotels as $hotel) {
		  $return_array[] = array(
						'label'=>$hotel->last_name.' '.$hotel->first_name  ,
						'id'=>$hotel->id,
						);
		}
		echo CJSON::encode($return_array);
	  }
	}
	public function actionStudentdetails()
	{
		$model=new BorrowBook;
		
		$this->render('studentdetails',array('model'=>$model));
		
	}
	public function actionListBook()
	{
		$id=$_REQUEST['id'];
		$model=new BorrowBook;
		
		$this->render('listbook',array('bid'=>$id));
		
	}
	public function actionError()
	{
		$model=new BorrowBook;
		
		$this->render('error',array('model'=>$model));
		
	}
	
	public function actionRemind()
	{
		$model=new BorrowBook;
		$id=$_REQUEST['id'];
		$headers='';
		$loggeduser=User::model()->findByAttributes(array('id'=>Yii::app()->user->id));
		$student=Students::model()->findByAttributes(array('id'=>$id));
		$to      = $student->email;
		$subject = Yii::t('app','Renewal of book');
		$message = Yii::t('app','Your due date will expire within').$_REQUEST['due'] .Yii::t('app','days.').Yii::t('app','To avoid fine please renew your book');
		$headers .= "From:".$loggeduser->email."\r\n";
		$headers .= "X-Sender-IP: $_SERVER[SERVER_ADDR]\r\n";
		$headers .= 'Date: '.date('n/d/Y g:i A')."\r\n";
        mail($to, $subject, $message, $headers);
		
	}
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('BorrowBook');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new BorrowBook('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['BorrowBook']))
			$model->attributes=$_GET['BorrowBook'];

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
		$model=BorrowBook::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='borrow-book-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
