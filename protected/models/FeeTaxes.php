<?php

/**
 * This is the model class for table "fee_taxes".
 *
 * The followings are the available columns in table 'fee_taxes':
 * @property integer $id
 * @property string $label
 * @property string $value
 * @property integer $created_by
 * @property string $created_at
 * @property integer $is_active
 */
class FeeTaxes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return FeeTaxes the static model class
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
		return 'fee_taxes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('label, value, created_by, created_at', 'required'),
			array('value', 'type', 'type'=>'float', 'message'=>Yii::t('app', '{attribute} must be a valid number')),
			array('created_by, is_active', 'numerical', 'integerOnly'=>true),
			array('label', 'length', 'max'=>200),
			array('value', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, label, value, created_by, created_at, is_active', 'safe', 'on'=>'search'),
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
			'label' => Yii::t('app','Label'),
			'value' => Yii::t('app','Value ( % )'),
			'created_by' => Yii::t('app','Created By'),
			'created_at' => Yii::t('app','Created At'),
			'is_active' => Yii::t('app','Is Active'),
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
		$criteria->compare('label',$this->label,true);
		$criteria->compare('value',$this->value,true);
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
}