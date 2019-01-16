<?php

/**
 * This is the model class for table "elective_scores".
 *
 * The followings are the available columns in table 'elective_scores':
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
class ElectiveScores extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ElectiveScores the static model class
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
		return 'elective_scores';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student_id, exam_id, grading_level_id, is_failed', 'numerical', 'integerOnly'=>true),
			array('marks', 'length', 'max'=>7),
			array('remarks', 'length', 'max'=>255),
			array('created_at, updated_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, student_id, exam_id, marks, grading_level_id, remarks, is_failed, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'student_id' => Yii::t("app",'Student'),
			'exam_id' => Yii::t("app",'Exam'),
			'marks' => Yii::t("app",'Marks'),
			'grading_level_id' => Yii::t("app",'Grading Level'),
			'remarks' => Yii::t("app",'Remarks'),
			'is_failed' => Yii::t("app",'Is Failed'),
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
		public function studentname($data,$row)
    {
		$student = Students::model()->findByAttributes(array('id'=>$data->student_id));
		if($student!=NULL)
		{
		return ucfirst($student->first_name).' '.ucfirst($student->last_name);
		}
		else
		{
			return '-';
		}
		
	}
	
	public function GetGradinglevel($data,$row)
	{
		$grade = GradingLevels::model()->findAllByAttributes(array('batch_id'=>$data->grading_level_id));
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
}