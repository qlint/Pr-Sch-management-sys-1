<?php

/**
 * This is the model class for table "timetable_entries".
 *
 * The followings are the available columns in table 'timetable_entries':
 * @property integer $id
 * @property integer $batch_id
 * @property integer $weekday_id
 * @property integer $class_timing_id
 * @property integer $subject_id
 * @property integer $employee_id
 */
class TimetableEntries extends CActiveRecord
{
	public $classtime;
	/**
	 * Returns the static model of the specified AR class.
	 * @return TimetableEntries the static model class
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
		return 'timetable_entries';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		    array('batch_id, weekday_id, subject_id, employee_id', 'required'),			
			array('batch_id, weekday_id, class_timing_id, subject_id, employee_id', 'numerical', 'integerOnly'=>true),
			//array('employee_id','check_allocation'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('employee_id','check','on'=>'new'),
			array('subject_id','check_subject','on'=>'new'),
			array('id, batch_id, weekday_id, class_timing_id, subject_id, employee_id,split_subject', 'safe', 'on'=>'search'),			
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

	public function check($attribute,$params){
		if(!isset($_POST['classtime'])){
		$batch		= Batches::model()->findByPk($this->batch_id);		
		$flag=0;
		$criteria				= new CDbCriteria();				
		$criteria->condition	= '`t`.`batch_id`<>:batch_id AND `t`.`employee_id`=:employee_id AND `t`.`weekday_id`=:weekday_id';
		$criteria->params		= array(':batch_id'=>  $this->batch_id, ':employee_id'=>  $this->employee_id, ':weekday_id'=>  $this->weekday_id);
		
		//checking timetable entries related with batches with in the current batch date range
		#----------------------------------------------------------#
		$criteria->join			= "JOIN `batches` `b` ON `b`.`id`=`t`.`batch_id`";
		$criteria->condition	.= ' AND ((`b`.`start_date`<=:batch_start AND `b`.`end_date`>=:batch_start) OR (`b`.`start_date`<=:batch_end AND `b`.`end_date`>=:batch_end) OR (`b`.`start_date`>=:batch_start AND `b`.`start_date`<=:batch_end) OR (`b`.`end_date`>=:batch_start AND `b`.`end_date`<=:batch_end))';
		
		$criteria->params[':batch_start']	= $batch->start_date;
		$criteria->params[':batch_end']		= $batch->end_date;			
		#----------------------------------------------------------#
		
		$model= $this->model()->findAll($criteria);
		
	   	if($model){
			foreach ($model as $data){//var_dump($data->attributes);
				$class_time_id		= $data->class_timing_id;				
				$class_model		= ClassTimings::model()->findByPk($class_time_id);
				$start_time			= date('H:i', strtotime($class_model->start_time));
				$end_time			= date('H:i', strtotime($class_model->end_time));				
				$current_class		= $this->class_timing_id;
				$curr_class_model	= ClassTimings::model()->findByPk($current_class);
				$curr_start_time	= date('H:i', strtotime($curr_class_model->start_time));
				$curr_end_time		= date('H:i', strtotime($curr_class_model->end_time));
				
				if(	($curr_start_time >= $start_time and $curr_start_time < $end_time) or
					($curr_end_time > $start_time and $curr_end_time <= $end_time) or
					($start_time >= $curr_start_time and $start_time < $curr_end_time) or
					($end_time > $curr_start_time and $end_time <= $curr_end_time)
				){
					//$this->addError($attribute,Yii::t('app','Teacher assigned to another').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"));
					$this->addError($attribute,Yii::t('app','Teacher assigned to another').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' <div class="wrnig-prip">'.CHtml::checkBox('classtime','',array('class' => 'classtime')).CHtml::label('Check here if want to assign the teacher in this time slot', "classtime")."</div>");
					
					return;
				}
			}
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
			'weekday_id' => Yii::t("app",'Weekday'),
			'class_timing_id' => Yii::t("app",'Class Timing'),
			'subject_id' => Yii::t("app",'Subject'),
			'employee_id' => Yii::t("app",'Teacher'),
			'split_subject'=> Yii::t("app",'Split Subject'),
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
		$criteria->compare('weekday_id',$this->weekday_id);
		$criteria->compare('class_timing_id',$this->class_timing_id);
		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('employee_id',$this->employee_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function check_subject($attribute,$params)
	{
		$sub_id  = $this->subject_id;
		if($this->is_elective == 2){
			$elective = Electives::model()->findByPk($this->subject_id);
			$subject = Subjects::model()->findByAttributes(array('elective_group_id'=>$elective->elective_group_id));
                        

			$subs_id  = $this->subject_id; 
			$max_count = $subject->max_weekly_classes;
			
			$criteria	= new CDbCriteria;
			$criteria->condition	= '`subject_id`=:subject_id AND `batch_id`=:batch_id AND `is_elective`=:is_elective';
			$criteria->params		= array(':subject_id'=>$subs_id,':batch_id'=>$this->batch_id,':is_elective'=>2);
			
			if(!$this->isNewRecord){
				$criteria->condition		.= ' AND `id`<>:id';
				$criteria->params[':id']	= $this->id;
			}
			
			$classcount=TimetableEntries::model()->findAll($criteria);                         
			if(count($classcount)>=$max_count){
				$this->addError($attribute, Yii::t("app",'Maximum weekly classes of this subject is exceeded!'));
			}
		}
		else if($sub_id!=NULL){			
			$count=Subjects::model()->findByAttributes(array('id'=>$sub_id));
			$max_count=$count->max_weekly_classes;
			
			$criteria	= new CDbCriteria;
			$criteria->condition	= '`subject_id`=:subject_id AND `batch_id`=:batch_id AND `is_elective`=:is_elective';
			$criteria->params		= array(':subject_id'=>$sub_id,':batch_id'=>$this->batch_id,':is_elective'=>0);
			
			if(!$this->isNewRecord){				
				$criteria->condition		.= ' AND `id`<>:id';
				$criteria->params[':id']	= $this->id;
			}
			
			$classcount=TimetableEntries::model()->findAll($criteria);
			if(count($classcount)>=$max_count){
				$this->addError($attribute, Yii::t("app",'Maximum weekly classes of this subject is exceeded!'));
			}
		}		
	}
	
	/* public function check_allocation($attribute,$params)
	{
		$emp_id	  = $this->employee_id;
		if($emp_id!=NULL)
		{	
			$time_table_entries = TimetableEntries::model()->findAllByAttributes(array('employee_id'=>$emp_id)); //get all timetable entries for selected employee
			$current_batch  	= Batches::model()->findByAttributes(array('id'=>$this->batch_id)); //get details of selected batch
			$current_timing 	= ClassTimings::model()->findByAttributes(array('id'=>$this->class_timing_id)); //get class timing details of current slot
			
			foreach($time_table_entries as $time_table_entry)
			{
				$batch = Batches::model()->findByAttributes(array('id'=>$time_table_entry->batch_id));
					if($batch->start_date <= $current_batch->end_date and $current_batch->start_date <= $batch->end_date){
						$class_timing = ClassTimings::model()->findByAttributes(array('id'=>$time_table_entry->class_timing_id);
							if($class_timing->start_time <= $current_timing->end_time and $current_timing->start_time <= $class_timing->end_time){
								$this->addError('employee_id', 'This teacher is already assigned this hour!');
							}
					}
			}			
		}
	} */
	//Get subject name from timetable entries
	public function	getSubjectName($timetable_id)
	{
		$timetable_entry	= $this->model()->findByPk($timetable_id);
		$subject_name		= '';		
		if($timetable_entry->is_elective == 0){ //In case of normal subject
			$subject	= Subjects::model()->findByPk($timetable_entry->subject_id);
			if($subject){
				if($timetable_entry->split_subject!=0 and $timetable_entry->split_subject!=NULL){ 
					if($subject->split_subject){
						$subject_splits	= SubjectSplit::model()->findByPk($timetable_entry->split_subject);
						$subject_name	=	html_entity_decode(ucfirst($subject_splits->split_name))."<br> (".html_entity_decode(ucfirst($subject->name)).")";
					}
					else{
						$subject_name	=	html_entity_decode(ucfirst($subject->name));	
					} 
				}else{
					$subject_name		=	html_entity_decode(ucfirst($subject->name));	
				} 		
			}			
		}
		else{ //In case of elective subject			
			$elective	= Electives::model()->findByPk($timetable_entry->subject_id);
			if($elective){
				$elective_group	= ElectiveGroups::model()->findByPk($elective->elective_group_id);
				if($elective_group){
					$subject_name	= html_entity_decode(ucfirst($elective_group->name));
				}
			}			
		}
		
		return $subject_name;
	}
	public function	getEmployeeName($timetable_id)
	{
		$timetable_entry	= $this->model()->findByPk($timetable_id);
		$employee_name		= '';
		$employee			= Employees::model()->findByPk($timetable_entry->employee_id);				
		if($employee){
			$employee_name	= ucfirst($employee->fullname);
		}
		
		return $employee_name;
	}
	
}