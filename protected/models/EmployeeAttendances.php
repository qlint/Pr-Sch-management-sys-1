<?php

/**
 * This is the model class for table "employee_attendances".
 *
 * The followings are the available columns in table 'employee_attendances':
 * @property integer $id
 * @property string $attendance_date
 * @property integer $employee_id
 * @property integer $employee_leave_type_id
 * @property string $reason
 * @property integer $is_half_day
 */
class EmployeeAttendances extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return EmployeeAttendances the static model class
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
		return 'employee_attendances';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('employee_id, employee_leave_type_id, is_half_day, half', 'numerical', 'integerOnly'=>true),
		//	array('reason', 'length', 'max'=>255),
			array('attendance_date', 'safe'),
			array('employee_leave_type_id, reason', 'required'),
			//array('reason','CRegularExpressionValidator', 'pattern'=>'/^[a-zA-Z][a-zA-Z ]*$/','message'=>"{attribute} should contain only letters."),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, attendance_date, employee_id, employee_leave_type_id, reason, is_half_day', 'safe', 'on'=>'search'),
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
			'attendance_date' => Yii::t("app",'Attendance Date'),
			'employee_id' => Yii::t("app",'Teacher'),
			'employee_leave_type_id' => Yii::t("app",'Teacher Leave Type'),
			'reason' => Yii::t("app",'Reason'),
			'is_half_day' => Yii::t("app",'Is Half Day'),
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
		$criteria->compare('attendance_date',$this->attendance_date,true);
		$criteria->compare('employee_id',$this->employee_id);
		$criteria->compare('employee_leave_type_id',$this->employee_leave_type_id);
		$criteria->compare('reason',$this->reason,true);
		$criteria->compare('is_half_day',$this->is_half_day);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	function getMonthname($nextmonth)
	{
	switch($nextmonth) 
		{ 
			case 1 : 
				$stringmonth = "January"; 
				break;
			case 2 : 
				$stringmonth = "February"; 
				break;
			case 3 : 
				$stringmonth = "March"; 
				break;
			case 4 : 
				$stringmonth = "April"; 
				break;
			case 5 : 
				$stringmonth = "May"; 
				break;
			case 6 : 
				$stringmonth = "June"; 
				break;
			case 7 : 
				$stringmonth = "July"; 
				break;
			case 8 : 
				$stringmonth = "August"; 
				break;
			case 9 : 
				$stringmonth = "September"; 
				break;
			case 10 : 
				$stringmonth = "October"; 
				break;
			case 11 : 
				$stringmonth = "November"; 
				break;
			case 12 : 
				$stringmonth = "December"; 
				break;
		}
		return $stringmonth;
	}
	
	function createDateRangeArray($strDateFrom,$strDateTo)
	{
		// takes two dates formatted as YYYY-MM-DD and creates an
		// inclusive array of the dates between the from and to dates.
	
		// could test validity of dates here but I'm already doing
		// that in the main script
	
		$aryRange=array();
	
		$iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
		$iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));
	
		if ($iDateTo>=$iDateFrom)
		{
			array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
			while ($iDateFrom<$iDateTo)
			{
				$iDateFrom+=86400; // add 24 hours
				array_push($aryRange,date('Y-m-d',$iDateFrom));
			}
		}
		return $aryRange;
	}
}