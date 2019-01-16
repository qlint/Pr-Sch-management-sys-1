<?php
/**
 * ImportCSV Module
 *
 * @author Artem Demchenkov <lunoxot@mail.ru>
 * @version 0.0.3
 *
 * ImportCSV is used for load positions from CSV file to database.
 * Import occurs in three steps:
 *
 * Upload file;
 * Select delimiter and table;
 * Select mode and columns in table.
 * Module has 3 modes:
 * 
 * Insert all - Add all rows;
 * Insert new - Add new rows. Old rows remain unchanged;
 * Insert new and replace old - Add new rows. Old rows replace.
 * All parameters from the previous imports will be saved in a special .php file in upload folder.
 * 
 * Requirements 
 * 
 * Yii 1.1
 * 
 * Usage 
 * 
 * 1) Copy all the 'importcsv' folder under /protected/modules;
 * 
 * 2) Register this module in /protected/config/main.php
 * 
 * 'modules'=>array(
 *         .........
 *         'importcsv'=>array(
 *             'path'=>'upload/importCsv/', // path to folder for saving csv file and file with import params
 *         ),
 *         ......
 *     ),
 * 3) Create a directory which you use in 'path'. Do not forget to set access permissions for directory 'path';
 * 
 * 4) The module is available here:
 * 
 * http://yourproject/importcsv.
 * 
 * Or here:
 * 
 * http://yourproject/index.php?r=importcsv.
 * 
 * Or somewhere else:-) It depends from path settings in your project;
 * 
 * 5) ATTENTION! The first row of your csv-file must will be a row with column names. 
 *
*/

class ImportcsvModule extends CWebModule
{
	public $subjectMaxCharsDisplay = 100;
	public $ellipsis = '...';
	public $allowableCharsSubject = '0-9a-z.,!?@\s*$%#&;:+=_(){}\[\]\/\\-';
        /*
         * path for csv file
         */
	public $path;
	public $delimiter;
	public $textDelimiter;
	public $table;
	public $perRequest;
	public $mode;
	public $tableKey;
	public $csvKey;
	public $allowedColumns;
	public $csvDefaultValues	= array();
	
	//new configurations
	public $models;
	public $scopes 	= array();
	public $scope 	= "forAdminRegistration";
	
	public $actions	= array();
	public $action	= 'insert';


	public function init()
	{
		$this->csvDefaultValues		= array(
			'Students'=>array(
				'admission_no'=>'11',
				'admission_date'=>'2/10/2015',
				'first_name'=>'Alex',
				'middle_name'=>'John',
				'last_name'=>'Francis',
				'course_id'=>'Course 1',
				'batch_id'=>'Batch 1',
				'date_of_birth'=>'10/10/1990',
				'gender'=>'M',
				'blood_group'=>'B+',
				'birth_place'=>'Kerala',
				'nationality_id'=>'Indian',
				'language'=>'English',
				'religion'=>'Christian',
				'student_category_id'=>'General',
				'address_line1'=>'Address 1',
				'address_line2'=>'Address 2',
				'city'=>'Cochin',
				'state'=>'Kerala',
				'pin_code'=>'556655',
				'country_id'=>'India',
				'phone1'=>'9897543423',
				'phone2'=>'8773423772',
				'email'=>'alexjohnfrancis@gmail.com',
				//'first_name_in_tamil'=>'அலெக்ஸ்',
				//'last_name_in_tamil'=>'பிரான்சிஸ்',
				'formal_edu_in_tamil'=>'Y',
				'is_email'=>'Y',
				'name_siblings'=>'Milan John Francis',
				'agree_1'=>'Y',
				'agree_2'=>'N',
				'is_confirm'=>'Y'
			),
			'Guardians'=>array(
				'first_name'=>'Francis',
				'last_name'=>'George',
				'relation'=>'Father',
				'email'=>'francisgeorge@gmail.com',
				'office_phone1'=>'9789353378',
				'office_phone2'=>'08653465484',
				'mobile_phone'=>'7894684846',
				'office_address_line1'=>'Address 1',
				'office_address_line2'=>'Address 2',
				'city'=>'Cochin',
				'state'=>'Kerala',
				'dob'=>'12/6/1960',
				'occupation'=>'Business',
				'income'=>'8000000',
				'education'=>'MBA',
				'gender'=>'M',
				'country_id'=>'India'
			)
		);
		
		$this->scopes 	= array(
			'forAdminRegistration'=>Yii::t("app", "Admin Student Registration"),
            'forOnlineRegistration'=>Yii::t("app", "Online Admission"),
			'forStudentProfile'=>Yii::t("app", "Student Profile"),
			'forStudentProfilePdf'=>Yii::t("app", "Student Profile PDF"),
            'forStudentPortal'=>Yii::t("app", "Student Portal"),
			'forParentPortal'=>Yii::t("app", "Parent Portal"),
            'forTeacherPortal'=>Yii::t("app", "Teacher Portal")
        );
		
		$this->actions	= array(
			'insert'=>Yii::t('app', 'Add'),
			'update'=>Yii::t('app', 'Edit'),
		);
        
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components

		//configure the importcsv with enabled static form fields
		$model 			= "Students";
		$scope 			= (isset($_GET['scope']) and $_GET['scope']!="" and $_GET['scope']!=NULL)?$_GET['scope']:$this->scope;
		$this->scope	= $scope;
		
		$action 		= (isset($_GET['action']) and $_GET['action']!="" and $_GET['action']!=NULL)?$_GET['action']:$this->action;
		$this->action	= $action;
		
		$fields = FormFields::model()->getStaticFields($model, $scope);
		$allowedColumns		= array();
		$requiredColumns	= array();

		foreach ($fields as $key => $field) {
			if($field->varname=="batch_id"){
				$allowedColumns[] 	= "course_id";
				$requiredColumns[] 	= "course_id";
			}
			
			$allowedColumns[] 	= $field->varname;
			if ($field->required==1) {
				$requiredColumns[] 	= $field->varname;
			}
		}

		//configure compare attributes
		$compareColumns	= array("nationality_id", "student_category_id", "country_id", "batch_id");
		foreach ($compareColumns as $column) {
			if(!in_array($column, $allowedColumns)){
				unset($this->models[$model]['compare'][$column]);
				if ($column=="batch_id") {
					unset($this->models[$model]['compare']['course_id']);
				}
			}
			else if(!in_array($column, $requiredColumns)){
				unset($this->models[$model]['compare'][$column]['requiredColumns']);
				if ($column=="batch_id") {
					unset($this->models[$model]['compare']['course_id']['requiredColumns']);
				}
			}
		}

		$removeColumns	= array("uid", "parent_id", "is_sms_enabled", "photo_file_name", "photo_content_type", "status_description", "is_active", "is_deleted", "created_at", "updated_at", "has_paid_fees", "photo_file_size", "user_id", "registration_id", "password", "registration_date", "academic_yr", "status", "is_completed", "type", "is_online", "photo_data", "parent_id_2", "immediate_contact_id", "membership_parent_id", "membership_agree");
		foreach ($removeColumns as $column) {
			if(($key = array_search($column, $allowedColumns)) !== false) {
			    unset($allowedColumns[$key]);
			}

			if(($key = array_search($column, $requiredColumns)) !== false) {
			    unset($requiredColumns[$key]);
			}
		}
		
		######## dynamic fields ########
		$dynamicColumns 	= array();
		//personal details
		$fields = FormFields::model()->getDynamicFields(1, 1, $scope);		
		foreach ($fields as $key => $field) {
			$allowedColumns[] 	= $field->varname;
			$dynamicColumns[] 	= $field->varname;
			if ($field->required==1) {
				$requiredColumns[] 	= $field->varname;
			}
		}
		//contact details
		$fields = FormFields::model()->getDynamicFields(1, 2, $scope);		
		foreach ($fields as $key => $field) {
			$allowedColumns[] 	= $field->varname;
			$dynamicColumns[] 	= $field->varname;
			if ($field->required==1) {
				$requiredColumns[] 	= $field->varname;
			}
		}
		######## dynamic fields ends ########
		
		$this->models[$model]['allowedColumns'] 	= array_values($allowedColumns);
		$this->models[$model]['requiredColumns'] 	= array_values($requiredColumns);
		$this->models[$model]['dynamicColumns'] 	= array_values($dynamicColumns);

		//externals , parent_id
		$fields = FormFields::model()->getStaticFields("Guardians", $scope);
		$allowedColumns		= array();
		$requiredColumns	= array();
		$dynamicColumns 	= array();

		foreach ($fields as $key => $field) {
			$allowedColumns[] 	= $field->varname;
			if ($field->required==1) {
				$requiredColumns[] 	= $field->varname;
			}
		}

		//configure compare attributes in external
		$compareColumns	= array("country_id");
		foreach ($compareColumns as $column) {
			if(!in_array($column, $allowedColumns)){
				unset($this->models[$model]['external']['parent_id']['compare'][$column]);
			}
			else if(!in_array($column, $requiredColumns)){
				unset($this->models[$model]['external']['parent_id']['compare'][$column]['requiredColumns']);
			}
		}

		$removeColumns	= array("uid", "ward_id", "created_at", "updated_at", "is_delete", "country_id");
		foreach ($removeColumns as $column) {
			if(($key = array_search($column, $allowedColumns)) !== false) {
			    unset($allowedColumns[$key]);
			}

			if(($key = array_search($column, $requiredColumns)) !== false) {
			    unset($requiredColumns[$key]);
			}
		}
		
		######## dynamic fields ########		
		//personal details
		$fields = FormFields::model()->getDynamicFields(2, 1, $scope);		
		foreach ($fields as $key => $field) {
			$allowedColumns[] 	= $field->varname;
			$dynamicColumns[] 	= $field->varname;
			if ($field->required==1) {
				$requiredColumns[] 	= $field->varname;
			}
		}
		//contact details
		$fields = FormFields::model()->getDynamicFields(2, 2, $scope);		
		foreach ($fields as $key => $field) {
			$allowedColumns[] 	= $field->varname;
			$dynamicColumns[] 	= $field->varname;
			if ($field->required==1) {
				$requiredColumns[] 	= $field->varname;
			}
		}
		
		$removeColumns	= array("relation_other");
		foreach ($removeColumns as $column) {
			if(($key = array_search($column, $allowedColumns)) !== false) {
			    unset($allowedColumns[$key]);
			}

			if(($key = array_search($column, $dynamicColumns)) !== false) {
			    unset($dynamicColumns[$key]);
			}
			
			if(($key = array_search($column, $requiredColumns)) !== false) {
			    unset($requiredColumns[$key]);
			}
		}
		######## dynamic fields ends ########
		
		$this->models[$model]['external']['parent_id']['allowedColumns'] 	= array_values($allowedColumns);
		$this->models[$model]['external']['parent_id']['requiredColumns'] 	= array_values($requiredColumns);
		$this->models[$model]['external']['parent_id']['dynamicColumns'] 	= array_values($dynamicColumns);
		
		$this->setImport(array(
			'importcsv.models.*',
			'importcsv.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
