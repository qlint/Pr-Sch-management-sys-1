<?php

class ElectiveExamsController extends RController
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
		$model=new ElectiveExams;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ElectiveExams']))
		{
			$model->attributes=$_POST['ElectiveExams'];
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
		public function actionUpdate($sid)
		{
		$model=$this->loadModel($sid);
		$old_model = $model->attributes; // For activity feed	
		
		
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
		if($settings!=NULL)
		{	
			$model->start_time=date($settings->displaydate.' '.$settings->timeformat,strtotime($model->start_time));
			$model->end_time=date($settings->displaydate.' '.$settings->timeformat,strtotime($model->end_time));
			$old_start_time = date($settings->displaydate.' '.$settings->timeformat,strtotime($old_model['start_time']));	// For activity feed
			$old_end_time = date($settings->displaydate.' '.$settings->timeformat,strtotime($old_model['end_time']));	// For activity feed
		}
		
	
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ElectiveExams']))
		{
			$model->attributes=$_POST['ElectiveExams'];
			$list = $_POST['ElectiveExams'];
			if($model->start_time)
			{
				
				$date1 = date('Y-m-d H:i',strtotime($list['start_time'][0]));
				$model->start_time = $date1; // To save
				$activity_start = date($settings->displaydate.' '.$settings->timeformat,strtotime($model->start_time)); // For activity feed
				
			}
			
			if($model->end_time)
			{
				$date2=date('Y-m-d H:i',strtotime($list['end_time'][0]));
				$model->end_time=$date2; // To save
				$activity_end = date($settings->displaydate.' '.$settings->timeformat,strtotime($model->end_time)); // For activity feed
			}
			$results = array_diff_assoc($model->attributes,$old_model); // To get the fields that are modified. 
			/*print_r($old_model);echo ' - OLD<br/><br/>';
			print_r($_POST['Exams']);echo ' - NEW<br/><br/>';
			//print_r($model);echo '<br/><br/>';
			print_r($results);echo ' - Modified Fields<br/><br/>';
			echo $old_start_time.' to '.$activity_start.'<br/><br/>';
			echo $old_end_time.' to '.$activity_end;*/
			
			if($model->save())
			{
				
				// Saving to activity feed
				$results = array_diff_assoc($model->attributes,$old_model); // To get the fields that are modified. 
				
				foreach($results as $key => $value)
				{
					echo $key;
					if($key!='updated_at')
					{
						if($key == 'start_time')
						{
							$value = $activity_start;
							$old_model[$key] = $old_start_time;//echo '</br/>-'.$old_model[$key].' to '.$value.'<br/><br/>';
						}
						elseif($key == 'end_time')
						{
							$value = $activity_end;
							$old_model[$key] = $old_end_time;
						}
						
						//$subject_name = Subjects::model()->findByAttributes(array('id'=>$model->subject_id));
						///$examgroup = ExamGroups::model()->findByAttributes(array('id'=>$model->exam_group_id));
						//$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
						//$exam = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
						
						//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
						//ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'18',$model->id,$exam,$model->getAttributeLabel($key),$old_model[$key],$value); 
						
						 
					}
				}	
				//END saving to activity feed
			
				
				$this->redirect(array('exams/create','id'=>$_REQUEST['id'],'exam_group_id'=>$_REQUEST['exam_group_id']));
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
			$model = ElectiveExams::model()->findByAttributes(array('id'=>$id));
			//$subject_name = Subjects::model()->findByAttributes(array('id'=>$model->subject_id));
			//$examgroup = ExamGroups::model()->findByAttributes(array('id'=>$model->exam_group_id));
			//$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
			//$exam = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();
			
			//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
			//ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'19',$model->id,$exam,NULL,NULL,NULL); 
			
			
			

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
		$dataProvider=new CActiveDataProvider('ElectiveExams');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ElectiveExams('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ElectiveExams']))
			$model->attributes=$_GET['ElectiveExams'];

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
		$model=ElectiveExams::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='elective-exams-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
