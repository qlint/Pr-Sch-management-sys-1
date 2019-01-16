<?php
$this->breadcrumbs=array(
	Yii::t('app','Students')=>array('index'),
	Yii::t('app','Attendance'),
);
?>
<script language="javascript">
function getmode(type){
	var student_id	= <?php echo $_REQUEST['id']; ?>;
	var batch_id	= $('#batch_id').val();
		if(student_id != '' && batch_id != ''){
			window.location= 'index.php?r=students/students/attentance&id='+student_id+'&bid='+batch_id;
		}
		else if(student_id != ''){
			window.location= 'index.php?r=students/students/attentance&id='+student_id;
		}
		else{
			window.location= 'index.php?r=students/students/attentance';
		}
}
</script>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
		<div class="emp_cont_left">
	   		<?php $this->renderPartial('profileleft');?>
		</div>
    </td>
    <td valign="top">
    <div class="cont_right formWrapper">
		<h1><?php echo Yii::t('app','Student Profile');?></h1>     
    	<div class="clear"></div>
    	<div class="emp_right_contner">
    		<div class="emp_tabwrapper">
     		<?php $this->renderPartial('application.modules.students.views.students.tab');?>
    		<div class="clear"></div>
 				<div class="emp_cntntbx" >
						<?php 
						$settings		= UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
						$day			= (isset($_REQUEST['date']))?date('Y-m-d', strtotime($_REQUEST['date'])):date("Y-m-d");
						$prev_day		= date('Y-m-d', strtotime('-1 days', strtotime($day)));
						$next_day		= date('Y-m-d', strtotime('+1 days', strtotime($day)));
						$this_date		= $day;
						
						$month_1		=	date("M", strtotime($this_date));
						$month			=	Yii::t('app',$month_1);
						$year			=	date("Y", strtotime($this_date));
						$days			=	date("d", strtotime($this_date));	
						$display_date 	= 	$month.' '.$year.' '.$days;
						
						$batch			= Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
						$begin 			= date('Y-m-d',strtotime($batch->start_date));
						$end			= date('Y-m-d',strtotime($batch->end_date)); 
						if($settings != NULL){
							$displayformat	= $settings->displaydate;
							$pickerformat	= $settings->dateformat;
						}
						else{
							$displayformat	= 'M d Y';
							$pickerformat 	= 'dd-mm-yy';
						}
							
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
						$is_edit = PreviousYearSettings::model()->findByAttributes(array('id'=>3));
						$is_delete = PreviousYearSettings::model()->findByAttributes(array('id'=>4));
				
						if($year != $current_academic_yr->config_value and ($is_insert->settings_value==0 or $is_edit->settings_value==0 or $is_delete->settings_value==0))
						{
						?>
							<div>
								<div class="yellow_bx" style="background-image:none;width:680px;padding-bottom:45px;">
									<div class="y_bx_head" style="width:650px;">
									<?php 
										echo Yii::t('app','You are not viewing the current active year.');
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
											echo Yii::t('app','To manage the attendance, enable the required options in Previous Academic Year Settings.');	
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

							<div class="attndwise-head">
								<h3><?php echo Yii::t("app", 'Day Wise Attendance');?></h3>
							</div>

							<div class="pdf-box">
								<div class="box-one">
									<div class="atnd_table-calender-bg atnd_tnav-new box-one-lft-rght" align="center">
										<?php echo CHtml::link('<div class="atnd-table-arow-left"><img src="'.Yii::app()->request->baseUrl.'/images/attnd-arow-left.png" height="13" width="7" border="0"></div>', array('/students/students/attentance', 'id'=>$batch->id, 'date'=>$prev_day), array('title'=>Yii::t('app', 'Previous Day')));											
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
													window.location.href	=  "'.$this->createUrl('students/attentance', array('id'=>$_REQUEST['id'])).'" + "&date=" + date;
												}'
											),
											'htmlOptions'=>array(
												'class'=>'atnd_table-cal-input',
												//'style'=>'text-align:center; border:none; left:27px; top:-3px; cursor:pointer;',
												'readonly'=>true
											),
										));
										if($next_day<=date('Y-m-d')){
											echo CHtml::link('<div class="atnd-table-arow-right"><img src="'.Yii::app()->request->baseUrl.'/images/attnd-arow-right.png" height="13" width="7" border="0"></div>', array('/attendance/studentAttentance/daily', 'id'=>$batch->id, 'date'=>$next_day), array('title'=>Yii::t('app', 'Next Day')));										
										}															
										?>                                                                      
									</div>
									<div class="subwise-blk box-one-lft-rght">
										<ul>
											<li>
											<?php echo CHtml::link(Yii::t("app","Daily"), array("/attendance/studentAttentance/index", "id"=>$batch_id), array("class"=>"sub-attnd-daily active-attnd"));?> 
											</li>
											<li>
											<?php echo CHtml::link(Yii::t("app","Monthly"), array("/attendance/studentAttentance/monthlyAttendance", "id"=>$batch_id), array("class"=>"sub-attnd-weekly"));?> 
											</li>
										</ul>
									</div>
								</div>
							</div>
							<?php
							if(count($students) == 0){
							?>
							<div class="not-found-box">
							<?php
							echo '<i class="os_no_found">'.Yii::t("app", "No students Found").'</i>';
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
								$batches    = 	BatchStudents::model()->studentBatch($_REQUEST['id']); 
								if($batches){
									foreach($batches as $batch){
										$batch_list[$batch->id]	= ucfirst($batch->name);
									}
								}
								if(count($batches) == 1){
									$batch    	= 	BatchStudents::model()->findByAttributes(array('student_id'=>$_REQUEST['id'], 'result_status'=>0));
									$bid 		=  $batch->batch_id;		
								}
								elseif(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL){
									$bid 		=  $_REQUEST['bid'];	
								}
								elseif(count($batches)>1){ 
									$batch    	= 	BatchStudents::model()->findByAttributes(array('student_id'=>$_REQUEST['id'], 'result_status'=>0, 'batch_id'=>$batches[0]->id));
									$bid 		=  $batch->batch_id;
								}
								echo '<div class="selectbox-student">'.CHtml::dropDownList('bid','',$batch_list,array('id'=>'batch_id','style'=>'width:200px;display: inline; margin-left: 7px;','class'=>'form-control input-sm mb14','options'=>array($bid=>array('selected'=>true)),'onchange'=>'getmode();')).'</div>';
								
							}?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</td>
	</tr>
</table>
