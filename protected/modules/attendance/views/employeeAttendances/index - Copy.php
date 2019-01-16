

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


<?php
/*$this->breadcrumbs=array(
	$this->module->id,
);*/
$this->breadcrumbs=array(
	Yii::t('app','Attendances')=>array('/attendance'),
	Yii::t('app','Teacher Attendances'),
);
?>
<script>
function course()
{
var id = document.getElementById('cou').value;
window.location= 'index.php?r=attendance/employeeAttendances/index&id='+id;	
}
</script>
<?php
  // $this->widget('application.extensions.ETooltip.ETooltip', array("selector"=>"#demo span[title]",'image'=>'white_arrow.png','tooltip'=>array("opacity"=>1, 'effect'=>'slide')));
?>
<div style="background:#fff; min-height:800px;">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td valign="top" width="96%" align="left">
            	<div class="full-formWrapper" id="demo">

                    <h1><?php echo Yii::t('app','Teacher Attendances');?></h1>
                    <?php $this->renderPartial('/default/employee_tab');?>

                    
                    <?php
                    
					$holidays = Holidays::model()->findAll();
					$holiday_arr=array();
					foreach($holidays as $key=>$holiday)
					{
						if(date('Y-m-d',$holiday->start)!=date('Y-m-d',$holiday->end))
						{
							$date_range = StudentAttentance::model()->createDateRangeArray(date('Y-m-d',$holiday->start),date('Y-m-d',$holiday->end));
							foreach ($date_range as $value) {
								$holiday_arr['id'][$value] = $holiday->id;
								$holiday_arr['title'][$value] = $holiday->title;
							}
						}
						else
						{
							$holiday_arr['id'][date('Y-m-d',$holiday->start)] = $holiday->id;
							$holiday_arr['title'][date('Y-m-d',$holiday->start)] = $holiday->title;
							
						}
					}
					
                    function getweek($date,$month,$year)
                    {
						$date = mktime(0, 0, 0,$month,$date,$year); 
						$week = date('w', $date); 
						switch($week) {
						case 0: 
						return 'Su';
						break;
						case 1: 
						return 'M';
						break;
						case 2: 
						return 'Tu';
						break;
						case 3: 
						return 'W';
						break;
						case 4: 
						return 'Th';
						break;
						case 5: 
						return 'F';
						break;
						case 6: 
						return 'Sa';
						break;
						}
                    }
                    ?>
                    
                    
                    
                    <div class="c_batch_tbar">
						<?php 
						if(isset($_REQUEST['id']))
                        {
						?>
                        
                        <div class="cb_right">
                            <div class="status_bx">
                                <ul>
                                <li><span><?php echo count(Employees::model()->findAll("employee_department_id=:x and is_deleted=:y", array(':x'=>$_REQUEST['id'],':y'=>0))); ?></span><?php echo Yii::t('app','Teacher(s)');?></li>
                                
                                </ul>
                                <div class="clear"></div>
                            </div>
                        
                        </div> <!-- END div class="cb_right" -->
                        
                        <?php 
						} //END if(isset($_REQUEST['id']))
						?>
                        <div class="cb_left">
                        	<div class="cb-block-left">
                            
							<?php
                            
                            $data = CHtml::listData(EmployeeDepartments::model()->findAll(),'id','name');
                            if(isset($_REQUEST['id']))
                            {
                                $sel= $_REQUEST['id'];
                            }
                            else
                            {
                                $sel='';
                            }
                            echo CHtml::dropDownList('id','',$data,array('prompt'=>Yii::t('app','Select Department'),'onchange'=>'course()','id'=>'cou','options'=>array($sel=>array('selected'=>true)))); ?>
                            </div>
                            <div class="cb-block-right">
                            
                            <span>
								<?php 
									if(Configurations::model()->teacherAttendanceMode() != 1){
										echo CHtml::link(Yii::t('app','Subject Wise Attendance'), array('/attendance/teacherSubjectAttendance'), array('class'=>'formbut-n'));
									}
								?>
                            </span> 
                            </div>
							<?php /*?><?php
                            
                            $data = CHtml::listData(EmployeeDepartments::model()->findAll(),'id','name');
                            if(isset($_REQUEST['id']))
                            {
                                $sel= $_REQUEST['id'];
                            }
                            else
                            {
                                $sel='';
                            }
                            
                            echo Yii::t('app','Select Department');
                            echo CHtml::dropDownList('id','',$data,array('prompt'=>Yii::t('app','Select Department'),'onchange'=>'course()','id'=>'cou','options'=>array($sel=>array('selected'=>true)))); ?><?php */?>
                           	<?php /*?><span><?php echo CHtml::link(Yii::t('app','Subject Wise Attendance'), array('/attendance/teacherSubjectAttendance'), array('class'=>'formbut-hm1')); ?></span> <?php */?>
                            <?php 
                                                        
                            if(!isset($_REQUEST['mon']))
                            {
                                $mon = date('F');
                                $mon_num = date('n');
                                $curr_year = date('Y');
                            }
                            else
                            {
                                $mon = $model->getMonthname($_REQUEST['mon']);
                                $mon_num = $_REQUEST['mon'];
                                $curr_year = $_REQUEST['year'];
                            }
                            $num = cal_days_in_month(CAL_GREGORIAN, $mon_num, $curr_year); // 31
                            ?>
                        </div> <!-- END div class="cb_left" -->
                        <div class="clear"></div>
                    </div> <!-- END div class="c_batch_tbar" -->
                    
                    
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
								$is_edit = PreviousYearSettings::model()->findByAttributes(array('id'=>3));
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
                    
                    
                    <?php
						if(isset($_REQUEST['id']) and $_REQUEST['id']!='')
						{
							$posts=Employees::model()->findAll("employee_department_id=:x AND is_deleted=:y", array(':x'=>$_REQUEST['id'], ':y'=>0));
							if($posts!=NULL) 
							{			
						
						?>

                            
 <div class="pdf-box">
        <div class="box-one">
        			<div align="center" class="atnd_tnav-date">
							<?php 
							echo CHtml::link('<div class="atnd_arow_l"><img src="images/atnd_arrow-l.png" width="7" border="0"  height="13" /></div>', array('index', 'mon'=>date("m",strtotime($curr_year."-".$mon_num."-01 -1 months")),'year'=>date("Y",strtotime($curr_year."-".$mon_num."-01 -1 months")),'id'=>$_REQUEST['id']));  
							echo $mon.'&nbsp;&nbsp;&nbsp; '.$curr_year; 
							
							echo CHtml::link('<div class="atnd_arow_r"><img src="images/atnd_arrow.png" border="0" width="7"  height="13" /></div>', array('index',  'mon'=>date("m",strtotime($curr_year."-".$mon_num."-01 +1 months")),'year'=>date("Y",strtotime($curr_year."-".$mon_num."-01 +1 months")),'id'=>$_REQUEST['id']));?>
							</div> <!-- END div align="center" class="atnd_tnav" -->
        </div>
        <div class="box-two">
            <div class="pdf-div">
								
								<?php 
								
								if($_REQUEST['mon']&&$_REQUEST['year']){
								echo CHtml::link(Yii::t('app','Generate PDF'), array('employeeAttendances/pdf','mon'=>$_REQUEST['mon'],'year'=>$_REQUEST['year'],'id'=>$_REQUEST['id']),array('target'=>'_blank','class'=>'pdf_but')); 
								}
								else{
								echo CHtml::link(Yii::t('app','Generate PDF'), array('employeeAttendances/pdf','mon'=>date("m"),'year'=>date("Y"),'id'=>$_REQUEST['id']),array('target'=>'_blank','class'=>'pdf_but')); 
								
								}
								?>
            </div>
        </div>
</div>                    

								<div class="atnd_Con" id="parent" style="width:954px;" >
                               <table id="fixTable" width="100%" border="0" cellspacing="0" cellpadding="0">
							   <thead>
									<tr>
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
										
										$emp_start = date('Y-m-d',strtotime($posts_1->joining_date));
										$emp_end = date('Y-m-d');
										/*echo $emp_start.'------'.$emp_end;*/
										$days = array();
										$emp_days = EmployeeAttendances::model()->createDateRangeArray($emp_start,$emp_end);
										
										$weekArray = array();
										
										$weekdays = Weekdays::model()->findAll("batch_id IS NULL AND weekday<>:y",array(':y'=>"0"));
										
										
										foreach($weekdays as $weekday)
										{
											$weekday->weekday = $weekday->weekday - 1;
											if($weekday->weekday <= 0)
											{
												$weekday->weekday = 7;
											}
											$weekArray[] = $weekday->weekday;
										}
										
										foreach($emp_days as $emp_day)
										{
											$week_number = date('N', strtotime($emp_day));
														
											//echo $day.'='.$week_number.'<br/>';
											if(in_array($week_number,$weekArray)) // If checking if it is a working day
											{
												array_push($days,$emp_day);
											}
										}
										/*************** END Get Employee Start and End *************/
										
										if($j%2==0)
											$class = 'class="odd"';	
										else
										 $class = 'class="even"';	
									
									?>
									<tr <?php echo $class; ?> >
										<td class="name"><?php echo Employees::model()->getTeachername($posts_1->id); ?></td>
										<?php
										for($i=1;$i<=$num;$i++)
										{
											echo '<td><span  id="td'."_".$i."_".$posts_1->id.'">';
											echo  $this->renderPartial('ajax',array('day'=>$i,'month'=>$mon_num,'year'=>$curr_year,'emp_id'=>$posts_1->id,'days'=>$days,'holiday_arr'=>$holiday_arr, 'employee'=>$posts_1));
											echo '</span><div  id="jobDialog123'."_".$i."_".$posts_1->id.'"></div></td>';
											echo '</span><div  id="jobDialogupdate'."_".$i."_".$posts_1->id.'"></div></td>';
										}
										?>
									</tr>
									<?php 
										$j++;
									 }
									?>
								</table>
						   
							
							</div> <!-- END div class="atnd_Con" -->
						
						<?php 
							} // END if($posts) 
							else{
								echo '<br>'?>
                                   <div class="not-fount-block">
                    					<span><?php echo Yii::t("app", "Nothing Found");?></span>
                                        </div>
                    						
                					
						<?php	}
						} // END if(isset($_REQUEST['id'])) 
						?>
						
					</div> <!-- END div #demo -->
				</td>
			</tr>
			</tbody>
		</table>
	</div>
<?php ?>
<script>
$('.at_abs').click(function(e) {
   $(".ui-dialog.ui-widget-content").remove(); 
   $("#employee-attendances-form").remove();
});
$('.abs').click(function(e) {
   $(".ui-dialog.ui-widget-content").remove(); 
   $("#employee-attendances-form").remove();
});

</script>
