<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tableHeadFixer.js"></script>
<style>
#parent {

	border: 1px #EAD5A4 solid;
}
.attndnce-wang{
	margin-top:0px;	
}
.edit_bttns{
    top: 0px;
    right: 155px;

}
.ea_pdf {
	padding: 0px;
	margin: 0px;
	width: 150px;
	height: auto;
	position: absolute;
	top:35px;
	right: -5px;
}
.atnd_tnav{
	top: 0;
}
.timetable-grid{
	width:auto;
	 overflow:inherit;	
}

		</style>
<?php
$batch=Batches::model()->findByAttributes(array('id'=>$_REQUEST['id'])); 
$this->breadcrumbs=array(
	Yii::t('app','Courses')=>array('/courses'),
	html_entity_decode($batch->name)=>array('/courses/batches/batchstudents','id'=>$_REQUEST['id']),
	Yii::t('app','Attendances'),
);
	

	$day			= (isset($_REQUEST['date']))?date('Y-m-d', strtotime($_REQUEST['date'])):date("Y-m-d");
	$prev_day		= date('Y-m-d', strtotime('-1 days', strtotime($day)));
	$next_day		= date('Y-m-d', strtotime('+1 days', strtotime($day)));
	$this_date		= $day;
	$batch			= Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
	$begin 			= date('Y-m-d',strtotime($batch->start_date));
	$end			= date('Y-m-d',strtotime($batch->end_date)); 
	$settings		= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
	
	$month_1		=	date("M", strtotime($this_date));
	$month			=	Yii::t('app',$month_1);
	$years			=	date("Y", strtotime($this_date));
	$days			=	date("d", strtotime($this_date));	
	$display_date 	= $month.' '.$years.' '.$days;
	
	if($settings != NULL){
		$displayformat	= $settings->displaydate;
		$pickerformat	= $settings->dateformat;
	}
	else{
		$displayformat	= 'M d Y';
		$pickerformat 	= 'dd-mm-yy';
	}
?>
             
<div  style="background:#fff; min-height:800px;"> 

    <table  width="100%" border="0" cellspacing="0" cellpadding="0">

        <tbody>
            <tr>
                <td valign="top">
                <div id="attendanceDialog"></div>
                <?php 
				if($batch!=NULL)
                {
                ?>
                    <div style="padding:20px;">
                        <div class="clear"></div>
                        <div class="emp_right_contner">
                            <div class="emp_tabwrapper">
								<?php $this->renderPartial('/batches/tab');?>
                                <div class="clear"></div>
									<?php
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
									$batch_id		= $batch->id;
									$is_week_day 	= StudentAttentance::model()->isWeekday($day, $batch_id);
									$is_holiday		= StudentAttentance::model()->isHoliday($day);	
									$students		= Yii::app()->getModule('students')->studentsOfBatch($_REQUEST['id']);
									?>
                                    <br />
                                     <?php
                                    Yii::app()->clientScript->registerScript(
                                    'myHideEffect',
                                    '$(".flash-success").animate({opacity: 1.0}, 3000).fadeOut("slow");',
                                    CClientScript::POS_READY
                                    );
                                    ?>
                                    
                                    <?php if(Yii::app()->user->hasFlash('notification')):?>
                                    <span class="flash-success">
                                    <?php echo Yii::app()->user->getFlash('notification'); ?>
                                    </span>
                                    <?php endif;
									
								if(count($students) !=0){
										 ?>
                                  
                                    <div class="attnd-tab-sectn-blk">
                                    <div class="tab-sectn">
                                    <ul>
                                    	<?php if(Configurations::model()->studentAttendanceMode() != 2){	 ?>
                                              		<li><?php echo CHtml::link(Yii::t("app","DAY WISE"), array("/courses/studentAttentance", "id"=>$batch->id), array("class"=>"active-attnd"));?> </li>
                                        <?php } ?>    
                                    	<?php if(Configurations::model()->studentAttendanceMode() != 1){	 ?>
                                              		<li><?php echo CHtml::link(Yii::t("app","SUBJECT WISE"), array("/courses/studentSubjectAttendance/daily", "id"=>$batch->id), array("class"=>"sub-attnd-daily"));?>  </li>  
                                        <?php } ?>    
                                    </ul>
                                    </div>
                                    </div>
                                    <div class="attnd-tab-inner-blk">
                                        <div class="attndwise-head">
                                        	<h3><?php echo Yii::t("app", 'Day wise Attendance');?></h3>
                                        </div>
                                        
<div class="opnsl_headerBox">
                        <div class="opnsl_actn_box">
                            <div class="opnsl_actn_box1">
<div class="atnd_table-calender-bg atnd_tnav-new " align="center">
											<?php                                                                        
												echo CHtml::link('<div class="atnd-table-arow-left"><img src="'.Yii::app()->request->baseUrl.'/images/attnd-arow-left.png" height="13" width="7" border="0"></div>', array('/courses/studentAttentance/daily', 'id'=>$batch->id, 'date'=>$prev_day), array('title'=>Yii::t('app', 'Previous Day')));											
												$this->widget('zii.widgets.jui.CJuiDatePicker', array(                        
												'name'=>'date',
												'value' =>$display_date,
												// additional javascript options for the date picker plugin
												'options'=>array(
												'showAnim'=>'fold',
												'dateFormat'=>$pickerformat,
												'changeMonth'=> true,
												'changeYear'=>true,
												'yearRange'=>'1900:'.(date('Y')),
												'onSelect'=>'js:function(date){
												window.location.href	=  "'.$this->createUrl('studentAttentance/daily', array('id'=>$batch_id)).'" + "&date=" + date;
												}'
												),
												'htmlOptions'=>array(
												'class'=>'atnd_table-cal-input',
												//'style'=>'text-align:center; border:none; left:27px; top:-3px; cursor:pointer;',
												'readonly'=>true
												),
												));
												if($next_day<=date('Y-m-d')){
												echo CHtml::link('<div class="atnd-table-arow-right"><img src="'.Yii::app()->request->baseUrl.'/images/attnd-arow-right.png" height="13" width="7" border="0"></div>', array('/courses/studentAttentance/daily', 'id'=>$batch->id, 'date'=>$next_day), array('title'=>Yii::t('app', 'Next Day')));										
												}
                                            ?>                                                                      
                                        </div>
                            </div>
                            <div class="opnsl_actn_box1">
<div class="subwise-blk ">
                                            <ul>
                                                <li><?php echo CHtml::link(Yii::t("app","Daily"), array("/courses/studentAttentance/index", "id"=>$batch_id), array("class"=>"sub-attnd-daily active-attnd"));?> </li>
                                                <li><?php echo CHtml::link(Yii::t("app","Monthly"), array("/courses/studentAttentance/monthlyAttendance", "id"=>$batch_id), array("class"=>"sub-attnd-weekly"));?> </li>
                                                                    
                                            </ul>
                                        </div>
                            </div>                            
                        </div>
                        <div class="opnsl_actn_box">
                             <div class="opnsl_actn_box1">
  <?php if(count($students) != 0 and $day >= $begin and $day <= $end and $day <= date("Y-m-d") and $is_week_day == 2 and $is_holiday != 1){
										echo CHtml::button(Yii::t('app','Send Notification'), array("submit"=>array('StudentAttentance/sendSms','batch_id'=>$_REQUEST['id'],'date'=>$day,'flag'=>"1", Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken,), 'csrf'=>true,'class'=>'formbut')); 
  }
                                    ?>
                            </div>
                            <div class="opnsl_actn_box1">
                               <?php
									if(count($students) != 0 and $day >= $begin and $day <= $end and $day <= date("Y-m-d") and $is_week_day == 2 and $is_holiday != 1){
										echo CHtml::link(Yii::t("app",'Generate PDF'), array('/courses/studentAttentance/dayPdf','batch'=>$batch->id, 'date'=>$day),array('target'=>'_blank','class'=>'pdf_but'));
									}?> 
                            </div>                        
                        </div>
                    </div>    
                                    
                               <?php
								if(count($students) == 0){
								?>
								<div class="not-found-box">
								<?php
								echo '<i class="os_no_found">'.Yii::t("app", "No students in batch").'</i>';
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
								elseif($day > date("Y-m-d")){
								?>
								<div class="not-found-box">
								<?php
								echo '<i class="os_no_found">'.Yii::t("app", "Cannot mark attendance for this date").'</i>';
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
                                <div class="attendance-table-block">
                                        <div class="attendance-table-block-tbl">
                                            <table border="0" align="center" width="100%" id="table" cellspacing="0">
                                            <tbody>
                                            <tr>
                                             <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
                                             <th width="10" class="loader daily-attnd-head"><?php echo Yii::t('app','Roll No');?></th>
                                             <?php } ?>
                                            <th  class="loader daily-attnd-head"><?php echo Yii::t('app','Name');?></th>
                                            <th  class="loader daily-attnd-head"><?php echo getweek($day); ?></th>                                                                                
                                            </tr>
                                            <?php
                                            foreach($students as $student){
												$batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'batch_id'=>$batch_id, 'status'=>1));
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
													}?></p>
                                            </td> 
                                         <?php } ?>
                                            <td class="td daywise-block">
                                            <p><?php echo $student->studentFullName(); ?></p>
                                            </td> 
                                            <td class="td">
                                            <div class="attendance-mark_leave-psn">
                                            <?php 
                                            if($day >= $begin and $day <= $end){//Check current day in b/w batch start and end date 																																												
                                            if($day >= $admission_date){// check the date is weekday or not and date is greater than student admission date
                                            ?>
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
                                            echo CHtml::ajaxLink(
												'<span>'.$tool_tip.'</span>',
												$this->createUrl('studentAttentance/updateDayAttendance'),
												array(
													'onclick'=>'$("#attendanceDialog").dialog("open");return false;',
													'update'=>'#attendanceDialog',
													'type' =>'GET',
													'data' => array(																												
														'student_id'=>$student->id,
														'batch_id'=>$batch_id,
														'date'=>$day																																																										
													 ),
													'dataType' => 'text'
												),
												array(
												'class'=>'student-timtable-update'
												)
                                            );																										
                                            ?>
                                            </div>
                                            <?php																								
                                            }
                                            else
                                            {
                                            echo '<i class="not_joined">'.Yii::t("app", "Student has not joined yet").'</i>';
                                            }
                                            }
                                            ?>
                                            </div>                                                                                    	
                                            </td>
                                            </tr>                                                                                    
                                            <?php																				
                                            }
                                            ?>                                                                            
                                            </tbody>
                                            </table>
                                        </div>
                                      </div>
                               </div> 
<?php															
							}
								}else{
									?>
                                    <div class="pdf-box"> 
                                          <div class="not-found-box">  <?php echo '<i class="os_no_found">'.Yii::t("app", "No students in batch").'</i>'; ?></div>
                                    </div>
									<?php
                                }
?>                                
                        </div> <!-- END div class="formConInner" -->
                      </div> <!-- END div class="emp_tabwrapper" -->
                    </div> <!-- END div class="emp_right_contner" -->                   
<?php
					} // END if($batch!=NULL)
?>
                </td>
            </tr>
        </tbody>
    </table>
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
		url: <?php echo CJavaScript::encode(Yii::app()->createUrl('/courses/studentAttentance/markDayAttendance'))?>,
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
</script>
