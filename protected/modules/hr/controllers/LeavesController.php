<?php

class LeavesController extends RController
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
	
	public function actionIndex()
	{
		$criteria 				= new CDbCriteria;
		$criteria->condition	= '`requested_by`=:requested_by';
		$criteria->params		= array(':requested_by'=>Yii::app()->user->Id);	
		$criteria->order 		= 'id DESC';
		
		$total = LeaveRequests::model()->count($criteria);
		$pages = new CPagination($total);
        $pages->setPageSize(Yii::app()->params['listPerPage']);
        $pages->applyLimit($criteria); 
		
		$posts = LeaveRequests::model()->findAll($criteria);
		$this->render('index', array('leaves'=>$posts, 'pages' => $pages, 'item_count'=>$total, 'page_size'=>Yii::app()->params['listPerPage']));
	}
	
	public function actionCreate()
	{
		$model=new LeaveRequests;
		if(isset($_POST['LeaveRequests']))
		{ 
			$model->attributes		=	$_POST['LeaveRequests'];
			$model->requested_by 	= 	Yii::app()->user->id;
			
			if($model->from_date)
    			 $model->from_date=date('Y-m-d',strtotime($model->from_date));
			if($model->to_date)
    			 $model->to_date=date('Y-m-d',strtotime($model->to_date));
				 
			 if($file=CUploadedFile::getInstance($model,'file_name')){
					$model->file_name=$file->name;				
			}	
			
			if($model->save()){
				if($model->file_name!=NULL){
						if(!is_dir('uploadedfiles/')){
							mkdir('uploadedfiles/');
						}
						if(!is_dir('uploadedfiles/leave_images/')){
							mkdir('uploadedfiles/leave_images/');
						}
						if(!is_dir('uploadedfiles/leave_images/'.$model->requested_by)){
							mkdir('uploadedfiles/leave_images/'.$model->requested_by);
						}
					//compress the image
					$info = getimagesize($_FILES['LeaveRequests']['tmp_name']['file_name']);
					if($info['mime'] == 'image/jpeg'){
						$image = imagecreatefromjpeg($_FILES['LeaveRequests']['tmp_name']['file_name']);
					}elseif($info['mime'] == 'image/gif'){
						$image = imagecreatefromgif($_FILES['LeaveRequests']['tmp_name']['file_name']);
					}elseif($info['mime'] == 'image/png'){
						$image = imagecreatefrompng($_FILES['LeaveRequests']['tmp_name']['file_name']);
					}
					
					$temp_file_name = $_FILES['LeaveRequests']['tmp_name']['file_name'];
					$destination_file = 'uploadedfiles/leave_images/'.$model->requested_by.'/'.$model->file_name;
					imagejpeg($image, $destination_file, 30);
				}
				
				//Email
				$college			= Configurations::model()->findByPk(1);						
				$template			= EmailTemplates::model()->findByPk(34);
				$profile			= Profile::model()->findByAttributes(array('user_id'=>$model->requested_by)); //leave requested person
				$user				= User::model()->findByAttributes(array('id'=>1));	
				$profile_user		= Profile::model()->findByAttributes(array('user_id'=>1));	
				$subject 			= $template->subject;
				$message 			= $template->template;
				$subject 			= str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);
				$message 			= str_replace("{{SCHOOL NAME}}",$college->config_value,$message);								
				$message    		= str_replace("{{NAME}}", ucfirst($profile_user->firstname).' '.ucfirst($profile_user->lastname), $message);
				$message    		= str_replace("{{USER NAME}}", ucfirst($profile->firstname).' '.ucfirst($profile->lastname), $message);
				UserModule::sendMail($user->email,$subject,$message);
				
				//Email for admin level users, custom users like hr manager
				$criteria 				= 	new CDbCriteria();
				$criteria->join 		= 	'JOIN authassignment aa ON aa.userid = user.id JOIN authitemchild aui ON aui.parent = aa.itemname';
				$criteria->condition	= 	'aui.child = :child';
				$criteria->params 		= 	array(":child" => 'Hr.LeaveTypes.*');
				$users 					= 	User::model()->findAll($criteria);
				if($users){
					foreach($users as $user){
						UserModule::sendMail($user->email,$subject,$message);
					}
				}
				
				//sms
				$from 		= $college->config_value;
				$to 		= $user->mobile_number;
				$template	= SystemTemplates::model()->findByPk(38);
				$message 	= $template->template;
				$message 	= str_replace("<Name>",ucfirst($profile->firstname).' '.ucfirst($profile->lastname),$message);
				if($to){
					SmsSettings::model()->sendSms($to,$from,$message);
				}
				
				//sms for admin level users, custom users like hr manager
				if($users){
					foreach($users as $user){
						$to 		= $user->mobile_number;
						if($to){
							SmsSettings::model()->sendSms($to,$from,$message);
				}
					}
				}
				
				//Mobile App Notification
				//For Android App
				if(Configurations::model()->isAndroidEnabled()){
					$reg_arr					= array();				
					$criteria 					= new CDbCriteria();
					$criteria->condition 		= 'uid<>:uid';
					$criteria->params[':uid'] 	= 1; 
					$reg_ids 					= UserDevice::model()->findAll($criteria);
					
					foreach($reg_ids as $reg_id){
						$reg_arr[] = $reg_id->device_id; 
					}
					
					$argument_arr = array('message'=>  $message,'device_id'=>$reg_arr);                
					Configurations::model()->devicenotice($argument_arr, 'Leave Request',"leave");
					
					//notification for admin level users, custom users like hr manager
					if($users){
						$reg_arr					= array();				
						$criteria 					= new CDbCriteria();
						$criteria->condition 		= 'uid<>:uid';
						foreach($users as $user){
							$criteria->params[':uid'] 	= $user->id;
						}
						$reg_ids 					= UserDevice::model()->findAll($criteria);
						foreach($reg_ids as $reg_id){
							$reg_arr[] = $reg_id->device_id; 
						}
					
					$argument_arr = array('message'=>  $message,'device_id'=>$reg_arr);                
					Configurations::model()->devicenotice($argument_arr, 'Leave Request',"leave");
					}
				}
				
				Yii::app()->user->setFlash('successMessage', Yii::t('app','Leave Request Send Successfully'));
				$this->redirect(array('index'));
			}
	}
	$this->render('create',array(
		'model'=>$model,
	));
	}
	
	public function actionSalary()
	{
		$this->render('salary');
	}
	public function actionSalarydetails()
	{
		$this->render('salarydetails');
	}
	
	public function actionCancel() { 
		if($_REQUEST['id']){ 
			$model =  LeaveRequests::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		}
		
		if(isset($_POST['LeaveRequests'])){
			$model->cancel_reason	= $_POST['LeaveRequests']['cancel_reason'];
			$model->status			= 3; //cancelled
			$model->handled_by		= Yii::app()->user->id;
			$model->handled_at		= date("Y-m-d H:i:s");
			if($model->save()){
				echo CJSON::encode(array(
					'status'=>'success',
					'flag'=>true,				
				));
				exit;
			}
			else{
				echo CJSON::encode(array(
					'status'=>'error',
					'errors'=>CActiveForm::validate($model),
				));
				exit;
			}
		}	
		
		Yii::app()->clientScript->scriptMap	= array(
			'jquery.js'=>false,				
			'jquery.min.js'=>false					
		);						
		$this->renderPartial('cancel',array('model'=>$model),false,true);		
	}
	
	public function actionView()
	{
		Yii::app()->clientScript->scriptMap['jquery.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
		$this->renderPartial('view',array('id'=>$_REQUEST['id']),false,true);		
	}
}
