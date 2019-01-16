<?php

/**
 * This is the model class for table "cbsc_exam_settings".
 *
 * The followings are the available columns in table 'cbsc_exam_settings':
 * @property integer $id
 * @property integer $academic_yr_id
 * @property integer $fa1_weightage
 * @property integer $fa2_weightage
 * @property integer $sa1_weightage
 * @property integer $sa2_weightage
 */
class CbscExamSettings extends CActiveRecord
{
    public $fa_error;
    public $sa_error;
    /**
	 * Returns the static model of the specified AR class.
	 * @return CbscExamSettings the static model class
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
		return 'cbsc_exam_settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('academic_yr_id,  fa1_weightage, fa2_weightage, sa1_weightage, sa2_weightage', 'required'),
			array('academic_yr_id, fa1_weightage, fa2_weightage, sa1_weightage, sa2_weightage', 'numerical', 'integerOnly'=>true),
			 array(' fa1_weightage, fa2_weightage, sa1_weightage, sa2_weightage', 'numerical', 'min'=>0),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, academic_yr_id, fa1_weightage, fa2_weightage, sa1_weightage, sa2_weightage', 'safe', 'on'=>'search'),
                        array('fa1_weightage, fa2_weightage, sa1_weightage, fa3_weightage, fa4_weightage, sa2_weightage','check'),
		);
	}
        
       
        public function check($attributes,$params)
        {
            if($attributes!=NULL)
            {                
                if($this->fa1_weightage > 50)
                {
                    $this->addError('fa1_weightage', Yii::t("app", "Weightage must be less than 50"));
                }
                if($this->fa2_weightage > 50)
                {
                    $this->addError('fa2_weightage', Yii::t("app", "Weightage must be less than 50"));
                }
                if($this->sa1_weightage > 50)
                {
                    $this->addError('sa1_weightage', Yii::t("app", "Weightage must be less than 50"));
                }
                if($this->sa2_weightage > 50)
                {
                    $this->addError('sa2_weightage', Yii::t("app", "Weightage must be less than 50"));
                }
            }
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
			'academic_yr_id' => 'Academic Yr',
			'fa1_weightage' => Yii::t("app","FA 1 Weightage"),
			'fa2_weightage' => Yii::t("app","FA 2 Weightage"),
			'sa1_weightage' => Yii::t("app","SA 1 Weightage"),
			'sa2_weightage' => Yii::t("app","SA 2 Weightage"),
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
		$criteria->compare('academic_yr_id',$this->academic_yr_id);
		$criteria->compare('fa1_weightage',$this->fa1_weightage);
		$criteria->compare('fa2_weightage',$this->fa2_weightage);
		$criteria->compare('sa1_weightage',$this->sa1_weightage);
		$criteria->compare('sa2_weightage',$this->sa2_weightage);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}