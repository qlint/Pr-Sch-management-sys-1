<?php

/**
 * This is the model class for table "sms_gateway".
 *
 * The followings are the available columns in table 'sms_gateway':
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property integer $method
 * @property string $responds_format
 */
class SmsGateway extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SmsGateway the static model class
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
		return 'sms_gateway';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, url, method', 'required'),
			array('method', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>150),
			array('url, responds_format', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, url, method, responds_format', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'url' => 'Url',
			'method' => 'Method',
			'responds_format' => 'Responds Format',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('method',$this->method);
		$criteria->compare('responds_format',$this->responds_format,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}