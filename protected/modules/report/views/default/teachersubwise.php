<style type="text/css">

.ui-menu .ui-menu-item a{ color:#000 !important;}

.ui-menu .ui-menu-item a:hover{ color:#fff !important;}

.ui-autocomplete{box-shadow: 0 0 6px #d6d6d6;}

</style>



<?php

$this->breadcrumbs=array(

	Yii::t('app','Report')=>array('/report'),

	Yii::t('app','Teacher Subject Wise Attendance Report'),

);

?>



<table width="100%" border="0" cellspacing="0" cellpadding="0">

    <tr>

        <td width="247" valign="top">

        	<?php $this->renderPartial('left_side');?>

        </td>

        <td valign="top">

        	 <!-- div class="cont_right" --> 

            <div class="cont_right">

                <h1><?php echo Yii::t('app','Teacher Subject Wise Attendance Report');?></h1>

                

                 <!-- DROP DOWNS -->

                 <?php echo CHtml::beginForm(Yii::app()->createUrl('/report/default/teachersubwise'),'get',array('id'=>'teacher-attendance')); ?>

                    <div class="formCon">

                        <div class="formConInner">

                       		<table style=" font-weight:normal;">

                                    	<!-- Row to select department -->

                                        

                                        <tr>

                                        	<td>&nbsp;</td>

                                            <td style="width:200px;"><strong><?php echo Yii::t('app','Select Department');?></strong><span class="required"> *</span></td>

                                            <td>&nbsp;</td>

                                            <td>

                                            <?php

											$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));

											

											$ac_year=$current_academic_yr->config_value;

											

											$departments = EmployeeDepartments::model()->findAll("status =:x", array(':x'=>1));

											

											$department_list = CHtml::listData($departments,'id','name');

											?>

											<?php

											echo CHtml::dropDownList('department_id','',$department_list,array('prompt'=>Yii::t('app','Select Department'),'style'=>'width:190px;',

											'ajax' => array(

											'type'=>'POST',

											'url'=>CController::createUrl('/timetable/teachersTimetable/employeename'),

											'update'=>'#employee_id',

											'data'=>'js:{department_id:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',

											),'options'=>array($_REQUEST['department_id']=>array('selected'=>true))));

											?>

                                            <div id="dept-error" class="required"></div>

											</td>

										</tr>

                                        <tr>

                                        	<td colspan="4">&nbsp;</td>

                                        </tr>

                                          <!-- END Row to select Departments -->

                                           <!-- Row to select employee -->

                                          <tr id="employee_dropdown"  >

                                        	<td>&nbsp;</td>

                                            

                                            <td style="width:200px;"><strong><?php echo Yii::t('app','Select Teacher');?></strong><span class="required"> *</span></td>

                                            <td>&nbsp;</td>

                                           

                                            <td>

                                                <?php 

												if($_REQUEST['department_id'] != NULL and $_REQUEST['department_id'] != '0')

												{

													$employee_names = CHtml::listData(Employees::model()->findAllByAttributes(array('employee_department_id'=>$_REQUEST['department_id'],'is_deleted'=>0),array('order'=>'id DESC')),'id','fullname'); 

													if($employee_names!=NULL){

														$employee_list = CMap::mergeArray(array(0=>Yii::t('app','All Teacher')),$employee_names);

														echo CHtml::dropDownList('employee_id','',$employee_list,array('prompt'=>Yii::t('app','Select Teacher'),'style'=>'width:190px;','options'=>array($_REQUEST['employee_id']=>array('selected'=>true))));

													}

												}

												else

												{																									

													echo CHtml::dropDownList('employee_id','',array(),array('prompt'=>Yii::t('app','Select Teacher'),'style'=>'width:190px;','options'=>array($_REQUEST['employee_id']=>array('selected'=>true))));

												}

                                                ?>

                                                <div id="emp-error" class="required"></div>

                                            </td>  

                                        </tr> 

                                        <tr>

                                        	<td colspan="4">&nbsp;</td>

                                        </tr>

                                        

                                       

                            <tr>

                                <td>&nbsp;</td>

                                <td><strong><?php echo Yii::t('app','Start Date');?></strong><span class="required"> *</span></td>

                                <td>&nbsp;</td>

                                <td>

                                    <?php 

										$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));

										if($settings!=NULL)

										{

											$date=$settings->dateformat;

											

										}

										else

										{

											$date = 'dd-mm-yy';	

										}

										

										 //

					$this->widget('zii.widgets.jui.CJuiDatePicker', array(

											'name' => 'start',

											'value'=>$_REQUEST['start'],

											'options'=>array(

												'yearRange'=>'1970:',

												'dateFormat'=>$date,

												'changeMonth'=> true,

												'changeYear'=>true,

												'dateFormat'=>$date,

											),

											'htmlOptions'=>array(

												//'onChange'=>'js:getstart()',

												'style' => 'width:180px;'

											),

										)); 

						

	 ?>               

     								<div id="start-error" class="required"></div>

     							</td>

                            </tr>

                            

                            <tr>

                                        	<td colspan="4">&nbsp;</td>

                                        </tr>

                                        

                                       

                            <tr>

                                <td>&nbsp;</td>

                                <td><strong><?php echo Yii::t('app','End Date');?></strong><span class="required"> *</span></td>

                                <td>&nbsp;</td>

                                <td>

                                    <?php

								$this->widget('zii.widgets.jui.CJuiDatePicker', array(

											'name' => 'end',

											'value'=>$_REQUEST['end'],

											'options'=>array(

												'yearRange'=>'1970:',

												'dateFormat'=>$date,

												'changeMonth'=> true,

												'changeYear'=>true,

												'dateFormat'=>$date,

											),

											'htmlOptions'=>array(

												//'onChange'=>'js:getend()',

												'style' => 'width:180px;'

											),

										));  ?>

                                        <div id="end-error" class="required"></div>        

                         </td>

                     </tr>

                     <tr>

                     	<td colspan="4">&nbsp;</td>

                     </tr>   

                     <tr>

                     	<td colspan="4"><?php echo CHtml::submitButton(Yii::t('app','Search'), array('class'=>'formbut', 'id'=>'search_btn')); ?></td>

                     </tr>

                     

                  </table>

               </div>

            </div>

		<?php echo CHtml::endForm(); ?>            

                 <!-- END DROP DOWNS -->

                <!-- REPORT SECTION -->

                 

               <?php

			    if(isset($_REQUEST['department_id']) and isset($_REQUEST['employee_id']) and isset($_REQUEST['start']) and isset($_REQUEST['end']) and $_REQUEST['department_id']!=NULL and $_REQUEST['employee_id']!=NULL and $_REQUEST['start']!=NULL and $_REQUEST['end']!=NULL) {

               if($_REQUEST['employee_id'] == 0){

						$employees = Employees::model()->findAllByAttributes(array('is_deleted'=>0, 'employee_department_id'=>$_REQUEST['department_id']));	

					 }else{

						 $employees = Employees::model()->findAllByAttributes(array('id'=>$_REQUEST['employee_id'],'is_deleted'=>0));	

					 }

							if($employees!=NULL) // If employee  present

							{

							?>

                            	<h3> <?php  echo Yii::t('app','Teacher Subject Wise Attendance Report');?></h3>

                                <div class="pdf-box">

                                    <div class="box-two">

                                    	<?php echo CHtml::link(Yii::t('app','Generate PDF'), array('/report/default/teacherpdf','department_id'=>$_REQUEST['department_id'], 'employee_id'=>$_REQUEST['employee_id'], 'start'=>$_REQUEST['start'], 'end'=>$_REQUEST['end']),array('target'=>"_blank",'class'=>'pdf_but')); ?>                                                        

                                    </div>

                                    <div class="box-one">

                                    </div>

                                </div>                              

                            

                            <div class="tablebx">

                            	<table width="100%" border="0" cellspacing="0" cellpadding="0">

                                    <tr class="tablebx_topbg">

                                        <td><?php echo Yii::t('app','Sl No');?></td>

                                        <td><?php echo Yii::t('app','Teacher No');?></td>

                                        <td><?php echo Yii::t('app','Joining Date');?></td>

                                        <td><?php echo Yii::t('app','Name');?></td>

                                        <td><?php echo Yii::t('app','Job Title');?></td>

                                        <td><?php echo Yii::t('app','Total leaves per classes');?></td>

                                        <td><?php echo Yii::t('app','Total leaves per hours');?></td>

                                    </tr>

                                    <?php

									$overall_sl = 1;

									foreach($employees as $employee)

									{

										

									?>

                                    <tr>

                                    	<td style="padding-top:10px; padding-bottom:10px;"><?php echo $overall_sl; $overall_sl++;?></td>

                                        <td><?php echo $employee->employee_number; ?></td>

                                         <td>

										 	<?php if($employee->joining_date!=NULL)

													{

														$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));

														if($settings!=NULL)

														{	

															$employee->joining_date = date($settings->displaydate,strtotime($employee->joining_date));

														}

														echo $employee->joining_date; 

													}

													else

													{

														echo '-';

													}

											 ?>

										</td>

                                        <td>

											<?php echo CHtml::link(ucfirst($employee->first_name).' '.ucfirst($employee->middle_name).' '.ucfirst($employee->last_name),array('/employees/employees/view','id'=>$employee->id));?>

										</td>

                                         <td>

											<?php if($employee->job_title!=NULL){ echo $employee->job_title; } else{ echo '-';}?>

										</td>

                                        <td>

              								<?php

											$subwises = TeacherSubjectwiseAttentance::model()->findAllByAttributes(array('employee_id'=>$employee->id));

										

											$start_date  = date('Y-m-d',strtotime($_REQUEST['start']));

											if($start_date < date('Y-m-d', strtotime($employee->joining_date))){

												$start_date	= date('Y-m-d', strtotime($employee->joining_date));

											}											

											$end_date  = date('Y-m-d',strtotime($_REQUEST['end']));

											$leave_count = 0;

											foreach($subwises as $subwise){

												if($start_date <= $subwise->date and $subwise->date <= $end_date){

													$leave_count  = 	$leave_count+1;

													

												}

											}

											echo $leave_count.'/';

											

												$total_entry_count	= 0;

												for($w=1;$w<=7;$w++){

													$criteria				= new CDbCriteria();

													$criteria->condition	= 'employee_id=:employee_id AND weekday_id=:weekday_id';

													$criteria->params		= array(':employee_id'=>$employee->id, ':weekday_id'=>$w); 

													$criteria->group		= 'class_timing_id';

													$entries = TimetableEntries::model()->findAll($criteria);													

													if(count($entries)>0){														

														$entry_count	= count($entries);														

														$weekday 		= $w-1;

														if($weekday==0) $weekday=7;

														

														$start_date  = date('Y-m-d',strtotime($_REQUEST['start']));

														if($start_date < date('Y-m-d', strtotime($employee->joining_date))){

															$start_date	= date('Y-m-d', strtotime($employee->joining_date));

														}

														$end_date  = date('Y-m-d',strtotime($_REQUEST['end']));

															

														$daycount	= 0;

														$start 		= new DateTime($start_date);

														$end   		= new DateTime($end_date);

														$end->modify('+1 day');														

														$interval 	= DateInterval::createFromDateString('1 day');

														$period 	= new DatePeriod($start, $interval, $end);

														foreach ($period as $dt){

															if ($dt->format('N') == $weekday){

																$day	=	$dt->format('Y-m-d');
																$is_holiday		= StudentAttentance::model()->isHoliday($day);	
																if(!$is_holiday){
																	$daycount++;
																}

															}

														}

														

														$total_entry_count	+= ($daycount * $entry_count);														

													}

												}

												echo $total_entry_count;

											?>                           

                                		</td>

                                       <td>
              								<?php
											$subwises = TeacherSubjectwiseAttentance::model()->findAllByAttributes(array('employee_id'=>$employee->id));
											$start_date  = date('Y-m-d',strtotime($_REQUEST['start']));
											if($start_date < date('Y-m-d', strtotime($employee->joining_date))){
												$start_date	= date('Y-m-d', strtotime($employee->joining_date));
											}
											$end_date  = date('Y-m-d',strtotime($_REQUEST['end']));
											$leave_minutes = 0;
											foreach($subwises as $subwise){
												if($start_date <= $subwise->date and $subwise->date <= $end_date){
													$timetable = TimetableEntries::model()->findByAttributes(array('id'=>$subwise->timetable_id));
													if($timetable){
														$class_timing = ClassTimings::model()->findByAttributes(array('id'=>$timetable->class_timing_id));
													}
													if($class_timing!=NULL){
														$minute_diff = (strtotime($class_timing->end_time) - strtotime($class_timing->start_time))/60;
														$leave_minutes	+= $minute_diff;
													}
												}
											}
											$leave_hour = floor($leave_minutes/60);
											$leave_mins = $leave_minutes%60;	
												$total_minutes	= 0;
												for($w=1;$w<=7;$w++){
													$criteria				= new CDbCriteria();
													$criteria->condition	= 'employee_id=:employee_id AND weekday_id=:weekday_id';
													$criteria->params		= array(':employee_id'=>$employee->id, ':weekday_id'=>$w); 
													$criteria->group		= 'class_timing_id';
													$time_tables = TimetableEntries::model()->findAll($criteria);
													if($time_tables){
														foreach($time_tables as $time_table)
														{
															$class_timing = ClassTimings::model()->findByAttributes(array('id'=>$time_table->class_timing_id));
															if($class_timing!=NULL){
																$minute_diff		= (strtotime($class_timing->end_time) - strtotime($class_timing->start_time))/60;
															}
															$weekday 		= $w-1;
															if($weekday==0) $weekday=7;
																$start_date  = date('Y-m-d',strtotime($_REQUEST['start']));
																if($start_date < date('Y-m-d', strtotime($employee->joining_date))){
																	$start_date	= date('Y-m-d', strtotime($employee->joining_date));
																}
																$end_date  = date('Y-m-d',strtotime($_REQUEST['end']));
															$daycount	= 0;
															$start 		= new DateTime($start_date);
															$end   		= new DateTime($end_date);
															$end->modify('+1 day');
															$interval 	= DateInterval::createFromDateString('1 day');
															$period 	= new DatePeriod($start, $interval, $end);													
															foreach ($period as $dt){
																if ($dt->format('N') == $weekday){
																	$day	=	$dt->format('Y-m-d');
																	$is_holiday		= StudentAttentance::model()->isHoliday($day);	
																	if(!$is_holiday){
																		$daycount++;
																	}
																}
															}
															$days = $daycount;
															$total_minutes += ($daycount * $minute_diff);
														}
													}
												}
												$hour = floor($total_minutes/60);
												$mins = $total_minutes%60;
												echo $leave_hour.'h'.$leave_mins.' / '.$hour.'h'.$mins;	
											?>                           

                                		</td>

                                    </tr>

                                    <?php

									

									} // end employee foreach

									?>

								</table>

                            </div>

                            

						<?php

					} // END If employee is present

					

				} // END If(isset())

                ?>

              

                <!-- END REPORT SECTION -->

               

            </div>

             <!-- END div class="cont_right" -->

        </td>

    </tr>

</table>

<script type="text/javascript">

$('#search_btn').click(function(ev){

	$('#dept-error').html('');

	$('#emp-error').html('');

	$('#start-error').html('');

	$('#end-error').html('');

	var dept	= $('#department_id').val();

	var emp		= $('#employee_id').val();

	var start	= $('#start').val();

	var end		= $('#end').val();

	var flag	= 0;

	if(dept == ''){

		var flag	= 1;

		$('#dept-error').html('<?php echo Yii::t('app','Department cannot be blank'); ?>');

	}

	if(emp == ''){

		var flag	= 1;

		$('#emp-error').html('<?php echo Yii::t('app','Teacher cannot be blank'); ?>');

	}

	if(start == ''){

		var flag	= 1;

		$('#start-error').html('<?php echo Yii::t('app','Start Date cannot be blank'); ?>');

	}

	if(end == ''){

		var flag	= 1;

		$('#end-error').html('<?php echo Yii::t('app','End Date cannot be blank'); ?>');

	}

	if(start != '' && end != ''){		

		var start_date 	= new Date($('#start').val());

		var end_date 	= new Date($('#end').val());

		var today		= new Date();		

		if(start_date > end_date){

			var flag	= 1;

			$('#end-error').html('<?php echo Yii::t('app', 'End Date should be greater than Start Date'); ?>');			

		}

		else if(end_date > today){

			var flag	= 1;

			$('#end-error').html('<?php echo Yii::t('app', 'End Date should be less than or equal to Current Date'); ?>');	

		}

	}

	if(flag == 1){

		return false;

	}

})

</script>

