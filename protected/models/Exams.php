<?php

/**
 * This is the model class for table "exams".
 *
 * The followings are the available columns in table 'exams':
 * @property integer $id
 * @property integer $exam_group_id
 * @property integer $subject_id
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
class Exams extends CActiveRecord
{
	
	public $max_mark;
	public $min_mark;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Exams the static model class
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
		return 'exams';
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
			array('exam_group_id, subject_id, maximum_marks, minimum_marks, grading_level_id, weightage, event_id', 'numerical', 'integerOnly'=>true),
                        array('end_time','check'),
                        array('maximum_marks','checkmark'),
                        array('maximum_marks, minimum_marks', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, exam_group_id, subject_id, start_time, end_time, maximum_marks, minimum_marks, grading_level_id, weightage, event_id, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'subject_id' => Yii::t("app",'Subject'),
			'start_time' => Yii::t("app",'Start Time'),
			'end_time' => Yii::t("app",'End Time'),
			'maximum_marks' => Yii::t("app",'Maximum Marks'),
			'minimum_marks' => Yii::t("app",'Minimum Marks'),
			'grading_level_id' => Yii::t("app",'Grading Level'),
			'weightage' => Yii::t("app",'Weightage'),
			'event_id' => Yii::t("app",'Event'),
			'created_at' => Yii::t("app",'Created At'),
			'updated_at' => Yii::t("app",'Updated At'),
			'max_mark' => Yii::t("app",'Max Mark'),
			'min_mark' => Yii::t("app",'Min Mark'),
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
		$criteria->compare('maximum_marks',number_format($this->maximum_marks),true);
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
	
	public function subjectname($data,$row)
    {
		$subject = Subjects::model()->findByAttributes(array('id'=>$data->subject_id));
		return $subject->name;
		
	}
	
	public function scorelabel($data,$row)
    {
        $exam_group_id  =   $data->exam_group_id;
        $exam_model     =   ExamGroups::model()->findByPk($exam_group_id);    
		$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		$is_teaching = TimetableEntries::model()->findByAttributes(array('subject_id'=>$data->subject_id,'employee_id'=>$employee->id,'is_elective'=>0));
		$classteacher = Batches::model()->findByAttributes(array('id'=>$exam_model->batch_id));
		if($is_teaching!=NULL or Yii::app()->controller->action->id=='classexamresult' or $classteacher->employee_id == $employee->id)
		{			
                   /* if($exam_model!=NULL && $exam_model->result_published !=1)
                    {*/
                        return Yii::t("app","Manage Scores");
                    /*}
                    else
                        return "-";*/
		}
		else
		{
			//$criteria = new CDbCriteria;
			//$criteria->condition = "subject_id=:sid AND employee_id=:empid AND is_elective=:eid";
			//$criteria->params=array(':empid'=>$employee->id,':eid'=>2,':sid'=>$data->subject_id);
			//$is_elec_teaching = TimetableEntries::model()->find($criteria);
			//if($is_elec_teaching!=NULL)
			//{
			//echo $data->subject_id;	
			//$elective = Electives::model()->findByPk($is_elec_teaching->subject_id);
			$is_assigned = count(EmployeeElectiveSubjects::model()->findByAttributes(array('subject_id'=>$data->subject_id,'employee_id'=>$employee->id)));
			 
			//$elec_group = ElectiveGroups::model()->findByAttributes(array('id'=>$elective->elective_group_id,'batch_id'=>$is_elec_teaching->batch_id));
			//$is_check = Subjects::model()->findByAttributes(array('code'=>$elec_group->code));
			//$is_assigned = count(EmployeeElectiveSubjects::model()->findByAttributes(array('elective_id'=>$elective->id)));
			if($is_assigned>0)
			{
                            if($exam_model!=NULL)// && $exam_model->result_published !=1
                            {
							return Yii::t("app","Manage Scores");
                            }
							else
							{
								return Yii::t("app","View Scores");
							}
			}
			else
			{
                            return Yii::t("app","View Scores");
			}
			//}
			//else
			//{
				//return Yii::t("app","View Scores");
			//}
		}
		  
	}
	public function electivename($data,$row)
    {
		//print_r($data);
		$subject = ElectiveGroups::model()->findByAttributes(array('id'=>$data->elective_id));
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
        
	public function checkmark($attributes,$params)
	{
		if($this->maximum_marks!="" && $this->minimum_marks!="")
		{
			if($this->minimum_marks > $this->maximum_marks)
			{
				$this->addError($attributes, Yii::t("app", "Maximum mark must be greater than minimum mark"));
			}
		}
	
	}
        
        public function getSubname()
        {
            $subject = Subjects::model()->findByAttributes(array('id'=>$this->subject_id));
            return $subject->name;
        }	
	
	public function getBatchId(){
		$subject = Subjects::model()->findByAttributes(array('id'=>$this->subject_id));		
		return ($subject!=NULL)?$subject->batch123->id:'';
	}
}