<?php

class StudentsController extends RController
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
	
	public function actionIndex()
	{
				
		$roles					= Rights::getAssignedRoles(Yii::app()->user->id); // check for single role
		$batch_id_arr			= array();
		$course_id_arr			= array();
		$student 				= Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		$batch_students			= BatchStudents::model()->findAllByAttributes(array('student_id'=>$student->id, 'status'=>1, 'result_status'=>0));		
		if($batch_students){
			foreach($batch_students as $value){
				$batch 				= Batches::model()->findByAttributes(array('id'=>$value->batch_id, 'is_active'=>1, 'is_deleted'=>0));
				if($batch){
					$batch_id_arr[]		= $batch->id;
					$course_id_arr[]	= $batch->course_id;
				}
			}
		}		
		
		$user_roles				= array();
		foreach($roles as $role){
			$user_roles[]	=	'"'.$role->name.'"';
		}
		$criteria				= new CDbCriteria();
		$criteria->join			= 'LEFT JOIN file_uploads_students t1 ON t.id = t1.table_id'; 
		$criteria->condition	= 't.placeholder=:placeholder AND t1.student_id=:student_id AND t.is_special_student=:is_special_student';
		$criteria->params		= array(':placeholder'=>'student', ':student_id'=>$student->id, ':is_special_student'=>1);
		$special_files			= FileUploads::model()->findAll($criteria);
		$special_file_arr	= array();
		if($special_files){
			foreach($special_files as $special_file){
				if(!in_array($special_file->id, $special_file_arr)){
					$special_file_arr[]	= $special_file->id;
				}
			}
		}	
		
		$val1	= '';
		$val2	= '';
		if(count($course_id_arr) > 0){
			$val1	= ' (`course` IN ('.implode(',',$course_id_arr).')) OR ';
		}
		if(count($batch_id_arr) > 0){
			$val2	= ' (`batch` IN ('.implode(',',$batch_id_arr).')) OR ';
		}
			
		$criteria					= new CDbCriteria;		
		$criteria->condition		= '`file`<>:null AND (`placeholder`=:null OR (`placeholder` IN ('.implode(',',$user_roles).') AND `is_special_student`=0)) AND ((`course` IS NULL) OR'.$val1.'(`course`=0)) AND ((`batch` IS NULL) OR'.$val2.'(`batch`=0))';
		$criteria->params			= array(':null'=>'');
		$criteria->addInCondition('id', $special_file_arr, 'OR');
		$criteria->order			= '`created_at` DESC';		
		$files						=	FileUploads::model()->findAll($criteria);
		if(isset($_POST['Downfiles'])){
			$selected_files	=	$_POST['Downfiles'];
			$slfiles	=	array();
			foreach($selected_files as $s_file){
				$model	=	FileUploads::model()->findByPk($s_file);
				if($model!=NULL){					
					$slfiles[]	=	'uploads/shared/'.$model->id.'/'.$model->file;
				}
			}			
			$zip			=	Yii::app()->zip;
			$fName			=	$this->generateRandomString(rand(10,20)).'.zip';
			$zipFile		=	'compressed/'.$fName;
			if($zip->makeZip($slfiles,$zipFile)){
				$fcon	=	file_get_contents($zipFile);
				header('Content-type:text/plain');
				header('Content-disposition:attachment; filename='.$fName);
				header('Pragma:no-cache');
				echo $fcon;
				unlink($zipFile);
			}
			else{
				Yii::app()->user->setFlash('success',Yii::t('app','Can\'t download'));
			}
			
		}
		$roles=Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role
		foreach($roles as $role)
		{				
			if(sizeof($roles)==1 and $role->name == 'student')
			{
				$this->render('/fileUploads/std_index',array('files'=>$files));//if the current role is student,it render std_index.php page else it take index.php page
			}
			else
			{
				$this->render('/fileUploads/index',array('files'=>$files));
			}
		}
	}
	
	public function loadModel($id)
	{
		$model=FileUploads::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,Yii::t('app','The requested page does not exist.'));
		return $model;
	}
	
	public function loadAuthorized($attributes)
	{
		$model=FileUploads::model()->findByAttributes($attributes);
		if($model===null){
			if(Yii::app()->request->isAjaxRequest){
				header("HTTP/1.0 404 Not Found");
				echo 'You are not authorized to perform this action.';
				exit;
			}
			else
				throw new CHttpException(404,Yii::t('app','You are not authorized to perform this action.'));
		}
		return $model;
	}

	
	private function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
}