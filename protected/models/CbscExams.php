<?php

/**
 * This is the model class for table "cbsc_exams".
 *
 * The followings are the available columns in table 'cbsc_exams':
 * @property integer $id
 * @property integer $exam_group_id
 * @property integer $subject_id
 * @property string $start_time
 * @property string $end_time
 * @property integer $maximum_marks
 * @property integer $minimum_marks
 * @property integer $grading_level_id
 * @property integer $weightage
 * @property integer $event_id
 * @property string $created_at
 * @property string $updated_at
 */
class CbscExams extends CActiveRecord
{
	public $max_mark;
	public $min_mark;
	/**
	 * Returns the static model of the specified AR class.
	 * @return CbscExams the static model class
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
		return 'cbsc_exams';
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
			array('exam_group_id, subject_id', 'numerical', 'integerOnly'=>true),
			array('end_time','check'),
                        
                        // The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, exam_group_id, subject_id, start_time, end_time, created_at, updated_at', 'safe', 'on'=>'search'),
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
	
	public function scorelabel($data,$row)
        {
		$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		$is_teaching = TimetableEntries::model()->findByAttributes(array('subject_id'=>$data->subject_id,'employee_id'=>$employee->id,'is_elective'=>0));
		if($is_teaching!=NULL or Yii::app()->controller->action->id=='classexamresult')
		{
			
			return Yii::t("app","Manage Scores");
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
				return Yii::t("app","Manage Scores");
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
        
       
        public static function getGrades($student_id, $subject_id, $batch_id)
        {
            $fa1_weightage = $fa2_weightage = $sa1_weightage =  $sa2_weightage = "";
            $weightage_settings= CbscExamSettings::model()->findByAttributes(array('academic_yr_id'=>Yii::app()->user->year));
            if($weightage_settings!=NULL)
            {
                //$fa_grand_total= $weightage_settings->fa_ia + $weightage_settings->fa_ga + $weightage_settings->fa_ppa;
               // $sa_grand_total= $weightage_settings->sa_speaking + $weightage_settings->sa_listening + $weightage_settings->sa_ppa;        

                $fa1_weightage= $weightage_settings->fa1_weightage;
                $fa2_weightage= $weightage_settings->fa2_weightage;
                $sa1_weightage= $weightage_settings->sa1_weightage;
                $sa2_weightage= $weightage_settings->sa2_weightage;
            }
            $grand_total = "";
            $term1_fa1_score= "";
            if($fa1_weightage!="")
            {
                $cbsc_exam_group_model= CbscExamGroups::model()->findByAttributes(array('term_id'=>1,'batch_id'=>$batch_id,'exam_type'=>'FA1','result_published'=>1));
                if($cbsc_exam_group_model!=NULL)
                {
                    $cbsc_exam_model= CbscExams::model()->findByAttributes(array('exam_group_id'=>$cbsc_exam_group_model->id,'subject_id'=>$subject_id));
                    if($cbsc_exam_model!=NULL)
                    {
                        $cbsc_exam_score= CbscExamScores::model()->findByAttributes(array('student_id'=>$student_id,'exam_id'=>$cbsc_exam_model->id));
                        if($cbsc_exam_score!=NULL)
                        {
                           
                            $marks= $cbsc_exam_score->marks;  
							$fa1_score = ($marks/$cbsc_exam_model->maximum_marks)*$fa1_weightage;
                            $term1_fa1_score= $fa1_score;                                                       
                        }
                    }
                }
            }
            
            $term1_fa2_score="";
            if($fa2_weightage!="")
            {
                $cbsc_exam_group_model= CbscExamGroups::model()->findByAttributes(array('term_id'=>1,'batch_id'=>$batch_id,'exam_type'=>'FA2','result_published'=>1));
                if($cbsc_exam_group_model!=NULL)
                {
                    $cbsc_exam_model= CbscExams::model()->findByAttributes(array('exam_group_id'=>$cbsc_exam_group_model->id,'subject_id'=>$subject_id));
                    if($cbsc_exam_model!=NULL)
                    {
                        $cbsc_exam_score= CbscExamScores::model()->findByAttributes(array('student_id'=>$student_id,'exam_id'=>$cbsc_exam_model->id));
                        if($cbsc_exam_score!=NULL)
                        {
                            $marks= $cbsc_exam_score->marks;  
							$fa2_score = ($marks/$cbsc_exam_model->maximum_marks)*$fa2_weightage;
                            $term1_fa2_score= $fa2_score;                                                         
                        }
                    }
                }
            }
            $term1_sa1_score="";
            if($sa1_weightage!="")
            {
                $cbsc_exam_group_model= CbscExamGroups::model()->findByAttributes(array('term_id'=>1,'batch_id'=>$batch_id,'exam_type'=>'SA1','result_published'=>1));
                if($cbsc_exam_group_model!=NULL)
                {
                    $cbsc_exam_model= CbscExams::model()->findByAttributes(array('exam_group_id'=>$cbsc_exam_group_model->id,'subject_id'=>$subject_id));
                    if($cbsc_exam_model!=NULL)
                    {
                        $cbsc_exam_score= CbscExamScores::model()->findByAttributes(array('student_id'=>$student_id,'exam_id'=>$cbsc_exam_model->id));
                        if($cbsc_exam_score!=NULL)
                        {
                            $marks= $cbsc_exam_score->marks;  
							$sa1_score = ($marks/$cbsc_exam_model->maximum_marks)*$sa1_weightage;
                            $term1_sa1_score= $sa1_score;                                                        
                        }
                    }
                }
            }
            
           
            $term2_sa2_score="";
            if($sa2_weightage!="")
            {
                $cbsc_exam_group_model= CbscExamGroups::model()->findByAttributes(array('term_id'=>2,'batch_id'=>$batch_id,'exam_type'=>'SA2','result_published'=>1));
                if($cbsc_exam_group_model!=NULL)
                {
                    $cbsc_exam_model= CbscExams::model()->findByAttributes(array('exam_group_id'=>$cbsc_exam_group_model->id,'subject_id'=>$subject_id));
                    if($cbsc_exam_model!=NULL)
                    {
                        $cbsc_exam_score= CbscExamScores::model()->findByAttributes(array('student_id'=>$student_id,'exam_id'=>$cbsc_exam_model->id));
                        if($cbsc_exam_score!=NULL)
                        {
                            $marks= $cbsc_exam_score->marks;  
							$sa2_score = ($marks/$cbsc_exam_model->maximum_marks)*$sa2_weightage;
                            $term1_sa2_score= $sa2_score;    
                        }
                    }
                }
            }
                        
            $total=$term1_fa1_score+$term1_sa1_score+$term2_fa2_score+$term2_sa2_score;
                return  $total;
          
        }
        
}