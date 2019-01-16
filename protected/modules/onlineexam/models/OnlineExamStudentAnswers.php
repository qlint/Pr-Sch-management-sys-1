<?php

/**
 * This is the model class for table "online_exam_student_answers".
 *
 * The followings are the available columns in table 'online_exam_student_answers':
 * @property integer $id
 * @property integer $student_id
 * @property integer $question_id
 * @property string $answer
 */
class OnlineExamStudentAnswers extends CActiveRecord {
 
   
	/**
	 * Returns the static model of the specified AR class.
	 * @return OnlineExamStudentAnswers the static model class
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
		return 'online_exam_student_answers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student_id, question_id, exam_id, ans', 'required'),
			array('student_id, question_id, exam_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, student_id, question_id, exam_id, ans, is_verified', 'safe', 'on'=>'search'),
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
			'question_id' => 'Question',
			'exam_id' => 'Exam Id',
			'ans' => 'Answer',
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
		$criteria->compare('question_id',$this->question_id);
		$criteria->compare('exam_id',$this->exam_id);
		$criteria->compare('ans',$this->ans,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        //get total count of verified answers
        public function getVerifiedAnswerCount($student_id,$exam_id=NULL)
        {
            $count  =   0;            
            if(isset($student_id) && $exam_id!=NULL)
            {
                
                $criteria                           =   new CDbCriteria;
                $criteria->join                     =   "JOIN `online_exam_questions` `eq` ON `eq`.`id`=`t`.`question_id`";
                $criteria->condition                =   '`t`.`student_id`=:student_id AND `eq`.`is_deleted`=:is_deleted AND `t`.`exam_id`=:exam_id AND `t`.`is_verified`=:is_verified';
                $criteria->params[':student_id']    =   $student_id;
                $criteria->params[':exam_id']       =   $exam_id;
                $criteria->params[':is_deleted']    =   0;     
                $criteria->params[':is_verified']   =   1;     
                $criteria->select                   =   'COUNT(is_verified) as is_verified';
                $answer_model                       =   OnlineExamStudentAnswers::model()->find($criteria);
                if($answer_model!=NULL)
                {
                    $count= $answer_model->is_verified;
                }                
            }                        
            return $count;
        }
        
        //check any pending answer verification
        public static function checkResultStatus($student_id,$exam_id=NULL)
        {
            $status =   0;
            if(isset($student_id) && $exam_id!=NULL)
            {
                $exam_attend_status     =   0;
                $verification_status    =   0;
                $model  = OnlineExamStudents::model()->findByAttributes(array('student_id'=>$student_id,'exam_id'=>$exam_id,'status'=>1));
                if($model!=NULL)
                {
                    $exam_attend_status =   1;
                }                
                $criteria                           =   new CDbCriteria;
                $criteria->join                     =   "JOIN `online_exam_questions` `eq` ON `eq`.`id`=`t`.`question_id`";
                $criteria->condition                =   '`t`.`student_id`=:student_id AND `eq`.`is_deleted`=:is_deleted AND `t`.`exam_id`=:exam_id AND `t`.`is_verified`=:is_verified';
                $criteria->params[':student_id']    =   $student_id;
                $criteria->params[':exam_id']       =   $exam_id;
                $criteria->params[':is_deleted']    =   0;     
                $criteria->params[':is_verified']   =   0;     
                $criteria->select                   =   'COUNT(is_verified) as is_verified';
                $answer_model                       =   OnlineExamStudentAnswers::model()->find($criteria);                
                if(isset($answer_model) && $answer_model->is_verified==0)
                {                    
                    $verification_status =   1;
                }
                
                if($exam_attend_status==1 && $verification_status==1)
                {
                    $status =   1; //exam attended and verification completed
                }
                else if($exam_attend_status==0)
                {
                    $status =   2; //exam not attended
                }
                else if($exam_attend_status==1 && $verification_status==0)
                {
                    $status =   3; //verification not completed
                }
            }                        
            return $status;
        }
        
        
}