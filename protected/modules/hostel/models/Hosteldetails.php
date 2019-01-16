<?php

/**
 * This is the model class for table "hosteldetails".
 *
 * The followings are the available columns in table 'hosteldetails':
 * @property integer $id
 * @property string $hostel_name
 * @property string $address
 */
class Hosteldetails extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Hosteldetails the static model class
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
		return 'hosteldetails';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hostel_name', 'required'),
			array('is_deleted', 'numerical', 'integerOnly'=>true),
			array('hostel_name, address', 'length', 'max'=>120),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, hostel_name, address,is_deleted', 'safe', 'on'=>'search'),
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
			'id' => Yii::t('app','ID'),
			'hostel_name' => Yii::t('app','Hostel Name'),
			'address' => Yii::t('app','Address'),
			'is_deleted' => Yii::t('app','Is Deleted'),
			
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
		$criteria->compare('hostel_name',$this->hostel_name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('is_deleted',$this->is_deleted,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}