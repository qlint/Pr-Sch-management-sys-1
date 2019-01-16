<?php

/**
 * This is the model class for table "exams_common".
 *
 * The followings are the available columns in table 'exams_common':
 * @property integer $id
 * @property string $name
 * @property string $exam_type
 * @property integer $is_published
 * @property integer $result_published
 * @property string $exam_date
 * @property integer $created_by
 * @property string $created_at
 */
class CommonExams extends CActiveRecord
{
	public $batches;
	/**
	 * Returns the static model of the specified AR class.
	 * @return CommonExams the static model class
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
		return 'exams_common';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('is_published, result_published, created_by', 'numerical', 'integerOnly'=>true),
			array('name, exam_type', 'length', 'max'=>25),
			array('created_at', 'safe'),			
			array('exam_type, name, exam_date, batches', 'required'),
			array('exam_date','type','type' =>'date','dateFormat' => 'yyyy-MM-dd','message' => "{attribute}: ".Yii::t("app","is not a date!")),                   
			//array('name','CRegularExpressionValidator', 'pattern'=>'/^[A-Za-z_ ]+$/','message'=>"{attribute} should contain only letters and numbers."),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, exam_type, is_published, result_published, exam_date', 'safe', 'on'=>'search'),
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
			'name' => Yii::t("app",'Name'),
			'batches' => Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),
			'exam_type' => Yii::t("app",'Exam Type'),
			'is_published' => Yii::t("app",'Date Is Published'),
			'result_published' => Yii::t("app",'Result Published'),
			'exam_date' => Yii::t("app",'Exam Date'),
			'created_by' => Yii::t("app",'Created By'),
			'created_at' => Yii::t("app",'Created At'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('exam_type',$this->exam_type,true);
		$criteria->compare('is_published',$this->is_published);
		$criteria->compare('result_published',$this->result_published);
		$criteria->compare('exam_date',$this->exam_date,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getExamDate(){
		$settings	= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
		if($settings != NULL){
			$displaydate	= $settings->displaydate;
		}
		else{
			$displaydate	= 'd M Y';
		}
		return date($displaydate, strtotime($this->exam_date));
	}
	
	public function getBatchName($batch_id){		
		$batch 	= Batches::model()->findByPk($batch_id);
		$name	= "";		
		if($batch!=NULL){
			$name	= $batch->course123->course_name;
			$semester_enabled	= Configurations::model()->isSemesterEnabledForCourse($batch->course123->id);
			if($semester_enabled and $batch->semester_id!=NULL){	// enabled
				$semester	= Semester::model()->findByPk($batch->semester_id);
				if($semester!=NULL and $semester->name!=NULL){
					$name	.= ' / '.$semester->name;
				}
			}
			$name	.= ' / '.$batch->name;
		}
		
		return $name;
	}
}