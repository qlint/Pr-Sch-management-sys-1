<?php
class ExamController extends RController
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
        
        //create new exam
        public function actionNew()
	{
            $model              =   new OnlineExams;           
            if(isset($_POST['OnlineExams']))
            {
                $model->attributes	= $_POST['OnlineExams'];
                if(isset($_POST['OnlineExams']['start_time']) && strtotime($_POST['OnlineExams']['start_time'])>0)
                {
                    $model->start_time  =   date('Y-m-d H:i:s',strtotime($_POST['OnlineExams']['start_time']));
                }else                
                    $model->start_time  =   '';
                
                if(isset($_POST['OnlineExams']['end_time']) && strtotime($_POST['OnlineExams']['end_time'])>0)
                {
                    $model->end_time  =   date('Y-m-d H:i:s',strtotime($_POST['OnlineExams']['end_time']));
                }else                
                    $model->end_time  =   '';                       
                $model->created_at	=   date('Y-m-d H:i:s');
                $model->created_by	=   Yii::app()->user->id; 
                $model->status          =   1;
                $model->is_deleted      =   0;
                if($_POST['OnlineExams']['choice_limit']=='')
                {
                    $model->choice_limit      =   1;
                }
                
                if($model->validate()){                       			
                    if($model->save()){                             
                            //redirect to questions setup
                            $this->redirect(array('addQuestion', 'id'=>$model->id));
                    }
                }
                $model->choice_limit    =   $_POST['OnlineExams']['choice_limit'];
            }            
            if($this->userRole()=="teacher")
            {
                    $this->render('teacher', array('model'=>$model), false, true);			
            }
            else{
                    throw new CHttpException(404,'You are not authorized to access this page.');
            }
	}
        
        //update exam details
        public function actionUpdate($id)
        {
            $date   =   strtotime(date("Y-m-d H:i:s"));  
            $model	=   $this->loadModel($id);
            $model->start_time  =   date('Y-m-d H:i',strtotime($model->start_time));
            $model->end_time    =   date('Y-m-d H:i',strtotime($model->end_time));
            if(strtotime($model->start_time) >= $date)
            {
                if(isset($_POST['OnlineExams']))
                {
                    $model->attributes	= $_POST['OnlineExams'];
                    if(isset($_POST['OnlineExams']['start_time']) && strtotime($_POST['OnlineExams']['start_time'])>0)
                    {
                        $model->start_time  =   date('Y-m-d H:i:s',strtotime($_POST['OnlineExams']['start_time']));
                    }else                
                        $model->start_time  =   '';

                    if(isset($_POST['OnlineExams']['end_time']) && strtotime($_POST['OnlineExams']['end_time'])>0)
                    {
                        $model->end_time  =   date('Y-m-d H:i:s',strtotime($_POST['OnlineExams']['end_time']));
                    }else                
                        $model->end_time  =   '';                       
                    $model->created_at	=   date('Y-m-d H:i:s');
                    $model->created_by	=   Yii::app()->user->id; 
                    $model->status          =   0;
                    $model->is_deleted      =   0;
                    if($model->validate()){                       			
                        if($model->save()){                             
                                //redirect to questions setup
                                $this->redirect(array('index', 'bid'=>$model->batch_id));
                        }
                    }
                }

                if($this->userRole()=="teacher")
                {
                        $this->render('teacher', array('model'=>$model), false, true);			
                }
                else{
                        throw new CHttpException(404,'You are not authorized to access this page.');
                } 
            }
            else
                throw new CHttpException(404,'You are not authorized to access this page.');
                 
        }

        //list all exams for a batch
        public function actionIndex()
        {
            if(isset($_REQUEST['bid']) && $_REQUEST['bid']!=NULL)
            {
                $criteria = new CDbCriteria;
                $criteria->condition = 'batch_id=:batch_id AND is_deleted=:is_deleted';            
                $criteria->params[':batch_id'] = $_REQUEST['bid'];
                $criteria->params[':is_deleted'] = 0;
                
                $total = OnlineExams::model()->count($criteria);
                $pages = new CPagination($total);
                $pages->setPageSize(Yii::app()->params['listPerPage']);
                $pages->applyLimit($criteria);  // the trick is here!
                $posts = OnlineExams::model()->findAll($criteria);

                $this->render('index',array(
                'list'=>$posts,
                'pages' => $pages,
                'item_count'=>$total,
                'page_size'=>Yii::app()->params['listPerPage'],)) ;
            }
            else
                throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
        }
                
        //add questions
        public function actionAddQuestion()
        {                                  
            $q_model    =   new OnlineExamQuestions;
            if(isset($_GET['id']) and is_numeric($_GET['id']))
            {
                $id	=   $_GET['id'];
                $model	=   $this->loadModel($id);
                if($this->userRole()=="teacher")
                {                        
                    $this->render('add_question', array('id'=>$id, 'model'=>$model, 'q_model'=>$q_model), false, true);                                               
                }                
                else{
                    throw new CHttpException(404,'You are not authorized to access this page.');
                }
            }
        }
        
        //save new questions
        public function actionSave()
        {
            $response	= array('status'=>'failed');
            if(isset($_POST['OnlineExamQuestions']))
            {
                $question_type  =   $_POST['OnlineExamQuestions']['question_type'];
                $question       =   $_POST['OnlineExamQuestions']['question'];
                $exam_id        =   $_POST['OnlineExamQuestions']['exam_id'];
                $batch_id       =   OnlineExams::getBatchId($exam_id);
                if($question_type!='' && $question!='' && $exam_id!='')
                {
                    $model                  =   new OnlineExamQuestions;
                    $model->exam_id         =   $exam_id;
                    $model->question        =   $question;
                    $model->question_type   =   $question_type;
                    $model->answer_id       =   NULL;
                    $model->question_order  =   OnlineExamQuestions::model()->getHighestOrder($exam_id);
                    $model->mark            =   $_POST['OnlineExamQuestions']['mark'];
                    $model->created_by      =   Yii::app()->user->id;
                    $model->status          =   0;                    
                    if($model->save())
                    {
                        if($question_type==1) //for multi choice
                        {
                            $options    =   $_POST['OnlineExamQuestions']['choice_answer'];
                            $answer_id  =   $_POST['choice_answer_id'];
                            foreach ($options as $key=>$choice)
                            {
                                $answer_model               =   new OnlineExamAnswers;
                                $answer_model->question_id  =   $model->id;
                                $answer_model->answer       =   $choice;
                                $answer_model->order        =   NULL;
                                if($answer_model->save())
                                {
                                    if($key==$answer_id)
                                    {
                                        //save correct answer id
                                        $model->answer_id   =   $answer_model->id;
                                        $model->save();
                                    }
                                }                                
                            }
                        }
                        else if($question_type==2) //for true/false
                        {
                            $options= array('1'=>1, '0'=>0);
                            $answer_id  =   $_POST['OnlineExamQuestions']['type_answer'];
                            foreach ($options as $key=>$choice)
                            {
                                $answer_model               =   new OnlineExamAnswers;
                                $answer_model->question_id  =   $model->id;
                                $answer_model->answer       =   $choice;
                                $answer_model->order        =   NULL;
                                if($answer_model->save())
                                {
                                    if($key==$answer_id)
                                    {
                                        //save correct answer id
                                        $model->answer_id   =   $answer_model->id;
                                        $model->save();
                                    }
                                }                                
                            }
                        } 
                        else if($question_type==3 || $question_type==4) //for single / multi line
                        {
                            $answer =   $_POST['OnlineExamQuestions']['exam_answer'];                            
                            $answer_model               =   new OnlineExamAnswers;
                            $answer_model->question_id  =   $model->id;
                            $answer_model->answer       =   $answer;
                            $answer_model->order        =   NULL;
                            if($answer_model->save())
                            {
                                $model->answer_id   =   $answer_model->id;
                                $model->save();
                            }
                            
                        }
                        
                        $response['status']	= "success";
                        if(isset($_POST['submit_type']) && $_POST['submit_type']==1) //submit with add another question
                        {
                            $response['data']	= Yii::app()->createUrl('onlineexam/exam/addQuestion', array('id'=>$exam_id,'bid'=>$batch_id));
                        }
                        else
                        $response['data']	= Yii::app()->createUrl('onlineexam/exam/questions', array('id'=>$exam_id,'bid'=>$batch_id,'key'=>$model->id));                       
                    }
                    else
                    {
                        $response['message']	= Yii::t('app',"Can't save neq question");
                    }                    
                }
                else{
                $response['message']	= Yii::t('app',"Can't save new question"); }
            }
            else{
                $response['message']	= Yii::t('app',"Can't save new question"); }
                
            echo json_encode($response);
            Yii::app()->end();
        }
        
        //list all questions in a question paper
        public function actionQuestions($id)
        {
            $this->render('question_list');
        }

        //update questions
        public function actionUpdateQp($id)
        {            
                $model      =   $this->loadQuestionModel($id);   
                $exists     =   OnlineExamStudentAnswers::model()->exists('exam_id = :exam_id',array('exam_id'=>$model->exam_id));
                if($this->userRole()=="teacher" && (!$exists))
                {                        
                    $this->render('update_question', array('id'=>$id, 'model'=>$model), false, true);                                               
                }
                else
                {
                    throw new CHttpException(404,'You are not authorized to access this page.');
                }                                            
        }
        
        //delete questions
        public function actionDeleteQp($id)
        {
            if(Yii::app()->request->isPostRequest)
            {
                    // we only allow deletion via POST request
                    $model      =   $this->loadQuestionModel($id);
                    $exam_id    =   $model->exam_id;  
                    $batch_id   =   OnlineExams::getBatchId($exam_id);
                    $model->is_deleted  =   1;
                    $model->question_order=0;
                    $model->save();

                    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
                    if(!isset($_GET['ajax']))
                            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('questions','id'=>$exam_id,'bid'=>$batch_id));
            }
            else
                    throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
        }
                
        //update question
        public function actionUpdateQuestion()
        {
            $response	= array('status'=>'failed');
            if(isset($_POST['OnlineExamQuestions']))
            {
                $id             =   $_POST['OnlineExamQuestions']['id'];                
                $question_type  =   $_POST['OnlineExamQuestions']['question_type'];
                $question       =   $_POST['OnlineExamQuestions']['question'];
                $exam_id        =   $_POST['OnlineExamQuestions']['exam_id'];
                $exam_model     =  OnlineExams::model()->findByPk($exam_id);
                if($question_type!='' && $question!='' && $exam_id!='')
                {
                    $model                  =   $this->loadQuestionModel($id);
                    $old_type               =   $model->question_type;
                    $model->exam_id         =   $exam_id;
                    $model->question        =   $question;
                    $model->question_type   =   $question_type;
                    $model->answer_id       =   NULL;
                    $model->mark            =   $_POST['OnlineExamQuestions']['mark'];
                    $model->created_by      =   Yii::app()->user->id;
                    $model->status          =   0;
                    if($model->save())
                    {                         
                        $exists    = OnlineExamAnswers::model()->exists('question_id = :question_id',array('question_id'=>$model->id));
                        if($exists!=NULL)
                        {
                            //delete all answers corresponding to question
                            $answers    = OnlineExamAnswers::model()->deleteAllByAttributes(array('question_id'=>$model->id));
                        }
                        //add answers as new entry and update answer id
                        if($question_type==1) //for multi choice
                        {
                            $options    =   $_POST['OnlineExamQuestions']['choice_answer'];
                            $answer_id  =   $_POST['choice_answer_id'];
                            foreach ($options as $key=>$choice)
                            {
                                $answer_model               =   new OnlineExamAnswers;
                                $answer_model->question_id  =   $model->id;
                                $answer_model->answer       =   $choice;
                                $answer_model->order        =   NULL;
                                if($answer_model->save())
                                {
                                    if($key==$answer_id)
                                    {
                                        //save correct answer id
                                        $model->answer_id   =   $answer_model->id;
                                        $model->save();
                                    }
                                }                                                                   
                            }
                        }
                        else if($question_type==2) //for true / false
                        {
                            $options= array('1'=>1, '0'=>0);
                            $answer_id  =   $_POST['OnlineExamQuestions']['type_answer'];
                            foreach ($options as $key=>$choice)
                            {
                                $answer_model               =   new OnlineExamAnswers;
                                $answer_model->question_id  =   $model->id;
                                $answer_model->answer       =   $choice;
                                $answer_model->order        =   NULL;
                                if($answer_model->save())
                                {
                                    if($key==$answer_id)
                                    {
                                        //save correct answer id
                                        $model->answer_id   =   $answer_model->id;
                                        $model->save();
                                    }
                                }                                
                            }
                        }
                        else if($question_type==3 || $question_type==4) //for single/multi line
                        {
                            $answer =   $_POST['OnlineExamQuestions']['exam_answer'];                            
                            $answer_model               =   new OnlineExamAnswers;
                            $answer_model->question_id  =   $model->id;
                            $answer_model->answer       =   $answer;
                            $answer_model->order        =   NULL;
                            if($answer_model->save())
                            {
                                $model->answer_id   =   $answer_model->id;
                                $model->save();
                            }
                        }
                                                
                        $batch_id='';
                        if($exam_model!=NULL)
                        {
                            $batch_id   =   $exam_model->batch_id;
                        }
                        $response['status']	= "success";
                        $response['data']	= Yii::app()->createUrl('onlineexam/exam/questions', array('id'=>$exam_id,'bid'=>$batch_id,'key'=>$model->id));
                    }
                    else
                    {
                        $response['message']	= Yii::t('app',"Can't save neq question");
                    }                    
                }
                else{
                $response['message']	= Yii::t('app',"Can't save neq question"); }
            }
            else{
                $response['message']	= Yii::t('app',"Can't save neq question"); }
                
            echo json_encode($response);
            Yii::app()->end();
        }
        
        //delete exam
        public function actionDelete($id)
        {
            if(Yii::app()->request->isPostRequest)
            {
                    // we only allow deletion via POST request
                    $model      =   $this->loadModel($id);
                    $batch_id   =   $model->batch_id;   
                    $model->is_deleted  =   1;
                    $model->save();

                    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
                    if(!isset($_GET['ajax']))
                            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index','bid'=>$batch_id));
            }
            else
                    throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
        }
           
        //view result
        public function actionResult()
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

                $this->render('result',array(
                'list'=>$posts,
                'pages' => $pages,
                'item_count'=>$total,
                'page_size'=>Yii::app()->params['listPerPage'],)) ;
            }
            else
                throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
            
        }
        
        //update exam status
        public function actionUpdateStatus()
        {
            $response	= array('status'=>'failed');
            if(isset($_POST['exam_id']) && $_POST['exam_id']!=NULL)
            {
                $status     =   $_POST['status'];
                $exam_id    =   $_POST['exam_id'];
                $model      =   OnlineExams::model()->findByPk($exam_id);
                if($model!=NULL)
                {
                    $model->status  =   $status;
                    if($model->save())
                    {
                        $response['status']	= "success";
                    }                    
                }
            } 
            echo json_encode($response);
            Yii::app()->end();
        }               

        public function actionAddChoice($ptrow=""){
		$model	= new OnlineExamQuestions;
		$data		= $this->renderPartial('add_question_choices',array('model'=>$model, 'ptrow'=>$ptrow), true);
		echo CJSON::encode(array('status'=>'success', 'data'=>$data));
	}
        
        protected function userRole(){
		$roles  = Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role		
		return key($roles);
	}
                
        protected function loadModel($id){
		$model	= OnlineExams::model()->findByPk($id);
		return $model;		
	}
        
        protected function loadQuestionModel($id){
		$model	= OnlineExamQuestions::model()->findByPk($id);
		return $model;		
	}
                                
}
?>