<?php

class NotificationController extends DefaultController
{
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
		);
	}
	public function actionIndex()
	{
		$templates 	= EmailTemplates::model()->findByAttributes(array('id'=>'25'));
		// var_dump($templates);exit;
		$student_id   = $_REQUEST['student_id'];
		
		$this->render('index',array(
			'data'	=> $templates,'student'=>$student_id 
			
		)) ;
		
	}
	public function actionUpdate($id)
	{
		$model=EmailTemplates::model()->findByAttributes(array('id'=>$id));
       
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['EmailTemplates']))
		{   
		
		    $model->attributes=$_POST['EmailTemplates'];
			$model->created_by	= Yii::app()->user->id;
			$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));									
			if($settings!=NULL)
			{	
			$timezone = Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
			date_default_timezone_set($timezone->timezone);
			}
			$model->created_at	= date('Y-m-d H:i:s');
			//echo $model->created_at;exit;
			
			
			if($model->save())
			{
				$this->redirect(array('index','student_id'=>$_GET['student_id']));
			}
		}
		
		$this->render('update',array(
			'model'=>$model,'student_id'=>$_GET['student_id']
		));
	}
    public function actionMailreminder()
	{ 
				
		$student_ids = array();
		$studentArray= array();
		$student_ids= $_GET['student_id'];
	
		$studentArray = explode(',',$student_ids);
			
		$templates 	= EmailTemplates::model()->findByAttributes(array('id'=>'25'));		
					
		if(isset($studentArray))
		{
			foreach($studentArray as $student_list)
			{  
				$student     = Students::model()->findByAttributes(array('id'=>$student_list));	
				$parent      = Guardians::model()->findByAttributes(array('id'=>$student->parent_id));
				$name        = $student->first_name;
				$leavedays   = array();
				$batch       = Batches::model()->findByAttributes(array('id'=>$student->batch_id));
				
				if($student->admission_date>=$batch->start_date)
				{ 
				$batch_start  = date('Y-m-d',strtotime($student->admission_date));
				
				}
				else
				{
				$batch_start  = date('Y-m-d',strtotime($batch->start_date));
				}	
				$batch_end=date('Y-m-d');
				
				$batch_days = array();
				$batch_range = StudentAttentance::model()->createDateRangeArray($batch_start,$batch_end);
				$batch_days = array_merge($batch_days,$batch_range); 
				
				$criteria 				= new CDbCriteria;		
				$criteria->join 		= 'LEFT JOIN student_leave_types t1 ON (t.leave_type_id = t1.id OR t.leave_type_id = 0)'; 
				$criteria->condition 	= 't1.is_excluded=:is_excluded AND t.student_id=:x AND t.date >=:z AND t.date <=:A AND t.batch_id=:batch_id';											
				$criteria->params 		= array(':is_excluded'=>0,':x'=>$student->id,':z'=>$start_date,':A'=>$end_date, 'batch_id'=>$batch->id);	
				$criteria->order		= 't.date DESC';
				$criteria->group		= 't.id';	
				
				
				
				$leaves    = StudentAttentance::model()->findAll($criteria);
				
				foreach($leaves as $leave)
				{
					if(!in_array($leave->date,$leavedays))
					{
					 array_push($leavedays,$leave->date);
					}
				}
			$days = array();
			$weekArray = array();
			$total_working_days = array();
			$weekdays = Weekdays::model()->findAll("batch_id=:x AND weekday<>:y", array(':x'=>$batch->id,':y'=>"0"));
			if(count($weekdays)==0)
			{
				
				$weekdays = Weekdays::model()->findAll("batch_id IS NULL AND weekday<>:y",array(':y'=>"0"));
			}
			
			foreach($weekdays as $weekday)
			{
				
				$weekday->weekday = $weekday->weekday - 1;
				if($weekday->weekday <= 0)
				{
					$weekday->weekday = 7;
				}
				$weekArray[] = $weekday->weekday;
			}
			
			foreach($batch_days as $batch_day)
			{
				$week_number = date('N', strtotime($batch_day));
				if(in_array($week_number,$weekArray)) // If checking if it is a working day
				{
					array_push($days,$batch_day);
				}
			}
			$holidays = Holidays::model()->findAllByAttributes(array('user_id'=>Yii::app()->user->id));
			$holiday_arr=array();
			foreach($holidays as $key=>$holiday)
			{
				if(date('Y-m-d',$holiday->start)!=date('Y-m-d',$holiday->end))
				{
					$date_range = StudentAttentance::model()->createDateRangeArray(date('Y-m-d',$holiday->start),date('Y-m-d',$holiday->end));
					foreach ($date_range as $value) 
					{
						$holiday_arr[] = date('Y-m-d',$date_range);
					}
				}
				else
				{
					$holiday_arr[] = date('Y-m-d',$holiday->start);
				}
			}
			foreach($days as $day)
			{
				
				if(!in_array($day,$holiday_arr)) // If checking if it is a working day
				{
					array_push($total_working_days,$day);
				}
			
			}
				
				$present     = count($total_working_days);
				$absent      = count($leavedays);
				$attendance  = round((($present-$absent)/$present)*100,0);
				
				$college     = Configurations::model()->findByPk(1);
				$subject     = $templates->subject;
				$subject     = str_replace("{{SCHOOL NAME}}",$college->config_value,$subject);
				$subject     = strip_tags($subject, '<p></p>');
				
				$message     = $templates->template;
				$message     = str_replace("{{STUDENT NAME}}",$name,$message);
				$message     = str_replace("{{ATTENDANCE}}",$attendance,$message);
				
				                        
				if($parent->email!=NULL)
				{	
								
					UserModule::sendMail($parent->email,$subject,$message);
					
					Yii::app()->user->setFlash('message', Yii::t('app','Email send successfully'));   // send mail to each parent
					
					                                                                     
				}
				
			}
			$this->redirect(array('/report/default/reminder'));
		}
		     $this->render('index',array('templates'=> $templates,'student'=>$student_id 
			
		)) ;
	
	
	}

	
}