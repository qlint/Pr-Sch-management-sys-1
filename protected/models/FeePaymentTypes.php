<?php

/**
 * This is the model class for table "fee_payment_types".
 *
 * The followings are the available columns in table 'fee_payment_types':
 * @property integer $id
 * @property string $type
 * @property integer $is_gateway
 * @property integer $created_by
 * @property string $created_at
 * @property integer $is_active
 */
class FeePaymentTypes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return FeePaymentTypes the static model class
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
		return 'fee_payment_types';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(			
			array('type, created_by, created_at', 'required'),
			array('type', 'unique'),
			array('is_gateway, created_by, is_active', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, is_gateway, created_by, created_at, is_active', 'safe', 'on'=>'search'),
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
			'id' => Yii::t('app', 'ID'),
			'type' => Yii::t('app', 'Type'),
			'is_gateway' => Yii::t('app', 'Is Gateway'),
			'created_by' => Yii::t('app', 'Created By'),
			'created_at' => Yii::t('app', 'Created At'),
			'is_active' => Yii::t('app', 'Status'),
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('is_gateway',$this->is_gateway);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('is_active',$this->is_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getUser(){
		$user	= User::model()->findByPk($this->created_by);
		if($user){
			$profile	= Profile::model()->findByAttributes(array("user_id"=>$user->id));
			if($profile)
				return $profile->fullname;
			else
				return "-";
		}
		return "-";
	}
	
	public function getFormattedDate(){
		$settings	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
		if($settings!=NULL){	
			return date($settings->displaydate,strtotime($this->created_at));
		}
		return date("Y-m-d", strtotime($this->created_at));
	}

	public function paymentGateway(){
		$gateway 	= FeePaymentTypes::model()->findByAttributes(array('is_gateway'=>1, 'current_gateway'=>1));
		if($gateway!=NULL){
			return $gateway->id;
		}

		return 0;
	}
}