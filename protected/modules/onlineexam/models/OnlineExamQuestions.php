<?php

/**
 * This is the model class for table "online_exam_questions".
 *
 * The followings are the available columns in table 'online_exam_questions':
 * @property integer $id
 * @property integer $exam_id
 * @property string $question
 * @property integer $question_type
 * @property integer $answer_id
 * @property integer $order
 * @property integer $created_by
 * @property integer $status
 */
class OnlineExamQuestions extends CActiveRecord {
    
    public $exam_answer;
    public $choice_answer;
    public $correct_answer;
    public $answer;
    public $type_answer;
    public $choice_answer_id;
	
	public $ans;
	
   
    /**
	 * Returns the static model of the specified AR class.
	 * @return OnlineExamQuestions the static model class
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
		return 'online_exam_questions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('exam_id, question, question_type', 'required'),
			array('exam_id, question_type, answer_id, question_order, created_by, status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, exam_id, question, question_type, answer_id, question_order, created_by, is_deleted, status, mark', 'safe', 'on'=>'search'),
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
			'exam_id' => 'Exam',
			'question' => 'Question',
			'question_type' => 'Question Type',
			'answer_id' => 'Answer',
			'question_order' => 'Order',
			'created_by' => 'Created By',
			'status' => 'Status',
                        'exam_answer'=>'Answer'
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
		$criteria->compare('exam_id',$this->exam_id);
		$criteria->compare('question',$this->question,true);
		$criteria->compare('question_type',$this->question_type);
		$criteria->compare('answer_id',$this->answer_id);
		$criteria->compare('question_order',$this->question_order);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        //total mark of exam
        public function getTotalScore($exam_id)
        {
            $score='';
            if(isset($exam_id) && $exam_id!=NULL)
            {                
                $criteria               =   new CDbCriteria;
                $criteria->condition    =   'exam_id=:exam_id and is_deleted=:is_deleted';
                $criteria->params       =   array(':exam_id'=>$exam_id, ':is_deleted'=>0);
                $criteria->select       =   'SUM(mark) as mark';   
                $model                  =   $this->model()->find($criteria);               
                if($model!=NULL)
                {
                    $score  =   $model->mark;
                }
            }
            return $score;            
        }
        
        //get max order of question for a specific exam
        public function getHighestOrder($exam_id)
        {
            $order=1;
            if(isset($exam_id) && $exam_id!=NULL)
            {                
                $criteria               =   new CDbCriteria;
                $criteria->condition    =   'exam_id=:exam_id and is_deleted=:is_deleted';
                $criteria->params       =   array(':exam_id'=>$exam_id, ':is_deleted'=>0);
                $criteria->select       =   'MAX(CAST(question_order AS UNSIGNED)) AS question_order';
                $model                  =   $this->model()->find($criteria);               
                if($model!=NULL && $model->question_order!=NULL)
                {
                    $order  =   $model->question_order + 1;
                }
            }
            return $order;   
        }
                
        //check exam have short / multiline questions
        public function checkExamType($exam_id)
        {
            $status=false;
            if(isset($exam_id) && $exam_id!=NULL)
            {                
                $criteria               =   new CDbCriteria;
                $criteria->condition    =   'exam_id=:exam_id and is_deleted=:is_deleted';
                $criteria->params       =   array(':exam_id'=>$exam_id, ':is_deleted'=>0);                
                $criteria->addInCondition("`t`.question_type", array(3,4));
                $model                  =   $this->model()->find($criteria);               
                if($model!=NULL)
                {
                    $status=true;
                }
            }
            return $status;   
        }
        
        //total question count of an exam
        public function getQuestionsCount($exam_id)
        {
            $count = 0;
            if(isset($exam_id) && $exam_id!=NULL)
            {                
                $criteria               =   new CDbCriteria;
                $criteria->condition    =   'exam_id=:exam_id and is_deleted=:is_deleted';
                $criteria->params       =   array(':exam_id'=>$exam_id, ':is_deleted'=>0);                
                $criteria->select       =   'COUNT(id) as id';
                $model                  =   $this->model()->find($criteria);               
                if($model!=NULL)
                {
                    $count  =   $model->id;
                }
            }
            return $count;
        }
        
}