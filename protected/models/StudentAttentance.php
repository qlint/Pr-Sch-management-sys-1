<?php



/**

 * This is the model class for table "student_attentance".

 *

 * The followings are the available columns in table 'student_attentance':

 * @property integer $id

 * @property integer $student_id

 * @property integer $date

 * @property string $reason

 */

class StudentAttentance extends CActiveRecord

{

	/**

	 * Returns the static model of the specified AR class.

	 * @return StudentAttentance the static model class

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

		return 'student_attentance';

	}



	/**

	 * @return array validation rules for model attributes.

	 */

	public function rules()

	{

		// NOTE: you should only define rules for those attributes that

		// will receive user inputs.		

		if((Yii::app()->controller->id == 'studentAttentance' or Yii::app()->controller->id == 'default') and (Yii::app()->controller->action->id == 'markDayAttendance' or Yii::app()->controller->action->id == 'updateDayAttendance')){
			return array(				
				array('student_id', 'numerical', 'integerOnly'=>true),
				array('reason', 'length', 'max'=>120),
				array('date', 'safe'),
				array('date', 'checkdate'),
				array('student_id, date, batch_id','required','on'=>'mark_day_attendance'),	
				array('student_id, date, batch_id, reason, leave_type_id','required','on'=>'update_day_attendance'),				
				array('id, student_id, date, reason, batch_id, leave_type_id', 'safe', 'on'=>'search'),
			);
		}
		else{
			return array(
				array('student_id, date, reason, leave_type_id', 'required'),
				array('student_id', 'numerical', 'integerOnly'=>true),
				array('reason', 'length', 'max'=>120),
				array('date', 'safe'),		
				array('date', 'checkdate'),				
				array('id, student_id, date, reason, batch_id, leave_type_id', 'safe', 'on'=>'search'),

			);

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

			'id' => Yii::t("app",'ID'),

			'student_id' => Yii::t("app",'Student'),

			'date' => Yii::t("app",'Date'),

			'reason' => Yii::t("app",'Reason'),

			'leave_type_id' => Yii::t("app",'Leave Type'),

		);

	}



	/**

	 * Retrieves a list of models based on the current search/filter conditions.

	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.

	 */

	 

	function createDateRangeArray($strDateFrom,$strDateTo)

	{

		// takes two dates formatted as YYYY-MM-DD and creates an

		// inclusive array of the dates between the from and to dates.

	

		// could test validity of dates here but I'm already doing

		// that in the main script

	

		$aryRange=array();

	

		$iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));

		$iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

	

		if ($iDateTo>=$iDateFrom)

		{

			array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry

			while ($iDateFrom<$iDateTo)

			{

				$iDateFrom+=86400; // add 24 hours

				array_push($aryRange,date('Y-m-d',$iDateFrom));

			}

		}

		return $aryRange;

	}

	

	function countDays($startDay,$endDay)

	{

		$startTimeStamp = strtotime($startDay);

		$endTimeStamp = strtotime($endDay);

		

		$timeDiff = abs($endTimeStamp - $startTimeStamp);

		

		$numberDays = ($timeDiff/86400)+1;  // 86400 seconds in one day

		

		// and you might want to convert to integer

		$numberDays = intval($numberDays);

		

		return $numberDays;

	}

	

	public function search()

	{

		// Warning: Please modify the following code to remove attributes that

		// should not be searched.



		$criteria=new CDbCriteria;



		$criteria->compare('id',$this->id);

		$criteria->compare('student_id',$this->student_id);

		$criteria->compare('date',$this->date);

		$criteria->compare('reason',$this->reason,true);

		$criteria->compare('leave_type_id',$this->leave_type_id);

		return new CActiveDataProvider($this, array(

			'criteria'=>$criteria,

		));

	}

	

	public function check($attribute,$params)

	{		

		if($this->date!='')

		{

			$student = Students::model()->findByAttributes(array('id'=>$this->student_id));

			if($this->date < $student->admission_date)

			{

					$this->addError($attribute,Yii::t("app",'Leave date should be greater than Student admission date'));

			}

		}

	}

	//Check whether the date is a working day

	public function isWeekday($date, $batch_id)

	{

		$flag	= 1;

		$day	= date('N', strtotime($date))+1;

		

		if($day > 7){

			$day = 1;

		}

		$weekdays = Weekdays::model()->findAllByAttributes(array('batch_id'=>$batch_id));

		if(count($weekdays) > 0){

			$is_working_day	= Weekdays::model()->findByAttributes(array('batch_id'=>$batch_id,'weekday'=>$day));

			if($is_working_day != NULL){

				$flag = 2;

			}

		}

		else{

			$weekdays = Weekdays::model()->find("batch_id IS NULL AND weekday=:weekday", array(':weekday'=>$day));

			if($weekdays){

				$flag = 2;

			}

		}			    	

		return $flag;

	}

	//Check whether the date is set as holiday

	public function isHoliday($date)

	{		

		$start	= $date.' '."00:00:00";

		$end 	= $date.' '."23:59:59";      

		$flag	= 0;

		$criteria 				= new CDbCriteria();     

		$criteria->condition 	= "start >=:start AND start <=:end";

		$criteria->params  		= array(':start'=>strtotime($start),':end'=>strtotime($end));

		$holiday				= Holidays::model()->findAll($criteria);

		if(count($holiday) > 0){

			$flag = 1;

		}

		return $flag;

	}	
	public function Checkdate($attribute,$params){
		if(Yii::app()->controller->id != 'androidApi'){ 
			$date	=	date('Y-m-d',strtotime($this->date));
			// check joining date 
			$join_date="";
			$student = Students::model()->findByAttributes(array('id'=>$this->student_id));
			if($student!=NULL)
			{
				$join_date  =   $student->admission_date;
				if($join_date > $date){
					$this->addError($attribute,$this->getAttributeLabel($attribute).' '.Yii::t("app",'must be greater than or equal to joining date'));
				}
			} 		
			//check curent date 
			
			if($date > date('Y-m-d')){
				$this->addError($attribute,$this->getAttributeLabel($attribute).' '.Yii::t("app",'must be less than or equal to current date.'));
			}
			// check holiday 
			$holiday_arr[] =array();
			$ischeck = Configurations::model()->findByPk(43);
			
			if($ischeck)
			{ 
				$holidays = Holidays::model()->findAll();
				$holiday_arr=array();
				foreach($holidays as $key=>$holiday)
				{
					if(date('Y-m-d',$holiday->start)!=date('Y-m-d',$holiday->end))
					{
						$date_range = StudentAttentance::model()->createDateRangeArray(date('Y-m-d',$holiday->start),date('Y-m-d',$holiday->end));
						foreach ($date_range as $value) {
							$holiday_arr[] = date('Y-m-d',$date_range);
						}
					}
					else
					{
						$holiday_arr[] = date('Y-m-d',$holiday->start);
					}
				}
			}
			if(in_array($date,$holiday_arr)) // If checking if it is a working day
			{
				$this->addError($attribute,' '.$this->date.' '.Yii::t("app",'is a holiday.'));
			} 
			
			// week day 
                        /*
			$weekdays = Weekdays::model()->findAll("batch_id=:x AND weekday<>:y", array(':x'=>$this->batch_id,':y'=>"0"));
			$weekArray	=	array();
			foreach($weekdays as $weekday)
			{
				
				$weekday->weekday = $weekday->weekday - 1;
				if($weekday->weekday <= 0)
				{
					$weekday->weekday = 7;
				}
				$weekArray[] = $weekday->weekday;
			} 
			$week_number = date('N', strtotime($this->date));
			if(!in_array($week_number,$weekArray)) // If checking if it is a working day
			{
                                $this->addError($attribute,' '.$this->date.' '.Yii::t("app",'is not a week day.'));
			}
                         * 
                         */
                        $week_day_flag = $this->isWeekday($this->date, $this->batch_id);
                        if($week_day_flag!=2){
                            $this->addError($attribute,' '.$this->date.' '.Yii::t("app",'is not a week day.'));
                        }
                        
			//batch start date and end date check
			$batch		=	Batches::model()->findByAttributes(array('id'=>$this->batch_id));
			$begin 		=	date('Y-m-d',strtotime($batch->start_date)); 
			$end 		= 	date('Y-m-d',strtotime($batch->end_date));
			if($date < $begin or $date > $end)
			{
				$this->addError($attribute,' '.Yii::t("app",'Date should be in the batch date range .'));
			}
		}
	}
	public function isClassTiming($t1, $t2,$t3,$t4,$this_date,$bid)
	{
		$start_time = $t1.' '.$t2;
		$end_time   = $t3.' '.$t4;
		$flag	= 0;
		$day	= date('N', strtotime($this_date))+1;;
		if($day > 7){
			$day = 1;
		}
		$class_time = ClassTimings::model()->findByAttributes(array('batch_id'=>$bid,'start_time'=>$start_time,'end_time'=>$end_time));
		if($class_time){
			 if($day == 1){
				 if($class_time->on_sunday == 1){
					$flag	= 1;
				 }
			 }else if($day == 2){
				 if($class_time->on_monday == 1){
					$flag	= 1;
				
				 }
			 }else if($day == 3){
				 if($class_time->on_tuesday == 1){
					$flag	= 1;
					
				 }
			 }else if($day == 4){
				 if($class_time->on_wednesday == 1){
					$flag	= 1;
				 }
			 }else if($day == 5){
				 if($class_time->on_thursday == 1){
					$flag	= 1;
				 }
			 }else if($day == 6){
				 if($class_time->on_friday == 1){
					$flag	= 1;
				 }
			 }else if($day == 7){
				 if($class_time->on_saturday == 1){
					$flag	= 1;
				 }
		 }				
		}

		return $flag;

	}
	
	
}