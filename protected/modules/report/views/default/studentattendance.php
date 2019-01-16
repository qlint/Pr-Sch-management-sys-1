<?php
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
?>
<style type="text/css">
.ui-menu .ui-menu-item a{ color:#000 !important;}
.ui-menu .ui-menu-item a:hover{ color:#fff !important;}
.ui-autocomplete{box-shadow: 0 0 6px #d6d6d6;}
.pdf-box {    
    margin-top: 0;   
}
</style>
<script>
function resetall() // Function to reset the search form
{
	document.getElementById("filler").style.display="none";
	document.getElementById("year").style.display="none";
	document.getElementById("month").style.display="none";
	document.getElementById("individual").style.display="none";
	document.getElementById("mode_id").length=1;
}


function updatemode() // Function to update mode dependent dropdown after selecting batch
{
	
	var course_id = document.getElementById('cid').value;
	var batch_id = document.getElementById('batchid').value;
	var sem_id = document.getElementById('semester_id').value;
	window.location= 'index.php?r=report/default/studentattendance&cid='+course_id+'&bid='+batch_id+'&sid='+sem_id;	
}

function getmode() // Function to get the dependent dropdown after selecting mode
{
	var mode_id = document.getElementById('mode_id').value;
	if(mode_id == 1) // Overall
	{
		var course_id = document.getElementById('cid').value;
		var batch_id = document.getElementById('batchid').value;
		var sem_id = document.getElementById('semester_id').value;
		document.getElementById("filler").style.display="none";
		document.getElementById("year").style.display="none";
		document.getElementById("month").style.display="none";
		document.getElementById("individual").style.display="none";
		window.location= 'index.php?r=report/default/studentattendance&cid='+course_id+'&bid='+batch_id+'&mode='+mode_id+'&sid='+sem_id;	
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
	var sem_id = document.getElementById('semester_id').value;
	var course_id = document.getElementById('cid').value;
	var batch_id = document.getElementById('batchid').value;
	var mode_id = document.getElementById('mode_id').value;
	var year_value = document.getElementById('year_value').value;
	window.location= 'index.php?r=report/default/studentattendance&cid='+course_id+'&bid='+batch_id+'&mode='+mode_id+'&year='+year_value+'&sid='+sem_id;	
	
}

function getmonthreport() // Function to get monthly report
{
	var sem_id = document.getElementById('semester_id').value;
	var course_id = document.getElementById('cid').value;
	var batch_id = document.getElementById('batchid').value;
	var mode_id = document.getElementById('mode_id').value;
	var month_value = document.getElementById('month_value').value;
	month_value = month_value.replace(/(^\s+|\s+$)/g,'');
	window.location= 'index.php?r=report/default/studentattendance&cid='+course_id+'&bid='+batch_id+'&mode='+mode_id+'&month='+month_value+'&sid='+sem_id;	
	
}
</script>

<?php
$this->breadcrumbs=array(
	Yii::t('app','Report')=>array('/report'),
	Yii::t('app','Student Attendance Report'),
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
        	 <!-- div class="cont_right" --> 
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Student Attendance Report');?></h1>
                 <!-- DROP DOWNS -->
                <div class="formCon">
                    <div class="formConInner">
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" class="s_search">
                            <tr>

                                <td style="width:200px;"><strong><?php echo Yii::t('app','Select Course');?></strong></td>

                                <?php
                                $model=new Courses;
                                $criteria = new CDbCriteria;
                                $criteria->compare('is_deleted',0); ?>
                                <td> 
									<?php
									$current_academic_yr = Configurations::model()->findByPk(35);
									$data = Courses::model()->findAllByAttributes(array('is_deleted'=>0,'academic_yr_id'=>$current_academic_yr->config_value),array('order'=>'id DESC'));  
                                    echo CHtml::dropDownList('cid','',CHtml::listData($data,'id','course_name'),array('encode'=>false, 'prompt'=>Yii::t('app','Select Course'),'style'=>'width:190px;',
                                    'ajax' => array(
                                    'type'=>'POST',
                                    'url'=>CController::createUrl('/report/default/semesters'),   
									'dataType'=>'JSON',                                
								   'beforeSend'=>'js:function(){    
							                                                                                 
										$("#semester_id").find("option").not(":first").remove();
										$("#batchid").find("option").not(":first").remove();
										$("#sem_div").hide();
										$("#sem_d").hide();
										$("#sem_tr").hide();
									}', 
									'success'=>'js:function(response){
									if(response.status=="success")
									{ 
										if(response.sem_status=="1")
										{
											$("#sem_div").show();
											$("#sem_d").show();
											$("#sem_tr").show();
											$("#semester_id").html(response.semester);
										}
											$("#batchid").html(response.batch);
									}
										
									}',
                                   'data'=>'js:{cid:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',
                                    ),'options'=>array($_REQUEST['cid']=>array('selected'=>true)),'onchange'=>'resetall()'));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                            	<td colspan="2">&nbsp;</td>
                            </tr>
                            <?php 
							$disp_status='none';
							if(isset($_GET['cid']) && $_GET['cid']!=NULL)
							{
								$sem_enabled= Configurations::model()->isSemesterEnabledForCourse($_GET['cid']); 
								if($sem_enabled==1)
								{
									$disp_status='block';
								}
							} 
							?>
                           
                            <tr>                                 
							
                                	<td style="width:200px;">
                                    <div class="" style="display:<?php echo $disp_status; ?>; padding-right: 10px" id="sem_div">
                                 	   <strong><?php echo Yii::t('app','Select Semester'); ?></strong>
                                    </div>
                                    </td>

                                   <td>
                                  <div class="" style="display:<?php echo $disp_status; ?>; padding-right: 10px" id="sem_d">
										<?php   
                                    if((isset($_GET['cid']) && $_GET['cid']!=NULL))
                                    {
                                        $criteria=new CDbCriteria;
                                        $criteria->join= 'JOIN semester_courses `sc` ON t.id = `sc`.semester_id';
                                        $criteria->condition='`sc`.course_id =:course_id';
                                        $criteria->params=array(':course_id'=>$_GET['cid']);
                                        $data	= Semester::model()->findAll($criteria);			
                                        $data	= CHtml::listData($data, 'id', 'name');	
										$data_list 		= CMap::mergeArray(array(0=>Yii::t('app','Batch without semester')),$data);
									
                                    }
                                    else
                                    {
                                        $data =  array();
                                    }
									$sid	=	$_REQUEST['sid'];
                                    echo CHtml::dropDownList('semester_id',(isset($_GET['sid']))?$_GET['sid']:'',$data_list,array('prompt'=>Yii::t('app','Select'),
									'style'=>'width:190px;',
                                    'ajax' => array(
                                    'type'=>'POST',
                                    'url'=>CController::createUrl('/report/default/sembatches'),
                                    'update'=>'#batchid',
                                    'beforeSend'=>'js:function(){   
									                                                                                            
                                                $("#batchid").find("option").not(":first").remove();
                                                
                                    }', 
                                    'data'=>'js:{course_id:$("#cid").val(), semester_id:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',
                                    ),
                                  //  'disabled'=>(isset($_POST['action']) and ($_POST['action']==-1 or $_POST['action']==1))?false:true,
                                    //'style'=>'width:170px;',
                                    'id'=>'semester_id',
                                    'options' => array($sid=>array('selected'=>true))));?>  
                                 </div>
                                     
                             </td> 
                             </div>
                            </tr>
                         
                            <tr>
                           <td colspan="2"> <div class="" style="display:<?php echo $disp_status; ?>; padding-right: 10px" id="sem_tr">
                            	&nbsp;
                                </div></td>
                            </tr>
                            
                            <tr>
                                
                                <td><strong><?php echo Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id");?></strong></td>

                                <td>
                                    <?php  
                                   // echo CHtml::dropDownList('batch_id','',array(),array('prompt'=>'Select Batch','id'=>'batchid','submit'=>array('/report/default/studentattendance')));
								   	if(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL)
									{ 
										if(isset($_REQUEST['sid']) && $_REQUEST['sid']!=NULL)
										{ 
										    if($_REQUEST['sid']== 0)
											{
												$criteria=new CDbCriteria;
												$criteria->condition='course_id =:course_id AND is_deleted=0 AND is_active=1 AND academic_yr_id=:year';
												$criteria->params=array(':course_id'=>$_REQUEST['cid'],':year'=>$current_academic_yr->config_value);
												$criteria->addCondition('semester_id IS NULL');
												$data	= Batches::model()->findAll($criteria);
												$batch_list	= CHtml::listData($data, 'id', 'name');	
											}
											else
											{
											$batch_list = CHtml::listData(Batches::model()->findAllByAttributes(array('course_id'=>$_REQUEST['cid'],'semester_id'=>$_REQUEST['sid'],'is_active'=>1,'is_deleted'=>0)),'id','name');
											}
										}else
										{
											$batch_list = CHtml::listData(Batches::model()->findAllByAttributes(array('course_id'=>$_REQUEST['cid'],'is_active'=>1,'is_deleted'=>0)),'id','name');
										}
										echo CHtml::dropDownList('batch_id','',$batch_list,array('encode'=>false,'prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),'id'=>'batchid','style'=>'width:190px;','options'=>array($_REQUEST['bid']=>array('selected'=>true)),'onchange'=>'updatemode()'));
									}
									else
									{
										echo CHtml::dropDownList('batch_id','',array(),array('prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),'id'=>'batchid','style'=>'width:190px;','onchange'=>'updatemode()'));
									}
                                    ?>
                                </td>
                            </tr>
                            <tr>
                            	<td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>

                                <td><strong><?php echo Yii::t('app','Select Mode');?></strong></td>
                                <td>
									<?php
									if(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL)
									{
										echo CHtml::dropDownList('mode_id','',array('1'=>Yii::t('app','Overall'),'2'=>Yii::t('app','Yearly'),'3'=>Yii::t('app','Monthly'),'4'=>Yii::t('app','Individual')),array('prompt'=>Yii::t('app','Select Mode'),'style'=>'width:190px;','onchange'=>'getmode()','id'=>'mode_id','options'=>array($_REQUEST['mode']=>array('selected'=>true)))); 
									}
									else
									{
										echo CHtml::dropDownList('mode_id','','',array('prompt'=>Yii::t('app','Select Mode'),'style'=>'width:190px;','id'=>'mode_id')); 
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
                            	<td colspan="2">&nbsp;</td>
                            </tr>
                           
                            <!-- ROW TO SELECT YEAR -->
                             <tr id="year" style=" <?php echo $year_style; ?> ">
                                <td><strong><?php echo Yii::t('app','Select Year');?></strong></td>
                                <td>
                                	<?php
									$yearNow = date("Y");
									$yearFrom = $yearNow - 20;
									if(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL){
										$batch = Batches::model()->findByPk($_REQUEST['bid']);
										$years = array();
										$start_year = date('Y',strtotime($batch->start_date));
										$end_year = date('Y',strtotime($batch->end_date));
										
										for($i=$start_year;$i<=$end_year;$i++)
											$years[$i] = $i;
										$arrYears = $years;
									}else{
										$arrYears = array();
										foreach (range($yearFrom, $yearNow) as $number) 
										{
											$arrYears[$number] = $number; 
										}
										$arrYears = array_reverse($arrYears, true);
									}
									
											 
									echo CHtml::dropDownList('year','',$arrYears,array('prompt'=>Yii::t('app','Select Year'),'style'=>'width:190px;','id'=>'year_value','onchange'=>'getyearreport()','options'=>array($_REQUEST['year']=>array('selected'=>true))));
									?>
                                </td>
                            </tr>
                            <!-- END ROW TO SELECT YEAR -->
                            
                            <!-- ROW TO SELECT MONTH -->
                            <tr id="month" style=" <?php echo $month_style; ?> ">
                                
                                <td><strong><?php echo Yii::t('app','Select Month');?></strong></td> 
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
										
										if(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL){
											$batch = Batches::model()->findByPk($_REQUEST['bid']);
											$years = array();
											$start_year = date('Y',strtotime($batch->start_date));
											$end_year = date('Y',strtotime($batch->end_date));											
										}
										else{
											$start_year = date('Y')-5;
											$end_year	= date('Y')+5;
										}
										
										$this->widget('ext.EJuiMonthPicker.EJuiMonthPicker', array(
											'name' => 'month_year',
											'value'=>$_REQUEST['month'],
											'options'=>array(
												'yearRange'=>$start_year.':'.$end_year,
												'dateFormat'=>$date,
											),
											'htmlOptions'=>array(
												'onChange'=>'js:getmonthreport()',
												'id' => 'month_value',
												'style' => 'width:180px;',
												'readonly' => true
											),
										));  
									?>
                                </td>
                            </tr>
                             <!-- END ROW TO SELECT MONTH -->
                             
                             <!-- ROW TO SELECT INDIVIDUAL -->
                             <tr id="individual" style=" <?php echo $individual_style; ?> ">
                        
                                <td><strong><?php echo Yii::t('app','Select Student');?></strong></td> 
                                <td>
                                    <?php  
										$student_name = '';
										if(isset($_REQUEST['student']) and $_REQUEST['student'] != NULL){
											$std	= Students::model()->findByPk($_REQUEST['student']);
											if($std){
												$student_name = $std->studentFullName('forStudentProfile');
											}
										}
										$this->widget('zii.widgets.jui.CJuiAutoComplete',
											array(
											  'name'=>'name',
											  'id'=>'individual_value',
											  'source'=>$this->createUrl('/site/autocomplete'),
											  'htmlOptions'=>array('placeholder'=>Yii::t('app','Student Name'),'style' => 'width:180px;'),
											  'value'=>$student_name,
											  'options'=>
												 array(
													   'showAnim'=>'fold',
													   'select'=>"js:function(student,ui){
														   var course_id = document.getElementById('cid').value;
														   var sem_id = document.getElementById('semester_id').value;
															var batch_id = document.getElementById('batchid').value;
															var mode_id = document.getElementById('mode_id').value;
															var individual_value = ui.item.id;
															individual_value = individual_value.replace(/(^\s+|\s+$)/g,'');
															window.location= 'index.php?r=report/default/studentattendance&cid='+course_id+'&sid='+sem_id+'&bid='+batch_id+'&mode='+mode_id+'&student='+individual_value;
														 }"
														),
										
											));
									?>
                                </td>
                            </tr>
                             <!-- END ROW TO SELECT INDIVIDUAL -->
                            
                        </table>
                    </div>
                </div>
                 <!-- END DROP DOWNS -->
                 
                 
                 <!-- REPORT SECTION -->
                 
                 <?php
                if(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL) // Checking if batch is selected
                {
					$students = Yii::app()->getModule('students')->studentsOfBatch($_REQUEST['bid']);
					if($students!=NULL) // If students are present
					{
						if(isset($_REQUEST['mode']) and $_REQUEST['mode']==1) // Checking if mode == 1 (Overall Report)
						{
						?>
							<h3><?php echo Yii::t('app','Overall Student Attendance Report');?></h3>
                            <!-- Overall PDF -->
                            <div class="pdf-box">
                                <div class="box-two">
                                    <?php echo CHtml::link(Yii::t('app','Generate PDF'), array('/report/default/studentoverallpdf','id'=>$_REQUEST['bid']),array('target'=>"_blank",'class'=>'pdf_but')); ?>
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
                                        <td><?php echo Yii::t('app','Adm No');?></td>
                                        <td><?php echo Yii::t('app','Admission Date');?></td>
                                        <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                        <td><?php echo Yii::t('app','Name');?></td>
                                        <?php } ?>
                                        <td><?php echo Yii::t('app','Working Days');?></td>
                                        <td><?php echo Yii::t('app','Leaves');?></td>
                                    </tr>
                                     <?php
									$overall_sl = 1;
									foreach($students as $student) // Displaying each student row.
									{
									?>
                                    <tr>
                                    	<td style="padding-top:10px; padding-bottom:10px;"><?php echo $overall_sl; $overall_sl++;?></td>
                                        <td><?php echo $student->admission_no; ?></td>
                                         <td>
										 	<?php 
											if($student->admission_date!=NULL)
											{
												$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
												$admission_date	= $student->admission_date;
												if($settings!=NULL)
												{	
													$admission_date = date($settings->displaydate,strtotime($student->admission_date));
												}
												echo $admission_date; 
											}
											else
											{
												echo '-';
											}
											?>
										</td>
                                        <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                        <td>
											<?php echo CHtml::link($student->studentFullName("forStudentProfile"),array('/students/students/view','id'=>$student->id));?>
										</td>
                                        <?php }?>
                                        <td>
											<?php																																	
												$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));	
																	
												if($student->admission_date>=$batch->start_date){ 
													$start_date  	= date('Y-m-d',strtotime($student->admission_date));												
												}
												else{
													$start_date  	= date('Y-m-d',strtotime($batch->start_date));
												}													
									
												if($batch->end_date >= date('Y-m-d')){
													$end_date		= date('Y-m-d');												
												}
												else{
													$end_date		= date('Y-m-d', strtotime($batch->end_date));
												}
												
									
												$batch_days_1  = array();
												$batch_range_1 = StudentAttentance::model()->createDateRangeArray($start_date,$end_date);  // to find total session
												$batch_days_1  = array_merge($batch_days_1,$batch_range_1);
												
												$days = array();
												$days_1 = array();
												$weekArray = array();
												
												$total_working_days_1 = array();
												$weekdays = Weekdays::model()->findAll("batch_id=:x AND weekday<>:y", array(':x'=>$batch->id,':y'=>"0"));
												if(count($weekdays)==0)
												{
													
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
									
									
									
												foreach($batch_days_1 as $batch_day_1)
												{
													$week_number = date('N', strtotime($batch_day_1));
													if(in_array($week_number,$weekArray)) // If checking if it is a working day
													{
														array_push($days_1,$batch_day_1);
													}
												}
												$holiday_arr[] =array();
												$ischeck = Configurations::model()->findByPk(43);
												
												if($ischeck->config_value != 1)
												{
													$holidays = Holidays::model()->findAll();
													$holiday_arr=array();
													foreach($holidays as $key=>$holiday)
													{
														if(date('Y-m-d',$holiday->start)!=date('Y-m-d',$holiday->end))
														{
															$date_range = StudentAttentance::model()->createDateRangeArray(date('Y-m-d',$holiday->start),date('Y-m-d',$holiday->end));
															foreach ($date_range as $value) {
																$holiday_arr[] = date('Y-m-d',$date_range);
															}
														}
														else
														{
															$holiday_arr[] = date('Y-m-d',$holiday->start);
														}
													}
												}
												
												
												foreach($days_1 as $day_1)
												{
													
													if(!in_array($day_1,$holiday_arr)) // If checking if it is a working day
													{
														array_push($total_working_days_1,$day_1);
													}
												}
						  
												
												echo count($total_working_days_1);	
											?>
										</td>
                                        <!-- Overall Attendance column -->
                                        <td>
                                        	<?php
											$leavedays 				= array();
											$criteria 				= new CDbCriteria;		
											$criteria->join 		= 'LEFT JOIN student_leave_types t1 ON (t.leave_type_id = t1.id OR t.leave_type_id = 0)'; 
											$criteria->condition 	= 't1.is_excluded=:is_excluded AND t.student_id=:x AND t.date >=:z AND t.date <=:A AND t.batch_id=:batch_id';											
											$criteria->params 		= array(':is_excluded'=>0,':x'=>$student->id,':z'=>$start_date,':A'=>$end_date, 'batch_id'=>$batch->id);
											$criteria->order		= 't.date DESC';
											$leaves    				= StudentAttentance::model()->findAll($criteria);
											
											foreach($leaves as $leave){
												if(!in_array($leave->date,$leavedays)){
													array_push($leavedays,$leave->date);
												}
											}
											echo count($leavedays);
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
							<h3><?php echo Yii::t('app','Yearly Student Attendance Report').' - '.$_REQUEST['year'];?></h3>
                            <!-- Yearly PDF -->
                            <div class="pdf-box">
                                <div class="box-two">
                                    <?php echo CHtml::link(Yii::t('app','Generate PDF'), array('/report/default/studentyearlypdf','id'=>$_REQUEST['bid'],'year'=>$_REQUEST['year']),array('target'=>"_blank",'class'=>'pdf_but')); ?>
                                </div>
                                <div class="box-one">
                                </div>
                            </div>                            
                            <!-- END Yearly PDF -->
                            <!-- Yearly Report Table -->
                            <div class="tablebx">
                            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr class="tablebx_topbg">
                                        <td><?php echo Yii::t('app','Sl No');?></td>
                                        <td><?php echo Yii::t('app','Adm No');?></td>
                                        <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                        <td><?php echo Yii::t('app','Name');?></td>
                                        <?php } ?>
                                        <td><?php echo Yii::t('app','Working Days');?></td>
                                        <td><?php echo Yii::t('app','Leaves');?></td>
                                    </tr>
                                    <?php
									$yearly_sl 	= 1;
									$yr_start_date	= $_REQUEST['year'].'-01-01';
									$yr_end_date	= $_REQUEST['year'].'-12-31';
									
									foreach($students as $student) // Displaying each employee row.
									{
									?>
                                    <tr>
                                    	<td style="padding-top:10px; padding-bottom:10px;"><?php echo $yearly_sl; $yearly_sl++;?></td>
                                        <td><?php echo $student->admission_no; ?></td>
                                        <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                        <td>
											<?php echo CHtml::link($student->studentFullName("forStudentProfile"),array('/students/students/view','id'=>$student->id));?>
										</td>
                                        <?php }?>
                                        <td>
											<?php
											
												$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));	
												
												$start_date		= $yr_start_date;
												if($start_date < $batch->start_date){
													$start_date 	= date('Y-m-d',strtotime($batch->start_date));
												}
												if($start_date < $student->admission_date){
													$start_date	= date('Y-m-d',strtotime($student->admission_date));
												}
																																									
												$end_date		= $yr_end_date;
												if($end_date > $batch->end_date){
													$end_date  	= date('Y-m-d',strtotime($batch->end_date));
												}
												if($end_date > date('Y-m-d')){
													$end_date	= date('Y-m-d');  
												}
												
												$batch_days_1  = array();
												$batch_range_1 = StudentAttentance::model()->createDateRangeArray($start_date,$end_date);  // to find total session
												$batch_days_1  = array_merge($batch_days_1,$batch_range_1);
												
												$days = array();
												$days_1 = array();
												$weekArray = array();
												
												$total_working_days_1 = array();
												$weekdays = Weekdays::model()->findAll("batch_id=:x AND weekday<>:y", array(':x'=>$batch->id,':y'=>"0"));
												if(count($weekdays)==0)
												{
													
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
									
									
									
												foreach($batch_days_1 as $batch_day_1)
												{
													$week_number = date('N', strtotime($batch_day_1));
													if(in_array($week_number,$weekArray)) // If checking if it is a working day
													{
														array_push($days_1,$batch_day_1);
													}
												}
												$holiday_arr[] =array();
												$ischeck = Configurations::model()->findByPk(43);
												
												if($ischeck->config_value != 1)
												{
													$holidays = Holidays::model()->findAll();
													$holiday_arr=array();
													foreach($holidays as $key=>$holiday)
													{
														if(date('Y-m-d',$holiday->start)!=date('Y-m-d',$holiday->end))
														{
															$date_range = StudentAttentance::model()->createDateRangeArray(date('Y-m-d',$holiday->start),date('Y-m-d',$holiday->end));
															foreach ($date_range as $value) {
																$holiday_arr[] = date('Y-m-d',$date_range);
															}
														}
														else
														{
															$holiday_arr[] = date('Y-m-d',$holiday->start);
														}
													}
												}
												
												
												foreach($days_1 as $day_1)
												{
													
													if(!in_array($day_1,$holiday_arr)) // If checking if it is a working day
													{
														array_push($total_working_days_1,$day_1);
													}
												}
						  
												
												echo count($total_working_days_1);	
																															
                                            ?>
                						</td>
                                         <!-- Yearly Attendance column -->
                                        <td>
                                        <?php
											$leavedays 				= array();
											$criteria 				= new CDbCriteria;		
											$criteria->join 		= 'LEFT JOIN student_leave_types t1 ON (t.leave_type_id = t1.id OR t.leave_type_id = 0)'; 
											$criteria->condition 	= 't1.is_excluded=:is_excluded AND t.student_id=:x AND t.date >=:z AND t.date <=:A AND t.batch_id=:batch_id';											
											$criteria->params 		= array(':is_excluded'=>0,':x'=>$student->id,':z'=>$start_date,':A'=>$end_date, 'batch_id'=>$batch->id);
											$criteria->order		= 't.date DESC';
											$leaves    				= StudentAttentance::model()->findAll($criteria);
											$required_year = $_REQUEST['year'];
											$l = 0; 
											foreach($leaves as $leave){
												$attendance_year = date('Y',strtotime($leave->date));
												if($attendance_year == $required_year)
												{
													if(!in_array($leave->date,$leavedays)){
														array_push($leavedays,$leave->date);
															$l++; 
													}
												
												}
											}
											echo $l;
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
							<h3><?php echo Yii::t('app','Monthly Student Attendance Report').' - '.$_REQUEST['month'];?></h3>
                            <!-- Monthly PDF -->
                            <div class="pdf-box">
                                <div class="box-two">
                                    <?php echo CHtml::link(Yii::t('app','Generate PDF'), array('/report/default/studentmonthlypdf','id'=>$_REQUEST['bid'],'month'=>$_REQUEST['month']),array('target'=>"_blank",'class'=>'pdf_but')); ?>
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
                                        <td><?php echo Yii::t('app','Adm No');?></td>
                                        <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                        <td><?php echo Yii::t('app','Name');?></td>
                                        <?php } ?>
                                        <td><?php echo Yii::t('app','Working Days');?></td>
                                        <td><?php echo Yii::t('app','Leaves');?></td>
                                    </tr>
                                     <?php
									$monthly_sl = 1;
									$requiredmonth 	= date('m',strtotime($_REQUEST['month']));
									$requiredyear 	= date('Y',strtotime($_REQUEST['month']));
									$number 		= cal_days_in_month(CAL_GREGORIAN, $requiredmonth, $requiredyear);									
									$yr_start_date	= $requiredyear."-".$requiredmonth."-01";
									$yr_end_date	=  $requiredyear."-".$requiredmonth."-".$number;
									
									foreach($students as $student) // Displaying each employee row.
									{
									?>
                                    <tr>
                                    	<td style="padding-top:10px; padding-bottom:10px;"><?php echo $monthly_sl; $monthly_sl++;?></td>
                                        <td><?php echo $student->admission_no; ?></td>
                                        <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                        <td>
											<?php echo CHtml::link($student->studentFullName("forStudentProfile"),array('/students/students/view','id'=>$student->id));?>
										</td>
                                        <?php }?>
                                         <!-- Monthly Attendance column -->
                                        <td>
                                        	<?php
												$student_details=Students::model()->findByAttributes(array('id'=>$student->id)); 
												$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));								
												
												$start_date		= $yr_start_date;
												if($start_date < $batch->start_date){
													$start_date 	= date('Y-m-d',strtotime($batch->start_date));
												}
												if($start_date < $student->admission_date){
													$start_date	= date('Y-m-d',strtotime($student->admission_date));
												}
																																									
												$end_date		= $yr_end_date;
												if($end_date > $batch->end_date){
													$end_date  = date('Y-m-d',strtotime($batch->end_date));
												}
												if($end_date > date('Y-m-d')){
													$end_date	= date('Y-m-d');
												}
												
												$batch_days_1  = array();
												$batch_range_1 = StudentAttentance::model()->createDateRangeArray($start_date,$end_date);  // to find total session
												$batch_days_1  = array_merge($batch_days_1,$batch_range_1);
												
												$days = array();
												$days_1 = array();
												$weekArray = array();
												
												$total_working_days_1 = array();
												$weekdays = Weekdays::model()->findAll("batch_id=:x AND weekday<>:y", array(':x'=>$batch->id,':y'=>"0"));
												if(count($weekdays)==0)
												{
													
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
									
									
									
												foreach($batch_days_1 as $batch_day_1)
												{
													$week_number = date('N', strtotime($batch_day_1));
													if(in_array($week_number,$weekArray)) // If checking if it is a working day
													{
														array_push($days_1,$batch_day_1);
													}
												}
												$holiday_arr[] =array();
												$ischeck = Configurations::model()->findByPk(43);
												
												if($ischeck->config_value != 1)
												{
													//$holidays = Holidays::model()->findAllByAttributes(array('user_id'=>Yii::app()->user->Id));
													$holidays = Holidays::model()->findAll();
													$holiday_arr=array();
													foreach($holidays as $key=>$holiday)
													{
														if(date('Y-m-d',$holiday->start)!=date('Y-m-d',$holiday->end))
														{
															$date_range = StudentAttentance::model()->createDateRangeArray(date('Y-m-d',$holiday->start),date('Y-m-d',$holiday->end));
															foreach ($date_range as $value) {
																$holiday_arr[] = date('Y-m-d',$date_range);
															}
														}
														else
														{
															$holiday_arr[] = date('Y-m-d',$holiday->start);
														}
													}
												}
												
												
												foreach($days_1 as $day_1)
												{
													
													if(!in_array($day_1,$holiday_arr)) // If checking if it is a working day
													{
														array_push($total_working_days_1,$day_1);
													}
												}
						  
												
												echo count($total_working_days_1);	
											?>
                                        </td> 
                                        <td>
                                        <?php
											$leavedays 				= array();
											$criteria 				= new CDbCriteria;		
											$criteria->join 		= 'LEFT JOIN student_leave_types t1 ON (t.leave_type_id = t1.id OR t.leave_type_id = 0)'; 
											$criteria->condition 	= 't1.is_excluded=:is_excluded AND t.student_id=:x AND t.date >=:z AND t.date <=:A AND t.batch_id=:batch_id';											
											$criteria->params 		= array(':is_excluded'=>0,':x'=>$student->id,':z'=>$start_date,':A'=>$end_date, 'batch_id'=>$batch->id);
											$criteria->order		= 't.date DESC';
											$leaves    				= StudentAttentance::model()->findAll($criteria);
											$required_month			= date('Y-m',strtotime($_REQUEST['month']));
											$l = 0; 
											foreach($leaves as $leave){ 
												$attendance_month = date('Y-m',strtotime($leave->date));
												if($attendance_month == $required_month)
												{
													if(!in_array($leave->date,$leavedays)){
														array_push($leavedays,$leave->date);
															$l++; 
													}
												
												}
											}
											echo $l;
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
							$batch_stu  = BatchStudents::model()->findByAttributes(array('student_id'=>$_REQUEST['student'],'batch_id'=>$_REQUEST['bid'],'result_status'=>0));
							
							if($batch_stu!=NULL){
                        		$individual = Students::model()->findByAttributes(array('id'=>$_REQUEST['student'],'is_active'=>1,'is_deleted'=>0));
							}
							$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));	
						?>
	                        <h3><?php echo Yii::t('app','Individual Student Attendance Report');?></h3>
                        <?php
							if($individual!=NULL) // Checking if employee present in the department selected
							{
						?>
                                
                                <!-- Individual PDF -->
                                <div class="pdf-box">
                                    <div class="box-two">
                                        <?php echo CHtml::link(Yii::t('app','Generate PDF'), array('/report/default/studentindividualpdf','id'=>$_REQUEST['bid'],'student'=>$_REQUEST['student']),array('target'=>"_blank",'class'=>'pdf_but')); ?>
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
                                            	<?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                                <td style="width:100px;">
                                                    <strong><?php echo Yii::t('app','Name'); ?></strong>
                                                </td>
                                                <td style="width:10px;">
                                                    <strong>:</strong>
                                                </td>
                                                <td style="width:200px;">
                                                    <?php echo CHtml::link($individual->studentFullName("forStudentProfile"),array('/students/students/view','id'=>$individual->id));?>
                                                </td>
                                                
                                                <td style="width:110px;">
                                                    <strong><?php echo Yii::t('app','Admission Number'); ?></strong>
                                                </td>
                                               <td style="width:10px;">
                                                    <strong>:</strong>
                                                </td>
                                                <td style="width:200px;">
                                                    <?php echo $individual->admission_no; ?>
                                                </td>
												<?php } 
												else{
												?>
                                                <td style="width:110px;">
                                                    <strong><?php echo Yii::t('app','Admission Number'); ?></strong>
                                                </td>
                                               <td style="width:10px;">
                                                    <strong>:</strong>
                                                </td>
                                                <td style="width:200px;">
                                                    <?php echo $individual->admission_no; ?>
                                                </td>
                                                <td style="width:100px;">&nbsp;</td>
                                                <td style="width:10px;">&nbsp;</td>
                                                <td style="width:200px;">&nbsp;</td>
                                                <?php
												}
												?>
                                            </tr>
                                            <tr>
                                                <td colspan="6">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong><?php echo Yii::t('app','Admission Date'); ?></strong>
                                                </td>
                                               <td>
                                                    <strong>:</strong>
                                                </td>
                                                <td>
                                                    <?php 
                                                    if($individual->admission_date!=NULL)
                                                    {
                                                        $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
														$admission_date = $individual->admission_date;
                                                        if($settings!=NULL)
                                                        {	
                                                            $admission_date = date($settings->displaydate,strtotime($individual->admission_date));
                                                        }
                                                        echo $admission_date; 
                                                    }
                                                    else
                                                    {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <strong><?php echo Yii::t('app','Leaves Taken'); ?></strong>
                                                </td>
                                               <td>
                                                    <strong>:</strong>
                                                </td>
                                                <td>
                                                    <?php 																										
													if($individual->admission_date>=$batch->start_date){ 
														$start_date  	= date('Y-m-d',strtotime($individual->admission_date));												
													}
													else{
														$start_date  	= date('Y-m-d',strtotime($batch->start_date));
													}													
										
													if($batch->end_date >= date('Y-m-d')){
														$end_date		= date('Y-m-d');												
													}
													else{
														$end_date		= date('Y-m-d', strtotime($batch->end_date));
													}
													$criteria 				= new CDbCriteria;		
													$criteria->join 		= 'LEFT JOIN student_leave_types t1 ON (t.leave_type_id = t1.id OR t.leave_type_id = 0)'; 
													$criteria->condition 	= 't1.is_excluded=:is_excluded AND t.student_id=:x AND t.date >=:z AND t.date <=:A AND t.batch_id=:batch_id';											
													$criteria->params 		= array(':is_excluded'=>0,':x'=>$individual->id,':z'=>$start_date,':A'=>$end_date, 'batch_id'=>$batch->id);
													$criteria->order		= 't.date DESC';
													$criteria->group 		= 't.id';
													$leaves    				= StudentAttentance::model()->findAll($criteria); 
													echo count($leaves);
                                                    ?>
                                                </td>
                                                </tr>
                                                <tr>
                                                	<td colspan="6">&nbsp;</td>
                                            	</tr>
                                                <tr>
                                                <td>
                                                    <strong><?php echo Yii::t('app','Working Days'); ?></strong>
                                                </td>
                                               <td>
                                                    <strong>:</strong>
                                                </td>
                                                <td>
                                                    <?php
																									
														$batch_days_1  = array();
														$batch_range_1 = StudentAttentance::model()->createDateRangeArray($start_date,$end_date);  // to find total session
														$batch_days_1  = array_merge($batch_days_1,$batch_range_1);
														
														$days = array();
														$days_1 = array();
														$weekArray = array();
														
														$total_working_days_1 = array();
														$weekdays = Weekdays::model()->findAll("batch_id=:x AND weekday<>:y", array(':x'=>$batch->id,':y'=>"0"));
														if(count($weekdays)==0)
														{
															
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
											
											
											
														foreach($batch_days_1 as $batch_day_1)
														{
															$week_number = date('N', strtotime($batch_day_1));
															if(in_array($week_number,$weekArray)) // If checking if it is a working day
															{
																array_push($days_1,$batch_day_1);
															}
														}
														$holiday_arr[] =array();
														$ischeck = Configurations::model()->findByPk(43);
														
														if($ischeck->config_value != 1)
														{
															//$holidays = Holidays::model()->findAllByAttributes(array('user_id'=>Yii::app()->user->Id));
															$holidays = Holidays::model()->findAll();
															$holiday_arr=array();
															foreach($holidays as $key=>$holiday)
															{
																if(date('Y-m-d',$holiday->start)!=date('Y-m-d',$holiday->end))
																{
																	$date_range = StudentAttentance::model()->createDateRangeArray(date('Y-m-d',$holiday->start),date('Y-m-d',$holiday->end));
																	foreach ($date_range as $value) {
																		$holiday_arr[] = date('Y-m-d',$date_range);
																	}
																}
																else
																{
																	$holiday_arr[] = date('Y-m-d',$holiday->start);
																}
															}
														}
														
														
														foreach($days_1 as $day_1)
														{
															
															if(!in_array($day_1,$holiday_arr)) // If checking if it is a working day
															{
																array_push($total_working_days_1,$day_1);
															}
														}
								  
														
														echo count($total_working_days_1);	
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
                                            <td><?php echo Yii::t('app','Reason');?></td>
                                        </tr>
                                        <?php
										$criteria 				= new CDbCriteria;		
										$criteria->join 		= 'LEFT JOIN student_leave_types t1 ON t.leave_type_id = t1.id'; 
										$criteria->condition 	= 't.student_id=:x AND t.date >=:z AND t.date <=:A AND t.batch_id=:batch_id';											
										$criteria->params 		= array(':x'=>$individual->id,':z'=>$start_date,':A'=>$end_date, 'batch_id'=>$batch->id);													
										$student_leaves			= StudentAttentance::model()->findAll($criteria);
										
										if($student_leaves!=NULL)
										{
											$individual_sl = 1;
											foreach($student_leaves as $leave) // Displaying each leave row.
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
														$leave->date = date($settings->displaydate,strtotime($leave->date));
													}
													echo $leave->date; 
													?>
												</td>
												<td>
													<?php
													if($leave->reason!=NULL)
													{
														echo ucfirst($leave->reason);
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
                                            	<td colspan="3" style="padding-top:10px; padding-bottom:10px;">
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
							} //END Checking if student present in the department selected
							else
							{
						?>
								<div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;">
									<div class="y_bx_head">
										<?php echo Yii::t('app','No such student present in this').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Try searching other').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?>
									</div>      
								</div>
						<?php
							}
						} // END Checking if mode == 3 (Monthly Report)
						else // If no mode is set
						{
						
						} // END If no mode is set
						
					} // END If students is present
					else
					{
					?>
						<div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;">
							<div class="y_bx_head">
								<?php echo Yii::t('app','No students present in this').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").' '.Yii::t('app','Try searching other').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?>.
							</div>      
						</div>
					<?php
					}
					   
				} // END Checking if batch is selected
                ?>
              
                <!-- END REPORT SECTION -->
               
            </div>
             <!-- END div class="cont_right" -->
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>