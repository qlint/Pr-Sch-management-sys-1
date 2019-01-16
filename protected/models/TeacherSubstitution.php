<?php

/**
 * This is the model class for table "teacher_substitution".
 *
 * The followings are the available columns in table 'teacher_substitution':
 * @property integer $id
 * @property string $date
 * @property integer $batch
 * @property integer $time_table_entry_id
 * @property integer $substitute_emp_id
 */
class TeacherSubstitution extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TeacherSubstitution the static model class
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
		return 'teacher_substitution';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('date, batch, time_table_entry_id, substitute_emp_id', 'required'),
			array('batch, time_table_entry_id, substitute_emp_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, date, batch, time_table_entry_id, substitute_emp_id, leave_request_id, date_leave, leave_requested_emp_id', 'safe', 'on'=>'search'),
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
			'date' => Yii::t("app",'Date'),
			'batch' => Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),
			'time_table_entry_id' => Yii::t("app",'Time Table Entry'),
			'substitute_emp_id' => Yii::t("app",'Substitute Emp'),
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
		$criteria->compare('date',$this->date,true);
		$criteria->compare('batch',$this->batch);
		$criteria->compare('time_table_entry_id',$this->time_table_entry_id);
		$criteria->compare('substitute_emp_id',$this->substitute_emp_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}