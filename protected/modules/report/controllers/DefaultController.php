<?php



class DefaultController extends RController

{

	public function actionIndex()

	{

		$criteria = new CDbCriteria;

		$criteria->compare('is_deleted',0);

		$total = Employees::model()->count($criteria);

		$criteria->order = 'id DESC';

		$criteria->limit = '5';

		$posts = Employees::model()->findAll($criteria);

		

		$criteria1 = new CDbCriteria;

		$criteria1->compare('is_deleted',0);

		$total1 = Students::model()->count($criteria);

		$criteria1->order = 'id DESC';

		$criteria1->limit = '5';

		$posts1 = Students::model()->findAll($criteria);

		

		$this->render('index',array(

			'total'=>$total,'list'=>$posts,

			'total1'=>$total1,'list1'=>$posts1

		));

		

	}

	public function filters()

	{

		return array(

			'rights', // perform access control for CRUD operations

		);

	}

	public function actionAdvancedreport()

	{ 

		$model=new Students;

		$criteria = new CDbCriteria;

		$criteria->compare('is_deleted',0);  // normal DB field

		$flag=0;

		

		if(isset($_REQUEST['search']))

		{   		    

			if(isset($_REQUEST['guard']) and $_REQUEST['guard']!=NULL)

			{

				$flag=1;

			}

			else

			{

				$flag=0;

			}

			

				

			if(isset($_REQUEST['studentname']) and $_REQUEST['studentname']!=NULL)

			{

			

				//$criteria->condition='(first_name LIKE :match or last_name LIKE :match or middle_name LIKE :match)';

				//$criteria->params = array(':match' => $_POST['studentname'].'%');

				if((substr_count( $_REQUEST['studentname'],' '))==0)

				{ 	

					$criteria->condition=$criteria->condition.' and (first_name LIKE :name or last_name LIKE :name or middle_name LIKE :name)';

					$criteria->params[':name'] = $_REQUEST['studentname'].'%';

				}

				else if((substr_count( $_REQUEST['studentname'],' '))<=1)

				{

					$name=explode(" ",$_REQUEST['studentname']);

					$criteria->condition=$criteria->condition.' and (first_name LIKE :name or last_name LIKE :name or middle_name LIKE :name)';

					$criteria->params[':name'] = $name[0].'%';

					$criteria->condition=$criteria->condition.' and (first_name LIKE :name1 or last_name LIKE :name1 or middle_name LIKE :name1)';

					$criteria->params[':name1'] = $name[1].'%';

				}

			}

			if(isset($_REQUEST['admissionnumber']) and $_REQUEST['admissionnumber']!=NULL)

			{

				

				$criteria->condition=$criteria->condition.' and  admission_no LIKE :admission';

			   $criteria->params[':admission'] = $_REQUEST['admissionnumber'];

			}

			

			if(isset($_REQUEST['email']) and $_REQUEST['email']!=NULL)

			{

				

				$criteria->condition=$criteria->condition.' and  email LIKE :email';

			   $criteria->params[':email'] = $_REQUEST['email'].'%';

			}

		

			if(isset($_REQUEST['Students']['gender']) and $_REQUEST['Students']['gender']!=NULL)

			{

				

				$criteria->condition=$criteria->condition.' and gender LIKE :gender';

			   $criteria->params[':gender'] = $_REQUEST['Students']['gender'].'%';

			}

			if(isset($_REQUEST['Students']['blood_group']) and $_REQUEST['Students']['blood_group']!=NULL)

			{

	

				$criteria->condition=$criteria->condition.' and blood_group = :blood_group';

				 $criteria->params[':blood_group'] = $_REQUEST['Students']['blood_group'];

			}

					

			if(isset($_REQUEST['Students']['dobrange']) and $_REQUEST['Students']['dobrange']!=NULL)

			{

				 

				  $model->dobrange = $_REQUEST['Students']['dobrange'] ;

				  if(isset($_REQUEST['Students']['date_of_birth']) and $_REQUEST['Students']['date_of_birth']!=NULL)

				  {

					  if($_REQUEST['Students']['dobrange']=='2')

					  {  

						  $model->date_of_birth = $_REQUEST['Students']['date_of_birth'];

						  $criteria->condition=$criteria->condition.' and  date_of_birth = :date_of_birth';

						  $criteria->params[':date_of_birth'] = date('Y-m-d',strtotime($_REQUEST['Students']['date_of_birth']));

					  }

					  if($_REQUEST['Students']['dobrange']=='1')

					  {  

					  

						  $model->date_of_birth = $_REQUEST['Students']['date_of_birth'];

						  $criteria->condition=$criteria->condition.' and date_of_birth < :date_of_birth';

						  $criteria->params[':date_of_birth'] = date('Y-m-d',strtotime($_REQUEST['Students']['date_of_birth']));

					  }

					  if($_REQUEST['Students']['dobrange']=='3')

					  {  

						  $model->date_of_birth = $_REQUEST['Students']['date_of_birth'];

						  $criteria->condition=$criteria->condition.' and date_of_birth > :date_of_birth';

						  $criteria->params[':date_of_birth'] = date('Y-m-d',strtotime($_REQUEST['Students']['date_of_birth']));

					  }

					  

				  }

			}

			elseif(isset($_REQUEST['Students']['dobrange']) and $_REQUEST['Students']['dobrange']==NULL)

			{

				  if(isset($_REQUEST['Students']['date_of_birth']) and $_REQUEST['Students']['date_of_birth']!=NULL)

				  {

					  $model->date_of_birth = $_REQUEST['Students']['date_of_birth'];

					  $criteria->condition=$criteria->condition.' and date_of_birth = :date_of_birth';

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

						  $criteria->condition=$criteria->condition.' and admission_date = :admission_date';

						  $criteria->params[':admission_date'] = date('Y-m-d',strtotime($_REQUEST['Students']['admission_date']));

					  }

					  if($_REQUEST['Students']['admissionrange']=='1')

					  {  

					  

						  $model->admission_date = $_REQUEST['Students']['admission_date'];

						  $criteria->condition=$criteria->condition.' and  admission_date < :admission_date';

						  $criteria->params[':admission_date'] = date('Y-m-d',strtotime($_REQUEST['Students']['admission_date']));

					  }

					  if($_REQUEST['Students']['admissionrange']=='3')

					  {  

						  $model->admission_date = $_REQUEST['Students']['admission_date'];

						  $criteria->condition=$criteria->condition.' and admission_date > :admission_date';

						  $criteria->params[':admission_date'] = date('Y-m-d',strtotime($_REQUEST['Students']['admission_date']));

					  }

					  

				  }

			}

			elseif(isset($_REQUEST['Students']['admissionrange']) and $_REQUEST['Students']['admissionrange']==NULL)

			{

				if(isset($_REQUEST['Students']['admission_date']) and $_REQUEST['Students']['admission_date']!=NULL)

				{

					$model->admission_date = $_REQUEST['Students']['admission_date'];

					$criteria->condition=$criteria->condition.' and admission_date = :admission_date';

					$criteria->params[':admission_date'] = date('Y-m-d',strtotime($_REQUEST['Students']['admission_date']));

				}

			  

			}

			

			$criteria->order = 'first_name ASC';

			

			if(isset($_REQUEST['pdf-btn'])){

				$students 	= Students::model()->findAll($criteria);

				$filename 	= 'Students.pdf';	

				Yii::app()->osPdf->generate("application.modules.report.views.default.advancedReportPdf", $filename, array('students'=>$students, 'flag'=>$flag));

				exit;

			}

			

			$total = Students::model()->count($criteria);

			$pages = new CPagination($total);

			$pages->setPageSize(Yii::app()->params['listPerPage']);

			$pages->applyLimit($criteria);  // the trick is here!

			$posts = Students::model()->findAll($criteria);

			$this->render('advancedreport',array('model'=>$model,'flag'=>$flag,

				'list'=>$posts,

				'pages' => $pages,

				'item_count'=>$total,

				'page_size'=>Yii::app()->params['listPerPage'],)) ;

		

				

		}

		else

		{  

			$this->render('advancedreport',array('model'=>$model,'flag'=>$flag));

		}

			

	}

	public function loadModel($id)

	{

		$model=ExamGroups::model()->findByPk($id);

		if($model===null)

			throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));

		return $model;

	}

        

        public function loadCbscModel($id)

	{

		$model=  CbscExamGroup17::model()->findByPk($id);

		if($model===null)

			throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));

		return $model;

	}

        

	public function actionAssessment()

	{

		$model_1=new ExamGroups;

		$criteria = new CDbCriteria;		

		if(isset($_POST['search']) && $_REQUEST['yid']!=NULL && $_REQUEST['batch']!=NULL  )

		{		
                    Yii::app()->user->setState('ses_session_yr',$_POST['yid']);
                    Yii::app()->user->setState('ses_semester_id',$_POST['semester_id']);
                    Yii::app()->user->setState('ses_batch_id',$_POST['batch']);
                    
                    $cbsc_format    = ExamFormat::getCbscformat($_REQUEST['batch']);

                    $exam_format	 = ExamFormat::model()->getExamformat($_REQUEST['batch']);// 1=>normal 2=>cbsc		

                    

                    if($exam_format==1){ 

                        if(isset($_POST['ExamGroups']['id']) &&  $_POST['ExamGroups']['id']!=NULL)

                        {

                            $criteria->condition='exam_group_id LIKE :match';

                            $criteria->params = array(':match' => $_POST['ExamGroups']['id'].'%');

                        }			

                    

                        $total = Exams::model()->count($criteria);

                        $pages = new CPagination($total);

                        $pages->setPageSize(Yii::app()->params['listPerPage']);

                        $pages->applyLimit($criteria);  // the trick is here!

                        $posts = Exams::model()->findAll($criteria);
                        $this->render('assessment',array('model_1'=>$model_1,'list'=>$posts, 'batch_id'=>$_POST['batch'],'group_id'=>$_POST['ExamGroups']['id'],'year_id'=>$_POST['yid'])) ;												

                    }
 
                    else if($cbsc_format){

                        if(isset($_POST['ExamGroups']['id']) &&  $_POST['ExamGroups']['id']!=NULL)

                        {

                            $criteria->condition='exam_group_id = :match';

                            $criteria->params = array(':match' => $_POST['ExamGroups']['id']);

                        }			

                      

                        $total = CbscExams17::model()->count($criteria);

                        $pages = new CPagination($total);

                        $pages->setPageSize(Yii::app()->params['listPerPage']);

                        $pages->applyLimit($criteria);  // the trick is here!

                        $posts = CbscExams17::model()->findAll($criteria);

                        $this->render('cbsc17/assessment',array('model_1'=>$model_1,'list'=>$posts, 'semester_id'=>$_POST['semester_id'], 'batch_id'=>$_POST['batch'],'group_id'=>$_POST['ExamGroups']['id'],'year_id'=>$_POST['yid'])) ;
						

                    }

		}

		else

		{

			$this->render('assessment',array('model_1'=>$model_1));

		}

	}

        

        public function actionCbscscore()

        {

            $pdf_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));

            $filename= $pdf_name->name.Yii::t('app','Assessment').' Report.pdf';

            Yii::app()->osPdf->generate("application.modules.report.views.default.cbsc17.assesspdf", $filename, array('model'=>$this->loadCbscModel($_REQUEST['examid'])),1);        

	}

        

	public function actionBatch()

	{

		

		if(isset($_POST['batch']))

		{

                    $batch_id=  $_POST['batch'];

                    $cbsc_format    = ExamFormat::getCbscformat($batch->id);

                    if(ExamFormat::model()->getExamformat($batch_id)==1 ){

                        $data=ExamGroups::model()->findAll('batch_id=:x',array(':x'=>$_POST['batch']));

                    }

                    else if($cbsc_format) //return true for cbsc 2017

                    {

                        $data=  CbscExamGroup17::model()->findAll('batch_id=:x',array(':x'=>$_POST['batch']));

                    }

		}

		echo CHtml::tag('option', array('value' => ''), CHtml::encode(Yii::t('app','Select Examination')), true);

		$data=CHtml::listData($data,'id','name');

		  foreach($data as $value=>$title)

		  {

			  echo CHtml::tag('option',

						 array('value'=>$value),CHtml::encode($title),true);

		  }

	}

	

	public function actionBatchlist()

	{				    	

		$data=Batches::model()->findAll('academic_yr_id=:id AND is_active=:x AND is_deleted=:y',array(':id'=>(int) $_POST['yid'],':x'=>1,':y'=>0));

		echo CHtml::tag('option', array('value' => ''), CHtml::encode(Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id")), true);

		$data=CHtml::listData($data,'id','name');

		foreach($data as $value=>$name)

		{

			echo CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);

		}

	}

	

	public function actionBatches()

	{

		$current_academic_yr = Configurations::model()->findByPk(35);

		$id	=$current_academic_yr->config_value;

		$batch_list 	= "<option value=''>".Yii::t('app','Select').' '.Students::model()->getAttributeLabel('batch_id')."</option>";

		$examination	= "<option value=''>".Yii::t('app','Select Examination')."</option>";
		
		
		$semester		= "<option value=''>".Yii::t('app','Select Semester')."</option>";
		$criteria=new CDbCriteria;
		$criteria->join= 'JOIN semester_courses `sc` ON t.id = `sc`.semester_id'; 
		$sem_datas	= Semester::model()->findAll($criteria);
		if($sem_datas){
			$sem_datas		= CHtml::listData($sem_datas,'id','name');
			$data_list 		= CMap::mergeArray(array(0=>Yii::t('app','Batch without semester')),$sem_datas);
			foreach($data_list as $val=>$sem){
				$semester .= CHtml::tag('option', array('value'=>$val),CHtml::encode(html_entity_decode($sem)),true);
			}
		}
		
		$datas			= Batches::model()->findAll('academic_yr_id=:id AND is_active=:x AND is_deleted=:y',array(':id'=>(int) $id,':x'=>1,':y'=>0));

		if($datas){

			$datas		= CHtml::listData($datas,'id','coursename');

			foreach($datas as $value=>$data){

				$batch_list .= CHtml::tag('option', array('value'=>$value),CHtml::encode(html_entity_decode($data)),true);

			}

		}
		echo json_encode(array('batch_list'=>$batch_list, 'examination'=>$examination,'semester'=>$semester));

	}

	

	public function actionBatchname()

	{			

		$data=Batches::model()->findAll('course_id=:id AND is_active=:x AND is_deleted=:y',array(':id'=>(int) $_POST['cid'],':x'=>1,':y'=>0));

		echo CHtml::tag('option', array('value' => ''), CHtml::encode(Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id")), true);

		$data=CHtml::listData($data,'id','name');

		foreach($data as $value=>$name)

		{

			echo CHtml::tag('option',array('value'=>$value),CHtml::encode(html_entity_decode($name)),true);

		}

	}

	public function actionSelectSemBatch()

	{

		$semid		=	$_GET['semid'];

		$sid		=	$_GET['sid'];

		$criteria = new CDbCriteria;

		$criteria->select = 't.id, t.name,t.course_id';

		$criteria->join = ' LEFT JOIN `batch_students` AS `b` ON t.id = b.batch_id';

		$criteria->condition = 't.semester_id = :semester_id AND b.student_id=:student_id';

        $criteria->params = array(":semester_id" => $semid,":student_id"=>$sid);

		$batches    =    Batches::model()->findAll($criteria);

		echo CHtml::tag('option', array('value' => ''), CHtml::encode(Yii::t('app','Select Batch')), true);

		foreach($batches as $batch)

		{

			$course    =    Courses::model()->findByPk($batch->course_id);

			echo CHtml::tag('option',array('value'=>$batch->id),CHtml::encode(html_entity_decode($batch->name.' ('.$course->course_name.')')),true);

		}

		

	}

	public function actionSembatches()

	{

		$current_academic_yr = Configurations::model()->findByPk(35);

		$yid	=$current_academic_yr->config_value; 
		if(isset($_POST['semester_id']) && $_POST['semester_id']!=NULL)
		{ 
               if($_POST['semester_id'] != 0)
			   {
					if(isset($_POST['status']) && $_POST['status']!=NULL)
					{ 
							echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','Select Batch')), true);
							$data = Batches::model()->findAll('academic_yr_id=:x AND is_deleted=:y AND is_active=1 AND semester_id=:sem_id',array(':x'=>$yid,':y'=>0,':sem_id'=>$_POST['semester_id']));	
							foreach ($data as $da){							
								$batch_data[$da->id] = $da->name.' ( '.$da->course123->course_name.' )';
							}
							//$data=CHtml::listData($data,'id','name'); 
							foreach($batch_data as $value=>$title)
							{
									echo CHtml::tag('option',array('value'=>$value),CHtml::encode(html_entity_decode($title)),true);
							}
					}else{
						$course_id= $_POST['course_id']; 
	
						$data = Batches::model()->findAll('academic_yr_id=:x AND is_deleted=:y AND is_active=1 AND semester_id=:sem_id AND course_id=:course_id',array(':x'=>$yid,':y'=>0,':sem_id'=>$_POST['semester_id'],':course_id'=>$course_id));				
	
						echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','Select')), true);
	
						$data=CHtml::listData($data,'id','name'); 
	
						foreach($data as $value=>$title)
	
						{
							echo CHtml::tag('option',array('value'=>$value),CHtml::encode(html_entity_decode($title)),true);
	
						}
					}
			   }
			   else
			   {
					   if(isset($_POST['status']) && $_POST['status']!=NULL)
						{ 
						
							echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','Select')), true);
							
							$criteria=new CDbCriteria;
							
							// $criteria->join= 'JOIN semester_courses `sc` ON t.id = `sc`.semester_id';
							
							$criteria->condition='is_deleted=0 AND is_active=1 AND academic_yr_id=:year';
							
							$criteria->params=array(':year'=>$yid);
							
							$criteria->addCondition('semester_id IS NULL');
							
							$data	= Batches::model()->findAll($criteria);
							
							$data	= CHtml::listData($data, 'id', 'name');	
							/*echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','Select Batch')), true);
							$data = Batches::model()->findAll('academic_yr_id=:x AND is_deleted=:y AND is_active=1',array(':x'=>$yid,':y'=>0));
							foreach ($data as $da){							
								$batch_data[$da->id] = $da->name.' ( '.$da->course123->course_name.' )';
							} */
							
							foreach($data as $value=>$title)
							{
									echo CHtml::tag('option',array('value'=>$value),CHtml::encode(html_entity_decode($title)),true);
							}
					
					}else{
						echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','Select')), true);
		
						$criteria=new CDbCriteria;
		
						// $criteria->join= 'JOIN semester_courses `sc` ON t.id = `sc`.semester_id';
		
						 $criteria->condition='course_id =:course_id AND is_deleted=0 AND is_active=1 AND academic_yr_id=:year';
		
						 $criteria->params=array(':course_id'=>$_POST['course_id'],':year'=>$yid);
		
						 $criteria->addCondition('semester_id IS NULL');
		
						 $data	= Batches::model()->findAll($criteria);
		
						 $data	= CHtml::listData($data, 'id', 'name');		
		
						 foreach($data as $value=>$name){
		
								 echo CHtml::tag('option',array('value'=>$value),CHtml::encode(html_entity_decode($name)),true);
		
						 }
					}
			   }

			}
			else if(isset($_POST['semester_id']) && $_POST['semester_id']=='')

			{
				if(isset($_POST['status']) && $_POST['status']!=NULL)
				{ 
						echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','Select Batch')), true);
						$data = Batches::model()->findAll('academic_yr_id=:x AND is_deleted=:y AND is_active=1',array(':x'=>$yid,':y'=>0));
						foreach ($data as $da){							
							$batch_data[$da->id] = $da->name.' ( '.$da->course123->course_name.' )';
						}
						//$data=CHtml::listData($data,'id','name'); 
						foreach($batch_data as $value=>$title)
						{
								echo CHtml::tag('option',array('value'=>$value),CHtml::encode(html_entity_decode($title)),true);
						}
				
				}else{
					echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','Select')), true);
	
					$criteria=new CDbCriteria;
	
					// $criteria->join= 'JOIN semester_courses `sc` ON t.id = `sc`.semester_id';
	
					 $criteria->condition='course_id =:course_id AND is_deleted=0 AND is_active=1 AND academic_yr_id=:year';
	
					 $criteria->params=array(':course_id'=>$_POST['course_id'],':year'=>$yid);
	
					 $criteria->addCondition('semester_id IS NULL');
	
					 $data	= Batches::model()->findAll($criteria);
	
					 $data	= CHtml::listData($data, 'id', 'name');		
	
					 foreach($data as $value=>$name){
	
							 echo CHtml::tag('option',array('value'=>$value),CHtml::encode(html_entity_decode($name)),true);
	
					 }
				}

			}

	}

	public function actionSelectSemester()

	{

		$id		=	$_GET['id'];

		$stu_batchs=BatchStudents::model()->findAllByAttributes(array("student_id"=>$id)); 

		$datas=array();

		

		foreach($stu_batchs as $stu_batch)

		{

			$bid	=	$stu_batch->batch_id;

			$batch	=	Batches::model()->findByPk($bid);

			$sem	=	Semester::model()->findByPk($batch->semester_id);

			if(isset($sem) and $sem!=NULL){

				if(!in_array($sem->id,$data))

					$data[]=$sem->id;

			}

						

		}

		echo CHtml::tag('option', array('value' => ''), CHtml::encode(Yii::t('app','Select Semester')), true);

		for($i=0;$i<count($data);$i++){

			$sems	=	Semester::model()->findByPk($data[$i]);

			echo CHtml::tag('option',array('value'=>$sems->id),CHtml::encode(html_entity_decode($sems->name)),true);

		}

	}

	public function actionSemesterreport()

	{

	  if(Configurations::model()->isSemesterEnabled()){

		$model=new Students;

		$flag=0;

			if(isset($_POST['search']))

			{

				

				$sid	=	$_POST['student_id'];

				$semid	=	$_POST['semester_id'];

				$bid	=	$_POST['batch_id'];

				$criteria = new CDbCriteria;

				if(isset($_POST['student_id']) and $_POST['student_id']!=NULL and isset($_POST['semester_id']) and $_POST['semester_id']!=NULL and isset($_POST['batch_id']) and $_POST['batch_id']!=NULL )

				{

					$criteria->condition='student_id LIKE :match';

					$criteria->params[':match'] = $_POST['student_id'];

				}

				else

				{

					$flag = 1;

					//$this->redirect(array('studentexam','flag'=>$flag));

					$this->render('semesterreport',array('flag'=>$flag,'student'=>$sid,

						'semid'=>$semid,

						'bid'=>$bid,));

					exit;

				}

			

			$total = ExamScores::model()->count($criteria);

			$pages = new CPagination($total);

      	    $pages->setPageSize(Yii::app()->params['listPerPage']);

			$pages->applyLimit($criteria);  // the trick is here!

			$posts = ExamScores::model()->findAll($criteria);

			

				$flag = 1;

				$this->render('semesterreport',array('model'=>$model,

				'student'=>$sid,

				'semid'=>$semid,

				'bid'=>$bid,

				'list'=>$posts,

				'pages' => $pages,

				'item_count'=>$total,

				'page_size'=>Yii::app()->params['listPerPage'],

				));

			exit;

		}

		

			$this->render('semesterreport',array('model'=>$model));

	 }

	 else{

		$this->redirect(array('//report'));

	 }



		

	}

	public function actionStudentexam()

	{

		

		$model=new Students;

		$flag=0;

                if(isset($_POST['search']) && $_POST['student_id'])

                {

                    

                    $details=Students::model()->findByAttributes(array('id'=>$_POST['student_id'],'is_deleted'=>0,'is_active'=>1));

                    if($details!=NULL && $details->batch_id!=NULL){

                        

                        $cbsc_format    = ExamFormat::getCbscformat($details->batch_id);

                        $exam_format	 = ExamFormat::model()->getExamformat($details->batch_id);// 1=>normal 2=>cbsc	

                        if($exam_format==1){

                            $criteria = new CDbCriteria;

                            if(isset($_POST['student_id']) and $_POST['student_id']!=NULL)

                            {

                                $criteria->condition='student_id LIKE :match';

                                $criteria->params[':match'] = $_POST['student_id'];

                                $id=$_POST['student_id'];	

                            }

                            else

                            {

                                $flag = 1;

                                //$this->redirect(array('studentexam','flag'=>$flag));

                                $this->render('studentexam',array('flag'=>$flag));

                                exit;

                            }			

                            $total = ExamScores::model()->count($criteria);

                            $pages = new CPagination($total);

                            $pages->setPageSize(Yii::app()->params['listPerPage']);

                            $pages->applyLimit($criteria);  // the trick is here!

                            $posts = ExamScores::model()->findAll($criteria);						

                            $flag = 1;

                            $this->render('studentexam',array('model'=>$model,'student'=>$id,

                            'list'=>$posts,

                            'pages' => $pages,

                            'item_count'=>$total,

                            'page_size'=>Yii::app()->params['listPerPage'],

                            ));

                            exit;

                        }else

                        {

                            $criteria = new CDbCriteria;

                            if(isset($_POST['student_id']) and $_POST['student_id']!=NULL)

                            {

                                $criteria->condition='student_id LIKE :match';

                                $criteria->params[':match'] = $_POST['student_id'];

                                $id=$_POST['student_id'];	

                            }

                            else

                            {

                                $flag = 1;

                                //$this->redirect(array('studentexam','flag'=>$flag));

                                $this->render('studentexam',array('flag'=>$flag));

                                exit;

                            }			

                            $total = CbscExamScores17::model()->count($criteria);

                            $pages = new CPagination($total);

                            $pages->setPageSize(Yii::app()->params['listPerPage']);

                            $pages->applyLimit($criteria);  // the trick is here!

                            $posts = CbscExamScores17::model()->findAll($criteria);						

                            $flag = 1;

                            $this->render('cbsc17/studentexam',array('model'=>$model,'student'=>$id,

                            'list'=>$posts,

                            'pages' => $pages,

                            'item_count'=>$total,

                            'page_size'=>Yii::app()->params['listPerPage'],

                            ));

                            exit;

                        }

                    }

                    

                    

                   

		}

		

                $this->render('studentexam',array('model'=>$model));

		

	}

	public function actionEmployeeattendance()

	{

		if(Configurations::model()->teacherAttendanceMode() != 2){

			if(isset($_POST['dep_id'])){

				$this->render('employeeattendance',array('dep_id'=>$_POST['dep_id']));

			}

			else{

				$this->render('employeeattendance');

			}

		}

		else{

			$this->redirect(array('/report'));

		}

		

	}

	public function actionStudentattendance()

	{

		if(isset($_POST['batch_id']))

		{

			$this->render('studentattendance',array('batch_id'=>$_POST['batch_id']));

		}

		else

		{

			$this->render('studentattendance');

		}

		

	}

	 public function actionAssessmentpdf()

        {

        $pdf_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));

		

        $filename= $pdf_name->name.Yii::t('app','Assessment').' Report.pdf';

        Yii::app()->osPdf->generate("application.modules.report.views.default.assesspdf", $filename, array('model'=>$this->loadModel($_REQUEST['examid'])),1);

        

	}

	

	public function actionStudentcbscpdf()

	{

		$student_name   = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));

		$exam_name      = CbscExamGroup17::model()->findByAttributes(array('id'=>$_REQUEST['exam_group_id']));
		$filename= ucfirst($student_name->first_name).' '.ucfirst($student_name->last_name).' '.ucfirst($exam_name->name).Yii::t('app',' Assessment').' Report.pdf';

		if($exam_name->class==1) //class 1-2

		{
			
			Yii::app()->osPdf->generate("application.modules.report.views.default.cbsc17.studentexampdf1", $filename, array());
			

		}

		else if($exam_name->class==2){ //class 3-8
			
			Yii::app()->osPdf->generate("application.modules.report.views.default.cbsc17.studentexampdf2", $filename, array());
			
		}

		else if($exam_name->class==3){ //class 9-10
			
			Yii::app()->osPdf->generate("application.modules.report.views.default.cbsc17.studentexampdf3", $filename, array());
			
		}

		else if($exam_name->class==4){ //class 11-12
           
			Yii::app()->osPdf->generate("application.modules.report.views.default.cbsc17.studentexampdf4", $filename, array());
			
		}

		

	

}

	public function actionStudentexampdf()

        {

        $student_name = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));

	$exam_name = ExamGroups::model()->findByAttributes(array('id'=>$_REQUEST['exam_group_id']));

		 

        $filename= ucfirst($student_name->first_name).' '.ucfirst($student_name->last_name).' '.ucfirst($exam_name->name).Yii::t('app',' Assessment').' Report.pdf';

        Yii::app()->osPdf->generate("application.modules.report.views.default.studentexampdf", $filename, array());

        

	}

	public function actionSemesterexampdf()

    {

        $student_name = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));

		$exam_name = ExamGroups::model()->findByAttributes(array('id'=>$_REQUEST['exam_group_id']));

		 

        $filename= ucfirst($student_name->first_name).' '.ucfirst($student_name->last_name).' '.ucfirst($exam_name->name).Yii::t('app',' Assessment').' Report.pdf';

        Yii::app()->osPdf->generate("application.modules.report.views.default.semesterexampdf", $filename, array());

        

	}

	

	public function actionEmpoverallpdf()

    {

      	$department_name = EmployeeDepartments::model()->findByAttributes(array('id'=>$_REQUEST['id']));		

        

        $filename= ucfirst($department_name->name).Yii::t('app',' Employees Overall Attendance').' Report.pdf';

        Yii::app()->osPdf->generate("application.modules.report.views.default.empoverallpdf", $filename, array());

        

	}

	

	public function actionEmpyearlypdf()

    {

      	$department_name = EmployeeDepartments::model()->findByAttributes(array('id'=>$_REQUEST['id']));		

        

        $filename= ucfirst($department_name->name).Yii::t('app',' Employees Yearly Attendance Report ').$_REQUEST['year'].'.pdf';

        Yii::app()->osPdf->generate("application.modules.report.views.default.empyearlypdf", $filename, array());

        

	}

	

	public function actionEmpmonthlypdf()

    {

      	$department_name = EmployeeDepartments::model()->findByAttributes(array('id'=>$_REQUEST['id']));		

        

         $filename= ucfirst($department_name->name).Yii::t('app',' Employees Monthly Attendance Report ').$_REQUEST['month'].'.pdf';

        Yii::app()->osPdf->generate("application.modules.report.views.default.empmonthlypdf", $filename, array());


	}

	

	public function actionEmpindividualpdf()

        {

            $employee_name = Employees::model()->findByAttributes(array('id'=>$_REQUEST['employee']));		        

            $filename= Employees::model()->getTeachername($employee_name->id).Yii::t('app',' Teacher Attendance').' Report.pdf';

            Yii::app()->osPdf->generate("application.modules.report.views.default.empindividualpdf", $filename, array());

	}

	

	public function actionStudentoverallpdf()

    {

      	$batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));

	        

        $filename= ucfirst($batch_name->name).Yii::t('app',' Students Overall Attendance').'Report.pdf';

        Yii::app()->osPdf->generate("application.modules.report.views.default.studentoverallpdf", $filename, array());

	}

	

	public function actionStudentyearlypdf()

    {

      	$batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));		

        

        $filename= ucfirst($batch_name->name).Yii::t('app',' Students Yearly Attendance Report ').$_REQUEST['year'].'.pdf';

        Yii::app()->osPdf->generate("application.modules.report.views.default.studentyearlypdf", $filename, array());

        

	}

	public function actionPercentpdf()

    {

		$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));

		if(Yii::app()->user->year)

		{

		$year = Yii::app()->user->year;

		//echo Yii::app()->user->year;

		}

		else

		{

		$year = $current_academic_yr->config_value;

		} 

		if($_GET['batch_id'] == 'All')

		{	

		$criteria = new CDbCriteria;

		$criteria->condition = "is_deleted=:x AND academic_yr_id=:y";

		$criteria->params = array(':x'=>'0',':y'=>$year);

		

		$batches = Batches::model()->findAll($criteria);

		//$batches = Batches::model()->findAll("is_deleted=:x AND academic_yr_id=:y", array(':x'=>'0',':y'=>$year));

		

		}

		else{	

		$batches = Batches::model()->findAll("is_deleted=:x AND academic_yr_id=:y AND id=:z", array(':x'=>'0',':y'=>$year,':z'=>$_GET['batch_id'])); 

		}

      	

		

                

                $filename = ucfirst($batch_name->name).Yii::t('app',' Attendance Percentage Report ').'.pdf';                

                Yii::app()->osPdf->generate("application.modules.report.views.default.percentpdf", $filename, array('batches'=>$batches,'perc'=>$per),1);

                

                

	}

	

	public function actionStudentmonthlypdf()

    {

      	$batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));		

        $filename= ucfirst($batch_name->name).Yii::t('app',' Students Monthly Attendance Report ').$_REQUEST['month'].'.pdf';

        Yii::app()->osPdf->generate("application.modules.report.views.default.studentmonthlypdf", $filename, array());

        

	}

	

	public function actionStudentindividualpdf()

        {

		$student_name = Students::model()->findByAttributes(array('id'=>$_REQUEST['student']));

		

        

        $filename= ucfirst($student_name->first_name).' '.ucfirst($student_name->last_name).Yii::t('app',' Student Attendance').'Report.pdf';

        Yii::app()->osPdf->generate("application.modules.report.views.default.studentindividualpdf", $filename, array());

	}

	public function actionReminder()

	{

		

		if(isset($_GET['search_button']))

		{

			

			$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));

						if(Yii::app()->user->year)

						{

							$year = Yii::app()->user->year;

							

						}

						else

						{

							$year = $current_academic_yr->config_value;

						} 

			if($_GET['batch_id'] == 'All')

				{	

					$criteria = new CDbCriteria;

					$criteria->condition = "is_deleted=:x AND academic_yr_id=:y";

					$criteria->params = array(':x'=>'0',':y'=>$year);

					//$total = Batches::model()->count($criteria);

					//$total = count($criteria);

					/*$pages = new CPagination($total);

					$pages->setPageSize(10);

					$pages->applyLimit($criteria);  // the trick is here!*/

					$batches = Batches::model()->findAll($criteria);

					

				}

			else{	

					$criteria = new CDbCriteria;

					$criteria->condition = "is_deleted=:x AND academic_yr_id=:y AND id=:z";

					$criteria->params = array(':x'=>'0',':y'=>$year,':z'=>$_GET['batch_id']);

					/*$total = count($criteria);

					$pages = new CPagination($total);

					$pages->setPageSize(10);

					$pages->applyLimit($criteria);  // the trick is here!*/

					$batches = Batches::model()->findAll($criteria); 

					//var_dump($_GET['batch_id']);exit;

					

			}

		   

			$this->render('reminder',array(

			'batches'=>$batches,

			'pages' => $pages,

			'item_count'=>$total,

			'page_size'=>10,

			)) ;

				exit;

				

			}

			$this->render('reminder');

	

		

	}

	public function actionCbscreport()

		{
			 if(isset($_REQUEST['cid']) and isset($_REQUEST['bid']))
                    {
                        $criteria = new CDbCriteria;	
						$criteria->join 	= "JOIN `batch_students` `bs` ON `bs`.`student_id`=`t`.`id`";	
                        $criteria->condition = '`t`.`is_deleted`=:is_deleted AND `t`.`is_active`=:is_active AND `bs`.`batch_id`=:batch_id AND bs.result_status=0';
                        $criteria->params = array(':is_deleted'=>0, ':is_active'=>1, ':batch_id'=>$_REQUEST['bid']);
                        
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
                        $criteria->order = 'first_name ASC';	
                        $students_list = Students::model()->findAll($criteria);

                        $total = Students::model()->count($criteria);
                        $pages = new CPagination($total);
                        $pages->setPageSize(Yii::app()->params['listPerPage']);
                        $pages->applyLimit($criteria);  // the trick is here!
                        $students = Students::model()->findAll($criteria);
                        
                        $this->render('cbscreport',array(
                        'students'=>$students,
                        'pages' => $pages,
                        'item_count'=>$total,
                        'page_size'=>Yii::app()->params['listPerPage'],)) ;
                        
                    }
                    else
                    {
			$this->render('cbscreport');
                    }

		}

	

	public function actionViewreport()

	{
		if(isset($_REQUEST['id']))
		{      
			$batch_id   =   $_REQUEST['bid'];
			$details        =   Students::model()->findByAttributes(array('id'=>$_REQUEST['id'],'is_deleted'=>0,'is_active'=>1));
			$batch 		=   BatchStudents::model()->findByAttributes(array('student_id'=>$_REQUEST['id'], 'batch_id'=>$_REQUEST['bid'], 'status'=>1, 'result_status'=>0));	
			if($batch!=NULL && $batch->batch_id!=NULL)
			{                    
				$cbsc_format        = ExamFormat::getCbscformat($batch_id);
				$exam_format	= ExamFormat::model()->getExamformat($batch_id);// 1=>normal 2=>cbsc	
				 if($exam_format!=1){
					$criteria = new CDbCriteria;
					if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL)
					{
						$criteria->condition='student_id LIKE :match';
						$criteria->params[':match'] = $_REQUEST['id'];
						$id=$_REQUEST['id'];	
					}
								
					$total = CbscExamScores17::model()->count($criteria);
					$pages = new CPagination($total);
					$pages->setPageSize(Yii::app()->params['listPerPage']);
					$pages->applyLimit($criteria);  // the trick is here!
					$posts = CbscExamScores17::model()->findAll($criteria);	
					$flag = 1;
					$this->render('viewreport',array('student'=>$id,
					'list'=>$posts,
					'pages' => $pages,
					'item_count'=>$total,
					'page_size'=>Yii::app()->params['listPerPage'],
					));                                
				 }

			}
		}
		//$this->render('viewreport');
	}

	public function actionCbscpdf()

	{

		$filename	= "report.pdf";

		Yii::app()->osPdf->generate("application.modules.report.views.default.cbscpdf", $filename, array(), 0);

	}

	public function actionSubwiseattentance()

	{

		if(isset($_POST['batch_id']))

		{

			$this->render('subwiseattentance',array('batch_id'=>$_POST['batch_id']));

		}

		else

		{

			$this->render('subwiseattentance');

		}

		

	}

	public function actionSubwisepdf()

    {

        $batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));

        $pdf_name = $batch->name.Yii::t('app',' Subject Wise Attendance').'Report.pdf';        

        Yii::app()->osPdf->generate("application.modules.report.views.default.subwisepdf", $pdf_name, array());

        

    }

	public function actionIndividualpdf()

    {

        $student_name = Students::model()->findByAttributes(array('id'=>$_REQUEST['student']));

        $pdf_name = ucfirst($student_name->first_name).' '.ucfirst($student_name->last_name).Yii::t('app',' Student Subject Wise Attendance').'Report.pdf';        

        Yii::app()->osPdf->generate("application.modules.report.views.default.individualpdf", $pdf_name, array());

        

    }

	public function actionTeachersubwise()

	{

		if(Configurations::model()->teacherAttendanceMode() != 1){

			$this->render('teachersubwise');

		}

		else{

			$this->redirect(array('/report'));

		}

	}

	public function actionTeacherpdf()

    {

		if(Configurations::model()->teacherAttendanceMode() != 1){

			$department = EmployeeDepartments::model()->findByAttributes(array('id'=>$_REQUEST['department_id']));

			$pdf_name = $department->name.Yii::t('app','Teacher Subject Wise Attendance').'Report.pdf';        

			Yii::app()->osPdf->generate("application.modules.report.views.default.teacherpdf", $pdf_name, array());

		}

		else{

			$this->redirect(array('/report'));

		}	

        

    }

	public function actionSemesters()

	{

		$sem_status=0;

		$semesters      = CHtml::tag('option', array('value' => ''), CHtml::encode(Yii::t('app','Select')), true);

		$batches        = CHtml::tag('option', array('value' => ''), CHtml::encode(Yii::t('app','Select')), true);

		if(isset($_REQUEST['cid']) and $_REQUEST['cid']!=NULL)

		{     

			$criteria=new CDbCriteria;

			$criteria->join= 'JOIN semester_courses `sc` ON t.id = `sc`.semester_id';

			$criteria->condition='`sc`.course_id =:course_id';

			$criteria->params=array(':course_id'=>$_REQUEST['cid']);

			$data	= Semester::model()->findAll($criteria);			

			$data	= CHtml::listData($data, 'id', 'name');
			$data_list 		= CMap::mergeArray(array(0=>Yii::t('app','Batch without semester')),$data);		
			foreach($data_list as $value=>$name){
					$semesters .= CHtml::tag('option', array('value'=>$value), CHtml::encode(html_entity_decode($name)),true);

			}
			$sem_enabled= Configurations::model()->isSemesterEnabledForCourse($_REQUEST['cid']);

			if($sem_enabled==1){

				$sem_status=1;

			}

			if($sem_status == 1){

				$criteria=new CDbCriteria;

			   // $criteria->join= 'JOIN semester_courses `sc` ON t.id = `sc`.semester_id';

				$criteria->condition='course_id =:course_id AND is_deleted=0 AND is_active=1';

				$criteria->params=array(':course_id'=>$_REQUEST['cid']);               

				$criteria->addCondition('semester_id IS NULL');                

				$data	= Batches::model()->findAll($criteria);

				$data	= CHtml::listData($data, 'id', 'name');		

				foreach($data as $value=>$name){

						$batches	.= CHtml::tag('option', array('value'=>$value), CHtml::encode(html_entity_decode($name)),true);

				}

			}else{

				$criteria=new CDbCriteria; 

				$data = CHtml::listData(Batches::model()->findAllByAttributes(array('course_id'=>$_REQUEST['cid'],'is_active'=>1,'is_deleted'=>0)),'id','name');               	

				foreach($data as $value=>$name){

						$batches	.= CHtml::tag('option', array('value'=>$value), CHtml::encode(html_entity_decode($name)),true);

				}				

			}

		}



		echo CJSON::encode(array('status'=>'success', 'semester'=>$semesters, 'batch'=>$batches,'sem_status'=>$sem_status));

		Yii::app()->end();

	}

	



}