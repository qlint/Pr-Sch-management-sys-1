 <?php

/**
 * This is the model class for table "registered_guardians".
 *
 * The followings are the available columns in table 'registered_guardians':
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $relation
 * @property string $email
 * @property string $dob
 * @property integer $income
 * @property string $office_phone1
 * @property string $office_phone2
 * @property string $mobile_phone
 * @property string $office_address_line1
 * @property string $office_address_line2
 * @property string $city
 * @property string $state
 * @property string $postal_code
 * @property integer $country_id
 * @property string $occupation
 * @property string $education
 * @property integer $is_deleted
 * @property string $created_at
 * @property string $updated_at
 */
class RegisteredGuardians extends CActiveRecord
{
	public $same_address;
	/**
	 * Returns the static model of the specified AR class.
	 * @return RegisteredGuardians the static model class
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
		return 'registered_guardians';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$roles=Rights::getAssignedRoles(Yii::app()->user->Id);	
		if(Yii::app()->user->id==NULL or (Yii::app()->user->id!=NULL and key($roles)!=NULL and (key($roles) == 'Admin')))
		{
			return array(
				array('first_name,last_name,relation,education,occupation,income,office_phone1,office_phone2,mobile_phone,office_address_line1,office_address_line2,city,state','filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
				array('first_name, last_name, relation,mobile_phone,email', 'required'),
				array('income, country_id, is_deleted', 'numerical', 'integerOnly'=>true),
				array('first_name, last_name, relation, email, office_phone1, office_phone2, mobile_phone, office_address_line1, office_address_line2, city, state, occupation, education', 'length', 'max'=>255),
				array('postal_code', 'length', 'max'=>100),
				array('dob, created_at, updated_at', 'safe'),
				array('email','email'),
				array('email','check'),
				array('email','unique'),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, first_name, last_name, relation, email, dob, income, office_phone1, office_phone2, mobile_phone, office_address_line1, office_address_line2, city, state, postal_code, country_id, occupation, education, is_deleted, created_at, updated_at', 'safe', 'on'=>'search'),
			);
		}else{
			return array(
				array('first_name,last_name,relation,education,occupation,income,office_phone1,office_phone2,mobile_phone,office_address_line1,office_address_line2,city,state','filter','filter'=>array($obj=new CHtmlPurifier(),'purify')),
				array('first_name, last_name, relation,mobile_phone,email', 'required'),
				array('income, country_id, is_deleted', 'numerical', 'integerOnly'=>true),
				array('first_name, last_name, relation, email, office_phone1, office_phone2, mobile_phone, office_address_line1, office_address_line2, city, state, occupation, education', 'length', 'max'=>255),
				array('postal_code', 'length', 'max'=>100),
				array('dob, created_at, updated_at', 'safe'),
				array('email','email'),
				array('email','check'),
				
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, first_name, last_name, relation, email, dob, income, office_phone1, office_phone2, mobile_phone, office_address_line1, office_address_line2, city, state, postal_code, country_id, occupation, education, is_deleted, created_at, updated_at', 'safe', 'on'=>'search'),
			);
		}
	}

	public function check($attribute,$params)
    {
		$roles=Rights::getAssignedRoles(Yii::app()->user->Id);		
		if(Yii::app()->user->id==NULL or (Yii::app()->user->id!=NULL and key($roles)!=NULL and (key($roles) == 'Admin')))
		{		
			$guardians	= Guardians::model()->findByAttributes(array('email'=>$this->$attribute,'is_delete'=>'0'));
			$employee	= Employees::model()->findByAttributes(array('email'=>$this->$attribute,'is_deleted'=>'0'));
			$user		= User::model()->findByAttributes(array('email'=>$this->$attribute));
			$student	= Students::model()->findByAttributes(array('email'=>$this->$attribute,'is_deleted'=>'0'));
			$registered_student = RegisteredStudents::model()->findByAttributes(array('email'=>$this->$attribute));
			if($this->$attribute!='' and Yii::app()->session['parent_id']==NULL){
				if($guardians!=NULL or $employee!=NULL or $user!=NULL or $student!=NULL or $registered_student!=NULL){
					$this->addError($attribute,Yii::t("app",'Email already in use'));
				}
			}
		}
    }
	
	protected function decryptToken($token){
		$salt 	= substr($token, -1);
		$token	= substr_replace($token, "", -5);
		for($i=0; $i<$salt; $i++){
			$token	= base64_decode(strrev($token));
		}
		return $token;
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
			'first_name' => Yii::t("app",'First Name'),
			'last_name' => Yii::t("app",'Last Name'),
			'relation' => Yii::t("app",'Relation'),
			'email' => Yii::t("app",'Email'),
			'dob' => Yii::t("app",'Date Of Birth'),
			'income' => Yii::t("app",'Income'),
			'office_phone1' => Yii::t("app",'Office / Home Phone 1'),
			'office_phone2' => Yii::t("app",'Office / Home Phone 2'),
			'mobile_phone' => Yii::t("app",'Mobile Phone'),
			'office_address_line1' => Yii::t("app",'Office / Home Address Line 1'),
			'office_address_line2' => Yii::t("app",'Office / Home Address Line 2'),
			'city' => Yii::t("app",'City'),
			'state' => Yii::t("app",'State'),
			'postal_code' => Yii::t("app",'Postal Code'),
			'country_id' => Yii::t("app",'Country'),
			'occupation' => Yii::t("app",'Occupation'),
			'education' => Yii::t("app",'Education'),
			'is_deleted' => Yii::t("app",'Is Deleted'),
			'created_at' => Yii::t("app",'Created At'),
			'updated_at' => Yii::t("app",'Updated At'),
			'same_address' => Yii::t('app','Same as student contact details')
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
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('relation',$this->relation,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('dob',$this->dob,true);
		$criteria->compare('income',$this->income);
		$criteria->compare('office_phone1',$this->office_phone1,true);
		$criteria->compare('office_phone2',$this->office_phone2,true);
		$criteria->compare('mobile_phone',$this->mobile_phone,true);
		$criteria->compare('office_address_line1',$this->office_address_line1,true);
		$criteria->compare('office_address_line2',$this->office_address_line2,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('postal_code',$this->postal_code,true);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('occupation',$this->occupation,true);
		$criteria->compare('education',$this->education,true);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}