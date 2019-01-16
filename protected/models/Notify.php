<?php

/**
 * This is the model class for table "notify".
 *
 * The followings are the available columns in table 'notify':
 * @property integer $id
 * @property integer $user_type
 * @property integer $filter
 * @property integer $course_id
 * @property integer $batch_id
 * @property integer $subject_id
 * @property integer $elective_group_id
 * @property integer $elective_id
 * @property integer $category_id
 * @property integer $department_id
 * @property integer $position_id
 * @property integer $grade_id
 * @property integer $staff_type
 * @property string $message
 * @property integer $type
 * @property integer $is_mail
 * @property integer $academic_yr
 * @property string $created_at
 * @property integer $created_by
 */
class Notify extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Notify the static model class
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
		return 'notify';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(			
			array('message, created_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_type, filter, course_id, batch_id, subject_id, elective_group_id, elective_id, category_id, department_id, position_id, grade_id, staff_type, message, type, is_mail, academic_yr, created_at, created_by, total_receiver_count', 'safe', 'on'=>'search'),
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
			'user_type' => 'User Type',
			'filter' => 'Filter',
			'course_id' => 'Course',
			'batch_id' => 'Batch',
			'subject_id' => 'Subject',
			'elective_group_id' => 'Elective Group',
			'elective_id' => 'Elective',
			'category_id' => 'Category',
			'department_id' => 'Department',
			'position_id' => 'Position',
			'grade_id' => 'Grade',
			'staff_type' => 'Staff Type',
			'message' => 'Message',
			'type' => 'Type',
			'is_mail' => 'Is Mail',
			'academic_yr' => 'Academic Yr',
			'created_at' => 'Created At',
			'created_by' => 'Created By',
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
		$criteria->compare('user_type',$this->user_type);
		$criteria->compare('filter',$this->filter);
		$criteria->compare('course_id',$this->course_id);
		$criteria->compare('batch_id',$this->batch_id);
		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('elective_group_id',$this->elective_group_id);
		$criteria->compare('elective_id',$this->elective_id);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('department_id',$this->department_id);
		$criteria->compare('position_id',$this->position_id);
		$criteria->compare('grade_id',$this->grade_id);
		$criteria->compare('staff_type',$this->staff_type);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('is_mail',$this->is_mail);
		$criteria->compare('academic_yr',$this->academic_yr);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('created_by',$this->created_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}