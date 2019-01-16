<?php

class ExamsController extends RController
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
		$model=new Exams;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Exams']))
		{
			$model->attributes=$_POST['Exams'];
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

		if(isset($_POST['Exams']))
		{
			$model->attributes=$_POST['Exams'];
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
			throw new CHttpException(400,Yii::t('app', 'Invalid request. Please do not repeat this request again.'));
	}


	//List all exams
	/*public function actionAllexams()
	{
		$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		$this->render('allexams',array('employee_id'=>$employee->id));
	}*/
	
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->render('index');
	}
	public function actionAllExam()
	{
		$this->render('allexam');
	}
	
	public function actionClassExam()
	{
		$this->render('classexam');
	}
//Listing of exam List	
	public function actionClassexams()
	{
		$this->render('classexams',array('batch_id'=>$_REQUEST['bid']));
	}
	public function actionAllexams()
	{
		$this->render('allexams',array('batch_id'=>$_REQUEST['bid']));
	}
//Listing of scheduled subjects	
	public function actionClassexamschedule()
	{
		$this->render('classexamschedule',array('batch_id'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id']));
	}
	public function actionAllexamschedule()
	{		
		$this->render('allexamschedule',array('employee_id'=>$_REQUEST['employee_id'],'batch_id'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id']));
	}
//Dispaly exam result	
	public function actionClassexamresult()
	{
		$this->render('classexamresult',array('employee_id'=>$_REQUEST['employee_id'],'batch_id'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id']));
	}
	public function actionAllexamresult()
	{
		$this->render('allexamresult',array('batch_id'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id']));
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Exams('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Exams']))
			$model->attributes=$_GET['Exams'];

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
		$model=Exams::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='exams-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		
	}
        
        public function actionTermExam()
        {
            $this->render("cbsc_exam");
        }
		
		 public function actionResults()
        {
			if(isset($_REQUEST['bid']))
                    {
                        $criteria               = new CDbCriteria;           
						$criteria->join         = "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`id`";           
						$criteria->condition    = "`t`.`is_active`=1 AND `t`.`is_deleted`=0 AND `bs`.`batch_id`=:batch_id AND `bs`.`status`=1 AND `bs`.`result_status`=0";
						$criteria->params       = array(":batch_id"=>$_REQUEST['bid']);
						$criteria->order        = "`t`.`first_name` ASC, `t`.`last_name` ASC";
                        $students_list = Students::model()->findAll($criteria);
						

                        $total = Students::model()->count($criteria);
                        $pages = new CPagination($total);
                        $pages->setPageSize(Yii::app()->params['listPerPage']);
                        $pages->applyLimit($criteria);  // the trick is here!
                        $students = Students::model()->findAll($criteria);
						
                        
                        $this->render('results',array(
                        'students'=>$students,
                        'pages' => $pages,
                        'item_count'=>$total,
                        'page_size'=>Yii::app()->params['listPerPage'],)) ;
                        
                    }
        }
		public function actionResult()
        {
            $this->render("student_result");
        }
		public function actionStudentcbscpdf()
		{
			
			$student_name   = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
			$exam_name      = CbscExamGroup17::model()->findByAttributes(array('id'=>$_REQUEST['exam_group_id']));
			$filename= ucfirst($student_name->first_name).' '.ucfirst($student_name->last_name).' '.ucfirst($exam_name->name).Yii::t('app',' Assessment').' Report.pdf';
			if($exam_name->class==1) //class 1-2
			{
				Yii::app()->osPdf->generate("application.modules.teachersportal.views.exams.studentexampdf1", $filename, array());
			}
			else if($exam_name->class==2){ //class 3-8
			
				Yii::app()->osPdf->generate("application.modules.teachersportal.views.exams.studentexampdf2", $filename, array());
			}
			else if($exam_name->class==3){ //class 9-10
			
				Yii::app()->osPdf->generate("application.modules.teachersportal.views.exams.studentexampdf3", $filename, array());
			}
			else if($exam_name->class==4){ //class 11-12
			
			
				Yii::app()->osPdf->generate("application.modules.teachersportal.views.exams.studentexampdf4", $filename, array());
			}
		}

		public function actionPdf()
        {
            $filename	= "report.pdf";
			Yii::app()->osPdf->generate("application.modules.teachersportal.views.exams.pdf", $filename, array(), 0);
        }
}
