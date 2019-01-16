<style type="text/css">
.table-responsive {
    border: 1px solid #ddd;
    margin-bottom: 15px;
    overflow-x: scroll;
    overflow-y: hidden;
    width: 100%;
}
.student-timetable-grid {
    width: inherit;
    overflow: inherit;
}

</style>
<?php
	$settings		= UserSettings::model()->findByAttributes(array('id'=>1));	
	$day			= (isset($_REQUEST['date']))?date('Y-m-d', strtotime($_REQUEST['date'])):date("Y-m-d");
	$prev_day		= date('Y-m-d', strtotime('-1 days', strtotime($day)));
	$next_day		= date('Y-m-d', strtotime('+1 days', strtotime($day)));
	$this_date		= $day;	
	if($_REQUEST['id']!=NULL){
		$list_flag	= 0;   
		$batch		= Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));			
	}
	else{
		 $employee	= Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));		 
		 $batches	= Batches::model()->findAll("employee_id=:x AND is_active=:y AND is_deleted=:z", array(':x'=>$employee->id,':y'=>1,':z'=>0));
		 $batches	= Batches::model()->findAll($criteria);	 
	
		 
		 if(count($batches)>1){
			 $list_flag 		= 1;
		 }
		 else{
			  $list_flag 		= 0;
			  $_REQUEST['id'] 	= $batches[0]->id;		
			  $batch			= Batches::model()->findByAttributes(array('id'=>$batches[0]->id));						 
		 }
	}
	$begin 			= date('Y-m-d',strtotime($batch->start_date));
	$end			= date('Y-m-d',strtotime($batch->end_date)); 	
	if($settings != NULL){
		$displayformat	= $settings->displaydate;
		$pickerformat	= $settings->dateformat;
	}
	else{
		$displayformat	= 'd M Y';
		$pickerformat 	= 'dd-mm-yy';
	}
	
?>

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
    <?php if(isset($_REQUEST['id'])){?>      
      <div class="people-item">
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
                <div class="row"> 
                	<div class="col-md-12">
                    	<div class="name_div">
				<?php 
                $batch_name = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
                $course_name = Courses::model()->findByAttributes(array('id'=>$batch_name->course_id));
				$semester=Semester::model()->findByAttributes(array('id'=>$batch_name->semester_id));
				$sem_enabled= Configurations::model()->isSemesterEnabledForCourse($course_name->id);
 
               ?>
                <div class="batch-block">
				    <p><?php echo '<span>'.Yii::t('app','Course').'</span> '.':'.' '.ucfirst($course_name->course_name).''; ?></p>
					<p><?php echo '<span>'.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>  '.':'.' '.ucfirst($batch_name->name);?></p>
					<?php if($sem_enabled==1 and $batch_name->semester_id!=NULL){ ?>
							<p> <?php echo '<span>'.Yii::t('app','Semester').'</span> '.':'.' '.ucfirst($semester->name).''; ?></p>
					<?php } ?>
				</div>
            
          		</div>
                    </div>
                </div>               
                <div class="row">
                	<div class="pdf-box">
                    	<div class="col-md-10 col-4-reqst">
                         <div class="row">
                         	<div class="col-md-6 col-4-reqst">
                            <div class="row">
                            <div class="col-md-6 col-4-reqst">
                             <div class="atnd_table-calender-bg atnd_tnav-new box-one-lft-rght" align="center">
                                            <?php                                                                        
                                            echo CHtml::link('<div class=""><img src="'.Yii::app()->request->baseUrl.'/images/attnd-arow-left.png" height="13" width="7" border="0"></div>', array('/teachersportal/default/studentAttendance', 'id'=>$_REQUEST['id'], 'date'=>$prev_day), array('title'=>Yii::t('app', 'Previous Day'), 'class'=>'atnd-table-arow-left'));											
                                            $this->widget('zii.widgets.jui.CJuiDatePicker', array(                        
                                            'name'=>'date',
                                            'value' =>date($displayformat, strtotime($this_date)),
                                            // additional javascript options for the date picker plugin
                                            'options'=>array(
                                            'showAnim'=>'fold',
                                            'dateFormat'=>$pickerformat,
                                            'changeMonth'=> true,
                                            'changeYear'=>true,
                                            'yearRange'=>'1900:'.(date('Y')),
                                            'onSelect'=>'js:function(date){
                                            window.location.href	=  "'.$this->createUrl('/teachersportal/default/studentAttendance', array('id'=>$_REQUEST['id'])).'" + "&date=" + date;
                                            }'
                                            ),
                                            'htmlOptions'=>array(
                                            'class'=>'atnd_table-cal-input',
                                            //'style'=>'text-align:center; border:none; left:27px; top:-3px; cursor:pointer;',
                                            'readonly'=>true
                                            ),
                                            ));
                                            if($next_day<=date('Y-m-d')){
                                            echo CHtml::link('<div class=""><img src="'.Yii::app()->request->baseUrl.'/images/attnd-arow-right.png" height="13" width="7" border="0"></div>', array('/teachersportal/default/studentAttendance', 'id'=>$_REQUEST['id'], 'date'=>$next_day), array('title'=>Yii::t('app', 'Next Day'),'class'=>'atnd-table-arow-right'));										
                                            }
                                            ?>                                                                      
                                        </div>
                            </div>
                            <div class="col-md-6 col-4-reqst">             
                            <div class="subwise-blk box-one-lft-rght">
                            <ul>
                            <li><?php echo CHtml::link(Yii::t("app","Daily"), array("/teachersportal/default/studentAttendance", "id"=>$batch->id), array("class"=>"sub-attnd-daily active-attnd"));?> </li>
                <li><?php echo CHtml::link(Yii::t("app","Monthly"), array("/teachersportal/default/StudentDayAttendance", "id"=>$batch->id), array("class"=>"sub-attnd-weekly "));?></li>
                            </ul>
                            </div>
                            </div>
                            </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-md-2 col-4-reqst">
                        <?php 
						$batch_id		= $batch->id;
						$is_week_day 	= StudentAttentance::model()->isWeekday($day, $batch_id);
               			$is_holiday		= StudentAttentance::model()->isHoliday($day);
						$students		= Yii::app()->getModule('students')->studentsOfBatch($_REQUEST['id']);
						
						
						if(count($students) != 0 and $day <= date("Y-m-d") and $day >= $begin and $day <= $end and $is_week_day == 2 and $is_holiday != 1){
							echo CHtml::link(Yii::t("app",'Generate PDF'), array('/teachersportal/default/studayPdf','batch'=>$batch->id, 'date'=>$day),array('target'=>'_blank','class'=>'btn btn-danger  pull-right')); 
						}?>
					  </div>
                    </div>
                </div>
                
       </div>      
         <?php }
		 else {?> 
        <!--<div id="attendanceDialog"></div>-->
        <?php if($list_flag==1){ ?>
        <div class="cleararea"></div>
        <div class="people-item">
        <?php
		$accademic_year = AcademicYears::model()->findAllByAttributes(array('is_deleted'=> 0));
		$acc_arr	= array();
		foreach($accademic_year as $value){
			$acc_arr[$value->id]	= ucfirst($value->name);
		}
		if(isset($_REQUEST['acc_id']) and $_REQUEST['acc_id'] != NULL){
			$accademic	= AcademicYears::model()->findByPk(array($_REQUEST['acc_id']));
		}
		else{
			$accademic	= AcademicYears::model()->findByAttributes(array('is_deleted'=> 0,'status'=>1));
		}
		
		echo Yii::t('app','Viewing Courses of Academic Year');
		if(count($accademic_year) > 1){
					 echo CHtml::dropDownList('acc_id','',$acc_arr,array('encode'=>false,'prompt'=>Yii::t("app",'Select Academic Year'),'style'=>'width:190px;','onchange'=>'getday()','class'=>'form-control','id'=>'acc_id','options'=>array($accademic->id=>array('selected'=>true))));
		}
		
		?>
        <div class="table-responsive">
          <table width="80%" border="0" cellspacing="0" cellpadding="0" class="table mb30">
           <thead>
              <!--class="cbtablebx_topbg"  class="sub_act"-->
              <tr class="pdtab-h">
			   <th align="center"><?php echo Yii::t('app','Course');?></th>
                <th align="center"><?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></th>
				 <th align="center"><?php echo Yii::t('app','Semester');?></th>
                <th align="center"><?php echo Yii::t('app','Class Teacher');?></th>
                <th align="center"><?php echo Yii::t('app','Start Date');?></th>
                <th align="center"><?php echo Yii::t('app','End Date');?></th>
              </tr>
              </thead>
               <tbody>
              <?php 
			             if($batches_id != NULL)
						{
                           foreach($batches_id as $batch_id)
                          {		
						    		$batch	=	Batches::model()->findByAttributes(array('id'=>$batch_id));	
						  			$model = AttendanceSettings::model()->findByAttributes(array('config_key'=>'type'));
									$course = Courses::model()->findByAttributes(array('id'=>$batch->course_id));
									$sem_enabled = Configurations::model()->isSemesterEnabledForCourse($course->id);
									if($model->config_value == 1)
						  				$link = CHtml::link(ucfirst($batch->name), array('/teachersportal/default/studentattendance','id'=>$batch->id));
									else
										$link = CHtml::link(ucfirst($batch->name), array('/attendance/subjectAttendance/tpAttendance','id'=>$batch->id));
								
                                    echo '<tr id="batchrow'.$batch->id.'">';
									echo '<td style="text-align:left; padding-left:10px; font-weight:bold;">'.ucfirst($course->course_name).'</td>';
                                    echo '<td style="text-align:left; padding-left:10px; font-weight:bold;">'.$link.'</td>';
									
									if($sem_enabled==1 and $batch->semester_id!=NULL){
										$semester=Semester::model()->findByAttributes(array('id'=>$batch->semester_id)); 
										echo '<td style="text-align:left; padding-left:10px; font-weight:bold;">'.$semester->name.'</td>';  
									}
									else{
										echo '<td style="text-align:left; padding-left:10px; font-weight:bold;">'.'-'.'</td>'; 
									}
                                    $settings=UserSettings::model()->findByAttributes(array('id'=>1));
										if($settings!=NULL)
										{	
											$date1=date($settings->displaydate,strtotime($batch->start_date));
											$date2=date($settings->displaydate,strtotime($batch->end_date));
		
										}
                                    $teacher = Employees::model()->findByAttributes(array('id'=>$batch->employee_id));					
                                    echo '<td style="text-align:left; padding-left:10px; font-weight:bold;">';
                                    if($teacher){
                                        echo Employees::model()->getTeachername($teacher->id);
                                    }
                                    else{
                                        echo '-';
                                    }
                                    echo '</td>';					
                                    echo '<td style="text-align:left; padding-left:10px; font-weight:bold;">'.$date1.'</td>';
                                    echo '<td style="text-align:left; padding-left:10px; font-weight:bold;">'.$date2.'</td>';
                                    echo '</tr>';
                                }
						}
						else
						{?>
							<tr>
                            <td style="text-align:center" colspan="7"><?php echo Yii::t('app','No Data Available');?></td>
                            </tr>
						<?php }
                               ?>
            </tbody>
          </table>
        </div>
        </div>
                <?php }
				}
                if($list_flag==0 or isset($_REQUEST['id'])){
                function getweek($day){
					$date   = date('d',strtotime($day));
					$month  = date('m',strtotime($day));
					$year 	= date('Y',strtotime($day));
					$date 	= mktime(0, 0, 0,$month,$date,$year); 
					$week 	= date('w', $date); 
					switch($week){
					case 0: 
					return 'Sunday';
					break;
					case 1: 
					return 'Monday';
					break;
					case 2: 
					return 'Tuesday';
					break;
					case 3: 
					return 'Wednesday';
					break;
					case 4: 
					return 'Thursday';
					break;
					case 5: 
					return 'Friday';
					break;
					case 6: 
					return 'Saturday';
					break;
                	}
                }
                
               
                ?>
                										
                <div class="subwis-tableposctn">
                    <div class="formWrapper formWrapper-subwis">
                        <div style="width:100%">  
                            <div>  
                        <?php  if(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL){?>
								<?php
                                if(count($students) == 0){
								?>
								<div class="not-found-box">
								<?php
								echo '<i class="os_no_found">'.Yii::t("app", "No students Found ").'</i>';
								?>
								</div>
								<?php
								}
								elseif($day > date("Y-m-d")){
								?>
								<div class="not-found-box">
								<?php
								echo '<i class="os_no_found">'.Yii::t("app", "Cannot mark attendance for this date").'</i>';
								?>
								</div>
								<?php
								}
								elseif($day < $begin){
								?>
								<div class="not-found-box">
								<?php
								echo '<i class="os_no_found">'.Yii::t("app", "Batch not started").'</i>';
								?>
								</div>
								<?php
								}
								elseif($day > $end){
								?>
								<div class="not-found-box">
								<?php
								echo '<i class="os_no_found">'.Yii::t("app", "Batch ended").'</i>';
								?>
								</div>
								<?php
								}
								elseif($is_week_day != 2 ){
								?>
								<div class="not-found-box">
								<?php
								echo '<i class="os_no_found">'.Yii::t("app", "Selected day is not a weekday").'</i>';
								?>
								</div>
								<?php
								}	
								elseif($is_holiday == 1){
								?>
								<div class="not-found-box">
								<?php
								echo '<i class="os_no_found">'.Yii::t("app", "Selected day is an annual holiday").'</i>';
								?>
								</div>
								<?php
								}
                                else{ 
                            ?>
                            
                            <div class="clearfix"></div>
                                <div class="student-timetable-grid">
                                    <div class="timetable-grid-scroll">
                                        <table border="0" align="center" width="100%" id="table" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                 <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
                                                  <th width="80" class="loader daily-attnd-head"><?php echo Yii::t('app','Roll No');?></th>
                                                  <?php } ?>
                                                    <th width="80" class="loader daily-attnd-head"><?php echo Yii::t('app','Name');?></th>
                                                    <th width="80" class="loader daily-attnd-head"><?php echo getweek($day); ?></th>                                                                                
                                                </tr>
												<?php
                                                foreach($students as $student){
													$batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$student->batch_id, 'status'=>1));
                                                $admission_date	= date("Y-m-d", strtotime($student->admission_date));
                                                $is_absent	= StudentAttentance::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$batch_id, 'date'=>$day));																				
                                                                                                                    
                                                if($is_absent != NULL){
                                                $present_class 	= '';
                                                $absent_class	= 'daily-absent';
                                                }
                                                else{
                                                $present_class 	= 'daily-present';
                                                $absent_class	= '';
                                                }
												$tool_tip		= 'Reason';
                                                ?>
                                                    <tr>
                                                      <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
                                                       <td class="td daywise-block">
                                                        <p><?php if($batch_student!=NULL and $batch_student->roll_no!=0){
																	echo $batch_student->roll_no;
																}
																else{
																	echo '-';
																}?>
                                                    	</p>
                                                        </td> 
                                                      <?php } ?>
                                                        <td class="td daywise-block">
                                                        <p><?php echo $student->studentFullName(); ?></p>
                                                        </td> 
                                                        <td class="td">
                                                        <?php 
                                                    if($day >= $begin and $day <= $end){//Check current day in b/w batch start and end date 																																												
                                                    if($day >= $admission_date){// check the date is weekday or not and date is greater than student admission date
                                                    ?>
                                                    <div class=" mark-atnd-posin"> 
                                                        <div class="daily-attnd-block">
                                                            <div class="attnd-action-block">
                                                                <ul>
                                                                    <li>
                                                                        <a href="javascript:void(0)" class="present <?php echo $present_class; ?>" data-student_id="<?php echo $student->id;?>" data-batch_id="<?php echo $batch->id; ?>" data-date="<?php echo $day; ?>" data-type="1"><?php echo Yii::t('app', 'Present'); ?></a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:void(0)" class="absent <?php echo $absent_class; ?>" data-student_id="<?php echo $student->id;?>" data-batch_id="<?php echo $batch->id; ?>" data-date="<?php echo $day; ?>" data-type="2"><?php echo Yii::t('app', 'Absent'); ?></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="student-action-box comn-tooltip">		
                                                        <?php
															echo CHtml::link(
																'<span>'.$tool_tip.'</span>',
																'javascript:void(0);',
																array(
																	'class'=>' open_popup student-timtable-update',
																	'data-ajax-url'=>$this->createUrl(
																		'/teachersportal/default/updateDayAttendance',
																		array(
																			'student_id'=>$student->id,
																			'batch_id'=>$batch_id,
																			'date'=>$day
																		)
																	),
																	'data-target'=>"#myModal",
																	'data-toggle'=>"modal",
																	'data-modal-label'=>Yii::t("app", "Mark Leave"),
																	'data-modal-description'=>Yii::t("app", "Mark the reason for leave")
																)
															);
															////																										
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <?php																								
                                                    }
                                                    else{
                                                            echo '<i class="not-found-box">'.Yii::t("app", "Student has not joined yet").'</i>';
                                                         }
                                                    }
                                                    ?>                                                                                    	
                                                    </td>
                                                </tr>                                                                                    
                                            <?php																				
                                            }
                                            ?>                                                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>            
                            <?php															
                            }
						 }// end if(isset($_REQUEST[['id']) and $_REQUEST[['id']!=NULL)
                            ?>												
                            </div>
                        </div>
                    </div>
                </div>    
                <?php                                        
                }?>
                </div>
                </div>
              </div>
          <div class="clear"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
$('.present, .absent').click(function(ev){
	var that		= $(this);
	var student_id	= $(this).attr('data-student_id');
	var batch_id	= $(this).attr('data-batch_id');
	var date		= $(this).attr('data-date');
	var type 		= $(this).attr('data-type');	
	$.ajax({
		type: "POST",
		url: <?php echo CJavaScript::encode(Yii::app()->createUrl('/teachersportal/default/markDayAttendance'))?>,
		data: {'student_id':student_id,'date':date,'batch_id':batch_id, 'type':type, "<?php echo Yii::app()->request->csrfTokenName;?>":"<?php echo Yii::app()->request->csrfToken;?>"},
		success: function(result){						
			if(result == 1){												
				that.addClass('daily-present');
				that.closest("ul").find(".absent").removeClass('daily-absent');
			}
			else if(result == 2){
				that.addClass('daily-absent');
				that.closest("ul").find(".present").removeClass('daily-present');
			}
		}
	});
});
$('#acc_id').change(function(ev){
	var acc_id	= $(this).val();
	if(acc_id != ''){
		window.location= 'index.php?r=teachersportal/default/studentattendance&acc_id='+acc_id;
	}
	else{
		window.location= 'index.php?r=teachersportal/default/studentattendance';
	}
});
</script>