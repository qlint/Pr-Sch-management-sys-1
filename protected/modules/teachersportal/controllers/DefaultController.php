<?php

class DefaultController extends RController
{	
	public function actionIndex()
	{
		$this->render('index');
	}
	
	public function actionEditprofile()
	{
		$model = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		if($model){
			if(isset($_POST['Employees'])){
				$model->attributes	= $_POST['Employees'];
				if(isset($_POST['Employees']['date_of_birth']) and $_POST['Employees']['date_of_birth'] != NULL){
					$model->date_of_birth	= date('Y-m-d', strtotime($_POST['Employees']['date_of_birth']));
				}
				$model->middle_name				= $_POST['Employees']['middle_name'];
				$model->home_address_line1		= $_POST['Employees']['home_address_line1'];
				$model->home_address_line2 		= $_POST['Employees']['home_address_line2'];
				$model->home_city 				= $_POST['Employees']['home_city'];
				$model->home_state 				= $_POST['Employees']['home_state'];
				$model->home_country_id 		= $_POST['Employees']['home_country_id'];
				$model->home_pin_code 			= $_POST['Employees']['home_pin_code'];
				
				$model->office_address_line1 	= $_POST['Employees']['office_address_line1'];
				$model->office_address_line2 	= $_POST['Employees']['office_address_line2'];
				$model->office_city 			= $_POST['Employees']['office_city'];
				$model->office_state 			= $_POST['Employees']['office_state'];
				$model->office_country_id 		= $_POST['Employees']['office_country_id'];
				$model->office_pin_code 		= $_POST['Employees']['office_pin_code'];
				
				$model->office_phone1 			= $_POST['Employees']['office_phone1'];
				$model->office_phone2 			= $_POST['Employees']['office_phone2'];
				$model->mobile_phone 			= $_POST['Employees']['mobile_phone'];
				$model->home_phone 				= $_POST['Employees']['home_phone'];
				$model->email 					= $_POST['Employees']['email'];
				$model->fax 					= $_POST['Employees']['fax'];
				if($model->save()){
					if($model->uid != 0 and $model->uid != NULL){
						$user	= User::model()->findByPk($model->uid);
						if($user){
							$user->email			= $model->email;
							$user->mobile_number	= $model->mobile_phone;
							$user->save();
						}
						
						$profile = Profile::model()->findByAttributes(array('user_id'=>$model->uid));
						if($profile){
							$profile->firstname	= $model->first_name;
							$profile->lastname 	= $model->last_name;
							$profile->save();
						}
					}	
					$this->redirect(array('/teachersportal/default/profile'));				
				}
			}
			$this->render('editprofile', array('model'=>$model));
		}
		else{
			throw new CHttpException(404, Yii::t('app','Invalid Request.'));
		}
	}
	
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	public function actionProfile()
	{
		$this->render('profile');
	}
	public function actionAttendance()
	{
		$this->render('attendance/attendance');
	}
	public function actionEventlist()
	{
		$this->render('eventlist');
	}
	public function actionEmployeeAttendance()
	{
		if(Configurations::model()->teacherAttendanceMode() != 2){
			$this->render('attendance/empattendance');
		}
		else{
			$this->redirect(array('/teachersportal/default/attendance'));
		}
	}
	/*public function actionDay()
	{
		if(Configurations::model()->studentAttendanceMode() != 2){
			$this->render('attendance/studayattendance');
		}
		else{
			$this->redirect(array('/teachersportal/default/attendance'));
		}
	}*/
	public function actionView()
	{
		
		$this->renderPartial('view',array('event_id'=>$_REQUEST['event_id']),false,true);
	}
	public function actionStudentDayAttendance()
	{
		if(Configurations::model()->studentAttendanceMode() != 2){	
			$this->render('attendance/studattendance');
		}
		else{
				$this->redirect(array('/teachersportal/default/subwiseattendance', 'id'=>$_REQUEST['id']));
			}
	}
	public function actionStudentAttendance()
	{ 
		if(isset($_REQUEST['acc_id']) and $_REQUEST['acc_id']!=NULL)
		{
			$acc_year = $_REQUEST['acc_id'];
		}
		else
		{ 
		 	$current_academic_yr  = Configurations::model()->findByAttributes(array('id'=>35));
  			$acc_yr     		  = AcademicYears::model()->findByAttributes(array('id'=>$current_academic_yr->config_value));
			$acc_year 			  = $acc_yr->id;
		}
		if(Configurations::model()->studentAttendanceMode() != 2){ 
		$employee	= Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));		
		$batches = Batches::model()->findAllByAttributes(array('employee_id'=>$employee->id,'academic_yr_id'=>$acc_year, 'is_active'=>1, 'is_deleted'=>0));
		$batch_array=array();
		foreach($batches as $batch)
		{
			$batch_array[]=$batch->id;
		}				
		
		$timetables = TimetableEntries::model()->findAllByAttributes(array('employee_id'=>$employee->id));
		foreach($timetables as $timetable){
			$batch_details = Batches::model()->findByAttributes(array('id'=>$timetable->batch_id,'academic_yr_id'=>$acc_year, 'is_active'=>1,'employee_id'=>$employee->id, 'is_deleted'=>0));
			if($batch_details){
				if(!in_array($timetable->batch_id,$batch_array)){
					$batch_array[] = $timetable->batch_id;
				}
			}
		}
			$this->render('attendance/studayattendance',array('batches_id'=>$batch_array));
		}
		else{ 
			$this->redirect(array('/teachersportal/default/daily', 'bid'=>$_REQUEST['id']));
		}
	}
	public function actionTimetable()
	{
		$this->render('timetable/timetable');
	}
	
	//fixed timetable
	public function actionEmployeeTimetable()
	{
		
		if(isset($_REQUEST['id'])){
			$timetable_format	= Configurations::model()->timetableFormat(isset($_REQUEST['id'])?$_REQUEST['id']:NULL);
			if($timetable_format==2){ // timetable format is flexible
				$this->redirect(array('employeeFlexibleTimetable', 'id'=>$_REQUEST['id']));
			}
		}
			
		$this->render('timetable/emptimetable');
	}
	
	//flexible timetable
	public function actionEmployeeFlexibleTimetable()
	{
		$timetable_format	= Configurations::model()->timetableFormat(isset($_REQUEST['id'])?$_REQUEST['id']:NULL);
		if($timetable_format==1){ // timetable format is fixed
			$this->redirect(array('employeeTimetable', 'id'=>$_REQUEST['id']));
		}
		
		$this->render('timetable/empflexibletimetable');
	}
	
	public function actionEmployeeClassTimetable()
	{
		if(isset($_REQUEST['id'])){
			$timetable_format	= Configurations::model()->timetableFormat(isset($_REQUEST['id'])?$_REQUEST['id']:NULL);
			if($timetable_format==2){ // timetable format is flexible
				$this->redirect(array('employeeFlexibleClassTimetable', 'id'=>$_REQUEST['id']));
			}
		}
		$this->render('timetable/mytimetable');
	}
	public function actionEmployeeFlexibleClassTimetable()
	{
		$timetable_format	= Configurations::model()->timetableFormat(isset($_REQUEST['id'])?$_REQUEST['id']:NULL);
		if($timetable_format==1){ // timetable format is fixed
			$this->redirect(array('employeeClassTimetable', 'id'=>$_REQUEST['id']));
		}
		
		$this->render('timetable/empflexibleclasstimetable');
	}
	
	public function actionStudentTimetable()
	{
		$this->render('timetable/studtimetable');
	}
	public function actionDayTimetable()
	{
		$this->render('timetable/daytimetable',array('model'=>$model));
	}
	public function actionExamination()
	{
		$this->render('examination/examination');
	}
	public function actionAllExam()
	{
		$this->render('examination/examination');
	}
	public function actionClassExam()
	{
		$this->render('examination/examination');
	}
	
	public function actionPdf()
    {
		$timetable_format	= Configurations::model()->timetableFormat(isset($_REQUEST['id'])?$_REQUEST['id']:NULL);
		if($timetable_format==2){ // timetable format is flexible
			$this->redirect(array('employeeflexibletimetablepdf', 'id'=>$_REQUEST['id']));
		}
		
		$batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$batch_name = $batch_name->name.' Class Timetable.pdf';                               
		$filename = $batch_name->name.' Class Timetable.pdf';
		Yii::app()->osPdf->generate("application.modules.teachersportal.views.default.exportpdf", $filename, array(), 1);
        ////////////////////////////////////////////////////////////////////////////////////
	}
	public function actionEmployeeflexibletimetablepdf()
    {
		$timetable_format	= Configurations::model()->timetableFormat(isset($_REQUEST['id'])?$_REQUEST['id']:NULL);
		if($timetable_format==1){ // timetable format is fixed
			$this->redirect(array('pdf', 'id'=>$_REQUEST['id']));
		}
		
        $file_name = ' classTimetable.pdf';
        Yii::app()->osPdf->generate("application.modules.teachersportal.views.default.empflexibletimetablepdf", $file_name, array(), 0, "", "A4", 5, 5, 5, 5);
        ////////////////////////////////////////////////////////////////////////////////////
    }
	public function actionClassPdf()
    {
		$timetable_format	= Configurations::model()->timetableFormat(isset($_REQUEST['id'])?$_REQUEST['id']:NULL);
		if($timetable_format==2){ // timetable format is flexible
			$this->redirect(array('employeeflexibleClasstimetablepdf', 'id'=>$_REQUEST['id']));
		}
		
		$batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$batch_name = $batch_name->name.' Class Timetable.pdf';                               
		$filename = $batch_name->name.' Class Timetable.pdf';
		Yii::app()->osPdf->generate("application.modules.teachersportal.views.default.classexportpdf", $filename, array(), 1);
        ////////////////////////////////////////////////////////////////////////////////////
	}
	
	public function actionEmployeeflexibleClasstimetablepdf()
    {
		$timetable_format	= Configurations::model()->timetableFormat(isset($_REQUEST['id'])?$_REQUEST['id']:NULL);
		if($timetable_format==1){ // timetable format is fixed
			$this->redirect(array('classPdf', 'id'=>$_REQUEST['id']));
		}
		
        $file_name = ' classTimetable.pdf';
        Yii::app()->osPdf->generate("application.modules.teachersportal.views.default.empflexibleclasstimetablepdf", $file_name, array(), 0, "", "A4", 5, 5, 5, 5);
        ////////////////////////////////////////////////////////////////////////////////////
    }
	
	public function actionDayPdf()
        {
		//$batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
	$batch_name = ' Class Day Timetable.pdf';                
                
        $filename = ' Class Day Timetable.pdf';
        Yii::app()->osPdf->generate("application.modules.teachersportal.views.default.daytimetablepdf", $filename, array(),1);       
                
        ////////////////////////////////////////////////////////////////////////////////////
	}
	/*---------PaySlip---------*/
	public function actionPayslip()
	{
		$model=Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		$this->render('payslip',array(
			'model'=>$model,
		));
	}

	/* --------Attendance------- */
	public function actionAddnew() 
	{
            
		$model=new StudentAttentance;
		$model1=new StudentLeaveTypes;
        // Ajax Validation enabled
        $this->performAjaxValidation($model);
        // Flag to know if we will render the form or try to add 
        // new jon.
        $flag=true;
        if(isset($_POST['StudentAttentance']))
		{   
			$flag=false;
			$model->attributes=$_POST['StudentAttentance'];
			$model->batch_id= $_POST['StudentAttentance']['batch_id'];
			$model->leave_type_id = $_POST['StudentAttentance']['leave_type_id'];
			if(!$model->validate()) {
				  echo CJSON::encode(array(
				'status'=>'error',
			   'errors'=>CActiveForm::validate($model)
			   ));
			 }
			 
           
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
         }
		if($flag) 
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$this->renderPartial('attendance/create',array('model'=>$model,'model1'=>$model1,'day'=>$_GET['day'],'month'=>$_GET['month'],'year'=>$_GET['year'],'emp_id'=>$_GET['emp_id'],'batch_id'=>$_GET['batch_id']),false,true);
		}
	}
	
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='student-attentance-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionEditLeave()
	{
		
		$model=StudentAttentance::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$model1=StudentLeaveTypes::model()->findByAttributes(array('id'=>$model->leave_type_id,'status'=>1));
		
		// Ajax Validation enabled
		//$this->performAjaxValidation($model);
		// Flag to know if we will render the form or try to add 
		// new jon.
		$flag=true;
		if(isset($_POST['StudentAttentance']))
		{  
			$reason =  $_POST['StudentAttentance']['reason'];
			$leave_type = $_POST['StudentAttentance']['leave_type_id'];
			$old_model = $model->attributes;      
			$flag=false;
			$model->attributes=$_POST['StudentAttentance'];
			  $model->batch_id= $_POST['StudentAttentance']['batch_id'];
			  $model->leave_type_id = $_POST['StudentAttentance']['leave_type_id'];
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
						'reason'=>$reason,
						'leave_type'=>$leave_type
                        ));
                 exit;    
			}
			
		}
		// var_dump($model->geterrors());
		if($flag) 
		{
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
		  
		   
			$this->renderPartial('attendance/update',array('model'=>$model,'model1'=>$model1,'day'=>$_GET['day'],'month'=>$_GET['month'],'year'=>$_GET['year'],'emp_id'=>$_GET['emp_id'],'batch_id'=>$_GET['batch_id']),false,true);
		}
	}
	
	public function actionDeleteLeave()
	{
		$flag=true;
		$model=StudentAttentance::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		if($model->delete())
		{
			$flag=false;
			$student = Students::model()->findByAttributes(array('id'=>$model->student_id));
			$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
			if($settings!=NULL)
			{	
				$date=date($settings->displaydate,strtotime($model->date));
			}
		
		//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
		ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'10',$model->student_id,ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name),$date,NULL,NULL);
		
			//Mobile Push Notification					
			if(Configurations::model()->isAndroidEnabled()){
				$sender_name	= PushNotifications::model()->getUserName(Yii::app()->user->id);
				$settings       = UserSettings::model()->findByAttributes(array('user_id'=>1));
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
													
					$argument_arr   =   array('message'=>$message, 'sender_name'=>$admin_name, 'device_id'=>array($value->device_id), 'id'=>'');                
					Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "daywise_attendance");																		
				}										
			}
		
		
		}
		
		 if($flag) {
                   
				Yii::app()->clientScript->scriptMap['jquery.js'] = false;
				$this->renderPartial('update',array('model'=>$model,'day'=>$_GET['day'],'month'=>$_GET['month'],'year'=>$_GET['year'],'emp_id'=>$_GET['emp_id']),false,true);
					
		}			  
	
	}
	
	/* --------Attendance End------- */
	
	/*--------- Scores ------------*/
	
	public function actionAddscores()
	{
		
		$model=new ExamScores;

		if(isset($_POST['ExamScores']))
		{
			
			$list = $_POST['ExamScores'];
			$count = count($list['student_id']);
			
			for($i=0;$i<$count;$i++)
			{
				if($list['marks'][$i]!=NULL or $list['remarks'][$i]!=NULL)
				{
					$exam=Exams::model()->findByAttributes(array('id'=>$list['exam_id']));
					$model=new ExamScores;
						
					$model->exam_id = $list['exam_id']; 
					$model->student_id = $list['student_id'][$i];
					$model->marks = $list['marks'][$i];
					$model->remarks = $list['remarks'][$i];
					$model->grading_level_id = $list['grading_level_id'];
				
					if(($list['marks'][$i])< ($exam->minimum_marks)) 
					{
						$model->is_failed = 1;
					}
					else 
					{
						$model->is_failed = '';
					}
					$model->created_at = $list['created_at'];
					$model->updated_at = $list['updated_at'];
					//$model->save();
					if($model->save())
					{
						$student = Students::model()->findByAttributes(array('id'=>$model->student_id));
						$student_name = ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);
						$subject_name = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
						if($subject_name!=NULL)
						{
							$examgroup = ExamGroups::model()->findByAttributes(array('id'=>$exam->exam_group_id));
							$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
							$exam = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
							$goal_name = $student_name.' '.Yii::t('app', 'for the exam').' '.$exam;
						}
						else
						{
							$goal_name = $student_name;
						}
						
						
						
						//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
						ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'20',$model->id,$goal_name,NULL,NULL,NULL); 
					}
				}
			}
				
				if($_REQUEST['allexam']==1){
					$url = 'default/allexam';
				}
				else{
					$url = 'default/classexam';
				}
				$this->redirect(array($url,'bid'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id'],'r_flag'=>$_REQUEST['r_flag'],'exam_id'=>$_REQUEST['exam_id']));
		   }
			
		$this->render('examination',array(
			'model'=>$model,
		));
	
	}
	
	public function actionDeleteall()
	{
		
		$delete = ExamScores::model()->findAllByAttributes(array('exam_id'=>$_REQUEST['exam_id']));
		foreach($delete as $delete1)
		{
			$delete1->delete();
		}
		
		if($_REQUEST['allexam']==1){
					$url = 'default/allexam';
		}
		else{
			$url = 'default/classexam';
		}
			$this->redirect(array($url,'bid'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id'],'r_flag'=>$_REQUEST['r_flag'],'exam_id'=>$_REQUEST['exam_id']));
		
	}
	
	public function actionDelete($id)
	{
		$delete = ExamScores::model()->findByAttributes(array('id'=>$id));
		
		
		//$model = ExamScores::model()->findByAttributes(array('id'=>$id));
			
		$student = Students::model()->findByAttributes(array('id'=>$delete->student_id));
		$student_name = ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);
		
		$exam = Exams::model()->findByAttributes(array('id'=>$delete->exam_id));
		$subject_name = Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
		$examgroup = ExamGroups::model()->findByAttributes(array('id'=>$exam->exam_group_id));
		$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
		$exam_name = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
		$goal_name = $student_name.' for the exam '.$exam_name;
		
		$delete->delete();
		
		//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
		ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'22',$delete->id,$goal_name,NULL,NULL,NULL); 
		
		
		
		
		
		
		if($_REQUEST['allexam']==1){
			$url = 'default/allexam';
		}
		else{
			$url = 'default/classexam';
		}
		$this->redirect(array($url,'bid'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id'],'r_flag'=>$_REQUEST['r_flag'],'exam_id'=>$_REQUEST['exam_id']));
		
	}
	public function actionEmployeepicupload($id)
	{		
		$model = Employees::model()->findByAttributes(array('id'=>$id));
		$file_name = DocumentUploads::model()->getFileName($_FILES["file"]["name"]);							
		//Save the profile pic to the folder
		if($model){	
			if($file_name!=NULL){
				if(!is_dir('uploadedfiles/')){
					mkdir('uploadedfiles/');
				}
				if(!is_dir('uploadedfiles/employee_profile_image/')){
					mkdir('uploadedfiles/employee_profile_image/');
				}
				if(!is_dir('uploadedfiles/employee_profile_image/'.$model->id)){
					mkdir('uploadedfiles/employee_profile_image/'.$model->id);
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
				$destination_file = 'uploadedfiles/employee_profile_image/'.$model->id.'/'.$file_name;
				imagejpeg($image, $destination_file, 30);
				
				//Insert Data in document_uploads table
				DocumentUploads::model()->insertData(2, $model->id, $file_name, 4, NULL, NULL, NULL, 0);  						
			}
		}		
		return;
	}
	
	public function actionUpdate($id)
	{
		
		$model=ExamScores::model()->findByAttributes(array('id'=>$id));
		$old_model = $model->attributes; // For activity feed	

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['ExamScores']))
		{
			$model->attributes=$_POST['ExamScores'];
			$exam=Exams::model()->findByAttributes(array('id'=>$_REQUEST['exam_id']));
			if($model->marks < $exam->minimum_marks){
				$model->is_failed = 1;
			}
			else 
			{
					$model->is_failed = '';
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
						$examgroup = ExamGroups::model()->findByAttributes(array('id'=>$exam->exam_group_id));
						$batch = Batches::model()->findByAttributes(array('id'=>$examgroup->batch_id));
						$exam_name = ucfirst($subject_name->name).' - '.ucfirst($examgroup->name).' ('.ucfirst($batch->name).'-'.ucfirst($batch->course123->course_name).')';
						$goal_name = $student_name.' for the exam '.$exam_name;
						
						if($key=='is_failed')
						{
							if($value == 1)
							{
								$value = 'Fail';
							}
							else
							{
								$value = 'Pass';
							}
							
							if($old_model[$key] == 1)
							{
								$old_model[$key] = 'Fail';
							}
							else
							{
								$old_model[$key] = 'Pass';
							}
						}
						
						//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
						ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'21',$model->id,$goal_name,$model->getAttributeLabel($key),$old_model[$key],$value); 
					}
				}
				//END saving to activity feed
				
				if($_REQUEST['allexam']==1){
					$url = 'default/allexam';
				}
				else{
					$url = 'default/classexam';
				}
				
				$this->redirect(array($url,'bid'=>$_REQUEST['bid'],'exam_group_id'=>$_REQUEST['exam_group_id'],'r_flag'=>$_REQUEST['r_flag'],'exam_id'=>$_REQUEST['exam_id']));
			}
		}
		
		$this->render('examination/examination',array(
			'model'=>$model,
		));
	}
	
	/*------- Scores End -------------*/
	
	
	/*
	* For adding documents
	*/
	
	
	public function actionDocument()
	{
		//echo $_POST['EmployeeDocument']['sid'];exit;
		$model=new EmployeeDocument;
		$flag = 1;
		$valid_file_types = array('image/jpeg','image/png','application/pdf','application/msword','text/plain'); // Creating the array of valid file types
		$files_not_saved = '';
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['EmployeeDocument']))
		{
			//var_dump($_POST['EmployeeDocument']);exit;
			
			$list = $_POST['EmployeeDocument'];
			$no_of_documents = count($list['title']); // Counting the number of files uploaded (No of rows in the form)
			for($i=0;$i<$no_of_documents;$i++) //Iterating the documents uploaded
			{
				$file = $_FILES['EmployeeDocument']['name']['file'][$i];
				$file_name = DocumentUploads::model()->getFileName($file);
				
				$model=new EmployeeDocument;
				$model->employee_id = $_POST['EmployeeDocument']['employee_id'][$i];
				$model->title = $_POST['EmployeeDocument']['title'][$i];
				$extension = end(explode('.',$_FILES['EmployeeDocument']['name']['file'][$i])); // Get extension of the file
				$model->file = $file_name;
				$model->file_type = $_FILES['EmployeeDocument']['type']['file'][$i];
				$model->is_approved = 0;
				$model->uploaded_by = Yii::app()->user->Id;
				$file_size = $_FILES['EmployeeDocument']['size']['file'][$i];
				if($model->employee_id!='' and $model->title!='' and $model->file!='' and $model->file_type!='') // Checking if Document name and file is uploaded
				{
					if(in_array($model->file_type,$valid_file_types)) // Checking file type
					{
						
						if($file_size <= 5242880) // Checking file size
						{
							if(!is_dir('uploadedfiles/')) // Creating uploaded file directory
							{
								mkdir('uploadedfiles/');
							}
							if(!is_dir('uploadedfiles/employee_document/')) // Creating employee_document directory
							{
								mkdir('uploadedfiles/employee_document/');
							}
							if(!is_dir('uploadedfiles/employee_document/'.$model->employee_id)) // Creating student directory for saving the files
							{
								mkdir('uploadedfiles/employee_document/'.$model->employee_id);
							}
							$temp_file_loc = $_FILES['EmployeeDocument']['tmp_name']['file'][$i];
							$destination_file = 'uploadedfiles/employee_document/'.$model->employee_id.'/'.$file_name;
							if(move_uploaded_file($temp_file_loc,$destination_file)) // Saving the files to the folder
							{
								
							
								if($model->save()) // Saving the model to database
								{
									
									DocumentUploads::model()->insertData(4, $model->id, $file_name, 3);	
									$flag = 1;
										
								}
								else // If model not saved
								{
									$flag = 0;
									if(file_exists($destination_file))
									{
										unlink($destination_file);
									}
									$files_not_saved = $files_not_saved.', '.$file_name;
									Yii::app()->user->setFlash('errorMessage', Yii::t('app', "File(s) ".$files_not_saved." was not saved. Please try again."));
									continue;
								}
								
							}
							else // If file not saved to the directory
							{
								$flag = 0;
								$files_not_saved = $files_not_saved.', '.$model->file;
								Yii::app()->user->setFlash('errorMessage', Yii::t('app', "File(s) ".$files_not_saved." was not saved. Please try again."));
								continue;
							}
						}
						else // If file size is too large. Greater than 5 MB
						{
							$flag = 0;
							Yii::app()->user->setFlash('errorMessage', Yii::t('app', "File size must not exceed 5MB!"));
						}
					}
					else // If file type is not valid
					{
						$flag = 0;
						Yii::app()->user->setFlash('errorMessage', Yii::t('app', "Only files with these extensions are allowed:")." jpg, png, pdf, doc, txt.");
					}
				}
				elseif($model->title=='' and $model->file_type!='') // If document name is empty
				{
					$flag = 0;
					Yii::app()->user->setFlash('errorMessage',Yii::t('app', "Document Name cannot be empty!"));
					//$this->redirect(array('create','model'=>$model,'id'=>$_REQUEST['id']));
				}
				elseif($model->title!='' and $model->file_type=='') // If file is not selected
				{

					$flag = 0;
					Yii::app()->user->setFlash('errorMessage', Yii::t('app', "File is not selected!"));
					
				}
				elseif($model->employee_id=='' and $model->title=='' and $model->file=='' and $model->file_type=='')
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
		if(Yii::app()->request->isPostRequest)
		{
		
			$model = EmployeeDocument::model()->findByAttributes(array('id'=>$_REQUEST['id']));
			$document_upload_model = DocumentUploads::model()->findByAttributes(array('model_id'=>4, 'file_id'=>$model->id, 'file_name'=>$model->file));
			$destination_file = 'uploadedfiles/employee_document/'.$model->employee_id.'/'.$model->file;
			if(file_exists($destination_file))
			{
				if(unlink($destination_file))
				{
					if($model->delete())
					{
						$document_upload_model->delete();
					}
					Yii::app()->user->setFlash('successMessage', Yii::t('app', "Document deleted successfully!"));	
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
		$model = EmployeeDocument::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$file_path = 'uploadedfiles/employee_document/'.$model->employee_id.'/'.$model->file;
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
		
		$model= EmployeeDocument::model()->findByAttributes(array('id'=>$_REQUEST['document_id'])); //Here $_REQUEST['id'] is student ID and $_REQUEST['document_id'] is document ID
		$old_model = $model->attributes;
		$old_file_name = $model->file;
		//var_dump($old_model);exit;
		$flag = 1; // If 1, no errors. If 0, some error is present.
		$valid_file_types = array('image/jpeg','image/png','application/pdf','application/msword','text/plain'); // Creating the array of valid file types
		
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['EmployeeDocument']))
		{
			$list = $_POST['EmployeeDocument'];
			$model->employee_id = $list['employee_id'];
			$model->title = $list['title'];
			
			if(($model->title != $old_model['file']) or ($_FILES['EmployeeDocument']['name']['file']!=NULL))
			{
				//echo 'dfsd';exit;
				$model->is_approved = 0;
			}
			if($model->title!=NULL and $model->employee_id!=NULL)
			{
				if($_FILES['EmployeeDocument']['name']['file']!=NULL)
				{
					$file = $_FILES['EmployeeDocument']['name']['file'];
					$file_name = DocumentUploads::model()->getFileName($file);
					
					$extension = end(explode('.',$_FILES['EmployeeDocument']['name']['file'])); // Get extension of the file
					$model->file = $file_name;
					
					$model->file_type = $_FILES['EmployeeDocument']['type']['file'];
					$file_size = $_FILES['EmployeeDocument']['size']['file'];
					if(in_array($model->file_type,$valid_file_types)) // Checking file type
					{
						if($file_size <= 5242880) // Checking file size
						{
							if(!is_dir('uploadedfiles/')) // Creating uploaded file directory
							{
								mkdir('uploadedfiles/');
							}
							if(!is_dir('uploadedfiles/employee_document/')) // Creating employee_document directory
							{
								mkdir('uploadedfiles/employee_document/');
							}
							if(!is_dir('uploadedfiles/employee_document/'.$model->employee_id)) // Creating student directory for saving the files
							{
								mkdir('uploadedfiles/employee_document/'.$model->employee_id);
							}
							$temp_file_loc = $_FILES['EmployeeDocument']['tmp_name']['file'];
							$destination_file = 'uploadedfiles/employee_document/'.$model->employee_id.'/'.$file_name;
							
							if(move_uploaded_file($temp_file_loc,$destination_file)) // Saving the files to the folder
							{
								$flag = 1;
								
							}
							else // If file not saved to the directory
							{
								$flag = 0;								
								Yii::app()->user->setFlash('errorMessage', Yii::t('app', "File")." ".$file_name." ".Yii::t('app', "was not saved. Please try again."));
							}
						}
						else // If file size is too large. Greater than 5 MB
						{
							$flag = 0;
							Yii::app()->user->setFlash('errorMessage', Yii::t('app', "File size must not exceed 5MB!"));
						}
					}
					else // If file type is not valid
					{
						$flag = 0;
						Yii::app()->user->setFlash('errorMessage', Yii::t('app', "Only files with these extensions are allowed:")." jpg, png, pdf, doc, txt.");
						
					}
				}
				else // No files selected
				{
					if($old_model['file']!=NULL and $list['new_file_field']==1)
					{
						$flag = 0;
						Yii::app()->user->setFlash('errorMessage', Yii::t('app', "No file selected!"));
					}
					
				}
			}
			else // No title entered
			{
				$flag = 0;
				Yii::app()->user->setFlash('errorMessage', Yii::t('app', "Document Name cannot be empty!"));
			}
			
			
			if($flag == 1) // Valid data
			{ 
				if($model->save())
				{
					if($_FILES['EmployeeDocument']['name']['file']!=NULL)
					{
						DocumentUploads::model()->insertData(4, $model->id, $file_name, 3, $old_file_name);		
						
						$old_destination_file = 'uploadedfiles/employee_document/'.$model->employee_id.'/'.$old_model['file'];	
						if(file_exists($old_destination_file))
						{
							unlink($old_destination_file);
						}
					}
					$this->redirect(array('profile'));
				}
				else
				{
					
					Yii::app()->user->setFlash('errorMessage', Yii::t('app', "Cannot update the document now. Try again later."));
					$this->redirect(array('documentupdate','id'=>$model->employee_id,'document_id'=>$_REQUEST['document_id']));
				}
					
			}
			else
			{
				$this->redirect(array('documentupdate','id'=>$model->employee_id,'document_id'=>$_REQUEST['document_id']));
				/*$this->render('update',array(
					'model'=>$model,'employee_id'=>$_REQUEST['id']
				));*/
				
			}
		}

		$this->render('documents/documentupdate',array(
			'model'=>$model,'employee_id'=>$_REQUEST['id']
		));
	
	}
	public function actionAchievements()
	{
		$this->render('achievements');
	}
	public function actionAchievementDownload()
	{
		$model=Achievements::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$file_path = 'uploadedfiles/employee_achievement_document/'.$model->user_id.'/'.$model->file;
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
	
	public function actionViewelective()
	{
		Yii::app()->clientScript->scriptMap['jquery.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
		
		$this->renderPartial('timetable/viewelective',array('id'=>$_REQUEST['id']),false,true);		
	}
	
	public function actionSubwiseattendance()
	{
		$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		$batches = Batches::model()->findAllByAttributes(array('employee_id'=>$employee->id));
		$batch_array=array();
		foreach($batches as $batch)
		{
			$batch_array[]=$batch->id;
		}
		
		
		$timetables = TimetableEntries::model()->findAllByAttributes(array('employee_id'=>$employee->id));
		foreach($timetables as $timetable)
		{
			if(!in_array($timetable->batch_id,$batch_array))
				$batch_array[]=$timetable->batch_id;
		}
		
		$this->render('subwiseattendance',array('batches_id'=>$batch_array));
	}
	public function actionStudentname()
	{	echo 'aa';exit;		
		$data=Students::model()->findAllByAttributes(array('batch_id'=>$_POST['bid']));
		echo CHtml::tag('option', array('value' => 0), CHtml::encode(Yii::t('app','Select Student')), true);
		$data=CHtml::listData($data,'id','stud');
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option',array('value'=>$value),CHtml::encode($name),true);
		}
	}
	public function actionSubwisepdf()
	{
		$student = Students::model()->findByAttributes(array('id'=>$_REQUEST['student_id']));
        $student = strtolower($student->first_name).'-subjectwise-attentance.pdf';
        Yii::app()->osPdf->generate("application.modules.teachersportal.views.default.subwisepdf", $student, array(),1);
	}
	
	//Actions Related to teacher subject wise attendance
	public function actionteachersubwise()
	{
		if(Configurations::model()->teacherAttendanceMode() != 1){
			$this->render('teachersubwise');
		}
		else{
			$this->redirect(array('/teachersportal/default/attendance'));
		}
			
	}
	
	public function actionTeachersubwisePdf(){
		$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));			
        $employee = strtolower($employee->first_name).'-subjectwise-attentance.pdf';
        Yii::app()->osPdf->generate("application.modules.teachersportal.views.default.teachersubwisePdf", $employee, array(), 1);
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
			
			if($model->leavetype_id == NULL)
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
			$entry 		= StudentSubjectwiseAttentance::model()->findByPk($id);
			$student 	= Students::model()->findByAttributes(array('id'=>$entry->student_id));
			$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
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
			
			$this->redirect(array('default/subwiseattendance','student_id'=>$entry->student_id, 'bid'=>$student->batch_id, 'date'=>$entry->date));
		}
		else
		{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
	}
	// subject wise attendance - daily
	public function actionDaily()
	{
		$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		$batches = Batches::model()->findAllByAttributes(array('employee_id'=>$employee->id));
		$batch_array=array();
		foreach($batches as $batch)
		{
			$batch_array[]=$batch->id;
		}
		
		
		$timetables = TimetableEntries::model()->findAllByAttributes(array('employee_id'=>$employee->id));
		foreach($timetables as $timetable)
		{
			if(!in_array($timetable->batch_id,$batch_array))
				$batch_array[]=$timetable->batch_id;
		}
		if(Configurations::model()->studentAttendanceMode() != 1){ 
			$this->render('daily',array('batches_id'=>$batch_array));
		}
		else{
			$this->redirect(array('default/studentattendance','id'=>$_REQUEST['bid']));
		}
	}
	public function actionStatus(){
		$done			= false;		
		$timetable_id	= $_POST['timetable_id'];
		$student_id		= $_POST['student_id'];
		$subject_id		= $_POST['subject_id'];
		$weekday_id		= $_POST['weekday_id'];
		$date			= $_POST['date'];
		
		$attendance		= StudentSubjectwiseAttentance::model()->findByAttributes(array('timetable_id'=>$timetable_id, 'student_id'=>$student_id, 'subject_id'=>$subject_id, 'weekday_id'=>$weekday_id, 'date'=>$date));
		$settings		= UserSettings::model()->findByAttributes(array('user_id'=>1));
		if($attendance==NULL){
			$attendance		= new StudentSubjectwiseAttentance('daily');
			$attendance->timetable_id	= $timetable_id;
			$attendance->student_id		= $student_id;
			$attendance->subject_id		= $subject_id;
			$attendance->weekday_id		= $weekday_id;
			$attendance->date			= $date;
			if($attendance->save()){
				//Mobile Push Notifications							
				$student			= Students::model()->findByPk($student_id);				
				$subject_name		= StudentSubjectwiseAttentance::model()->getSubjectName($attendance->timetable_id);
				$class_timing		= StudentSubjectwiseAttentance::model()->getClassTimingLabel($attendance->timetable_id);
				$sender				= Profile::model()->findByAttributes(array('user_id'=>Yii::app()->user->Id));								
				$sender_name		= ucfirst($sender->firstname).' '.ucfirst($sender->lastname);
				$timetable			= TimetableEntries::model()->findByAttributes(array('id'=>$attendance->timetable_id));																									
												
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
					$message	= str_replace("{Date}", date($settings->displaydate, strtotime($attendance->date)), $message);
													
					 $argument_arr = array('message'=>$message, 'device_id'=>array($value->device_id),'sender_name'=>$sender_name, 'date'=>$attendance->date, 'student_id'=>$student_id, 'class_timing_id'=>$timetable->class_timing_id);       
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
					$message	= str_replace("{Date}", date($settings->displaydate, strtotime($attendance->date)), $message);
					
					$argument_arr = array('message'=>$message, 'device_id'=>array($value->device_id), 'sender_name'=>$sender_name, 'date'=>$attendance->date, 'class_timing_id'=>$timetable->class_timing_id);  
					Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "student_subjectwise_attendance");																		
				}					
				
				$link	= $this->renderPartial('_link', array('id'=>$attendance->id), true, true);
				$done	= true;
			}
		}
		else{
			if($attendance->delete()){
				//Push Notification						
				$student			= Students::model()->findByPk($student_id);				
				$subject_name		= StudentSubjectwiseAttentance::model()->getSubjectName($attendance->timetable_id);
				$class_timing		= StudentSubjectwiseAttentance::model()->getClassTimingLabel($attendance->timetable_id);
				$sender				= Profile::model()->findByAttributes(array('user_id'=>Yii::app()->user->Id));								
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
					$message	= str_replace("{Date}", date($settings->displaydate, strtotime($attendance->date)), $message);
													
					 $argument_arr = array('message'=>$message, 'device_id'=>array($value->device_id),'sender_name'=>$sender_name, 'date'=>$attendance->date, 'student_id'=>$student_id, 'class_timing_id'=>$timetable->class_timing_id);       
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
					$message	= str_replace("{Date}", date($settings->displaydate, strtotime($attendance->date)), $message);
					
					$argument_arr = array('message'=>$message, 'device_id'=>array($value->device_id), 'sender_name'=>$sender_name, 'date'=>$attendance->date, 'class_timing_id'=>$timetable->class_timing_id);  
					Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "student_subjectwise_attendance");																		
				}					
				
				$link	= $this->renderPartial('_link', array(
					'timetable_id'=>$timetable_id,
					'student_id'=>$student_id,
					'subject_id'=>$subject_id,
					'weekday_id'=>$weekday_id,
					'date'=>$date
				), true, true);
				$done	= true;
			}
		}
		
		if($done)
			echo json_encode(array('status'=>'success', 'link'=>$link));
		else
			echo json_encode(array('status'=>'error', 'errors'=>$attendance->getErrors()));
	}
	public function actionMark() { 
		if($_REQUEST['id']){
			$model 	=  StudentSubjectwiseAttentance::model()->findByAttributes(array('id'=>$_REQUEST['id']));			
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
		else{	
			Yii::app()->clientScript->scriptMap	= array(
				'jquery.js'=>false,				
				'jquery.min.js'=>false					
			);						
			$this->renderPartial('mark-attendance',array('model'=>$model),false,true);	
		}
	}
	public function actionDailyPdf(){
		$batch 		= Batches::model()->findByPk($_REQUEST['batch']);
        $filename 	= strtolower($batch->name).'-daily-subjectwise-attentance.pdf';
        Yii::app()->osPdf->generate("application.modules.teachersportal.views.default.daily_pdf", $filename, array(),1);
	}
	//Manage Day wise attendance
	public function actionMarkDayAttendance()
	{		
		$student_id	= $_POST['student_id'];
		$batch_id	= $_POST['batch_id'];
		$date		= $_POST['date'];
		$type		= $_POST['type'];
		$model	    = StudentAttentance::model()->findByAttributes(array('student_id'=>$student_id, 'batch_id'=>$batch_id, 'date'=>$date));
		
		if($type == 1){ //check whether the request is to mark present
			if($model != NULL){							
				if($model->delete()){
					//Mobile Push Notification					
					if(Configurations::model()->isAndroidEnabled()){
						$sender_name	= PushNotifications::model()->getUserName(Yii::app()->user->id);
						$settings       = UserSettings::model()->findByAttributes(array('user_id'=>1));
                        $date           = ($settings!=NULL)?(date($settings->displaydate, strtotime($model->date))):date('Y-m-d', $model->date);    
						$student		= Students::model()->findByPk($student_id);
						
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
		else if($type == 2){
			if($model == NULL){				
				$model					= new StudentAttentance();
				//$model->scenario		= 'mark_day_attendance';
				$model->setScenario('mark_day_attendance');
				$model->student_id 		= $student_id;
				$model->batch_id 		= $batch_id;
				$model->date 			= $date;
				if($model->save()){
					//Mobile Push Notification					
					if(Configurations::model()->isAndroidEnabled()){
						$sender_name	= PushNotifications::model()->getUserName(Yii::app()->user->id);
						$settings       = UserSettings::model()->findByAttributes(array('user_id'=>1));
                        $date           = ($settings!=NULL)?(date($settings->displaydate, strtotime($model->date))):date('Y-m-d', $model->date);    
						$student		= Students::model()->findByPk($student_id);
						
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
				}								
			}
		}		
		echo $type;
	}
	public function actionUpdateDayAttendance()
	{			
		$model 				= StudentAttentance::model()->findByAttributes(array('student_id'=>$_REQUEST['student_id'], 'batch_id'=>$_REQUEST['batch_id'], 'date'=>$_REQUEST['date']));	
		if($model == NULL){
			$model	= new StudentAttentance;
		}
		$model->scenario	= 'update_day_attendance';	
		if(isset($_POST['StudentAttentance'])){			
			$model->attributes		= $_POST['StudentAttentance'];
			$model->student_id		= $_REQUEST['student_id'];
			$model->batch_id		= $_REQUEST['batch_id'];
			$model->date			= date('Y-m-d', strtotime($_REQUEST['date']));
			$model->reason			= $_POST['StudentAttentance']['reason'];
			$model->leave_type_id	= $_POST['StudentAttentance']['leave_type_id'];			
			if($model->save()){
				//Mobile Push Notification					
				if(Configurations::model()->isAndroidEnabled()){
					$sender_name	= PushNotifications::model()->getUserName(Yii::app()->user->id);
					$settings       = UserSettings::model()->findByAttributes(array('user_id'=>1));
					$date           = ($settings!=NULL)?(date($settings->displaydate, strtotime($model->date))):date('Y-m-d', $model->date);    
					$student		= Students::model()->findByPk($model->student_id);
					
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
		else{		
			Yii::app()->clientScript->scriptMap	= array(
				'jquery.js'=>false,				
				'jquery.min.js'=>false					
			);						
			$this->renderPartial('attendance/mark-day-attendance',array('model'=>$model),false,true);
		}
	}
	public function actionStuDayPdf()
     {
		$batch 		= Batches::model()->findByAttributes(array('id'=>$_REQUEST['batch']));
		$id = $_REQUEST['batch'];
		
		$filename	= $batch->name.' Daily Student Attendance.pdf';
		Yii::app()->osPdf->generate("application.modules.teachersportal.views.default.attendance.studaypdf", $filename, array(), 1);
        ////////////////////////////////////////////////////////////////////////////////////
	}
	public function actionStudentDayPdf()
     {
		$batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$filename	= $batch_name->name.' Student Attendance.pdf';
		Yii::app()->osPdf->generate("application.modules.teachersportal.views.default.attendance.studentdaypdf", $filename, array(), 1);
        ////////////////////////////////////////////////////////////////////////////////////
	}
	
}
