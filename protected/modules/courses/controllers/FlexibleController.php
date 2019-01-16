<?php

class FlexibleController extends RController
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
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function beforeAction(){
		$action				= Yii::app()->controller->action->id;
		$redirect_actions	= array('timetable', 'pdf');
		if(in_array($action, $redirect_actions)){
			if(Configurations::model()->timetableFormat(isset($_REQUEST['id'])?$_REQUEST['id']:NULL)==1){ // timetable format is fixed				
				$params	= array('/courses/weekdays/'.$action);
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
		/*$dataProvider=new CActiveDataProvider('Weekdays');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));*/
		$model=new Weekdays;
		if(isset($_POST['Weekdays']))
		{
			$batches 		= $model->findAll("batch_id IS NOT NULL");
			$batchids_array	= CHtml::listData($batches, "batch_id", "batch_id");
			
			$weekdays = array('sunday','monday','tuesday','wednesday','thursday','friday','saturday');
			if((isset($_REQUEST['id']) and $_REQUEST['id']=='NULL') or !isset($_REQUEST['id']))
			$batch = $model->findAll("batch_id IS NULL");
			else
			$batch = $model->findAll("batch_id=:x", array(':x'=>$_REQUEST['id']));
			foreach ($batch as $child)
				{
					$child->delete();
				}
			$i=0;
			for($j=0;$j<count($_POST['Weekdays']);$j++)
			{
				$weekday = new Weekdays;
				
				if((isset($_REQUEST['id']) and $_REQUEST['id']=='NULL') or !isset($_REQUEST['id']))
				{
					if($_POST['Weekdays'][$weekdays[$i]]==0){
						$criteria				= new CDbCriteria;
						$criteria->condition	= "`t`.`weekday_id`=:weekday";	
						$criteria->params		= array(":weekday"=>$i+1);
						$criteria->addNotInCondition('`t`.`batch_id`', $batchids_array);
						$timetableEntries		= TimetableEntries::model()->findAll($criteria);
						foreach ($timetableEntries as $entry){
							$entry->delete();
						}
					}
					
					$weekday->weekday = $_POST['Weekdays'][$weekdays[$i]];
					$weekday->save();
				
				}
				else
				{
					//clear all timetable entries if weekday is not set
					if($_POST['Weekdays'][$weekdays[$i]]==0){
						$criteria				= new CDbCriteria;
						$criteria->condition	= "`t`.`batch_id`=:batch AND `t`.`weekday_id`=:weekday";	
						$criteria->params		= array(":batch"=>$_REQUEST['id'], ":weekday"=>$i+1);
						$timetableEntries		= TimetableEntries::model()->findAll($criteria);
						foreach ($timetableEntries as $entry){
							$entry->delete();
						}
					}
				
					$weekday->weekday = $_POST['Weekdays'][$weekdays[$i]];
					$weekday->batch_id = $_REQUEST['id'];
					$weekday->save();
				
				}
				$i++;
			}
			 Yii::app()->user->setFlash('notification',Yii::t('app','Data Saved'));
			
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
            $batch_name = strtolower($batch_name->name).'-class-timetable.pdf';
            /*
            # HTML2PDF has very similar syntax
            $html2pdf = Yii::app()->ePdf->HTML2PDF();
            $html2pdf = new HTML2PDF('P', 'A4', 'en');
            $html2pdf->setDefaultFont('freesans');
            $html2pdf->WriteHTML($this->renderPartial('application.modules.timetable.views.weekdays.exportpdf', array(), true));
            $html2pdf->Output($batch_name);
            */
            Yii::app()->osPdf->generate("application.modules.courses.views.flexible.exportpdf", $batch_name, array(), 0, "", "A4", 5, 5, 5, 5);
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
						 array('value'=>$value),CHtml::encode($name),true);
		  }
	}
}
