<?php

/**
 * This is the model class for table "fee_particular_access".
 *
 * The followings are the available columns in table 'fee_particular_access':
 * @property string $id
 * @property integer $academic_year_id
 * @property string $particular_id
 * @property integer $access_type
 * @property integer $course
 * @property integer $batch
 * @property integer $student_category_id
 * @property string $admission_no
 * @property integer $amount
 * @property string $created_at
 * @property string $last_edited
 * @property integer $created_by
 * @property integer $edited_by
 */
class FeeParticularAccess extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return FeeParticularAccess the static model class
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
		return 'fee_particular_access';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('access_type, amount, created_at, created_by', 'required'),
			array('academic_year_id, access_type, course, batch, student_category_id, created_by, edited_by', 'numerical', 'integerOnly'=>true),
			array('amount', 'type', 'type'=>'float', 'message'=>Yii::t('app', '{attribute} must be a valid number')),
			array('particular_id', 'length', 'max'=>20),
			array('admission_no', 'length', 'max'=>250),
			array('admission_no', 'required', 'on'=>'admission_no'),
			array('admission_no', 'isValidAdmissionNumber', 'on'=>'admission_no'),
			array('academic_year_id, particular_id, course, batch, student_category_id, admission_no, last_edited, edited_by', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, academic_year_id, particular_id, access_type, course, batch, student_category_id, admission_no, amount, created_at, last_edited, created_by, edited_by', 'safe', 'on'=>'search'),
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
			'academic_year_id' => 'Academic Year',
			'particular_id' => 'Particular',
			'access_type' => 'Access Type',
			'course' => 'Course',
			'batch' => Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),
			'student_category_id' => 'Student Category',
			'admission_no' => 'Admission No',
			'amount' => 'Amount',
			'created_at' => 'Created At',
			'last_edited' => 'Last Edited',
			'created_by' => 'Created By',
			'edited_by' => 'Edited By',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('academic_year_id',$this->academic_year_id);
		$criteria->compare('particular_id',$this->particular_id,true);
		$criteria->compare('access_type',$this->access_type);
		$criteria->compare('course',$this->course);
		$criteria->compare('batch',$this->batch);
		$criteria->compare('student_category_id',$this->student_category_id);
		$criteria->compare('admission_no',$this->admission_no,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('last_edited',$this->last_edited,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('edited_by',$this->edited_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function isValidAdmissionNumber(){
		$string		= str_replace(' ', '', $this->admission_no);
		$numbers	= explode(",",$string);
		foreach($numbers as $number){
			$found	= Students::model()->findByAttributes(array('admission_no'=>$number, 'is_active'=>1, 'is_deleted'=>0));
			if($found==NULL){
				$this->addError('admission_no', Yii::t('app', 'Invalid admission number found'));
			}
		}
	}
}