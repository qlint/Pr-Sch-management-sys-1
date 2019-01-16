<?php

class SubjectsController extends RController
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
				'actions'=>array('index','view','Addnew','Addnew1'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','subjects'),
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
		$model=new Subjects;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Subjects']))
		{ 
			$model->attributes=$_POST['Subjects'];
			$data=SubjectName::model()->findByAttributes(array('id'=>$model->name));
			if($data!=NULL)
			{
				$model->name=$data->name;
				$model->code=$data->code;
				
			}
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

		if(isset($_POST['Subjects']))
		{
			$model->attributes=$_POST['Subjects'];
			$data=SubjectName::model()->findByAttributes(array('id'=>$model->name));
			if($data!=NULL)
			{
				$model->name=$data->name;
				$model->code=$data->code;
				
			}
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		            Yii::app()->clientScript->scriptMap['jquery.js'] = false;
                    $this->renderPartial('update',array('model'=>$model,'id'=>1,'batch_id'=>$_GET['val1']),false,true);
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
		$dataProvider=new CActiveDataProvider('Subjects');
		$model=new Subjects;
		$this->render('index',array(
			'model'=>$model,
		));
	}
	public function actionAddnew() {
                $model=new Subjects;
        // Ajax Validation enabled
        //$this->performAjaxValidation($model);
        // Flag to know if we will render the form or try to add 
        // new jon.
                $flag=true;
	   	if(isset($_POST['Submit']))
        {       $flag=false;
            $model->attributes=$_POST['Subjects'];
            $model->save();
              
                }
                if($flag) {
                    Yii::app()->clientScript->scriptMap['jquery.js'] = false;
                    $this->renderPartial('create',array('model'=>$model,'id'=>1,'batch_id'=>$_GET['val1']),false,true);
                }
        }
		public function actionAddnew1() {
                $model=new Subjects;
        // Ajax Validation enabled
        //$this->performAjaxValidation($model);
        // Flag to know if we will render the form or try to add 
        // new jon.
                $flag=true;
	   	if(isset($_POST['Submit']))
        {       $flag=false;
            $model->attributes=$_POST['Subjects'];
            $model->save();
              
                }
                if($flag) {
                    Yii::app()->clientScript->scriptMap['jquery.js'] = false;
                    $this->renderPartial('create1',array('model'=>$model,'id'=>2,'batch_id'=>$_GET['val1']),false,true);
                }
        }

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Subjects('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Subjects']))
			$model->attributes=$_GET['Subjects'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	public function actionSubjects()
	{
		$model=new Subjects('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['id']))
			$model->batch_id=$_GET['id'];

		$this->render('subjects',array(
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
		$model=Subjects::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='subjects-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
