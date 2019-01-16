<?php
$student_visible_fields   = FormFields::model()->getVisibleFields('Students', 'forStudentProfile');
?>
<style type="text/css">
.ui-menu .ui-menu-item a{ color:#000 !important;}
.ui-menu .ui-menu-item a:hover{ color:#fff !important;}
.ui-autocomplete{box-shadow: 0 0 6px #d6d6d6;}
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
	window.location= 'index.php?r=report/default/subwiseattentance&cid='+course_id+'&bid='+batch_id+'&sid='+sem_id;	
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
		window.location= 'index.php?r=report/default/subwiseattentance&cid='+course_id+'&bid='+batch_id+'&mode='+mode_id+'&sid='+sem_id;
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
	var course_id = document.getElementById('cid').value;
	var batch_id = document.getElementById('batchid').value;
	var mode_id = document.getElementById('mode_id').value;
	var year_value = document.getElementById('year_value').value;
	window.location= 'index.php?r=report/default/subwiseattentance&cid='+course_id+'&bid='+batch_id+'&mode='+mode_id+'&year='+year_value;
	
}

function getmonthreport() // Function to get monthly report
{
	var course_id = document.getElementById('cid').value;
	var batch_id = document.getElementById('batchid').value;
	var mode_id = document.getElementById('mode_id').value;
	var month_value = document.getElementById('month_value').value;
	var sem_id = document.getElementById('semester_id').value;
	month_value = month_value.replace(/(^\s+|\s+$)/g,'');
	window.location= 'index.php?r=report/default/subwiseattentance&cid='+course_id+'&bid='+batch_id+'&mode='+mode_id+'&month='+month_value+'&sid='+sem_id;
	
}
</script>

<?php
$this->breadcrumbs=array(
	Yii::t('app','Report')=>array('/report'),
	Yii::t('app','Student Subject Wise Attendance Report'),
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
                <h1><?php echo Yii::t('app','Student Subject Wise Attendance Report');?></h1>
                
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
                                    echo CHtml::dropDownList('cid','',CHtml::listData($data,'id','course_name'),array('prompt'=>Yii::t('app','Select Course'),'style'=>'width:190px;',
									'encode'=>false,
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
                            	<td colspan="4">&nbsp;</td>
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
                                    </div></td>

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
                                    echo CHtml::dropDownList('semester_id',(isset($_GET['sid']))?$_GET['sid']:'',$data_list,array('prompt'=>Yii::t('app','Select'),
									'encode'=>false,
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
                                    'options' => array()));?>  
                                </div>     
                             </td> 
                             
                            </tr>
                         
                            <tr>
                            <td colspan="2"><div class="" style="display:<?php echo $disp_status; ?>; padding-right: 10px" id="sem_tr">
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
										$batch_list = CHtml::listData(Batches::model()->findAllByAttributes(array('course_id'=>$_REQUEST['cid'],'is_active'=>1,'is_deleted'=>0)),'id','name');
										echo CHtml::dropDownList('batch_id','',$batch_list,array('encode'=>false,'prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),'id'=>'batchid','style'=>'width:190px;','options'=>array($_REQUEST['bid']=>array('selected'=>true)),'onchange'=>'updatemode()'));
									}
									else
									{
										echo CHtml::dropDownList('batch_id','',array(),array('encode'=>false,'prompt'=>Yii::t('app','Select').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),'id'=>'batchid','style'=>'width:190px;','onchange'=>'updatemode()'));
									}
                                    ?>
                                </td>
                            </tr>
                            <tr>
                            	<td colspan="4">&nbsp;</td>
                            </tr>
                            <tr> 
                                <td><strong><?php echo Yii::t('app','Select Mode');?></strong></td>
                                <td>
									<?php
									if(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL)
									{
										echo CHtml::dropDownList('mode_id','',array('1'=>Yii::t('app','Overall'),'2'=>Yii::t('app','Yearly'),'3'=>Yii::t('app','Monthly'),'4'=>Yii::t('app','Individual')),array('encode'=>false,'prompt'=>Yii::t('app','Select Mode'),'style'=>'width:190px;','onchange'=>'getmode()','id'=>'mode_id','options'=>array($_REQUEST['mode']=>array('selected'=>true)))); 
									}
									else
									{
										echo CHtml::dropDownList('mode_id','','',array('encode'=>false,'prompt'=>Yii::t('app','Select Mode'),'style'=>'width:190px;','id'=>'mode_id')); 
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
									
											 
									echo CHtml::dropDownList('year','',$arrYears,array('prompt'=>Yii::t('app','Select Year'),'style'=>'width:190px;','id'=>'year_value','onchange'=>'getyearreport()','encode'=>false,'options'=>array($_REQUEST['year']=>array('selected'=>true))));
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
											$end_year = date('Y');
											
											for($i=$start_year;$i<=$end_year;$i++)
												$years[$i] = $i;
												
											$year_range = count($years)-1;
										}else{
											$year_range = 20;
										}
										$this->widget('ext.EJuiMonthPicker.EJuiMonthPicker', array(
											'name' => 'month_year',
											'value'=>$_REQUEST['month'],
											'options'=>array(
												'yearRange'=>'-'. $year_range .':',
												'dateFormat'=>$date,
											),
											'htmlOptions'=>array(
												'onChange'=>'js:getmonthreport()',
												'id' => 'month_value',
												'style' => 'width:180px;'
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
                                    <?php  $this->widget('zii.widgets.jui.CJuiAutoComplete',
											array(
											  'name'=>'name',
											  'id'=>'individual_value',
											  'source'=>$this->createUrl('/site/autocomplete'),
											  'htmlOptions'=>array('placeholder'=>Yii::t('app','Student Name'),'style' => 'width:180px;'),
											  'options'=>
												 array(
													   'showAnim'=>'fold',
													   'select'=>"js:function(student,ui){
														   var course_id = document.getElementById('cid').value;
															var batch_id = document.getElementById('batchid').value;
															var mode_id = document.getElementById('mode_id').value;
															var sem_id = document.getElementById('semester_id').value;
															var individual_value = ui.item.id;
															individual_value = individual_value.replace(/(^\s+|\s+$)/g,'');
															window.location= 'index.php?r=report/default/subwiseattentance&cid='+course_id+'&bid='+batch_id+'&sid='+sem_id+'&mode='+mode_id+'&student='+individual_value;
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
				$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));	
				$timetable_entries = TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['bid']));
				$class_timings = ClassTimings::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['bid']));
				if($class_timings!=NULL){ // If class timing  present
					if($timetable_entries!=NULL) // If timetable  present
					{
						if(isset($_REQUEST['mode']) and $_REQUEST['mode']==1 or isset($_REQUEST['mode']) and $_REQUEST['mode']==2 or isset($_REQUEST['mode']) and $_REQUEST['mode']==3) // Checking if mode == 1 (Overall Report)
						{
						?>

                                  <div class="button-bg">
                                    <div class="pdf-btn-posiction">
                                       <?php echo CHtml::link(Yii::t('app','Generate PDF'), array('/report/default/subwisepdf','id'=>$_REQUEST['bid'], 'year'=>$_REQUEST['year'], 'month'=>$_REQUEST['month'], 'mode'=>$_REQUEST['mode']),array('target'=>"_blank",'class'=>'cbut')); ?>                
                                    </div>
                                    <?php
                                ?>  
                                    <div class="btn-posiction">
                                       <h3><?php  if(isset($_REQUEST['mode']) and $_REQUEST['mode']==1){
										   echo Yii::t('app','Overall Student Subject Wise Attendance Report');
									     }?>
                                         <?php  if(isset($_REQUEST['mode']) and $_REQUEST['mode']==2){
										   echo Yii::t('app','Yearly Student Subject Wise Attendance Report');
									     }?>
                                         <?php  if(isset($_REQUEST['mode']) and $_REQUEST['mode']==3){
										   echo Yii::t('app','Monthly Student Subject Wise Attendance Report');
									     }?></h3>
                                       
                                    </div> 
                                   
                                </div>
                            
                            <div class="tablebx">
                            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr class="tablebx_topbg">
                                        <td><?php echo Yii::t('app','Sl No');?></td>
                                        <td><?php echo Yii::t('app','Adm No');?></td>
                                        <td><?php echo Yii::t('app','Admission Date');?></td>
                                        <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                        <td><?php echo Yii::t('app','Name');?></td>
                                        <?php } ?>
                                         <td><?php echo Yii::t('app','Batch');?></td>
                                          <td><?php echo Yii::t('app','Subject');?></td>
                                        <td><?php echo Yii::t('app','No Of Classes');?></td>
                                        <td><?php echo Yii::t('app','Leaves');?></td>
                                    </tr>
                                     <?php
									$criteria = new CDbCriteria();
									$criteria->condition = 'batch_id = :batch_id';
									$criteria->group = 'subject_id';
									$criteria->params = array('batch_id' => $_REQUEST['bid']);
									$times = TimetableEntries::model()->findAll($criteria);
									$overall_sl = 1;
									foreach($times as $time) // Displaying each subjects row.
									{
										$subject = Subjects::model()->findByAttributes(array('id'=>$time->subject_id));
										$batchstudents = BatchStudents::model()->findAllByAttributes(array('batch_id'=>$_REQUEST['bid'], 'status'=>1));
										//var_dump($batchstudents);exit;
										foreach($batchstudents as $batchstudent){ // Displaying each students row.
										$flag	=	0;
										if($time->is_elective == 2){
											$stu_elective	=	StudentElectives::model()->findByAttributes(array('student_id'=>$batchstudent->student_id,'elective_id'=>$time->subject_id));
											if(isset($stu_elective) and $stu_elective!=NULL)
												$flag	=	1;
											else
												$flag	=	0;
										}else
										{
											$flag	=	1;											
										}
										
										$student = Students::model()->findByAttributes(array('id'=>$batchstudent->student_id));
										
									if($flag ==	1)
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
												if($settings!=NULL)
												{	
													$student->admission_date = date($settings->displaydate,strtotime($student->admission_date));
												}
												echo $student->admission_date; 
											}
											else
											{
												echo '-';
											}
											?>
										</td>
                                        <?php if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){ ?>
                                        <td>
											<?php if($student!=NULL){
													echo CHtml::link($student->studentFullName("forStudentProfile"),array('/students/students/view','id'=>$student->id));
												  }?>
										</td>
                                        <?php }?>
                                  
                                         <td>
											<?php echo $batch->name;?>
										</td>
                                		<td>
											<?php
												if($time->is_elective == 2){
													$elective	= Electives::model()->findByAttributes(array('id'=>$time->subject_id));
													$elective_g	= ElectiveGroups::model()->findByAttributes(array('id'=>$elective->elective_group_id));
													if($elective){
														echo ucfirst($elective->name).' ( '.$elective_g->name.' )';
													}
													else{
														echo '-';
													}
												}
												else{
													$subject=Subjects::model()->findByAttributes(array('id'=>$time->subject_id));
													
													if($subject!=NULL){	
														echo ucfirst($subject->name); 															
													}
													else{
														echo '-';
													}
												}
												
											?>
										</td>
                                        <td>
											<?php
											$student_details=Students::model()->findByAttributes(array('id'=>$student->id)); 
											$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));	
																			
											if($student_details->admission_date>=$batch->start_date)
											{ 
												$batch_start  = date('Y-m-d',strtotime($student_details->admission_date));
											
											}
											else
											{
												$batch_start  = date('Y-m-d',strtotime($batch->start_date));
											}
											$batch_end    = date('Y-m-d');
												$total_entry_count	= 0;
												for($w=1;$w<=7;$w++){
													$entries = TimetableEntries::model()->findAllByAttributes(array('subject_id'=>$time->subject_id, 'weekday_id'=>$w));
													if(count($entries)>0){														
														$entry_count	= count($entries);														
														$weekday 		= $w-1;
														if($weekday==0) $weekday=7;
														
														if($_REQUEST['mode']==1){
															$start_date = $batch_start;
															$end_date 	= $batch_end;
														}elseif($_REQUEST['mode']==2){
															$year = $_REQUEST['year'];
																	
															$yr_start = date('Y-m-d', mktime(0, 0, 0, 1, 1,  $year ));
															$yr_end = date('Y-m-d', mktime(0, 0, 0, 12, 31,  $year ));
															
															
															if($yr_start < $batch_start){
																$start_date = $batch_start;
															}
															else{
																$start_date = $yr_start;
															}
															if($yr_end > $batch_end){
																$end_date = $batch_end;
															}
															else{
																$end_date = $yr_end;
															}
															
														}elseif($_REQUEST['mode']==3){
																	$timestamp    = strtotime($_REQUEST['month']);
																	
																	$month_start = date('Y-m-01', $timestamp);
																	$month_end  = date('Y-m-t', $timestamp); 
																	
																	
																	if($month_start < $batch_start){
																		$start_date = $batch_start;
																	}
																	else{
																		$start_date = $month_start;
																	}
																	if($month_end > $batch_end){
																		$end_date = $batch_end;
																	}
																	else{
																		$end_date = $month_end;
																	}
																	
														}
														
														$daycount	= 0;
														$start 		= new DateTime($start_date);
														$end   		= new DateTime($end_date);
														$end->modify('+1 day');
														$interval 	= DateInterval::createFromDateString('1 day');
														$period 	= new DatePeriod($start, $interval, $end);
														foreach ($period as $dt){
															if ($dt->format('N') == $weekday){
																//check holiyday
																$day	=	$dt->format('Y-m-d'); 
																$is_holiday		= StudentAttentance::model()->isHoliday($day);	
																$ischeck = Configurations::model()->findByPk(43); 														
																if($ischeck->config_value != 1)
																{
																	if(!$is_holiday){ 
																		$daycount++; 
																	}
																}else{
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
											if($_REQUEST['mode']==1){
												
												 $subwise = StudentSubjectwiseAttentance::model()->findAllByAttributes(array('student_id'=>$student->id, 'subject_id'=>$time->subject_id)); 
													echo count($subwise);
											}
											elseif($_REQUEST['mode']==2){
												$year = $_REQUEST['year'];
												
												 $subwise = StudentSubjectwiseAttentance::model()->findAllByAttributes(array('student_id'=>$student->id, 'subject_id'=>$time->subject_id));
												 $leaves = 0;
												 foreach($subwise as $subwise_1)
													{
														 $leave_year = date('Y', strtotime($subwise_1->date));
														if($leave_year == $year)
														{
															$leaves++; 
														}
														
													}
													echo $leaves;
												
											}
											elseif($_REQUEST['mode']==3){
												$requiredmonth = date('m',strtotime($_REQUEST['month']));
												$requiredyear = date('Y',strtotime($_REQUEST['month']));
												
												 $subwise = StudentSubjectwiseAttentance::model()->findAllByAttributes(array('subject_id'=>$time->subject_id, 'student_id'=>$student->id)); 
												  $leaves = 0;
												 	foreach($subwise as $subwise_1){
													  $leave_year = date('Y', strtotime($subwise_1->date));
													  $leave_month = date('m',strtotime($subwise_1->date));
													   
													if($leave_year == $requiredyear and $leave_month == $requiredmonth){
														 
														$leaves++; 
													}
													
													
												 }
												 echo $leaves;
												
											}
													?>
                                        	
                                        </td>
                                    </tr>
                                    <?php
										}
										} // end student foreach
									} // end subject foreach
									?>
								</table>
                            </div>
                            
						<?php
						} // END Checking if mode == 1 (Overall Report)
						
						
						elseif(isset($_REQUEST['mode']) and $_REQUEST['mode']==4) // Checking if mode == 4 (Individual Report)
						{ 
							$batch_stu  = BatchStudents::model()->findByAttributes(array('student_id'=>$_REQUEST['student'],'batch_id'=>$_REQUEST['bid'],'result_status'=>0));
							if($batch_stu!=NULL){
                        		$individual = Students::model()->findByAttributes(array('id'=>$_REQUEST['student'],'is_active'=>1,'is_deleted'=>0));
							}
							$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));	
						?>
	                      
                        <?php
							if($individual!=NULL) // Checking if employee present in the department selected
							{
						?>

                                <div class="button-bg">
                                <div class="pdf-btn-posiction">
                                   <?php echo CHtml::link(Yii::t('app','Generate PDF'), array('/report/default/individualpdf','id'=>$_REQUEST['bid'],'student'=>$_REQUEST['student']),array('target'=>"_blank",'class'=>'cbut')); ?>           
                                </div>
                                
                                <div class="btn-posiction">
                                     <h3><?php echo Yii::t('app','Individual Student Subject Wise Attendance Report');?></h3>
                                </div> 
                            </div>
                                
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
                                                    <?php 
													if($individual!=NULL){
														echo CHtml::link($individual->studentFullName("forStudentProfile"),array('/students/students/view','id'=>$individual->id));
													}?>
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
                                                        if($settings!=NULL)
                                                        {	
                                                            $individual->admission_date=date($settings->displaydate,strtotime($individual->admission_date));
                                                        }
                                                        echo $individual->admission_date; 
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
													$criteria->join			= 'JOIN `timetable_entries` `t1` ON `t1`.`id` = `t`.`timetable_id` JOIN `student_leave_types` `t2` ON (`t2`.`id` = `t`.`leavetype_id` OR `t`.`leavetype_id` = 0)'; 
													$criteria->condition 	= 't2.is_excluded=:is_excluded AND  t.date >=:z AND t.date <=:A AND t.student_id=:x AND t1.batch_id=:y';											
													$criteria->params 		= array(':is_excluded'=>0,':z'=>$start_date,':A'=>$end_date,':x'=>$individual->id, ':y'=>$_REQUEST['bid']);
													$criteria->order		= 't.date DESC';
													$criteria->group 		= 't.id';													
													$subwise = StudentSubjectwiseAttentance::model()->findAll($criteria);
												
													echo count($subwise);
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
                                             <td><?php echo Yii::t('app','Subject');?></td>
                                            <td><?php echo Yii::t('app','No Of Classes');?></td>
                                            <td><?php echo Yii::t('app','Leaves');?></td>
                                        </tr>
                                        <?php
										$leaves = StudentSubjectwiseAttentance::model()->findAllByAttributes(array('student_id'=>$_REQUEST['student']));
										
										if($leaves!=NULL)
										{
											
											$criteria = new CDbCriteria();
											$criteria->condition = 'batch_id = :batch_id';
											$criteria->group = 'subject_id';
											$criteria->params = array('batch_id' => $batch->id);
											$times = TimetableEntries::model()->findAll($criteria);
									
											$individual_sl = 1;
											foreach($times as $time) // Displaying each leave row.
											{
												if($time->is_elective == 2){													
													$stude_elective	= StudentElectives::model()->findByAttributes(array('student_id'=>$_REQUEST['student'],'batch_id'=>$batch->id));
												}
												if((isset($stude_elective) and $stude_elective->elective_id == $time->subject_id) or $time->is_elective !=2){													
													
												?>
                                                <tr>
                                                    <td style="padding-top:10px; padding-bottom:10px;"><?php echo $individual_sl; $individual_sl++;?></td>
                                                     <!-- Individual Attendance row -->
                                                    <td>
                                                        <?php 
                                                        if($time->is_elective == 2){
                                                            $elective	= Electives::model()->findByAttributes(array('id'=>$time->subject_id));
                                                            $elective_g	= ElectiveGroups::model()->findByAttributes(array('id'=>$elective->elective_group_id));
                                                            if($elective){
                                                                echo ucfirst($elective->name).' ( '.$elective_g->name.' )';
                                                            }
                                                            else{
                                                                echo '-';
                                                            }
                                                        }
                                                        else{
                                                            $subject=Subjects::model()->findByAttributes(array('id'=>$time->subject_id));
                                                            if($subject!=NULL){	
                                                                echo ucfirst($subject->name); 															
                                                            }
                                                            else{
                                                                echo '-';
                                                            }
                                                        }
                                                        
                                                        ?>
                                                    </td>
                                                
                                                        <td>
                                                            <?php
                                                    $total_entry_count	= 0;
                                                    for($w=1;$w<=7;$w++){
                                                        $entries = TimetableEntries::model()->findAllByAttributes(array('subject_id'=>$time->subject_id, 'weekday_id'=>$w));
                                                        if(count($entries)>0){														
                                                            $entry_count	= count($entries);														
                                                            $weekday 		= $w-1;
                                                            if($weekday==0) $weekday=7;
                                                            
                                                            $student_details=Students::model()->findByAttributes(array('id'=>$_REQUEST['student'])); 
                                                            $batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['bid']));	
                                                                                            
                                                            if($student_details->admission_date>=$batch->start_date)
                                                            { 
                                                                $batch_start  = date('Y-m-d',strtotime($student_details->admission_date));
                                                            
                                                            }
                                                            else
                                                            {
                                                                $batch_start  = date('Y-m-d',strtotime($batch->start_date));
                                                            }
                                                            $batch_end    = date('Y-m-d');
                                                            
                                                            $start_date = $batch_start;
                                                            $end_date 	= $batch_end;
                                                            
                                                            
                                                            
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
																	$ischeck = Configurations::model()->findByPk(43);														
																	if($ischeck->config_value != 1)
																	{
																		if(!$is_holiday){
																			
																			$daycount++;
																		}
																	}else{
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
                                                           $subwise = StudentSubjectwiseAttentance::model()->findAllByAttributes(array('student_id'=>$_REQUEST['student'], 'subject_id'=>$time->subject_id));
                                                           echo count($subwise);
                                                            ?>
                                                        </td>
                                                </tr>
											<?php
												}
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
						
						
					} // END If timetable is present
					else
					{
					?>
						<div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;">
							<div class="y_bx_head">
								<?php echo Yii::t('app','No Timetable created for this').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?>
							</div>      
						</div>
					<?php
					}
				} // END If classtiming is present
				else{
					?>
						<div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;">
							<div class="y_bx_head">
								<?php echo Yii::t('app','No Classtimings created for this').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"); ?>
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