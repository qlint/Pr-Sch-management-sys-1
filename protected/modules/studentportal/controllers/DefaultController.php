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
	public function actionAbsenceDetails()
	{
		$this->render('absencedetails',array('id'=>$_REQUEST['id'],'yid'=>$_REQUEST['yid']));
	}
	public function actionProfile()
	{
		$this->render('profile');
	}
	public function actionRequisition()
	{
		$model=new PurchaseMaterialRequistion;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PurchaseMaterialRequistion']))
		{ 
			$model->attributes=$_POST['PurchaseMaterialRequistion'];
			$model->department_id='0';
			$model->employee_id=Yii::app()->User->Id;
			if($model->save())
				$this->redirect(array('RequisitionView'));
		}

		$this->render('requisition',array(
			'model'=>$model,
		));
		
	}
	public function actionRequisitionView()
	{
		$criteria 				= 	new CDbCriteria;
		$criteria->condition	=   'employee_id=:employee_id';
		$criteria->params 		= 	array(':employee_id'=>Yii::app()->User->Id);		
		$criteria->order 		= 	'id DESC';
		
		$total = PurchaseMaterialRequistion::model()->count($criteria);
		$pages = new CPagination($total);
        $pages->setPageSize(Yii::app()->params['listPerPage']);
        $pages->applyLimit($criteria); 
		
		$materials = PurchaseMaterialRequistion::model()->findAll($criteria);
		$this->render('requisitionview', array('materials'=>$materials, 'pages' => $pages, 'item_count'=>$total, 'page_size'=>Yii::app()->params['listPerPage'		         ]));
	}
	public function actionRequisitionDelete($id)
	{ 
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$model = PurchaseMaterialRequistion::model()->findByPk($id);
			if($model){				
				if($model->delete())
				Yii::app()->user->setFlash('successMessage', Yii::t('app','Material Request Deleted Successfully'));
			}	
				//$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
				$this->redirect(array('requisitionview'));	
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
	public function actionRequisitionUpdate($id)
	{
		$model= PurchaseMaterialRequistion::model()->findByPk($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PurchaseMaterialRequistion']))
		{
			$model->attributes=$_POST['PurchaseMaterialRequistion'];
			$model->status=0;
			$model->status=0;
			$model->is_issued=0;
			if($model->save())
			{
			Yii::app()->user->setFlash('successMessage', Yii::t('app','Material Request Updated Successfully'));
				$this->redirect(array('requisitionview'));
			}
		}

		$this->render('requisitionupdate',array(
			'model'=>$model,
		));
	}
	public function actionEditprofile()
	{
		$model=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
		if(isset($_POST['Students']))
		{
			$old_model = $model->attributes; // For activity feed
			$model->attributes=$_POST['Students'];
			if($model->date_of_birth)
			{
				$model->date_of_birth=date('Y-m-d',strtotime($model->date_of_birth));
			}
			
			//dynamic fields
			$fields   = FormFields::model()->getDynamicFields(1, 1, "forStudentPortal");
			foreach ($fields as $key => $field) {			
				if($field->form_field_type==6){  // date value
					$field_name = $field->varname;
					if($model->$field_name!=NULL and $model->$field_name!="0000-00-00" and $settings!=NULL){
						$model->$field_name = date('Y-m-d',strtotime($model->$field_name));
					}
				}
			}
			
			$fields   = FormFields::model()->getDynamicFields(1, 2, "forStudentPortal");
			foreach ($fields as $key => $field) {			
				if($field->form_field_type==6){  // date value
					$field_name = $field->varname;
					if($model->$field_name!=NULL and $model->$field_name!="0000-00-00" and $settings!=NULL){
						$model->$field_name = date('Y-m-d',strtotime($model->$field_name));
					}
				}
			}
			
			if($model->save())
			{
				
				$profile = Profile::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
				$profile->firstname = $model->first_name;
				$profile->lastname = $model->last_name;
				$profile->save();
				
				$user	= User::model()->findByPk($model->uid);
					if($user){
						$user->mobile_number	= $model->phone1;
						$user->save();
					}
				
				// Saving to activity feed
				$results = array_diff_assoc($_POST['Students'],$old_model); // To get the fields that are modified.
				
				foreach($results as $key => $value)
				{
					if($key != 'updated_at')
					{
						
						if($key == 'gender')
						{
							if($value == 'F')
							{
								$value = Yii::t('app','Female');
							}else
							{
								$value = Yii::t('app','Male');
							}
							if($old_model[$key] == 'F')
							{
								$old_model[$key] = Yii::t('app','Female');
							}
							else
							{
								$old_model[$key] = Yii::t('app','Male');
							}
						}
						elseif($key == 'nationality_id' or $key == 'country_id')
						{
							$value = Countries::model()->findByAttributes(array('id'=>$value));
							$value = $value->name;
							
							$old_model_value = Countries::model()->findByAttributes(array('id'=>$old_model[$key]));
							$old_model[$key] = $old_model_value->name;
						}
						//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
						ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'4',$model->id,ucfirst($model->first_name).' '.ucfirst($model->middle_name).' '.ucfirst($model->last_name),$model->getAttributeLabel($key),$old_model[$key],$value); 
						
						
					}
				}	
				//END saving to activity feed
				
				$this->redirect(array('profile'));
				
			}
			
		}
		
		if($model->date_of_birth!=NULL and $settings!=NULL){
			$date2=date($settings->displaydate,strtotime($model->date_of_birth));
			$model->date_of_birth=$date2;
		}
		$fields   = FormFields::model()->getDynamicFields(1, 1, "forStudentPortal");
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
		
		$fields   = FormFields::model()->getDynamicFields(1, 2, "forStudentPortal");
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
		
		$this->render('editprofile',array('model'=>$model));
	}
	
	public function actionAttendancePdf()
        {
        $student = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$student = $student->first_name.' '.$student->last_name.' Attendance.pdf';                
        
        $filename= $student->first_name.' '.$student->last_name.' Attendance.pdf';
        Yii::app()->osPdf->generate("application.modules.studentportal.views.default.attentstud", $filename, array(),1);
 
        ////////////////////////////////////////////////////////////////////////////////////
	}
	
	public function actionPdf()
        {
		$batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$batch_name = $batch_name->name.' Class Timetable.pdf';                
        $filename= $batch_name->name.' Class Timetable.pdf';
		if(isset($_REQUEST['type']) and $_REQUEST['type'] == 1){ //In case of flexible timetable
		 Yii::app()->osPdf->generate("application.modules.studentportal.views.default.exportpdf_flexible", $filename, array(), 0, "", "A4", 5, 5, 5, 5);			
		}
		else{
		 Yii::app()->osPdf->generate("application.modules.studentportal.views.default.exportpdf", $filename, array(),1);
		}      
                
        ////////////////////////////////////////////////////////////////////////////////////
	}
	public function actionCourse()
	{
		$this->render('course');
	}
	
	public function actionMessages()
	{
		$this->render('messages');
	}
	
	public function actionAttendance()
	{	
		if(Configurations::model()->studentAttendanceMode() != 2){
				$this->render('attendance');
		}
		else{
				$this->redirect(array('subwiseattendance'));
		}
	}
	public function actionTimetable()
	{
		$this->render('timetable');
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
	public function actionView()
	{
		
		$this->renderPartial('view',array('event_id'=>$_REQUEST['event_id']),false,true);
	}
	
	
	/*
	* For adding documents
	*/
	
	
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
				$model->student_id = $_POST['StudentDocument']['student_id'][$i];
				$model->title = $_POST['StudentDocument']['title'][$i];
				$model->doc_type = $_POST['StudentDocument']['doc_type'][$i];
				$extension = end(explode('.',$_FILES['StudentDocument']['name']['file'][$i])); // Get extension of the file
				$model->file= DocumentUploads::model()->getFileName($_FILES['StudentDocument']['name']['file'][$i]);                               
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
									Yii::app()->user->setFlash('errorMessage',Yii::t('app',"File(s) ").$files_not_saved.Yii::t('app'," was not saved. Please try again."));
									continue;
								}
							}
							else // If file not saved to the directory
							{
								$flag = 0;
								$files_not_saved = $files_not_saved.', '.$model->file;
								Yii::app()->user->setFlash('errorMessage',Yii::t('app',"File(s) ").$files_not_saved.Yii::t('app'," was not saved. Please try again."));
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
						Yii::app()->user->setFlash('errorMessage',Yii::t('app',"Only files with these extensions are allowed:")." jpg, png, pdf, doc, txt.");
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
				$this->redirect(array('profile'));
				
			}
			else // If errors are present, redirect to the same page
			{
				$this->redirect(array('profile'));
				
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
			$this->redirect(array('profile'));
		}
		else
		{
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
					$model->file_type 	= $_FILES['StudentDocument']['type']['file'];
					
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
								Yii::app()->user->setFlash('errorMessage',Yii::t('app',"File ").$model->file.Yii::t('app'," was not saved. Please try again."));
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
						Yii::app()->user->setFlash('errorMessage',Yii::t('app',"Only files with these extensions are allowed:")." jpg, png, pdf, doc, txt.");
						
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
					$this->redirect(array('profile'));
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

		$this->render('documents/documentupdate',array(
			'model'=>$model,'student_id'=>$_REQUEST['id']
		));
	
	}
	
	public function actionLognotice()
	{
		$this->render('lognotice');
	}
	public function actionAchievements()
	{
		$this->render('achievements');
	}
	public function actionAchievementDownload()
	{
		$model=Achievements::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$file_path = 'uploadedfiles/achievement_document/'.$model->user_id.'/'.$model->file;
		$file_content = file_get_contents($file_path);
		$model->doc_title = str_replace(' ','',$model->doc_title);
		header("Content-Type: ".$model->file_type);
		header("Content-disposition: attachment; filename=".$model->file);
		header("Pragma: no-cache");
		echo $file_content;
		exit;
	}
	
	public function actionDashboard()
	{
		$this->render('dashboard');
	}	
	
//Student Profile Picture Upload
	public function actionStudentPicUpload($id)
	{		
		if(isset($id) and $id!=NULL){
			$model = Students::model()->findByPk($id);			
			if($model){				
				$file_name = DocumentUploads::model()->getFileName($_FILES["file"]["name"]);
									
				if($_FILES["file"]["name"]!=NULL){
					if(!is_dir('uploadedfiles/')){
						mkdir('uploadedfiles/');
					}
					if(!is_dir('uploadedfiles/student_profile_image/')){
						mkdir('uploadedfiles/student_profile_image/');
					}
					if(!is_dir('uploadedfiles/student_profile_image/'.$model->id)){
						mkdir('uploadedfiles/student_profile_image/'.$model->id);
					}
					//compress the image
					$info = getimagesize($_FILES['file']['tmp_name']); 
					if($info['mime'] == 'image/jpeg'){
						$image = imagecreatefromjpeg($_FILES['file']['tmp_name']);
					}elseif($info['mime'] == 'image/gif'){
						$image = imagecreatefromgif($_FILES['file']['tmp_name']);
					}elseif($info['mime'] == 'image/png'){
						$image = imagecreatefrompng($_FILES['file']['tmp_name']);
					}
					
					$temp_file_name = $_FILES['file']['tmp_name'];					
					$destination_file = 'uploadedfiles/student_profile_image/'.$model->id.'/'.$file_name;
					imagejpeg($image, $destination_file, 30); //compress the size
					
					//Insert Data in document_uploads table					
					DocumentUploads::model()->insertData(1, $model->id, $file_name, 6, NULL, NULL, NULL, 0);  											
				}				
			}			
		}
		return;
	}
        
	public function actionCbsc()
	{
		$this->render("batch_level/gradebook");
	}
	public function actionCbsc17()
	{
		$this->render("batch_level/exam_result17",array('bid'=>$bid));
	}
	
	public function actionCbscPdf()
	{
		$filename	= "report.pdf";
		Yii::app()->osPdf->generate("application.modules.studentportal.views.default.batch_level.cbsc_gradebook_pdf", $filename, array(), NULL);
	}
	public function actionCbsc17Pdf()
	{
		$student_name   = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$exam_name      = CbscExamGroup17::model()->findByAttributes(array('id'=>$_REQUEST['exam_group_id']));
		
		$filename= ucfirst($student_name->first_name).' '.ucfirst($student_name->last_name).' '.ucfirst($exam_name->name).Yii::t('app',' Assessment').' Report.pdf';
		
		if($exam_name->class==1) //class 1-2
		{
			Yii::app()->osPdf->generate("application.modules.studentportal.views.default.cbsc17.studentexampdf1", $filename, array());
		}
		else if($exam_name->class==2){ //class 3-8
			Yii::app()->osPdf->generate("application.modules.studentportal.views.default.cbsc17.studentexampdf2", $filename, array());
		}
		else if($exam_name->class==3){ //class 9-10
			Yii::app()->osPdf->generate("application.modules.studentportal.views.default.cbsc17.studentexampdf3", $filename, array());
		}
		else if($exam_name->class==4){ //class 11-12
			Yii::app()->osPdf->generate("application.modules.studentportal.views.default.cbsc17.studentexampdf4", $filename, array());
		}
	}
	
	public function actionExam()
	{
		$this->render("batch_level/exam");
	}
	
	public function actionExamList()
	{
		$this->render("batch_level/exam_list");
	}
	public function actionSemExamList()
	{
		if($_REQUEST['bid']!="" && ExamFormat::model()->getExamformat($_REQUEST['bid'])== 2){ // cbsc
			$this->render("batch_level/semexam_list17");
		}
		else{
			$this->render("batch_level/semexam_list");
		}
	}
	public function actionSemResult()
	{
		$this->render("batch_level/sem_results");
	}
	public function actionExamTimetable()
	{
		$this->render("exam_timetable/index");
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
	
	public function actionSubwiseattendance()
	{
		if(Configurations::model()->studentAttendanceMode() != 1){
			$this->render('subwiseattendance');
		}
		else{
			$this->redirect(array('attendance'));
			
		}
	}
	public function actionViewsubwise()
	{
		Yii::app()->clientScript->scriptMap['jquery.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
		$this->renderPartial('viewsubwise',array('id'=>$_REQUEST['id']),false,true);		
	}
	public function actionSubwisepdf()
	{
		$student = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
        $student = strtolower($student->first_name).'-subjectwise-attentance.pdf';
        Yii::app()->osPdf->generate("application.modules.studentportal.views.default.subwisepdf", $student, array(),1);
	}
	public function actionSelectSemBatch()
	{
		$semid		=	$_GET['semid'];
		if($semid!=NULL){
			$student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
			$sid		=	$student->id;
			$criteria = new CDbCriteria;
			$criteria->select = 't.id, t.name';
			$criteria->join = ' LEFT JOIN `batch_students` AS `b` ON t.id = b.batch_id';
			$criteria->condition = 't.semester_id = :semester_id AND b.student_id=:student_id';
			$criteria->params = array(":semester_id" => $semid,":student_id"=>$sid);
			$batches   		=    Batches::model()->findAll($criteria);
			$batchvalue		=    "<option value=''>".Yii::t('app','Select Batch')."</option>"; 
			$batches		= CHtml::listData($batches,'id','name');
			foreach($batches as $value=>$data)
			{
				$batchvalue .= CHtml::tag('option', array('value'=>$value),CHtml::encode(html_entity_decode($data)),true);
				//echo CHtml::tag('option',array('value'=>$batch->id),CHtml::encode($batch->name),true);
			}
			echo json_encode(array('batchvalue'=>$batchvalue));
		}else{
			$batchvalue		=    "<option value=''>".Yii::t('app','Select Batch')."</option>"; 
			echo json_encode(array('batchvalue'=>$batchvalue,'status'=>1));
		}
			
	}
	public function actionSemResultpdf()
    {
      	$batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
	        
        $filename= ucfirst($batch_name->name).Yii::t('app',' Students Enrollment').'Report.pdf';
        Yii::app()->osPdf->generate("application.modules.studentportal.views.default.batch_level.SemResultpdf", $filename, array(), 1);
	}
	public function actionSemResultpdf17()
    {
      	$batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));
	        
        $filename= ucfirst($batch_name->name).Yii::t('app',' Students Enrollment').'Report.pdf';
        Yii::app()->osPdf->generate("application.modules.studentportal.views.default.batch_level.semester_pdf17", $filename, array(), 1);
	}
	
       
}