<?php
/**
 * Ajax Crud Administration
 * ExamController *
 * InfoWebSphere {@link http://libkal.gr/infowebsphere}
 * @author  Spiros Kabasakalis <kabasakalis@gmail.com>
 * @link http://reverbnation.com/spiroskabasakalis/
 * @copyright Copyright &copy; 2011-2012 Spiros Kabasakalis
 * @since 1.0
 * @ver 1.3
 * @license The MIT License
 */

class Exam17Controller extends RController
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
        
	public function actionAllexams()
	{
			$this->render('allexams',array('batch_id'=>$_REQUEST['bid']));
	}
	
	public function actionIndex(){
		
	}
	public function actionAllexamresult()
	{
		$this->render('allexamresult',array('batch_id'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id']));
	}
	public function actionAllexamscore()
	{
		
		$model=new CbscExamScores17;
		
		if(isset($_POST['CbscExamScores17']))
		{		
		
			$list = $_POST['CbscExamScores17'];
			$count = count($list['student_id']); 
			for($i=0;$i<$count;$i++)
			{
				if($list['written_exam'][$i]!=NULL or $list['periodic_test'][$i]!=NULL or $list['note_book'][$i]!=NULL or $list['subject_enrichment	'][$i]!=NULL)
				{
					$exam				 = CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
					$sub				 = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
					$model						= new CbscExamScores17;
					$model->exam_id 			= $list['exam_id']; 
					$model->student_id   		= $list['student_id'][$i];
					$model->written_exam 		= $list['written_exam'][$i];
					$model->periodic_test 		= $list['periodic_test'][$i];
					$model->note_book 			= $list['note_book'][$i];
					$model->subject_enrichment	= $list['subject_enrichment'][$i]; 
					$model->total 				= $list['total'][$i];
					$model->remarks		 		= htmlspecialchars_decode($list['remarks'][$i]); 
					
					$exam_group = CbscExams17::model()->findByPk($_REQUEST['examid']);
					$is_grade = CbscExamGroup17::model()->findByPk($exam_group->exam_group_id);
					if(($list['marks'][$i])< ($exam->minimum_marks)) 
					{

							$model->is_failed = 1;
					}
					else 
					{
							$model->is_failed = 0;
					}
					$model->created_at = $list['created_at'];
					$model->updated_at = $list['updated_at'];
					
					$student_data = CbscExamScores17::model()->findByAttributes(array('student_id'=>$model->student_id,'exam_id'=>$list['exam_id']));

					if($student_data==NULL)
					{
						if(!$model->validate()){
							//get error from particular model
							foreach($model->getErrors() as $attribute=>$error){
								$key		= "CbscExamScores17_".$attribute."_".$i;							
								$errors[$key][$i]	= $error[0];
							}
						}
					}
						
				}
			}		 	
		if(count($errors)>0){ 
			echo CJSON::encode(array('status'=>'error', 'errors'=>$errors));			
			exit;
		}
		else
		{ 	 
			for($i=0;$i<$count;$i++)
			{
				if($list['written_exam'][$i]!=NULL or $list['periodic_test'][$i]!=NULL or $list['note_book'][$i]!=NULL or $list['subject_enrichment	'][$i]!=NULL)
				{
					
					$exam	= CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
					$sub 	= Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
					$model				 		= new CbscExamScores17;
					$model->exam_id 	 		= $list['exam_id']; 
					$model->student_id   		= $list['student_id'][$i];
					$model->written_exam 		= number_format($list['written_exam'][$i],2);
					$model->periodic_test		= number_format($list['periodic_test'][$i],2);
					$model->note_book 			= number_format($list['note_book'][$i],2);
					$model->subject_enrichment 	= number_format($list['subject_enrichment'][$i],2);
					$model->total 				= $list['total'][$i];
					$model->remarks		 		= htmlspecialchars_decode($list['remarks'][$i]);
					$exam_group 		 		= CbscExams17::model()->findByPk($_REQUEST['examid']);
					if(($list['total'][$i])< ($exam->minimum_marks)) 
					{
						$model->is_failed = 1;
					}
					else 
					{
							$model->is_failed = 0;
					} 
					$model->created_at = $list['created_at'];
					$model->updated_at = $list['updated_at'];
				 	$student_data = CbscExamScores17::model()->findByAttributes(array('student_id'=>$model->student_id,'exam_id'=>$list['exam_id']));

					if($student_data==NULL)
					{
						if($model->save())
						{ 
								$student 		= Students::model()->findByAttributes(array('id'=>$model->student_id));
								$student_name  	= ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);
								$subject_name 	= Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
								$examgroup 		= CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
								$batch 			= Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
								$exam 			= ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
								$goal_name 		= $student_name.Yii::t('app',' for the Cbsc exam ').$exam; 
								ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'20',$model->id,$goal_name,NULL,NULL,NULL); 
						} else{
							var_dump($model->getErrors());exit;
						}
					 } 
				}
			}	
	   	}
	  	 echo CJSON::encode(array('status'=>'success'));
			exit;
	}
		
		$this->render('allexamscore',array('bid'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id'],'exam_id'=>$_REQUEST['exam_id'],'model'=>$model));
	}
	public function actiondelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			$model = CbscExamScores17::model()->findByPk($id);
			$model->delete(); 
			//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
			ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'22',$model->id,$goal_name,NULL,NULL,NULL); 

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,Yii::t('app','Invalid request. Please do not repeat this request again.'));
	}
	public function actiondeleteall()
	{
		$exam 	= CbscExams17::model()->findByPk($_REQUEST['exam_id']);
		$delete = CbscExamScores17::model()->findAllByAttributes(array('exam_id'=>$_REQUEST['exam_id']));
			foreach($delete as $delete1)
			{
				$delete1->delete();
			} 
			$this->redirect(array('allexamscore','bid'=>$_REQUEST['id'],'exam_group_id'=>$exam->exam_group_id,'exam_id'=>$_REQUEST['exam_id']));
		
	}
	public function actionUpdate($id)
	{
		$model=$this->loadScoresModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CbscExamScores17']))
		{
		
			$model->attributes = $_POST['CbscExamScores17'];
			$model->remarks    = htmlspecialchars_decode($_POST['CbscExamScores17']['remarks']);
			$exam = CbscExams17::model()->findByAttributes(array('id'=>$model->exam_id));
			if(($model->total)< ($exam->minimum_marks)) 
			{
				$model->is_failed = 1;
			}
			else
			{
				$model->is_failed = 0;
			}			
			if($model->save())
			{ 
				// Saving to activity feed
				$results = array_diff_assoc($model->attributes,$old_model); // To get the fields that are modified. 
				foreach($results as $key => $value)
				{
					if($key!='updated_at')
					{
						$student = Students::model()->findByAttributes(array('id'=>$model->student_id));
						$student_name = ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);
						
						$subject_name = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
						$examgroup = CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
						$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
						$exam_name = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
						$goal_name = $student_name.Yii::t('app',' for the CBSC exam ').$exam_name;
						
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
						ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'21',$model->id,$goal_name,$model->getAttributeLabel($key),$old_model[$key],$value); 
					}
				}
				//END saving to activity feed
				
				$this->redirect(array('allexamscore','exam_id'=>$_REQUEST['examid'],'bid'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id']));
			}  
				
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	public function loadScoresModel($id)
	{
		$model=CbscExamScores17::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
		return $model;
	}
	public function actionAllExam()
	{
		$this->render('allexam');
	}
	public function actionAllexamschedule()
	{		
		$this->render('allexamschedule',array('employee_id'=>$_REQUEST['employee_id'],'batch_id'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id']));
	}
	public function actionCbscExamSplit()
	{
		$this->render('examscoresplit',array('batch_id'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id'],'exam_id'=>$_REQUEST['exam_id']));
	}
	public function actionExamScoresSplit()
	{
		
		if($_REQUEST['examid'] != NULL and $_REQUEST['id'] != NULL)
		{                 
			$this->checkBatchActive($_REQUEST['id']);		
			$model=new CbscExamScores17;
			 if(isset($_POST['CbscExamScores17']))
			{ 		 	                			                        
				$list = $_POST['CbscExamScores17'];
				$count = count($list['student_id']); 
				for($i=0;$i<$count;$i++)
				{
					if($list['remarks'][$i]!=NULL or $list['sub_category1'][$i]!=NULL or $list['sub_category2'][$i]!=NULL or $list['total'][$i]!=NULL)
					{
						
						$exam					=	CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
                        $sub 					= 	Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
						$model					=	new CbscExamScores17;
						$model->exam_id 		= 	$list['exam_id']; 
						$model->student_id 		= 	$list['student_id'][$i];
						$model->sub_category1 	= 	$list['sub_category1'][$i];
						$model->sub_category2 	= 	$list['sub_category2'][$i];
						$split					=	array('0'=>$model->sub_category1,'1'=>$model->sub_category2);
						$model->total 			= 	$list['total'][$i];
						$model->remarks 		= 	$list['remarks'][$i]; 
						$exam_group 			= 	CbscExams17::model()->findByPk($_REQUEST['examid']);
						$is_grade 				= 	CbscExamGroup17::model()->findByPk($exam_group->exam_group_id); 
						  
						if(($list['total'][$i])< ($exam->minimum_marks)) 
						{

								$model->is_failed = 1;
						}
						else 
						{
								$model->is_failed = 0;
						} 
						$model->created_at = $list['created_at'];
						$model->updated_at = $list['updated_at'];
						$student_data = CbscExamScores17::model()->findByAttributes(array('student_id'=>$model->student_id,'exam_id'=>$list['exam_id']));

						if($student_data==NULL)
						{
							if(!$model->validate()){
								//get error from particular model 
								foreach($model->getErrors() as $attribute=>$error){
									$key		= "CbscExamScores17_".$attribute."_".$i;							
									$errors[$key][$i]	= $error[0]; 
								}
							}
						}
					}
				}
				if(count($errors)>0){
					echo CJSON::encode(array('status'=>'error', 'errors'=>$errors));			
					exit;
				}
				else
				{
					for($i=0;$i<$count;$i++)
					{
						if($list['total'][$i]!=NULL or $list['remarks'][$i]!=NULL or $list['sub_category1'][$i]!=NULL or $list['sub_category1'][$i]!=NULL)
						{
							$exam=CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
							$sub = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id)); 
							
							$model					=	new CbscExamScores17;
							$model->exam_id 		= 	$list['exam_id']; 
							$model->student_id		= 	$list['student_id'][$i];
							$model->sub_category1 	= 	$list['sub_category1'][$i];
							$model->sub_category2 	= 	$list['sub_category2'][$i];
							$split					=	array('0'=>$model->sub_category1,'1'=>$model->sub_category2);
							$model->total 			= 	$list['total'][$i];
							$model->remarks 		= 	$list['remarks'][$i];
							//$model->grading_level_id = $list['grading_level_id'];
							$exam_group = CbscExams17::model()->findByPk($_REQUEST['examid']);
							$is_grade 	= CbscExamGroup17::model()->findByPk($exam_group->exam_group_id);
							
							if(($list['total'][$i])< ($exam->minimum_marks)) 
							{
								$model->is_failed = 1;
							}
							else 
							{
								$model->is_failed = 0;
							}
							$model->created_at = $list['created_at'];
							$model->updated_at = $list['updated_at'];
							$student_data = ExamScores::model()->findByAttributes(array('student_id'=>$model->student_id,'exam_id'=>$list['exam_id']));
							
							if($student_data==NULL)
							{
								if($model->save())
								{ 
									if($sub->split_subject == 1){
										for($k=0;$k<2;$k++){
											$exam_score_split					=	new CbscExamScoresSplit17;
											$exam_score_split->student_id		=	$model->student_id;
											$exam_score_split->exam_scores_id	=	$model->id;
											$exam_score_split->mark				=	$split[$k];
											$exam_score_split->save();
										}
									}
								$student = Students::model()->findByAttributes(array('id'=>$model->student_id));
								$student_name = ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);
								
								$subject_name 	= Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
								$examgroup 		= CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
								$batch 			= Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
								$exam 			= ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
								$goal_name		= $student_name.Yii::t('app',' for the CBSC exam ').$exam;
								
								
									//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
									ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'20',$model->id,$goal_name,NULL,NULL,NULL); 
								}
							}
						}
					}
					echo CJSON::encode(array('status'=>'success'));
					exit;
				}
			} 
			//$this->render('cbscExamSplit',array( 'model'=>$model));
		}
	}
	public function checkBatchActive($id) //Check whether the batch is active
	{
		$batch = Batches::model()->findByAttributes(array('id'=>$id, 'is_active'=>1, 'is_deleted'=>0));
		if($batch == NULL){
			$this->redirect(array('/examination'));
		}		
	}
	public function actionDeleteallSplit()
	{
		if(Yii::app()->request->isPostRequest){
			$delete = CbscExamScores17::model()->findAllByAttributes(array('exam_id'=>$_REQUEST['examid']));
			foreach($delete as $delete1)
			{
				$id	=	$delete1->id;
				if($delete1->delete()){
					$subject_cps	=	CbscExamScoresSplit17::model()->findAllByAttributes(array('exam_scores_id'=>$id));
					foreach($subject_cps as $subject_cp){ 
						$subject_cp->delete();
					}
				}
			}			
			$this->redirect(array('cbscExamSplit','bid'=>$_REQUEST['id'],'exam_group_id'=>$_REQUEST['exam_group_id'],'exam_id'=>$_REQUEST['examid']));
		}
		else
		{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
	}
	public function actionDeleteSplit(){
		
		if(Yii::app()->request->isPostRequest)
		{ 
			$id	=	$_REQUEST['id'];
			$model = CbscExamScores17::model()->findByAttributes(array('id'=>$id));
			
			$student = Students::model()->findByAttributes(array('id'=>$model->student_id));
			$student_name = ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);
			
			$exam = CbscExams17::model()->findByAttributes(array('id'=>$model->exam_id));
			$subject_name = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
			$examgroup = CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
			$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
			$exam_name = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
			$goal_name = $student_name.Yii::t('app',' for the CBSC exam ').$exam_name;
			// we only allow deletion via POST request
			if($this->loadscoreModel($id)->delete()){
				$subject_cps	=	CbscExamScoresSplit17::model()->findAllByAttributes(array('exam_scores_id'=>$id));
				foreach($subject_cps as $subject_cp){ 
					$subject_cp->delete();
				}
			}
			
			//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
			ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'22',$model->id,$goal_name,NULL,NULL,NULL); 

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('examScoresSplit'));
		}
		else
			throw new CHttpException(400,Yii::t('app','Invalid request. Please do not repeat this request again.'));
	}
	public function actionUpdateSplit(){
		$model	=	CbscExamScores17::model()->findByPk($_REQUEST['id']);
		$old_model = $model->attributes; // For activity feed	
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CbscExamScores17']))
		{
			$model->attributes=$_POST['CbscExamScores17'];
			$exam = CbscExams17::model()->findByAttributes(array('id'=>$model->exam_id));
			if(($model->total)< ($exam->minimum_marks)) 
			{
				$model->is_failed = 1;
			}
			else
			{
				$model->is_failed = 0;
			}
			
			
			
			if($model->save())
			{
				$sub1	=	$_POST['CbscExamScores17']['sub_category1'];
				$sub2	=	$_POST['CbscExamScores17']['sub_category2'];	
				$split	=	array('0'=>$sub1,'1'=>$sub2); 
				$subject_cps	=	CbscExamScoresSplit17::model()->findAllByAttributes(array('exam_scores_id'=>$model->id));
				$l=0;
				foreach($subject_cps as $subject_cp){
					$subject_cp->mark	=	$split[$l];
					$subject_cp->save();
					$l++;
				}
				// Saving to activity feed
				$results = array_diff_assoc($model->attributes,$old_model); // To get the fields that are modified. 
				foreach($results as $key => $value)
				{
					if($key!='updated_at')
					{
						$student = Students::model()->findByAttributes(array('id'=>$model->student_id));
						$student_name = ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);
						
						$subject_name = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
						$examgroup = CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
						$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
						$exam_name = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
						$goal_name = $student_name.Yii::t('app',' for the CBSC exam ').$exam_name;
						
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
						ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'21',$model->id,$goal_name,$model->getAttributeLabel($key),$old_model[$key],$value); 
					}
				}
				//END saving to activity feed
				
				$this->redirect(array('cbscExamSplit','bid'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id'],'exam_id'=>$_REQUEST['exam_id']));
			} 
		}

		$this->render('update_split',array(
			'model'=>$model,
		));
	}
	public function loadscoreModel($id)
	{
		$model=CbscExamScores17::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
		return $model;
	}
        
}
?>