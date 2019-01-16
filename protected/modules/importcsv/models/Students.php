<?php
class Students extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Students the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public $status;
	public $dobrange;
	public $admissionrange;
	public $task_type;
	
	private $_model;
	private $_modelReg;
	private $_rules = array();
	public $max_adm_no;
	

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'students';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		if (!$this->_rules) {
			$not_required	= array("country_id", "nationality_id");
			$required 		= array();
			$numerical 		= array();	
			$decimal 		= array();
			$safe			= array();
			$rules 			= array();			
			$model			= $this->getFields();			
			foreach ($model as $field) {
				$field_rule 	= array();
				$rule_added		= false;
				if (!in_array($field->varname, $not_required) and $field->required==FormFields::REQUIRED_YES){					
					array_push($required,$field->varname);
					$rule_added		= true;
				}
				if ($field->field_type=='DECIMAL'){
					array_push($decimal,$field->varname);
					$rule_added		= true;
				}
				if ($field->field_type=='INTEGER'){
					array_push($numerical,$field->varname);
					$rule_added		= true;
				}
				
				if($rule_added==false){
					array_push($safe,$field->varname);
				}
			}
			array_push($rules,array(implode(',',$required), 'required'));
			array_push($rules,array(implode(',',$numerical), 'numerical', 'integerOnly'=>true));			
			array_push($rules,array(implode(',',$decimal), 'match', 'pattern' => '/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/'));
			array_push($rules,array(implode(',',$safe), 'safe'));
			array_push($rules,array('email','email'));
			array_push($rules,array('admission_no, phone1, email','unique'));
			array_push($rules,array('email','check'));
			array_push($rules,array('national_student_id','checkNationalId'));
			array_push($rules,array('photo_data', 'file', 'types'=>'jpg, gif, png', 'allowEmpty' => true));
			$this->_rules = $rules;
		}

		return $this->_rules;                                   
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
	
	public function defaultScope()
	{
	 	if((isset(Yii::app()->controller->module->id) and (Yii::app()->controller->module->id=="onlineadmission")) or (isset(Yii::app()->controller->module->id) and (Yii::app()->controller->module->id=="courses") and Yii::app()->controller->action->id == 'waitinglist') or (isset(Yii::app()->controller->module->id) and (Yii::app()->controller->module->id=="parentportal") and Yii::app()->controller->action->id == 'checkStatus')){
	  		return parent::defaultScope();
	 	}else{
	  		return array(
	   			'condition'=> $this->getTableAlias(false, false).".type=:not_online",
	   			'params' => array(":not_online"=>0)
	  		);
	 	} 
	}
	public function check($attribute,$params)
    {
		$guardians= Guardians::model()->findByAttributes(array('email'=>$this->$attribute,'is_delete'=>'0'));
		$employee= Employees::model()->findByAttributes(array('email'=>$this->$attribute,'is_deleted'=>'0'));
		$validate = User::model()->findByAttributes(array('email'=>$this->$attribute));
		
		if($this->$attribute!='')
		{
			if(($validate!=NULL and $validate->id!=$this->uid) or $employee!=NULL or $guardians!=NULL)
			{
				$this->addError($attribute,Yii::t("app",'Email ').'"'.$this->$attribute.'"'.Yii::t('app',' has already been taken'));
			}
		}
    }
	
	//check the phone number is unique
	/*public function check_phone($attribute,$params)
    {
		
		$student= Students::model()->findByAttributes(array('phone1'=>$this->$attribute));
		$employee= Employees::model()->findByAttributes(array('mobile_phone'=>$this->$attribute));
		$parent= Guardians::model()->findByAttributes(array('mobile_phone'=>$this->$attribute));
		
		if(Yii::app()->controller->action->id!='update' and $this->$attribute!='')
		{
			
			if($student!=NULL or $employee!=NULL or $parent!=NULL)
			{
			
				$this->addError($attribute,' Mobile Phone already in use');
			}
		}
		elseif(Yii::app()->controller->action->id == 'update' and $this->$attribute!='')
		{
			if($student!=NULL or $employee!=NULL or $parent!=NULL)
			{
				if($student->id != $this->id)
					$this->addError($attribute,'Phone Number already in use');
			}
		}
    }*/
	//end 
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	 
	public function attributeLabels()
	{
		$labels = array(
			'uid' => Yii::t('app','User ID'),
			'id' => Yii::t("app",'ID'),
			'course_id' => Yii::t("app",'Course')
		);
		$model=$this->getFields();
		
		foreach ($model as $field)
			$labels[$field->varname] = Yii::t('app',$field->title);
			
		return $labels;		
	}

	public function scopes()
	{
		return array(
			'lastRecord'=>array(
				'condition' => 'is_online=1',
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
		$criteria->compare('admission_no',$this->admission_no,true);
		$criteria->compare('class_roll_no',$this->class_roll_no,true);
		$criteria->compare('admission_date',$this->admission_date,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('middle_name',$this->middle_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('batch_id',$this->batch_id);
		$criteria->compare('date_of_birth',$this->date_of_birth,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('blood_group',$this->blood_group,true);
		$criteria->compare('birth_place',$this->birth_place,true);
		$criteria->compare('nationality_id',$this->nationality_id);
		$criteria->compare('language',$this->language,true);
		$criteria->compare('religion',$this->religion,true);
		$criteria->compare('student_category_id',$this->student_category_id);
		$criteria->compare('address_line1',$this->address_line1,true);
		$criteria->compare('address_line2',$this->address_line2,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('pin_code',$this->pin_code,true);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('phone1',$this->phone1,true);
		$criteria->compare('phone2',$this->phone2,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('immediate_contact_id',$this->immediate_contact_id);
		$criteria->compare('is_sms_enabled',$this->is_sms_enabled);
		$criteria->compare('photo_file_name',$this->photo_file_name,true);
		$criteria->compare('photo_content_type',$this->photo_content_type,true);
		$criteria->compare('photo_data',$this->photo_data,true);
		$criteria->compare('status_description',$this->status_description,true);
		$criteria->compare('is_active',$this->is_active);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->compare('has_paid_fees',$this->has_paid_fees);
		$criteria->compare('photo_file_size',$this->photo_file_size);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function getval()
	{
		return '"123"';
	}
	
	public function getFullname()
	{
	
		return '</td><td style="padding-left:15px;">'.CHtml::link($this->first_name.' '.$this->last_name, array('/students/students/view', 'id'=>$this->id)).'
								   </td><td style="padding-left:15px;">'.$this->admission_no.'</td>'.
								 '</tr>';
									 
	}
    

	public function checkNationalId($attribute,$params)
    {
		$model= Students::model()->findByAttributes(array('national_student_id'=>$this->$attribute,'is_deleted'=>'0'));
		
		if($this->$attribute!='')
		{
			if(($model!=NULL and $model->id!=$this->id))
			{
				$this->addError($attribute,$this->getAttributeLabel('national_student_id').' '.Yii::t("app",'already exist'));
			}
		}
    }

    public function getFullnames()
	{
            $name 	= "";

		if(FormFields::model()->isVisible('first_name', 'Students', 'forStudentProfile'))
                {
                    $name 	.= ucfirst($this->first_name);
                }

                if(FormFields::model()->isVisible('middle_name','Students', 'forStudentProfile'))
                {
                    $name 	.= (($name!="")?" ":"").ucfirst($this->middle_name);
                }

                if(FormFields::model()->isVisible('last_name','Students', 'forStudentProfile'))
                {
                    $name 	.= (($name!="")?" ":"").ucfirst($this->last_name);
                }
                
                if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))                                            
                {
                    return '</td><td style="padding-left:15px;">'.CHtml::link($name, array('/students/students/view', 'id'=>$this->id)).'
								   </td><td style="padding-left:15px;">'.$this->admission_no.'</td>'.
								 '</tr>';
                }
                else
                {
                    return '</td><td style="padding-left:15px;">'.$this->admission_no.'</td>'.
								 '</tr>';
                }
	
		
									 
	}
	
	public function getT_fullname()
	{
            $name= "";
            $no="";
            if(FormFields::model()->isVisible('first_name','Students','forTeacherPortal'))
            {
                $name.= $this->first_name;
            }
            if(FormFields::model()->isVisible('last_name','Students','forTeacherPortal'))
            {
                $name.= " ".$this->last_name;
            }
            if(FormFields::model()->isVisible('admission_no','Students','forTeacherPortal'))
            {
                $no= $this->admission_no;
            }
			if(Yii::app()->controller->module->id == 'teachersportal'){
				if($this->studentFullName('forTeacherPortal')){
					return '</td><td>'.$name.'</td><td >'.$no.'</td>'.'</tr>';	
				}else{
					return '</td><td >'.$no.'</td>'.'</tr>';								   								 
				}
			}else{
            	return '</td><td>'.$name.'</td><td >'.$no.'</td>'.'</tr>';								   								 
			}
            
            //		return '</td><td>'.$this->first_name.' '.$this->last_name.'
            //								   </td><td >'.$this->admission_no.'</td>'.
            //								 '</tr>';
									 
	}
	public function getStudentname()
	{
		return ucfirst($this->first_name).' '.ucfirst($this->middle_name).' '.ucfirst($this->last_name);
	}
	
	public function getStudentnameforstudentprofile()
	{
		$name 	= "";

		if(FormFields::model()->isVisible('first_name', 'Students', 'forStudentProfile'))
        {
            $name 	.= ucfirst($this->first_name);
        }

        if(FormFields::model()->isVisible('middle_name','Students', 'forStudentProfile'))
        {
            $name 	.= (($name!="")?" ":"").ucfirst($this->middle_name);
        }

        if(FormFields::model()->isVisible('last_name','Students', 'forStudentProfile'))
        {
            $name 	.= (($name!="")?" ":"").ucfirst($this->last_name);
        }
		if($name !=""){
        	return $name;
		}else{
			return '-';
		}
	}
	
	public function getStudentnameforparentportal()
	{
		$name 	= "";

		if(FormFields::model()->isVisible('first_name', 'Students', 'forParentPortal'))
        {
            $name 	.= ucfirst($this->first_name);
        }

        if(FormFields::model()->isVisible('middle_name','Students', 'forParentPortal'))
        {
            $name 	.= (($name!="")?" ":"").ucfirst($this->middle_name);
        }

        if(FormFields::model()->isVisible('last_name','Students', 'forParentPortal'))
        {
            $name 	.= (($name!="")?" ":"").ucfirst($this->last_name);
        }
		if($name !=NULL){
        	return $name;
		}else{
			return '-';
		}
	}

	public function studentFullName($scope='forStudentProfile'){
		$name 	= "";

		if(FormFields::model()->isVisible('first_name', 'Students', $scope))
        {
            $name 	.= ucfirst($this->first_name);
        }

        if(FormFields::model()->isVisible('middle_name','Students', $scope))
        {
            $name 	.= (($name!="")?" ":"").ucfirst($this->middle_name);
        }

        if(FormFields::model()->isVisible('last_name','Students', $scope))
        {
            $name 	.= (($name!="")?" ":"").ucfirst($this->last_name);
        }

        return $name;
	}
        
        
	
//Student Profile Image Path
	public function getProfileImagePath($id){
		$model = Students::model()->findByPk($id);
		$path = 'uploadedfiles/student_profile_image/'.$model->id.'/'.$model->photo_file_name;	
		return $path;
	}
//Get the fiedls from form_fields	
	public function getFields() {
		$scope 		= NULL;
		$model 		= "Students";
		$scope 		= Yii::app()->controller->module->scope;

		$criteria 				= new CDbCriteria;
		$criteria->condition	= "`model`=:model";
		$criteria->params		= array(':model'=>$model);
		$criteria->order		= "`id` ASC";
		return FormFields::model()->$scope()->findAll($criteria);
	}
	
	public function getDynamicFields() {
		$scope 		= NULL;
		$model 		= "Students";
		$scope 		= Yii::app()->controller->module->scope;

		$criteria 				= new CDbCriteria;
		$criteria->condition	= "`model`=:model AND `is_dynamic`=:is_dynamic";
		$criteria->params		= array(':model'=>$model, ':is_dynamic'=>1, ':is_exception'=>0);
		$criteria->order		= "`position` ASC";
		return FormFields::model()->$scope()->findAll($criteria);
	}
	
//Online Admission Functions
// Approve process 	
	public function approveProcess($id,$batch)
	{     	 		
		$registered_student = Students::model()->findByAttributes(array('id'=>$id));
		$registered_guardian = Guardians::model()->findByAttributes(array('id'=>$registered_student->parent_id));
		
		//Manage waiting list
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
				
		//Find the largest admssion no
		//$criteria1 = new CDbCriteria;
		//$criteria1->select='MAX(admission_no) as max_adm_no';				
		//$adm_no	= Students::model()->find($criteria1);
		$adm_no = Yii::app()->db->createCommand()
				  ->select("MAX(CAST(admission_no AS UNSIGNED)) as `max_adm_no`")
				  ->from('students')				 
				  ->queryRow();
				  
		$registered_student->admission_no = $adm_no['max_adm_no']+1;				
		$registered_student->admission_date = date('Y-m-d');
		$registered_student->batch_id = $batch;
		$registered_student->created_at = date('Y-m-d H:i:s');
		$registered_student->updated_at = '';
		$registered_student->type = 0;
		$registered_student->user_id = Yii::app()->user->id;
				
		if($registered_student->save())
		{
			
			if($registered_student->phone1)
			{
				$student_no = $registered_student->phone1;	
			}
			elseif($registered_student->phone2)
			{
				$student_no = $registered_student->phone2;
			}
		//create student user	
			$student_uid = Students::model()->createUser($registered_student->id,$registered_student->first_name,$registered_student->last_name,$registered_student->email,$student_no,'student');
			if($student_uid)
			{
				  //saving user id to students table.
				$registered_student->saveAttributes(array('uid'=>$student_uid));	
				
			}			
			
			
			// Saving to batch_student table to get current and previous batches of the student
			  if($registered_student->batch_id)
			  {
				  $current_academic_yr = Configurations::model()->findByPk(35);
				  $batch_student = BatchStudents::model()->findAll('student_id=:x AND batch_id=:y',array(':x'=>$registered_student->id,':y'=>$registered_student->batch_id));
				  if(!$batch_student)
				  {
					  $new_batch = new BatchStudents;
					  $new_batch->student_id = $registered_student->id;
					  $new_batch->batch_id = $registered_student->batch_id;
					  $new_batch->academic_yr_id = $current_academic_yr->config_value;
					  $new_batch->status =1;
					  $new_batch->save();
				  }
			  }			 
		}
				
		if(isset($registered_guardian) and $registered_guardian->uid == 0)
		{									
			$guardian_no = $registered_guardian->mobile_phone;	
			$registered_student->saveAttributes(array('immediate_contact_id'=>$registered_guardian->id));
			$parent_uid = Students::model()->createUser($registered_guardian->id,$registered_guardian->first_name,$registered_guardian->last_name,$registered_guardian->email,$guardian_no,'parent');			
			if($parent_uid){				
				$registered_guardian->saveAttributes(array('uid'=>$parent_uid));	
			}									
		}
		else
		{			
			$registered_student->saveAttributes(array('immediate_contact_id'=>$registered_guardian->id));			
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
		$registered_student->saveAttributes(array('status'=>1));
		Yii::app()->user->setFlash('successMessage', Yii::t("app","Action performed successfully"));
				
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
		$student = Students::model()->findByAttributes(array('id'=>$id));	
		$parent = Guardians::model()->findByAttributes(array('id'=>$student->parent_id));	
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
	
	public function getPrimaryGuardian($student_id){		
		$student				= Students::model()->findByPk($student_id);
		if($student!=NULL and $student->parent_id!=NULL and $student->parent_id!=0){
			$guardian 				= Guardians::model()->findByPk($student->parent_id);
			return $guardian;
		}
		
		return NULL;
	}
}