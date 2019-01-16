<?php

/**
 * This is the model class for table "attendances".
 *
 * The followings are the available columns in table 'attendances':
 * @property integer $id
 * @property integer $student_id
 * @property integer $period_table_entry_id
 * @property integer $forenoon
 * @property integer $afternoon
 * @property string $reason
 */
class Attendances extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Attendances the static model class
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
		return 'attendances';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student_id, period_table_entry_id, forenoon, afternoon', 'numerical', 'integerOnly'=>true),
			array('reason', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			//array('reason','CRegularExpressionValidator', 'pattern'=>'/^[a-zA-Z .-]+/','message'=>'{attribute} '.Yii::t('app','should contain only letters')),
			array('id, student_id, period_table_entry_id, forenoon, afternoon, reason', 'safe', 'on'=>'search'),
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
			'id' => Yii::t("app",'ID'),
			'student_id' => Yii::t("app",'Student'),
			'period_table_entry_id' => Yii::t("app",'Period Table Entry'),
			'forenoon' => Yii::t("app",'Forenoon'),
			'afternoon' => Yii::t("app",'Afternoon'),
			'reason' => Yii::t("app",'Reason'),
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
		$criteria->compare('student_id',$this->student_id);
		$criteria->compare('period_table_entry_id',$this->period_table_entry_id);
		$criteria->compare('forenoon',$this->forenoon);
		$criteria->compare('afternoon',$this->afternoon);
		$criteria->compare('reason',$this->reason,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function getStudentAttendance($student_id,$batch_id)
        {
            //settings for holiday added in attendance
            $holiday_setting= 0;
            $holiday_model= Configurations::model()->findByPk(43);
            {
                $holiday_setting= $holiday_model->config_value;
            }
            
            $student_details=Students::model()->findByAttributes(array('id'=>$student_id)); 
            $batch = Batches::model()->findByAttributes(array('id'=>$batch_id));
            if(strtotime(date('Y-m-d')) <= strtotime($batch->end_date))
            {
                $batch_end1  = date('Y-m-d');	
            }
            else
            {
                $batch_end1  = date('Y-m-d',strtotime($batch->end_date));	
            }
            // $batch_end1  = date('Y-m-d');	
            if($student_details->admission_date>=$batch->start_date)
            { 
            $batch_start  = date('Y-m-d',strtotime($student_details->admission_date));

            }
            else
            {
            $batch_start  = date('Y-m-d',strtotime($batch->start_date));
            }
            $batch_days_1  = array();
            $batch_range_1 = StudentAttentance::model()->createDateRangeArray($batch_start,$batch_end1);  // to find total session
            $batch_days_1  = array_merge($batch_days_1,$batch_range_1);

            $days = array();
            $days_1 = array();
            $weekArray = array();

            $total_working_days_1 = array();
            $weekdays = Weekdays::model()->findAll("batch_id=:x AND weekday<>:y", array(':x'=>$batch->id,':y'=>"0"));
            if(count($weekdays)==0)
            {

                    $weekdays = Weekdays::model()->findAll("batch_id IS NULL AND weekday<>:y",array(':y'=>"0"));
            }

            foreach($weekdays as $weekday)
            {

                    $weekday->weekday = $weekday->weekday - 1;
                    if($weekday->weekday <= 0)
                    {
                            $weekday->weekday = 7;
                    }
                    $weekArray[] = $weekday->weekday;
            }



            foreach($batch_days_1 as $batch_day_1)
            {
                    $week_number = date('N', strtotime($batch_day_1));
                    if(in_array($week_number,$weekArray)) // If checking if it is a working day
                    {
                            array_push($days_1,$batch_day_1);
                    }
            }
            
            $criteria= new CDbCriteria;
            $criteria->condition= 'start >=:batch_start AND end<= :batch_end';
            $criteria->params= array(':batch_start'=>  strtotime($batch_start),':batch_end'=>strtotime($batch_end1));
            $holidays = Holidays::model()->findAll($criteria);
            
            $holiday_arr=array();
            if($holidays!=NULL && $holiday_setting==1)
            {
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
                       
            

            foreach($days_1 as $day_1)
            {

                    if(!in_array($day_1,$holiday_arr)) // If checking if it is a working day
                    {
                            array_push($total_working_days_1,$day_1);
                    }
            }
            
            return count($total_working_days_1);
        }
        
        public function getLeaves($student_id)
        {
            $model= Students::model()->findByPk($student_id);
            $batch_id= $model->batch_id;
            if($model->batch_id == 0)
            {
                $last_batch = BatchStudents::model()->findByAttributes(array('student_id'=>$model->id,'result_status'=>2));
                $batch_id = $last_batch->batch_id; 
            }
            else
            {
                $batch_id = $model->batch_id;
            }
            
            
            $criteria = new CDbCriteria;		
            $criteria->join = 'LEFT JOIN student_leave_types t1 ON t.leave_type_id = t1.id'; 
            $criteria->condition = 't1.is_excluded=:is_excluded and t.student_id=:student_id AND batch_id=:batch_id AND date<=:date';
            $criteria->params = array(':is_excluded'=>0,':student_id'=>$student_id,':batch_id'=>$batch_id ,':date'=>date('Y-m-d'));
            $leaves    = StudentAttentance::model()->findAll($criteria);
            
            return count($leaves);
        }
		
		
		
	public function getterm1Attendance($student_id,$batch_id)
        {
            //settings for holiday added in attendance
            $holiday_setting= 0;
            $holiday_model= Configurations::model()->findByPk(43);
            {
                $holiday_setting= $holiday_model->config_value;
            }
			$batch= Batches::model()->findByPk($batch_id);
			$academic_yr= AcademicYears::model()->findByPk($batch->academic_yr_id);
            
            $student_details=Students::model()->findByAttributes(array('id'=>$student_id)); 
            $term = Terms::model()->findByAttributes(array('academic_yr_id'=>$academic_yr->id, 'term_id'=>1));
            if(strtotime(date('Y-m-d')) <= strtotime($term->end_date))
            {
                $term_end1  = date('Y-m-d');	
            }
            else
            {
                $term_end1  = date('Y-m-d',strtotime($term->end_date));	
            }
            // $batch_end1  = date('Y-m-d');	
            if($student_details->admission_date>=$term->start_date)
            { 
            $term_start  = date('Y-m-d',strtotime($student_details->admission_date));

            }
            else
            {
            $term_start  = date('Y-m-d',strtotime($term->start_date));
            }
            $batch_days_1  = array();
            $batch_range_1 = StudentAttentance::model()->createDateRangeArray($term_start,$term_end1);  // to find total session
            $batch_days_1  = array_merge($batch_days_1,$batch_range_1);

            $days = array();
            $days_1 = array();
            $weekArray = array();

            $total_working_days_1 = array();
            $weekdays = Weekdays::model()->findAll("batch_id=:x AND weekday<>:y", array(':x'=>$batch->id,':y'=>"0"));
            if(count($weekdays)==0)
            {

                    $weekdays = Weekdays::model()->findAll("batch_id IS NULL AND weekday<>:y",array(':y'=>"0"));
            }

            foreach($weekdays as $weekday)
            {

                    $weekday->weekday = $weekday->weekday - 1;
                    if($weekday->weekday <= 0)
                    {
                            $weekday->weekday = 7;
                    }
                    $weekArray[] = $weekday->weekday;
            }



            foreach($batch_days_1 as $batch_day_1)
            {
                    $week_number = date('N', strtotime($batch_day_1));
                    if(in_array($week_number,$weekArray)) // If checking if it is a working day
                    {
                            array_push($days_1,$batch_day_1);
                    }
            }
            
            $criteria= new CDbCriteria;
            $criteria->condition= 'start >=:term_start AND end<= :term_end';
            $criteria->params= array(':term_start'=>  strtotime($term_start),':term_end'=>strtotime($term_end1));
            $holidays = Holidays::model()->findAll($criteria);
            
            $holiday_arr=array();
            if($holidays!=NULL && $holiday_setting==1)
            {
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
                       
            

            foreach($days_1 as $day_1)
            {

                    if(!in_array($day_1,$holiday_arr)) // If checking if it is a working day
                    {
                            array_push($total_working_days_1,$day_1);
                    }
            }
            
            return count($total_working_days_1);
        }	
        
        public function getterm2Attendance($student_id,$batch_id)
        {
            //settings for holiday added in attendance
            $holiday_setting= 0;
            $holiday_model= Configurations::model()->findByPk(43);
            {
                $holiday_setting= $holiday_model->config_value;
            }
			$batch= Batches::model()->findByPk($batch_id);
			$academic_yr= AcademicYears::model()->findByPk($batch->academic_yr_id);
            
            $student_details=Students::model()->findByAttributes(array('id'=>$student_id)); 
            $term = Terms::model()->findByAttributes(array('academic_yr_id'=>$academic_yr->id, 'term_id'=>2));
            if(strtotime(date('Y-m-d')) <= strtotime($term->end_date))
            {
                $term_end1  = date('Y-m-d');	
            }
            else
            {
                $term_end1  = date('Y-m-d',strtotime($term->end_date));	
            }
            // $batch_end1  = date('Y-m-d');	
            if($student_details->admission_date>=$term->start_date)
            { 
            $term_start  = date('Y-m-d',strtotime($student_details->admission_date));

            }
            else
            {
            $term_start  = date('Y-m-d',strtotime($term->start_date));
            }
            $batch_days_1  = array();
            $batch_range_1 = StudentAttentance::model()->createDateRangeArray($term_start,$term_end1);  // to find total session
            $batch_days_1  = array_merge($batch_days_1,$batch_range_1);

            $days = array();
            $days_1 = array();
            $weekArray = array();

            $total_working_days_1 = array();
            $weekdays = Weekdays::model()->findAll("batch_id=:x AND weekday<>:y", array(':x'=>$batch->id,':y'=>"0"));
            if(count($weekdays)==0)
            {

                    $weekdays = Weekdays::model()->findAll("batch_id IS NULL AND weekday<>:y",array(':y'=>"0"));
            }

            foreach($weekdays as $weekday)
            {

                    $weekday->weekday = $weekday->weekday - 1;
                    if($weekday->weekday <= 0)
                    {
                            $weekday->weekday = 7;
                    }
                    $weekArray[] = $weekday->weekday;
            }



            foreach($batch_days_1 as $batch_day_1)
            {
                    $week_number = date('N', strtotime($batch_day_1));
                    if(in_array($week_number,$weekArray)) // If checking if it is a working day
                    {
                            array_push($days_1,$batch_day_1);
                    }
            }
            
            $criteria= new CDbCriteria;
            $criteria->condition= 'start >=:term_start AND end<= :term_end';
            $criteria->params= array(':term_start'=>  strtotime($term_start),':term_end'=>strtotime($term_end1));
            $holidays = Holidays::model()->findAll($criteria);
            
            $holiday_arr=array();
            if($holidays!=NULL && $holiday_setting==1)
            {
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
                       
            

            foreach($days_1 as $day_1)
            {

                    if(!in_array($day_1,$holiday_arr)) // If checking if it is a working day
                    {
                            array_push($total_working_days_1,$day_1);
                    }
            }
            
            return count($total_working_days_1);
        }	
        
}