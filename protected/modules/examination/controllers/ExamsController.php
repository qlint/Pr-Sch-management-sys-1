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
				'actions'=>array('admin','delete','manage'),
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
	
	public function checkBatchActive($id) //Check whether the batch is active
	{
		$batch = Batches::model()->findByAttributes(array('id'=>$id, 'is_active'=>1, 'is_deleted'=>0));
		if($batch == NULL){
			$this->redirect(array('/examination'));
		}		
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate1()
	{
		$model=new Exams;
		$model_1=new ExamGroups;
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Exams']))
		{
			
			//$model->attributes=$_POST['Exams'];
			if(isset($_REQUEST['exam_group_id']))
			{
				$insert_id=$_REQUEST['exam_group_id'];
			}
			else
			{
				$model_1->attributes=$_POST['ExamGroups'];
				$model_1->batch_id = $_REQUEST['id']; 
				$model_1->save();
				$insert_id = Yii::app()->db->getLastInsertID();
			}
			$posts=Subjects::model()->findAll("batch_id=:x AND no_exams=:y", array(':x'=>$_REQUEST['id'],':y'=>0));
			$list = $_POST['Exams'];
			$count = count($list['subject_id']);
			$electivecount = count($_POST['ElectiveExams']['elective_id']);
			
			$j=0;
			for($i=0;$i<$count;$i++)
			{
				
				if($list['maximum_marks'][$i]!=NULL and $list['minimum_marks'][$i]!=NULL)
				{	
					$model=new Exams;
					$model->exam_group_id = $insert_id; 
					$model->subject_id = $list['subject_id'][$i];
					$model->maximum_marks = $list['maximum_marks'][$i];
					$model->minimum_marks = $list['minimum_marks'][$i];
					$model->start_time = $list['start_time'][$i];
					$model->end_time = $list['end_time'][$i];
					if($model->start_time)
					{
						$date1=date('Y-m-d H:i',strtotime($model->start_time));
						$model->start_time=$date1;
					}
					
					if($model->end_time)
					{
						$date2=date('Y-m-d H:i',strtotime($model->end_time));
						$model->end_time=$date2;
					}
					$model->grading_level_id = $list['grading_level_id'];
					$model->weightage = $list['weightage'];
					$model->event_id = $list['event_id'];
					$model->created_at = $list['created_at'];
					$model->updated_at = $list['updated_at'];
					
					//$model->save();
					if($model->save())
					{
														
						$subject_name = Subjects::model()->findByAttributes(array('id'=>$model->subject_id));
						$examgroup = ExamGroups::model()->findByAttributes(array('id'=>$model->exam_group_id));
						$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
						$exam = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
						
						//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
						ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'17',$model->id,$exam,NULL,NULL,NULL); 
												
					}
					
				}
			}
			// electives - anupama
			for($i=0;$i<$electivecount;$i++)
			{
			
				if($_POST['ElectiveExams']['maximum_marks'][$i]!=NULL and $_POST['ElectiveExams']['minimum_marks'][$i]!=NULL)
				{	
					$electives = new ElectiveExams;
					$electives->exam_group_id = $insert_id; 
					$electives->elective_id = $_POST['ElectiveExams']['elective_id'][$i];
					$electives->maximum_marks = $_POST['ElectiveExams']['maximum_marks'][$i];
					$electives->minimum_marks = $_POST['ElectiveExams']['minimum_marks'][$i];
					$electives->start_time = $_POST['ElectiveExams']['start_time'][$i];
					$electives->end_time = $_POST['ElectiveExams']['end_time'][$i];
					if($electives->start_time)
					{
						$date1=date('Y-m-d H:i',strtotime($electives->start_time));
						$electives->start_time=$date1;
					}
					
					if($electives->end_time)
					{
						$date2=date('Y-m-d H:i',strtotime($electives->end_time));
						$electives->end_time=$date2;
					}
					$electives->grading_level_id = $list['grading_level_id'];
					$electives->weightage = $list['weightage'];
					$electives->event_id = $list['event_id'];
					$electives->created_at = $list['created_at'];
					$electives->updated_at = $list['updated_at'];
					//$model->save();
					if($electives->save())
					{				
						//$subject_name = Subjects::model()->findByAttributes(array('id'=>$model->subject_id));
						//$examgroup = ExamGroups::model()->findByAttributes(array('id'=>$model->exam_group_id));
						//$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
						//$exam = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
						
						//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
						//ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'17',$model->id,$exam,NULL,NULL,NULL); 
					}
					
				}
			}
				$this->redirect(array('exams/create','id'=>$_REQUEST['id'],'exam_group_id'=>$_REQUEST['exam_group_id']));
		}

		$this->render('create',array(
			'model'=>$model,'model_1'=>$model_1,'electives'=>$electives,'electivegroups'=>$electivegroups
		));
	}
        
	public function actionCreate()
	{
		if($_REQUEST['exam_group_id'] != NULL and $_REQUEST['id'] != NULL){	
			$this->checkBatchActive($_REQUEST['id']);	
			$model		= new Exams;
			$model_1	= new ExamGroups;
			
			if(isset($_POST['Exams'])){	                  			
				if(isset($_REQUEST['exam_group_id'])){
					$insert_id = $_REQUEST['exam_group_id'];
				}
				else{
					$model_1->attributes	= $_POST['ExamGroups'];
					$model_1->batch_id 		= $_REQUEST['id']; 
					$model_1->save();
					$insert_id 				= Yii::app()->db->getLastInsertID();
				}
				$posts			= Subjects::model()->findAll("batch_id=:x AND no_exams=:y", array(':x'=>$_REQUEST['id'],':y'=>0));
				$list 			= $_POST['Exams'];
				$count 			= count($list['subject_id']);
				$electivecount 	= count($_POST['ElectiveExams']['elective_id']);			
				$j				= 0;
				for($i=1;$i<=$count;$i++){				
					if($list['maximum_marks'][$i]!=NULL || $list['minimum_marks'][$i]!=NULL){	
						$model					= new Exams;
						$model->exam_group_id 	= $insert_id; 
						$model->subject_id 		= $list['subject_id'][$i];
						$model->maximum_marks 	= $list['maximum_marks'][$i];
						$model->minimum_marks 	= $list['minimum_marks'][$i];
						$model->start_time 		= $list['start_time'][$i];
						$model->end_time 		= $list['end_time'][$i];
						if($model->start_time){
							$date1				= date('Y-m-d H:i',strtotime($model->start_time));
							$model->start_time	= $date1;
						}					
						if($model->end_time){
							$date2				= date('Y-m-d H:i',strtotime($model->end_time));
							$model->end_time	= $date2;
						}
						$model->grading_level_id 	= $list['grading_level_id'];
						$model->weightage 			= $list['weightage'];
						$model->event_id 			= $list['event_id'];
						$model->created_at 			= $list['created_at'];
						$model->updated_at 			= $list['updated_at'];
						if(!$model->validate()){					   
							//get error from particular model
							foreach($model->getErrors() as $attribute=>$error){
								$key				= "Exams_".$attribute."_".$i;							
								$errors[$key][$i]	= $error[0];
							}
						}										
					}
				}
							
				for($i=1;$i<=$electivecount;$i++){			
					if($_POST['ElectiveExams']['maximum_marks'][$i]!=NULL || $_POST['ElectiveExams']['minimum_marks'][$i]!=NULL){	
						$electives 					= new ElectiveExams;
						$electives->exam_group_id 	= $insert_id; 
						$electives->elective_id 	= $_POST['ElectiveExams']['elective_id'][$i];
						$electives->maximum_marks 	= $_POST['ElectiveExams']['maximum_marks'][$i];
						$electives->minimum_marks 	= $_POST['ElectiveExams']['minimum_marks'][$i];
						$electives->start_time 		= $_POST['ElectiveExams']['start_time'][$i];
						$electives->end_time 		= $_POST['ElectiveExams']['end_time'][$i];
						if($electives->start_time){
							$date1					= date('Y-m-d H:i',strtotime($electives->start_time));
							$electives->start_time	= $date1;
						}
		
						if($electives->end_time){
							$date2					= date('Y-m-d H:i',strtotime($electives->end_time));
							$electives->end_time	= $date2;
						}
						$electives->grading_level_id 	= $list['grading_level_id'];
						$electives->weightage 			= $list['weightage'];
						$electives->event_id 			= $list['event_id'];
						$electives->created_at 			= $list['created_at'];
						$electives->updated_at 			= $list['updated_at'];
						if(!$electives->validate()){
							//get error from particular model
							foreach($electives->getErrors() as $attribute=>$error){
								$key		= "ElectiveExams_".$attribute."_".$i;							
								$errors[$key][$i]	= $error[0];
							}
						}	
					}
				}
				if(count($errors)>0){
					echo CJSON::encode(array('status'=>'error', 'errors'=>$errors));			
					exit;
				}
				else{
					for($i=1;$i<=$count;$i++){				
						if($list['maximum_marks'][$i]!=NULL and $list['minimum_marks'][$i]!=NULL){	
							$model					= new Exams;
							$model->exam_group_id 	= $insert_id; 
							$model->subject_id 		= $list['subject_id'][$i];
							$model->maximum_marks 	= $list['maximum_marks'][$i];
							$model->minimum_marks 	= $list['minimum_marks'][$i];
							$model->start_time 		= $list['start_time'][$i];
							$model->end_time 		= $list['end_time'][$i];
							if($model->start_time){
								$date1				= date('Y-m-d H:i',strtotime($model->start_time));
								$model->start_time	= $date1;
							}
							
							if($model->end_time){
								$date2				= date('Y-m-d H:i',strtotime($model->end_time));
								$model->end_time	= $date2;
							}
							$model->grading_level_id 	= $list['grading_level_id'];
							$model->weightage 			= $list['weightage'];
							$model->event_id 			= $list['event_id'];
							$model->created_at 			= $list['created_at'];
							$model->updated_at 			= $list['updated_at'];
							
							if($model->save()){															
								$subject_name 	= Subjects::model()->findByAttributes(array('id'=>$model->subject_id));
								$examgroup 		= ExamGroups::model()->findByAttributes(array('id'=>$model->exam_group_id));
								$batch 			= Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
								$exam 			= ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
								
								//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
								ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'17',$model->id,$exam,NULL,NULL,NULL); 													
							}						
						}
					}
					
					for($i=1;$i<=$electivecount;$i++){
				
						if($_POST['ElectiveExams']['maximum_marks'][$i]!=NULL and $_POST['ElectiveExams']['minimum_marks'][$i]!=NULL){	
							$electives 					= new ElectiveExams;
							$electives->exam_group_id 	= $insert_id; 
							$electives->elective_id 	= $_POST['ElectiveExams']['elective_id'][$i];
							$electives->maximum_marks 	= $_POST['ElectiveExams']['maximum_marks'][$i];
							$electives->minimum_marks 	= $_POST['ElectiveExams']['minimum_marks'][$i];
							$electives->start_time 		= $_POST['ElectiveExams']['start_time'][$i];
							$electives->end_time 		= $_POST['ElectiveExams']['end_time'][$i];
							if($electives->start_time){
								$date1					= date('Y-m-d H:i',strtotime($electives->start_time));
								$electives->start_time	= $date1;
							}
							
							if($electives->end_time){
								$date2					= date('Y-m-d H:i',strtotime($electives->end_time));
								$electives->end_time	= $date2;
							}
							$electives->grading_level_id 	= $list['grading_level_id'];
							$electives->weightage 			= $list['weightage'];
							$electives->event_id 			= $list['event_id'];
							$electives->created_at 			= $list['created_at'];
							$electives->updated_at			= $list['updated_at'];						
							$electives->save();						
						}
					}
					echo CJSON::encode(array('status'=>'success'));
					exit;
				}                                                                                                                                                                        										
			}
			$this->render('create',array(
				'model'=>$model,'model_1'=>$model_1,'electives'=>$electives,'electivegroups'=>$electivegroups
			));
		}
		else{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
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
		
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
		if($settings!=NULL)
		{
			if($model->start_time!='0000-00-00 00:00:00'){
				$model->start_time=date($settings->displaydate.' '.$settings->timeformat,strtotime($model->start_time));
			}
			if($model->end_time!='0000-00-00 00:00:00'){
				$model->end_time=date($settings->displaydate.' '.$settings->timeformat,strtotime($model->end_time));
			}
			$old_start_time = date($settings->displaydate.' '.$settings->timeformat,strtotime($old_model['start_time']));	// For activity feed
			$old_end_time = date($settings->displaydate.' '.$settings->timeformat,strtotime($old_model['end_time']));	// For activity feed
		}
		
		
		

		if(isset($_POST['Exams']))
		{
			$model->attributes=$_POST['Exams'];
			
			$list = $_POST['Exams'];
			if($model->start_time[0]!="")
			{
				$date1 = date('Y-m-d H:i',strtotime($list['start_time'][0]));
				$model->start_time = $date1; // To save
				$activity_start = date($settings->displaydate.' '.$settings->timeformat,strtotime($model->start_time)); // For activity feed
				
			}else{
				$model->start_time="";
			}
			
			if($model->end_time[0]!="")
			{
				$date2=date('Y-m-d H:i',strtotime($list['end_time'][0]));
				$model->end_time=$date2; // To save
				$activity_end = date($settings->displaydate.' '.$settings->timeformat,strtotime($model->end_time)); // For activity feed
			}else{
				$model->end_time="";
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
						
						$subject_name = Subjects::model()->findByAttributes(array('id'=>$model->subject_id));
						$examgroup = ExamGroups::model()->findByAttributes(array('id'=>$model->exam_group_id));
						$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
						$exam = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
						
						//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
						ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'18',$model->id,$exam,$model->getAttributeLabel($key),$old_model[$key],$value); 
						
						 
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
			$model = Exams::model()->findByAttributes(array('id'=>$id));
			$subject_name = Subjects::model()->findByAttributes(array('id'=>$model->subject_id));
			$examgroup = ExamGroups::model()->findByAttributes(array('id'=>$model->exam_group_id));
			$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
			$exam = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();
			
			//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
			ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'19',$model->id,$exam,NULL,NULL,NULL); 

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
			Yii::app()->user->setFlash('successMessage', Yii::t('app'," Selected Exam Group Deleted Successfully"));
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
		$dataProvider=new CActiveDataProvider('Exams');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
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
	
	public function actionManage()
	{
		
		$model=new Exams('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['exam_group_id']))
			$model->exam_group_id=$_GET['exam_group_id'];

		$this->render('manage',array(
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
			throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
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
}
