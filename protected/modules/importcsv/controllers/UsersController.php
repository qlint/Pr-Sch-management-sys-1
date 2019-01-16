<?php
class UsersController extends RController
{
	public function filters(){
		return array(
			'rights'
		);
	}
	public function actionIndex()
	{
		$this->render('index');
	}
		
	public function actionStudent()
	{		
		if(isset($_POST['studentuser']))
		{			
			foreach($_POST['student_user'] as $student_id)
			{
				$student = Students::model()->findByAttributes(array('id'=>$student_id));				
				if($student->email!=NULL)
				{
                                        $salt= User::model()->getSalt(); 
					$user=new User;
					$profile=new Profile;
					$user->username = substr(md5(uniqid(mt_rand(), true)), 0, 10);
					$user->email = $student->email;
					$user->activkey=UserModule::encrypting(microtime().$student->first_name);
					$password = substr(md5(uniqid(mt_rand(), true)), 0, 10);
					$user->password=UserModule::encrypting($salt.$password);
					$user->superuser=0;
					$user->status=1;
                                        if(isset($student->phone1))
                                        {
                                            $user->mobile_number= $student->phone1;
                                        }
                                        $user->salt= $salt;
					if($user->save())
					{					
						//assign role
						$authorizer = Yii::app()->getModule("rights")->getAuthorizer();
						$authorizer->authManager->assign('student', $user->id); 
						
						//profile
						$profile->firstname = $student->first_name;
						$profile->lastname = $student->last_name;
						$profile->user_id=$user->id;
						$profile->save();
						
						//saving user id to students table.
						$student->saveAttributes(array('uid'=>$user->id));				
						
						
						$notification = NotificationSettings::model()->findByAttributes(array('id'=>12));
						$college=Configurations::model()->findByPk(1);
					//send mail
						if($notification->mail_enabled == '1' and $notification->student == '1')
						{						
							$template=EmailTemplates::model()->findByPk(16);
							$subject = $template->subject;
							$message = $template->template;						
							$subject = str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);						
							$message = str_replace("{{SCHOOL NAME}}",$college->config_value,$message);
							$message = str_replace("{{PASSWORD}}",$password,$message);																				
							UserModule::sendMail($student->email,$subject,$message);
							
						}	
					//Message					
						if($notification->msg_enabled == '1' and $notification->student == '1')
						{						
							$to = $student->uid;
							$subject = Yii::t('app','Welcome to ').$college->config_value;
							$message = Yii::t('app','Hi, Welcome to ').$college->config_value.Yii::t('app','. We are looking forward to your esteemed presence and cooperation with our organization.');
							NotificationSettings::model()->sendMessage($to,$subject,$message);
						}
						
						//send previously generated invoices, if any
						Yii::app()->getModule("fees")->sendInvoicesForNewStudent($student_id);
						
						$flash	=	Yii::t('app','User(s) created successfully');
						$type	=	'success';
					}
				}
				else
				{
					$flash	=	Yii::t('app','No email id given');
					$type	=	'error';
				}
			}
			
			Yii::app()->user->setFlash($type,$flash);
			$this->redirect(array('student'));
			 
		}
		else
		{
			$criteria = new CDbCriteria;
			$criteria->condition='uid = :uid and is_deleted = :is_deleted';		
			$criteria->params = array(':uid'=>0,':is_deleted'=>0);		
			$criteria->order = "last_name ASC";			
			$total = Students::model()->count($criteria); // Count students
			$pages = new CPagination($total);
			$pages->setPageSize(50);
			$pages->applyLimit($criteria);
			$studentlist = Students::model()->findAll($criteria);
			$this->render('/default/student',array('item_count'=>$total,'pages'=>$pages,'studentlist'=>$studentlist));
		}
	}
	
	
	public function actionParent()
	{
		
		if(isset($_POST['parent_user']))
		{
			foreach($_POST['parent_user'] as $parent_id)
			{
				//echo 'ID: '.$parent_id.'-';
				$parent = Guardians::model()->findByAttributes(array('id'=>$parent_id));				
				if($parent->email!=NULL)
				{
					$is_parent_1 = Students::model()->countByAttributes(array('parent_id'=>$parent_id,'is_deleted'=>0));
					
					//$is_parent_2 = Students::model()->countByAttributes(array('parent_id_2'=>$parent_id,'is_deleted'=>0,'is_active'=>1));
					
					//echo $is_parent_1.'-'.$is_parent_2.'<br/>';	
                                        $salt= User::model()->getSalt();  
					$user=new User;
					$profile=new Profile;
					$user->username = substr(md5(uniqid(mt_rand(), true)), 0, 10);
					$user->email = $parent->email;					
					$user->activkey=UserModule::encrypting(microtime().$parent->first_name);
					$password = substr(md5(uniqid(mt_rand(), true)), 0, 10);
					$user->password=UserModule::encrypting($salt.$password);
					$user->superuser=0;
					$user->status=1;
					$user->salt= $salt;
                                        if(isset($parent->mobile_phone))
                                        {
                                            $user->mobile_number= $parent->mobile_phone;
                                        }
					$user->create_at=date('Y-m-d H:i:s');
								
					if($user->save())
					{
						//assign role
						$authorizer = Yii::app()->getModule("rights")->getAuthorizer();
						$authorizer->authManager->assign('parent', $user->id);
			
						//profile
						$profile->firstname = $parent->first_name;
						$profile->lastname = $parent->last_name;
						$profile->user_id=$user->id;
						$profile->save();
			
						//saving user id to guardian table.
						$parent->saveAttributes(array('uid'=>$user->id));
						
						$notification = NotificationSettings::model()->findByAttributes(array('id'=>12));
						$college=Configurations::model()->findByPk(1);
					//send mail
						if($notification->mail_enabled == '1' and $notification->parent_1 == '1')
						{						
							$template=EmailTemplates::model()->findByPk(16);
							$subject = $template->subject;
							$message = $template->template;						
							$subject = str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);						
							$message = str_replace("{{SCHOOL NAME}}",$college->config_value,$message);
							$message = str_replace("{{PASSWORD}}",$password,$message);																				
							UserModule::sendMail($parent->email,$subject,$message);
							
						}	
					//Message					
						if($notification->msg_enabled == '1' and $notification->parent_1 == '1')
						{						
							$to = $parent->uid;
							$subject = Yii::t('app','Welcome to ').$college->config_value;
							$message = Yii::t('app','Hi, Welcome to ').$college->config_value.Yii::t('app','. We are looking forward to your esteemed presence and cooperation with our organization.');
							NotificationSettings::model()->sendMessage($to,$subject,$message);
						}
			
			
					$flash	=	Yii::t('app','User(s) created successfully');
					$type	=	'success';
					}
				}
				else
				{
					$flash	=	Yii::t('app','No email id given');
					$type	=	'error';
				}
			}//exit;
						
			Yii::app()->user->setFlash($type,$flash);
			 $this->redirect(array('parent'));
		}
		else
		{
			$criteria = new CDbCriteria;
			$criteria->condition='uid = :uid and is_delete = :is_delete';		
			$criteria->params = array(':uid'=>0,':is_delete'=>0);		
			$criteria->order = "last_name ASC";			
			$total = Guardians::model()->count($criteria); // Count students
			$pages = new CPagination($total);
			$pages->setPageSize(50);
			$pages->applyLimit($criteria);
			$parentlist = Guardians::model()->findAll($criteria);
			$this->render('/default/parent',array('item_count'=>$total,'pages'=>$pages,'parentlist'=>$parentlist));
		}
		
		
	}
	
	
	
	public function actionEmployee()
	{
		if(isset($_POST['employee_user']))
		{
			foreach($_POST['employee_user'] as $employee_id)
			{
				$employee = Employees::model()->findByAttributes(array('id'=>$employee_id));
				if($employee->email!=NULL)
				{
                     $salt= User::model()->getSalt();  
					$user=new User;
					$profile=new Profile;
					$user->username = substr(md5(uniqid(mt_rand(), true)), 0, 10);
					$user->email = $employee->email;
					$user->activkey=UserModule::encrypting(microtime().$employee->first_name);
					$password = substr(md5(uniqid(mt_rand(), true)), 0, 10);
					$user->password=UserModule::encrypting($salt.$password);
					$user->superuser=0;
					$user->status=1;
					if(isset($employee->mobile_phone))
					{
						$user->mobile_number= $employee->mobile_phone;
					}
					$user->salt= $salt;
					
					if($user->save())
					{ 
						//assign role
						$authorizer = Yii::app()->getModule("rights")->getAuthorizer();
						$authorizer->authManager->assign('teacher', $user->id); 
						
						//profile
						$profile->firstname = $employee->first_name;
						$profile->lastname = $employee->last_name;
						$profile->user_id=$user->id;
						$profile->save();
						
						//saving user id to students table.
						$employee->saveAttributes(array('uid'=>$user->id));					
						
						$notification = NotificationSettings::model()->findByAttributes(array('id'=>12));
						$college=Configurations::model()->findByPk(1);
					//send mail
						if($notification->mail_enabled == '1' and $notification->employee == '1')
						{						
							$template=EmailTemplates::model()->findByPk(16);
							$subject = $template->subject;
							$message = $template->template;						
							$subject = str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);						
							$message = str_replace("{{SCHOOL NAME}}",$college->config_value,$message);
							$message = str_replace("{{PASSWORD}}",$password,$message);																				
							UserModule::sendMail($employee->email,$subject,$message);							
						}	
					//Message					
						if($notification->msg_enabled == '1' and $notification->employee == '1')
						{						
							$to = $employee->uid;
							$subject = Yii::t('app','Welcome to ').$college->config_value;
							$message = Yii::t('app','Hi, Welcome to ').$college->config_value.Yii::t('app','. We are looking forward to your esteemed presence and cooperation with our organization.');
							NotificationSettings::model()->sendMessage($to,$subject,$message);
						}				
					
						
						$flash	=	Yii::t('app','User(s) created successfully');
						$type	=	'success';
					}
				}
				else
				{
					$flash	=	Yii::t('app','No email id given');
					$type	=	'error';
				}
			}
			
			Yii::app()->user->setFlash($type,$flash);
			$this->redirect(array('employee'));
			 
		}
		else
		{
			$criteria = new CDbCriteria;
			$criteria->condition='uid = :uid and is_deleted = :is_deleted';		
			$criteria->params = array(':uid'=>0,':is_deleted'=>0);		
			$criteria->order = "last_name ASC";			
			$total = Employees::model()->count($criteria); // Count students
			$pages = new CPagination($total);
			$pages->setPageSize(50);
			$pages->applyLimit($criteria);
			$employeelist = Employees::model()->findAll($criteria);
			$this->render('/default/employee',array('item_count'=>$total,'pages'=>$pages,'employeelist'=>$employeelist));
		}
		
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