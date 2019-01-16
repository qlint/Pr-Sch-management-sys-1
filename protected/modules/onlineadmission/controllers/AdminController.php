<?php
class AdminController extends RController
{		
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	
//showing the details of pending students	
	public function actionApproval()
	{
		$model	= new Students;		
		if(ModuleAccess::model()->check('Students')){
			$this->layout = '//layouts/column2';
					
			$criteria = new CDbCriteria;
			$criteria->compare('is_deleted',0);			
			$criteria->condition	= 'status = :status and is_completed = :is_completed and academic_yr = :academic_yr';			
			$criteria->params 		= array(':status'=>0,':is_completed'=>3,':academic_yr'=>Yii::app()->user->year);
		//Filter Section Conditions
			if(isset($_REQUEST['val'])){
				$criteria->condition		= $criteria->condition.' and '.'(first_name LIKE :match or last_name LIKE :match or middle_name LIKE :match)';				
				$criteria->params[':match'] = $_REQUEST['val'].'%';
			}
				
			if(isset($_REQUEST['name']) and $_REQUEST['name']!=NULL){
				if((substr_count( $_REQUEST['name'],' '))==0){ 	
					$criteria->condition		= $criteria->condition.' and '.'(first_name LIKE :name or last_name LIKE :name or middle_name LIKE :name)';
					$criteria->params[':name'] 	= $_REQUEST['name'].'%';
				}
				else if((substr_count( $_REQUEST['name'],' '))>=1){
					$name						= explode(" ",$_REQUEST['name']);
					$criteria->condition		= $criteria->condition.' and '.'(first_name LIKE :name or last_name LIKE :name or middle_name LIKE :name)';
					$criteria->params[':name'] 	= $name[0].'%';
					$criteria->condition		= $criteria->condition.' and '.'(first_name LIKE :name1 or last_name LIKE :name1 or middle_name LIKE :name1)';
					$criteria->params[':name1'] = $name[1].'%';
				
				}
			}
			if(isset($_REQUEST['registrationnumber']) and $_REQUEST['registrationnumber']!=NULL){
				$criteria->condition						= $criteria->condition.' and '.'registration_id LIKE :registrationnumber';
				$criteria->params[':registrationnumber'] 	= $_REQUEST['registrationnumber'].'%';
			}	
			if(isset($_REQUEST['Students']['batch_id']) and $_REQUEST['Students']['batch_id']!=NULL){
				$model->batch_id 				= $_REQUEST['Students']['batch_id'];
				$criteria->condition			= $criteria->condition.' and '.'batch_id = :batch_id';
				$criteria->params[':batch_id'] 	= $_REQUEST['Students']['batch_id'];
			}
					
			//Pagination		
			$total = Students::model()->count($criteria); // Count students
			$pages = new CPagination($total);
			$pages->setPageSize(10);
			$pages->applyLimit($criteria);
				
			$students = Students::model()->findAll($criteria); // Get students
				
			if (Yii::app()->request->isAjaxRequest)
				Yii::app()->getClientScript()->scriptMap=array('jquery.js'=>false, 'jquery.ui.js'=>false);
				
			$this->render('approval',array(
				'students'=>$students,
				'item_count'=>$total,
				'pages'=>$pages,
				'model'=>$model,						
			));	
		}
		else{
			throw new CHttpException(404,Yii::t('app','You are not authorized to view this page.'));
		}
	}
//showing the detilas of pending,approved,disapproved,waitinglist students
	public function actionOnlineApplicants()
	{
		$model	= new Students;
		
		if(ModuleAccess::model()->check('Students')){
			$this->layout = '//layouts/column2';
			
			$criteria 				= new CDbCriteria;
			$criteria->condition	= 'is_deleted LIKE :is_deleted and is_completed = :is_completed and academic_yr =:academic_yr and is_online=:is_online';				
			$criteria->params 		= array(':is_deleted'=>0,':is_completed'=>3,':academic_yr'=>Yii::app()->user->year,'is_online'=>1);
			//Filter Section Conditions
			if(isset($_REQUEST['val'])){
				$criteria->condition		= $criteria->condition.' and '.'(first_name LIKE :match or last_name LIKE :match or middle_name LIKE :match)';			
				$criteria->params[':match'] = $_REQUEST['val'].'%';
			}
				
			if(isset($_REQUEST['name']) and $_REQUEST['name']!=NULL){
				if((substr_count( $_REQUEST['name'],' '))==0){ 	
					$criteria->condition		= $criteria->condition.' and '.'(first_name LIKE :name or last_name LIKE :name or middle_name LIKE :name)';
					$criteria->params[':name'] 	= $_REQUEST['name'].'%';
				}
				else if((substr_count( $_REQUEST['name'],' '))>=1){
					$name						= explode(" ",$_REQUEST['name']);
					$criteria->condition		= $criteria->condition.' and '.'(first_name LIKE :name or last_name LIKE :name or middle_name LIKE :name)';
					$criteria->params[':name'] 	= $name[0].'%';
					$criteria->condition		= $criteria->condition.' and '.'(first_name LIKE :name1 or last_name LIKE :name1 or middle_name LIKE :name1)';
					$criteria->params[':name1'] = $name[1].'%';				
				}
			}
			
			if(isset($_REQUEST['registrationnumber']) and $_REQUEST['registrationnumber']!=NULL){
				$criteria->condition						= $criteria->condition.' and '.'registration_id LIKE :registrationnumber';
				$criteria->params[':registrationnumber'] 	= $_REQUEST['registrationnumber'].'%';
			}	
			
			if(isset($_REQUEST['Students']['status']) and $_REQUEST['Students']['status']!=NULL){	
				if($_REQUEST['Students']['status']=='pending'){
					$status = 0;
				}
				else{
					$status = $_REQUEST['Students']['status'];
				}
				$model->status 					= $status;
				$criteria->condition			= $criteria->condition.' and '.'status = :status';
				$criteria->params[':status'] 	= $status;
			}
				
			if(isset($_REQUEST['Students']['batch_id']) and $_REQUEST['Students']['batch_id']!=NULL){
				$model->batch_id 				= $_REQUEST['Students']['batch_id'];
				$criteria->condition			= $criteria->condition.' and '.'batch_id = :batch_id';
				$criteria->params[':batch_id'] 	= $_REQUEST['Students']['batch_id'];
			}						
							
			//Pagination											
			$total = Students::model()->count($criteria); // Count students
			$pages = new CPagination($total);
			$pages->setPageSize(10);
			$pages->applyLimit($criteria);
				
			$students = Students::model()->findAll($criteria); // Get students
				
			if (Yii::app()->request->isAjaxRequest)
				Yii::app()->getClientScript()->scriptMap=array('jquery.js'=>false, 'jquery.ui.js'=>false);
				
			$this->render('onlineapplicants',array(
				'students'=>$students,
				'item_count'=>$total,
				'pages'=>$pages,
				'criteria'=>$criteria,'model'=>$model,
			));		
		}
		else{
			throw new CHttpException(404,Yii::t('app','You are not authorized to view this page.'));
		}
	}
	
	
//view the profile details of student & parent 	
	public function actionView()
	{		
		$roles = Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role
		foreach($roles as $role){
			if(sizeof($roles)==1 and $role->name == 'Admin'){
				$this->layout 	= '//layouts/column2';
				$model 			= Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
				$model_1 		= Guardians::model()->findByAttributes(array('id'=>$model->parent_id));				
				$this->render('view',array('model'=>$model,'model_1'=>$model_1));
			}
			else{
				throw new CHttpException(404,Yii::t('app','The page cannot be found.'));
			}
		}
	}
	
	
	//Function to generate Registration ID	
	private function getRegistrationId()
	{		
		$lastId = RegisteredStudents::model()->lastRecord()->find();		
		if($lastId){
			$regId = $lastId->registration_id+1;
		}
		else{
			$regIdStart = RegistrationSettings::model()->findByPk(1);
			$regId 		= $regIdStart->settings_value;			
		}		
		return $regId;
	}
			
	/**
	* Approve Registered Student
	*/
	public function actionApprove()
	{	
		$model_1	= '';
		$error_flag = 0;
		$model 		= Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));				
		$model_1	= Guardians::model()->findByAttributes(array('id'=>$model->parent_id));
		$flag		= true;
		
		if($model){
			if(!$model->validate()){
				$error_flag = 1;
			}
		}
		
		if($model_1){
			if(!$model_1->validate()){
				$error_flag = 1;
			}
		}
		
		if(!empty($_POST)){			
			Students::model()->approveProcess($_POST['Students']['id'],$_POST['batch']);
			echo CJSON::encode(array(
				'status'=>'success',
			));
			exit;      
		}
		if($flag){			
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$content	= $this->renderPartial('approve',array('model'=>$model, 'model_1'=>$model_1, 'error_flag'=>$error_flag),true,true);
			echo CJSON::encode(array(
				'status'=>'success',
				'content'=>$content
			));
			exit;
		}
	}
		
	/**
	* Disapprove Registered Student
	*/
	public function actionDisapprove()
	{
		$model 			= Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$parent 		= Guardians::model()->findByAttributes(array('id'=>$model->parent_id)); 
		$model->saveAttributes(array('status'=>-1));
		$notification 	= NotificationSettings::model()->findByAttributes(array('id'=>15));
		$college		= Configurations::model()->findByPk(1);
		if($notification->mail_enabled == '1' or $notification->sms_enabled == '1'){
		//Mail to student	
			if($notification->student == '1' and $notification->mail_enabled == '1'){
				$student_email 	= EmailTemplates::model()->findByPk(20);
				$subject 		= $student_email->subject;
				$message 		= $student_email->template;				
				$subject 		= str_replace("{{SCHOOL}}",ucfirst($college->config_value),$subject);
				$message 		= str_replace("{{SCHOOL}}",ucfirst($college->config_value),$message);			
				UserModule::sendMail($model->email,strip_tags($subject),$message);
			}			
		//Send sms
			if($notification->student == '1' and $notification->sms_enabled == '1'){					
				$from 			= $college->config_value;
				$sms_template 	= SystemTemplates::model()->findByAttributes(array('id'=>31));
				$sms_message 	= $sms_template->template;
				SmsSettings::model()->sendSms($model->phone1,$from,$sms_message);
			}				
		//Mail to parent
			if($notification->parent_1 == '1' and $notification->mail_enabled == '1'){
				$parent_email 	= EmailTemplates::model()->findByPk(19);
				$subject 		= $parent_email->subject;
				$message 		= $parent_email->template;
				$subject 		= str_replace("{{SCHOOL}}",ucfirst($college->config_value),$subject);
				$message 		= str_replace("{{SCHOOL}}",ucfirst($college->config_value),$message);
				$message 		= str_replace("{{STUDENT NAME}}",ucfirst($model->first_name).' '.ucfirst($model->middle_name).' '.ucfirst($model->last_name),$message);
				UserModule::sendMail($parent->email,strip_tags($subject),$message);
			}			
		//Send sms
			if($notification->parent_1 == '1' and $notification->sms_enabled == '1'){					
				$from 			= $college->config_value;
				$sms_template 	= SystemTemplates::model()->findByAttributes(array('id'=>30));
				$sms_message 	= $sms_template->template;
				SmsSettings::model()->sendSms($parent->mobile_phone,$from,$sms_message);
			}					
		}
				
		Yii::app()->user->setFlash('successMessage', Yii::t('app','Action performed successfully'));
		if($_REQUEST['flag']==1){
			$this->redirect(array('onlineapplicants'));
		}
		else{
			$this->redirect(array('approval'));
		}
	}
	
	/**
	* Delete Registered Student
	*/
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest){
			
			if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL){ //In case of single delete				
				$id		= array($_REQUEST['id']);
			}
			if(isset($_POST['student_id']) and $_POST['student_id']!=NULL){ //In case of common delete
				$id 	= $_POST['student_id']; 	
			}
			
			if(count($id) > 0){
				for($i = 0; $i < count($id); $i++){	
					$model 						= Students::model()->findByAttributes(array('id'=>$id[$i]));
					$parent 					= Guardians::model()->findByAttributes(array('id'=>$model->parent_id));
					$is_have_another_student 	= GuardianList::model()->countByAttributes(array('guardian_id'=>$parent->id));
					
					if($is_have_another_student == 1){	
						$parent->delete();
					}
					$model->delete();
					$guardian_list = GuardianList::model()->findByAttributes(array('student_id'=>$id[$i]));
					if($guardian_list){
						$guardian_list->delete();
					}
				//Deleting student documents	
					$studentDocuments = StudentDocument::model()->findAllByAttributes(array('student_id'=>$id[$i]));
					if(count($studentDocuments)>0){
						foreach($studentDocuments as $studentDocument){
							if($model->status!=1){ //checking if the student is approved or not				
								$destination_file = 'uploadedfiles/student_document/'.$model->id.'/'.$studentDocument->file;
								if(file_exists($destination_file)){
									if(unlink($destination_file)){
										$studentDocument->delete();						
									}
								}
							}
							else{
								$studentDocument->delete();
							}								
						}
						if($model->status!=1){ //checking if the student is approved or not				
							$path = 'uploadedfiles/student_document/'.$model->id;
							rmdir($path);
						}
					}
				//Deleting waiting list datas if the student is in waiting list
					$waitinglist = WaitinglistStudents::model()->findByAttributes(array('student_id'=>$id[$i]));
					if($waitinglist!=NULL){
						$criteria 						= new CDbCriteria;
						$criteria->condition 			= 'batch_id=:batch_id AND priority>:priority';
						$criteria->params[':batch_id'] 	= $waitinglist->batch_id;
						$criteria->params[':priority'] 	= $waitinglist->priority;						
						$DetailsOfStudent 				= WaitinglistStudents::model()->findAll($criteria);
						foreach($DetailsOfStudent as $change){
							$change->saveAttributes(array('priority'=>$change->priority - 1));
						}
						$waitinglist->delete();
					}											
				}
				Yii::app()->user->setFlash('successMessage', Yii::t('app','Action performed successfully'));
			}
			if($_REQUEST['flag'] == 1){
				$this->redirect(array('onlineapplicants'));
			}
			elseif($_REQUEST['flag'] == 2){
				$this->redirect(array('incompleteReg'));
			}
			else{
				$this->redirect(array('approval'));
			}
		}
		else{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}		
	}
		
//Delete the profile image	
	public function actionRemove()
	{		
		$model 		= Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));		
		$file_name 	= $model->photo_file_name;			
		if($model->saveAttributes(array('photo_file_name'=>''))){
			$path = 'uploadedfiles/student_profile_image/'.$model->id.'/'.$file_name;
			if(file_exists($path)){		
				unlink($path);													
			}
		}
		$this->redirect(array('profileedit','id'=>$_REQUEST['id']));
	}
	public function actionDisplaySavedImage()
	{
		$model=$this->loadModel($_GET['id']);
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Transfer-Encoding: binary');
		header('Content-length: '.$model->photo_file_size);
		header('Content-Type: '.$model->photo_content_type);
		header('Content-Disposition: attachment; filename='.$model->photo_file_name);
		echo $model->photo_data;
	}
	
	public function loadModel($id)
	{
		$model	= Students::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
		return $model;
	}
	
	public function actionProfileedit()
	{
		$this->layout 	= '//layouts/column2';		
		$model 			= Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));		
		$model_1 		= Guardians::model()->findByAttributes(array('id'=>$model->parent_id));		
		$settings		= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
		if($settings!=NULL){	
			$model->date_of_birth	= date($settings->displaydate,strtotime($model->date_of_birth));
			if($model_1->dob!=NULL and $model_1->dob == '0000-00-00'){
				$model_1->dob 	= '';
			}else{
				$model_1->dob	= date($settings->displaydate,strtotime($model_1->dob));			
			}
		}		
		
		if(!empty($_POST)){	
			$model->attributes 		= $_POST['Students'];
			$model_1->attributes 	= $_POST['Guardians'];							
			if($model->date_of_birth){
				$model->date_of_birth = date('Y-m-d',strtotime($model->date_of_birth));
			}
			if(isset($_POST['Guardians']['dob'])){
				$model_1->dob = date('Y-m-d',strtotime($_POST['Guardians']['dob']));						 
			}				
			$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
			if($settings!=NULL){	
				$timezone = Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
				date_default_timezone_set($timezone->timezone);										
			}
			
			//dynamic fields
			$fields   = FormFields::model()->getDynamicFields(1, 1, "forOnlineRegistration");
			foreach ($fields as $key => $field) {			
				if($field->form_field_type==6){  // date value
					$field_name = $field->varname;
					if($model->$field_name!=NULL and $model->$field_name!="0000-00-00" and $settings!=NULL){
						$model->$field_name = date('Y-m-d',strtotime($model->$field_name));						
					}
				}
			}
			
			$fields   = FormFields::model()->getDynamicFields(1, 2, "forOnlineRegistration");			
			foreach ($fields as $key => $field) {			
				if($field->form_field_type==6){  // date value
					$field_name = $field->varname;
					if($model->$field_name!=NULL and $model->$field_name!="0000-00-00" and $settings!=NULL){
						$model->$field_name = date('Y-m-d',strtotime($model->$field_name));												
					}
				}
			}
			
			$fields   = FormFields::model()->getDynamicFields(2, 1, "forOnlineRegistration");
			foreach ($fields as $key => $field) {			
				if($field->form_field_type==6){  // date value
					$field_name = $field->varname;
					if($model_1->$field_name!=NULL and $model_1->$field_name!="0000-00-00" and $settings!=NULL){
						$model_1->$field_name = date('Y-m-d',strtotime($model_1->$field_name));
					}
				}
			}
			
			$fields   = FormFields::model()->getDynamicFields(2, 2, "forOnlineRegistration");
			foreach ($fields as $key => $field) {			
				if($field->form_field_type==6){  // date value
					$field_name = $field->varname;
					if($model_1->$field_name!=NULL and $model_1->$field_name!="0000-00-00" and $settings!=NULL){
						$model_1->$field_name = date('Y-m-d',strtotime($model_1->$field_name));
					}
				}
			}
						
			$model->updated_at 		= date('Y-m-d H:i:s');
			$model_1->updated_at 	= date('Y-m-d H:i:s');
			if($file = CUploadedFile::getInstance($model,'photo_data')){
				$file_name 				= DocumentUploads::model()->getFileName($file);
				$model->photo_file_name	= $file_name;				
			}	
			$model->validate();
			$model_1->validate();
			if($model->validate() and $model_1->validate()){
				if($model->save() and $model_1->save()){
					$is_guardian_list = GuardianList::model()->findByAttributes(array('student_id'=>$model->id,'guardian_id'=>$model_1->id));
					if($is_guardian_list == NULL){
						$guardian_list				= new GuardianList;
						$guardian_list->student_id	= $model->id;
						$guardian_list->guardian_id	= $model_1->id;
						$guardian_list->relation	= $model_1->relation;
						$guardian_list->save();	
					}else{
						$is_guardian_list->relation = $model_1->relation;
						$is_guardian_list->save();
					}	
					//Save the profile pic to the folder	
					if($model->photo_file_name!=NULL){
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
						$info = getimagesize($_FILES['Students']['tmp_name']['photo_data']); 
						if($info['mime'] == 'image/jpeg'){
							$image = imagecreatefromjpeg($_FILES['Students']['tmp_name']['photo_data']);
						}elseif($info['mime'] == 'image/gif'){
							$image = imagecreatefromgif($_FILES['Students']['tmp_name']['photo_data']);
						}elseif($info['mime'] == 'image/png'){
							$image = imagecreatefrompng($_FILES['Students']['tmp_name']['photo_data']);
						}
						
						$temp_file_name 	= $_FILES['Students']['tmp_name']['photo_data'];					
						$destination_file 	= 'uploadedfiles/student_profile_image/'.$model->id.'/'.$file_name;
						imagejpeg($image, $destination_file, 30);
					}									
					$this->redirect(array('view','id'=>$model->id));					
				}
			}			
		}
		
		//dynamic date fields
		$fields   = FormFields::model()->getDynamicFields(1, 1, "forOnlineRegistration");
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
		
		$fields   = FormFields::model()->getDynamicFields(1, 2, "forOnlineRegistration");
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
		
		$fields   = FormFields::model()->getDynamicFields(2, 1, "forOnlineRegistration");
		foreach ($fields as $key => $field) {			
			if($field->form_field_type==6){  // date value
				$field_name = $field->varname;
				if($model_1->$field_name!=NULL and $model_1->$field_name!="0000-00-00" and $settings!=NULL){
					$model_1->$field_name = date($settings->displaydate,strtotime($model_1->$field_name));
				}
				else{
					$model_1->$field_name=NULL;
				}
			}
		}
		
		$fields   = FormFields::model()->getDynamicFields(2, 2, "forOnlineRegistration");
		foreach ($fields as $key => $field) {			
			if($field->form_field_type==6){  // date value
				$field_name = $field->varname;
				if($model_1->$field_name!=NULL and $model_1->$field_name!="0000-00-00" and $settings!=NULL){
					$model_1->$field_name = date($settings->displaydate,strtotime($model_1->$field_name));
				}
				else{
					$model_1->$field_name=NULL;
				}
			}
		}
		
		$this->render('profileedit',array('model'=>$model,'model_1'=>$model_1));
	}
				
	public function actionShowImage()
	{			
		$model=Logo::model()->findByPk($_GET['id']);
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Transfer-Encoding: binary');
		header('Content-length: '.$model->photo_file_size);
		header('Content-Type: '.$model->photo_content_type);
		header('Content-Disposition: attachment; filename='.$model->photo_file_name);
		echo  $model->photo_data;
	}
		
//Downloading the student document	
	public function actionDownload()
	{		
		$student_id		= $_REQUEST['student_id'];
		$model			= StudentDocument::model()->findByPk($_REQUEST['id']);		
		$file_path 		= 'uploadedfiles/student_document/'.$model->student_id.'/'.$model->file;
		$file_content 	= file_get_contents($file_path);
		$model->title 	= str_replace(' ','',$model->title);
		header("Content-Type: ".$model->file_type);
		header("Content-disposition: attachment; filename=".$model->file);
		header("Pragma: no-cache");
		echo $file_content;
		exit;
	}	
	//Missing document notification
	public function actionNotify()
	{
		$college		= Configurations::model()->findByPk(1);
		$student 		= Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$notification 	= NotificationSettings::model()->findByAttributes(array('id'=>19));
		$guardian 		= Guardians::model()->findByAttributes(array('id'=>$student->parent_id,'is_delete'=>0));
		$doc_lists 		= StudentDocumentList::model()->findAll();
		$name 			= '<strong>'.$student->first_name.' '.$student->last_name.'</strong>';
		if($notification->sms_enabled=='1' and $notification->parent_1 == '1'){ // Checking if SMS is enabled.		
			if($guardian->mobile_phone){
				$to = $model->mobile_phone;	
			}
			if($to!=''){ // Send SMS if phone number is provided								
				$from 		= $college->config_value;
				$template	= SystemTemplates::model()->findByPk(35);
				$message 	= $template->template;
				
				SmsSettings::model()->sendSms($to,$from,$message);				
			} // End send SMS
		}
		if($notification->mail_enabled == '1' and $notification->parent_1 == '1'){		
			$template	= EmailTemplates::model()->findByPk(26);
			$subject 	= $template->subject;
			$message 	= $template->template;				
			$subject 	= str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);																	
			$message 	= str_replace("{{STUDENT NAME}}",$name,$message);
			if($doc_lists != NULL){				
				foreach($doc_lists as $list){
					$studentdoc = StudentDocument::model()->findByAttributes(array('student_id'=>$student->id,'doc_type'=>$list->name));					
					if(count($studentdoc) == 0){
						$doc = $doc.$list->name.'<br>';						
					}
				}				
			}			
			$message = str_replace("{{DOCUMENT NAME}}",$doc,$message);			
			UserModule::sendMail($guardian->email,$subject,$message);		
		}
		$this->redirect(array('/onlineadmission/admin/view','id'=>$_REQUEST['id']));
	}	
	
	public function actionIncompleteReg()
	{		
		$completed_step_arr					= array(1, 2);
		$date								= date('Y-m-d H:i:s', strtotime('-5 hour'));
		
		$criteria 							= new CDbCriteria;
		$criteria->condition				= 'is_online=:is_online AND created_at < :created_at AND academic_yr=:academic_yr';				
		$criteria->params[':is_online'] 	= 1;
		$criteria->params[':created_at']	= $date;	
		$criteria->params[':academic_yr']	= Yii::app()->user->year;
		
		if(isset($_REQUEST['val'])){
			$criteria->condition		= $criteria->condition.' AND '.'(first_name LIKE :match OR last_name LIKE :match OR middle_name LIKE :match)';			
			$criteria->params[':match'] = $_REQUEST['val'].'%';
		}
		
		if(isset($_REQUEST['name']) and $_REQUEST['name']!=NULL){
			if((substr_count( $_REQUEST['name'],' '))==0){ 	
				$criteria->condition		= $criteria->condition.' AND '.'(first_name LIKE :name OR last_name LIKE :name OR middle_name LIKE :name)';
				$criteria->params[':name'] 	= $_REQUEST['name'].'%';
			}
			else if((substr_count( $_REQUEST['name'],' '))>=1){
				$name						= explode(" ",$_REQUEST['name']);
				$criteria->condition		= $criteria->condition.' AND '.'(first_name LIKE :name OR last_name LIKE :name OR middle_name LIKE :name)';
				$criteria->params[':name']  = $name[0].'%';
				$criteria->condition		= $criteria->condition.' AND '.'(first_name LIKE :name1 OR last_name LIKE :name1 OR middle_name LIKE :name1)';
				$criteria->params[':name1'] = $name[1].'%';			
			}
		}
		
		if(isset($_REQUEST['email']) and $_REQUEST['email']!=NULL){
			$criteria->condition			= $criteria->condition.' AND '.'email=:email';
			$criteria->params[':email'] 	= $_REQUEST['email'];
		}
		
		if(isset($_REQUEST['phone_no']) and $_REQUEST['phone_no']!=NULL){
			$criteria->condition			= $criteria->condition.' AND '.'phone1=:phone1';
			$criteria->params[':phone1'] 	= $_REQUEST['phone_no'];
		}
		
		$criteria->addInCondition('is_completed', $completed_step_arr);
		$criteria->order 					= 'first_name ASC';
		
		//Pagination								
		$total = Students::model()->count($criteria); // Count students
		$pages = new CPagination($total);
		$pages->setPageSize(20);
		$pages->applyLimit($criteria);
			
		$students	= Students::model()->findAll($criteria);
		
		$this->render('incompleteReg', array('students'=>$students, 'pages' => $pages, 'item_count'=>$total, 'page_size'=>20));		
	}
	
	public function actionBatch()
	{				
		$data	= Batches::model()->findAll('course_id=:id AND is_deleted=:x AND is_active=:y', array(':id'=>(int) $_POST['cid'],':x'=>'0',':y'=>1));				  
		echo CHtml::tag('option', array('value' => ''), CHtml::encode(Yii::t('app','Select')), true); 
		$data	= CHtml::listData($data,'id','name');
		foreach($data as $value => $name){
			echo CHtml::tag('option', array('value'=>$value),CHtml::encode(html_entity_decode($name)),true);
		}
	}
}