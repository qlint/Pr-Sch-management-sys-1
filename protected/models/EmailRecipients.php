<?php

/**
 * This is the model class for table "email_recipients".
 *
 * The followings are the available columns in table 'email_recipients':
 * @property integer $id
 * @property string $mail_id
 * @property string $users
 * @property string $batches
 */
class EmailRecipients extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return EmailRecipients the static model class
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
		return 'email_recipients';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mail_id, users', 'required'),
			array('mail_id, users', 'length', 'max'=>120),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, mail_id, users, batches', 'safe', 'on'=>'search'),
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
			'mail_id' => Yii::t("app",'Mail'),
			'users' => Yii::t("app",'Users'),
			'batches' => Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),
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
		$criteria->compare('mail_id',$this->mail_id,true);
		$criteria->compare('users',$this->users,true);
		$criteria->compare('batches',$this->batches,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}