<?php

class StaffController extends RController
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
		$model=new Staff;
		
		if(isset($_POST['Staff']))
		{
			$model->attributes=$_POST['Staff'];
			$emp_id	= Staff::model()->findAll(array('order' => 'id DESC','limit' => 1));
			if(!$emp_id){
				$emp_id_1='E1';
			}else{
				$length = strlen($emp_id[0]['employee_number']);
				$substr1 = substr($emp_id[0]['employee_number'],0,1);
				$substr2 = trim(substr($emp_id[0]['employee_number'],1,$length-1));
				$next_no = $substr2+1;				
				$emp_id_1 = 'E'.$next_no;					
			}
			$model->employee_number = $emp_id_1;
			if($model->validate()){
				$model->joining_date 	=	date('Y-m-d',strtotime($model->joining_date));
				$model->date_of_birth 	=	date('Y-m-d',strtotime($model->date_of_birth));
				$model->user_type 		=	1;
				if($model->passport_expiry!=""){
					$model->passport_expiry 	=	date('Y-m-d',strtotime($model->passport_expiry));
				}
				if($model->save()){
					//adding user for current staff
					$role	=	UserRoles::model()->findByPk($model->staff_type);
					$user	=	new User;
					$profile=	new Profile;
					$salt	= 	User::model()->getSalt();
					$user->username 	= 	substr(md5(uniqid(mt_rand(), true)), 0, 10);
					$user->email 		= 	$model->email;
					$user->activkey 	=	UserModule::encrypting(microtime().$model->first_name);
					$password 			= 	substr(md5(uniqid(mt_rand(), true)), 0, 10);
					$user->password 	=	UserModule::encrypting($salt.$password);
					$user->mobile_number= 	$model->mobile_phone;
					$user->superuser 	=	0;
					$user->status 		=	1;
					$user->salt 		= 	$salt;
					
					if($user->save())
					{
						//assign role
						$authorizer = Yii::app()->getModule("rights")->getAuthorizer();
						$authorizer->authManager->assign($role->name, $user->id); 
						
						//profile
						$profile->firstname 	= 	$model->first_name;
						$profile->lastname 		= 	$model->last_name;
						$profile->user_id 		=	$user->id;
						$profile->save();
						
						//saving user id to students table.
						$model->saveAttributes(array('uid'=>$user->id));
					
						// For Sending SMS
						
						$notification = NotificationSettings::model()->findByAttributes(array('id'=>5));
						$college=Configurations::model()->findByPk(1);
						$to = '';
						
						if($notification->mail_enabled == '1' and $notification->employee == '1')
						{									
							$template=EmailTemplates::model()->findByPk(3);
							$subject = $template->subject;
							$message = $template->template;
							$subject = str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);								
							$message = str_replace("{{SCHOOL NAME}}",$college->config_value,$message);
							$message = str_replace("{{EMAIL}}",$model->email,$message);
							$message = str_replace("{{PASSWORD}}",$password,$message);	
														
							UserModule::sendMail($model->email,$subject,$message);								
							
						}
												
					}
					$this->redirect(array('index'));
				}
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
		if($settings!=NULL)
		{
			$date=$settings->displaydate;
		}
		else
			$date = 'd-m-Y';
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if($model->joining_date!="0000-00-00"){
			$model->joining_date 	=	date($date,strtotime($model->joining_date));
		}
		if($model->date_of_birth!="0000-00-00"){
			$model->date_of_birth 	=	date($date,strtotime($model->date_of_birth));
		}
		if($model->passport_expiry!=NULL and $model->passport_expiry!="0000-00-00"){
			$model->passport_expiry 	=	date($date,strtotime($model->passport_expiry));
		}else{
			$model->passport_expiry="";
		}
		
		
		if(isset($_POST['Staff']))
		{
			$model->attributes 			=	$_POST['Staff'];
			
			if($model->validate()){
				$model->joining_date 	=	date('Y-m-d',strtotime($model->joining_date));
				$model->date_of_birth 	=	date('Y-m-d',strtotime($model->date_of_birth));
				$model->user_type 		=	1;
				if($model->passport_expiry!=""){
					$model->passport_expiry 	=	date('Y-m-d',strtotime($model->passport_expiry));
				}
				if($model->save()){
					$this->redirect(array('index'));
				}
			}
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
			$model	=	$this->loadModel($id);
			$model->saveAttributes(array("is_deleted"=>1));
			

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model		=	new Staff;
		$criteria	=	new CDbCriteria;
		$criteria->condition 	=	'user_type=1 AND is_deleted=0';
		
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
		
		if(isset($_REQUEST['employee_number']) and $_REQUEST['employee_number']!=NULL)
		{
			$criteria->condition=$criteria->condition.' and '.'employee_number LIKE :employee_number';
			$criteria->params[':employee_number'] = $_REQUEST['employee_number'].'%';
		}
		
		if(isset($_REQUEST['Staff']['gender']) and $_REQUEST['Staff']['gender']!=NULL)
		{
			$model->gender = $_REQUEST['Staff']['gender'];
			$criteria->condition=$criteria->condition.' and '.' `t`.gender LIKE :gender';
		    $criteria->params[':gender'] = $_REQUEST['Staff']['gender']."%";
		}
		
		$total = Staff::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->setPageSize(Yii::app()->params['listPerPage']);
		$pages->applyLimit($criteria);  // the trick is here
		$list		=	Staff::model()->findAll($criteria);
		$this->render('index',array(
		'list'=>$list,
		'model'=>$model,
		'pages' => $pages,
		'item_count'=>$total,
		'page_size'=>Yii::app()->params['listPerPage'],)) ;
	}
	
	public function actionSalaryDetails(){
		
		$criteria	=	new CDbCriteria;
		$criteria->condition 	=	'is_deleted=0';
		
		$total = Staff::model()->count($criteria);
		$pages = new CPagination($total);
		$pages->setPageSize(25);
		$pages->applyLimit($criteria);  // the trick is here
		$list		=	Staff::model()->findAll($criteria);
		$this->render('salarydetails',array(
		'list'=>$list,
		'pages' => $pages,
		'item_count'=>$total,
		'page_size'=>25)) ;
	}
	
	public function actionAddsalarydetails(){
		$model	=	Staff::model()->findByPk($_REQUEST['id']);
		if($model->tds_type==NULL){
			$model->tds_type=0;
		}
		if($model->basic_pay==0){
			$model->basic_pay="";
		}
		if($model->TDS==0.00 or $model->TDS==NULL){
			$model->TDS="";
		}		
		if($model->ESI==0 or $model->ESI==NULL){
			$model->ESI="";
		}
		if($model->EPF==0 or $model->EPF==NULL){
			$model->EPF="";
		}
		
		if(isset($_POST['Staff'])){ 
			$model->attributes =	$_POST['Staff'];
			if($model->save()){
				$this->redirect(array('salarydetails'));
			}
		}
		
		$this->render('addsalarydetails',array(
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
		$model=Staff::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='staff-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
