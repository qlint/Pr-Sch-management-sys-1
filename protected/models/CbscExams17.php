<?php

/**
 * This is the model class for table "cbsc_exams_17".
 *
 * The followings are the available columns in table 'cbsc_exams_17':
 * @property integer $id
 * @property integer $exam_group_id
 * @property integer $subject_id
 * @property string $start_time
 * @property string $end_time
 * @property integer $maximum_marks
 * @property integer $minimum_marks
 * @property string $created_at
 * @property string $updated_at
 */
class CbscExams17 extends CActiveRecord
{
	public $max_mark;
	public $min_mark;
	/**
	 * Returns the static model of the specified AR class.
	 * @return CbscExams17 the static model class
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
		return 'cbsc_exams_17';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('start_time, end_time, maximum_marks, minimum_marks', 'required'),
			array('exam_group_id, subject_id, maximum_marks, minimum_marks', 'numerical', 'integerOnly'=>true),
			array('start_time, end_time, created_at, updated_at', 'safe'),
			array('end_time','check'),
			array('maximum_marks','checkmarkval'),
			array('minimum_marks','checkmarkval2'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, exam_group_id, subject_id, start_time, end_time, maximum_marks, minimum_marks, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'exam_group_id' => 'Exam Group',
			'subject_id' => 'Subject',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'maximum_marks' => 'Maximum Marks',
			'minimum_marks' => 'Minimum Marks',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
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
		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('maximum_marks',$this->maximum_marks);
		$criteria->compare('minimum_marks',$this->minimum_marks);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function subjectname($data,$row)
        {
		$subject = Subjects::model()->findByAttributes(array('id'=>$data->subject_id));
		return $subject->name;
		
	}
	public function starttime($data,$row)
	{
		$stime=$data->start_time;
		if($stime!='0000-00-00 00:00:00'){
			$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
				if($settings!=NULL)
				{
					$date=$settings->dateformat;
				}
				else
				{
					$date = 'dd-mm-yy';	
				}
			$startdate=date($settings->displaydate,strtotime($stime));
			$starttime=date($settings->timeformat,strtotime($stime));
			return $startdate." ".$starttime;
		}
		else{
			return '-';
		}
	}
	public function endtime($data,$row)
	{
		$etime=$data->end_time;
		if($etime!='0000-00-00 00:00:00'){
			$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
				if($settings!=NULL)
				{
					$date=$settings->dateformat;
				}
				else
				{
					$date = 'dd-mm-yy';	
				}
			
			$enddate=date($settings->displaydate,strtotime($etime));
			$endtime=date($settings->timeformat,strtotime($etime));
			return $enddate." ".$endtime;
		}
		else{
			return '-';
		}
	}
	public function checkmarkval2($attributes,$params)
	{
		if($this->maximum_marks<$this->minimum_marks){
			$this->addError($attributes, Yii::t("app", "Minimum mark must be less than maximum Marks"));
		}
	}
	public function checkmarkval($attributes,$params)
	{
		if($this->maximum_marks<$this->minimum_marks){
			$this->addError($attributes, Yii::t("app", "Maximum Marks must be greater than minimum mark"));
		}
		$exam_g	= CbscExamGroup17::model()->findByAttributes(array('id'=>$this->exam_group_id));
		
		if($exam_g->class == 1){
			if($this->maximum_marks!=60)
				$this->addError($attributes, Yii::t("app", "Maximum Marks must be 60"));
		}
		if($exam_g->class == 2  or $exam_g->class == 3 or $exam_g->class == 4){
			if($this->maximum_marks!=100)
				$this->addError($attributes, Yii::t("app", "Maximum Marks must be 100"));
		} 
	
	}
	public function scorelabel($data,$row)
    {
                $exam_group_id  =   $data->exam_group_id;
                $exam_model     =   ExamGroups::model()->findByPk($exam_group_id);    
		$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		$is_teaching = TimetableEntries::model()->findByAttributes(array('subject_id'=>$data->subject_id,'employee_id'=>$employee->id,'is_elective'=>0));
		if($is_teaching!=NULL or Yii::app()->controller->action->id=='classexamresult')
		{	 
           return Yii::t("app","Manage Scores");
		}
		else
		{ 
			$is_assigned = count(EmployeeElectiveSubjects::model()->findByAttributes(array('subject_id'=>$data->subject_id,'employee_id'=>$employee->id)));
			if($is_assigned>0)
			{
				if($exam_model!=NULL && $exam_model->result_published !=1)
				{
					return Yii::t("app","Manage Scores");
				}
			}
			else
			{
                return Yii::t("app","View Scores");
			} 
		}
		
	}
	public function getSubname()
	{
		$subject = Subjects::model()->findByAttributes(array('id'=>$this->subject_id));
		return $subject->name;
	}
   public function check($attributes,$params)
    {
	    if($this->start_time!="" && $this->end_time!="")
            {
                if($this->start_time > $this->end_time)
                {
                    $this->addError($attributes, Yii::t("app", "End time must be greater than Start time"));
                }
            }
		
	}
}
