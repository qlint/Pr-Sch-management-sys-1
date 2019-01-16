<?php
class AndroidApiController extends CController
{
	public function checkAuthKey(){		
		$user		= NULL;
		if (!function_exists('getallheaders')){
            $headers    = $this->getallheaders();
        }
        else{
		    $headers	= getallheaders();				
	    }
	    
		if($headers!=NULL and isset($headers['Authorization-Key']) and ($auth_key=$headers['Authorization-Key'])!=NULL){ 
			$uid		= $this->decryptToken($headers['Authorization-Key']);	
			$user		= User::model()->findByAttributes(array('id'=>$uid, 'status'=>User::STATUS_ACTIVE));
			//Check wherther the system is under offline
			if($user != NULL){
				//Set Language
				$settings	= UserSettings::model()->findByAttributes(array('user_id'=>$user->id));
				if($settings!=NULL){
					$language	= $settings->language;
				}
				else{
					$language	= 'en_us';
				}
				Yii::app()->translate->setLanguage($language);
            	//$this->checkOffline($user->username);			
			}
		}
		
		if($user == NULL){
			$response["error"] 		= true;
			$response["error_msg"] 	= "Authentication Failed";
			echo json_encode($response);
			exit;
		}
	}
	
	public function getallheaders() 
    { 
        $headers = array(); 
        foreach ($_SERVER as $name => $value){ 
            if (substr($name, 0, 5) == 'HTTP_'){ 
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
            } 
        } 
        return $headers; 
    } 
	
	protected function encryptToken($token){
		$salt	= rand(5, 9);		
		for($i=0; $i<$salt; $i++){
			$token	= strrev(base64_encode($token));
		}
		$token	.= rand(1000, 9999);
		$token	.= $salt;
		return $token;
	}
	
	protected function decryptToken($token)
	{
		$salt 	= substr($token, -1);
		$token	= substr_replace($token, "", -5);
		for($i=0; $i<$salt; $i++){
			$token	= base64_decode(strrev($token));
		}
		return $token;
	}
	
	//Check whether the system set to offline
	protected function checkOffline($username)
	{
		$is_offline	= SystemOfflineSettings::model()->checkUpdate($username);
		if($is_offline == 0){
			$response["error"] 		= true;
			$response["message"]	= Yii::app()->user->getState('offline_message');
			$response["offline"]	= 1;
			echo json_encode($response);
			exit;
		}
	}
	
	public function actionIndex(){		
		if(isset($_POST)){
			$post	= $_POST;						
			if(isset($post['tag']) && $post['tag'] != ''){
				// get tag...
				$tag	= $post['tag'];
				
				//Check Authentication
				if($tag != 'login' and $tag != 'lostPassword'){
					$this->checkAuthKey();
				}
								
				// User login...
				if($tag == 'login'){					
					// Request type is check Login
					if((isset($post['email']) && $post['email'] != NULL) && (isset($post['password']) && $post['password'] != NULL)){
						//Check whether the system is offline or not
						$this->checkOffline($post['email']);
						
						$email 		= $post['email'];
						$password 	= $post['password'];
						$result 	= $this->login($email,$password);
						
						if($result['status']){
							$user	= User::model()->notsafe()->findByPk(Yii::app()->user->id);
							if($user){
								$user->saveAttributes(array('lastvisit_at'=>date('Y-m-d H:i:s')));
							}
							
							$response["error"] 				= false;
							$response["uid"] 				= $user->id;
							$response["user"]["name"] 		= $user->username;
							$response["user"]["email"] 		= $user->email;
							$response["user"]["created_at"] = $user->create_at;
							$response["user"]["updated_at"] = $user->lastvisit_at;
							$response["user"]["auth_key"]	= $this->encryptToken($user->id);
							$response['user']['superuser']	= $user->superuser;
							
							//add user type............
							$roles	= Rights::getAssignedRoles($user->id); // check for single role
							$setting	= UserSettings::model()->findByAttributes(array('user_id'=>$user->id));		
							if($setting != NULL){
								if($setting->language != NULL){
									$response['user']['language']	= $setting->language;
								}
							}
							if($roles){
								$id		= '';
								$name	= '';
								foreach($roles as $role){
									if(sizeof($roles)==1 and $role->name == 'Admin'){
										$user_type	= 4;
										$profile	= Profile::model()->findByAttributes(array('user_id'=>$user->id));
										if($profile){
											$name	= ucfirst($profile->firstname).' '.ucfirst($profile->lastname);
										}
									}
								    else if(sizeof($roles)==1 and $role->name == 'parent'){
										$parent		= Guardians::model()->findByAttributes(array('uid'=>$user->id));
										$id			= $parent->id;
										$user_type	= 1;
										if($parent){
											$name	= ucfirst($parent->first_name).' '.ucfirst($parent->last_name);
										}
								    }
								    else if(sizeof($roles)==1 and $role->name == 'student'){ 
										$student		= Students::model()->findByAttributes(array('uid'=>$user->id));
										$name			= ucfirst($student->first_name)." ".ucfirst($student->middle_name)." ".ucfirst($student->last_name);											
										$user_type		= 2;
										$id				= $student->id;
										
										//Get Profile image path
										$path = $this->getProfileImagePath($student->id, 2);
										if($path != NULL){
											$response['user']['image']			= Yii::app()->getBaseUrl(true).'/'.$path;												
										}										
								    }
								    else if(sizeof($roles)==1 and $role->name == 'teacher'){ 
										$teacher	= Employees::model()->findByAttributes(array('uid'=>$user->id));
										$id			= $teacher->id;
										$user_type	= 3;
										if($teacher){
											$name	= ucfirst($teacher->first_name).' '.ucfirst($teacher->middle_name).' '.ucfirst($teacher->last_name);
										}
										
										//Get Profile image path
										$path = $this->getProfileImagePath($teacher->id, 3);
										if($path != NULL){
											$response['user']['image']			= Yii::app()->getBaseUrl(true).'/'.$path;												
										}	
								    }
									else if(sizeof($roles)==1 and $role->name == 'BusSupervisor'){ 
										$user_type	= 5;
										$profile	= Profile::model()->findByAttributes(array('user_id'=>$user->id));
										if($profile){
											$name	= ucfirst($profile->firstname).' '.ucfirst($profile->lastname);
										}
								    }
									else{ //Custom users
										$user_type	= 6;
										$profile	= Profile::model()->findByAttributes(array('user_id'=>$user->id));
										if($profile){
											$name	= ucfirst($profile->firstname).' '.ucfirst($profile->lastname);
										}
									}																											
								}
							}	
							$college	= Configurations::model()->findByPk(1);
							
							$response['user']['name']	= $name;				
							$response["user"]["type"] 	= $user_type;	
							$response['user']['id']		= $id;
							if($college->config_value != NULL){							
								$response['user']['school_name']	= html_entity_decode(ucfirst($college->config_value)); 
							}																			
						}
						else{
							$response["error"] 	= true;
							if($result['flag'] == 1){
								$response["errors"] 	= array('email'=>array($result['message']));							
							}
							else if($result['flag'] == 2){
								$response["errors"] 	= array('password'=>array($result['message']));							
							}
							else{
								$response["errors"] 	= array('message'=>array($result['message']));
							}
						}
						echo json_encode($response, JSON_UNESCAPED_SLASHES);
						exit;
					}
					else{
						$response["error"] 		= true;
						$response["error_msg"] 	= "Invalid Request";
						echo json_encode($response);
						exit;
					}
				}
				elseif($tag == 'dashboardCount'){
					$uid					= $_POST['uid'];					
					$response['mailbox']	= Mailbox::model()->newMsgs($uid);
					
					echo json_encode($response);
					exit;
				}				
				elseif($tag == 'getModules'){ //List of modules accessable for custom users					
					$roles		= Rights::getAssignedRoles($_POST['uid']);
					$modules	= array(); 
					if($roles){												
						$user_role	= UserRoles::model()->findByAttributes(array('name'=>key($roles)));
						if($user_role){
							$criteria 				= new CDbCriteria;							
							$criteria->condition	= '`role_id`=:role_id';
							$criteria->params		= array(':role_id'=>$user_role->id);
							$module_access			= ModuleAccess::model()->findAll($criteria);
							if($module_access){
								foreach($module_access as $key=>$module){
									$modules[]	= $module->module_id;
								}
							}						
						}
						if(key($roles) == 'pm'){
							$modules[]	= 1;
						}
					}
					echo json_encode(array('modules'=>$modules));
					exit;
				}
				else if($tag == 'lostPassword'){ //Password recovery mail function
					if(isset($_POST['login_or_email']) and $_POST['login_or_email'] != NULL){
						$model 					= new UserRecoveryForm;
						$model->login_or_email	= $_POST['login_or_email'];
						
						if($model->validate()){
							$user 			= User::model()->notsafe()->findbyPk($model->user_id);	
												
							$activation_url = Yii::app()->getBaseUrl(true).str_replace('android.php', 'index.php', Yii::app()->createUrl('/user/recovery',array("activkey" => $user->activkey, "email" => $user->email)));
														
							$subject 		= Yii::t('app',"You have requested the password recovery site {site_name}",
												array(
													'{site_name}'=>Yii::app()->name,
												));
			    			$message 		= Yii::t('app',"You have requested the password recovery site {site_name}. To receive a new password, go to {activation_url}.",
												array(
													'{site_name}'=>Yii::app()->name,
													'{activation_url}'=>$activation_url,
												));
							
			    			UserModule::sendMail($user->email,$subject,$message);
							echo json_encode(array('status'=>"success", 'message'=>'Please check your email. An instructions was sent to your email address.'));	
						}
						else{
							$errors	= $model->getErrors();
							echo json_encode(array('status'=>"error", 'errors'=>$errors));	
						}
					}
					else{
						$response["error"] 		= true;
						$response["error_msg"] 	= "Invalid Request";
						echo json_encode($response);
						exit;
					}
				}
				elseif($tag == 'news'){ //news.........				
					Yii::import('application.modules.mailbox.models.Mailbox');
					$dataProvider	= new CActiveDataProvider(Mailbox::model()->inbox(2), array('pagination'=>false));
					$data 			= $dataProvider->getData();															
					$return_arr 	= array();
					$i 				= 1;					
					foreach($data as $key => $val){	
						$created_by						= '';
						$profile						= Profile::model()->findByAttributes(array('user_id'=>$val->initiator_id));
						if($profile){
							$created_by					= ucfirst($profile->firstname).' '.ucfirst($profile->lastname);
						}
						$return_arr[$key]['id'] 		= $i++; 
						$return_arr[$key]['title']		= html_entity_decode(ucfirst($val->subject));
						$return_arr[$key]['text']		= html_entity_decode(ucfirst($val->text));
						$return_arr[$key]['date']		= Yii::app()->getModule('mailbox')->getDate($val->modified);	
						$return_arr[$key]['created_by']	= $created_by;			
					}
					$post_data = json_encode(array('news' =>$return_arr),JSON_UNESCAPED_SLASHES);
					echo $post_data;
					exit;					
				}
                elseif($tag == 'inbox'){ //inbox........				
					Yii::import('application.modules.mailbox.models.Mailbox');
					if(isset($post['uid']) and $post['uid']!= NULL){
						$user_id 		= $post['uid'];
						$dataProvider	= new CActiveDataProvider(Mailbox::model()->inbox($post['uid']));
						$data			= $dataProvider->getData();						
						$return_arr 	= array();
						$i 				= 1;
						foreach($data as $key=>$val){
						
							if($user_id == $val->initiator_id){
								$counterUserId = $val->interlocutor_id;
							}
							else{
								$counterUserId = $val->initiator_id;
							}
							
							//Get Profile image path
							$path = $this->getProfileImagePath($counterUserId, 1);
							
							$username 								= Yii::app()->getModule('mailbox')->getFromLabel($counterUserId);
							$return_arr[$key]['user_id']			= $counterUserId;
							$return_arr[$key]['id'] 				= $i++; 
							$return_arr[$key]['subject']			= html_entity_decode(ucfirst($val->subject));							
							$discription							= html_entity_decode(ucfirst($val->text));
							
							$return_arr[$key]['message']			= strip_tags($discription);
							$return_arr[$key]['date']				= Yii::app()->getModule('mailbox')->getDate($val->modified);
							$return_arr[$key]['sender']				= $username;
							$return_arr[$key]['sender_email']		= Yii::app()->getModule('mailbox')->getUserEmail($counterUserId);
							$return_arr[$key]['conversation_id']	= $val->conversation_id;	
							if($path != NULL){
								$return_arr[$key]['path']			= Yii::app()->getBaseUrl(true).'/'.$path;												
							}
						}
						$post_data = json_encode(array('inbox' =>$return_arr),JSON_UNESCAPED_SLASHES);
						echo $post_data;
						exit;						
					}
					else{
						$response["error"] 		= true;
						$response["error_msg"] 	= "Invalid Request";
						echo json_encode($response);
						exit;
					}
				}
                elseif($tag == 'sentmail'){ //sent mails........				
					Yii::import('application.modules.mailbox.models.Mailbox');
					if(isset($post['uid']) and $post['uid'] != NULL){
						$user_id 		= $post['uid'];
						$dataProvider	= new CActiveDataProvider(DashboardMessage::model()->sent($post['uid']));
						$data			= $dataProvider->getData();						
						$return_arr 	= array();
						$i 				= 1;
						foreach($data as $key=>$val){															                                                        
							$username 								= Yii::app()->getModule('mailbox')->getFromLabel($user_id);
							$receiver_name 							= Yii::app()->getModule('mailbox')->getFromLabel($val->recipient_id);
							$return_arr[$key]['user_id']			= $user_id;
							$return_arr[$key]['id'] 				= $i++; 
							$return_arr[$key]['subject']			= html_entity_decode(ucfirst($val->subject));                            
							$discription							= html_entity_decode(ucfirst($val->text));
							
							$return_arr[$key]['message']			= strip_tags($discription);
							$return_arr[$key]['date']				= Yii::app()->getModule('mailbox')->getDate($val->created);
							$return_arr[$key]['sender']				= $username;
							$return_arr[$key]['receiver']			= $receiver_name;
							$return_arr[$key]['sender_email']		= Yii::app()->getModule('mailbox')->getUserEmail($user_id);
							$return_arr[$key]['conversation_id']	= $val->conversation_id;														
						}
						$post_data = json_encode(array('sentmail' =>$return_arr),true);
						echo $post_data;
						exit;
					
					}
					else{
						$response["error"] 		= true;
						$response["error_msg"] 	= "Invalid Request";
						echo json_encode($response);
						exit;
					}
				}                                				                          				
				elseif($tag == 'init_event'){
					$event_types_arr 	= array();
					$event_privacy_arr	= array();
					
					$criteria 			= new CDbCriteria;
					$criteria->order	= 'id ASC';
					$event_types		= EventsType::model()->findAll($criteria);
					if($event_types){
						foreach($event_types as $key => $value){
							$event_types_arr[$key]['id']	= $value->id;
							$event_types_arr[$key]['name']	= ucfirst($value->name);
						}
					}
					
					$all_roles	= new RAuthItemDataProvider('roles', array('type'=>2));
					$data 		= $all_roles->fetchData();
					if($data){
						$event_privacy_arr[0]['id']		= "0";
						$event_privacy_arr[0]['name']	= 'Public';
						$i = 1;
						foreach($data as $key => $value){							
							$event_privacy_arr[$i]['id']	= $value->name;
							$event_privacy_arr[$i]['name']	= $value->name;
							
							$i++;
						}
					}
					
					$post_data = json_encode(array('event_types' =>$event_types_arr, 'event_privacy'=>$event_privacy_arr),JSON_UNESCAPED_SLASHES);
					echo $post_data;
					exit;
				}
				elseif($tag == 'events'){ //events.......				
					if(isset($post['uid']) and $post['uid']!= NULL){
						$roles = Rights::getAssignedRoles($post['uid']); // check for single role
						foreach($roles as $role){
							$rolename = $role->name;
						}						
						$criteria 			= new CDbCriteria;
						$criteria->order 	= 'start DESC';
						if($_REQUEST['type']){
							$criteria->condition 		= 'type=:type';
							$criteria->params[':type'] 	= $_REQUEST['type'];
							if($rolename!= 'Admin'){
								$criteria->condition 				= $criteria->condition.' AND (placeholder= :default or placeholder=:placeholder)';
								$criteria->params[':placeholder'] 	= $rolename;
								$criteria->params[':default'] 		= '0';
							}
						}
						else{
							if($rolename!= 'Admin'){
								$criteria->condition 				= 'placeholder = :default or placeholder=:placeholder';
								$criteria->params[':placeholder'] 	= $rolename;
								$criteria->params[':default'] 		= '0';
							}
						}						
						$events 	= Events::model()->findAll($criteria);						
						$return_arr = array();
						$i 			= 1;
						$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
						$timezone 	= Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
						date_default_timezone_set($timezone->timezone);
						foreach($events as $key => $val){
							$return_arr[$key]['id'] 			= $i++; 
							$return_arr[$key]['title']			= html_entity_decode(ucfirst($val->title));
							$return_arr[$key]['description']	= html_entity_decode(ucfirst($val->desc));
							$return_arr[$key]['organizer']		= $val->organizer;
							$return_arr[$key]['startdate']		= date("Y M d h i a", $val->start);
							$return_arr[$key]['enddate']		= date("Y M d h i a", $val->end);												
						}
						$post_data = json_encode(array('events' =>$return_arr),JSON_UNESCAPED_SLASHES);
						echo $post_data;
						exit;						
					}
					else{
						$response["error"] 		= true;
						$response["error_msg"] 	= "Invalid Request";
						echo json_encode($response);
						exit;
					}					
				}                                
				elseif($tag=="dashboard"){
					if(isset($post['uid']) and $post['uid']!= NULL){
						$page_id	= 1;                                    
						if( ($post['page_id']) && ($post['page_id'])!=NULL && ($post['page_id'])>1){
							$page_id	= $post['page_id'];
						}                                        
						if($page_id == 1){
							$start_limit	= 1;
							$end_limit		= $page_id*10;
						}
						else{
							$start_limit	= (($page_id-1)*10)+1;
							$end_limit		= $start_limit+9;
						}
                                                $response 	=   array();
                                                $resp_key       =   0;
						//news begin
						Yii::import('application.modules.mailbox.models.Mailbox');
						$news_dataProvider 	= new CActiveDataProvider(Mailbox::model()->inbox(2),array('criteria' => array('offset'=>$start_limit,'limit'=>$end_limit,'order'=>'modified DESC')));
						$news_data 			= $news_dataProvider->getData();										
						$news_return_arr 	= array();
						$i 					= 1;
						if($news_data!=NULL){
							foreach($news_data as $key => $val){
								$created_by							= '';
								$profile							= Profile::model()->findByAttributes(array('user_id'=>$val->initiator_id));
								if($profile){
									$created_by						= ucfirst($profile->firstname).' '.ucfirst($profile->lastname);
								}
								              
								$response[$resp_key]['id']          = $val->conversation_id;
								$response[$resp_key]['title']       = html_entity_decode(ucfirst($val->subject));
								$response[$resp_key]['message']     = html_entity_decode(ucfirst(strip_tags($val->text)));
								$response[$resp_key]['date']        = $this->getDate($val->modified);
								$response[$resp_key]['sort']        = $val->modified;
								$response[$resp_key]['created_by']	= $created_by;	
								$response[$resp_key]['tag']         = 'news';
								$resp_key++;
							}
						}
                                               
						//inbox begin                                        
						$user_id 			= $post['uid'];
						$inbox_dataProvider = new CActiveDataProvider(Mailbox::model()->inbox($post['uid']),array('criteria' => array('offset'=>$start_limit,'limit'=>$end_limit,'order'=>'modified DESC')));
						$inbox_data			= $inbox_dataProvider->getData();						
						$inbox_return_arr   = array();
						$i 					= 1;
						foreach($inbox_data as $key=>$val){							
							if($user_id == $val->initiator_id){
								$counterUserId = $val->interlocutor_id;
							}
							else{
								$counterUserId = $val->initiator_id;
							}
							
							//Get Profile image path
							$path = $this->getProfileImagePath($counterUserId, 1);
							
							$username 							= Yii::app()->getModule('mailbox')->getFromLabel($counterUserId);
							$response[$resp_key]['id']          = $val->conversation_id;
							$response[$resp_key]['sender_id']   = $counterUserId;
							$response[$resp_key]['title']       = html_entity_decode(ucfirst($val->subject));
							$response[$resp_key]['message']     = html_entity_decode(ucfirst(strip_tags($val->text)));
							$response[$resp_key]['sender_name'] = $username;
							$response[$resp_key]['sender_email']= Yii::app()->getModule('mailbox')->getUserEmail($counterUserId);
							$response[$resp_key]['date']        = $this->getDate($val->modified);
							$response[$resp_key]['sort']        = $val->modified;
							if($path != NULL){
								$response[$resp_key]['image']	= Yii::app()->getBaseUrl(true).'/'.$path;	
							}
							$response[$resp_key]['tag']         = 'inbox';
							$resp_key++;  
						}												
						//events begin                                        
						$roles = Rights::getAssignedRoles($post['uid']); // check for single role
						foreach($roles as $role){
							$rolename = $role->name;
						}
						$criteria 			= new CDbCriteria;
						$criteria->order 	= 'start DESC';
						$criteria->offset	= $start_limit;
						$criteria->limit	= $end_limit;
						if($_REQUEST['type']){
							$criteria->condition 		= 'type=:type';
							$criteria->params[':type'] 	= $_REQUEST['type'];
							if($rolename!= 'Admin'){
								$criteria->condition 				= $criteria->condition.' AND (placeholder= :default or placeholder=:placeholder)';
								$criteria->params[':placeholder'] 	= $rolename;
								$criteria->params[':default'] 		= '0';
							}
						}
						else{
							if($rolename != 'Admin'){
								$criteria->condition 				= 'placeholder = :default or placeholder=:placeholder';
								$criteria->params[':placeholder'] 	= $rolename;
								$criteria->params[':default'] 		= '0';
							}
						}						
						$events 			= Events::model()->findAll($criteria);
						$events_return_arr 	= array();
						$i 					= 1;
						foreach($events as $key => $val){							                                                        
							$response[$resp_key]['id']          	= $val->id;
							$response[$resp_key]['title']       	= html_entity_decode(ucfirst($val->title));
							$response[$resp_key]['message']     	= html_entity_decode(ucfirst(strip_tags($val->desc)));
							$response[$resp_key]['date']        	= date("Y M d", $val->start);
							$response[$resp_key]['time']        	= date("h:i a", $val->start)." - ".date("h:i a", $val->end);
							$response[$resp_key]['sort']        	= $val->start;
							$response[$resp_key]['tag']         	= 'event';
							$response[$resp_key]['calendar_date']	= date("Y-m-d", $val->start);
							$resp_key++;
						}	
                                                
                        $this->sortDashboard($response, 'sort'); //sort array with date						
                        $post_data = json_encode(array('response' =>$response,'page_id'=>$page_id),JSON_UNESCAPED_SLASHES);
						echo $post_data;
						exit;						
					}
					else{
						$response["error"] 		= true;
						$response["error_msg"] 	= "Invalid Request";
						echo json_encode($response);
						exit;
					}				
				}                                                                
				elseif($tag == 'timetable'){ //timetable......				
					$return_arr = $this->gettimetable($post);
					$post_data 	= json_encode(array('timetable' =>$return_arr),JSON_UNESCAPED_SLASHES);
					echo $post_data;
					exit;				
				}
				elseif($tag == 'register_device'){ //register loged in device
					if((isset($post['uid']) and $post['uid'] != NULL) && (isset($post['device_id']) and $post['device_id'] != NULL) && (isset($post['auth_key']) and $post['auth_key'] != NULL)){
						$model 				= new UserDevice;
						$model->uid 		= $post['uid'];
						$model->device_id 	= $post['device_id'];
						$model->auth_key	= $post['auth_key'];
						if($model->save())
							echo 'success';
						else
							echo 'error';
					}
					else{
						echo 'Invalid Request';
					}
					exit;
				}
				elseif($tag == 'downloads'){//downloads........									
					if(isset($post['uid']) && $post['uid'] != NULL){
						$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
						$data 		= $this->getdownloads($post);
						$return_arr = array();
						$i 			= 1;
						foreach($data as $key=>$val){
							$created_at	= '';
							$created_by	= '';
							if($val->created_at != NULL and $val->created_at != '0000-00-00 00:00:00')	{
								$created_at	= date($settings->displaydate, strtotime($val->created_at));
							}
							if($val->created_by != NULL){
								$profile	= Profile::model()->findByAttributes(array('user_id'=>$val->created_by));
								if($profile){
									$created_by	= ucfirst($profile->firstname).' '.ucfirst($profile->lastname);
								}
							}
							$return_arr[$key]['title']			= html_entity_decode(ucfirst($val->title));														
							$return_arr[$key]['created_at']		= $created_at;	
							$return_arr[$key]['created_by']		= $created_by;
							$return_arr[$key]['description']	= html_entity_decode(ucfirst($val->description));
							$return_arr[$key]['url']			= Yii::app()->createAbsoluteUrl('androidApi/downloadfile',array('id'=>$val->id));	
								
						}
						$post_data = json_encode(array('downloads' =>$return_arr),JSON_UNESCAPED_SLASHES);
						echo $post_data;
						exit;					
					}
					else{
						$response["error"] 		= true;
						$response["error_msg"] 	= "Invalid Request";
						echo json_encode($response);
						exit;
					}				
				}
				else if($tag == 'file_upload_inputs'){ //Download module file upload				
					$uid					= $post['uid'];
					$level					= $post['level'];
					if($uid != NULL){
						if($level == 1){ //Display category, placeholder, course
							$category_arr		= array();
							$placeholder_arr	= array();
							$course_arr			= array();
							$file_type_arr		= array();
							//Category List
							Yii::app()->getModule('downloads');
							$categories			= FileCategory::model()->findAll();
							if($categories){
								foreach($categories as $key=>$category){
									$category_arr[$key]['id']	= $category->id;
									$category_arr[$key]['name']	= html_entity_decode(ucfirst($category->category));
								}
							}
							//Placeholder List
							$all_roles	= new RAuthItemDataProvider('roles', array('type'=>2));
							$datas		= $all_roles->fetchData();
							if($datas){	
								$placeholder_arr[0]['id']	= '';
								$placeholder_arr[0]['name']	= 'Public';
								$i	= 1;
								foreach($datas as $value){
									if($value->name != 'parent' and $value->name != 'BusSupervisor'){
										$placeholder_arr[$i]['id'] 		= $value->name;
										$placeholder_arr[$i]['name'] 	= ucfirst($value->name);
										$i++;
									}
								}
							}
							//Course List
							$current_academic_yr 	= Configurations::model()->findByAttributes(array('id'=>35));
							$criteria				= new CDbCriteria();
							$criteria->condition	= 'is_deleted=:is_deleted AND academic_yr_id=:academic_yr_id';							
							$criteria->params		= array(':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value);
							$criteria->order		= 'course_name ASC';
							$courses				= Courses::model()->findAll($criteria);
							if($courses){
								foreach($courses as $key=>$course){
									$course_arr[$key]['id']		= $course->id;
									$course_arr[$key]['name']	= html_entity_decode(ucfirst($course->course_name));
								}
							}
							
							//File Type Lists
							$types	= array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'mp4', 'doc', 'txt', 'ppt', 'docx', 'pptx');
							foreach($types as $key => $type){
								$file_type_arr[$key]['name']	= $type; 	
							}	
							
							echo json_encode(array('category' => $category_arr, 'placeholder' => $placeholder_arr, 'courses' => $course_arr, 'file_types'=>$file_type_arr), JSON_UNESCAPED_SLASHES);
							exit;
						}
						else if($level == 2){ //Display batches
							$course_id				= $post['course_id'];
							$batch_arr				= array();
							
							$criteria				= new CDbCriteria();
							$criteria->condition	= 'is_deleted=:is_deleted AND is_active=:is_active AND course_id=:course_id';
							$criteria->params		= array(':is_deleted'=>0, ':is_active'=>1, ':course_id'=>$course_id);
							$criteria->order		= 'name ASC';
							$batches				= Batches::model()->findAll($criteria);
							if($batches){
								foreach($batches as $key=>$batch){
									$batch_arr[$key]['id']		= $batch->id;
									$batch_arr[$key]['name']	= html_entity_decode(ucfirst($batch->name));
								}
							}
							echo json_encode(array('batches' => $batch_arr), JSON_UNESCAPED_SLASHES);
							exit;
						}
						else if($level == 3){ //Display Students
							$batch_id		= $post['batch_id'];
							$students_arr	= array();
							$student_lists	= Yii::app()->getModule('students')->studentsOfBatch($batch_id);
							if($student_lists){
								foreach($student_lists as $key=>$student){
									$students_arr[$key]['id']	= $student->id;
									$students_arr[$key]['name'] = $student->studentFullName('forTeacherPortal');
								}
							}
							echo json_encode(array('students' => $students_arr), JSON_UNESCAPED_SLASHES);
							exit;
						}
					}
					else{
						$response["error"] 		= true;
						$response["error_msg"] 	= "Invalid Request";
						echo json_encode($response);
						exit;	
					}					
				}
				else if($tag == 'file_upload'){
					$uid		= $_POST['uid'];				
					$role 		= Rights::getAssignedRoles($uid);
					Yii::app()->getModule('downloads');
					if(isset($_POST['FileUploads'])){
						$model	= new FileUploads;
						$model->attributes	= $_POST['FileUploads'];						
						$model->file		= $_FILES['FileUploads']['name']['file'];
						$model->file_type	= $_FILES['FileUploads']['type']['file'];
						$model->created_by	= $uid;
						$model->created_at	= date('Y-m-d H:i:s');
						if(key($role) == 'teacher'){
							$model->description	= $_POST['FileUploads']['description'];
							$students			= json_decode($_POST['FileUploads']['students']);		
							if(count($students) > 0){
								if(count($students) == 1 and $students[0] == ''){
									$model->is_special_student = 0;
								}
								else{
									$model->is_special_student = 1;
								}
							}
							else{
								$model->is_special_student = 0;
							}
						}
						else{
							$model->is_special_student 	= 0;
							$academic_yr 				= Configurations::model()->findByPk(35);
							$model->academic_yr_id 		= $academic_yr->config_value;
						}
						if($model->validate()){
							if($model->save()){
								if($_FILES['FileUploads']['name']['file'] != NULL){
									$new_file_name		= DocumentUploads::model()->getFileName($_FILES['FileUploads']['name']['file']);
									$destination_file 	= 'uploads/shared/'.$model->id.'/'.$new_file_name;
									if(!is_dir('uploads/')){
										mkdir('uploads/');
									}	
									if(!is_dir('uploads/shared/')){
										mkdir('uploads/shared/');
									}
									if(!is_dir('uploads/shared/'.$model->id)){
										mkdir('uploads/shared/'.$model->id);
									}
									if(move_uploaded_file($_FILES['FileUploads']['tmp_name']['file'], $destination_file)){
										$model->file	= $new_file_name;
										$model->save();
									}									
								}
								if(key($role) == 'teacher'){
									//Save particular student details
									if(count($students) > 0){
										for($i = 0; $i < count($students); $i++){
											if($students[$i] != NULL){
												$model_1				= new FileUploadsStudents;
												$model_1->table_id		= $model->id;
												$model_1->student_id	= $students[$i];
												$model_1->save();
											}
										}
									}
									
									$category	= FileCategory::model()->findByPk($model->category);
									if($category->category == "Homework"){
										$status 	=	1;
									}
									else{
										$status 	=	0;
									}
									DocumentUploads::model()->insertData(5, $model->id, $model->file, 7, '', $uid, 1, $status);
								}
								else{
									DocumentUploads::model()->insertData(5, $model->id, $model->file, 7, '', $uid, 1, 1);
								}
								
								echo json_encode(array('status'=>'success'));
								exit;
							}
						}						
						else{						
							$errors	= $model->getErrors();
							echo json_encode(array('status'=>'error', 'errors'=>$errors), JSON_UNESCAPED_SLASHES);
							exit;						
						}																
					}						
				}
				else if($tag == 'update_file'){
					$uid	= $_POST['uid'];
					$role 	= Rights::getAssignedRoles($uid);
					if(isset($_POST['id']) and $_POST['id'] != NULL){
						$id	= $_POST['id'];
					}
					else if(isset($_POST['FileUploads']['id']) and $_POST['FileUploads']['id'] != NULL){
						$id	= $_POST['FileUploads']['id'];						
					}
					$return_arr	= array();					
					Yii::app()->getModule('downloads');
					$model	= FileUploads::model()->findByAttributes(array('id'=>$id, 'created_by'=>$uid));
					if($model){
						if(isset($_POST['FileUploads'])){							
							$model->attributes	= $_POST['FileUploads'];							
							$old_file_name			= $model->file;
							if($_FILES['FileUploads']['name']['file'] != NULL){						
								$model->file		= $_FILES['FileUploads']['name']['file'];
								$model->file_type	= $_FILES['FileUploads']['type']['file'];
							}							
							if(key($role) == 'teacher'){
								$model->description	= $_POST['FileUploads']['description'];
								$students			= json_decode($_POST['FileUploads']['students']);		
								if(count($students) > 0){
									if(count($students) == 1 and $students[0] == ''){
										$model->is_special_student = 0;
									}
									else{
										$model->is_special_student = 1;
									}
								}
								else{
									$model->is_special_student = 0;
								}
							}
							else{
								$model->is_special_student 	= 0;								
							}
							if($model->validate()){
								if($model->save()){
									if($_FILES['FileUploads']['name']['file'] != NULL){
										//Remove old file
										$image_path	= 'uploads/shared/'.$model->id.'/'.$old_file_name;
										if(file_exists($image_path)){
											unlink($image_path);										
										}
										//Add new file										
										$new_file_name		= DocumentUploads::model()->getFileName($_FILES['FileUploads']['name']['file']);
										$destination_file 	= 'uploads/shared/'.$model->id.'/'.$new_file_name;
										if(!is_dir('uploads/')){
											mkdir('uploads/');
										}	
										if(!is_dir('uploads/shared/')){
											mkdir('uploads/shared/');
										}
										if(!is_dir('uploads/shared/'.$model->id)){
											mkdir('uploads/shared/'.$model->id);
										}
										if(move_uploaded_file($_FILES['FileUploads']['tmp_name']['file'], $destination_file)){
											$model->file	= $new_file_name;
											if($model->save()){
												$document_upload	= DocumentUploads::model()->findByAttributes(array('model_id'=>5, 'file_id'=>$model->id, 'identifier'=>7));
												if($document_upload){
													$document_upload->file_name = $model->file;
													$document_upload->save();
												}
											}
										}									
									}
									if(key($role) == 'teacher'){
										//Remove old entry
										$old_entry 	= FileUploadsStudents::model()->findAllByAttributes(array('table_id'=>$model->id));										
										if($old_entry){
											foreach($old_entry as $value){												
												$value->delete();
											}
										}
										//Save particular student details
										if(count($students) > 0){
											for($i = 0; $i < count($students); $i++){
												if($students[$i] != NULL){
													$model_1				= new FileUploadsStudents;
													$model_1->table_id		= $model->id;
													$model_1->student_id	= $students[$i];
													$model_1->save();
												}
											}
										}										
									}																		
									echo json_encode(array('status'=>'success'));
									exit;
								}
							}						
							else{						
								$errors	= $model->getErrors();
								echo json_encode(array('status'=>'error', 'errors'=>$errors), JSON_UNESCAPED_SLASHES);
								exit;						
							}														
						}
						else{
							$return_arr['FileUploads']['id']				= $model->id;
							$return_arr['FileUploads']['title']				= ucfirst($model->title);
							$return_arr['FileUploads']['category']			= $model->category;
							$return_arr['FileUploads']['placeholder']		= $model->placeholder;
							$return_arr['FileUploads']['course']			= $model->course;
							$return_arr['FileUploads']['batch']				= $model->batch;
							if(key($role) == 'teacher'){
								$students_arr		= array();
								$student_name_arr	= array();
								if($model->is_special_student == 1){
									$file_upload_students = FileUploadsStudents::model()->findAllByAttributes(array('table_id'=>$model->id));
									if($file_upload_students){
										foreach($file_upload_students as $key => $value){
											$student			= Students::model()->findByPk($value->student_id);
											$students_arr[] 	= $value->student_id;
											$student_name_arr[]	= $student->getStudentname(); //This is for IOS only
											
										}
									}
									if(count($students_arr) > 0){
										$return_arr['FileUploads']['students']		= $students_arr;
										$return_arr['FileUploads']['student_name']	= $student_name_arr; //This is for IOS only
									}									
								}
								$return_arr['FileUploads']['description']	= ucfirst($model->description);								
							}
							$return_arr['FileUploads']['file']				= $model->file;
							
							echo json_encode(array('file'=>$return_arr), JSON_UNESCAPED_SLASHES);
							exit;
						}
					}
					else{
						$response["error"] 		= true;
						$response["error_msg"] 	= "Invalid Request";
						echo json_encode($response);
						exit;	
					}					
				}
				else if($tag == 'manage_upload'){
					$uid				= $_POST['uid'];
					$role 				= Rights::getAssignedRoles($uid);
					$return_arr			= array();
					$placeholder_arr	= array(); 
					Yii::app()->getModule('downloads');
					
					//For Pagination
					$page_id	= 1; 
					$limit		= 10;                                   
					if(($_POST['page_id']) && ($_POST['page_id'])!=NULL && ($_POST['page_id'])>1){
						$page_id	= $_POST['page_id'];
					}                                        
					if($page_id == 1){
						$start_limit	= 0;						
					}
					else{
						$start_limit	= (($page_id - 1) * 10);						
					}
					
					$criteria				= new CDbCriteria();
					$criteria->join			= 'JOIN `document_uploads` `t1` ON `t1`.`file_id` = `t`.`id`';	
					if(key($role) == 'teacher'){
						$criteria->condition	= '`t`.`created_by`=:created_by AND `t1`.`model_id`=:model_id AND `t1`.`status`=:status AND `t1`.`created_by`=:created_by';
						$criteria->params		= array(':created_by'=>$uid, ':model_id'=>5, ':status'=>1);
					}
					else{
						$academic_yr 			= Configurations::model()->findByPk(35);
						$criteria->condition	= '`t`.`created_by`=:created_by AND `t`.`academic_yr_id`=:academic_yr_id AND `t1`.`model_id`=:model_id AND `t1`.`status`=:status AND `t1`.`created_by`=:created_by';
						$criteria->params		= array(':created_by'=>$uid, ':academic_yr_id'=>$academic_yr->config_value,':model_id'=>5, ':status'=>1);
					}
					
					if(isset($_REQUEST['title']) and $_REQUEST['title'] != NULL){ 
						$criteria->condition		.= ' AND title LIKE :title';
						$criteria->params[':title']	= '%'.$_REQUEST['title'].'%';
					}
					if(isset($_REQUEST['placeholder']) and $_REQUEST['placeholder'] != NULL){
						if($_REQUEST['placeholder'] != '0'){
							$criteria->condition				.= ' AND placeholder=:placeholder';
							$criteria->params[':placeholder']	= $_REQUEST['placeholder'];
						}
						else{
							$criteria->condition				.= ' AND placeholder=:placeholder';
							$criteria->params[':placeholder']	= '';
						}
					}
										
					$criteria->offset	= $start_limit;
					$criteria->limit	= $limit;
					$files 				= FileUploads::model()->findAll($criteria);					
					if($files){
						foreach($files as $key => $value){							
							$course_name	= '';
							$batch_name		= '';							
							
							if($value->course != NULL){
								$course		= Courses::model()->findByAttributes(array('id'=>$value->course));
								if($course){
									$course_name	= html_entity_decode(ucfirst($course->course_name));
								}
							}
							if($value->batch != NULL){
								$batch		= Batches::model()->findByAttributes(array('id'=>$value->batch));
								if($batch){
									$batch_name	= html_entity_decode(ucfirst($batch->name));
								}
							}
							
							$return_arr[$key]['id']				= $value->id;
							$return_arr[$key]['title']			= html_entity_decode(ucfirst($value->title));							
							$return_arr[$key]['placeholder']	= ($value->placeholder != NULL)?ucfirst($value->placeholder):"Public";
							$return_arr[$key]['course']			= ($course_name != NULL)?$course_name:"-";
							$return_arr[$key]['batch']			= ($batch_name != NULL)?$batch_name:"-";															
						}
					}
					
					//Placeholder List
					$all_roles	= new RAuthItemDataProvider('roles', array('type'=>2));
					$datas		= $all_roles->fetchData();
					if($datas){	
						$placeholder_arr[0]['id']	= '0';
						$placeholder_arr[0]['name']	= 'Public';
						$i	= 1;
						foreach($datas as $value){
							if($value->name != 'parent' and $value->name != 'BusSupervisor'){
								$placeholder_arr[$i]['id'] 		= $value->name;
								$placeholder_arr[$i]['name'] 	= ucfirst($value->name);
								$i++;
							}
						}
					}
					echo json_encode(array('files'=>$return_arr, 'placeholder'=>$placeholder_arr), JSON_UNESCAPED_SLASHES);
					exit;								
				}
				else if($tag == 'view_upload'){
					$uid		= $_POST['uid'];
					$id			= $_POST['id'];
					$role 		= Rights::getAssignedRoles($uid);
					$return_arr	= array();
					Yii::app()->getModule('downloads');					
					$model	= FileUploads::model()->findByPk($id);					
					if($model != NULL){
						$category		= FileCategory::model()->findByAttributes(array('id'=>$model->category));
						$course_name	= '';
						$batch_name		= '';
						if(key($role) == 'teacher'){
							$students		= '';
							$student_upload	= FileUploadsStudents::model()->findAllByAttributes(array('table_id'=>$model->id));
							if($student_upload){
								$student_arr = array();
								foreach($student_upload as $val){
									$student	= Students::model()->findByPk($val->student_id);
									if($student){						
										$student_arr[]	= $student->studentFullName('forTeacherPortal');
									}
								}
								if(count($student_arr) > 0){
									$students	=  implode(', ', $student_arr);
								}									
							}
						}
						
						if($model->course != NULL){
							$course		= Courses::model()->findByAttributes(array('id'=>$model->course));
							if($course){
								$course_name	= ucfirst($course->course_name);
							}
						}
						if($model->batch != NULL){
							$batch		= Batches::model()->findByAttributes(array('id'=>$model->batch));
							if($batch){
								$batch_name	= ucfirst($batch->name);
							}
						}
						
						$return_arr['id']				= $model->id;
						$return_arr['title']			= html_entity_decode(ucfirst($model->title));
						$return_arr['category']			= ($category != NULL)?html_entity_decode(ucfirst($category->category)):"-";
						$return_arr['placeholder']		= ($model->placeholder != NULL)?ucfirst($model->placeholder):"Public";
						$return_arr['course']			= ($course_name != NULL)?html_entity_decode($course_name):"-";
						$return_arr['batch']			= ($batch_name != NULL)?html_entity_decode($batch_name):"-";
						if(key($role) == 'teacher'){
							$return_arr['students']		= ($students != NULL)?$students:"-";
							$return_arr['description']	= ($model->description != NULL)?html_entity_decode(ucfirst($model->description)):"-";
						}
						if($model->file != NULL){
							$return_arr['url']			= Yii::app()->createAbsoluteUrl('androidApi/downloadfile',array('id'=>$model->id));
						}
						
						echo json_encode($return_arr, JSON_UNESCAPED_SLASHES);
						exit;						
					}
					else{
						$response["error"] 		= true;
						$response["error_msg"] 	= "Invalid Request";
						echo json_encode($response);
						exit;
					}
				}
				else if($tag == 'delete_file'){
					$uid	= $_POST['uid'];
					$id		= $_POST['id'];	
					Yii::app()->getModule('downloads');							
					$model	= FileUploads::model()->findByAttributes(array('id'=>$id, 'created_by'=>$uid));					
					if($model != NULL){
						$file_id	= $model->id;
						$file_name	= $model->file;
						if($model->delete()){
							$image_path	= 'uploads/shared/'.$model->id.'/'.$file_name;
							if(file_exists($image_path)){
								if(unlink($image_path)){
									rmdir('uploads/shared/'.$file_id);
								}
							}
							DocumentUploads::model()->deleteDocument(5, $file_id, $file_name);
							
							$student_uploads	= FileUploadsStudents::model()->findAllByAttributes(array('table_id'=>$file_id));
							if($student_uploads){
								foreach($student_uploads as $value){
									$value->delete();
								}
							}
							
							echo json_encode(array('status'=>'success'));
							exit;
						}
						else{
							$response["error"] 		= true;
							$response["error_msg"] 	= "File not deleted";
							echo json_encode($response);
							exit;
						}
					}
					else{
						$response["error"] 		= true;
						$response["error_msg"] 	= "Invalid Request";
						echo json_encode($response);
						exit;
					}
				}
				else if($tag == 'remove_file'){ //Remove the file during the data update
					$uid	= $_POST['uid'];
					$id		= $_POST['id'];	
					Yii::app()->getModule('downloads');							
					$model	= FileUploads::model()->findByAttributes(array('id'=>$id, 'created_by'=>$uid));					
					if($model != NULL){
						$image_path	=	'uploads/shared/'.$model->id.'/'.$model->file;
						if(file_exists($image_path)){
							if(unlink($image_path)){
								$model->file		=	NULL;
								$model->file_type	=	NULL;
								$model->save();
								
								echo json_encode(array('status'=>'success'));
								exit;								
							}
						}
						else{
							$response["error"] 		= true;
							$response["error_msg"] 	= "Invalid Request";
							echo json_encode($response);
							exit;
						}
					}
					else{
						$response["error"] 		= true;
						$response["error_msg"] 	= "Invalid Request";
						echo json_encode($response);
						exit;
					}
				}
				elseif($tag == 'logout'){ // delete logged user device
					if((isset($post['uid']) and $post['uid'] != NULL)&& (isset($post['device_id']) and $post['device_id'] != NULL)){
						UserDevice::model()->deleteAll('uid =:id AND device_id=:device_id',array('id' => $post['uid'],'device_id'=>$post['device_id']));
						echo 'success';
					}
					else{
						echo 'Invalid Request';
					}					
					exit;
				}
				elseif($tag == 'exams'){ //exams.......				
					if(isset($post['uid']) && $post['uid']!= NULL){
						$subject_name	= "";
						$group_name		= "";
						$roles 			= Rights::getAssignedRoles($post['uid']); // check for single role
						foreach($roles as $role){
							$rolename = $role->name;
						}                                          
						if($rolename == "student" or $rolename == 'parent'){
							$uid		= $_POST['uid'];
							$level		= $_POST['level'];
							$header					= array();								
							if($rolename == 'student'){
								$student	= Students::model()->findByAttributes(array('uid'=>$uid));
							}
							else{
								$student	= Students::model()->findByAttributes(array('id'=>$_POST['student_id']));
							}
							if($rolename == 'parent'){
								$header['student_name']	= ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);
								$header['student_id']	= $student->id;
							}
							if($level == 1){ //List of student's Batches	
								$batch_arr				= array();							
																
								$criteria 				= new CDbCriteria();		
								$criteria->join 		= 'JOIN batches t1 ON t1.id = t.batch_id'; 
								$criteria->condition	= '`t1`.`is_active`=:is_active AND `t1`.`is_deleted`=:is_deleted AND `t`.`student_id`=:student_id';	
								$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':student_id'=>$student->id);
								$criteria->order		= '`t`.`status` DESC';														
								$batch_students			= BatchStudents::model()->findAll($criteria);
								if($batch_students){
									foreach($batch_students as $key => $value){
										$batch				= Batches::model()->findByPk($value->batch_id);
										$course				= Courses::model()->findByPk($batch->course_id);
										$academic_yr		= AcademicYears::model()->findByPk($batch->academic_yr_id);
										$semester_enabled	= Configurations::model()->isSemesterEnabled();
										$course_sem_enabled	= Configurations::model()->isSemesterEnabledForCourse($course->id);
										$exam_type			= ExamFormat::model()->getExamformat($batch->id); //1 => Normal,  2 => CBSC	 
										$status				= Yii::t('app', 'Current').' '.Students::model()->getAttributeLabel('batch_id');
										if($value->status == 0){
											$status		= Yii::t('app', 'Previous').' '.Students::model()->getAttributeLabel('batch_id');
										}
										
										$batch_arr[$key]['id']			= $batch->id;
										$batch_arr[$key]['name']		= html_entity_decode(ucfirst($batch->name));
										$batch_arr[$key]['course']		= html_entity_decode(ucfirst($course->course_name));
										$batch_arr[$key]['academic_yr']	= ucfirst($academic_yr->name);
										$batch_arr[$key]['status']		= $status;
										if($semester_enabled == 1 and $course_sem_enabled == 1 and $batch->semester_id != NULL){
											$semester						= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
											$batch_arr[$key]['semester']	= html_entity_decode(ucfirst($semester->name));
										}
										$batch_arr[$key]['exam_type']		= $exam_type;
									}
								}
								$post_data = json_encode(array('batches' =>$batch_arr, 'header'=>$header),JSON_UNESCAPED_SLASHES);
								echo $post_data;
								exit;
							}
							else if($level == 2){ //List of all result published exam groups
								$batch_id				= $_POST['batch_id'];
								$batch					= Batches::model()->findByPk($batch_id);
								$course					= Courses::model()->findByPk($batch->course_id);
								$header['course']		= html_entity_decode(ucfirst($course->course_name));
								$header['batch']		= html_entity_decode(ucfirst($batch->name));						
								$exam_group_arr			= array();
																	
								$criteria 				= new CDbCriteria();
								$criteria->condition	= 'batch_id=:batch_id AND is_published=:is_published'; 
								$criteria->params		= array(':batch_id'=>$batch_id, ':is_published'=>1);
								$exam_groups			= ExamGroups::model()->findAll($criteria);
								
								if($exam_groups){
									foreach($exam_groups as $key => $value){									
										$exam_group_arr[$key]['exam_group_id']		= $value->id;
										$exam_group_arr[$key]['name']				= html_entity_decode(ucfirst($value->name));
										$exam_group_arr[$key]['result_published']	= $value->result_published;																			
									}
								}
								
								$post_data = json_encode(array('exam_groups' =>$exam_group_arr, 'header'=>$header, 'batch_id'=>$batch_id),JSON_UNESCAPED_SLASHES);
								echo $post_data;
								exit;	
							}
							else if($level == 3){ //Score details
								$exam_group_id				= $_POST['exam_group_id'];	
								$batch_id					= $_POST['batch_id'];
								$batch						= Batches::model()->findByPk($batch_id);
								$course						= Courses::model()->findByPk($batch->course_id);
								$exam_group					= ExamGroups::model()->findByPk($exam_group_id);
								$header['course']			= html_entity_decode(ucfirst($course->course_name));
								$header['batch']			= html_entity_decode(ucfirst($batch->name));
								$header['examination']		= html_entity_decode(ucfirst($exam_group->name));
								$scores_arr					= array();						
								
								$criteria 					= new CDbCriteria();		
								$criteria->join 			= 'JOIN exams t1 ON t1.id = t.exam_id'; 
								$criteria->condition		= '`t1`.`exam_group_id`=:exam_group_id AND `t`.`student_id`=:student_id';
								$criteria->params			= array(':exam_group_id'=>$exam_group_id, ':student_id'=>$student->id);
								$criteria->order			= '`t`.`exam_id` ASC';
								$scores						= ExamScores::model()->findAll($criteria);
								if($scores){
									$i = 0;
									foreach($scores as $score){	
										$exam		= Exams::model()->findByPk($score->exam_id);	
										$result		= Yii::t('app', 'Passed');
										if($score->marks < $exam->minimum_marks){
											$result	= Yii::t('app', 'Failed');
										}
										
										$subject	= Subjects::model()->findByPk($exam->subject_id);
										if($subject->elective_group_id != 0 and $subject->elective_group_id != NULL){
											$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id,'batch_id'=>$batch_id, 'elective_group_id'=>$subject->elective_group_id));
											if($student_elective){
												$elective_name = Electives::model()->findByAttributes(array('id'=>$student_elective->elective_id));
												if($elective_name){
													$scores_arr[$i]['subject'] = html_entity_decode(ucfirst($subject->name)).' ('.html_entity_decode(ucfirst($elective_name->name)).')';
												}
												else{
													$scores_arr[$i]['subject'] = '';
												}
											}
										}
										else{									
											$scores_arr[$i]['subject']		= html_entity_decode(ucfirst($subject->name));								
										}
										$scores_arr[$i]['remark']		= html_entity_decode(ucfirst($score->remarks));
										if($exam_group->exam_type == 'Marks'){ //If the exam type is Marks
											$scores_arr[$i]['mark']		= $score->marks;
										}
										else if($exam_group->exam_type == 'Grades'){
											$scores_arr[$i]['grade']	= $this->getGrade($batch_id, $score->marks);
										}
										else{
											$scores_arr[$i]['mark']		= $score->marks;
											$scores_arr[$i]['grade']	= $this->getGrade($batch_id, $score->marks);
										}
										
										$scores_arr[$i]['result']		= $result;
										
										$i++;
									}
								}
								
								$post_data = json_encode(array('scores' =>$scores_arr, 'header'=>$header, 'batch_id'=>$batch_id),JSON_UNESCAPED_SLASHES);
								echo $post_data;
								exit;	
							}
						}					
						else{
							$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));											
							$batch_id	= $_POST['batch_id'];
							$level		= $_POST['level'];
							if($batch_id != NULL){
								$batch				= Batches::model()->findByPk($batch_id);
								$course				= Courses::model()->findByPk($batch->course_id);
								$header['course']	= html_entity_decode(ucfirst($course->course_name));
								$header['batch']	= html_entity_decode(ucfirst($batch->name));
								if($level == 1){ //Exam Group List
									$exam_group_arr			= array();
																	
									$criteria 				= new CDbCriteria();
									if($rolename == 'teacher'){
										$criteria->condition	= 'batch_id=:batch_id AND is_published=:is_published'; 
										$criteria->params		= array(':batch_id'=>$batch_id, ':is_published'=>1);
										
									}
									else{
										$criteria->condition	= 'batch_id=:batch_id'; 
										$criteria->params		= array(':batch_id'=>$batch_id);
									}
									$exam_groups			= ExamGroups::model()->findAll($criteria);
									
									if($exam_groups){
										foreach($exam_groups as $key => $value){
											$is_published		= 'No';
											$result_published	= 'No';
											if($value->is_published == 1){
												$is_published		= 'Yes';
											}
											if($value->result_published == 1){
												$result_published	= 'Yes';
											}
											$exam_group_arr[$key]['exam_group_id']		= $value->id;
											$exam_group_arr[$key]['name']				= html_entity_decode(ucfirst($value->name));
											$exam_group_arr[$key]['exam_type']			= html_entity_decode(ucfirst($value->exam_type));
											$exam_group_arr[$key]['is_published']		= $is_published;
											$exam_group_arr[$key]['result_published']	= $result_published;
											$exam_group_arr[$key]['exam_date']			= date($settings->displaydate, strtotime($value->exam_date));										
										}
									}
									
									$post_data = json_encode(array('exam_groups' =>$exam_group_arr, 'header'=>$header, 'batch_id'=>$batch_id),JSON_UNESCAPED_SLASHES);
									echo $post_data;
									exit;
								}
								else if($level == 2){ //List of Scheduled subjects of selected Exam group
									$scheduled_subject_arr	= array();
									$exam_group_id			= $_POST['exam_group_id'];	
									$exam_group 			= ExamGroups::model()->findByAttributes(array('id'=>$exam_group_id)); 
									$header['examination']	= ucfirst($exam_group->name);
									
									$criteria 				= new CDbCriteria();
									$criteria->condition	= 'exam_group_id=:exam_group_id';
									$criteria->params		= array(':exam_group_id'=>$exam_group_id);
									$scheduled_subjects		= Exams::model()->findAll($criteria);
									if($scheduled_subjects){
										$i = 0;
										foreach($scheduled_subjects as $value){
											$subject	= Subjects::model()->findByAttributes(array('id'=>$value->subject_id, 'is_deleted'=>0));
											if($subject){ 
												$scheduled_subject_arr[$i]['exam_id']		= $value->id;
												$scheduled_subject_arr[$i]['subject']		= html_entity_decode(ucfirst($subject->name));
												$scheduled_subject_arr[$i]['start_time']	= date($settings->displaydate.' '.$settings->timeformat, strtotime($value->start_time));
												$scheduled_subject_arr[$i]['end_time']		= date($settings->displaydate.' '.$settings->timeformat, strtotime($value->end_time));
												$scheduled_subject_arr[$i]['max_mark']		= $value->maximum_marks;
												$scheduled_subject_arr[$i]['min_mark']		= $value->minimum_marks;
												
												$i++;
											}
										}
									}
									
									$post_data = json_encode(array('scheduled_subjects' =>$scheduled_subject_arr, 'header'=>$header, 'batch_id'=>$batch_id),JSON_UNESCAPED_SLASHES);
									echo $post_data;
									exit;
								}
								else if($level == 3){ //Score Details								
									$exam_id				= $_POST['exam_id'];
									$scores_arr				= array();
									$exam 					= Exams::model()->findByAttributes(array('id'=>$exam_id));
									$exam_group 			= ExamGroups::model()->findByAttributes(array('id'=>$exam->exam_group_id));  
									$subject				= Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
									$header['examination']	= html_entity_decode(ucfirst($exam_group->name));
									$header['subject']		= html_entity_decode(ucfirst($subject->name));
									
									$criteria 				= new CDbCriteria();
									$criteria->condition	= 'exam_id=:exam_id';
									$criteria->params		= array(':exam_id'=>$exam_id);
									$exam_scores			= ExamScores::model()->findAll($criteria);								
									
									if($exam_scores){	
										$i	= 0;								
										foreach($exam_scores as $exam_score){
											$student	= Students::model()->findByAttributes(array('id'=>$exam_score->student_id, 'is_deleted'=>0, 'is_active'=>1));																	
											if($student){
												$scores_arr[$i]['student_id']	= $student->id;
												$scores_arr[$i]['student_name']	= ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);											
												$scores_arr[$i]['remark']		= html_entity_decode(ucfirst($exam_score->remarks));
												if($exam_group->exam_type == 'Marks'){ //If the exam type is Marks
													$scores_arr[$i]['mark']			= $exam_score->marks;
												}
												else if($exam_group->exam_type == 'Grades'){
													$scores_arr[$i]['grade']		= $this->getGrade($batch_id, $exam_score->marks);
												}
												else{
													$scores_arr[$i]['mark']			= $exam_score->marks;
													$scores_arr[$i]['grade']		= $this->getGrade($batch_id, $exam_score->marks);
												}
												
												if($subject->split_subject == 1){
													$split_subjects	= SubjectSplit::model()->findAllByAttributes(array('subject_id'=>$subject->id));
													$split_scores	= ExamScoresSplit::model()->findAllByAttributes(array('student_id'=>$student->id, 'exam_scores_id'=>$exam_score->id));
													if($split_subjects != NULL and $split_scores != NULL){
														$split_subject_arr	= array();
														foreach($split_subjects as $key => $value){
															$split_subject_arr[$key]['name']	= html_entity_decode(ucfirst($value->split_name));
															$split_subject_arr[$key]['mark']	= (isset($split_scores[$key]['mark']) and $split_scores[$key]['mark'] != NULL)?$split_scores[$key]['mark']:'-';
															
														}
														$scores_arr[$i]['split_subjects']	= $split_subject_arr;
													}
												}
												
												//Get Profile image path
												$path = $this->getProfileImagePath($student->id, 2);
												if($path != NULL){
													$scores_arr[$i]['image']		= Yii::app()->getBaseUrl(true).'/'.$path;												
												}
												
												$i++;
											}
										}																								
									}
									$post_data = json_encode(array('scores' =>$scores_arr, 'header'=>$header, 'batch_id'=>$batch_id),JSON_UNESCAPED_SLASHES);
									echo $post_data;
									exit;
								}
							}												          
						}										
					}
					else{
						$response["error"] = true;
						$response["error_msg"] = "Invalid Request";
						echo json_encode($response);
						exit;
					}					
				}
				else if($tag == 'check_exam_type'){ //Check whether the exam type is CBSE or Normal Exam
					$uid		= $_POST['uid'];
					$batch_id	= $_POST['batch_id'];
					$exam_type	= ExamFormat::model()->getExamformat($batch_id); //1 => Normal,  2 => CBSC
					echo json_encode(array('type'=>$exam_type),JSON_UNESCAPED_SLASHES);
					exit;
				}
				else if($tag == 'cbse_exam_17'){ //CBSE Exam 2017
					$uid		= $_POST['uid'];
					$role 		= Rights::getAssignedRoles($uid);
					$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));	
						
					if(key($role) == 'student' or key($role) == 'parent'){ //In case of Parent or Student User					
						$level		= $_POST['level'];
						$batch_id	= $_POST['batch_id'];
						$header		= array();								
						if(key($role) == 'student'){
							$student	= Students::model()->findByAttributes(array('uid'=>$uid));
						}
						else{
							$student	= Students::model()->findByAttributes(array('id'=>$_POST['student_id']));
						}
						if(key($role) == 'parent'){
							$header['student_name']	= $student->getStudentname();
							$header['student_id']	= $student->id;
						}
																	
						$batch				= Batches::model()->findByPk($batch_id);
						$course				= Courses::model()->findByPk($batch->course_id);
						$header['course']	= ($course != NULL)?trim(html_entity_decode(ucfirst($course->course_name))):"-";
						$header['batch']	= ($batch != NULL)?trim(html_entity_decode(ucfirst($batch->name))):"-";
						
						if($level == 1){ //Get list of exam groups
							$exam_group_arr	= array();
							
							$criteria				= new CDbCriteria();
							$criteria->condition	= 'batch_id=:batch_id AND type=:type AND date_published=:date_published'; 
							$criteria->params		= array(':batch_id'=>$batch_id, ':type'=>2, ':date_published'=>1);
							$model					= CbscExamGroup17::model()->findAll($criteria);
							if($model != NULL){
								foreach($model as $key => $value){
									$exam_group_arr[$key]['id']					= $value->id;
									$exam_group_arr[$key]['name']				= html_entity_decode(ucfirst($value->name));																											
									$exam_group_arr[$key]['result_published']	= ($value->result_published == 1)?'Yes':'No';									
								}
							}
							echo json_encode(array('exam_groups' =>$exam_group_arr, 'header'=>$header, 'batch_id'=>$batch_id),JSON_UNESCAPED_SLASHES);
							exit;
						}
						else if($level == 2){ //Get scheduled subject list
							$scheduled_subject_arr	= array();
							$exam_group_id			= $_POST['exam_group_id'];
							$exam_group 			= CbscExamGroup17::model()->findByAttributes(array('id'=>$exam_group_id));
							$header['examination']	= ucfirst($exam_group->name);
							
							$criteria				= new CDbCriteria();
							$criteria->condition	= 'exam_group_id=:exam_group_id';
							$criteria->params		= array(':exam_group_id'=>$exam_group_id);
							$scheduled_subjects		= CbscExams17::model()->findAll($criteria);
							if($scheduled_subjects){
								$i	= 0;
								foreach($scheduled_subjects as $value){
									$subject	= Subjects::model()->findByPk($value->subject_id);
									if($subject != NULL){
										$scheduled_subject_arr[$i]['id']			= $value->id;
										$scheduled_subject_arr[$i]['subject']		= ($subject != NULL)?html_entity_decode(ucfirst($subject->name)):'-';
										$scheduled_subject_arr[$i]['start_time']	= date($settings->displaydate.' '.$settings->timeformat, strtotime($value->start_time));
										$scheduled_subject_arr[$i]['end_time']		= date($settings->displaydate.' '.$settings->timeformat, strtotime($value->end_time));
										$scheduled_subject_arr[$i]['max_mark']		= $value->maximum_marks;
										$scheduled_subject_arr[$i]['min_mark']		= $value->minimum_marks;
										
										$i++;
									}									
								}
							}
							
							echo json_encode(array('scheduled_subjects' =>$scheduled_subject_arr, 'header'=>$header, 'batch_id'=>$batch_id),JSON_UNESCAPED_SLASHES);
							exit;
						}
						else if($level == 3){ //Get score details
							$score_arr				= array();
							$exam_group_id			= $_POST['exam_group_id'];
							$exam_group 			= CbscExamGroup17::model()->findByAttributes(array('id'=>$exam_group_id));
							$header['examination']	= ucfirst($exam_group->name);
							
							$criteria				= new CDbCriteria();
							$criteria->condition	= 'exam_group_id=:exam_group_id';
							$criteria->params		= array(':exam_group_id'=>$exam_group_id);
							$scheduled_subjects		= CbscExams17::model()->findAll($criteria);							
							
							if($scheduled_subjects != NULL){
								$i = 0;
								foreach($scheduled_subjects as $scheduled_subject){
									$subject	= Subjects::model()->findByPk($scheduled_subject->subject_id);
									if($subject != NULL){
										$criteria				= new CDbCriteria();
										$criteria->condition	= 'exam_id=:exam_id AND student_id=:student_id';
										$criteria->params		= array(':exam_id'=>$scheduled_subject->id, ':student_id'=>$student->id);
										$score					= CbscExamScores17::model()->find($criteria);
										
										if($score != NULL){
											if($exam_group->class == 4){ //In case of Class 11 - 12	
												$header['type']		= '1';
												$split_subject_arr	= array();
												$split_score		= CbscExamScoresSplit17::model()->findAllByAttributes(array('student_id'=>$student->id, 'exam_scores_id'=>$score->id));
												
												$score_arr[$i]['id']			= $score->id;
												$score_arr[$i]['subject']		= html_entity_decode(ucfirst($subject->name));																						
												$score_arr[$i]['total']			= $score->total;
												$score_arr[$i]['remark']		= html_entity_decode(ucfirst($score->remarks));
												$score_arr[$i]['status']		= ($score->is_failed == 1)?'Fail':'Pass';
												$score_arr[$i]['status_value']	= $score->is_failed;
												$score_arr[$i]['grade']			= CbscExamScores17::model()->getClass2Grade($score->total);
												
												//Mark of split subjects
												if($split_score != NULL){
													$criteria				= new CDbCriteria();
													$criteria->join			= 'JOIN	`subjects` `t1` ON `t`.`subject_id` = `t1`.`id` JOIN `cbsc_exams_17` `t2` ON `t2`.`subject_id` = `t1`.`id` JOIN `cbsc_exam_scores_17` `t3` ON `t3`.`exam_id` = `t2`.`id`';
													$criteria->condition	= '`t3`.`id`=:exam_score_id'; 	
													$criteria->params		= array(':exam_score_id'=>$score->id);
													$subject_splits			= SubjectSplit::model()->findAll($criteria);
													if($subject_splits != NULL){
														foreach($subject_splits as $j => $value){
															$split_subject_arr[$j]['name']	= html_entity_decode(ucfirst($value->split_name));
															$split_subject_arr[$j]['value']	= $split_score[$j]['mark'];
														}
														$score_arr[$i]['split_subject']	= $split_subject_arr;
													}
												}	
												
												$i++;
											}
											else{ //In case of Class 1-2, Class 3-8 & Class 9-10	
												$header['type']	= '2';	
												if($exam_group->class == 1){
													$grade	= CbscExamScores17::model()->getClass1Grade($score->total);
												}
												else{
													$grade	= CbscExamScores17::model()->getClass2Grade($score->total);
												}
												
												$score_arr[$i]['id']					= $score->id;
												$score_arr[$i]['subject']				= html_entity_decode(ucfirst($subject->name));																								
												$score_arr[$i]['written_exam']			= $score->written_exam;
												$score_arr[$i]['periodic_test']			= $score->periodic_test;
												$score_arr[$i]['note_book']				= $score->note_book;
												$score_arr[$i]['subject_enrichment']	= $score->subject_enrichment;
												$score_arr[$i]['total']					= $score->total;
												$score_arr[$i]['remark']				= html_entity_decode(ucfirst($score->remarks));
												$score_arr[$i]['status']				= ($score->is_failed == 1)?'Fail':'Pass';
												$score_arr[$i]['status_value']			= $score->is_failed;
												$score_arr[$i]['grade']					= $grade;
												
												$i++;
											}
										}																				
									}
								}
							}
							echo json_encode(array('scores' =>$score_arr, 'header'=>$header, 'batch_id'=>$batch_id),JSON_UNESCAPED_SLASHES);
							exit;
						}
						else{
							$response["error"] = true;
							$response["error_msg"] = "Invalid Request";
							echo json_encode($response);
							exit;
						}
					}
					else{ //In case of Admin, Teacher or Custom user						
						$batch_id	= $_POST['batch_id'];
						$level		= $_POST['level'];
						
						$batch				= Batches::model()->findByPk($batch_id);
						$course				= Courses::model()->findByPk($batch->course_id);
						$header['course']	= ($course != NULL)?trim(html_entity_decode(ucfirst($course->course_name))):"-";
						$header['batch']	= ($batch != NULL)?trim(html_entity_decode(ucfirst($batch->name))):"-";
						
						if($level == 1){ //Get List of Exam Group
							$exam_group_arr	= array();
							
							$criteria				= new CDbCriteria();
							$criteria->condition	= 'batch_id=:batch_id AND type=:type'; 
							$criteria->params		= array(':batch_id'=>$batch_id, ':type'=>2);
							$model					= CbscExamGroup17::model()->findAll($criteria);
							if($model != NULL){
								foreach($model as $key => $value){
									$exam_group_arr[$key]['id']					= $value->id;
									$exam_group_arr[$key]['name']				= html_entity_decode(ucfirst($value->name));
									$exam_group_arr[$key]['type']				= CbscExamGroup17::model()->getTypeName($value->type);
									$exam_group_arr[$key]['class']				= CbscExamGroup17::model()->getClassName($value->class);
									$exam_group_arr[$key]['is_final']			= ($value->is_final == 1)?'Yes':'No';
									$exam_group_arr[$key]['date_published']		= ($value->date_published == 1)?'Yes':'No';
									$exam_group_arr[$key]['result_published']	= ($value->result_published == 1)?'Yes':'No';
									$exam_group_arr[$key]['exam_date']			= ($settings != NULL)?date($settings->displaydate,strtotime($value->created_at)):$value->created_at;
								}
							}
							echo json_encode(array('exam_groups' =>$exam_group_arr, 'header'=>$header, 'batch_id'=>$batch_id),JSON_UNESCAPED_SLASHES);
							exit;
						}
						else if($level == 2){ //List of Scheduled subjects of selected Exam group
							$scheduled_subject_arr	= array();
							$exam_group_id			= $_POST['exam_group_id'];
							$exam_group 			= CbscExamGroup17::model()->findByAttributes(array('id'=>$exam_group_id));
							$header['examination']	= ucfirst($exam_group->name);
							
							$criteria				= new CDbCriteria();
							$criteria->condition	= 'exam_group_id=:exam_group_id';
							$criteria->params		= array(':exam_group_id'=>$exam_group_id);
							$scheduled_subjects		= CbscExams17::model()->findAll($criteria);
							if($scheduled_subjects){
								$i	= 0;
								foreach($scheduled_subjects as $value){
									$subject	= Subjects::model()->findByPk($value->subject_id);
									if($subject != NULL){
										$scheduled_subject_arr[$i]['id']			= $value->id;
										$scheduled_subject_arr[$i]['subject']		= ($subject != NULL)?html_entity_decode(ucfirst($subject->name)):'-';
										$scheduled_subject_arr[$i]['start_time']	= date($settings->displaydate.' '.$settings->timeformat, strtotime($value->start_time));
										$scheduled_subject_arr[$i]['end_time']		= date($settings->displaydate.' '.$settings->timeformat, strtotime($value->end_time));
										$scheduled_subject_arr[$i]['max_mark']		= $value->maximum_marks;
										$scheduled_subject_arr[$i]['min_mark']		= $value->minimum_marks;
										
										$i++;
									}									
								}
							}
							
							echo json_encode(array('scheduled_subjects' =>$scheduled_subject_arr, 'header'=>$header, 'batch_id'=>$batch_id),JSON_UNESCAPED_SLASHES);
							exit;
						}
						else if($level == 3){ //Score Details	
							$score_arr				= array();
							$exam_id				= $_POST['exam_id'];
							$exam					= CbscExams17::model()->findByPk($exam_id);
							$exam_group				= CbscExamGroup17::model()->findByPk($exam->exam_group_id);								
							$subject				= Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
							$header['examination']	= html_entity_decode(ucfirst($exam_group->name));
							$header['subject']		= html_entity_decode(ucfirst($subject->name));	
							//Get Scores						
							$criteria				= new CDbCriteria();
							$criteria->condition	= 'exam_id=:exam_id';
							$criteria->params		= array(':exam_id'=>$exam_id);
							$scores					= CbscExamScores17::model()->findAll($criteria);	
							//Get Class Average 							
							$sum	= 0;
							foreach($scores as $value){
								$sum	= $sum + $value->total;
							}
							$class_average	= $sum / count($scores);
							$class_average	= substr($class_average, 0, 5);
							$header['class_average']	= $class_average;
													
							if($exam_group->class == 4){ //In case of Class 11 - 12	
								$header['type']	= '1';														
								if($scores){
									foreach($scores as $key => $value){
										$split_subject_arr	= array();
										$student			= Students::model()->findByPk($value->student_id);	
										$batch_student		= BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$batch_id, 'status'=>1, 'result_status'=>0));
										$split_score		= CbscExamScoresSplit17::model()->findAllByAttributes(array('student_id'=>$value->student_id, 'exam_scores_id'=>$value->id));
										if($batch_student->roll_no != 0){
											$roll_no	= $batch_student->roll_no;
										}
										else{
											$roll_no	= '-';
										}
										//Profile Image Path
										$path	= $this->getProfileImagePath($value->student_id, 2);
										
										$score_arr[$key]['id']				= $value->id;
										$score_arr[$key]['roll_no']			= $roll_no;
										$score_arr[$key]['student_name'] 	= ($student != NULL)?$student->getStudentname():'-';
										$score_arr[$key]['total']			= $value->total;
										$score_arr[$key]['remark']			= html_entity_decode(ucfirst($value->remarks));
										$score_arr[$key]['status']			= ($value->is_failed == 1)?'Fail':'Pass';
										$score_arr[$key]['status_value']	= $value->is_failed;
										$score_arr[$key]['grade']			= CbscExamScores17::model()->getClass2Grade($value->total);
										if($path != NULL){
											$score_arr[$key]['path']	= Yii::app()->getBaseUrl(true).'/'.$path;	
										}
										//Mark of split subjects
										if($split_score != NULL){
											$criteria				= new CDbCriteria();
											$criteria->join			= 'JOIN	`subjects` `t1` ON `t`.`subject_id` = `t1`.`id` JOIN `cbsc_exams_17` `t2` ON `t2`.`subject_id` = `t1`.`id` JOIN `cbsc_exam_scores_17` `t3` ON `t3`.`exam_id` = `t2`.`id`';
											$criteria->condition	= '`t3`.`id`=:exam_score_id'; 	
											$criteria->params		= array(':exam_score_id'=>$value->id);
											$subject_splits			= SubjectSplit::model()->findAll($criteria);
											if($subject_splits != NULL){
												foreach($subject_splits as $i => $value){
													$split_subject_arr[$i]['name']	= html_entity_decode(ucfirst($value->split_name));
													$split_subject_arr[$i]['value']	= $split_score[$i]['mark'];
												}
												$score_arr[$key]['split_subject']	= $split_subject_arr;
											}
										}										
									}
								}								  
							}
							else{ //In case of Class 1-2, Class 3-8 & Class 9-10	
								$header['type']	= '2';															
								if($scores){
									foreach($scores as $key => $value){
										$student	= Students::model()->findByPk($value->student_id);	
										$batch_student		= BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$batch_id, 'status'=>1, 'result_status'=>0));
										if($batch_student->roll_no != 0){
											$roll_no	= $batch_student->roll_no;
										}
										else{
											$roll_no	= '-';
										}
										if($exam_group->class == 1){
											$grade	= CbscExamScores17::model()->getClass1Grade($value->total);
										}
										else{
											$grade	= CbscExamScores17::model()->getClass2Grade($value->total);
										}
										//Profile Image Path
										$path	= $this->getProfileImagePath($value->student_id, 2);
										
										$score_arr[$key]['id']					= $value->id;
										$score_arr[$key]['roll_no']				= $roll_no;
										$score_arr[$key]['student_name'] 		= ($student != NULL)?$student->getStudentname():'-';
										$score_arr[$key]['written_exam']		= $value->written_exam;
										$score_arr[$key]['periodic_test']		= $value->periodic_test;
										$score_arr[$key]['note_book']			= $value->note_book;
										$score_arr[$key]['subject_enrichment']	= $value->subject_enrichment;
										$score_arr[$key]['total']				= $value->total;
										$score_arr[$key]['remark']				= html_entity_decode(ucfirst($value->remarks));
										$score_arr[$key]['status']				= ($value->is_failed == 1)?'Fail':'Pass';
										$score_arr[$key]['status_value']		= $value->is_failed;
										$score_arr[$key]['grade']				= $grade;
										if($path != NULL){
											$score_arr[$key]['path']	= Yii::app()->getBaseUrl(true).'/'.$path;	
										}
									}
								}
							}
							echo json_encode(array('scores' =>$score_arr, 'header'=>$header, 'batch_id'=>$batch_id),JSON_UNESCAPED_SLASHES);
							exit;
						}
						else{
							$response["error"] = true;
							$response["error_msg"] = "Invalid Request";
							echo json_encode($response);
							exit;
						}												
					}				
				}
				else if($tag == 'co_scholastic_skills'){ //Get Co-Scholastic Skills List & student list with Co-Scholastic Skill score 
					$uid		= $_POST['uid'];
					$batch_id	= $_POST['batch_id']; 
					$role 		= Rights::getAssignedRoles($uid);
					if(key($role) == 'student' or key($role) == 'parent'){
						$student_id	= $_POST['student_id'];
						$return_arr	= array();
						
						$criteria				= new CDbCriteria();
						$criteria->condition	= 'batch_id=:batch_id'; 
						$criteria->params		= array(':batch_id'=>$batch_id);
						$model					= CbscCoScholastic::model()->findAll($criteria);
						if($model){
							foreach($model as $key => $value){
								$score	= CbscCoscholasticScore::model()->findByAttributes(array('coscholastic_id'=>$value->id, 'student_id'=>$student_id));
																
								$return_arr[$key]['name']	= html_entity_decode(ucfirst($value->skill));								
								$return_arr[$key]['score']	= ($score != NULL)?$score->score:'-';
								
							}
						}
						echo json_encode($return_arr, JSON_UNESCAPED_SLASHES);
						exit;
					}
					else{
						$level		= $_POST['level'];
						$return_arr	= array();
						if($level == 1){ //Get Co-Scholastic Skills List
							$criteria				= new CDbCriteria();
							$criteria->condition	= 'batch_id=:batch_id'; 
							$criteria->params		= array(':batch_id'=>$batch_id);
							$model					= CbscCoScholastic::model()->findAll($criteria);
							if($model){
								foreach($model as $key => $value){								
									$return_arr[$key]['key']	= $value->id;
									$return_arr[$key]['value']	= html_entity_decode(ucfirst($value->skill));	
								}
							}
							echo json_encode($return_arr, JSON_UNESCAPED_SLASHES);
							exit;
						}
						else if($level == 2){ //Student list with Co-Scholastic Skill score under the selected batch
							$coscholastic_id	= $_POST['coscholastic_id'];
							$students			= Yii::app()->getModule('students')->studentsOfBatch($batch_id);
							if($students){
								foreach($students as $key => $value){
									//Profile Image Path
									$path	= $this->getProfileImagePath($value->id, 2);
									//Get Score
									$model	= CbscCoscholasticScore::model()->findByAttributes(array('coscholastic_id'=>$coscholastic_id, 'student_id'=>$value->id));								
									
									$return_arr[$key]['id']				= $value->id;
									$return_arr[$key]['name']			= $value->getStudentname();
									$return_arr[$key]['admission_no']	= $value->admission_no;
									$return_arr[$key]['score']			= ($model != NULL)?$model->score:'-';
									if($path != NULL){
										$return_arr[$key]['path']	= Yii::app()->getBaseUrl(true).'/'.$path;	
									}
								}
							}
							echo json_encode($return_arr, JSON_UNESCAPED_SLASHES);
							exit;
						}
						else{
							$response["error"] = true;
							$response["error_msg"] = "Invalid Request";
							echo json_encode($response);
							exit;
						}
					}
				}
				else if($tag == 'cbse17_exam_result'){ //View the CBSE exam result
					$uid		= $_POST['uid'];
					$batch_id	= $_POST['batch_id'];
					$level		= $_POST['level'];
					if($level == 1){
						$return_arr	= array();
						$students	= Yii::app()->getModule('students')->studentsOfBatch($batch_id);
						if($students){
							foreach($students as $key => $value){
								//Profile Image Path
								$path	= $this->getProfileImagePath($value->id, 2);																
								
								$return_arr[$key]['id']				= $value->id;
								$return_arr[$key]['name']			= $value->getStudentname();
								$return_arr[$key]['admission_no']	= $value->admission_no;							
								if($path != NULL){
									$return_arr[$key]['path']	= Yii::app()->getBaseUrl(true).'/'.$path;	
								}
							}
						}
						echo json_encode($return_arr, JSON_UNESCAPED_SLASHES);
						exit;
					}
					else if($level == 2){ //Get the grade book details
						$uid			= $_POST['uid'];
						$student_id		= $_POST['student_id'];
						$batch_id		= $_POST['batch_id'];
						$header			= array();
						$return_arr		= array();
						$role 			= Rights::getAssignedRoles($uid);
						$student		= Students::model()->findByPk($student_id);
						if($student != NULL){
							$batch			= Batches::model()->findByPk($batch_id);
							$course			= Courses::model()->findByPk($batch->course_id);
							$batch_student	= BatchStudents::model()->findByAttributes(array('student_id'=>$student_id, 'batch_id'=>$batch_id));
							$settings		= UserSettings::model()->findByAttributes(array('user_id'=>1));	
							
							$header['name']				= $student->getStudentname();
							$header['admission_no']		= $student->admission_no;
							$header['roll_no']			= ($batch_student->roll_no != NULL and $batch_student->roll_no != 0)?$batch_student->roll_no:'-';
							$header['date_of_birth']	= ($settings != NULL and $student->date_of_birth != NULL and $student->date_of_birth != '0000-00-00')?date($settings->displaydate, strtotime($student->date_of_birth)):'-';
							$header['course']			= html_entity_decode(ucfirst($course->course_name));
							$header['batch']			= html_entity_decode(ucfirst($batch->name));
							
							$criteria				= new CDbCriteria();
							if(key($role) == 'student' or key($role) == 'parent'){
								$criteria->condition	= 'batch_id=:batch_id AND type=:type AND result_published=:result_published'; 
								$criteria->params		= array(':batch_id'=>$batch_id, ':type'=>2, ':result_published'=>1);
							}
							else{
								$criteria->condition	= 'batch_id=:batch_id AND type=:type'; 
								$criteria->params		= array(':batch_id'=>$batch_id, ':type'=>2);
							}
							$exam_groups			= CbscExamGroup17::model()->findAll($criteria);							
							if($exam_groups != NULL){
								foreach($exam_groups as $i => $exam_group){	
									$flag								=  '0';								
									$return_arr[$i]['exam_group_name']	= html_entity_decode(ucfirst($exam_group->name));
									
									$exams = CbscExams17::model()->findAllByAttributes(array('exam_group_id'=>$exam_group->id));									
									if($exams != NULL){
										$j 			= 0;
										$score_arr	= array();
										foreach($exams as $exam){
											$subject	= Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
											if($subject){
												$score	= CbscExamScores17::model()->findByAttributes(array('exam_id'=>$exam->id,'student_id'=>$student_id));
												if($score){
													$flag	= '1';
													
													$score_arr[$j]['subject']	= html_entity_decode(ucfirst($subject->name));
													$score_arr[$j]['score']		= $score->total;
													$score_arr[$j]['remark']	= ($score->remarks != NULL)?html_entity_decode(ucfirst($score->remarks)):'-';													
													$j++;
												}
											}										
										}
									}
									if($flag == '1'){
										$return_arr[$i]['url']	= Yii::app()->createAbsoluteUrl('androidApi/studentcbscpdf',array('id'=>$student_id, 'exam_group_id'=>$exam_group->id));			
									}
									$return_arr[$i]['score']	= $score_arr;
								}
							}							
							
							echo json_encode(array('header'=>$header, 'scores'=>$return_arr), JSON_UNESCAPED_SLASHES);
							exit;
						}
						else{
							$response["error"] = true;
							$response["error_msg"] = "Invalid Request";
							echo json_encode($response);
							exit;
						}
					}
					else{
						$response["error"] = true;
						$response["error_msg"] = "Invalid Request";
						echo json_encode($response);
						exit;
					}
				}
				else if($tag == 'teacher_batches'){ //List of teacher's batches 
					$uid				= $_POST['uid'];
					$my_batch_arr		= array();
					$tutor_batch_arr	= array();
					$academic_yr		= '';
					$role				= Rights::getAssignedRoles($uid);
					if(key($role)!=NULL and key($role) == 'teacher'){
						$employee	= Employees::model()->findByAttributes(array('uid'=>$uid));
						if(isset($_POST['academic_yr']) and $_POST['academic_yr'] != NULL){
							$academic_yr	= $_POST['academic_yr'];
						}
						//My Class
						$criteria				= new CDbCriteria();								
						$criteria->condition	= 'employee_id=:employee_id AND is_active=:is_active AND is_deleted=:is_deleted AND academic_yr_id=:academic_yr_id';
						$criteria->params		= array(':employee_id'=>$employee->id, ':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$academic_yr);
						$criteria->order		= 'name ASC';
						$my_batches				= Batches::model()->findAll($criteria);
						if($my_batches){
							foreach($my_batches as $key => $value){
								$course				= Courses::model()->findByPk($value->course_id);
								$class_teacher		= Employees::model()->findByPk($value->employee_id);
								$exam_type			= ExamFormat::model()->getExamformat($value->id); //1 => Normal,  2 => CBSC	
								$semester_enabled	= Configurations::model()->isSemesterEnabled();
								$course_sem_enabled	= Configurations::model()->isSemesterEnabledForCourse($course->id); 	
								
								$my_batch_arr[$key]['id']				= $value->id;
								$my_batch_arr[$key]['name']				= html_entity_decode(ucfirst($value->name)).' ( '.html_entity_decode(ucfirst($course->course_name)).' )';
								$my_batch_arr[$key]['batch']			= html_entity_decode(ucfirst($value->name));
								$my_batch_arr[$key]['course']			= html_entity_decode(ucfirst($course->course_name));
								$my_batch_arr[$key]['class_teacher']	= ucfirst($class_teacher->first_name).' '.ucfirst($class_teacher->middle_name).' '.ucfirst($class_teacher->last_name);
								$my_batch_arr[$key]['exam_type']		= $exam_type;
								if($semester_enabled == 1 and $course_sem_enabled == 1 and $value->semester_id != NULL){
									$semester	= Semester::model()->findByAttributes(array('id'=>$value->semester_id));
									$my_batch_arr[$key]['semester']		= html_entity_decode(ucfirst($semester->name));
								}
							}							
						}
						
						//Tutor Class
						$criteria 				= new CDbCriteria;		
						$criteria->join 		= 'JOIN timetable_entries t1 ON t.id = t1.batch_id';
						$criteria->group 		= '`t1`.`batch_id`';	 
						$criteria->condition	= '`t1`.`employee_id`=:employee_id AND `t`.`is_active`=:is_active AND `t`.`is_deleted`=:is_deleted AND `t`.`academic_yr_id`=:academic_yr_id';
						$criteria->params		= array(':employee_id'=>$employee->id, ':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$academic_yr);
						$criteria->order		= '`t`.`name` ASC';
						$tutor_batches			= Batches::model()->findAll($criteria);								
						if($tutor_batches){
							foreach($tutor_batches as $key => $value){
								$course				= Courses::model()->findByPk($value->course_id);
								$class_teacher		= Employees::model()->findByPk($value->employee_id);
								$exam_type			= ExamFormat::model()->getExamformat($value->id); //1 => Normal,  2 => CBSC	
								$is_class_teacher	= ($employee->id == $value->employee_id)?"1":"0"; 
								$semester_enabled	= Configurations::model()->isSemesterEnabled();
								$course_sem_enabled	= Configurations::model()->isSemesterEnabledForCourse($course->id); 
								
								
								$tutor_batch_arr[$key]['id']				= $value->id;
								$tutor_batch_arr[$key]['name']				= html_entity_decode(ucfirst($value->name)).' ( '.html_entity_decode(ucfirst($course->course_name)).' )';
								$tutor_batch_arr[$key]['batch']				= html_entity_decode(ucfirst($value->name));
								$tutor_batch_arr[$key]['course']			= html_entity_decode(ucfirst($course->course_name));
								$tutor_batch_arr[$key]['class_teacher']		= ucfirst($class_teacher->first_name).' '.ucfirst($class_teacher->middle_name).' '.ucfirst($class_teacher->last_name);
								$tutor_batch_arr[$key]['exam_type']			= $exam_type;
								$tutor_batch_arr[$key]['is_class_teacher']	= $is_class_teacher;
								if($semester_enabled == 1 and $course_sem_enabled == 1 and $value->semester_id != NULL){
									$semester	= Semester::model()->findByAttributes(array('id'=>$value->semester_id));
									$tutor_batch_arr[$key]['semester']		= html_entity_decode(ucfirst($semester->name));
								}
							}							
						}										
					}
					
					$post_data = json_encode(array('myClass' =>$my_batch_arr, 'tutorClass'=>$tutor_batch_arr),JSON_UNESCAPED_SLASHES);
					echo $post_data;
					exit;
				}	
				else if($tag == 'exam_schedules'){
					$uid			= $_POST['uid'];
					$exam_group_id	= $_POST['exam_group_id'];		
					$exam_group 	= ExamGroups::model()->findByAttributes(array('id'=>$exam_group_id)); 			
					if($uid != NULL and $exam_group_id != NULL and $exam_group != NULL){
						$exams_arr				= array();
						$settings				= UserSettings::model()->findByAttributes(array('user_id'=>1));						
						$batch					= Batches::model()->findByPk($exam_group->batch_id);
						$course					= Courses::model()->findByPk($batch->course_id);
						$header['course']		= html_entity_decode(ucfirst($course->course_name));
						$header['batch']		= html_entity_decode(ucfirst($batch->name));
						$header['examination']	= ucfirst($exam_group->name);
						
						$criteria				= new CDbCriteria();
						$criteria->condition	= 'exam_group_id=:exam_group_id';
						$criteria->params		= array(':exam_group_id'=>$exam_group_id);
						$criteria->order		= 'start_time ASC';
						$exams					= Exams::model()->findAll($criteria);
						if($exams){
							$i = 0;
							foreach($exams as $exam){
								$subject	= Subjects::model()->findByAttributes(array('id'=>$exam->subject_id, 'is_deleted'=>0));
								if($subject){
									$exams_arr[$i]['exam_id']		= $exam->id;
									$exams_arr[$i]['subject']		= html_entity_decode(ucfirst($subject->name));
									$exams_arr[$i]['start_time']	= date($settings->displaydate.' '.$settings->timeformat, strtotime($exam->start_time));
									$exams_arr[$i]['end_time']		= date($settings->displaydate.' '.$settings->timeformat, strtotime($exam->end_time));
									$exams_arr[$i]['max_mark']		= $exam->maximum_marks;
									$exams_arr[$i]['min_mark']		= $exam->minimum_marks;
									
									$i++;
								}
							}
						}
						$post_data = json_encode(array('scheduled_subjects' =>$exams_arr, 'header'=>$header),JSON_UNESCAPED_SLASHES);
						echo $post_data;
						exit;
					}
					else{
						$response["error"] = true;
						$response["error_msg"] = "Invalid Request";
						echo json_encode($response);
						exit;
					}
				}
                elseif($tag == 'library'){ //Library					
					if(isset($post['uid']) && $post['uid']!= NULL){
						$data 		= $this->getbooks($post);
						$return_arr = array();
						$i 			= 1;
						foreach($data as $key=>$val){							
							$return_arr[$key]['bookname']	= $val->book_name;
							$return_arr[$key]['issuedate']	= $val->issue_date;
							$return_arr[$key]['duedate']	= $val->due_date;						
						}
						$post_data = json_encode(array('library' =>$return_arr),JSON_UNESCAPED_SLASHES);
						echo $post_data;
						exit;
						
					}else{
						$response["error"] = true;
						$response["error_msg"] = "Invalid Request";
						echo json_encode($response);
						exit;
					}
				}
				elseif ($tag == 'attendance_student'){ //Student attendance details				
					$id						= $_POST['id'];
					$student				= Students::model()->findByAttributes(array('id'=>$id));
					$total_working_days_1 	= array();
					$settings				= UserSettings::model()->findByAttributes(array('user_id'=>1));
					$batch_arr				= array();
					$batch					= array();
						
					$batches	= BatchStudents::model()->StudentBatch($student->id);
					if($batches != NULL){
						foreach($batches as $key => $value){							
							$batch_arr[$key]['id']		= $value->id;
							$batch_arr[$key]['name']	= html_entity_decode(ucfirst($value->name)).' ( '.html_entity_decode(ucfirst($value->course123->course_name)).' )';
						}
						
						if(isset($_POST['batch_id']) and $_POST['batch_id'] != NULL){
							$batch 	= Batches::model()->findByPk($_POST['batch_id']);
						}
						else{
							$batch 	= Batches::model()->findByPk($batches[0]['id']);
						}
					}
					if($batch != NULL){						
						if($student->admission_date >= $batch->start_date){ 
							$batch_start	= date('Y-m-d',strtotime($student->admission_date));	
						}
						else{
							$batch_start	= date('Y-m-d',strtotime($batch->start_date));
						}	
												
						if($batch->end_date >= date('Y-m-d')){
							$batch_end		= date('Y-m-d');												
						}
						else{
							$batch_end		= date('Y-m-d', strtotime($batch->end_date));
						}	
						
						$batch_days_1  	= array();
						$batch_range_1 	= StudentAttentance::model()->createDateRangeArray($batch_start,$batch_end);  // to find total session
						$batch_days_1  	= array_merge($batch_days_1,$batch_range_1);
						
						$days 		= array();
						$days_1 	= array();
						$weekArray 	= array();
												
						$weekdays 	= Weekdays::model()->findAll("batch_id=:x AND weekday<>:y", array(':x'=>$batch->id,':y'=>"0"));
						if(count($weekdays)==0){		
							$weekdays = Weekdays::model()->findAll("batch_id IS NULL AND weekday<>:y",array(':y'=>"0"));
						}
						
						foreach($weekdays as $weekday){		
							$weekday->weekday = $weekday->weekday - 1;
							if($weekday->weekday <= 0){
								$weekday->weekday = 7;
							}
							$weekArray[] = $weekday->weekday;
						}
								
						foreach($batch_days_1 as $batch_day_1){
							$week_number = date('N', strtotime($batch_day_1));
							if(in_array($week_number,$weekArray)){ // If checking if it is a working day		
								array_push($days_1,$batch_day_1);
							}
						}
						
						$holidays 		= Holidays::model()->findAllByAttributes(array('user_id'=>1));
						$holiday_arr	= array();
						foreach($holidays as $key=>$holiday){
							if(date('Y-m-d',$holiday->start)!=date('Y-m-d',$holiday->end)){
								$date_range = StudentAttentance::model()->createDateRangeArray(date('Y-m-d',$holiday->start),date('Y-m-d',$holiday->end));
								foreach ($date_range as $value){
									$holiday_arr[] = date('Y-m-d',$date_range);
								}
							}
							else{
								$holiday_arr[] = date('Y-m-d',$holiday->start);
							}
						}
								
						foreach($days_1 as $day_1){		
							if(!in_array($day_1,$holiday_arr)){ // If checking if it is a working day		
								array_push($total_working_days_1,$day_1);
							}
						}
					}
								
					$return_arr_1		= array();
					$return_arr_2		= array();
					$student_attendance	= '';
					
					if($batch){						
						$criteria 					= new CDbCriteria;		
						$criteria->join 			= 'LEFT JOIN student_leave_types t1 ON (t.leave_type_id = t1.id OR t.leave_type_id = 0)'; 
						$criteria->condition 		= 't1.is_excluded=:is_excluded AND t.student_id=:x AND t.date >=:z AND t.date <=:A AND t.batch_id=:batch_id';
						$criteria->params 			= array(':is_excluded'=>0,':x'=>$student->id,':z'=>$batch_start,':A'=>$batch_end, 'batch_id'=>$batch->id);												
						$criteria->group			= 't.id';
						$student_attendance_count   = StudentAttentance::model()->findAll($criteria);																		
						
						$criteria 					= new CDbCriteria;												
						$criteria->condition 		= 't.student_id=:x AND t.date >=:z AND t.date <=:A AND t.batch_id=:batch_id';											
						$criteria->params 			= array(':x'=>$student->id,':z'=>$batch_start,':A'=>$batch_end, 'batch_id'=>$batch->id);
						$criteria->order			= 't.date DESC';
						$student_attendance			= StudentAttentance::model()->findAll($criteria);
					}
					$count	= 0;
					if($student_attendance != NULL){ 
						foreach($student_attendance as $key => $value) {
							$leave_type						= StudentLeaveTypes::model()->findByPk($value->leave_type_id);
							$return_arr_1[$key]['reason']	= ($value->reason != NULL)?html_entity_decode(ucfirst($value->reason)):'-';
							$return_arr_1[$key]['id']		= $value->id;
							$return_arr_1[$key]['type']		= ($leave_type != NULL)?html_entity_decode(ucfirst($leave_type->name)):'-';
							$return_arr_1[$key]['date']		= date($settings->displaydate,strtotime($value->date));
							
							$count++;	
						}
					}
					//Get Profile image path
					$path 		= $this->getProfileImagePath($student->id, 2);
					if($path != NULL){
						$return_arr_2['image']		= Yii::app()->getBaseUrl(true).'/'.$path;												
					}
					$return_arr_2['name']			= ucfirst($student->first_name)." ".ucfirst($student->middle_name)." ".ucfirst($student->last_name);
					$return_arr_2['admission']		= $student->admission_no;
					$return_arr_2['working_days']	= count($total_working_days_1);
					$return_arr_2['leaves']			= count($student_attendance_count);
					//Get Profile image path
					$path = $this->getProfileImagePath($student->id, 2);
					if($path != NULL){
						$return_arr_2['image']			= Yii::app()->getBaseUrl(true).'/'.$path;												
					}
					
					if($batch != NULL){
						$course 						= Courses::model()->findByAttributes(array('id'=>$batch->course_id, 'is_deleted'=>0));		
						$return_arr_2['course_batch'] 	= html_entity_decode(ucfirst($course->course_name)).' / '.html_entity_decode(ucfirst($batch->name));
					}
					else{
						$return_arr_2['course_batch']	= 'No Active Batch';
					}
				
					echo json_encode(array('report'=>$return_arr_1,'profile'=>$return_arr_2, 'batches'=>$batch_arr),JSON_UNESCAPED_SLASHES);	
				}
				elseif($tag == 'attendance_batch'){ //List of all batches. This is for class teacher users								
					$uid		= $_POST['uid'];
					$roles 		= Rights::getAssignedRoles($uid);
					$return_arr	= array();								
					if(key($roles) == 'teacher'){
						$employee	= Employees::model()->findByAttributes(array('uid'=>$uid));
						$batches	= Batches::model()->findAllByAttributes(array('employee_id'=>$employee->id, 'is_deleted'=>0, 'is_active'=>1));						
						if($batches){
							foreach($batches as $key=>$val){
								$return_arr[$key]['id'] 	=	$val->id;
								$return_arr[$key]['name']	=	html_entity_decode(ucfirst($val->name));
							}											
						}
					}	
					$post_data = json_encode(array('batches' =>$return_arr),JSON_UNESCAPED_SLASHES);
					echo $post_data;
					exit;				
				}
				elseif($tag == 'batch_students'){ //list of all students in a batch
					if((isset($post['uid']) and $post['uid'] != NULL)){
						$batch_id			= $_POST['batch_id'];
						$leave_type_arr		= array();
						$return_arr			= array();
						$students			= Yii::app()->getModule('students')->studentsOfBatch($batch_id);
						$leave_types		= StudentLeaveTypes::model()->findAllByAttributes(array('status'=>1));
						if($students){
							$i	= 0;
							foreach($students as $student){
								$is_leave						= 0;
								$absent_leave_type				= '';
								$absent_reason					= '';
								$absent_leave_type_id			= '';
								$is_student_absent				= StudentAttentance::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$batch_id, 'date'=>date('Y-m-d')));								
								$return_arr[$i]['student_id']	= $student->id;
								$return_arr[$i]['name']			= ucfirst($student->first_name).' '.ucfirst($student->last_name);
								
								//Profile Image Path
								$path	= $this->getProfileImagePath($student->id, 2);
								if($path != NULL){
									$return_arr[$i]['path']		= Yii::app()->getBaseUrl(true).'/'.$path;												
								}								
								if($is_student_absent != NULL){
									$type		= StudentLeaveTypes::model()->findByPk($is_student_absent->leave_type_id);		
									if($type){
										$absent_leave_type		= ucfirst($type->name);	
										$absent_leave_type_id	= $type->id;									
									}
									$absent_reason	= ucfirst($is_student_absent->reason);
									$is_leave		= 1; 									
								}
								$return_arr[$i]['is_leave']				= $is_leave;
								$return_arr[$i]['absent_leave_type']	= $absent_leave_type;
								$return_arr[$i]['absent_leave_type_id']	= $absent_leave_type_id;
								$return_arr[$i]['absent_reason']		= $absent_reason;
								
								$i++;
							}
						}
						if($leave_types){
							$j = 0; 
							foreach($leave_types as $leave_type){
								$leave_type_arr[$j]['type_id']	= $leave_type->id;
								$leave_type_arr[$j]['type']		= html_entity_decode(ucfirst($leave_type->name));
								
								$j++;
							}
						}
						$post_data = json_encode(array('students' =>$return_arr, 'reason'=>$leave_type_arr),JSON_UNESCAPED_SLASHES);
						echo $post_data;
						exit;																
					}
					else{
						echo 'Invalid Request';
						exit;
					}					
				}                                
				elseif($tag == 'upload_profile_pic'){				
					$file_name 	= DocumentUploads::model()->getFileName(basename($_FILES['image']['name']));	
					$uid		= $_POST['uid'];				
					$roles 		= Rights::getAssignedRoles($_POST['uid']); // check for single role
					foreach($roles as $role){
						$rolename	= $role->name;
					}	
														
					if($rolename == 'student'){										
						$role_id			= 1;
						$identifier			= 6;						
						$model				= Students::model()->findByAttributes(array('uid'=>$uid));
						$destination_file 	= 'uploadedfiles/student_profile_image/'.$model->id.'/'.$file_name;
						if(isset($_FILES['image']['name'])){						
							if(!is_dir('uploadedfiles/')){
								mkdir('uploadedfiles/');
							}
							if(!is_dir('uploadedfiles/student_profile_image/')){
								mkdir('uploadedfiles/student_profile_image/');
							}
							if(!is_dir('uploadedfiles/student_profile_image/'.$model->id)){
								mkdir('uploadedfiles/student_profile_image/'.$model->id);
							}						
						}
					}
					else{					
						$role_id			= 2;
						$identifier			= 4;						
						$model				= Employees::model()->findByAttributes(array('uid'=>$uid));
						$file_name 			= DocumentUploads::model()->getFileName(basename($_FILES['image']['name']));
						$destination_file 	= 'uploadedfiles/employee_profile_image/'.$model->id.'/'.$file_name;
						
						if(isset($_FILES['image']['name'])){						
							if(!is_dir('uploadedfiles/')){
								mkdir('uploadedfiles/');
							}
							if(!is_dir('uploadedfiles/employee_profile_image/')){
								mkdir('uploadedfiles/employee_profile_image/');
							}
							if(!is_dir('uploadedfiles/employee_profile_image/'.$model->id)){
								mkdir('uploadedfiles/employee_profile_image/'.$model->id);
							}						
						}					
					}					
					
					try {
						// Throws exception incase file is not being moved
						if (!move_uploaded_file($_FILES['image']['tmp_name'], $destination_file)) {
						// make error flag true
							$response['error'] 		= true;
							$response['message'] 	= 'Could not move the file!';
						}
						
						DocumentUploads::model()->insertData($role_id, $model->id, $file_name, $identifier, NULL, $uid, 1, 0);   
						
						$response['message']	= 'File uploaded successfully! :ip ';
						$response['error'] 		= false;
						$response['file_path'] 	= $file_upload_url . basename($_FILES['image']['name']);
					} catch (Exception $e) {
						// Exception occurred. Make error flag true
						$response['error'] 		= true;
						$response['message'] 	= $e->getMessage();
					}					
					echo json_encode($response);					
				}                                
				elseif($tag == 'add_attendance'){ //Add Attendance
					$uid		= $_POST['uid'];					
					$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));	
					$level		= $_POST['level'];
					if($level == 1){ //List of students under the selected batch with attendance status
						$status			= 1;
						$student_arr	= array();
						$leave_type_arr	= array();						
						$batch_id		= $_POST['batch_id'];
						$date			= $_POST['date'];							
						$day 			= date('w', strtotime($date));
						$batch			= Batches::model()->findByPk($batch_id);
						$is_holiday		= StudentAttentance::model()->isHoliday($date); //Check holiday
						$isWeekday		= $this->checkWeekday($day + 1, $batch_id); //Check whether the selected day is a weekday	
						
						if($batch->start_date > $date){
							$status		= 0;
							$message	= 'Batch not started yet';
						}
						else if($batch->end_date < $date){
							$status		= 0;
							$message	= 'Batch end';
						}
						if($date > date('Y-m-d')){
							$status		= 0;
							$message	= 'Cannot mark attendance for upcoming dates';
						}
						else if($isWeekday != 1){
							$status		= 0;
							$message	= 'Selected date is not a weekday';
						}
						else if($is_holiday){
							$status		= 0;
							$message	= 'Selected date is a Holiday';
						}
						else{							
							$students	= Yii::app()->getModule('students')->studentsOfBatch($batch_id);
							if($students != NULL){
								$i	= 0;
								foreach($students as $student){
									$is_leave						= 0;
									$absent_leave_type				= '';
									$absent_reason					= '';
									$absent_leave_type_id			= '';									
									$is_student_absent				= StudentAttentance::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$batch_id, 'date'=>$date));								
									$student_arr[$i]['student_id']	= $student->id;
									$student_arr[$i]['name']		= $student->getStudentname();
									
									if(Configurations::model()->rollnoSettingsMode() != 2){
										$batch_student	= BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$batch_id, 'status'=>1, 'result_status'=>0));										
										$student_arr[$i]['roll_no']	= ($batch_student->roll_no != 0)?$batch_student->roll_no:'-';
									}
									
									//Profile Image Path
									$path	= $this->getProfileImagePath($student->id, 2);
									if($path != NULL){
										$student_arr[$i]['path']	= Yii::app()->getBaseUrl(true).'/'.$path;												
									}								
									if($is_student_absent != NULL){
										$type		= StudentLeaveTypes::model()->findByPk($is_student_absent->leave_type_id);		
										if($type){
											$absent_leave_type		= ucfirst($type->name);	
											$absent_leave_type_id	= $type->id;									
										}
										$absent_reason	= ucfirst($is_student_absent->reason);
										$is_leave		= 1; 									
									}
									$student_arr[$i]['is_leave']				= $is_leave;
									$student_arr[$i]['absent_leave_type']		= $absent_leave_type;
									$student_arr[$i]['absent_leave_type_id']	= $absent_leave_type_id;
									$student_arr[$i]['absent_reason']			= $absent_reason;
									
									$i++;
								}
								
								//Get Leave Types
								$leave_types	= StudentLeaveTypes::model()->findAllByAttributes(array('status'=>1));
								if($leave_types != NULL){									
									foreach($leave_types as $key => $leave_type){
										$leave_type_arr[$key]['type_id']	= $leave_type->id;
										$leave_type_arr[$key]['type']		= html_entity_decode(ucfirst($leave_type->name));																				
									}
								}								
							}
						}
																								
						if($status == 1){
							echo json_encode(array('status'=>$status, 'students'=>$student_arr, 'leave_type'=>$leave_type_arr), JSON_UNESCAPED_SLASHES);
						}
						else{
							echo json_encode(array('status'=>$status, 'message'=>$message), JSON_UNESCAPED_SLASHES);
						}
						exit;
					}
					else if($level == 2){
						$is_present	= $_POST['is_present']; //1 => Mark Present, 0 => Mark Absent
						
						$model	= StudentAttentance::model()->findByAttributes(array('student_id'=>$_POST['StudentAttentance']['student_id'], 'batch_id'=>$_POST['StudentAttentance']['batch_id'], 'date'=>$_POST['StudentAttentance']['date']));
						if($is_present == 1){	
							if($model != NULL){
								if($model->delete()){
									//Mobile Push Notification					
									if(Configurations::model()->isAndroidEnabled()){
										$student 		= Students::model()->findByAttributes(array('id'=>$model->student_id));	
										$sender_name	= PushNotifications::model()->getUserName($uid);									
										$date           = ($settings!=NULL)?(date($settings->displaydate, strtotime($model->date))):date('Y-m-d', $model->date);    				
										
										//To Parent						
										$user_device	= PushNotifications::model()->getGuardianDevice($student->id);
										//Get Messages
										$push_notifications		= PushNotifications::model()->getNotificationDatas(20);
										foreach($user_device as $value){								
											//Get key value of the notification data array					
											$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
											
											$message	= $push_notifications[$key]['message'];	
											$message	= str_replace("{Marked By}", $sender_name, $message);						
											$message	= str_replace("{Student Name}", $student->getStudentname(), $message);
											$message	= str_replace("{Date}", $date, $message);
																			
											$argument_arr   =   array('message'=>$message, 'sender_name'=>$sender_name, 'device_id'=>array($value->device_id), 'id'=>'', 'student_id'=>$student->id);              
											Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "daywise_attendance");																		
										}
										
										//To Student
										$user_device	= PushNotifications::model()->getStudentDevice($student->uid);		
										//Get Messages
										$push_notifications		= PushNotifications::model()->getNotificationDatas(21);
										foreach($user_device as $value){								
											//Get key value of the notification data array					
											$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
											
											$message	= $push_notifications[$key]['message'];	
											$message	= str_replace("{Marked By}", $sender_name, $message);													
											$message	= str_replace("{Date}", $date, $message);
																			
											$argument_arr   =   array('message'=>$message, 'sender_name'=>$sender_name, 'device_id'=>array($value->device_id), 'id'=>'');                
											Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "daywise_attendance");																		
										}										
									}
																	
									echo json_encode(array('status'=>'success', 'is_present'=>$is_present));			
									exit;
								}
							}
						}
						else{	
							if($model == NULL){					
								$model	= new StudentAttentance;
							}
							$model->student_id		= $_POST['StudentAttentance']['student_id'];
							$model->batch_id		= $_POST['StudentAttentance']['batch_id'];
							$model->date			= $_POST['StudentAttentance']['date'];
							$model->reason			= $_POST['StudentAttentance']['reason'];
							$model->leave_type_id	= $_POST['StudentAttentance']['leave_type_id'];
							if($model->save()){
								//Mobile Push Notification					
								if(Configurations::model()->isAndroidEnabled()){
									$sender_name	= PushNotifications::model()->getUserName($uid);						
									$date           = ($settings!=NULL)?(date($settings->displaydate, strtotime($model->date))):date('Y-m-d', $model->date);    
									$student 		= Students::model()->findByAttributes(array('id'=>$model->student_id));				
									
									//To Parent						
									$user_device	= PushNotifications::model()->getGuardianDevice($student->id);
									//Get Messages
									$push_notifications		= PushNotifications::model()->getNotificationDatas(18);
									foreach($user_device as $value){								
										//Get key value of the notification data array					
										$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);											
										$message	= $push_notifications[$key]['message'];	
										$message	= str_replace("{Marked By}", $sender_name, $message);						
										$message	= str_replace("{Student Name}", $student->getStudentname(), $message);
										$message	= str_replace("{Date}", $date, $message);
																		
										$argument_arr   =   array('message' => $message, 'sender_name' =>$sender_name, 'device_id' => array($value->device_id), 'id'=>$model->id, 'student_id'=>$student->id);               
										Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "daywise_attendance");																		
									}
									
									//To Student
									$user_device	= PushNotifications::model()->getStudentDevice($student->uid);		
									//Get Messages
									$push_notifications		= PushNotifications::model()->getNotificationDatas(19);
									foreach($user_device as $value){								
										//Get key value of the notification data array					
										$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
										
										$message	= $push_notifications[$key]['message'];	
										$message	= str_replace("{Marked By}", $sender_name, $message);													
										$message	= str_replace("{Date}", $date, $message);
																		
										$argument_arr   =   array('message'=>$message, 'sender_name'=>$sender_name, 'device_id'=>array($value->device_id), 'id'=>$model->id);                 
										Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "daywise_attendance");																		
									}										
								}
								
								echo json_encode(array('status'=>'success', 'is_present'=>$is_present));			
								exit;
							}
							else{
								$errors	= $model->getErrors();																			
								echo json_encode(array('status'=>"error", 'errors'=>$errors));	
								exit;
							}
						}											
					}
					else{
						$response["error"] 		= true;
						$response["error_msg"] 	= "Invalid Request";
						echo json_encode($response);
						exit;
					}
				}                                
				elseif($tag=='students_list') { //Students List				
					$uid		= $_POST['uid'];
					$parent_id	= $_POST['parent_id'];
					$roles	= Rights::getAssignedRoles($uid);
					
					foreach($roles as $key=> $value){
						$role	= $value->name;
					}
					
					if($role == 'parent' or $parent_id != NULL){
						if($role == 'parent'){
							$guardian				= Guardians::model()->findByAttributes(array('uid'=>$uid));
						}
						else{
							$guardian				= Guardians::model()->findByAttributes(array('id'=>$parent_id));
						}
						
						$criteria 				= new CDbCriteria;		
						$criteria->join 		= 'LEFT JOIN guardian_list t1 ON t.id = t1.student_id'; 
						$criteria->condition 	= 't1.guardian_id=:guardian_id AND t.is_active=:is_active AND is_deleted=:is_deleted';
						$criteria->params 		= array(':guardian_id'=>$guardian->id, ':is_active'=>1, 'is_deleted'=>0);
						$criteria->order		= '`t`.`first_name` ASC';
						$students 				= Students::model()->findAll($criteria);					
												
						if($students){														
							foreach($students as $key => $student){
								//Profile Image Path
								$path	= $this->getProfileImagePath($student->id, 2);
										
								$return_arr[$key]['id']				= $student->id;							   															
								$return_arr[$key]['uid']			= $student->uid;
								$return_arr[$key]['name'] 			= ucfirst($student->first_name)." ".ucfirst($student->middle_name)." ".ucfirst($student->last_name);
								$return_arr[$key]['admission_no']	= $student->admission_no;
								$return_arr[$key]['email']			= $student->email;	
								if($path != NULL){
									$return_arr[$key]['path']		= Yii::app()->getBaseUrl(true).'/'.$path;	
								}
							}
							$post_data = json_encode(array('students' =>$return_arr),JSON_UNESCAPED_SLASHES);
							echo $post_data;
							exit;                                         
						}
						else{
							$post_data = json_encode(array('students' =>array()),JSON_UNESCAPED_SLASHES);
							echo $post_data;
							exit;
						}  						
					}					
					else{					
						$batch_id		= $_POST['batch_id']; 
						$return_arr		= array();	
						$students		= Yii::app()->getModule('students')->studentsOfBatch($batch_id);	
										
						foreach ($students as $key => $value) {	
							//Profile Image Path
							$path	= $this->getProfileImagePath($value->id, 2);
							
							$return_arr[$key]['id']				= $value->id;
							$return_arr[$key]['uid']			= $value->uid;												
							$return_arr[$key]['name']			= ucfirst($value->first_name)." ".ucfirst($value->middle_name)." ".ucfirst($value->last_name);
							$return_arr[$key]['admission_no']	= $value->admission_no;
							$return_arr[$key]['email']			= $value->email;
							if($path != NULL){
								$return_arr[$key]['path']		= Yii::app()->getBaseUrl(true).'/'.$path;	
							}																			
						}						
						echo json_encode(array('students'=>$return_arr),JSON_UNESCAPED_SLASHES);					
					}										
				}
                elseif($tag=="add_news"){ //Add News
					if(isset($post['uid']) && $post['uid']!= NULL){						
						$user_id	= "";
						$user		= User::model()->findByAttributes(array('email'=>$_POST['from']));
						if($user){
							$user_id= $user->id;
						}
						$model					= new Publish;
						$model->author_id		= $user_id;
						$model->title			= $_POST['topic'];
						$model->content			= $_POST['message'];
						$model->conversation_id	= 0;
						$model->message_id		= 0;
						$model->is_published	= 0;
						$model->created_at		= date('Y-m-d H:i:s');
						if($model->save()){
							echo "success";
						}
						else{ 
							echo "error";
						}
					}
					else{ 
						echo "error"; 
					}					
				}
                elseif($tag=="add_mail"){
					if(isset($_POST['conversation_id']) && $_POST['conversation_id']!= NULL){						
						$sender				= 0;
						$receiver			= 0;
						$conversation_id	= $_POST['conversation_id'];
						$conver_model		= Mailbox::model()->findByPk($conversation_id);
						if($conver_model){
							$sender		= $conver_model->initiator_id;
							$receiver	= $conver_model->interlocutor_id;									
						}
						$sender_email	= $_POST['from'];
												
						$conv 					= new Mailbox();
						$conv->subject			= $_POST['subject'];
						$conv->initiator_id		= $sender;
						$conv->interlocutor_id 	= $receiver;					
						$conv->modified 		= time();
						$conv->bm_read 			= 1;
						if($conv->save()){
							$sender_id		= 0;
							$receiver_id	= 0;
							$sender_name	= "";
							$sender			= User::model()->findByAttributes(array('email'=>$_POST['from']));
							if($sender){
								$sender_id	= $sender->id;
							}
							$receiver	= User::model()->findByAttributes(array('email'=>$_POST['to']));
							if($receiver){
								$receiver_id	= $receiver->id;
							}
							
							$profile	= Profile::model()->findByAttributes(array('user_id'=>$sender_id));
							if($profile){
								$sender_name	= ucfirst($profile->firstname)." ".ucfirst($profile->lastname);
							}
							
							$msg 					= new Message;
							$msg->text 				= $_POST['compose'];                                                
							$msg->created 			= time();			
							$msg->sender_id 		= $sender_id;
							$msg->recipient_id 		= $receiver_id;
							$crc64 					= sprintf('%u',hash('crc32', $msg->text)) . sprintf('%u',hash('crc32b', $msg->text));
							$msg->crc64				= base_convert($crc64,16,10); // 64bit INT
							$msg->conversation_id 	= $conversation_id;
							if($msg->save()){  
								//Mobile Push Notification
								if(Configurations::model()->isAndroidEnabled()){						
									$device_arr		= array();
									$receiver		= User::model()->findByAttributes(array('id'=>$receiver_id));
									$sender			= User::model()->findByAttributes(array('id'=>$sender_id));
									$sender_name	= Configurations::model()->getUserName($sender_id);
									
									$criteria				= new CDbCriteria();
									$criteria->condition	= 'uid=:uid';
									$criteria->params		= array(':uid'=>$receiver->id); 
									$criteria->group		= 'device_id';
									$model	 				= UserDevice::model()->findAll($criteria);
									//Get Messages
									$push_notifications		= PushNotifications::model()->getNotificationDatas(1);
									
									foreach($model as $value){								
										//Get key value of the notification data array					
										$key			= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
										
										$message		= $push_notifications[$key]['message'];
										$message		= str_replace("{Sender}", $sender_name, $message);
														
										$argument_arr 	= array('message'=>$message, 'content' => html_entity_decode(ucfirst(strip_tags($msg->text))), 'device_id'=>array($value->device_id), 'sender_email'=>$receiver->email, 'sender_name'=>$sender_name, 'conversation_id'=>$conversation_id, 'subject'=>html_entity_decode(ucfirst($conv->subject)));                
										Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], 'inbox');
									}						
								}											 
								echo json_encode(array('status'=>'success'));
							}
						}
						else{
							 echo json_encode(array('status'=>'error'));
						}
					}
					else{
						$sender_id		= 0;
						$receiver_id	= 0;
						$sender_name	= "";
						if(isset($post['uid']) && $post['uid']!= NULL){    
							$sender_email	= $_POST['from'];
							$sender			= User::model()->findByAttributes(array('email'=>$_POST['from']));
							if($sender){
								$sender_id	= $sender->id;
							}
							$receiver	= User::model()->findByAttributes(array('email'=>$_POST['to']));
							if($receiver){
								$receiver_id	= $receiver->id;
							}
							
							$profile	= Profile::model()->findByAttributes(array('user_id'=>$sender_id));
							if($profile){
								$sender_name	= ucfirst($profile->firstname)." ".ucfirst($profile->lastname);
							}
							
							if($receiver_id == NULL){
								echo json_encode(array('status'=>'error','response'=>'Invalid Receiver'));
							}
							elseif($sender_id == $receiver_id){
								echo json_encode(array('status'=>'error','response'=>"Can't send message to self!"));
							}
							else{
								$conv 					= new Mailbox();
								$conv->subject			= $_POST['subject'];
								$conv->initiator_id		= $sender_id;
								$conv->interlocutor_id 	= $receiver_id;								
								$conv->modified 		= time();
								$conv->bm_read 			= 1;
								if($conv->save()){
									$msg 					= new Message;
									$msg->text 				= $_POST['compose'];                                                
									$msg->created 			= time();			
									$msg->sender_id 		= $conv->initiator_id;
									$msg->recipient_id 		= $conv->interlocutor_id;
									$crc64 					= sprintf('%u',hash('crc32', $msg->text)) . sprintf('%u',hash('crc32b', $msg->text));
									$msg->crc64				= base_convert($crc64,16,10); // 64bit INT
									$msg->conversation_id 	= $conv->conversation_id;
									if($msg->save()){                                                            
										//Mobile Push Notification
										if(Configurations::model()->isAndroidEnabled()){
											$device_arr		= array();
											$receiver		= User::model()->findByAttributes(array('id'=>$receiver_id));
											$sender			= User::model()->findByAttributes(array('id'=>$sender_id));
											$sender_name	= Configurations::model()->getUserName($sender_id);
											
											$criteria				= new CDbCriteria();
											$criteria->condition	= 'uid=:uid';
											$criteria->params		= array(':uid'=>$receiver->id); 
											$criteria->group		= 'device_id';
											$model	 				= UserDevice::model()->findAll($criteria);
											//Get Messages
											$push_notifications		= PushNotifications::model()->getNotificationDatas(1);
											
											foreach($model as $value){		
												//Get key value of the notification data array					
												$key			= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
												
												$message		= $push_notifications[$key]['message'];
												$message		= str_replace("{Sender}", $sender_name, $message);
																
												$argument_arr 	= array('message'=>$message, 'content' => html_entity_decode(ucfirst(strip_tags($msg->text))), 'device_id'=>array($value->device_id), 'sender_email'=>$receiver->email, 'sender_name'=>$sender_name, 'conversation_id'=>$msg->conversation_id, 'subject'=>html_entity_decode(ucfirst($conv->subject)));                
												Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], 'inbox');
											}						
										}										
										echo json_encode(array('status'=>'success'));
									}
								}
								else{
									 echo json_encode(array('status'=>'error'));
								}
							}
						}
						else{	
							echo json_encode(array('status'=>'error')); 
						}
					}
				}                                
				elseif($tag=="add_events"){ //Add Event
					if(isset($post['uid']) && $post['uid']!= NULL){						
						$eventId		= 0;
						$user_id 		= $_POST['uid'];
						$title 			= $_POST['title'];
						$type 			= $_POST['type'];
						$placeholder 	= $_POST['privacy'];
						$desc 			= $_POST['description'];						
						$start 			= strtotime($_POST['startdate']."".$_POST['starttime']);
						$end 			= strtotime($_POST['startdate']."".$_POST['endtime']);						
						$allDay 		= $_POST['all_day'];
						$editable 		= $_POST['editable'];
						if(isset($_POST['eventId']) && $_POST['eventId'] != ""){
							$eventId	= $_POST['eventId'];
						}                                                                                
						$organizer		= $_POST['organizer'];                                                                         
						$event 				= ($eventId == 0) ? new Events : Events::model()->findByPk($eventId);
						$event->title 		= $title;
						$event->desc 		= $desc;
						$event->type 		= $type;
						$event->user_id 	= $user_id;
						$event->start 		= $start;
						$event->end 		= $end;
						$event->allDay 		= $allDay;
						$event->editable 	= $editable;
						$event->placeholder = $placeholder;
						$event->organizer	= $organizer; 											                                                                                      
						if($event->save()){    
							if(Configurations::model()->isAndroidEnabled()){
								$users_arr = array();	
								$college	= Configurations::model()->findByPk(1);
								if($placeholder != '0'){
									$criteria	= new CDbCriteria();
									$criteria->condition 	= 'itemname=:itemname AND userid<>:userid';
									$criteria->params		= array(':itemname'=>$placeholder, ':userid'=>$post['uid']);
									$users 					= AuthAssignment::model()->findAll($criteria);
									if($users){
										foreach($users as $user){
											if(!in_array($user->userid, $users_arr)){
												$users_arr[] = $user->userid;
											}
										}
									}
								}				
								$criteria	= new CDbCriteria();					 
								if($placeholder != '0'){
									$criteria->addInCondition('uid', $users_arr);
								}
								else{
									$criteria->condition		= 'uid<>:uid';
									$criteria->params[':uid']	= $post['uid'];
								}
								$criteria->group	= 'device_id'; 
								$user_device 		= UserDevice::model()->findAll($criteria);
								//Get Messages
								$push_notifications		= PushNotifications::model()->getNotificationDatas(17);
								foreach($user_device as $value){								
									//Get key value of the notification data array					
									$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
									
									$message	= $push_notifications[$key]['message'];
									$message	= str_replace("{Title}", html_entity_decode(ucfirst($event->title)), $message);		
									$message	= str_replace("{School Name}", ucfirst($college->config_value), $message);	
									
									$argument_arr = array('message'=>$message, 'device_id'=>array($value->device_id), 'start_date'=>date('Y-m-d', $event->start));                
									Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "events");																		
								}					
							}
							echo json_encode(array('status'=>'success'));
						}
						else{                                               
							echo json_encode(array('status'=>'error'));			
						}                                            
					}
					else{
						echo "error";echo json_encode(array('status'=>'error'));
					}
				}                                
				elseif($tag == 'pending_news'){ //news.........						
					if(isset($post['uid']) && $post['uid']!= NULL){
						$criteria			= new CDbCriteria;
						$criteria->order 	= 'created_at DESC';		
						$model				= Publish::model()->findAll($criteria);
						$return_arr 		= array();
						if($model != NULL){							
							$i	= 1;
							foreach($model as $key=>$val){                                                    
								$date	= date('j M Y, g:ia', strtotime($val->created_at));
								
								$return_arr[$key]['id'] 		= $val->id;
								$return_arr[$key]['title']		= html_entity_decode(ucfirst($val->title));
								$return_arr[$key]['content']	= html_entity_decode(ucfirst($val->content));
								$return_arr[$key]['created_at']	= $date;                                                    
							}							
						}
						$post_data = json_encode(array('news' =>$return_arr),JSON_UNESCAPED_SLASHES);
						echo $post_data;
						exit;
					}
					else{
						$response["error"] 		= true;
						$response["error_msg"] 	= "Invalid Request";
						echo json_encode($response);
						exit;
					}				
				}                                      
				elseif($tag == 'teacher_course'){					
					$uid				= $_POST['uid'];
					$employee			= Employees::model()->findAllByAttributes(array('uid'=>$uid));
					$courses			= Batches::model()->findAllByAttributes(array('employee_id'=>$employee[0]->id));
					$department			= EmployeeDepartments::model()->findByPk($employee[0]->employee_department_id);					
					$return_arr			= array();
					$return_arr_header	= array();
										
					$return_arr_header['name']			= $employee[0]->first_name." ".$employee[0]->middle_name." ".$employee[0]->last_name;
					$return_arr_header['title']			= $employee[0]->job_title;
					$return_arr_header['department']	= $department->name;
					$return_arr_header['teacher_no']	= $employee[0]->employee_number;
					
					$batches 		= Batches::model()->findAllByAttributes(array('employee_id'=>$employee[0]->id));
					$batch_array	= array();
					foreach($batches as $batch){
						$batch_array[]	= $batch->id;
					}
					$timetables = TimetableEntries::model()->findAllByAttributes(array('employee_id'=>$employee[0]->id));
					foreach($timetables as $timetable){
						if(!in_array($timetable->batch_id,$batch_array))
							$batch_array[]=$timetable->batch_id;
					}										  
				   	$batches = Batches::model()->findAllByAttributes(array('id'=>$batch_array));
					foreach ($batches as $key => $value) {	
						$employee	= Employees::model()->findByPk($value->employee_id);					
						
						$return_arr[$key]['batchname']				= $value->name;	
						$return_arr[$key]['program_coordinater']	= $employee->first_name." ".$employee->middle_name." ".$employee->last_name;
						$return_arr[$key]['startdate']				= $value->start_date;
						$return_arr[$key]['enddate']				= $value->end_date;						
					}					
					echo json_encode(array('header'=>$return_arr_header,'courses'=>$return_arr));					
				}
				else if($tag == 'getFields'){
					$uid	= $_POST['uid'];
					$criteria				= new CDbCriteria();
					$criteria->condition	= 'model=:model AND student_profile=:student_profile AND is_exception=:is_exception';					
					$criteria->params		= array(':model'=>'Students', ':student_profile'=>1, ':is_exception'=>0);
					$fields					= FormFields::model()->findAll($criteria);
					foreach($fields as $field){
						echo $field->varname.'<br>';
					}
					exit;
				}
				elseif($tag == 'profile'){ //Profile Deatils of Student, Parent,  Teacher				
					$uid				= $_POST['uid'];
					$type				= $_POST['type']; // Types are 1 & 2. ( 1 => Logined user profile, 2 => Student Profile, 3 => Parent Profile, 4 => Teacher Profile )
					$return_arr			= array(); 						
					$roles 				= Rights::getAssignedRoles($uid);																	
					$role				= key($roles);
					$settings			= UserSettings::model()->findByAttributes(array('user_id'=>1));									
					
					if(($type == 1 and $role == 'student') or ($type == 2 and isset($_POST['id']) and $_POST['id'] != NULL)){						
						$guardianarray	= array();						
						if($type == 1){
							$profile	= Students::model()->findByAttributes(array('uid'=>$uid));
						}
						else if($type == 2){
							$profile	= Students::model()->findByAttributes(array('id'=>$_POST['id']));
						}
						
						if($profile){
							$batch_name					= '';
							$course_name				= '';
							$gender						= '';
							$primary_contact			= '';
							$emergency_contact			= '';
							$roll_no					= '';
							$personal_fields			= array();
							$contact_fields				= array();
							$emergency_primary_fields	= array();
							$scope						= 'forStudentProfile'; //If the logined user is Admin or custom users
							//Active Batch
							$criteria 				= new CDbCriteria();		
							$criteria->join 		= 'LEFT JOIN batch_students t1 ON t.id = t1.batch_id'; 
							$criteria->condition	= '`t`.`is_active`=:is_active AND `t`.`is_deleted`=:is_deleted AND `t1`.`student_id`=:student_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status';	
							$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':student_id'=>$profile->id, ':status'=>1, ':result_status'=>0);														
							$batch					= Batches::model()->find($criteria);
							if($batch){
								$batch_name			= ucfirst($batch->name);								
								$course				= Courses::model()->findByAttributes(array('id'=>$batch->course_id));
								if($course){
									$course_name	= ucfirst($course->course_name);
								}
								$batch_student		= BatchStudents::model()->findByAttributes(array('student_id'=>$profile->id, 'batch_id'=>$batch->id, 'status'=>1, 'result_status'=>0));
								if($batch_student->roll_no != 0){
									$roll_no	= $batch_student->roll_no;
								}
							}
							
							$nationality			= Nationality::model()->findByPk($profile->nationality_id);
							$country				= Countries::model()->findByPk($profile->country_id);
														
							$student_categories		= StudentCategories::model()->findByPk($profile->student_category_id);
							if($profile->gender == 'M'){
								$gender	= Yii::t('app', 'Male');
							}
							else if($profile->gender == 'F'){
								$gender	= Yii::t('app', 'Female');
							}							
							//Get Scope
							if($role == 'student'){
								$scope	= 'forStudentPortal';
							}
							else if($role == 'parent'){
								$scope	= 'forParentPortal';
							}
							else if($role == 'teacher'){
								$scope	= 'forTeacherPortal';
							}
														
							$return_arr['name'] 			= $profile->studentFullName($scope);
							$return_arr['admission_no']		= $profile->admission_no;
							$return_arr['roll_no']			= $roll_no;
																				
							//Fields in Personal details
							$i = 0;
							if(FormFields::model()->isVisible('admission_date', 'Students', $scope)){
								$personal_fields[$i]['title']	= $profile->getAttributeLabel('admission_date'); 
								$personal_fields[$i]['value']	= ($profile->admission_date != NULL and $profile->admission_date != '0000-00-00')?date($settings->displaydate, strtotime($profile->admission_date)):"";
								$i++;
							}
							if(FormFields::model()->isVisible('national_student_id','Students',$scope)){
								$personal_fields[$i]['title']	= $profile->getAttributeLabel('national_student_id'); 
								$personal_fields[$i]['value']	= ($profile->national_student_id != NULL)?$profile->national_student_id:'-';
								$i++;
							}
							if(FormFields::model()->isVisible('date_of_birth','Students',$scope)){
								$personal_fields[$i]['title']	= $profile->getAttributeLabel('date_of_birth'); 
								$personal_fields[$i]['value']	= ($profile->date_of_birth != NULL and $profile->date_of_birth != '0000-00-00')?date($settings->displaydate, strtotime($profile->date_of_birth)):"";
								$i++;
							}
							if(FormFields::model()->isVisible('gender','Students',$scope)){
								$personal_fields[$i]['title']	= $profile->getAttributeLabel('gender'); 
								$personal_fields[$i]['value']	= ($gender != NULL)?$gender:'-';
								$i++;
							}
							if(FormFields::model()->isVisible('blood_group','Students',$scope)){
								$personal_fields[$i]['title']	= $profile->getAttributeLabel('blood_group'); 
								$personal_fields[$i]['value']	= ($profile->blood_group != NULL)?$profile->blood_group:'-';
								$i++;
							}
							if(FormFields::model()->isVisible('birth_place','Students',$scope)){
								$personal_fields[$i]['title']	= $profile->getAttributeLabel('birth_place'); 
								$personal_fields[$i]['value']	= ($profile->birth_place != NULL)?ucfirst($profile->birth_place):'-';
								$i++;
							}
							if(FormFields::model()->isVisible('nationality_id','Students',$scope)){
								$personal_fields[$i]['title']	= $profile->getAttributeLabel('nationality_id'); 
								$personal_fields[$i]['value']	= ($nationality->name != NULL)?ucfirst($nationality->name):'';
								$i++;
							}
							if(FormFields::model()->isVisible('language','Students',$scope)){
								$personal_fields[$i]['title']	= $profile->getAttributeLabel('language'); 
								$personal_fields[$i]['value']	= ($profile->language != NULL)?ucfirst($profile->language):'-';
								$i++;
							}
							if(FormFields::model()->isVisible('religion','Students',$scope)){
								$personal_fields[$i]['title']	= $profile->getAttributeLabel('religion'); 
								$personal_fields[$i]['value']	= ($profile->religion != NULL)?ucfirst($profile->religion):'-';
								$i++;
							}
							if(FormFields::model()->isVisible('student_category_id','Students',$scope)){
								$personal_fields[$i]['title']	= $profile->getAttributeLabel('student_category_id'); 
								$personal_fields[$i]['value']	= ($student_categories != NULL)?ucfirst($student_categories->name):'-';
								$i++;
							}
							//Dynamic Fields
							$fields	= FormFields::model()->getDynamicFields(1, 1, $scope);
							
							if($fields){
								foreach($fields as $key => $field){							
									if($field->form_field_type!=NULL){
										if(FormFields::model()->isVisible($field->varname,'Students',$scope)){								
											$personal_fields[$i]['title']	= ucfirst($profile->getAttributeLabel($field->varname));                                  																																			
											$field_name = $field->varname;
											if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
												$personal_fields[$i]['value']	= html_entity_decode(ucfirst(FormFields::model()->getFieldValue($profile->$field_name)));
											}
											else if($field->form_field_type==6){  // date value
												if($settings!=NULL and $profile->$field_name!=NULL and $profile->$field_name!="0000-00-00"){
													$date1  = date($settings->displaydate,strtotime($profile->$field_name));
													$personal_fields[$i]['value'] = $date1;
												}
												else{
													if($profile->$field_name!=NULL and $profile->$field_name!="0000-00-00"){
														$personal_fields[$i]['value'] = $profile->$field_name;
													}
													else{
														$personal_fields[$i]['value'] = '-';
													}
												}
											}
											else{
												$personal_fields[$i]['value'] = (isset($profile->$field_name) and $profile->$field_name!="")?html_entity_decode(ucfirst($profile->$field_name)):"-";
											}	
											$i++;					
										} 
									} 				                                            
								}
							}
							
							//Fields in Contact details
							$i = 0;
							if(FormFields::model()->isVisible('address_line1','Students',$scope)){
								$contact_fields[$i]['title']	= $profile->getAttributeLabel('address_line1'); 
								$contact_fields[$i]['value']	= ($profile->address_line1 != NULL)?ucfirst($profile->address_line1):'-';
								$i++;
							}
							if(FormFields::model()->isVisible('address_line2','Students',$scope)){
								$contact_fields[$i]['title']	= $profile->getAttributeLabel('address_line2'); 
								$contact_fields[$i]['value']	= ($profile->address_line2 != NULL)?ucfirst($profile->address_line2):'-';
								$i++;
							}
							if(FormFields::model()->isVisible('city','Students',$scope)){
								$contact_fields[$i]['title']	= $profile->getAttributeLabel('city'); 
								$contact_fields[$i]['value']	= ($profile->city != NULL)?ucfirst($profile->city):'-';
								$i++;
							}
							if(FormFields::model()->isVisible('state','Students',$scope)){
								$contact_fields[$i]['title']	= $profile->getAttributeLabel('state'); 
								$contact_fields[$i]['value']	= ($profile->state != NULL)?ucfirst($profile->state):'-';
								$i++;
							}
							if(FormFields::model()->isVisible('pin_code','Students',$scope)){
								$contact_fields[$i]['title']	= $profile->getAttributeLabel('pin_code'); 
								$contact_fields[$i]['value']	= ($profile->pin_code != NULL)?ucfirst($profile->pin_code):'-';
								$i++;
							}
							if(FormFields::model()->isVisible('country_id','Students',$scope)){
								$contact_fields[$i]['title']	= $profile->getAttributeLabel('country_id'); 
								$contact_fields[$i]['value']	= ($country->name != NULL)?$country->name:'-';
								$i++;
							}
							if(FormFields::model()->isVisible('phone1','Students',$scope)){
								$contact_fields[$i]['title']	= $profile->getAttributeLabel('phone1'); 
								$contact_fields[$i]['value']	= ($profile->phone1 != NULL)?trim($profile->phone1):'';
								$contact_fields[$i]['is_phone']	= 1;
								$i++;
							}
							if(FormFields::model()->isVisible('phone2','Students',$scope)){
								$contact_fields[$i]['title']	= $profile->getAttributeLabel('phone2'); 
								$contact_fields[$i]['value']	= ($profile->phone2 != NULL)?trim($profile->phone2):'';
								$contact_fields[$i]['is_phone']	= 1;
								$i++;
							}
							if(FormFields::model()->isVisible('email','Students',$scope)){
								$contact_fields[$i]['title']	= $profile->getAttributeLabel('email'); 
								$contact_fields[$i]['value']	= ($profile->email != NULL)?$profile->email:'-';
								$i++;															
							}
							//Dynamic Fields
							$fields	= FormFields::model()->getDynamicFields(1, 2, $scope);
							if($fields){
								foreach($fields as $key => $field){							
									if($field->form_field_type!=NULL){
										if(FormFields::model()->isVisible($field->varname,'Students',$scope)){								
											$contact_fields[$i]['title']	= ucfirst($profile->getAttributeLabel($field->varname));                                  																																			
											$field_name = $field->varname;
											if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
												$contact_fields[$i]['value']	= html_entity_decode(ucfirst(FormFields::model()->getFieldValue($profile->$field_name)));
											}
											else if($field->form_field_type==6){  // date value
												if($settings!=NULL and $profile->$field_name!=NULL and $profile->$field_name!="0000-00-00"){
													$date1  = date($settings->displaydate,strtotime($profile->$field_name));
													$contact_fields[$i]['value'] = $date1;
												}
												else{
													if($profile->$field_name!=NULL and $profile->$field_name!="0000-00-00"){
														$contact_fields[$i]['value'] = $profile->$field_name;
													}
													else{
														$contact_fields[$i]['value'] = '-';
													}
												}
											}
											else{
												$contact_fields[$i]['value'] = (isset($profile->$field_name) and $profile->$field_name!="")?html_entity_decode(ucfirst($profile->$field_name)):"-";
											}	
											$i++;					
										} 
									} 				                                            
								}
							}							
							$return_arr['personal_fields']	= $personal_fields;
							$return_arr['contact_fields']	= $contact_fields;
														
							//Get Profile image path
							$path = $this->getProfileImagePath($profile->id, 2);
							if($path != NULL){
								$return_arr['image']			= Yii::app()->getBaseUrl(true).'/'.$path;												
							}						
							
							//Primary & Emergency contact details
							if($profile->parent_id == 0 or $profile->parent_id == NULL){
								$primary_contact	= 'Not Assigned'; 
							}
							else{
								$primary_gud	= Guardians::model()->findByAttributes(array('id'=>$profile->parent_id, 'is_delete'=>0));
								if(FormFields::model()->isVisible("fullname", "Guardians", 'forStudentProfile')){
									if($primary_gud){											
										$primary_contact	= $primary_gud->ParentFullname('forStudentProfile');
									}
									else{
										$primary_contact	= '-';
									}
								}
							}
							
							if($profile->immediate_contact_id == 0 or $profile->immediate_contact_id == NULL){
								$emergency_contact	= 'Not Assigned'; 
							}
							else{
								$emergency_gud	= Guardians::model()->findByAttributes(array('id'=>$profile->immediate_contact_id,'is_delete'=>0));
								if(FormFields::model()->isVisible("fullname", "Guardians", 'forStudentProfile')){
									if($emergency_gud){											
										$emergency_contact	= $emergency_gud->ParentFullname('forStudentProfile');
									}
									else{
										$emergency_contact	= '-';
									}
								}
							}
							//Fields in Primary & Emergency Contact
							$emergency_primary_fields[0]['title']	= 'Primary Contact';
							$emergency_primary_fields[0]['value']	= $primary_contact;
							$emergency_primary_fields[1]['title']	= 'Emergency Contact';
							$emergency_primary_fields[1]['value']	= $emergency_contact;
							
							$return_arr['emergency_primary_fields']	= $emergency_primary_fields;
							
							//Guardian List							
							$criteria 				= new CDbCriteria();		
							$criteria->join 		= 'LEFT JOIN guardian_list t1 ON t.id = t1.guardian_id';
							$criteria->condition	= '`t`.`is_delete`=:is_delete AND `t1`.`student_id`=:student_id';
							$criteria->params		= array(':is_delete'=>0, ':student_id'=>$profile->id);
							$guardians				= Guardians::model()->findAll($criteria);	
							if($guardians){
								foreach ($guardians as $key => $value) {
									$guardian_list					= GuardianList::model()->findByAttributes(array('student_id'=>$profile->id, 'guardian_id'=>$value->id));
									$guardianarray[$key]['id']		= $value->id;										
									$guardianarray[$key]['name']	= ucfirst($value->first_name).' '.ucfirst($value->last_name);	
									$guardianarray[$key]['relation']= ucfirst($guardian_list->relation);		 														
								}
							}
							$return_arr['parents']	= $guardianarray;																										
						}						
						echo json_encode($return_arr, JSON_UNESCAPED_SLASHES);						
					}
					else if(($type == 1 and $role == 'parent') or ($type == 3 and isset($_POST['id']) and $_POST['id'] != NULL)){					
						$student_array	= array();
						if($type == 1){						
							$guardian	= Guardians::model()->findByAttributes(array('uid'=>$uid));
						}
						else if($type == 3){
							$guardian	= Guardians::model()->findByAttributes(array('id'=>$_POST['id']));
						}
						if($guardian){							
							$country			= Countries::model()->findByPk($guardian->country_id);
							$personal_fields	= array();
							$contact_fields		= array();
							//Get Scope						
							$scope				= 'forStudentProfile';							
							if($role == 'student'){
								$scope	= 'forStudentPortal';
							}
							else if($role == 'parent'){
								$scope	= 'forParentPortal';
							}
							else if($role == 'teacher'){
								$scope	= 'forTeacherPortal';
							}	
							
							$return_arr['name']			= $guardian->parentFullName($scope);
							if(FormFields::model()->isVisible('email','Guardians',$scope)){
								$return_arr['email']	= $guardian->email;
							}
							//Fields in Personal details
							$i = 0;					
							if(FormFields::model()->isVisible('dob','Guardians',$scope)){
								$personal_fields[$i]['title']	= $guardian->getAttributeLabel('dob'); 
								$personal_fields[$i]['value']	= ($guardian->dob != NULL and $guardian->dob != '0000-00-00')?date($settings->displaydate, strtotime($guardian->dob)):"-";
								$i++;
							}
							if(FormFields::model()->isVisible('education','Guardians',$scope)){
								$personal_fields[$i]['title']	= $guardian->getAttributeLabel('education'); 
								$personal_fields[$i]['value']	= ($guardian->education != NULL)?ucfirst($guardian->education):'-';
								$i++;
							}
							if(FormFields::model()->isVisible('occupation','Guardians',$scope)){
								$personal_fields[$i]['title']	= $guardian->getAttributeLabel('occupation'); 
								$personal_fields[$i]['value']	= ($guardian->occupation != NULL)?ucfirst($guardian->occupation):'-';
								$i++;
							}
							if(FormFields::model()->isVisible('income','Guardians',$scope)){
								$personal_fields[$i]['title']	= $guardian->getAttributeLabel('income'); 
								$personal_fields[$i]['value']	= ($guardian->income != NULL)?ucfirst($guardian->income):'-';
								$i++;
							}
							//Dynamic Fields
							$fields	= FormFields::model()->getDynamicFields(2, 1, $scope);
							if($fields){
								foreach($fields as $key => $field){							
									if($field->form_field_type!=NULL){
										if(FormFields::model()->isVisible($field->varname,'Guardians',$scope)){								
											$personal_fields[$i]['title']	= ucfirst($guardian->getAttributeLabel($field->varname));                                  																																			
											$field_name = $field->varname;
											if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
												$personal_fields[$i]['value']	= html_entity_decode(ucfirst(FormFields::model()->getFieldValue($guardian->$field_name)));
											}
											else if($field->form_field_type==6){  // date value
												if($settings!=NULL and $guardian->$field_name!=NULL and $guardian->$field_name!="0000-00-00"){
													$date1  = date($settings->displaydate,strtotime($guardian->$field_name));
													$personal_fields[$i]['value'] = $date1;
												}
												else{
													if($guardian->$field_name!=NULL and $guardian->$field_name!="0000-00-00"){
														$personal_fields[$i]['value'] = $guardian->$field_name;
													}
													else{
														$personal_fields[$i]['value'] = '-';
													}
												}
											}
											else{
												$personal_fields[$i]['value'] = (isset($guardian->$field_name) and $guardian->$field_name!="")?html_entity_decode(ucfirst($guardian->$field_name)):"-";
											}	
											$i++;					
										} 
									} 				                                            
								}
							}
							
							//Fields in Contact details
							$i = 0;
							if(FormFields::model()->isVisible('mobile_phone','Guardians',$scope)){
								$contact_fields[$i]['title']	= $guardian->getAttributeLabel('mobile_phone'); 
								$contact_fields[$i]['value']	= ($guardian->mobile_phone != NULL)?trim($guardian->mobile_phone):'';
								$contact_fields[$i]['is_phone']	= 1;
								$i++;
							}
							if(FormFields::model()->isVisible('office_phone1','Guardians',$scope)){
								$contact_fields[$i]['title']	= $guardian->getAttributeLabel('office_phone1'); 
								$contact_fields[$i]['value']	= ($guardian->office_phone1 != NULL)?trim($guardian->office_phone1):'';
								$contact_fields[$i]['is_phone']	= 1;
								$i++;
							}
							if(FormFields::model()->isVisible('office_phone2','Guardians',$scope)){
								$contact_fields[$i]['title']	= $guardian->getAttributeLabel('office_phone2'); 
								$contact_fields[$i]['value']	= ($guardian->office_phone2 != NULL)?trim($guardian->office_phone2):'';
								$contact_fields[$i]['is_phone']	= 1;
								$i++;
							}
							if(FormFields::model()->isVisible('office_address_line1','Guardians',$scope)){
								$contact_fields[$i]['title']	= $guardian->getAttributeLabel('office_address_line1'); 
								$contact_fields[$i]['value']	= ($guardian->office_address_line1 != NULL)?ucfirst($guardian->office_address_line1):'-';
								$i++;
							}
							if(FormFields::model()->isVisible('office_address_line2','Guardians',$scope)){
								$contact_fields[$i]['title']	= $guardian->getAttributeLabel('office_address_line2'); 
								$contact_fields[$i]['value']	= ($guardian->office_address_line2 != NULL)?ucfirst($guardian->office_address_line2):'-';
								$i++;
							}							
							if(FormFields::model()->isVisible('city','Guardians',$scope)){
								$contact_fields[$i]['title']	= $guardian->getAttributeLabel('city'); 
								$contact_fields[$i]['value']	= ($guardian->city != NULL)?ucfirst($guardian->city):'-';
								$i++;
							}
							if(FormFields::model()->isVisible('state','Guardians',$scope)){
								$contact_fields[$i]['title']	= $guardian->getAttributeLabel('state'); 
								$contact_fields[$i]['value']	= ($guardian->state != NULL)?ucfirst($guardian->state):'-';
								$i++;
							}
							if(FormFields::model()->isVisible('country_id','Guardians',$scope)){
								$contact_fields[$i]['title']	= $guardian->getAttributeLabel('country_id'); 
								$contact_fields[$i]['value']	= ($country != NULL)?ucfirst($country->name):'-';
								$i++;
							}
							//Dynamic Fields
							$fields	= FormFields::model()->getDynamicFields(2, 2, $scope);
							if($fields){
								foreach($fields as $key => $field){							
									if($field->form_field_type!=NULL){
										if(FormFields::model()->isVisible($field->varname,'Guardians',$scope)){								
											$contact_fields[$i]['title']	= ucfirst($guardian->getAttributeLabel($field->varname));                                  																																			
											$field_name = $field->varname;
											if(in_array($field->form_field_type, array(3, 4, 5))){  // dropdown, radio, checkbox
												$contact_fields[$i]['value']	= html_entity_decode(ucfirst(FormFields::model()->getFieldValue($guardian->$field_name)));
											}
											else if($field->form_field_type==6){  // date value
												if($settings!=NULL and $guardian->$field_name!=NULL and $guardian->$field_name!="0000-00-00"){
													$date1  = date($settings->displaydate,strtotime($guardian->$field_name));
													$contact_fields[$i]['value'] = $date1;
												}
												else{
													if($guardian->$field_name!=NULL and $guardian->$field_name!="0000-00-00"){
														$contact_fields[$i]['value'] = $guardian->$field_name;
													}
													else{
														$contact_fields[$i]['value'] = '-';
													}
												}
											}
											else{
												$contact_fields[$i]['value'] = (isset($guardian->$field_name) and $guardian->$field_name!="")?html_entity_decode(ucfirst($guardian->$field_name)):"-";
											}	
											$i++;					
										} 
									} 				                                            
								}
							}
														
							$return_arr['personal_fields']	= $personal_fields;
							$return_arr['contact_fields']	= $contact_fields;							
							
							//Students
							$criteria 				= new CDbCriteria();		
							$criteria->join 		= 'LEFT JOIN guardian_list t1 ON t.id = t1.student_id';
							$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t`.`is_active`=:is_active AND `t1`.`guardian_id`=:guardian_id';
							$criteria->params		= array(':is_deleted'=>0, ':is_active'=>1, ':guardian_id'=>$guardian->id);
							$criteria->order		= '`t`.`first_name` ASC';
							$students				= Students::model()->findAll($criteria);
							foreach($students as $key => $value) {
								$guardian_list						= GuardianList::model()->findByAttributes(array('student_id'=>$value->id, 'guardian_id'=>$guardian->id));
								$student_array[$key]['id']			= $value->id;	
								$student_array[$key]['name']		= ucfirst($value->first_name).' '.ucfirst($value->middle_name).' '.ucfirst($value->last_name);	
								$student_array[$key]['relation']	= ucfirst($guardian_list->relation);												
							}
							
							$return_arr['students']	= $student_array;
						}
						echo json_encode($return_arr,JSON_UNESCAPED_SLASHES);						
					}
					else if(($type == 1 and $role == 'teacher') or ($type == 4 and isset($_POST['id']) and $_POST['id'] != NULL)){	
						if($type == 1){						
							$teacher	= Employees::model()->findByAttributes(array('uid'=>$uid));
						}
						else if($type == 4){
							$teacher	= Employees::model()->findByAttributes(array('id'=>$_POST['id']));
						}					
						if($teacher){
							$gender			= '';
							$experience		= '';
							
							$department		= EmployeeDepartments::model()->findByPk($teacher->employee_department_id);
							$nationality	= Nationality::model()->findByPk($teacher->nationality_id);
							$category		= EmployeeCategories::model()->findByPk($teacher->employee_category_id);
							$position		= EmployeePositions::model()->findByPk($teacher->employee_position_id);
							$home_country	= Countries::model()->findByPk($teacher->home_country_id);
							$office_country	= Countries::model()->findByPk($teacher->office_country_id);
							$grade			= EmployeeGrades::model()->findByPk($teacher->employee_grade_id);
							
							if($teacher->gender == 'M'){
								$gender	= Yii::t('app', 'Male');
							}
							else if($teacher->gender == 'F'){
								$gender	= Yii::t('app', 'Female');
							}
							
							if($teacher->experience_year and !$teacher->experience_month)
								$experience	= $teacher->experience_year." ".Yii::t('app','year(s)');
							elseif(!$model->experience_year and $model->experience_month)
								$experience	= $teacher->experience_month." ".Yii::t('app','month(s)');
							elseif($teacher->experience_year and $teacher->experience_month)
								$experience	= $teacher->experience_year." ".Yii::t('app','year(s)')." ".Yii::t('app','and')." ".$teacher->experience_month." ".Yii::t('app','month(s)');							
																											
							$return_arr['name']					= ucfirst($teacher->first_name)." ".ucfirst($teacher->middle_name)." ".ucfirst($teacher->last_name);
							$return_arr['joining_date']			= date($settings->displaydate, strtotime($teacher->joining_date));
							$return_arr['teacher_no']			= $teacher->employee_number;
							$return_arr['gender']				= $gender;
							$return_arr['dob']					= ($teacher->date_of_birth != NULL and $teacher->date_of_birth != '0000-00-00')?date($settings->displaydate, strtotime($teacher->date_of_birth)):"";
							$return_arr['department']			= ($department->name != NULL)?ucfirst($department->name):'';
							$return_arr['position']				= ($position->name != NULL)?ucfirst($position->name):'';
							$return_arr['category']				= ($category->name != NULL)?ucfirst($category->name):'';
							$return_arr['grade']				= ($grade->name != NULL)?ucfirst($grade->name):'';
							$return_arr['title']				= ucfirst($teacher->job_title);
							$return_arr['qualification']		= ucfirst($teacher->qualification);
							$return_arr['experience']			= $experience;
							$return_arr['experience_details']	= ucfirst($teacher->experience_detail);							
							$return_arr['marital_status']		= ($teacher->marital_status != NULL)?ucfirst($teacher->marital_status):'';
							$return_arr['children_count']		= ($teacher->children_count != NULL)?$teacher->children_count:'';
							$return_arr['father']				= ucfirst($teacher->father_name);
							$return_arr['mother']				= ucfirst($teacher->mother_name);
							$return_arr['husband_name']			= ucfirst($teacher->husband_name);
							$return_arr['blood_group']			= $teacher->blood_group;
							$return_arr['nationality']			= ($nationality->name!=null)?$nationality->name:'';
							$return_arr['email']				= $teacher->email;														
							$return_arr['home_address_1']		= ucfirst($teacher->home_address_line1);
							$return_arr['home_address_2']		= ucfirst($teacher->home_address_line2);
							$return_arr['home_city']			= ucfirst($teacher->home_city);
							$return_arr['home_state']			= ucfirst($teacher->home_state);
							$return_arr['home_country']			= ($home_country->name != NULL)?$home_country->name:'';
							$return_arr['home_pin_code']		= $teacher->home_pin_code;								
							$return_arr['office_address_line1']	= ucfirst($teacher->office_address_line1);
							$return_arr['office_address_line2']	= ucfirst($teacher->office_address_line2);
							$return_arr['office_city']			= ucfirst($teacher->office_city);
							$return_arr['office_state']			= ucfirst($teacher->office_state);
							$return_arr['office_country']		= ($office_country->name != NULL)?$office_country->name:'';
							$return_arr['office_pin_code']		= $teacher->office_pin_code;							
							$return_arr['office_phone1']		= $teacher->office_phone1;
							$return_arr['office_phone2']		= $teacher->office_phone2;
							$return_arr['mobile_phone']			= $teacher->mobile_phone;
							$return_arr['home_phone']			= $teacher->home_phone;
							$return_arr['fax']					= $teacher->fax;	
							
							//Get Profile image path
							$path = $this->getProfileImagePath($teacher->id, 3);
							if($path != NULL){
								$return_arr['image']	= Yii::app()->getBaseUrl(true).'/'.$path;												
							}												
						}						
						echo json_encode($return_arr,JSON_UNESCAPED_SLASHES);
					}
					else{					
						$user			= User::model()->findByPk($uid);
						$profile		= Profile::model()->findByAttributes(array('user_id'=>$uid));
						$created_at		= '';	
						$lastvisit_at	= '';					
						if($user->create_at != NULL and $user->create_at != '0000-00-00 00:00:00'){
							$created_at		= date($settings->displaydate.' '.$settings->timeformat, strtotime($user->create_at));
						}
						if($user->lastvisit_at != NULL and $user->lastvisit_at != '0000-00-00 00:00:00'){
							$lastvisit_at	= date($settings->displaydate.' '.$settings->timeformat, strtotime($user->lastvisit_at));
						}
						
						$return_arr['name']				= ucfirst($profile->firstname)." ".ucfirst($profile->lastname);
						$return_arr['email']			= $user->email;
						$return_arr['create_at']		= $created_at;
						$return_arr['last_visit_at']	= $lastvisit_at;
						$return_arr['username']			= $user->username;
						
						echo json_encode($return_arr);					
					}
				} 				
				else if($tag=="get_profile_pic"){ //Profile Picture display				
					$uid	= $_POST['uid'];
					$roles 	= Rights::getAssignedRoles($uid);					
					foreach ($roles as $key => $value){					
						$rights	= $value->name;
					}					
					if($rights == 'student' or ($_POST['type'] == 1 and $_POST['id'] != NULL)){	
						if($_POST['type'] == 1){
							$model	= Students::model()->findByAttributes(array('id'=>$_POST['id']));						
						}
						else{
							$model	= Students::model()->findByAttributes(array('uid'=>$uid));						
						}
						$path	= $model->getProfileImagePath($model->id);
						$data	= file_get_contents($path);
						if($data){
							echo json_encode(array('status'=>'success','data'=>base64_encode($data)));
						}  						
					}
					else if($rights=='teacher' or ($_POST['type'] == 2 and $_POST['id'] != NULL)){	
						if($_POST['type'] == 2){				
							$model	= Employees::model()->findByAttributes(array('id'=>$_POST['id']));
						}
						else{
							$model	= Employees::model()->findByAttributes(array('uid'=>$uid));
						}
						$path	= $model->getProfileImagePath($model->id);
						$data	= file_get_contents($path);
						if($data){
							echo json_encode(array('status'=>'success','data'=>base64_encode($data)));
						}  						
					}					
				}
				else if($tag == 'log_category'){ //Get list of student log category
					$uid		= $_POST['uid'];
					$role		= Rights::getAssignedRoles($uid);					
					$return_arr	= array();
					if(key($role) == 'teacher'){ //In case of Teacher
						$criteria 				= new CDbCriteria;
						$criteria->condition	= 'is_deleted=:is_deleted AND type=:type AND editable=:editable';
						$criteria->params		= array(':is_deleted'=>0, ':type'=>1, ':editable'=>1);
						$criteria->order		= 'name ASC';
						$model					= LogCategory::model()->findAll($criteria);
					}
					else{
						$criteria 				= new CDbCriteria;
						$criteria->condition	= 'is_deleted=:is_deleted AND type=:type';
						$criteria->params		= array(':is_deleted'=>0, ':type'=>1);
						$criteria->order		= 'name ASC';
						$model					= LogCategory::model()->findAll($criteria);
					}
					if($model != NULL){
						foreach($model as $key => $value){							
							$return_arr[$key]['id']		= $value->id;
							$return_arr[$key]['name']	= html_entity_decode(ucfirst($value->name));							
						}
					}
					
					echo json_encode($return_arr, JSON_UNESCAPED_SLASHES);
					exit;					
				}	
				else if($tag == 'create_log'){
					$uid		= $_POST['uid'];
					$batch_id	= $_POST['batch_id'];
					$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
					$timezone 	= Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
					date_default_timezone_set($timezone->timezone);
		
					$model				= new LogComment();					
					$model->user_id		= $_POST['LogComment']['user_id'];
					$model->category_id	= $_POST['LogComment']['category_id'];
					$model->comment		= $_POST['LogComment']['comment'];
					$model->notice_p2	= 0;
					$model->created_by	= $uid;
					$model->user_type	= 1;					
					$model->notice		= $_POST['LogComment']['notice'];
					if($model->notice == 1){
						$model->notice_p1	= 1;
						$model->notice_p2	= 1;
					}
					else{
						$model->notice_p1	= 0;
						$model->notice_p2	= 0;
					}
					$model->visible_p	= 1;
					$model->visible_t	= 1;
					$model->visible_s	= 1;
					
					$model->date		= date('Y-m-d H:i:s');																				
					if($model->save()){						
						$teacher 		= Employees::model()->findByAttributes(array('uid'=>$uid));
						$student		= Students::model()->findByPk($model->user_id);
						$category		= LogCategory::model()->findByPk($model->category_id);
						$batch			= Batches::model()->findByPk($batch_id);	
						$college		= Configurations::model()->findByPk(1);
						//Mobile Notifications
						if(Configurations::model()->isAndroidEnabled() and $model->notice == 1){
							$teacher 		= Employees::model()->findByAttributes(array('uid'=>$uid));
							$student		= Students::model()->findByPk($model->user_id);
							$category		= LogCategory::model()->findByPk($model->category_id);
							$batch			= Batches::model()->findByPk($batch_id);													
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
								
								$argument_arr = array('message' => $message, 'sender_name' => $teacher->getFullname(), 'device_id' => array($value->device_id), 'student_id'=>$model->user_id, 'id'=>$model->id);                 
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
										
										$argument_arr = array('message' => $message, 'sender_name' => $teacher->getFullname(), 'device_id' => array($value->device_id), 'student_id'=>$model->user_id, 'id'=>$model->id);                
										Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "logs");
									}						
								}
							}
							else{ //In case of class teacher, send notification to tutor teachers
								if($model->notice){
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
											
											$argument_arr = array('message' => $message, 'sender_name' => $teacher->getFullname(), 'device_id' => array($value->device_id), 'student_id'=>$model->user_id, 'id'=>$model->id);                
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
								$argument_arr 	= array('message' => $message, 'sender_name' => $teacher->getFullname(), 'device_id' => array($value->device_id), 'student_id'=>$model->user_id, 'id'=>$model->id);               
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
								$argument_arr 	= array('message' => $message, 'sender_name' => $teacher->getFullname(), 'device_id' => array($value->device_id), 'student_id'=>$model->user_id, 'id'=>$model->id);               
								Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "logs");																		
							}					
						}
						
						//Mail & SMS Notification
						if($model->notice){	
							//Send SMS & Mail to Parent
							$criteria				= new CDbCriteria;		
							$criteria->join 		= 'LEFT JOIN guardian_list t1 ON t.id = t1.guardian_id'; 
							$criteria->condition 	= 't1.student_id=:student_id AND is_delete=:is_delete';
							$criteria->params 		= array(':student_id'=>$student->id,'is_delete'=>0);
							$guardians				= Guardians::model()->findAll($criteria); 
							if($guardians != NULL){
								//Mail Template							
								$template		= EmailTemplates::model()->findByPk(4);
								$subject 		= $template->subject;
								$subject 		= str_replace("{{STUDENT NAME}}",$student->getStudentname(),$subject);
								$subject 		= str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);
								$message 		= $template->template;
								$message 		= str_replace("{{COMMENT}}",html_entity_decode(ucfirst($model->comment)),$message);
								$message 		= str_replace("{{STUDENT NAME}}",$student->getStudentname(),$message);
								$message 		= str_replace("{{CATEGORY}}",html_entity_decode(ucfirst($category->name)),$message);
								$message 		= str_replace("{{TEACHER}}",$teacher->getFullname(),$message);
								
								//SMS Template
								$template_sms		= SystemTemplates::model()->findByPk(23);
								$message_template 	= $template_sms->template;
								$message_sms 		= str_replace("<LOG CONTENT>",html_entity_decode(ucfirst($model->comment)),$message_template);
								$message_sms 		= str_replace("<STUDENT NAME>",$student->getStudentname(),$message_sms);
								$message_sms 		= str_replace("<CATEGORY NAME>",html_entity_decode(ucfirst($category->name)),$message_sms);								
								foreach($guardians as $guardian){
									//Send Mail
									if($guardian->email != NULL){
										UserModule::sendMail($guardian->email, $subject, $message);
									}
									//Send SMS
									if($guardian->mobile_phone != NULL){
										SmsSettings::model()->sendSms($guardian->mobile_phone, $college->config_value, $message_sms);
									}
								}								
							}
							
							//Send Mail & SMS to Student
							//Mail Template
							$template 		= EmailTemplates::model()->findByPk(5);
							$subject 		= $template->subject;
							$subject 		= str_replace("{{STUDENT NAME}}",$student->getStudentname(),$subject);
							$subject 		= str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);
							$message		 = $template->template;
							$message 		= str_replace("{{COMMENT}}",html_entity_decode(ucfirst($model->comment)),$message);
							$message 		= str_replace("{{STUDENT NAME}}",$student->getStudentname(),$message);
							$message 		= str_replace("{{CATEGORY}}",html_entity_decode(ucfirst($category->name)),$message);
							$message 		= str_replace("{{TEACHER}}",$teacher->getFullname(),$message);
							//SMS Template
							$template_sms	= SystemTemplates::model()->findByPk(24);
							$message_sms	= $template_sms->template;
							$message_sms 	= str_replace("<LOG CONTENT>",html_entity_decode(ucfirst($model->comment)),$message_sms);
							$message_sms 	= str_replace("<STUDENT NAME>",$student->getStudentname(),$message_sms);
							$message_sms 	= str_replace("<CATEGORY NAME>",html_entity_decode(ucfirst($category->name)),$message_sms);
							//Send Mail
							if($student->email != NULL){
								UserModule::sendMail($student->email, $subject, $message);
							}
							//Send SMS
							if($student->phone1 != NULL){
								SmsSettings::model()->sendSms($student->phone1, $college->config_value, $message_sms);											
							}
						}
												
						echo json_encode(array('status'=>"success"));
						exit;
					}
					else{
						$errors	= $model->getErrors();																			
						echo json_encode(array('status'=>"error", 'errors'=>$errors));	
						exit;
					}					
				}	
				else if($tag == 'update_log'){ //Update Log
					$uid	= $_POST['uid'];
					if(isset($_POST['LogComment']) and $_POST['LogComment'] != NULL){
						$model		= LogComment::model()->findByPk($_POST['LogComment']['id']);
						if($model != NULL){							
							$model->category_id	= $_POST['LogComment']['category_id'];
							$model->comment		= $_POST['LogComment']['comment'];														
							$model->created_by	= $uid;
							if($model->save()){						
								$teacher 		= Employees::model()->findByAttributes(array('uid'=>$uid));
								$student		= Students::model()->findByPk($model->user_id);
								$category		= LogCategory::model()->findByPk($model->category_id);
								$batch			= Batches::model()->findByPk($batch_id);	
								$college		= Configurations::model()->findByPk(1);
								//Mobile Notifications
								if(Configurations::model()->isAndroidEnabled() and $model->notice == 1){
									$teacher 		= Employees::model()->findByAttributes(array('uid'=>$uid));
									$student		= Students::model()->findByPk($model->user_id);
									$category		= LogCategory::model()->findByPk($model->category_id);
									$batch			= Batches::model()->findByPk($batch_id);													
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
										
										$argument_arr = array('message' => $message, 'sender_name' => $teacher->getFullname(), 'device_id' => array($value->device_id), 'student_id'=>$model->user_id, 'id'=>$model->id);                 
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
												
												$argument_arr = array('message' => $message, 'sender_name' => $teacher->getFullname(), 'device_id' => array($value->device_id), 'student_id'=>$model->user_id, 'id'=>$model->id);                
												Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "logs");
											}						
										}
									}
									else{ //In case of class teacher, send notification to tutor teachers
										if($model->notice){
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
													
													$argument_arr = array('message' => $message, 'sender_name' => $teacher->getFullname(), 'device_id' => array($value->device_id), 'student_id'=>$model->user_id, 'id'=>$model->id);                
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
										$argument_arr 	= array('message' => $message, 'sender_name' => $teacher->getFullname(), 'device_id' => array($value->device_id), 'student_id'=>$model->user_id, 'id'=>$model->id);               
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
										$argument_arr 	= array('message' => $message, 'sender_name' => $teacher->getFullname(), 'device_id' => array($value->device_id), 'student_id'=>$model->user_id, 'id'=>$model->id);               
										Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "logs");																		
									}					
								}
								
								//Mail & SMS Notification
								if($model->notice){	
									//Send SMS & Mail to Parent
									$criteria				= new CDbCriteria;		
									$criteria->join 		= 'LEFT JOIN guardian_list t1 ON t.id = t1.guardian_id'; 
									$criteria->condition 	= 't1.student_id=:student_id AND is_delete=:is_delete';
									$criteria->params 		= array(':student_id'=>$student->id,'is_delete'=>0);
									$guardians				= Guardians::model()->findAll($criteria); 
									if($guardians != NULL){
										//Mail Template							
										$template		= EmailTemplates::model()->findByPk(4);
										$subject 		= $template->subject;
										$subject 		= str_replace("{{STUDENT NAME}}",$student->getStudentname(),$subject);
										$subject 		= str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);
										$message 		= $template->template;
										$message 		= str_replace("{{COMMENT}}",html_entity_decode(ucfirst($model->comment)),$message);
										$message 		= str_replace("{{STUDENT NAME}}",$student->getStudentname(),$message);
										$message 		= str_replace("{{CATEGORY}}",html_entity_decode(ucfirst($category->name)),$message);
										$message 		= str_replace("{{TEACHER}}",$teacher->getFullname(),$message);
										
										//SMS Template
										$template_sms		= SystemTemplates::model()->findByPk(23);
										$message_template 	= $template_sms->template;
										$message_sms 		= str_replace("<LOG CONTENT>",html_entity_decode(ucfirst($model->comment)),$message_template);
										$message_sms 		= str_replace("<STUDENT NAME>",$student->getStudentname(),$message_sms);
										$message_sms 		= str_replace("<CATEGORY NAME>",html_entity_decode(ucfirst($category->name)),$message_sms);								
										foreach($guardians as $guardian){
											//Send Mail
											if($guardian->email != NULL){
												UserModule::sendMail($guardian->email, $subject, $message);
											}
											//Send SMS
											if($guardian->mobile_phone != NULL){
												SmsSettings::model()->sendSms($guardian->mobile_phone, $college->config_value, $message_sms);
											}
										}								
									}
									
									//Send Mail & SMS to Student
									//Mail Template
									$template 		= EmailTemplates::model()->findByPk(5);
									$subject 		= $template->subject;
									$subject 		= str_replace("{{STUDENT NAME}}",$student->getStudentname(),$subject);
									$subject 		= str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);
									$message		 = $template->template;
									$message 		= str_replace("{{COMMENT}}",html_entity_decode(ucfirst($model->comment)),$message);
									$message 		= str_replace("{{STUDENT NAME}}",$student->getStudentname(),$message);
									$message 		= str_replace("{{CATEGORY}}",html_entity_decode(ucfirst($category->name)),$message);
									$message 		= str_replace("{{TEACHER}}",$teacher->getFullname(),$message);
									//SMS Template
									$template_sms		= SystemTemplates::model()->findByPk(24);
									$message_sms	 	= $template_sms->template;
									$message_sms 		= str_replace("<LOG CONTENT>",html_entity_decode(ucfirst($model->comment)),$message_sms);
									$message_sms 		= str_replace("<STUDENT NAME>",$student->getStudentname(),$message_sms);
									$message_sms 		= str_replace("<CATEGORY NAME>",html_entity_decode(ucfirst($category->name)),$message_sms);
									//Send Mail
									if($student->email != NULL){
										UserModule::sendMail($student->email, $subject, $message);
									}
									//Send SMS
									if($student->phone1 != NULL){
										SmsSettings::model()->sendSms($student->phone1, $college->config_value, $message_sms);											
									}
								}
														
								echo json_encode(array('status'=>"success"));
								exit;							
							}
							else{
								$errors	= $model->getErrors();																			
								echo json_encode(array('status'=>"error", 'errors'=>$errors));	
								exit;
							}
						}
						else{
							$response["error"] 		= true;
							$response["error_msg"] 	= "Invalid Request";
							echo json_encode($response);
							exit;
						}
					}
					else{
						$id			= $_POST['id'];	
						$return_arr	= array();
						$model		= LogComment::model()->findByPk($id);
						if($model != NULL){							
							$return_arr['LogComment']['id']				= $model->id;
							$return_arr['LogComment']['category_id']	= $model->category_id;
							$return_arr['LogComment']['comment']		= html_entity_decode(ucfirst($model->comment));							
							
							echo json_encode($return_arr, JSON_UNESCAPED_SLASHES);
							exit;
						}
						else{
							$response["error"] 		= true;
							$response["error_msg"] 	= "Invalid Request";
							echo json_encode($response);
							exit;
						}						
					}					
				}	
				else if($tag == 'delete_log'){ //Delete Log
					$uid		= $_POST['uid'];
					$response	= array(); 
					$comment	= LogComment::model()->findByAttributes(array('id'=>$_POST['id']));
					$student	= Students::model()->findByPk($comment->user_id);	
					$category	= LogCategory::model()->findByPk($comment->category_id);	
					if($comment != NULL){
						if($comment->delete()){
							//Mobile Push Notification
							if($comment->notice == 1){
								$college		= Configurations::model()->findByPk(1);			
								$teacher 		= Employees::model()->findByAttributes(array('uid'=>$uid));			
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
							echo json_encode(array('status'=>"success"));
							exit;
						}
					}
					else{
						$response["error"] 		= true;
						$response["error_msg"] 	= "Invalid Request";
						echo json_encode($response);
						exit;
					}
				}	
				elseif($tag == 'log'){ //Logs List				
					$uid		= $_POST['uid']; //Logined user's uid	
					$student_id	= $_POST['student_id'];
					$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
					$roles		= Rights::getAssignedRoles($uid);
					foreach($roles as $key => $value){					
						$role	= $value->name;					
					}
					
					if($role == 'student'){											
						$criteria 				= new CDbCriteria;
						$criteria->condition	= 'user_id=:user_id AND user_type=:user_type AND visible_s=:visible_s';
						$criteria->params		= array(':user_id'=>$student_id, ':user_type'=>1, ':visible_s'=>1);
						$criteria->order		= 'id desc';	
						$logs					= LogComment::model()->findAll($criteria);
						$return_arr	= array();
						foreach($logs as $key => $value){
							$user							= Profile::model()->findByAttributes(array('user_id'=>$value->created_by));	
							$return_arr[$key]['id']			= $value->id;
							$return_arr[$key]['created_by']	= ucfirst($user->firstname).' '.ucfirst($user->lastname);
							$return_arr[$key]['comment']	= html_entity_decode(ucfirst($value->comment));
							$return_arr[$key]['date']		= date('d M Y h:m a', strtotime($value->date));
							$return_arr[$key]['category']	= html_entity_decode(ucfirst(LogCategory::model()->findByPk($value->category_id)->name));
						}
					}
					else if($role == 'teacher'){											
						$criteria 				= new CDbCriteria;
						$criteria->condition	= 'user_id=:user_id AND user_type=:user_type AND visible_t=:visible_t';
						$criteria->params		= array(':user_id'=>$student_id, ':user_type'=>1, ':visible_t'=>1);
						$criteria->order		= 'id desc';	
						$logs					= LogComment::model()->findAll($criteria);	
						$return_arr				= array();
						foreach ($logs as $key => $value) {	
							$user							= Profile::model()->findByAttributes(array('user_id'=>$value->created_by));	
							$return_arr[$key]['id']			= $value->id;
							$return_arr[$key]['created_by']	= ucfirst($user->firstname).' '.ucfirst($user->lastname);
							$return_arr[$key]['comment']	= html_entity_decode(ucfirst($value->comment));
							$return_arr[$key]['date']		= date('d M Y h:m a', strtotime($value->date));
							$return_arr[$key]['category']	= html_entity_decode(ucfirst(LogCategory::model()->findByPk($value->category_id)->name));
							if($value->category->editable){
								$return_arr[$key]['flag']	= '1';
							}
							else{
								$return_arr[$key]['flag']	= '0';
							}
						}
					}
					else if($role == 'parent'){											
						$criteria 				= new CDbCriteria;
						$criteria->condition	= 'user_id=:user_id AND user_type=:user_type AND visible_p=:visible_p';
						$criteria->params		= array(':user_id'=>$student_id, ':user_type'=>1, ':visible_p'=>1);
						$criteria->order		= 'id desc';	
						$logs					= LogComment::model()->findAll($criteria);	
						$return_arr				= array();
						foreach ($logs as $key => $value) {	
							$user							= Profile::model()->findByAttributes(array('user_id'=>$value->created_by));	
							$return_arr[$key]['id']			= $value->id;
							$return_arr[$key]['created_by']	= ucfirst($user->firstname).' '.ucfirst($user->lastname);
							$return_arr[$key]['comment']	= html_entity_decode(ucfirst($value->comment));
							$return_arr[$key]['date']		= date('d M Y h:m a', strtotime($value->date));
							$return_arr[$key]['category']	= html_entity_decode(ucfirst(LogCategory::model()->findByPk($value->category_id)->name));
						}
					}
					else{ //This is for Admin & custom users											
						$criteria 				= new CDbCriteria;
						$criteria->condition	= 'user_id=:user_id AND user_type=:user_type';
						$criteria->params		= array(':user_id'=>$student_id, ':user_type'=>1);
						$criteria->order		= 'id desc';	
						$logs					= LogComment::model()->findAll($criteria);	
						$return_arr				= array();
						foreach ($logs as $key => $value) {	
							$user							= Profile::model()->findByAttributes(array('user_id'=>$value->created_by));	
							$return_arr[$key]['id']			= $value->id;
							$return_arr[$key]['created_by']	= ucfirst($user->firstname).' '.ucfirst($user->lastname);
							$return_arr[$key]['comment']	= html_entity_decode(ucfirst($value->comment));
							$return_arr[$key]['date']		= date('d M Y h:m a', strtotime($value->date));
							$return_arr[$key]['category']	= html_entity_decode(ucfirst(LogCategory::model()->findByPk($value->category_id)->name));
						}
					}					
					echo json_encode(array('log'=>$return_arr), JSON_UNESCAPED_SLASHES);					
				}
				
				elseif($tag == 'change_profile'){
					$uid			= $_POST['uid'];
					$email			= $_POST['email'];
					$username		= $_POST['username'];
					$user			= User::model()->findByPk($uid);
					$user->username	= $username;
					$user->email	= $email;
										
					if($user->validate()){
						if($user->save()){
							$roles = Rights::getAssignedRoles($uid);	
							if(key($roles) == 'student'){
								$model	= Students::model()->findByAttributes(array('uid'=>$uid));																
								if($model){
									$model->email	= $user->email;															
									$model->save();
								}
							}
							if(key($roles) == 'parent'){
								$model	= Guardians::model()->findByAttributes(array('uid'=>$uid));								
								if($model){
									$model->email	= $user->email;
									$model->save();
								}
							}
							if(key($roles) == 'teacher'){
								$model	= Employees::model()->findByAttributes(array('uid'=>$uid));
								if($model){
									$model->email	= $user->email;
									$model->save();
								}
							}
							echo json_encode(array('status'=>'success'));
							exit;
						}
					}					
					else{						
						$errors	= $user->getErrors();
						echo json_encode(array('status'=>'error', 'errors'=>$errors));
						exit;						
					}				
				}				
				else if($tag == 'change_password'){
					$uid			= $_POST['uid'];
					$oldPassword	= $_POST['oldPassword'];
					$password		= $_POST['password'];
					$verifyPassword	= $_POST['verifyPassword'];										
					
					$model 					= new UserChangePassword;
					$model->oldPassword		= $oldPassword;
					$model->password		= $password;
					$model->verifyPassword	= $verifyPassword;
					if($model->validate()){
						$user			= User::model()->findByPk($uid);	
						$salt			= User::model()->getSalt(); 
						$user->password	= $salt.$password;							
						$user->password	= Yii::app()->getModule('user')->encrypting($user->password);                                        
						$user->activkey	= Yii::app()->getModule('user')->encrypting(microtime().$user->password);
						$user->salt		= $salt; 
						if($user->save()){
							echo json_encode(array('status'=>'success'));
							exit;
						}
					}
					else{										
						$errors	= $model->getErrors();
						echo json_encode(array('status'=>'error', 'errors'=>$errors));
						exit;											
					}										
				}
				else if($tag == 'leaves_teacher'){	
					$settings		= UserSettings::model()->findByAttributes(array('user_id'=>1));			
					$uid			= $_POST['uid'];
					$return_arr		= array();
					
					$criteria 				= new CDbCriteria;
					$criteria->condition 	= 'requested_by=:requested_by';
					$criteria->params 		= array(':requested_by'=>$uid);
					$criteria->order 		= 'id DESC';	
					$leaves					= LeaveRequests::model()->findAll($criteria);
					if($leaves){					
						foreach ($leaves as $key => $value) {												
							$return_arr[$key]['startdate']			= date($settings->displaydate, strtotime($value->from_date));
							$return_arr[$key]['enddate']			= date($settings->displaydate, strtotime($value->to_date));
							$return_arr[$key]['leave_type']			= ucfirst(LeaveTypes::model()->findByPk($value->leave_type_id)->type);														
							$return_arr[$key]['reason']				= ucfirst($value->reason);
							if($value->is_half_day == 0){
								$return_arr[$key]['is_half_day']	= 'No';	
							}
							else if($value->is_half_day == 1){
								$return_arr[$key]['is_half_day']	= 'Fore Noon';	
							}
							else if($value->is_half_day == 2){
								$return_arr[$key]['is_half_day']	= 'After Noon';	
							}
							
							if($value->status == 0){
								$return_arr[$key]['status']			= "Pending";									
							}
							else if($value->status == 1){
								$return_arr[$key]['status']			= "Approved";									
							}
							else if($value->status == 2){
								$return_arr[$key]['status']			= "Rejected";									
							}
							else if($value->status == 3){
								$return_arr[$key]['status']			= "Cancelled";									
							}
							
							$return_arr[$key]['status_value']		= $value->status;
							
							if($value->status == 1 or $value->status == 2){
								$return_arr[$key]['response']		= ucfirst($value->response);
							}
							if($value->status == 3){
								$return_arr[$key]['cancel_reason']	= ucfirst($value->cancel_reason);
							}
						}					
					}
					echo json_encode(array('leaves'=>$return_arr));					
				}
				else if($tag == 'get_leave_types'){
					$leaves_types	= LeaveTypes::model()->findAllByAttributes(array('is_deleted'=>0)); 
					$return_arr		= array();
					foreach($leaves_types as $key => $value){					
						$return_arr[$key]['id']		= $value->id;
						$return_arr[$key]['name']	= ucfirst($value->type);					
					}					
					echo json_encode(array('leave_types'=>$return_arr));					
				}
				else if($tag == 'add_leave'){				
					$uid			= $_POST['uid'];
					$leave_type_id	= $_POST['leave_type_id'];
					$reason			= $_POST['reason'];
					$from_date		= $_POST['start_date'];
					$to_date		= $_POST['end_date'];
					$ishalfday		= $_POST['ishalfday'];
					$half			= $_POST['half'];
					
					$apply_leave					= new LeaveRequests;					
					$apply_leave->leave_type_id		= $leave_type_id;
					$apply_leave->requested_by		= $uid;
					if($from_date != NULL){
						$apply_leave->from_date		= date('Y-m-d', strtotime($from_date));
					}
					else{
						$apply_leave->from_date		= '';
					}
					if($to_date != NULL){
						$apply_leave->to_date		= date('Y-m-d', strtotime($to_date));					
					}
					else{
						$apply_leave->to_date		= '';
					}
					if($ishalfday == 1){
						$apply_leave->is_half_day	= $half;
					}
					else{
						$apply_leave->is_half_day	= 0;
					}
					$apply_leave->reason			= $reason;
					$apply_leave->status			= 0;
					$apply_leave->handled_by		= 0;
					
					if($apply_leave->validate()){
						if($apply_leave->save()){
							echo json_encode(array('status'=>"success"));							
						}												
					}
					else{
						$errors	= $apply_leave->getErrors();
						echo json_encode(array('status'=>"error", 'errors'=>$errors));	
					}										
				}
				else if($tag == 'get_course_list'){
					$uid	= $_POST['uid'];
					$rights	= Rights::getAssignedRoles($uid);
					foreach($rights as $key => $value) {					
						$right	= $value->name;					
					}
					if($right == 'teacher'){						
						$batch_arr	= array();
						if(isset($_POST['academic_yr']) and $_POST['academic_yr'] != NULL){
							$academic_yr	= $_POST['academic_yr'];
						}						
						$employee	= Employees::model()->findByAttributes(array('uid'=>$uid));
						
						$criteria				= new CDbCriteria();
						$criteria->condition	= 'employee_id=:employee_id AND is_active=:is_active AND is_deleted=:is_deleted AND academic_yr_id=:academic_yr_id';
						$criteria->params		= array(':employee_id'=>$employee->id, ':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$academic_yr);
						$criteria->order		= 'name ASC';
						$criteria->group		= 'id'; 
						$my_batches				= Batches::model()->findAll($criteria);						
						
						$criteria				= new CDbCriteria();
						$criteria->join			= 'JOIN `timetable_entries` `te` ON `te`.`batch_id` = `t`.`id`';
						$criteria->condition	= '`te`.`employee_id`=:employee_id AND `t`.`is_active`=:is_active AND `t`.`is_deleted`=:is_deleted AND `t`.`academic_yr_id`=:academic_yr_id AND `t`.`employee_id`<>:employee_id';
						$criteria->params		= array(':employee_id'=>$employee->id, ':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$academic_yr);
						$criteria->order		= '`t`.`name` ASC';
						$criteria->group		= '`t`.`id`';
						$tutor_batches			= Batches::model()->findAll($criteria);
												
						$batches = array_merge($my_batches, $tutor_batches);																																						
						foreach ($batches as $key => $batch){													
							$course	= Courses::model()->findByPk($batch->course_id);
							
							$batch_arr[$key]['id']			= $batch->id;					
							$batch_arr[$key]['name']		= html_entity_decode(ucfirst($batch->name));
							$batch_arr[$key]['course_name']	= html_entity_decode(ucfirst($course->course_name));
							
							if($batch->employee_id == $employee->id){
								$batch_arr[$key]['is_class_teacher']	= 1;
							}
							else{
								$batch_arr[$key]['is_class_teacher']	= 0;
							}
						}
						if($batch_arr != NULL){ //Sort batches base on name
							usort($batch_arr, 'sortArray');
						}						
						echo json_encode(array("courses"=>$batch_arr));											
					}
					else{
						$batches	= Batches::model()->findAllByAttributes(array('is_active'=>1, 'is_deleted'=>0));
						$return_arr = array();						
						foreach($batches as $key => $batch){						
							$return_arr[$key]['name']	= html_entity_decode(ucfirst($batch->name));
							$return_arr[$key]['id']		= $batch->id;						
						}
						echo json_encode(array("courses"=>$return_arr));						
					}					
				}
				else if($tag == 'fee'){				
					$uid		= $_POST['uid'];
					$id			= $_POST['id'];
					$return_arr	= array();										
					$currency   = Configurations::model()->findByPk(5);
					$settings   = UserSettings::model()->findByAttributes(array('user_id'=>1));
					
					$criteria				= new CDbCriteria();
					$criteria->condition	= 'table_id=:table_id AND user_type=:user_type';
					$criteria->params		= array(':table_id'=>$id, ':user_type'=>1);    					
					$criteria->order		= 'id DESC';
					$fee					= FeeInvoices::model()->findAll($criteria);
					
					foreach ($fee as $key => $value) {	
						if($value->is_canceled == 1){
							$is_paid	= 'Cancelled'; 
						}
						else{
							if($value->is_paid == 1){
								$is_paid	= 'Paid';
							}
							else{
								$is_paid	= 'Unpaid';
							}
						}
						$category						= FeeCategories::model()->findByPk($value->fee_id);
						$student						= Students::model()->findByAttributes(array('id'=>$value->table_id));				
						$return_arr[$key]['id']			= $value->id;
						$return_arr[$key]['name']		= html_entity_decode(ucfirst(trim($value->name)));
						$return_arr[$key]['recipient']	= ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);
						$return_arr[$key]['category']	= html_entity_decode(ucfirst(trim($category->name)));											
						$return_arr[$key]['is_paid']	= $is_paid;
						$return_arr[$key]['status']		= $value->is_paid;
						$return_arr[$key]['duedate']	= date($settings->displaydate,strtotime($value->due_date));
						$return_arr[$key]['start_date']	= date($settings->displaydate,strtotime($value->created_at));
						
						//Get last payment details
						$last_payment_date			= '-';
						$criteria					= new CDbCriteria();
						$criteria->condition		= 'invoice_id=:id AND status=1';
						$criteria->params[':id'] 	= $value->id;
						$criteria->order 			= "id DESC";
						$criteria->limit 			= 1;
						$exemple = FeeTransactions::model()->findAll($criteria);
						if($exemple[0]['is_deleted'] == 0){
							if($exemple != NULL){
								if($settings != NULL){
									$last_payment_date	= date($settings->displaydate, strtotime($exemple[0]['date']));
								}
							}							
						}						
						
						$return_arr[$key]['last_payment_date']	= $last_payment_date;
						
						$particular_arr				= array();
						$fee_invoice_particulars	= FeeInvoiceParticulars::model()->findAllByAttributes(array('invoice_id'=>$value->id));
						
						$i				= 0;
						$amount_total	= 0;
						$fine_total		= 0;						
						$sub_total		= 0;
						$discount_total	= 0;
						$tax_total		= 0;					
						foreach($fee_invoice_particulars as $particular){	
							//Discount							
							if($particular->discount_type == 1){
								$discount	= $particular->discount_value." %";
							}
							else if($particular->discount_type == 2){
								$discount	=  number_format($particular->discount_value, 2).(($currency!=NULL)?" ".$currency->config_value:'');
							}
							else{
								$discount	= '-';
							}
							
							//Tax
							$tax_value	= FeeTaxes::model()->findByPk($particular->tax);
							if($tax_value != NULL){
								$tax 	= number_format($tax_value->value, 2)." %";
							}
							else{
								$tax	= '-';
							}																								
							
							$particular_arr[$i]['sl_no']		= $i + 1;	
							$particular_arr[$i]['name']			= html_entity_decode(ucfirst($particular->name));
							$particular_arr[$i]['description']	= ($particular->description != NULL)?html_entity_decode(ucfirst($particular->description)):'-';
							$particular_arr[$i]['unit_price']	= number_format($particular->amount, 2);
							$particular_arr[$i]['discount']		= $discount;
							$particular_arr[$i]['tax']			= $tax;
							
							$sub_total	+= $particular->amount;
							$amount	= $particular->amount;
							//apply discount
							if($particular->discount_type==1){	//percentage
								$idiscount	= (($particular->amount * $particular->discount_value)/100);
								$amount		= $amount - $idiscount;
								$discount_total	+= $idiscount;
							}
							else if($particular->discount_type==2){	//amount
								$amount	= $amount - $particular->discount_value;
								$discount_total	+= $particular->discount_value;
							}
							
							//apply tax
							if($particular->tax!=0){
								$tax	= FeeTaxes::model()->findByPk($particular->tax);
								if($tax!=NULL){
									$itax	= (($amount * $tax->value)/100);
									$amount	= $amount + $itax;
									$tax_total	+= $itax;
								}
							}
							
							$particular_arr[$i]['amount']	= number_format($amount, 2);
															
							$i++;
							
							$amount_total	+= $amount;
						}
						
						$return_arr[$key]['particular']	= $particular_arr;
						$return_arr[$key]['subtotal']	= number_format($sub_total, 2);
						$return_arr[$key]['discount']	= number_format($discount_total, 2);																								
						$return_arr[$key]['tax']		= number_format($tax_total, 2);
						$return_arr[$key]['total']		= number_format($amount_total, 2);	
						$return_arr[$key]['amount']		= number_format($amount_total, 2);											
						
						$amount_payable = 0;
						$payments       = 0;
						$adjustments    = 0;
						$transcations	= FeeTransactions::model()->findAllByAttributes(array('invoice_id'=>$value->id));					
						foreach($transcations as $index=>$ctransaction){
							if($ctransaction->is_deleted == 0 and $ctransaction->status == 1){
								if($ctransaction->amount<0){
									$adjustments    += $ctransaction->amount;
								}
								else{
									$payments       += $ctransaction->amount;
								}
							}
						}
					
						$amount_payable = $amount_total - ( $payments + $adjustments );
						
						$return_arr[$key]['amount_payable']		= number_format($amount_payable, 2);
						$return_arr[$key]['payment_details']	= number_format($payments, 2);
						$return_arr[$key]['adjustments']		= number_format($adjustments, 2);
						
						$return_arr[$key]['balance']			= number_format($amount_payable, 2);
						if($currency!=NULL && $currency->config_key=='CurrencyType'){
							$return_arr[$key]['currency']= $currency->config_value;
						}						
					}										
					echo json_encode(array('fee'=>$return_arr));					
				}
				else if($tag=='get_teachers_list'){ //List of all teachers
					$criteria 				= new CDbCriteria();
					$criteria->condition	= 'is_deleted=:is_deleted';
					$criteria->params		= array(':is_deleted' => 0); 	
					$criteria->order		= 'first_name ASC';			
					$teachers				= Employees::model()->findAll($criteria);
					$return_arr				= array();					
					foreach($teachers as $key => $value){
						//Profile Image Path
						$path								= $this->getProfileImagePath($value->id, 3);
							
						$return_arr[$key]['id']				= $value->id;
						$return_arr[$key]['uid']			= $value->uid;										
						$return_arr[$key]['name']			= ucfirst($value->first_name)." ".ucfirst($value->middle_name)." ".ucfirst($value->last_name);	
						$return_arr[$key]['employee_no']	= $value->employee_number;
						$return_arr[$key]['email']			= $value->email;	
						if($path != NULL){
							$return_arr[$key]['image']		= Yii::app()->getBaseUrl(true).'/'.$path;	
						}
					}					
					echo json_encode(array('teachers'=>$return_arr), JSON_UNESCAPED_SLASHES);				
				}
				
				else if($tag == 'achievements'){
					$id 			= $_POST['id'];
					$type			= $_POST['type'];					
					$return_arr		= array();
					$settings		= UserSettings::model()->findByAttributes(array('user_id'=>1));				
					if($type == 1){ //In case of student
						$model		= Students::model()->findByPk($id); 
						if($model != NULL){
							$criteria				= new CDbCriteria();
							$criteria->condition	= 'user_id=:user_id AND user_type=:user_type AND is_deleted=:is_deleted';
							$criteria->params		= array(':user_id'=>$model->id, ':user_type'=>1, ':is_deleted'=>0);
							$criteria->order		= 'id DESC'; 
							$achievements			= Achievements::model()->findAll($criteria);
						}
					}
					else if($type == 2){ //In case of employee
						$model		= Employees::model()->findByPk($id);						
						if($model != NULL){
							$criteria				= new CDbCriteria();
							$criteria->condition	= 'user_id=:user_id AND user_type=:user_type AND is_deleted=:is_deleted';
							$criteria->params		= array(':user_id'=>$model->id, ':user_type'=>2, ':is_deleted'=>0);
							$criteria->order		= 'id DESC'; 
							$achievements			= Achievements::model()->findAll($criteria);							
						}
					}
					
					if($achievements != NULL){						
						foreach($achievements as $key => $value) {				
							$return_arr[$key]['title']			= html_entity_decode(ucfirst($value->achievement_title));
							$return_arr[$key]['description']	= html_entity_decode(ucfirst($value->description));
							$return_arr[$key]['date']			= date($settings->displaydate, strtotime($value->created_at));
							$return_arr[$key]['doc_title']		= html_entity_decode(ucfirst($value->doc_title));	
							if($type == 1){ //In case of student
								$path = 'uploadedfiles/achievement_document/'.$value->user_id.'/'.$value->file;
								if(file_exists($path)){
									$return_arr[$key]['file_path']	= Yii::app()->getBaseUrl(true).'/'.$path;
								}
							}
							else if($type == 2){ //In case of employee
								$path = 'uploadedfiles/employee_achievement_document/'.$value->user_id.'/'.$value->file;
								if(file_exists($path)){
									$return_arr[$key]['file_path']	= Yii::app()->getBaseUrl(true).'/'.$path;
								}
							}
						}
					}
					echo json_encode(array('achievements'=>$return_arr), JSON_UNESCAPED_SLASHES);
				}
				elseif ($tag == 'setting'){ //Settings page datas
					$uid	= $_POST['uid'];
					$roles	= Rights::getAssignedRoles($uid);	
					foreach ($roles as $key=> $value){
						$role	= $value->name;
					}
					$email			= '';					
					$image_path		= '';
					$name			= '';
					$first_name		= '';
					$last_name		= '';
					$user			= User::model()->findByPk($uid);
					$settings		= UserSettings::model()->findByAttributes(array('user_id'=>1));		
					if($role == 'student'){
						$profile	= Students::model()->findByAttributes(array('uid'=>$uid));
						$email		= $profile->email;						
						$name		= ucfirst($profile->first_name).' '.ucfirst($profile->middle_name).' '.ucfirst($profile->last_name);
						$first_name	= ucfirst($profile->first_name);
						$last_name	= ucfirst($profile->last_name);
						//Get Profile image path
						$path 		= $this->getProfileImagePath($profile->id, 2);
						if($path != NULL){
							$image_path		= Yii::app()->getBaseUrl(true).'/'.$path;												
						}
					}
					else if($role == 'parent'){
						$profile	= Guardians::model()->findByAttributes(array('uid'=>$uid));
						$email		= $profile->email;						
						$name		= ucfirst($profile->first_name).' '.ucfirst($profile->last_name);
						$first_name	= ucfirst($profile->first_name);
						$last_name	= ucfirst($profile->last_name);
					}
					else if($role == 'teacher'){
						$profile	= Employees::model()->findByAttributes(array('uid'=>$uid));
						$email		= $profile->email;						
						$name		= ucfirst($profile->first_name).' '.ucfirst($profile->middle_name).' '.ucfirst($profile->last_name);
						$first_name	= ucfirst($profile->first_name);
						$last_name	= ucfirst($profile->last_name);
						//Get Profile image path
						$path 		= $this->getProfileImagePath($profile->id, 3);
						if($path != NULL){
							$image_path		= Yii::app()->getBaseUrl(true).'/'.$path;												
						}
					}
					else{
						$profile	= Profile::model()->findByAttributes(array('user_id'=>$uid));
						if($profile){							
							$name	= ucfirst($profile->firstname).' '.ucfirst($profile->lastname);
							$first_name	= ucfirst($profile->firstname);
							$last_name	= ucfirst($profile->lastname);
						}
						$email		= $user->email;
					}
										
					$return_arr			= array();
					$registration_date	= '';	
					$last_visit			= '';
					if($user->create_at != NULL and $user->create_at != '0000-00-00 00:00:00'){
						$registration_date	= date($settings->displaydate.' '.$settings->timeformat, strtotime($user->create_at));
					}
					if($user->lastvisit_at != NULL and $user->lastvisit_at != '0000-00-00 00:00:00'){
						$last_visit			= date($settings->displaydate.' '.$settings->timeformat, strtotime($user->lastvisit_at));
					}
						
					$return_arr['username']				= $user->username;
					$return_arr['name']					= $name;					
					$return_arr['email']				= $email;
					$return_arr['first_name']			= $first_name;
					$return_arr['last_name']			= $last_name;																				
					$return_arr['registration_date']	= $registration_date;						
					$return_arr['last_visit']			= $last_visit;
					$return_arr['active']				= ($user->status=='1')?"Active":"Inactive";
					if($image_path != NULL){
						$return_arr['image']			= $image_path;
					}
					
					echo json_encode($return_arr, JSON_UNESCAPED_SLASHES);					
				}
				else if($tag=='echo'){
					echo "Success";
				}
				elseif($tag == 'courses'){					
					$return_arr			= array();
					$uid				= $_POST['uid'];
					$roles 				= Rights::getAssignedRoles($uid);																						
					$header				= array();
					if(key($roles) == 'student'){
						$student	= Students::model()->findByAttributes(array('uid'=>$uid));
					}
					else if(key($roles) == 'parent'){
						$student	= Students::model()->findByAttributes(array('id'=>$_POST['id']));
					}
					
					$criteria 				= new CDbCriteria;		
					$criteria->join 		= 'LEFT JOIN batches t1 ON t.batch_id = t1.id';
					$criteria->condition	= 't1.is_active=:is_active AND t1.is_deleted=:is_deleted AND t.student_id=:student_id';
					$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':student_id'=>$student->id);
					$criteria->order		= '`t`.`academic_yr_id` DESC, `t1`.`name` ASC';					
					$batch_students			= BatchStudents::model()->findAll($criteria);
					
					//Get Profile image path
					$path 		= $this->getProfileImagePath($student->id, 2);
					if($path != NULL){
						$header['image']		= Yii::app()->getBaseUrl(true).'/'.$path;												
					}
					$header['admission_no']		= $student->admission_no;
					$header['name']				= ucfirst($student->first_name)." ".ucfirst($student->middle_name)." ".ucfirst($student->last_name);
					$header['course_student']	= "";
					$header['batch_student']	= "";
					
					foreach ($batch_students as $key => $value) {
						$batch				= Batches::model()->findByPk($value->batch_id);
						$course				= Courses::model()->findByPk($batch->course_id);
						$academic_yr		= AcademicYears::model()->findByPk($value->academic_yr_id);
						$semester_enabled	= Configurations::model()->isSemesterEnabled();
						$course_sem_enabled	= Configurations::model()->isSemesterEnabledForCourse($course->id);
						
						//Status
						$status	= PromoteOptions::model()->findByAttributes(array('option_value'=>$value->result_status));						
						if($value->result_status == 3){
							$status_value	= Yii::t('app','Previous');										
						}
						else{
							$status_value	= Yii::t('app',$status->option_name);
						}
										
						$return_arr[$key]['batch']		= html_entity_decode(ucfirst($batch->name));
						$return_arr[$key]['courses']	= html_entity_decode(ucfirst($course->course_name));
						$return_arr[$key]['year']		= ucfirst($academic_yr->name);
						$return_arr[$key]['status']		= $status_value;
						$return_arr[$key]['status_id']	= $value->result_status;
						if($semester_enabled == 1 and $course_sem_enabled == 1 and $batch->semester_id != NULL){
							$semester	= Semester::model()->findByAttributes(array('id'=>$batch->semester_id));
							$return_arr[$key]['semester']	= ($semester != NULL)?html_entity_decode(ucfirst($semester->name)):'-';
						}
						
						
						if($value->status == '1'){
							$header['course_student']	= $return_arr[$key]['courses'];
							$header['batch_student']	= $return_arr[$key]['batch'];
						}						
					}					
					echo json_encode(array('data'=>$return_arr,'header'=>$header), JSON_UNESCAPED_SLASHES);
				}

				elseif ($tag == 'get_all_courses'){ //Get list of Academic Year, Courses, Batches 								
					$return_arr_acedamic	= array();
					$return_arr_course		= array();
					$return_arr_batch		= array();
					
					//Academic Year List
					$criteria				= new CDbCriteria();
					$criteria->condition	= 'is_deleted=:is_deleted'; 	
					$criteria->params		= array(':is_deleted' => 0);
					$criteria->order		= 'status DESC';
					$academicyears			= AcademicYears::model()->findAll($criteria);
					
					foreach($academicyears as $key => $value){	
						$return_arr_acedamic[$key]['id']	= $value->id;				
						$return_arr_acedamic[$key]['name']	= ucfirst($value->name);											
					}
					
					//Course List
					$criteria				= new CDbCriteria();
					$criteria->condition	= 'is_deleted=:is_deleted';
					$criteria->params		= array(':is_deleted' => 0);
					$criteria->order		= 'course_name ASC';
					$course					= Courses::model()->findAll($criteria);
					
					foreach($course as $key => $value){	
						$return_arr_course[$key]['id']			= $value->id;				
						$return_arr_course[$key]['name']		= html_entity_decode(ucfirst($value->course_name));						
						$return_arr_course[$key]['acedamic_id'] = $value->academic_yr_id;					
					}
					
					//Batch List
					$criteria				= new CDbCriteria();
					$criteria->condition	= 'is_deleted=:is_deleted AND is_active=:is_active';
					$criteria->params		= array(':is_deleted' => 0, ':is_active' => 1);
					$criteria->order		= 'start_date ASC';
					$batch					= Batches::model()->findAll($criteria);
					
					foreach($batch as $key => $value){				
						$return_arr_batch[$key]['id']			= $value->id;	
						$return_arr_batch[$key]['name']			= html_entity_decode(ucfirst($value->name));						
						$return_arr_batch[$key]['course_id']	= $value->course_id;					
					}
					echo json_encode(array('acedamic_year'=>$return_arr_acedamic, 'courses'=>$return_arr_course, 'batches'=>$return_arr_batch));					
				}


                                elseif($tag == 'publish_news')
                                {

                                    if(isset($post['uid']) && $post['uid']!= NULL)
                                    {
                                        if(isset($_POST['news_id']) && $_POST['news_id']!=NULL)
                                        {
                                            
                                            $news_id= $_POST['news_id'];
                                            $news=Publish::model()->findByPk($news_id);
                                            //saving is_published as 1.
                                                $to= 'news@example.com';
                                                $subject= $news->title;
                                                $message= $news->content;
                                                
                                                    $t = time();
                                                    $conv = new Mailbox();
                                                    $conv->subject = ($subject)? $subject : Yii::app()->getModule('mailbox')->defaultSubject;
                                                    $conv->to = $to;
                                                    //$conv->initiator_id = Yii::app()->getModule('mailbox')->getUserIdMail();
                                                    $conv->initiator_id= 1;
                                                        
                                                   
                                                    // Check if username exist
                                                    if(strlen($to)>1)
                                                            $conv->interlocutor_id = Yii::app()->getModule('mailbox')->getUserIdMail($to);
                                                    else
                                                            $conv->interlocutor_id = 0;
                                                    
                                                    
                                                    // ...if not check if To field is user id
                                                    if(!$conv->interlocutor_id)
                                                    {
                                                            if($to && (Yii::app()->getModule('mailbox')->allowLookupById || Yii::app()->getModule('mailbox')->isAdmin()))
                                                                    $username = Yii::app()->getModule('mailbox')->getUserName($to);
                                                            if(@$username) {
                                                                    $conv->interlocutor_id = $to;
                                                                    $conv->to = $username;
                                                            }
                                                            else {
                                                                    // possible that javscript was off and user selected from the userSupportList drop down.
                                                                    if( $this->module->getUserIdMail($to)) {

                                                                            $conv->to = $to;
                                                                            $conv->initiator_id = Yii::app()->getModule('mailbox')->getUserIdMail($to);
                                                                    }
                                                                    else
                                                                    {
                                                                            //$err_message = 1;
                                                                            //$conv->addError('to',Yii::t('app','User not found?'));
                                                                            echo "Error1";
                                                                    }
                                                            }
                                                    }

                                                    if($conv->interlocutor_id && $conv->initiator_id == $conv->interlocutor_id) 
                                                    {
                                                            //$err_message = 2;
                                                            //$conv->addError('to', Yii::t('app',"Can't send message to self!"));
                                                            echo "Error 2";
                                                    }

                                                    if(!ModuleAccess::model()->check('My Account')){
                                                            echo "Error 3";
                                                            //$conv->addError('to', Yii::t('app',"Don't have permission to send news!"));
                                                            //$err_message = 3;
                                                    }

                                                    // check user-to-user perms
                                                    if(!$conv->hasErrors() && !Yii::app()->getModule('mailbox')->userToUser && !Yii::app()->getModule('mailbox')->isAdmin())
                                                    {
                                                            if(!Yii::app()->getModule('mailbox')->isAdmin($conv->to)){
                                                                    echo "Error 4";
                                                                    $conv->addError('to', Yii::t('app',"Invalid user!"));
                                                                    $err_message = 4; }
                                                    }

                                                    $conv->modified = $t;
                                                    $conv->bm_read = Mailbox::INITIATOR_FLAG;
                                                    if(Yii::app()->getModule('mailbox')->isAdmin())
                                                            $msg = new DashboardMessage('admin');
                                                    else
                                                            $msg = new DashboardMessage('user');
                                                    $msg->text = $message;
                                                    $validate = $conv->validate(array('text'),false); // html purify
                                                    $msg->created = $t;
                                                    $msg->sender_id = $conv->initiator_id;
                                                    $msg->recipient_id = $conv->interlocutor_id;
                                                    if(Yii::app()->getModule('mailbox')->checksums) {
                                                            $msg->crc64 = DashboardMessage::crc64($msg->text); // 64bit INT
                                                    }
                                                    else
                                                            $msg->crc64 = 0;
                                                    // Validate
                                                    $validate = $conv->validate(null,false); // don't clear errors
                                                    $validate = $msg->validate() && $validate;
                                                    if($validate)
                                                    { 
                                                            $conv->save();
                                                            $msg->conversation_id = $conv->conversation_id;
                                                            //$msg->save();
                                                            if($msg->save()){
																

                                                            }
                                                    }
                                                    else
                                                    {                                                            
                                                            Yii::app()->user->setFlash('notification', $err_message);
                                                           
                                                    }
													
													if(Configurations::model()->isAndroidEnabled()){	
														$college		= Configurations::model()->findByPk(1);
														$sender_name	= PushNotifications::model()->getUserName($post['uid']);
														
														$criteria 					= new CDbCriteria();
														$criteria->condition 		= 'uid<>:uid';
														$criteria->params[':uid'] 	= $post['uid'];
														$criteria->group			= 'device_id'; 
														$user_device 				= UserDevice::model()->findAll($criteria);				
														//Get Messages
														$push_notifications		= PushNotifications::model()->getNotificationDatas(2);
														foreach($user_device as $value){								
															//Get key value of the notification data array					
															$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
															
															$message	= $push_notifications[$key]['message'];
															$message	= str_replace("{Title}", html_entity_decode(ucfirst($news->title)), $message);
															$message	= str_replace("{School Name}", ucfirst($college->config_value), $message);	
															
															$argument_arr = array('message'=>$message, 'content'=>html_entity_decode(ucfirst($news->content)), 'device_id'=>array($value->device_id), 'sender_name'=>$sender_name, 'subject'=>html_entity_decode(ucfirst($news->title)));               
															Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "news");																		
														}			
													}
											
                                            $model=Publish::model()->findByPk($news_id);
                                            if($model)
                                            {
                                                $model->delete();
                                            }
                                            
                                            echo "success";
                                        }
                                        else
                                        {
                                            echo "error";
                                        }
                                    }
                                }
                ################### Transpot module integration starts ###################
				
				elseif($tag == 'add_device'){	// checek if the device is assigned to a route. Add device to db if not exists
					if(isset($post['device_id']) and isset($post['uid'])){
						$device_id	= $post['device_id'];
						$uid		= $post['uid'];
						$device		= Devices::model()->findByAttributes(array('device_id'=>$device_id));
						if($device==NULL){
							$device 			= new Devices;
							$device->device_id	= $device_id;
							$device->created_by	= $uid;
							$device->created_at	= date("Y-m-d H:i:s");
							$device->save();
						}
						
						if($device!=NULL){	//cehck whether device assinged
							$route_device	= RouteDevices::model()->findByAttributes(array('device_id'=>$device->id));
							if($route_device!=NULL){
								$response["error"] 		= false;
								$response["approved"] 	= ($route_device->status==1)?true:false;
								echo json_encode($response);
								exit;
							}
							else{
								$routes	= $this->getAllRoutes();
								$routes_array	= array();
								foreach($routes as $index=>$route){
									$routes_array[$index]['id']		= $route->id;
									$routes_array[$index]['name']	= $route->route_name;
									
									//check route is already assigned with a device
									$route_device	= RouteDevices::model()->findByAttributes(array('route_id'=>$route->id));
									$routes_array[$index]['assigned']	= ($route_device==NULL)?false:true;
								}
								
								$response["error"] 		= false;
								$response["routes"]		= $routes_array;
								echo json_encode($response);
								exit;
							}
						}
					}
					
					$response["error"] 		= true;
					$response["error_msg"] 	= "Invalid Request";
					echo json_encode($response);
					exit;
				}
				elseif($tag == 'assign_device'){	// Assign this device to a route
					if(isset($post['device_id']) and isset($post['route_id']) and isset($post['uid'])){
						$device_id	= $post['device_id'];
						$route_id	= $post['route_id'];
						$uid		= $post['uid'];
						$device		= Devices::model()->findByAttributes(array('device_id'=>$device_id));
						
						if($device!=NULL){	//cehck whether route assinged
							$route_device	= RouteDevices::model()->findByAttributes(array('device_id'=>$device->id, 'route_id'=>$route_id));
							if($route_device==NULL){
								$route_device	= new RouteDevices;
								$route_device->device_id	= $device->id;
								$route_device->route_id		= $route_id;
								$route_device->created_by	= $uid;
								$route_device->created_at	= date("Y-m-d H:i:s");
								$route_device->status		= 0;	// waiting approval
								if($route_device->save()){
									$response["error"] 		= false;									
								}
								else{
									$response["error"] 		= false;
									$response["error_msg"] 	= "Errror found";
								}
								echo json_encode($response);
								exit;
							}
						}
					}
					
					$response["error"] 		= true;
					$response["error_msg"] 	= "Invalid Request";
					echo json_encode($response);
					exit;
				}
				elseif($tag=="station_students_list"){	// get students under a stop
					if(isset($post['station_id'])){						
						$students_array			= array();						
						if(isset($post['section_id']) and isset($post['mode']) and isset($post['device_id'])){
							$stop_id				= $post['station_id'];
							$criteria				= new CDbCriteria;
							$criteria->condition	= "`stop_id`=:stop_id";
							$criteria->params		= array(':stop_id'=>$stop_id);
							$stop_students			= Transportation::model()->findAll($criteria);
							
							$route_id		= NULL;							
							$section_id		= $post['section_id'];
							$mode			= $post['mode'];
							$device_id		= $post['device_id'];
							$device		= Devices::model()->findByAttributes(array('device_id'=>$device_id));
							if($device!=NULL){
								$route_model	= RouteDevices::model()->findByAttributes(array('device_id'=>$device->id, 'status'=>1));
								if($route_model!=NULL)
									$route_id	= $route_model->route_id;
							}
							
							if($mode=="entry")
								$mode_id	= 0;
							else
								$mode_id	= 1;
							
							$counter	= 0;
							foreach($stop_students as $index=>$stop_student){
								$isValid				= true;
								
								if($mode_id==1){
									//entered into bus
									$criteria				= new CDbCriteria;
									$criteria->condition	= "section_id=:section_id AND mode=:mode AND route_id=:route_id AND student_id=:student_id AND STR_TO_DATE(created_at,'%Y-%m-%d')=:date";
									$criteria->params		= array(':section_id'=>$section_id, ':mode'=>0,':route_id'=>$route_id, ':student_id'=>$stop_student->student_id, ':date'=>date("Y-m-d"));
									$entered				= RouteAttendance::model()->find($criteria);
									
									$isValid		= ($entered)?true:false;
								}
								
								if($isValid){								
									$student	= Students::model()->findByPk($stop_student->student_id);
									if($student!=NULL){
										//Profile Image Path
										$path	= $this->getProfileImagePath($student->id, 2);
										
										$students_array[$counter]['id']	= $student->id;
										$students_array[$counter]['uid']	= $student->uid;
										$students_array[$counter]['name']	= $student->studentFullName();
										if($path != NULL){
											$students_array[$counter]['image']	= Yii::app()->getBaseUrl(true).'/'.$path;
										}
										
										//attendance start
										$attendance		= NULL;
										if($route_id!=NULL and $section_id!=NULL){
											$criteria				= new CDbCriteria;
											$criteria->condition	= "section_id=:section_id AND mode=:mode AND route_id=:route_id AND student_id=:student_id AND STR_TO_DATE(created_at,'%Y-%m-%d')=:date";
											$criteria->params		= array(':section_id'=>$section_id, ':mode'=>$mode_id,':route_id'=>$route_id, ':student_id'=>$student->id, ':date'=>date("Y-m-d"));
											$attendance				= RouteAttendance::model()->find($criteria);
										}
										
										
										$students_array[$counter]['isPresent']	= ($attendance!=NULL)?true:false;
										//attendance end
										$counter++;
									}
								}
							}
						}
						
						$response["error"] 		= false;
						$response["students"]	= $students_array;
						echo json_encode($response, JSON_UNESCAPED_SLASHES);
						exit;
					}
					
					$response["error"] 		= true;
					$response["error_msg"] 	= "Invalid Request";
					echo json_encode($response);
					exit;
				}
				
				//fetch station list/student list for transport module
				elseif($tag=="station_list")
				{
					$device_id= $_POST['device_id'];
					$section_id= $_POST['section_id'];
					$mode= $_POST['mode'];
					$return_arr= array();
					$stop_id= $student_id_list= array();
					$device		= Devices::model()->findByAttributes(array('device_id'=>$device_id));
					if($device!=NULL){
						$route_model= RouteDevices::model()->findByAttributes(array('device_id'=>$device->id,'status'=>1));
						if($route_model!=NULL)
						{
							$route_id= $route_model->route_id;
							$stop_model= StopDetails::model()->findAllByAttributes(array('route_id'=>$route_id));
							if($stop_model!=NULL)
							{
								foreach ($stop_model as $key=>$value)
								{
									$return_arr[$key]['id']=$value->id;
									$return_arr[$key]['stop_name']=$value->stop_name;								
									$stop_id[] = $value->id;
								}
							}
							
							if((($section_id==1 && $mode=="exit") or ($section_id==2 && $mode=="entry")) and isset($post['section_id']) and isset($post['mode']) and isset($post['device_id'])){
								$route_id		= NULL;
								
								$section_id		= $post['section_id'];
								$mode			= $post['mode'];
								$device_id		= $post['device_id'];
								
								if($mode=="entry")
									$mode_id	= 0;
								else
									$mode_id	= 1;
								
								$device		= Devices::model()->findByAttributes(array('device_id'=>$device_id));
								if($device!=NULL){
									$route_model	= RouteDevices::model()->findByAttributes(array('device_id'=>$device->id, 'status'=>1));
									if($route_model!=NULL)
										$route_id	= $route_model->route_id;
								}
								
								$criteria= new CDbCriteria;
								$criteria->addInCondition('stop_id',$stop_id);
								$students_model= Transportation::model()->findAll($criteria);
								if($students_model!=NULL){
									$counter	= 0;
									foreach($students_model as $index=>$student_data){
										$isValid				= true;
								
										if($mode_id==1){
											//entered into bus
											$criteria				= new CDbCriteria;
											$criteria->condition	= "section_id=:section_id AND mode=:mode AND route_id=:route_id AND student_id=:student_id AND STR_TO_DATE(created_at,'%Y-%m-%d')=:date";
											$criteria->params		= array(':section_id'=>$section_id, ':mode'=>0,':route_id'=>$route_id, ':student_id'=>$student_data->student_id, ':date'=>date("Y-m-d"));
											$entered				= RouteAttendance::model()->find($criteria);
											
											$isValid		= ($entered)?true:false;
										}
										
										if($isValid){
											$student	= Students::model()->findByPk($student_data->student_id);
											if($student!=NULL){
												//Profile Image Path
												$path	= $this->getProfileImagePath($student->id, 2);
												
												$student_id_list[$counter]['id']		= $student->id;
												$student_id_list[$counter]['uid']		= $student->uid;
												$student_id_list[$counter]['name']	= $student->studentFullName();	
												if($path != NULL){
													$student_id_list[$counter]['image']= Yii::app()->getBaseUrl(true).'/'.$path;
												}
												
												//attendance start
												$attendance		= NULL;
												if($route_id!=NULL and $section_id!=NULL){
													$criteria				= new CDbCriteria;
													$criteria->condition	= "section_id=:section_id AND mode=:mode AND route_id=:route_id AND student_id=:student_id AND STR_TO_DATE(created_at,'%Y-%m-%d')=:date";
													$criteria->params		= array(':section_id'=>$section_id, ':mode'=>$mode_id,':route_id'=>$route_id, ':student_id'=>$student->id, ':date'=>date("Y-m-d"));
													$attendance				= RouteAttendance::model()->find($criteria);
												}
												
												$student_id_list[$counter]['isPresent']	= ($attendance!=NULL)?true:false;
												//attendance end
												$counter++;
											}
										}
									}
								}
							}
							
							if(($section_id==1 && $mode=="entry") or ($section_id==2 && $mode=="exit"))
							{
									echo json_encode(array('station_list'=>$return_arr), JSON_UNESCAPED_SLASHES);
							}
							elseif(($section_id==1 && $mode=="exit") or ($section_id==2 && $mode=="entry"))
							{
									echo json_encode(array('students'=>$student_id_list), JSON_UNESCAPED_SLASHES);
							}
							
						}										
						else
						{
							echo "error";
						}
					}
					else{
						echo "error";
					}
				}
				
				elseif($tag=="add_route_attendance")
				{                                    
					$device_id		= $_POST['device_id'];
					$section_id		= $_POST['section_id'];
					$mode			= $_POST['mode'];					
					$student_ids	= $_POST['student_ids'];
					$user_id 		= $_POST['uid'];
					
					$device			= Devices::model()->findByAttributes(array('device_id'=>$device_id));
					$route_model	= RouteDevices::model()->findByAttributes(array('device_id'=>$device->id,'status'=>1));
					if($route_model!=NULL){
						$route_id			= $route_model->route_id;
						$err_student_ids	= array();
						$flag=0;
						if($mode=="entry")
						{
							$mode_id=0;
						}
						else
						{
							$mode_id=1;
						}
						$decoded = json_decode($student_ids);
						$students = $decoded->students;                                        
										   
						if(($device_id!=NULL) && ($section_id!=NULL) && ($mode!=NULL))
						{
							$student_ids	= array();
							foreach ($students as $key=>$student_data){
								$student_ids[]	= $student_data->student_id;
								$criteria= new CDbCriteria;
								$criteria->condition= "section_id=:section_id AND mode=:mode AND route_id=:route_id AND student_id=:student_id AND STR_TO_DATE(created_at,'%Y-%m-%d')=:date";
								$criteria->params= array(':section_id'=>$section_id, ':mode'=>$mode_id,':route_id'=>$route_id, ':student_id'=>$student_data->student_id, ':date'=>date("Y-m-d"));                                                                                                               
								$model= RouteAttendance::model()->find($criteria);
								if($model!=NULL)
								{
									$model->section_id= $section_id;
									$model->mode= $mode_id;
									$model->route_id= $route_id;
									$model->student_id= $student_data->student_id;
									$model->created_at= date("Y-m-d H:i:s");
									$model->created_by= $user_id;
								}
								else
								{                                                    
									$model= new RouteAttendance;
									$model->section_id= $section_id;
									$model->mode= $mode_id;
									$model->route_id= $route_id;
									$model->student_id= $student_data->student_id;
									$model->created_at= date("Y-m-d H:i:s");
									$model->created_by= $user_id;
								}
								if(!$model->save())
								{   
									$flag=1;
									$err_student_ids[]= $student_data->student_id;
								}
							}
							
							//delete remaining rows from attendance - start
							$stop_id				= $post['station_id'];
							$criteria				= new CDbCriteria;
							
							if(isset($stop_id) and $stop_id!=NULL){
								$criteria->condition	= "`stop_id`=:stop_id";
								$criteria->params		= array(':stop_id'=>$stop_id);
							}
							else{
								if($route_model!=NULL){
									$route_id		= $route_model->route_id;
									$stop_model		= StopDetails::model()->findAllByAttributes(array('route_id'=>$route_id));
									if($stop_model!=NULL){
										$stop_ids	= array();
										foreach ($stop_model as $key=>$value){							
											$stop_ids[] = $value->id;
										}
										$criteria->addInCondition('stop_id', $stop_ids);
									}
								}
							}							
							
							$criteria->addNotInCondition('student_id', $student_ids);
							$stop_students			= Transportation::model()->findAll($criteria);
							$remove_student_ids		= array();
							foreach($stop_students as $stop_student){
								$remove_student_ids[]	= $stop_student->student_id;
							}
							
							if(count($remove_student_ids)>0){
								$criteria				= new CDbCriteria;
								$criteria->condition	= "section_id=:section_id AND mode=:mode AND route_id=:route_id AND STR_TO_DATE(created_at,'%Y-%m-%d')=:date";
								$criteria->params		= array(':section_id'=>$section_id, ':mode'=>$mode_id,':route_id'=>$route_id, ':date'=>date("Y-m-d"));
								$criteria->addInCondition('student_id', $remove_student_ids);
								$remove_rows			= RouteAttendance::model()->findAll($criteria);
								
								if($remove_rows!=NULL and count($remove_rows)>0){
									RouteAttendance::model()->deleteAll($criteria);
								}
							}
							//delete remaining rows from attendance - end
							
							
							//send notifications to parent(s) - start
							$notification 	= NotificationSettings::model()->findByAttributes(array('id'=>21));
							$sms_template	= SystemTemplates::model()->findByPk(38);
														
							$stop_ids	= array();
							if(isset($post['station_id']) and $post['station_id']!=NULL){
								$stop_ids[]		= $post['station_id'];
							}
							else{
								$stops		= StopDetails::model()->findAllByAttributes(array('route_id'=>$route_id));
								if($stops!=NULL){
									foreach ($stops as $key=>$stop){
										$stop_ids[] = $stop->id;
									}
								}
							}							
							
							$criteria		= new CDbCriteria;
							$criteria->condition	= "`student_id` IS NOT NULL";
							$criteria->addInCondition('`stop_id`',$stop_ids);
							$stop_students	= Transportation::model()->findAll($criteria);
							if($stop_students==NULL){
							}
							else{
								
								foreach($stop_students as $index=>$stop_student){
									$student	= Students::model()->findByAttributes(array('id'=>$stop_student->student_id, 'is_deleted'=>0, 'is_active'=>1));
									if($student!=NULL){
										$student_fullname		= ucwords(strtolower($student->studentFullName()));
										
										//fetch guardians
										$criteria 				= new CDbCriteria();
										$criteria->join			= "JOIN `guardian_list` `gl` ON `gl`.`guardian_id`=`t`.`id`";
										$criteria->condition	= "`gl`.`student_id`=:student_id AND `t`.`is_delete`=:is_delete";
										$criteria->params		= array(':student_id'=>$student->id, ':is_delete'=>0);
										$guardians				= Guardians::model()->findAll($criteria);
										
										foreach($guardians as $index=>$guardian){
											if($guardian->uid!=NULL and $guardian->uid!=0){
												$criteria 					= new CDbCriteria();
												$criteria->condition 		= 'uid=:uid';
												$criteria->params[':uid'] 	= $guardian->uid; 
												$devices 					= UserDevice::model()->findAll($criteria);
												$devices_ids				= array();
												foreach($devices as $device){
													$devices_ids[]	= $device->device_id;
												}
													
												$attendance_status	= 0;	// absent
												$msg			= array();													
												if($section_id==1 and $mode=="entry"){	// from station
													$criteria= new CDbCriteria;
													$criteria->condition= "section_id=:section_id AND mode=:mode AND route_id=:route_id AND student_id=:student_id AND STR_TO_DATE(created_at,'%Y-%m-%d')=:date";
													$criteria->params= array(':section_id'=>1, ':mode'=>0,':route_id'=>$route_id, ':student_id'=>$student->id, ':date'=>date("Y-m-d"));                                                                                                               
													$attendance	= RouteAttendance::model()->find($criteria);
												
													if($attendance!=NULL){
														$attendance_status	= 1; // present
														$msg['title']	= $student_fullname.' entered from stop';
														$msg['message']	= 'Your child '.$student_fullname.' entered from stop on date - '.date("Y/m/d", strtotime($attendance->created_at)).' at '.date("h:i A", strtotime($attendance->created_at));
													}
													else{
														$msg['title']	= $student_fullname.' not entered from stop';
														$msg['message']	= 'Your child '.$student_fullname.' not entered from stop on date - '.date("Y/m/d");
													}
												}
												else if($section_id==1 and $mode=="exit"){	// at school
													$criteria= new CDbCriteria;
													$criteria->condition= "section_id=:section_id AND mode=:mode AND route_id=:route_id AND student_id=:student_id AND STR_TO_DATE(created_at,'%Y-%m-%d')=:date";
													$criteria->params= array(':section_id'=>1, ':mode'=>1,':route_id'=>$route_id, ':student_id'=>$student->id, ':date'=>date("Y-m-d"));                                                                                                               
													$attendance	= RouteAttendance::model()->find($criteria);
												
													if($attendance!=NULL){
														$attendance_status	= 1; // present
														$msg['title']	= $student_fullname.' exit at school';
														$msg['message']	= 'Your child '.$student_fullname.' exit at school on date - '.date("Y/m/d", strtotime($attendance->created_at)).' at '.date("h:i A", strtotime($attendance->created_at));
													}
													else{
														$msg['title']	= $student_fullname.' not exit at school';
														$msg['message']	= 'Your child '.$student_fullname.' not exit at school on date - '.date("Y/m/d");
													}
												}
												else if($section_id==2 and $mode=="entry"){	// from school
													$criteria= new CDbCriteria;
													$criteria->condition= "section_id=:section_id AND mode=:mode AND route_id=:route_id AND student_id=:student_id AND STR_TO_DATE(created_at,'%Y-%m-%d')=:date";
													$criteria->params= array(':section_id'=>2, ':mode'=>0,':route_id'=>$route_id, ':student_id'=>$student->id, ':date'=>date("Y-m-d"));                                                                                                               
													$attendance	= RouteAttendance::model()->find($criteria);
												
													if($attendance!=NULL){
														$attendance_status	= 1; // present
														$msg['title']	= $student_fullname.' entered from school';
														$msg['message']	= 'Your child '.$student_fullname.' entered from school on date - '.date("Y/m/d", strtotime($attendance->created_at)).' at '.date("h:i A", strtotime($attendance->created_at));
													}
													else{
														$msg['title']	= $student_fullname.' not entered from school';
														$msg['message']	= 'Your child '.$student_fullname.' not entered from school on date - '.date("Y/m/d");
													}
												}
												else if($section_id==2 and $mode=="exit"){	// at school
													$criteria= new CDbCriteria;
													$criteria->condition= "section_id=:section_id AND mode=:mode AND route_id=:route_id AND student_id=:student_id AND STR_TO_DATE(created_at,'%Y-%m-%d')=:date";
													$criteria->params= array(':section_id'=>2, ':mode'=>1,':route_id'=>$route_id, ':student_id'=>$student->id, ':date'=>date("Y-m-d"));                                                                                                               
													$attendance	= RouteAttendance::model()->find($criteria);
												
													if($attendance!=NULL){
														$attendance_status	= 1; // present
														$msg['title']	= $student_fullname.' exit at stop';
														$msg['message']	= 'Your child '.$student_fullname.' exit at stop on date - '.date("Y/m/d", strtotime($attendance->created_at)).' at '.date("h:i A", strtotime($attendance->created_at));
													}
													else{
														$msg['title']	= $student_fullname.' not exit at stop';
														$msg['message']	= 'Your child '.$student_fullname.' not exit at stop on date - '.date("Y/m/d");
													}
												}
												
												//send android app notification
												if(count($devices_ids)>0){
													if(Configurations::model()->isAndroidEnabled()){	// if android is enabled for the application
														//send notification
														$argument_arr = array('message'=>$msg['message'],'device_id'=>$devices_ids);                
														Configurations::model()->devicenotice($argument_arr, $msg['title'],"transport");
														
														/*$gcm 			= new GCM();
														$msg['tag']		= "transport";
														$gcm->send_notification($devices_ids, $msg);*/
													}
												}
												
												//send sms notification
												if($sms_template!=NULL and $student->parent_id!=NULL and $student->parent_id==$guardian->id){// if primary contact														
													$to 			= '';
													if($notification!=NULL and $notification->sms_enabled=='1' and $notification->parent_1=='1'){ // Checking if SMS is enabled.						
														$to		= $guardian->mobile_phone;															
														if($to!=''){ // Send SMS if phone number is provided
															$message 		= $sms_template->template;
															$message 		= str_replace("<STUDENT NAME>",$student_fullname,$message);
															$status			= "";
															if($attendance_status==1){
																if($mode=="entry"){
																	$status	.= "entered from ";
																	if($section_id==1)
																		$status	.= "stop";
																	else if($section_id==2)
																		$status	.= "school";
																}
																else{
																	$status	.= "exit at ";
																	if($section_id==1)
																		$status	.= "school";
																	else if($section_id==2)
																		$status	.= "stop";
																}
															}
															else{
																if($mode=="entry"){
																	$status	.= "not entered from ";
																	if($section_id==1)
																		$status	.= "stop";
																	else if($section_id==2)
																		$status	.= "school";
																}
																else{
																	$status	.= "not exit at ";
																	if($section_id==1)
																		$status	.= "school";
																	else if($section_id==2)
																		$status	.= "stop";
																}
															}
															
															$message 		= str_replace("<STATUS>",$status,$message);
															if($attendance!=NULL){
																$message 		= str_replace("<DATE>",date("Y/m/d", strtotime($attendance->created_at)),$message);
																$message 		= str_replace("<TIME>",date("h:i A", strtotime($attendance->created_at)),$message);
															}
															else{
																$message 		= str_replace("<DATE>",date("Y/m/d"),$message);
																$message 		= str_replace("<TIME>","",$message);
															}
															
															SmsSettings::model()->sendSms($to,$from,$message);
														} // End send SMS
													} // End check if SMS is enabled
												}
											}
										}
									}
								}
							}							
							//send notifications to parent(s) - end
							
							if($flag==1)
								echo json_encode(array('status'=>'success','error_list'=>$err_student_ids));
							else
								echo json_encode(array('status'=>'success'));
						}
						else
						{
							echo "error";
						}
					}
					else{
						echo "error";
					}
				}
				else if($tag == 'route_title'){
					$uid		= $_POST['uid'];
					$device_id	= $_POST['device_id'];
					if($uid != NULL and $device_id != NULL){
						$device	= Devices::model()->findByAttributes(array('device_id'=>$device_id, 'created_by'=>$uid));
						if($device){
							$route_device	= RouteDevices::model()->findByAttributes(array('device_id'=>$device->id, 'created_by'=>$uid, 'status'=>1));
							if($route_device){
								$route_detail	= RouteDetails::model()->findByPk($route_device->route_id);
								$route_name		= '';
								if($route_detail){
									$route_name	= ucfirst($route_detail->route_name);									
								}
								echo json_encode(array('route_name'=>$route_name));
								exit;
							}
						}
						else{
							$response["error"] = true;
							$response["error_msg"] = "Device Not Found";
							echo json_encode($response);
							exit;
						}
					}
					else{
						$response["error"] = true;
						$response["error_msg"] = "Invalid Request";
						echo json_encode($response);
						exit;
					}
				} ################### Transpot module integration END ###################
				else if($tag == 'teacher_attendance_settings'){ //Attendance mode settings
					$uid	= $_POST['uid'];
					$mode	= Configurations::model()->teacherAttendanceMode(); //1 => Day wise, 2 => Subject wise, 3 => Both
					echo json_encode(array('mode'=>$mode), JSON_UNESCAPED_SLASHES);
					exit;
				}
				else if($tag == 'teacher_leave_types'){ //Get employee leave types
					$uid			= $_POST['uid'];
					$employee_id	= $_POST['employee_id'];
					$return_arr		= array();
					$employee		= Employees::model()->findByPk($employee_id);
					if($employee->gender == 'M'){
						$gender	= 1;
					}
					if($employee->gender == 'F'){
						$gender	= 2;
					}
					
					$criteria				= new CDbCriteria;
					$criteria->condition	= '(gender=:gender OR gender=0) AND is_deleted=:is_deleted';
					$criteria->params		= array(':gender'=>$gender, ':is_deleted'=>0);
					$leave_types 			= LeaveTypes::model()->findAll($criteria);
					if($leave_types){
						foreach($leave_types as $key => $value){
							$return_arr[$key]['id']		= $value->id;
							$return_arr[$key]['name']	= html_entity_decode(ucfirst($value->type));
						}
					}
					echo json_encode($return_arr, JSON_UNESCAPED_SLASHES);						
					exit;
				}
				else if($tag == 'teacher_attendance'){ //Teacher day wise attendance
					$uid		= $_POST['uid'];
					$role		= Rights::getAssignedRoles($uid);
					$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));	
					if(key($role) != 'teacher'){ //In case of Admin or Custom users
						$level	= $_POST['level'];
						if($level == 1){ //This is for getting department list
							$department_arr			= array();
							$criteria				= new CDbCriteria();
							$criteria->condition	= 'status=:status';
							$criteria->params		= array(':status'=>1);
							$criteria->order		= 'name ASC'; 
							$departments			= EmployeeDepartments::model()->findAll($criteria);
							if($departments){
								foreach($departments as $key => $department){
									$department_arr[$key]['id']		= $department->id;
									$department_arr[$key]['name']	= html_entity_decode(ucfirst($department->name));								
								}
							}
							echo json_encode(array('departments' =>$department_arr), JSON_UNESCAPED_SLASHES);						
							exit;
						}
						else if($level == 2){ //This is for getting employee list under the selected department
							$department_id	= $_POST['department_id'];
							$date			= $_POST['date'];
							$employee_arr	= array();
							$flag			= 0;
							$check_weekday	= $this->isWeekday($date);
							$check_holiday	= $this->isHoliday($date);	
							if($check_holiday == 1){
								$flag		= 1;
								$message	= 'Selected date is a Holiday'; 
							}
							else if($check_weekday == 1){
								$flag		= 1;
								$message	= 'Selected date is not a weekday'; 
							}	
							else if($date > date('Y-m-d')){
								$flag		= 1;
								$message	= 'Cannot mark attendance for upcoming dates'; 
							}
							else{							
								if($department_id != NULL){
									$criteria				= new CDbCriteria();
									if($department_id == 'all'){	
										$criteria->condition	= 'is_deleted=:is_deleted';
										$criteria->params 		= array(':is_deleted'=>0);
									}
									else{
										$criteria->condition	= 'employee_department_id=:employee_department_id AND is_deleted=:is_deleted';
										$criteria->params 		= array(':employee_department_id'=>$department_id, ':is_deleted'=>0);
									}
									$criteria->order		= 'first_name ASC';
									$employees				= Employees::model()->findAll($criteria);
								}
								if($employees){
									foreach($employees as $key => $value){
										$is_leave				= 0;
										$absent_leave_type		= '';
										$absent_reason			= '';
										$absent_leave_type_id	= '';
										$is_half_day			= 0;
										$half					= 0;	
										$attendance	= EmployeeAttendances::model()->findByAttributes(array('employee_id'=>$value->id, 'attendance_date'=>$date));
										if($attendance != NULL){
											$leave_type	= LeaveTypes::model()->findByPk($attendance->employee_leave_type_id);
											if($leave_type){
												$absent_leave_type		= html_entity_decode(ucfirst($leave_type->type));
												$absent_leave_type_id	= $attendance->employee_leave_type_id;
											}										
											$absent_reason	= html_entity_decode(ucfirst($attendance->reason));										
											$is_half_day	= $attendance->is_half_day;
											$half			= $attendance->half;										
											$is_leave	= 1;
											
										}
										$employee_arr[$key]['id']					= $value->id;
										$employee_arr[$key]['name']					= $value->getFullname();
										$employee_arr[$key]['employee_no']			= $value->employee_number;
										$employee_arr[$key]['is_leave']				= $is_leave;
										$employee_arr[$key]['absent_leave_type']	= $absent_leave_type;
										$employee_arr[$key]['absent_leave_type_id']	= $absent_leave_type_id;
										$employee_arr[$key]['absent_reason']		= $absent_reason;
										$employee_arr[$key]['is_half_day']			= $is_half_day;	
										$employee_arr[$key]['half']					= $half;
										
										//Get Profile image path
										$path = $this->getProfileImagePath($value->id, 3);
										if($path != NULL){
											$employee_arr[$key]['image']	= Yii::app()->getBaseUrl(true).'/'.$path;												
										}							
									}
								}							
							}
							if($flag == 1){
								echo json_encode(array('flag' =>$flag, 'message'=>$message), JSON_UNESCAPED_SLASHES);							
							}
							else{
								echo json_encode(array('flag' =>$flag, 'employees'=>$employee_arr), JSON_UNESCAPED_SLASHES);						
							}
							exit;
						}
						else if($level == 3){ //This is for managing absent	
							$college		= Configurations::model()->findByPk(1);							
							$sender_name	= PushNotifications::model()->getUserName($uid);
							$is_present		= $_POST['is_present']; //1 => Mark Present, 0 => Mark Absent
							$model			= EmployeeAttendances::model()->findByAttributes(array('attendance_date'=>date('Y-m-d', strtotime($_POST['EmployeeAttendances']['attendance_date'])), 'employee_id'=>$_POST['EmployeeAttendances']['employee_id']));		
							if($is_present == 1){ //Mark Present
								if($model != NULL){
									if($model->delete()){
										//Mobile Push Notifications
										$employee	= Employees::model()->findByPk($model->employee_id);
										if($employee->uid != 0){
											//Get devices
											$criteria				= new CDbCriteria();
											$criteria->condition	= 'uid=:uid'; 
											$criteria->params		= array(':uid'=>$employee->uid);
											$criteria->group		= 'device_id';
											$user_device			= UserDevice::model()->findAll($criteria);
											//Get Messages
											$push_notifications		= PushNotifications::model()->getNotificationDatas(35);
											foreach($user_device as $value){								
												//Get key value of the notification data array					
												$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);											
												$message	= $push_notifications[$key]['message'];	
												$message	= str_replace("{School Name}", html_entity_decode(ucfirst($college->config_value)), $message);								
												$message	= str_replace("{Date}", date($settings->displaydate, strtotime($model->attendance_date)), $message);																																																				
												$argument_arr = array('message'=>$message, 'device_id'=>array($value->device_id),'sender_name'=>$sender_name, 'date'=>$model->attendance_date, 'teacher_id'=>$employee->id);       
												Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "teacher_daywise_attendance");																		
											}											
										}								
									}								
									echo json_encode(array('status'=>'success', 'is_present'=>$is_present));
									exit;
								}
							}
							else{ //Mark Absent
								if($model == NULL){							
									$model	= new EmployeeAttendances;						
								}
								$model->attendance_date			= date('Y-m-d', strtotime($_POST['EmployeeAttendances']['attendance_date']));
								$model->employee_id				= $_POST['EmployeeAttendances']['employee_id'];
								$model->employee_leave_type_id	= (int)$_POST['EmployeeAttendances']['employee_leave_type_id'];
								$model->reason					= $_POST['EmployeeAttendances']['reason'];
								$model->is_half_day				= $_POST['EmployeeAttendances']['is_half_day'];
								$model->half					= $_POST['EmployeeAttendances']['half'];
								
								if($model->save()){
									//Mobile Push Notifications								
									$employee	= Employees::model()->findByPk($model->employee_id);
									if($employee->uid != 0){
										//Get devices
										$criteria				= new CDbCriteria();
										$criteria->condition	= 'uid=:uid'; 
										$criteria->params		= array(':uid'=>$employee->uid);
										$criteria->group		= 'device_id';
										$user_device			= UserDevice::model()->findAll($criteria);
										//Get Messages
										$push_notifications		= PushNotifications::model()->getNotificationDatas(34);
										foreach($user_device as $value){								
											//Get key value of the notification data array					
											$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
											
											$message	= $push_notifications[$key]['message'];	
											$message	= str_replace("{School Name}", html_entity_decode(ucfirst($college->config_value)), $message);								
											$message	= str_replace("{Date}", date($settings->displaydate, strtotime($model->attendance_date)), $message);		
											$message	= str_replace("{Reason}", html_entity_decode(ucfirst($model->reason)), $message);																													
											$argument_arr = array('message'=>$message, 'device_id'=>array($value->device_id),'sender_name'=>$sender_name, 'date'=>$model->attendance_date, 'teacher_id'=>$employee->id);       
											Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "teacher_daywise_attendance");																		
										}										
									}
									
									echo json_encode(array('status'=>'success', 'is_present'=>$is_present));
									exit;
								}
								else{
									$errors	= $model->getErrors();
									echo json_encode(array('status'=>'error', 'errors'=>$errors));
									exit;
								}
							}
						}
					}
					else{ //In case of Teacher - View Absent details based on the selected month					
						$employee	= Employees::model()->findByAttributes(array('uid'=>$uid));
						$return_arr	= array();
						if($employee != NULL){
							if(isset($_REQUEST['month']) and $_REQUEST['month'] != NULL){
								$month	= $_REQUEST['month'];
							}
							else{
								$month	= date('Y-m');
							}
							$start_date	= $month.'-01';
							$end_date	= date('Y-m-t',strtotime($month));
							
							$criteria				= new CDbCriteria();
							$criteria->condition	= 'employee_id=:employee_id AND (attendance_date >=:start_date AND attendance_date <=:end_date)'; 
							$criteria->params		= array(':employee_id'=>$employee->id, ':start_date'=>$start_date, ':end_date'=>$end_date);
							
							$model					= EmployeeAttendances::model()->findAll($criteria);
							if($model != NULL){
								foreach($model as $key => $value){
									$leave_type	= LeaveTypes::model()->findByPk($value->employee_leave_type_id);									
									
									$return_arr[$key]['id']				= $value->id;
									$return_arr[$key]['date']			= date($settings->displaydate, strtotime($value->attendance_date));
									$return_arr[$key]['calendar_date']	= date("Y M d h i a", strtotime($value->attendance_date));
									$return_arr[$key]['leave_type']		= ($leave_type != NULL)?html_entity_decode(ucfirst($leave_type->type)):'-';
									$return_arr[$key]['reason']			= html_entity_decode(ucfirst($value->reason));
									$return_arr[$key]['half']			= $value->half;											
								}
							}
							echo json_encode(array('attendance' =>$return_arr),JSON_UNESCAPED_SLASHES);
							exit;							
						}
						else{
							$response["error"] 		= true;
							$response["error_msg"] 	= "Invalid Request";
							echo json_encode($response);
							exit;
						}
					}
				}
				else if($tag == 'teacher_subjectwise_attendance'){ //Teacher Subject Wise Attendance
					$uid		= $_POST['uid'];
					$role		= Rights::getAssignedRoles($uid);
					$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));	
					if(key($role) != 'teacher'){ //In case of Admin or Custom users
						$level		= $_POST['level'];
						if($level == 1){
							$employee_arr	= array();
							$department_id	= $_POST['department_id'];
							
							$criteria		= new CDbCriteria();
							if($department_id == 'all'){	
								$criteria->condition	= 'is_deleted=:is_deleted';
								$criteria->params 		= array(':is_deleted'=>0);
							}
							else{
								
								$criteria->condition	= 'employee_department_id=:employee_department_id AND is_deleted=:is_deleted';
								$criteria->params 		= array(':employee_department_id'=>$department_id, ':is_deleted'=>0);
							}
							$criteria->order		= 'first_name ASC';
							$employees				= Employees::model()->findAll($criteria);
							if($employees){
								foreach($employees as $key => $value){									
									$employee_arr[$key]['id']	= $value->id;
									$employee_arr[$key]['name']	= $value->getFullname();																
								}
							}
							echo json_encode(array('employees'=>$employee_arr), JSON_UNESCAPED_SLASHES);
						}
						else if($level == 2){ //List the timing s of selected teacher
							$employee_id	= $_POST['employee_id'];
							$flag			= 0;
							$timing_arr		= array();						
							if(isset($_POST['date']) and $_POST['date'] != NULL){
								$date	= date('Y-m-d', strtotime($_POST['date'])); 
							}
							else{
								$date	= date('Y-m-d');
							}
							
							$day			= date('w', strtotime($date)) + 1;
							$check_weekday	= $this->isWeekday($date);
							$check_holiday	= $this->isHoliday($date);	
							if($check_holiday == 1){
								$flag		= 1;
								$message	= 'Selected date is a Holiday'; 
							}
							else if($check_weekday == 1){
								$flag		= 1;
								$message	= 'Selected date is not a weekday'; 
							}	
							else if($date > date('Y-m-d')){
								$flag		= 1;
								$message	= 'Cannot mark attendance for upcoming dates'; 
							}
							else{					
								if(Configurations::model()->timetableFormat() == 1){
									$criteria				= new CDbCriteria();
									$criteria->join			= 'JOIN `class_timings` `t1` ON `t1`.`id` = `t`.`class_timing_id`';
									$criteria->condition	= '`t`.`employee_id`=:employee_id AND `t`.`weekday_id`=:weekday_id';
									$criteria->params		= array(':employee_id'=>$employee_id, ':weekday_id'=>$day);
									$criteria->order 		= "STR_TO_DATE(`t1`.`start_time`, '%h:%i %p')"; 
									$criteria->group		= '`t`.`class_timing_id`';   
									$model					= TimetableEntries::model()->findAll($criteria);							
								}
								else{
									$weekday_attributes	= array(1=>'on_sunday', 2=>'on_monday', 3=>'on_tuesday', 4=>'on_wednesday', 5=>'on_thursday', 6=>'on_friday', 7=>'on_saturday');
									
									$criteria				= new CDbCriteria();
									$criteria->join			= 'JOIN `class_timings` `t1` ON `t1`.`id` = `t`.`class_timing_id`';
									$criteria->condition	= '`t`.`employee_id`=:employee_id AND `t`.`weekday_id`=:weekday_id AND `t1`.`'.$weekday_attributes[$day].'`=:week_day_status';
									$criteria->params		= array(':employee_id'=>$employee_id, ':weekday_id'=>$day, ':week_day_status'=>1);
									$criteria->order 		= "STR_TO_DATE(`t1`.`start_time`, '%h:%i %p')";   
									$criteria->group		= '`t`.`class_timing_id`'; 
									$model					= TimetableEntries::model()->findAll($criteria);
								}
								if($model != NULL){
									foreach($model as $key => $value){
										$class_timing	= ClassTimings::model()->findByPk($value->class_timing_id);
										$subject_name	= $this->getSubjectName($value->id);
										$batch			= Batches::model()->findByPk($value->batch_id);
										$course			= Courses::model()->findByAttributes(array('id'=>$batch->course_id));
										$is_absent		= TeacherSubjectwiseAttentance::model()->findByAttributes(array('employee_id'=>$employee_id, 'timetable_id'=>$value->id, 'weekday_id'=>$value->weekday_id, 'date'=>$date));
										$is_present		= 1;
										//traslate AM and PM
										$t1 			= date('h:i', strtotime($class_timing->start_time));	
										$t2 			= date('A', strtotime($class_timing->start_time));								
										$t3				= date('h:i', strtotime($class_timing->end_time));	
										$t4				= date('A', strtotime($class_timing->end_time));	
										
										$timing_arr[$key]['id']				= $value->id;
										$timing_arr[$key]['time']			= $t1.' '.$t2.' - '.$t3.' '.$t4;
										$timing_arr[$key]['subject']		= $subject_name;
										$timing_arr[$key]['batch']			= ($batch != NULL)?html_entity_decode(ucfirst($batch->name)).' ( '.html_entity_decode(ucfirst($course->course_name)).' )':'';	
										$timing_arr[$key]['timetable_id']	= $value->id;
										$timing_arr[$key]['subject_id']		= $value->subject_id;
										$timing_arr[$key]['weekday_id']		= $value->weekday_id;
										
										if($is_absent != NULL){
											$leave_type	= LeaveTypes::model()->findByPk($is_absent->leavetype_id);
											$is_present	= 0;
											
											$timing_arr[$key]['absent_id']		= $is_absent->id;
											$timing_arr[$key]['leave_type']		= ($leave_type != NULL)?html_entity_decode(ucfirst($leave_type->type)):'';
											$timing_arr[$key]['leave_type_id']	= $is_absent->leavetype_id;
											$timing_arr[$key]['reason']			= html_entity_decode(ucfirst($is_absent->reason));									
										}
										$timing_arr[$key]['is_present']	= $is_present; //1 => Present, 0 => Absent
									}
								}
							}
													
							if($flag == 1){
								echo json_encode(array('flag' =>$flag, 'message'=>$message), JSON_UNESCAPED_SLASHES);						
							}
							else{
								echo json_encode(array('flag' =>$flag, 'timings'=>$timing_arr), JSON_UNESCAPED_SLASHES);						
							}
							exit;
						}
						else if($level == 3){
							$is_present	= $_POST['is_present']; //1 => Mark Present, 0 => Mark Absent
							$model		= TeacherSubjectwiseAttentance::model()->findByAttributes(array('employee_id'=>$_POST['employee_id'], 'timetable_id'=>$_POST['timetable_id'], 'weekday_id'=>$_POST['weekday_id'], 'date'=>$_POST['date']));
							if($is_present == 1){
								if($model != NULL){
									if($model->delete()){
										echo json_encode(array('status'=>'success', 'is_present'=>$is_present));
										exit;
									}
								}
							}
							else{
								if($model == NULL){
									$model	= new TeacherSubjectwiseAttentance;								
								}
								$model->employee_id		= $_POST['employee_id'];
								$model->timetable_id	= $_POST['timetable_id'];
								$model->reason			= $_POST['reason'];
								$model->leavetype_id	= $_POST['leavetype_id'];
								$model->date			= date('Y-m-d', strtotime($_POST['date']));
								$model->weekday_id		= $_POST['weekday_id'];
								$model->subject_id		= $_POST['subject_id'];
								if($model->save()){
									echo json_encode(array('status'=>'success', 'is_present'=>$is_present));
									exit;
								}
								else{
									$errors	= $model->getErrors();
									echo json_encode(array('status'=>'error', 'errors'=>$errors));
									exit;
								}
							}
						}
						else{
							$response["error"] = true;
							$response["error_msg"] = "Invalid Request";
							echo json_encode($response);
							exit;
						}
					}
					else{ //In case of teacher user
						$flag			= 0;
						$timing_arr		= array();
						$message		= '';
						$employee		= Employees::model()->findByAttributes(array('uid'=>$uid));
						$employee_id	= $employee->id;
						if(isset($_POST['date']) and $_POST['date'] != NULL){
							$date	= date('Y-m-d', strtotime($_POST['date'])); 
						}
						else{
							$date	= date('Y-m-d');
						}
						$day			= date('w', strtotime($date)) + 1;
						$check_weekday	= $this->isWeekday($date);
						$check_holiday	= $this->isHoliday($date);	
						if($check_holiday == 1){
							$flag		= 1;
							$message	= 'Selected date is a Holiday'; 
						}
						else if($check_weekday == 1){
							$flag		= 1;
							$message	= 'Selected date is not a weekday'; 
						}							
						else{
							
							if(Configurations::model()->timetableFormat() == 1){
								$criteria				= new CDbCriteria();
								$criteria->join			= 'JOIN `class_timings` `t1` ON `t1`.`id` = `t`.`class_timing_id`';
								$criteria->condition	= '`t`.`employee_id`=:employee_id AND `t`.`weekday_id`=:weekday_id';
								$criteria->params		= array(':employee_id'=>$employee_id, ':weekday_id'=>$day);
								$criteria->order 		= "STR_TO_DATE(`t1`.`start_time`, '%h:%i %p')"; 
								$criteria->group		= '`t`.`class_timing_id`';   
								$model					= TimetableEntries::model()->findAll($criteria);							
							}
							else{
								$weekday_attributes	= array(1=>'on_sunday', 2=>'on_monday', 3=>'on_tuesday', 4=>'on_wednesday', 5=>'on_thursday', 6=>'on_friday', 7=>'on_saturday');
								
								$criteria				= new CDbCriteria();
								$criteria->join			= 'JOIN `class_timings` `t1` ON `t1`.`id` = `t`.`class_timing_id`';
								$criteria->condition	= '`t`.`employee_id`=:employee_id AND `t`.`weekday_id`=:weekday_id AND `t1`.`'.$weekday_attributes[$day].'`=:week_day_status';
								$criteria->params		= array(':employee_id'=>$employee_id, ':weekday_id'=>$day, ':week_day_status'=>1);
								$criteria->order 		= "STR_TO_DATE(`t1`.`start_time`, '%h:%i %p')";   
								$criteria->group		= '`t`.`class_timing_id`'; 
								$model					= TimetableEntries::model()->findAll($criteria);
							}
							
							if($model != NULL){
								foreach($model as $key => $value){
									$class_timing	= ClassTimings::model()->findByPk($value->class_timing_id);
									$subject_name	= $this->getSubjectName($value->id);
									$batch			= Batches::model()->findByPk($value->batch_id);
									$course			= Courses::model()->findByAttributes(array('id'=>$batch->course_id));
									$is_absent		= TeacherSubjectwiseAttentance::model()->findByAttributes(array('employee_id'=>$employee_id, 'timetable_id'=>$value->id, 'weekday_id'=>$value->weekday_id, 'date'=>$date));
									$is_present		= 1;
									//traslate AM and PM
									$t1 			= date('h:i', strtotime($class_timing->start_time));	
									$t2 			= date('A', strtotime($class_timing->start_time));								
									$t3				= date('h:i', strtotime($class_timing->end_time));	
									$t4				= date('A', strtotime($class_timing->end_time));	
									
									$timing_arr[$key]['id']				= $value->id;
									$timing_arr[$key]['time']			= $t1.' '.$t2.' - '.$t3.' '.$t4;
									$timing_arr[$key]['subject']		= $subject_name;
									$timing_arr[$key]['batch']			= ($batch != NULL)?html_entity_decode(ucfirst($batch->name)).' ( '.html_entity_decode(ucfirst($course->course_name)).' )':'';										
									
									if($is_absent != NULL){
										$leave_type	= LeaveTypes::model()->findByPk($is_absent->leavetype_id);
										$is_present	= 0;
																				
										$timing_arr[$key]['leave_type']		= ($leave_type != NULL)?html_entity_decode(ucfirst($leave_type->type)):'';										
										$timing_arr[$key]['reason']			= html_entity_decode(ucfirst($is_absent->reason));									
									}
									if($date <= date('Y-m-d')){
										$timing_arr[$key]['is_present']	= $is_present; //1 => Present, 0 => Absent
									}
								}
							}
						}	
						if($flag == 1){
							echo json_encode(array('flag' =>$flag, 'message'=>$message), JSON_UNESCAPED_SLASHES);						
						}
						else{
							echo json_encode(array('flag' =>$flag, 'timings'=>$timing_arr), JSON_UNESCAPED_SLASHES);						
						}
						exit;					
					}
				}
				else if($tag == 'daily_subject_wise_attendance'){ //Manage students daily subjectwise attendance
					$uid	= $_POST['uid'];					
					$role	= Rights::getAssignedRoles($uid);
					if(key($role) == 'teacher'){ //In case of Teacher users
						$batch_id	= $_POST['batch_id'];						
						if(isset($_POST['date']) and $_POST['date'] != NULL){
							$date	= date('Y-m-d', strtotime($_POST['date'])); 
						}
						else{
							$date	= date('Y-m-d');
						}
						$class_timing_arr	= array();
						$student_arr		= array();
						$leave_type_arr		= array();
						$header_arr			= array();
						$status				= 1;
						$message			= '';
						$day 				= date('w', strtotime($date));
						$batch				= Batches::model()->findByPk($batch_id);
						$employee			= Employees::model()->findByAttributes(array('uid'=>$uid));
						if($batch){ //Check whether the batch is present
							$is_holiday		= StudentAttentance::model()->isHoliday($date);
							$isWeekday		= $this->checkWeekday($day + 1, $batch_id); //Check whether the selected day is a weekday	
							if($isWeekday == 1){
								if(!$is_holiday){ //Check whether the selected day is a holiday or not
									if($batch->start_date <= $date and $date <= $batch->end_date){ //Check whether the selected date is in between the batch dates
										$weekday	= Weekdays::model()->find("batch_id=:x AND weekday=:weekday", array(':x'=>$batch_id, ':weekday'=>($day + 1)));										
										if($weekday == NULL){
											$weekday	= Weekdays::model()->find("batch_id IS NULL AND weekday=:weekday", array(':weekday'=>($day + 1)));
										}
										if($weekday!=NULL){										
											$weekday_attributes	= array(1=>'on_sunday', 2=>'on_monday', 3=>'on_tuesday', 4=>'on_wednesday', 5=>'on_thursday', 6=>'on_friday', 7=>'on_saturday');																				
											
											//Class timings
											if($batch->employee_id == $employee->id){
												$criteria				= new CDbCriteria;
												$criteria->condition 	= "batch_id=:batch_id AND `".$weekday_attributes[$day+1]."`=:week_day_status";
												$criteria->params 		= array(':batch_id'=>$batch_id, ':week_day_status'=>1);
												$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
												$timings 				= ClassTimings::model()->findAll($criteria);  
											}
											else{
												$criteria				= new CDbCriteria;
												$criteria->join			= 'JOIN `timetable_entries` `t1` ON `t1`.`class_timing_id` = `t`.`id`';										
												$criteria->condition 	= "`t`.`batch_id`=:batch_id AND `t`.`".$weekday_attributes[$day+1]."`=:week_day_status AND `t1`.`employee_id`=:employee_id AND `t1`.`weekday_id`=:weekday_id";
												$criteria->params 		= array(':batch_id'=>$batch_id, ':week_day_status'=>1, ':employee_id'=>$employee->id, ':weekday_id'=>$day+1);
												$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
												$timings 				= ClassTimings::model()->findAll($criteria);  
											}
											if($timings){
												foreach($timings as $key => $timing){
													$class_timing_arr[$key]['key']		= $timing->id;
													$class_timing_arr[$key]['value']	= $timing->start_time.' - '.$timing->end_time;
												}
												
												//Leaves types list
												$leave_types	= StudentLeaveTypes::model()->findAllByAttributes(array('status'=>1));
												if($leave_types){
													foreach($leave_types as $key => $value){
														$leave_type_arr[$key]['key']	= $value->id;
														$leave_type_arr[$key]['value']	= html_entity_decode(ucfirst($value->name));	
													}
												}
												
												if(isset($_POST['class_timing_id']) and $_POST['class_timing_id'] != NULL){
													$class_timing_id	= $_POST['class_timing_id'];
												}
												else{
													$class_timing_id	= $timings[0]['id'];
												}
												//Get students list uder the selected batch
												$students	= Yii::app()->getModule('students')->studentsOfBatch($batch_id);
												if($students){
													$set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$batch_id,'weekday_id'=>$weekday['weekday'],'class_timing_id'=>$class_timing_id));
													if($set){
														if($set->is_elective == 0){ //In case of normal subject
															$subject	= Subjects::model()->findByPk($set->subject_id);
															$employee	= Employees::model()->findByPk($set->employee_id);
															if($subject){
																$header_arr['subject']	= html_entity_decode(ucfirst($subject->name));
															}
															if($employee){
																$header_arr['employee']	= html_entity_decode(ucfirst($employee->fullname));
															}
														}
														else{ //In case of elective subject															
															$elective	= Electives::model()->findByPk($set->subject_id);
															if($elective){
																$elective_group	= ElectiveGroups::model()->findByPk($elective->elective_group_id);
																if($elective_group){
																	$header_arr['subject']	= html_entity_decode(ucfirst($elective_group->name));
																}
															}															
														}
													}
													foreach($students as $key => $student){
														$student_arr[$key]['id']	= $student->id;
														$student_arr[$key]['name']	= $student->studentFullName();
														if($date < $student->admission_date){
															$student_arr[$key]['status']	= 0;
															$student_arr[$key]['message']	= 'Student has not joined yet';
														}
														else{
															if($date >= $student->admission_date and $date <= date("Y-m-d")){
																if($set == NULL){
																	$is_break = ClassTimings::model()->findByAttributes(array('id'=>$class_timing_id,'is_break'=>1));
																	if($is_break != NULL){
																		$student_arr[$key]['status']	= 0;
																		$student_arr[$key]['message']	= 'Break';
																	}
																	else{
																		$student_arr[$key]['status']	= 0;
																		$student_arr[$key]['message']	= 'Not Assigned';
																	}																
																}
																else{
																	$flag = 1;
																	if($set->is_elective == 2){
																		$elective	= Electives::model()->findByPk($set->subject_id);
																		if($elective){
																			$student_elective	= StudentElectives::model()->findByAttributes(array('elective_group_id'=>$elective->elective_group_id, 'student_id'=>$student->id));
																			if($student_elective == NULL){
																				$flag	= 0;
																			}
																		}
																	}
																	if($flag == 1){
																		$subjectwise =  StudentSubjectwiseAttentance::model()->findByAttributes(array('student_id'=>$student->id, 'timetable_id'=>$set->id, 'weekday_id'=>$set->weekday_id, 'date'=>$date));
																		
																		$student_arr[$key]['status']		= 1;
																		$student_arr[$key]['timetable_id']	= $set->id;
																		$student_arr[$key]['subject_id']	= $set->subject_id;
																		$student_arr[$key]['weekday_id']	= $set->weekday_id;
																		$student_arr[$key]['date']			= $date;
																		if($subjectwise == NULL){																	
																			$student_arr[$key]['is_present']	= 1; //indicate present
																		}
																		else{
																			$leave_type	= StudentLeaveTypes::model()->findByPk($subjectwise->leavetype_id);
																			
																			$student_arr[$key]['is_present']	= 0; //indicate absent
																			$student_arr[$key]['leave_type']	= ($leave_type != NULL)?html_entity_decode(ucfirst($leave_type->name)):'';
																			$student_arr[$key]['leave_type_id']	= ($leave_type != NULL)?$leave_type->id:'';
																			$student_arr[$key]['reason']		= ($subjectwise->reason != NULL)?html_entity_decode(ucfirst($subjectwise->reason)):''; 
																			$student_arr[$key]['attendance_id']	= $subjectwise->id;
																		}
																	}
																	else{
																		$student_arr[$key]['status']	= 0;
																		$student_arr[$key]['message']	= 'Elective Not Assigned';
																	}
																}
															}
															else{
																$student_arr[$key]['status']	= 0;
																$student_arr[$key]['message']	= 'NA';
															}
														}
													}
												}
												else{
													$status		= 0;
													$message	= 'No Students';
												}
											}
											else{
												$status		= 0;
												$message	= 'No Class Timings';
											}
										}
										else{
											$status		= 0;
											$message	= 'Timetable is not set for this date';
										}
									}
									else{
										$status		= 0;
										$message	= 'No class on this date';
									}
								}
								else{
									$status		= 0;
									$message	= 'Selected date is a Holiday';
								}
							}
							else{
								$status		= 0;
								$message	= 'Selected date is not a weekday';
							}
						}
						
						if($status == 1){
							echo json_encode(array('status'=>$status, 'class_timings'=>$class_timing_arr, 'header'=>$header_arr,'leave_types'=>$leave_type_arr, 'students'=>$student_arr), JSON_UNESCAPED_SLASHES);
						}
						else{
							echo json_encode(array('status'=>$status, 'message'=>$message), JSON_UNESCAPED_SLASHES);
						}
						exit;	
					}
					else{ //In case od Admin User & Custom User
						$batch_id	= $_POST['batch_id'];						
						if(isset($_POST['date']) and $_POST['date'] != NULL){
							$date	= date('Y-m-d', strtotime($_POST['date'])); 
						}
						else{
							$date	= date('Y-m-d');
						}
						$class_timing_arr	= array();
						$student_arr		= array();
						$leave_type_arr		= array();
						$header_arr			= array();
						$status				= 1;
						$message			= '';
						$day 				= date('w', strtotime($date));
						$batch				= Batches::model()->findByPk($batch_id);						
						if($batch){ //Check whether the batch is present
							$is_holiday		= StudentAttentance::model()->isHoliday($date);
							$isWeekday		= $this->checkWeekday($day + 1, $batch_id); //Check whether the selected day is a weekday	
							if($isWeekday == 1){
								if(!$is_holiday){ //Check whether the selected day is a holiday or not
									if($batch->start_date <= $date and $date <= $batch->end_date){ //Check whether the selected date is in between the batch dates
										$weekday	= Weekdays::model()->find("batch_id=:x AND weekday=:weekday", array(':x'=>$batch_id, ':weekday'=>($day + 1)));										
										if($weekday == NULL){
											$weekday	= Weekdays::model()->find("batch_id IS NULL AND weekday=:weekday", array(':weekday'=>($day + 1)));
										}
										if($weekday!=NULL){
											//Class timings
											$weekday_attributes	= array(1=>'on_sunday', 2=>'on_monday', 3=>'on_tuesday', 4=>'on_wednesday', 5=>'on_thursday', 6=>'on_friday', 7=>'on_saturday');																				
											
											$criteria				= new CDbCriteria;
											$criteria->condition 	= "batch_id=:batch_id AND `".$weekday_attributes[$day+1]."`=:week_day_status";
											$criteria->params 		= array(':batch_id'=>$batch_id, ':week_day_status'=>1);
											$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
											$timings 				= ClassTimings::model()->findAll($criteria);  										                                                  
											if($timings){
												foreach($timings as $key => $timing){
													$class_timing_arr[$key]['key']		= $timing->id;
													$class_timing_arr[$key]['value']	= $timing->start_time.' - '.$timing->end_time;
												}
												
												//Leaves types list
												$leave_types	= StudentLeaveTypes::model()->findAllByAttributes(array('status'=>1));
												if($leave_types){
													foreach($leave_types as $key => $value){
														$leave_type_arr[$key]['key']	= $value->id;
														$leave_type_arr[$key]['value']	= html_entity_decode(ucfirst($value->name));	
													}
												}
												
												if(isset($_POST['class_timing_id']) and $_POST['class_timing_id'] != NULL){
													$class_timing_id	= $_POST['class_timing_id'];
												}
												else{
													$class_timing_id	= $timings[0]['id'];
												}
												//Get students list uder the selected batch
												$students	= Yii::app()->getModule('students')->studentsOfBatch($batch_id);
												if($students){
													$set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$batch_id,'weekday_id'=>$weekday['weekday'],'class_timing_id'=>$class_timing_id));
													if($set){
														if($set->is_elective == 0){ //In case of normal subject
															$subject	= Subjects::model()->findByPk($set->subject_id);
															$employee	= Employees::model()->findByPk($set->employee_id);
															if($subject){
																$header_arr['subject']	= html_entity_decode(ucfirst($subject->name));
															}
															if($employee){
																$header_arr['employee']	= html_entity_decode(ucfirst($employee->fullname));
															}
														}
														else{ //In case of elective subject															
															$elective	= Electives::model()->findByPk($set->subject_id);
															if($elective){
																$elective_group	= ElectiveGroups::model()->findByPk($elective->elective_group_id);
																if($elective_group){
																	$header_arr['subject']	= html_entity_decode(ucfirst($elective_group->name));
																}
															}															
														}
													}
													foreach($students as $key => $student){
														$student_arr[$key]['id']	= $student->id;
														$student_arr[$key]['name']	= $student->studentFullName();
														if($date < $student->admission_date){
															$student_arr[$key]['status']	= 0;
															$student_arr[$key]['message']	= 'Student has not joined yet';
														}
														else{
															if($date >= $student->admission_date and $date <= date("Y-m-d")){
																if($set == NULL){
																	$is_break = ClassTimings::model()->findByAttributes(array('id'=>$class_timing_id,'is_break'=>1));
																	if($is_break != NULL){
																		$student_arr[$key]['status']	= 0;
																		$student_arr[$key]['message']	= 'Break';
																	}
																	else{
																		$student_arr[$key]['status']	= 0;
																		$student_arr[$key]['message']	= 'Not Assigned';
																	}																
																}
																else{
																	$flag = 1;
																	if($set->is_elective == 2){
																		$elective	= Electives::model()->findByPk($set->subject_id);
																		if($elective){
																			$student_elective	= StudentElectives::model()->findByAttributes(array('elective_group_id'=>$elective->elective_group_id, 'student_id'=>$student->id));
																			if($student_elective == NULL){
																				$flag	= 0;
																			}
																		}
																	}
																	if($flag == 1){
																		$subjectwise =  StudentSubjectwiseAttentance::model()->findByAttributes(array('student_id'=>$student->id, 'timetable_id'=>$set->id, 'weekday_id'=>$set->weekday_id, 'date'=>$date));
																		
																		$student_arr[$key]['status']		= 1;
																		$student_arr[$key]['timetable_id']	= $set->id;
																		$student_arr[$key]['subject_id']	= $set->subject_id;
																		$student_arr[$key]['weekday_id']	= $set->weekday_id;
																		$student_arr[$key]['date']			= $date;
																		if($subjectwise == NULL){																	
																			$student_arr[$key]['is_present']	= 1; //indicate present
																		}
																		else{
																			$leave_type	= StudentLeaveTypes::model()->findByPk($subjectwise->leavetype_id);
																			
																			$student_arr[$key]['is_present']	= 0; //indicate absent
																			$student_arr[$key]['leave_type']	= ($leave_type != NULL)?html_entity_decode(ucfirst($leave_type->name)):'';
																			$student_arr[$key]['leave_type_id']	= ($leave_type != NULL)?$leave_type->id:'';
																			$student_arr[$key]['reason']		= ($subjectwise->reason != NULL)?html_entity_decode(ucfirst($subjectwise->reason)):''; 
																			$student_arr[$key]['attendance_id']	= $subjectwise->id;
																		}
																	}
																	else{
																		$student_arr[$key]['status']	= 0;
																		$student_arr[$key]['message']	= 'Elective Not Assigned';
																	}
																}
															}
															else{
																$student_arr[$key]['status']	= 0;
																$student_arr[$key]['message']	= 'NA';
															}
														}
													}
												}
												else{
													$status		= 0;
													$message	= 'No Students';
												}
											}
											else{
												$status		= 0;
												$message	= 'No Class Timings';
											}
										}
										else{
											$status		= 0;
											$message	= 'Timetable is not set for this date';
										}
									}
									else{
										$status		= 0;
										$message	= 'No class on this date';
									}
								}
								else{
									$status		= 0;
									$message	= 'Selected date is a Holiday';
								}
							}
							else{
								$status		= 0;
								$message	= 'Selected date is not a weekday';
							}
						}
						
						if($status == 1){
							echo json_encode(array('status'=>$status, 'class_timings'=>$class_timing_arr, 'header'=>$header_arr,'leave_types'=>$leave_type_arr, 'students'=>$student_arr), JSON_UNESCAPED_SLASHES);
						}
						else{
							echo json_encode(array('status'=>$status, 'message'=>$message), JSON_UNESCAPED_SLASHES);
						}
						exit;						
					}									
				}
				else if($tag == 'attendance_settings'){ //Check whether daywise or subjectwise or both attendance is enabled
					$uid		= $_POST['uid'];
					$return_arr	= array();					
					$role		= Rights::getAssignedRoles($uid);
					if(key($role) == 'student'){
						$student	= Students::model()->findByAttributes(array('uid'=>$uid));
						$student_id	= $student->id;
					}
					else{
						$student_id	= $_POST['student_id'];
						$student	= Students::model()->findByAttributes(array('id'=>$student_id));
					}
					//Find active batch
					$criteria				= new CDbCriteria();
					$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`batch_id` = `t`.`id`'; 
					$criteria->condition	= '`t`.`is_active`=:is_active AND `t`.`is_deleted`=:is_deleted AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status AND `t1`.`student_id`=:student_id';
					$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':status'=>1, ':result_status'=>0, ':student_id'=>$student->id);
					$batch					= Batches::model()->find($criteria);
					if($batch){												
						$flag					= 1; //1 => Display day wise & subjectwise attendance, 2 => Display day wise Attendance only, 3 => Display subjectwise attendance only
						$model		= Configurations::model()->findByAttributes(array('config_key'=>'Student Attendance'));
						if($model->config_value == 3){ 
							$flag	= 1; //Display day wise & subjectwise attendance
						}
						else if($model->config_value == 1){ 
							$flag	= 2; //Display day wise Attendance only
						}
						else if($model->config_value == 2){ 
							$flag	= 3; //Display subjectwise attendance only
						}
						$return_arr['status']	= 1;
						$return_arr['flag']		= $flag;																	
					}
					else{
						$return_arr['status']	= 0;
						$return_arr['message']	= 'No Active Batch';
					}
					
					echo json_encode($return_arr, JSON_UNESCAPED_SLASHES);
					exit;
				}
				else if($tag == 'change_language'){ //User language change
					$uid		= $_POST['uid'];
					$language	= $_POST['language']; 
					$model		= UserSettings::model()->findByAttributes(array('user_id'=>$uid));					
					if($model != NULL){
						$model->language	= $language;
						if($model->save()){
							echo json_encode(array('status'=>'success'), JSON_UNESCAPED_SLASHES);
							exit;
						}
						else{
							echo json_encode(array('status'=>'error'), JSON_UNESCAPED_SLASHES);
							exit;
						}
					}
					else{
						$admin_settings 	= UserSettings::model()->findByAttributes(array('user_id'=>1));	
						
						$model 				= new UserSettings;
						$model->attributes 	= $admin_settings->attributes;
						$model->user_id		= $uid; 
						$model->language	= $language;
						if($model->save()){
							echo json_encode(array('status'=>'success'), JSON_UNESCAPED_SLASHES);
							exit;
						}
						else{
							echo json_encode(array('status'=>'error'), JSON_UNESCAPED_SLASHES);
							exit;
						}						
					}
				}
				else if($tag == 'view_subjectwise_attendance'){ //Subjectwise attendance view in parent & student portal
					$uid			= $_POST['uid'];
					$return_arr		= array();	
					$role			= Rights::getAssignedRoles($uid);
					$batch_arr		= array();
					
					if(key($role) == 'student'){
						$student	= Students::model()->findByAttributes(array('uid'=>$uid));
						$student_id	= $student->id;
					}
					else{
						$student_id	= $_POST['student_id'];
						$student	= Students::model()->findByAttributes(array('id'=>$student_id));
					}
					
					$batches	= BatchStudents::model()->StudentBatch($student_id);
					
					if($batches){
						foreach($batches as $key => $value){							
							$batch_arr[$key]['id']		= $value->id;
							$batch_arr[$key]['name']	= html_entity_decode(ucfirst($value->name)).' ( '.html_entity_decode(ucfirst($value->course123->course_name)).' )';
						}
						
						if(isset($_POST['date']) and $_POST['date'] != NULL){
							$date	= date('Y-m-d', strtotime($_POST['date']));
						}
						else{
							$date	= date('Y-m-d');
						}
						if(isset($_POST['batch_id']) and $_POST['batch_id'] != NULL){
							$batch_id	= $_POST['batch_id'];							
						}
						else{
							$batch_id	= $batches[0]['id'];
						}
						
						$day 				= date('w', strtotime($date));	
						$batch				= Batches::model()->findByPk($batch_id);																
						$course				= Courses::model()->findByAttributes(array('id'=>$batch->course_id));
						$header['course']	= ($course != NULL)?html_entity_decode(ucfirst($course->course_name)):'-';
						$header['batch']	= html_entity_decode(ucfirst($batch->name));
						$header['name']		= $student->studentFullName();											
						$isWeekday			= $this->checkWeekday($day + 1, $batch_id); //Check whether the selected day is a weekday	
						
						if($isWeekday == 1){
							$weekday_attributes	= array(1=>'on_sunday', 2=>'on_monday', 3=>'on_tuesday', 4=>'on_wednesday', 5=>'on_thursday', 6=>'on_friday', 7=>'on_saturday');	
							//Class timings							
							$criteria				= new CDbCriteria;
							$criteria->condition 	= "batch_id=:batch_id AND `".$weekday_attributes[$day+1]."`=:week_day_status";
							$criteria->params 		= array(':batch_id'=>$batch_id, ':week_day_status'=>1);
							$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
							$timings 				= ClassTimings::model()->findAll($criteria);  
														
							if($timings){
								foreach($timings as $key_timing => $value_timing){	
									$return_arr[$key_timing]['id']			= $value_timing->id;								
									$return_arr[$key_timing]['start_time']	= date("H:i", strtotime($value_timing->start_time));
									$return_arr[$key_timing]['end_time']	= date("H:i", strtotime($value_timing->end_time));
									
									if($value_timing->is_break == '0'){																		
										$timetableEntries	= TimetableEntries::model()->findByAttributes(array('batch_id'=>$batch_id, 'weekday_id'=>$day+1, 'class_timing_id'=>$value_timing->id));										
										if($timetableEntries){
											if($date >= $batch->start_date and $date <= $batch->end_date){
												if($date >= $student->admission_date){
													if($date <= date('Y-m-d')){
														$return_arr[$key_timing]['is_present']	= 1;																
													}
												}
											}
											if($timetableEntries->is_elective == 2){
												$elective	= Electives::model()->findByAttributes(array('id'=>$timetableEntries->subject_id));
												if($elective){
													$elective_group	= ElectiveGroups::model()->findByAttributes(array('id'=>$elective->elective_group_id));
													if($elective_group){
														$return_arr[$key_timing]['subject'] = html_entity_decode(ucfirst($elective_group->name));
													}
												}												
											}
											else{
												$subject	= Subjects::model()->findByAttributes(array('id'=>$timetableEntries->subject_id));	
												if($subject){
													$return_arr[$key_timing]['subject'] = html_entity_decode(ucfirst($subject->name));
													$employee	= Employees::model()->findByAttributes(array('id'=>$timetableEntries->employee_id));
													if($employee){
														$return_arr[$key_timing]['employee'] = ucfirst($employee->first_name).' '.ucfirst($employee->middle_name).' '.ucfirst($employee->last_name);

													}
												}
																								
											}
											$subjectwise_attendance = StudentSubjectwiseAttentance::model()->findByAttributes(array('student_id'=>$student_id, 'timetable_id'=>$timetableEntries->id, 'weekday_id'=>$timetableEntries->weekday_id, 'date'=>$date));											
											if($subjectwise_attendance){
												$leave_type	= StudentLeaveTypes::model()->findByPk($subjectwise_attendance->leavetype_id);
												$return_arr[$key_timing]['leave_type']			= ($leave_type != NULL)?html_entity_decode(ucfirst($leave_type->name)).' ( '.html_entity_decode($leave_type->label).' ) ':'-';
												$return_arr[$key_timing]['leave_type_label']	= ($leave_type != NULL)?' ( '.html_entity_decode($leave_type->label).' ) ':'';
												$return_arr[$key_timing]['reason']		= ($subjectwise_attendance->reason != NULL)?html_entity_decode(ucfirst($subjectwise_attendance->reason)):'-'; 
												$return_arr[$key_timing]['is_present']	= 0;
											}
											
										}									
									}
									else{
										$return_arr[$key_timing]['subject']	= 'Break';
									}										
								}							
							}
						}
					}
					$post_data = json_encode(array('header'=>$header, 'timetable' =>$return_arr, 'batches'=>$batch_arr),JSON_UNESCAPED_SLASHES);
					echo $post_data;
					exit();
				}
				else if($tag == 'manage_subjectwise_attendance'){ //Manage subjectwise present & absence
					$settings		= UserSettings::model()->findByAttributes(array('user_id'=>1));	
					$uid			= $_POST['uid'];
					$student_id		= $_POST['student_id'];
					$timetable_id	= $_POST['timetable_id'];
					$weekday_id		= $_POST['weekday_id'];
					$subject_id		= $_POST['subject_id'];
					$date			= date('Y-m-d', strtotime($_POST['date']));
					$type			= $_POST['type']; //1 => Use switch feature, 2 => Use popup feature(with reason & leave type)
					$is_present		= 1; //1 => Indicate present, 0 => Indicate Absent
					
					if($type == 1){ //For Switching feature
						$model		= StudentSubjectwiseAttentance::model()->findByAttributes(array('student_id'=>$student_id, 'timetable_id'=>$timetable_id, 'date'=>$date, 'weekday_id'=>$weekday_id, 'subject_id'=>$subject_id));						
						if($model != NULL){							
							if($model->delete()){ //Mark present
								//Push Notification
								$reg_arr			= array();	
								$is_present			= 1; //Indicate Absent			
								$student			= Students::model()->findByPk($student_id);				
								$subject_name		= StudentSubjectwiseAttentance::model()->getSubjectName($model->timetable_id);
								$class_timing		= StudentSubjectwiseAttentance::model()->getClassTimingLabel($model->timetable_id);
								$sender				= Profile::model()->findByAttributes(array('user_id'=>$uid));								
								$sender_name		= ucfirst($sender->firstname).' '.ucfirst($sender->lastname);
								$timetable			= TimetableEntries::model()->findByAttributes(array('id'=>$_POST['timetable_id']));																								
																
								//Push Notification to guardian						
								$user_device	= PushNotifications::model()->getGuardianDevice($student_id);				
								//Get Messages
								$push_notifications		= PushNotifications::model()->getNotificationDatas(24);
								foreach($user_device as $value){								
									//Get key value of the notification data array					
									$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
									
									$message	= $push_notifications[$key]['message'];	
									$message	= str_replace("{Student Name}", $student->studentFullName(), $message);
									$message	= str_replace("{Subject Name}", $subject_name, $message);
									$message	= str_replace("{Class Timing}", $class_timing, $message);
									$message	= str_replace("{Date}", date($settings->displaydate, strtotime($model->date)), $message);
																	
									 $argument_arr = array('message'=>$message, 'device_id'=>array($value->device_id),'sender_name'=>$sender_name, 'date'=>$model->date, 'student_id'=>$student_id, 'class_timing_id'=>$timetable->class_timing_id);       
									Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "student_subjectwise_attendance");																		
								}	
								
								//Push notification to student
								$user_device	= PushNotifications::model()->getStudentDevice($student->uid);	
								//Get Messages
								$push_notifications		= PushNotifications::model()->getNotificationDatas(25);
								foreach($user_device as $value){								
									//Get key value of the notification data array					
									$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
									
									$message	= $push_notifications[$key]['message'];	
									$message	= str_replace("{Subject Name}", $subject_name, $message);
									$message	= str_replace("{Class Timing}", $class_timing, $message);
									$message	= str_replace("{Date}", date($settings->displaydate, strtotime($model->date)), $message);
									
									$argument_arr = array('message'=>$message, 'device_id'=>array($value->device_id), 'sender_name'=>$sender_name, 'date'=>$model->date, 'class_timing_id'=>$timetable->class_timing_id);  
									Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "student_subjectwise_attendance");																		
								}	
								
								echo json_encode(array('status'=>'success', 'is_present'=>$is_present), JSON_UNESCAPED_SLASHES);
								exit;
							}
						}
						else{ //Mark Absent
							$model					= new StudentSubjectwiseAttentance;
							$model->student_id		= $student_id;
							$model->timetable_id	= $timetable_id;
							$model->date			= $date;
							$model->weekday_id		= $weekday_id;
							$model->subject_id		= $subject_id;							
							if($model->save()){
								$is_present			= 0; //Indicate Absent
								//Mobile Push Notifications
								$student			= Students::model()->findByPk($student_id);				
								$subject_name		= StudentSubjectwiseAttentance::model()->getSubjectName($model->timetable_id);
								$class_timing		= StudentSubjectwiseAttentance::model()->getClassTimingLabel($model->timetable_id);
								$sender				= Profile::model()->findByAttributes(array('user_id'=>$uid));								
								$sender_name		= ucfirst($sender->firstname).' '.ucfirst($sender->lastname);
								$timetable			= TimetableEntries::model()->findByAttributes(array('id'=>$model->timetable_id));																									
																
								//Push Notification to guardian						
								$user_device	= PushNotifications::model()->getGuardianDevice($student_id);				
								//Get Messages
								$push_notifications		= PushNotifications::model()->getNotificationDatas(22);
								foreach($user_device as $value){								
									//Get key value of the notification data array					
									$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
									
									$message	= $push_notifications[$key]['message'];	
									$message	= str_replace("{Student Name}", $student->studentFullName(), $message);
									$message	= str_replace("{Subject Name}", $subject_name, $message);
									$message	= str_replace("{Class Timing}", $class_timing, $message);
									$message	= str_replace("{Date}", date($settings->displaydate, strtotime($model->date)), $message);
																	
									 $argument_arr = array('message'=>$message, 'device_id'=>array($value->device_id),'sender_name'=>$sender_name, 'date'=>$model->date, 'student_id'=>$student_id, 'class_timing_id'=>$timetable->class_timing_id);       
									Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "student_subjectwise_attendance");																		
								}				
								
								//Push notification to student
								$user_device	= PushNotifications::model()->getStudentDevice($student->uid);	
								//Get Messages
								$push_notifications		= PushNotifications::model()->getNotificationDatas(23);
								foreach($user_device as $value){								
									//Get key value of the notification data array					
									$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
									
									$message	= $push_notifications[$key]['message'];	
									$message	= str_replace("{Subject Name}", $subject_name, $message);
									$message	= str_replace("{Class Timing}", $class_timing, $message);
									$message	= str_replace("{Date}", date($settings->displaydate, strtotime($model->date)), $message);
									
									$argument_arr = array('message'=>$message, 'device_id'=>array($value->device_id), 'sender_name'=>$sender_name, 'date'=>$model->date, 'class_timing_id'=>$timetable->class_timing_id);  
									Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "student_subjectwise_attendance");																		
								}
																
								echo json_encode(array('status'=>'success', 'is_present'=>$is_present, 'attendance_id'=>$model->id), JSON_UNESCAPED_SLASHES);
								exit;
							}
						}						
					}
					else if($type == 2){ //For popup functionality. ie, with reason & leave type					
						if(isset($_POST['attendance_id']) and $_POST['attendance_id'] != NULL){
							$model	= StudentSubjectwiseAttentance::model()->findByPk($_POST['attendance_id']);
							if($model == NULL){
								$model					= new StudentSubjectwiseAttentance;	
								$model->student_id		= $student_id;
								$model->timetable_id	= $timetable_id;
								$model->date			= $date;
								$model->weekday_id		= $weekday_id;
								$model->subject_id		= $subject_id;		
							}
						}
						else{
							$model					= new StudentSubjectwiseAttentance;	
							$model->student_id		= $student_id;
							$model->timetable_id	= $timetable_id;
							$model->date			= $date;
							$model->weekday_id		= $weekday_id;
							$model->subject_id		= $subject_id;						
						}
																		
						$model->reason			= $_POST['reason'];
						$model->leavetype_id	= $_POST['leavetype_id'];						
						if($model->validate()){
							if($model->save()){
								$is_present			= 0; //Indicate Absent
								//Mobile Push Notifications
								//Mobile Push Notifications
								$student			= Students::model()->findByPk($student_id);				
								$subject_name		= StudentSubjectwiseAttentance::model()->getSubjectName($model->timetable_id);
								$class_timing		= StudentSubjectwiseAttentance::model()->getClassTimingLabel($model->timetable_id);
								$sender				= Profile::model()->findByAttributes(array('user_id'=>$uid));								
								$sender_name		= ucfirst($sender->firstname).' '.ucfirst($sender->lastname);
								$timetable			= TimetableEntries::model()->findByAttributes(array('id'=>$model->timetable_id));																									
																
								//Push Notification to guardian						
								$user_device	= PushNotifications::model()->getGuardianDevice($student_id);				
								//Get Messages
								$push_notifications		= PushNotifications::model()->getNotificationDatas(22);
								foreach($user_device as $value){								
									//Get key value of the notification data array					
									$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
									
									$message	= $push_notifications[$key]['message'];	
									$message	= str_replace("{Student Name}", $student->studentFullName(), $message);
									$message	= str_replace("{Subject Name}", $subject_name, $message);
									$message	= str_replace("{Class Timing}", $class_timing, $message);
									$message	= str_replace("{Date}", date($settings->displaydate, strtotime($model->date)), $message);
																	
									 $argument_arr = array('message'=>$message, 'device_id'=>array($value->device_id),'sender_name'=>$sender_name, 'date'=>$model->date, 'student_id'=>$student_id, 'class_timing_id'=>$timetable->class_timing_id);       
									Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "student_subjectwise_attendance");																		
								}				
								
								//Push notification to student
								$user_device	= PushNotifications::model()->getStudentDevice($student->uid);	
								//Get Messages
								$push_notifications		= PushNotifications::model()->getNotificationDatas(23);
								foreach($user_device as $value){								
									//Get key value of the notification data array					
									$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
									
									$message	= $push_notifications[$key]['message'];	
									$message	= str_replace("{Subject Name}", $subject_name, $message);
									$message	= str_replace("{Class Timing}", $class_timing, $message);
									$message	= str_replace("{Date}", date($settings->displaydate, strtotime($model->date)), $message);
									
									$argument_arr = array('message'=>$message, 'device_id'=>array($value->device_id), 'sender_name'=>$sender_name, 'date'=>$model->date, 'class_timing_id'=>$timetable->class_timing_id);  
									Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "student_subjectwise_attendance");																		
								}
																
								echo json_encode(array('status'=>'success', 'is_present'=>$is_present, 'attendance_id'=>$model->id), JSON_UNESCAPED_SLASHES);
								exit;
							}
						}
						else{
							$errors	= $model->getErrors();
							echo json_encode(array('status'=>'error', 'errors'=>$errors));
							exit;
						}
					}
				}
				elseif($tag == 'complaints') {	//Complaint List	
					//Complaint List			
					$uid		= $_POST['uid'];					
					$role		= Rights::getAssignedRoles($uid); 					
					$role_arr	= array();
					
					//In case of Admin & Custom User
					if(key($role) != 'student' and key($role) != 'parent' and key($role) != 'teacher'){ 
						//Get all user types
						$all_roles	= new RAuthItemDataProvider('roles', array('type'=>2));						
						$datas		= $all_roles->fetchData();	
						if($datas != NULL){
							foreach($datas as $key => $value){
								$role_arr[$key]['key']		= $value->name;
								$role_arr[$key]['value']	= ucfirst($value->name); 
							}
						}
						
						//For Pagination
						$page_id	= 1; 
						$limit		= 10;                                   
						if(($_POST['page_id']) && ($_POST['page_id'])!=NULL && ($_POST['page_id'])>1){
							$page_id	= $_POST['page_id'];
						}                                        
						if($page_id == 1){
							$start_limit	= 0;						
						}
						else{
							$start_limit	= (($page_id - 1) * 10);						
						}
					
						$criteria 							= new CDbCriteria;
						$criteria->join 					= 'JOIN authassignment t1 ON t.uid = t1.userid JOIN users t2 ON t.uid = t2.id'; 
						$criteria->condition				= 't2.status=:user_status';
						$criteria->params[':user_status']	= 1;
						$criteria->order 					= 't.status ASC, t.id DESC, t.date DESC';				
						if(isset($_REQUEST['subject']) and $_REQUEST['subject']!=NULL){
							$criteria->condition			= $criteria->condition.' AND t.subject LIKE :subject';
							$criteria->params[':subject'] 	= $_REQUEST['subject'].'%';
							$flag 							= 1;
						}
						if(isset($_REQUEST['status']) and $_REQUEST['status']!=NULL){
							$model->status					= $_REQUEST['Complaints']['status'];							
							$criteria->condition			= $criteria->condition.' AND t.status = :status';							
							$criteria->params[':status'] 	= $_REQUEST['status'];
							$flag 							= 1;
						}
						
						if(isset($_REQUEST['role']) and $_REQUEST['role']!=NULL){							
							$criteria->condition 		= $criteria->condition.' AND t1.itemname=:itemname';												
							$criteria->params[':itemname'] 	= $_REQUEST['role'];
						}						
						if(isset($_REQUEST['name']) and $_REQUEST['name']!=NULL){							
							$criteria1 = new CDbCriteria;										
							if((substr_count( $_REQUEST['name'],' ')) == 0){ 	
								$criteria1->condition		= '(firstname LIKE :name or lastname LIKE :name)';
								$criteria1->params[':name'] = $_REQUEST['name'].'%';
							}
							else if((substr_count( $_REQUEST['name'],' ')) >= 1){
								$name							= explode(" ",$_REQUEST['name']);
								$criteria1->condition			= '(firstname LIKE :name or lastname LIKE :name)';
								$criteria1->params[':name'] 	= $name[0].'%';
								$criteria1->condition			= $criteria1->condition.' and '.'(firstname LIKE :name1 or lastname LIKE :name1)';
								$criteria1->params[':name1'] 	= $name[1].'%';
							}
							$users 	= Profile::model()->findAll($criteria1);
							$ids 	= array();
							foreach($users as $user){
								$ids[] = $user->user_id;
							}			
							$criteria->addInCondition('uid', $ids);
						}
						$criteria->offset		= $start_limit;
						$criteria->limit		= $limit;
					}
					else{ //In case of Student, Parent, Teacher User
						//For Pagination
						$page_id	= 1; 
						$limit		= 10;                                   
						if(($_POST['page_id']) && ($_POST['page_id'])!=NULL && ($_POST['page_id'])>1){
							$page_id	= $_POST['page_id'];
						}                                        
						if($page_id == 1){
							$start_limit	= 0;						
						}
						else{
							$start_limit	= (($page_id - 1) * 10);						
						}
					
						$criteria 				= new CDbCriteria();
						$criteria->condition	= 'uid=:uid';
						$criteria->params		= array(':uid'=>$uid);
						$criteria->order		= 'date DESC'; 
						$criteria->offset		= $start_limit;
						$criteria->limit		= $limit;
					}
					$complaints	= Complaints::model()->findAll($criteria);					
					$return_arr	= array();		
					$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));	
					$i			= 0;		
					foreach ($complaints as $key => $value) {
						$category	= ComplaintCategories::model()->findByPk($value->category_id);	
						$name		= Complaints::model()->getName($value->uid);						
										
						$return_arr[$i]['id']			= $value->id;
						$return_arr[$i]['name']			= ($name != NULL)?$name:'-';
						$return_arr[$i]['subject']		= html_entity_decode(ucfirst(trim($value->subject)));
						$return_arr[$i]['complaint']	= html_entity_decode(ucfirst(trim($value->complaint)));
						$return_arr[$i]['category']		= ($category != NULL)?html_entity_decode(ucfirst($category->category)):'-';
						$return_arr[$i]['date']			= date($settings->displaydate, strtotime($value->date));
						$return_arr[$i]['status_id']	= $value->status;
						$return_arr[$i]['status']		= ($value->status==1)?"Closed":"Open";					
						
						$i++;
						
					}
					
					echo json_encode(array('complaints'=>$return_arr, 'user_types'=>$role_arr), JSON_UNESCAPED_SLASHES);
					exit;					
				
				}
				elseif($tag == 'complaint_category') {								
					$return_arr	= array();					
					$categories	= ComplaintCategories::model()->findAll();
					if($categories){
						foreach($categories as $key => $value){	
							$return_arr[$key]['id']		= $value->id;			
							$return_arr[$key]['name']	= html_entity_decode(ucfirst($value->category));					
						}					
					}
					echo json_encode($return_arr);					
				}
				else if($tag == 'add_complaint'){	//Add Complaint							
					$model					= new Complaints;					
					$model->uid				= $_POST['uid'];
					$model->category_id		= $_POST['category'];
					$model->subject			= $_POST['subject'];
					$model->complaint		= $_POST['complaint'];
					$model->viewed			= 0;
					$model->status			= 0;
					$model->date			= date("Y-m-d H:i:s");					
					if($model->validate()){
						if($model->save()){
							//For Mobile Notification
							if(Configurations::model()->isAndroidEnabled()){
								$uid	= $_POST['uid'];
								$roles	= Rights::getAssignedRoles($uid);								
								//In case of Parent
								if(key($roles) == 'parent'){
									$parent 		= Guardians::model()->findByAttributes(array('uid'=>$uid));	
									$parent_name	= ucfirst($parent->first_name).' '.ucfirst($parent->last_name);									
									$category		= ComplaintCategories::model()->findByPk($model->category_id);
									//Student						
									$student 	= PushNotifications::model()->getStudents($parent->id);
									
									//Student Active Batch
									$student_name	= '-';
									$batch_name		= '-';
									if($student != NULL){											
										$batch				= PushNotifications::model()->getStudentActiveBatch($student->id);
										if($batch != NULL){
											$batch_name	= html_entity_decode(ucfirst($batch->name));
										}
										$student_name	= $student->getStudentname();
									}
									
									//Admin Level Users
									$criteria				= new CDbCriteria();
									$criteria->join			= 'JOIN `authassignment` `t1` ON `t1`.`userid` = `t`.`uid`';					
									$criteria->condition	= '`t1`.`itemname`=:itemname';
									$criteria->params		= array(':itemname'=>'Admin');					
									$user_device 			= UserDevice::model()->findAll($criteria);
									
									//Get Messages
									$push_notifications		= PushNotifications::model()->getNotificationDatas(4);
									foreach($user_device as $value){								
										//Get key value of the notification data array					
										$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
										
										$message	= $push_notifications[$key]['message'];
										$message	= str_replace("{Guardian Name}", $parent_name, $message);
										$message	= str_replace("{Student Name}", $student_name, $message);
										$message	= str_replace("{Batch Name}", $batch_name, $message);
										$message	= str_replace("{Category}", html_entity_decode(ucfirst($category->category)), $message);	
										
										$argument_arr = array('message' => $message, 'sender_name' => $parent_name, 'device_id' => array($value->device_id), 'id'=>$model->id, 'type'=>'1');                
										Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "complaints");																		
									}						
								}
								else if(key($roles) == 'student'){ //In case of Student
									$student 		= Students::model()->findByAttributes(array('uid'=>$uid));
									$category		= ComplaintCategories::model()->findByAttributes(array('id'=>$model->category_id));							
									//Student Active Batch
									$student_name	= '-';
									$batch_name		= '-';
									if($student != NULL){
										$batch	= PushNotifications::model()->getStudentActiveBatch($student->id);
										if($batch != NULL){
											$batch_name	= html_entity_decode(ucfirst($batch->name));
										}
										$student_name	= $student->getStudentname();
									}
									//Admin Level Users
									$criteria				= new CDbCriteria();
									$criteria->join			= 'JOIN `authassignment` `t1` ON `t1`.`userid` = `t`.`uid`';					
									$criteria->condition	= '`t1`.`itemname`=:itemname';
									$criteria->params		= array(':itemname'=>'Admin');					
									$user_device 			= UserDevice::model()->findAll($criteria);
									
									//Get Messages
									$push_notifications		= PushNotifications::model()->getNotificationDatas(3);
									foreach($user_device as $value){								
										//Get key value of the notification data array					
										$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
										
										$message	= $push_notifications[$key]['message'];
										$message	= str_replace("{Student Name}", $student_name, $message);
										$message	= str_replace("{Batch Name}", $batch_name, $message);
										$message	= str_replace("{Category}", html_entity_decode(ucfirst($category->category)), $message);		
										
										$argument_arr = array('message' => $message, 'sender_name' => $student_name, 'device_id' => array($value->device_id), 'id'=>$model->id, 'type'=>'1');                
										Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "complaints");																	
									}						
								}
								else if(key($roles) == 'teacher'){ //In case of Teacher
									$teacher 		= Employees::model()->findByAttributes(array('uid'=>$uid));
									$category		= ComplaintCategories::model()->findByAttributes(array('id'=>$model->category_id));													
									$teacher_name	= $teacher->getFullname();
									
									//Admin Level Users
									$criteria				= new CDbCriteria();
									$criteria->join			= 'JOIN `authassignment` `t1` ON `t1`.`userid` = `t`.`uid`';					
									$criteria->condition	= '`t1`.`itemname`=:itemname';
									$criteria->params		= array(':itemname'=>'Admin');					
									$user_device 			= UserDevice::model()->findAll($criteria);	
									
									//Get Messages
									$push_notifications		= PushNotifications::model()->getNotificationDatas(5);
									foreach($user_device as $value){								
										//Get key value of the notification data array					
										$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
										
										$message	= $push_notifications[$key]['message'];
										$message	= str_replace("{Teacher Name}", $teacher_name, $message);							
										$message	= str_replace("{Category}", html_entity_decode(ucfirst($category->category)), $message);				
										
										$argument_arr = array('message' => $message, 'sender_name' => $teacher_name, 'device_id' => array($value->device_id), 'id'=>$model->id, 'type'=>'1');                  
										Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "complaints");																	
									}						
								}												
							}
														
							echo json_encode(array('status'=>"success"));							
						}												
					}
					else{
						$errors	= $model->getErrors();																			
						echo json_encode(array('status'=>"error", 'errors'=>$errors));	
						exit;
					}									
				}
				else if($tag == 'complaint_manage'){ //For closing or reopening the complaint
					$uid		= $_POST['uid'];
					$id			= $_POST['id'];
					$type		= $_POST['type']; //1=>Close, 2=>Reopen
					$response	= array();
					if($type != NULL){					
						$model	= Complaints::model()->findByPk($id);					
						if($model != NULL){
							if($type == 1){ //For closing
								$model->status		= 1;
								$model->closed_by	= $uid;								
							}
							else if($type == 2){ //For reopening
								$model->status			= 0;
								
								$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
								$timezone 	= Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
								date_default_timezone_set($timezone->timezone);
								
								$model->reopened_date	= date('Y-m-d H:i:s');
							}
							if($model->save()){
								//For Mobile Notification
								if(Configurations::model()->isAndroidEnabled()){
									$sender_name	= PushNotifications::model()->getUserName($uid);	
									if($model->uid == $uid){ //If the action is performed by the complaint created user
										//Admin Level Users
										$criteria				= new CDbCriteria();
										$criteria->join			= 'JOIN `authassignment` `t1` ON `t1`.`userid` = `t`.`uid`';					
										$criteria->condition	= '`t1`.`itemname`=:itemname';
										$criteria->params		= array(':itemname'=>'Admin');					
										$user_device			= UserDevice::model()->findAll($criteria);
									}
									else{
										$criteria				= new CDbCriteria();
										$criteria->condition	= 'uid=:uid';
										$criteria->params		= array(':uid'=>$model->uid);
										$criteria->group		= 'device_id';
										$user_device			= UserDevice::model()->findAll($criteria);
									}					
									//Get Messages
									if($type == 1){ //For closing
										$push_notifications		= PushNotifications::model()->getNotificationDatas(6);
									}
									else{ //For reopening
										$push_notifications		= PushNotifications::model()->getNotificationDatas(7);
									}
									foreach($user_device as $value){								
										//Get key value of the notification data array					
										$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
										
										$message	= $push_notifications[$key]['message'];
										$message	= str_replace("{Subject}", html_entity_decode(ucfirst($model->subject)), $message);							
										$message	= str_replace("{User Name}", $sender_name, $message);				
										
										$argument_arr = array('message' => $message, 'sender_name' => $sender_name, 'device_id' => array($value->device_id), 'id'=>$model->id, 'type'=>'1');                  
										Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "complaints");																	
									}					
								}
								
								echo json_encode(array('status'=>'success'));
								exit;
							}
							else{
								$response["error"] 		= true;
								$response["error_msg"] 	= "Invalid Request";
								echo json_encode($response);
								exit;
							}
						}
						else{
							$response["error"] 		= true;
							$response["error_msg"] 	= "Invalid Request";
							echo json_encode($response);
							exit;	
						}
					}
					else{
						$response["error"] 		= true;
						$response["error_msg"] 	= "Invalid Request";
						echo json_encode($response);
						exit;
					}				
				}
				else if($tag == 'complaint_view'){ //View the details fo the complaint & chat history
					$uid		= $_POST['uid'];
					$id			= $_POST['id'];
					$type		= $_POST['type']; //1=>Details of the complaint, 2=>Chat list
					$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));	
					$model		= Complaints::model()->findByPk($id);
					$return_arr	= array();
					if($model){
						if($type == 1){ //Details of the complaint
							$category	= ComplaintCategories::model()->findByPk($model->category_id);																	
											
							$return_arr['id']			= $model->id;
							$return_arr['name']			= PushNotifications::model()->getUserName($model->uid);
							$return_arr['subject']		= html_entity_decode(ucfirst(trim($model->subject)));
							$return_arr['complaint']	= html_entity_decode(ucfirst(trim($model->complaint)));
							$return_arr['category']		= ($category != NULL)?html_entity_decode(ucfirst($category->category)):'-';
							$return_arr['date']			= date($settings->displaydate,strtotime($model->date));
							$return_arr['status_id']	= $model->status;
							$return_arr['status']		= ($model->status==1)?Yii::t('app', 'Closed'):Yii::t('app', 'Open');
							
							echo json_encode($return_arr, JSON_UNESCAPED_SLASHES);
							exit;
						}
						else if($type == 2){ //For getting chat list
							$return_arr	= array();
							
							$criteria				= new CDbCriteria();
							$criteria->condition	= 'complaint_id=:complaint_id';
							$criteria->params		= array(':complaint_id'=>$id); 
							$criteria->order		= 'id ASC'; 
							$model					= ComplaintFeedback::model()->findAll($criteria);
							if($model != NULL){
								foreach($model as $key => $value){
									$return_arr[$key]['id']				= $value->id;
									$return_arr[$key]['name']			= PushNotifications::model()->getUserName($value->uid);
									$return_arr[$key]['comment']		= html_entity_decode(ucfirst($value->feedback)); 
									$return_arr[$key]['date']			= date($settings->displaydate, strtotime($value->date)).' '.date($settings->timeformat,strtotime($value->date));
									if($uid == $value->uid){
										$return_arr[$key]['is_right']	= '1';
									}
									else{
										$return_arr[$key]['is_right']	= '0';
									}									
								}
							}
							echo json_encode($return_arr, JSON_UNESCAPED_SLASHES);
							exit;
						}
						else{
							$response["error"] 		= true;
							$response["error_msg"] 	= "Invalid Request";
							echo json_encode($response);
							exit;
						}						
					}
					else{
						$response["error"] 		= true;
						$response["error_msg"] 	= "Invalid Request";
						echo json_encode($response);
						exit;
					}				
				}
				else if($tag == 'add_chat'){ //Chating in complaint						
					$uid		= $_POST['uid'];
					$role		= Rights::getAssignedRoles($uid);
					$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
					$timezone 	= Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
					date_default_timezone_set($timezone->timezone);				
					
					$model				= new ComplaintFeedback;
					$model->attributes	= $_POST['ComplaintFeedback']; 
					$model->uid			= $uid;
					$model->date 		= date('Y-m-d H:i:s');
					if($model->validate()){
						if($model->save()){
							$complaint	= Complaints::model()->findByAttributes(array('id'=>$model->complaint_id));	
							if(key($role) != 'student' and key($role) != 'parent' and key($role) != 'teacher'){ 
								//Mobile Notification
								if(Configurations::model()->isAndroidEnabled()){									
									$college	= Configurations::model()->findByPk(1);
									$profile	= Profile::model()->findByAttributes(array('user_id'=>$uid));
						
									$criteria 				= new CDbCriteria;	
									$criteria->condition	= 'uid=:uid';
									$criteria->params		= array(':uid'=>$complaint->uid);
									$user_device 			= UserDevice::model()->findAll($criteria);
									//Get Messages
									$push_notifications		= PushNotifications::model()->getNotificationDatas(11);
									foreach($user_device as $value){								
										//Get key value of the notification data array					
										$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
										
										$message	= $push_notifications[$key]['message'];
										$message	= str_replace("{School Name}", html_entity_decode(ucfirst($college->config_value)), $message);	
										$message	= str_replace("{Subject}", html_entity_decode(ucfirst($complaint->subject)), $message);		
										
										$argument_arr = array('message' => $message, 'sender_name' => ucfirst($profile->firstname).' '.ucfirst($profile->lastname), 'device_id' => array($value->device_id), 'id'=>$model->id, 'complaint_id'=>$model->complaint_id, 'type'=>'2');                  
										Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "complaints");																		
									}						
								}
							}
							else{
								//Mobile push notification
								if(key($role) == 'parent'){
									$parent 		= Guardians::model()->findByAttributes(array('uid'=>$uid));		
									$parent_name	= ucfirst($parent->first_name).' '.ucfirst($parent->last_name);							
									//Student
									$student 	= PushNotifications::model()->getStudents($parent->id);
									
									//Student Active Batch
									$student_name	= '-';
									$batch_name		= '-';
									if($student != NULL){
										$batch	= PushNotifications::model()->getStudentActiveBatch($student->id);
										if($batch != NULL){
											$batch_name	= html_entity_decode(ucfirst($batch->name));
										}
										$student_name	= $student->getStudentname();
									}
									
									//Admin Level Users
									$criteria				= new CDbCriteria();
									$criteria->join			= 'JOIN `authassignment` `t1` ON `t1`.`userid` = `t`.`uid`';					
									$criteria->condition	= '`t1`.`itemname`=:itemname';
									$criteria->params		= array(':itemname'=>'Admin');					
									$user_device 			= UserDevice::model()->findAll($criteria);
									//Get Messages
									$push_notifications		= PushNotifications::model()->getNotificationDatas(9);
									foreach($user_device as $value){								
										//Get key value of the notification data array					
										$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
										
										$message	= $push_notifications[$key]['message'];
										$message	= str_replace("{Guardian Name}", $parent_name, $message);
										$message	= str_replace("{Student Name}", $student_name, $message);
										$message	= str_replace("{Batch Name}", $batch_name, $message);	
										
										$argument_arr = array('message' => $message, 'sender_name' => $parent_name, 'device_id' => array($value->device_id), 'id'=>$model->id, 'complaint_id'=>$model->complaint_id, 'type'=>'2');                
										Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "complaints");	
									}						
								}
								else if(key($role) == 'student'){
									$student 		= Students::model()->findByAttributes(array('uid'=>$uid));																
									//Student Active Batch
									$student_name	= '-';
									$batch_name		= '-';
									if($student != NULL){
										$batch	= PushNotifications::model()->getStudentActiveBatch($student->id);
										if($batch != NULL){
											$batch_name	= html_entity_decode(ucfirst($batch->name));
										}
										$student_name	= $student->getStudentname();
									}
									
									//Admin Level Users
									$criteria				= new CDbCriteria();
									$criteria->join			= 'JOIN `authassignment` `t1` ON `t1`.`userid` = `t`.`uid`';					
									$criteria->condition	= '`t1`.`itemname`=:itemname';
									$criteria->params		= array(':itemname'=>'Admin');					
									$user_device 			= UserDevice::model()->findAll($criteria);
									//Get Messages
									$push_notifications		= PushNotifications::model()->getNotificationDatas(8);
									foreach($user_device as $value){								
										//Get key value of the notification data array					
										$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
										
										$message	= $push_notifications[$key]['message'];
										$message	= str_replace("{Student Name}", $student_name, $message);
										$message	= str_replace("{Batch Name}", $batch_name, $message);	
										
										$argument_arr = array('message' => $message, 'sender_name' => $student_name, 'device_id' => array($value->device_id), 'id'=>$model->id, 'complaint_id'=>$model->complaint_id, 'type'=>'2');                 
										Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "complaints");	
									}
								}
								else if(key($role) == 'teacher'){
									$teacher 		= Employees::model()->findByAttributes(array('uid'=>$uid));																								
									$teacher_name	= $teacher->getFullname();								
									
									//Admin Level Users
									$criteria				= new CDbCriteria();
									$criteria->join			= 'JOIN `authassignment` `t1` ON `t1`.`userid` = `t`.`uid`';					
									$criteria->condition	= '`t1`.`itemname`=:itemname';
									$criteria->params		= array(':itemname'=>'Admin');					
									$user_device 			= UserDevice::model()->findAll($criteria);	
									//Get Messages
									$push_notifications		= PushNotifications::model()->getNotificationDatas(10);
									foreach($user_device as $value){								
										//Get key value of the notification data array					
										$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
										
										$message	= $push_notifications[$key]['message'];
										$message	= str_replace("{Teacher Name}", $teacher_name, $message);		
										
										$argument_arr = array('message' => $message, 'sender_name' => $teacher_name, 'device_id' => array($value->device_id), 'id'=>$model->id, 'complaint_id'=>$model->complaint_id, 'type'=>'2');                  
										Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "complaints");	
									}
								}									
							}
							
							echo json_encode(array('status'=>'success'));
							exit;
						}
					}
					else{
						$errors	= $model->getErrors();																			
						echo json_encode(array('status'=>"error", 'errors'=>$errors));	
						exit;
					}					 
				}
				else if($tag == 'update_chat'){ //Update complaint chat
					$uid		= $_POST['uid'];	
					$role		= Rights::getAssignedRoles($uid);				
					if(isset($_POST['ComplaintFeedback']) and $_POST['ComplaintFeedback'] != NULL){
						$model	= ComplaintFeedback::model()->findByPk($_POST['ComplaintFeedback']['id']);
						if($model != NULL){
							$model->feedback	= $_POST['ComplaintFeedback']['feedback'];
							if($model->save()){								
								$complaint	= Complaints::model()->findByAttributes(array('id'=>$model->complaint_id));	
								if(key($role) != 'student' and key($role) != 'parent' and key($role) != 'teacher'){ 
									//Mobile Notification
									if(Configurations::model()->isAndroidEnabled()){									
										$college	= Configurations::model()->findByPk(1);
										$profile	= Profile::model()->findByAttributes(array('user_id'=>$uid));
							
										$criteria 				= new CDbCriteria;	
										$criteria->condition	= 'uid=:uid';
										$criteria->params		= array(':uid'=>$complaint->uid);
										$user_device 			= UserDevice::model()->findAll($criteria);
										//Get Messages
										$push_notifications		= PushNotifications::model()->getNotificationDatas(11);
										foreach($user_device as $value){								
											//Get key value of the notification data array					
											$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
											
											$message	= $push_notifications[$key]['message'];
											$message	= str_replace("{School Name}", html_entity_decode(ucfirst($college->config_value)), $message);	
											$message	= str_replace("{Subject}", html_entity_decode(ucfirst($complaint->subject)), $message);		
											
											$argument_arr = array('message' => $message, 'sender_name' => ucfirst($profile->firstname).' '.ucfirst($profile->lastname), 'device_id' => array($value->device_id), 'id'=>$model->id, 'complaint_id'=>$model->complaint_id, 'type'=>'2');                  
											Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "complaints");																		
										}						
									}
								}
								else{
									//Mobile push notification
									if(key($role) == 'parent'){
										$parent 		= Guardians::model()->findByAttributes(array('uid'=>$uid));		
										$parent_name	= ucfirst($parent->first_name).' '.ucfirst($parent->last_name);							
										//Student
										$student 	= PushNotifications::model()->getStudents($parent->id);
										
										//Student Active Batch
										$student_name	= '-';
										$batch_name		= '-';
										if($student != NULL){
											$batch	= PushNotifications::model()->getStudentActiveBatch($student->id);
											if($batch != NULL){
												$batch_name	= html_entity_decode(ucfirst($batch->name));
											}
											$student_name	= $student->getStudentname();
										}
										
										//Admin Level Users
										$criteria				= new CDbCriteria();
										$criteria->join			= 'JOIN `authassignment` `t1` ON `t1`.`userid` = `t`.`uid`';					
										$criteria->condition	= '`t1`.`itemname`=:itemname';
										$criteria->params		= array(':itemname'=>'Admin');					
										$user_device 			= UserDevice::model()->findAll($criteria);
										//Get Messages
										$push_notifications		= PushNotifications::model()->getNotificationDatas(9);
										foreach($user_device as $value){								
											//Get key value of the notification data array					
											$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
											
											$message	= $push_notifications[$key]['message'];
											$message	= str_replace("{Guardian Name}", $parent_name, $message);
											$message	= str_replace("{Student Name}", $student_name, $message);
											$message	= str_replace("{Batch Name}", $batch_name, $message);	
											
											$argument_arr = array('message' => $message, 'sender_name' => $parent_name, 'device_id' => array($value->device_id), 'id'=>$model->id, 'complaint_id'=>$model->complaint_id, 'type'=>'2');                
											Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "complaints");	
										}						
									}
									else if(key($role) == 'student'){
										$student 		= Students::model()->findByAttributes(array('uid'=>$uid));																
										//Student Active Batch
										$student_name	= '-';
										$batch_name		= '-';
										if($student != NULL){
											$batch	= PushNotifications::model()->getStudentActiveBatch($student->id);
											if($batch != NULL){
												$batch_name	= html_entity_decode(ucfirst($batch->name));
											}
											$student_name	= $student->getStudentname();
										}
										
										//Admin Level Users
										$criteria				= new CDbCriteria();
										$criteria->join			= 'JOIN `authassignment` `t1` ON `t1`.`userid` = `t`.`uid`';					
										$criteria->condition	= '`t1`.`itemname`=:itemname';
										$criteria->params		= array(':itemname'=>'Admin');					
										$user_device 			= UserDevice::model()->findAll($criteria);
										//Get Messages
										$push_notifications		= PushNotifications::model()->getNotificationDatas(8);
										foreach($user_device as $value){								
											//Get key value of the notification data array					
											$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
											
											$message	= $push_notifications[$key]['message'];
											$message	= str_replace("{Student Name}", $student_name, $message);
											$message	= str_replace("{Batch Name}", $batch_name, $message);	
											
											$argument_arr = array('message' => $message, 'sender_name' => $student_name, 'device_id' => array($value->device_id), 'id'=>$model->id, 'complaint_id'=>$model->complaint_id, 'type'=>'2');                 
											Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "complaints");	
										}
									}
									else if(key($role) == 'teacher'){
										$teacher 		= Employees::model()->findByAttributes(array('uid'=>$uid));																								
										$teacher_name	= $teacher->getFullname();								
										
										//Admin Level Users
										$criteria				= new CDbCriteria();
										$criteria->join			= 'JOIN `authassignment` `t1` ON `t1`.`userid` = `t`.`uid`';					
										$criteria->condition	= '`t1`.`itemname`=:itemname';
										$criteria->params		= array(':itemname'=>'Admin');					
										$user_device 			= UserDevice::model()->findAll($criteria);	
										//Get Messages
										$push_notifications		= PushNotifications::model()->getNotificationDatas(10);
										foreach($user_device as $value){								
											//Get key value of the notification data array					
											$key		= PushNotifications::model()->getKeyData($value->uid, $push_notifications);
											
											$message	= $push_notifications[$key]['message'];
											$message	= str_replace("{Teacher Name}", $teacher_name, $message);		
											
											$argument_arr = array('message' => $message, 'sender_name' => $teacher_name, 'device_id' => array($value->device_id), 'id'=>$model->id, 'complaint_id'=>$model->complaint_id, 'type'=>'2');                  
											Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "complaints");	
										}
									}									
								}
								
								echo json_encode(array('status'=>'success'));
								exit;
							}
							else{
								$errors	= $model->getErrors();																			
								echo json_encode(array('status'=>"error", 'errors'=>$errors));	
								exit;
							}
						}
						else{
							$response["error"] 		= true;
							$response["error_msg"] 	= "Invalid Request";
							echo json_encode($response);
							exit;
						}
					}
					else{
						$id			= $_POST['id'];
						$return_arr	= array();
						$model		= ComplaintFeedback::model()->findByPk($id);
						if($model != NULL){
							$return_arr['ComplaintFeedback']['id']			= $model->id;
							$return_arr['ComplaintFeedback']['feedback']	= html_entity_decode(ucfirst($model->feedback));
							
							echo json_encode($return_arr, JSON_UNESCAPED_SLASHES);
							exit;
						}
						else{
							$response["error"] 		= true;
							$response["error_msg"] 	= "Invalid Request";
							echo json_encode($response);
							exit;
						}
					}				
				}
				else if($tag == 'delete_chat'){ //Delete complaint chat
					$uid	= $_POST['uid'];
					$id		= $_POST['id'];
					$model	= ComplaintFeedback::model()->findByPk($id);
					if($model){
						if($model->delete()){
							echo json_encode(array('status'=>'success'));
							exit;
						}
					}
					else{
						$response["error"] 		= true;
						$response["error_msg"] 	= "Invalid Request";
						echo json_encode($response);
						exit;
					}
				}
				else if($tag == 'notify'){ //This is to send sms to selected users	
					$response	= array();			
					$uid		= $_POST['uid'];
					$level		= $_POST['level'];
					if($level == 1){ //For user types & types
						//User Types						
						$user_types[0]['key']	= '1';
						$user_types[0]['value']	= 'All';
						
						$user_types[1]['key']	= '2';
						$user_types[1]['value']	= 'Students';
						
						$user_types[2]['key']	= '3';
						$user_types[2]['value']	= 'Parents';
						
						$user_types[3]['key']	= '4';
						$user_types[3]['value']	= 'Teachers';
						
						$user_types[4]['key']	= '5';
						$user_types[4]['value']	= 'Non Teaching Staffs';
						
						//Types
						$types[0]['key']	= '1';
						$types[0]['value']	= 'None';
						
						$types[1]['key']	= '2';
						$types[1]['value']	= 'Holiday';
						
						$types[2]['key']	= '3';
						$types[2]['value']	= 'Notice';
						
						$types[3]['key']	= '4';
						$types[3]['value']	= 'Alert';
							
						echo json_encode(array('user_types'=>$user_types, 'types'=>$types), JSON_UNESCAPED_SLASHES);
						exit;	
					}
					else if($level == 2){ //For getting filter list based on the user type
						$user_type	= $_POST['user_type'];
						$filters	= array();
						if($user_type == 2){ //In case of Student							
							$filters[0]['key']		= '1';
							$filters[0]['value']	= 'All';
							
							$filters[1]['key']		= '2';
							$filters[1]['value']	= 'Course';
							
							$filters[2]['key']		= '3';
							$filters[2]['value']	= 'Category';
						}
						else if($user_type == 3){ //In case of Parent
							$filters[0]['key']		= '1';
							$filters[0]['value']	= 'All';
							
							$filters[1]['key']		= '2';
							$filters[1]['value']	= 'Course';
							
							$filters[2]['key']		= '3';
							$filters[2]['value']	= 'Category';						
						}
						else if($user_type == 4){ //In case of Teacher							
							$filters[0]['key']		= '1';
							$filters[0]['value']	= 'All';
							
							$filters[1]['key']		= '2';
							$filters[1]['value']	= 'Course';
							
							$filters[2]['key']		= '3';
							$filters[2]['value']	= 'Subject';
							
							$filters[3]['key']		= '4';
							$filters[3]['value']	= 'Elective';
							
							$filters[4]['key']		= '5';
							$filters[4]['value']	= 'Category';
							
							$filters[5]['key']		= '6';
							$filters[5]['value']	= 'Department';
							
							$filters[6]['key']		= '7';
							$filters[6]['value']	= 'Position';
							
							$filters[7]['key']		= '8';
							$filters[7]['value']	= 'Grade';
						}
						else if($user_type == 5){ //In case of Non Teaching Staffs							
							$filters[0]['key']		= '1';
							$filters[0]['value']	= 'All';
							
							$filters[1]['key']		= '2';
							$filters[1]['value']	= 'Staff Type';
						}						
						echo json_encode($filters, JSON_UNESCAPED_SLASHES);
						exit;
					}
					else if($level == 3){ //For getting values based on the selected filter
						$user_type				= $_POST['user_type'];
						$filter					= $_POST['filter'];
						$current_academic_yr 	= Configurations::model()->findByAttributes(array('id'=>35));
						if($user_type == 2 or $user_type == 3){ //In case of Student or Parent type						
							if($filter == 2){ //In case of Course
								$course_arr				= array();
								$batch_arr				= array();															
								//Course List
								$criteria				= new CDbCriteria();
								$criteria->condition	= 'is_deleted=:is_deleted AND academic_yr_id=:academic_yr_id';
								$criteria->params		= array(':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value);
								$criteria->order		= 'course_name ASC';
								$courses				= Courses::model()->findAll($criteria);
								if($courses){
									$i	= 1;
									$j	= 1;
									$course_arr[0]['id']		= '0';
									$course_arr[0]['name']		= 'All';
									$batch_arr[0]['id']			= '0';
									$batch_arr[0]['name']		= 'All';
									$batch_arr[0]['course_id']	= '0';	
									foreach($courses as $course){
										$course_arr[$i]['id']	= $course->id;
										$course_arr[$i]['name']	= html_entity_decode(ucfirst($course->course_name));
										
										//Batch List
										$criteria				= new CDbCriteria();
										$criteria->condition	= 'is_deleted=:is_deleted AND is_active=:is_active AND course_id=:course_id';
										$criteria->params		= array(':is_deleted'=>0, ':is_active'=>1, ':course_id'=>$course->id);
										$criteria->order		= 'name ASC';
										$batches				= Batches::model()->findAll($criteria);
										if($batches){											
											foreach($batches as $batch){												
												$batch_arr[$j]['id']		= $batch->id;
												$batch_arr[$j]['name']		= html_entity_decode(ucfirst($batch->name));	
												$batch_arr[$j]['course_id']	= $batch->course_id;
												
												$j++;
											}
										}	
										$i++;																			
									}
								}	
								echo json_encode(array('courses'=>$course_arr, 'batches'=>$batch_arr), JSON_UNESCAPED_SLASHES);
								exit;							
							}
							else if($filter == 3){ //In case of Student Category
								$category_arr	= array();
								
								$criteria				= new CDbCriteria();
								$criteria->condition	= 'is_deleted=:is_deleted';
								$criteria->params		= array(':is_deleted'=>0); 
								$categories				= StudentCategories::model()->findAll($criteria);
								if($categories){
									$i	= 1;
									$category_arr[0]['key']		= '0';
									$category_arr[0]['value']	= 'All';
									foreach($categories as $value){
										$category_arr[$i]['key']	= $value->id;
										$category_arr[$i]['value']	= html_entity_decode(ucfirst($value->name));
										
										$i++;
									}
								}
								echo json_encode(array('categories'=>$category_arr), JSON_UNESCAPED_SLASHES);
								exit;
							}
						}
						else if($user_type == 4){ //In case of Teacher type
							if($filter == 2){ //In case of Course
								$course_arr				= array();
								$batch_arr				= array();															
								//Course List
								$criteria				= new CDbCriteria();
								$criteria->condition	= 'is_deleted=:is_deleted AND academic_yr_id=:academic_yr_id';
								$criteria->params		= array(':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value);
								$criteria->order		= 'course_name ASC';
								$courses				= Courses::model()->findAll($criteria);
								if($courses){
									$i	= 1;
									$j	= 1;
									$course_arr[0]['id']		= '0';
									$course_arr[0]['name']		= 'All';
									$batch_arr[0]['id']			= '0';
									$batch_arr[0]['name']		= 'All';	
									$batch_arr[0]['course_id']	= '0';
									foreach($courses as $course){
										$course_arr[$i]['id']	= $course->id;
										$course_arr[$i]['name']	= html_entity_decode(ucfirst($course->course_name));
										
										//Batch List
										$criteria				= new CDbCriteria();
										$criteria->condition	= 'is_deleted=:is_deleted AND is_active=:is_active AND course_id=:course_id';
										$criteria->params		= array(':is_deleted'=>0, ':is_active'=>1, ':course_id'=>$course->id);
										$criteria->order		= 'name ASC';
										$batches				= Batches::model()->findAll($criteria);
										if($batches){											
											foreach($batches as $batch){												
												$batch_arr[$j]['id']		= $batch->id;
												$batch_arr[$j]['name']		= html_entity_decode(ucfirst($batch->name));	
												$batch_arr[$j]['course_id']	= $batch->course_id;
												
												$j++;
											}
										}	
										$i++;																			
									}
								}	
								echo json_encode(array('courses'=>$course_arr, 'batches'=>$batch_arr), JSON_UNESCAPED_SLASHES);
								exit;							
							}
							else if($filter == 3){ //In case of Subject
								$subject_arr			= array();																
								
								$criteria				= new CDbCriteria();
								$criteria->join			= 'JOIN `batches` `t1` ON `t1`.`id` = `t`.`batch_id`';
								$criteria->condition 	= '`t1`.`is_active`=:is_active AND `t1`.`is_deleted`=:is_deleted AND `t1`.`academic_yr_id`=:academic_yr_id AND `t`.`is_deleted`=:is_deleted AND `t`.`elective_group_id`=:elective_group_id';
								$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':elective_group_id'=>0);
								$criteria->order		= '`t`.`name` ASC';
								$subjects				= Subjects::model()->findAll($criteria);
								if($subjects){
									$i	= 1;	
									$subject_arr[0]['id']			= '0';
									$subject_arr[0]['name']			= 'All';
									$subject_arr[0]['course_batch']	= '';								
									foreach($subjects as $subject){
										$batch	= Batches::model()->findByPk($subject->batch_id);
										$course	= Courses::model()->findByPk($batch->course_id);
										
										$subject_arr[$i]['id']				= $subject->id;
										$subject_arr[$i]['name']			= trim(html_entity_decode(ucfirst($subject->name)));
										$subject_arr[$i]['course_batch']	= trim(html_entity_decode(ucfirst($course->course_name))).' / '.trim(html_entity_decode(ucfirst($batch->name)));;																			
										
										$i++;
									}
								}
								echo json_encode(array('subjects'=>$subject_arr), JSON_UNESCAPED_SLASHES);
								exit;								
							}
							else if($filter == 4){ //In case of Teacher - Elective
								$elective_group_arr		= array();
								$elective_arr			= array();								
								
								$criteria				= new CDbCriteria();
								$criteria->join			= 'JOIN `batches` `t1` ON `t1`.`id` = `t`.`batch_id`';
								$criteria->condition 	= '`t1`.`is_active`=:is_active AND `t1`.`is_deleted`=:is_deleted AND `t1`.`academic_yr_id`=:academic_yr_id AND `t`.`is_deleted`=:is_deleted AND `t`.`elective_group_id`<>:elective_group_id';
								$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':elective_group_id'=>0);
								$criteria->order		= '`t`.`name` ASC';
								$elective_groups		= Subjects::model()->findAll($criteria);
								if($elective_groups){
									$i	= 1;
									$j	= 1;
									$elective_group_arr[0]['id']			= '0';
									$elective_group_arr[0]['name']			= 'All';
									$elective_group_arr[0]['course_batch']	= '';
									$elective_arr[0]['id']					= '0';
									$elective_arr[0]['name']				= 'All';
									$elective_arr[0]['refer_id']			= '';
									foreach($elective_groups as $elective_group){
										$batch	= Batches::model()->findByPk($elective_group->batch_id);
										$course	= Courses::model()->findByPk($batch->course_id);
										
										$elective_group_arr[$i]['id']			= $elective_group->id;
										$elective_group_arr[$i]['name']			= trim(html_entity_decode(ucfirst($elective_group->name)));
										$elective_group_arr[$i]['course_batch']	= trim(html_entity_decode(ucfirst($course->course_name))).' / '.trim(html_entity_decode(ucfirst($batch->name)));;		
										//Electives
										$criteria				= new CDbCriteria();
										$criteria->condition	= 'elective_group_id=:elective_group_id AND is_deleted=:is_deleted';
										$criteria->params		= array(':elective_group_id'=>$elective_group->elective_group_id, ':is_deleted'=>0);
										$criteria->order		= 'name ASC';
										$electives				= Electives::model()->findAll($criteria);
										if($electives){
											foreach($electives as $value){
												$elective_arr[$j]['id']			= $value->id;
												$elective_arr[$j]['name']		= trim(html_entity_decode(ucfirst($value->name)));
												$elective_arr[$j]['refer_id']	= $elective_group->id;
												
												$j++;
											}
										}
										$i++;
									}
								}
								echo json_encode(array('elective_groups'=>$elective_group_arr, 'electives'=>$elective_arr), JSON_UNESCAPED_SLASHES);
								exit;								
							}
							else if($filter == 5){ //In case of Teacher Category
								$teacher_category_arr	= array();
								
								$criteria				= new CDbCriteria();
								$criteria->condition	= 'status=:status';
								$criteria->params		= array(':status'=>1);
								$criteria->order		= 'name ASC';
								$teacher_categories		= EmployeeCategories::model()->findAll($criteria); 
								if($teacher_categories){
									$teacher_category_arr[0]['key']		= '0';
									$teacher_category_arr[0]['value']	= 'All';
									$i = 1;
									foreach($teacher_categories as $key => $value){
										$teacher_category_arr[$i]['key']		= $value->id;
										$teacher_category_arr[$i]['value']	= html_entity_decode(ucfirst($value->name));
										$i++;
									}
								}
								echo json_encode(array('categories'=>$teacher_category_arr), JSON_UNESCAPED_SLASHES);
								exit;
							}
							else if($filter == 6){ //In case of Department
								$department_arr	= array();
								
								$criteria				= new CDbCriteria();
								$criteria->condition	= 'status=:status';
								$criteria->params		= array(':status'=>1);
								$criteria->order		= 'name ASC';
								$teacher_departments	= EmployeeDepartments::model()->findAll($criteria); 
								if($teacher_departments){
									$department_arr[0]['id']	= '0';
									$department_arr[0]['name']	= 'All';
									$i = 1;
									foreach($teacher_departments as $key => $value){
										$department_arr[$i]['id']	= $value->id;
										$department_arr[$i]['name']	= html_entity_decode(ucfirst($value->name));
										$i++;
									}
								}
								echo json_encode(array('departments'=>$department_arr), JSON_UNESCAPED_SLASHES);
								exit;
							}
							else if($filter == 7){ //In case of Position							
								$position_arr	= array();
								
								$criteria				= new CDbCriteria();
								$criteria->condition	= 'status=:status';
								$criteria->params		= array(':status'=>1);
								$criteria->order		= 'name ASC';
								$teacher_positions		= EmployeePositions::model()->findAll($criteria);
								if($teacher_positions){
									$position_arr[0]['id']		= '0';
									$position_arr[0]['name']	= 'All';
									$i = 1;
									foreach($teacher_positions as $key => $value){
										$position_arr[$i]['id']		= $value->id;
										$position_arr[$i]['name']	= html_entity_decode(ucfirst($value->name));
										$i++;
									}
								}
								echo json_encode(array('positions'=>$position_arr), JSON_UNESCAPED_SLASHES);
								exit;
							}
							else if($filter == 8){ //In case of Grade
								$grade_arr	= array();
								
								$criteria				= new CDbCriteria();
								$criteria->condition	= 'status=:status';
								$criteria->params		= array(':status'=>1);
								$criteria->order		= 'name ASC';
								$teacher_grades			= EmployeeGrades::model()->findAll($criteria);
								if($teacher_grades){
									$grade_arr[0]['id']		= '0';
									$grade_arr[0]['name']	= 'All';
									$i = 1;
									foreach($teacher_grades as $key => $value){
										$grade_arr[$i]['id']	= $value->id;
										$grade_arr[$i]['name']	= html_entity_decode(ucfirst($value->name));
										$i++;
									}
								}
								echo json_encode(array('grades'=>$grade_arr), JSON_UNESCAPED_SLASHES);
								exit;
							}
						}
						else if($user_type == 5){ //In case of Non Teaching Staffs
							if($filter == 2){ //In case of Staff Type
								$staff_type_arr	= array();
								
								$criteria 				= new CDbCriteria;
								$criteria->condition	= "id !=:x";
								$criteria->params 		= array(':x'=>1);		
								$user_roles				= UserRoles::model()->findAll($criteria);
								if($user_roles){
									foreach($user_roles as $key => $value){
										$staff_type_arr[$key]['id']		= $value->id;
										$staff_type_arr[$key]['name']	= html_entity_decode(ucfirst($value->name));
									}
								}
								echo json_encode(array('staff_types'=>$staff_type_arr), JSON_UNESCAPED_SLASHES);
								exit;
							}
						}
						else{
							$response["error"] = true;
							$response["error_msg"] = "Invalid Request";
							echo json_encode($response);
							exit;	
						}
					}
					else if($level == 4){ //Saving the values
						$user_type				= $_POST['user_type'];
						$current_academic_yr	= Configurations::model()->findByAttributes(array('id'=>35));
						$academic_yr			= $current_academic_yr->config_value;
						$settings				= UserSettings::model()->findByAttributes(array('user_id'=>1));
						$timezone 				= Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
       					date_default_timezone_set($timezone->timezone);
						
						if(isset($_POST['id']) and $_POST['id'] != NULL){ //In case of resent
							$notify			= Notify::model()->findByPk($_POST['id']);
							$academic_yr	= $notify->academic_yr; 
						}						
						
						$model							= new Notify;
						$model->user_type				= $_POST['user_type'];  
						$model->filter					= $_POST['filter'];
						$model->course_id				= $_POST['course_id'];
						$model->batch_id				= $_POST['batch_id'];
						$model->subject_id				= $_POST['subject_id'];
						$model->elective_group_id		= $_POST['elective_group_id'];
						$model->elective_id				= $_POST['elective_id'];
						$model->category_id				= $_POST['category_id'];
						$model->department_id			= $_POST['department_id'];
						$model->position_id				= $_POST['position_id'];
						$model->grade_id				= $_POST['grade_id']; 
						$model->staff_type				= $_POST['staff_type'];
						$model->message					= $_POST['message'];
						$model->type					= $_POST['type'];
						$model->is_mail					= $_POST['is_mail'];
						$model->academic_yr				= $academic_yr;
						$model->created_at				= date('Y-m-d H:i:s');
						$model->created_by				= $uid;
						$model->total_receiver_count	= 0;
						if($model->save()){	
							$model->saveAttributes(array('total_receiver_count'=>$this->getReceiverCount($model->id)));												
							$backJob = Yii::app()->background->start(
								array(
									'androidApi/notifyBackJob', 
									'id'=>$model->id									
								)
							);
							
							echo json_encode(array('status'=>'success'));						
							exit;
						}
						else{
							$response["error"] = true;
							$response["error_msg"] = "Something went wrong!";
							echo json_encode($response);
							exit;	
						}
					}					
					else if($level == 5){ //Send details listing
						$send_list_arr	= array();
						$settings		= UserSettings::model()->findByAttributes(array('user_id'=>1));
						$sender_name	= PushNotifications::model()->getUserName($uid);
						//For Pagination
						$page_id	= 1; 
						$limit		= 10;                                   
						if(($_POST['page_id']) && ($_POST['page_id'])!=NULL && ($_POST['page_id'])>1){
							$page_id	= $_POST['page_id'];
						}                                        
						if($page_id == 1){
							$start_limit	= 0;						
						}
						else{
							$start_limit	= (($page_id - 1) * 10);						
						}
					
						$criteria			= new CDbCriteria();
						$criteria->order	= 'id DESC';
						$criteria->offset	= $start_limit;
						$criteria->limit	= $limit;
						
						$model				= Notify::model()->findAll($criteria); 
						
						if($model){
							$user_type	= array(1=>'All', 2=>'Students', 3=>'Parents', 4=>'Teachers', 5=>'Non Teaching Staffs');
							$filter		= array(2=>array(1=>'All', 2=>'Course', 3=>'Category'), 3=>array(1=>'All', 2=>'Course', 3=>'Category'), 4=>array(1=>'All', 2=>'Course', 3=>'Subject', 4=>'Elective', 5=>'Category', 6=>'Department', 7=>'Position', 8=>'Grade'), 5=>array(1=>'All', 2=>'Staff Type'));
							$types		= array(1=>'None', 2=>'Holiday', 3=>'Notice', 4=>'Alert');
							foreach($model as $key => $value){		
								$is_complete	= '1';						
								$academic_yr	= AcademicYears::model()->findByPk($value->academic_yr);
								
								$send_list_arr[$key]['id']				= $value->id;
								$send_list_arr[$key]['user_type']		= $user_type[$value->user_type];
								$send_list_arr[$key]['user_type_id']	= $value->user_type;
								if($value->filter != NULL){
									$send_list_arr[$key]['filter']		= $filter[$value->user_type][$value->filter];
									$send_list_arr[$key]['filter_id']	= $value->filter;
								}
								if($value->course_id != NULL){
									if($value->course_id == 0){
										$send_list_arr[$key]['course']	= 'All';
									}
									else{
										$course	= Courses::model()->findByPk($value->course_id);
										$send_list_arr[$key]['course']	= ($course != NULL)?html_entity_decode(ucfirst($course->course_name)):'-';
									}
									$send_list_arr[$key]['course_id']	= $value->course_id;
								}
								if($value->batch_id != NULL){
									if($value->batch_id == 0){
										$send_list_arr[$key]['batch']	= 'All';
									}
									else{
										$batch	= Batches::model()->findByPk($value->batch_id);
										$send_list_arr[$key]['batch']	= ($batch != NULL)?html_entity_decode(ucfirst($batch->name)):'-';
									}
									$send_list_arr[$key]['batch_id']	= $value->batch_id;
								}
								if($value->subject_id != NULL){
									if($value->subject_id == 0){
										$send_list_arr[$key]['subject']	= 'All';
									}
									else{
										$subject	= Subjects::model()->findByPk($value->subject_id);
										$send_list_arr[$key]['subject']	= ($subject != NULL)?html_entity_decode(ucfirst($subject->name)):'-';
									}
									$send_list_arr[$key]['subject_id']	= $value->subject_id;
								}
								if($value->elective_group_id != NULL){
									if($value->elective_group_id == 0){
										$send_list_arr[$key]['elective_group']	= 'All';
									}
									else{
										$elective_group	= Subjects::model()->findByPk($value->elective_group_id);
										$send_list_arr[$key]['elective_group']	= ($elective_group != NULL)?html_entity_decode(ucfirst($elective_group->name)):'-';
									}
									$send_list_arr[$key]['elective_group_id']	= $value->elective_group_id;
								}
								if($value->elective_id != NULL){
									if($value->elective_id == 0){
										$send_list_arr[$key]['elective']	= 'All';
									}
									else{
										$elective	= Electives::model()->findByPk($value->elective_id);
										$send_list_arr[$key]['elective']	= ($elective != NULL)?html_entity_decode(ucfirst($elective->name)):'-';
									}
									$send_list_arr[$key]['elective_id']	= $value->elective_id;
								}
								if($value->category_id != NULL){
									if($value->category_id == 0){
										$send_list_arr[$key]['category']	= 'All';
									}
									else{
										if($value->user_type == 2 or $value->user_type == 3){ //For Student & Parent
											$category	= StudentCategories::model()->findByPk($value->category_id);
											$send_list_arr[$key]['category']	= ($category != NULL)?html_entity_decode(ucfirst($category->name)):'-';
										}
										else if($value->user_type == 4){ //For Teacher
											$category	= EmployeeCategories::model()->findByPk($value->category_id);
											$send_list_arr[$key]['category']	= ($category != NULL)?html_entity_decode(ucfirst($category->name)):'-';
										}
									}
									$send_list_arr[$key]['category_id']	= $value->category_id;
								}
								if($value->department_id != NULL){
									if($value->department_id == 0){
										$send_list_arr[$key]['department']	= 'All';
									}
									else{
										$department	= EmployeeDepartments::model()->findByPk($value->department_id);
										$send_list_arr[$key]['department']	= ($department != NULL)?html_entity_decode(ucfirst($department->name)):'-';
									}
									$send_list_arr[$key]['department_id']	= $value->department_id;
								}
								if($value->position_id != NULL){
									if($value->position_id == 0){
										$send_list_arr[$key]['position']	= 'All';
									}
									else{
										$position	= EmployeePositions::model()->findByPk($value->position_id);
										$send_list_arr[$key]['position']	= ($position != NULL)?html_entity_decode(ucfirst($position->name)):'-';
									}
									$send_list_arr[$key]['position_id']	= $value->position_id;
								}
								if($value->grade_id != NULL){
									if($value->grade_id == 0){
										$send_list_arr[$key]['grade']	= 'All';
									}
									else{
										$grade	= EmployeeGrades::model()->findByPk($value->grade_id);
										$send_list_arr[$key]['grade']	= ($grade != NULL)?html_entity_decode(ucfirst($grade->name)):'-';
									}
									$send_list_arr[$key]['grade_id']	= $value->grade_id;
								}
								if($value->staff_type != NULL){
									if($value->staff_type == 0){
										$send_list_arr[$key]['staff_type']	= 'All';										
									}
									else{
										$staff_type	= UserRoles::model()->findByPk($value->staff_type);
										$send_list_arr[$key]['staff_type']	= ($staff_type != NULL)?html_entity_decode(ucfirst($staff_type->name)):'-';
									}
									$send_list_arr[$key]['staff_type_id']	= $value->staff_type;
								}								
								$send_list_arr[$key]['message']		= html_entity_decode(ucfirst($value->message));
								$send_list_arr[$key]['type']		= $types[$value->type];
								$send_list_arr[$key]['type_id']		= $value->type;
								$send_list_arr[$key]['created_at']	= date($settings->displaydate, strtotime($value->created_at)).' '.date($settings->timeformat, strtotime($value->created_at));	
								$send_list_arr[$key]['created_by']	= ($sender_name != NULL)?$sender_name:'-';	
								$send_list_arr[$key]['academic_yr']	= ($academic_yr != NULL)?html_entity_decode(ucfirst($academic_yr->name)):'-';	
								$send_list_arr[$key]['is_mail']	= $value->is_mail;
								//Total receiver count								
								$send_list_arr[$key]['total_count']	= $value->total_receiver_count;
								
								//Get Total send count
								$criteria				= new CDbCriteria();
								$criteria->condition	= 'notify_id=:notify_id AND status=:status';
								$criteria->params		= array(':notify_id'=>$value->id, ':status'=>1); 
								$send_count				= NotifyReceivers::model()->count($criteria);
								$send_list_arr[$key]['send_count']	= $send_count;
								
								//Check whether any pbm occure in sending
								$criteria				= new CDbCriteria();
								$criteria->condition	= 'notify_id=:notify_id AND status=:status';
								$criteria->params		= array(':notify_id'=>$value->id, ':status'=>2); 
								$check_error			= NotifyReceivers::model()->count($criteria);
								
								//Pending count
								$criteria				= new CDbCriteria();
								$criteria->condition	= 'notify_id=:notify_id AND status=:status';
								$criteria->params		= array(':notify_id'=>$value->id, ':status'=>0); 
								$pending				= NotifyReceivers::model()->count($criteria);
								
								if($value->total_receiver_count > 0 and $value->total_receiver_count != $send_count){
									$is_complete	= '0';
								}	
								
								if($value->total_receiver_count > 0 and $pending == 0 and $check_error > 0){
									$is_complete	= 2;
								}
								
								$send_list_arr[$key]['is_complete']	= $is_complete;								
							}
						}
						echo json_encode(array('send_list'=>$send_list_arr), JSON_UNESCAPED_SLASHES);
						exit;
					}
					else if($level == 6){ //Get the send count & total count of the selected data
						$ids		= json_decode($_POST['ids']);
						$count_arr	= array(); 
						foreach($ids as $key => $id){
							$notify			= Notify::model()->findByPk($id);	
							$is_complete	= '1';						
							//Total receiver count							 
							$total_count			= $notify->total_receiver_count;
							
							//Get Total send count
							$criteria				= new CDbCriteria();
							$criteria->condition	= 'notify_id=:notify_id AND status=:status';
							$criteria->params		= array(':notify_id'=>$id, ':status'=>1); 
							$send_count				= NotifyReceivers::model()->count($criteria);
							
							//Check whether any pbm occure in sending
							$criteria				= new CDbCriteria();
							$criteria->condition	= 'notify_id=:notify_id AND status=:status';
							$criteria->params		= array(':notify_id'=>$id, ':status'=>2); 
							$check_error			= NotifyReceivers::model()->count($criteria);
							
							//Pending count
							$criteria				= new CDbCriteria();
							$criteria->condition	= 'notify_id=:notify_id AND status=:status';
							$criteria->params		= array(':notify_id'=>$id, ':status'=>0); 
							$pending				= NotifyReceivers::model()->count($criteria);
							
							$count_arr[$key]['id']			= $id;
							$count_arr[$key]['total_count']	= $total_count;
							$count_arr[$key]['send_count']	= $send_count;
							if($total_count > 0 and $total_count != $send_count){
								$is_complete	= '0';
							}
							
							if($total_count > 0 and $pending == 0 and $check_error > 0){
								$is_complete	= 2;
							}
							
							$count_arr[$key]['is_complete']	= $is_complete; //0 => Pending, 1 => Completed, 2 => Some error occured
						}
						echo json_encode(array('count_details'=>$count_arr), JSON_UNESCAPED_SLASHES);
						exit;
					}
					else{
						$response["error"] = true;
						$response["error_msg"] = "Invalid Request";
						echo json_encode($response);
						exit;
					}
				}
				else if($tag == 'getAcademicYears'){
					$uid				= $_POST['uid'];
					$academic_yr_arr	= array();
					
					$criteria				= new CDbCriteria();
					$criteria->condition	= 'is_deleted=:is_deleted';
					$criteria->params		= array(':is_deleted'=>0);
					$criteria->order		= 'status DESC, name ASC';
					$academicYears			= AcademicYears::model()->findAll($criteria);
					if($academicYears != NULL){
						foreach($academicYears as $key => $value){
							$academic_yr_arr[$key]['key']	= $value->id;
							$academic_yr_arr[$key]['name']	= html_entity_decode(ucfirst($value->name));
						}
					}
					echo json_encode(array('academicYears'=>$academic_yr_arr), JSON_UNESCAPED_SLASHES);
					exit;
				}
				//End of tags
                                                                                                
			}else{
				$response["error"] = true;
				$response["error_msg"] = "Invalid Request";
				echo json_encode($response);
				exit;
			}
		}else{
			$response["error"] = true;
			$response["error_msg"] = "Invalid Request";
			echo json_encode($response);
			exit;
		}                
	}	
	//Related to Notify Feature - Return the total number of receivers based on the selected filter
	public function getReceiverCount($id){		
		$model								= Notify::model()->findByPk($id);
		$current_academic_yr->config_value	= $model->academic_yr;
		$total_count						= 0;
		if($model != NULL){
			if($model->user_type == 1){ //In case of All users
				//Save Student users																										
				$criteria				= new CDbCriteria();
				$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id`';
				$criteria->condition	= '`t`.`is_active`=:is_active AND `t`.`is_deleted`=:is_deleted AND `t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status';
				$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0);
				$criteria->group		= '`t1`.`student_id`';
				$students				= Students::model()->findAll($criteria);								
				if($students){
					$student_ids	= array();
					foreach($students as $student){						
						$student_ids[]	= $student->id;
					}
					
					//Save Parent user								
					$criteria				= new CDbCriteria();
					$criteria->select		= '`t`.`id`';
					$criteria->join			= 'JOIN `guardians` `t1` ON `t1`.`id` = `t`.`guardian_id`';
					$criteria->condition	= '`t1`.`is_delete`=:is_delete';
					$criteria->params		= array(':is_delete'=>0);
					$criteria->group		= '`t`.`guardian_id`';
					$criteria->addInCondition('`t`.`student_id`', $student_ids);
					$parents				= GuardianList::model()->count($criteria);	
					
					$total_count	+= count($students) + $parents;				
				}
				//Save Teacher user
				$criteria				= new CDbCriteria();								
				$criteria->condition	= 'is_deleted=:is_deleted';
				$criteria->params		= array(':is_deleted'=>0);
				$employees				= Employees::model()->count($criteria);
				
				//Save Non Teaching Staffs
				$criteria				= new CDbCriteria();								
				$criteria->condition	= 'is_deleted=:is_deleted AND user_type=:user_type';
				$criteria->params		= array(':is_deleted'=>0, ':user_type'=>1);
				$staffs					= Staff::model()->count($criteria);		
				
				$total_count	+= $employees + $staffs;																	
			}
			else if($model->user_type == 2){ //In case of Student							
				$filter	= $model->filter;
				if($filter == 1){ //In case of All
					//All Students							
					$criteria				= new CDbCriteria();
					$criteria->select		= '`t`.`id`';
					$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id`';
					$criteria->condition	= '`t`.`is_active`=:is_active AND `t`.`is_deleted`=:is_deleted AND `t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status';
					$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0);
					$criteria->group		= '`t1`.`student_id`';
					$students				= Students::model()->count($criteria);																	
				}
				else if($filter == 2){ //In case of Course					
					if($model->course_id == 0){ //In case of All Courses
						//Student List
						$criteria				= new CDbCriteria();
						$criteria->select		= '`t`.`id`';
						$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id`';
						$criteria->condition	= '`t`.`is_active`=:is_active AND `t`.`is_deleted`=:is_deleted AND `t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status';
						$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0);
						$criteria->group		= '`t1`.`student_id`';
						$students				= Students::model()->count($criteria);
					}
					else if($model->course_id != 0 and $model->batch_id == 0){ //In case of All Batched under a selected Course																	
						//Student List
						$criteria				= new CDbCriteria();
						$criteria->select		= '`t`.`id`';
						$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id`';
						$criteria->condition	= '`t`.`is_active`=:is_active AND `t`.`is_deleted`=:is_deleted AND `t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status AND `t2`.`course_id`=:course_id';
						$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0, ':course_id'=>$model->course_id);
						$criteria->group		= '`t1`.`student_id`';
						$students				= Students::model()->count($criteria);															
					}
					else if($model->course_id != 0 and $model->batch_id != 0){ //In case of a particular batch selection
						//Student List
						$criteria				= new CDbCriteria();
						$criteria->select		= '`t`.`id`';
						$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id`';
						$criteria->condition	= '`t`.`is_active`=:is_active AND `t`.`is_deleted`=:is_deleted AND `t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status AND `t1`.`batch_id`=:batch_id';
						$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0, ':batch_id'=>$model->batch_id);
						$criteria->group		= '`t1`.`student_id`';
						$students				= Students::model()->count($criteria);																
					}
				}
				else if($filter == 3){ //In case of Category								
					//Student List
					$criteria				= new CDbCriteria();
					$criteria->select		= '`t`.`id`';
					$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id`';									
					if($model->category_id == 0){ //In case of All Category
						$criteria->condition	= '`t`.`is_active`=:is_active AND `t`.`is_deleted`=:is_deleted AND `t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status';
						$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0);
					}
					else{ //In case of a selected category
						$criteria->condition	= '`t`.`is_active`=:is_active AND `t`.`is_deleted`=:is_deleted AND `t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status AND `t`.`student_category_id`=:student_category_id';
						$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0, ':student_category_id'=>$model->category_id);
					}
					$criteria->group		= '`t1`.`student_id`';
					$students				= Students::model()->count($criteria);																							
				}
				$total_count	+= $students;				
			}
			else if($model->user_type == 3){ //In case of Parent							
				$filter	= $model->filter;
				if($filter == 1){ //In case of All Parents
					$criteria				= new CDbCriteria();
					$criteria->select		= '`t`.`id`';
					$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`student_id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id` JOIN `guardians` `t3` ON `t3`.`id` = `t`.`guardian_id`';
					$criteria->condition	= '`t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status AND `t3`.`is_delete`=:is_deleted';
					$criteria->params		= array(':is_deleted'=>0, ':is_active'=>1,':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0);
					$criteria->group		= '`t`.`guardian_id`';
					$guardians				= GuardianList::model()->count($criteria);									
				}
				else if($filter == 2){ //In case of Course Filter
					if($model->course_id == 0){ //In case of All Courses
						$criteria				= new CDbCriteria();
						$criteria->select		= '`t`.`id`';
						$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`student_id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id` JOIN `guardians` `t3` ON `t3`.`id` = `t`.`guardian_id`';
						$criteria->condition	= '`t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status AND `t3`.`is_delete`=:is_deleted';
						$criteria->params		= array(':is_deleted'=>0, ':is_active'=>1,':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0);
						$criteria->group		= '`t`.`guardian_id`';
						$guardians				= GuardianList::model()->count($criteria);	
					}
					else if($model->course_id != 0 and $model->batch_id == 0){ //In case of All Batched under a selected Course																	
						$criteria				= new CDbCriteria();
						$criteria->select		= '`t`.`id`';
						$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`student_id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id` JOIN `guardians` `t3` ON `t3`.`id` = `t`.`guardian_id`';
						$criteria->condition	= '`t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status AND `t3`.`is_delete`=:is_deleted AND `t2`.`course_id`=:course_id';
						$criteria->params		= array(':is_deleted'=>0, ':is_active'=>1,':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0, ':course_id'=>$model->course_id);
						$criteria->group		= '`t`.`guardian_id`';
						$guardians				= GuardianList::model()->count($criteria);														
					}
					else if($model->course_id != 0 and $model->batch_id != 0){ //In case of a particular batch selection
						$criteria				= new CDbCriteria();
						$criteria->select		= '`t`.`id`';
						$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`student_id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id` JOIN `guardians` `t3` ON `t3`.`id` = `t`.`guardian_id`';
						$criteria->condition	= '`t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status AND `t3`.`is_delete`=:is_deleted AND `t1`.`batch_id`=:batch_id';
						$criteria->params		= array(':is_deleted'=>0, ':is_active'=>1,':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0, ':batch_id'=>$model->batch_id);
						$criteria->group		= '`t`.`guardian_id`';
						$guardians				= GuardianList::model()->count($criteria);											
					}
				}
				else if($filter == 3){ //In case of Category Filter 
					$criteria				= new CDbCriteria();
					$criteria->select		= '`t`.`id`';	
					if($model->category_id == '0'){ //In case of All Category																						
						$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`student_id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id` JOIN `guardians` `t3` ON `t3`.`id` = `t`.`guardian_id`';
						$criteria->condition	= '`t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status AND `t3`.`is_delete`=:is_deleted';
						$criteria->params		= array(':is_deleted'=>0, ':is_active'=>1,':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0);
					}
					else{ //For a seleceted Category									
						$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`student_id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id` JOIN `guardians` `t3` ON `t3`.`id` = `t`.`guardian_id` JOIN `students` `t4` ON `t4`.`id` = `t`.`student_id`';
						$criteria->condition	= '`t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status AND `t3`.`is_delete`=:is_deleted AND `t4`.`student_category_id`=:student_category_id';
						$criteria->params		= array(':is_deleted'=>0, ':is_active'=>1,':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0, ':student_category_id'=>$model->category_id);
					}
					$criteria->group		= '`t`.`guardian_id`';
					$guardians				= GuardianList::model()->count($criteria);									
				}
				
				$total_count	+= $guardians;
			}
			else if($model->user_type == 4){ //In case of Teacher							
				$filter	= $model->filter;
				if($filter == 1){ //To All Teachers									
					$criteria				= new CDbCriteria();								
					$criteria->condition	= 'is_deleted=:is_deleted';
					$criteria->params		= array(':is_deleted'=>0);
					$employees				= Employees::model()->count($criteria);
				}
				else if($filter == 2){ //In case of Course	
					if($model->course_id == 0){
						$employees	= Yii::app()->db->createCommand('select e.* from batches b join subjects s on s.batch_id = b.id left join employee_elective_subjects es on s.id = es.subject_id left join employees_subjects es1 on s.id = es1.subject_id join employees e on e.id = IFnull(es.employee_id, es1.employee_id) where b.academic_yr_id =:year AND e.is_deleted=:is_deleted AND b.is_active=:is_active AND b.is_deleted=:is_deleted AND s.is_deleted=:is_deleted group by e.id')->bindValue(':year',$current_academic_yr->config_value)->bindValue(':is_active',1)->bindValue(':is_deleted',0)->queryAll();
					}					
					else if($model->course_id != 0 and $model->batch_id == 0){ //In case of All Batched under a selected Course																	
						$employees	= Yii::app()->db->createCommand('select e.* from batches b join subjects s on s.batch_id = b.id left join employee_elective_subjects es on s.id = es.subject_id left join employees_subjects es1 on s.id = es1.subject_id join employees e on e.id = IFnull(es.employee_id, es1.employee_id) where b.academic_yr_id =:year AND e.is_deleted=:is_deleted AND b.is_active=:is_active AND b.is_deleted=:is_deleted AND s.is_deleted=:is_deleted AND b.course_id=:course_id group by e.id')->bindValue(':year',$current_academic_yr->config_value)->bindValue(':is_active',1)->bindValue(':is_deleted',0)->bindValue(':course_id',$model->course_id)->queryAll();						
					}
					else if($model->course_id != 0 and $model->batch_id != 0){ //In case of a particular batch selection
						$employees	= Yii::app()->db->createCommand('select e.* from batches b join subjects s on s.batch_id = b.id left join employee_elective_subjects es on s.id = es.subject_id left join employees_subjects es1 on s.id = es1.subject_id join employees e on e.id = IFnull(es.employee_id, es1.employee_id) where b.academic_yr_id =:year AND e.is_deleted=:is_deleted AND b.is_active=:is_active AND b.is_deleted=:is_deleted AND s.is_deleted=:is_deleted AND b.id=:id group by e.id')->bindValue(':year',$current_academic_yr->config_value)->bindValue(':is_active',1)->bindValue(':is_deleted',0)->bindValue(':id',$model->batch_id)->queryAll();						
					}
					
					$employees	= count($employees);					                                        							
				}
				else if($filter == 3){ //In case of Subjects
					$criteria				= new CDbCriteria();
					$criteria->select		= '`t`.`id`';
					$criteria->join			= 'JOIN `employees_subjects` `t1` ON `t1`.`employee_id` = `t`.`id` JOIN `subjects` `t2` ON `t2`.`id` = `t1`.`subject_id` JOIN `batches` `t3` ON `t3`.`id` = `t2`.`batch_id`';
					if($model->subject_id != 0){ //For Selected Subject
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t2`.`is_deleted`=:is_deleted AND `t3`.`academic_yr_id`=:academic_yr_id AND `t3`.`is_active`=:is_active AND `t3`.`is_deleted`=:is_deleted AND `t2`.`id`=:subject_id';
						$criteria->params		= array(':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':is_active'=>1, ':subject_id'=>$model->subject_id);
					}
					else{ //For All Subjects
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t2`.`is_deleted`=:is_deleted AND `t3`.`academic_yr_id`=:academic_yr_id AND `t3`.`is_active`=:is_active AND `t3`.`is_deleted`=:is_deleted';
						$criteria->params		= array(':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':is_active'=>1);
					}
					$criteria->group		= '`t1`.`employee_id`';
					$employees				= Employees::model()->count($criteria);										
				}
				else if($filter == 4){ //In case of Electives
					$criteria				= new CDbCriteria();
					$criteria->select		= '`t`.`id`';
					$criteria->join			= 'JOIN `employee_elective_subjects` `t1` ON `t1`.`employee_id` = `t`.`id` JOIN `subjects` `t2` ON `t2`.`id` = `t1`.`subject_id` JOIN `batches` `t3` ON `t3`.`id` = `t2`.`batch_id`';
					if($model->elective_group_id == 0){ //For All Elective Groups										
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t2`.`is_deleted`=:is_deleted AND `t3`.`academic_yr_id`=:academic_yr_id AND `t3`.`is_active`=:is_active AND `t3`.`is_deleted`=:is_deleted';
						$criteria->params		= array(':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':is_active'=>1);
					}
					else if($model->elective_group_id != 0 and $model->elective_id == 0){ //For All electives under the selected Elective Group
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t2`.`is_deleted`=:is_deleted AND `t3`.`academic_yr_id`=:academic_yr_id AND `t3`.`is_active`=:is_active AND `t3`.`is_deleted`=:is_deleted AND `t1`.`subject_id`=:subject_id';
						$criteria->params		= array(':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':is_active'=>1, ':subject_id'=>$model->elective_group_id);
						
					}
					else if($model->elective_group_id != 0 and $model->elective_id != 0){ //For a selected elective
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t2`.`is_deleted`=:is_deleted AND `t3`.`academic_yr_id`=:academic_yr_id AND `t3`.`is_active`=:is_active AND `t3`.`is_deleted`=:is_deleted AND `t1`.`elective_id`=:elective_id';
						$criteria->params		= array(':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':is_active'=>1, ':elective_id'=>$model->elective_id);
					}
					$criteria->group	 	= '`t1`.`employee_id`';
					$employees				= Employees::model()->count($criteria);										
				}
				else if($filter == 5){ //In case of Teacher Category
					$criteria				= new CDbCriteria();					
					if($model->category_id != 0){ //For a selected Category
						$criteria->condition	= 'is_deleted=:is_deleted AND employee_category_id=:employee_category_id';
						$criteria->params		= array(':is_deleted'=>0, ':employee_category_id'=>$model->category_id);
					}
					else{ //In case of All Category									
						$criteria->join	= 'JOIN `employee_categories` `t1` ON `t1`.`id` = `t`.`employee_category_id`';
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t1`.`status`=:status';
						$criteria->params		= array(':is_deleted'=>0, ':status'=>1);
					}
					$employees				= Employees::model()->count($criteria);			
				}
				else if($filter == 6){ //In case of Teacher Department
					$criteria				= new CDbCriteria();
					if($model->department_id != 0){ //For a selected Department
						$criteria->condition	= 'is_deleted=:is_deleted AND employee_department_id=:employee_department_id';
						$criteria->params		= array(':is_deleted'=>0, ':employee_department_id'=>$model->department_id);
					}
					else{ //In case of All Departments									
						$criteria->join	= 'JOIN `employee_departments` `t1` ON `t1`.`id` = `t`.`employee_department_id`';
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t1`.`status`=:status';
						$criteria->params		= array(':is_deleted'=>0, ':status'=>1);
					}
					$employees				= Employees::model()->count($criteria);	
				}
				else if($filter == 7){ //In case of Teacher Position
					$criteria				= new CDbCriteria();
					if($model->position_id != 0){ //For a selected Position
						$criteria->condition	= 'is_deleted=:is_deleted AND employee_position_id=:employee_position_id';
						$criteria->params		= array(':is_deleted'=>0, ':employee_position_id'=>$model->position_id);
					}
					else{ //In case of All Position									
						$criteria->join	= 'JOIN `employee_positions` `t1` ON `t1`.`id` = `t`.`employee_position_id`';
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t1`.`status`=:status';
						$criteria->params		= array(':is_deleted'=>0, ':status'=>1);
					}
					$employees				= Employees::model()->count($criteria);	
				}
				else if($filter == 8){ //In case of Teacher Grade
					$criteria				= new CDbCriteria();
					if($model->grade_id != 0){ //For a selected Grade
						$criteria->condition	= 'is_deleted=:is_deleted AND employee_grade_id=:employee_grade_id';
						$criteria->params		= array(':is_deleted'=>0, ':employee_grade_id'=>$model->grade_id);
					}
					else{ //In case of All Grade									
						$criteria->join	= 'JOIN `employee_grades` `t1` ON `t1`.`id` = `t`.`employee_grade_id`';
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t1`.`status`=:status';
						$criteria->params		= array(':is_deleted'=>0, ':status'=>1);
					}
					$employees				= Employees::model()->count($criteria);	
				}
																																									
				$total_count	+= $employees;								
			}
			else if($model->user_type == 5){ //In case of Non Teaching Staff
				$filter	= $model->filter;								
				if($filter == 1){ //For getting All non teaching staff
					$criteria				= new CDbCriteria();								
					$criteria->condition	= 'is_deleted=:is_deleted AND user_type=:user_type';
					$criteria->params		= array(':is_deleted'=>0, ':user_type'=>1);
					$staffs					= Staff::model()->count($criteria);	
				}
				else if($filter == 2){ //In case of Staff Type	
					$criteria				= new CDbCriteria();
					$criteria->select		= '`t`.`id`';								
					$criteria->join			= 'JOIN `user_roles` `t1` ON `t1`.`id` = `t`.`staff_type`';
					if($model->staff_type == 0){ //In case of All																											
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t`.`user_type`=:user_type AND `t1`.`status`=:status';
						$criteria->params		= array(':is_deleted'=>0, ':user_type'=>1, ':status'=>1);
					}
					else{ //For getting staffs based on the selected staff type
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t`.`user_type`=:user_type AND `t1`.`status`=:status AND `t`.`staff_type`=:staff_type';
						$criteria->params		= array(':is_deleted'=>0, ':user_type'=>1, ':status'=>1, ':staff_type'=>$model->staff_type);
					}
					$staffs	= Staff::model()->count($criteria);
				}
																													
				$total_count	+= $staffs;
			}
		}
		return $total_count;
	}
		
	//Related to Notify Feature - Save the user details as background process
	public function actionNotifyBackJob(){		
		$id									= $_REQUEST['id'];
		$model								= Notify::model()->findByPk($id);
		$current_academic_yr->config_value	= $model->academic_yr;
		
		if($model != NULL){
			if($model->user_type == 1){ //In case of All users
				//Save Student users																										
				$criteria				= new CDbCriteria();
				$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id`';
				$criteria->condition	= '`t`.`is_active`=:is_active AND `t`.`is_deleted`=:is_deleted AND `t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status';
				$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0);
				$criteria->group		= '`t1`.`student_id`';
				$students				= Students::model()->findAll($criteria);								
				if($students){
					$student_ids	= array();
					foreach($students as $student){
						$notify_receiver				= new NotifyReceivers;
						$notify_receiver->notify_id		= $model->id;
						$notify_receiver->receiver_id	= $student->id;
						$notify_receiver->role			= 1;
						$notify_receiver->status		= 0;
						$notify_receiver->save();
						
						$student_ids[]	= $student->id;
					}
					
					//Save Parent user								
					$criteria				= new CDbCriteria();
					$criteria->join			= 'JOIN `guardians` `t1` ON `t1`.`id` = `t`.`guardian_id`';
					$criteria->condition	= '`t1`.`is_delete`=:is_delete';
					$criteria->params		= array(':is_delete'=>0);
					$criteria->group		= '`t`.`guardian_id`';
					$criteria->addInCondition('`t`.`student_id`', $student_ids);
					$parents				= GuardianList::model()->findAll($criteria);
					if($parents){
						foreach($parents as $parent){
							$notify_receiver				= new NotifyReceivers;
							$notify_receiver->notify_id		= $model->id;
							$notify_receiver->receiver_id	= $parent->guardian_id;
							$notify_receiver->role			= 2;
							$notify_receiver->status		= 0;
							$notify_receiver->save();
						}
					}
				}
				//Save Teacher user
				$criteria				= new CDbCriteria();								
				$criteria->condition	= 'is_deleted=:is_deleted';
				$criteria->params		= array(':is_deleted'=>0);
				$employees				= Employees::model()->findAll($criteria);
				if($employees){
					foreach($employees as $employee){
						$notify_receiver				= new NotifyReceivers;
						$notify_receiver->notify_id		= $model->id;
						$notify_receiver->receiver_id	= $employee->id;
						$notify_receiver->role			= 3;
						$notify_receiver->status		= 0;
						$notify_receiver->save();
					}
				}
				//Save Non Teaching Staffs
				$criteria				= new CDbCriteria();								
				$criteria->condition	= 'is_deleted=:is_deleted AND user_type=:user_type';
				$criteria->params		= array(':is_deleted'=>0, ':user_type'=>1);
				$staffs					= Staff::model()->findAll($criteria);								
				if($staffs){
					foreach($staffs as $staff){
						$notify_receiver				= new NotifyReceivers;
						$notify_receiver->notify_id		= $model->id;
						$notify_receiver->receiver_id	= $staff->id;
						$notify_receiver->role			= 4;
						$notify_receiver->status		= 0;
						$notify_receiver->save();
					}
				}								
			}
			else if($model->user_type == 2){ //In case of Student							
				$filter	= $model->filter;
				if($filter == 1){ //In case of All
					//All Students							
					$criteria				= new CDbCriteria();
					$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id`';
					$criteria->condition	= '`t`.`is_active`=:is_active AND `t`.`is_deleted`=:is_deleted AND `t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status';
					$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0);
					$criteria->group		= '`t1`.`student_id`';
					$students				= Students::model()->findAll($criteria);																	
				}
				else if($filter == 2){ //In case of Course					
					if($model->course_id == 0){ //In case of All Courses
						//Student List
						$criteria				= new CDbCriteria();
						$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id`';
						$criteria->condition	= '`t`.`is_active`=:is_active AND `t`.`is_deleted`=:is_deleted AND `t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status';
						$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0);
						$criteria->group		= '`t1`.`student_id`';
						$students				= Students::model()->findAll($criteria);
					}
					else if($model->course_id != 0 and $model->batch_id == 0){ //In case of All Batched under a selected Course																	
						//Student List
						$criteria				= new CDbCriteria();
						$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id`';
						$criteria->condition	= '`t`.`is_active`=:is_active AND `t`.`is_deleted`=:is_deleted AND `t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status AND `t2`.`course_id`=:course_id';
						$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0, ':course_id'=>$model->course_id);
						$criteria->group		= '`t1`.`student_id`';
						$students				= Students::model()->findAll($criteria);															
					}
					else if($model->course_id != 0 and $model->batch_id != 0){ //In case of a particular batch selection
						//Student List
						$criteria				= new CDbCriteria();
						$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id`';
						$criteria->condition	= '`t`.`is_active`=:is_active AND `t`.`is_deleted`=:is_deleted AND `t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status AND `t1`.`batch_id`=:batch_id';
						$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0, ':batch_id'=>$model->batch_id);
						$criteria->group		= '`t1`.`student_id`';
						$students				= Students::model()->findAll($criteria);																
					}
				}
				else if($filter == 3){ //In case of Category								
					//Student List
					$criteria				= new CDbCriteria();
					$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id`';									
					if($model->category_id == 0){ //In case of All Category
						$criteria->condition	= '`t`.`is_active`=:is_active AND `t`.`is_deleted`=:is_deleted AND `t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status';
						$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0);
					}
					else{ //In case of a selected category
						$criteria->condition	= '`t`.`is_active`=:is_active AND `t`.`is_deleted`=:is_deleted AND `t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status AND `t`.`student_category_id`=:student_category_id';
						$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0, ':student_category_id'=>$model->category_id);
					}
					$criteria->group		= '`t1`.`student_id`';
					$students				= Students::model()->findAll($criteria);																							
				}
				
				if($students){									
					foreach($students as $student){
						$notify_receiver				= new NotifyReceivers;
						$notify_receiver->notify_id		= $model->id;
						$notify_receiver->receiver_id	= $student->id;
						$notify_receiver->role			= 1;
						$notify_receiver->status		= 0;
						$notify_receiver->save();																						
					}
				}
			}
			else if($model->user_type == 3){ //In case of Parent							
				$filter	= $model->filter;
				if($filter == 1){ //In case of All Parents
					$criteria				= new CDbCriteria();
					$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`student_id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id` JOIN `guardians` `t3` ON `t3`.`id` = `t`.`guardian_id`';
					$criteria->condition	= '`t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status AND `t3`.`is_delete`=:is_deleted';
					$criteria->params		= array(':is_deleted'=>0, ':is_active'=>1,':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0);
					$criteria->group		= '`t`.`guardian_id`';
					$guardians				= GuardianList::model()->findAll($criteria);									
				}
				else if($filter == 2){ //In case of Course Filter
					if($model->course_id == 0){ //In case of All Courses
						$criteria				= new CDbCriteria();
						$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`student_id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id` JOIN `guardians` `t3` ON `t3`.`id` = `t`.`guardian_id`';
						$criteria->condition	= '`t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status AND `t3`.`is_delete`=:is_deleted';
						$criteria->params		= array(':is_deleted'=>0, ':is_active'=>1,':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0);
						$criteria->group		= '`t`.`guardian_id`';
						$guardians				= GuardianList::model()->findAll($criteria);	
					}
					else if($model->course_id != 0 and $model->batch_id == 0){ //In case of All Batched under a selected Course																	
						$criteria				= new CDbCriteria();
						$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`student_id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id` JOIN `guardians` `t3` ON `t3`.`id` = `t`.`guardian_id`';
						$criteria->condition	= '`t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status AND `t3`.`is_delete`=:is_deleted AND `t2`.`course_id`=:course_id';
						$criteria->params		= array(':is_deleted'=>0, ':is_active'=>1,':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0, ':course_id'=>$model->course_id);
						$criteria->group		= '`t`.`guardian_id`';
						$guardians				= GuardianList::model()->findAll($criteria);														
					}
					else if($model->course_id != 0 and $model->batch_id != 0){ //In case of a particular batch selection
						$criteria				= new CDbCriteria();
						$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`student_id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id` JOIN `guardians` `t3` ON `t3`.`id` = `t`.`guardian_id`';
						$criteria->condition	= '`t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status AND `t3`.`is_delete`=:is_deleted AND `t1`.`batch_id`=:batch_id';
						$criteria->params		= array(':is_deleted'=>0, ':is_active'=>1,':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0, ':batch_id'=>$model->batch_id);
						$criteria->group		= '`t`.`guardian_id`';
						$guardians				= GuardianList::model()->findAll($criteria);											
					}
				}
				else if($filter == 3){ //In case of Category Filter 
					$criteria				= new CDbCriteria();
					if($model->category_id == '0'){ //In case of All Category																	
						$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`student_id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id` JOIN `guardians` `t3` ON `t3`.`id` = `t`.`guardian_id`';
						$criteria->condition	= '`t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status AND `t3`.`is_delete`=:is_deleted';
						$criteria->params		= array(':is_deleted'=>0, ':is_active'=>1,':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0);
					}
					else{ //For a seleceted Category									
						$criteria->join			= 'JOIN `batch_students` `t1` ON `t1`.`student_id` = `t`.`student_id` JOIN `batches` `t2` ON `t2`.`id` = `t1`.`batch_id` JOIN `guardians` `t3` ON `t3`.`id` = `t`.`guardian_id` JOIN `students` `t4` ON `t4`.`id` = `t`.`student_id`';
						$criteria->condition	= '`t2`.`is_active`=:is_active AND `t2`.`is_deleted`=:is_deleted AND `t2`.`academic_yr_id`=:academic_yr_id AND `t1`.`status`=:status AND `t1`.`result_status`=:result_status AND `t3`.`is_delete`=:is_deleted AND `t4`.`student_category_id`=:student_category_id';
						$criteria->params		= array(':is_deleted'=>0, ':is_active'=>1,':academic_yr_id'=>$current_academic_yr->config_value, ':status'=>1, ':result_status'=>0, ':student_category_id'=>$model->category_id);
					}
					$criteria->group		= '`t`.`guardian_id`';
					$guardians				= GuardianList::model()->findAll($criteria);									
				}
				
				if($guardians){
					foreach($guardians as $guardian){
						$notify_receiver				= new NotifyReceivers;
						$notify_receiver->notify_id		= $model->id;
						$notify_receiver->receiver_id	= $guardian->guardian_id;
						$notify_receiver->role			= 2;
						$notify_receiver->status		= 0;
						$notify_receiver->save();											
					}
				}
			}
			else if($model->user_type == 4){ //In case of Teacher							
				$filter	= $model->filter;
				if($filter == 1){ //To All Teachers									
					$criteria				= new CDbCriteria();								
					$criteria->condition	= 'is_deleted=:is_deleted';
					$criteria->params		= array(':is_deleted'=>0);
					$employees				= Employees::model()->findAll($criteria);
				}
				else if($filter == 2){ //In case of Course	
					if($model->course_id == 0){ //In case of All Courses
						$employees	= Yii::app()->db->createCommand('select e.* from batches b join subjects s on s.batch_id = b.id left join employee_elective_subjects es on s.id = es.subject_id left join employees_subjects es1 on s.id = es1.subject_id join employees e on e.id = IFnull(es.employee_id, es1.employee_id) where b.academic_yr_id =:year AND e.is_deleted=:is_deleted AND b.is_active=:is_active AND b.is_deleted=:is_deleted AND s.is_deleted=:is_deleted group by e.id')->bindValue(':year',$current_academic_yr->config_value)->bindValue(':is_active',1)->bindValue(':is_deleted',0)->queryAll();
					}
					else if($model->course_id != 0 and $model->batch_id == 0){ //In case of All Batched under a selected Course																	
						$employees	= Yii::app()->db->createCommand('select e.* from batches b join subjects s on s.batch_id = b.id left join employee_elective_subjects es on s.id = es.subject_id left join employees_subjects es1 on s.id = es1.subject_id join employees e on e.id = IFnull(es.employee_id, es1.employee_id) where b.academic_yr_id =:year AND e.is_deleted=:is_deleted AND b.is_active=:is_active AND b.is_deleted=:is_deleted AND s.is_deleted=:is_deleted AND b.course_id=:course_id group by e.id')->bindValue(':year',$current_academic_yr->config_value)->bindValue(':is_active',1)->bindValue(':is_deleted',0)->bindValue(':course_id',$model->course_id)->queryAll();						
					}
					else if($model->course_id != 0 and $model->batch_id != 0){ //In case of a particular batch selection
						$employees	= Yii::app()->db->createCommand('select e.* from batches b join subjects s on s.batch_id = b.id left join employee_elective_subjects es on s.id = es.subject_id left join employees_subjects es1 on s.id = es1.subject_id join employees e on e.id = IFnull(es.employee_id, es1.employee_id) where b.academic_yr_id =:year AND e.is_deleted=:is_deleted AND b.is_active=:is_active AND b.is_deleted=:is_deleted AND s.is_deleted=:is_deleted AND b.id=:id group by e.id')->bindValue(':year',$current_academic_yr->config_value)->bindValue(':is_active',1)->bindValue(':is_deleted',0)->bindValue(':id',$model->batch_id)->queryAll();						
					}
				}
				else if($filter == 3){ //In case of Subjects
					$criteria				= new CDbCriteria();
					$criteria->join			= 'JOIN `employees_subjects` `t1` ON `t1`.`employee_id` = `t`.`id` JOIN `subjects` `t2` ON `t2`.`id` = `t1`.`subject_id` JOIN `batches` `t3` ON `t3`.`id` = `t2`.`batch_id`';
					if($model->subject_id != 0){ //For Selected Subject
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t2`.`is_deleted`=:is_deleted AND `t3`.`academic_yr_id`=:academic_yr_id AND `t3`.`is_active`=:is_active AND `t3`.`is_deleted`=:is_deleted AND `t2`.`id`=:subject_id';
						$criteria->params		= array(':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':is_active'=>1, ':subject_id'=>$model->subject_id);
					}
					else{ //For All Subjects
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t2`.`is_deleted`=:is_deleted AND `t3`.`academic_yr_id`=:academic_yr_id AND `t3`.`is_active`=:is_active AND `t3`.`is_deleted`=:is_deleted';
						$criteria->params		= array(':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':is_active'=>1);
					}
					$criteria->group		= '`t1`.`employee_id`';
					$employees				= Employees::model()->findAll($criteria);										
				}
				else if($filter == 4){ //In case of Electives
					$criteria				= new CDbCriteria();
					$criteria->join			= 'JOIN `employee_elective_subjects` `t1` ON `t1`.`employee_id` = `t`.`id` JOIN `subjects` `t2` ON `t2`.`id` = `t1`.`subject_id` JOIN `batches` `t3` ON `t3`.`id` = `t2`.`batch_id`';
					if($model->elective_group_id == 0){ //For All Elective Groups										
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t2`.`is_deleted`=:is_deleted AND `t3`.`academic_yr_id`=:academic_yr_id AND `t3`.`is_active`=:is_active AND `t3`.`is_deleted`=:is_deleted';
						$criteria->params		= array(':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':is_active'=>1);
					}
					else if($model->elective_group_id != 0 and $model->elective_id == 0){ //For All electives under the selected Elective Group
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t2`.`is_deleted`=:is_deleted AND `t3`.`academic_yr_id`=:academic_yr_id AND `t3`.`is_active`=:is_active AND `t3`.`is_deleted`=:is_deleted AND `t1`.`subject_id`=:subject_id';
						$criteria->params		= array(':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':is_active'=>1, ':subject_id'=>$model->elective_group_id);
						
					}
					else if($model->elective_group_id != 0 and $model->elective_id != 0){ //For a selected elective
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t2`.`is_deleted`=:is_deleted AND `t3`.`academic_yr_id`=:academic_yr_id AND `t3`.`is_active`=:is_active AND `t3`.`is_deleted`=:is_deleted AND `t1`.`elective_id`=:elective_id';
						$criteria->params		= array(':is_deleted'=>0, ':academic_yr_id'=>$current_academic_yr->config_value, ':is_active'=>1, ':elective_id'=>$model->elective_id);
					}
					$criteria->group	 	= '`t1`.`employee_id`';
					$employees				= Employees::model()->findAll($criteria);										
				}
				else if($filter == 5){ //In case of Teacher Category
					$criteria				= new CDbCriteria();
					if($model->category_id != 0){ //For a selected Category
						$criteria->condition	= 'is_deleted=:is_deleted AND employee_category_id=:employee_category_id';
						$criteria->params		= array(':is_deleted'=>0, ':employee_category_id'=>$model->category_id);
					}
					else{ //In case of All Category									
						$criteria->join	= 'JOIN `employee_categories` `t1` ON `t1`.`id` = `t`.`employee_category_id`';
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t1`.`status`=:status';
						$criteria->params		= array(':is_deleted'=>0, ':status'=>1);
					}
					$employees				= Employees::model()->findAll($criteria);			
				}
				else if($filter == 6){ //In case of Teacher Department
					$criteria				= new CDbCriteria();
					if($model->department_id != 0){ //For a selected Department
						$criteria->condition	= 'is_deleted=:is_deleted AND employee_department_id=:employee_department_id';
						$criteria->params		= array(':is_deleted'=>0, ':employee_department_id'=>$model->department_id);
					}
					else{ //In case of All Departments									
						$criteria->join	= 'JOIN `employee_departments` `t1` ON `t1`.`id` = `t`.`employee_department_id`';
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t1`.`status`=:status';
						$criteria->params		= array(':is_deleted'=>0, ':status'=>1);
					}
					$employees				= Employees::model()->findAll($criteria);	
				}
				else if($filter == 7){ //In case of Teacher Position
					$criteria				= new CDbCriteria();
					if($model->position_id != 0){ //For a selected Position
						$criteria->condition	= 'is_deleted=:is_deleted AND employee_position_id=:employee_position_id';
						$criteria->params		= array(':is_deleted'=>0, ':employee_position_id'=>$model->position_id);
					}
					else{ //In case of All Position									
						$criteria->join	= 'JOIN `employee_positions` `t1` ON `t1`.`id` = `t`.`employee_position_id`';
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t1`.`status`=:status';
						$criteria->params		= array(':is_deleted'=>0, ':status'=>1);
					}
					$employees				= Employees::model()->findAll($criteria);	
				}
				else if($filter == 8){ //In case of Teacher Grade
					$criteria				= new CDbCriteria();
					if($model->grade_id != 0){ //For a selected Grade
						$criteria->condition	= 'is_deleted=:is_deleted AND employee_grade_id=:employee_grade_id';
						$criteria->params		= array(':is_deleted'=>0, ':employee_grade_id'=>$model->grade_id);
					}
					else{ //In case of All Grade									
						$criteria->join	= 'JOIN `employee_grades` `t1` ON `t1`.`id` = `t`.`employee_grade_id`';
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t1`.`status`=:status';
						$criteria->params		= array(':is_deleted'=>0, ':status'=>1);
					}
					$employees				= Employees::model()->findAll($criteria);	
				}
																																									
				if($employees){
					foreach($employees as $employee){
						$notify_receiver				= new NotifyReceivers;
						$notify_receiver->notify_id		= $model->id;
						$notify_receiver->receiver_id	= $employee['id'];
						$notify_receiver->role			= 3;
						$notify_receiver->status		= 0;
						$notify_receiver->save();																	
					}																	
				}								
			}
			else if($model->user_type == 5){ //In case of Non Teaching Staff
				$filter	= $model->filter;								
				if($filter == 1){ //For getting All non teaching staff
					$criteria				= new CDbCriteria();								
					$criteria->condition	= 'is_deleted=:is_deleted AND user_type=:user_type';
					$criteria->params		= array(':is_deleted'=>0, ':user_type'=>1);
					$staffs					= Staff::model()->findAll($criteria);	
				}
				else if($filter == 2){ //In case of Staff Type	
					$criteria				= new CDbCriteria();								
					$criteria->join			= 'JOIN `user_roles` `t1` ON `t1`.`id` = `t`.`staff_type`';
					if($model->staff_type == 0){ //In case of All																											
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t`.`user_type`=:user_type AND `t1`.`status`=:status';
						$criteria->params		= array(':is_deleted'=>0, ':user_type'=>1, ':status'=>1);
					}
					else{ //For getting staffs based on the selected staff type
						$criteria->condition	= '`t`.`is_deleted`=:is_deleted AND `t`.`user_type`=:user_type AND `t1`.`status`=:status AND `t`.`staff_type`=:staff_type';
						$criteria->params		= array(':is_deleted'=>0, ':user_type'=>1, ':status'=>1, ':staff_type'=>$model->staff_type);
					}
					$staffs	= Staff::model()->findAll($criteria);
				}
																													
				if($staffs){
					foreach($staffs as $staff){
						$notify_receiver				= new NotifyReceivers;
						$notify_receiver->notify_id		= $model->id;
						$notify_receiver->receiver_id	= $staff->id;
						$notify_receiver->role			= 4;
						$notify_receiver->status		= 0;
						$notify_receiver->save();
					}
				}
			}
		}											
	}
	//Related to Notify Feature - Send SMS, Mail Notification to users 
	public function actionSendNotification(){
		$college	= Configurations::model()->findByPk(1);
		$from 		= trim(html_entity_decode(ucfirst($college->config_value)));
		
		$criteria				= new CDbCriteria();
		$criteria->condition	= 'status=:status';
		$criteria->params		= array(':status'=>0); 
		$criteria->limit		= 100;
		$criteria->order		= 'id ASC';
		$model					= NotifyReceivers::model()->findAll($criteria);
		if($model != NULL){
			foreach($model as $value){
				$notify		= Notify::model()->findByPk($value->notify_id);
				$phone_no	= '';
				$email		= '';
				if($value->role == 1){ //In case of Student
					$student	= Students::model()->findByPk($value->receiver_id);
					if($student){
						$phone_no	= ($student->phone1 != NULL)?trim($student->phone1):'';
						$email		= ($student->email != NULL)?trim($student->email):'';
					}
				}
				else if($value->role == 2){ //In case of Parent
					$parent	= Guardians::model()->findByPk($value->receiver_id); 
					if($parent){
						$phone_no	= ($parent->mobile_phone != NULL)?trim($parent->mobile_phone):'';
						$email		= ($parent->email != NULL)?trim($parent->email):'';
					}
				}
				else if($value->role == 3){ //In case of Teacher
					$teacher	= Employees::model()->findByPk($value->receiver_id);
					if($teacher){
						$phone_no	= ($teacher->mobile_phone != NULL)?trim($teacher->mobile_phone):'';
						$email		= ($teacher->email != NULL)?trim($teacher->email):'';
					}
				}
				else if($value->role == 4){ //In case of Non Teaching Staffs
					$staff	= Staff::model()->findByPk($value->receiver_id);
					if($staff){
						$phone_no	= ($staff->mobile_phone != NULL)?trim($staff->mobile_phone):'';
						$email		= ($staff->email != NULL)?trim($staff->email):'';
					}
				}
				
				//Send SMS
				if($phone_no != NULL){
					SmsSettings::model()->sendSms($phone_no, $from, trim(html_entity_decode(ucfirst($notify->message))));
					$value->saveAttributes(array('status'=>1));					
				}
				else{
					$value->saveAttributes(array('status'=>2));	
				}
				
				//Send Mail
				if($notify->is_mail == 1 and $email != NULL){
					UserModule::sendMail($email, 'Message from '.$from, trim(html_entity_decode(ucfirst($notify->message))));
				}
			}
		}
	}
	
	// login validation based on Yii user module.........
	public function login($email,$password){
		
		$identity=new UserIdentity($email,$password);
		$identity->authenticate();
		switch($identity->errorCode)
		{
			case UserIdentity::ERROR_NONE:
				$duration = 0;
				Yii::app()->user->login($identity,$duration);
				$result = array('status'=>true,'message'=>"", 'flag'=>'');
				break;
			case UserIdentity::ERROR_EMAIL_INVALID:
				$result = array('status'=>false,'message'=>"Email is incorrect.", 'flag'=>1);
				break;
			case UserIdentity::ERROR_USERNAME_INVALID:
				$result = array('status'=>false,'message'=>"Username is incorrect.", 'flag'=>1);
				break;
			case UserIdentity::ERROR_STATUS_NOTACTIV:
				$result = array('status'=>false,'message'=>"You account is not activated.", 'flag'=>'');
				break;
			case UserIdentity::ERROR_STATUS_BAN:
				$result = array('status'=>false,'message'=>"You account is blocked.", 'flag'=>'');
				break;
			case UserIdentity::ERROR_PASSWORD_INVALID:
				$result = array('status'=>false,'message'=>"Password is incorrect.", 'flag'=>2);
				break;
		}
		return $result;
		
	}
	private function gettimetable($post)
	{
		$user_id	= $post['uid'];
 		$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));		
		$return_arr	= array();
		$roles		= Rights::getAssignedRoles($user_id); // check for single role
		if($roles){
			foreach($roles as $role){
				if(sizeof($roles)== 1 and $role->name == 'parent'){
					$user_type	= 1;
			   	}
				else if(sizeof($roles)== 1 and $role->name == 'student'){ 
					$user_type	= 2;
				}
				else if(sizeof($roles)== 1 and $role->name == 'teacher'){ 
					$user_type	= 3;
				}	
				else{
					$user_type	= 4; //This is for admin & custom users 
				}
			}
		}
				
		if($user_type == 2 or ($user_type == 4 and $_POST['batch_id'] != NULL) or (($user_type == 1 or $user_type == 4) and $_POST['id'] != NULL)){ //For Student, Parent, Admin & Custom Users
			$batch			= '';			
			$header			= '';
			$course_name	= '';
			$batch_arr		= array();
			if($user_type == 2 or (($user_type == 1 or $user_type == 4) and $_POST['id'] != NULL)){
				if($user_type == 2){
					$student	= Students::model()->findByAttributes(array('uid'=>$user_id));
				}
				else{
					$student	= Students::model()->findByAttributes(array('id'=>$_POST['id']));
				}
				$batches	= BatchStudents::model()->StudentBatch($student->id);
				if($batches){
					foreach($batches as $key => $value){							
						$batch_arr[$key]['id']		= $value->id;
						$batch_arr[$key]['name']	= html_entity_decode(ucfirst($value->name)).' ( '.html_entity_decode(ucfirst($value->course123->course_name)).' )';
					}
					
					if(isset($_POST['batch_id']) and $_POST['batch_id'] != NULL){
						$batch_id	= $_POST['batch_id'];							
					}
					else{
						$batch_id	= $batches[0]['id'];
					}
					$batch	= Batches::model()->findByPk($batch_id);		
				}				
			}
			else{
				$batch	= Batches::model()->findByAttributes(array('id'=>$_POST['batch_id'], 'is_active'=>1, 'is_deleted'=>0));
			}
			$day					= $_POST['day'];
			$return_arr				= array();
			$weekday_arr			= array();			
			if($batch){
				$batch_name				= html_entity_decode(ucfirst($batch->name));
				$course					= Courses::model()->findByAttributes(array('id'=>$batch->course_id, 'is_deleted'=>0));
				if($course){
					$course_name		= html_entity_decode(ucfirst($course->course_name));
				}
				$header					= 'Course / '.Students::model()->getAttributeLabel('batch_id').' : '.$course_name.' / '.$batch_name;
				
				$criteria				= new CDbcriteria;			
				$criteria->condition	= 'batch_id=:batch_id AND weekday<>:weekday';
				$criteria->params		= array(':batch_id'=>$batch->id, ':weekday'=>0);
				$weekdays				= Weekdays::model()->findAll($criteria);
				if($weekdays == NULL){ //In case of batch weekdays not available, common weekday used
					$criteria				= new CDbcriteria;			
					$criteria->condition	= 'batch_id IS NULL AND weekday<>:weekday';
					$criteria->params		= array(':weekday'=>0);
					$weekdays				= Weekdays::model()->findAll($criteria);
				}						
				if($weekdays){
					foreach($weekdays as $weekday){
						$weekday_arr[]	= $weekday->weekday;
					}
				}
				if(in_array($day, $weekday_arr)){
					if(Configurations::model()->timetableFormat($batch->id) == 1){ 
						$criteria				= new CDbCriteria;
						$criteria->condition 	= "batch_id = :batch_id";
						$criteria->params		= array(':batch_id'=>$batch->id);
						$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";
						$timings 				= ClassTimings::model()->findAll($criteria);
					}
					else{
						$weekday_attributes	= array(1=>'on_sunday',2=>'on_monday',3=>'on_tuesday',4=>'on_wednesday',5=>'on_thursday',6=>'on_friday',7=>'on_saturday');
						$weekday_condition		= "`".$weekday_attributes[$day]."`=:week_day_status";
						$criteria				= new CDbCriteria;
						$criteria->condition 	= "batch_id=:x AND ".$weekday_condition;
						$criteria->params 		= array(':x'=>$batch->id, ':week_day_status'=>1);
						$criteria->order 		= "STR_TO_DATE(start_time, '%h:%i %p')";  
						$timings 				= ClassTimings::model()->findAll($criteria);
					}
					if($timings){
						$i = 0;
						foreach($timings as $timing){
							$return_arr[$i]['start_time']	= date("H:i", strtotime($timing->start_time));
							$return_arr[$i]['end_time']		= date("H:i", strtotime($timing->end_time));
							if($timing->is_break == 1){
								$return_arr[$i]['subject']	= Yii::t('app', 'Break');
								$return_arr[$i]['employee']	= '';
							}
							else{
								$time_table_entry	= TimetableEntries::model()->findByAttributes(array('batch_id'=>$timing->batch_id, 'weekday_id'=>$day, 'class_timing_id'=>$timing->id)); 
								if($time_table_entry){
									if($time_table_entry->is_elective == 0){ //In case of normal subject
										$subject	= Subjects::model()->findByAttributes(array('id'=>$time_table_entry->subject_id, 'is_deleted'=>0));
										if($subject){
											if($time_table_entry->split_subject == 0){
												$return_arr[$i]['subject']	= html_entity_decode(ucfirst($subject->name));
											}
											else{
												$split_subject	= SubjectSplit::model()->findByPk($time_table_entry->split_subject);
												if($split_subject){
													$return_arr[$i]['subject']	= html_entity_decode(ucfirst($split_subject->split_name)).'('.html_entity_decode(ucfirst($subject->name)).')';
												}
											}
										}
										else{
											$return_arr[$i]['subject']	= '';
										}
										
										$employee	= Employees::model()->findByAttributes(array('id'=>$time_table_entry->employee_id, 'is_deleted'=>0));
										if($employee){
											$return_arr[$i]['employee']	= ucfirst($employee->first_name).' '.ucfirst($employee->last_name);
										}
										else{
											$return_arr[$i]['employee']	= '';
										}
									}
									else if($time_table_entry->is_elective == 2){ //In case of electives
										$elective_entries	= TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$timing->batch_id, 'weekday_id'=>$day, 'class_timing_id'=>$timing->id, 'is_elective'=>2)); 
										if($elective_entries){
											$j 				= 0; 
											$elective_arr	= array();
											foreach($elective_entries as $elective_entry){												
												$elective		= Electives::model()->findByAttributes(array('id'=>$elective_entry->subject_id, 'is_deleted'=>0));
												$elective_group = ElectiveGroups::model()->findByAttributes(array('id'=>$elective->elective_group_id, 'is_deleted'=>0));
												if($elective){
													$elective_arr[$j]['elective_name']	= html_entity_decode(ucfirst($elective->name));													
												}
												else{
													$elective_arr[$j]['elective_name']	= '';
												}
												$employee	= Employees::model()->findByAttributes(array('id'=>$elective_entry->employee_id, 'is_deleted'=>0));
												if($employee){
													$elective_arr[$j]['employee_name']	= ucfirst($employee->first_name).' '.ucfirst($employee->last_name);
												}
												else{
													$elective_arr[$j]['employee_name']	= '';
												}												
												$j++;
											}
											if($elective_group){
												$return_arr[$i]['subject']	= html_entity_decode(ucfirst($elective_group->name));
											}
											else{
												$return_arr[$i]['subject']	= '';
											}
											$return_arr[$i]['employee']	= '';
											
											$return_arr[$i]['elective']	= $elective_arr;
										}										
									}
								}
								else{
									$return_arr[$i]['subject']	= Yii::t('app', 'Not Assigned');
									$return_arr[$i]['employee']	= '';
								}
							}
							
							$i++;
						}
					}
				}				
			}
			$post_data	= json_encode(array('timetable' =>$return_arr, 'header'=>$header, 'batches'=>$batch_arr),JSON_UNESCAPED_SLASHES);			
			echo $post_data;
			exit();			
		}		
		elseif($user_type == 3){ //User is teacher
			$employee	= Employees::model()->findByAttributes(array('uid'=>$user_id));
			$day		= $_POST['day'];
			$return_arr	= array();
			$criteria 				= new CDbCriteria;		
			$criteria->join 		= 'LEFT JOIN class_timings t1 ON t.class_timing_id = t1.id';
			$criteria->condition 	= 't.employee_id=:employee_id and t.weekday_id=:weekday_id';
			$criteria->params 		= array(':employee_id'=>$employee->id, ':weekday_id'=>$day);
			$criteria->order  		= "STR_TO_DATE(t1.start_time, '%h:%i %p')";
			$timetableEntries		= TimetableEntries::model()->findAll($criteria); 			
			if($timetableEntries){
				$i = 0;
				foreach($timetableEntries as $key => $value){
					$course_name	= '';
					$batch_name		= '';
					$subject_name	= '';
					$timing			= ClassTimings::model()->findByAttributes(array('id'=>$value->class_timing_id));
					$batch			= Batches::model()->findByAttributes(array('id'=>$value->batch_id, 'is_active'=>1, 'is_deleted'=>0));
					if($batch){
						$batch_name	= html_entity_decode(ucfirst($batch->name));
						$course		= Courses::model()->findByAttributes(array('id'=>$batch->course_id, 'is_deleted'=>0));
						if($course){
							$course_name	= html_entity_decode(ucfirst($course->course_name));
						}
					}
					
					if($value->is_elective == 0){ //In case Subject
						$subject	= Subjects::model()->findByAttributes(array('id'=>$value->subject_id));
						if($subject){
							if($value->split_subject == 0){
								$subject_name	= html_entity_decode(ucfirst($subject->name));
							}
							else{
								$split_subject	= SubjectSplit::model()->findByPk($value->split_subject);
								if($split_subject){
									$subject_name	= html_entity_decode(ucfirst($split_subject->split_name)).'('.html_entity_decode(ucfirst($subject->name)).')';
								}
							}
						}
					}
					else{	//In case of Elective
						$elective	= Electives::model()->findByAttributes(array('id'=>$value->subject_id));
						if($elective){
							$subject_name	= html_entity_decode(ucfirst($elective->name));
						}
					}
					
					$return_arr[$i]['start_time']	= date("H:i", strtotime($timing->start_time));
					$return_arr[$i]['end_time']		= date("H:i", strtotime($timing->end_time));	
					$return_arr[$i]['course']		= $course_name;				
					$return_arr[$i]['batch']		= $batch_name;
					$return_arr[$i]['subject']		= $subject_name;
					
					$i++;
				}
			}
			$post_data	= json_encode(array('timetable' =>$return_arr),JSON_UNESCAPED_SLASHES);			
			echo $post_data;
			exit();
		}			
		return  array_values($return_arr);
	}
	public function getdownloads($post){
		$user_id	= $post['uid'];
		$user_type	= '';
		$files 		= array();
		$roles		= Rights::getAssignedRoles($user_id); // check for single role
		if($roles){
			foreach($roles as $role){
				if(sizeof($roles) == 1 and $role->name == 'parent'){
					$user_type	= 1;
			   	}
			   	else if(sizeof($roles) == 1 and $role->name == 'student'){ 
					$user_type	= 2;
			   	}
			   	else if(sizeof($roles) == 1 and $role->name == 'teacher'){ 
					$user_type	= 3;
			   	}
			   	else if(sizeof($roles) == 1 and $role->name == 'Admin'){ 
					$user_type	= 4;
			   	}
				else{ //custome users
					$user_type = 5;
				}
			}
		}
		Yii::import('application.modules.downloads.models.FileUploads');
				
		if($user_type == 2 or ($user_type == 1 and $_POST['id'])){ //if user is student.........	
			if($user_type == 2){
				$student 			= Students::model()->findByAttributes(array('uid'=>$user_id));
			}
			else{
				$student 			= Students::model()->findByAttributes(array('id'=>$_POST['id']));
			}	
			$batch_id_arr			= array();
			$course_id_arr			= array();		
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
			$criteria->join			= 'JOIN file_uploads_students t1 ON t.id = t1.table_id JOIN document_uploads t2 ON t2.file_id = t.id'; 
			$criteria->condition	= 't.placeholder=:placeholder AND t1.student_id=:student_id AND t.is_special_student=:is_special_student AND t2.model_id=:model_id AND t2.status=:status';
			$criteria->params		= array(':placeholder'=>'student', ':student_id'=>$student->id, ':is_special_student'=>1, ':model_id'=>5, ':status'=>1);
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
			$criteria->join				= 'JOIN `document_uploads` `t1` ON `t1`.`file_id` = `t`.`id`';		
			$criteria->condition		= '`t`.`file`<>:null AND `t1`.`model_id`=:model_id AND `t1`.`status`=:status AND (`t`.`placeholder`=:null OR (`t`.`placeholder`=:placeholder AND `t`.`is_special_student`=0)) AND ((`t`.`course` IS NULL) OR'.$val1.'(`t`.`course`=0)) AND ((`t`.`batch` IS NULL) OR'.$val2.'(`t`.`batch`=0))';
			$criteria->params			= array(':null'=>'', ':placeholder'=>'student', ':model_id'=>5, ':status'=>1);
			$criteria->addInCondition('`t`.`id`', $special_file_arr, 'OR');
			$criteria->order			= '`t`.`created_at` DESC';
			$criteria->group			= '`t`.`id`';				
			$files						= FileUploads::model()->findAll($criteria);						
		}
		elseif($user_type == 3){ //if user is teacher.........		
			$criteria				= new CDbCriteria;
			$criteria->join			= 'JOIN `document_uploads` `t1` ON `t1`.`file_id` = `t`.`id`';			
			$criteria->condition	= '`t`.`file`<>:null AND `t1`.`model_id`=:model_id AND `t1`.`status`=:status';
			$criteria->params		= array(':null'=>'', ':model_id'=>5, ':status'=>1);		
			$roles					= Rights::getAssignedRoles($user_id); // check for single role
			$user_roles				= array();
			foreach($roles as $role){
				$user_roles[]	=	'"'.$role->name.'"';
			}
			$teacher = Employees::model()->findByAttributes(array('uid'=>$user_id));
			$batches = Batches::model()->findAllByAttributes(array('employee_id'=>$teacher->id, 'is_active'=>1, 'is_deleted'=>0));
			foreach($batches as $classteacher){				
				$batch[] = $classteacher->id;				
			}
			
			$timetable = TimetableEntries::model()->findAllByAttributes(array('employee_id'=>$teacher->id));
			foreach($timetable as $period){	
				$is_batch		= Batches::model()->findByAttributes(array('id'=>$period->batch_id, 'is_active'=>1, 'is_deleted'=>0));	
				if($is_batch){		
					$batch[] 	= $period->batch_id;
				}
			}			
			$unique_batch = array_unique($batch);			
			if(count($unique_batch)>0){
				$criteria->condition		.=	' AND (`t`.`placeholder`=:null OR `t`.`created_by`=:user_id OR (`t`.`placeholder` IN ('.implode(',',$user_roles).')) AND (`t`.`batch` IS NULL OR `t`.`batch` IN ('.implode(',',$unique_batch).'))) ';
			}
			else{
				$criteria->condition		.=	' AND (`t`.`placeholder`=:null OR `t`.`created_by`=:user_id) OR (`t`.`placeholder` IN ('.implode(',',$user_roles).'))';
			}
			$criteria->params[':user_id']	=	$user_id;
			$criteria->order				=	'`t`.`created_at` DESC';
			
			$files							=	FileUploads::model()->findAll($criteria);
			
		}
		elseif($user_type == 4 or $user_type == 5){ //if user is admin or custom user.........		
			$academic_yr 			= Configurations::model()->findByPk(35);
			$criteria				= new CDbCriteria;
			$criteria->condition	= '`file`<>:null and `academic_yr_id`=:year_id';
			$criteria->params		= array(':null'=>'',':year_id'=>$academic_yr->config_value);
			$criteria->order		= '`created_at` DESC';	
			$files					= FileUploads::model()->findAll($criteria);		
		}
		
		return $files;		
	}
	public function actionDownloadfile($id){
		Yii::import('application.modules.downloads.models.FileUploads');
		$model	=	FileUploads::model()->findByPk($id);
		if($model!=NULL){	
			$file = 'uploads/shared/'.$model->id.'/'.$model->file;
			if(file_exists($file)){
				Yii::app()->getRequest()->sendFile( $model->file , file_get_contents($file));
			}									
		}else{
			$response["error"] = true;
			$response["error_msg"] = "Invalid Request";
			echo json_encode($response);
			exit;
		}
	}
	private function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	///return exams of particular batch............
	public function getexams($post)
	{
		$user_id	=	$post['uid'];
		$student	=	Students::model()->findByAttributes(array('uid'=>$user_id));
		if($student){
			
			$batch					=	Batches::model()->findByPk($student->batch_id);
			if($batch){
				$criteria 				=	new CDbCriteria;
				$criteria->condition	=	"batch_id =:batch_id AND is_published =:is_published";
				$criteria->params		=	array(':batch_id' => $batch->id,':is_published'=>1);
				$model	=	ExamGroups::model()->findAll($criteria);
				return $model;
			}else
				return array();
		}else
			return array();
	}
	public function getbooks($post)
	{
		Yii::import('application.modules.library.models.BorrowBook');
		$user_id	=	$post['uid'];
		$student	=	Students::model()->findByAttributes(array('uid'=>$user_id));
		if($student){
			$criteria 				=	new CDbCriteria;
			$criteria->condition	=	"student_id =:student_id AND status =:status";
			$criteria->params		=	array(':student_id' => $student->id,':status'=>"C");
			$model	=	BorrowBook::model()->findAll($criteria);
			return $model;
			
		}else
			return array();
	}
	
	protected function getAllRoutes(){
		$criteria	= new CDbCriteria;
		$routes		= RouteDetails::model()->findAll($criteria);
		return $routes;
	}
	
	protected function getGrade($batch_id, $mark){
		$grading_levels	= GradingLevels::model()->findAllByAttributes(array('batch_id'=>$batch_id),array('order'=>'min_score DESC'));		
		if($grading_levels == NULL){
			$grading_levels	= GradingLevels::model()->findAllByAttributes(array('batch_id'=>NULL),array('order'=>'min_score DESC'));	
		}
		$i = count($grading_levels);		
		foreach($grading_levels as $grading_level){		
			if($grading_level->min_score <= $mark){
				return  $grading_level->name;
			}
			else{
				$i--;
				continue;				
			}
		}
		if($i <= 0){
			return Yii::t("app",'No Grades');
		}
	}
        
	protected function sortDashboard(&$array, $column, $direction = SORT_DESC)
	{
		$reference_array = array();
		foreach($array as $key => $row) {
			$reference_array[$key] = $row[$column];
		}
		array_multisort($reference_array, $direction, $array);
	}
	
	
        
    protected function getDate($time)
	{
		$timediff = time() - $time;
		if($timediff < 60 )
			$date = $timediff . ' sec ago';
		elseif($timediff < 3600 - 60) // within last hour
			$date = ceil($timediff / 60) . ' min ago';
		elseif($time > strtotime('today')) // today
			$date = date('h:i a',$time);
		elseif($time > strtotime(date('Y-1-01'))) // within this year
			$date = date('M j',$time);
		else
			$date = date('m/d/y',$time); // last year or more
		return $date;
	}
	
	//Get Profile Image Path
	protected function getProfileImagePath($value, $type) //[ $value => It may be user id or id ] [$type => 1 -> teacher or student, 2 -> student, 3 -> teacher ]
	{
		$path	= '';
		if($type == 1){ //In case of teacher or student
			$roles 	= Rights::getAssignedRoles($value);
			if(key($roles) == 'student' or key($roles) == 'teacher'){
				if(key($roles) == 'student'){
					$model	= Students::model()->findByAttributes(array('uid'=>$value));	
				}
				if(key($roles) == 'teacher'){
					$model	= Employees::model()->findByAttributes(array('uid'=>$value));	
				}
				if($model != NULL and $model->photo_file_name != NULL){
					$path = $model->getProfileImagePath($model->id);
				}
			}	
		}
		else if($type == 2){ //In case of student
			$model	= Students::model()->findByAttributes(array('id'=>$value));
			if($model != NULL and $model->photo_file_name != NULL){
				if(file_exists($model->getProfileImagePath($model->id))){
					$path = $model->getProfileImagePath($model->id);
				}
				
			}
		}
		else if($type == 3){ //In case of teacher
			$model	= Employees::model()->findByAttributes(array('id'=>$value));
			if($model != NULL and $model->photo_file_name != NULL){
				if(file_exists($model->getProfileImagePath($model->id))){
					$path = $model->getProfileImagePath($model->id);
				}
			}	
		}
		
		return $path;
	}
	
	//Check whether the date is set as holiday
	protected function isHoliday($date)
	{		
		$start	= $date.' '."00:00:00";
		$end 	= $date.' '."23:59:59";      
		$flag	= 0;
		
		$criteria 				= new CDbCriteria();     
		$criteria->condition 	= "start >=:start AND start <=:end";
		$criteria->params  		= array(':start'=>strtotime($start),':end'=>strtotime($end));
		$holiday				= Holidays::model()->findAll($criteria);
		if(count($holiday) > 0){
			$flag = 1;
		}
		return $flag;
	}	
	//Check whether the date is set as weekday
	public function isWeekday($date)
	{
		$flag	= 0;
		$day	= date('N', strtotime($date))+1;
		if($day > 7){
			$day = 1;
		}		
		$weekdays = Weekdays::model()->find("batch_id IS NULL AND weekday=:weekday", array(':weekday'=>$day));
		if($weekdays == NULL){
			$flag = 1;
		}					    	
		return $flag;
	}
	
	//Get subject name from timetable entries
	public function	getSubjectName($timetable_id)
	{
		$timetable_entry	= TimetableEntries::model()->findByPk($timetable_id);
		$subject_name		= '';
		
		if($timetable_entry->is_elective == 0){ //In case of normal subject
			$subject	= Subjects::model()->findByPk($timetable_entry->subject_id);			
			if($subject){
				$subject_name	= html_entity_decode(ucfirst($subject->name));
				if($timetable_entry->split_subject != 0){
					$split_subject	= SubjectSplit::model()->findByPk($timetable_entry->split_subject);
					if($split_subject != NULL){
						$subject_name	= $subject_name.' ( '.html_entity_decode(ucfirst($split_subject->split_name)).' )';
					}
				}
			}			
		}
		else{ //In case of elective subject			
			$elective	= Electives::model()->findByPk($timetable_entry->subject_id);
			if($elective){
				$elective_group	= ElectiveGroups::model()->findByPk($elective->elective_group_id);
				if($elective_group){
					$subject_name	= html_entity_decode(ucfirst($elective_group->name));
				}
			}			
		}
		
		return $subject_name;
	}
	
	//Get subject name from timetable entries
	public function	getEmployeeName($timetable_id)
	{
		$timetable_entry	= TimetableEntries::model()->findByPk($timetable_id);
		$employee_name		= '';
		$employee			= Employees::model()->findByPk($timetable_entry->employee_id);				
		if($employee){
			$employee_name	= ucfirst($employee->fullname);
		}
		
		return $employee_name;
	}	
	
	//Get Class timing label from timetable entries
	public function getClassTimingLabel($timetable_id)
	{
		$timetable_entry	= TimetableEntries::model()->findByPk($timetable_id);
		$class_timing_label	= '';
		$class_timing		= ClassTimings::model()->findByPk($timetable_entry->class_timing_id);
		if($class_timing){
			$class_timing_label	= $class_timing->start_time.' - '.$class_timing->end_time;
		}
		
		return $class_timing_label;
	}
	
	//Check Whether the day is a weekday for batch
	public function checkWeekday($day, $batch_id)
	{	
		$flag	= 0;	
		$weekdays = Weekdays::model()->findAllByAttributes(array('batch_id'=>$batch_id));
		if(count($weekdays) > 0){
			$is_working_day	= Weekdays::model()->findByAttributes(array('batch_id'=>$batch_id,'weekday'=>$day));
			if($is_working_day != NULL){
				$flag = 1;
			}
		}
		else{
			$weekdays = Weekdays::model()->find("batch_id IS NULL AND weekday=:weekday", array(':weekday'=>$day));
			if($weekdays){
				$flag = 1;
			}
		}			    	
		return $flag;
	}
	//For cbse
	public function actionStudentcbscpdf()
	{	
		$student_name   = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$exam_name      = CbscExamGroup17::model()->findByAttributes(array('id'=>$_REQUEST['exam_group_id']));
		$filename		= ucfirst($student_name->first_name).' '.ucfirst($student_name->last_name).' '.ucfirst($exam_name->name).Yii::t('app',' Assessment').' Report.pdf';
		if($exam_name->class==1){ //class 1-2		
			Yii::app()->osPdf->generate("application.modules.teachersportal.views.exams.studentexampdf1", $filename, array());
		}
		else if($exam_name->class==2){ //class 3-8
			Yii::app()->osPdf->generate("application.modules.teachersportal.views.exams.studentexampdf2", $filename, array());
		}
		else if($exam_name->class==3){ //class 9-10
			Yii::app()->osPdf->generate("application.modules.teachersportal.views.exams.studentexampdf3", $filename, array());
		}
		else if($exam_name->class==4){ //class 11-12
			Yii::app()->osPdf->generate("application.modules.teachersportal.views.exams.studentexampdf4", $filename, array());
		}
	}
	
	public function sortArray($datas, $b) {
		return strcmp($datas["name"], $b["name"]);
	}   
        
}

	
	