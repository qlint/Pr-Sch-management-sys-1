<?php

/**
 * This is the model class for table "complaint_feedback".
 *
 * The followings are the available columns in table 'complaint_feedback':
 * @property integer $id
 * @property integer $uid
 * @property integer $complaint_id
 * @property string $feedback
 * @property string $date
 */
class ComplaintFeedback extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ComplaintFeedback the static model class
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
		return 'complaint_feedback';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, complaint_id, feedback, date', 'required'),
			array('uid, complaint_id', 'numerical', 'integerOnly'=>true),
			array('feedback', 'length', 'max'=>1024),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, complaint_id, feedback, date', 'safe', 'on'=>'search'),
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
			'uid' => Yii::t("app",'Uid'),
			'complaint_id' => Yii::t("app",'Complaint'),
			'feedback' => Yii::t("app",'Comment'),
			'date' => Yii::t("app",'Date'),
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
		$criteria->compare('uid',$this->uid);
		$criteria->compare('complaint_id',$this->complaint_id);
		$criteria->compare('feedback',$this->feedback,true);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}