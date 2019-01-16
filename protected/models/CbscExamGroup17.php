<?php

/**
 * This is the model class for table "cbsc_exam_group_17".
 *
 * The followings are the available columns in table 'cbsc_exam_group_17':
 * @property integer $id
 * @property integer $batch_id
 * @property string $name
 * @property integer $type
 * @property integer $class
 * @property integer $is_final
 * @property integer $date_published
 * @property integer $result_published
 * @property string $created_at
 */
class CbscExamGroup17 extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CbscExamGroup17 the static model class
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
		return 'cbsc_exam_group_17';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('batch_id, name, type, class, is_final, date_published, result_published, created_at', 'required'),
			array('batch_id, type, class, is_final, date_published, result_published', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, batch_id, name, type, class, is_final, date_published, result_published, created_at', 'safe', 'on'=>'search'),
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
			'batch_id' => 'Batch',
			'name' => 'Name',
			'type' => 'Type',
			'class' => 'Class',
			'is_final' => 'Is Final Exam',
			'date_published' => 'Date Published',
			'result_published' => 'Result Published',
			'created_at' => 'Date',
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
		$criteria->compare('batch_id',$this->batch_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('class',$this->class);
		$criteria->compare('is_final',$this->is_final);
		$criteria->compare('date_published',$this->date_published);
		$criteria->compare('result_published',$this->result_published);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function searchBatch()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->condition= 'batch_id='.$_REQUEST['bid'];

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public static function ExamTypes()
        {            
            //return array(1=>'CBSE 2016',2=>'CBSE 2017');
            return array(2=>'CBSE 2017');
        }
        
        public static function ClassTypes()
        {                 
            return array(1=>'Class 1-2',2=>'Class 3-8',3=>'Class 9-10',4=>'Class 11-12');
        }
        
		public function examType($data,$row)
		{
			$types  =   array(1=>'CBSE 2016',2=>'CBSE 2017');
			echo isset($types[$data->type])?$types[$data->type]:'-'; 
		}
		
		public static function Exam($id)
		{            
		$types  =   array(1=>'CBSE 2016',2=>'CBSE 2017');
		echo isset($types[$id])?$types[$id]:'-'; 
		} 
		
		
		public function classType($data,$row)
		{
			$types  =   array(1=>'Class 1-2',2=>'Class 3-8',3=>'Class 9-10',4=>'Class 11-12');
			echo isset($types[$data->class])?$types[$data->class]:'-'; 
		}
		
		public function getTypeName($id)
		{
			$types  =   array(1=>'CBSE 2016',2=>'CBSE 2017');
			return isset($types[$id])?$types[$id]:'-'; 
		}
		
		public function getClassName($id)
		{
			$class  =    array(1=>'Class 1-2',2=>'Class 3-8',3=>'Class 9-10',4=>'Class 11-12');
			return isset($class[$id])?$class[$id]:'-'; 
		}
		public static function ClassTypeData($id)
	{            
		 $class  =   array(1=>'Class 1-2',2=>'Class 3-8',3=>'Class 9-10',4=>'Class 11-12');
		echo isset($class[$id])?$class[$id]:'-'; 
	}
	public function subjectname($data,$row)
    {
		
		$groups = CbscExams17::model()->findAllByAttributes(array('exam_group_id'=>$data->id));
		foreach($groups as $group)
		{
			$subjects[]=Subjects::model()->findByAttributes(array('id'=>$group->subject_id));
		}
		if($subjects!=NULL)
		{
			foreach($subjects as $key=>$subject){
				$name=$name.ucfirst($subject->name);
				if($key+1<count($subjects))
					$name=$name.' ,';
			}
			return $name;
		}
		else
		{
			return '-';
		}
		
	}
        
        
}