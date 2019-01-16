<?php

class StudentAttentanceController extends RController
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
				'actions'=>array('index','view','Addnew','Attentancepdf','Pdf','Attentstud','Pdf1','studentattendancepdf'),
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
		$model=new StudentAttentance;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['StudentAttentance']))
		{
			$model->attributes=$_POST['StudentAttentance'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['StudentAttentance']))
		{
			$model->attributes=$_POST['StudentAttentance'];
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
			throw new CHttpException(400,Yii::t('app','Invalid request. Please do not repeat this request again.'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		
		$dataProvider=new CActiveDataProvider('StudentAttentance');
		/*$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));*/
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	public function actionAddnew() {
        $model=new StudentAttentance;
        // Ajax Validation enabled
        $this->performAjaxValidation($model);
        // Flag to know if we will render the form or try to add 
        // new jon.
        $flag=true;
        if(isset($_POST['StudentAttentance']) and isset($_POST['StudentAttentance']['reason'])) 
        {      
			$flag=false;
            $model->attributes=$_POST['StudentAttentance'];
            $model->batch_id= $_POST['StudentAttentance']['batch_id'];
			if(!$model->validate()) {
          echo CJSON::encode(array(
        'status'=>'error',
       'errors'=>CActiveForm::validate($model)
       ));
     }
     else{
            if($model->save()) 
			{
				
				$student = Students::model()->findByAttributes(array('id'=>$model->student_id));
				$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
				if($settings!=NULL)
				{	
					$date=date($settings->displaydate,strtotime($model->date));			
				}
				
				//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
				ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'8',$model->student_id,ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name),$date,NULL,NULL);
				
				//Mobile Push Notification					
				if(Configurations::model()->isAndroidEnabled()){
					$sender_name	= PushNotifications::model()->getUserName(Yii::app()->user->id);						
					$date           = ($settings!=NULL)?(date($settings->displaydate, strtotime($model->date))):date('Y-m-d', $model->date);    									
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
				
				
              echo CJSON::encode(array(
                        'status'=>'success',
                        ));
             exit;      
  			}
					else
					{
						echo CJSON::encode(array(
								'status'=>'error',
								));
						 exit;    
					}
            }
	    }
		
		if($flag) {
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$this->renderPartial('create',array('model'=>$model,'day'=>$_GET['day'],'month'=>$_GET['month'],'year'=>$_GET['year'],'emp_id'=>$_GET['emp_id'],'bid'=>$_GET['bid']),false,true);
		}
   }
		/*
		edit the marked leave
		*/
		
     public function actionEditLeave()
	 {
        $model=StudentAttentance::model()->findByAttributes(array('id'=>$_REQUEST['id']));
        // Ajax Validation enabled
        $this->performAjaxValidation($model);
        // Flag to know if we will render the form or try to add 
        // new jon.
        $flag=true;
        if(isset($_POST['StudentAttentance']))
        {    
			$old_model = $model->attributes;  
	    	$flag=false;
            $model->attributes=$_POST['StudentAttentance'];
			$reson = $_POST['StudentAttentance']['reason'];
			$leave_type=$_POST['StudentAttentance']['leave_type_id'];
            if($model->save()) 
			{
				$student = Students::model()->findByAttributes(array('id'=>$model->student_id));
				$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
				if($settings!=NULL)
				{	$date=date($settings->displaydate,strtotime($model->date));
			
				}
				
				
				// Saving to activity feed
				$results = array_diff_assoc($_POST['StudentAttentance'],$old_model); // To get the fields that are modified.
				//print_r($old_model);echo '<br/><br/>';print_r($_POST['Students']);echo '<br/><br/>';print_r($results);echo '<br/><br/>'.count($results);echo '<br/><br/>';
				foreach($results as $key => $value)
				{
					if($key != 'date')
					{
						//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
						ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'9',$model->student_id,ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name),$model->getAttributeLabel($key),$date,$value);
					}
					
				}	
				//END saving to activity feed	
				
				//Mobile Push Notification					
				if(Configurations::model()->isAndroidEnabled()){
					$sender_name	= PushNotifications::model()->getUserName(Yii::app()->user->id);						
					$date           = ($settings!=NULL)?(date($settings->displaydate, strtotime($model->date))):date('Y-m-d', $model->date);    				
					
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
				
				
            	echo CJSON::encode(array(
                        'status'=>'success',
                        ));
                 exit;    
            }
			else
			{
				echo CJSON::encode(array(
                        'status'=>'error',
						'reason'=>$reson,
						'leave_type'=>$leave_type
                        ));
                 exit;    
			}
			
        }
	  // var_dump($model->geterrors());
		if($flag) {
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$this->renderPartial('update',array('model'=>$model,'day'=>$_GET['day'],'month'=>$_GET['month'],'year'=>$_GET['year'],'emp_id'=>$_GET['emp_id']),false,true);
		}
     }
		/* Delete the marked leave
		*/
	public function actionDeleteLeave()
	{ 
		if(isset($_REQUEST['id']) and  $_REQUEST['id']!=NULL){			
			$model = StudentAttentance::model()->findByAttributes(array('id'=>$_REQUEST['id']));
			if($model){
				if($model->delete()){
					//Mobile Push Notification					
					if(Configurations::model()->isAndroidEnabled()){
						$sender_name	= PushNotifications::model()->getUserName(Yii::app()->user->id);
						$settings       = UserSettings::model()->findByAttributes(array('user_id'=>1));
						$date           = ($settings!=NULL)?(date($settings->displaydate, strtotime($model->date))):date('Y-m-d', $model->date);    				
						$student = Students::model()->findByAttributes(array('id'=>$model->student_id));
						
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
															
							$argument_arr   =   array('message'=>$message, 'sender_name'=>$admin_name, 'device_id'=>array($value->device_id), 'id'=>'');                
							Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "daywise_attendance");																		
						}										
					}
				}
			}
		}
	
	
	}
	public function actionAttentancepdf()
	{
		//$this->layout='';
		//header("Content-type: image/jpeg");
		//echo $model->photo_data;
		$this->render('printpdf',array(
			'model'=>$this->loadModel($_REQUEST['id']),
		));
	}
	 public function actionPdf()
    {
        
        # HTML2PDF has very similar syntax
        $html2pdf = Yii::app()->ePdf->HTML2PDF();

        $html2pdf->WriteHTML($this->renderPartial('attentancepdf', array('model'=>$this->loadModel($_REQUEST['id'])), true));
        $html2pdf->Output();
 
        ////////////////////////////////////////////////////////////////////////////////////
	}
	 public function actionStudentpdf()
    {
        
        # HTML2PDF has very similar syntax
        $html2pdf = Yii::app()->ePdf->HTML2PDF();

        $html2pdf->WriteHTML($this->renderPartial('studpdf', array('model'=>$this->loadModel($_REQUEST['id'])), true));
        $html2pdf->Output();
 
        ////////////////////////////////////////////////////////////////////////////////////
	}
	public function actionAttentstud()
	{
		//$this->layout='';
		//header("Content-type: image/jpeg");
		//echo $model->photo_data;
		$this->render('printpdf',array(
			'model'=>$this->loadModel($_REQUEST['id']),
		));
	}
	 public function actionPdf1()
    {
        $student = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$filename = $student->first_name.' '.$student->last_name.' Attendance.pdf';
		Yii::app()->osPdf->generate("application.modules.students.views.studentAttentance.attentstud", $filename, array(), 1);
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new StudentAttentance('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['StudentAttentance']))
			$model->attributes=$_GET['StudentAttentance'];

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
		$model=StudentAttentance::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='student-attentance-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	public function actionSubwiseattentance()
	{
		$this->render('subwiseattentance');
	}
	public function actionSubjectwise() { 
		if($_REQUEST['id']){ 
			$model =  StudentSubjectwiseAttentance::model()->findByAttributes(array('id'=>$_REQUEST['id']));
			
		}
		else{
			$model	= new StudentSubjectwiseAttentance;	
		}
		
		if(isset($_POST['StudentSubjectwiseAttentance'])){
			$model->attributes		= $_POST['StudentSubjectwiseAttentance'];
			$model->reason			= $_POST['StudentSubjectwiseAttentance']['reason'];
			$model->leavetype_id	= $_POST['StudentSubjectwiseAttentance']['leavetype_id'];
			$model->date			= date('Y-m-d',strtotime($_POST['StudentSubjectwiseAttentance']['date']));	
			
			if($model->leavetype_id==NULL)
				$model->leavetype_id	= 0;
					
			if($model->save()){
				//Mobile Push Notifications
				$settings			= UserSettings::model()->findByAttributes(array('user_id'=>1));							
				$student			= Students::model()->findByPk($model->student_id);
				$student_id			= $student->id;				
				$subject_name		= StudentSubjectwiseAttentance::model()->getSubjectName($model->timetable_id);
				$class_timing		= StudentSubjectwiseAttentance::model()->getClassTimingLabel($model->timetable_id);
				$sender				= Profile::model()->findByAttributes(array('user_id'=>Yii::app()->user->Id));								
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
		$this->renderPartial('subwise',array('model'=>$model),false,true);		
	}
	public function actionViewsubwise()
	{
		Yii::app()->clientScript->scriptMap['jquery.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
		$this->renderPartial('viewsubwise',array('id'=>$_REQUEST['id']),false,true);		
	}
	public function actionRemove($id)
	{
		if(Yii::app()->request->isPostRequest){	
			$entry = StudentSubjectwiseAttentance::model()->findByPk($id);
			if($entry!=NULL){
				if($entry->delete()){
					//Push Notification								
					$student			= Students::model()->findByPk($entry->student_id);
					$student_id			= $student->id;					
					$subject_name		= StudentSubjectwiseAttentance::model()->getSubjectName($entry->timetable_id);
					$class_timing		= StudentSubjectwiseAttentance::model()->getClassTimingLabel($entry->timetable_id);
					$sender				= Profile::model()->findByAttributes(array('user_id'=>Yii::app()->user->Id));								
					$sender_name		= ucfirst($sender->firstname).' '.ucfirst($sender->lastname);
					$timetable			= TimetableEntries::model()->findByAttributes(array('id'=>$entry->timetable_id));																								
													
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
						$message	= str_replace("{Date}", date($settings->displaydate, strtotime($entry->date)), $message);
														
						 $argument_arr = array('message'=>$message, 'device_id'=>array($value->device_id),'sender_name'=>$sender_name, 'date'=>$entry->date, 'student_id'=>$student_id, 'class_timing_id'=>$timetable->class_timing_id);       
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
						$message	= str_replace("{Date}", date($settings->displaydate, strtotime($entry->date)), $message);
						
						$argument_arr = array('message'=>$message, 'device_id'=>array($value->device_id), 'sender_name'=>$sender_name, 'date'=>$entry->date, 'class_timing_id'=>$timetable->class_timing_id);  
						Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "student_subjectwise_attendance");																		
					}					
				}
			}
			
			$this->redirect(array('studentAttentance/subwiseattentance','id'=>$entry->student_id, 'date'=>$entry->date));
		}
		else
		{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
	}
	public function actionSubwisepdf()
	{
		$student = Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
        $student = strtolower($student->first_name).'-subjectwise-attentance.pdf';
        Yii::app()->osPdf->generate("application.modules.students.views.studentAttentance.subwisepdf", $student, array(),1);
	}
}
