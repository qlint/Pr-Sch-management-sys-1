<?php

class ElectiveScoresController extends RController
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
				'actions'=>array('admin','delete','deleteall'),
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
		
		$model=new ElectiveScores;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ElectiveScores']))
		{
			
			$list = $_POST['ElectiveScores'];
			$count = count($list['student_id']);
			
			for($i=0;$i<$count;$i++)
			{
				if($list['marks'][$i]!=NULL or $list['remarks'][$i]!=NULL)
				{
					$exam = ElectiveExams::model()->findByAttributes(array('id'=>$_REQUEST['elective']));
					
					$model=new ElectiveScores;
						
					$model->exam_id = $list['exam_id']; 
					$model->student_id = $list['student_id'][$i];
					$model->marks = $list['marks'][$i];
					$model->remarks = $list['remarks'][$i];
					$model->grading_level_id = $list['grading_level_id'];
					if(($list['marks'][$i])< ($exam->minimum_marks)) 
					{
						$model->is_failed = 1;
					}
					else
					{
						$model->is_failed = '';
					}
					$model->created_at = $list['created_at'];
					$model->updated_at = $list['updated_at'];
					//$model->save();
					if($model->save())
					{
						//$student = Students::model()->findByAttributes(array('id'=>$model->student_id));
						//$student_name = ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);
						
						//$subject_name = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
						//$examgroup = ExamGroups::model()->findByAttributes(array('id'=>$exam->exam_group_id));
						//$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
						//$exam = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
						//$goal_name = $student_name.' for the exam '.$exam;
						
						
						//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
						//ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'20',$model->id,$goal_name,NULL,NULL,NULL); 
					}
				}
			}
				$this->redirect(array('electiveScores/create','id'=>$_REQUEST['id'],'examid'=>$_REQUEST['examid'],'elective'=>$_REQUEST['elective']));
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
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ElectiveScores']))
		{
			$model->attributes=$_POST['ElectiveScores'];
			$exam = ElectiveExams::model()->findByAttributes(array('id'=>$_REQUEST['elective']));
			if(($model->marks)< ($exam->minimum_marks)) 
			{
				$model->is_failed = 1;
			}
			else
			{
				$model->is_failed = '';
			}
			
			if($model->save())
			{
				// Saving to activity feed
				$results = array_diff_assoc($model->attributes,$old_model); // To get the fields that are modified. 
				foreach($results as $key => $value)
				{
					if($key!='updated_at')
					{
						
						
						
						if($key=='is_failed')
						{
							if($value == 1)
							{
								$value = Yii::t('app','Fail');
							}
							else
							{
								$value = Yii::t('app','Pass');
							}
							
							if($old_model[$key] == 1)
							{
								$old_model[$key] = Yii::t('app','Fail');
							}
							else
							{
								$old_model[$key] = Yii::t('app','Pass');
							}
						}
						
						
						//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
						//ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'21',$model->id,$goal_name,$model->getAttributeLabel($key),$old_model[$key],$value); 
					}
				}
				//END saving to activity feed
				$this->redirect(array('electiveScores/create','id'=>$_REQUEST['id'],'examid'=>$_REQUEST['examid'],'elective'=>$_REQUEST['elective']));
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
			$model = ElectiveScores::model()->findByAttributes(array('id'=>$id));
			
			//$student = Students::model()->findByAttributes(array('id'=>$model->student_id));
			//$student_name = ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);
			
			//$exam = Exams::model()->findByAttributes(array('id'=>$model->exam_id));
		//	$subject_name = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
			//$examgroup = ExamGroups::model()->findByAttributes(array('id'=>$exam->exam_group_id));
		//	$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
		//	$exam_name = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
		//	$goal_name = $student_name.' for the exam '.$exam_name;
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();
			
			//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
			//ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'22',$model->id,$goal_name,NULL,NULL,NULL); 
			
			
			
			
			
			
			
			
			// we only allow deletion via POST request
			//$this->loadModel($id)->delete();

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
		$dataProvider=new CActiveDataProvider('ElectiveScores');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	public function actionDeleteall()
	{
		if(Yii::app()->request->isPostRequest){
			$delete = ElectiveScores::model()->findAllByAttributes(array('exam_id'=>$_REQUEST['examid']));
			foreach($delete as $delete1)
			{
				$delete1->delete();
			}
			
			
			$this->redirect(array('create','id'=>$_REQUEST['id'],'examid'=>$_REQUEST['examid'],'elective'=>$_REQUEST['elective']));
		}
		else
		{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ElectiveScores('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ElectiveScores']))
			$model->attributes=$_GET['ElectiveScores'];

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
		$model=ElectiveScores::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='elective-scores-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
