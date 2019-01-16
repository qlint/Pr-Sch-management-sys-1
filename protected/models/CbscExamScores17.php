<?php

/**
 * This is the model class for table "cbsc_exam_scores_17".
 *
 * The followings are the available columns in table 'cbsc_exam_scores_17':
 * @property integer $id
 * @property integer $student_id
 * @property double $exam_id
 * @property double $written_exam
 * @property double $periodic_test
 * @property double $note_book
 * @property double $subject_enrichment
 * @property integer $total
 * @property string $remarks
 * @property integer $is_failed
 * @property string $created_at
 * @property string $updated_at
 */
class CbscExamScores17 extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CbscExamScores17 the static model class
	 */
	 public $sub_category1;
	public $sub_category2;
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cbsc_exam_scores_17';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student_id,exam_id, total, is_failed, created_at, updated_at', 'required'),
			array('student_id, is_failed', 'numerical', 'integerOnly'=>true),
			array('exam_id, written_exam, periodic_test, note_book, subject_enrichment', 'numerical'),
			array('remarks', 'length', 'max'=>225),
			array('sub_category1,sub_category2', 'match', 'pattern'=>'/^[0-9]+(\\.[0-9]+)?$/'),
			array('sub_category1','sub1_cat'),
			array('sub_category2','sub2_cat'),
			array('written_exam','checkwe'),
			array('periodic_test','checkpt'),
			array('note_book','checknb'),
			array('subject_enrichment','checkse'),
			array('total','checkmarkval'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, student_id, exam_id, written_exam, periodic_test, note_book, subject_enrichment, total, remarks, is_failed, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'written_exam' => 'Written Exam',
			'periodic_test' => 'Periodic Test',
			'note_book' => 'Note Book',
			'subject_enrichment' => 'Subject Enrichment',
			'total' => 'Total',
			'remarks' => 'Remarks',
			'is_failed' => 'Is Failed',
			'created_at' => 'Created At',
			'sub_category1' => 'This',
			'sub_category2' => 'This',
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
		$criteria->compare('written_exam',$this->written_exam);
		$criteria->compare('periodic_test',$this->periodic_test);
		$criteria->compare('note_book',$this->note_book);
		$criteria->compare('subject_enrichment',$this->subject_enrichment);
		$criteria->compare('total',$this->total);
		$criteria->compare('remarks',$this->remarks,true);
		$criteria->compare('is_failed',$this->is_failed);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function studentRollno($data,$row){
		$roll_no 	= "";
		$student = Students::model()->findByAttributes(array('id'=>$data->student_id));
		if(Configurations::model()->rollnoSettingsMode() != 2)
        {
			$batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
			if($batch_student!=NULL and $batch_student->roll_no != 0)
			{
            $roll_no 	.= $batch_student->roll_no;
			}
			else
			{
				$roll_no 	.='-';
			}
        }
		else{
			 $roll_no 	.='-';
			 
		}
        return $roll_no;
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
	public function getRemarks($data,$row){
		echo $data->remarks;
	}
	public function checkmarkval($attributes,$params)
	{
		$exm_id=$this->exam_id;
		if($this->total<0)
		{
			$this->addError($attributes, Yii::t("app", "Mark must be a positive integer"));
		}
		$exam=CbscExams17::model()->findByAttributes(array('id'=>$exm_id));
		if($this->total > $exam->maximum_marks)
		{
			$this->addError($attributes, Yii::t("app", "Mark must be less than $exam->maximum_marks ( Exam maximum marks)"));
		}
	
	}
	public function checkwe($attributes,$params)
	{
		
		$exm_id	= $this->exam_id;
		$exam	= CbscExams17::model()->findByAttributes(array('id'=>$exm_id));
		$exam_g	= CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
		if($exam_g->class !=4){
			if($this->written_exam ==  NULL){
				$this->addError($attributes, Yii::t("app", "Writtem exam cannot be blank."));
			}
		}
		if($this->written_exam<0){
			$this->addError($attributes, Yii::t("app", "Writtem exam must be a positive integer"));
		}
		if($exam_g->class == 1){
			if($this->written_exam>40) {
				$this->addError($attributes, Yii::t("app", "Writtem exam must be less than 40 "));
			}
		}else if($exam_g->class == 2 or $exam_g->class == 3){
			if($this->written_exam>80) {
				$this->addError($attributes, Yii::t("app", "Writtem exam must be less than 80 "));
			}
		}
	
	}
	public function checkpt($attributes,$params)
	{
		$exm_id	= $this->exam_id;
		$exam	= CbscExams17::model()->findByAttributes(array('id'=>$exm_id));
		$exam_g	= CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
		if($exam_g->is_final == 1  and $exam_g->class !=4){
			if($this->periodic_test ==  NULL){
				$this->addError($attributes, Yii::t("app", "Periodic test cannot be blank."));
			}
		}
		if($this->periodic_test<0){
			$this->addError($attributes, Yii::t("app", "Periodic test must be a positive integer"));
		}
		if($exam_g->class == 1 or $exam_g->class == 2 or $exam_g->class == 3){
			if($this->periodic_test>10) {
				$this->addError($attributes, Yii::t("app", "Periodic test exam must be less than 10 "));
			}
		} 
	
	}
	public function checknb($attributes,$params)
	{
		$exm_id	= $this->exam_id;
		$exam	= CbscExams17::model()->findByAttributes(array('id'=>$exm_id));
		$exam_g	= CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
		if($exam_g->is_final == 1 and $exam_g->class !=4){
			if($this->note_book ==  NULL){
				$this->addError($attributes, Yii::t("app", "Note book cannot be blank."));
			}
		}
		if($this->note_book<0){
			$this->addError($attributes, Yii::t("app", "Note book must be a positive integer"));
		}
		if($exam_g->class == 1 or $exam_g->class == 2 or $exam_g->class == 3){
			if($this->note_book>5) {
				$this->addError($attributes, Yii::t("app", "Note book exam must be less than 5 "));
			}
		} 
	
	}
	public function checkse($attributes,$params)
	{
		$exm_id	= $this->exam_id;
		$exam	= CbscExams17::model()->findByAttributes(array('id'=>$exm_id));
		$exam_g	= CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
		if($exam_g->is_final == 1 and $exam_g->class !=4){
			if($this->subject_enrichment ==  NULL){
				$this->addError($attributes, Yii::t("app", "Subject enrichment cannot be blank."));
			}
		}
		if($this->subject_enrichment<0){
			$this->addError($attributes, Yii::t("app", "Subject enrichment must be a positive integer"));
		}
		if($exam_g->class == 1 or $exam_g->class == 2 or $exam_g->class == 3){
			if($this->subject_enrichment>5) {
				$this->addError($attributes, Yii::t("app", "Subject enrichment exam must be less than 5 "));
			}
		} 
	
	}
	
	public function getClass1Grade($mark)
	{			
		if($mark >= 54.6 and $mark <= 60){
			$grade	= 'A1';
		}
		else if($mark >= 48.6 and $mark <= 54.5){
			$grade	= 'A2';
		}
		else if($mark >= 42.6 and $mark <= 48.5){
			$grade	= 'B1';
		}
		else if($mark >= 36.6 and $mark <= 42.5){
			$grade	= 'B2';
		}
		else if($mark >= 30.6 and $mark <= 36.5){
			$grade	= 'C1';
		}
		else if($mark >= 24.6 and $mark <= 30.5){
			$grade	= 'C2';
		}
		else if($mark >= 19.8 and $mark <= 24.5){
			$grade	= 'D';
		}
		else{
			$grade	= 'E';
		}
		return $grade;
	}
	
	public function getClass2Grade($mark)
	{			
		if($mark >= 91 and $mark <= 100){
			$grade	= 'A1';
		}
		else if($mark >= 81 and $mark <= 90){
			$grade	= 'A2';
		}
		else if($mark >= 71 and $mark <= 80){
			$grade	= 'B1';
		}
		else if($mark >= 61 and $mark <= 70){
			$grade	= 'B2';
		}
		else if($mark >= 51 and $mark <= 60){
			$grade	= 'C1';
		}
		else if($mark >= 41 and $mark <= 50){
			$grade	= 'C2';
		}
		else if($mark >= 33 and $mark <= 40){
			$grade	= 'D';
		}
		else{
			$grade	= 'E';
		}
		
		return $grade;
	}
	public function getcategory1($id)
	{
		$subject_spits	=	CbscExamScoresSplit17::model()->findByAttributes(array('exam_scores_id'=>$id));
		return $subject_spits->mark;
	}
	public function getcategory2($id)
	{
		$criteria=new CDbCriteria;
		$criteria->condition = "exam_scores_id LIKE :exam_scores_id";
		$criteria->params = array(":exam_scores_id"=>$id); 
		$criteria->order = 'id DESC';
		$models = CbscExamScoresSplit17::model()->findAll($criteria);  
		foreach($models as $model){
			return $model->mark;exit;
		}
	}
	public function category1($data,$row)
	{
		$subject_spits	=	CbscExamScoresSplit17::model()->findByAttributes(array('exam_scores_id'=>$data->id));
		return $subject_spits->mark;
	}
	public function category2($data,$row)
	{
		$criteria=new CDbCriteria;
		$criteria->condition = "exam_scores_id LIKE :exam_scores_id";
		$criteria->params = array(":exam_scores_id"=>$data->id); 
		$criteria->order = 'id DESC';
		$models = CbscExamScoresSplit17::model()->findAll($criteria);  
		foreach($models as $model){
			return $model->mark;exit;
		}
	}
	public function sub1_cat($attributes,$params)
	{
		if($this->sub_category1<0){
			$this->addError($attributes, Yii::t("app", "This must be a positive integer"));
		}
		$exm_id	= $this->exam_id;
		
		$exam	= CbscExams17::model()->findByPk($exm_id); 
		$sub	= Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
		if($sub->split_subject == 1){
			$exam_g	= CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
			
			if($exam_g->class ==4){
				if($this->sub_category1 ==  NULL){
					$this->addError($attributes, Yii::t("app", "This cannot be blank."));
				}else if($this->sub_category1>70){
					$this->addError($attributes, Yii::t("app", "Marks must be less than or equal to 70"));
				}
			} 
		}
	}
	public function sub2_cat($attributes,$params)
	{
		if($this->sub_category2<0){
			$this->addError($attributes, Yii::t("app", "This must be a positive integer"));
		}
		$exm_id	= $this->exam_id;
		$exam	= CbscExams17::model()->findByAttributes(array('id'=>$exm_id));
		$sub	= Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
		if($sub->split_subject == 1){
			$exam_g	= CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
			if($exam_g->class ==4){
				if($this->sub_category2 ==  NULL){
					$this->addError($attributes, Yii::t("app", "This cannot be blank."));
				}else if($this->sub_category2>30){
					$this->addError($attributes, Yii::t("app", "Marks must  be less than or equal to 30"));
				}
			} 
		}
	}  
        
    public function studentAdm($data,$row){
		$num= "";
		$scope='forStudentProfile';
		$student = Students::model()->findByAttributes(array('id'=>$data->student_id));
		if(FormFields::model()->isVisible('admission_no', 'Students', $scope))
		{
		    $num = ucfirst($student->admission_no);
		}
				
		return $num;
	}
	
	public function getGrade($data,$row){
		
		if($_REQUEST['examid']== NULL)
		{
			$_REQUEST['examid']=$_REQUEST['exam_id'];
		}
		//echo $_REQUEST['examid'];exit;
		$exm = CbscExams17::model()->findByAttributes(array('id'=>$_REQUEST['examid'])); 
	   	$examgroups = CbscExamGroup17::model()->findByAttributes(array('id'=>$exm->exam_group_id));
	   	if($examgroups->class==1){
	   		$grade = $this->getClass1Grade($data->total);
	   	}else{
	   		$grade = $this->getClass2Grade($data->total);
	   	}
	   	
	   	return $grade;
	}
}