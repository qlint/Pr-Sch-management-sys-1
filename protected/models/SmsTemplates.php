<?php

/**
 * This is the model class for table "sms_templates".
 *
 * The followings are the available columns in table 'sms_templates':
 * @property integer $id
 * @property string $template
 * @property integer $created_by
 * @property string $created_at
 */
class SmsTemplates extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SmsTemplates the static model class
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
		return 'sms_templates';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, template, created_by, created_at', 'required'),
			array('created_by', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, template, created_by, created_at', 'safe', 'on'=>'search'),
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
			'template' => Yii::t("app",'Template'),
			'created_by' => Yii::t("app",'Created By'),
			'created_at' => Yii::t("app",'Created At'),
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
		$criteria->compare('template',$this->template,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getCreate_at()
	{
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
		$timezone = Timezone::model()->findByAttributes(array('id'=>$settings->timezone));		
		date_default_timezone_set($timezone->timezone);
		$date = date($settings->displaydate,strtotime($this->created_at));	
		$time = date($settings->timeformat,strtotime($this->created_at)); 
		return $date.' '.$time;
		//return date('d-m-Y H:i:s',strtotime($this->created_at));
	}
}