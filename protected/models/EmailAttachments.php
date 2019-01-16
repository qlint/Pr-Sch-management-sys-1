<?php

/**
 * This is the model class for table "email_attachments".
 *
 * The followings are the available columns in table 'email_attachments':
 * @property integer $id
 * @property integer $mail_id
 * @property string $file
 * @property string $file_type
 * @property integer $created_by
 */
class EmailAttachments extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return EmailAttachments the static model class
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
		return 'email_attachments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mail_id, file, file_type, created_by', 'required'),
			array('mail_id, created_by', 'numerical', 'integerOnly'=>true),
			array('file', 'length', 'max'=>200),
			array('file_type', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, mail_id, file, file_type, created_by', 'safe', 'on'=>'search'),
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
			'file' => Yii::t("app",'File'),
			'file_type' => Yii::t("app",'File Type'),
			'created_by' => Yii::t("app",'Created By'),
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
		$criteria->compare('mail_id',$this->mail_id);
		$criteria->compare('file',$this->file,true);
		$criteria->compare('file_type',$this->file_type,true);
		$criteria->compare('created_by',$this->created_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}