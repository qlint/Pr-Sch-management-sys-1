<?php

class FileUploadsController extends RController
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
		$model=new FileUploads;
		$academic_yr = Configurations::model()->findByPk(35);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['FileUploads']))
		{						
			$model->attributes	=	$_POST['FileUploads'];			
			$model->created_by	=	Yii::app()->user->id;	
					
			
			$obj_img			=	CUploadedFile::getInstance($model,'file');
			
			
			if($obj_img!=NULL){
				$file_name = DocumentUploads::model()->getFileName($obj_img);
				$model->file		=	$file_name;
			}
			
			$model->file_type	=	$obj_img->type;
			$model->academic_yr_id = $academic_yr->config_value;
			$model->created_at	= date('Y-m-d H:i:s');
			$model->is_special_student = 0;
			if($model->validate()){
				if($model->save()){
					if($obj_img!=NULL){	
						$path	=	'uploads/shared/'.$model->id.'/';
						if(!is_dir($path)){
							mkdir($path);
						}					
						
						if(!$obj_img->saveAs($path.$file_name)){
							$model->file	=	NULL;							
						}
						else{
							$model->file	=	$file_name;
						}	
						
						DocumentUploads::model()->insertData(5, $model->id, $file_name, 7);				
					}
					else{
						$model->file	=	NULL;
					}
					if($model->save()){
						//If the placeholder is student, send notification to student & guardian
						if($model->placeholder == 'student'){
							FileUploads::model()->sendNotification($model->id);
						}
						//For Android App
						if(Configurations::model()->isAndroidEnabled()){														
							if($model->placeholder == "Admin"){
								Yii::import('application.modules.rights.models.AuthAssignment');
								$users = AuthAssignment::model()->findAllByAttributes(array('itemname'=>"Admin"));
								foreach($users as $user){
									$reg_ids = UserDevice::model()->findAllByAttributes(array('uid'=>$user->userid));
									foreach($reg_ids as $reg_id){
										$reg_arr[] = $reg_id->device_id; 
									}
								}
								$argument_arr = array('message'=>$model->title,'device_id'=>$reg_arr);
								Configurations::model()->devicenotice($argument_arr,"File Uploaded","downloads");							
							}
                                                        
							elseif($model->placeholder == "student"){
                                                            $criteria = new CDbCriteria();
                                                            if($model->batch!=NULL)
                                                            {															
                                                                    $criteria->condition = "batch_id =:batch";
                                                                    $criteria->params = array(":batch"=>$model->batch);
                                                            }
                                                            $students = Students::model()->findAll($criteria);
                                                            foreach($students as $student){
                                                                    $reg_ids = UserDevice::model()->findAllByAttributes(array('uid'=>$student->uid));
                                                                    foreach($reg_ids as $reg_id){
                                                                            $reg_arr[] = $reg_id->device_id; 
                                                                    }
                                                            }
                                                            $argument_arr = array('message'=>$model->title,'device_id'=>$reg_arr);
                                                            Configurations::model()->devicenotice($argument_arr,"File Uploaded","downloads");

                                                        }
                                                elseif($model->placeholder == "parent"){      

                                                    $criteria = new CDbCriteria();
                                                    $criteria->join	= 'JOIN `courses` `co` ON `co`.`is_deleted`=0';
                                                    $criteria->join	.= ' JOIN `batches` `bt` ON `bt`.`course_id`=`co`.`id`';
                                                    $criteria->join	.= ' JOIN `batch_students` `bs` ON `bs`.`batch_id`=`bt`.`id`';
                                                    $criteria->join	.= ' JOIN `guardian_list` `gl` ON `gl`.`student_id`=`bs`.`student_id` AND `gl`.`guardian_id`=`t`.id';
                                                    if($model->course!="" && ($model->batch!="" && $model->batch!=0))
                                                    {                                                        
                                                        $criteria->condition="`co`.id =:course_id AND `co`.academic_yr_id =:ac_yr AND `bt`.id=:batch_id AND `bt`.is_active=:is_active AND `bt`.is_deleted=:is_deleted";
                                                        $criteria->params=array(':course_id'=>$model->course, ':ac_yr'=>$year ,':batch_id'=>$model->batch, ':is_deleted'=>0, ':is_active'=>1);
                                                    }
                                                    elseif($model->course!="" && ($model->batch=="" or $model->batch==0))
                                                    {
                                                       $batch_ids= array();
                                                        $batch_model= Batches::model()->findAllByAttributes(array('course_id'=>$model->course,'academic_yr_id'=>$year, 'is_active'=>1, 'is_deleted'=>0 ));
                                                        if($batch_model!=NULL)
                                                        {
                                                            foreach ($batch_model as $batch_data)
                                                            {
                                                                $batch_ids[]= $batch_data->id;
                                                            }
                                                        }       

                                                        $criteria->condition="`co`.id =:course_id AND `co`.academic_yr_id =:ac_yr AND `bt`.is_active=:is_active AND `bt`.is_deleted=:is_deleted";                                                        
                                                        $criteria->params=array(':course_id'=>$model->course ,':ac_yr'=>$year, ':is_deleted'=>0, ':is_active'=>1);
                                                        $criteria->addInCondition('`bt`.`id`',$batch_ids);
                                                    }

                                                    $criteria->distinct = true;
                                                    $parents = Guardians::model()->findAll($criteria);
                                                    foreach($parents as $parent){
                                                            $reg_ids = UserDevice::model()->findAllByAttributes(array('uid'=>$parent->uid));
                                                            foreach($reg_ids as $reg_id){
                                                                    $reg_arr[] = $reg_id->device_id; 
                                                            }
                                                    }
                                                    $argument_arr = array('message'=>$model->title,'device_id'=>$reg_arr);
                                                    Configurations::model()->devicenotice($argument_arr,"File Uploaded","downloads");

                                                }
                                                elseif($model->placeholder == "teacher"){

                                                            $criteria = new CDbCriteria();
                                                            $criteria->join	= 'JOIN `courses` `co` ON `co`.`is_deleted`=0';
                                                            $criteria->join	.= ' JOIN `batches` `bt` ON `bt`.`course_id`=`co`.`id`';                                                            
                                                            $criteria->join	.= ' JOIN `timetable_entries` `te` ON `te`.`employee_id`=`t`.`id` AND `te`.batch_id=`bt`.id';
                                                            if($model->course!="" && ($model->batch!="" && $model->batch!=0))
                                                            {
                                                                $batch_ids= array();
                                                                $criteria->condition="`co`.id =:course_id AND `co`.academic_yr_id =:ac_yr AND `bt`.id=:batch_id AND `bt`.is_active=:is_active AND `bt`.is_deleted=:is_deleted";
                                                                $criteria->params=array(':course_id'=>$model->course, ':ac_yr'=>$year ,':batch_id'=>$model->batch, ':is_deleted'=>0, ':is_active'=>1);
                                                                $batch_ids[]= $model->batch;
                                                            }
                                                            elseif($model->course!="" && ($model->batch=="" or $model->batch==0))
                                                            {
                                                                
                                                                $batch_ids= array();
                                                                $batch_model= Batches::model()->findAllByAttributes(array('course_id'=>$model->course,'academic_yr_id'=>$year, 'is_active'=>1, 'is_deleted'=>0 ));
                                                                if($batch_model!=NULL)
                                                                {
                                                                    foreach ($batch_model as $batch_data)
                                                                    {
                                                                        $batch_ids[]= $batch_data->id;
                                                                    }
                                                                }  
                                                                
                                                                $criteria->condition="`co`.id =:course_id AND `co`.academic_yr_id =:ac_yr AND `bt`.is_active=:is_active AND `bt`.is_deleted=:is_deleted";
                                                                $criteria->params=array(':course_id'=>$model->course ,':ac_yr'=>$year, ':is_deleted'=>0, ':is_active'=>1);
                                                                $criteria->addInCondition('`bt`.`id`',$batch_ids);
                                                            }                                                    
                                                            $criteria->distinct = true;                                                    
                                                            $teachers = Employees::model()->findAll($criteria);
                                                            foreach($teachers as $teacher){
                                                                    $reg_ids = UserDevice::model()->findAllByAttributes(array('uid'=>$teacher->uid));
                                                                    foreach($reg_ids as $reg_id){
                                                                            $reg_arr[] = $reg_id->device_id; 
                                                                    }
                                                            }
                                                            
                                                            //select class teachers                                                            
                                                            if($model->course!="" && !empty($batch_ids))
                                                            {
                                                                $criteria = new CDbCriteria();
                                                                $criteria->join	= 'JOIN `courses` `co` ON `co`.`is_deleted`=0';
                                                                $criteria->join .= ' JOIN batches `bt` ON `bt`.employee_id= t.id AND `bt`.course_id= `co`.id';                                                                     
                                                                $criteria->condition="`co`.id =:course_id AND `co`.academic_yr_id =:ac_yr AND `bt`.is_active=:is_active AND `bt`.is_deleted=:is_deleted";
                                                                $criteria->params=array(':course_id'=>$model->course ,':ac_yr'=>$year, ':is_deleted'=>0, ':is_active'=>1);
                                                                $criteria->addInCondition('`bt`.id',$batch_ids);
                                                                
                                                                $teachers = Employees::model()->findAll($criteria);
                                                                foreach($teachers as $teacher){
                                                                    $reg_ids = UserDevice::model()->findAllByAttributes(array('uid'=>$teacher->uid));
                                                                    foreach($reg_ids as $reg_id)
                                                                    {
                                                                        if(!in_array($reg_id->device_id, $reg_arr))
                                                                        {
                                                                            $reg_arr[] = $reg_id->device_id; 
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                                                                               
                                                            $argument_arr = array('message'=>$model->title,'device_id'=>$reg_arr);
                                                            Configurations::model()->devicenotice($argument_arr,"File Uploaded","downloads");

                                                        }
                                                        
							elseif($model->placeholder == ""){						
								if($model->course!="" && $model->batch!=""){
									$students = Students::model()->findAllByAttributes(array('batch_id'=>$model->batch));
									foreach($students as $student){
										$reg_ids = UserDevice::model()->findAllByAttributes(array('uid'=>$student->uid));
										foreach($reg_ids as $reg_id){
												$reg_arr[] = $reg_id->device_id; 
										}
									}
									$argument_arr = array('message'=>$model->title,'device_id'=>$reg_arr);
								}
								elseif($model->course!="" && $model->batch==""){
									$batch_array = array();
									$batches = Batches::model()->findAllByAttributes(array('course_id'=>$model->course));
									if($batches){
										foreach ($batches as $batch){
											$batch_array[]= $batch->id;
										}
									}
									
									$criteria = new CDbCriteria();
									$criteria->addCondition('batch_id',$batch_array);                                                
									$students = Students::model()->findAll($criteria);
									foreach($students as $student){
										$reg_ids = UserDevice::model()->findAllByAttributes(array('uid'=>$student->uid));
										foreach($reg_ids as $reg_id){
												$reg_arr[] = $reg_id->device_id; 
										}
									}
									$argument_arr = array('message'=>$model->title,'device_id'=>$reg_arr);															
								}
								else{
									$criteria = new CDbCriteria();
									$criteria->condition = 'uid<>:uid';
									$criteria->params[':uid'] = Yii::app()->user->id; 
									$reg_ids = UserDevice::model()->findAll($criteria);
									foreach($reg_ids as $reg_id){
											$reg_arr[] = $reg_id->device_id; 
									}
									$argument_arr = array('message'=>$model->title,'device_id'=>$reg_arr);
									
								}
								Configurations::model()->devicenotice($argument_arr,"File Uploaded","downloads");
							}												
						}
					}
					$this->redirect(array('view','id'=>$model->id));
				}
			}
			
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
		$model = $this->loadModel($id);
		$old_file_name = $model->file;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['FileUploads']))
		{
			
			$model->attributes	=	$_POST['FileUploads'];
			$obj_img			=	CUploadedFile::getInstance($model,'file');
			
			if($obj_img!=NULL){
				$file_name = DocumentUploads::model()->getFileName($obj_img);
				$model->file		=	$file_name;
			}
			
			if($obj_img!=NULL){
				$model->file		=	$file_name;
				$model->file_type	=	$obj_img->type;
			}
			if($model->placeholder=='')	
				$model->placeholder	=	NULL;
										
			if($model->save()){				
				if($obj_img!=NULL){
					$path	=	'uploads/shared/'.$model->id.'/';
					if(!is_dir($path)){
						mkdir($path);
					}					
					//generate random image name
					//$randomImage	=	$this->generateRandomString(rand(10,15)).'.'.$obj_img->extensionName;
					
					$randomImage = $obj_img->name;
					
					if(!$obj_img->saveAs($path.$file_name)){
						$model->file	=	NULL;							
					}
					else{
						$model->file	=	$file_name;
					}	
					
					DocumentUploads::model()->insertData(5, $model->id, $file_name, 7, $old_file_name);			
							
					$model->save();		
				}				
				$this->redirect(array('view','id'=>$model->id));
			}
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
			$model	=	$this->loadModel($id);
			$model_id= $model->id;
			$filename= $model->file;
			if($model!=NULL and $model->file!=NULL){
				$image_path	=	'uploads/shared/'.$model->id.'/'.$model->file;
				if(file_exists($image_path)){
					if(unlink($image_path)){
						rmdir('uploads/shared/'.$model->id.'/');
					}
				}
			}
			// we only allow deletion via POST request
					if($model->delete())
						{					
							//delete entry from document upload - admin approve/reject
							DocumentUploads::model()->deleteDocument(5, $model_id, $filename);					
						}

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
		$academic_yr = Configurations::model()->findByPk(35);
		$criteria	=	new CDbCriteria;
		$criteria->condition	=	'`file`<>:null and `academic_yr_id`=:year_id';
		$criteria->params	=	array(':null'=>'',':year_id'=>$academic_yr->config_value);
		$criteria->order	=	'`created_at` DESC';	
		$files		=	FileUploads::model()->findAll($criteria);
		if(isset($_POST['Downfiles'])){
			$selected_files	=	$_POST['Downfiles'];
			$slfiles	=	array();
			foreach($selected_files as $s_file){
				
				$model	=	FileUploads::model()->findByPk($s_file);
				if($model!=NULL){
									
					$slfiles[]	=	'uploads/shared/'.$model->id.'/'.$model->file;
				}
			}	
			$file_name = DocumentUploads::model()->generateSalt();	
			$zip			=	Yii::app()->zip;
			$fName			=	$file_name.'.zip';
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
		$this->render('index',array('files'=>$files));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new FileUploads('search');
		
		$model->unsetAttributes();  // clear any default values
		$academic_yr = Configurations::model()->findByPk(35);
		$model->academic_yr_id = $academic_yr->config_value;
		if(isset($_GET['FileUploads']))
			$model->attributes=$_GET['FileUploads'];
			

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionBatch()
	{
		
		
		$data=Batches::model()->findAll('course_id=:id AND is_active=:x ', 
        array(':id'=>(int) $_POST['course'],':x'=>'1'));
				  
		echo CHtml::tag('option', array('value' => 0), CHtml::encode('Select').' '.Students::model()->getAttributeLabel('batch_id'), true);
 
         $data=CHtml::listData($data,'id','name');
		  foreach($data as $value=>$name)
		  {
			  echo CHtml::tag('option',
						 array('value'=>$value),CHtml::encode($name),true);
		  }
	}
	
	public function actionRemovefile(){
		
			$id = $_REQUEST['id'];
			
				$model	=	$this->loadModel($id);
				
				if($model!=NULL and $model->file!=NULL){
					
					$image_path	=	'uploads/shared/'.$model->id.'/'.$model->file;
					if(file_exists($image_path)){
						if(unlink($image_path)){
							$model->file		=	NULL;
							$model->file_type	=	NULL;
							$model->save();
						}
					}
					else{
						echo Yii::t('app','file not exists');
						exit;
					}
				}
				$this->redirect(array('update','id'=>$id));
		
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=FileUploads::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='file-uploads-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
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
