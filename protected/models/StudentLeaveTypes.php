<?php

/**
 * This is the model class for table "student_leave_types".
 *
 * The followings are the available columns in table 'student_leave_types':
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $is_excluded
 * @property integer $status
 * @property string $label
 * @property string $colour_code
 */
class StudentLeaveTypes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return StudentLeaveTypes the static model class
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
		return 'student_leave_types';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, code, label, colour_code, status', 'required'),
			array('is_excluded, status', 'numerical', 'integerOnly'=>true),
			array('name, code, label', 'length', 'max'=>255),
			array('label', 'length', 'max'=>2),
			array('colour_code', 'length', 'max'=>225),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, code, is_excluded, status, label, colour_code', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'code' => 'Code',
			'is_excluded' => 'Exclude in Attendance % calculation',
			'status' => 'Status',
			'label' => 'Label',
			'colour_code' => 'Colour Code',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('is_excluded',$this->is_excluded);
		$criteria->compare('status',$this->status);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('colour_code',$this->colour_code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}