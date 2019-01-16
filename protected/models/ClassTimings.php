<?php

/**
 * This is the model class for table "class_timings".
 *
 * The followings are the available columns in table 'class_timings':
 * @property integer $id
 * @property integer $batch_id
 * @property string $name
 * @property string $start_time
 * @property string $end_time
 * @property integer $is_break
 */
class ClassTimings extends CActiveRecord
{
	public $all_batches;
    public $exception;
	public $all_weekdays;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return ClassTimings the static model class
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
		return 'class_timings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('batch_id, is_break', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('start_time, end_time', 'length', 'max'=>120),
			array('name, start_time, end_time', 'required'),
			array('end_time','check'),
			array('start_time','check_start','on'=>'add'),
			array('end_time','check_end','on'=>'add'),
			array('on_sunday, on_monday, on_tuesday, on_wednesday, on_thursday, on_friday, on_saturday','safe'),
			
			//array('name','CRegularExpressionValidator', 'pattern'=>'/^[A-Za-z0-9_ ]+$/','message'=>"{attribute} ".Yii::t("app","should contain only letters.")),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, batch_id, name, start_time, end_time, is_break, admin_id, is_edit', 'safe', 'on'=>'search'),
		);
	}

	//for checking start time exist on another class time
	public function check_start($attribute,$params)
	{
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
		if($settings!=NULL){
				$time=$settings->timeformat;
		}
		if(Yii::app()->controller->id == 'classTiming'){
			$batch_id = $_REQUEST['id'];
		}else{
			$batch_id = 0;
		}			
		$criteria= new CDbCriteria;
		if(Yii::app()->controller->action->id=="update"){
			$criteria->condition= "batch_id=:batch_id AND id != :id";
			$criteria->params=array(':batch_id'=>$batch_id, ':id'=>$this->id);
		}
		if(Yii::app()->controller->action->id=="create"){
			$criteria->condition= "batch_id=:batch_id";
			$criteria->params=array(':batch_id'=>$batch_id);
		}
		
		//special case - for flexible timings
		
		if(Configurations::model()->timetableFormat($batch_id)==2){
			$weekday_condition	= '';
			if(isset($this->on_sunday) and $this->on_sunday==1){
				$weekday_condition					.= "on_sunday=:on_sunday";
				$criteria->params[':on_sunday']		= 1;
			}
			if(isset($this->on_monday) and $this->on_monday==1){
				$weekday_condition					.= (($weekday_condition!="")?" OR ":"")."on_monday=:on_monday";
				$criteria->params[':on_monday']		= 1;
			}
			if(isset($this->on_tuesday) and $this->on_tuesday==1){
				$weekday_condition					.= (($weekday_condition!="")?" OR ":"")."on_tuesday=:on_tuesday";
				$criteria->params[':on_tuesday']	= 1;
			}
			if(isset($this->on_wednesday) and $this->on_wednesday==1){
				$weekday_condition					.= (($weekday_condition!="")?" OR ":"")."on_wednesday=:on_wednesday";
				$criteria->params[':on_wednesday']	= 1;
			}
			if(isset($this->on_thursday) and $this->on_thursday==1){
				$weekday_condition					.= (($weekday_condition!="")?" OR ":"")."on_thursday=:on_thursday";
				$criteria->params[':on_thursday']	= 1;
			}
			if(isset($this->on_friday) and $this->on_friday==1){
				$weekday_condition					.= (($weekday_condition!="")?" OR ":"")."on_friday=:on_friday";
				$criteria->params[':on_friday']		= 1;
			}
			if(isset($this->on_saturday) and $this->on_saturday==1){
				$weekday_condition					.= (($weekday_condition!="")?" OR ":"")."on_saturday=:on_saturday";
				$criteria->params[':on_saturday']	= 1;
			}
			
			if($weekday_condition!="")
				$criteria->condition	.= " AND ( ".$weekday_condition." )";
		}
		
		//special case - for flexible timings		
		
		$class_timings = ClassTimings::model()->findAll($criteria);
		foreach($class_timings as $class_timing){
			//check time format - 12HRS / 14HRS
			$st_time = preg_match('#^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$#', $class_timing->start_time);
			$en_time = preg_match('#^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$#', $class_timing->end_time);
			if($st_time != 1){
				$class_timing->start_time = date("H:i", strtotime($class_timing->start_time));
			}
			if($st_time != 1){
				$class_timing->end_time = date("H:i", strtotime($class_timing->end_time));
			}
			if($time=='h:i A'){
				$start_time = date("H:i", strtotime($this->start_time));  
				$end_time= date("H:i", strtotime($this->end_time)); 
			}else{
				$start_time = $this->start_time;  
				$end_time= $this->end_time;
			}
			
			if($start_time >= $class_timing->start_time and $start_time < $class_timing->end_time){
				$this->addError($attribute,Yii::t('app','Timing already in use'));
				return;
			}  
			else{
				if($start_time < $class_timing->end_time and $class_timing->end_time < $end_time){
					$this->addError($attribute,Yii::t('app','Timing already in use'));
					return;
				}
			}
		}                
	}

	public function check_end($attribute,$params)
	{
		if(Yii::app()->controller->id == 'classTiming'){
			$batch_id = $_REQUEST['id'];
		}else{
			$batch_id = 0;
		}
		$criteria= new CDbCriteria;
		if(Yii::app()->controller->action->id=="update"){
			$criteria->condition= "batch_id=:batch_id AND id != :id";
			$criteria->params=array(':batch_id'=>$batch_id, ':id'=>$this->id);
		}
		if(Yii::app()->controller->action->id=="create"){
			$criteria->condition= "batch_id=:batch_id";
			$criteria->params=array(':batch_id'=>$batch_id);
		}
		
		//special case - for flexible timings
		
		if(Configurations::model()->timetableFormat($batch_id)==2){
			$weekday_condition	= '';
			if(isset($this->on_sunday) and $this->on_sunday==1){
				$weekday_condition					.= "on_sunday=:on_sunday";
				$criteria->params[':on_sunday']		= 1;
			}
			if(isset($this->on_monday) and $this->on_monday==1){
				$weekday_condition					.= (($weekday_condition!="")?" OR ":"")."on_monday=:on_monday";
				$criteria->params[':on_monday']		= 1;
			}
			if(isset($this->on_tuesday) and $this->on_tuesday==1){
				$weekday_condition					.= (($weekday_condition!="")?" OR ":"")."on_tuesday=:on_tuesday";
				$criteria->params[':on_tuesday']	= 1;
			}
			if(isset($this->on_wednesday) and $this->on_wednesday==1){
				$weekday_condition					.= (($weekday_condition!="")?" OR ":"")."on_wednesday=:on_wednesday";
				$criteria->params[':on_wednesday']	= 1;
			}
			if(isset($this->on_thursday) and $this->on_thursday==1){
				$weekday_condition					.= (($weekday_condition!="")?" OR ":"")."on_thursday=:on_thursday";
				$criteria->params[':on_thursday']	= 1;
			}
			if(isset($this->on_friday) and $this->on_friday==1){
				$weekday_condition					.= (($weekday_condition!="")?" OR ":"")."on_friday=:on_friday";
				$criteria->params[':on_friday']		= 1;
			}
			if(isset($this->on_saturday) and $this->on_saturday==1){
				$weekday_condition					.= (($weekday_condition!="")?" OR ":"")."on_saturday=:on_saturday";
				$criteria->params[':on_saturday']	= 1;
			}
			
			if($weekday_condition!="")
				$criteria->condition	.= " AND ( ".$weekday_condition." )";
		}
		
		//special case - for flexible timings
		
		$class_timings = ClassTimings::model()->findAll($criteria);	
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
		if($settings!=NULL){
			$time=$settings->timeformat;
		}
		
		foreach($class_timings as $class_timing){
			$st_time = preg_match('#^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$#', $class_timing->start_time);
			$en_time = preg_match('#^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$#', $class_timing->end_time);
			if($st_time != 1){
				$class_timing->start_time = date("H:i", strtotime($class_timing->start_time));
			}
			if($st_time != 1){
				$class_timing->end_time = date("H:i", strtotime($class_timing->end_time));
			}
			if($time=='h:i A'){
				$end_time = date("H:i", strtotime($this->end_time));                        
			}else{
				$end_time = $this->end_time;                        
			}
			if($end_time > $class_timing->start_time and $end_time < $class_timing->end_time){
				$this->addError($attribute,Yii::t('app','Timing already in use'));
				return;
			}
			else{
				if($end_time < $class_timing->end_time and $end_time > $class_timing->start_time){
					$this->addError($attribute,Yii::t('app','Timing already in use'));
					return;
				}
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

	public function check($attribute,$params)
        {
            $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
            if($settings!=NULL){
                    $time=$settings->timeformat;
            }
            /*if($time=='h:i A'){
                $start_time = date("H:i", strtotime($this->start_time));
                $end_time = date("H:i", strtotime($this->end_time));
            }else{
                $start_time = $this->start_time;
                $end_time = $this->end_time;
            }*/
       		
       		$start_time = date("H:i", strtotime($this->start_time));
            $end_time = date("H:i", strtotime($this->end_time));
            
            
            
            if($start_time!=NULL and $end_time!=NULL)
            {
                    if($start_time == $end_time)
                    {
                            $this->addError('end_time',Yii::t('app','End time must be greater than start time'));
                    }
                    if($start_time >  $end_time)
                        {
                            $this->addError('end_time',Yii::t('app','End time must be greater than start time'));
                    }
            }
		
	} 
        
        
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t("app",'ID'),
			'batch_id' => Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),
			'name' => Yii::t("app",'Name'),
			'start_time' => Yii::t("app",'Start Time'),
			'end_time' => Yii::t("app",'End Time'),
			'is_break' => Yii::t("app",'Is Break'),
			'action' => Yii::t("app",'Action'),
			'all_batches' =>Yii::t("app",'All').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),
			'all_weekdays' => Yii::t('app', 'All'),
			'on_sunday' => Yii::t('app', 'Sunday'),
			'on_monday' => Yii::t('app', 'Monday'),
			'on_tuesday' => Yii::t('app', 'Tuesday'),
			'on_wednesday' => Yii::t('app', 'Wednesday'),
			'on_thursday' => Yii::t('app', 'Thursday'),
			'on_friday' => Yii::t('app', 'Friday'),
			'on_saturday' => Yii::t('app', 'Saturday'),
		);
	}

	public function getBatch_name(){
		$model=Batches::model()->findByPk($this->batch_id);
		return $model->name;
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
		if(Yii::app()->controller->id == 'commonClassTimings'){
			$criteria->compare('batch_id',0);
		}else{
			$criteria->compare('batch_id',$this->batch_id);
		}
		$criteria->compare('name',$this->name,true);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('is_break',$this->is_break);
		$criteria->order = 'start_time asc';
	 	$criteria->order = "STR_TO_DATE(start_time, '%h:%i %p')";
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getWeekDays(){
		$weekdays	= array(
			'on_sunday'=>$this->getAttributeLabel('on_sunday'),
			'on_monday'=>$this->getAttributeLabel('on_monday'),
			'on_tuesday'=>$this->getAttributeLabel('on_tuesday'),
			'on_wednesday'=>$this->getAttributeLabel('on_wednesday'),
			'on_thursday'=>$this->getAttributeLabel('on_thursday'),
			'on_friday'=>$this->getAttributeLabel('on_friday'),
			'on_saturday'=>$this->getAttributeLabel('on_saturday')
		);
		
		return $weekdays;
	}	
	public function startTime($data,$row)
    {
		$t_interval		=	Configurations::model()->convertTime($data->start_time); 
		return $t_interval;
		
	}
	public function endTime($data,$row)
    {
		$t_interval		=	Configurations::model()->convertTime($data->end_time); 
		return $t_interval;
	}
	/*public function convertTime($time) {
		$t_interval1 = date('h:i', strtotime($time));	
		$t_interval2 = date('A', strtotime($time));
		return $t_interval1.' '.Yii::t("app",$t_interval2);
 	}
 	  */
}