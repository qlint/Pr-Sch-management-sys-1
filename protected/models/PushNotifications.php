<?php

/**
 * This is the model class for table "push_notifications".
 *
 * The followings are the available columns in table 'push_notifications':
 * @property integer $id
 * @property integer $notification_number
 * @property string $type
 * @property string $language
 * @property string $title
 * @property string $message
 */
class PushNotifications extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return PushNotifications the static model class
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
		return 'push_notifications';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('notification_number', 'numerical', 'integerOnly'=>true),
			array('type, language, title', 'length', 'max'=>255),
			array('message, description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, notification_number, type, language, title, message', 'safe', 'on'=>'search'),
		);
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
			'notification_number' => 'Notification Number',
			'type' => 'Type',
			'language' => 'Language',
			'title' => 'Title',
			'message' => 'Message',
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
		$criteria->compare('notification_number',$this->notification_number);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('language',$this->language,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('message',$this->message,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getNotificationDatas($notification_no) //Get all datas based on the notification number
	{
		$criteria				= new CDbCriteria();
		$criteria->condition	= 'notification_number=:notification_number';
		$criteria->params		= array(':notification_number'=>$notification_no);
		$push_notifications		= PushNotifications::model()->findAll($criteria);
		
		return CJSON::decode(CJSON::encode($push_notifications)); 
	}
	
	public function getLanguage($uid) //Get the user language
	{		
		$settings		= UserSettings::model()->findByAttributes(array('user_id'=>$uid));		
		$language		= 'en_us';
		if($settings != NULL){
			if($settings->language != NULL){
				$language	= $settings->language;
			}
		}
		return $language;
	}
	
	public function getKeyData($uid, $data_arr) //Get the key value of the notification data array
	{
		$language		= PushNotifications::model()->getLanguage($uid);							
		$key 			= array_search($language, array_column($data_arr, 'language'));
		if($key == NULL){
			$key	= array_search('en_us', array_column($data_arr, 'language'));
		}
		return $key;
	}
	//Get the name of user from user id
	public function getUserName($uid)
	{
		$role	= Rights::getAssignedRoles($uid);
		$name	= '';
		if(key($role) == 'student'){
			$student	= Students::model()->findByAttributes(array('uid'=>$uid));
			if($student){
				$name	= ucfirst($student->first_name).' '.ucfirst($student->middle_name).' '.ucfirst($student->last_name);
			}
		}
		else if(key($role) == 'parent'){
			$guardian	= Guardians::model()->findByAttributes(array('uid'=>$uid));
			if($guardian){
				$name	= ucfirst($guardian->first_name).' '.ucfirst($guardian->last_name);
			}
		}
		else if(key($role) == 'teacher'){
			$empoyee	= Employees::model()->findByAttributes(array('uid'=>$uid));
			if($empoyee){
				$name	= ucfirst($empoyee->first_name).' '.ucfirst($empoyee->middle_name).' '.ucfirst($empoyee->last_name);
			}
		}
		else{
			$profile	= Profile::model()->findByAttributes(array('user_id'=>$uid));
			if($profile){
				$name	= ucfirst($profile->firstname).' '.ucfirst($profile->lastname);
			}
		}
		
		return $name;
	}
	//Get the active batch(s) of student from student id
	//Type 0 => Get students's only one active batch, 1 => Get students's all batches
	public function getStudentActiveBatch($student_id, $type = 0) 
	{
		$batch	= array();
		
		$criteria 				= new CDbCriteria;		
		$criteria->join 		= 'LEFT JOIN batch_students t1 ON t1.batch_id = t.id';
		$criteria->condition	= 't.is_active=:is_active AND t.is_deleted=:is_deleted AND t1.student_id=:student_id AND t1.status=:status AND t1.result_status=:result_status';
		$criteria->params		= array(':is_active'=>1, ':is_deleted'=>0, ':student_id'=>$student_id, ':status'=>1, ':result_status'=>0);
		if($type == 0){					
			$batch				= Batches::model()->find($criteria);	
		}
		else if($type == 1){
			$batch				= Batches::model()->findAll($criteria);	
		}
		
		return $batch;
	}
	
	//Get Students of a parent
	//Type 0 => Get only one student of the selected parent, 1 => Get all students of the selected parent
	public function getStudents($parent_id, $type = 0)
	{
		$student	= array();
		
		$criteria 				= new CDbCriteria;		
		$criteria->join 		= 'LEFT JOIN guardian_list t1 ON t.id = t1.student_id'; 
		$criteria->condition 	= 't1.guardian_id=:guardian_id and t.is_active=:is_active and is_deleted=:is_deleted';
		$criteria->params 		= array(':guardian_id'=>$parent_id,':is_active'=>1,'is_deleted'=>0);
		if($type == 0){
			$student 			= Students::model()->find($criteria);
		}
		else if($type == 1){
			$student 			= Students::model()->findAll($criteria);
		}
		
		return $student;
	}
	
	public function getSystemLanguages()
	{
		$languages = array(
						'en_us'=>'English', 
						'af'=>'Afrikaans',
						'sq'=>'shqiptar',
						'ar'=>'العربية',
						'cz'=>'中国的 ',
						'cs'=>'český', 
						'nl'=>'Nederlands', 
						'fr'=>'français', 
						'de'=>'Deutsch', 
						'el'=>'ελληνικά',
						'gu'=>'Γκουτζαρατικά',
						'hi'=>'हिंदी',
						'id'=>'Indonesia', 
						'ga'=>'Gaeilge',
						'it'=>'italiano',  
						'ja'=>'日本人',
						'kn'=>'ಕನ್ನಡ', 
						'ko'=>'한국의', 
						'la'=>'Latine',
						'ms'=>'Melayu', 
						'pt'=>'português', 
						'ru'=>'русский', 
						'es'=>'español',
						'ta'=>'தமிழ்',
						'te'=>'తెలుగు',
						'th'=>'ภาษาไทย',
						'uk'=>'Український',
						'ur'=>'اردو',
						'vi'=>'Việt',
						'vi_vn'=>'Tiếng Việt'
					);
		return $languages;			
	}
	
	//Get Guardian Devices
	public static function getGuardianDevice($student_id)
	{
		$criteria     			= new CDbCriteria;  
		$criteria->join   		= 'JOIN `guardians` `t1` ON `t1`.`uid` = `t`.`uid` JOIN `guardian_list` `t2` ON `t2`.`guardian_id` = `t1`.`id`';
		$criteria->condition 	= '`t2`.`student_id`=:student_id AND `t1`.`is_delete`=:is_delete';
		$criteria->params  		= array(':student_id'=>$student_id, ':is_delete'=>0);
		$criteria->group		= '`t`.`device_id`';
		$model	    			= UserDevice::model()->findAll($criteria);
		
		return $model;
	}
	
	//get student device ids
	public static function getStudentDevice($student_uid)
	{                                                     
		$criteria               = new CDbCriteria();                                            
		$criteria->condition    = 'uid=:user_id';
		$criteria->params       = array(':user_id'=>$student_uid);
		$criteria->group		= 'device_id';					
		$model 		            = UserDevice::model()->findAll($criteria);
		
		return $model;
	}
	
	//get admin users device ids
	public static function getAdminDevices()
	{
		$criteria				= new CDbCriteria();
		$criteria->join			= 'JOIN `authassignment` `au` ON `au`.`userid` = `t`.`uid`';					
		$criteria->condition	= '`au`.`itemname`=:itemname';
		$criteria->params		= array(':itemname'=>'Admin');	
		$criteria->group		= '`t`.`device_id`';						
		$model 					= UserDevice::model()->findAll($criteria);
		
		return $model;
	}
}