<?php

class ResultController extends RController
{
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}

	public function actionIndex()
	{
		
		$render=true;
		if($_GET['search_id']=='1' and isset($_GET['search_button']))
		{
			
			$flag = 0;
			if($year_id==NULL)
			{
				$current_academic_yr = Configurations::model()->findByPk(35);
				$year_id  = $current_academic_yr->config_value;					
			}
			if($_GET['course']==0 and $_GET['search_id']=='1' and $_GET['batch_id']==0 and $_GET['group_id']==0 and $_GET['exam_id']==0)
			{   
			    $flag=1;
				$criteria  = new CDbCriteria;
				
				$criteria->join		= ' JOIN `exams` `ee` ON `ee`.`id`=`t`.`exam_id`';
                $criteria->join		.= ' JOIN `students` `st` ON `st`.`id`=`t`.`student_id`';
				$criteria->join		.= 'LEFT JOIN `exam_groups` `eg` ON `eg`.`id`=`ee`.`exam_group_id`';
				$criteria->condition="`eg`.is_published =:is_published AND `st`.is_deleted=:is_deleted AND `st`.is_active=:is_active";
                $criteria->params=array(':is_published'=>1, ':is_deleted'=>0, ':is_active'=>1);
				//$criteria->group = 'id';
				$criteria->order = 'student_id DESC';
				
				$total = ExamScores::model()->count($criteria);
				$pages = new CPagination($total);
				$pages->setPageSize(Yii::app()->params['listPerPage']);
				$pages->applyLimit($criteria);  // the trick is here!
				$score = ExamScores::model()->findAll($criteria);
				//var_dump($pages);exit;
				$render=false;
				$this->render('index',array('score'=>$score,'search_id'=>$_GET['search_id'],
				'list'=>$score,
				'pages' => $pages,
				'item_count'=>$total,
				'page_size'=>Yii::app()->params['listPerPage'],
				 'flag'=>$flag,
				));
				//$score=ExamScores::model()->findAll($criteria);
				//var_dump($score);exit;
				//$this->render('index',array('score'=>$score,'flag'=>$flag,'search_id'=>$_POST['search_id']));
				
			}
			elseif($_GET['search_id']=='1' and $_GET['course']!=0 and $_GET['batch_id']==0 and $_GET['group_id']==0 and $_GET['exam_id']==0)
			{ 
				$flag=1;
				$course = $_GET['course'];
				$batch_id  = array();
				$batches  = Batches::model()->findAllByAttributes(array('course_id'=>$_GET['course']));
				foreach($batches as $batch)
				{
					$batch_id[] = $batch->id;
				}
				
				$criteria_3  = new CDbCriteria;
				$criteria_3->addInCondition('batch_id',$batch_id);
				//$criteria_3->addInCondition('is_active',1);
				//$criteria_3->addInCondition('is_deleted',0);
				
			
				
				$students = Students::model()->findAll($criteria_3);
				
				$student_id = array();
				foreach($students as $student)
				{
				    $student_id[]= 	$student->id;
				}
				//var_dump($student_id);exit;
				$criteria_1  = new CDbCriteria;
				
				$criteria_1->join		= ' JOIN `exams` `ee` ON `ee`.`id`=`t`.`exam_id`';
				$criteria_1->join		.= 'LEFT JOIN `exam_groups` `eg` ON `eg`.`id`=`ee`.`exam_group_id`';
				$criteria_1->condition="`eg`.is_published =:is_published";
	        	$criteria_1->params=array(':is_published'=>1);
			   // $criteria_1->condtion	 = '`eg`.`exam_groups`=1';
				$criteria_1->addInCondition('student_id',$student_id);
				//$criteria_1->group = 'id';
				$criteria_1->order = 'student_id DESC';
				
				$total = ExamScores::model()->count($criteria_1);
				$pages = new CPagination($total);
				$pages->setPageSize(Yii::app()->params['listPerPage']);
				$pages->applyLimit($criteria_1);  // the trick is here!
				$score = ExamScores::model()->findAll($criteria_1);
				$render=false;
				$this->render('index',array('score'=>$score,'search_id'=>$_GET['search_id'],
				'course'=>$_GET['course'],
				'list'=>$score,
				'pages' => $pages,
				'item_count'=>$total,
				'page_size'=>Yii::app()->params['listPerPage'],
				 'flag'=>$flag,
				));
				
				//$score_1=ExamScores::model()->findAll($criteria_1);
				
				//$this->render('index',array('score'=>$score_1,'flag'=>$flag,'search_id'=>$_POST['search_id'],'course'=>$_POST['course']));
			}
			elseif($_GET['search_id']=='1' and $_GET['batch_id']!=0 and $_GET['group_id']==0 and $_GET['exam_id']==0)
			{ 
				$flag=1;
				$batch = $_GET['batch_id'];
				$students = Students::model()->findAllByAttributes(array('batch_id'=>$_GET['batch_id'], 'is_active'=>1, 'is_deleted'=>0));
				
				$student_id = array();
				foreach($students as $student)
				{
				    $student_id[]= 	$student->id;
				}
				
				$criteria_1  = new CDbCriteria;
				
				$criteria_1->join		= ' JOIN `exams` `ee` ON `ee`.`id`=`t`.`exam_id`';
				$criteria_1->join		.= 'LEFT JOIN `exam_groups` `eg` ON `eg`.`id`=`ee`.`exam_group_id`';
				$criteria_1->condition="`eg`.is_published =:is_published";
	        	$criteria_1->params=array(':is_published'=>1);
				$criteria_1->addInCondition('student_id',$student_id);
				//$criteria_1->group = 'id';
				$criteria_1->order = 'student_id DESC';
				
				$total = ExamScores::model()->count($criteria_1);
				$pages = new CPagination($total);
				$pages->setPageSize(Yii::app()->params['listPerPage']);
				$pages->applyLimit($criteria_1);  // the trick is here!
				$score = ExamScores::model()->findAll($criteria_1);
				$render=false;
				$this->render('index',array('score'=>$score,'search_id'=>$_GET['search_id'],
				'course'=>$_GET['course'],
				'batch'=>$_GET['batch_id'],
				'list'=>$score,
				'pages' => $pages,
				'item_count'=>$total,
				'page_size'=>Yii::app()->params['listPerPage'],
				 'flag'=>$flag,
				));
				
				
				//$score_1=ExamScores::model()->findAll($criteria_1);
				
				//$this->render('index',array('score'=>$score_1,'flag'=>$flag,'search_id'=>$_POST['search_id'],'course'=>$_POST['course'],'batch'=>$_POST['batch_id']));
			}
			elseif($_GET['search_id']=='1' and $_GET['batch_id']!=0 and $_GET['group_id']!=0 and $_GET['exam_id']==0)
			{  
				$flag=1;
				$batch = $_GET['batch_id'];
				$group = $_GET['group_id'];
				
				$criteria_2  = new CDbCriteria;
				
				$criteria_2->join		= ' JOIN `subjects` `ss` ON `ss`.`id`=`t`.`subject_id`';
				$criteria_2->condition="`ss`.batch_id =:batch_id and exam_group_id = :exam_group_id";
				$criteria_2->params=array(':batch_id'=>$_GET['batch_id'],':exam_group_id'=>$_GET['group_id']);
				//$criteria_2->params[':exam_group_id'] = $_POST['group_id'];
				//$criteria_2->compare	= '`ss`.`batch_id`=$_POST['batch_id']';
				
				$exams = Exams::model()->findAll($criteria_2);
				
				$exams_id = array();
				foreach($exams as $exam)
				{
				    $exams_id[]= 	$exam->id;
				}
				
				$criteria_1  = new CDbCriteria;
				
				$criteria_1->join		= ' JOIN `exams` `ee` ON `ee`.`id`=`t`.`exam_id`';
				$criteria_1->join		.= 'LEFT JOIN `exam_groups` `eg` ON `eg`.`id`=`ee`.`exam_group_id`';
				$criteria_1->condition="`eg`.is_published =:is_published";
	        	$criteria_1->params=array(':is_published'=>1);
				
				$criteria_1->addInCondition('exam_id',$exams_id);
				//$criteria_1->group = 'id';
				$criteria_1->order = 'student_id DESC';
				
				$total = ExamScores::model()->count($criteria_1);
				$pages = new CPagination($total);
				$pages->setPageSize(Yii::app()->params['listPerPage']);
				$pages->applyLimit($criteria_1);  // the trick is here!
				$score = ExamScores::model()->findAll($criteria_1);
				$render=false;
				$this->render('index',array('score'=>$score,'search_id'=>$_GET['search_id'],
				'course'=>$_GET['course'],
				'batch'=>$_GET['batch_id'],
				'group'=>$_GET['group_id'],
				'list'=>$score,
				'pages' => $pages,
				'item_count'=>$total,
				'page_size'=>Yii::app()->params['listPerPage'],
				 'flag'=>$flag,
				));
				
				//$score_2=ExamScores::model()->findAll($criteria_1);
				
				//$this->render('index',array('score'=>$score_2,'flag'=>$flag,'search_id'=>$_POST['search_id'],'course'=>$_POST['course'],'batch'=>$_POST['batch_id'],'group'=>$_POST['group_id']));
			}
			elseif($_GET['search_id']=='1' and $_GET['batch_id']!=0 and $_GET['group_id']!=0 and $_GET['exam_id']!=0)
			{  
				$flag=1;
				$batch = $_GET['batch_id'];
				$group = $_GET['group_id'];
				$subject = $_GET['exam_id'];
				$exams = Exams::model()->findAllByAttributes(array('exam_group_id'=>$_GET['group_id'],'subject_id'=>$_GET['exam_id']));
				
				$exams_id = array();
				foreach($exams as $exam)
				{
				    $exams_id[]= 	$exam->id;
				}
				
				$criteria_1  = new CDbCriteria;
				
				$criteria_1->join		= ' JOIN `exams` `ee` ON `ee`.`id`=`t`.`exam_id`';
				$criteria_1->join		.= 'LEFT JOIN `exam_groups` `eg` ON `eg`.`id`=`ee`.`exam_group_id`';
				$criteria_1->condition="`eg`.is_published =:is_published";
	        	$criteria_1->params=array(':is_published'=>1);
				$criteria_1->addInCondition('exam_id',$exams_id);
				//$criteria_1->group = 'id';
				$criteria_1->order = 'student_id DESC';
				
				$total = ExamScores::model()->count($criteria_1);
				$pages = new CPagination($total);
				$pages->setPageSize(Yii::app()->params['listPerPage']);
				$pages->applyLimit($criteria_1);  // the trick is here!
				$score = ExamScores::model()->findAll($criteria_1);
				$render=false;
				$this->render('index',array('score'=>$score,'search_id'=>$_GET['search_id'],
				'course'=>$_GET['course'],
				'batch'=>$_GET['batch_id'],
				'group'=>$_GET['group_id'],
				'exam1'=>$_GET['exam_id'],
				'list'=>$score,
				'pages' => $pages,
				'item_count'=>$total,
				'page_size'=>Yii::app()->params['listPerPage'],
				 'flag'=>$flag,
				));
				
				//$score_3=ExamScores::model()->findAll($criteria_1);
				
				//$this->render('index',array('score'=>$score_3,'flag'=>$flag,'search_id'=>$_POST['search_id'],'course'=>$_POST['course'],'batch'=>$_POST['batch_id'],'group'=>$_POST['group_id'],'exam1'=>$_POST['exam_id']));
			}
		}
		elseif($_GET['search_id']=='2' and isset($_GET['search_button']))
		{
			
			if(isset($_GET['student_id']) and $_GET['student_id']!=NULL)
			{   
			
				$criteria_2  = new CDbCriteria;
				
				/*$criteria_2->condition='student_id LIKE :match';
				$criteria_2->params[':match'] = $_POST['student_id'];
				$criteria_2->order = 'student_id DESC';
				$criteria_2->join		= ' JOIN `exams` `ee` ON `ee`.`id`=`t`.`exam_id`';
				$criteria_2->join		.= 'LEFT JOIN `exam_groups` `eg` ON `eg`.`is_published`=1';*/
				
				$criteria_2->join		= ' JOIN `exams` `ee` ON `ee`.`id`=`t`.`exam_id`';
				$criteria_2->join		.= 'LEFT JOIN `exam_groups` `eg` ON `eg`.`id`=`ee`.`exam_group_id`';
				$criteria_2->condition="`eg`.is_published =:is_published and student_id LIKE :match";
				$criteria_2->params=array(':is_published'=>1,':match'=> $_GET['student_id']);
				
				$id=$_GET['student_id'];
				
				$total = ExamScores::model()->count($criteria_2);
				$pages = new CPagination($total);
				$pages->setPageSize(Yii::app()->params['listPerPage']);
				$pages->applyLimit($criteria);  // the trick is here!
				$posts = ExamScores::model()->findAll($criteria_2);
				
				
				$flag = 2;
				$render=false;
				$this->render('index',array('model'=>$model,'student'=>$id,
				'list'=>$posts,
				'pages' => $pages,
				'item_count'=>$total,
				'page_size'=>Yii::app()->params['listPerPage'],
				 'flag'=>$flag,
				));	
			}
		}
		
		if($render==true)
			$this->render('index');
	}
	public function actionStudentexampdf()
    {
        $student_name 	= Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$exam_name 		= ExamGroups::model()->findByAttributes(array('id'=>$_REQUEST['exam_group_id']));
		$filename 		= ucfirst($student_name->first_name).' '.ucfirst($student_name->last_name).' '.ucfirst($exam_name->name).Yii::t('app',' Assessment').' Report.pdf';		
		Yii::app()->osPdf->generate("application.modules.examination.views.result.studentexampdf", $filename, array());
	}
	public function actionResultpdf()
    {      
		$filename = 'Rsult Search'.' Report.pdf';ob_end_clean();		
		Yii::app()->osPdf->generate("application.modules.examination.views.result.resultpdf", $filename, array('search_id'=>$_REQUEST['search_id'],'course'=>$_REQUEST['course'],'batch'=>$_REQUEST['batch'],'group'=>$_REQUEST['group'],'exam'=>$_REQUEST['exam']));  
	}
    public function actionBatch()
	{		
	   $current_academic_yr = Configurations::model()->findByPk(35);
	   $year_id  = $current_academic_yr->config_value;	
	   
	   if($_REQUEST['course'])
	   {   
	       
			$data=Batches::model()->findAll('academic_yr_id=:id AND is_active=:x AND is_deleted=:y AND course_id=:z',array(':id'=>$year_id,':x'=>1,':y'=>0,':z'=>$_REQUEST['course']));
		}
	    else
	    {   
			 $data=Batches::model()->findAll('academic_yr_id=:id AND is_active=:x AND is_deleted=:y',array(':id'=>$year_id,':x'=>1,':y'=>0));
		}
			
		echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','All')), true);
		$data=CHtml::listData($data,'id','name');
		
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);
		}
	   
	 }
	  public function actionGroup()
	  {		
	   
	   if($_REQUEST['batch_id'])
	   {   
	       
			$data=ExamGroups::model()->findAll('batch_id=:x AND is_published=:y',array(':x'=>$_REQUEST['batch_id'],':y'=>1));
		}
	    else
	    {   
			 $data=ExamGroups::model()->findAll('is_published=:y',array(':y'=>1));
		}
			
		echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','All')), true);
		$data=CHtml::listData($data,'id','name');
		
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);
		}
	   
	 }
	 public function actionExam()
	  {		
	   $current_academic_yr = Configurations::model()->findByPk(35);
	   $year_id  = $current_academic_yr->config_value;	
	   
	   if($_REQUEST['group_id'])
	   {   
	       $exams=Exams::model()->findAll('exam_group_id=:x',array(':x'=>$_REQUEST['group_id']));
		   $subject_id=array();
		   foreach($exams as $exam)
		   {
			   $subject_id[] = $exam->subject_id;
		   }
			$criteria  = new CDbCriteria;
			$criteria ->compare('is_deleted',0);
			$criteria->addInCondition('id',$subject_id);
			$data=Subjects::model()->findAll($criteria);
			
		}
	    else
	    {   
			 $data=Subjects::model()->findAll('is_deleted=:y',array(':y'=>0));
		}
			
		echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','All')), true);
		$data=CHtml::listData($data,'id','name');
		
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);
		}
	   
	 }
	
}