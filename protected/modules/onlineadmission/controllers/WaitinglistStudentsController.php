<?php

class WaitinglistStudentsController extends RController
{
	public function actionIndex()
	{
		$this->render('index');
	}
	public function actionCreate()
	{
		$model 		= new WaitinglistStudents;
		$student 	= Students::model()->FindByAttributes(array('id'=>$_REQUEST['id']));	
		$parent 	= Guardians::model()->findByAttributes(array('id'=>$student->parent_id)); 		
		$bid 		= $student->batch_id;
		if($_POST['WaitinglistStudents']){
			$model->attributes = $_POST['WaitinglistStudents'];
			$model->student_id = $student->id;
			if($model->save()){			
				$student->saveAttributes(array('status'=>-3));
			//Mail
				$notification 	= NotificationSettings::model()->findByAttributes(array('id'=>15));
				$college		= Configurations::model()->findByPk(1);
				if($notification->mail_enabled == '1' or $notification->sms_enabled == '1'){
					//Mail to student
					if($notification->mail_enabled == '1' and $notification->student == '1'){								
						$student_email 	= EmailTemplates::model()->findByPk(20);
						$subject 		= $student_email->subject;
						$message 		= $student_email->template;
						$subject 		= str_replace("{{SCHOOL}}",ucfirst($college->config_value),$subject);
						$message 		= str_replace("{{SCHOOL}}",ucfirst($college->config_value),$message);			
						UserModule::sendMail($student->email,strip_tags($subject),$message);
					}
					// Send sms to student
					if($notification->sms_enabled == '1' and $notification->student == '1'){						
						$from 			= $college->config_value;				
						$sms_template 	= SystemTemplates::model()->findByAttributes(array('id'=>31));
						$sms_message 	= $sms_template->template;
						SmsSettings::model()->sendSms($student->phone1,$from,$sms_message);
					}
					
					//Mail to parent 1	
					if($notification->mail_enabled == '1' and $notification->parent_1 == '1'){
						$parent_email 	= EmailTemplates::model()->findByPk(19);
						$subject 		= $parent_email->subject;
						$message 		= $parent_email->template;
						$subject 		= str_replace("{{SCHOOL}}",ucfirst($college->config_value),$subject);
						$message 		= str_replace("{{SCHOOL}}",ucfirst($college->config_value),$message);
						$message 		= str_replace("{{STUDENT NAME}}",ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name),$message);
						UserModule::sendMail($parent->email,strip_tags($subject),$message);
					}
					//Send sms to parent 1
					if($notification->sms_enabled == '1' and $notification->parent_1 == '1'){					
						$from 			= $college->config_value;
						$sms_template 	= SystemTemplates::model()->findByAttributes(array('id'=>30));
						$sms_message 	= $sms_template->template;
						SmsSettings::model()->sendSms($parent->mobile_phone,$from,$sms_message);
					}					
				}								
				$this->redirect(array('list'));
			}
		}
		
		$this->render('create',array('model'=>$model,'student'=>$student,'bid'=>$bid));
	}
	public function actionList()
	{
		$model 					= new Students;			
		$criteria				= new CDbCriteria;
		$criteria->select		= 't.student_id,t.batch_id,t.priority,t.status';
		$criteria->join			= 'LEFT JOIN students st ON  st.id=t.student_id'; 
		$criteria->condition	= 't.status LIKE :status and st.status LIKE :st_Status and st.academic_yr = :st_academic_yr';
		$criteria->params 		= array(':status' => 0,':st_Status' => -3,':st_academic_yr' => Yii::app()->user->year);				
		$criteria->order 		= 'st.last_name ASC';
								
		if(isset($_REQUEST['name']) and $_REQUEST['name']!=NULL){
			if((substr_count( $_REQUEST['name'],' '))==0){ 	
				$criteria->condition		= $criteria->condition.' and '.'(st.first_name LIKE :name or st.last_name LIKE :name or st.middle_name LIKE :name)';
				$criteria->params[':name'] 	= $_REQUEST['name'].'%';
			}
			else if((substr_count( $_REQUEST['name'],' '))>=1){
				$name						= explode(" ",$_REQUEST['name']);
				$criteria->condition		= $criteria->condition.' and '.'(st.first_name LIKE :name or st.last_name LIKE :name or st.middle_name LIKE :name)';
				$criteria->params[':name'] 	= $name[0].'%';
				$criteria->condition		= $criteria->condition.' and '.'(st.first_name LIKE :name1 or st.last_name LIKE :name1 or st.middle_name LIKE :name1)';
				$criteria->params[':name1'] = $name[1].'%';			
			}
		}
				
		if(isset($_REQUEST['priority']) and $_REQUEST['priority']!=NULL){			 
			$criteria->condition			= $criteria->condition.' and '.'(t.priority LIKE :priority)';		
			$criteria->params[':priority'] 	= $_REQUEST['priority'];		
		}
		if(isset($_REQUEST['RegisteredStudents']['batch_id']) and $_REQUEST['RegisteredStudents']['batch_id']!=NULL){		
			$model->batch_id 				= $_REQUEST['RegisteredStudents']['batch_id'];
			$criteria->condition			= $criteria->condition.' and '.'(st.batch_id = :batch_id)';		
			$criteria->params[':batch_id'] 	= $_REQUEST['RegisteredStudents']['batch_id'];
		}
		$criteria->order = 't.priority ASC';
		//Pagination		
		$total = WaitinglistStudents::model()->count($criteria); // Count students
		
		$pages = new CPagination($total);
		$pages->setPageSize(10);
		$pages->applyLimit($criteria);
					   
		$waitingListStudents = WaitinglistStudents::model()->findAll($criteria);
				
		if (Yii::app()->request->isAjaxRequest)
				Yii::app()->getClientScript()->scriptMap=array('jquery.js'=>false, 'jquery.ui.js'=>false);
		$this->render('list',array('waitingListStudents'=>$waitingListStudents,'model'=>$model,'item_count'=>$total,'pages'=>$pages,'criteria'=>$criteria));			
	}
	public function actionBatch()
	{				
		$data	= Batches::model()->findAll('course_id=:id AND is_deleted=:x AND is_active=:y', array(':id'=>(int) $_POST['cid'],':x'=>'0',':y'=>1));				  
		echo CHtml::tag('option', array('value' => ''), CHtml::encode(Yii::t('app','Select')), true); 
		$data	= CHtml::listData($data,'id','name');
		foreach($data as $value => $name){
			echo CHtml::tag('option', array('value'=>$value),CHtml::encode($name),true);
		}
	}
	public function actionManage()
	{
		$model 		= WaitinglistStudents::model()->findByAttributes(array('student_id'=>$_REQUEST['id']));	
		$student 	= Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));	
		if($_POST['WaitinglistStudents']){			
			$model->attributes = $_POST['WaitinglistStudents'];
			if($model->save()){				
				if(isset($_REQUEST['from']) and $_REQUEST['from']=='course'){
					$this->redirect(array('/courses/batches/waitinglist','id'=>$_POST['WaitinglistStudents']['batch_id']));
				}
				else{
					$this->redirect(array('waitinglistStudents/list'));
				}
			}
		}
		
		$this->render('manage',array('model'=>$model,'student'=>$student));
	}
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest){
		
			$model 		= WaitinglistStudents::model()->findByAttributes(array('student_id'=>$_REQUEST['id']));	
			$student 	= Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));
			
			$criteria 						= new CDbCriteria;
			$criteria->condition			= 'batch_id=:batch_id AND priority>:priority';
			$criteria->params[':batch_id'] 	= $model->batch_id;
			$criteria->params[':priority'] 	= $model->priority;						
			$DetailsOfStudent 				= WaitinglistStudents::model()->findAll($criteria);
			foreach($DetailsOfStudent as $change){
				$change->saveAttributes(array('priority'=>$change->priority - 1));
			}
			$student->saveAttributes(array('status'=>0));
			$model->delete();
			Yii::app()->user->setFlash('successMessage', Yii::t('app','Successfully Deleted'));
			if(isset($_REQUEST['from']) and $_REQUEST['from']=='course'){
				$this->redirect(array('/courses/batches/waitinglist','id'=>$_REQUEST['batch_id']));
			}
			else{		
				$this->redirect(array('list'));							
			}
		}
		else{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
	}
	public function actionMakepending()
	{
		$model 		= WaitinglistStudents::model()->findByAttributes(array('student_id'=>$_REQUEST['id']));	
		$student 	= Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));	
		$student->saveAttributes(array('status'=>0,'is_deleted'=>0,'batch_id'=>NULL));
		$criteria 						= new CDbCriteria;
		$criteria->condition 			= 'batch_id=:batch_id AND priority>:priority';
		$criteria->params[':batch_id'] 	= $model->batch_id;
		$criteria->params[':priority'] 	= $model->priority;						
		$DetailsOfStudent 				= WaitinglistStudents::model()->findAll($criteria);
		foreach($DetailsOfStudent as $change){
			$change->saveAttributes(array('priority'=>$change->priority - 1));
		}
		if($model->delete()){
			if(isset($_REQUEST['from']) and $_REQUEST['from']=='course'){
				$this->redirect(array('/courses/batches/waitinglist','id'=>$_REQUEST['batch_id']));
			}
			else{
				$this->redirect(array('list'));
			}
		}
		Yii::app()->user->setFlash('successMessage', Yii::t('app','Action performed successfully'));		
		$this->redirect(array('list'));
	}
	
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}

	
//Display Priority value	
	public function actionPriority()
	{
		$batch_id 	= $_POST['batch_id'];
		$model 		= WaitinglistStudents::model()->findAllByAttributes(array('batch_id'=>$_POST['batch_id']));	
		if($model!=NULL){
			$priority_no = array();
			foreach($model as $priority){
				$priority_no[] = $priority->priority;
			}
			$largest_value = max($priority_no);
			$current_value = $largest_value+1;
			echo $current_value;
		}
		else{
			echo 1;
		}
		
	}
}
