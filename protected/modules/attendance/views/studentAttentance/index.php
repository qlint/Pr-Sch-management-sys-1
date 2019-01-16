<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tableHeadFixer.js"></script>
		<script>
			$(document).ready(function() {
				$("#fixTable").tableHeadFixer({"left" : 1}); 
			});
		</script>
		<style>
			#parent {
				height: 350px;
			    border: 1px #EAD5A4 solid;
			}
			
			#fixTable {
				width: 1190px !important;
			}
			.atnd_Con table {
				border:none;	
			}
		</style>

<script language="javascript">
function course()
{
var id = document.getElementById('bat').value;
window.location= 'index.php?r=studentAttentance/index&id='+id;	
}
</script>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Attendances')=>array('/attendance'),
	Yii::t('app','Student Attendances'),
);

/*$this->menu=array(
	array('label'=>'Create Attendances', 'url'=>array('create')),
	array('label'=>'Manage Attendances', 'url'=>array('admin')),
);*/
?>
<?php
$batch=Batches::model()->findByAttributes(array('id'=>$_REQUEST['id'])); ?>
<div style="background:#fff; min-height:800px;">   
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td valign="top">
                    <div style="padding:20px;">
                    <?php 
                    if($batch!=NULL)
                    {
                    ?>
                        <!--<div class="searchbx_area">
                        <div class="searchbx_cntnt">
                        <ul>
                        <li><a href="#"><img src="images/search_icon.png" width="46" height="43" /></a></li>
                        <li><input class="textfieldcntnt"  name="" type="text" /></li>
                        </ul>
                        </div>
                        
                        </div>-->
                        
                        
                        <div class="clear"></div>
                        <div class="emp_right_contner">
                            <div class="emp_tabwrapper">
								<?php $this->renderPartial('/default/tab');?>
                                <div class="clear"></div>
                                
                                <?php 

								$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
								if(Yii::app()->user->year)
								{
									$year = Yii::app()->user->year;
								}
								else
								{
									$year = $current_academic_yr->config_value;
								}
								$is_insert = PreviousYearSettings::model()->findByAttributes(array('id'=>2));
								$is_edit   = PreviousYearSettings::model()->findByAttributes(array('id'=>3));
								$is_delete = PreviousYearSettings::model()->findByAttributes(array('id'=>4));
								
								if($year != $current_academic_yr->config_value and ($is_insert->settings_value==0 or $is_edit->settings_value==0 or $is_delete->settings_value==0))
								{
								?>
									<div>
										<div class="yellow_bx" style="background-image:none;width:95%;padding-bottom:45px;">
											<div class="y_bx_head" style="width:95%;">
											<?php 
												echo Yii::t('app','You are not viewing the current active year. ');
												if($is_insert->settings_value==0 and $is_edit->settings_value!=0 and $is_delete->settings_value!=0)
												{ 
													echo Yii::t('app','To mark the attendance, enable Create option in Previous Academic Year Settings.');
												}
												elseif($is_insert->settings_value!=0 and $is_edit->settings_value==0 and $is_delete->settings_value!=0)
												{
													echo Yii::t('app','To edit the attendance, enable Edit option in Previous Academic Year Settings.');
												}
												elseif($is_insert->settings_value!=0 and $is_edit->settings_value!=0 and $is_delete->settings_value==0)
												{
													echo Yii::t('app','To delete the attendance, enable Delete option in Previous Academic Year Settings.');
												}
												else
												{
													echo Yii::t('app','To manage the the attendance, enable the required options in Previous Academic Year Settings.');	
												}
											?>
											</div>
											<div class="y_bx_list" style="width:650px;">
												<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
											</div>
										</div>
									</div>
								<?php
								}
								
								?>
                                <div class="formWrapper formWrapper-subwis">
                                
								<?php                                
                                function getweek($date,$month,$year)
                                {
                                $date = mktime(0, 0, 0,$month,$date,$year); 
                                $week = date('w', $date); 
                                switch($week) {
                                case 0: 
                                return 'Su';
                                break;
                                case 1: 
                                return 'Mo';
                                break;
                                case 2: 
                                return 'Tu';
                                break;
                                case 3: 
                                return 'We';
                                break;
                                case 4: 
                                return 'Th';
                                break;
                                case 5: 
                                return 'Fr';
                                break;
                                case 6: 
                                return 'Sa';
                                break;
                                }
                                }
                                ?>
                                <?php
		if(isset($_REQUEST['id']))
		{
		
			$batch= Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
			if($year != $batch->academic_yr_id)
			{
				$this->redirect(array('/attendance'));
			}
			else
			{
			
		
		?>
		
		<div>
			<?php
			Yii::app()->clientScript->registerScript(
			'myHideEffect',
			'$(".flash-success-os").animate({opacity: 1.0}, 3000).fadeOut("slow");',
			CClientScript::POS_READY
			);
			?>
			
			<?php if(Yii::app()->user->hasFlash('notification')):?>
				<span class="flash-success-os" style="color:#F00; padding-left:15px; font-size:12px">
					<?php echo Yii::app()->user->getFlash('notification'); ?>
				</span>
			<?php endif; ?>
			
			
			
			<?php
			 
			$subjects=Subjects::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id']));
			//echo CHtml::dropDownList('batch_id','',CHtml::listData(Subjects::model()->findAll("batch_id=:x",array(':x'=>$_REQUEST['id'])), 'id', 'name'), array('empty'=>'Select Type'));
			
			$model = new EmployeeAttendances;
			
			//month name
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
			//month name
			$num = cal_days_in_month(CAL_GREGORIAN, $mon_num, $curr_year); // 31
			$this_date		= $day;
			?>
		</div> <!-- END div class="ea_droplist" -->
        
    <div class="attnd-tab-sectn-blk">   
        <div class="tab-sectn">
            <ul>
             <?php if(Configurations::model()->studentAttendanceMode() != 2){ ?>
                		<li><?php echo CHtml::link(Yii::t("app","DAY WISE"), array("/attendance/studentAttentance", "id"=>$batch->id), array("class"=>"active-attnd"));?> </li>
                <?php } ?>    
                <?php if(Configurations::model()->studentAttendanceMode() != 1){ ?>
                		<li><?php echo CHtml::link(Yii::t("app","SUBJECT WISE"), array("/attendance/studentSubjectAttendance/daily", "id"=>$batch->id), array("class"=>"sub-attnd-daily"));?> </li>
                <?php } ?>    
            </ul>
    	</div>
    </div>
		
		 <?php
		 
		$displayformat	= 'F Y'; 
		$pickerformat 	= 'M yy';		 
										 
		/********************** GET BATCH DAYS *********************/
		
		$batch_start = date('Y-m-d',strtotime($batch->start_date));
		$batch_end = date('Y-m-d',strtotime($batch->end_date));
		
		/*$temp_begin = date('Y-m',strtotime($batch->start_date));
		$temp_end = date('Y-m',strtotime($batch->end_date));*/
		$days = array();
		$batch_days = array();
		$batch_range = StudentAttentance::model()->createDateRangeArray($batch_start,$batch_end);
		$batch_days = array_merge($batch_days,$batch_range);
			
		
		
		/********** End Subject range ***********/
		
		
		
		$weekArray = array();
		$weekdays = Weekdays::model()->findAll("batch_id=:x AND weekday<>:y", array(':x'=>$batch->id,':y'=>"0"));
		
		if(count($weekdays)==0)
		{
		?>
				<span style="display:block; padding-bottom:6px;">*<?php echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Weekdays not set. System default weekdays will be selected.'); ?></span>
		<?php	
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
		//var_dump($weekArray);
		
		foreach($batch_days as $batch_day)
		{
			$week_number = date('N', strtotime($batch_day));
						
			//echo $day.'='.$week_number.'<br/>';
			if(in_array($week_number,$weekArray)) // If checking if it is a working day
			{
				array_push($days,$batch_day);
			}
		}
		
		//var_dump($days);exit;
		
		
		/********************** END GET BATCH DAYS *********************/
		
												/************** Limit months ****************/
												
												/*
												* Get initial start/end month and year. Used active course
												*/
												
												$begin = date('Y-m',strtotime($batch->start_date));
												$begin_y = date('Y',strtotime($batch->start_date));
												$end = date('Y-m',strtotime($batch->end_date));
												$end_y = date('Y',strtotime($batch->end_date));
												$curr_mon_yr = date("Y-m",strtotime($curr_year."-".$mon_num));
												
												/*echo 'Begin - '.$begin.'<br />';
												echo 'End - '.$end.'<br />';
												*/
												
												
												/************** END Limit months ****************/
										?> 
                                
                                
                                
                                
                                
                                
                                <?php
                                /*if($mon_num=='1')
                                {
                                $mon_num=2;
                                }
                                if($mon_num=='12')
                                {
                                $mon_num=11;
                                }*/
                                ?>

                                

<!--                                <div class="atnd_Con" style="margin:25px 0px 0px -16px; overflow: scroll; width: 961px;">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">-->
                                    
<div class="attnd-tab-inner-blk">
    <div class="attndwise-head">
    	<h3><?php echo Yii::t('app','Day Wise Attendance');?></h3>
    </div>                                   
<div class="pdf-box">
    <div class="box-one">
        <div align="center" class="atnd_table-calender-bg atnd_tnav-new box-one-lft-rght">
			<?php 
				if($curr_mon_yr > $begin)
				{
				echo CHtml::link('<div class="atnd-table-arow-left"><img src="images/attnd-arow-left.png" width="7" border="0"  height="13" /></div>', array('monthlyAttendance', 'mon'=>date("m",strtotime($curr_year."-".$mon_num."-01 -1 months")),'year'=>date("Y",strtotime($curr_year."-".$mon_num."-01 -1 months")),'id'=>$_REQUEST['id'])); 
				}
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
						window.location.href	=  "'.$this->createUrl('studentAttentance/monthlyAttendance', array('id'=>$batch->id)).'" + "&date=" + date;
						}'
				),
				'htmlOptions'=>array(
					'class'=>'atnd_table-cal-input',
							//'style'=>'text-align:center; border:none; left:27px; top:-3px; cursor:pointer;',
							'readonly'=>true
				),
			));
			?>
            </p>
        </div>
			<?php 
				if($curr_mon_yr < $end)
				{
				echo CHtml::link('<div class="atnd-table-arow-right"><img src="images/attnd-arow-right.png" width="7" border="0"  height="13" /></div>', array('monthlyAttendance', 'mon'=>date("m",strtotime($curr_year."-".$mon_num."-01 +1 months")),'year'=>date("Y",strtotime($curr_year."-".$mon_num."-01 +1 months")),'id'=>$_REQUEST['id']));
				}
            ?>
        </div>
        <div class="subwise-blk box-one-lft-rght">
            <ul>
                <li><?php echo CHtml::link(Yii::t("app","Daily"), array("/attendance/studentAttentance/index", "id"=>$batch->id), array("class"=>"sub-attnd-daily"));?> </li>
                <li><?php echo CHtml::link(Yii::t("app","Monthly"), array("/attendance/studentAttentance/monthlyAttendance", "id"=>$batch->id), array("class"=>"sub-attnd-weekly active-attnd"));?></li>
            </ul>
        </div>
    </div>
	
<?php  $posts=Yii::app()->getModule('students')->studentsOfBatch($_REQUEST['id']);

 
		?>	
    <div class="box-two">
         <div class="pdf-div">
            <div class="bttns_addstudent-n">
            <?php  if($posts!=NULL){?>
                <ul>
                    <li>
                             <?php // Button to send SMS
                                $notification=NotificationSettings::model()->findByAttributes(array('id'=>4));
                                    //if($notification->sms_enabled=='1' or $notification->mail_enabled == '1' or $notification->msg_enabled == '1'){ // Checking if SMS or mail or message is enabled
                                
									//if($posts!=NULL){// Check if students is present in the batch. Show SMS button only if there are students in the batch. 
									?>
                                        <div class=""> 
                                        <?php echo CHtml::button(Yii::t('app','Send Notification'), array("submit"=>array('StudentAttentance/sendSms','batch_id'=>$_REQUEST['id'], Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken,), 'csrf'=>true,'class'=>'formbut')); ?>
                                        </div>
									<?php
									//}
                               // }
                                ?>
                    </li>
                    			<li>
                                
                                <div>
                                <?php 
                               
                                if($_REQUEST['mon']&&$_REQUEST['year']){
                                echo CHtml::link(Yii::t('app','Generate PDF'), array('StudentAttentance/pdf','mon'=>$_REQUEST['mon'],'year'=>$_REQUEST['year'],'id'=>$_REQUEST['id']),array('target'=>'_blank','class'=>'pdf_but')); 
                                }
                                else{
                                echo CHtml::link(Yii::t('app','Generate PDF'), array('StudentAttentance/pdf','mon'=>date("m"),'year'=>date("Y"),'id'=>$_REQUEST['id']),array('target'=>'_blank','class'=>'pdf_but')); 
                                
                                }
                                ?>
                                </div> <!-- END div class="ea_pdf" -->
                    </li>
                </ul>
                <?php
			}
			?>
            </div>
         </div>
    </div>
</div>
	<?php 
 	
	if($posts==NULL){ ?>
	     <div class="not-found-box">
			<?php echo '<i class="os_no_found">'.Yii::t("app", "No students Found").'</i>';?>
	 	</div>
	<?php }
			else{ ?>
 				<div class="overflow-table">
							<div class="atnd_Con" id="parent" style="width:920px;" >
                               <table id="fixTable" width="100%" border="0" cellspacing="0" cellpadding="0">
							       <thead>
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
									</thead>
									<tbody>	
										<?php
                                        $j=0;
                                        foreach($posts as $posts_1)
                                        {
											 $batch_student=BatchStudents::model()->findByAttributes(array('student_id'=>$posts_1->id, 'batch_id'=>$posts_1->batch_id, 'status'=>1));
											if($j%2==0)
												$class = 'class="odd"';	
											else
												$class = 'class="even"';	
											
											?>
										<tr <?php echo $class; ?> >
                                          <?php if(Configurations::model()->rollnoSettingsMode() != 2){?>
                                         			<td align="center"><?php if($batch_student!=NULL and $batch_student->roll_no!=0){
																									echo $batch_student->roll_no;
																								}
																								else{
																									echo '-';
																								}?></td>
                                        <?php } ?>
											<td class="name"><?php 
                                                                                        if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile"))
                                                                                        {
                                                                                        $name='';
                                                                                        $name=  $posts_1->studentFullName('forStudentProfile');
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            echo "-";
                                                                                        }
                                                                                        echo $name; ?></td>
											<?php
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
											
											for($i=1;$i<=$num;$i++)
											{
												echo '<td class="abs"><span  id="td'.$i.$posts_1->id.'">';
												
												 //replace ajax.php file................... 
												 
												$day	=	$i;
												$month	=	$mon_num;
												$year	=	$curr_year;
												$emp_id	=	$posts_1->id;
												$find = StudentAttentance::model()->findAll("date=:x AND student_id=:y AND batch_id=:z", array(':x'=>$year.'-'.$month.'-'.$day,':y'=>$emp_id, ':z'=>$_REQUEST['id']));
												
												//mark tick on present..
												$today_day = date('d');
												$today_month = date('n');
												$today_year = date('Y');
												$cell_date = date('Y-m-d',strtotime($year.'-'.$month.'-'.$day));
												$today_date = date('Y-m-d');
												
												
												
												
												if($cell_date <= $today_date and in_array($cell_date,$days) and !in_array($cell_date,$holiday_arr['id']))
												{
													
													$span = '<i class="fa fa-check" style="color:#090"></i>';
												}
												else
												{ ?>
													<span style="display:block; width:100%;  background:#F2F2F2"></span><?php
												}
												
												$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
												if(Yii::app()->user->year)
												{
													$ac_year = Yii::app()->user->year;
												}
												else
												{
													$ac_year = $current_academic_yr->config_value;
												}
												$is_insert = PreviousYearSettings::model()->findByAttributes(array('id'=>2));
												
												
												//check with admission date
												$stud_admission_date	= date("Y-m-d", strtotime($posts_1->admission_date));
												$current_date			= date("Y-m-d", strtotime($year.'-'.$month.'-'.$day));
												if($stud_admission_date<=$current_date){												
													if(count($find)==0)
													{
														if(array_key_exists($cell_date, $holiday_arr))
														{
															$holiday_now = Holidays::model()->findByAttributes(array('id'=>$holiday_arr[$cell_date]));
															
														?>
															<span style="display:block; width:100%; height:40px; background:#D63535" class="holidays" title="<?php echo $holiday_now->title; ?>"></span>
														<?php
														}	
								else if(in_array($cell_date,$days) and !array_key_exists($cell_date, $holiday_arr) and $cell_date <= $today_date )
														{
																if(($ac_year == $current_academic_yr->config_value) or ($ac_year != $current_academic_yr->config_value and $is_insert->settings_value!=0)){
																echo CHtml::ajaxLink($span,$this->createUrl('StudentAttentance/addnew'),array(
																		'onclick'=>'$("#jobDialog'.$day.$emp_id.'").dialog("open"); return false;',
																		'update'=>'#jobDialog123'.$day.$emp_id,'type' =>'GET','data'=>array('day' =>$day,'month'=>$month,'year'=>$year,'emp_id'=>$emp_id, 'batch_id'=>$_REQUEST['id']),
																		
																		),array('id'=>'showJobDialog'.$day.$emp_id,'class'=>'at_abs'));
																			//echo '<div id="jobDialog'.$day.$emp_id.'"></div>';
																}
																else
																{
																	?>
															<span onclick="alert('<?php echo Yii::t('app','Enable Insert Option in Previous Academic Year Settings!'); ?>');" style="display:block;">&nbsp;</span>
																	<?php
																}
														}
														else
														{
														?>
															<span style="display:block; width:100%; height:40px; background:#F2F2F2"></span>
														<?php
														}
													}
													else
													{
														$student_attentance=StudentAttentance::model()->findByAttributes(array('id'=>$find[0]['id']));
														$leave_types=StudentLeaveTypes::model()->findByAttributes(array('id'=>$student_attentance->leave_type_id));
														if($leave_types)
														
														{
															$class1="abs1";
														}
														else
														{
															$class1="abs";
														}
														echo CHtml::ajaxLink('<span class='.$class1.' style="color:'.$leave_types->colour_code.';text-align:center;padding-top:2px">'.$leave_types->label.'</span>',
															$this->createUrl('StudentAttentance/EditLeave'),array(
															'onclick'=>'$("#jobDialog'.$day.$emp_id.'").dialog("open"); return false;',
															'update'=>'#jobDialogupdate'.$day.$emp_id,'type' =>'GET',
															'data'=>array('id'=>$find[0]['id'],
															'day' =>$day,'month'=>$month,'year'=>$year,'emp_id'=>$emp_id)),
															array('id'=>'showJobDialog'.$day.$emp_id,'title'=>Yii::t('app','Reason:').' '.$find['0']['reason']));
														
													
													}
													 //replace ajax.php file...................
												
												}
												else{
												?>
                                                    <span style="display:block; width:100%; height:40px; background:#F2F2F2"></span>
                                                <?php
												}
												
																		
												echo '</span><div  id="jobDialog123'.$i.$posts_1->id.'"></div></td>';
												echo '</span><div  id="jobDialogupdate'.$i.$posts_1->id.'"></div></td>';
											}
											?>
										</tr>
											<?php 
                                            $j++; 
                                        } // END foreach($posts as $posts_1)
                                        ?>
                                    </table>
                                </div> <!-- END div class="atnd_Con" -->
                                </div> 
                                </div>
                                
        		<?php     } 
								} // END if(isset($_REQUEST['id']))                                
                                ?>
                                </div> <!-- END div class="formConInner" -->
                            </div> <!-- END div class="emp_tabwrapper" -->
                        </div> <!-- END div class="emp_right_contner" -->
                    <?php
					} // END $batch!=NULL
					 } ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php?>
<script>
$('.abs').click(function(e) {
    $('form#student-attentance-form').remove();
});
</script>