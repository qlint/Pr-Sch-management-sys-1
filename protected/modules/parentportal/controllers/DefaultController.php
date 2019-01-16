<?php

class DefaultController extends RController
{
	public function actionIndex()
	{
		//$this->render('index');
	}
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	public function actionProfile()
	{
		$this->render('profile');
	}
	public function actionStudentprofile()
	{
		$this->render('student/studentprofile');
	}
	public function actioncourse()
	{
		$this->render('course');
	}
	public function actionMessages()
	{
		$this->render('messages');
	}
	public function actionEvents()
	{
		$this->render('events');
	}
	public function actionAttendance()
	{
		$this->render('attendance');
	}
	public function actionAttendance1()
	{
		$this->render('attendance1');
	}
	public function actionEventlist()
	{
		$this->render('eventlist');
	}
	public function actionExams()
	{
            $this->render('batch_level/batches');
		//$this->render('exams');
	}
	public function actionFees()
	{
		$this->render('fees');
	}
	public function actionReports()
	{
		$this->render('reports');
	}
		public function actionViewmessage()
	{
		
		$this->render('viewmessage');
	}
	
	public function actionAbsenceDetails()
	{
		$this->render('absencedetails',array('id'=>$_REQUEST['id'],'yid'=>$_REQUEST['yid']));
	}
	
	public function actionAttendancePdf()
    {
        $student = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$student = $student->first_name.' '.$student->last_name.' Attendance.pdf';
                       
        $filename = $student->first_name.' '.$student->last_name.' Attendance.pdf';
        Yii::app()->osPdf->generate("application.modules.parentportal.views.default.attentstud", $filename, array(),1);
 
        ////////////////////////////////////////////////////////////////////////////////////
	}
	public function actionNewmessage()
	{
		$message = new Message();
		if(isset($_POST['Message']))
		{
		$message->attributes=$_POST['Message'];
		if($message->save())
		$this->redirect(array('messages','id'=>$message->id));
		}
		$this->render('Newmessage',array(
			'message'=>$message,
		));
		
	}
	public function actionView()
	{
		
		$this->renderPartial('view',array('event_id'=>$_REQUEST['event_id']),false,true);
	}
	
	
	public function actionDocument()
	{ 
		//echo $_POST['StudentDocument']['sid'];exit;
		$model=new StudentDocument;
		$flag = 1;
		$valid_file_types = array('image/jpeg','image/png','application/pdf','application/msword','text/plain'); // Creating the array of valid file types
		$files_not_saved = '';
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['StudentDocument']))
		{
			$list = $_POST['StudentDocument'];
			$no_of_documents = count($list['doc_type']); // Counting the number of files uploaded (No of rows in the form)
			for($i=0;$i<$no_of_documents;$i++) //Iterating the documents uploaded
			{
				$model=new StudentDocument;
				$model->student_id = $_POST['StudentDocument']['student_id'];
				$model->doc_type = $_POST['StudentDocument']['title'][$i];
				$model->title = $_POST['StudentDocument']['title'][$i];
				$model->doc_type = $_POST['StudentDocument']['doc_type'][$i];
				$extension = end(explode('.',$_FILES['StudentDocument']['name']['file'][$i])); // Get extension of the file
                                
                                $model->file = DocumentUploads::model()->getFileName($_FILES['StudentDocument']['name']['file'][$i]); // Generate random string as filename
                                
				//$model->file = $this->generateRandomString(rand(6,10)).'.'.$extension; // Generate random string as filename
				$model->file_type = $_FILES['StudentDocument']['type']['file'][$i];
				$model->is_approved = 0;
				$model->uploaded_by = Yii::app()->user->Id;
				$file_size = $_FILES['StudentDocument']['size']['file'][$i];
				if($model->student_id!='' and $model->doc_type!='' and $model->file!='' and $model->file_type!='') // Checking if Document name and file is uploaded
				{
					if(in_array($model->file_type,$valid_file_types)) // Checking file type
					{
						
						if($file_size <= 5242880) // Checking file size
						{
							if(!is_dir('uploadedfiles/')) // Creating uploaded file directory
							{
								mkdir('uploadedfiles/');
							}
							if(!is_dir('uploadedfiles/student_document/')) // Creating student_document directory
							{
								mkdir('uploadedfiles/student_document/');
							}
							if(!is_dir('uploadedfiles/student_document/'.$model->student_id)) // Creating student directory for saving the files
							{
								mkdir('uploadedfiles/student_document/'.$model->student_id);
							}
							$temp_file_loc = $_FILES['StudentDocument']['tmp_name']['file'][$i];
							$destination_file = 'uploadedfiles/student_document/'.$model->student_id.'/'.$model->file;
							if(move_uploaded_file($temp_file_loc,$destination_file)) // Saving the files to the folder
							{
								if($model->save()) // Saving the model to database
								{
									$flag = 1;
                                                                        
                                                                        //save data for admin approve 
                                                                        DocumentUploads::model()->insertData(3, $model->id, $model->file, 5);
								}
								else // If model not saved
								{
									$flag = 0;
									if(file_exists($destination_file))
									{
										unlink($destination_file);
									}
									$files_not_saved = $files_not_saved.', '.$model->file;
									Yii::app()->user->setFlash('errorMessage',Yii::t('app',"File(s)").$files_not_saved.Yii::t('app',"was not saved. Please try again."));
									continue;
								}
							}
							else // If file not saved to the directory
							{
								$flag = 0;
								$files_not_saved = $files_not_saved.', '.$model->file;
								Yii::app()->user->setFlash('errorMessage',Yii::t('app',"File(s)")." ".$files_not_saved.Yii::t('app',"was not saved. Please try again."));
								continue;
							}
						}
						else // If file size is too large. Greater than 5 MB
						{
							$flag = 0;
							Yii::app()->user->setFlash('errorMessage',Yii::t('app',"File size must not exceed 5MB!"));
						}
					}
					else // If file type is not valid
					{
						$flag = 0;
						Yii::app()->user->setFlash('errorMessage',Yii::t('app',"Only files with these extensions are allowed: jpg, png, pdf, doc, txt."));
					}
				}
				elseif($model->title=='' and $model->file_type!='') // If document name is empty
				{
					$flag = 0;
					Yii::app()->user->setFlash('errorMessage',Yii::t('app',"Document Name cannot be empty!"));
					//$this->redirect(array('create','model'=>$model,'id'=>$_REQUEST['id']));
				}
				elseif($model->title!='' and $model->file_type=='') // If file is not selected
				{
					$flag = 0;
					Yii::app()->user->setFlash('errorMessage',Yii::t('app',"File is not selected!"));
					
				}

				elseif($model->student_id=='' and $model->title=='' and $model->file=='' and $model->file_type=='')
				{
					$flag=1;
				}
			}
			if($flag == 1) // If no errors, go to next step of the student registration
			{
				$this->redirect(array('studentprofile','id'=>$_POST['StudentDocument']['student_id']));
				
			}
			else // If errors are present, redirect to the same page
			{
				$this->redirect(array('studentprofile', 'id'=>$_POST['StudentDocument']['student_id']));
				
			}
		} // END isset
/*
		$this->render('create',array(
			'model'=>$model,
		));*/
	}
	public function actionDeletes()
	{
		if(Yii::app()->request->isPostRequest){
			$model = StudentDocument::model()->findByAttributes(array('id'=>$_REQUEST['id']));
                        $id= $model->id;
                        $filename= $model->file;
			$destination_file = 'uploadedfiles/student_document/'.$model->student_id.'/'.$model->file;
			if(file_exists($destination_file))
			{
				if(unlink($destination_file))
				{
					$model->delete();                                        
                                        //delete entry from document uploads
                                        DocumentUploads::model()->deleteDocument(3, $id, $filename);
                                        
					Yii::app()->user->setFlash('successMessage',Yii::t('app',"Document deleted successfully!"));	
				}
			}
			$this->redirect(array('studentprofile'));
		}
		else{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
	}
	
	/**
	* Download Files
	*/
	public function actionDownload()
	{
		$model = StudentDocument::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$file_path = 'uploadedfiles/student_document/'.$model->student_id.'/'.$model->file;
		$file_content = file_get_contents($file_path);
		$model->title = str_replace(' ','',$model->title);
		header("Content-Type: ".$model->file_type);
		header("Content-disposition: attachment; filename=".$model->file);
		header("Pragma: no-cache");
		echo $file_content;
		exit;
	}
	
	private function generateRandomString($length = 5) 
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) 
		{
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	
	public function actionDocumentupdate()
	{
		
		$model= StudentDocument::model()->findByAttributes(array('id'=>$_REQUEST['document_id'])); //Here $_REQUEST['id'] is student ID and $_REQUEST['document_id'] is document ID
		$old_model = $model->attributes;
		//var_dump($old_model);exit;
		$flag = 1; // If 1, no errors. If 0, some error is present.
		$valid_file_types = array('image/jpeg','image/png','application/pdf','application/msword','text/plain'); // Creating the array of valid file types
		
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['StudentDocument']))
		{
			$list = $_POST['StudentDocument'];
			$model->student_id = $list['student_id'];
			$model->title = $list['title'];
			$model->doc_type	= $list['doc_type']; 
			
			if(($model->title != $old_model['file']) or ($_FILES['StudentDocument']['name']['file']!=NULL))
			{
				//echo 'dfsd';exit;
				$model->is_approved = 0;
			}
			if($model->doc_type!=NULL and $model->student_id!=NULL)
			{
				if($_FILES['StudentDocument']['name']['file']!=NULL)
				{
					
					$extension = end(explode('.',$_FILES['StudentDocument']['name']['file'])); // Get extension of the file
					$model->file = $this->generateRandomString(rand(6,10)).'.'.$extension; // Generate random string as filename
					$model->file_type = $_FILES['StudentDocument']['type']['file'];
					$file_size = $_FILES['StudentDocument']['size']['file'];
					if(in_array($model->file_type,$valid_file_types)) // Checking file type
					{
						if($file_size <= 5242880) // Checking file size
						{
							if(!is_dir('uploadedfiles/')) // Creating uploaded file directory
							{
								mkdir('uploadedfiles/');
							}
							if(!is_dir('uploadedfiles/student_document/')) // Creating student_document directory
							{
								mkdir('uploadedfiles/student_document/');
							}
							if(!is_dir('uploadedfiles/student_document/'.$model->student_id)) // Creating student directory for saving the files
							{
								mkdir('uploadedfiles/student_document/'.$model->student_id);
							}
							$temp_file_loc = $_FILES['StudentDocument']['tmp_name']['file'];
							$destination_file = 'uploadedfiles/student_document/'.$model->student_id.'/'.$model->file;
							
							if(move_uploaded_file($temp_file_loc,$destination_file)) // Saving the files to the folder
							{
								$flag = 1;
								
							}
							else // If file not saved to the directory
							{
								$flag = 0;								
								Yii::app()->user->setFlash('errorMessage',Yii::t('app',"File")." ".$model->file." ".Yii::t('app',"was not saved. Please try again."));
							}
						}
						else // If file size is too large. Greater than 5 MB
						{
							$flag = 0;
							Yii::app()->user->setFlash('errorMessage',Yii::t('app',"File size must not exceed 5MB!"));
						}
					}
					else // If file type is not valid
					{
						$flag = 0;
						Yii::app()->user->setFlash('errorMessage',Yii::t('app',"Only files with these extensions are allowed: jpg, png, pdf, doc, txt."));
						
					}
				}
				else // No files selected
				{
					if($old_model['file']!=NULL and $list['new_file_field']==1)
					{
						$flag = 0;
						Yii::app()->user->setFlash('errorMessage',Yii::t('app',"No file selected!"));
					}
					
				}
			}
			else // No title entered
			{
				$flag = 0;
				Yii::app()->user->setFlash('errorMessage',Yii::t('app',"Document Name cannot be empty!"));
			}
			
			
			if($flag == 1) // Valid data
			{ 
				if($model->save())
				{
					if($_FILES['StudentDocument']['name']['file']!=NULL)
					{
						$old_destination_file = 'uploadedfiles/student_document/'.$model->student_id.'/'.$old_model['file'];	
						if(file_exists($old_destination_file))
						{
							unlink($old_destination_file);
						}
					}
					$this->redirect(array('studentprofile', 'id'=>$model->student_id));
				}
				else
				{
					
					Yii::app()->user->setFlash('errorMessage',Yii::t('app',"Cannot update the document now. Try again later."));
					$this->redirect(array('documentupdate','id'=>$model->student_id,'document_id'=>$_REQUEST['document_id']));
				}
					
			}
			else
			{
				$this->redirect(array('documentupdate','id'=>$model->student_id,'document_id'=>$_REQUEST['document_id']));
				/*$this->render('update',array(
					'model'=>$model,'student_id'=>$_REQUEST['id']
				));*/
				
			}
		}

		$this->render('student/documentupdate',array(
			'model'=>$model,'student_id'=>$_REQUEST['id']
		));
	
	}
	
//Check status of newly added student
	public function actionCheckStatus()
	{				
		$this->render('checkStatus'
		);
	}
	public function actionLognotice()
	{
		$this->render('lognotice');
	}
	public function actionDashboard()
	{
		$this->render('dashboard');
	}
        
        //for update parent portal
        public function actionEdit($id)
        {
            $model=$this->loadModel($id);
			$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
            if(isset($_POST['Guardians']))
            {
                $model->attributes= $_POST['Guardians'];
                $model->dob = date('Y-m-d H:i:s', strtotime($_POST['Guardians']['dob']));
				
				$fields   = FormFields::model()->getDynamicFields(2, 1, "forParentPortal");				
				foreach ($fields as $key => $field) {			
					if($field->form_field_type==6){  // date value
						$field_name = $field->varname;
						if($model->$field_name!=NULL and $model->$field_name!="0000-00-00" and $settings!=NULL){
							$model->$field_name = date('Y-m-d',strtotime($model->$field_name));												
						}
					}
				}
				
				$fields   = FormFields::model()->getDynamicFields(2, 2, "forParentPortal");
				foreach ($fields as $key => $field) {			
					if($field->form_field_type==6){  // date value
						$field_name = $field->varname;
						if($model->$field_name!=NULL and $model->$field_name!="0000-00-00" and $settings!=NULL){
							$model->$field_name = date('Y-m-d',strtotime($model->$field_name));
						}
					}
				}
				
                if($model->validate())
                {					
                    if($model->save())
                    {
						if($model->id != 0){
							//Update User table
							$user	= User::model()->findByPk($model->uid);
							if($user){
								$user->email			= $model->email;
								$user->mobile_number	= $model->mobile_phone;
								$user->save();
							}
						}
					
                        $this->redirect(array('profile'));
                    }					
                }                   
            }
			
			$fields   = FormFields::model()->getDynamicFields(2, 1, "forParentPortal");
			foreach ($fields as $key => $field) {			
				if($field->form_field_type==6){  // date value
					$field_name = $field->varname;
					if($model->$field_name!=NULL and $model->$field_name!="0000-00-00" and $settings!=NULL){
						$model->$field_name = date($settings->displaydate,strtotime($model->$field_name));
					}
					else{
						$model->$field_name=NULL;
					}
				}
			}
			
			$fields   = FormFields::model()->getDynamicFields(2, 2, "forParentPortal");
			foreach ($fields as $key => $field) {			
				if($field->form_field_type==6){  // date value
					$field_name = $field->varname;
					if($model->$field_name!=NULL and $model->$field_name!="0000-00-00" and $settings!=NULL){
						$model->$field_name = date($settings->displaydate,strtotime($model->$field_name));
					}
					else{
						$model->$field_name=NULL;
					}
				}
			}
            $this->render('edit',array(
			'model'=>$model,
		));
        }
        
        public function loadModel($id)
	{
		$model=Guardians::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
		return $model;
	}
        
	public function actionCbsc()
	{
		$this->render("batch_level/cbsc_gradebook");
	}
	
	public function actionCbscPdf()
	{
		$filename	= "report.pdf";
		Yii::app()->osPdf->generate("application.modules.parentportal.views.default.batch_level.cbsc_gradebook_pdf", $filename, array(), NULL);
	}
	
	public function actionExam()
	{
		$this->render("batch_level/exam");
	}
	
	public function actionExamList()
	{
		$this->render("batch_level/exam_list");
	}
	
	public function actionExamTimetable()
	{
		$this->render("batch_level/timetable");
	}
	
	function actionCurrentEvents()
	{
		$roles 		= Rights::getAssignedRoles(Yii::app()->user->Id);
		$rolename	= key($roles);
		
		$criteria 							= new CDbCriteria;
		$criteria->order 					= 'start ASC';	
		$criteria->condition 				= '(placeholder= :default OR placeholder=:placeholder)';
		$criteria->params[':placeholder'] 	= $rolename;
		$criteria->params[':default'] 		= '0';
		if(isset($_REQUEST['type']) and $_REQUEST['type'] != NULL and $_REQUEST['type'] != 0){
			$criteria->condition		= $criteria->condition.' AND type=:type';
			$criteria->params[':type']	= $_REQUEST['type'];
		}
		$criteria->addCondition('DATE_FORMAT(FROM_UNIXTIME(start), "%Y-%m") =:eventdate');
        $criteria->params[':eventdate'] = $_REQUEST['year'].'-'.$_REQUEST['month'];
		$events = Events::model()->findAll($criteria);
		$this->renderPartial('displayEvents',array('events'=>$events));
		
	}	
        
        public function actionSemesters()
        {
            $this->render("batch_level/semester");
        }
        
        public function actionSemesterPdf()
        {
            $filename	= "Semester Result.pdf";
            Yii::app()->osPdf->generate("application.modules.parentportal.views.default.batch_level.semester_result_pdf", $filename, array(), NULL);
        }

    public function actionSubwiseattendance(){
		if(Configurations::model()->studentAttendanceMode() != 1){
			$this->render('subwiseattendance');
		}
		else{
			$this->redirect(array('attendance'));
			
		}
	}
	
	public function actionViewsubwise(){
		Yii::app()->clientScript->scriptMap['jquery.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
		$this->renderPartial('viewsubwise', array(), false, true);
	}
	
	public function actionSubwisepdf(){
		$student 	= Students::model()->findByPk($_REQUEST['id']);
		$filename 	= strtolower($student->first_name.'-'.$student->last_name.'-attendance.pdf');
		Yii::app()->osPdf->generate("application.modules.parentportal.views.default.subwisepdf", $filename, array(),1);
	}
	
	//Downloads
	public function actionDownloads()
	{
		$files	='';
		if(isset($_REQUEST['id']) and $_REQUEST['id'] != NULL){	
			Yii::app()->getModule('downloads');		
			$batch_id_arr			= array();
			$course_id_arr			= array();
			$student 				= Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
			$batch_students			= BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id, 'status'=>1, 'result_status'=>0));		
			if($batch_students){
				foreach($batch_students as $value){
					$batch 				= Batches::model()->findByAttributes(array('id'=>$value->batch_id, 'is_active'=>1, 'is_deleted'=>0));
					if($batch){
						$batch_id_arr[]		= $batch->id;
						$course_id_arr[]	= $batch->course_id;
					}
				}
			}
						
			$criteria				= new CDbCriteria();
			$criteria->join			= 'LEFT JOIN file_uploads_students t1 ON t.id = t1.table_id'; 
			$criteria->condition	= 't.placeholder=:placeholder AND t1.student_id=:student_id AND t.is_special_student=:is_special_student';
			$criteria->params		= array(':placeholder'=>'student', ':student_id'=>$student->id, ':is_special_student'=>1);
			$special_files			= FileUploads::model()->findAll($criteria);
			$special_file_arr	= array();
			if($special_files){
				foreach($special_files as $special_file){
					if(!in_array($special_file->id, $special_file_arr)){
						$special_file_arr[]	= $special_file->id;
					}
				}
			}	
			$val1	= '';
			$val2	= '';
			if(count($course_id_arr) > 0){
				$val1	= ' (`course` IN ('.implode(',',$course_id_arr).')) OR ';
			}
			if(count($batch_id_arr) > 0){
				$val2	= ' (`batch` IN ('.implode(',',$batch_id_arr).')) OR ';
			}
			
			$criteria					= new CDbCriteria;		
			$criteria->condition		= '`file`<>:null AND (`placeholder`=:null OR (`placeholder`=:placeholder AND `is_special_student`=0)) AND ((`course` IS NULL) OR'.$val1.'(`course`=0)) AND ((`batch` IS NULL) OR'.$val2.'(`batch`=0))';	
			$criteria->params			= array(':null'=>'', ':placeholder'=>'student');
			$criteria->addInCondition('id', $special_file_arr, 'OR');
			$criteria->order			= '`created_at` DESC';		
			$files						=	FileUploads::model()->findAll($criteria);
			//Download file
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
					Yii::app()->user->setFlash('success',Yii::t('app','Can\'t download'));
				}
				
			}
		}
		$this->render('downloads', array('files' => $files));
	}
	public function actionCbsc17()
	{
		$this->render("batch_level/exam_result17",array('bid'=>$bid));
	}
	public function actionCbsc17Pdf()
	{
		$student_name   = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$exam_name      = CbscExamGroup17::model()->findByAttributes(array('id'=>$_REQUEST['exam_group_id']));
		
		$filename= ucfirst($student_name->first_name).' '.ucfirst($student_name->last_name).' '.ucfirst($exam_name->name).Yii::t('app',' Assessment').' Report.pdf';
		
		if($exam_name->class==1) //class 1-2
		{
			Yii::app()->osPdf->generate("application.modules.parentportal.views.default.cbsc17.studentexampdf1", $filename, array());
		}
		else if($exam_name->class==2){ //class 3-8
			Yii::app()->osPdf->generate("application.modules.parentportal.views.default.cbsc17.studentexampdf2", $filename, array());
		}
		else if($exam_name->class==3){ //class 9-10
			Yii::app()->osPdf->generate("application.modules.parentportal.views.default.cbsc17.studentexampdf3", $filename, array());
		}
		else if($exam_name->class==4){ //class 11-12
			Yii::app()->osPdf->generate("application.modules.parentportal.views.default.cbsc17.studentexampdf4", $filename, array());
		}
	}
}