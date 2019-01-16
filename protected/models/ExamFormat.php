<?php

/**
 * This is the model class for table "exam_format".
 *
 * The followings are the available columns in table 'exam_format':
 * @property integer $id
 * @property string $exam_format
 * @property integer $value
 */
class ExamFormat extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ExamFormat the static model class
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
		return 'exam_format';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('exam_format, value', 'required'),
			array('value', 'numerical', 'integerOnly'=>true),
			array('exam_format', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, exam_format, value', 'safe', 'on'=>'search'),
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
			'exam_format' => 'Exam Format',
			'value' => 'Value',
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
		$criteria->compare('exam_format',$this->exam_format,true);
		$criteria->compare('value',$this->value);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function getExamformat($batch_id = NULL)
	{
		$level = Configurations::model()->findByPk(41);
		if($level->config_value < 0) // course level or batch level
		{
                   
			$batch = Batches::model()->findByPk($batch_id);
			if($batch == '' or $batch == NULL)
			{
				$format = 1;
			}
			else
			{
				if($level->config_value == -2) // batch level
				{
					if($batch->exam_format == '' or $batch->exam_format == NULL)
					{
						$format = 1;
					}
					else
					{
					     $format = $batch->exam_format;
					}
				}
				else
				{
					$course = Courses::model()->findByPk($batch->course_id);
					if($course == '' or $course == NULL)
					{
						$format = 1;
					}
					else
					{
						if($course->exam_format == '' or $course->exam_format == NULL or $course->exam_format == 0)
						{
							$format = 1;
						}
						else
						{
					        $format =  $course->exam_format;
						}
					}
				}
			}
			
		}
		else
		{
			$format = $level->config_value;
		}
		
		return $format;
		
	}
        
        public static function getCbscformat($batch_id = NULL)
        {
            return true; //for CBSC 17 format
        }
}