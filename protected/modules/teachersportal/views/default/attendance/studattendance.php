<style type="text/css">
.table-responsive {
    border: 1px solid #ddd;
    margin-bottom: 15px;
    overflow-x: scroll;
    overflow-y: hidden;
    width: 100%;
}
</style>
<?php Yii::app()->clientScript->registerCoreScript('jquery');?>

<div id="parent_Sect">
<?php $this->renderPartial('leftside');?>
<div class="right_col"  id="req_res123">
<!--contentArea starts Here-->
<div id="parent_rightSect">
  <div class="parentright_innercon">
    <div class="pageheader">
      <div class="col-lg-8">
        <h2><i class="fa fa-file-text"></i><?php echo Yii::t('app', 'Attendance');?><span><?php echo Yii::t('app', 'View your attendance here');?> </span></h2>
      </div>
      <div class="col-lg-2"> </div>
      <div class="breadcrumb-wrapper"> <span class="label"><?php echo Yii::t('app', 'You are here:');?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
          
          <li class="active"><?php echo Yii::t('app', 'Attendance');?></li>
        </ol>
      </div>
      <div class="clearfix"></div>
    </div>
    <div class="contentpanel">
      <div class="panel-heading">
        <h3 class="panel-title"><?php echo Yii::t('app','Mark Student Attendance'); ?></h3>
      </div>
      <div class="people-item">
      
        
        
        <?php
        	$model = new EmployeeAttendances;
			if(isset($_REQUEST['date']) and $_REQUEST['date']!=NULL){
				$day	=	date('Y-m', strtotime($_REQUEST['date']));
				$mon = $model->getMonthname(date('F', strtotime($_REQUEST['date']))); 
				$mon_num	= date('m', strtotime($_REQUEST['date']));
				$curr_year 	= date('Y', strtotime($_REQUEST['date'])); 
			}
			else if(!isset($_REQUEST['mon']))
			{
				$mon = date('F');
				$mon_num = date('n');
				$curr_year = date('Y');
				$day		=	date("Y-m");
			}
			else
			{
				$mon = $model->getMonthname($_REQUEST['mon']);
				$mon_num = $_REQUEST['mon'];
				$curr_year = $_REQUEST['year'];
				$day		=	$_REQUEST['year'].'-'.$_REQUEST['mon'];
			}
			$num = cal_days_in_month(CAL_GREGORIAN, $mon_num, $curr_year);
			$this_date		= $day;
			
			$displayformat	= 'F Y'; 
			$pickerformat 	= 'M yy';
		?>
  <?php if(isset($_REQUEST['id'])){?>      
		<div class="attendance-block-bg">
             
                <div class="attnd-tab-sectn-blk">   
                <div class="tab-sectn">
                <ul>
               <?php if(Configurations::model()->studentAttendanceMode() != 2){ ?>
                                                <li><?php echo CHtml::link(Yii::t("app","DAY WISE"), array("/teachersportal/default/studentAttendance", "id"=>$_REQUEST['id']), array("class"=>"active-attnd"));?> </li>
                                       <?php } ?>    
                                    <?php if(Configurations::model()->studentAttendanceMode() != 1){ ?>
                								<li><?php echo CHtml::link(Yii::t("app","SUBJECT WISE"), array("/teachersportal/default/daily", "bid"=>$_REQUEST['id']), array("class"=>"sub-attnd-daily"));?> </li>
                <?php } ?>    
                </ul>
                </div>
                </div>
                <div class="attndwise-head">
                <h3><?php echo Yii::t('app','Day Wise Attendance');?></h3>
                </div>
                <?php 
                $batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
                $course_name = Courses::model()->findByAttributes(array('id'=>$batch_name->course_id));
				$semester=Semester::model()->findByAttributes(array('id'=>$batch_name->semester_id)); 
				$sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course_name->id);
				
				$begin_y = date('Y',strtotime($batch_name->start_date));
				$end_y = date('Y',strtotime($batch_name->end_date));
               ?>		
               <div class="batch-block">
				    <p><?php echo '<span>'.Yii::t('app','Course').'</span> '.':'.' '.ucfirst($course_name->course_name).''; ?></p>
					<p><?php echo '<span>'.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>  '.':'.' '.ucfirst($batch_name->name);?></p>
					<?php if($sem_enabled==1 and $batch_name->semester_id!=NULL){ ?>
							<p> <?php echo '<span>'.Yii::t('app','Semester').'</span> '.':'.' '.ucfirst($semester->name).''; ?></p>
					<?php } ?>
				</div>
                <div class="row">
                	<div class="pdf-box">
                    
                    	<div class="col-md-10 col-4-reqst">
                         <div class="row">
                         	<div class="col-md-6 col-4-reqst">
                            <div class="row">
                            <div class="col-md-6 col-4-reqst">
                            
                             <div class="atnd_table-calender-bg-none atnd_tnav-new box-one-lft-rght" align="center">
            <?php 
                        echo CHtml::link('<div class=""><img src="images/attnd-arow-left.png" width="7" border="0"  height="13" /></div>', array('studentdayattendance', 'mon'=>date("m",strtotime($curr_year."-".$mon_num."-01 -1 months")),'year'=>date("Y",strtotime($curr_year."-".$mon_num."-01 -1 months")),'id'=>$_REQUEST['id']), array('class'=>'atnd-table-arow-left')); 
						?>
                        <div class="fixed-datepik">
                        <p>
                        <?php
						   $this->widget('ext.EJuiMonthPicker.EJuiMonthPicker', array(
								'name' => 'month_year',
								'value'=>date($displayformat, strtotime($this_date)),
								'options'=>array(
									'yearRange'=>$begin_y.':'.$end_y,
									'dateFormat'=>$pickerformat,
									'onSelect'=>'js:function(date){
										window.location.href	=  "'.$this->createUrl('default/studentdayattendance', array('id'=>$_REQUEST['id'])).'" + "&date=" + date;
									}'
								),
								'htmlOptions'=>array(
									'class'=>'atnd_table-cal-input',
									'readonly'=>true
								),
							));
						?>
                        </p></div>
                        <?php
                        echo CHtml::link('<div class=""><img src="images/attnd-arow-right.png" width="7" border="0"  height="13" /></div>', array('studentdayattendance', 'mon'=>date("m",strtotime($curr_year."-".$mon_num."-01 +1 months")),'year'=>date("Y",strtotime($curr_year."-".$mon_num."-01 +1 months")),'id'=>$_REQUEST['id']), array('class'=>'atnd-table-arow-right'));
			?>
                                                                             
                                        </div>
                            </div>
                            <div class="col-md-6 col-4-reqst">             
                            <div class="subwise-blk box-one-lft-rght">
                            <ul>
                            <li><?php echo CHtml::link(Yii::t("app","Daily"), array("/teachersportal/default/studentAttendance", "id"=>$_REQUEST['id']), array("class"=>"sub-attnd-daily"));?> </li>
                <li><?php echo CHtml::link(Yii::t("app","Monthly"), array("/teachersportal/default/StudentDayAttendance", "id"=>$_REQUEST['id']), array("class"=>"sub-attnd-weekly active-attnd"));?></li>
                            </ul>
                            </div>
                            </div>
                            </div>
                            </div>
                        </div>
                        </div>
					
                        <div class="col-md-2 col-4-reqst">
					<?php	if($posts!=NULL){ ?>
                        	<?php
                         		if($_REQUEST['mon']&&$_REQUEST['year']){
                                	echo CHtml::link(Yii::t('app','Generate PDF'), array('/teachersportal/default/studentdayPdf','mon'=>$_REQUEST['mon'],'year'=>$_REQUEST['year'],'id'=>$_REQUEST['id']),array('target'=>'_blank','class'=>'btn btn-danger  pull-right')); 
                                }
                                else{
                                	echo CHtml::link(Yii::t('app','Generate PDF'), array('/teachersportal/default/studentdayPdf','mon'=>$mon_num,'year'=>$curr_year,'id'=>$_REQUEST['id']),array('target'=>'_blank','class'=>'btn btn-danger  pull-right')); 
                                
         						}
                                ?>
					<?php } ?>
                        </div>
                    </div>
                </div>
                
             </div>
        
        
  <?php }
  else{ ?>      

        <?php  //If $list_flag = 1, table of batches will be displayed. If $list_flag = 0, attendance table will be displayed.
			   if($_REQUEST['id']!=NULL){
						$list_flag=0;   		
				 }
				else{
					 $employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
					 $batch=Batches::model()->findAll("employee_id=:x AND is_active=:y AND is_deleted=:z", array(':x'=>$employee->id,':y'=>1,':z'=>0));
					 if(count($batch)>1){
						 $list_flag = 1;
					 }
					 else{
						  $list_flag = 0;
						  $_REQUEST['id'] = $batch[0]->id;							 
					 }
				}?>
        <?php if($list_flag==1){ ?>
        <div class="cleararea"></div>
        <div class="table-responsive">
          <table width="80%" border="0" cellspacing="0" cellpadding="0" class="table mb30">
           <thead>
              <!--class="cbtablebx_topbg"  class="sub_act"-->
              <tr class="pdtab-h">
                <th align="center"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Name');?></th>
                <th align="center"><?php echo Yii::t('app','Class Teacher');?></th>
                <th align="center"><?php echo Yii::t('app','Start Date');?></th>
                <th align="center"><?php echo Yii::t('app','End Date');?></th>
              </tr>
              </thead>
               <tbody>
              <?php 
                          foreach($batch as $batch_1)
                          {			
						  			$model = AttendanceSettings::model()->findByAttributes(array('config_key'=>'type'));
									if($model->config_value == 1)
						  				$link = CHtml::link($batch_1->name, array('/teachersportal/default/studentdayattendance','id'=>$batch_1->id));
									else
										$link = CHtml::link($batch_1->name, array('/attendance/subjectAttendance/tpAttendance','id'=>$batch_1->id));
								
                                    echo '<tr id="batchrow'.$batch_1->id.'">';
                                    echo '<td style="text-align:left; padding-left:10px; font-weight:bold;">'.$link.'</td>';
                                    $settings=UserSettings::model()->findByAttributes(array('id'=>1));
										if($settings!=NULL)
										{	
											$date1=date($settings->displaydate,strtotime($batch_1->start_date));
											$date2=date($settings->displaydate,strtotime($batch_1->end_date));
		
										}
                                    $teacher = Employees::model()->findByAttributes(array('id'=>$batch_1->employee_id));					
                                    echo '<td align="center">';
                                    if($teacher){
                                        echo Employees::model()->getTeachername($teacher->id);
                                    }
                                    else{
                                        echo '-';
                                    }
                                    echo '</td>';					
                                    echo '<td align="center">'.$date1.'</td>';
                                    echo '<td align="center">'.$date2.'</td>';
                                    echo '</tr>';
                                }
                               ?>
            </tbody>
          </table>
        </div>
        
        <?php }
  		}
		$posts=Yii::app()->getModule('students')->studentsOfBatch($_REQUEST['id']);
		 if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL){
			if($posts!=NULL){
                if($list_flag==0 or isset($_REQUEST['id'])){ 
					function getweek($date,$month,$year)
					{
						$date = mktime(0, 0, 0,$month,$date,$year); 
						$week = date('w', $date); 
						switch($week) {
							case 0: 
							return 'S<br>';
							break;
							case 1: 
							return 'M<br>';
							break;
							case 2: 
							return 'T<br>';
							break;
							case 3: 
							return 'W<br>';
							break;
							case 4: 
							return 'T<br>';
							break;
							case 5: 
							return 'F<br>';
							break;
							case 6: 
							return 'S<br>';
							break;
						}
					}
				?>
        <div class="atdn_div">
          <!-- End top navigation div -->
          <div class="table-responsive">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table mb30">
              <tr>
               <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
               <th><?php echo Yii::t('app','Roll No');?></th>
                <?php } ?>
                <th><?php echo Yii::t('app','Name');?></th>
                <?php
                            for($i=1;$i<=$num;$i++)
                            {
                                echo '<th>'.getweek($i,$mon_num,$curr_year).'<span>'.$i.'</span></th>';
                            }
                            ?>
              </tr>
              <?php
			  
		/////////////////	  
				$selected_batch= Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
				$batch_start = date('Y-m-d',strtotime($selected_batch->start_date));
				$batch_end = date('Y-m-d',strtotime($selected_batch->end_date));
								
				$days = array();
				$batch_days = array();
				$batch_range = StudentAttentance::model()->createDateRangeArray($batch_start,$batch_end);
				$batch_days = array_merge($batch_days,$batch_range);
				
				$weekArray = array();
				$weekdays = Weekdays::model()->findAll("batch_id=:x AND weekday<>:y", array(':x'=>$selected_batch->id,':y'=>"0"));
				
				if(count($weekdays)==0){										
					$weekdays = Weekdays::model()->findAll("batch_id IS NULL AND weekday<>:y",array(':y'=>"0"));
				}
				
				foreach($weekdays as $weekday){					
					$weekday->weekday = $weekday->weekday - 1;
					if($weekday->weekday <= 0){
						$weekday->weekday = 7;
					}
					$weekArray[] = $weekday->weekday;
				}
				foreach($batch_days as $batch_day){
					$week_number = date('N', strtotime($batch_day));													
					if(in_array($week_number,$weekArray)) // If checking if it is a working day
					{
						array_push($days,$batch_day);
					}
				}
			  
	///////////////		  
			   
								$holidays = Holidays::model()->findAll();
								$holiday_arr=array();
								foreach($holidays as $key=>$holiday){
									if(date('Y-m-d',$holiday->start)!=date('Y-m-d',$holiday->end)){
										$date_range = StudentAttentance::model()->createDateRangeArray(date('Y-m-d',$holiday->start),date('Y-m-d',$holiday->end));
										foreach ($date_range as $value) {
											$holiday_arr[$value] = $holiday->id;
										}
									}
									else{
										$holiday_arr[date('Y-m-d',$holiday->start)] = $holiday->id;
									}
								}
			   
								foreach($posts as $posts_1)
								{
									$batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$posts_1->id, 'batch_id'=>$posts_1->batch_id, 'status'=>1));
									echo '<tr>';
									 if(Configurations::model()->rollnoSettingsMode() != 2){
										 if($batch_student!=NULL and $batch_student->roll_no!=0){
											 echo '<td class="name">'.$batch_student->roll_no.'</td>';
										}
										else{
											echo '<td class="name">'.'-'.'</td>';
										}
										
									 }
									
										echo '<td class="name">'.$posts_1->studentFullName('forTeacherPortal').'</td>';
										for($i=1;$i<=$num;$i++)
										{
											echo '<td><span  id="td'.$i.$posts_1->id.'">';
											echo  $this->renderPartial('attendance/ajax',array('day'=>$i,'month'=>$mon_num,'year'=>$curr_year,'emp_id'=>$posts_1->id,'batch_id'=>$_REQUEST['id'], 'days'=>$days, 'holiday_arr'=>$holiday_arr));
											echo '</span><div  id="jobDialog123'.$i.$posts_1->id.'"></div></td>';
											echo '</span><div  id="jobDialogupdate'.$i.$posts_1->id.'"></div></td>';
										}
									echo '</tr>';
								}
						?>
            </table>
          </div>
        </div>
        <!-- End attendance div -->
        <?php 
			}
		}
		else{ ?>
		<div class="not-found-box">
			<?php
				echo '<i class="os_no_found">'.Yii::t("app", "No students Found").'</i>';
			?>
		</div>
		<?php
		}
	} // end if checking id is present?>
      </div>
    </div>
  </div>
  <div class="clear"></div>
</div>