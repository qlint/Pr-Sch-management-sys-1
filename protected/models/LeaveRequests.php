<?php

/**
 * This is the model class for table "leave_requests".
 *
 * The followings are the available columns in table 'leave_requests':
 * @property integer $id
 * @property integer $leave_type_id
 * @property string $from_date
 * @property string $to_date
 * @property integer $is_half_day
 * @property string $reason
 * @property string $file_name
 * @property integer $status

 */
class LeaveRequests extends CActiveRecord
{
	public $half;
	public $half_day;
	/**
	 * Returns the static model of the specified AR class.
	 * @return LeaveRequests the static model class
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
		return 'leave_requests';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		if(Yii::app()->controller->module->id=='hr' and Yii::app()->controller->id=='leaves' and Yii::app()->controller->action->id == 'cancel' or Yii::app()->controller->module->id=='teachersportal' and Yii::app()->controller->id=='leaves' and Yii::app()->controller->action->id == 'cancel'){
			return array(
				array('cancel_reason', 'required'),
			);
		}
		else if(Yii::app()->controller->id == 'leaveRequests' and Yii::app()->controller->action->id == 'approve'){
			return array(
				array('from_date, to_date, response', 'required'),
				array('from_date', 'checkleavedate'),
				array('from_date', 'checkfromdate'),
				array('from_date', 'checkleavetaken'),
			);
		}
		else if(Yii::app()->controller->id == 'leaveRequests' and Yii::app()->controller->action->id == 'reject'){
			return array(
				array('response', 'required'),
			);
		}
		else{
			return array(
				array('leave_type_id, from_date, to_date, reason', 'required'),
				array('leave_type_id, requested_by, is_half_day, status', 'numerical', 'integerOnly'=>true),
				array('reason, cancel_reason, file_name', 'length', 'max'=>255),
				array('from_date', 'checkleavedate'),
				array('from_date', 'checkfromdate'),
				array('from_date', 'checkleavetaken'),
				//array('leave_type_id', 'checkleavetaken'),
				//array('leave_type_id', 'checkremaining'),
				array('is_half_day', 'checkhalfday'),				
				array('status, handled_by, handled_at, response', 'required', 'on'=>'respond'),
				array('cancel_reason', 'safe', 'on'=>'respond'),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, leave_type_id, requested_by, from_date, to_date, is_half_day, reason, file_name, status, cancel_reason', 'safe', 'on'=>'search'),
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
			'leaveType'=>array(self::BELONGS_TO, 'LeaveTypes', 'leave_type_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'leave_type_id' => Yii::t('app','Leave Type'),
			'requested_by' => Yii::t('app','Requested By'),
			'from_date' => Yii::t('app','From'),
			'to_date' => Yii::t('app','To'),
			'is_half_day' => Yii::t('app','Is Half Day'),
			'reason' => Yii::t('app','Reason'),
			'file_name' => Yii::t('app','File Name'),
			'status' => Yii::t('app','Status'),
			'cancel_reason' => Yii::t('app','Reason'),
			'response'=>Yii::t('app','Response'),
			
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
		$criteria->compare('leave_type_id',$this->leave_type_id);
		$criteria->compare('requested_by',$this->requested_by);
		$criteria->compare('from_date',$this->from_date,true);
		$criteria->compare('to_date',$this->to_date,true);
		$criteria->compare('is_half_day',$this->is_half_day);
		$criteria->compare('reason',$this->reason,true);
		$criteria->compare('file_name',$this->file_name,true);
		$criteria->compare('file_data',$this->file_data,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('cancel_reason',$this->cancel_reason);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function checkleavedate($attribute,$params)
	{		
		if($this->from_date!='' and $this->to_date!=''){
			if($this->from_date >$this->to_date){				
				$this->addError($attribute,Yii::t("app",'From date must be less than To date'));				
			}
		}
	}
	
	public function checkfromdate($attribute,$params)
	{		
		if($this->from_date != ''){ 
			if($this->isNewRecord){
				if(Yii::app()->controller->id == 'androidApi'){
					$employee	= Employees::model()->findByAttributes(array('uid'=>$_REQUEST['uid']));
				}
				else{
					$employee	= Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
				}
			}
			else{
				$employee	= Employees::model()->findByAttributes(array('uid'=>$this->requested_by));
			}					
			$from_date		= date('Y-m-d', strtotime($this->from_date));	
			$joining_date	= $employee->joining_date;
			if($from_date < $joining_date){ 
				$this->addError($attribute,Yii::t("app",'From date must be greater than  joining date'));
			}
		}
	}	
	
	public function checkhalfday($attribute,$params){
		if($this->is_half_day != NULL and $this->from_date != NULL and $this->to_date != NULL){
			$from_date 	= date('Y-m-d', strtotime($this->from_date));
			$to_date 	= date('Y-m-d', strtotime($this->to_date));
			if($from_date != $to_date and $this->is_half_day != 0){
				$this->addError($attribute,Yii::t("app",'For half day, start date and end date should be same.'));
			}
		}
	}
	
	public function checkleavetaken($attribute,$params){
		$leaves		= 0;
		$take_leave	= 0;		
		if($this->isNewRecord){
			if(Yii::app()->controller->id == 'androidApi'){
				$employee	= Employees::model()->findByAttributes(array('uid'=>$_REQUEST['uid']));
			}
			else{
				$employee	= Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
			}
		}
		else{
			$employee	= Employees::model()->findByAttributes(array('uid'=>$this->requested_by));
		}		
		$flag = 0;
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
   		if($settings!=NULL)
    	{
		   $date=$settings->dateformat;		   
	 	}
   		 else
		{
			$date = 'dd-mm-yy';					 		
		}
	//Getting dates b/w start & end date	
		$date_between = array();
		$begin = new DateTime($_POST['LeaveRequests']['from_date']);
		$end = new DateTime($_POST['LeaveRequests']['to_date']);		
		$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
		foreach($daterange as $date){
			$date_between[] = $date->format("Y-m-d");
		}
		if(!in_array($_POST['LeaveRequests']['to_date'],$date_between))
		{
			$date_between[] = date('Y-m-d',strtotime($_POST['LeaveRequests']['to_date']));
		}
		if($begin == $end){ 
			$from	= date('Y-m-d',strtotime($_POST['LeaveRequests']['from_date']));
			$leavecheck_1 = LeaveRequests::model()->findByAttributes(array('requested_by'=>$employee->uid,'from_date'=>$from, 'is_half_day'=>$_POST['LeaveRequests']['is_half_day'], 'status'=>1));
			
			if($leavecheck_1){
						$take_leave=1;
					}
			
		}
		else{
				for($i = 0; $i<count($date_between); $i++){
					$leavecheck_1 = EmployeeAttendances::model()->findByAttributes(array('employee_id'=>$employee->id,'attendance_date'=>$date_between[$i]));
					
					if($leavecheck_1){
						$take_leave=1;
					}
					else{
						
						//Check whether leave already applied on the selected dates
						$criteria = new CDbCriteria;
						$criteria->condition = 'requested_by=:requested_by and status<>:status';
						$criteria->params = array(':requested_by'=>Yii::app()->user->id,':status'=>1);		
						$applied_leave_details = LeaveRequests::model()->findAll($criteria);
						
						$applied_leave_dates = array();
						foreach($applied_leave_details as $applied_leave_detail){
							$begin_date = new DateTime($applied_leave_detail->from_date);
							$end_date = new DateTime($applied_leave_detail->to_date);		
							$dateranges = new DatePeriod($begin_date, new DateInterval('P1D'), $end_date);
							foreach($dateranges as $date1){
								if(!in_array($date1->format("Y-m-d"),$applied_leave_dates)){
									$applied_leave_dates[] = $date1->format("Y-m-d");
								}
							}
							if(!in_array($applied_leave_detail->to_date,$applied_leave_dates)){
								$applied_leave_dates[] = $applied_leave_detail->to_date;
							}
						}
						$is_already_applied = 0;
						if($applied_leave_dates){
							for($j = 0; $j<count($date_between); $j++){
								if(in_array($date_between[$j],$applied_leave_dates)){
									$leaves=1;
								}
							}
						}
						
					}
				}
			}
		if($take_leave==1)
			$this->addError($attribute,Yii::t("app",'Leave already taken'));
		if($leaves==1)
					$this->addError($attribute,Yii::t("app",'Already applied leave date(s) are in the request'));
		//checking holidays
		$holy	=	0;
		$holidays = Holidays::model()->findAll();
		$days_arr = array();
		foreach($holidays as $holiday)
		{
			$days_arr[] = date('Y-m-d',$holiday->start);
		}
		$$holiday_dates	= array();
		for($i = 0;$i<count($date_between);$i++)
		{
			if(in_array($date_between[$i],$days_arr))
			{
				$holiday_dates[] = date($settings->displaydate, strtotime($date_between[$i]));
				$holy=1;
			}
		}
		
		if($holy==1)
			$this->addError($attribute,Yii::t("app",'Cannot apply leave for holidays').' ( '.implode(',', $holiday_dates).' )');
			
	}
	
	public function checkremaining($attribute,$params)
	{		
		if($this->leave_type_id!=''){
			if($this->isNewRecord){
				$employee	= Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
			}
			else{
				$employee	= Employees::model()->findByAttributes(array('uid'=>$this->requested_by));
			}
			
			$taken	=  EmployeeAttendances::model()->findAllByAttributes(array('employee_leave_type_id'=>$this->leave_type_id, 'employee_id'=>$employee->id));
		
			$days=0;
			if($taken){							  
				foreach($taken as $take){
					if($take->is_half_day == 0){
						$days		=	$days+1;
						$leave 		= 	LeaveTypes::model()->findByAttributes(array('id'=>$this->leave_type_id)); 
						$remaining 	=	($leave->count)-($days);
					}else{
						$days		=	$days+.5;
						$leave 		= 	LeaveTypes::model()->findByAttributes(array('id'=>$this->leave_type_id)); 
						$remaining 	=	($leave->count)-($days); 
					}
				}	
			}
			else{
				$leave 			= 	LeaveTypes::model()->findByAttributes(array('id'=>$this->leave_type_id)); 
				$remaining		=   $leave->count;
			}
						
			if($remaining<= 0){
				$this->addError($attribute,Yii::t("app",'No leaves Remaining for this type'));
			}			
		}
	}
}