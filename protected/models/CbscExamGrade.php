<?php

/**
 * This is the model class for table "cbsc_exam_grade".
 *
 * The followings are the available columns in table 'cbsc_exam_grade':
 * @property integer $id
 * @property string $grade
 * @property integer $min_mark
 * @property integer $max_mark
 * @property string $grade_point
 * @property string $status
 */
class CbscExamGrade extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CbscExamGrade the static model class
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
		return 'cbsc_exam_grade';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('grade, min_mark, max_mark, grade_point, status', 'required'),
			array('min_mark, max_mark', 'numerical', 'integerOnly'=>true),
			array('grade, status', 'length', 'max'=>20),
			array('grade_point', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, grade, min_mark, max_mark, grade_point, status', 'safe', 'on'=>'search'),
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
			'grade' => 'Grade',
			'min_mark' => 'Min Mark',
			'max_mark' => 'Max Mark',
			'grade_point' => 'Grade Point',
			'status' => 'Status',
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
		$criteria->compare('grade',$this->grade,true);
		$criteria->compare('min_mark',$this->min_mark);
		$criteria->compare('max_mark',$this->max_mark);
		$criteria->compare('grade_point',$this->grade_point,true);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        
        public function getGrade($mark)
        {            
            $criteria1= new CDbCriteria;
            $criteria1->select= "MAX(max_mark) as max_mark, grade";            
            $model1= $this->model()->find($criteria1);
            if($model1!=NULL)
            {
                $max_mark = $model1->max_mark;
                $grade= $model1->grade;
            }
            
            $criteria= new CDbCriteria;
            $criteria->condition= "max_mark >=:mark AND min_mark <=:mark";
            $criteria->params= array(':mark'=>$mark);
            $model= $this->model()->find($criteria);
            if($model!=NULL)
            {
                return $model->grade;
            }
            elseif($mark > $max_mark)
            {
                return $grade;
            }
            else
            {
                return "-";
            }
        }
        
        public function getGradePoint($mark)
        {
            $criteria= new CDbCriteria;
            $criteria->condition= "max_mark >=:mark AND min_mark <=:mark";
            $criteria->params= array(':mark'=>$mark);
            $model= $this->model()->find($criteria);
            if($model!=NULL)
            {
                return $model->grade_point;
            }
            else
            {
                return "-";
            }
        }
        
}