<?php

class EmployeesController extends RController
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
				'actions'=>array('index','view','Create2','update2','Manage','savesearch','DisplaySavedImage','pdf','Address','Contact','Addinfo','Remove','achievements'),
				'users'=>array('@'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','batch','add','achievements'),
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
	
	public function actionSubjectAsso()
	{
		$this->render('subjectasso',array(
			'model'=>$this->loadModel($_REQUEST['id']),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Employees;
        $roles = Rights::getAssignedRoles(Yii::app()->user->Id);
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Employees']))
		{	
			$model->attributes=$_POST['Employees'];
			$list = $_POST['Employees'];
			
			
			/*
			* Checking whether date of birth is null
			*/
			if(!$list['date_of_birth']){
				$dob="";
			}
			else{
				$dob=date('Y-m-d',strtotime($list['date_of_birth']));
			}
			
			/*
			* If experience is given in years or in months, experience detail field become mandatory.
			*/
			if (($list['experience_year']&&$list['experience_year']!='0')||($list['experience_month']&&$list['experience_month']!='0')) {
					$model->setScenario('hasExperience');
			}
				$model->employee_number = $list['employee_number'];

				if(isset($list['joining_date']) and $list['joining_date']!=NULL){
					$model->joining_date = date('Y-m-d',strtotime($list['joining_date']));
				}
				$model->first_name = $list['first_name'];
				$model->middle_name = $list['middle_name'];
				$model->last_name = $list['last_name'];
				$model->gender = $list['gender'];
				$model->date_of_birth = $dob;
				$model->employee_department_id = $list['employee_department_id'];
				$model->employee_position_id = $list['employee_position_id'];
				$model->employee_category_id = $list['employee_category_id'];
				$model->employee_grade_id = $list['employee_grade_id'];
				$model->job_title = $list['job_title'];
				$model->qualification = $list['qualification'];
				$model->status = $list['status'];
				$model->experience_year = $list['experience_year'];
				$model->experience_month = $list['experience_month'];
				$model->experience_detail = $list['experience_detail'];
				$model->marital_status = $list['marital_status'];
				$model->children_count = $list['children_count'];
				
				$model->father_name = $list['father_name'];
				$model->mother_name = $list['mother_name'];
				$model->husband_name = $list['husband_name'];
				$model->blood_group = $list['blood_group'];
				$model->home_country_id = $list['home_country_id'];
				$model->email = $list['email'];
				
				if($file=CUploadedFile::getInstance($model,'photo_data')){
					$model->photo_file_name=$file->name;					
				}
				
                                if($file=CUploadedFile::getInstance($model,'photo_data')){
				$file_name = DocumentUploads::model()->getFileName($file->name);	
				if(key($roles)!=NULL and (key($roles) == 'Admin')){						
					$model->photo_file_name = $file_name;				
				}
                                }
				$model->created_at = date('Y-m-d H:i:s'); 		
				if($model->save())
				{
				//Save the profile pic to the folder	
					if($model->photo_file_name!=NULL){
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
						$info = getimagesize($_FILES['Employees']['tmp_name']['photo_data']); 
						if($info['mime'] == 'image/jpeg'){
							$image = imagecreatefromjpeg($_FILES['Employees']['tmp_name']['photo_data']);
						}elseif($info['mime'] == 'image/gif'){
							$image = imagecreatefromgif($_FILES['Employees']['tmp_name']['photo_data']);
						}elseif($info['mime'] == 'image/png'){
							$image = imagecreatefrompng($_FILES['Employees']['tmp_name']['photo_data']);
						}
																	
						$temp_file_name = $_FILES['Employees']['tmp_name']['photo_data'];					
						$destination_file = 'uploadedfiles/employee_profile_image/'.$model->id.'/'.$file_name;
						imagejpeg($image, $destination_file, 30);
                                                
						//add entry to document uploads for admin approve                                               
						DocumentUploads::model()->insertData(2, $model->id, $file_name, 4);		
					}
					$this->redirect(array('create2','id'=>$model->id));
				}
			//}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	public function actionDisplaySavedImage()
		{
			$model=$this->loadModel($_GET['id']);
			header('Pragma: public');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Content-Transfer-Encoding: binary');
			header('Content-length: '.$model->photo_file_size);
			header('Content-Type: '.$model->photo_content_type);
			header('Content-Disposition: attachment; filename='.$model->photo_file_name);
			echo $model->photo_data;
		}
	public function actionRemove()
	{
		$model = Employees::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$file_name = $model->photo_file_name;	
		$model->photo_file_name = NULL;
		if($model->save()){
			$path = 'uploadedfiles/employee_profile_image/'.$model->id.'/'.$file_name;
			if(file_exists($path)){		
				unlink($path);													
			}
		}
		$this->redirect(array('update','id'=>$_REQUEST['id']));
	}
	public function actionCreate2()
	{
		$model=new Employees;

		if(isset($_POST['Employees']))
		{
			$model->attributes=$_POST['Employees'];
			$list = $_POST['Employees'];
			$model =$model->findByAttributes(array('id'=>$_REQUEST['id']));
				
				$model->home_address_line1 = $list['home_address_line1'];
				$model->home_address_line2 = $list['home_address_line2'];
				$model->home_city = $list['home_city'];
				$model->home_state = $list['home_state'];
				$model->home_country_id = $list['home_country_id'];
				$model->home_pin_code = $list['home_pin_code'];
				
				$model->office_address_line1 = $list['office_address_line1'];
				$model->office_address_line2 = $list['office_address_line2'];
				$model->office_city = $list['office_city'];
				$model->office_state = $list['office_state'];
				$model->office_country_id = $list['office_country_id'];
				$model->office_pin_code = $list['office_pin_code'];
				
				$model->office_phone1 = $list['office_phone1'];
				$model->office_phone2 = $list['office_phone2'];
				$model->mobile_phone = $list['mobile_phone'];
				$model->home_phone = $list['home_phone'];
				$model->fax = $list['fax'];

				if($model->save()){				
					$this->redirect(array('/employees/employeeDocument/create','id'=>$model->id));
				
				}
		}
		$this->render('create2',array(
			'model'=>$model,
		));
	}
	
	
	
	
	
	
		public function actionCreate3()
	{
		$model=new Employees;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		 $this->performAjaxValidation($model);

		if(isset($_POST['Employees']))
		{
			$old_model = $model->attributes; // For activity feed
			$list = $_POST['Employees'];
			$model =$model->findByAttributes(array('id'=>$_REQUEST['id']));				
				$model->date_join = $list['date_join'];
				$model->salary_date = $list['salary_date'];
				$model->bank_name = $list['bank_name'];
				$model->bank_acc_no = $list['bank_acc_no'];
				$model->basic_pay = $list['basic_pay'];
				$model->HRA = $list['HRA'];
				$model->PF = $list['PF'];
				$model->TDS = $list['TDS'];
				$model->DA = $list['DA'];
				$model->others1 = $list['others1'];
				$model->others2 = $list['others2'];
				$model->user_id = $list['user_id'];
				
			if($model->save()){
				$this->redirect(array('view','id'=>$model->id));
			}
		}
		$this->render('create3',array(
			'model'=>$model,
		));
	}
	
	
	
	
	
	
	
	
	
	public function actionAddress()
	{
		$model = new Employees;
		/*$this->render('address',array(
			'model'=>$model,
		));*/
		$this->render('address',array(
			'model'=>$this->loadModel($_REQUEST['id']),
		));
	}
		public function actionContact()
	{
		$model = new Employees;
		/*$this->render('address',array(
			'model'=>$model,
		));*/
		$this->render('contact',array(
			'model'=>$this->loadModel($_REQUEST['id']),
		));
	}
		public function actionPayslip()
	{
		$model = new Employees;
		/*$this->render('address',array(
			'model'=>$model,
		));*/
		$this->render('payslip',array(
			'model'=>$this->loadModel($_REQUEST['id']),
		));
	}

	public function actionAddinfo()
	{
		$model = new Employees;
		/*$this->render('address',array(
			'model'=>$model,
		));*/
		$this->render('addinfo',array(
			'model'=>$this->loadModel($_REQUEST['id']),
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
                $roles = Rights::getAssignedRoles(Yii::app()->user->Id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
			if($settings!=NULL)
				{	
					$date1=date($settings->displaydate,strtotime($model->joining_date));
					$date2=date($settings->displaydate,strtotime($model->date_of_birth));
					
		
				}
				$model->joining_date=$date1;
				$model->date_of_birth=$date2;
		if(isset($_POST['Employees']))
		{
			
			$old_model = $model->attributes; // For activity feed
			
			
			
			$model->attributes=$_POST['Employees'];
			
			
			if($model->joining_date)
				$model->joining_date = date('Y-m-d',strtotime($model->joining_date));
			if($model->date_of_birth)
				$model->date_of_birth = date('Y-m-d',strtotime($model->date_of_birth));
			if($file=CUploadedFile::getInstance($model,'photo_data'))
			{
				$model->photo_file_name=$file->name;				
			}
                                                
                        if($file=CUploadedFile::getInstance($model,'photo_data'))
                        {
				$file_name = DocumentUploads::model()->getFileName($file->name);	
				if(key($roles)!=NULL and (key($roles) == 'Admin')){						
					$model->photo_file_name = $file_name;				
				}
                        }
			if($model->save())
			{
				//Save the profile pic to the folder	
				if($model->photo_file_name!=NULL){
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
					$info = getimagesize($_FILES['Employees']['tmp_name']['photo_data']); 
					if($info['mime'] == 'image/jpeg'){
						$image = imagecreatefromjpeg($_FILES['Employees']['tmp_name']['photo_data']);
					}elseif($info['mime'] == 'image/gif'){
						$image = imagecreatefromgif($_FILES['Employees']['tmp_name']['photo_data']);
					}elseif($info['mime'] == 'image/png'){
						$image = imagecreatefrompng($_FILES['Employees']['tmp_name']['photo_data']);
					}
																
					$temp_file_name = $_FILES['Employees']['tmp_name']['photo_data'];					
					$destination_file = 'uploadedfiles/employee_profile_image/'.$model->id.'/'.$file_name;
					imagejpeg($image, $destination_file, 30);
                                        
                    //add entry to document uploads for admin approve                                               
                    DocumentUploads::model()->insertData(2, $model->id, $file_name, 4);	
                                        
				}
				
				$results = array_diff_assoc($_POST['Employees'],$old_model); // To get the fields that are modified.

				foreach($results as $key => $value)
				{
					if($key != 'updated_at')
					{
						if($key == 'gender')
						{
							if($value == 'F')
							{
								$value = 'Female';
							}else
							{
								$value = 'Male';
							}
							if($old_model[$key] == 'F')
							{
								$old_model[$key] = 'Female';
							}
							else
							{
								$old_model[$key] = 'Male';
							}
						}
						elseif($key == 'employee_category_id')
						{
							$value = EmployeeCategories::model()->findByAttributes(array('id'=>$value));
							$value = ucfirst($value->name);
							
							$old_model_value = EmployeeCategories::model()->findByAttributes(array('id'=>$old_model[$key]));
							$old_model[$key] = ucfirst($old_model_value->name);
						}
						elseif($key == 'employee_position_id')
						{
							$value = EmployeePositions::model()->findByAttributes(array('id'=>$value));
							$value = ucfirst($value->name);
							
							$old_model_value = EmployeePositions::model()->findByAttributes(array('id'=>$old_model[$key]));
							$old_model[$key] = ucfirst($old_model_value->name);
						}
						elseif($key == 'employee_department_id')
						{
							$value = EmployeeDepartments::model()->findByAttributes(array('id'=>$value));
							$value = ucfirst($value->name);
							
							$old_model_value = EmployeeDepartments::model()->findByAttributes(array('id'=>$old_model[$key]));
							$old_model[$key] = ucfirst($old_model_value->name);
						}
						elseif($key == 'employee_grade_id')
						{
							$value = EmployeeGrades::model()->findByAttributes(array('id'=>$value));
							$value = ucfirst($value->name);
							
							$old_model_value = EmployeeGrades::model()->findByAttributes(array('id'=>$old_model[$key]));
							$old_model[$key] = ucfirst($old_model_value->name);
						}
						elseif($key == 'home_country_id' or $key == 'country_id')
						{
							$value = Countries::model()->findByAttributes(array('id'=>$value));
							$value = $value->name;
							
							$old_model_value = Countries::model()->findByAttributes(array('id'=>$old_model[$key]));
							$old_model[$key] = $old_model_value->name;
						}	
					}
				}	
				//END saving to activity feed		
				$this->redirect(array('update2','id'=>$model->id,'type'=>$_GET['type']));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	public function actionUpdate2($id)
	{
		$model=$this->loadModel($id);
		

		// Uncomment the following line if AJAX validation is needed
		 $this->performAjaxValidation($model);
         
		if(isset($_POST['Employees']))
		{ 
			$old_model = $model->attributes; // For activity feed
			$list = $_POST['Employees'];
			$model =$model->findByAttributes(array('id'=>$_REQUEST['id']));
				
				$model->home_address_line1 = $list['home_address_line1'];
				$model->home_address_line2 = $list['home_address_line2'];
				$model->home_city = $list['home_city'];
				$model->home_state = $list['home_state'];
				$model->home_country_id = $list['home_country_id'];
				$model->home_pin_code = $list['home_pin_code'];
				
				$model->office_address_line1 = $list['office_address_line1'];
				$model->office_address_line2 = $list['office_address_line2'];
				$model->office_city = $list['office_city'];
				$model->office_state = $list['office_state'];
				$model->office_country_id = $list['office_country_id'];
				$model->office_pin_code = $list['office_pin_code'];
				
				$model->office_phone1 = $list['office_phone1'];
				$model->office_phone2 = $list['office_phone2'];
				$model->mobile_phone = $list['mobile_phone'];
				$model->home_phone = $list['home_phone'];
				$model->fax = $list['fax'];
				
				
				$model->user_id = $list['user_id'];

			if($model->save())
			{
                if(isset($_REQUEST['type']) and $_REQUEST['type']==1)
                { 
                    $this->redirect(array('/employees/employeeDocument/create','id'=>$model->id));
                }
                else
                { 
                    $this->redirect(array('view','id'=>$model->id));
                }
			}
		}
		$this->render('update2',array(
			'model'=>$model,
		));
	}
	
	public function actionPdf()
    {
		$employee = Employees::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$filename = Employees::model()->getTeachername($employee->id).' Profile.pdf';
		Yii::app()->osPdf->generate("application.modules.employees.views.employees.print", $filename, array('model'=>$this->loadModel($_REQUEST['id'])));
	}
	
	public function actionSubjectAssoPdf()
    {
		$employee = Employees::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		$filename = Employees::model()->getTeachername($employee->id).' Subjects.pdf';		
		Yii::app()->osPdf->generate("application.modules.employees.views.employees.printsubjects", $filename, array('model'=>$this->loadModel($_REQUEST['id'])));
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
		$criteria = new CDbCriteria;
		$criteria->compare('is_deleted',0);
		$total = Employees::model()->count($criteria);
		$criteria->order = 'id DESC';
		$criteria->limit = '10';
		$posts = Employees::model()->findAll($criteria);
		
		
		$this->render('index',array(
			'total'=>$total,'list'=>$posts
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Employees('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Employees']))
			$model->attributes=$_GET['Employees'];

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
		$model=Employees::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='employees-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	
	/**
	 * Performs the Advance search.
	 * By Rajith
	 */
	public function actionManage()
	 {		 
		$model=new Employees;
		$criteria = new CDbCriteria;
		$criteria->condition 				= '(is_deleted=:is_deleted)';
		$criteria->params[':is_deleted'] 	= 0; 
		if(isset($_REQUEST['val']))
		{
		 $criteria->condition=$criteria->condition.' and (first_name LIKE :match or last_name LIKE :match or middle_name LIKE :match)';
		 $criteria->params[':match'] = $_REQUEST['val'].'%';
		}
		if(isset($_REQUEST['name']) and $_REQUEST['name']!=NULL)
		{
			
	   if((substr_count( $_REQUEST['name'],' '))==0)
		 { 	
		 $criteria->condition=$criteria->condition.' and '.'(first_name LIKE :name or last_name LIKE :name or middle_name LIKE :name)';
		 $criteria->params[':name'] = $_REQUEST['name'].'%';
		}
		else if((substr_count( $_REQUEST['name'],' '))<=1)
		{
		 $name=explode(" ",$_REQUEST['name']);
		 $criteria->condition=$criteria->condition.' and '.'(first_name LIKE :name or last_name LIKE :name or middle_name LIKE :name)';
		 $criteria->params[':name'] = $name[0].'%';
		 $criteria->condition=$criteria->condition.' and '.'(first_name LIKE :name1 or last_name LIKE :name1 or middle_name LIKE :name1)';
		 $criteria->params[':name1'] = $name[1].'%';
		 	
		}
		}
		
		if(isset($_REQUEST['employeenumber']) and $_REQUEST['employeenumber']!=NULL)
		{
		 $criteria->condition=$criteria->condition.' and '.'employee_number LIKE :employeenumber';
		 $criteria->params[':employeenumber'] = $_REQUEST['employeenumber'].'%';
		}
		
		if(isset($_REQUEST['Employees']['employee_department_id']) and $_REQUEST['Employees']['employee_department_id']!=NULL)
		{
			$model->employee_department_id = $_REQUEST['Employees']['employee_department_id'];
			$criteria->condition=$criteria->condition.' and '.'employee_department_id = :employee_department_id';
		    $criteria->params[':employee_department_id'] = $_REQUEST['Employees']['employee_department_id'];
		}
		
		if(isset($_REQUEST['Employees']['employee_category_id']) and $_REQUEST['Employees']['employee_category_id']!=NULL)
		{
			$model->employee_category_id = $_REQUEST['Employees']['employee_category_id'];
			$criteria->condition=$criteria->condition.' and '.'employee_category_id = :employee_category_id';
		    $criteria->params[':employee_category_id'] = $_REQUEST['Employees']['employee_category_id'];
		}
		
		if(isset($_REQUEST['Employees']['employee_position_id']) and $_REQUEST['Employees']['employee_position_id']!=NULL)
		{
			$model->employee_position_id = $_REQUEST['Employees']['employee_position_id'];
			$criteria->condition=$criteria->condition.' and '.'employee_position_id = :employee_position_id';
		    $criteria->params[':employee_position_id'] = $_REQUEST['Employees']['employee_position_id'];
		}
		
		
		if(isset($_REQUEST['Employees']['employee_grade_id']) and $_REQUEST['Employees']['employee_grade_id']!=NULL)
		{
			$model->employee_grade_id = $_REQUEST['Employees']['employee_grade_id'];
			$criteria->condition=$criteria->condition.' and '.'employee_grade_id = :employee_grade_id';
		    $criteria->params[':employee_grade_id'] = $_REQUEST['Employees']['employee_grade_id'];
		}
		
		
		if(isset($_REQUEST['Employees']['gender']) and $_REQUEST['Employees']['gender']!=NULL)
		{
			$model->gender = $_REQUEST['Employees']['gender'];
			$criteria->condition=$criteria->condition.' and '.'gender = :gender';
		    $criteria->params[':gender'] = $_REQUEST['Employees']['gender'];
		}
		
		if(isset($_REQUEST['Employees']['marital_status']) and $_REQUEST['Employees']['marital_status']!=NULL)
		{
			$model->marital_status = $_REQUEST['Employees']['marital_status'];
			$criteria->condition=$criteria->condition.' and '.'marital_status = :marital_status';
		    $criteria->params[':marital_status'] = $_REQUEST['Employees']['marital_status'];
		}
		
		if(isset($_REQUEST['Employees']['blood_group']) and $_REQUEST['Employees']['blood_group']!=NULL)
		{
			$model->blood_group = $_REQUEST['Employees']['blood_group'];
			$criteria->condition=$criteria->condition.' and '.'blood_group = :blood_group';
		    $criteria->params[':blood_group'] = $_REQUEST['Employees']['blood_group'];
		}
		
		if(isset($_REQUEST['Employees']['home_country_id']) and $_REQUEST['Employees']['home_country_id']!=NULL)
		{
			$model->home_country_id = $_REQUEST['Employees']['home_country_id'];
			$criteria->condition=$criteria->condition.' and '.'home_country_id = :home_country_id';
		    $criteria->params[':home_country_id'] = $_REQUEST['Employees']['home_country_id'];
		}
		
		
		if(isset($_REQUEST['Employees']['dobrange']) and $_REQUEST['Employees']['dobrange']!=NULL)
		{
			  
			  $model->dobrange = $_REQUEST['Employees']['dobrange'] ;
			  if(isset($_REQUEST['Employees']['date_of_birth']) and $_REQUEST['Employees']['date_of_birth']!=NULL)
			  {
				  if($_REQUEST['Employees']['dobrange']=='2')
				  {  
					  $model->date_of_birth = $_REQUEST['Employees']['date_of_birth'];
					  $criteria->condition=$criteria->condition.' and '.'date_of_birth = :date_of_birth';
					  $criteria->params[':date_of_birth'] = date('Y-m-d',strtotime($_REQUEST['Employees']['date_of_birth']));
				  }
				  if($_REQUEST['Employees']['dobrange']=='1')
				  {  
				  
					  $model->date_of_birth = $_REQUEST['Employees']['date_of_birth'];
					  $criteria->condition=$criteria->condition.' and '.'date_of_birth < :date_of_birth';
					  $criteria->params[':date_of_birth'] = date('Y-m-d',strtotime($_REQUEST['Employees']['date_of_birth']));
				  }
				  if($_REQUEST['Employees']['dobrange']=='3')
				  {  
					  $model->date_of_birth = $_REQUEST['Employees']['date_of_birth'];
					  $criteria->condition=$criteria->condition.' and '.'date_of_birth > :date_of_birth';
					  $criteria->params[':date_of_birth'] = date('Y-m-d',strtotime($_REQUEST['Employees']['date_of_birth']));
				  }
				  
			  }
		}
		elseif(isset($_REQUEST['Employees']['dobrange']) and $_REQUEST['Employees']['dobrange']==NULL)
		{
			  if(isset($_REQUEST['Employees']['date_of_birth']) and $_REQUEST['Employees']['date_of_birth']!=NULL)
			  {
				  $model->date_of_birth = $_REQUEST['Employees']['date_of_birth'];
				  $criteria->condition=$criteria->condition.' and '.'date_of_birth = :date_of_birth';
				  $criteria->params[':date_of_birth'] = date('Y-m-d',strtotime($_REQUEST['Employees']['date_of_birth']));
			  }
		}
		
		
		if(isset($_REQUEST['Employees']['joinrange']) and $_REQUEST['Employees']['joinrange']!=NULL)
		{
			  
			  $model->joinrange = $_REQUEST['Employees']['joinrange'] ;
			  if(isset($_REQUEST['Employees']['joining_date']) and $_REQUEST['Employees']['joining_date']!=NULL)
			  {
				  if($_REQUEST['Employees']['joinrange']=='2')
				  {  
					  $model->joining_date = $_REQUEST['Employees']['joining_date'];
					  $criteria->condition=$criteria->condition.' and '.'joining_date = :joining_date';
					  $criteria->params[':joining_date'] = date('Y-m-d',strtotime($_REQUEST['Employees']['joining_date']));
				  }
				  if($_REQUEST['Employees']['joinrange']=='1')
				  {  
				  
					  $model->joining_date = $_REQUEST['Employees']['joining_date'];
					  $criteria->condition=$criteria->condition.' and '.'joining_date < :joining_date';
					  $criteria->params[':joining_date'] = date('Y-m-d',strtotime($_REQUEST['Employees']['joining_date']));
				  }
				  if($_REQUEST['Employees']['joinrange']=='3')
				  {  
					  $model->joining_date = $_REQUEST['Employees']['joining_date'];
					  $criteria->condition=$criteria->condition.' and '.'joining_date > :joining_date';
					  $criteria->params[':joining_date'] = date('Y-m-d',strtotime($_REQUEST['Employees']['joining_date']));
				  }
				  
			  }
		}
		elseif(isset($_REQUEST['Employees']['joinrange']) and $_REQUEST['Employees']['joinrange']==NULL)
		{
			  if(isset($_REQUEST['Employees']['joining_date']) and $_REQUEST['Employees']['joining_date']!=NULL)
			  {
				  $model->joining_date = $_REQUEST['Employees']['joining_date'];
				  $criteria->condition=$criteria->condition.' and '.'joining_date = :joining_date';
				  $criteria->params[':joining_date'] = date('Y-m-d',strtotime($_REQUEST['Employees']['joining_date']));
			  }
		}
		
		if(isset($_REQUEST['Employees']['status']) and $_REQUEST['Employees']['status']!=NULL)
		{
			$model->status = $_REQUEST['Employees']['status'];
			$criteria->condition=$criteria->condition.' and '.'is_active = :status';
		    $criteria->params[':status'] = $_REQUEST['Employees']['status'];
		}
		
		$criteria->order = 'first_name ASC';
		//$criteria->condition ='is_deleted = :x';
		//$criteria->params = array(':x'=>0);
		
		
		$total = Employees::model()->count($criteria);
		$pages = new CPagination($total);
        $pages->setPageSize(Yii::app()->params['listPerPage']);
        $pages->applyLimit($criteria);  // the trick is here!
		$posts = Employees::model()->findAll($criteria);
		
		if(isset($_GET['print'])){			
			$filename 	= 'print.pdf';		
			Yii::app()->osPdf->generate("application.modules.employees.views.employees.employeepdf", $filename, array('employee'=>$posts));
		}
		
		$this->render('manage',array('model'=>$model,
		'list'=>$posts,
		'pages' => $pages,
		'item_count'=>$total,
		'page_size'=>Yii::app()->params['listPerPage'],)) ;
	 }
	 
	 public function actionDeletes()
	{
		if(Yii::app()->request->isPostRequest){		
			$model = Employees::model()->findByAttributes(array('id'=>$_REQUEST['id']));
			if($model->saveAttributes(array('is_deleted'=>'1')))
			{
				$employee_subjects=EmployeesSubjects::model()->findAllByAttributes(array('employee_id'=>$model->id));
				$employee_electives=EmployeeElectiveSubjects::model()->findAllByAttributes(array('employee_id'=>$model->id));
				$class_teachers=Batches::model()->findAllByAttributes(array('employee_id'=>$model->id));
				
				if($employee_subjects)
				{
					foreach($employee_subjects as $employee_subject)     // delete asociated subject allocation
					{
						$employee_subject->delete();
					}
				}
				if($employee_electives)
				{
					foreach($employee_electives as $employee_elective)    // delete asociated elective allocation
					{
						$employee_elective->delete();
					}
				}
				
				if($class_teachers)
				{
					foreach($class_teachers as $class_teacher)     // delete asociated subject allocation
					{
						
						$class_teacher->employee_id=0;
						$class_teacher->save();
					}
				}
			}

			$this->redirect(array('/employees/employees/manage'));

		}
			
		else
		{
			throw new CHttpException(404,Yii::t('app','Invalid Request.'));
		}
		
	}
	public function actionDelete_all()
	{
		if(Yii::app()->request->isPostRequest){			
		$datas = $_POST['id'];		
		 
			foreach($datas as $data)
			{
				$model = Employees::model()->findByAttributes(array('id'=>$data));
				
				if($model->saveAttributes(array('is_deleted'=>'1'))){

					$employee_subjects=EmployeesSubjects::model()->findAllByAttributes(array('employee_id'=>$model->id));
					$employee_electives=EmployeeElectiveSubjects::model()->findAllByAttributes(array('employee_id'=>$model->id));
					$class_teachers=Batches::model()->findAllByAttributes(array('employee_id'=>$model->id));
					
					if($employee_subjects)
					{
						foreach($employee_subjects as $employee_subject)     // delete asociated subject allocation
						{
							$employee_subject->delete();
						}
					}
					if($employee_electives)
					{
						foreach($employee_electives as $employee_elective)    // delete asociated elective allocation
						{
							$employee_elective->delete();
						}
					}
					if($class_teachers)
					{
						foreach($class_teachers as $class_teacher)     // delete asociated subject allocation
						{
							$class_teacher->employee_id=0;
							$class_teacher->save();
						}
					}
				
				}
			}
			
			echo CJSON::encode(array(
				'status'=>'success'
			));
			exit;
		}
		else{
			echo CJSON::encode(array(
				'status'=>'error'
			));
			exit;
		}
	}
	public function actionLog()
	{
		
		$criteria = new CDbCriteria;
		$criteria->order = 'date DESC';
		$criteria->condition='user_id=:x AND user_type=:type';
		$criteria->params = array(':x'=>$_REQUEST['id'],':type'=>2);
		$model1 = new LogComment;
		$total = LogComment::model()->count($criteria); // Count feeds
		$pages = new CPagination($total);
		$pages->setPageSize(Yii::app()->params['listPerPage']);
		$pages->applyLimit($criteria);
		
		$feeds = LogComment::model()->findAll($criteria); // Get feeds
		$this->render('log',array(
			'model'=>$this->loadModel($_REQUEST['id']),
			'model1'=>$model1,
			'comments'=>$feeds,
			'pages'=>$pages,
			'criteria'=>$criteria,
			
			
			));
	}
	public function actionDocument()
	{
		$this->render('document',array(
			'model'=>$this->loadModel($_REQUEST['id']),
		));
	}
	public function actionAttendance()
	{
		if(Configurations::model()->teacherAttendanceMode() != 2){
			$model	= new Employees;
			$this->render('attentance',array(
				'model'=>$this->loadModel($_REQUEST['id']),
			));
		}
		else{
			$this->redirect(array('/employees/teacherSubjectAttendance', 'id'=>$_REQUEST['id']));
		}
	}
	public function actionAchievements()
	{
		
		$model1 = new Achievements;
		$this->render('achievements',array(
			'model'=>$this->loadModel($_REQUEST['id']),
			'model1'=>$model1,
			'comments'=>$feeds,
			'pages'=>$pages,
			'criteria'=>$criteria,
			
			
			));
	}

	public function actionPositions(){

		if(isset($_POST['employee_category_id']))
		{
			
			$data=EmployeePositions::model()->findAll('employee_category_id=:x',array(':x'=>$_POST['employee_category_id']));
		}
		echo CHtml::tag('option', array('value' => ''), CHtml::encode(Yii::t('app','Select Position')), true);
		$data=CHtml::listData($data,'id','name');
		  foreach($data as $value=>$title)
		  {
			  echo CHtml::tag('option',
						 array('value'=>$value),CHtml::encode($title),true);
		  }

	}
}
