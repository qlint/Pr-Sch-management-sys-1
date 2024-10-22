<?php

class WeekdaysController extends RController
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
				'actions'=>array('index','view','Timetable','Addnew','Publish','Exportpdf','pdf'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','batch'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','fulltimetable'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function beforeAction(){
		$action				= Yii::app()->controller->action->id;
		$redirect_actions	= array('timetable', 'pdf', 'fullpdf');
		if(in_array($action, $redirect_actions)){
			if(Configurations::model()->timetableFormat(isset($_REQUEST['id'])?$_REQUEST['id']:NULL)==2){ // timetable format is not selected as course level					
				$params	= array('/timetable/flexible/'.$action);
				foreach($_REQUEST as $key=>$param){
					if($key!="r"){
						$params[$key]	= $param;
					}
				}
				$this->redirect($params);
			}
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
		$model=new Weekdays;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Weekdays']))
		{
			$model->attributes=$_POST['Weekdays'];
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

		if(isset($_POST['Weekdays']))
		{
			$model->attributes=$_POST['Weekdays'];
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
		if(!isset($_REQUEST['type']) and !isset($_REQUEST['id']))
		{
			throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
		}
		$model=new Weekdays;
		if(isset($_POST['Weekdays']))
		{
			
	
			$weekdays = array('sunday','monday','tuesday','wednesday','thursday','friday','saturday');
			if((isset($_REQUEST['id']) and $_REQUEST['id']=='NULL') or (isset($_REQUEST['type']) and $_REQUEST['type']=='default'))
			{
			$batch = $model->findAll("batch_id IS NULL");
			}
			else
			{
			$batch = $model->findAll("batch_id=:x", array(':x'=>$_REQUEST['id']));
			}
			foreach ($batch as $child)
				{
					$child->delete();
				}
			$i=0;
			for($j=0;$j<count($_POST['Weekdays']);$j++)
			{
				$weekday = new Weekdays;
				
				if((isset($_REQUEST['id']) and $_REQUEST['id']=='NULL') or (isset($_REQUEST['type']) and $_REQUEST['type']=='default'))
				{
					
					$weekday->weekday = $_POST['Weekdays'][$weekdays[$i]];
					$weekday->save();
				
				}
				else
				{
					$weekday->weekday = $_POST['Weekdays'][$weekdays[$i]];
					$weekday->batch_id = $_REQUEST['id'];
					$weekday->save();
				
				}
				$i++;
			}
			 Yii::app()->user->setFlash('notification',Yii::t('app','Data Saved Successfully'));
			
		}
		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	 public function actionAddnew() {
        $model=new ClassTimings;
        // Ajax Validation enabled
        $this->performAjaxValidation($model);
        // Flag to know if we will render the form or try to add 
        // new jon.
                $flag=true;
        if(isset($_POST['Submit']))
        {       $flag=false;
            $model->attributes=$_POST['ClassTimings'];
 
            $model->save();
			//$this->redirect(array('classTimings'));
                }
                if($flag) {
                    Yii::app()->clientScript->scriptMap['jquery.js'] = false;
                    $this->renderPartial('create',array('model'=>$model,'id'=>$_GET['val1']),false,true);
                }
        }
	
	public function actionAdmin()
	{
		$model=new Weekdays('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Weekdays']))
			$model->attributes=$_GET['Weekdays'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	public function actionTimetable()
	{
		$model=new Weekdays;
		if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL)
		{ 
			$model->mydropdownlist=$_REQUEST['id'];
		}
		
		$this->render('timetable',array(
		'model'=>$model,
		));
	}
	
	public function actionFulltimetable()
	{
		$model=new Weekdays;
		if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL)
		{ 
			$model->mydropdownlist=$_REQUEST['id'];
		}
		//var_dump($_REQUEST['id']);exit;
		$this->render('fulltimetable',array(
		'model'=>$model,
		));
	}
	
	public function actionCoursename()
	{			
		$data=Courses::model()->findAll('academic_yr_id=:id AND is_deleted=:y',array(':id'=>(int) $_POST['yid'],':y'=>0));
		echo CHtml::tag('option', array('value' => ''), CHtml::encode(Yii::t('app','Select Course')), true);
		echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','All Courses')), true);
		$data=CHtml::listData($data,'id','course_name');
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option',array('value'=>$value),CHtml::encode(html_entity_decode($name)),true);
		}
	}
	
	public function actionBatchname()
	{			
		$data=Batches::model()->findAll('course_id=:id AND is_active=:x AND is_deleted=:y',array(':id'=>(int) $_POST['cid'],':x'=>1,':y'=>0));
		echo CHtml::tag('option', array('value' => ''), CHtml::encode(Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id")), true);
		echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','All').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id")), true);
		$data=CHtml::listData($data,'id','name');
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option',array('value'=>$value),CHtml::encode(html_entity_decode($name)),true);
		}
	}
	
	
	public function actionExportpdf()
	{
		$model=new Weekdays;
		if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL)
	   { 
	   $model->mydropdownlist=$_REQUEST['id'];
	   }
		

		$this->render('Exportpdf',array(
			'model'=>$model,
		)); 
	}
	 public function actionPdf()
     {
		$batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$batch_name = $batch_name->name.' Class Timetable.pdf';               
                
                $filename= $batch_name->name.' Class Timetable.pdf';
                Yii::app()->osPdf->generate("application.modules.timetable.views.weekdays.exportpdf", $filename, array(),1);
        ////////////////////////////////////////////////////////////////////////////////////
	}
	public function actionFullPdf()
     {
		$batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));		
                                                
                $filename= $batch_name->name.' Class Timetable.pdf';
                Yii::app()->osPdf->generate("application.modules.timetable.views.weekdays.fulltimetablepdf", $filename, array(),1);
        ////////////////////////////////////////////////////////////////////////////////////
	}
	public function actionPublish()
	{
		
		$model=new Weekdays;
		$start_date = Configurations::model()->findByAttributes(array('id'=>'13'));
		$end_date = Configurations::model()->findByAttributes(array('id'=>'14'));
		
		$present = PeriodEntries::model()->findByAttributes(array('batch_id'=>$_REQUEST['id']));
		if(count($present)!==0)
		{
			
			$perii=PeriodEntries::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id']));
			foreach($perii as $perii_1)
			{
			$perii_1->delete();
			}
			//$this->redirect(array('timetable','id'=>$_REQUEST['id']));	
		}
		
		$i=0;
		$date = strtotime(date("Y-m-d", strtotime($start_date->config_value)) ." +".$i." day");
			do{
				
				$date = strtotime(date("Y-m-d", strtotime($start_date->config_value)) ." +".$i." day");
				$week = date('N', strtotime(date('Y-m-d', $date)));
				if($week == 1)
				{
					$day = 7;
				}
				else
				{
					$day = $week+1;
				}
				$flag = Weekdays::model()->findByAttributes(array('batch_id'=>$_REQUEST['id'],'weekday'=>$day));
				if(count($flag)!=0)
				{
					$period = new PeriodEntries;
					$period->month_date = date('Y-m-d', $date);
					$period->batch_id 	= $_REQUEST['id'];	
					$period->save();
				}
				else
				{
					$period = new PeriodEntries;
					$period->month_date = date('Y-m-d', $date);
					$period->batch_id 	= $_REQUEST['id'];	
					$period->save();
				}
				
				$i++;
				/*{
					$this->redirect(array('timetable','id'=>$_REQUEST['id']));
				}*/
			}while(date('Y-m-d', $date) == $end_date->config_value);
		//exit;
		$this->redirect(array('timetable','id'=>$_REQUEST['id']));
		/*$this->render('timetable',array(
			'model'=>$model,
		));*/
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Weekdays::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='weekdays-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionBatch()
	{
		$data=Batches::model()->findAll('course_id=:id', 
                  array(':id'=>(int) $_POST['cid']));
 
         $data=CHtml::listData($data,'id','name');
		 echo CHtml::tag('option', array('value' => 0), CHtml::encode('-Select-'), true);
		  foreach($data as $value=>$name)
		  {
			  echo CHtml::tag('option',
						 array('value'=>$value),CHtml::encode(html_entity_decode($name)),true);
		  }
	}
	public function actionViewelective()
	{
		Yii::app()->clientScript->scriptMap['jquery.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
		
		$this->renderPartial('viewelective',array('id'=>$_REQUEST['id']),false,true);		
	}
}
