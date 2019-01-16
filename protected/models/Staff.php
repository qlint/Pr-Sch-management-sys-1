<?php

/**
 * This is the model class for table "employees".
 *
 * The followings are the available columns in table 'employees':
 * @property integer $id
 * @property integer $uid
 * @property integer $employee_category_id
 * @property string $employee_number
 * @property string $joining_date
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $gender
 * @property string $job_title
 * @property integer $employee_position_id
 * @property integer $employee_department_id
 * @property integer $reporting_manager_id
 * @property integer $employee_grade_id
 * @property string $qualification
 * @property string $experience_detail
 * @property integer $experience_year
 * @property integer $experience_month
 * @property integer $status
 * @property string $status_description
 * @property string $date_of_birth
 * @property string $marital_status
 * @property integer $children_count
 * @property string $father_name
 * @property string $mother_name
 * @property string $husband_name
 * @property string $blood_group
 * @property integer $nationality_id
 * @property string $home_address_line1
 * @property string $home_address_line2
 * @property string $home_city
 * @property string $home_state
 * @property integer $home_country_id
 * @property string $home_pin_code
 * @property string $office_address_line1
 * @property string $office_address_line2
 * @property string $office_city
 * @property string $office_state
 * @property integer $office_country_id
 * @property string $office_pin_code
 * @property string $office_phone1
 * @property string $office_phone2
 * @property string $mobile_phone
 * @property string $home_phone
 * @property string $email
 * @property string $fax
 * @property string $photo_file_name
 * @property string $photo_content_type
 * @property string $photo_data
 * @property string $created_at
 * @property string $updated_at
 * @property integer $photo_file_size
 * @property integer $user_id
 * @property integer $is_deleted
 * @property string $date_join
 * @property string $salary_date
 * @property string $bank_name
 * @property integer $bank_acc_no
 * @property integer $basic_pay
 * @property integer $HRA
 * @property integer $PF
 * @property integer $TDS
 * @property string $DA
 * @property string $others1
 * @property string $others2
 * @property integer $passport_no
 * @property string $passport_expiry
 * @property integer $user_type
 */
class Staff extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Staff the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'employees';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		if(Yii::app()->controller->action->id=="addsalarydetails"){
			return array(
				array('basic_pay, TDS', 'required'),
				array('uid, employee_category_id, employee_position_id, employee_department_id, reporting_manager_id, employee_grade_id, experience_year, experience_month, status, children_count, nationality_id, home_country_id, office_country_id, photo_file_size, user_id, is_deleted, bank_acc_no, basic_pay, HRA, PF, passport_no, user_type', 'numerical', 'integerOnly'=>true),
				array('employee_number, first_name, middle_name, last_name, job_title, qualification, status_description, marital_status, father_name, mother_name, husband_name, blood_group, home_address_line1, home_address_line2, home_city, home_state, home_pin_code, office_address_line1, office_address_line2, office_city, office_state, office_pin_code, office_phone1, office_phone2, mobile_phone, home_phone, email, fax, photo_file_name, photo_content_type, date_join, salary_date, bank_name, DA, others1, others2', 'length', 'max'=>255),
				array('gender', 'length', 'max'=>10),
				array('joining_date, experience_detail, date_of_birth, photo_data, created_at, updated_at, passport_expiry,staff_type,tds_type,ESI,EPF', 'safe'),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, uid, employee_category_id, employee_number, joining_date, first_name, middle_name, last_name, gender, job_title, employee_position_id, employee_department_id, reporting_manager_id, employee_grade_id, qualification, experience_detail, experience_year, experience_month, status, status_description, date_of_birth, marital_status, children_count, father_name, mother_name, husband_name, blood_group, nationality_id, home_address_line1, home_address_line2, home_city, home_state, home_country_id, home_pin_code, office_address_line1, office_address_line2, office_city, office_state, office_country_id, office_pin_code, office_phone1, office_phone2, mobile_phone, home_phone, email, fax, photo_file_name, photo_content_type, photo_data, created_at, updated_at, photo_file_size, user_id, is_deleted, date_join, salary_date, bank_name, bank_acc_no, basic_pay, HRA, PF, TDS, DA, others1, others2, passport_no, passport_expiry, user_type', 'safe', 'on'=>'search'),
			);
		}
		else if(isset(Yii::app()->controller->module) and Yii::app()->controller->module->id=="user"){
			return array(
				array('first_name, last_name, email', 'required'),
				array('uid, employee_category_id, employee_position_id, employee_department_id, reporting_manager_id, employee_grade_id, experience_year, experience_month, status, children_count, nationality_id, home_country_id, office_country_id, photo_file_size, user_id, is_deleted, bank_acc_no, basic_pay, HRA, PF, passport_no, user_type', 'numerical', 'integerOnly'=>true),
				array('employee_number, first_name, middle_name, last_name, job_title, qualification, status_description, marital_status, father_name, mother_name, husband_name, blood_group, home_address_line1, home_address_line2, home_city, home_state, home_pin_code, office_address_line1, office_address_line2, office_city, office_state, office_pin_code, office_phone1, office_phone2, mobile_phone, home_phone, email, fax, photo_file_name, photo_content_type, date_join, salary_date, bank_name, DA, others1, others2', 'length', 'max'=>255),
				array('gender', 'length', 'max'=>10),
				array('joining_date, experience_detail, date_of_birth, mobile_phone, photo_data, created_at, updated_at, passport_expiry,staff_type,tds_type,ESI,EPF', 'safe'),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, uid, employee_category_id, employee_number, joining_date, first_name, middle_name, last_name, gender, job_title, employee_position_id, employee_department_id, reporting_manager_id, employee_grade_id, qualification, experience_detail, experience_year, experience_month, status, status_description, date_of_birth, marital_status, children_count, father_name, mother_name, husband_name, blood_group, nationality_id, home_address_line1, home_address_line2, home_city, home_state, home_country_id, home_pin_code, office_address_line1, office_address_line2, office_city, office_state, office_country_id, office_pin_code, office_phone1, office_phone2, mobile_phone, home_phone, email, fax, photo_file_name, photo_content_type, photo_data, created_at, updated_at, photo_file_size, user_id, is_deleted, date_join, salary_date, bank_name, bank_acc_no, basic_pay, HRA, PF, TDS, DA, others1, others2, passport_no, passport_expiry, user_type', 'safe', 'on'=>'search'),
			);
		}
		else{
			return array(
				array('first_name, last_name, joining_date, gender, mobile_phone,email,date_of_birth, staff_type, bank_name, bank_acc_no, basic_pay', 'required'),
				array('uid, employee_category_id, employee_position_id, employee_department_id, reporting_manager_id, employee_grade_id, experience_year, experience_month, status, children_count, nationality_id, home_country_id, office_country_id, photo_file_size, user_id, is_deleted, bank_acc_no, basic_pay, HRA, PF, passport_no, user_type', 'numerical', 'integerOnly'=>true),
				array('TDS,ESI,EPF','checkInt'),
				array('employee_number, first_name, middle_name, last_name, job_title, qualification, status_description, marital_status, father_name, mother_name, husband_name, blood_group, home_address_line1, home_address_line2, home_city, home_state, home_pin_code, office_address_line1, office_address_line2, office_city, office_state, office_pin_code, office_phone1, office_phone2, mobile_phone, home_phone, email, fax, photo_file_name, photo_content_type, date_join, salary_date, bank_name, DA, others1, others2', 'length', 'max'=>255),
				array('gender', 'length', 'max'=>10),
				array('joining_date, experience_detail, date_of_birth, photo_data, created_at, updated_at, passport_expiry,staff_type,tds_type,ESI,EPF', 'safe'),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, uid, employee_category_id, employee_number, joining_date, first_name, middle_name, last_name, gender, job_title, employee_position_id, employee_department_id, reporting_manager_id, employee_grade_id, qualification, experience_detail, experience_year, experience_month, status, status_description, date_of_birth, marital_status, children_count, father_name, mother_name, husband_name, blood_group, nationality_id, home_address_line1, home_address_line2, home_city, home_state, home_country_id, home_pin_code, office_address_line1, office_address_line2, office_city, office_state, office_country_id, office_pin_code, office_phone1, office_phone2, mobile_phone, home_phone, email, fax, photo_file_name, photo_content_type, photo_data, created_at, updated_at, photo_file_size, user_id, is_deleted, date_join, salary_date, bank_name, bank_acc_no, basic_pay, HRA, PF, TDS, DA, others1, others2, passport_no, passport_expiry, user_type', 'safe', 'on'=>'search'),
			);
		}
	}

	
	public function checkInt($attribute,$params)
	{
		if($this->$attribute!="" and $this->$attribute!=0){
			if(filter_var($this->$attribute,FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^([0-9]*[.])?[0-9]+$/")))){
			}else{
				$this->addError($attribute,$this->getAttributeLabel($attribute).' '.Yii::t("app",'must be a number'));
			}
		}else{
			$this->$attribute 	=	0;
		}
	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'uid' => 'Uid',
			'employee_category_id' => Yii::t('app','Employee Category'),
			'employee_number' => Yii::t('app','Employee Number'),
			'joining_date' => Yii::t('app','Joining Date'),
			'first_name' => Yii::t('app','First Name'),
			'middle_name' => Yii::t('app','Middle Name'),
			'last_name' => Yii::t('app','Last Name'),
			'gender' => Yii::t('app','Gender'),
			'job_title' => Yii::t('app','Job Title'),
			'employee_position_id' => Yii::t('app','Employee Position'),
			'employee_department_id' => Yii::t('app','Department'),
			'reporting_manager_id' => Yii::t('app','Reporting Manager'),
			'employee_grade_id' => Yii::t('app','Employee Grade'),
			'qualification' =>Yii::t('app','Qualification'),
			'experience_detail' => Yii::t('app','Experience Detail'),
			'experience_year' => Yii::t('app','Experience Year'),
			'experience_month' => Yii::t('app','Experience Month'),
			'status' => Yii::t('app','Status'),
			'status_description' => Yii::t('app','Status Description'),
			'date_of_birth' => Yii::t('app','Date Of Birth'),
			'marital_status' => Yii::t('app','Marital Status'),
			'children_count' =>Yii::t('app','Children Count'),
			'father_name' => Yii::t('app','Father Name'),
			'mother_name' => Yii::t('app','Mother Name'),
			'husband_name' => Yii::t('app','Husband Name'),
			'blood_group' => Yii::t('app','Blood Group'),
			'nationality_id' => Yii::t('app','Nationality'),
			'home_address_line1' => Yii::t('app','Home Address Line1'),
			'home_address_line2' => Yii::t('app','Home Address Line2'),
			'home_city' => Yii::t('app','Home City'),
			'home_state' => Yii::t('app','Home State'),
			'home_country_id' => Yii::t('app','Home Country'),
			'home_pin_code' => Yii::t('app','Home Pin Code'),
			'office_address_line1' => Yii::t('app','Office Address Line1'),
			'office_address_line2' => Yii::t('app','Office Address Line2'),
			'office_city' => Yii::t('app','Office City'),
			'office_state' => Yii::t('app','Office State'),
			'office_country_id' => Yii::t('app','Office Country'),
			'office_pin_code' => Yii::t('app','Office Pin Code'),
			'office_phone1' => Yii::t('app','Office Phone1'),
			'office_phone2' => Yii::t('app','Office Phone2'),
			'mobile_phone' => Yii::t('app','Mobile Phone'),
			'home_phone' => Yii::t('app','Home Phone'),
			'email' => Yii::t('app','Email'),
			'fax' => Yii::t('app','Fax'),
			'photo_file_name' => Yii::t('app','Photo File Name'),
			'photo_content_type' => Yii::t('app','Photo Content Type'),
			'photo_data' => Yii::t('app','Photo Data'),
			'created_at' => Yii::t('app','Created At'),
			'updated_at' => Yii::t('app','Updated At'),
			'photo_file_size' => Yii::t('app','Photo File Size'),
			'user_id' => Yii::t('app','User'),
			'is_deleted' => Yii::t('app','Is Deleted'),
			'date_join' => Yii::t('app','Date Join'),
			'salary_date' => Yii::t('app','Salary Date'),
			'bank_name' => Yii::t('app','Bank Name'),
			'bank_acc_no' => Yii::t('app','Bank Acc No'),
			'basic_pay' => Yii::t('app','Basic Pay'),
			'HRA' => Yii::t('app','HRA'),
			'PF' =>Yii::t('app','PF'),
			'TDS' => Yii::t('app','TDS'),
			'tds_type' => Yii::t('app','Type'),
			'ESI' => Yii::t('app','ESI'),
			'EPF' => Yii::t('app','EPF'),
			'DA' => Yii::t('app','DA'),
			'others1' => Yii::t('app','Others1'),
			'others2' => Yii::t('app','Others2'),
			'passport_no' => Yii::t('app','Passport No'),
			'passport_expiry' => Yii::t('app','Passport Expiry'),
			'user_type' => Yii::t('app','User Type'),
		);
	}
	
	public function getFullname(){
		return ucfirst($this->first_name)." ".ucfirst($this->middle_name)." ".ucfirst($this->last_name);
	}
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('employee_category_id',$this->employee_category_id);
		$criteria->compare('employee_number',$this->employee_number,true);
		$criteria->compare('joining_date',$this->joining_date,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('middle_name',$this->middle_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('job_title',$this->job_title,true);
		$criteria->compare('employee_position_id',$this->employee_position_id);
		$criteria->compare('employee_department_id',$this->employee_department_id);
		$criteria->compare('reporting_manager_id',$this->reporting_manager_id);
		$criteria->compare('employee_grade_id',$this->employee_grade_id);
		$criteria->compare('qualification',$this->qualification,true);
		$criteria->compare('experience_detail',$this->experience_detail,true);
		$criteria->compare('experience_year',$this->experience_year);
		$criteria->compare('experience_month',$this->experience_month);
		$criteria->compare('status_description',$this->status_description,true);
		$criteria->compare('date_of_birth',$this->date_of_birth,true);
		$criteria->compare('mobile_phone',$this->mobile_phone,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('fax',$this->fax,true);
		$criteria->compare('salary_date',$this->salary_date,true);
		$criteria->compare('bank_name',$this->bank_name,true);
		$criteria->compare('bank_acc_no',$this->bank_acc_no);
		$criteria->compare('basic_pay',$this->basic_pay);
		$criteria->compare('HRA',$this->HRA);
		$criteria->compare('PF',$this->PF);
		$criteria->compare('TDS',$this->TDS);
		$criteria->compare('DA',$this->DA,true);
		$criteria->compare('others1',$this->others1,true);
		$criteria->compare('others2',$this->others2,true);
		$criteria->compare('passport_no',$this->passport_no);
		$criteria->compare('passport_expiry',$this->passport_expiry,true);
		$criteria->compare('user_type',$this->user_type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}