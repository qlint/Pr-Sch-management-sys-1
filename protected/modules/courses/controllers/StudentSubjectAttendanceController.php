<?php

class StudentSubjectAttendanceController extends RController
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
	 * Lists all models.
	 */
	public function actionIndex()
	{
		Yii::app()->clientScript->scriptMap		= array(
			'jquery.js' => false,
			'jquery.min.js' => false
		);
		
		if(Configurations::model()->studentAttendanceMode() != 1){
			$this->render('index');
		}
		else{
			$this->redirect(array('/courses/studentAttentance', 'id'=>$_REQUEST['id']));
		}
	}	
	
	public function actionMark(){ 
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
		
		Yii::app()->clientScript->scriptMap	= array(
			'jquery.js'=>false,				
			'jquery.min.js'=>false					
		);						
		$this->renderPartial('mark-attendance',array('model'=>$model),false,true);		
	}
	
	public function actionView(){
		Yii::app()->clientScript->scriptMap['jquery.js'] = false;
		Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
		$this->renderPartial('view-attendance',array('id'=>$_REQUEST['id']),false,true);		
	}
	
	public function actionRemove($id, $bid, $stud_id, $date){
		if(Yii::app()->request->isPostRequest){
			$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
			$entry 		= StudentSubjectwiseAttentance::model()->findByPk($id);
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
			$this->redirect(array('index', 'id'=>$bid, 'stud_id'=>$stud_id, 'date'=>$date));
		}
		else{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
	}
	
	public function actionPdf(){
		$student = Students::model()->findByAttributes(array('id'=>$_REQUEST['stud_id']));
        $student = strtolower($student->first_name).'-subjectwise-attentance.pdf';
        Yii::app()->osPdf->generate("application.modules.attendance.views.studentSubjectAttendance.pdf", $student, array(), 0, 5, 5, 5, 5);
	}
	public function actionDaily()
	{
		if(Configurations::model()->studentAttendanceMode() != 1){
			
			$this->render('daily');
		}
		else{
			$this->redirect(array('/courses/studentAttentance', 'id'=>$_REQUEST['id']));
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
			//$attendance->setScenario('daily'); 
			//$attendance->scenario 		= 'daily';	// scenario			
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
	public function actionDailyPdf(){
		$batch 		= Batches::model()->findByPk($_REQUEST['batch']);
        $filename 	= strtolower($batch->name).'-daily-subjectwise-attentance.pdf';
        Yii::app()->osPdf->generate("application.modules.courses.views.studentSubjectAttendance.daily_pdf", $filename, array(),1);
	}
		
}
