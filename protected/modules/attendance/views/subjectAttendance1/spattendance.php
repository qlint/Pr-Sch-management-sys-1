<?php Yii::app()->clientScript->registerCoreScript('jquery');?>

<div id="parent_Sect">
<?php $this->renderPartial('application.modules.studentportal.views.default.leftside');?>
<div class="right_col"  id="req_res123">
<!--contentArea starts Here-->
<div id="parent_rightSect">
  <div class="parentright_innercon">
    <div class="pageheader">
      <div class="col-lg-8">
        <h2><i class="fa fa-dedent"></i><?php echo Yii::t('app','Student Attendance'); ?><span><?php echo Yii::t('app','View your Student Attendance here'); ?> </span></h2>
      </div>
      <div class="col-lg-2"> </div>
      <div class="breadcrumb-wrapper"> <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
          
          <li class="active"><?php echo Yii::t('app','Student Attendance'); ?></li>
        </ol>
      </div>
      <div class="clearfix"></div>
    </div>
    <div class="contentpanel">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo Yii::t('app','View Student Attendance'); ?></h3>
      </div>
      <div class="people-item">
        <?php $this->renderPartial('application.modules.teachersportal.views.default.employee_tab');?>
        <?php 
			  
			  	$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
		
				if(isset($_REQUEST['date']))
					$curr_date = date('Y-m-d',strtotime($_REQUEST['date']));
				else
					$curr_date = date('Y-m-d');
		
				$date = mktime(0, 0, 0,date('m',strtotime($curr_date)),date('d',strtotime($curr_date)),date('Y',strtotime($curr_date))); 
				$week = date('w', $date);
				 
				
				//students from current batch.......
				$criteria = new CDbCriteria;
				$criteria->condition = 'is_deleted=:is_deleted AND is_active=:is_active';
				$criteria->params[':is_deleted'] = 0;
				$criteria->params[':is_active'] = 1;
		
				$batch = Batches::model()->findByAttributes(array('id'=>$student->batch_id));
				$begin_date = date('Y-m-d',strtotime($batch->start_date)); 
				$end_date = date('Y-m-d',strtotime($batch->end_date));
				
				//holidays..........
				$holidays = Holidays::model()->findAll();
				$holiday_arr=array();
				foreach($holidays as $key=>$holiday)
				{
					if(date('Y-m-d',$holiday->start)!=date('Y-m-d',$holiday->end))
					{
						$date_range = StudentAttentance::model()->createDateRangeArray(date('Y-m-d',$holiday->start),date('Y-m-d',$holiday->end));
						foreach ($date_range as $value) {
							$holiday_arr[$value] = $holiday->id;
						}
					}
					else
					{
						$holiday_arr[date('Y-m-d',$holiday->start)] = $holiday->id;
					}
				}
				
				
				
				//Getting dates in a week
				$day = date('w');
				$week_start = date('Y-m-d', strtotime('-'.$day.' days'));
				$week_end = date('Y-m-d', strtotime('+'.(6-$day).' days'));
				$date_between = array();
				$begin = new DateTime($week_start);
				$end = new DateTime($week_end);
				
				$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
				
				foreach($daterange as $date){
					$date_between[] = $date->format("Y-m-d");
				}
				if(!in_array($week_end,$date_between))
				{
					$date_between[] = date('Y-m-d',strtotime($week_end));
				}   
				
				$weekdays=Weekdays::model()->findAll("batch_id=:x", array(':x'=>$batch->id));										
				if(count($weekdays)==0)
					$weekdays=Weekdays::model()->findAll("batch_id IS NULL");
				
				
				// find all weeks inside batch duration..............
				$weekArray = array();
				foreach($weekdays as $weekday)
				{
					$weekday->weekday = $weekday->weekday - 1;
					if($weekday->weekday <= 0)
						$weekday->weekday = 7;
					$weekArray[] = $weekday->weekday;
				}
				
				
				
				$days_name = array(0=>"SUN",1=>"MON",2=>"TUE",3=>"WED",4=>"THRU",5=>"FRI",6=>"SAT");
				
				$criteria=new CDbCriteria;
				$criteria->condition = "batch_id=:x";
				$criteria->params = array(':x'=>$batch->id);
				$criteria->order = "STR_TO_DATE(start_time, '%h:%i %p')";  
				$timing = ClassTimings::model()->findAll($criteria);
				$count_timing = count($timing);
				
				
				if($settings!=NULL)
					$date_format=$settings->displaydate;
				else
					$date_format = 'dd-mm-yy';	
				
				
				
				//find working days............
				$days = array();
				$batch_days = array();
				$batch_range = StudentAttentance::model()->createDateRangeArray($begin_date,$end_date);
				$batch_days = array_merge($batch_days,$batch_range);
				foreach($batch_days as $batch_day)
				{
					$week_number = date('N', strtotime($batch_day));
					if(in_array($week_number,$weekArray)) // If checking if it is a working day
						array_push($days,$batch_day);
					
				}
				
			?>
       
        <div class="atdn_div">
          <div class="name_div"> <br />
            <?php 
			   $course_name = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
				echo Yii::t('app','Course Name:').$course_name->course_name.'<br/>'; 	
				echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name:').$batch->name;?>
          </div>
          
          
          <div class="timetable_div">
            <?php
			if($timing!=NULL) // If class timing is set
			{
			?>
            
            <div align="center" class="atnd_tnav" style="top:10px;">
			<?php 
            if($curr_date > $begin_date)
            {   
                echo CHtml::link('<div class="atnd_arow_l"><img src="images/atnd_arrow-l.png" width="7" border="0"  height="13" /></div>',
                        array('/attendance/subjectAttendance/spAttendance','date'=>date('Y-m-d',strtotime($curr_date . "-1 days")),
                            'id'=>$batch->id)); 
            }
                echo date($date_format,strtotime($curr_date)); 
            if($curr_date < $end_date)
             {  
                echo CHtml::link('<div class="atnd_arow_r"><img src="images/atnd_arrow.png" width="7" border="0"  height="13" /></div>',
                array('/attendance/subjectAttendance/spAttendance','date'=>date('Y-m-d',strtotime($curr_date . "+1 days")),
                            'id'=>$batch->id)); 
             }
            
            ?>
            </div>
                <br />
            <div class="table-responsive" style=" overflow:scroll; overflow-y: hidden">
             <?php if(array_key_exists($curr_date, $holiday_arr)){ 
						$holiday_now = Holidays::model()->findByAttributes(array('id'=>$holiday_arr[$curr_date]));
				?>
                
                <span style="display:block; width:100%; height:40px; background:#D63535"  title="<?php echo $holiday_now->title; ?>"></span>
                <?php }elseif(in_array($curr_date,$days)){ ?>
              <table border="0" align="center" width="90%" id="table" cellspacing="0" class="table mb30">
                <tbody>
                  <tr>
                    <td class="td"><div class="name"><?php echo Yii::t('app','Student Name'); ?></div></td>
                    <?php
                        for($i=0;$i<$count_timing;$i++)
                        {
                            echo ' <td class="td">
                            <div  onclick="" style="position: relative;width:100px;">
                            <div class="tt-subject">
                            <div class="subject">';
                            $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$batch->id,'weekday_id'=>$weekdays[$week]['weekday'],'class_timing_id'=>$timing[$i]['id'])); 			
                            if(count($set)==0)
                            {
                                $is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
                                if($is_break==NULL)
                                    echo Yii::t('app','Not Assigned');
                                
                                else
                                    echo Yii::t('app','Break');
                            }
                            else
                            {
                                $time_sub = Subjects::model()->findByAttributes(array('id'=>$set->subject_id));
                                $time_emp = Employees::model()->findByAttributes(array('id'=>$set->employee_id));
                                if($time_sub!=NULL)
                                {
                                    echo $time_sub->name.'<br>';
                                }
                                if($time_emp!=NULL)
                                {
                                    $is_substitute = TeacherSubstitution::model()->findByAttributes(array('leave_requested_emp_id'=>$time_emp->id,'time_table_entry_id'=>$set->id));
                                    
                                    if($is_substitute and in_array($is_substitute->date_leave,$date_between))
                                    {
                                        $employee = Employees::model()->findByAttributes(array('id'=>$is_substitute->substitute_emp_id));
                                        echo '<div class="employee">'.Employees::model()->getTeachername($employee->id).'</div>';
                                    }
                                    else
                                    {
                                        echo '<div class="employee">'.Employees::model()->getTeachername($time_emp->id).'</div>';
                                    }
                                }
                                
                                
                            }
                            
                            echo '</div>
                            </div>
                            </div>
                            </td>';  
                        }
                        ?>
                    <!--timetable_td --> 
                  </tr>
                
                  <tr>
                    <td class="td"><div class="name"><?php echo $student->getStudentname(); ?></div></td>
                    <?php
						
						$today_day =	date('d');
						$today_month =	date('n');
						$today_year =	date('Y');
						$today_date = 	date('Y-m-d');
						$std_id		=	$student->id;
						
                        for($i=0;$i<$count_timing;$i++)
                        {
                            echo ' <td class="td">
                            <div  onclick="" style="position: relative;width:100px;">
                            <div class="tt-subject">
                            <div class="subject">';
                            $set =  TimetableEntries::model()->findByAttributes(array('batch_id'=>$batch->id,'weekday_id'=>$weekdays[$week]['weekday'],'class_timing_id'=>$timing[$i]['id'])); 
									
                            if(count($set)==0)
                            {
                                $is_break = ClassTimings::model()->findByAttributes(array('id'=>$timing[$i]['id'],'is_break'=>1));
                                if($is_break==NULL)
									echo Yii::t('app','Not Assigned');
								else
                                    echo Yii::t('app','Break');
                            }
                            else
                            {
								$timing_value = $timing[$i]['id']; 
								$sub_id = $set->subject_id;
								$subject = Subjects::model()->findByAttributes(array('id'=>$sub_id));
								$absent = StudentSubjectAttendance::model()->findByAttributes(array('date'=>$curr_date,'student_id'=>$std_id,'subject_id'=>$sub_id,'timing_id'=>$timing_value));
								if($absent){
									
									$label = '<span class="abs"></span>';
									echo $label;
									
								}
								else{
									if($curr_date < $today_date)	
										$label = '<i class="fa fa-check" style="color:#090"></i>';
									else
										$label = Yii::t('app','Mark');
									
									echo $label;
								}
							}
                           
                            echo '</div>
                            </div>
                            </div>
                            </td>';  
                        }
                        ?>
                    <!--timetable_td --> 
                  </tr>
                 
                </tbody>
              </table>
              <?php }else{ ?>
 <span style="display:block; width:100%; height:40px; background:#F2F2F2"  title=""></span>
               <?php } ?> 
            </div>
            <?php } // End if timing is set
							else // If no class timings set
							 {
								 ?>
            <div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;">
              <div class="y_bx_head"> <?php echo Yii::t('app','No timetable set for this').' '. Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?> </div>
            </div>
            <?php	 
							 }
                        ?>
          </div>
          <!-- End timetable div (timetable_div)--> 
        </div>
        <!-- End entire div (atdn_div) --> 
        
      </div>
    </div>
  </div>
  <div class="clear"></div>
</div>
