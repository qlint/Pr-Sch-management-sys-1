<?php

class LeaveRequestsController extends RController
{
	public $defaultAction	= 'pending';
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
	
	public function actionPending(){
		$criteria	= new CDbCriteria;
		$criteria->condition	= '`status`=:status';
		$criteria->params		= array(':status'=>0);
		$criteria->order		= '`from_date`';
		$requests	= LeaveRequests::model()->findAll($criteria);

		$total 		= LeaveRequests::model()->count($criteria);
		$page_size	= 20;
		$pages 		= new CPagination($total);
		$pages->setPageSize($page_size);
		$pages->applyLimit($criteria);  // the trick is here!
		$requests 	= LeaveRequests::model()->findAll($criteria);
		
		$this->render('pending', array(
			'requests'=>$requests,
			'pages' => $pages,
			'item_count'=>$total,
			'page_size'=>$page_size
		));
	}
	
	public function actionApproved(){
		$criteria	= new CDbCriteria;
		$criteria->condition	= '`status`=:status';
		$criteria->params		= array(':status'=>1);
		$criteria->order		= '`from_date`';
		$requests	= LeaveRequests::model()->findAll($criteria);

		$total 		= LeaveRequests::model()->count($criteria);
		$page_size	= 20;
		$pages 		= new CPagination($total);
		$pages->setPageSize($page_size);
		$pages->applyLimit($criteria);  // the trick is here!
		$requests 	= LeaveRequests::model()->findAll($criteria);
		
		$this->render('approved', array(
			'requests'=>$requests,
			'pages' => $pages,
			'item_count'=>$total,
			'page_size'=>$page_size
		));
	}
	
	public function actionCancelled(){
		$criteria	= new CDbCriteria;
		$criteria->condition	= '`status`=:status1 OR `status`=:status2';
		$criteria->params		= array(':status1'=>2, ':status2'=>3);
		$criteria->order		= '`from_date`';
		$requests	= LeaveRequests::model()->findAll($criteria);

		$total 		= LeaveRequests::model()->count($criteria);
		$page_size	= 20;
		$pages 		= new CPagination($total);
		$pages->setPageSize($page_size);
		$pages->applyLimit($criteria);  // the trick is here!
		$requests 	= LeaveRequests::model()->findAll($criteria);
		
		$this->render('cancelled', array(
			'requests'=>$requests,
			'pages' => $pages,
			'item_count'=>$total,
			'page_size'=>$page_size
		));
	}
	
	public function actionView($id){
		$this->render('view', array(
			'model'=>$this->loadModel($id)
		));
	}
	
	public function actionApprove($id){
		$model	= $this->loadModel($id);
		if($model!=NULL){
			$model->setScenario('respond');			
			if(isset($_POST['LeaveRequests'])){
				$model->attributes	= $_POST['LeaveRequests'];
				$model->from_date	= date("Y-m-d H:i:s", strtotime($model->from_date));
				$model->to_date		= date("Y-m-d H:i:s", strtotime($model->to_date));
				$model->handled_by	= Yii::app()->user->id;
				$model->handled_at	= date("Y-m-d H:i:s");
				$model->status		= 1;	//approved
				if($model->save()){
					//entry to EmployeeAttendances table
						$date_arr = Configurations::daterange($model->from_date,$model->to_date);
						$employee = Employees::model()->findByAttributes(array('uid'=>$model->requested_by));
						foreach($date_arr as $val){
								$model_1							=	new EmployeeAttendances;
								$model_1->attendance_date			=	$val;
								$model_1->employee_id				=	$employee->id;
								$model_1->employee_leave_type_id 	= 	$model->leave_type_id;
								$model_1->reason					=	$model->reason;
							if($model->is_half_day == 1 or $model->is_half_day == 2){
								$model_1->is_half_day				=	1;
								$model_1->half						=	$model->is_half_day;	
							}
							else{
								$model_1->is_half_day				=	0;
								$model_1->half						=	0;	
							}
											
							$model_1->save();
						
					}
					//entry ends to EmployeeAttendances table
					$notification 	= NotificationSettings::model()->findByPk(23);
					$college		= Configurations::model()->findByPk(1);
					//employee
					$employee	= Staff::model()->findByAttributes(array('uid'=>$model->requested_by));
					if($notification->mail_enabled == '1' and $notification->employee == '1'){						
						if($employee!=NULL){						
							//send email to employee
							$template	= EmailTemplates::model()->findByPk(30);					
							$subject 	= $template->subject;
							$message 	= $template->template;						
							$subject 	= str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);								
							$message 	= str_replace("{{SCHOOL NAME}}",$college->config_value,$message);
							$message 	= str_replace("{{RESPONSE}}",$model->response,$message);
							
							//date range
							$settings 	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
							$date		= ($settings!=NULL)?(date($settings->displaydate, strtotime($model->from_date))):date('Y-m-d', $model->from_date);
							if($model->from_date!=$model->to_date){
								$date	.= " - ".(($settings!=NULL)?(date($settings->displaydate, strtotime($model->to_date))):date('Y-m-d', $model->to_date));
							}
							
							$message 	= str_replace("{{DATE}}",$date,$message);
							
							UserModule::sendMail($employee->email,$subject,$message);							
						}
					}
					
					//For Android App
					if(Configurations::model()->isAndroidEnabled()){				
						$criteria = new CDbCriteria();
						$criteria->condition = 'uid=:uid';
						$criteria->params[':uid'] = $employee->uid; 
						$reg_ids = UserDevice::model()->findAll($criteria);
						
						foreach($reg_ids as $reg_id){
							$reg_arr[] = $reg_id->device_id; 
						}
						//date range
						$settings 	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
						$date		= ($settings!=NULL)?(date($settings->displaydate, strtotime($model->from_date))):date('Y-m-d', $model->from_date);
						if($model->from_date!=$model->to_date){
							$date	.= " - ".(($settings!=NULL)?(date($settings->displaydate, strtotime($model->to_date))):date('Y-m-d', $model->to_date));
						}
						$text			= Yii::t('app', 'Your leave request for').' '.$date.' '.Yii::t('app','has been approved.');
						$argument_arr 	= array('message'=>$text, 'device_id'=>$reg_arr);                
						Configurations::model()->devicenotice($argument_arr, Yii::t('app', 'Leave Request Approved'),"leaves");
					}
					
					Yii::app()->user->setFlash('successMessage', Yii::t('app', 'Leave request has been approved'));
					
					echo CJSON::encode(array('status'=>'success'));
					exit;
				}
				else{
					echo CJSON::encode(array('status'=>'error', 'errors'=>$model->getErrors()));
					exit;
				}
			}
			else{
				Yii::app()->clientScript->scriptMap['jquery.js'] = false;
				Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
				$this->renderPartial('_approve', array('model'=>$model), false, true);
			}
		}
	}
	
	public function actionReject($id){
		$model	= $this->loadModel($id);
		if($model!=NULL){
			$model->setScenario('respond');			
			if(isset($_POST['LeaveRequests'])){	
				$model->attributes	= $_POST['LeaveRequests'];
				$model->from_date	= date("Y-m-d H:i:s", strtotime($model->from_date));
				$model->to_date		= date("Y-m-d H:i:s", strtotime($model->to_date));
				$model->handled_by	= Yii::app()->user->id;
				$model->handled_at	= date("Y-m-d H:i:s");
				$model->status		= 2;	// rejected
				if($model->save()){
					$notification 	= NotificationSettings::model()->findByPk(23);
					$college		= Configurations::model()->findByPk(1);
					//employee
					$employee	= Staff::model()->findByAttributes(array('uid'=>$model->requested_by));
					if($notification->mail_enabled == '1' and $notification->employee == '1'){						
						if($employee!=NULL){						
							//send email to employee
							$template	= EmailTemplates::model()->findByPk(31);					
							$subject 	= $template->subject;
							$message 	= $template->template;						
							$subject 	= str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);								
							$message 	= str_replace("{{SCHOOL NAME}}",$college->config_value,$message);
							$message 	= str_replace("{{RESPONSE}}",$model->response,$message);
							
							//date range
							$settings 	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
							$date		= ($settings!=NULL)?(date($settings->displaydate, strtotime($model->from_date))):date('Y-m-d', $model->from_date);
							if($model->from_date!=$model->to_date){
								$date	.= " - ".(($settings!=NULL)?(date($settings->displaydate, strtotime($model->to_date))):date('Y-m-d', $model->to_date));
							}
							
							$message 	= str_replace("{{DATE}}",$date,$message);
							
							UserModule::sendMail($employee->email,$subject,$message);
						}
					}
					//For Android App
					if(Configurations::model()->isAndroidEnabled()){				
						$criteria = new CDbCriteria();
						$criteria->condition = 'uid=:uid';
						$criteria->params[':uid'] = $employee->uid; 
						$reg_ids = UserDevice::model()->findAll($criteria);
						
						foreach($reg_ids as $reg_id){
							$reg_arr[] = $reg_id->device_id; 
						}
						//date range
						$settings 	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
						$date		= ($settings!=NULL)?(date($settings->displaydate, strtotime($model->from_date))):date('Y-m-d', $model->from_date);
						if($model->from_date!=$model->to_date){
							$date	.= " - ".(($settings!=NULL)?(date($settings->displaydate, strtotime($model->to_date))):date('Y-m-d', $model->to_date));
						}
						$text			= Yii::t('app', 'Your leave request for').' '.$date.' '.Yii::t('app','has been rejected.');
						$argument_arr 	= array('message'=>$text, 'device_id'=>$reg_arr);                
						Configurations::model()->devicenotice($argument_arr, Yii::t('app', 'Leave Request Rejected'),"leaves");
					}
					
					Yii::app()->user->setFlash('successMessage', Yii::t('app', 'Leave request has been rejected'));
					
					echo CJSON::encode(array('status'=>'success'));
					exit;
				}
				else{
					echo CJSON::encode(array('status'=>'error', 'errors'=>$model->getErrors()));
					exit;
				}		
			}
			else{
				Yii::app()->clientScript->scriptMap['jquery.js'] = false;
				Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
				$this->renderPartial('_reject', array('model'=>$model), false, true);
			}
		}
	}
	
	public function actionDownloadFile($id){
		$model	= $this->loadModel($id);
		if($model!=NULL and $model->file_name!=NULL){
			$file 	= $model->file_name;
			$path 	= "uploadedfiles/leave_images/" . $model->requested_by . "/" . $file;
			if (file_exists($path)) {
			    header('Content-Description: File Transfer');
			    header('Content-Type: application/octet-stream');
			    header('Content-Disposition: attachment; filename="'.basename($file).'"');
			    header('Expires: 0');
			    header('Cache-Control: must-revalidate');
			    header('Pragma: public');
			    header('Content-Length: ' . filesize($path));
			    readfile($path);
			    exit;
			}
			else{
				throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
			}
		}
		else{
			throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
		}
	}
	
	public function loadModel($id)
	{
		$model=LeaveRequests::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app', 'The requested page does not exist.'));
		return $model;
	}
}
