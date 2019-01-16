<?php

class CourseController extends RController
{
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','batch','addtrainee','Subjects','Timetable','Attendance','Exams','Trainees','Materials','Getbatch','Logdetails'),
				'users'=>array('@'),
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
	
	public function actionIndex()
	{	
            if(isset($_REQUEST['acc_id']) and $_REQUEST['acc_id'] != NULL){
           	 	$accademic	= AcademicYears::model()->findByPk(array($_REQUEST['acc_id']));
            }
            else{
            	$accademic	= AcademicYears::model()->findByAttributes(array('is_deleted'=> 0,'status'=>1));
            }			
		$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		$batches = Batches::model()->findAllByAttributes(array('employee_id'=>$employee->id,'academic_yr_id'=>$accademic->id, 'is_active'=>1, 'is_deleted'=>0));
		$batch_array=array();
		foreach($batches as $batch)
		{
			$batch_array[]=$batch->id;
		}				
		
		$timetables = TimetableEntries::model()->findAllByAttributes(array('employee_id'=>$employee->id));
		foreach($timetables as $timetable){
			$batch_details = Batches::model()->findByAttributes(array('id'=>$timetable->batch_id,'academic_yr_id'=>$accademic->id,'is_active'=>1, 'is_deleted'=>0));
			if($batch_details){
				if(!in_array($timetable->batch_id,$batch_array)){
					$batch_array[] = $timetable->batch_id;
				}
			}
		}		
		$this->render('index',array('batches_id'=>$batch_array));
	}
	
	
	
	public function actionBatch()
	{
		$this->render('batch');
	}
	public function actionAddtrainee()
	{
		
		
		
		
		$model=new Students;
		$criteria = new CDbCriteria;
		$criteria->compare('is_deleted',0);  // normal DB field
		$criteria->condition='is_deleted=:is_del';
		$criteria->params = array(':is_del'=>0);
		
		$criteria1 = new CDbCriteria;
		$criteria1->condition='batch_id<>:x OR batch_id IS NULL';
		$criteria1->params = array(':x'=>$_REQUEST['id']);

		 
		$stud=Students::model()->findAll($criteria1);
		
		 foreach($stud as $stud_1)
		   {
			   $students[] = $stud_1->id;
			   
		   }
		$criteria->addInCondition('id',$students);
		
		if(isset($_REQUEST['val']))
		{
		 $criteria->condition=$criteria->condition.' and '.'(first_name LIKE :match or last_name LIKE :match or middle_name LIKE :match)';
		 //$criteria->params = array(':match' => $_REQUEST['val'].'%');
		  $criteria->params[':match'] = $_REQUEST['val'].'%';
		}
		
		if(isset($_REQUEST['name']) and $_REQUEST['name']!=NULL)
		{
		if((substr_count( $_REQUEST['name'],' '))==0)
		 { 	
		 $criteria->condition=$criteria->condition.' and '.'(first_name LIKE :name or last_name LIKE :name or middle_name LIKE :name)';
		 $criteria->params[':name'] = $_REQUEST['name'].'%';
		}
		else if((substr_count( $_REQUEST['name'],' '))>=1)
		{
		 $name=explode(" ",$_REQUEST['name']);
		 $criteria->condition=$criteria->condition.' and '.'(first_name LIKE :name or last_name LIKE :name or middle_name LIKE :name)';
		 $criteria->params[':name'] = $name[0].'%';
		 $criteria->condition=$criteria->condition.' and '.'(first_name LIKE :name1 or last_name LIKE :name1 or middle_name LIKE :name1)';
		 $criteria->params[':name1'] = $name[1].'%';
		 	
		}
		}
		
		if(isset($_REQUEST['admissionnumber']) and $_REQUEST['admissionnumber']!=NULL)
		{
		 $criteria->condition=$criteria->condition.' and '.'admission_no LIKE :admissionnumber';
		 $criteria->params[':admissionnumber'] = $_REQUEST['admissionnumber'].'%';
		}
		
		if(isset($_REQUEST['Students']['batch_id']) and $_REQUEST['Students']['batch_id']!=NULL)
		{
			$model->batch_id = $_REQUEST['Students']['batch_id'];
			$criteria->condition=$criteria->condition.' and '.'batch_id = :batch_id';
		    $criteria->params[':batch_id'] = $_REQUEST['Students']['batch_id'];
		}
		
		//company checking
		if(isset($_REQUEST['Students']['parent_id']) and $_REQUEST['Students']['parent_id']!=NULL)
		{
			$model->parent_id		= $_REQUEST['Students']['parent_id'];
			$criteria->condition	= $criteria->condition.' and '.'parent_id = :parent_id';
		    $criteria->params[':parent_id']	= $_REQUEST['Students']['parent_id'];
		}
		
		if(isset($_REQUEST['Students']['gender']) and $_REQUEST['Students']['gender']!=NULL)
		{
			$model->gender = $_REQUEST['Students']['gender'];
			$criteria->condition=$criteria->condition.' and '.'gender = :gender';
		    $criteria->params[':gender'] = $_REQUEST['Students']['gender'];
		}
		
		/*if(isset($_REQUEST['Students']['blood_group']) and $_REQUEST['Students']['blood_group']!=NULL)
		{
			$model->blood_group = $_REQUEST['Students']['blood_group'];
			$criteria->condition=$criteria->condition.' and '.'blood_group = :blood_group';
		    $criteria->params[':blood_group'] = $_REQUEST['Students']['blood_group'];
		}*/
		
		if(isset($_REQUEST['Students']['nationality_id']) and $_REQUEST['Students']['nationality_id']!=NULL)
		{
			$model->nationality_id = $_REQUEST['Students']['nationality_id'];
			$criteria->condition=$criteria->condition.' and '.'nationality_id = :nationality_id';
		    $criteria->params[':nationality_id'] = $_REQUEST['Students']['nationality_id'];
		}
		
		
		if(isset($_REQUEST['Students']['dobrange']) and $_REQUEST['Students']['dobrange']!=NULL)
		{
			  
			  $model->dobrange = $_REQUEST['Students']['dobrange'] ;
			  if(isset($_REQUEST['Students']['date_of_birth']) and $_REQUEST['Students']['date_of_birth']!=NULL)
			  {
				  if($_REQUEST['Students']['dobrange']=='2')
				  {  
					  $model->date_of_birth = $_REQUEST['Students']['date_of_birth'];
					  $criteria->condition=$criteria->condition.' and '.'date_of_birth = :date_of_birth';
					  $criteria->params[':date_of_birth'] = date('Y-m-d',strtotime($_REQUEST['Students']['date_of_birth']));
				  }
				  if($_REQUEST['Students']['dobrange']=='1')
				  {  
				  
					  $model->date_of_birth = $_REQUEST['Students']['date_of_birth'];
					  $criteria->condition=$criteria->condition.' and '.'date_of_birth < :date_of_birth';
					  $criteria->params[':date_of_birth'] = date('Y-m-d',strtotime($_REQUEST['Students']['date_of_birth']));
				  }
				  if($_REQUEST['Students']['dobrange']=='3')
				  {  
					  $model->date_of_birth = $_REQUEST['Students']['date_of_birth'];
					  $criteria->condition=$criteria->condition.' and '.'date_of_birth > :date_of_birth';
					  $criteria->params[':date_of_birth'] = date('Y-m-d',strtotime($_REQUEST['Students']['date_of_birth']));
				  }
				  
			  }
		}
		elseif(isset($_REQUEST['Students']['dobrange']) and $_REQUEST['Students']['dobrange']==NULL)
		{
			  if(isset($_REQUEST['Students']['date_of_birth']) and $_REQUEST['Students']['date_of_birth']!=NULL)
			  {
				  $model->date_of_birth = $_REQUEST['Students']['date_of_birth'];
				  $criteria->condition=$criteria->condition.' and '.'date_of_birth = :date_of_birth';
				  $criteria->params[':date_of_birth'] = date('Y-m-d',strtotime($_REQUEST['Students']['date_of_birth']));
			  }
		}
		
		
		if(isset($_REQUEST['Students']['admissionrange']) and $_REQUEST['Students']['admissionrange']!=NULL)
		{
			  
			  $model->admissionrange = $_REQUEST['Students']['admissionrange'] ;
			  if(isset($_REQUEST['Students']['admission_date']) and $_REQUEST['Students']['admission_date']!=NULL)
			  {
				  if($_REQUEST['Students']['admissionrange']=='2')
				  {  
					  $model->admission_date = $_REQUEST['Students']['admission_date'];
					  $criteria->condition=$criteria->condition.' and '.'admission_date = :admission_date';
					  $criteria->params[':admission_date'] = date('Y-m-d',strtotime($_REQUEST['Students']['admission_date']));
				  }
				  if($_REQUEST['Students']['admissionrange']=='1')
				  {  
				  
					  $model->admission_date = $_REQUEST['Students']['admission_date'];
					  $criteria->condition=$criteria->condition.' and '.'admission_date < :admission_date';
					  $criteria->params[':admission_date'] = date('Y-m-d',strtotime($_REQUEST['Students']['admission_date']));
				  }
				  if($_REQUEST['Students']['admissionrange']=='3')
				  {  
					  $model->admission_date = $_REQUEST['Students']['admission_date'];
					  $criteria->condition=$criteria->condition.' and '.'admission_date > :admission_date';
					  $criteria->params[':admission_date'] = date('Y-m-d',strtotime($_REQUEST['Students']['admission_date']));
				  }
				  
			  }
		}
		elseif(isset($_REQUEST['Students']['admissionrange']) and $_REQUEST['Students']['admissionrange']==NULL)
		{
			  if(isset($_REQUEST['Students']['admission_date']) and $_REQUEST['Students']['admission_date']!=NULL)
			  {
				  $model->admission_date = $_REQUEST['Students']['admission_date'];
				  $criteria->condition=$criteria->condition.' and '.'admission_date = :admission_date';
				  $criteria->params[':admission_date'] = date('Y-m-d',strtotime($_REQUEST['Students']['admission_date']));
			  }
		}
		
		if(isset($_REQUEST['Students']['status']) and $_REQUEST['Students']['status']!=NULL)
		{
			$model->status = $_REQUEST['Students']['status'];
			$criteria->condition=$criteria->condition.' and '.'is_active = :status';
		    $criteria->params[':status'] = $_REQUEST['Students']['status'];
		}
		
		
			
			if(isset($_POST['bid'])) {
			
			
			foreach ($_POST['bid'] as $value)
			{
				$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
				$batch_student=Students::model()->findByAttributes(array('id'=>$value));
			    $current_batch=CurrentBatches::model()->findByAttributes(array('student_id'=>$value,'batch_id'=>$batch->id));
				/*if($current_batch != NULL)
				{
					$current_batch->status = 0;
					$current_batch->save(); 
					
				}*/
				$batch_student->saveAttributes(array('batch_id'=>$_REQUEST['id']));
				if($current_batch == NULL)
				{
					$today = date('Y-m-d H:i:s');
					$currentBatches = new CurrentBatches; 
					$currentBatches->student_id = $value;
					$currentBatches->batch_id = $_REQUEST['id'];
					$currentBatches->course_id = $batch->course_id;
					if(($batch->start_date < $today) and ($batch->end_date > $today))
					{
						$currentBatches->status = 1; 
					}
					else
					{
						$currentBatches->status = 0; 
					}
					$currentBatches->save();
				}
				
			}
			 $this->redirect(array('addtrainee','id'=>$_REQUEST['id']));
			}
			
		
		$criteria->order = 'first_name ASC';
		
		$total = Students::model()->count($criteria);
		$pages = new CPagination($total);
        $pages->setPageSize(Yii::app()->params['listPerPage']);
        $pages->applyLimit($criteria);  // the trick is here!
		/*print_r($criteria->condition);
		echo '<br/><br/>';
		var_dump($criteria->params);exit;*/
		$posts = Students::model()->findAll($criteria);
		
		 
		$this->render('addtrainee',array('model'=>$model,
		'list'=>$posts,
		'pages' => $pages,
		'item_count'=>$total,
		'page_size'=>Yii::app()->params['listPerPage'],)) ;
		
		
	
	}
	
	public function actionSubjects()
	{
		$model = new Subjects;
		$employee= Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		$batches=Batches::model()->findAllByAttributes(array('id'=>$_REQUEST['id'],'employee_id'=>$employee->id));
		
		if(isset($batches) && $batches!=NULL)
		{
			$dataProvider = new CActiveDataProvider('Subjects',array(
							'criteria'=>array(
							'condition'=>'batch_id = '.$_REQUEST['id'],
							)));
							
		}
		else 
		{
			$criteria = new CDbCriteria();
			$criteria->distinct=true;
			$criteria->select='subject_id';
			$criteria->condition = "batch_id=:col_val AND 
							employee_id=:col_val2";
			$criteria->params = array(':col_val' => $_REQUEST['id'], ':col_val2' => $employee->id); 
			$timetable=TimetableEntries::model()->findAll($criteria);    
			$dataProvider =array();
			$subject_id=array();
			foreach($timetable as $sub)
			{
				$subject_id[]=$sub->subject_id;
					
			}
			$criteria1 = new CDbCriteria();
			$criteria1->addInCondition('id', $subject_id);
			$dataProvider=Subjects::model()->findAll($criteria1);   
			$dataProvider = new CActiveDataProvider('Subjects',array('criteria'=>$criteria1));
			//var_dump($dataProvider);exit;
		}
		$this->render('subjects',array(
			'dataProvider'=>$dataProvider,
			'model'=>$model
		));
		
	}
		
	public function actionTimetable()
	{
		
		$this->render('timetable');
	}
	
	public function actionAttendance()
	{
		$this->render('attendance');
	}
	
	public function actionExams()
	{
		$this->render('exams');
	}
	public function actionTrainees()
	{
		 $criteria = new CDbCriteria;
		 $criteria->join= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`id`';	
		 $criteria->condition = '`t`.is_deleted=:is_deleted AND `t`.is_active=:is_active AND `t1`.result_status=:result_status AND `t1`.batch_id=:batch_id AND `t1`.status !=:status';
		 $criteria->params[':is_deleted'] = 0;
		 $criteria->params[':is_active'] = 1;
		 $criteria->params[':result_status'] = 0; 
		  $criteria->params[':status'] = 2;
		 $criteria->params[':batch_id'] = $_REQUEST['id'];
		 $criteria->order = '`t1`.roll_no'; 
		 $total = Students::model()->count($criteria);
		 $pages = new CPagination($total);
		 $pages->setPageSize(Yii::app()->params['listPerPage']);
		 $pages->applyLimit($criteria);  // the trick is here!
		 $posts = Students::model()->findAll($criteria);
		
		
		
		
		 
		$this->render('trainees',array(
		'list'=>$posts,
		'pages' => $pages,
		'item_count'=>$total,
		'page_size'=>Yii::app()->params['listPerPage'],)) ;
		
	}
	
	public function actionMaterials()
	{
		//$this->render('materials');
		$criteria	=	new CDbCriteria;
		$criteria->condition	=	'`file`<>:null AND `batch` =:batch_id';
		$criteria->params	=	array(':null'=>'',':batch_id'=>$_REQUEST['id']);
		$criteria->order	=	'`created_at` DESC';	
		$files		=	FileUploads::model()->findAll($criteria);
		if(isset($_POST['Downfiles'])){
			$selected_files	=	$_POST['Downfiles'];
			$slfiles	=	array();
			foreach($selected_files as $s_file){
				$model	=	FileUploads::model()->findByPk($s_file);
				if($model!=NULL){					
					$slfiles[]	=	'uploads/shared/'.$model->id.'/'.$model->file;
				}
			}			
			$zip			=	Yii::app()->zip;
			$fName			=	$this->generateRandomString(rand(10,20)).'.zip';
			$zipFile		=	'compressed/'.$fName;
			if($zip->makeZip($slfiles,$zipFile)){
				$fcon	=	file_get_contents($zipFile);
				header('Content-type:text/plain');
				header('Content-disposition:attachment; filename='.$fName);
				header('Pragma:no-cache');
				echo $fcon;
				unlink($zipFile);
			}
			else{
				Yii::app()->user->setFlash('success', Yii::t('app', 'Can\'t download'));
			}
			
		}
		$this->render('materials',array('files'=>$files));
	}
	
	private function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	
	
	public function actionGetbatch()
	{
		
		if(isset($_POST['course']))
		{
			$data = Batches::model()->findAll('course_id=:x AND is_deleted=:y',array(':x'=>$_POST['course'],':y'=>0));
		}
		echo CHtml::tag('option', array('value' => 0), CHtml::encode('Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"), true);
		$data=CHtml::listData($data,'id','name');
		  foreach($data as $value=>$title)
		  {
			  echo CHtml::tag('option',
						 array('value'=>$value),CHtml::encode($title),true);
		  }
	}
	public function actionElectiveView()
	{
	 $this->render('elective_view');	
	}
	
	
	// adding elective to students
	
		public function actionElective() 
			{
		if(isset($_POST['elective']))
		{
		
			if(isset($_POST['sid']))
        	 {
				
				  if(isset($_POST['elective_id']) and $_POST['elective_id']!=NULL)
					{ 
					  foreach($_POST['sid'] as $sid)
				 		{
							
							$Student=Students::model()->findByAttributes(array('id'=>$sid));
							
							$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$sid,'elective_group_id'=>$_POST['elective_group_id']));
							if($_POST['elective_id']!=NULL and $_POST['elective_id']!=0)
							{
								
								// new record
								if($student_elective==NULL)
								{
									$electives  = new StudentElectives;
									$electives->student_id = $sid;
									$electives->batch_id = $_REQUEST['id'];
									$electives->elective_id = $_POST['elective_id'];
									$electives->elective_group_id = $_POST['elective_group_id'];
									$electives->status = 1;
									$electives->created = date('Y-m-d h:i:s');
									$electives->save();
									Yii::app()->user->setFlash('success', "Elective added to the student");
							
								}
								else
								{
								
									Yii::app()->user->setFlash('error', "Elective is already assigned");
									$this->redirect(array('elective', 'id' =>$_REQUEST['id']));
								}
							}
							else
							{
								Yii::app()->user->setFlash('error', "Select  a subject");
								$this->redirect(array('elective', 'id' =>$_REQUEST['id']));
							}
						}
						
					 
					 $this->redirect(array('elective', 'id' =>$_REQUEST['id']));
					 }
					 else
					 {
						 Yii::app()->user->setFlash('bid', "Select a Subject!");
             			$this->redirect(array('elective', 'id' =>$_REQUEST['id']));
			 		  }
				 
				 }
				 else
				 {
					 if(isset($_POST['elective_id']) and $_POST['elective_id']!=NULL)
					 {
						 Yii::app()->user->setFlash('sid', "Select atleast one student!");
					 }
					 else
					 {
			
						 Yii::app()->user->setFlash('sid', Yii::t('app', "* Select atleast one student!"));
						 Yii::app()->user->setFlash('bid', Yii::t('app', "* Select a subject!"));
					 }
             		$this->redirect(array('elective', 'id' =>$_REQUEST['id']));
			 
		 	}
		}
		 $this->render('elective'); 
	}
	public function actionLog()
	{
		$cs=Yii::app()->clientScript;
		$cs->scriptMap=array(
			//'jquery.js'=>false,
			//'jquery.ui.js' => false,
		);
		
		$model=Students::model()->findByAttributes(array('id'=>$_REQUEST['student_id']));
		$criteria = new CDbCriteria;
		$criteria->order = 'date DESC';
		$criteria->condition='user_id=:x AND user_type=:type';
		$criteria->params[':x'] = $_REQUEST['student_id'];
		$criteria->params[':type'] = 1;
		$model1 = new LogComment;
		$total = LogComment::model()->count($criteria); // Count feeds
		$pages = new CPagination($total);
		$pages->setPageSize(Yii::app()->params['listPerPage']);
		$pages->applyLimit($criteria);
		
		
		$feeds = LogComment::model()->findAll($criteria); //var_dump($feeds);exit;// Get feeds
		$this->render('log',array(
			'model'=>$model,
			'model1'=>$model1,
			'comments'=>$feeds,
			'pages'=>$pages,
			'criteria'=>$criteria,
			
			));
	}
	
	public function actionStudentlog()
	{
		  $criteria               = new CDbCriteria;           
            $criteria->join         = "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`id`";           
            $criteria->condition    = "`t`.`is_active`=1 AND `t`.`is_deleted`=0 AND `bs`.`batch_id`=:batch_id AND `bs`.`status`=1 AND `bs`.`result_status`=0";
            $criteria->params       = array(":batch_id"=>$_REQUEST['id']);
            $criteria->order        = "`t`.`first_name` ASC, `t`.`last_name` ASC";
            $students               = Students::model()->findAll($criteria);
			$pages = new CPagination($total);
			$pages->setPageSize(Yii::app()->params['listPerPage']);
			$pages->applyLimit($criteria);  // the trick is here!
			$posts = Students::model()->findAll($criteria);
			$this->render('studentlog',array(
			'list'=>$posts,
			'pages' => $pages,
			'item_count'=>$total,
			'page_size'=>Yii::app()->params['listPerPage'],)) ;
	}
	
	
	public function actionStudents()
	{
		$this->render('studentprofile');
	}
	public function actionCourses()
	{
		$this->render('courses');
	}
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}