<?php

/**
 * This is the model class for table "registered_students".
 *
 * The followings are the available columns in table 'registered_students':
 * @property integer $id
 * @property integer $registration_id
 * @property string $password
 * @property integer $parent_id
 * @property string $registration_date
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property integer $batch_id
 * @property string $date_of_birth
 * @property string $blood_group
 * @property string $gender
 * @property string $birth_place
 * @property integer $nationality_id
 * @property string $language
 * @property string $religion
 * @property integer $student_category_id
 * @property string $photo_file_name
 * @property string $photo_content_type
 * @property integer $photo_file_size
 * @property string $photo_data
 * @property string $address_line1
 * @property string $address_line2
 * @property string $city
 * @property string $state
 * @property string $pin_code
 * @property integer $country_id
 * @property string $phone1
 * @property string $phone2
 * @property string $email
 * @property integer $status
 * @property integer $is_deleted
 * @property string $created_at
 * @property string $updated_at
 * @property integer $agent
 */
class RegisteredStudents extends CActiveRecord
{
	public $course;
	/**
	 * Returns the static model of the specified AR class.
	 * @return RegisteredStudents the static model class
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
		return 'registered_students';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('first_name,middle_name,birth_place,language,religion,address_line1,address_line2,city,state,pin_code,phone1,phone2,last_name','filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
			array('first_name, last_name, gender, date_of_birth, address_line1, address_line2, city, state, pin_code, country_id, phone1, email,nationality_id', 'required',),
			array('email','email'),
			array('email','check'),
			array('email','unique'),
			array('registration_id, parent_id, batch_id, nationality_id, student_category_id, photo_file_size, country_id, status, is_deleted, agent', 'numerical', 'integerOnly'=>true),
			array('password, first_name, middle_name, last_name, blood_group, gender, birth_place, language, religion, photo_file_name, photo_content_type, address_line1, address_line2, city, state, pin_code, phone1, phone2, email', 'length', 'max'=>255),
			array('date_of_birth, created_at, updated_at,is_completed, course, academic_yr', 'safe'),			
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('photo_data', 'file', 'types'=>'jpg, gif, png', 'allowEmpty' => true),
			array('id, registration_id, password, parent_id, registration_date, first_name, middle_name, last_name, batch_id, date_of_birth, blood_group, gender, birth_place, nationality_id, language, religion, student_category_id, photo_file_name, photo_content_type, photo_file_size, photo_data, address_line1, address_line2, city, state, pin_code, country_id, phone1, phone2, email, status, is_deleted, created_at, updated_at, agent', 'safe', 'on'=>'search'),
		);
	}
	
	public function check($attribute,$params)
    {		
		$guardians	= Guardians::model()->findByAttributes(array('email'=>$this->$attribute,'is_delete'=>'0'));
		$employee	= Employees::model()->findByAttributes(array('email'=>$this->$attribute,'is_deleted'=>'0'));
		$user		= User::model()->findByAttributes(array('email'=>$this->$attribute));
		$student	= Students::model()->findByAttributes(array('email'=>$this->$attribute,'is_deleted'=>'0'));
		$registered_guardian = RegisteredGuardians::model()->findByAttributes(array('email'=>$this->$attribute));
		if($this->$attribute!='')
		{
			if($guardians!=NULL or $employee!=NULL or $user!=NULL or $student!=NULL or $registered_guardian!=NULL)
			{
				$this->addError($attribute,Yii::t("app",'Email already in use'));
			}
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
			'id' => Yii::t("app",'ID'),
			'registration_id' => Yii::t("app",'Registration'),
			'password' => Yii::t("app",'Password'),
			'parent_id' => Yii::t("app",'Parent'),
			'registration_date' => Yii::t("app",'Registration Date'),
			'first_name' => Yii::t("app",'First Name'),
			'middle_name' => Yii::t("app",'Middle Name'),
			'last_name' => Yii::t("app",'Last Name'),
			'batch_id' => Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),
			'date_of_birth' => Yii::t("app",'Date Of Birth'),
			'blood_group' => Yii::t("app",'Blood Group'),
			'gender' => Yii::t("app",'Gender'),
			'birth_place' => Yii::t("app",'Birth Place'),
			'nationality_id' => Yii::t("app",'Nationality'),
			'language' => Yii::t("app",'Language'),
			'religion' => Yii::t("app",'Religion'),
			'student_category_id' => Yii::t("app",'Student Category'),
			'photo_file_name' => Yii::t("app",'Photo File Name'),
			'photo_content_type' => Yii::t("app",'Photo Content Type'),
			'photo_file_size' => Yii::t("app",'Photo File Size'),
			'photo_data' => Yii::t("app",'Photo Data'),
			'address_line1' => Yii::t("app",'Address Line 1'),
			'address_line2' => Yii::t("app",'Address Line 2'),
			'city' => Yii::t("app",'City'),
			'state' => Yii::t("app",'State'),
			'pin_code' => Yii::t("app",'Pin Code'),
			'country_id' => Yii::t("app",'Country'),
			'phone1' => Yii::t("app",'Phone 1'),
			'phone2' => Yii::t("app",'Phone 2'),
			'email' => Yii::t("app",'Email'),
			'status' => Yii::t("app",'Status'),
			'is_completed' => Yii::t("app",'Registration Completed'),
			'is_deleted' => Yii::t("app",'Is Deleted'),
			'created_at' => Yii::t("app",'Created At'),
			'updated_at' => Yii::t("app",'Updated At'),
			'agent' => Yii::t("app",'Agent'),
			'course' => Yii::t("app",'Course'),
		);
	}
	
	public function scopes()
	{
		return array(
			'lastRecord'=>array(
				'order'=>'id DESC',
				'limit'=>1,
			),
		);
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
		$criteria->compare('registration_id',$this->registration_id);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('registration_date',$this->registration_date,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('middle_name',$this->middle_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('batch_id',$this->batch_id);
		$criteria->compare('date_of_birth',$this->date_of_birth,true);
		$criteria->compare('blood_group',$this->blood_group,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('birth_place',$this->birth_place,true);
		$criteria->compare('nationality_id',$this->nationality_id);
		$criteria->compare('language',$this->language,true);
		$criteria->compare('religion',$this->religion,true);
		$criteria->compare('student_category_id',$this->student_category_id);
		$criteria->compare('photo_file_name',$this->photo_file_name,true);
		$criteria->compare('photo_content_type',$this->photo_content_type,true);
		$criteria->compare('photo_file_size',$this->photo_file_size);
		$criteria->compare('photo_data',$this->photo_data,true);
		$criteria->compare('address_line1',$this->address_line1,true);
		$criteria->compare('address_line2',$this->address_line2,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('pin_code',$this->pin_code,true);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('phone1',$this->phone1,true);
		$criteria->compare('phone2',$this->phone2,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->compare('agent',$this->agent);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
// Approve process 	
	public function approveProcess($id,$batch)
	{     
	 
		$new_student = new Students;
		$new_guardian = new Guardians;
		
		$registered_student = RegisteredStudents::model()->findByAttributes(array('id'=>$id));
		$waitinglist = WaitinglistStudents::model()->findByAttributes(array('student_id'=>$id));
		if($waitinglist!=NULL)
		{
			$criteria = new CDbCriteria;
			$criteria->condition = 'batch_id=:batch_id AND priority>:priority';
			$criteria->params[':batch_id'] = $waitinglist->batch_id;
			$criteria->params[':priority'] = $waitinglist->priority;						
			$DetailsOfStudent = WaitinglistStudents::model()->findAll($criteria);
			foreach($DetailsOfStudent as $change)
			{
				$change->saveAttributes(array('priority'=>$change->priority - 1));
			}
			$waitinglist->delete();
		}
		$registered_guardian = RegisteredGuardians::model()->findByAttributes(array('id'=>$registered_student->parent_id));
		
		
		$existing_guardian = Guardians::model()->findByAttributes(array('email'=>$registered_guardian->email));
		
		
		$new_student->attributes = $registered_student->attributes;
		$new_guardian->attributes = $registered_guardian->attributes;
		
		
		$adm_no	= Students::model()->find(array('order' => 'id DESC','limit' => 1));
		
		$new_student->admission_no = $adm_no->admission_no+1;
		$new_student->admission_date = date('Y-m-d');
		$new_student->batch_id = $batch;
		$new_student->created_at = date('Y-m-d H:i:s');
		$new_student->updated_at = '';
		$new_student->user_id = Yii::app()->user->id;
		
		
		
				
		if($new_student->save())
		{
			
			if($new_student->phone1)
			{
				$student_no = $new_student->phone1;	
			}
			elseif($new_student->phone2)
			{
				$student_no = $new_student->phone2;
			}
		//create student user	
			$student_uid = RegisteredStudents::model()->createUser($new_student->id,$new_student->first_name,$new_student->last_name,$new_student->email,$student_no,'student');
			if($student_uid)
			{
				  //saving user id to students table.
				$new_student->saveAttributes(array('uid'=>$student_uid));	
				
			}
			//$new_student->saveAttributes(array('uid'=>0));	
			
			
			// Saving to batch_student table to get current and previous batches of the student
			  if($new_student->batch_id)
			  {
				  $current_academic_yr = Configurations::model()->findByPk(35);
				  $batch_student = BatchStudents::model()->findAll('student_id=:x AND batch_id=:y',array(':x'=>$new_student->id,':y'=>$new_student->batch_id));
				  if(!$batch_student)
				  {
					  $new_batch = new BatchStudents;
					  $new_batch->student_id = $new_student->id;
					  $new_batch->batch_id = $new_student->batch_id;
					  $new_batch->academic_yr_id = $current_academic_yr->config_value;
					  $new_batch->status =1;
					  $new_batch->save();
				  }
			  }
			  
//Saving the student Document
			$online_student_documents = OnlineStudentDocument::model()->findAllByAttributes(array('student_id'=>$registered_student->id));
			foreach($online_student_documents as $online_student_document)
			{
				$studentDocumentList = StudentDocumentList::model()->findByAttributes(array('id'=>$online_student_document->title));
				$student_document = new StudentDocument;
				$student_document->student_id = $new_student->id;
				$student_document->file = $online_student_document->file;
				$student_document->title = $studentDocumentList->name;
				$student_document->file_type = $online_student_document->file_type;
				$student_document->is_approved = 0;
				$student_document->uploaded_by = 0;	
				$student_document->save();			
			}
			
		//Rename the foldername of student document to current student id
			$existing_folder_name = 'uploadedfiles/student_document/'.'online_'.$registered_student->id;
			$new_folder_name = 'uploadedfiles/student_document/'.$new_student->id;
			rename($existing_folder_name,$new_folder_name);
			
		//Saving the profile image
			if($registered_student->photo_file_name!=NULL){
				$online_path = 'uploadedfiles/online_student_profile_image/'.$registered_student->id.'/'.$registered_student->photo_file_name;
				
				if(!is_dir('uploadedfiles/')){
					mkdir('uploadedfiles/');
				}
				if(!is_dir('uploadedfiles/student_profile_image/')){
					mkdir('uploadedfiles/student_profile_image/');
				}
				if(!is_dir('uploadedfiles/student_profile_image/'.$new_student->id)){
					mkdir('uploadedfiles/student_profile_image/'.$new_student->id);
				}				
				$destination_path = 'uploadedfiles/student_profile_image/'.$new_student->id.'/'.$new_student->photo_file_name;
				copy($online_path, $destination_path);	
			}
		}
		
		
//Saving The new guardian details	
		if(!$existing_guardian and $new_guardian->attributes!=NULL)
		{
			$new_guardian->created_at = date('Y-m-d H:i:s');		
			$new_guardian->updated_at = '';
			//$new_guardian->is_active = 1;			
				if($new_guardian->save())
				{
					//Save relation
					$relation = new GuardianList;
					$relation->guardian_id = $new_guardian->id;
					$relation->student_id = $new_student->id;
					$relation->relation = $registered_guardian->relation;
					$relation->save();
					
					$guardian_no = $new_guardian->mobile_phone;	
					$new_student->saveAttributes(array('parent_id'=>$new_guardian->id,'immediate_contact_id'=>$new_guardian->id));
					$parent_uid = RegisteredStudents::model()->createUser($new_guardian->id,$new_guardian->first_name,$new_guardian->last_name,$new_guardian->email,$guardian_no,'parent');
					
					if($parent_uid)
					{
						//saving user id to students table.
						$new_guardian->saveAttributes(array('uid'=>$parent_uid));	
					}
					
					
				}
				
		}
		else
		{			
			$new_student->saveAttributes(array('parent_id'=>$existing_guardian->id,'immediate_contact_id'=>$existing_guardian->id));
			//Save relation
			$relation = new GuardianList;
			$relation->guardian_id = $existing_guardian->id;
			$relation->student_id = $new_student->id;
			$relation->relation = $registered_guardian->relation;
			$relation->save();
			//$new_student->parent_id = $existing_guardian->id;
			//$new_student->save();
			$notification = NotificationSettings::model()->findByAttributes(array('id'=>14));
			$college=Configurations::model()->findByPk(1);
			if($notification->mail_enabled=='1' and $notification->parent_1=='1')
			{
				$url = Yii::app()->getBaseUrl(true);
				$email = EmailTemplates::model()->findByPk(21);
				$subject = $email->subject;
				$message = $email->template;
				$student = Students::model()->findByAttributes(array('parent_id'=>$existing_guardian->id));
				$subject = str_replace("{{SCHOOL}}",ucfirst($college->config_value),$subject);
				$message = str_replace("{{SCHOOL}}",ucfirst($college->config_value),$message);
				$message = str_replace("{{GUARDIAN}}",ucfirst($existing_guardian->first_name),$message);
				$message = str_replace("{{APPLICANT}}",ucfirst($new_student->first_name).' '.ucfirst($new_student->last_name),$message);
				$message = str_replace("{{USERNAME}}", Yii::t("app","Use Existing Username"),$message);
				$message = str_replace("{{PASSWORD}}", Yii::t("app","Use Existing Password"),$message);				
				$message = str_replace("{{LINK}}",$url,$message);
				$mailfunction_success = UserModule::sendMail($existing_guardian->email,$subject,$message);
			}
		//Send SMS
			if($notification->sms_enabled=='1' and $notification->parent_1=='1')
			{				
				$from = $college->config_value;				
				$sms_template = SystemTemplates::model()->findByAttributes(array('id'=>27));
				$sms_message = $sms_template->template;
				$message = str_replace("<School Name>",$college->config_value,$sms_message);
				$sms_success = SmsSettings::model()->sendSms($existing_guardian->mobile_phone,$from,$message);
			}
		//send message
			if($notification->parent_1 == '1' and $notification->msg_enabled == '1')
			{						
				$to = $existing_guardian->uid;
				$subject = Yii::t("app",'Welcome to ').$college->config_value;
				$message = Yii::t("app",'Hi, Welcome to ').$college->config_value.Yii::t("app",'. We are looking forward to your esteemed presence and cooperation with our organization.');
				$msg_success = NotificationSettings::model()->sendMessage($to,$subject,$message);		
			}	
			
		}
		$registered_student->saveAttributes(array('status'=>1,'agent'=>Yii::app()->user->id));
		Yii::app()->user->setFlash('successMessage', Yii::t("app","Action performed successfully"));
		
		//$this->redirect(array('approval'));
		return;
	}
	
//User creation during approve process	
	public function createUser($id,$first_name,$last_name,$email,$phone,$role)
	{
		
		$user = new User;
		$profile = new Profile;
		$user->username = substr(md5(uniqid(mt_rand(), true)), 0, 10);
		$user->email = $email;
		$user->activkey=UserModule::encrypting(microtime().$first_name);
		$password = substr(md5(uniqid(mt_rand(), true)), 0, 10);
		$user->password=UserModule::encrypting($password);
		$user->superuser=0;
		$user->status=1;
		
		if($user->save())
		{
			//assign role
			$authorizer = Yii::app()->getModule("rights")->getAuthorizer();
			$authorizer->authManager->assign($role, $user->id); 
			
			//profile
			$profile->firstname = $first_name;
			$profile->lastname = $last_name;
			$profile->user_id = $user->id;
			$profile->save();			
			
			$notification = NotificationSettings::model()->findByAttributes(array('id'=>14));
			if($notification->mail_enabled == '1' or $notification->sms_enabled == '1' or $notification->msg_enabled == '1')
			{	
				$mail_success = $this->sendApprovalMail($id,$email,$role,$profile->firstname,$user->username,$password,$phone,$user->id);
			}
			else
			{
				return $user->id;
			}
			
		}			
			return $user->id;
			
	}
//Send approval mail, sms, message to users	during approval process
	public function sendApprovalMail($id,$to,$role,$first_name,$username,$password,$phone,$uid)
	{
		$notification = NotificationSettings::model()->findByAttributes(array('id'=>14));
		$college=Configurations::model()->findByPk(1);
		if($role == 'student')
		{
			$student_email = EmailTemplates::model()->findByPk(22);
			$subject = $student_email->subject;
			$message = $student_email->template;			
		}
		elseif($role == 'parent')
		{
			$parent_email = EmailTemplates::model()->findByPk(21);
			$subject = $parent_email->subject;
			$message = $parent_email->template;
			$student = Students::model()->findByAttributes(array('parent_id'=>$id));
		}
		$url = Yii::app()->getBaseUrl(true);
		
		$subject = str_replace("{{SCHOOL}}",ucfirst($college->config_value),$subject);
		$message = str_replace("{{SCHOOL}}",ucfirst($college->config_value),$message);
		if($role == 'student')
		{
			$message = str_replace("{{APPLICANT}}",ucfirst($first_name),$message);
		}
		elseif($role == 'parent')
		{
			$message = str_replace("{{GUARDIAN}}",ucfirst($first_name),$message);
			$message = str_replace("{{APPLICANT}}",ucfirst($student->first_name).' '.ucfirst($student->last_name),$message);			
		}
		
		$message = str_replace("{{USERNAME}}",$username.' or '.$to,$message);
		$message = str_replace("{{PASSWORD}}",$password,$message);
		$message = str_replace("{{LINK}}",$url,$message);
		
	//send mail	
		if($role == 'student' and $notification->student == '1' and $notification->mail_enabled == '1')
		{								
			$mailfunction_success = UserModule::sendMail($to,$subject,$message);
		}elseif($role == 'parent' and $notification->parent_1 == '1' and $notification->mail_enabled == '1')
		{
			$mailfunction_success = UserModule::sendMail($to,$subject,$message);
		}		
	//send sms	
		if($role == 'student' and $notification->student == '1' and $notification->sms_enabled == '1')
		{											
			$from = $college->config_value;				
			$sms_template = SystemTemplates::model()->findByAttributes(array('id'=>29));
			$sms_message = $sms_template->template;
			$sms_success = SmsSettings::model()->sendSms($phone,$from,$sms_message);
			
		}elseif($role == 'parent' and $notification->parent_1 == '1' and $notification->sms_enabled == '1')
		{			
			$from = $college->config_value;				
			$sms_template = SystemTemplates::model()->findByAttributes(array('id'=>27));
			$sms_message = $sms_template->template;
			$message = str_replace("<School Name>",$college->config_value,$sms_message);
			$sms_success = SmsSettings::model()->sendSms($phone,$from,$message);			
		}
	//send message	
		if($role == 'student' and $notification->student == '1' and $notification->msg_enabled == '1')
		{														
			$to = $uid;
			$subject = Yii::t("app",'Welcome to ').$college->config_value;
			$message = Yii::t("app",'Hi, Welcome! Your study at ').$college->config_value.Yii::t("app",' is an important time of discovery, and we\'re here to support you along the way.');
			$msg_success = NotificationSettings::model()->sendMessage($to,$subject,$message);
			
		}elseif($role == 'parent' and $notification->parent_1 == '1' and $notification->msg_enabled == '1')
		{						
			$to = $uid;
			$subject = Yii::t("app",'Welcome to ').$college->config_value;
			$message = Yii::t("app",'Hi, Welcome to ').$college->config_value.Yii::t("app",'. We are looking forward to your esteemed presence and cooperation with our organization.');
			$msg_success = NotificationSettings::model()->sendMessage($to,$subject,$message);		
		}	
		
		//$headers = "MIME-Version: 1.0\r\nFrom: tanuja1990@gmail.com\r\nReply-To: tanuja1990@gmail.com\r\nContent-Type: text/html; charset=utf-8";
		//mail('tanuja@wiwoinc.com','subject','message',$headers);
		if($mailfunction_success or $sms_success or $msg_success)
		{
			
			return 1;
		}
		else
		{
			
			return 0;
		}
		
	}
//Send mail & sms during registration	
	public function sendRegistrationMail($id)
	{								
		$student = RegisteredStudents::model()->findByAttributes(array('id'=>$id));	
		$parent = RegisteredGuardians::model()->findByAttributes(array('id'=>$student->parent_id));	
		$url = Yii::app()->getBaseUrl(true).'/index.php?r=onlineadmission/registration/';
		$settings = UserSettings::model()->findByAttributes(array('user_id'=>1));
		if($settings!=NULL)
		{	
			$student->registration_date = date($settings->displaydate,strtotime($student->registration_date));
		}
		$college=Configurations::model()->findByPk(1);
		$notification = NotificationSettings::model()->findByAttributes(array('id'=>13));
	//Sending mail & sms to student 	
		if($notification->student == '1' and $notification->mail_enabled == '1')
		{
			$student_email = EmailTemplates::model()->findByPk(17);
			$subject = $student_email->subject;
			$message = $student_email->template;
			$subject = str_replace("{{SCHOOL}}",ucfirst($college->config_value),$subject);
			$message = str_replace("{{SCHOOL}}",ucfirst($college->config_value),$message);
			$message = str_replace("{{APPLICANT}}",ucfirst($student->first_name),$message);
			$message = str_replace("{{DATE}}",$student->registration_date,$message);
			$message = str_replace("{{ID}}",$student->registration_id,$message);
			$message = str_replace("{{PIN}}",$student->password,$message);
			$message = str_replace("{{LINK}}",$url,$message);
					
			UserModule::sendMail($student->email,$subject,$message);						
		}	
		if($notification->student == '1' and $notification->sms_enabled == '1')
		{				
			$from = $college->config_value;
			$sms_template = SystemTemplates::model()->findByAttributes(array('id'=>28));
			$sms_message = $sms_template->template;
			SmsSettings::model()->sendSms($student->phone1,$from,$sms_message);
		} 				
	//Send mail & sms to Parent
		if($notification->parent_1 == '1' and $notification->mail_enabled == '1')
		{			
			$parent_email = EmailTemplates::model()->findByPk(18);	
			$subject = $parent_email->subject;
			$message = $parent_email->template;								
			$subject = str_replace("{{SCHOOL}}",ucfirst($college->config_value),$subject);
			$message = str_replace("{{SCHOOL}}",ucfirst($college->config_value),$message);
			$message = str_replace("{{APPLICANT}}",ucfirst($student->first_name),$message);
			$message = str_replace("{{DATE}}",$student->registration_date,$message);
			$message = str_replace("{{ID}}",$student->registration_id,$message);
			$message = str_replace("{{PIN}}",$student->password,$message);
			$message = str_replace("{{LINK}}",$url,$message);
			
			UserModule::sendMail($parent->email,$subject,$message);
		}
	//Send SMS	
		if($notification->parent_1 == '1' and $notification->sms_enabled == '1')
		{				
			$from = $college->config_value;
			$sms_template = SystemTemplates::model()->findByAttributes(array('id'=>26));
			$sms_message = str_replace("<Student Name>",ucfirst($student->first_name).' '.ucfirst($student->last_name),$sms_template->template);
			$sms_message = str_replace("<School Name>",$college->config_value,$sms_message);
			SmsSettings::model()->sendSms($parent->mobile_phone,$from,$sms_message);
		}		
		return 1;
		
	}
	
	//Student Profile Image Path
	public function getProfileImagePath($id){
		$model = RegisteredStudents::model()->findByPk($id);
		$path = 'uploadedfiles/online_student_profile_image/'.$model->id.'/'.$model->photo_file_name;	
		return $path;
	}
}