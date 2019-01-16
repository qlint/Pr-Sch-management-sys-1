<?php

/**
 * This is the model class for table "cbsc_exam_scores".
 *
 * The followings are the available columns in table 'cbsc_exam_scores':
 * @property integer $id
 * @property integer $student_id
 * @property integer $exam_id
  * @property integer $marks
 * @property integer $is_failed
 * @property string $created_at
 * @property string $updated_at
 */
class CbscExamScores extends CActiveRecord
{
	public $check_type;
	public $score_limit;
	public $sub_category1;
	public $sub_category2;
	/**
	 * Returns the static model of the specified AR class.
	 * @return CbscExamScores the static model class
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
		return 'cbsc_exam_scores';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('marks,sub_category1,sub_category2', 'required'),
			array('student_id, exam_id, marks,,sub_category1,sub_category2 is_failed', 'numerical', 'integerOnly'=>true),
			//array('marks','checksubject'),
			array('marks','checkmarkval'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, student_id, exam_id, marks, remarks, is_failed, created_at, updated_at,sub_category1,sub_category2', 'safe', 'on'=>'search'),
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
			'exam_id' => 'Exam',
			'sub_category1'=> Yii::t("app",'First Sub Category'),
			'sub_category2'=> Yii::t("app",'Second Sub Category'),
			'marks' => 'Total Marks',
			'remarks' => 'Remarks',
			'is_failed' => 'Is Failed',
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
		$criteria->compare('student_id',$this->student_id);
		$criteria->compare('exam_id',$this->exam_id);
		$criteria->compare('marks',$this->marks);
		$criteria->compare('remarks',$this->remarks);
		$criteria->compare('is_failed',$this->is_failed);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getRemarks($data,$row){
		echo $data->remarks;
	}
	public function studentFullName($data,$row){
		$name 	= "";
		$scope='forStudentProfile';
		$student = Students::model()->findByAttributes(array('id'=>$data->student_id));
		if(FormFields::model()->isVisible('first_name', 'Students', $scope))
        {
            $name 	.= ucfirst($student->first_name);
        }

        if(FormFields::model()->isVisible('middle_name','Students', $scope))
        {
            $name 	.= (($name!="")?" ":"").ucfirst($student->middle_name);
        }

        if(FormFields::model()->isVisible('last_name','Students', $scope))
        {
            $name 	.= (($name!="")?" ":"").ucfirst($student->last_name);
        }

        return $name;
	}
	 public function gridStudentName($scope='forStudentProfile')
        {
            $model= Students::model()->model()->findByPk($this->student_id);
            $name 	= "";
            if($model)
            {
                if(FormFields::model()->isVisible('first_name', 'Students', $scope))
                {
                    $name 	.= ucfirst($model->first_name);
                }

                if(FormFields::model()->isVisible('middle_name','Students', $scope))
                {
                    $name 	.= (($name!="")?" ":"").ucfirst($model->middle_name);
                }

                if(FormFields::model()->isVisible('last_name','Students', $scope))
                {
                    $name 	.= (($name!="")?" ":"").ucfirst($model->last_name);
                }
            }
            return $name;
	}
	public function category1($data,$row)
	{
		$subject_spits	=	CbscexamScoresSplit::model()->findByAttributes(array('exam_scores_id'=>$data->id));
		return $subject_spits->mark;
	}
	public function category2($data,$row)
	{
		$criteria=new CDbCriteria;
		$criteria->condition = "exam_scores_id LIKE :exam_scores_id";
		$criteria->params = array(":exam_scores_id"=>$data->id); 
		$criteria->order = 'id DESC';
		$models = CbscexamScoresSplit::model()->findAll($criteria); 
		foreach($models as $model){
			return $model->mark;exit;
		}
	}
	
	public function checksubject($data,$row)
	{
		$examid=$_REQUEST['examid'];
		$exam=CbscExams::model()->findByAttributes(array('id'=>$examid));
		$subject=Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
		if($subject->cbsc_common == 1){
			if($this->marks >5)
			$this->addError($attributes, Yii::t("app", "Mark must be less than or equal to 5"));
		}
	}
	
	public function checkmarkval($attributes,$params)
	{
		$exm_id=$this->exam_id;
		if($this->marks<0)
		{
			$this->addError($attributes, Yii::t("app", "Mark must be a positive integer"));
		}
		$exam=CbscExams::model()->findByAttributes(array('id'=>$exm_id));
		if($this->marks>$exam->maximum_marks)
		{
			$this->addError($attributes, Yii::t("app", "Mark must be less than $exam->maximum_marks ( Exam maximum marks)"));
		}
	
	}
	public function studentRollno($data,$row){
		$roll_no 	= "";
		$student = Students::model()->findByAttributes(array('id'=>$data->student_id));
		if(Configurations::model()->rollnoSettingsMode() != 2)
        {
			$batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
            $roll_no 	.= $batch_student->roll_no;
        }
		else{
			 $roll_no 	.='-';
		}
        return $roll_no;
	}
	
}