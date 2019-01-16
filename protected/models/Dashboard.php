<?php

/**
 * This is the model class for table "dashboard".
 *
 * The followings are the available columns in table 'dashboard':
 * @property integer $id
 * @property string $block
 * @property integer $is_visible
 * @property integer $portal
 * @property integer $default_order
 */
class Dashboard extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Dashboard the static model class
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
		return 'dashboard';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('block', 'required'),
			array('is_visible, portal, default_order', 'numerical', 'integerOnly'=>true),
			array('block', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, block, is_visible, portal, default_order', 'safe', 'on'=>'search'),
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
			'block' => 'Block',
			'is_visible' => 'Is Visible',
			'portal' => 'Portal',
			'default_order' => 'Default Order',
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
		$criteria->compare('block',$this->block,true);
		$criteria->compare('is_visible',$this->is_visible);
		$criteria->compare('portal',$this->portal);
		$criteria->compare('default_order',$this->default_order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        //get recent news
        public static function getNews()
        {
            $news = DashboardMessage::model()->findAll(array("condition"=>"recipient_id='".Yii::app()->getModule('mailbox')->newsUserId."'",'order'=>'message_id DESC'));
            return $news;
        }
        
        //get student admission counts
        public static function getStudents()
        {
            if(Yii::app()->user->year)
            {
                $year = Yii::app()->user->year;
            }
            else
            {
                $current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
                $year = $current_academic_yr->config_value;
            }
            $criteria = new CDbCriteria; 
            $criteria->compare('is_deleted',0);
            $criteria->condition = 'is_active=:is_active and is_deleted = :is_deleted';
            $criteria->params = array(':is_active'=>1,'is_deleted'=>0);
            $batch_stu = BatchStudents::model()->findAllByAttributes(array('result_status'=>0,'status'=>1,'academic_yr_id'=>$year));
            $students	=array();
            foreach($batch_stu as $stu)
            {
                $students[]	=	$stu->student_id;
            }
            $criteria->addInCondition('id',$students);
            //end
            $total = Students::model()->count($criteria);
            $criteria->order = 'id DESC';
            $criteria->limit = '10';
            $recent = Students::model()->findAll($criteria);
            $inactive =   Students::model()->findAll(array('condition'=>'is_active=:x AND is_deleted=:y','params'=>array(':x'=>'0',':y'=>'0'),'group'=>'id'));
            
            return array('total'=>$total,'recent'=>$recent,'inactive'=>$inactive);
        }

        //attendance deatils of students - for dashboard chart
        public static function getStudentAttendance()
        {
            $currdate = date('d-m-Y');
            $one =date("m"); 
            $one_1=date("M");
            $two =date("m d y", strtotime("-1 months", strtotime($currdate))); 
            $two_1 =date("M", strtotime("-1 months", strtotime($currdate))); 
            $three =date("m", strtotime("-2 months", strtotime($currdate))); 
            $three_1=date("M", strtotime("-2 months", strtotime($currdate))); 
            $four =date("m", strtotime("-3 months", strtotime($currdate))); 
            $four_1 =date("M", strtotime("-3 months", strtotime($currdate))); 
            $five =date("m", strtotime("-4 months", strtotime($currdate))); 
            $five_1 =date("M", strtotime("-4 months", strtotime($currdate))); 
            $six =date("m", strtotime("-5 months", strtotime($currdate))); 
            $six_1 =date("M", strtotime("-5 months", strtotime($currdate))); 
            $seven =date("m", strtotime("-6 months", strtotime($currdate))); 
            $seven_1 =date("M", strtotime("-6 months", strtotime($currdate))); 
            $eight =date("m", strtotime("-7 months", strtotime($currdate))); 
            $eight_1 =date("M", strtotime("-7 months", strtotime($currdate))); 
            $nine =date("m", strtotime("-8 months", strtotime($currdate))); 
            $nine_1 =date("M", strtotime("-8 months", strtotime($currdate))); 
            $ten =date("m", strtotime("-9 months", strtotime($currdate))); 
            $ten_1 =date("M", strtotime("-9 months", strtotime($currdate))); 
            $eleven =date("m", strtotime("-10 months", strtotime($currdate))); 
            $eleven_1 =date("M", strtotime("-10 months", strtotime($currdate))); 
            $twelve =date("m", strtotime("-11 months", strtotime($currdate))); 
            $twelve_1 =date("M", strtotime("-11 months", strtotime($currdate))); 

            $data_1 = Students::model()->findAll('month(admission_date)=:id AND is_deleted=:status',array(':id'=>$one,':status'=>'0'));	
            $data_2 = Students::model()->findAll('month(admission_date)=:id AND is_deleted=:status',array(':id'=>$two,':status'=>'0'));
            $data_3 = Students::model()->findAll('month(admission_date)=:id AND is_deleted=:status',array(':id'=>$three,':status'=>'0'));
            $data_4 = Students::model()->findAll('month(admission_date)=:id AND is_deleted=:status',array(':id'=>$four,':status'=>'0'));
            $data_5 = Students::model()->findAll('month(admission_date)=:id AND is_deleted=:status',array(':id'=>$five,':status'=>'0'));
            $data_6 = Students::model()->findAll('month(admission_date)=:id AND is_deleted=:status',array(':id'=>$six,':status'=>'0'));
            $data_7 = Students::model()->findAll('month(admission_date)=:id AND is_deleted=:status',array(':id'=>$seven,':status'=>'0'));
            $data_8 = Students::model()->findAll('month(admission_date)=:id AND is_deleted=:status',array(':id'=>$eight,':status'=>'0'));
            $data_9 = Students::model()->findAll('month(admission_date)=:id AND is_deleted=:status',array(':id'=>$nine,':status'=>'0'));
            $data_10 = Students::model()->findAll('month(admission_date)=:id AND is_deleted=:status',array(':id'=>$ten,':status'=>'0'));
            $data_11 = Students::model()->findAll('month(admission_date)=:id AND is_deleted=:status',array(':id'=>$eleven,':status'=>'0'));
            $data_12 = Students::model()->findAll('month(admission_date)=:id AND is_deleted=:status',array(':id'=>$twelve,':status'=>'0'));

            $month = '["'.$one_1.'","'.$two_1.'","'.$three_1.'","'.$four_1.'","'.$five_1.'","'.$six_1.'","'.$seven_1.'","'.$eight_1.'","'.$nine_1.'","'.$ten_1.'","'.$eleven_1.'","'.$twelve_1.'",]';
            $data = "[".count($data_1).",".count($data_2).",".count($data_3).",".count($data_4).",".count($data_5).",".count($data_6).",".count($data_7).",".count($data_8).",".count($data_9).",".count($data_10).",".count($data_11).",".count($data_12).",]";
            
            return array('month'=>$month,'data'=>$data);
        }
        
        //teacher details
        public static function getTeacherCount()
        {
            $criteria = new CDbCriteria;
            $criteria->compare('is_deleted',0);
            $total = Employees::model()->count($criteria);
            $criteria->order = 'id DESC';
            $criteria->limit = '10';
            $posts = Employees::model()->findAll($criteria);
            
            return array('total'=>$total,'recent'=>count($posts));
        }
        
        //examination deatils - for chart
        public static function getExamDetails()
        {
            $average	= $anual_avg = $avg = $last_avg = 0;                      
            //exam_group_ids
            $exam_group_ids	= array();
            //exam_ids
            $exam_ids           = array();
            $exam_criteria	= array();
            $all_students_marks	= array();
            $passed_students	= array();
            $max_total_marks	= 0;
            $students_total_marks	= 0;
            //exam groups
            $criteria		= new CDbCriteria;
            $criteria->condition	= 'YEAR(`exam_date`) = :this_year AND `result_published`=:result_published';
            $criteria->params	= array( ':this_year' => date('Y'),':result_published' =>1);						  						  
            $exam_groups            = ExamGroups::model()->findAll($criteria);
            if(count($exam_groups)>0)
            {
                foreach($exam_groups as $exam_group){
                    array_push($exam_group_ids, $exam_group->id);
                }	
                //exams
                $criteria		= new CDbCriteria;
                $criteria->addInCondition('`exam_group_id`', $exam_group_ids);
                $exams              = Exams::model()->findAll($criteria);
                if(count($exams)>0){
                        foreach($exams as $exam){	
                                array_push($exam_ids, $exam->id);	
                                $exam_criteria[$exam->id]	= array('min'	=> $exam->minimum_marks,'max'	=> $exam->maximum_marks);                                                      
                        }
                        //exam scores
                        $criteria		= new CDbCriteria;
                        $criteria->addInCondition('`exam_id`', $exam_ids);
                        $exam_scores	= ExamScores::model()->findAll($criteria);
                        if(count($exam_scores)>0){
                                foreach($exam_scores as $exam_score){
                                        $all_students_marks[$exam_score->student_id][$exam_score->exam_id]	= $exam_score->marks;
                                }

                                //fetching all student ids for checkig if student exists
                                $allstudents	= Students::model()->findAll();
                                $student_ids	= array();
                                foreach($allstudents as $student){
                                        array_push($student_ids, $student->id);
                                }

                                foreach($all_students_marks as $student_id=>$student_marks){				
                                        if(in_array($student_id, $student_ids)){
                                                $student_passed_the_exam	= true;
                                                foreach($student_marks as $exam_id=>$mark){
                                                        if($mark < $exam_criteria[$exam_id]['min'])		//checking mark with $exam_criteria min mark
                                                                $student_passed_the_exam	= false;

                                                        $max_total_marks		+= $exam_criteria[$exam_id]['max'];
                                                        $students_total_marks	+= ($mark > $exam_criteria[$exam_id]['max'])?$exam_criteria[$exam_id]['max']:$mark;
                                                }
                                                //check if student passed
                                                if($student_passed_the_exam)
                                                    array_push($passed_students, $student_id);	
                                        }
                                        else{
                                            unset($all_students_marks[$student_id]);
                                        }
                                }
                        }
                }
            }
            //annual exam pass
            $average	=	floor(( count($passed_students) / count($all_students_marks) ) * 100);
            //annual exam average marks
            $anual_avg	=	floor(( $students_total_marks / $max_total_marks ) * 100);
            
            
            return array('average'=>$average,'anual_avg'=>$anual_avg);
        }
        
        //events details
        public static function getEvents()
        {
            $events_sameday =   $events_sameweek    =   $events_nextweek    =   $events_nextmonth   =   array();
            $criteria = new CDbCriteria;
            $criteria->order = 'start DESC';
            if($rolename!= 'Admin')
            {
                $criteria->condition = 'placeholder = :default or placeholder=:placeholder';
                $criteria->params[':placeholder'] = $rolename;
                $criteria->params[':default'] = '0';
            }
            $events = Events::model()->findAll($criteria);
            if($events and $events!=NULL)
            {
                foreach($events as $event)
                {
                    $today              = strtotime("00:00:00");
                    $next_monday = strtotime('Next Monday', $today);
                    $second_next_monday = strtotime('+1 week',$next_monday);
                    $next_month = strtotime('+1 month',$today);
                    $next_month_start = strtotime('first day of this month',$next_month);
                    $next_month_end = strtotime('first day of next month',$next_month);
                    if(date("Y-m-d",$event->start) == date('Y-m-d') )
                    {
                    $events_sameday[] = $event ; 
                    }
                    elseif($event->start >= $today and $event->start < $next_monday)
                    {
                    $events_sameweek[] = $event ; 
                    }
                    elseif($event->start >= $next_monday and $event->start < $second_next_monday)
                    {
                    $events_nextweek[] = $event ; 	
                    }
                    elseif($event->start >= $next_month_start and $event->start < $next_month_end)
                    {
                    $events_nextmonth[] = $event ; 	
                    }
                }
            }            
            return array('events_sameday'=>$events_sameday,'events_sameweek'=>$events_sameweek,'events_nextweek'=>$events_nextweek,'events_nextmonth'=>$events_nextmonth);
        }
        
        //get fees details
        public static function getFees()
        {
            if(Yii::app()->user->year)
            {
                $year = Yii::app()->user->year;
            }
            else
            {
                $current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
                $year = $current_academic_yr->config_value;
            }
            $date                       =   date("Y-m-d");
            $fee_collected              =   0;
            $criteria                   =   new CDbCriteria;
            $criteria->condition        =   'is_deleted=0 AND status=1';
            $criteria->addCondition("DATE_FORMAT(date, '%Y-%m-%d') = '$date'");            
            $criteria->select           =   'SUM(amount) as amount';                        
            $fee_model                  =   FeeTransactions::model()->find($criteria);
            
            if($fee_model)
            {
                $fee_collected  =   $fee_model->amount;
            }
		
            $criteria			= new CDbCriteria;		
            $criteria->condition	= 'academic_year_id=:yr';
            $criteria->params[':yr'] 	= $year;	
            $criteria->order		= "`id` DESC";
            $total			= FeeCategories::model()->count($criteria);			            
            //invoices generated for
            $criteria			= new CDbCriteria;
            $criteria->condition	= 'academic_year_id=:yr';
            $criteria->params[':yr'] 	= $year;
            $criteria->compare("invoice_generated", 1);				
            $invoices     		= FeeCategories::model()->findAll($criteria);
            
            return array('fee_category'=>$total,'invoices'=>count($invoices),'fees'=>  number_format($fee_collected));
        }
        
        //get student attendance
        public static function getAttendance()
        {
            $date = strtotime(date("Y-m-d"));
            $day_status             =   "Active";   
            $batch_model            =   $employee_model =   array();
            $student_absent_count   =   $employee_absent_count  =   0;
            //check current day is holiday or not
            $criteria = new CDbCriteria;
            $criteria->select="*";
            $criteria->condition = "start<=:date AND end >=:date";
            $criteria->params = array(':date'=>$date);            
            $holiday_model          =   Holidays::model()->findAll($criteria);
            if($holiday_model!=NULL)
            {
                $day_status         =   "Holiday";                
            } 
            else
            {                                    
                $batch_model                =   array();
                $criteria 			=   new CDbCriteria;
                $criteria->join             =   'JOIN batches bt ON t.batch_id = bt.id JOIN weekdays wd ON wd.batch_id = bt.id  JOIN timetable_entries te ON te.batch_id = bt.id JOIN students st ON st.id = t.student_id';            
                $criteria->condition        =   ' `bt`.academic_yr_id=:x AND `bt`.is_deleted=0 AND `bt`.is_active=1 AND `bt`.start_date <=:c_date AND `bt`.end_date >=:c_date';                        
                $criteria->condition       .=   ' AND wd.weekday = :c_day AND te.weekday_id = :c_day';            //day
                $criteria->condition       .=   ' AND `st`.`is_deleted`=:is_deleted AND `st`.`is_active`=:is_active'; //student
                $criteria->condition       .=   ' AND `t`.`result_status`=:result_status AND `t`.`status`=:status';  //batch student            
                $criteria->params           =   array(':x'=>Yii::app()->user->year,':c_date'=>date('Y-m-d'),':c_day'=>(date('w')+1), ':is_deleted'=>0, ':is_active'=>1, ':result_status'=>0, ':status'=>1);
                $batch_model                =   BatchStudents::model()->findAll($criteria);                            
                if($batch_model!=NULL)
                {                
                    foreach ($batch_model as $student)
                    {
                        $exist  = StudentAttentance::model()->exists('batch_id=:batch_id AND student_id=:student_id AND date=:c_date', array(':batch_id'=>$student->batch_id,':student_id'=>$student->student_id,':c_date'=>date('Y-m-d')));
                        if($exist)
                        {
                            $student_absent_count++;
                        }
                    }
                }
                //teacher details
                $criteria               =   new CDbCriteria;
                $criteria->condition    =   'is_deleted=0 AND user_type=0';
                $employee_model         =   Employees::model()->findAll($criteria);
                if($employee_model!=NULL)
                {                
                    foreach ($employee_model as $employee)
                    {
                        $exist  = EmployeeAttendances::model()->exists('employee_id=:employee_id AND attendance_date=:c_date', array(':employee_id'=>$employee->id,':c_date'=>date('Y-m-d')));
                        if($exist)
                        {
                            $employee_absent_count++;
                        }
                    }
                }                
            }
                                                
            return array('total_student'=> count($batch_model),'student_absent'=>$student_absent_count,'total_employees'=>count($employee_model), 'employee_absent'=>$employee_absent_count ,'day_status'=>$day_status);
                   
        }
        
}