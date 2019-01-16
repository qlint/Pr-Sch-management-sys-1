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

class Exam17Controller extends RController
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
        
        public function actionIndex()
        {
            if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL)
            {                
                $batch_id = $_GET['id'];
                if($batch_id!="" && ExamFormat::model()->getExamformat($batch_id)== 1){
                    $model=new ExamGroups('search');
                    $model->unsetAttributes();  // clear any default values
                    if(isset($_GET['id']))
                            $model->batch_id=$_GET['id'];

                    $this->redirect(array('/examination/exam','id'=>$model->batch_id));
                }
                else
                {                    
                    $model=new CbscExamGroup17('search');
                    $model->unsetAttributes();  // clear any default values
                    if(isset($_GET['id']))
                            $model->batch_id=$_GET['id'];                     
                    $this->render('index',array('model'=>$model));
                }
            }
            else
                throw new CHttpException(404,Yii::t('app','Invalid Request.'));
        }
        
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

            $model=$this->loadModels($_POST['id']);
            $this->renderPartial('view_exam_group',array('model'=>$model),false, true);
        }
        public function loadModels($id)
	{
		$model=CbscExamGroup17::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
		return $model;
	}
      
        public function actionReturnForm(){
				
            //Figure out if we are updating a Model or creating a new one.
            if(isset($_POST['update_id'])){
                $model= $this->loadGroupModel($_POST['update_id']);
                $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                if($settings!=NULL){	
                        $model->created_at=date($settings->displaydate,strtotime($model->created_at));
                }
            }
            else $model=new CbscExamGroup17;            
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
      
        public function actionAjax_Create(){
        if(isset($_POST['CbscExamGroup17']))
        {
            $model=new CbscExamGroup17;           
            $model->attributes=$_POST['CbscExamGroup17'];
            $model->created_at=date('Y-m-d',strtotime($model->created_at));		
            //return the JSON result to provide feedback.
            if($model->save(false)){ 
                $subjects = Subjects::model()->findAllByAttributes(array('batch_id'=>$model->batch_id,'cbsc_common'=>1));                
                foreach($subjects as $subject){
                    $exam=new CbscExams;
                    $exam->exam_group_id=$model->id;
                    $exam->subject_id=$subject->id;
                    $exam->maximum_marks=5;
                    $exam->minimum_marks=0;
                    $exam->start_time=date('Y-m-d',strtotime(date('Y-m-d')));;
                    $exam->end_time=date('Y-m-d',strtotime(date('Y-m-d')));;
                    $exam->created_at=date('Y-m-d',strtotime(date('Y-m-d')));
                    $exam->save();
                }																				
                //Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
                ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'11',$model->id,ucfirst($model->name),NULL,NULL,NULL); 

                // Send SMS if saved
                $notification = NotificationSettings::model()->findByAttributes(array('id'=>6));
                $college=Configurations::model()->findByPk(1);
                $to = '';
                // Send SMS,mail,message only if, SMS or mail or message is enabled and schedule is published
                if($model->date_published=='1'){ 
                        $students=Students::model()->findAll("batch_id=:x and is_deleted=:y and is_active=:z", array(':x'=>$model->batch_id,':y'=>0,':z'=>1)); //Selecting students of the batch
                        foreach ($students as $student){ 
                                if($student->phone1){ // Checking if phone number is provided
                                        $to = $student->phone1;	
                                }
                                elseif($student->phone2){
                                        $to = $student->phone2;
                                }
                                $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                                if($settings!=NULL)
                                {	
                                        $model->created_at=date($settings->displaydate,strtotime($model->created_at));				
                                }
                        //SMS	
                                if($to!='' and $notification->sms_enabled=='1' and $notification->student == '1'){ // Sending SMS to each student											
                                        $from = $college->config_value;
                                        $template=SystemTemplates::model()->findByPk(8);
                                        $message = $template->template;
                                        $message = str_replace("<Exam Name>",$model->name,$message);

                                        SmsSettings::model()->sendSms($to,$from,$message);
                                }
                        //Mail
                                if($notification->mail_enabled == '1' and $notification->student == '1')
                                {
                                        $template=EmailTemplates::model()->findByPk(6);											
                                        $subject = $template->subject;
                                        $message = $template->template;
                                        $subject = str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);
                                        $subject = str_replace("{{EXAM NAME}}",$model->name,$subject);											
                                        $message = str_replace("{{SCHOOL NAME}}",$college->config_value,$message);
                                        $message = str_replace("{{EXAM NAME}}",$model->name,$message);
                                        $message = str_replace("{{EXAM DATE}}",$model->created_at,$message);

                                        UserModule::sendMail($student->email,$subject,$message);

                                }	
                        //message
                                if($schedule_notification->msg_enabled == '1' and $schedule_notification->student == '1')
                                {											
                                        $subject = $model->name.Yii::t('app',' is scheduled');
                                        $message = Yii::t('app','Hi, ').$model->name.Yii::t('app',' exam is scheduled on ').$model->created_at;
                                        NotificationSettings::model()->sendMessage($student->uid,$subject,$message);

                                }	
                        }
                }								
                echo json_encode(array('success'=>true,'id'=>$model->primaryKey) );
                exit;
            } else
            {	
                echo json_encode(array('success'=>false));
                exit;
            }
        }
  }
        public function actionAjax_Update(){
		if(isset($_POST['CbscExamGroup17'])){
           	$model = $this->loadGroupModel($_POST['update_id']);
			
			// For SMS
		   	$prev_name = $model->name;
		   	$prev_date_published = $model->date_published; // Fetching previous is_published
			$prev_result_published = $model->result_published; // Fetching previous result_published
			$prev_date = $model->created_at; //Fetching previous exam date
			// End For SMS
			
			// For Activity Feed
			$old_model	= $model->attributes; // For activity feed	
			$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
			if($settings!=NULL){	
				$old_date = date($settings->displaydate,strtotime($old_model['created_at']));			
			}
			// End For Activity Feed			
			
			$model->attributes	= $_POST['CbscExamGroup17'];
			$model->created_at	= date('Y-m-d',strtotime($model->created_at));
			if($model->save(false)){   		
				// Saving to activity feed
				$results = array_diff_assoc($model->attributes,$old_model); // To get the fields that are modified.
				
				foreach($results as $key => $value){
					if($key == 'name'){
						$value = ucfirst($value);
					}
					elseif($key == 'date_published'){
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
					elseif($key == 'created_at'){
						$value 				= $_POST['CbscExamGroup17']['created_at'];
						$old_model[$key] 	= $old_date;
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
					$model->created_at = date($settings->displaydate,strtotime($model->created_at));				
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
							if($prev_date_published=='0' and $model->date_published=='1' and $prev_result_published=='0' and $model->result_published=='0'){ 
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
									$mail_message 	= str_replace("{{EXAM DATE}}",$model->created_at,$mail_message);
									
									if(FormFields::model()->isVisible('email','Students','forAdminRegistration')){								
										UserModule::sendMail($student->email,$mail_subject,$mail_message);
									}
								}
								
								//Message
								if($notification->msg_enabled == '1' and $notification->student == '1'){
									$msg_subject = $model->name.Yii::t('app',' is scheduled');
									$msg_message = 'Hi, '.$model->name.Yii::t('app',' exam is scheduled on ').$model->created_at;
									
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
							elseif($prev_date_published=='1' and $model->date_published=='1' and $prev_result_published=='0' and $model->result_published=='0'){ 
								// If exam schedule already published and result is not published
								if(strcasecmp($prev_name, $model->name) == 0){ // Checking if exam name is changed and if not changed.
									if(strcasecmp($prev_date, $model->created_at) != 0){
										
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
											$mail_message 	= str_replace("{{EXAM DATE}}",$model->created_at,$mail_message);
											
											if(FormFields::model()->isVisible('email','Students','forAdminRegistration')){													
												UserModule::sendMail($student->email,$mail_subject,$mail_message);
											}
										}
										 
										//Message
										if($notification->msg_enabled == '1' and $notification->student == '1'){
											$msg_subject = $model->name.Yii::t('app',' is schedule is modified');
											$msg_message = Yii::t('app','Hi, ').$model->name.Yii::t('app',' exam is scheduled on ').$model->created_at;
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
									
									if(strcasecmp($prev_date, $model->date) != 0){ // if exam name is changed and date is also changed.
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
											$mail_message 	= str_replace("{{EXAM DATE}}",$model->created_at,$mail_message);
											
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
							if($model->date_published=='1' and $prev_result_published=='0' and $model->result_published=='1'){//If result is published								
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

        public function actionAjax_delete(){		 
		$id=$_POST['id'];
		$deleted=$this->loadGroupModel($id);
		
		$deleted_batch_id = $deleted->batch_id; // Saving the id of the batch that is going to be deleted.
		if ($deleted->delete()){ 	
		  echo json_encode(array('success'=>true));
		   exit;
		}
		else{
		echo json_encode(array('success'=>false));
		  exit;
		}
				
        }
        
        
        public function actionAjaxdeletes(){		 
		$id=$_POST['id'];
		$deleted=$this->loadGroupModel($id);
		
		$deleted_batch_id = $deleted->batch_id; // Saving the id of the batch that is going to be deleted.
		if ($deleted->delete()){ 	
		  echo json_encode(array('success'=>true));
		   exit;
		}
		else{
		echo json_encode(array('success'=>false));
		  exit;
		}
				
        }
	  
        public function actionCreate()
	{ 
		if($_REQUEST['exam_group_id'] != NULL and $_REQUEST['id'] != NULL){	
		
					
			$this->checkBatchActive($_REQUEST['id']);	
			$model		= new CbscExams17;
			$model_1	= new CbscExamGroup17;
			
			if(isset($_POST['CbscExams17'])){	 
					  			
				if(isset($_REQUEST['exam_group_id'])){
					$insert_id = $_REQUEST['exam_group_id'];
				}
				else{
					$model_1->attributes	= $_POST['CbscExamGroup17'];
					$model_1->batch_id 		= $_REQUEST['id']; 
					$model_1->save();
					$insert_id 				= Yii::app()->db->getLastInsertID();
				}
				$posts			= Subjects::model()->findAll("batch_id=:x AND no_exams=:y", array(':x'=>$_REQUEST['id'],':y'=>0));
				$list 			= $_POST['CbscExams17'];
				$count 			= count($list['subject_id']);
				$electivecount 	= count($_POST['ElectiveExams']['elective_id']);			
				$j				= 0;
				for($i=1;$i<=$count;$i++){				
					if($list['maximum_marks'][$i]!=NULL || $list['minimum_marks'][$i]!=NULL || $list['start_time'][$i]!=NULL || $list['end_time'][$i]!=NULL){	
						$model					= new CbscExams17;
						$model->exam_group_id 	= $insert_id; 
						$model->subject_id 		= $list['subject_id'][$i];
						$model->maximum_marks 	= $list['maximum_marks'][$i];
						$model->minimum_marks 	= $list['minimum_marks'][$i];
						$model->start_time 		= $list['start_time'][$i];
						$model->end_time 		= $list['end_time'][$i];
						if($model->start_time){
							$date1				= date('Y-m-d H:i',strtotime($model->start_time));
							$model->start_time	= $date1;
						}					
						if($model->end_time){
							$date2				= date('Y-m-d H:i',strtotime($model->end_time));
							$model->end_time	= $date2;
						}
						$model->created_at 			= $list['created_at'];
						$model->updated_at 			= $list['updated_at'];
						if(!$model->validate()){					   
							//get error from particular model
							foreach($model->getErrors() as $attribute=>$error){
								$key				= "CbscExams17_".$attribute."_".$i;							
								$errors[$key][$i]	= $error[0];
							}
						}										
					}
				}
							
				for($i=1;$i<=$electivecount;$i++){			
					if($_POST['ElectiveExams']['maximum_marks'][$i]!=NULL || $_POST['ElectiveExams']['minimum_marks'][$i]!=NULL){	
						$electives 					= new ElectiveExams;
						$electives->exam_group_id 	= $insert_id; 
						$electives->elective_id 	= $_POST['ElectiveExams']['elective_id'][$i];
						$electives->maximum_marks 	= $_POST['ElectiveExams']['maximum_marks'][$i];
						$electives->minimum_marks 	= $_POST['ElectiveExams']['minimum_marks'][$i];
						$electives->start_time 		= $_POST['ElectiveExams']['start_time'][$i];
						$electives->end_time 		= $_POST['ElectiveExams']['end_time'][$i];
						if($electives->start_time){
							$date1					= date('Y-m-d H:i',strtotime($electives->start_time));
							$electives->start_time	= $date1;
						}
		
						if($electives->end_time){
							$date2					= date('Y-m-d H:i',strtotime($electives->end_time));
							$electives->end_time	= $date2;
						}
						$electives->created_at 			= $list['created_at'];
						$electives->updated_at 			= $list['updated_at'];
						if(!$electives->validate()){
							//get error from particular model
							foreach($electives->getErrors() as $attribute=>$error){
								$key		= "ElectiveExams_".$attribute."_".$i;							
								$errors[$key][$i]	= $error[0];
							}
						}	
					}
				}
				if(count($errors)>0){ 
					echo CJSON::encode(array('status'=>'error', 'errors'=>$errors));			
					exit;
				}
				else{
					for($i=1;$i<=$count;$i++){				
						if($list['maximum_marks'][$i]!=NULL and $list['minimum_marks'][$i]!=NULL){	
							$model					= new CbscExams17;
							$model->exam_group_id 	= $insert_id; 
							$model->subject_id 		= $list['subject_id'][$i];
							$model->maximum_marks 	= $list['maximum_marks'][$i];
							$model->minimum_marks 	= $list['minimum_marks'][$i];
							$model->start_time 		= $list['start_time'][$i];
							$model->end_time 		= $list['end_time'][$i];
							if($model->start_time){
								$date1				= date('Y-m-d H:i',strtotime($model->start_time));
								$model->start_time	= $date1;
							}
							
							if($model->end_time){
								$date2				= date('Y-m-d H:i',strtotime($model->end_time));
								$model->end_time	= $date2;
							}
							$model->created_at 			= $list['created_at'];
							$model->updated_at 			= $list['updated_at'];
							
							if($model->save()){															
								$subject_name 	= Subjects::model()->findByAttributes(array('id'=>$model->subject_id));
								$examgroup 		= CbscExamGroups::model()->findByAttributes(array('id'=>$model->exam_group_id));
								$batch 			= Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
								$exam 			= ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
								
								//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
								ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'17',$model->id,$exam,NULL,NULL,NULL); 													
							}						
						}
					}
					
					for($i=1;$i<=$electivecount;$i++){
				
						if($_POST['ElectiveExams']['maximum_marks'][$i]!=NULL and $_POST['ElectiveExams']['minimum_marks'][$i]!=NULL){	
							$electives 					= new ElectiveExams;
							$electives->exam_group_id 	= $insert_id; 
							$electives->elective_id 	= $_POST['ElectiveExams']['elective_id'][$i];
							$electives->maximum_marks 	= $_POST['ElectiveExams']['maximum_marks'][$i];
							$electives->minimum_marks 	= $_POST['ElectiveExams']['minimum_marks'][$i];
							$electives->start_time 		= $_POST['ElectiveExams']['start_time'][$i];
							$electives->end_time 		= $_POST['ElectiveExams']['end_time'][$i];
							if($electives->start_time){
								$date1					= date('Y-m-d H:i',strtotime($electives->start_time));
								$electives->start_time	= $date1;
							}
							
							if($electives->end_time){
								$date2					= date('Y-m-d H:i',strtotime($electives->end_time));
								$electives->end_time	= $date2;
							}
							
							$electives->created_at 			= $list['created_at'];
							$electives->updated_at			= $list['updated_at'];						
							$electives->save();						
						}
					}
					echo CJSON::encode(array('status'=>'success'));
					exit;
				}                                                                                                                                                                        										
			}
			$this->render('create',array(
				'model'=>$model,'model_1'=>$model_1,'electives'=>$electives,'electivegroups'=>$electivegroups
			));
		}
		else{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
	}
	
	public function actionUpdate($sid)
	{
		$model=$this->loadModel($sid);
		$old_model = $model->attributes; // For activity feed	
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
		if($settings!=NULL)
		{
			if($model->start_time!='0000-00-00 00:00:00'){	
				$model->start_time=date($settings->displaydate.' '.$settings->timeformat,strtotime($model->start_time));
			}
			if($model->end_time!='0000-00-00 00:00:00'){
				$model->end_time=date($settings->displaydate.' '.$settings->timeformat,strtotime($model->end_time));
			}
			$old_start_time = date($settings->displaydate.' '.$settings->timeformat,strtotime($old_model['start_time']));	// For activity feed
			$old_end_time = date($settings->displaydate.' '.$settings->timeformat,strtotime($old_model['end_time']));	// For activity feed
		}

		if(isset($_POST['CbscExams17']))
		{
			$model->attributes=$_POST['CbscExams17'];
			
			$list = $_POST['CbscExams17'];
			if($model->start_time[0]!="")
			{
				$date1 = date('Y-m-d H:i',strtotime($list['start_time'][0]));
				$model->start_time = $date1; // To save
				$activity_start = date($settings->displaydate.' '.$settings->timeformat,strtotime($model->start_time)); // For activity feed
				
			}else{
				$model->start_time="";
			}
			
			if($model->end_time[0]!="")
			{
				$date2=date('Y-m-d H:i',strtotime($list['end_time'][0]));
				$model->end_time=$date2; // To save
				$activity_end = date($settings->displaydate.' '.$settings->timeformat,strtotime($model->end_time)); // For activity feed
			}else{
				$model->end_time="";
			}
			$results = array_diff_assoc($model->attributes,$old_model);
			$model->maximum_marks=$_POST['CbscExams17']['maximum_marks'];
			$model->minimum_marks=$_POST['CbscExams17']['minimum_marks'];
			
			if($model->save())
			{ 
				
				// Saving to activity feed
				$results = array_diff_assoc($model->attributes,$old_model); // To get the fields that are modified. 
				
				foreach($results as $key => $value)
				{
					echo $key;
					if($key!='updated_at')
					{
						if($key == 'start_time')
						{
							$value = $activity_start;
							$old_model[$key] = $old_start_time;//echo '</br/>-'.$old_model[$key].' to '.$value.'<br/><br/>';
						}
						elseif($key == 'end_time')
						{
							$value = $activity_end;
							$old_model[$key] = $old_end_time;
						}
						
						$subject_name = Subjects::model()->findByAttributes(array('id'=>$model->subject_id));
						$examgroup = CbscExamGroup17::model()->findByAttributes(array('id'=>$model->exam_group_id));
						$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
						$exam = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
						
						//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
						ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'18',$model->id,$exam,$model->getAttributeLabel($key),$old_model[$key],$value); 
						
						 
					}
				}	
				//END saving to activity feed
			
				
				$this->redirect(array('exam17/create','id'=>$_REQUEST['id'],'exam_group_id'=>$_REQUEST['exam_group_id']));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			$model = CbscExams17::model()->findByAttributes(array('id'=>$id));
			$subject_name = Subjects::model()->findByAttributes(array('id'=>$model->subject_id));
			$examgroup = CbscExamGroup17::model()->findByAttributes(array('id'=>$model->exam_group_id));
			$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
			$exam = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();
			
			//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
			ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'19',$model->id,$exam,NULL,NULL,NULL); 

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
			Yii::app()->user->setFlash('successMessage', Yii::t('app'," Selected Exam Group Deleted Successfully"));
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,Yii::t('app','Invalid request. Please do not repeat this request again.'));
	}
	public function actionAdmin()
	{
		$model=new Exams('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Exams']))
			$model->attributes=$_GET['Exams'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	public function actionDeleteallSplit()
	{
		if(Yii::app()->request->isPostRequest){
			$delete = CbscExamScores17::model()->findAllByAttributes(array('exam_id'=>$_REQUEST['examid']));
			foreach($delete as $delete1)
			{
				$id	=	$delete1->id;
				if($delete1->delete()){
					$subject_cps	=	CbscExamScoresSplit17::model()->findAllByAttributes(array('exam_scores_id'=>$id));
					foreach($subject_cps as $subject_cp){ 
						$subject_cp->delete();
					}
				}
			}
			
			
			$this->redirect(array('examScoresSplit','id'=>$_REQUEST['id'],'examid'=>$_REQUEST['examid']));
		}
		else
		{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
	}
	public function actionDeleteSplit(){
		
		if(Yii::app()->request->isPostRequest)
		{ 
			$id	=	$_REQUEST['id'];
			$model = CbscExamScores17::model()->findByAttributes(array('id'=>$id));
			
			$student = Students::model()->findByAttributes(array('id'=>$model->student_id));
			$student_name = ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);
			
			$exam = CbscExams17::model()->findByAttributes(array('id'=>$model->exam_id));
			$subject_name = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
			$examgroup = CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
			$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
			$exam_name = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
			$goal_name = $student_name.Yii::t('app',' for the CBSC exam ').$exam_name;
			// we only allow deletion via POST request
			if($this->loadscoreModel($id)->delete()){
				$subject_cps	=	CbscExamScoresSplit17::model()->findAllByAttributes(array('exam_scores_id'=>$id));
				foreach($subject_cps as $subject_cp){ 
					$subject_cp->delete();
				}
			}
			
			//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
			ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'22',$model->id,$goal_name,NULL,NULL,NULL); 

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('examScoresSplit'));
		}
		else
			throw new CHttpException(400,Yii::t('app','Invalid request. Please do not repeat this request again.'));
	}
	public function actionUpdateSplit(){
		$model	=	CbscExamScores17::model()->findByPk($_REQUEST['sid']);
		$subject_cps	=	CbscExamScoresSplit17::model()->findAllByAttributes(array('exam_scores_id'=>$model->id));
		//split subject
		$exm 			= 	CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
		if($exm!=NULL)
		{
			$sub = Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
		}
		$subject_splits	=	SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$sub->id)); 
		$subject_array	=	array();
		foreach($subject_splits as $subject_split){
			$subject_array[]=$subject_split->split_name;
		}
		if(count($subject_cps) !=0){
			$k=1;
			foreach($subject_cps as $subject_cp)
			{
				$att			=	'sub_category'.$k;
				$k++; 
				$model->$att	=	$subject_cp->mark;
			}
		} 
		$old_model = $model->attributes; // For activity feed	
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CbscExamScores17']))
		{
			$model->attributes=$_POST['CbscExamScores17'];
			$exam = CbscExams17::model()->findByAttributes(array('id'=>$model->exam_id));
			if(($model->total)< ($exam->minimum_marks)) 
			{
				$model->is_failed = 1;
			}
			else
			{
				$model->is_failed = 0;
			}
			
			
			
			if($model->save())
			{
				$sub1	=	$_POST['CbscExamScores17']['sub_category1'];
				$sub2	=	$_POST['CbscExamScores17']['sub_category2'];	
				$split	=	array('0'=>$sub1,'1'=>$sub2);
				$subject_cps	=	CbscExamScoresSplit17::model()->findAllByAttributes(array('exam_scores_id'=>$model->id));
				$l=0;
				foreach($subject_cps as $subject_cp){
					$subject_cp->mark	=	$split[$l];
					$subject_cp->save();
					$l++;
				}
				// Saving to activity feed
				$results = array_diff_assoc($model->attributes,$old_model); // To get the fields that are modified. 
				foreach($results as $key => $value)
				{
					if($key!='updated_at')
					{
						$student = Students::model()->findByAttributes(array('id'=>$model->student_id));
						$student_name = ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);
						
						$subject_name = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
						$examgroup = CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
						$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
						$exam_name = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
						$goal_name = $student_name.Yii::t('app',' for the CBSC exam ').$exam_name;
						
						if($key=='is_failed')
						{
							if($value == 1)
							{
								$value = Yii::t('app','Fail');
							}
							else
							{
								$value = Yii::t('app','Pass');
							}
							
							if($old_model[$key] == 1)
							{
								$old_model[$key] = Yii::t('app','Fail');
							}
							else
							{
								$old_model[$key] = Yii::t('app','Pass');
							}
						}
						
						
						
						//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
						ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'21',$model->id,$goal_name,$model->getAttributeLabel($key),$old_model[$key],$value); 
					}
				}
				//END saving to activity feed
				
				$this->redirect(array('examScoresSplit','id'=>$_REQUEST['id'],'examid'=>$_REQUEST['examid']));
			}
		}

		$this->render('update_split',array(
			'model'=>$model,
		));
	}
	public function actionExamScoresSplit()
	{
		
		if($_REQUEST['examid'] != NULL and $_REQUEST['id'] != NULL)
		{                    
			$this->checkBatchActive($_REQUEST['id']);		
			$model=new CbscExamScores17;
			 if(isset($_POST['CbscExamScores17']))
			{              			                        
				$list = $_POST['CbscExamScores17'];
				$count = count($list['student_id']); 
				
				for($i=0;$i<$count;$i++)
				{
					if($list['remarks'][$i]!=NULL or $list['sub_category1'][$i]!=NULL or $list['sub_category2'][$i]!=NULL  or $list['total'][$i]!=NULL)
					{ 
						$exam					=	CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
                        $sub 					= 	Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
						$model					=	new CbscExamScores17;
						$model->exam_id 		= 	$list['exam_id']; 
						$model->student_id 		= 	$list['student_id'][$i];
						$model->sub_category1 	= 	$list['sub_category1'][$i];
						$model->sub_category2 	= 	$list['sub_category2'][$i];
						$split					=	array('0'=>$model->sub_category1,'1'=>$model->sub_category2);
						$model->total 			= 	$list['total'][$i];
						$model->remarks 		= 	$list['remarks'][$i]; 
						$exam_group 			= 	CbscExams17::model()->findByPk($_REQUEST['examid']);
						$is_grade 				= 	CbscExamGroup17::model()->findByPk($exam_group->exam_group_id); 
						 

						if(($list['total'][$i])< ($exam->minimum_marks)) 
						{

								$model->is_failed = 1;
						}
						else 
						{
								$model->is_failed = 0;
						}
						$model->created_at = $list['created_at'];
						$model->updated_at = $list['updated_at'];
						
						$student_data = CbscExamScores17::model()->findByAttributes(array('student_id'=>$model->student_id,'exam_id'=>$list['exam_id']));
						if($student_data==NULL)
						{
							//$model->save();
							if(!$model->validate()){ 
								//get error from particular model 
								foreach($model->getErrors() as $attribute=>$error){
									$key		= "CbscExamScores17_".$attribute."_".$i;							
									$errors[$key][$i]	= $error[0]; 
								}
							}
							
						}
					}
				}
				if(count($errors)>0){ 
					echo CJSON::encode(array('status'=>'error', 'errors'=>$errors));			
					exit;
				}
				else
				{
					for($i=0;$i<$count;$i++)
					{
						if($list['remarks'][$i]!=NULL or $list['sub_category1'][$i]!=NULL or $list['sub_category1'][$i]!=NULL  or $list['total'][$i]!=NULL)
						{ 
							$exam=CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
							$sub = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id)); 
							
							$model					=	new CbscExamScores17;
							$model->exam_id 		= 	$list['exam_id']; 
							$model->student_id		= 	$list['student_id'][$i];
							$model->sub_category1 	= 	$list['sub_category1'][$i];
							$model->sub_category2 	= 	$list['sub_category2'][$i];
							$split					=	array('0'=>$model->sub_category1,'1'=>$model->sub_category2);
							$model->total 			= 	$list['total'][$i];
							$model->remarks 		= 	$list['remarks'][$i];
							//$model->grading_level_id = $list['grading_level_id'];
							$exam_group = CbscExams17::model()->findByPk($_REQUEST['examid']);
							$is_grade 	= CbscExamGroup17::model()->findByPk($exam_group->exam_group_id);
							
							if(($list['total'][$i])< ($exam->minimum_marks)) 
							{
								$model->is_failed = 1;
							}
							else 
							{
								$model->is_failed = 0;
							}
							$model->created_at = $list['created_at'];
							$model->updated_at = $list['updated_at'];
							
							$student_data = ExamScores::model()->findByAttributes(array('student_id'=>$model->student_id,'exam_id'=>$list['exam_id']));
							if($student_data==NULL)
							{ 
								if($model->save())
								{
									if($sub->split_subject == 1){
										for($k=0;$k<2;$k++){
											$exam_score_split					=	new CbscExamScoresSplit17;
											$exam_score_split->student_id		=	$model->student_id;
											$exam_score_split->exam_scores_id	=	$model->id;
											$exam_score_split->mark				=	$split[$k];
											$exam_score_split->save();
										}
									}
								$student = Students::model()->findByAttributes(array('id'=>$model->student_id));
								$student_name = ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);
								
								$subject_name 	= Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
								$examgroup 		= CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
								$batch 			= Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
								$exam 			= ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
								$goal_name		= $student_name.Yii::t('app',' for the CBSC exam ').$exam;
								
								
									//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
									ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'20',$model->id,$goal_name,NULL,NULL,NULL); 
								}
							}
						}
					}
					echo CJSON::encode(array('status'=>'success'));
					exit;
				}
			} 
			$this->render('create_split',array( 'model'=>$model));
		}
	}
	public function actionExamScores()
	{
		if($_REQUEST['examid'] != NULL and $_REQUEST['id'] != NULL)
		{    
			$exam	= CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
			$exam_g	= CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
			if($exam_g->class == 4){ 
				$this->redirect(array('examScoresSplit','examid'=>$_REQUEST['examid'],'id'=>$_REQUEST['id']));
			}else{
				$this->checkBatchActive($_REQUEST['id']);		
				$model=new CbscExamScores17;	
				if(isset($_POST['CbscExamScores17']))
				{    
					$list = $_POST['CbscExamScores17'];
					$count = count($list['student_id']);
					for($i=0;$i<$count;$i++)
					{
						if($list['written_exam'][$i]!=NULL or $list['periodic_test'][$i]!=NULL or $list['note_book'][$i]!=NULL or $list['subject_enrichment	'][$i]!=NULL)
						{
							$exam				 = CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
							$sub				 = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
							$model						= new CbscExamScores17;
							$model->exam_id 			= $list['exam_id']; 
							$model->student_id   		= $list['student_id'][$i];
							$model->written_exam 		= $list['written_exam'][$i];
							$model->periodic_test 		= $list['periodic_test'][$i];
							$model->note_book 			= $list['note_book'][$i];
							$model->subject_enrichment	= $list['subject_enrichment'][$i]; 
							$model->total 				= $list['total'][$i];
							$model->remarks		 		= htmlspecialchars_decode($list['remarks'][$i]); 
							
							$exam_group = CbscExams17::model()->findByPk($_REQUEST['examid']);
							$is_grade = CbscExamGroup17::model()->findByPk($exam_group->exam_group_id);
	
							if(($list['marks'][$i])< ($exam->minimum_marks)) 
							{
	
									$model->is_failed = 1;
							}
							else 
							{
									$model->is_failed = 0;
							}
							$model->created_at = $list['created_at'];
							$model->updated_at = $list['updated_at'];
							
							$student_data = CbscExamScores17::model()->findByAttributes(array('student_id'=>$model->student_id,'exam_id'=>$list['exam_id']));
	
							if($student_data==NULL)
							{
								if(!$model->validate()){
									//get error from particular model
									foreach($model->getErrors() as $attribute=>$error){
										$key		= "CbscExamScores17_".$attribute."_".$i;							
										$errors[$key][$i]	= $error[0];
									}
								}
							}
						}
					}     
						 
					if(count($errors)>0){ 
						echo CJSON::encode(array('status'=>'error', 'errors'=>$errors));			
						exit;
					}else{                        
						for($i=0;$i<$count;$i++)
						{
							if($list['written_exam'][$i]!=NULL or $list['periodic_test'][$i]!=NULL or $list['note_book'][$i]!=NULL or $list['subject_enrichment	'][$i]!=NULL)
							{
								
								$exam	= CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
								$sub 	= Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
								$model				 		= new CbscExamScores17;
								$model->exam_id 	 		= $list['exam_id']; 
								$model->student_id   		= $list['student_id'][$i];
								$model->written_exam 		= number_format($list['written_exam'][$i],2);
								$model->periodic_test		= number_format($list['periodic_test'][$i],2);
								$model->note_book 			= number_format($list['note_book'][$i],2);
								$model->subject_enrichment 	= number_format($list['subject_enrichment'][$i],2);
								$model->total 				= $list['total'][$i];
								$model->remarks		 		= htmlspecialchars_decode($list['remarks'][$i]);
								$exam_group 		 		= CbscExams17::model()->findByPk($_REQUEST['examid']);
							  
								if(($list['total'][$i])< ($exam->minimum_marks)) 
								{
									$model->is_failed = 1;
								}
								else 
								{
										$model->is_failed = 0;
								} 
								$model->created_at = $list['created_at'];
								$model->updated_at = $list['updated_at'];
								$student_data = CbscExamScores17::model()->findByAttributes(array('student_id'=>$model->student_id,'exam_id'=>$list['exam_id']));
	
								if($student_data==NULL)
								{
									if($model->save())
									{ 
											$student 		= Students::model()->findByAttributes(array('id'=>$model->student_id));
											$student_name  	= ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);
											$subject_name 	= Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
											$examgroup 		= CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
											$batch 			= Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
											$exam 			= ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
											$goal_name 		= $student_name.Yii::t('app',' for the Cbsc exam ').$exam; 
											ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'20',$model->id,$goal_name,NULL,NULL,NULL); 
									} 
								 }
							}
						}
						echo CJSON::encode(array('status'=>'success'));
						exit;
					}        
				}
				//var_dump($model);exit;
				$this->render('exam_scores',array(
				'model'=>$model,
			  ));
			}
		}
		else{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
	}
	public function actionexamScoresDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			$model = CbscExamScores17::model()->findByPk($id);
			$model->delete(); 
			//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
			ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'22',$model->id,$goal_name,NULL,NULL,NULL); 

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,Yii::t('app','Invalid request. Please do not repeat this request again.'));
	}
	public function actionExamScoresUpdate($sid)
	{
		
		$model=CbscExamScores17::model()->findByPk($sid);
		$old_model = $model->attributes; // For activity feed	
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CbscExamScores17']))
		{
			$model->attributes = $_POST['CbscExamScores17'];
			$model->remarks    = htmlspecialchars_decode($_POST['CbscExamScores17']['remarks']);
			$exam = CbscExams17::model()->findByAttributes(array('id'=>$model->exam_id));
			if(($model->total)< ($exam->minimum_marks)) 
			{
				$model->is_failed = 1;
			}
			else
			{
				$model->is_failed = 0;
			}			
			if($model->save())
			{ 
				// Saving to activity feed
				$results = array_diff_assoc($model->attributes,$old_model); // To get the fields that are modified. 
				foreach($results as $key => $value)
				{
					if($key!='updated_at')
					{
						$student = Students::model()->findByAttributes(array('id'=>$model->student_id));
						$student_name = ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);
						
						$subject_name = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
						$examgroup = CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
						$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
						$exam_name = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
						$goal_name = $student_name.Yii::t('app',' for the CBSC exam ').$exam_name;
						
						if($key=='is_failed')
						{
							if($value == 1)
							{
								$value = Yii::t('app','Fail');
							}
							else
							{
								$value = Yii::t('app','Pass');
							}
							
							if($old_model[$key] == 1)
							{
								$old_model[$key] = Yii::t('app','Fail');
							}
							else
							{
								$old_model[$key] = Yii::t('app','Pass');
							}
						}
						
						//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
						ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'21',$model->id,$goal_name,$model->getAttributeLabel($key),$old_model[$key],$value); 
					}
				}
				//END saving to activity feed
				
				$this->redirect(array('examScores','id'=>$_REQUEST['id'],'examid'=>$_REQUEST['examid']));
			} 
		}

		$this->render('exam_scores_update',array(
			'model'=>$model,
		));
	}
	public function actionDeleteall()
	{
		if(Yii::app()->request->isPostRequest){ 
		$delete = CbscExamScores17::model()->findAllByAttributes(array('exam_id'=>$_REQUEST['examid']));
			foreach($delete as $delete1)
			{
				$delete1->delete();
			}
			$this->redirect(array('examScores','id'=>$_REQUEST['id'],'examid'=>$_REQUEST['examid']));
		}
		else
		{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
	}
	public function loadscoreModel($id)
	{
		$model=CbscExamScores17::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
		return $model;
	}
	public function loadsplitModel($id)
	{
		$model=CbscExamScoresSplit17::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
		return $model;
	}
	public function loadModel($id)
	{
		$model=CbscExams17::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
		return $model;
	}
	public function loadGroupModel($id)
	{
		$model=CbscExamGroup17::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
		return $model;
	}
	public function checkBatchActive($id) //Check whether the batch is active
	{
		$batch = Batches::model()->findByAttributes(array('id'=>$id, 'is_active'=>1, 'is_deleted'=>0));
		if($batch == NULL){
			$this->redirect(array('/examination'));
		}		
	}
	public function actionPrintpdf()
	{	
		$filename	= "report.pdf";
		//Yii::app()->osPdf->generate("application.modules.CBSCExam.views.exam17.printpdf", $filename, array(),0);
	}
	public function actionClass3to8pdf()
	{	
		$filename	= "report.pdf";
		Yii::app()->osPdf->generate("application.modules.CBSCExam.views.exam17.class3to8pdf", $filename, array(),0);
	}
	public function actionClass9to10pdf()
	{	
		$filename	= "report.pdf";
		Yii::app()->osPdf->generate("application.modules.CBSCExam.views.exam17.class9to10pdf", $filename, array(),0);
	}
	public function actionClass11to12pdf()
	{	
		$filename	= "report.pdf";
		Yii::app()->osPdf->generate("application.modules.CBSCExam.views.exam17.class11to12pdf", $filename, array(),0);
	}
	public function actionPdf()
    {
		
		$batch_name 	= Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$exam 			= CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
		$examgroup	 	= CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
		$subject 		= Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
		$filename 		= $batch_name->name.' '.$examgroup->name.' '.$subject->name.'.pdf';
		Yii::app()->osPdf->generate("application.modules.CBSCExam.views.exam17.scorepdf", $filename, array(), 1); 
 
	}
        
	public function actionPublishDate()
	{	
		if($_REQUEST['exam_group_id']!=NULL and $_REQUEST['id']!=NULL){			
			$exam_group = CbscExamGroup17::model()->findByPk($_REQUEST['exam_group_id']);
			if($exam_group->saveAttributes(array('date_published'=>'1'))){
				//Mobile Push Notification
				if(Configurations::model()->isAndroidEnabled()){
					$batch			= Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
					$sender_name	= PushNotifications::model()->getUserName(Yii::app()->user->id);
					$students		= Yii::app()->getModule('students')->studentsOfBatch($batch->id);
					if($students){
						//Get Messages
						$student_push_notifications	= PushNotifications::model()->getNotificationDatas(26);	
						$parent_push_notifications	= PushNotifications::model()->getNotificationDatas(27);	
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
										$message	= str_replace("{Class Name}",  CbscExamGroup17::model()->getClassName($exam_group->class), $message);
										$message	= str_replace("{Batch Name}", html_entity_decode(ucfirst($batch->name)), $message);
										
										$argument_arr = array('message'=>$message, 'device_id'=>array($student_device->device_id), 'sender_name'=>$sender_name, 'batch_id'=>$batch->id, 'exam_group_id'=>$exam_group->id, 'flag'=>1);               
										Configurations::model()->devicenotice($argument_arr, $student_push_notifications[$key]['title'], "cbse_exam");																			
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
										$message	= str_replace("{Class Name}",  CbscExamGroup17::model()->getClassName($exam_group->class), $message);
										$message	= str_replace("{Batch Name}", html_entity_decode(ucfirst($batch->name)), $message);
										$message	= str_replace("{Student Name}", $student->getStudentname(), $message);
										
										$argument_arr = array('message'=>$message, 'device_id'=>array($guardian_device->device_id), 'sender_name'=>$sender_name, 'batch_id'=>$batch->id, 'exam_group_id'=>$exam_group->id, 'student_id'=>$student->id, 'flag'=>1);               
										Configurations::model()->devicenotice($argument_arr, $parent_push_notifications[$key]['title'], "cbse_exam");	
									}
								}							
							}
						}
					}
				}
			}
			$this->redirect(Yii::app()->request->urlReferrer);		
		}
	}
	
     public function actionPublishResult()
	{
		
		if($_REQUEST['exam_group_id']!=NULL and $_REQUEST['id']!=NULL)
		{
			$exam_group = CbscExamGroup17::model()->findByPk($_REQUEST['exam_group_id']);
			if($exam_group->saveAttributes(array('result_published'=>'1'))){
				//Mobile Push Notification
				if(Configurations::model()->isAndroidEnabled()){
					$batch			= Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
					$sender_name	= PushNotifications::model()->getUserName(Yii::app()->user->id);
					$students		= Yii::app()->getModule('students')->studentsOfBatch($batch->id);
					if($students){
						//Get Messages
						$student_push_notifications	= PushNotifications::model()->getNotificationDatas(30);	
						$parent_push_notifications	= PushNotifications::model()->getNotificationDatas(31);	
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
										$message	= str_replace("{Class Name}",  CbscExamGroup17::model()->getClassName($exam_group->class), $message);
										$message	= str_replace("{Batch Name}", html_entity_decode(ucfirst($batch->name)), $message);
										
										$argument_arr = array('message'=>$message, 'device_id'=>array($student_device->device_id), 'sender_name'=>$sender_name, 'batch_id'=>$batch->id, 'exam_group_id'=>$exam_group->id, 'flag'=>2);               
										Configurations::model()->devicenotice($argument_arr, $student_push_notifications[$key]['title'], "cbse_exam");																			
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
										$message	= str_replace("{Class Name}",  CbscExamGroup17::model()->getClassName($exam_group->class), $message);
										$message	= str_replace("{Batch Name}", html_entity_decode(ucfirst($batch->name)), $message);
										$message	= str_replace("{Student Name}", $student->getStudentname(), $message);
										
										$argument_arr = array('message'=>$message, 'device_id'=>array($guardian_device->device_id), 'sender_name'=>$sender_name, 'batch_id'=>$batch->id, 'exam_group_id'=>$exam_group->id, 'student_id'=>$student->id, 'flag'=>2);               
										Configurations::model()->devicenotice($argument_arr, $parent_push_notifications[$key]['title'], "cbse_exam");	
									}
								}							
							}
						}
					}
				}
			}
			$this->redirect(Yii::app()->request->urlReferrer);
		}
	}
	public function actionSplitpdf()
    {
		$batch_name 	= Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$exam 			= CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['examid']));
		$examgroup	 	= CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
		$subject 		= Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
		$filename 		= $batch_name->name.' '.$examgroup->name.' '.$subject->name.'.pdf';
		Yii::app()->osPdf->generate("application.modules.CBSCExam.views.exam17.scoresplitpdf", $filename, array(), 1); 
	}
}
?>