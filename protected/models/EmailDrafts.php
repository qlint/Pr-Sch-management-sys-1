<?php

/**
 * This is the model class for table "email_drafts".
 *
 * The followings are the available columns in table 'email_drafts':
 * @property integer $id
 * @property string $subject
 * @property string $message
 * @property string $created_by
 * @property string $status
 */
class EmailDrafts extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return EmailDrafts the static model class
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
		return 'email_drafts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('subject, message, created_by', 'required'),
			array('subject', 'length', 'max'=>120),
			array('message', 'length', 'max'=>12000),
			array('created_by, status', 'length', 'max'=>11),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, subject, message, created_by, status', 'safe', 'on'=>'search'),
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
			'subject' => Yii::t("app",'Subject'),
			'message' => Yii::t("app",'Message'),
			'created_by' => Yii::t("app",'Created By'),
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
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('created_by',$this->created_by,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}