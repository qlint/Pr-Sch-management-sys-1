<?php

/**
 * This is the model class for table "online_exams".
 *
 * The followings are the available columns in table 'online_exams':
 * @property integer $id
 * @property string $name
 * @property integer $batch_id
 * @property string $start_time
 * @property string $end_time
 * @property string $created_at
 * @property integer $created_by
 * @property integer $status
 */
class OnlineExams extends CActiveRecord {
    public $course;
    /**
	 * Returns the static model of the specified AR class.
	 * @return OnlineExams the static model class
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
		return 'online_exams';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, batch_id, start_time, duration, end_time, created_at, created_by', 'required'),
			array('batch_id, created_by, status', 'numerical', 'integerOnly'=>true),
                        array('end_time','check'),
                        array('duration','checktime'),
                        array('duration', 'numerical', 'max'=>300, 'min'=>1),
                        array('choice_limit', 'numerical', 'max'=>25, 'min'=>2),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, batch_id, start_time, end_time, duration, created_at, created_by, status, is_deleted', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'batch_id' => 'Batch',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'created_at' => 'Created At',
			'created_by' => 'Created By',
			'status' => 'Status',
                        'duration' => 'Duration',
                        'choice_limit' => 'Multi Choice Limit',
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
		$criteria->compare('batch_id',$this->batch_id);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function encryptToken($etoken){ 
	
		$salt	= rand(5, 9);
		for($i=0; $i<$salt; $i++){
			$etoken	= strrev(base64_encode($etoken));
		}
		$etoken	.= rand(1000, 9999);
		$etoken	.= $salt;
		return $etoken;
	}
	public function decryptToken($token)
	{
		$salt 	= substr($token, -1);
		$token	= substr_replace($token, "", -5);
		for($i=0; $i<$salt; $i++){
			$token	= base64_decode(strrev($token));
		}
		return $token;
	}
        
        protected function getBatch(){
		$batch	= Batches::model()->findByPk($this->batch_id);
		if($batch==NULL)
			return '-';
		return $batch->name;
	}
	
        public static function getBatchId($id)
        {
            $batch_id='';
            $model= OnlineExams::model()->findByPk($id);
            if($model!=NULL)
            {
                $batch_id= $model->batch_id;
            }
            return $batch_id;
        }

        public function check($attributes,$params)
        {			
	    if($this->start_time!="" && $this->end_time!="")
            {
                if($this->start_time > $this->end_time)
                {
                    $this->addError($attributes, Yii::t("app", "End time must be greater than Start time"));
                }
            }								
	}
        
        public function checktime($attributes,$params)
        {			
	    if($this->start_time!="" && $this->end_time!="" && $this->duration)
            {
                $start  =   strtotime($this->start_time);
                $end    =   strtotime($this->end_time);
                $duration   =   round(abs($end - $start) / 60,2);
                if($duration < $this->duration)
                {
                    $this->addError($attributes, Yii::t("app", "Duration must be less than or equal to time interval"));
                }
            }								
	}
	 
}