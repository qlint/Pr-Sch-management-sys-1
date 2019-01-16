<style>
.scrollbox1 {
   overflow: auto;
    width: auto !important;
    height: 440px;
    padding: 0 5px;
}
.scrollbox2 {
   overflow: auto;
    width: auto !important;
    height:376px;
    padding: 0 5px;
}
.scrollbox3 {
   overflow: auto;
    width: auto !important;
    height: 155px;
    padding: 0 5px;
}
.scrollbox4 {
   overflow: auto;
    width: auto !important;
    height:339px;
    padding: 0 5px;
}
.scrollbox5 {
   overflow: auto;
    width: auto !important;
    height:359px;
    padding: 0 5px;
}

button, input[type="submit"]{ border: 0px solid #cbcbcb !important;
    border-radius: 0px !important;
	padding: 12px 6px !important;}
	
.ui-dialog .ui-dialog-titlebar{ padding:0px;
	position:inherit;}
	
.ui-widget-content{ box-shadow:0px;
	padding:0px !important;}
	
.e_pop_bttm{ min-height:40px !important; }

.e_pop_top{ min-height:122px !important}

.ui-widget-header{ border:0px !important;
	background:none !important;}
	
</style>

<script language="javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/enscroll-0.6.1.min.js"></script>
<?php $settings=UserSettings::model()->findByAttributes(array('user_id'=>1)); ?>
 <?php $this->renderPartial('leftside');?> 
 
 <div class="pageheader">
      <h2><i class="fa fa-home"></i><?php echo Yii::t('app','Dashboard');?><span><?php echo Yii::t('app','View your dashboard here');?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:');?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
          <li class="active"><?php echo Yii::t('app','Dashboard');?></li>
        </ol>
      </div>
    </div>
     <div class="contentpanel">
    	<div class="col-lg-12">
            <div class="row">
            <div class="col-md-6">
              <div class="panel-heading">
              <h4 class="panel-title dashbord_icon"><i class="fa fa-bullhorn"></i><?php echo Yii::t('app','News');?></h4>
                         </div>
            <div class="people-item" style="height:495px; overflow:hidden;">
            <div class="table-responsive">
<div class="main_box">
     		<div class="scrollbox1">
            
    <?php 
	//$newss = DashboardMessage::model()->findAllByAttributes(array('recipient_id'=>Yii::app()->getModule('mailbox')->newsUserId));
        $newss = DashboardMessage::model()->findAll(array("condition"=>"recipient_id='".Yii::app()->getModule('mailbox')->newsUserId."'",'order'=>'message_id DESC'));
	if($newss and $newss!=NULL)
	{ 
		 foreach($newss as $news)
		 { ?>
           <div class="main_box1">         
           <div id="home3" class="tab-pane active tab-pane_dashboard">
            <h4 class="dark"><?php echo html_entity_decode(ucfirst(@Mailbox::model()->findByAttributes(array('conversation_id'=>$news->conversation_id))->subject)) ;?></h4>
            <p><?php echo html_entity_decode(ucfirst($news->text)); ?></p>
          </div>
		</div>			
					
	<?php }
	}
	else
	{?>
    
    		<div id="home3" class="tab-pane active">
            <h4 class="dark"><?php echo Yii::t('app','No News');?></h4>
            <p>. . . .</p>
          </div>
               
    <?php } ?>
      </div>
    </div>
  </div>
          
          
                <!-- panel -->
                
            </div>
            </div>
            
            <div class="col-md-6">
                        <div class="panel-heading">
              <h4 class="panel-title dashbord_icon"><i class="fa fa-calendar"></i><?php echo Yii::t('app','Events');?></h4>
            </div>
            <div class="people-item" style="overflow:hidden;">
            <div class="table-responsive">
            <ul class="nav nav-tabs nav-dark">
          <li class="active"><a data-toggle="tab" href="#home2"><strong><?php echo Yii::t('app','Today');?></strong></a></li>
          <li><a data-toggle="tab" href="#profile2"><strong><?php echo Yii::t('app','Current Week');?></strong></a></li>
          <li><a data-toggle="tab" href="#about2"><strong><?php echo Yii::t('app','Next Week');?></strong></a></li>
          <li><a data-toggle="tab" href="#contact2"><strong><?php echo Yii::t('app','Next month');?></strong></a></li>
        </ul>
        
        <?php 
		$roles = Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role
        foreach($roles as $role)
        {
            $rolename = $role->name;
        }
        
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
		
		
		?>
        
                <div class="tab-content mb30 scrollbox2" >
          <div id="home2" class="tab-pane active">
         <div class="widget-messaging">
          <ul>
          <?php 
		if($events_sameday and $events_sameday!=NULL)
		{
		foreach($events_sameday as $event_sameday)
		{
			if($settings!=NULL)
			{	
				$time=Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
				date_default_timezone_set($time->timezone);
				$date_1 = date($settings->displaydate,$event_sameday->start);
				$time=date($settings->timeformat,$event_sameday->start);
				
			}
			
			echo '<li>';
			echo '<small class="pull-right">'.$date_1.'&nbsp;&nbsp;  '.$time.'</small>';
			echo CHtml::ajaxLink('<h4 class="sender">'.substr($event_sameday->title,0,25).'</h4>'
				,$this->createUrl('default/view',array('event_id'=>$event_sameday->id)),array('update'=>'#jobDialog'),array('id'=>'showJobDialog1'.$event_sameday->id,'class'=>'add'));
				echo '<small>'.substr($event_sameday->desc,0,50).'</small>';
				
				echo '</li>';
		}
		}
		else
		{
			echo '<p style="padding:40px; text-align:center;">'.Yii::t('app','No Events Today').'</p>';
		}
		?>
          </ul>
         </div> 
          
          
          </div>
          <div id="profile2" class="tab-pane">
          	 <div class="widget-messaging">
          <ul>
            <?php 
				if($events_sameweek and $events_sameweek!=NULL)
				{
				foreach($events_sameweek as $event_sameweek)
				{
					
					if($settings!=NULL)
					{	
						$time=Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
						date_default_timezone_set($time->timezone);
						
						$date_1 = date($settings->displaydate,$event_sameweek->start);
						$time=date($settings->timeformat,$event_sameweek->start);
					}		
					echo '<li>';
					echo '<small class="pull-right">'.$date_1.'&nbsp;&nbsp;'.$time.'</small>';
					echo CHtml::ajaxLink('<h4 class="sender">'.substr($event_sameweek->title,0,25).'</h4>'
						,$this->createUrl('default/view',array('event_id'=>$event_sameweek->id)),array('update'=>'#jobDialog'),array('id'=>'showJobDialog1'.$event_sameweek->id,'class'=>'add'));
						echo '<small>'.substr($event_sameweek->desc,0,50).'</small>';
						
						echo '</li>';
				
				
				
				
				
				}
				}
				else
				{
					echo '<p style="padding:40px; text-align:center;">'.Yii::t('app','No Upcoming Events This week').'</p>';
				}
				?>
                </ul></div>
          </div>
          <div id="about2" class="tab-pane">
           <div class="widget-messaging">
          <ul>
            <?php 
				if($events_nextweek and $events_nextweek!=NULL)
				{
				foreach($events_nextweek as $event_nextweek)
				{ 
					if($settings!=NULL)
					{	
						$time=Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
						date_default_timezone_set($time->timezone);
						
						$date_1 = date($settings->displaydate,$event_nextweek->start);
						$time=date($settings->timeformat,$event_nextweek->start);	
					}	
					echo '<li>';
					echo '<small class="pull-right">'.$date_1.'&nbsp;&nbsp;'.$time.'</small>';
					echo CHtml::ajaxLink('<h4 class="sender">'.substr($event_nextweek->title,0,25).'</h4>'
					,$this->createUrl('default/view',array('event_id'=>$event_nextweek->id)),array('update'=>'#jobDialog'),array('id'=>'showJobDialog1'.$event_nextweek->id,'class'=>'add'));
					echo '<small>'.substr($event_nextweek->desc,0,50).'</small>';
				
				echo '</li>';
				}
				}
				else
				{
					echo '<p style="padding:40px; text-align:center;">'.Yii::t('app','No Upcoming Events Next Week').'</p>';
				}
				?>
                </ul></div>
          </div>
          <div id="contact2" class="tab-pane">
           <div class="widget-messaging">
          <ul>
            <?php 
				if($events_nextmonth and $events_nextmonth!=NULL)
				{
				foreach($events_nextmonth as $event_nextmonth)
				{
					if($settings!=NULL)
					{	
						$time=Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
						date_default_timezone_set($time->timezone);
					
						$date_1 = date($settings->displaydate,$event_nextmonth->start);
						$time=date($settings->timeformat,$event_nextmonth->start);
					}	
					echo '<li>';
					echo '<small class="pull-right">'.$date_1.'&nbsp;&nbsp;'.$time.'</small>';
					echo CHtml::ajaxLink('<h4 class="sender">'.substr($event_nextmonth->title,0,25).'</h4>'
					,$this->createUrl('default/view',array('event_id'=>$event_nextmonth->id)),array('update'=>'#jobDialog'),array('id'=>'showJobDialog1'.$event_nextmonth->id,'class'=>'add'));
					echo '<small>'.substr($event_nextmonth->desc,0,50).'</small>';
					
				}
				}
				else
				{
					echo '<p style="padding:40px; text-align:center;">'.Yii::t('app','No Upcoming Events Next Month').'</p>';
				}
				?>
                </ul></div>
          </div>
        </div>
          
          </div>
                <!-- panel -->
                
            </div>
            <div id="jobDialog"></div>
            </div>
             </div>
       <?php    
            $student 			= Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));            
			$student_id 		= $student->id;
			$batchstudents  	= BatchStudents::model()->studentBatch($student->id);
			$student_batch_id	= '';			
			if($batchstudents != NULL){
				$student_batch_id = isset($_REQUEST['bid'])?$_REQUEST['bid']:$batchstudents[0]['id'];
			}
			$batch_arr	= array();
			foreach($batchstudents as $value){
				$course    				= 	Courses::model()->findByAttributes(array('id'=>$value->course_id));
				$batch_arr[$value->id]	= 	ucfirst($value->name).' ( '.ucfirst($course->course_name).' )';
			}
       ?>
             <div class="row">
       <?php if(Configurations::model()->studentAttendanceMode() != 2){?>     
            <div class="col-md-4">
                        <div class="panel-heading">
              <h4 class="panel-title dashbord_icon"><i class="fa fa-file-text"></i><?php echo Yii::t('app','Attendance');?></h4>
            </div>
            <div class="people-item" style="height:295px; overflow:hidden;">
            <div class="table-responsive">

                <?php                      
                if(count($batchstudents)>1){
                    echo CHtml::dropDownList('batch_id', $student_batch_id, $batch_arr ,array('encode'=>false,'id'=>'batch_drop','class'=>'form-control'));
                }
                ?>
           <div class="row" style="padding:10px 0px; text-align:center;"><?php echo Yii::t('app','Last 7 days of');?> &nbsp;  <strong><?php echo ' '.date('Y F'); ?></strong></div>
           <div class="table-responsive table_respnsive_srl">
      
  <?php                                    
  		
  		if($student_batch_id and ($student_batch_id !=NULL or $student_batch_id!=0))
                { 
			
		$criteria = new CDbCriteria;
		$criteria->condition = "batch_id=:x and weekday!=:y";
		$criteria->params = array(':x'=>$student_batch_id,':y'=>0);
		$batch_end  = Batches::model()->findByPk($student_batch_id);
		$weekdays   = Weekdays::model()->findAll($criteria);  
		
		$annual_holidays = Holidays::model()->findAll();
		$holidays = array();
		foreach($annual_holidays as $annual_holiday){
			$holidays[] = date('Y-m-d',$annual_holiday->start);
		}
		
		$check_weekday = array();
		foreach($weekdays as $weekday){
			$check_weekday[] = $weekday->weekday;
		}
		
		/*if(count($weekdays)!=7)
		{
			$weekdays = Weekdays::model()->findAll("batch_id IS NULL");
		}*/
		?> 
        
        	<table class="table table-bordered mb30" width="100%" cellpadding="0" cellspacing="0">
            <thead>
              		<tr>	
                <?php 
				for ($i = 6; $i >= 0; $i--)
				{ ?>
                 <td><?php 
				$weekday_number = date('N', strtotime("-$i days")) + 1; 
				if($weekday_number==8)
				{
					$weekday_number = 1 ;
				}

					$date = date('d', strtotime("-$i days"));
					echo date('D', strtotime("-$i days")).'<br/>';echo $date;
				?>
                 </th>
                 <?php } ?>
                 </tr></thead>
                 <tbody>
              <tr>
                <?php 
				for ($i = 6; $i >= 0; $i--)
				{ 
				$weekday_number = date('N', strtotime("-$i days")) + 1; 
				if($weekday_number==8)
				{
					$weekday_number = 1 ;
				}
				$date = date('Y-m-d', strtotime("-$i days"));
				if(in_array($weekday_number,$check_weekday) and !in_array($date,$holidays)){
					/*if($weekdays[$weekday_number]['weekday']==0)
					{
						 echo '<li></li>';
					}
					else
					{*/
					$att_date	=	date('Y-m-d', strtotime("-$i days"));
					
					if($student->admission_date <= $att_date and $batch_end->end_date >= $att_date )
					{
						$attendance = StudentAttentance::model()->findByAttributes(array('date'=>date('Y-m-d', strtotime("-$i days")),'student_id'=>$student->id,  'batch_id'=>$_REQUEST['bid'])); 
						if($attendance and $attendance!=NULL)
						{
							 $title= Yii::t('app',"Reason")." : ". ucfirst($attendance->reason);?>
							  <td><span  class="bg_white_cross tooltips" data-original-title="<?php echo $title; ?>"  ></span></td> <?php
						}
						else
						{
							echo '<td><span  class="bg_white_tick"></span></td>';
						}
					}
					else
						{
							echo '<td><span class="weekend-mark" title="">W</span></td>';
						}
					//}
				}else{
					if(in_array($date,$holidays)){
						echo '<td><span class="holiday-mark" title="Holiday">H</span></td>';
					}else{
						echo '<td><span class="weekend-mark" title="">W</span></td>';
					}
				}
					
					?>
                <?php }?>
                	
                 </tr>
              
            </tbody>  
                
            </table>
            

            <?php 
			
			}
			else if(count($batchstudents)==0)
			{
			  echo Yii::t('app','You are not Enrolled in any').' '.Yii::app()->getModule("students")->labelCourseBatch();
			}
			?>
         </div>
                <!-- panel -->
            </div>
            </div>
            </div>
            
            
       <?php } ?>
			
			<div class="col-md-4">
            <div class="panel-heading">
              <h4 class="panel-title dashbord_icon"><i class="fa fa-list-alt"></i><?php echo Yii::t('app','Examination');?></h4>
            </div>
            <div class="people-item" style="height:295px; overflow:hidden;">
            <div class="table-responsive">
            <?php                    
                if(count($batchstudents)>1){
                    echo CHtml::dropDownList('batch_id', $student_batch_id, $batch_arr ,array('encode'=>false,'id'=>'batch_drop_exam','class'=>'form-control'));
                }
                ?>
      
  <?php if($student_batch_id and ($student_batch_id !=NULL or $student_batch_id!=0))
        { 
            if(ExamFormat::model()->getExamformat($student_batch_id)== 2) // cbsc format
            { 
				$criteria= new CDbCriteria;
				$criteria->join= 'JOIN cbsc_exams_17 `t1` ON t1.id=`t`.exam_id JOIN cbsc_exam_group_17 `t2` ON t2.id=`t1`.exam_group_id';
				$criteria->condition= '`t2`.batch_id=:batch_id AND t.student_id=:student_id';
				$criteria->params= array(':batch_id'=>$_REQUEST['bid'], ':student_id'=>$student->id);
				$examscores = CbscExamScores17::model()->findAll($criteria);
				if($examscores != NULL){
						?>
						<div class="table-responsive scrollbox3" style="height:200px">
							<table class="table table-invoice">
								<tr>
									<th width="30%"  style="text-align:left" height="35"><?php echo Yii::t('app','Exam');?></th>
									<th  width="30%"  style="text-align:left" height="35"><?php echo Yii::t('app','Subject');?></th>
									<th  width="30%" style="text-align:left"><?php echo Yii::t('app','Mark');?></th>
									<th  width="30%" style="text-align:left"><?php echo Yii::t('app','Grade');?></th>
								</tr>
								<?php foreach($examscores as $examscore)
									  {
										$exam=CbscExams17::model()->findByAttributes(array('id'=>$examscore->exam_id));
										$group=CbscExamGroup17::model()->findByAttributes(array('id'=>$exam->exam_group_id));
										$subject= Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
										$scores = CbscExamScores17::model()->findByAttributes(array('exam_id'=>$exam->id,'student_id'=>$student->id));?>
                           
										<tr>
											<td width="30%" style="text-align:left"><?php echo $group->name ; ?></td>
											<td width="30%" style="text-align:left"><?php echo $subject->name; ?></td>
											<td width="30%" style="text-align:left"><?php echo $scores->total; ?></td>
											<td width="30%" style="text-align:left"><?php if($group->class == 1){
																									   echo CbscExamScores17::model()->getClass1Grade($scores->total);
																									  
																								  }
																								  else{
																									   echo CbscExamScores17::model()->getClass2Grade($scores->total);
																									   
																								  }
																						 ?></td>
										</tr>
								<?php } ?>
							</table>
						</div>
				<?php
				}
				else
				{
					echo Yii::t('app','Examinations are not available for your').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");
				}
            }
            else // default format
            {
			$criteria= new CDbCriteria;
			$criteria->join= 'JOIN exams `t1` ON t1.id=`t`.exam_id JOIN exam_groups `t2` ON t2.id=`t1`.exam_group_id';
			$criteria->condition= '`t2`.batch_id=:batch_id AND t.student_id=:student_id';
			$criteria->params= array(':batch_id'=>$student_batch_id, ':student_id'=>$student->id);
			$examscores = ExamScores::model()->findAll($criteria);
			
			if($examscores != NULL){
            ?>
          
            <div class="table-responsive scrollbox3" style="height:200px">
			  <table class="table table-invoice">
			  <tr>
				<th width="30%"  style="text-align:left" height="35"><?php echo Yii::t('app','Exam');?></th>
				<th  width="30%"  style="text-align:left" height="35"><?php echo Yii::t('app','Subject');?></th>
				<th  width="30%" style="text-align:left"><?php echo Yii::t('app','Mark');?></th>
			  </tr>
          
          <?php foreach($examscores as $examscore)
                                {
                                    $exam=Exams::model()->findByAttributes(array('id'=>$examscore->exam_id));
                                    $group=ExamGroups::model()->findByAttributes(array('id'=>$exam->exam_group_id));
									
									
									 $criteria = new CDbCriteria;
									 $criteria->condition = 'batch_id=:x';
									 $criteria->params = array(':x'=>$group->batch_id);	
									 $criteria->order = 'min_score DESC';
									 $grades = GradingLevels::model()->findAll($criteria);
									
			                        $t = count($grades); 
                                    $sub=Subjects::model()->findByAttributes(array('id'=>$exam->subject_id));
                                    if($sub->elective_group_id!=0 and $sub->elective_group_id!=NULL)
                                            {
                                                
                                                $student_elective = StudentElectives::model()->findByAttributes(array('student_id'=>$student->id));
                                                if($student_elective!=NULL)
                                                {
                                                    $electname = Electives::model()->findByAttributes(array('id'=>$student_elective->elective_id,'elective_group_id'=>$sub->elective_group_id));
                                                    if($electname!=NULL)
                                                    {
                                                        $subjectname = $electname->name;
                                                    }
                                                }
                                            
                                                
                                            }
                                            else
                                            {
                                                $subjectname = $sub->name;
                                            } ?>
                                            <?php if($group->result_published==1)
                                            { ?>
          <tr>
            <td width="30%" style="text-align:left"><?php echo $group->name ; ?></td>
            <td width="30%" style="text-align:left"><?php echo $subjectname ; ?></td>
            <!--<td width="30%" style="text-align:left"><?php //echo $exam->marks ; ?></td>-->
            <td width="30%" style="text-align:left"><?php 
			if($group->exam_type == 'Marks')
			{
				echo $examscore->marks ;
			}
			 else if($group->exam_type == 'Grades') {
				 foreach($grades as $grade)
						{
							
						 if($grade->min_score <= $examscore->marks)
							{	
								$grade_value =  $grade->name;
							}
							else
							{
								$t--;
								
								continue;
								
							}
						echo $grade_value ;
						break;
						
						}
						if($t<=0) 
							{
								$glevel = " No Grades" ;
							}
				 }
				 
				 else if($group->exam_type == 'Marks And Grades'){
					 foreach($grades as $grade)
						{
							
						 if($grade->min_score <= $examscore->marks)
							{	
								$grade_value =  $grade->name;
							}
							else
							{
								$t--;
								
								continue;
								
							}
						echo $examscore->marks . " & ".$grade_value ;
						break;
						
							
						} 
						if($t<=0) 
							{
								echo $examscore->marks." & ".Yii::t('app','No Grades');
							}
						 } 
									
			?>
            </td>
            
          </tr>
          <?php }
		 
		  } ?>
         
          
        </table>
          </div>
   <?php 
			}
			else{
				echo Yii::t('app','Examinations are not available for your').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");
			}
   		} 
   	}
   ?>
                
                <?php 
                if(count($batchstudents)==0)
                {
                  echo Yii::t('app','You are not Enrolled in any').' '.Yii::app()->getModule("students")->labelCourseBatch();
                }
                ?>
          </div>
         
          
                <!-- panel -->
                
            </div>
            </div>
			 <?php 
		  	$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
			if($settings!=NULL)
			{	
				$current_date = date($settings->displaydate,strtotime(date('Y-m-d')));
											
			}
			else
				$current_date =  date('Y-m-d');
			 ?>
			
            <div class="col-md-4">
            <div class="panel-heading">
              <h4 class="panel-title dashbord_icon"><i class="fa fa-columns"></i><?php echo Yii::t('app','Time Table'.' - '.$current_date);?></h4>
            
            </div>
            <div class="people-item" style="height:274px; overflow:hidden;">
            <div class="table-responsive">
            <?php                           
                if(count($batchstudents)>1){
                    echo CHtml::dropDownList('batch_id', $student_batch_id, $batch_arr ,array('encode'=>false,'id'=>'batch_drop_timetable','class'=>'form-control'));
                }
                ?>
          
            <div class="table-responsive scrollbox4" style="height:200px">
       
            
            <?php 
            		$student = Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id)) ;
					if($student and $student!=NULL)
					{
					?>
				<div class="dash_box4 for_student_box4">
					
					
				
			<?php if($student_batch_id and ($student_batch_id !=NULL or $student_batch_id!=0))
			{ 
			
			$check_entry = TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$student_batch_id));
			
			if($check_entry and $check_entry!=NULL)
			{
				
			$TimetableEntries = TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$student_batch_id,'weekday_id'=>date('N')+1));
			
			
			//var_dump($TimetableEntries);exit;
			if($TimetableEntries and $TimetableEntries!=NULL)
			{
			?>
			<table  border="0" cellspacing="0" cellpadding="0" class="table table-invoice">
			  <tr>
				<th width="50%"><?php echo Yii::t('app','Time');?></th>
				<th width="50%"><?php echo Yii::t('app','Subject');?></th>
			  </tr>
			  <?php foreach($TimetableEntries as $TimetableEntry)
			  { ?>
			  <tr>
				
				<?php 
				
				$ClassTiming= ClassTimings::model()->findByAttributes(array('id'=>$TimetableEntry->class_timing_id)); 
				
				if($ClassTiming and $ClassTiming!=NULL)
				{ 
					if($TimetableEntry->is_elective == 2){	
					
					$subject = Electives::model()->findByAttributes(array('id'=>$TimetableEntry->subject_id));
					$existing_elective = StudentElectives::model()->findByAttributes(array('elective_id'=>$subject->id,'student_id'=>$student->id));
					if($existing_elective!=NULL){
				?>
					<td width="50%"><div class="dash_blue"><?php echo $ClassTiming->start_time.' - '.$ClassTiming->end_time ?></div></td> 
					<td width="50%" style="text-align:left;">
                    <?php
					  if($subject and $subject!=NULL)
					  {
						  echo $subject->name;
					  }
					  else
					  {
						  echo '----';
					  }
					}
					  ?>
                     </td>	
				<?php 
					}else{
				?>
                	<td width="50%"><div class="dash_blue"><?php echo $ClassTiming->start_time.' - '.$ClassTiming->end_time ?></div></td>
                	<td width="50%" style="text-align:left;"><?php $subject = Subjects::model()->findByAttributes(array('id'=>$TimetableEntry->subject_id));
					  if($subject and $subject!=NULL)
					  {
						  echo $subject->name;
					  }
					  else
					  {
						  echo '----';
					  }?>
                     </td>	
                <?php		
					}
				}?>
				<?php /*?>else
				{ ?>
					<td><div class="dash_blue"></div></td>
				<td></td>
				<?php }<?php */?>
			  </tr>
			  <?php } ?>
			  
			  
			</table>
            <div style="text-align:right;"><?php echo CHtml::link(Yii::t('app','More'), array('/studentportal/default/timetable','bid'=>$student_batch_id));?></div>
			<?php }
			else
			{
				echo '<p style="padding:40px 0px; text-align:center;">'.Yii::t('app','No Classes Scheduled for Today').'</p>';
			}}
			else
			{
				echo Yii::t('app','Time Table not available for your').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");
			}
			}
			else
			{
				echo Yii::t('app','You are not Enrolled in any').' '.Yii::app()->getModule("students")->labelCourseBatch();
			}
			?>
			
			
				</div>
    
    <?php } ?>

          
          </div>
          </div>
         
          
                <!-- panel -->
                
            </div>
            </div>
       
       
       		<div class="col-md-12">
                <div class="panel-heading">
               	 <h4 class="panel-title dashbord_icon"><i class="fa fa-envelope"></i><?php echo Yii::t('app','Mailbox');?></h4>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                    <div class="scrollbox5"> 
                    <div class="v-timeline"> 
                        <?php 
						$mailbox_messages = new CActiveDataProvider(Mailbox::model()->inbox(Yii::app()->user->Id)); 
						$this->widget('zii.widgets.CListView', array(
						'id'=>'mailbox',
						'dataProvider'=>$mailbox_messages,
						'itemView'=>'_news_list',
						'template'=>'{items}',
						
					)); ?>
                    </div>
                       </div> 
                    </div><!-- panel-body -->
                </div><!-- panel -->
              </div>
            </div>

    <script>
     
$('.scrollbox1 .scrollbox2 .scrollbox3 .scrollbox4 .scrollbox5').enscroll({
    showOnHover: true,
    verticalTrackClass: 'track3',
    verticalHandleClass: 'handle3'
}); 
</script>
<script>
 $('#batch_drop').change(function(){
        var batch_id   ="<?php echo isset($_REQUEST['bid'])?$_REQUEST['bid']:''; ?>";
        var batch= $(this).val();
        var url= window.location.href;
        if (location.href.indexOf('bid=') > -1) {
            if(batch==""){
                url = location.href.replace('&bid='+batch_id, "");
            }else{
                url = location.href.replace('bid='+batch_id, 'bid='+batch);
            }
         }else{
             url += '&bid='+batch;
         }                     
        window.location.href= url;
        
    });
</script>
<script>
 $('#batch_drop_exam').change(function(){
        var batch_id   ="<?php echo isset($_REQUEST['bid'])?$_REQUEST['bid']:''; ?>";
        var batch= $(this).val();
        var url= window.location.href;
        if (location.href.indexOf('bid=') > -1) {
            if(batch==""){
                url = location.href.replace('&bid='+batch_id, "");
            }else{
                url = location.href.replace('bid='+batch_id, 'bid='+batch);
            }
         }else{
             url += '&bid='+batch;
         }                     
        window.location.href= url;
        
    });
</script>
<script>
 $('#batch_drop_timetable').change(function(){
        var batch_id   ="<?php echo isset($_REQUEST['bid'])?$_REQUEST['bid']:''; ?>";
        var batch= $(this).val();
        var url= window.location.href;
        if (location.href.indexOf('bid=') > -1) {
            if(batch==""){
                url = location.href.replace('&bid='+batch_id, "");
            }else{
                url = location.href.replace('bid='+batch_id, 'bid='+batch);
            }
         }else{
             url += '&bid='+batch;
         }                     
        window.location.href= url;
        
    });
</script>