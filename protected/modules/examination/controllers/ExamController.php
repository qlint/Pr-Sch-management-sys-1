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

class ExamController extends RController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

public function   init() {
             $this->registerAssets();
              parent::init();
 }

  private function registerAssets(){

            Yii::app()->clientScript->registerCoreScript('jquery');

         //IMPORTANT about Fancybox.You can use the newest 2.0 version or the old one
        //If you use the new one,as below,you can use it for free only for your personal non-commercial site.For more info see
		//If you decide to switch back to fancybox 1 you must do a search and replace in index view file for "beforeClose" and replace with 
		//"onClosed"
        // http://fancyapps.com/fancybox/#license
          // FancyBox2
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js_plugins/fancybox2/jquery.fancybox.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/js_plugins/fancybox2/jquery.fancybox.css', 'screen');
         // FancyBox
         //Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/fancybox/jquery.fancybox-1.3.4.js', CClientScript::POS_HEAD);
         // Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js_plugins/fancybox/jquery.fancybox-1.3.4.css','screen');
        //JQueryUI (for delete confirmation  dialog)
         Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/jqui1812/js/jquery-ui-1.8.12.custom.min.js', CClientScript::POS_HEAD);
         Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js_plugins/jqui1812/css/dark-hive/jquery-ui-1.8.12.custom.css','screen');
          ///JSON2JS
         Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/json2/json2.js');
       

           //jqueryform js
               Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/ajaxform/jquery.form.js', CClientScript::POS_HEAD);
              Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js_plugins/ajaxform/form_ajax_binding.js', CClientScript::POS_HEAD);
              Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js_plugins/ajaxform/client_val_form.css','screen');

 }


	/**
	 * @return array action filters
	 */

     public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
        
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('returnView'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('ajax_create','ajax_update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index','returnForm','ajax_delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ExamGroups::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='exam-groups-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

        //AJAX CRUD

         public function actionReturnView(){

               //don't reload these scripts or they will mess up the page
                //yiiactiveform.js still needs to be loaded that's why we don't use
                // Yii::app()->clientScript->scriptMap['*.js'] = false;
                $cs=Yii::app()->clientScript;
                $cs->scriptMap=array(
                                                 'jquery.min.js'=>false,
                                                 'jquery.js'=>false,
                                                 'jquery.fancybox-1.3.4.js'=>false,
                                                 'jquery.fancybox.js'=>false,
                                                 'jquery-ui-1.8.12.custom.min.js'=>false,
                                                 'json2.js'=>false,
                                                 'jquery.form.js'=>false,
                                                'form_ajax_binding.js'=>false
        );

        $model=$this->loadModel($_POST['id']);
        $this->renderPartial('view',array('model'=>$model),false, true);
      }

             public function actionReturnForm(){
				
              //Figure out if we are updating a Model or creating a new one.
             if(isset($_POST['update_id'])){
				$model= $this->loadModel($_POST['update_id']);
				$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
				if($settings!=NULL){	
					$model->exam_date=date($settings->displaydate,strtotime($model->exam_date));
				}
			 }
			 else $model=new ExamGroups;
            //  Comment out the following line if you want to perform ajax validation instead of client validation.
            //  You should also set  'enableAjaxValidation'=>true and
            //  comment  'enableClientValidation'=>true  in CActiveForm instantiation ( _ajax_form  file).


             //$this->performAjaxValidation($model);

               //don't reload these scripts or they will mess up the page
                //yiiactiveform.js still needs to be loaded that's why we don't use
                // Yii::app()->clientScript->scriptMap['*.js'] = false;
                $cs=Yii::app()->clientScript;
                $cs->scriptMap=array(
                                                 'jquery.min.js'=>false,
                                                 'jquery.js'=>false,
                                                 'jquery.fancybox-1.3.4.js'=>false,
                                                 'jquery.fancybox.js'=>false,
                                                 'jquery-ui-1.8.12.custom.min.js'=>false,
                                                 'json2.js'=>false,
                                                 'jquery.form.js'=>false,
                                                 'form_ajax_binding.js'=>false
        );


        $this->renderPartial('_ajax_form', array('model'=>$model), false, true);
      }
	
	public function checkBatchActive($id) //Check whether the batch is active
	{
		$batch = Batches::model()->findByAttributes(array('id'=>$id, 'is_active'=>1, 'is_deleted'=>0));
		if($batch == NULL){
			$this->redirect(array('/examination'));
		}		
	}

    public function actionIndex()
	{
		if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL){
			
			$this->checkBatchActive($_REQUEST['id']);
			$batch_id = $_GET['id'];
			if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 1){
				$model=new ExamGroups('search');
				$model->unsetAttributes();  // clear any default values
				if(isset($_GET['id']))
					$model->batch_id=$_GET['id'];
		        
				$this->render('index',array('model'=>$model));
			}
			else{
				$model=new CbscExamGroups('search');
				$model->unsetAttributes();  // clear any default values
				if(isset($_GET['id']))
					$model->batch_id=$_GET['id'];
							
				$this->redirect(array('/CBSCExam/exam','id'=>$model->batch_id));			
			}
		}
		else{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
	}
	
	
	
	/*publish a particular exam group date*/
	
	public function actionPublishDate()
	{
		
		if($_REQUEST['exam_group_id']!=NULL and $_REQUEST['id']!=NULL)
		{
			$exam_group = ExamGroups::model()->findByPk($_REQUEST['exam_group_id']);
			$exam_group->is_published = 1;
			if($exam_group->save()){
				//Mobile Push Notification
				if(Configurations::model()->isAndroidEnabled()){
					$batch			= Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
					$sender_name	= PushNotifications::model()->getUserName(Yii::app()->user->id);
					$students		= Yii::app()->getModule('students')->studentsOfBatch($batch->id);
					if($students){
						//Get Messages
						$student_push_notifications	= PushNotifications::model()->getNotificationDatas(28);	
						$parent_push_notifications	= PushNotifications::model()->getNotificationDatas(29);	
						foreach($students as $student){
							//To student
							if($student->uid != NULL and $student->uid != 0){
								//Get student devices
								$student_devices	= PushNotifications::model()->getStudentDevice($student->uid);
								if($student_devices){																	
									foreach($student_devices as $student_device){
										//Get key value of the notification data array					
										$key	= PushNotifications::model()->getKeyData($student_device->uid, $student_push_notifications);
										
										$message	= $student_push_notifications[$key]['message'];
										$message	= str_replace("{Exam Name}", html_entity_decode(ucfirst($exam_group->name)), $message);										
										$message	= str_replace("{Batch Name}", html_entity_decode(ucfirst($batch->name)), $message);
										
										$argument_arr = array('message'=>$message, 'device_id'=>array($student_device->device_id), 'sender_name'=>$sender_name, 'batch_id'=>$batch->id, 'exam_group_id'=>$exam_group->id, 'flag'=>1);               
										Configurations::model()->devicenotice($argument_arr, $student_push_notifications[$key]['title'], "default_exam");																			
									}
								}
								
								//To Guardians							
								//Get student devices
								$guardian_devices	= PushNotifications::model()->getGuardianDevice($student->id);
								if($guardian_devices){																	
									foreach($guardian_devices as $guardian_device){
										//Get key value of the notification data array					
										$key	= PushNotifications::model()->getKeyData($guardian_device->uid, $parent_push_notifications);
										
										$message	= $parent_push_notifications[$key]['message'];
										$message	= str_replace("{Exam Name}", html_entity_decode(ucfirst($exam_group->name)), $message);										
										$message	= str_replace("{Batch Name}", html_entity_decode(ucfirst($batch->name)), $message);
										$message	= str_replace("{Student Name}", $student->getStudentname(), $message);
										
										$argument_arr = array('message'=>$message, 'device_id'=>array($guardian_device->device_id), 'sender_name'=>$sender_name, 'batch_id'=>$batch->id, 'exam_group_id'=>$exam_group->id, 'flag'=>1);               
										Configurations::model()->devicenotice($argument_arr, $parent_push_notifications[$key]['title'], "default_exam");	
									}
								}							
							}
						}
					}
				}
				
				if(!isset($_GET['ajax']))
				{
    				$this->redirect(Yii::app()->request->urlReferrer);
				}
				//$this->redirect(array('index'));
			}
		}
	}
	
	/*publish a particular exam group result*/
	
	public function actionPublishResult()
	{
		
		if($_REQUEST['exam_group_id']!=NULL and $_REQUEST['id']!=NULL)
		{
			$exam_group = ExamGroups::model()->findByPk($_REQUEST['exam_group_id']);
			$exam_group->result_published = 1;
			if($exam_group->save()){
				//Mobile Push Notification
				if(Configurations::model()->isAndroidEnabled()){
					$batch			= Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
					$sender_name	= PushNotifications::model()->getUserName(Yii::app()->user->id);
					$students		= Yii::app()->getModule('students')->studentsOfBatch($batch->id);
					if($students){
						//Get Messages
						$student_push_notifications	= PushNotifications::model()->getNotificationDatas(32);	
						$parent_push_notifications	= PushNotifications::model()->getNotificationDatas(33);	
						foreach($students as $student){
							//To student
							if($student->uid != NULL and $student->uid != 0){
								//Get student devices
								$student_devices	= PushNotifications::model()->getStudentDevice($student->uid);
								if($student_devices){																	
									foreach($student_devices as $student_device){
										//Get key value of the notification data array					
										$key	= PushNotifications::model()->getKeyData($student_device->uid, $student_push_notifications);
										
										$message	= $student_push_notifications[$key]['message'];
										$message	= str_replace("{Exam Name}", html_entity_decode(ucfirst($exam_group->name)), $message);										
										$message	= str_replace("{Batch Name}", html_entity_decode(ucfirst($batch->name)), $message);
										
										$argument_arr = array('message'=>$message, 'device_id'=>array($student_device->device_id), 'sender_name'=>$sender_name, 'batch_id'=>$batch->id, 'exam_group_id'=>$exam_group->id, 'flag'=>2);               
										Configurations::model()->devicenotice($argument_arr, $student_push_notifications[$key]['title'], "default_exam");																			
									}
								}
								
								//To Guardians							
								//Get student devices
								$guardian_devices	= PushNotifications::model()->getGuardianDevice($student->id);
								if($guardian_devices){																	
									foreach($guardian_devices as $guardian_device){
										//Get key value of the notification data array					
										$key	= PushNotifications::model()->getKeyData($guardian_device->uid, $parent_push_notifications);
										
										$message	= $parent_push_notifications[$key]['message'];
										$message	= str_replace("{Exam Name}", html_entity_decode(ucfirst($exam_group->name)), $message);										
										$message	= str_replace("{Batch Name}", html_entity_decode(ucfirst($batch->name)), $message);
										$message	= str_replace("{Student Name}", $student->getStudentname(), $message);
										
										$argument_arr = array('message'=>$message, 'device_id'=>array($guardian_device->device_id), 'sender_name'=>$sender_name, 'batch_id'=>$batch->id, 'exam_group_id'=>$exam_group->id, 'student_id'=>$student->id, 'flag'=>2);               
										Configurations::model()->devicenotice($argument_arr, $parent_push_notifications[$key]['title'], "default_exam");	
									}
								}							
							}
						}
					}
				}
				
				
				if(!isset($_GET['ajax']))
				{
    				$this->redirect(Yii::app()->request->urlReferrer);
				}
				//$this->redirect(array('index'));
			}
		}
	}


	public function actionAjax_Update(){
		if(isset($_POST['ExamGroups'])){
           	$model = $this->loadModel($_POST['update_id']);
			
			// For SMS
		   	$prev_name = $model->name;
		   	$prev_is_published = $model->is_published; // Fetching previous is_published
			$prev_result_published = $model->result_published; // Fetching previous result_published
			$prev_exam_date = $model->exam_date; //Fetching previous exam date
			// End For SMS
			
			// For Activity Feed
			$old_model	= $model->attributes; // For activity feed	
			$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
			if($settings!=NULL){	
				$old_exam_date = date($settings->displaydate,strtotime($old_model['exam_date']));			
			}
			// End For Activity Feed			
			
			$model->attributes	= $_POST['ExamGroups'];
			$model->exam_date	= date('Y-m-d',strtotime($model->exam_date));
			if($model->save(false)){				
				// Saving to activity feed
				$results = array_diff_assoc($model->attributes,$old_model); // To get the fields that are modified.
				
				foreach($results as $key => $value){
					if($key == 'name'){
						$value = ucfirst($value);
					}
					elseif($key == 'is_published'){
						if($value == 1){
							$value = Yii::t('app','Published');
						}
						else{
							$value = Yii::t('app','Not Published');
						}
						
						if($old_model[$key] == 1){
							$old_model[$key] = Yii::t('app','Published');
						}
						else{
							$old_model[$key] = Yii::t('app','Not Published');
						}
					}
					elseif($key == 'result_published'){
						if($value == 1){
							$value = Yii::t('app','Result Published');
						}
						else{
							$value = Yii::t('app','Result Not Published');
						}
						
						if($old_model[$key] == 1){
							$old_model[$key] = Yii::t('app','Result Published');
						}
						else{
							$old_model[$key] = Yii::t('app','Result Not Published');
						}
					}
					elseif($key == 'exam_date'){
						$value 				= $_POST['ExamGroups']['exam_date'];
						$old_model[$key] 	= $old_exam_date;
					}
					
					//Adding activity feed 
					ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'12',$model->id,ucfirst($model->name),$model->getAttributeLabel($key),$old_model[$key],$value); 
				}	
				//END saving to activity feed
					
				// Send SMS, mail, message if saved
				$notification 	= NotificationSettings::model()->findByAttributes(array('id'=>6));
				$college		= Configurations::model()->findByPk(1);
				$to				= '';
				$message 		= '';
				$settings		= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
				if($settings!=NULL){	
					$model->exam_date = date($settings->displaydate,strtotime($model->exam_date));				
				}
				// Send Schedule SMS only if, SMS is enabled and schedule is published
				if($notification->sms_enabled == '1' or $notification->mail_enabled == '1' or $notification->msg_enabled == '1'){ 
					$students=Students::model()->findAll("batch_id=:x", array(':x'=>$model->batch_id)); //Selecting students of the batch
					
					$reg_arr_1 = array();//device ids of all students in batch
					$reg_arr_2 = array();	
					$argument_arr_1 = array();
					$argument_arr_2 = array();
					
					foreach ($students as $student){ 
					
						$messages 	= array();//all messages of this student.
						
						if($student->phone1){ // Checking if phone number is provided
							$to = $student->phone1;	
						}
						elseif($student->phone2){
							$to = $student->phone2;
						}
						if($to!=''){ // Sending SMS to each student							
							$from = $college->config_value;
							if($prev_is_published=='0' and $model->is_published=='1' and $prev_result_published=='0' and $model->result_published=='0'){ 
								// If exam schedule made published and result is not published								
								$template	= SystemTemplates::model()->findByPk(8);
								$message 	= $template->template;
								$message 	= str_replace("<Exam Name>",$model->name,$message);
								
								//Mail	
								if($notification->mail_enabled == '1' and $notification->student == '1'){									
									$template		= EmailTemplates::model()->findByPk(6);
									$mail_subject 	= $template->subject;
									$mail_message 	= $template->template;																	
									$mail_subject 	= str_replace("{{SCHOOL NAME}}",$college->config_value,$mail_subject);
									$mail_subject 	= str_replace("{{EXAM NAME}}",$model->name,$mail_subject);																		
									$mail_message 	= str_replace("{{SCHOOL NAME}}",$college->config_value,$mail_message);
									$mail_message 	= str_replace("{{EXAM NAME}}",$model->name,$mail_message);
									$mail_message 	= str_replace("{{EXAM DATE}}",$model->exam_date,$mail_message);
									
									if(FormFields::model()->isVisible('email','Students','forAdminRegistration')){								
										UserModule::sendMail($student->email,$mail_subject,$mail_message);
									}
								}
								
								//Message
								if($notification->msg_enabled == '1' and $notification->student == '1'){
									$msg_subject = $model->name.Yii::t('app',' is scheduled');
									$msg_message = 'Hi, '.$model->name.Yii::t('app',' exam is scheduled on ').$model->exam_date;
									
									NotificationSettings::model()->sendMessage($student->uid,$msg_subject,$msg_message);
								}
								
								//For Android App
								if(Configurations::model()->isAndroidEnabled()){									
									$reg_ids = UserDevice::model()->findAllByAttributes(array('uid'=>$student->uid));
									foreach($reg_ids as $reg_id){
										$reg_arr_1[] = $reg_id->device_id; 
									}
									$message 		= str_replace("<Exam Name>",$model->name,$message);
									$argument_arr_1 = array('message'=>$message,'device_id'=>$reg_arr_1);
								}
								
							}
							elseif($prev_is_published=='1' and $model->is_published=='1' and $prev_result_published=='0' and $model->result_published=='0'){ 
								// If exam schedule already published and result is not published
								if(strcasecmp($prev_name, $model->name) == 0){ // Checking if exam name is changed and if not changed.
									if(strcasecmp($prev_exam_date, $model->exam_date) != 0){
										
										$template	= SystemTemplates::model()->findByPk(9);
										$message 	= $template->template;
										$message 	= str_replace("<Exam Name>",$model->name,$message);
										
									   	//Mail
										if($notification->mail_enabled == '1' and $notification->student == '1'){
											$template		= EmailTemplates::model()->findByPk(7);											
											$mail_subject 	= $template->subject;
											$mail_message 	= $template->template;
											$mail_subject 	= str_replace("{{SCHOOL NAME}}",$college->config_value,$mail_subject);
											$mail_subject 	= str_replace("{{EXAM NAME}}",$model->name,$mail_subject);																						
											$mail_message 	= str_replace("{{SCHOOL NAME}}",$college->config_value,$mail_message);
											$mail_message 	= str_replace("{{EXAM NAME}}",$model->name,$mail_message);
											$mail_message 	= str_replace("{{EXAM DATE}}",$model->exam_date,$mail_message);
											
											if(FormFields::model()->isVisible('email','Students','forAdminRegistration')){													
												UserModule::sendMail($student->email,$mail_subject,$mail_message);
											}
										}
										 
										//Message
										if($notification->msg_enabled == '1' and $notification->student == '1'){
											$msg_subject = $model->name.Yii::t('app',' is schedule is modified');
											$msg_message = Yii::t('app','Hi, ').$model->name.Yii::t('app',' exam is scheduled on ').$model->exam_date;
											NotificationSettings::model()->sendMessage($student->uid,$msg_subject,$msg_message);
										}
									}
								}
								else{ // If exam name is changed.
									
									$template	= SystemTemplates::model()->findByPk(10);
									$message 	= $template->template;
									$message 	= str_replace("<Old Exam Name>",$prev_name,$message);
									$message 	= str_replace("<New Exam Name>",$model->name,$message);
									
									//Mail
									if($notification->mail_enabled == '1' and $notification->student == '1'){
										$template		= EmailTemplates::model()->findByPk(9);											
										$mail_subject 	= $template->subject;
										$mail_message 	= $template->template;
										$mail_subject 	= str_replace("{{SCHOOL NAME}}",$college->config_value,$mail_subject);
										$mail_subject 	= str_replace("{{EXAM NAME}}",$model->name,$mail_subject);																						
										$mail_message 	= str_replace("{{SCHOOL NAME}}",$college->config_value,$mail_message);
										$mail_message 	= str_replace("{{EXAM NAME}}",$model->name,$mail_message);
										$mail_message 	= str_replace("{{OLD EXAM NAME}}",$prev_name,$mail_message);
										
										if(FormFields::model()->isVisible('email','Students','forAdminRegistration')){													
											UserModule::sendMail($student->email,$mail_subject,$mail_message);
										}
									}
									 
									//Message
									if($notification->msg_enabled == '1' and $notification->student == '1'){
										$msg_subject = $prev_name.Yii::t('app',' name is modified');
										$msg_message = Yii::t('app','Hi, ').$prev_name.Yii::t('app',' exam name is changed to ').$model->name.'.';
										
										NotificationSettings::model()->sendMessage($student->uid,$msg_subject,$msg_message);
									}
									
									if(strcasecmp($prev_exam_date, $model->exam_date) != 0){ // if exam name is changed and date is also changed.
										$template	= SystemTemplates::model()->findByPk(11);
										$message 	= $template->template;
										$message 	= str_replace("<Old Exam Name>",$prev_name,$message);
										$message 	= str_replace("<New Exam Name>",$model->name,$message);
										
										//Mail	
										if($notification->mail_enabled == '1' and $notification->student == '1'){
											$template		= EmailTemplates::model()->findByPk(8);											
											$mail_subject 	= $template->subject;
											$mail_message 	= $template->template;
											$mail_subject 	= str_replace("{{SCHOOL NAME}}",$college->config_value,$mail_subject);
											$mail_subject 	= str_replace("{{EXAM NAME}}",$model->name,$mail_subject);																						
											$mail_message 	= str_replace("{{SCHOOL NAME}}",$college->config_value,$mail_message);
											$mail_message 	= str_replace("{{EXAM NAME}}",$model->name,$mail_message);
											$mail_message 	= str_replace("{{OLD EXAM NAME}}",$prev_name,$mail_message);
											$mail_message 	= str_replace("{{EXAM DATE}}",$model->exam_date,$mail_message);
											
											if(FormFields::model()->isVisible('email','Students','forAdminRegistration')){													
												UserModule::sendMail($student->email,$mail_subject,$mail_message);
											}
										}
										
										//Message
										if($notification->msg_enabled == '1' and $notification->student == '1'){
											$msg_subject = $prev_name.Yii::t('app',' details are modified');
											$msg_message = Yii::t('app','Hi, ').$prev_name.Yii::t('app',' exam name is changed to ').$model->name.'.'.Yii::t('app',' Also, the schedule is modified');
											
											NotificationSettings::model()->sendMessage($student->uid,$msg_subject,$msg_message);
										}
									}
								}								
							}
							
							if($message!='' and $notification->sms_enabled == '1' and $notification->student == '1'){ // Send SMS if there is some message.
								SmsSettings::model()->sendSms($to,$from,$message);
							}
						} // End send SMS to each student
					
				
						// Send Result SMS only if, SMS is enabled and result is published
						if($notification->sms_enabled=='1' or $notification->mail_enabled == '1' or $notification->msg_enabled == '1'){ // Exam Result SMS
							if($model->is_published=='1' and $prev_result_published=='0' and $model->result_published=='1'){//If result is published								
								//SMS	
								if($notification->sms_enabled and $notification->student == '1'){
									$from 		= $college->config_value;
									$template	= SystemTemplates::model()->findByPk(12);
									$message 	= $template->template;
									$message 	= str_replace("<Exam Name>",$model->name,$message);
									
									SmsSettings::model()->sendSms($to,$from,$message);
								}
								
								//For Android App
								if(Configurations::model()->isAndroidEnabled()){										
									$reg_ids = UserDevice::model()->findAllByAttributes(array('uid'=>$student->uid));
									foreach($reg_ids as $reg_id){
										$reg_arr_2[] = $reg_id->device_id; 
									}
									$template		= SystemTemplates::model()->findByPk(12);
									$message 		= $template->template;
									$message 		= str_replace("<Exam Name>",$model->name,$message);
									$argument_arr_2 = array('message'=>$message,'device_id'=>$reg_arr_2);
								}
								
								//Mail
								if($notification->mail_enabled == '1' and $notification->student == '1'){							
									$template		= EmailTemplates::model()->findByPk(11);
									$mail_subject 	= $template->subject;
									$mail_message 	= $template->template;
									$mail_subject 	= str_replace("{{SCHOOL NAME}}",$college->config_value,$mail_subject);
									$mail_subject 	= str_replace("{{EXAM NAME}}",$model->name,$mail_subject);														
									$mail_message 	= str_replace("{{SCHOOL NAME}}",$college->config_value,$mail_message);
									$mail_message 	= str_replace("{{EXAM NAME}}",$model->name,$mail_message);
									
									if(FormFields::model()->isVisible('email','Students','forAdminRegistration')){						
										UserModule::sendMail($student->email,$mail_subject,$mail_message);
									}
								}
								
								//Message	
								if($notification->msg_enabled == '1' and $notification->student == '1'){
									$msg_subject = $model->name.Yii::t('app',' results published');
									$msg_message = Yii::t('app','Hi, ').$model->name.Yii::t('app',' exam result is published.');
									
									NotificationSettings::model()->sendMessage($student->uid,$msg_subject,$msg_message);
								}								
							}
						}
			  		}
					
					//For Android App
					if(Configurations::model()->isAndroidEnabled()){	
						Configurations::model()->devicenotice($argument_arr_1,"Exams","exams");
						Configurations::model()->devicenotice($argument_arr_2,"Exams","exams");
					}
				}				
                echo json_encode(array('success'=>true));
			}else
				echo json_encode(array('success'=>false));
			}
	}


	public function actionAjax_Create()
	{
		if(isset($_POST['ExamGroups'])){
			$model = new ExamGroups;			
			$model->attributes = $_POST['ExamGroups'];
			$model->exam_date = date('Y-m-d',strtotime($model->exam_date));
						                       
			if($model->save(false)){															
				ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'11',$model->id,ucfirst($model->name),NULL,NULL,NULL); 
							
				// Send SMS if saved
				$notification = NotificationSettings::model()->findByAttributes(array('id'=>6));
				$college=Configurations::model()->findByPk(1);
				$to = '';
				// Send SMS,mail,message only if, SMS or mail or message is enabled and schedule is published
				if($model->is_published=='1'){ 
					$students=Students::model()->findAll("batch_id=:x and is_deleted=:y and is_active=:z", array(':x'=>$model->batch_id,':y'=>0,':z'=>1)); 
					
					$reg_arr = array();//device ids of all students in batch
					$argument_arr_1 = array();
					
					foreach($students as $student){ 
						if($student->phone1){ // Checking if phone number is provided
							$to = $student->phone1;	
						}
						elseif($student->phone2){
							$to = $student->phone2;
						}
						$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
						if($settings!=NULL){	
							$model->exam_date=date($settings->displaydate,strtotime($model->exam_date));				
						}
						
						//SMS	
						if($to!='' and $notification->sms_enabled=='1' and $notification->student == '1'){ // Sending SMS to each student											
							$from		= $college->config_value;
							$template	= SystemTemplates::model()->findByPk(8);
							$message 	= $template->template;
							$message 	= str_replace("<Exam Name>",$model->name,$message);
							
							SmsSettings::model()->sendSms($to,$from,$message);
						}
						
						//create message to app device...
						if(Configurations::model()->isAndroidEnabled()){											
							$reg_ids = UserDevice::model()->findAllByAttributes(array('uid'=>$student->uid));
							foreach($reg_ids as $reg_id){
								$reg_arr[] = $reg_id->device_id; 
							}
							$template	= SystemTemplates::model()->findByPk(8);
							$message 	= $template->template;
							$message 	= str_replace("<Exam Name>",$model->name,$message);
							$argument_arr_1 = array('message'=>$message,'device_id'=>$reg_arr);		
						}
						
						//Mail
						if($notification->mail_enabled == '1' and $notification->student == '1'){
							$template	= EmailTemplates::model()->findByPk(6);											
							$subject 	= $template->subject;
							$message 	= $template->template;
							$subject 	= str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);
							$subject 	= str_replace("{{EXAM NAME}}",$model->name,$subject);											
							$message 	= str_replace("{{SCHOOL NAME}}",$college->config_value,$message);
							$message 	= str_replace("{{EXAM NAME}}",$model->name,$message);
							$message 	= str_replace("{{EXAM DATE}}",$model->exam_date,$message);
							
							if(FormFields::model()->isVisible('email','Students','forAdminRegistration')){
								UserModule::sendMail($student->email,$subject,$message);
							}						
						}	
						
						//message
						if($schedule_notification->msg_enabled == '1' and $schedule_notification->student == '1'){											
							$subject = $model->name.Yii::t('app',' is scheduled');
							$message = Yii::t('app','Hi, ').$model->name.Yii::t('app',' exam is scheduled on ').$model->exam_date;
							
							NotificationSettings::model()->sendMessage($student->uid,$subject,$message);						
						}	
					}
					if(Configurations::model()->isAndroidEnabled()){	
						Configurations::model()->devicenotice($argument_arr_1,"Exams","exams");
					}
				}	
				echo json_encode(array('success'=>true,'id'=>$model->primaryKey) );
				exit;
			} 
			else{	
				echo json_encode(array('success'=>false));
				exit;
			}
		}
	}

     public function actionAjax_delete(){
				$id=$_POST['id'];
				$deleted=$this->loadModel($id);
				$deleted_batch_id = $deleted->batch_id; // Saving the id of the batch that is going to be deleted.
				if ($deleted->delete()){
					
					//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
					ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'13',$deleted_batch_id,ucfirst($deleted->name),NULL,NULL,NULL); 
					
					// For SMS
					$notification = NotificationSettings::model()->findByAttributes(array('id'=>6));
					$college=Configurations::model()->findByPk(1);
					$to = '';
					if(($notification->sms_enabled=='1' or $notification->msg_enabled == '1' or $notification->mail_enabled == '1') and $deleted->is_published == '1'){ // Checking if SMS is enabled.						
						$students=Students::model()->findAll("batch_id=:x", array(':x'=>$deleted_batch_id)); //Selecting students of the batch
						foreach ($students as $student)
						{ 
							if($student->phone1){ // Checking if phone number is provided
								$to = $student->phone1;	
							}
							elseif($student->phone2){
								$to = $student->phone2;
							}
						//SMS								
							if($to!='' and $notification->sms_enabled=='1' and $notification->student=='1'){ // Sending SMS to each student								
								$from = $college->config_value;
								$template=SystemTemplates::model()->findByPk(13);
								$message = $template->template;
								$message = str_replace("<Exam Name>",$deleted->name,$message);
								
								SmsSettings::model()->sendSms($to,$from,$message);								
							}
						//Mail	
							if($notification->mail_enabled=='1' and $notification->student=='1')
							{								
								$template=EmailTemplates::model()->findByPk(10);
								$subject = $template->subject;
								$message = $template->template;												
								$subject = str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);
								$subject = str_replace("{{EXAM NAME}}",$deleted->name,$subject);								
								$message = str_replace("{{SCHOOL NAME}}",$college->config_value,$message);
								$message = str_replace("{{EXAM NAME}}",$deleted->name,$message); 
								if(FormFields::model()->isVisible('email','Students','forAdminRegistration')){								
									UserModule::sendMail($student->email,$subject,$message);
								}
							}							
						//Message	
							if($notification->msg_enabled=='1' and $notification->student=='1')
							{
								$subject = $deleted->name.Yii::t('app',' is cancelled');
								$message = Yii::t('app','Hi, ').$deleted->name.Yii::t('app',' exam is cancelled.');
								NotificationSettings::model()->sendMessage($student->uid,$subject,$message);
							}
						}
					}
					// End For SMS
					
				   
				   
				 // Delete Exam and exam score	
				 $exam=Exams::model()->findAllByAttributes(array('exam_group_id'=>$id));
				   //print_r($exam);	  
				 foreach ($exam as $exam1)
				 {  
				   $examscore=ExamScores::model()->findAllByAttributes(array('exam_id'=>$exam1->id));
				  
					 foreach($examscore as $examscore1)
					 {
					 $examscore1->delete();
					 }
					  
					  $exam1->delete();
				   }
				   // End Delete Exam and exam score		
				  echo json_encode(array('success'=>true));
				   exit;
				}
				else{
				echo json_encode(array('success'=>false));
				  exit;
				}
				
      }
	  
	  
	  public function actionBatchname()
		{			
			$data=Batches::model()->findAll('course_id=:id AND is_active=:x AND is_deleted=:y',array(':id'=>(int) $_POST['cid'],':x'=>1,':y'=>0));
			echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id")), true);
			$data=CHtml::listData($data,'id','name');
			foreach($data as $value=>$name)
			{
				echo CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);
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
						
				$data_list = CMap::mergeArray(array(0=>Yii::t('app','Batch without semester')),$data);
				
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
                
                public function actionBatches()
                {
                    $current_academic_yr = Configurations::model()->findByPk(35);
                    $year       =   $current_academic_yr->config_value;
                        if(isset($_POST['semester_id']) && $_POST['semester_id']!=NULL)
                        { 
						if($_POST['semester_id'] == 0){
							echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','Select')), true);
                            $criteria=new CDbCriteria;
                            // $criteria->join= 'JOIN semester_courses `sc` ON t.id = `sc`.semester_id';
                             $criteria->condition='course_id =:course_id AND is_deleted=0 AND is_active=1 AND academic_yr_id=:year';
                             $criteria->params=array(':course_id'=>$_POST['cid'],':year'=>$year);
                             $criteria->addCondition('semester_id IS NULL');
                             $data	= Batches::model()->findAll($criteria);
                             $data	= CHtml::listData($data, 'id', 'name');		
                             foreach($data as $value=>$name){
                                     echo CHtml::tag('option',array('value'=>$value),CHtml::encode(html_entity_decode($name)),true);
                             }
						}
						else{
								$course_id= $_POST['cid'];
								$data = Batches::model()->findAll('academic_yr_id=:x AND is_deleted=:y AND is_active=1 AND semester_id=:sem_id AND course_id=:course_id',array(':x'=>$year,':y'=>0,':sem_id'=>$_POST['semester_id'],':course_id'=>$course_id));				
								echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','Select')), true);
								 if(isset($_POST['status']) && $_POST['status']==1){
									 $data=CHtml::listData($data,'id','name');
								 }else{
									 $data=CHtml::listData($data,'id','coursename');
								 }
								foreach($data as $value=>$title)
								{
										echo CHtml::tag('option',array('value'=>$value),CHtml::encode(html_entity_decode($title)),true);
								}
							}
                        }
                        else if(isset($_POST['semester_id']) && $_POST['semester_id']=='')
                        {
                            echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','Select')), true);
                            $criteria=new CDbCriteria;
                            // $criteria->join= 'JOIN semester_courses `sc` ON t.id = `sc`.semester_id';
                             $criteria->condition='course_id =:course_id AND is_deleted=0 AND is_active=1 AND academic_yr_id=:year';
                             $criteria->params=array(':course_id'=>$_POST['cid'],':year'=>$year);
                             $criteria->addCondition('semester_id IS NULL');
                             $data	= Batches::model()->findAll($criteria);
                             $data	= CHtml::listData($data, 'id', 'name');		
                             foreach($data as $value=>$name){
                                     echo CHtml::tag('option',array('value'=>$value),CHtml::encode(html_entity_decode($name)),true);
                             }
                        }
                }
                
		public function actionSubjectname()
		{			
			$data=Subjects::model()->findAll('batch_id=:id AND is_deleted=:y',array(':id'=>(int) $_POST['batchid'],':y'=>0));
			echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','Select Subject')), true);
			$data=CHtml::listData($data,'id','name');
			foreach($data as $value=>$name)
			{
				echo CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);
			}
		}
		
		 /*?>public function actionExamgroup()
		{			
			$students=Students::model()->findAllByAttributes(array('batch_id'=>$_POST['batchid']));
			$exam=Exams::model()->findByAttributes(array('subject_id'=>$_POST['subjectid']));
			$score=ExamScores::model()->findByAttributes(array('exam_id'=>$exam->id));
			$exam_group_name=Examgroups::model()->findbyAttributes(array('id'=>$exam->exam_group_id));
			$this->render('gradebook');
			
		}<?php */
		
		public function actiongradebook()
		{
			$this->render('gradebook');
		}
		public function actionPrintpdf()
		{	
			$filename	= "report.pdf";
			Yii::app()->osPdf->generate("application.modules.examination.views.exam.printpdf", $filename, array(), 1);
		}
		public function actionExcelreport()
		{
			Yii::app()->request->sendFile('Grade Book Excel Report.xls',
			$this->renderPartial('excel',array(),true));
		}
                
                public function actionCbsc()
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
                        
                        $this->render('cbsc_gradebook',array(
                        'students'=>$students,
                        'pages' => $pages,
                        'item_count'=>$total,
                        'page_size'=>Yii::app()->params['listPerPage'],)) ;
                        
                    }
                    else
                    {
			$this->render('cbsc_gradebook');
                    }
		}
                
                public function actionResult()
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
                                $this->render('cbsc17',array('student'=>$id,
                                'list'=>$posts,
                                'pages' => $pages,
                                'item_count'=>$total,
                                'page_size'=>Yii::app()->params['listPerPage'],
                                ));                                
                             }

                        }
                    }
                                
                                
                }

                public function actionView()
                {
                    $this->render('cbsc_gradebook_view');
                }
                public function actionCbscpdf()
                {
                    $filename	= "report.pdf";
                    Yii::app()->osPdf->generate("application.modules.examination.views.exam.cbsc_gradebook_pdf", $filename, array(), NULL);
                }
	  
}
