<?php

/**
 * This is the model class for table "teacher_subjectwise_attentance".
 *
 * The followings are the available columns in table 'teacher_subjectwise_attentance':
 * @property integer $id
 * @property integer $employee_id
 * @property integer $timetable_id
 * @property string $reason
 * @property integer $leavetype_id
 * @property string $date
 * @property integer $weekday_id
 * @property integer $subject_id
 */
class TeacherSubjectwiseAttentance extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TeacherSubjectwiseAttentance the static model class
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
		return 'teacher_subjectwise_attentance';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('leavetype_id, reason', 'required'),
			array('employee_id, timetable_id, leavetype_id, weekday_id, subject_id', 'numerical', 'integerOnly'=>true),
			array('reason', 'length', 'max'=>255),
			array('date', 'safe'),
			array('leavetype_id','leavetypecheck'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, employee_id, timetable_id, reason, leavetype_id, date, weekday_id, subject_id', 'safe', 'on'=>'search'),
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
			'employee_id' => Yii::t('app','Employee'),
			'timetable_id' => Yii::t('app','Timetable'),
			'reason' => Yii::t('app','Reason'),
			'leavetype_id' => Yii::t('app','Leave Type'),
			'date' => Yii::t('app','Date'),
			'weekday_id' => Yii::t('app','Weekday'),
			'subject_id' => Yii::t('app','Subject'),
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
		$criteria->compare('employee_id',$this->employee_id);
		$criteria->compare('timetable_id',$this->timetable_id);
		$criteria->compare('reason',$this->reason,true);
		$criteria->compare('leavetype_id',$this->leavetype_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('weekday_id',$this->weekday_id);
		$criteria->compare('subject_id',$this->subject_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	//check the phone number is unique
	public function leavetypecheck($attribute,$params)
	{
		$tleave	=TeacherSubjectwiseAttentance::model()->findByAttributes(array('employee_id'=>$this->employee_id,'leavetype_id'=>$this->leavetype_id));
		$leave_types =LeaveTypes::model()->findBypk($this->leavetype_id);
		if(count($tleave) >=$leave_types->count){
			$this->addError($attribute,Yii::t('app','Leave has already been taken'));
			
		}
	}
}