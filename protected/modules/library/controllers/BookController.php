<?php

class BookController extends RController
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
				'actions'=>array('index','view','booksearch','bookdetails','autocomplete','manage','subjects','booklist','allbooks','autocomplete1'),
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
		$model	= new Book;
		if(isset($_POST['Book'])){
			$authordetails		= Author::model()->findByAttributes(array('author_name'=>$_POST['Book']['author']));
			$publication		= Publication::model()->findByAttributes(array('name'=>$_POST['Book']['publisher']));
			$model->attributes	= $_POST['Book'];
			if($model->validate()){	
				//If the publisher is not availbale, create new publisher & save that id to book		
				if($publication == NULL){
					$publisher			= new Publication;
					$publisher->name	= $_POST['Book']['publisher'];
					if($publisher->save()){
						$model->publisher	= $publisher->publication_id;
					}
				}
				else{
					$model->publisher		= $publication->publication_id;				
				}
				//If the author is not availbale, create new author & save that id to book
				if($authordetails){
					$model->author	= $authordetails->auth_id;						
				}
				else{
					$author					= new Author;
					$author->author_name	= $_POST['Book']['author'];
					if($author->save()){
						$model->author			= $author->auth_id;					
					}
				}
				
				if($model->save()){				
					$model->status	= 'C';
					$this->redirect(array('view', 'id'=>$model->id));
				}				
			}
		}
		$this->render('create',array('model'=>$model));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
             
			  $authordetails=Author::model()->findByAttributes(array('auth_id'=>$model->author));
			  $publication=Publication::model()->findByAttributes(array('publication_id'=>$model->publisher));
			  $pub_id = $model->publisher;
			  $auth_id = $model->author;
			  $model->author=$authordetails->author_name;
			  $model->publisher=$publication->name;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Book']))
		{ 
			//$model->attributes=$_POST['Book'];
			$authordetails=Author::model()->findByAttributes(array('author_name'=>$_POST['Book']['author']));
			$publication=Publication::model()->findByAttributes(array('name'=>$_POST['Book']['publisher']));
			$model->attributes=$_POST['Book'];
			if($model->validate())
			{
				if($authordetails==NULL)
				{
					$auther1=Author::model()->findByAttributes(array('auth_id'=>$auth_id));
					$auther1->author_name=$_POST['Book']['author'];
					$auther1->save();
					$model->author=$auther1->auth_id;
					//$model->author=$authordetails->auth_id;	
				}
				else
				{
					$model->author=$authordetails->auth_id;
					
				}
				if($publication==NULL)
				{
					
				$publication1=Publication::model()->findByAttributes(array('publication_id'=>$pub_id));
				$publication1->name=$_POST['Book']['publisher'];
				$publication1->save();
				$model->publisher=$publication1->publication_id;
				}
				else
				{
					$model->publisher=$publication->publication_id;
					
				}
				if($model->save())
					$this->redirect(array('view', 'id'=>$model->id));
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
	
	public function actionRemove($id)
	{
		// we only allow deletion via POST request
		//$this->loadModel($id)->delete();
		if(Yii::app()->request->isPostRequest){
			$book = Book::model()->findByAttributes(array('id'=>$id));
			$book->is_deleted = '1';
			$book->save();
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			//if(!isset($_GET['ajax']))
	//			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('manage'));
			$this->redirect(array('manage'));
		}
		else
		{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Book');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	public function actionBookSearch()
	{
		$roles=Rights::getAssignedRoles(Yii::app()->user->Id);
		if(isset($_POST['search']))
		{
		if(isset($_POST['search']) && isset($_POST['text']))
		{
			
			$criteria = new CDbCriteria;
			//$criteria->compare('is_deleted',0);  // normal DB field
		    $criteria->condition='is_deleted=0';
		
		    //$criteria->params = array(':is_del'=>0);
			if($_POST['search']==1)
			{						
				if($_POST['text']!=NULL)
				{	
					$criteria->condition = $criteria->condition.' AND subject LIKE :match';
					$criteria->params = array(':match' => $_POST['text'].'%');
				}				
			}
			if($_POST['search']==2)
			{
			$criteria->condition=$criteria->condition.' AND title LIKE :match';
		 	$criteria->params = array(':match' => $_POST['text'].'%');
			
			}
			if($_POST['search']==3)
			{
				
				$author=Author::model()->findByAttributes(array('author_name'=>$_POST['text']));
				
			
				if($author!=NULL and $_POST['text'])
				{
					$criteria->condition=$criteria->condition.' AND author LIKE :match';
		 			$criteria->params = array(':match' => $author->auth_id);
				}
			
			}
			if($_POST['search']==4)
			{
			$criteria->condition=$criteria->condition.' AND isbn LIKE :match';
		 	$criteria->params = array(':match' => $_POST['text'].'%');
			
			}
		}
	
		$total = Book::model()->count($criteria);
		$pages = new CPagination($total);
        $pages->setPageSize(Yii::app()->params['listPerPage']);
		$pages->applyLimit($criteria);  // the trick is here!
		$posts = Book::model()->findAll($criteria);
		
		foreach($roles as $role)
		{				
			if(sizeof($roles)==1 and $role->name == 'student')//if the current role is student,it render stud_booksearch.php page else it take booksearch.php page
			{
				$this->render('stud_booksearch',array(
				'list'=>$posts,
				'pages' => $pages,
				'item_count'=>$total,
				'page_size'=>Yii::app()->params['listPerPage'],
				));	
				
			}
			else
			{
				$this->render('booksearch',array(
				'list'=>$posts,
				'pages' => $pages,
				'item_count'=>$total,
				'page_size'=>Yii::app()->params['listPerPage'],
				));	
			}
		}
	}
	else
	{
		foreach($roles as $role)
		{				
			if(sizeof($roles)==1 and $role->name == 'student')//if the current role is student,it render stud_booksearch.php page else it take booksearch.php page
			{
				$this->render('stud_booksearch');
			}
			else
			{
				$this->render('booksearch');
			}
		}
	}
	}
	public function actionManage()
	{
		$model=new Book;
		$roles=Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role
		foreach($roles as $role)
		{				
			if(sizeof($roles)==1 and $role->name == 'student')
			{
				$this->render('stud_manage',array('model'=>$model));//if the current role is student,it render stud_manage.php page else it take manage.php page
			}
			else
			{
				$this->render('manage',array('model'=>$model));
			}
		}
	}
	public function actionBookdetails()
	{
		$model=new Book;
		$this->render('bookdetails',array('model'=>$model));
		
	}
	public function actionSubjects()
	{
		$model = new BorrowBook;
		if(isset($_POST['BorrowBook']['subject']))
		{
			$criteria=new CDbCriteria;			
			$criteria->condition='subject =:subject and is_deleted=:is_deleted';
			$criteria->params = array(':subject' => $_POST['BorrowBook']['subject'], ':is_deleted'=>0);
			$data=Book::model()->findAll($criteria);
			//$data=Book::model()->findAll('subject=:x and is_deleted=:y',array(':x'=>$_POST['BorrowBook']['subject'],':y'=>0));
		}
		else if(isset($_POST['subject']))
		{
			$criteria=new CDbCriteria;			
			$criteria->condition='subject =:subject and is_deleted=:is_deleted';
			$criteria->params = array(':subject' => $_POST['subject'], ':is_deleted'=>0);
			$data=Book::model()->findAll($criteria);
			//$data=Book::model()->findAll('subject=:x and is_deleted=:y',array(':x'=>$_POST['subject'],':y'=>0));
		}
		echo CHtml::tag('option', array('value' => ''), CHtml::encode('Select'), true);
		$data=CHtml::listData($data,'id','title');
		  foreach($data as $value=>$title)
		  {
			  echo CHtml::tag('option',
						 array('value'=>$value),CHtml::encode($title),true);
		  }
	}
	public function actionCheckSubjects()
	{
		$model_2=BorrowBook::model()->findAll('student_id=:x and book_id=:q and status=:y',array(':x'=>$_POST['stud_id'],'q'=>$_POST['book_id'],':y'=>C));
		if(count($model_2))
		{
			echo Yii::t('app',"Student already taken this Book");
		}
	}
	public function actionBooklist()
	{
		$model=new Book;
		$this->render('booklist',array('model'=>$model,'book_id'=>$_POST['book']));
	}
	public function actionAllbooks()
	{
		$model=new Book;
		$this->render('allbooks',array('model'=>$model,'book_id'=>$_POST['book']));
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Book('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Book']))
			$model->attributes=$_GET['Book'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	public function actionAutocomplete() 
	 {
	  if (isset($_GET['term'])) {
		$criteria=new CDbCriteria;
		$criteria->alias = "title";
		//$criteria->condition = "title   like '%" . $_GET['term'] . "%'";
		$criteria->condition='title LIKE :match';
		 $criteria->params = array(':match' => $_GET['term'].'%');
		 $criteria->addSearchCondition('is_deleted', 0);
		 $userArray = Book::model()->findAll($criteria);
		
		$hotels = Book::model()->findAll($criteria);
	
		$return_array = array();
		foreach($hotels as $hotel) {
		  $return_array[] = array(
						'label'=>$hotel->id.$hotel->title,
						'value'=>$hotel->title,
						'id'=>$hotel->id,
						);
		}
		echo CJSON::encode($return_array);
	  }
	}
	public function actionAutocomplete1() 
	 {
	  if (isset($_GET['term'])) {
		$criteria=new CDbCriteria;
		$criteria->alias = "auth_name";
		//$criteria->condition = "title   like '%" . $_GET['term'] . "%'";
		$criteria->condition='author_name LIKE :match';
		 $criteria->params = array(':match' => $_GET['term'].'%');
		 $userArray = Author::model()->findAll($criteria);
		
		$hotels = Author::model()->findAll($criteria);
	
		$return_array = array();
		foreach($hotels as $hotel) {
		  $return_array[] = array(
						'label'=>$hotel->author_name,
						'value'=>$hotel->author_name,
						'id'=>$hotel->auth_id,
						);
		}
		echo CJSON::encode($return_array);
	  }
	}
	
//List subjects
	public function actionListSubject()
	{
		if(isset($_GET['term']) and $_GET['term']!=NULL){
			$subject_arr = array();
			//Select subjects from subject common pool
			$criteria=new CDbCriteria;			
			$criteria->condition='subject_name LIKE :subject_name';
			$criteria->params = array(':subject_name' => $_GET['term'].'%');
			$subjects = SubjectsCommonPool::model()->findAll($criteria);
			if($subjects){
				foreach($subjects as $subject){
					$subject_arr[] = ucfirst(strtolower($subject->subject_name));
				}
			}
			//Select elecives
			$criteria1=new CDbCriteria;			
			$criteria1->condition='name LIKE :match';
			$criteria1->params = array(':match' => $_GET['term'].'%');
			$electives = Electives::model()->findAll($criteria1);
			if($electives){
				foreach($electives as $elective){
					if(!in_array(ucfirst(strtolower($elective->name)), $subject_arr)){
						$subject_arr[] = ucfirst(strtolower($elective->name));
					}
				}
			}				
			echo CJSON::encode($subject_arr);			
		}
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Book::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='book-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionBookBorrowed()
	{
		$student = Students::model()->findByAttributes(array('uid'=>Yii::app()->user->Id));
		$criteria = new CDbCriteria;
		$criteria->condition = 'student_id=:student_id';
		$criteria->params = array(':student_id'=>$student->id);
		
		$total = BorrowBook::model()->count($criteria);
		$pages = new CPagination($total);
        $pages->setPageSize(Yii::app()->params['listPerPage']);
        $pages->applyLimit($criteria);  
		$bookLists = BorrowBook::model()->findAll($criteria);
		
		$this->render('bookBorrowed',array('bookLists'=>$bookLists, 'pages' => $pages, 'item_count'=>$total, 'page_size'=>Yii::app()->params['listPerPage']));
	}
}
