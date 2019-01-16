<style type="text/css">
.ui-menu .ui-menu-item a{ color:#000 !important;}
.ui-menu .ui-menu-item a:hover{ color:#fff !important;}
.ui-autocomplete{box-shadow: 0 0 6px #d6d6d6;}
.pdf-box {    
    margin-top: 0;   
}
</style>

<script language="javascript">

function updatemode() // Function to get the dependent dropdown after selecting department
{
	var dep_id = document.getElementById('dep_id').value;
	if(dep_id != ''){
		window.location= 'index.php?r=report/default/employeeattendance&dep='+dep_id;	
	}
	else{
		window.location= 'index.php?r=report/default/employeeattendance';	
	}
}

function getmode() // Function to get the dependent dropdown after selecting mode
{
	  var dep_id = document.getElementById('dep_id').value;
	  var mode_id = document.getElementById('mode_id').value;
	  if(dep_id != '' && mode_id == '') // Some year is selected
	  {
		  window.location= 'index.php?r=report/default/employeeattendance&dep='+dep_id;
	  }
		
	var mode_id = document.getElementById('mode_id').value;
	if(mode_id == 1) // Overall
	{
		var dep_id = document.getElementById('dep_id').value;
		document.getElementById("filler").style.display="none";
		document.getElementById("year").style.display="none";
		document.getElementById("month").style.display="none";
		document.getElementById("individual").style.display="none";
		window.location= 'index.php?r=report/default/employeeattendance&dep='+dep_id+'&mode='+mode_id;
	}
	else if(mode_id == 2) // Yearly
	{
		document.getElementById("filler").style.display="table-row";
		document.getElementById("year").style.display="table-row";
		document.getElementById("month").style.display="none";
		document.getElementById("individual").style.display="none";
		
	}
	else if(mode_id == 3) // Monthly
	{
		document.getElementById("filler").style.display="table-row";
		document.getElementById("year").style.display="none";
		document.getElementById("month").style.display="table-row";
		document.getElementById("individual").style.display="none";
	}
	else if(mode_id == 4) // Individual
	{
		document.getElementById("filler").style.display="table-row";
		document.getElementById("year").style.display="none";
		document.getElementById("month").style.display="none";
		document.getElementById("individual").style.display="table-row";
	}
	else
	{
		document.getElementById("filler").style.display="none";
		document.getElementById("year").style.display="none";
		document.getElementById("month").style.display="none";
		document.getElementById("individual").style.display="none";
	}
	
}

function getyearreport() // Function to get yearly report
{
	var dep_id = document.getElementById('dep_id').value;
	var mode_id = document.getElementById('mode_id').value;
	var year_value = document.getElementById('year_value').value;
	window.location= 'index.php?r=report/default/employeeattendance&dep='+dep_id+'&mode='+mode_id+'&year='+year_value;
	
}

function getmonthreport() // Function to get monthly report
{
	var dep_id = document.getElementById('dep_id').value;
	var mode_id = document.getElementById('mode_id').value;
	var month_value = document.getElementById('month_value').value;
	month_value = month_value.replace(/(^\s+|\s+$)/g,'');
	window.location= 'index.php?r=report/default/employeeattendance&dep='+dep_id+'&mode='+mode_id+'&month='+month_value;
	
}

</script>

<?php
$this->breadcrumbs=array(
	Yii::t('app','Report')=>array('/report'),
	Yii::t('app','Teacher Attendance Report'),
);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student-form',
	'enableAjaxValidation'=>false,
)); ?>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('left_side');?>
        </td>
        <td valign="top"> 
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Teacher Attendance Report');?></h1>
                <!-- DROP DOWNS -->
                <div class="formCon">
                    <div class="formConInner">
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" class="s_search">
                            <tr>
                                <td>&nbsp;</td>
                                <td><strong><?php echo Yii::t('app','Select Department');?></strong></td>
                                <td>&nbsp;</td>
                                <td>
									<?php /*?><?php 
                                    echo CHtml::dropDownList('dep_id','',CHtml::listData(EmployeeDepartments::model()->findAll(),'id','name'),array('prompt'=>'Select Department','options'=>array($dep_id=>array('selected'=>true)),'id'=>'dep_id','submit'=>array('/report/default/employeeattendance')));
                                    ?><?php */?>
                                    <?php 
									$department_list = CHtml::listData(EmployeeDepartments::model()->findAll(),'id','name');
									/*echo CHtml::dropDownList('dep_id','',$department_list,array('prompt'=>'Select Department','id'=>'dep_id','style'=>'width:160px;','options'=>array($dep_id=>array('selected'=>true))));*/
									echo CHtml::dropDownList('dep_id','',$department_list,array('prompt'=>Yii::t('app','Select Department'),'style'=>'width:177px;','onchange'=>'updatemode()','id'=>'dep_id','options'=>array($_REQUEST['dep']=>array('selected'=>true)))); 
									?>
                                </td>
                            </tr>
                            <tr>
                            	<td colspan="4">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><strong><?php echo Yii::t('app','Select Mode');?></strong></td>
                                <td>&nbsp;</td>
                                <td>
									<?php
									if(isset($_REQUEST['dep']) and $_REQUEST['dep']!=NULL)
									{
										echo CHtml::dropDownList('mode_id','',array('1'=>Yii::t('app','Overall'),'2'=>Yii::t('app','Yearly'),'3'=>Yii::t('app','Monthly'),'4'=>Yii::t('app','Individual')),array('prompt'=>Yii::t('app','Select Mode'),'style'=>'width:177px;','onchange'=>'getmode()','id'=>'mode_id','options'=>array($_REQUEST['mode']=>array('selected'=>true)))); 
									}
									else
									{
										echo CHtml::dropDownList('mode_id','','',array('prompt'=>Yii::t('app','Select Mode'),'style'=>'width:177px;','onchange'=>'getmode()','id'=>'mode_id')); 
									}
                                   /* echo CHtml::dropDownList('dep_id','',array('1'=>'Overall','2'=>'Yearly','3'=>'Monthly'),array('prompt'=>'Select Mode','style'=>'width:160px;','options'=>array($dep_id=>array('selected'=>true)),'id'=>'mode_id','submit'=>array('/report/default/employeeattendance')));*/
                                    ?>
                                </td>
                            </tr>
                            
                             <?php
							if($_REQUEST['mode']==2)
							{
								$year_style = "display:table-row";
								$month_style = "display:none";
								$individual_style = "display:none";
								$filler_style = "display:table-row";
							}
							elseif($_REQUEST['mode']==3)
							{
								$year_style = "display:none";
								$month_style = "display:table-row";
								$individual_style = "display:none";
								$filler_style = "display:table-row";
							}
							elseif($_REQUEST['mode']==4)
							{
								$year_style = "display:none";
								$month_style = "display:none";
								$individual_style = "display:table-row";
								$filler_style = "display:table-row";
							}
							else
							{
								$year_style = "display:none";
								$month_style = "display:none";
								$individual_style = "display:none";
								$filler_style = "display:none";
							}
							?>
                            
                            <tr id="filler" style=" <?php echo $filler_style; ?> ">
                            	<td colspan="4">&nbsp;</td>
                            </tr>
                           
                            <!-- ROW TO SELECT YEAR -->
                             <tr id="year" style=" <?php echo $year_style; ?> ">
                                <td>&nbsp;</td>
                                <td><strong><?php echo Yii::t('app','Select Year');?></strong></td>
                                <td>&nbsp;</td>
                                <td>
                                	<?php
									$yearNow = date("Y",strtotime('+5 years'));
									$yearFrom = $yearNow - 20;
									$arrYears = array();
									foreach (range($yearFrom, $yearNow) as $number) 
									{
										 $arrYears[$number] = $number; 
									 }
									 
									$arrYears = array_reverse($arrYears, true);
											 
									echo CHtml::dropDownList('year','',$arrYears,array('prompt'=>Yii::t('app','Select Year'),'style'=>'width:177px;','id'=>'year_value','onchange'=>'getyearreport()','options'=>array($_REQUEST['year']=>array('selected'=>true))));
									?>
                                </td>
                            </tr>
                            <!-- END ROW TO SELECT YEAR -->
                            
                            <!-- ROW TO SELECT MONTH -->
                            <tr id="month" style=" <?php echo $month_style; ?> ">
                                <td>&nbsp;</td>
                                <td><strong><?php echo Yii::t('app','Select Month');?></strong></td>
                                <td>&nbsp;</td>
                                <td>
                                    <?php 
										$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
										if($settings!=NULL)
										{
											$date=str_ireplace("d","",$settings->dateformat);
											
										}
										else
										{
											$date = 'm-y';
										}
										$this->widget('ext.EJuiMonthPicker.EJuiMonthPicker', array(
											'name' => 'month_year',
											'value'=>$_REQUEST['month'],
											'options'=>array(
												'yearRange'=>'-20:',
												'dateFormat'=>$date,
											),
											'htmlOptions'=>array(
												'onChange'=>'js:getmonthreport()',
												'id' => 'month_value',
											),
										));  
									?>
                                </td>
                            </tr>
                             <!-- END ROW TO SELECT MONTH -->
                             
                             <!-- ROW TO SELECT INDIVIDUAL -->
                             <tr id="individual" style=" <?php echo $individual_style; ?> ">
                                <td>&nbsp;</td>
                                <td><strong><?php echo Yii::t('app','Teacher ');?></strong></td>
                                <td>&nbsp;</td>
                                <td>
                                    <?php 
										$employee_name	= '';
										if(isset($_REQUEST['employee']) and $_REQUEST['employee'] != NULL){
											$employee_detail = Employees::model()->findByPk($_REQUEST['employee']);
											if($employee_detail){
												$employee_name = Employees::model()->getTeachername($employee_detail->id);
											}
										}
										$this->widget('zii.widgets.jui.CJuiAutoComplete',
											array(
											  'name'=>'name',
											  'id'=>'individual_value',
											  'source'=>$this->createUrl('/site/employeeautocomplete',array('dep'=>$_REQUEST['dep'])),
											  'value'=>$employee_name,
											  'htmlOptions'=>array('placeholder'=>Yii::t('app','Teacher Name')),
											  'options'=>
												 array(
													   'showAnim'=>'fold',
													   'select'=>"js:function(employee,ui){
														   var dep_id = document.getElementById('dep_id').value;
															var mode_id = document.getElementById('mode_id').value;
															var individual_value = ui.item.id;
															individual_value = individual_value.replace(/(^\s+|\s+$)/g,'');
															window.location= 'index.php?r=report/default/employeeattendance&dep='+dep_id+'&mode='+mode_id+'&employee='+individual_value;
														 }"
														),
										
											));
									?>
                                </td>
                            </tr>
                             <!-- END ROW TO SELECT INDIVIDUAL -->
                        </table>
                    </div> <!-- END div class="formConInner" -->
                </div><!--  END div class="formCon" -->
                 <!-- END DROP DOWNS -->
                 
                 
                 
                 <!-- REPORT SECTION -->
                <?php
                if(isset($_REQUEST['dep']) and $_REQUEST['dep']!=NULL) // Checking if department is selected
                {
					$employees = Employees::model()->findAll("employee_department_id=:x and is_deleted=:y", array(':x'=>$_REQUEST['dep'],':y'=>0));
					if($employees!=NULL) // If employees are present
					{
						if(isset($_REQUEST['mode']) and $_REQUEST['mode']==1) // Checking if mode == 1 (Overall Report)
						{
						?>
							<h3><?php echo Yii::t('app','Overall Teacher Attendance Report');?></h3>
                            <!-- Overall PDF -->                           
                            <div class="pdf-box">
                                <div class="box-two">
                                    <?php echo CHtml::link(Yii::t('app', 'Generate PDF'), array('/report/default/empoverallpdf','id'=>$_REQUEST['dep']),array('target'=>"_blank",'class'=>'pdf_but','class'=>'pdf_but')); ?>
                                </div>
                                <div class="box-one">
                                </div>
                            </div>
                            
                            <!-- END Overall PDF -->
                            <!-- Overall Report Table -->
                            <div class="tablebx">
                            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr class="tablebx_topbg">
                                        <td><?php echo Yii::t('app','Sl No');?></td>
                                        <td><?php echo Yii::t('app','Teacher No');?></td>
                                        <td><?php echo Yii::t('app','Joining Date');?></td>
                                        <td><?php echo Yii::t('app','Name');?></td>
                                        <td><?php echo Yii::t('app','Job Title');?></td>
                                        <td><?php echo Yii::t('app','Leaves');?></td>
                                    </tr>
                                     <?php
									$overall_sl = 1;
									foreach($employees as $employee) // Displaying each employee row.
									{
									?>
                                    <tr>
                                    	<td style="padding-top:10px; padding-bottom:10px;"><?php echo $overall_sl; $overall_sl++;?></td>
                                        <td><?php echo $employee->employee_number; ?></td>
                                         <td>
										 	<?php 
											if($employee->joining_date!=NULL)
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
											<?php echo CHtml::link(ucfirst($employee->first_name).'  '.ucfirst($employee->middle_name).'  '.ucfirst($employee->last_name),array('/employees/employees/view','id'=>$employee->id));?>
										</td>
                                        <td>
											<?php
											if($employee->job_title!=NULL)
											{
												echo ucfirst($employee->job_title);
											}
											else
											{
												echo '-';
											}
											?>
										</td>
                                        <!-- Overall Attendance column -->
                                        <td>
                                        	<?php
											$leaves = EmployeeAttendances::model()->findAllByAttributes(array('employee_id'=>$employee->id));
											$emp_leave = 0;
											foreach($leaves as $leave)
											{
												if($leave->is_half_day == 1)
												{
													$emp_leave = $emp_leave + 0.5;
												}
												else
												{
													$emp_leave++;
												}
											}
											echo $emp_leave;
											?>
                                        </td>
                                        <!-- End overall Attendance column -->
                                    </tr>
                                    <?php
									}
									?>
								</table>
                            </div>
                            <!-- END Overall Report Table -->
                            
						<?php
						} // END Checking if mode == 1 (Overall Report)
						elseif(isset($_REQUEST['mode']) and $_REQUEST['mode']==2) // Checking if mode == 2 (Yearly Report)
						{
						?>
							<h3><?php echo Yii::t('app','Yearly Teacher Attendance Report').' - '.$_REQUEST['year'];?></h3>
    
                            <div class="pdf-box">
                                <div class="box-two">
                                    <?php echo CHtml::link(Yii::t('app', 'Generate PDF'), array('/report/default/empyearlypdf','id'=>$_REQUEST['dep'],'year'=>$_REQUEST['year']),array('target'=>"_blank",'class'=>'pdf_but')); ?>
                                </div>
                                <div class="box-one">
                                </div>
                            </div>

                            
                            <div class="tablebx">
                            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr class="tablebx_topbg">
                                        <td><?php echo Yii::t('app','Sl No');?></td>
                                        <td><?php echo Yii::t('app','Teacher No');?></td>
                                        <td><?php echo Yii::t('app','Name');?></td>
                                        <td><?php echo Yii::t('app','Job Title');?></td>
                                        <td><?php echo Yii::t('app','Leaves');?></td>
                                    </tr>
                                    <?php
									$yearly_sl = 1;
									foreach($employees as $employee) // Displaying each employee row.
									{
										$is_na = '';
										$joining_yr = date('Y', strtotime($employee->joining_date));
										if($joining_yr > $_REQUEST['year']){
											$is_na = Yii::t('app','N/A');
										}
										
									?>
                                    <tr>
                                    	<td style="padding-top:10px; padding-bottom:10px;"><?php echo $yearly_sl; $yearly_sl++;?></td>
                                        <td><?php echo $employee->employee_number; ?></td>
                                        <td>
											<?php echo CHtml::link(ucfirst($employee->first_name).'  '.ucfirst($employee->middle_name).'  '.ucfirst($employee->last_name),array('/employees/employees/view','id'=>$employee->id));?>
										</td>
                                        <td>
											<?php
											if($employee->job_title!=NULL)
											{
												echo ucfirst($employee->job_title);
											}
											else
											{
												echo '-';
											}
											?>
										</td>
                                         <!-- Yearly Attendance column -->
                                        <td>
                                        	<?php
											if($is_na == ''){
												$attendances = EmployeeAttendances::model()->findAllByAttributes(array('employee_id'=>$employee->id));
												$required_year = $_REQUEST['year'];
												//$joining_year = date('Y',strtotime($employee->joining_date));
												//if($required_year >= $joining_year)
												//{
												$leaves = 0;
												foreach($attendances as $attendance)
												{
													$attendance_year = date('Y',strtotime($attendance->attendance_date));
													if($attendance_year == $required_year)
													{
														if($attendance->is_half_day)
														{
															$leaves = $leaves + 0.5;
														}
														else
														{
															$leaves++;
														}
													}
												}
												echo $leaves;
											}
											else{
												echo $is_na;
											}
											
											?>
                                        </td>
                                        <!-- End Yearly Attendance column -->
                                    </tr>
                                    <?php
									}
									?>
								</table>
                            </div>
                            <!-- END Yearly Report Table -->
						<?php
						} // END Checking if mode == 2 (Yearly Report)
						elseif(isset($_REQUEST['mode']) and $_REQUEST['mode']==3) // Checking if mode == 3 (Monthly Report)
						{
						?>
							<h3><?php echo Yii::t('app','Monthly Teacher Attendance Report').' - '.$_REQUEST['month'];?></h3>
                            <!-- Monthly PDF -->
                            
                          	<div class="pdf-box">
                                <div class="box-two">
                                    <?php echo CHtml::link(Yii::t('app', 'Generate PDF'), array('/report/default/empmonthlypdf','id'=>$_REQUEST['dep'],'month'=>$_REQUEST['month']),array('target'=>"_blank",'class'=>'pdf_but')); ?>
                                </div>
                                <div class="box-one">
                                </div>
                            </div>

                            <!-- END Monthly PDF -->
                            <!-- Monthly Report Table -->
                            <div class="tablebx">
                            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr class="tablebx_topbg">
                                        <td><?php echo Yii::t('app','Sl No');?></td>
                                        <td><?php echo Yii::t('app','Teacher No');?></td>
                                        <td><?php echo Yii::t('app','Name');?></td>
                                        <td><?php echo Yii::t('app','Job Title');?></td>
                                        <td><?php echo Yii::t('app','Leaves');?></td>
                                    </tr>
                                     <?php
															
									$monthly_sl = 1;
									foreach($employees as $employee) // Displaying each employee row.
									{										
									?>
                                    <tr>
                                    	<td style="padding-top:10px; padding-bottom:10px;"><?php echo $monthly_sl; $monthly_sl++;?></td>
                                        <td><?php echo $employee->employee_number; ?></td>
                                        <td>
											<?php echo CHtml::link(ucfirst($employee->first_name).'  '.ucfirst($employee->middle_name).'  '.ucfirst($employee->last_name),array('/employees/employees/view','id'=>$employee->id));?>
										</td>
                                        <td>
											<?php	
																	
											if($employee->job_title!=NULL)
											{
												echo ucfirst($employee->job_title);
											}
											else
											{
												echo '-';
											}
											?>
										</td>
                                         <!-- Monthly Attendance column -->
                                        <td>
                                        	<?php
											$attendances = EmployeeAttendances::model()->findAllByAttributes(array('employee_id'=>$employee->id));
											$required_month = date('Y-m',strtotime($_REQUEST['month']));
											$joining_month = date('Y-m',strtotime($employee->joining_date));
											if($required_month >= $joining_month)
											{
												$leaves = 0;
												foreach($attendances as $attendance)
												{
													$attendance_month = date('Y-m',strtotime($attendance->attendance_date));
													if($attendance_month == $required_month)
													{
														if($attendance->is_half_day)
														{
															$leaves = $leaves + 0.5;
														}
														else
														{
															$leaves++;
														}
													}
												}
												echo $leaves;
											}
											else
											{
												echo Yii::t('app','N/A');
											}
											?>
                                        </td>
                                        <!-- End Monthly Attendance column -->
                                    </tr>
                                    <?php
									}
									?>
								</table>
                            </div>
                            <!-- END Monthly Report Table -->
						<?php
						} // END Checking if mode == 3 (Monthly Report)
						elseif(isset($_REQUEST['mode']) and $_REQUEST['mode']==4) // Checking if mode == 4 (Individual Report)
						{
                        	$individual = Employees::model()->findByAttributes(array('id'=>$_REQUEST['employee'],'employee_department_id'=>$_REQUEST['dep'],'is_deleted'=>0));
						?>
	                        <h3><?php echo Yii::t('app','Individual Teacher Attendance Report');?></h3>
                        <?php
							if($individual!=NULL) // Checking if employee present in the department selected
							{
						?>
                                
                                <!-- Individual PDF -->                                
                                <div class="pdf-box">
                                    <div class="box-two">
                                        <?php echo CHtml::link(Yii::t('app', 'Generate PDF'), array('/report/default/empindividualpdf','id'=>$_REQUEST['dep'],'employee'=>$_REQUEST['employee']),array('target'=>"_blank",'class'=>'pdf_but')); ?>
                                    </div>
                                    <div class="box-one">
                                    </div>
                                </div>
                                <!-- END Individual PDF -->
                                <!-- Individual Details -->
                                <div class="formCon">
                                    <div class="formConInner">
                                        <table>
                                            <tr>
                                                <td style="width:100px;">
                                                    <strong><?php echo Yii::t('app','Name'); ?></strong>
                                                </td>
                                                <td style="width:10px;">
                                                    <strong>:</strong>
                                                </td>
                                                <td style="width:200px;">
                                                    <?php echo CHtml::link(ucfirst($individual->first_name).'  '.ucfirst($individual->middle_name).'  '.ucfirst($individual->last_name),array('/employees/employees/view','id'=>$individual->id));?>
                                                </td>
                                                <td style="width:110px;">
                                                    <strong><?php echo Yii::t('app','Teacher Number'); ?></strong>
                                                </td>
                                               <td style="width:10px;">
                                                    <strong>:</strong>
                                                </td>
                                                <td style="width:200px;">
                                                    <?php echo $individual->employee_number; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong><?php echo Yii::t('app','Job Title'); ?></strong>
                                                </td>
                                               <td>
                                                    <strong>:</strong>
                                                </td>
                                                <td>
                                                    <?php 
                                                    if($individual->job_title!=NULL)
                                                    {
                                                        echo ucfirst($individual->job_title);
                                                    }
                                                    else
                                                    {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <strong><?php echo Yii::t('app','Joining Date'); ?></strong>
                                                </td>
                                               <td>
                                                    <strong>:</strong>
                                                </td>
                                                <td>
                                                    <?php 
                                                    if($individual->joining_date!=NULL)
                                                    {
                                                        $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                                                        if($settings!=NULL)
                                                        {	
                                                            $individual->joining_date=date($settings->displaydate,strtotime($individual->joining_date));
                                                        }
                                                        echo $individual->joining_date; 
                                                    }
                                                    else
                                                    {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6">&nbsp;</td>
                                            </tr>
                                            <tr>
                                            	<td>
                                                    <strong><?php echo Yii::t('app','Leaves Taken'); ?></strong>
                                                </td>
                                               <td>
                                                    <strong>:</strong>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $leaves = EmployeeAttendances::model()->findAll('employee_id=:x ORDER BY attendance_date ASC',array(':x'=>$individual->id));
													$emp_leave = 0;
													foreach($leaves as $leave)
													{
														if($leave->is_half_day == 1)
														{
															$emp_leave = $emp_leave + 0.5;
														}
														else
														{
															$emp_leave++;
														}
													}
													echo $emp_leave;
													
													
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <!-- END Individual Details -->                            
                                
                                <!-- Individual Report Table -->
                                <div class="tablebx">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr class="tablebx_topbg">
                                            <td><?php echo Yii::t('app','Sl No');?></td>
                                            <td><?php echo Yii::t('app','Leave Date');?></td>
                                            <td><?php echo Yii::t('app','Half Day');?></td>
                                            <td><?php echo Yii::t('app','Reason');?></td>
                                        </tr>
                                        <?php
										if($leaves!=NULL)
										{
											$individual_sl = 1;
											foreach($leaves as $leave) // Displaying each leave row.
											{
											?>
											<tr>
												<td style="padding-top:10px; padding-bottom:10px;"><?php echo $individual_sl; $individual_sl++;?></td>
												 <!-- Individual Attendance row -->
												<td>
													<?php 
													$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
													if($settings!=NULL)
													{	
														$leave->attendance_date=date($settings->displaydate,strtotime($leave->attendance_date));
													}
													echo $leave->attendance_date; 
													?>
												</td>
                                                <td>
                                                	<?php
													if($leave->is_half_day == 1)
													{
														echo Yii::t('app','Yes');
													}
													else
													{
														echo Yii::t('app','No');
													}
													?>
                                                </td>
												<td>
													<?php
													if($leave->reason!=NULL)
													{
														echo $leave->reason;
													}
													else
													{
														echo '-';
													}
													?>
												</td>
												<!-- End Individual Attendance row -->
											</tr>
											<?php
											}
											
										}
										else
										{
										?>
											<tr>
												<td colspan="4" style="padding-top:10px; padding-bottom:10px;">
													<strong><?php echo Yii::t('app','No leaves taken!'); ?></strong>
												</td>
											</tr>
										<?php
										}
										?>
                                    </table>
                                </div>
                                <!-- END Individual Report Table -->
						<?php
							} //END Checking if employee present in the department selected
							else
							{
						?>
								<div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;">
									<div class="y_bx_head">
										<?php echo Yii::t('app','No such teacher present in this department. Try searching other departments.'); ?>
									</div>      
								</div>
						<?php
							}
						} // END Checking if mode == 3 (Monthly Report)
						else // If no mode is set
						{
						
						} // END If no mode is set
						
					} // END If employees is present
					else
					{
					?>
						<div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;">
							<div class="y_bx_head">
								<?php echo Yii::t('app','No teacher present in this department. Try searching other departments.'); ?>
							</div>      
						</div>
					<?php
					}
					   
				} // END Checking if department is selected
                    ?>
               <!-- END REPORT SECTION -->
                    
            </div> <!-- END div class="cont_right" -->
        </td>
    </tr>
</table>
 <?php $this->endWidget(); ?>