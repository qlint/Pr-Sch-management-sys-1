<?php

class LogcommentController extends RController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
				'actions'=>array('index','view'),
				'users'=>array('*'),
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

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{		
		if(isset($_POST['LogComment']['id']) && $_POST['LogComment']['id']!= NULL){
			$comment			= LogComment::model()->findByAttributes(array('id'=>$_POST['LogComment']['id']));
			$comment->user_id	= $_POST['LogComment']['user_id'];
		}
		else{
			$comment			= new LogComment;
			$comment->user_id	= $_POST['LogComment']['student_id'];
		}
		$comment->user_type 	= 1;
		$comment->created_by	= Yii::app()->user->id;
		$comment->comment		= $_POST['LogComment']['comment'];
		$comment->category_id	= $_POST['LogComment']['category_id'];
		$comment->notice		= $_POST['LogComment']['notice'];
		$comment->visible_p		= 1;
		$comment->visible_t		= 1;
		$comment->visible_s		= 1;
		
		$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
		$timezone 	= Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
       	date_default_timezone_set($timezone->timezone);		
		$comment->date	= date('Y-m-d H:i:s');		
		if($comment->save()){			
			if($comment->notice){
				$uid			= Yii::app()->user->Id;
				$sender			= Profile::model()->findByAttributes(array('user_id'=>$uid));								
				$sender_name	= ucfirst($sender->firstname).' '.ucfirst($sender->lastname);
				$college		= Configurations::model()->findByPk(1);				
				$student 		= Students::model()->findByAttributes(array('id'=>$_POST['LogComment']['student_id']));
				$category 		= LogCategory::model()->findByAttributes(array('id'=>$_POST['LogComment']['category_id']));
				$teacher 		= Profile::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
				$to 			= '';
				$from 			= $college->config_value;
				$message 		= $_POST['LogComment']['comment'];
				$message_sms 	= $_POST['LogComment']['comment'];
				//Update message with template.......
				$template		= EmailTemplates::model()->findByPk(4);
				$subject 		= $template->subject;
				$subject 		= str_replace("{{STUDENT NAME}}",$student->getStudentname(),$subject);
				$subject 		= str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);
				$login_message 	= $template->template;
				$message 		= str_replace("{{COMMENT}}",$message,$login_message);
				$message 		= str_replace("{{STUDENT NAME}}",$student->getStudentname(),$message);
				$message 		= str_replace("{{CATEGORY}}",$category->name,$message);
				$message 		= str_replace("{{TEACHER}}",ucfirst($teacher->firstname).' '.ucfirst($teacher->lastname),$message);
				//Update sms with template.......
				$template_sms		= SystemTemplates::model()->findByPk(23);
				$message_template 	= $template_sms->template;
				$message_sms 		= str_replace("<LOG CONTENT>",$message_sms,$message_template);
				$message_sms 		= str_replace("<STUDENT NAME>",$student->getStudentname(),$message_sms);
				$message_sms 		= str_replace("<CATEGORY NAME>",$category->name,$message_sms);								
				$notification 		= NotificationSettings::model()->findByAttributes(array('id'=>11));
				if($student->parent_id){
					$parent1 = Guardians::model()->findByAttributes(array('id'=>$student->parent_id));
					if($notification->sms_enabled == '1' and $notification->parent_1 == '1'){ // Checking if SMS is enabled.
						if($parent1->mobile_phone){
							$to = $parent1->mobile_phone;	
						}						
						if($to!='' and $notification->sms_enabled == '1'){ // Send SMS if phone number is provided					
							SmsSettings::model()->sendSms($to,$from,$message_sms);							
						} // End send SMS
					} // End check if SMS is enabled
					if($notification->mail_enabled == '1' and $notification->parent_1 == '1'){
						UserModule::sendMail($parent1->email,$subject,$message);
					}
					if($notification->msg_enabled == '1' and $notification->parent_1 == '1'){
						$msg_subject = Yii::t('app', 'Log Notification of ').$student->getStudentname();
						$msg_message = Yii::t('app', 'Hi, A log is created for your child').', '.$student->getStudentname().'<br>'.' '.Yii::t('app', 'Log content :')." ".$_POST['LogComment']['comment'];
						NotificationSettings::model()->sendMessage($parent1->uid,$msg_subject,$msg_message);
					}
				}				
				if($student){
					//Update message with template.......
					$message1 		= $_POST['LogComment']['comment'];
					$template1 		= EmailTemplates::model()->findByPk(5);
					$subject1 		= $template1->subject;
					$subject1 		= str_replace("{{STUDENT NAME}}",$student->getStudentname(),$subject1);
					$subject1 		= str_replace("{{SCHOOL NAME}}",$college->config_value,$subject1);
					$login_message1 = $template1->template;
					$message1 		= str_replace("{{COMMENT}}",$message1,$login_message1);
					$message1 		= str_replace("{{STUDENT NAME}}",$student->getStudentname(),$message1);
					$message1 		= str_replace("{{CATEGORY}}",$category->name,$message1);
					$message1 		= str_replace("{{TEACHER}}",$teacher->firstname,$message1);
					
					//Update sms with template.......
					$message_sms1 		= $_POST['LogComment']['comment'];
					$template_sms1		= SystemTemplates::model()->findByPk(24);
					$message_template1 	= $template_sms1->template;
					$message_sms1 		= str_replace("<LOG CONTENT>",$message_sms1,$message_template1);
					$message_sms1 		= str_replace("<STUDENT NAME>",$student->getStudentname(),$message_sms1);
					$message_sms1 		= str_replace("<CATEGORY NAME>",$category->name,$message_sms1);
					
					if($notification->sms_enabled == '1' and $notification->student == '1'){ // Checking if SMS is enabled.
						if($student->phone1){
							$to = $student->phone1;	
						}
						else if($student->phone2){
							$to = $student->phone2;	
						}
						
						if($to!='' and $notification->sms_enabled == '1'){ // Send SMS if phone number is provided					
							SmsSettings::model()->sendSms($to,$from,$message_sms1);							
						} // End send SMS
					} // End check if SMS is enabled
					
					if($notification->mail_enabled == '1' and $notification->student == '1'){
						UserModule::sendMail($student->email,$subject1,$message1);
					}
				//Send internal msg to student	
					if($notification->msg_enabled == '1' and $notification->student == '1'){
						$msg_subject = Yii::t('app', 'Log Notification');
						$msg_message = Yii::t('app', 'Hi, A new log is created for you.').' <br>'.Yii::t('app', 'Log content :').' '.$_POST['LogComment']['comment'];
						NotificationSettings::model()->sendMessage($student->uid,$msg_subject,$msg_message);
					}
				}
				
				//Mobile Notifications
				if(Configurations::model()->isAndroidEnabled()){
					$teacher 		= Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
					$student		= Students::model()->findByPk($comment->user_id);
					$category		= LogCategory::model()->findByPk($comment->category_id);
					$batch			= Batches::model()->findByPk($_REQUEST['batch_id']);													
					//Admin Level Users
					$criteria				= new CDbCriteria();
					$criteria->join			= 'JOIN `authassignment` `t1` ON `t1`.`userid` = `t`.`uid`';					
					$criteria->condition	= '`t1`.`itemname`=:itemname';
					$criteria->params		= array(':itemname'=>'Admin');					
					$user_device 			= UserDevice::model()->findAll($criteria);	
					//Get Messages
					$push_notifications		= PushNotifications::model()->getNotificationDatas(14);
					foreach($user_device as $value){								
						//Get key value of the notification data array					
						$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
						
						$message	= $push_notifications[$key]['message'];
						$message	= str_replace("{Teacher Name}", $teacher->getFullname(), $message);
						$message	= str_replace("{Student Name}", $student->getStudentname(), $message);
						$message	= str_replace("{Category}", html_entity_decode(ucfirst($category->name)), $message);	
						
						$argument_arr = array('message' => $message, 'sender_name' => $teacher->getFullname(), 'device_id' => array($value->device_id), 'student_id'=>$comment->user_id, 'id'=>$comment->id);                 
						Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "logs");																		
					}
					//In case of tutor teacher, send notification to class teacher
					if($batch->employee_id != $teacher->id){   
						$criteria				= new CDbCriteria();
						$criteria->join			= 'JOIN `employees` `t1` ON `t1`.`uid` = `t`.`uid`';
						$criteria->condition	= '`t1`.`id`=:id';
						$criteria->params		= array(':id'=>$batch->employee_id);
						$user_device 			= UserDevice::model()->findAll($criteria);	
						if($user_device){
							foreach($user_device as $value){
								//Get key value of the notification data array					
								$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
								
								$message	= $push_notifications[$key]['message'];				
								$message	= str_replace("{Teacher Name}", $teacher->getFullname(), $message);
								$message	= str_replace("{Student Name}", $student->getStudentname(), $message);
								$message	= str_replace("{Category}", html_entity_decode(ucfirst($category->name)), $message);
								
								$argument_arr = array('message' => $message, 'sender_name' => $teacher->getFullname(), 'device_id' => array($value->device_id), 'student_id'=>$comment->user_id, 'id'=>$comment->id);                
								Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "logs");
							}						
						}
					}
					else{ //In case of class teacher, send notification to tutor teachers
						if($comment->notice){
							$criteria				= new CDbCriteria();
							$criteria->join			= 'JOIN `employees` `t1` ON `t1`.`uid` = `t`.`uid` JOIN `timetable_entries` `t2` ON `t2`.`employee_id` = `t1`.`id`';
							$criteria->condition	= '`t1`.`is_deleted`=:is_deleted AND `t2`.`batch_id`=:batch_id AND `t1`.`id`<>:id';
							$criteria->group		= '`t`.`device_id`';
							$criteria->params		= array(':is_deleted'=>0, ':batch_id'=>$batch->id, ':id'=>$teacher->id);
							$user_device 			= UserDevice::model()->findAll($criteria);	
							if($user_device){
								foreach($user_device as $value){
									//Get key value of the notification data array					
									$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
									
									$message	= $push_notifications[$key]['message'];				
									$message	= str_replace("{Teacher Name}", $teacher->getFullname(), $message);
									$message	= str_replace("{Student Name}", $student->getStudentname(), $message);
									$message	= str_replace("{Category}", html_entity_decode(ucfirst($category->name)), $message);
									
									$argument_arr = array('message' => $message, 'sender_name' => $teacher->getFullname(), 'device_id' => array($value->device_id), 'student_id'=>$comment->user_id, 'id'=>$comment->id);                
									Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "logs");
								}						
							}
						}
					}
										
					//Notification send to Student					
					$criteria				= new CDbCriteria();
					$criteria->condition	= 'uid=:uid';
					$criteria->params		= array(':uid'=>$student->uid);
					$criteria->group		= 'device_id';
					$user_device 			= UserDevice::model()->findAll($criteria);
					//Get Messages
					$push_notifications		= PushNotifications::model()->getNotificationDatas(12);
					foreach($user_device as $value){								
						//Get key value of the notification data array					
						$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
						
						$message		= $push_notifications[$key]['message'];
						$message		= str_replace("{Category}", html_entity_decode(ucfirst($category->name)), $message);						
						$argument_arr 	= array('message' => $message, 'sender_name' => $teacher->getFullname(), 'device_id' => array($value->device_id), 'student_id'=>$comment->user_id, 'id'=>$comment->id);               
						Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "logs");																		
					}		
					
					//Notification send to Parent
					$criteria 				= new CDbCriteria;		
					$criteria->join 		= 'JOIN guardians t1 ON t1.uid = t.uid JOIN guardian_list t2 ON t2.guardian_id = t1.id';
					$criteria->condition	= 't2.student_id=:student_id';
					$criteria->params		= array(':student_id'=>$student->id);
					$criteria->group		= '`t`.`device_id`';
					$user_device 			= UserDevice::model()->findAll($criteria);
					//Get Messages
					$push_notifications		= PushNotifications::model()->getNotificationDatas(13);
					foreach($user_device as $value){								
						//Get key value of the notification data array					
						$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
						
						$message		= $push_notifications[$key]['message'];
						$message		= str_replace("{Student Name}", $student->getStudentname(), $message);
						$message		= str_replace("{Category}", html_entity_decode(ucfirst($category->name)), $message);
						$argument_arr 	= array('message' => $message, 'sender_name' => $teacher->getFullname(), 'device_id' => array($value->device_id), 'student_id'=>$comment->user_id, 'id'=>$comment->id);               
						Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "logs");																		
					}					
				}								
			}						
			$id				= $comment->id;			
			$arr['status']	='success';
			$arr['content']	= $this->renderPartial('ajax_comment_submit', array('comment'=>$comment,'id'=>$id),true,true);
			echo json_encode($arr);
			Yii::app()->end();  
		}
		else
		{
				 $error = CActiveForm::validate($comment);
                                if($error!='[]'){
									$er_array	= array();
									$er_array	= json_decode($error, true);
									
									//extra errors here
									//$er_array['test_erro'][0]	= 'testing...';
									//extra errors here
									
									$error	= json_encode(array(
									'status'=>'error',
									'error'=>$er_array,
								));
                                   	echo $error;
									
									
									
								}
                                Yii::app()->end();  
		}	
		
		
						
		
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['LogComment']))
		{
			$model->attributes=$_POST['LogComment'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400, Yii::t('app', 'Invalid request. Please do not repeat this request again.'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('LogComment');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new LogComment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['LogComment']))
			$model->attributes=$_GET['LogComment'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=LogComment::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='log-comment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionEditcomment() 
	{
		
		$model=LogComment::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$id=$_REQUEST['id'];
		
		$this->renderPartial('ajax_comment_edit', array('model1'=>$model,'id'=>$id),false,true);
		
		
		
						
		
	}
	
	
	public function actionDeletecomment() 
	{
		$comment	= LogComment::model()->findByAttributes(array('id'=>$_REQUEST['id']));	
		$student	= Students::model()->findByPk($comment->user_id);	
		$category	= LogCategory::model()->findByPk($comment->category_id);
		if($comment->delete()){	
			//Mobile Push Notification
			if($comment->notice == 1){
				$college		= Configurations::model()->findByPk(1);			
				$teacher 		= Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));			
				//To Student					
				$criteria				= new CDbCriteria();
				$criteria->condition	= 'uid=:uid';
				$criteria->params		= array(':uid'=>$student->uid);
				$criteria->group		= '`t`.`device_id`';
				$user_device 			= UserDevice::model()->findAll($criteria);
				//Get Messages
				$push_notifications		= PushNotifications::model()->getNotificationDatas(15);
				foreach($user_device as $value){								
					//Get key value of the notification data array					
					$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
					
					$message	= $push_notifications[$key]['message'];
					$message	= str_replace("{Category}", html_entity_decode(ucfirst($category->name)), $message);	
					
					$argument_arr 	= array('message' => $message, 'sender_name' => $teacher->getFullname(), 'device_id' => array($value->device_id), 'student_id'=>$student->id, 'id'=>$comment->id, 'flag'=>'0');                
					Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "logs");																		
				}
				
				//To Parent
				$criteria 				= new CDbCriteria;		
				$criteria->join 		= 'JOIN guardians t1 ON t1.uid = t.uid JOIN guardian_list t2 ON t2.guardian_id = t1.id';
				$criteria->condition	= 't2.student_id=:student_id';
				$criteria->params		= array(':student_id'=>$student->id);
				$criteria->group		= '`t`.`device_id`';
				$user_device 			= UserDevice::model()->findAll($criteria);
				//Get Messages
				$push_notifications		= PushNotifications::model()->getNotificationDatas(16);
				foreach($user_device as $value){								
					//Get key value of the notification data array					
					$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
					
					$message	= $push_notifications[$key]['message'];
					$message	= str_replace("{Category}", html_entity_decode(ucfirst($category->name)), $message);
					$message	= str_replace("{Student Name}", $student->getStudentname(), $message);	
					
					$argument_arr 	= array('message' => $message, 'sender_name' => $teacher->getFullname(), 'device_id' => array($value->device_id), 'student_id'=>$student->id, 'id'=>$comment->id, 'flag'=>'0');            
					Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "logs");																		
				}
			}
				
			echo 'true';
		}
		else{
			echo 'false';
		}		
	}
}
