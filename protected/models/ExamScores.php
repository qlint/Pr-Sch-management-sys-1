<?php

/**
 * This is the model class for table "exam_scores".
 *
 * The followings are the available columns in table 'exam_scores':
 * @property integer $id
 * @property integer $student_id
 * @property integer $exam_id
 * @property string $marks
 * @property integer $grading_level_id
 * @property string $remarks
 * @property integer $is_failed
 * @property string $created_at
 * @property string $updated_at
 */
class ExamScores extends CActiveRecord
{
	public $name;
	public $roll_no;
	public $sub_category1;
	public $sub_category2;
	/**
	 * Returns the static model of the specified AR class.
	 * @return ExamScores the static model class
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
		return 'exam_scores';
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
			array('marks , sub_category1, sub_category2, student_id, exam_id, grading_level_id, is_failed', 'numerical', 'integerOnly'=>true),
			array('marks,sub_category1,sub_category2', 'length', 'max'=>7),
			array('marks','checkmarkval'), 
			array('remarks', 'length', 'max'=>150),
			array('created_at, updated_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, student_id, exam_id, grading_level_id, remarks, is_failed, created_at, updated_at,sub_category1,sub_category2', 'safe', 'on'=>'search'),
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
                     'student'    => array(self::BELONGS_TO, 'Students',    'student_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t("app",'ID'),
			'student_id' => Yii::t("app",'Student'),
			'exam_id' => Yii::t("app",'Exam'),
			'marks' => Yii::t("app",'Total Marks'),
			'sub_category1'=> Yii::t("app",'First Sub Category'),
			'sub_category2'=> Yii::t("app",'Second Sub Category'),
			'grading_level_id' => Yii::t("app",'Grading Level'),
			'remarks' => Yii::t("app",'Remarks'),
			'is_failed' => Yii::t("app",'Result Status'),
			'created_at' => Yii::t("app",'Created At'),
			'updated_at' => Yii::t("app",'Updated At'),
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
		
		
		$sort = new CSort();
        $sort->attributes = array(
        'firstname'=>array(
       'asc'=>'(SELECT first_name from students 
            WHERE students.id = t.student_id) ASC',       
       'desc'=>'(SELECT first_name from students 
            WHERE students.id = t.student_id) DESC',     
        ),
       '*', // add all of the other columns as sortable   
       ); 
		
		

		$criteria=new CDbCriteria;
                
		$criteria->compare('id',$this->id);
		$criteria->compare('student_id',$this->student_id);
		$criteria->compare('exam_id',$this->exam_id);
		$criteria->compare('marks',$this->marks,true);
		$criteria->compare('grading_level_id',$this->grading_level_id);
		$criteria->compare('remarks',$this->remarks,true);
		$criteria->compare('is_failed',$this->is_failed);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
                $criteria->compare('student.is_deleted',0, true);
                $criteria->compare('student.is_active',1, true);
                $criteria->with = array('student'); 
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort' =>$sort,
			'pagination'=>array(
	            'pageSize'=>50
       ),
	   
		));
	}
	
	
	public function studentname($data,$row)
    {
		$student = Students::model()->findByAttributes(array('id'=>$data->student_id));
		if($student!=NULL)
		{
                    if((Yii::app()->controller->id=='examScores' and Yii::app()->controller->action->id=='allexamscore'))
                    {
                        $name= "";
                        if(FormFields::model()->isVisible('first_name','Students','forTeacherPortal'))
                        {
                            $name.= ucfirst($student->first_name);
                        }
                        if(FormFields::model()->isVisible('last_name','Students','forTeacherPortal'))
                        {
                            $name.= " ".ucfirst($student->last_name);
                        }
                        return $name;
                       // return ucfirst($student->first_name).'- '.ucfirst($student->last_name);
                    }
                    else
                    {
                        return ucfirst($student->first_name).' '.ucfirst($student->last_name);
                    }
		}
		else
		{
			return '-';
		}
		
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
	
	public function GetGradinglevel($data,$row)
	{
		//$grade = GradingLevels::model()->findAllByAttributes(array('batch_id'=>$data));
		$grade = GradingLevels::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['id']),array('order'=>'min_score DESC'));
		
		if(!$grade)
		{
			$grade = GradingLevels::model()->findAllByAttributes(array('batch_id'=>NULL),array('order'=>'min_score DESC'));	
		}
		$i = count($grade);
		
		foreach($grade as $grade1)
		{
			//var_dump($grade1->min_score);exit;

			if($grade1->min_score<=$data->marks)
			{
				return  $grade1->name;
			}
			else
			{
				$i--;
				continue;
				
			}
		}
		if($i<=0){
			return Yii::t("app",'No Grades');
		}
	}
	
	/*get the grades for the teacher portal*/
	public function GetGradinglevelteacher($data,$row)
	{
		//$grade = GradingLevels::model()->findAllByAttributes(array('batch_id'=>$data));
		$grade = GradingLevels::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['bid']),array('order'=>'min_score DESC'));
		if(!$grade)
		{
			$grade = GradingLevels::model()->findAllByAttributes(array('batch_id'=>NULL),array('order'=>'min_score DESC'));	
		}
		$i = count($grade);
		
		foreach($grade as $grade1)
		{
			//var_dump($grade1->min_score);exit;

			if($grade1->min_score<=$data->marks)
			{
				return  $grade1->name;
			}
			else
			{
				$i--;
				continue;
				
			}
		}
		if($i<=0){
			return Yii::t("app",'No Grades');
		}
	}
	
	public function GetGradinglevelpdf($data)
	{
		$grade = GradingLevels::model()->findAllByAttributes(array('batch_id'=>$data->grading_level_id),array('order'=>'min_score DESC'));
		$i = count($grade);
		foreach($grade as $grade1)
		{

			if($grade1->min_score<=$data->marks)
			{
			return  $grade1->name;
			}
			else
			{
				$i--;
				continue;
				
			}
		}
		if($i<=0){
			return Yii::t("app",'No Grades');
		}
	}
	public function checkmarkval($attributes,$params)
	{
		$exm_id=$this->exam_id;
		if($this->marks<0)
		{
			$this->addError($attributes, Yii::t("app", "Mark must be a positive integer"));
		}
		$exam=Exams::model()->findByAttributes(array('id'=>$exm_id));
		if($this->marks>$exam->maximum_marks)
		{
			$this->addError($attributes, Yii::t("app", "Mark must be less than  $exam->maximum_marks ( Exam maximum marks)"));
		}
	
	} 
	public function category1($data,$row)
	{
		$subject_spits	=	ExamScoresSplit::model()->findByAttributes(array('exam_scores_id'=>$data->id));
		return $subject_spits->mark;
	}
	public function category2($data,$row)
	{
		$criteria=new CDbCriteria;
		$criteria->condition = "exam_scores_id LIKE :exam_scores_id";
		$criteria->params = array(":exam_scores_id"=>$data->id); 
		$criteria->order = 'id DESC';
		$models = ExamScoresSplit::model()->findAll($criteria); 
		foreach($models as $model){
			return $model->mark;exit;
		}
	}
	
	public function studentRollno($data,$row){
		$roll_no 	= "";
		$student = Students::model()->findByAttributes(array('id'=>$data->student_id));
		if(Configurations::model()->rollnoSettingsMode() != 2)
        {
			if($batch_student!=NULL and $batch_student->roll_no != 0)
			{
				$batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
				$roll_no 	.= $batch_student->roll_no;
			}
			else{
				$roll_no 	.='-';
			}
        }
		else{
			 $roll_no 	.='-';
		}
        return $roll_no;
	}
	
	public function GetDefaultgradinglevel($bid,$marks)
	{
		$grade = GradingLevels::model()->findAllByAttributes(array('batch_id'=>$bid),array('order'=>'min_score DESC'));
		
		if(!$grade)
		{
			$grade = GradingLevels::model()->findAllByAttributes(array('batch_id'=>NULL),array('order'=>'min_score DESC'));	
		}
		$i = count($grade);
		
		foreach($grade as $grade1)
		{
			
			if($grade1->min_score<=$marks)
			{
				return  $grade1->name;
			}
			else
			{
				$i--;
				continue;
				
			}
		}
		if($i<=0){
			return Yii::t("app",'No Grades');
		}
	}
	
	public function checkAccess($data)
	{
		$flag = 1;
		$exm = Exams::model()->findByAttributes(array('id'=>$data->exam_id));
		if($exm!=NULL)
		{
			$sub = Subjects::model()->findByAttributes(array('id'=>$exm->subject_id));
		}
		if($sub!=NULL)
		{
			$student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$data->student_id, 'elective_group_id'=>$sub->elective_group_id));
		}
		$teachflag=0;
		$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
		$is_teaching = EmployeesSubjects::model()->findByAttributes(array('subject_id'=>$sub->id,'employee_id'=>$employee->id));
		if($is_teaching!=NULL)
		{
			$teachflag=1;
		}
		else
		{ 
			$is_assigned = count(EmployeeElectiveSubjects::model()->findByAttributes(array('subject_id'=>$sub->id,'elective_id'=>$student_elective->elective_id,'employee_id'=>$employee->id)));
			
			if($is_assigned!=NULL)
			{
				$teachflag=1;
			}
		}
		if($teachflag != 1)
		{
			$flag =0;
		}
		
		return $flag;
	}

}
