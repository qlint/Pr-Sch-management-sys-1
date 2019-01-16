<?php

/**
 * This is the model class for table "student_subjectwise_attentance".
 *
 * The followings are the available columns in table 'student_subjectwise_attentance':
 * @property integer $id
 * @property integer $student_id
 * @property integer $timetable_id
 * @property string $reason
 * @property integer $leavetype_id
 * @property string $date
 * @property integer $weekday_id
 */
class StudentSubjectwiseAttentance extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return StudentSubjectwiseAttentance the static model class
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
		return 'student_subjectwise_attentance';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(			
			array('reason, leavetype_id', 'safe'),
			array('student_id, timetable_id, leavetype_id, weekday_id, subject_id', 'numerical', 'integerOnly'=>true),
			array('reason', 'length', 'max'=>255),
			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, student_id, timetable_id, reason, leavetype_id, date, weekday_id, subject_id', 'safe', 'on'=>'search'),
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
			'student_id' => 'Student',
			'timetable_id' => 'Timetable',
			'reason' => 'Reason',
			'leavetype_id' => 'Leave Type',
			'date' => 'Date',
			'weekday_id' => 'Weekday',
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
		$criteria->compare('student_id',$this->student_id);
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
	
	public function getClasses($weekday = NULL, $start = NULL, $end = NULL)
	{
		//$weekday	= 1; // 1-mon, 2-tue, 3-wed, 4-thu, 5-fri, 6-sat, 7-sun
		$daycount	= 0;
		//$start 		= new DateTime('2017-03-01');
		//$end   		= new DateTime('2017-03-31');
		$end->modify('+1 day');
		
		$interval 	= DateInterval::createFromDateString('1 day');
		$period 	= new DatePeriod($start, $interval, $end);
		foreach ($period as $dt){
			if ($dt->format('N') == $weekday){
				$daycount++;
			}
		}
		return $daycount;	
	}
	
	//Get subject name from timetable entries
	public function	getSubjectName($timetable_id)
	{
		$timetable_entry	= TimetableEntries::model()->findByPk($timetable_id);
		$subject_name		= '';		
		if($timetable_entry->is_elective == 0){ //In case of normal subject
			$subject	= Subjects::model()->findByPk($timetable_entry->subject_id);			
			if($subject){
				$subject_name	= html_entity_decode(ucfirst($subject->name));
			}			
		}
		else{ //In case of elective subject			
			$elective	= Electives::model()->findByPk($timetable_entry->subject_id);
			if($elective){
				$elective_group	= ElectiveGroups::model()->findByPk($elective->elective_group_id);
				if($elective_group){
					$subject_name	= html_entity_decode(ucfirst($elective_group->name));
				}
			}			
		}
		
		return $subject_name;
	}
	
//Get subject name from timetable entries
	public function	getEmployeeName($timetable_id)
	{
		$timetable_entry	= TimetableEntries::model()->findByPk($timetable_id);
		$employee_name		= '';
		$employee			= Employees::model()->findByPk($timetable_entry->employee_id);				
		if($employee){
			$employee_name	= ucfirst($employee->fullname);
		}
		
		return $employee_name;
	}	
	
//Get Class timing label from timetable entries
	public function getClassTimingLabel($timetable_id)
	{
		$timetable_entry	= TimetableEntries::model()->findByPk($timetable_id);
		$class_timing_label	= '';
		$class_timing		= ClassTimings::model()->findByPk($timetable_entry->class_timing_id);
		if($class_timing){
			$class_timing_label	= $class_timing->start_time.' - '.$class_timing->end_time;
		}
		
		return $class_timing_label;
	}
}