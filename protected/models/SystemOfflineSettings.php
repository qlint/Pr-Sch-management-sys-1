<?php

/**
 * This is the model class for table "system_offline_settings".
 *
 * The followings are the available columns in table 'system_offline_settings':
 * @property integer $id
 * @property string $offline_message
 * @property string $start_time
 * @property string $end_time
 * @property integer $status
 * @property integer $created_at
 * @property string $allowed_users
 */
class SystemOfflineSettings extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SystemOfflineSettings the static model class
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
		return 'system_offline_settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('start_time, end_time, status, created_at', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('offline_message', 'length', 'max'=>255),
			array('allowed_users', 'safe'),
                        array('end_time','check'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, offline_message, start_time, end_time, status, created_at, allowed_users', 'safe', 'on'=>'search'),
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
        
        public function check($attribute,$params)
        {
            if($this->end_time < $this->start_time)
            {
                $this->addError('end_time', Yii::t('app','End time must be greater than start time'));
            }
        }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'offline_message' => Yii::t('app','Message'),
			'start_time' => Yii::t('app','Start Time'),
			'end_time' => Yii::t('app','End Time'),
			'status' => Yii::t('app','Status'),
			'created_at' => Yii::t('app','Created At'),
			'allowed_users' => Yii::t('app','Allowed Users'),
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
		$criteria->compare('offline_message',$this->offline_message,true);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('allowed_users',$this->allowed_users,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        
        
        //check system on update time
        public function checkUpdate($name)
        {
            
            $settins_model= UserSettings::model()->findByAttributes(array('user_id'=>1));
            if($settins_model)
            {
                $zone_id= $settins_model->timezone;
                $timezone_model= Timezone::model()->findByPk($zone_id);
                if($timezone_model)
                {
                    date_default_timezone_set($timezone_model->timezone);
                }
            }
            
            $status=0;
            $array_list= array();
            $id= Yii::app()->user->id;
            $model= $this->model()->findAllByAttributes(array('status'=>1));
            foreach ($model as $key=>$data)
            {
                $start_time= $data->start_time;
                $end_time= $data->end_time;
                $current_time= date('Y-m-d H:i:s');
                if($end_time>= $current_time && $current_time>=$start_time)
                {
                    if(!preg_match('/\b' . $name . '\b/', $data->allowed_users))
                    {
                        $array_list[]=$data->id;   
                    }
                                    
                }
            }
             $user_login_status=1;
            if($array_list)
            {
                $id= $array_list[0];
                $model_message= SystemOfflineSettings::model()->findByPk($id);
                if($model_message)
                {
                    Yii::app()->user->setState('offline_message',$model_message->offline_message);
                }
                $user_login_status=0;
            }
            
            return $user_login_status;
        }
        
        public function updateStatus()
        {
            $offline_status=0;
            $model= $this->model()->findAllByAttributes(array('status'=>1));
            if($model)
            {
                return $offline_status=1;
            }
            return $offline_status;
        }
        
}