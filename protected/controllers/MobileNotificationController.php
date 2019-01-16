<?php

class MobileNotificationController extends RController
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

	public function actionIndex()
	{
		if(!empty($_POST)){							
			if(count($_POST['notification_number']) > 0){
				
				for($i = 0; $i < count($_POST['notification_number']); $i++){
					if($_POST['id'][$i] != NULL){
						$model	= PushNotifications::model()->findByPk($_POST['id'][$i]);
						if($_POST['title'][$i] != NULL and $_POST['message'][$i] != NULL){
							$model->title	= $_POST['title'][$i];
							$model->message	= $_POST['message'][$i];
							$model->save();
						}
						else{
							$model->delete();
						}
					}
					else{
						if($_POST['title'][$i] != NULL and $_POST['message'][$i] != NULL){
							$model						= new PushNotifications;
							$model->notification_number	= $_POST['notification_number'][$i];
							$model->type				= $_POST['type'][$i];
							$model->description			= $_POST['description'][$i];
							$model->language			= $_POST['language'][$i];
							$model->title				= $_POST['title'][$i];
							$model->message				= $_POST['message'][$i];
							$model->save();
						}
					}
				}
			}
			$this->redirect(array('/mobileNotification', 'type'=>$_REQUEST['url_type'], 'lang'=>$_REQUEST['url_lang']));
		}
		else{			
			$this->render('index');
		}		
	}
	
	public function actionDeleteAll()
	{
		$type	= PushNotifications::model()->findByPk($_REQUEST['type']);
		
		$criteria				= new CDbCriteria();
		$criteria->condition	= 'type=:type AND language=:language';
		$criteria->params		= array(':type'=>$type->type, 'language'=>$_REQUEST['lang']);		
		$model					= PushNotifications::model()->findAll($criteria);	
		if($model != NULL){
			foreach($model as $value){
				$value->delete();
			}
		}
		$this->redirect(array('/mobileNotification', 'type'=>$_REQUEST['type'], 'lang'=>$_REQUEST['lang']));
	}
	//This is to restore the default English message
	public function actionRestore()
	{
		$delete_query	= Yii::app()->db->createCommand(
						"DELETE FROM `push_notifications` WHERE `id` >=1 and `id` <= 35"
					);
		$delete_query->execute();
		
		
		$insert_query	= Yii::app()->db->createCommand(
			"INSERT INTO `push_notifications` (`id`, `notification_number`, `type`, `description`, `language`, `title`, `message`) VALUES
			(1, 1, 'Email', 'When a new message is sent or replied to a message', 'en_us', 'New Message', 'You have received a message from {Sender}'),
			(2, 2, 'News', 'When a new news is published', 'en_us', 'News Published', '{Title} has been published by {School Name}'),
			(3, 3, 'Complaint', 'When a complaint registered by student', 'en_us', 'Complaint Registered', 'The student {Student Name} at {Batch Name} logged a complaint in category {Category}'),
			(4, 4, 'Complaint', 'When a complaint registered by guardian', 'en_us', 'Complaint Registered', '{Guardian Name} guardian of {Student Name} at {Batch Name} logged a Complaint in category {Category}'),
			(5, 5, 'Complaint', 'When a complaint registered by teacher', 'en_us', 'Complaint Registered', 'The teacher {Teacher Name} logged a complaint in category {Category}'),
			(6, 6, 'Complaint', 'When a complaint closed by any user', 'en_us', 'Complaint Closed', 'Registered complaint {Subject}, has been closed by {User Name}'),
			(7, 7, 'Complaint', 'When a complaint reopened by any user', 'en_us', 'Complaint Reopened', 'Registered complaint {Subject}, has been reopened by {User Name}'),
			(8, 8, 'Complaint', 'When a student logged a feedback for a complaint', 'en_us', 'Complaint Feedback', 'The student {Student Name} at {Batch Name} added a feedback'),
			(9, 9, 'Complaint', 'When a guardian logged a feedback for a complaint', 'en_us', 'Complaint Feedback', '{Guardian Name} parent of {Student Name} at {Batch Name} added a feedback'),
			(10, 10, 'Complaint', 'When a teacher logged a feedback for a complaint', 'en_us', 'Complaint Feedback', 'The teacher {Teacher Name} added a feedback'),
			(11, 11, 'Complaint', 'When admin logged a feedback for a complaint', 'en_us', 'Complaint Feedback', '{School Name} added a feedback to your comment titled {Subject}'),
			(12, 12, 'Student Log', 'When Teacher added a log, notification will send to Student.', 'en_us', 'Log Added', 'A log has been added to you in category {Category}'),
			(13, 13, 'Student Log', 'When Teacher added a log, notification will send to Guardian.', 'en_us', 'Log Added', 'A log has been added to {Student Name}, in category {Category}'),
			(14, 14, 'Student Log', 'When Teacher added a log, notification will send to Admin & related teachers.', 'en_us', 'Log Added', 'The teacher {Teacher Name} added a log for {Student Name}, in category {Category}'),
			(15, 15, 'Student Log', 'When Teacher deleted a log, notification will send to student.', 'en_us', 'Log Deleted', 'Log which is in the {Category} has been deleted'),
			(16, 16, 'Student Log', 'When Teacher deleted a log, notification will send to guardian.', 'en_us', 'Log Deleted', 'Log which is in the {Category} of your child {Student Name} has been deleted'),
			(17, 17, 'Event', 'When an event added by the school', 'en_us', 'Event Added', 'The event {Title} has been added to your calendar by {School Name}'),
			(18, 18, 'Student Attendance', 'When admin or teacher marked the day wise attendance of a student as absent, notification will send to the respective parents', 'en_us', 'Absent Marked', '{Marked By} marked your child {Student Name} as absent on {Date}'),
			(19, 19, 'Student Attendance', 'When admin or teacher marked the day wise attendance of a student as absent, notification will send to the respective student', 'en_us', 'Absent Marked', '{Marked By} marked you as absent on {Date}'),
			(20, 20, 'Student Attendance', 'When admin or teacher marked the day wise attendance of a student as present, notification will send to the respective parents', 'en_us', 'Absent Cancelled', '{Marked By} marked your child {Student Name} as present on {Date}'),
			(21, 21, 'Student Attendance', 'When admin or teacher marked the day wise attendance of a student as present, notification will send to the respective student', 'en_us', 'Absent Cancelled', '{Marked By} marked you as present on {Date}'),
			(22, 22, 'Student Attendance', 'When admin or teacher marked the subject wise attendance of a student as absent, notification will send to the respective parents;', 'en_us', 'Absent Marked', 'Your child {Student Name} is absent for the {Subject Name} session at {Class Timing} on {Date}'),
			(23, 23, 'Student Attendance', 'When admin or teacher marked the subject wise attendance of a student as absent, notification will send to the respective student;', 'en_us', 'Absent Marked', 'You are absent for the {Subject Name} session at {Class Timing} on {Date}'),
			(24, 24, 'Student Attendance', 'When admin or teacher marked the subject wise attendance of a student as present, notification will send to the respective parents;', 'en_us', 'Absent Cancelled', 'Your child {Student Name} is present for the {Subject Name} session at {Class Timing} on {Date}'),
			(25, 25, 'Student Attendance', 'When admin or teacher marked the subject wise attendance of a student as present, notification will send to the respective student;', 'en_us', 'Absent Cancelled', 'You are present for the {Subject Name} session at {Class Timing} on {Date}'),
			(26, 26, 'CBSE Exam', 'When Admin publish an exam, notification will send to student', 'en_us', 'Exam Published', 'Exam {Exam Name} has been published for the class {Class Name} under the batch {Batch Name}'),
			(27, 27, 'CBSE Exam', 'When Admin publish an exam, notification will send to guardian', 'en_us', 'Exam Published', 'Exam {Exam Name} created for the class {Class Name} has been published for your child {Student Name} studying in batch {Batch Name}'),
			(28, 28, 'Default Exam', 'When Admin publish an exam, notification will send to student', 'en_us', 'Exam Published', 'Exam {Exam Name} has been published for the batch {Batch Name}'),
			(29, 29, 'Default Exam', 'When Admin publish an exam, notification will send to guardian', 'en_us', 'Exam Published', 'Exam {Exam Name} has been published for your child {Student Name} studying in batch {Batch Name}'),
			(30, 30, 'CBSE Exam', 'When Admin publish exam result, notification will send to student', 'en_us', 'Exam Result Published', 'Exam Result of the {Exam Name} has been published for the class {Class Name} under the batch {Batch Name}'),
			(31, 31, 'CBSE Exam', 'When Admin publish exam result, notification will send to guardian', 'en_us', 'Exam Result Published', 'Exam Result of the {Exam Name} created for the class {Class Name} has been published for the batch {Batch Name} of your child {Student Name}'),
			(32, 32, 'Default Exam', 'When Admin publish exam result, notification will send to student', 'en_us', 'Exam Result Published', 'Exam Result of the {Exam Name} has been published for the batch {Batch Name}'),
			(33, 33, 'Default Exam', 'When Admin publish exam result, notification will send to guardian', 'en_us', 'Exam Result Published', 'Exam Result of the {Exam Name} has been published for the batch {Batch Name} of your child {Student Name}'),
			(34, 34, 'Teacher Attendance', 'When admin marked the day wise attendance of a teacher as absent, notification will send to the respective teacher', 'en_us', 'Absent Marked', '{School Name} marked you as absent on {Date}, due to {Reason}'),
			(35, 35, 'Teacher Attendance', 'When admin marked the day wise attendance of a teacher as present, notification will send to the respective teacher', 'en_us', 'Absent Cancelled', '{School Name} marked you as present on {Date}')				
				"
				);
		$insert_query->execute();
		
		$this->redirect(array('/mobileNotification', 'type'=>$_REQUEST['type'], 'lang'=>$_REQUEST['lang']));
	}
}
