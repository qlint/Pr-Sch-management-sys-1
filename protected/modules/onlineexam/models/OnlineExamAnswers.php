<?php

/**
 * This is the model class for table "online_exam_answers".
 *
 * The followings are the available columns in table 'online_exam_answers':
 * @property integer $id
 * @property integer $question_id
 * @property string $answer
 * @property integer $order
 */
class OnlineExamAnswers extends CActiveRecord {
 
  
	/**
	 * Returns the static model of the specified AR class.
	 * @return OnlineExamAnswers the static model class
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
		return 'online_exam_answers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('question_id, answer', 'required'),
			array('question_id, order', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, question_id, answer, order', 'safe', 'on'=>'search'),
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
			'question_id' => 'Question',
			'answer' => 'Answer',
			'order' => 'Order',
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
		$criteria->compare('question_id',$this->question_id);
		$criteria->compare('answer',$this->answer,true);
		$criteria->compare('order',$this->order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        
        public function getAnswer($id)
        {
            $answer =   '-';
            if(isset($id) && $id!=NULL)
            {
                $model  = $this->model()->findByPk($id);
                if($model!=NULL)
                {
                    $answer =   $model->answer;
                }
            }
            return $answer;
        }
        
        //total exam score for multi choice and true/false questions
        public static function getChoiceScore($student_id,$exam_id=NULL,$batch_id)
        {
            $score='-';                 
            if(isset($student_id) && $exam_id!=NULL && isset($batch_id))
            {
                $total  =   0;
                $criteria                           =   new CDbCriteria;
                $criteria->join                     =   "JOIN `online_exam_questions` `eq` ON `eq`.`id`=`t`.`question_id` JOIN `online_exams` `oe` ON `oe`.`id`=`eq`.`exam_id`";
                $criteria->condition                =   '`t`.`student_id`=:student_id AND `oe`.`id`=:exam_id';
                $criteria->params[':student_id']    =   $student_id;
                $criteria->params[':exam_id']       =   $exam_id;
                $criteria->addInCondition("`eq`.question_type", array(1,2));
                $answer_model   = OnlineExamStudentAnswers::model()->findAll($criteria);
                
                
                if($answer_model!=NULL)
                {                   
                    foreach ($answer_model as $answer)
                    {
                        $question_id= $answer->question_id;
                        $question_model = OnlineExamQuestions::model()->findByPk($question_id);
                        if($question_model!=NULL)
                        {
                            if($question_model->answer_id==$answer->ans)
                            {
                                $total+=$question_model->mark;
                            }
                        }
                    }
                    $score=$total;
                }                                                                                                
            }
            return $score;
        }
        
        //total exam score for short and multi line questions
        public static function getTextScore($student_id,$exam_id=NULL,$batch_id)
        {
            $score='-';                 
            if(isset($student_id) && $exam_id!=NULL && isset($batch_id))
            {
                
                $criteria                           =   new CDbCriteria;                
                $criteria->condition                =   '`t`.`student_id`=:student_id AND `t`.`exam_id`=:exam_id';
                $criteria->params[':student_id']    =   $student_id;
                $criteria->params[':exam_id']       =   $exam_id;               
                $answer_model                       =   OnlineExamScores::model()->findAll($criteria);                                
                if($answer_model!=NULL)
                {       
                    $total  =   0;
                    foreach ($answer_model as $data)
                    {                        
                        $total+=$data->score;                            
                    }
                    $score=$total;
                }                  
            }
            return $score;
        }
}