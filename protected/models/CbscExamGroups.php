<?php

/**
 * This is the model class for table "cbsc_exam_groups".
 *
 * The followings are the available columns in table 'cbsc_exam_groups':
 * @property integer $id
 * @property integer $term_id
 * @property string $name
 * @property string $exam_type
 * @property string $mark_type
 * @property integer $date_published
 * @property integer $result_published
 * @property string $date
 */
class CbscExamGroups extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CbscExamGroups the static model class
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
		return 'cbsc_exam_groups';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('term_id, name, batch_id, exam_type, date', 'required'),
			array('term_id, batch_id, date_published, result_published', 'numerical', 'integerOnly'=>true),
			array('name, exam_type,', 'length', 'max'=>255),
			array('exam_type', 'checkterm'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, term_id, batch_id, name, exam_type,date_published, result_published, date', 'safe', 'on'=>'search'),
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
			'term_id' => 'Term',
			'name' => 'Name',
			'batch_id' => Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),
			'exam_type' => 'Exam Type',
			'date_published' => 'Date Published',
			'result_published' => 'Result Published',
			'date' => 'Exam Date',
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
		$criteria->compare('term_id',$this->term_id);
		$criteria->compare('batch_id',$this->batch_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('exam_type',$this->exam_type,true);
		$criteria->compare('date_published',$this->date_published);
		$criteria->compare('result_published',$this->result_published);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function examType($data,$row)
	{
		echo Yii::t('app',$data->exam_type);
	}
	
	public function checkterm($attribute,$params)
	{		
            if($this->term_id!='' && $this->batch_id!="" && $this->exam_type!="")
            {
                $exam_group= CbscExamGroups::model()->findByAttributes(array('term_id'=>$this->term_id,'batch_id'=>$this->batch_id,'exam_type'=>$this->exam_type));
                if($exam_group!=NULL)
                {
                        $this->addError($attribute,Yii::t("app",'Exam already created for this term'));
                }
            }
	}
	
	
	public function getExamtype($term_id = NULL, $batch_id=NULL, $id=NULL)
		{	
			$criteria	= new CDbCriteria;
			$criteria->condition	= "term_id=:term_id AND batch_id=:batch_id";
			$criteria->params		= array(":term_id"=>$term_id, ":batch_id"=>$batch_id);
			if($id!=NULL){
				$criteria->condition	.= " AND id<>:id";
				$criteria->params[":id"]	= $id;
			}
                    //$examgroups = CbscExamGroups::model()->findAllByAttributes(array('term_id'=>$term_id,'batch_id'=>$batch_id));						
					$examgroups = CbscExamGroups::model()->findAll($criteria);
                    $type = array();
                    foreach($examgroups as $examgroup)
                    {
                        $type[] = $examgroup->exam_type;
                    }	
                    if($term_id){	

                        if($term_id == 1)
                        {
                            $exam_type = array('FA1'=>'FA1', 'SA1'=>'SA1');
                           	$data = array_diff($exam_type,$type);
                        }
                        if($term_id == 2){					
                            $exam_type = array('FA2'=>'FA2','SA2'=>'SA2');
                          	$data =  array_diff($exam_type,$type);
                        }

                        
                    }	
					return $data;		
		} 
}