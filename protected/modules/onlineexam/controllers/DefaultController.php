<?php
class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
	public function actionList()
	{
		$criteria 				= 	new CDbCriteria();
		$criteria->condition	=   'batch_id=:batch_id';
		$criteria->params 		= 	array(':batch_id'=>$_REQUEST['bid']);
		
		$total = OnlineExams::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->setPageSize(Yii::app()->params['listPerPage']);
		$pages->applyLimit($criteria); 
		
		$online_exams = OnlineExams::model()->findAll($criteria);
		$this->render('list', array('online_exams'=>$online_exams, 'pages' => $pages, 'item_count'=>$total, 'page_size'=>Yii::app()->params['listPerPage']));
	}
	public function actionAttend()
	{ 
		$current_time 		= 	date('Y-m-d H:i:s');
		$etoken			 	= 	$_REQUEST['etoken'];
		$exam_id 			=	OnlineExams::model()->decryptToken($etoken);
		$offset 			=	OnlineExams::model()->decryptToken($_REQUEST['offset']);			
		$exam				= 	OnlineExams::model()->findByAttributes(array('id'=>$exam_id));
		
		if($exam->end_time>$current_time){
			
			$student		= 	Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
			$attend			= 	OnlineExamStudents::model()->findByAttributes(array('exam_id'=>$exam_id, 'student_id'=>$student->id));
			if($attend == NULL){
				$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
				$timezone 	= Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
       			date_default_timezone_set($timezone->timezone);
				
				$student_attend 						= 	new OnlineExamStudents;
				$student_attend->student_id 			=	$student->id;
				$student_attend->exam_id 				=	$exam_id;
				$student_attend->status 				=	0;
				$student_attend->exam_start_time 		=	date('Y-m-d H:i:s');
				$student_attend->save();
			}
			else{
				if($attend->status == 1){
					$this->redirect(array('error'));
				}
			}
			
			$model = new OnlineExamQuestions;
				if($_REQUEST['etoken']!=NULL and $_REQUEST['offset']!=NULL and $_REQUEST['qid']!=NULL){
					if(isset($_POST['OnlineExamQuestions']['ans']) and $_POST['OnlineExamQuestions']['ans']!=NULL){ //create answers
						$stud_ans					= 	OnlineExamStudentAnswers::model()->findByAttributes(array('question_id'=>$_REQUEST['qid'],'student_id'=>$student->id, 'exam_id'=>$exam->id));
						if($stud_ans == NULL){
							$question				= 	OnlineExamQuestions::model()->findByAttributes(array('id'=>$_REQUEST['qid']));
							$model					=	new OnlineExamStudentAnswers;
							$model->student_id 		=	$student->id;
							$model->exam_id 		=	$exam_id;
							$model->question_id 	=	$_REQUEST['qid'];
							$model->ans 			=	$_POST['OnlineExamQuestions']['ans'];
							if($question->question_type == 1 or $question->question_type == 2){
								$model->is_verified =	1;
							}
							else{
								$model->is_verified =	0;
							}
						}
						if($model->save()){
							echo json_encode(array('status'=>'success', 'etoken'=>$_REQUEST['etoken'],'offset'=>$_REQUEST['offset']));
							exit;
						}
					}
					if(isset($_POST['OnlineExamStudentAnswers']['ans']) and $_POST['OnlineExamStudentAnswers']['ans']!=NULL){ //update answers using back button
							$model					=	OnlineExamStudentAnswers::model()->findByAttributes(array('question_id'=>$_REQUEST['qid']));
							$model->ans 			=	$_POST['OnlineExamStudentAnswers']['ans'];
							if($model->save()){
								echo json_encode(array('status'=>'success', 'etoken'=>$_REQUEST['etoken'],'offset'=>$_REQUEST['offset']));
								exit;
							}
					}
					else{
						echo json_encode(array('status'=>'success', 'etoken'=>$_REQUEST['etoken'],'offset'=>$_REQUEST['offset']));
						exit;
					}
				}
				
			Yii::app()->user->setState("eid", $etoken);
			$this->render("attend",array('model'=>$model));
		}
		else{
			$this->redirect(array('error'));
		}
	}
	
	public function actionResult()
	{
		$model 					= 	new OnlineExamQuestions;
		$criteria 				= 	new CDbCriteria();
		$criteria->condition 	= 	'exam_id=:exam_id and is_deleted = :is_deleted';
		$criteria->params 		= 	array(':exam_id'=>$_REQUEST['id'],':is_deleted'=>0);
		$criteria->addInCondition('question_type',array(1,2));
		$criteria->order		=   'question_order ASC';      
		
		$total = OnlineExamQuestions::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->setPageSize(Yii::app()->params['listPerPage']);
		$pages->applyLimit($criteria); 
		
		$questions = OnlineExamQuestions::model()->findAll($criteria);
		$this->render('result', array('questions'=>$questions, 'model'=>$model, 'pages' => $pages, 'item_count'=>$total, 'page_size'=>Yii::app()->params['listPerPage']));
	}	
	public function actionAnswer()
	{
		$criteria 				= 	new CDbCriteria();
		$criteria->condition 	= 	'exam_id=:exam_id and is_deleted = :is_deleted';
		$criteria->params 		= 	array(':exam_id'=>$_REQUEST['id'],':is_deleted'=>0);
		$criteria->order		=   'question_order ASC';      
		
		$total = OnlineExamQuestions::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->setPageSize(Yii::app()->params['listPerPage']);
		$pages->applyLimit($criteria); 
		
		$questions = OnlineExamQuestions::model()->findAll($criteria);
		$this->render('answer', array('questions'=>$questions, 'pages' => $pages, 'item_count'=>$total, 'page_size'=>Yii::app()->params['listPerPage']));
	}
	 public function actionSubmit()
	{
		$exam_id   		=   OnlineExams::model()->decryptToken($_REQUEST['etoken']);
		$student		= 	Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		$model			= 	OnlineExamStudents::model()->findByAttributes(array('student_id'=>$student->id, 'exam_id'=>$exam_id));
		
		$criteria 				= 	new CDbCriteria();
		$criteria->condition 	= 	'exam_id=:exam_id and is_deleted = :is_deleted';
		$criteria->params 		= 	array(':exam_id'=>$exam_id,':is_deleted'=>0);
		$criteria->addInCondition('question_type',array(3,4));
		$questions = OnlineExamQuestions::model()->findAll($criteria);
		
		if($model){
		$model->status 	= 1;
			if($model->save()){
				if(count($questions) > 0){
					$this->render("submit");
				}
				else{
					$this->render("score");
				}
			}
		}
	}
	public function actionError()
	{	
		$this->render("error");
	}
	public function actionScore()
	{	
		$this->render("score");
	}
	public function actionExams()
	{
		$criteria 				= 	new CDbCriteria();
		$criteria->condition	=   'batch_id=:batch_id';
		$criteria->params 		= 	array(':batch_id'=>$_REQUEST['bid']);
		
		$total = OnlineExams::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->setPageSize(Yii::app()->params['listPerPage']);
		$pages->applyLimit($criteria); 
		
		$online_exams = OnlineExams::model()->findAll($criteria);
		$this->render('exams', array('online_exams'=>$online_exams, 'pages' => $pages, 'item_count'=>$total, 'page_size'=>Yii::app()->params['listPerPage']));
	}
	/*protected function checkAccess($etoken){
		if(!(Yii::app()->user->hasState("eid") and OnlineExams::model()->decryptToken(Yii::app()->user->getState("eid"))==OnlineExams::model()->decryptToken($etoken))){
			$this->redirect(array('error'));
		}
	}*/
}