<?php

/**
 * This is the model class for table "elective_exams".
 *
 * The followings are the available columns in table 'elective_exams':
 * @property integer $id
 * @property integer $exam_group_id
 * @property integer $elective_id
 * @property string $start_time
 * @property string $end_time
 * @property string $maximum_marks
 * @property string $minimum_marks
 * @property integer $grading_level_id
 * @property integer $weightage
 * @property integer $event_id
 * @property string $created_at
 * @property string $updated_at
 */
class ElectiveExams extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ElectiveExams the static model class
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
		return 'elective_exams';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('exam_group_id, elective_id, grading_level_id, weightage, event_id', 'numerical', 'integerOnly'=>true),
			array('maximum_marks, minimum_marks', 'length', 'max'=>10),
			array('start_time, end_time, created_at, updated_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, exam_group_id, elective_id, start_time, end_time, maximum_marks, minimum_marks, grading_level_id, weightage, event_id, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'exam_group_id' => Yii::t("app",'Exam Group'),
			'elective_id' => Yii::t("app",'Elective'),
			'start_time' => Yii::t("app",'Start Time'),
			'end_time' => Yii::t("app",'End Time'),
			'maximum_marks' => Yii::t("app",'Maximum Marks'),
			'minimum_marks' => Yii::t("app",'Minimum Marks'),
			'grading_level_id' => Yii::t("app",'Grading Level'),
			'weightage' => Yii::t("app",'Weightage'),
			'event_id' => Yii::t("app",'Event'),
			'created_at' => Yii::t("app",'Created At'),
			'updated_at' => Yii::t("app",'Updated At'),
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
		$criteria->compare('exam_group_id',$this->exam_group_id);
		$criteria->compare('elective_id',$this->elective_id);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('maximum_marks',$this->maximum_marks,true);
		$criteria->compare('minimum_marks',$this->minimum_marks,true);
		$criteria->compare('grading_level_id',$this->grading_level_id);
		$criteria->compare('weightage',$this->weightage);
		$criteria->compare('event_id',$this->event_id);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}