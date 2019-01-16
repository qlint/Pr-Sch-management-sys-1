<?php

class QuestionsController extends RController
{
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
        
        public function init(){
		$cs=Yii::app()->clientScript;
		$cs->scriptMap=array(
			'jquery.min.js'=>false,
			'jquery.ui.js' => false,
		);
	}
        
        //list all students for verify short and multi line answers
        public function actionVerify()
        {
            if(isset($_REQUEST['id']) && ($_REQUEST['id']!=NULL))
            {
                $batch_id                           =   OnlineExams::model()->getBatchId($_REQUEST['id']);                
                $is_active                          =   1;
                $criteria                           =   new CDbCriteria;
                $criteria->condition                =   '`t`.`is_deleted`=:is_deleted AND `t`.`is_active`=:is_active AND `bs`.`batch_id`=:batch_id';
                $criteria->params[':is_deleted']    =   0;
                $criteria->params[':is_active']     =   $is_active;
                $criteria->params[':batch_id']      =   $batch_id;
                if($is_active)
                {
                    $criteria->condition 	.= " AND `bs`.`result_status`=:result_status AND `bs`.`status`=:status";
                    $criteria->params[':result_status']  = 0;
                    $criteria->params[':status']   	 = 1;
                }
                $criteria->order	= 't.first_name ASC';
                $criteria->join 	= "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`id`";
                
                $total = Students::model()->count($criteria);
                $pages = new CPagination($total);
                $pages->setPageSize(Yii::app()->params['listPerPage']);
                $pages->applyLimit($criteria);  // the trick is here!
                $posts = Students::model()->findAll($criteria);

                $this->render('verify',array(
                'list'=>$posts,
                'pages' => $pages,
                'item_count'=>$total,
                'page_size'=>Yii::app()->params['listPerPage'],)) ;
            }
            else
                throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
        }
        
        //check and add mark for short and multi line answer
        public function actionVerifyAnswer()
        {
            $exam_token =   $_REQUEST['exam_id'];
            $student_id =   $_REQUEST['id'];
            $exam_id    =   OnlineExams::model()->decryptToken($exam_token);            
            if(isset($_REQUEST['exam_id']) && isset($_REQUEST['id']))
            {
                $this->render("verify_answer",array(''));
            }  
            else
                throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
        }
        
        //change order of questions
        public function actionChangeOrder()
        {
            $response = array('status'=>'failed');
            if(isset($_POST['exam_id']) && $_POST['exam_id']!=NULL)
            {
                if(isset($_POST['questions']) && $_POST['questions']!="")
                {
                    $questions  =   $_POST['questions'];
                    foreach ($questions as $data)
                    {
                        $model  = OnlineExamQuestions::model()->findByAttributes(array('id'=>$data['id'],'exam_id'=>$_POST['exam_id']));
                        if($model!=NULL)
                        {
                            $model->question_order   =   $data['order'];
                            $model->save();
                        }                        
                    }
                    $response['status']	= "success";
                }                
            }
            echo json_encode($response);
            Yii::app()->end();
        }
        
        //add score for short and multi line questions
        public function actionAddScore()
        {
            $response = array('status'=>'failed');
            if(isset($_POST['question_id']) && $_POST['question_id']!=NULL)
            {
                if(isset($_POST['student_id']) && $_POST['student_id']!="" )
                {
                    $score          =   floatval($_POST['score']);
                    $exam_id        =   $_POST['exam_id'];
                    $question_id    =   $_POST['question_id'];
                    $student_id     =   $_POST['student_id'];
                    $offset         =   $_POST['offset'];
                    $qp_count       =   $_POST['qp_count'];
                    $batch_id       =   OnlineExams::getBatchId($exam_id);
                    $model          =   OnlineExamScores::model()->findByAttributes(array('student_id'=>$student_id,'exam_id'=>$exam_id,'question_id'=>$question_id));
                    if($model==NULL)
                    {
                        $model          =   new OnlineExamScores;
                    }
                    $model->student_id  =   $student_id;
                    $model->exam_id     =   $exam_id;
                    $model->question_id =   $question_id;
                    $model->score       =   $score;
                    $model->created_at  =   date('Y-m-d H:i:s');
                    $model->created_by  =   Yii::app()->user->id;
                    if($model->save())
                    {
                        //set is_verified to 1 - for mark entered answer
                        $student_answer_model   =   OnlineExamStudentAnswers::model()->findByAttributes(array('student_id'=>$student_id,'exam_id'=>$exam_id,'question_id'=>$question_id));
                        if($student_answer_model!=NULL)
                        {
                            $student_answer_model->is_verified=1;
                            $student_answer_model->save();
                        }                        
                        $response['status']	=   "success";
                        if($qp_count>0)
                        {
                            $data                   =   array('id'=>$student_id,'exam_id'=>OnlineExams::model()->encryptToken($exam_id),'offset'=>OnlineExams::model()->encryptToken($offset+1));
                            $response['url']        =   Yii::app()->createUrl('onlineexam/questions/verifyAnswer', $data);
                        }
                        else
                            $response['url']        =   Yii::app()->createUrl('onlineexam/questions/verify', array('id'=>$exam_id));
                    }                                      
                }                
            }
            echo json_encode($response);
            Yii::app()->end();
        }
}
?>