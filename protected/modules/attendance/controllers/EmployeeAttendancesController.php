<?php

class EmployeeAttendancesController extends RController
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
				'actions'=>array('index','view','Addnew','pdf'),
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
		$model=new EmployeeAttendances;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['EmployeeAttendances']))
		{
			$model->attributes=$_POST['EmployeeAttendances'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	public function actionAddnew() 
	{
		$model	= new EmployeeAttendances;		
		$this->performAjaxValidation($model);        
		$flag	= true;
        if(isset($_POST['EmployeeAttendances'])){ 			
			$flag				= false;
			$model->attributes	= $_POST['EmployeeAttendances'];
			if($_POST['EmployeeAttendances']['is_half_day']==1){
				if($_POST['half_session'] == 1){
					$model->half	= 1;
				}
				else if($_POST['half_session']==2){
					$model->half	= 2;
				}
			}
            if($model->save()){
				$employee	= Employees::model()->findByAttributes(array('id'=>$model->employee_id));
				$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
				if($settings!=NULL){	
					$date	= date($settings->displaydate,strtotime($model->attendance_date));			
				}
				//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
				ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'26',$model->employee_id,Employees::model()->getTeachername($employee->id),$date,NULL,NULL);
				
				
				//Mobile Push Notifications
				if(Configurations::model()->isAndroidEnabled()){									
					if($employee->uid != 0){
						$college		= Configurations::model()->findByPk(1);
						$sender_name	= PushNotifications::model()->getUserName(Yii::app()->user->id);
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
				}
				
                 echo CJSON::encode(array(
                        'status'=>'success',
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
		if($flag){
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$this->renderPartial('create',array('model'=>$model,'day'=>$_GET['day'],'month'=>$_GET['month'],'year'=>$_GET['year'],'emp_id'=>$_GET['emp_id']),false,true);
		}
	}			
	public function actionEditLeave()
	{
		$model	= EmployeeAttendances::model()->findByAttributes(array('id'=>$_REQUEST['id']));		
		$flag	= true;
		if(isset($_POST['EmployeeAttendances'])){
			$old_model			= $model->attributes;           
			$flag				= false;
			$model->attributes	= $_POST['EmployeeAttendances'];
			if(isset($_POST['EmployeeAttendances']['is_half_day']) and $_POST['EmployeeAttendances']['is_half_day'] == 1){
				if($_POST['half_session'] == 1){
					$model->half	= 1;
				}
				else if($_POST['half_session'] == 2){
					$model->half	= 2;
				}
			}
			else{
				$model->half	= 0;
			}				
			if($model->save()){
				$employee	= Employees::model()->findByAttributes(array('id'=>$model->employee_id));
				$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
				if($settings != NULL){	
					$date	= date($settings->displaydate,strtotime($model->attendance_date));			
				}				
				// Saving to activity feed
				$results	= array_diff_assoc($_POST['EmployeeAttendances'],$old_model); 
				foreach($results as $key => $value){
					if($key != 'attendance_date'){
						if($key == 'employee_leave_type_id'){
							$leave = EmployeeLeaveTypes::model()->findByAttributes(array('id'=>$value));
							$value = ucfirst($leave->name);
						}						
						//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
						ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'27',$model->employee_id,Employees::model()->getTeachername($employee->id),$model->getAttributeLabel($key),$date,$value);
					}					
				}	
				//END saving to activity feed
				//Mobile Push Notifications
				if(Configurations::model()->isAndroidEnabled()){									
					if($employee->uid != 0){
						$college		= Configurations::model()->findByPk(1);
						$sender_name	= PushNotifications::model()->getUserName(Yii::app()->user->id);
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
				}
				
				echo CJSON::encode(array(
                        'status'=>'success',
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
		if($flag){
			Yii::app()->clientScript->scriptMap['jquery.js'] = false;
			$this->renderPartial('update',array('model'=>$model,'day'=>$_GET['day'],'month'=>$_GET['month'],'year'=>$_GET['year'],'emp_id'=>$_GET['emp_id']),false,true);
		}
	}	
	/* Delete the marked leave */
	public function actionDeleteLeave()
	{
		$flag		= true;
		$delete		= EmployeeAttendances::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$model		= EmployeeAttendances::model()->DeleteAllByAttributes(array('id'=>$_REQUEST['id']));		
		$employee 	= Employees::model()->findByAttributes(array('id'=>$delete->employee_id));
		$settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
		if($settings != NULL){	
			$date	= date($settings->displaydate,strtotime($delete->attendance_date));			
		}		
		//Adding activity to feed via saveFeed($initiator_id,$activity_type,$goal_id,$goal_name,$field_name,$initial_field_value,$new_field_value)
		ActivityFeed::model()->saveFeed(Yii::app()->user->Id,'28',$delete->employee_id,Employees::model()->getTeachername($employee->id),$date,NULL,NULL);
		
		//Mobile Push Notifications
		if(Configurations::model()->isAndroidEnabled()){					
			if($employee->uid != 0){
				$college		= Configurations::model()->findByPk(1);
				$sender_name	= PushNotifications::model()->getUserName(Yii::app()->user->id);
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
					$message	= str_replace("{Date}", date($settings->displaydate, strtotime($delete->attendance_date)), $message);																																																				
					$argument_arr = array('message'=>$message, 'device_id'=>array($value->device_id),'sender_name'=>$sender_name, 'date'=>$delete->attendance_date, 'teacher_id'=>$employee->id);       
					Configurations::model()->devicenotice($argument_arr, $push_notifications[$key]['title'], "teacher_daywise_attendance");																		
				}											
			}								
		}
		
		if($flag){
			echo json_encode(array("status"=>"success"));
			exit;			
		}			  	
	}
		
	/*
		
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['EmployeeAttendances']))
		{
			$model->attributes=$_POST['EmployeeAttendances'];
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
		if(Configurations::model()->teacherAttendanceMode() != 2){
			$model=new EmployeeAttendances;
			$this->render('index',array(
				'model'=>$model,
			));
		}
		else{
			$this->redirect(array('/attendance/teacherSubjectAttendance'));
		}
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new EmployeeAttendances('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['EmployeeAttendances']))
			$model->attributes=$_GET['EmployeeAttendances'];

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
		$model=EmployeeAttendances::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='employee-attendances-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	/*public function actionPdf()
	 {
		 $data=Employees::model()->findAll("employee_department_id=:x", array(':x'=>$_REQUEST['id']));
		 $logo=new logo;
         

	 Yii::import('application.extensions.fpdf.*');
     require('fpdf.php');$pdf = new FPDF();
     $pdf->AddPage();
     $pdf->SetFont('Arial','BU',15);
	 $pdf->Cell(75,10,'Employee Attendance Report',0,0,'C');
	 $pdf->Ln();
	 $pdf->Ln();
	 $pdf->SetFont('Arial','BU',10);
	// $pdf=$pdf->Image('logo.png',10,12,30,0);
	 $w= array(40,40,60);

	 $header = array('Name','Leaves','Remarks');
	 
    //Header
    for($i=0;$i<count($header);$i++)
	{
        $pdf->Cell($w[$i],7,$header[$i],1,0,'C',false);
    
	}
     $pdf->Ln();
	 $pdf->SetFont('Arial','',10);

	 $fill=false;
	 $i=40;
	 foreach($data as $data1)
	 {
	 $pdf->Cell($i,6,$data1->first_name,1,0,'L',$fill);
	 
	 $fullday=count(EmployeeAttendances::model()->findAllByAttributes(array('employee_id'=>$data1->id,'is_half_day'=>'0')));
	 $halfday=count(EmployeeAttendances::model()->findAllByAttributes(array('employee_id'=>$data1->id,'is_half_day'=>'1')));
	 $halfday=$halfday/2;
	 $total=$fullday+$halfday;
	 
	 $pdf->Cell($i,6,$total,1,0,'C',$fill);
	 $pdf->Cell($i+20,6,'',1,0,'C',$fill);
	 
	 $pdf->Ln();
	 }
	 
     $pdf->Output();
	 Yii::app()->end();
	 }*/
	
	 public function actionPdf()
        {
            $department = EmployeeDepartments::model()->findByAttributes(array('id'=>$_REQUEST['id']));		        
            $filename= $department->name.' Department Attendance.pdf';
            Yii::app()->osPdf->generate("application.modules.attendance.views.employeeAttendances.attentancepdf", $filename, array(),1); 
	}


public function actionLeaveCheck()
	{
		
		$taken=EmployeeAttendances::model()->findAllByAttributes(array('employee_id'=>$_REQUEST['employee_id'],'employee_leave_type_id'=>$_REQUEST['leave_type']));
		$type=EmployeeLeaveTypes::model()->findByAttributes(array('id'=>$_REQUEST['leave_type']));
		
		
		$count=$type->max_leave_count;
		if(count($taken)>=$count)
		{
			
			echo Yii::t('app',"This leave type count is exceeded, choose another leave type");
			
		}
		else
		{
			echo " ";
		}
		
		
	}	
	
}
