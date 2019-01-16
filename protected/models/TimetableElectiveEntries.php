<?php

/**
 * This is the model class for table "timetable_elective_entries".
 *
 * The followings are the available columns in table 'timetable_elective_entries':
 * @property integer $id
 * @property integer $timetable_entry_id
 * @property integer $elective_gp_id
 * @property integer $elective_id
 * @property integer $employee_id
 * @property integer $batch_id
 */
class TimetableElectiveEntries extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TimetableElectiveEntries the static model class
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
		return 'timetable_elective_entries';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('timetable_entry_id, elective_gp_id, elective_id, employee_id, batch_id', 'required'),
			array('timetable_entry_id, elective_gp_id, elective_id, employee_id, batch_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, timetable_entry_id, elective_gp_id, elective_id, employee_id, batch_id', 'safe', 'on'=>'search'),
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
			'timetable_entry_id' => Yii::t("app",'Timetable Entry'),
			'elective_gp_id' => Yii::t("app",'Elective Gp'),
			'elective_id' => Yii::t("app",'Elective'),
			'employee_id' => Yii::t("app",'Teacher'),
			'batch_id' => Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),
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
		$criteria->compare('timetable_entry_id',$this->timetable_entry_id);
		$criteria->compare('elective_gp_id',$this->elective_gp_id);
		$criteria->compare('elective_id',$this->elective_id);
		$criteria->compare('employee_id',$this->employee_id);
		$criteria->compare('batch_id',$this->batch_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}