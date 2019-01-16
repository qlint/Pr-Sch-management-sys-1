<?php

/**
 * This is the model class for table "contacts".
 *
 * The followings are the available columns in table 'contacts':
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $mobile
 * @property string $email
 * @property integer $created_by
 * @property string $created_at
 * @property integer $status
 */
class Contacts extends CActiveRecord
{
	public $group;
	
	public function import_contacts_config(){
		return array(
			'allowed_attributes' => array(
				'first_name',
				'last_name',
				'mobile',
				'email'
			),
			'required_attributes' => array(
				/*'first_name',
				'last_name',*/
				'mobile',
				'email'
			),
			'allowed_file_formats' => array(
				'csv',
				'xls',
			)
		);
	}
	/**
	 * Returns the static model of the specified AR class.
	 * @return Contacts the static model class
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
		return 'contacts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('first_name, mobile, email, created_by, created_at, status', 'required'),
			array('created_by, status', 'numerical', 'integerOnly'=>true),
			array('email', 'unique'),
			array('email', 'email'),
			array('group', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, first_name, last_name, mobile, email, created_by, created_at, status', 'safe', 'on'=>'search'),
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
			'id' => Yii::t("app",'ID'),
			'first_name' => Yii::t("app",'First Name'),
			'last_name' => Yii::t("app",'Last Name'),
			'mobile' => Yii::t("app",'Mobile'),
			'email' => Yii::t("app",'Email'),
			'created_by' => Yii::t("app",'Created By'),
			'created_at' => Yii::t("app",'Created At'),
			'status' => Yii::t("app",'Status'),
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
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	protected function getFullname(){
		return $this->first_name.' '.$this->last_name;
	}
}