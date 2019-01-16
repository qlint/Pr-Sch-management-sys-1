<style type="text/css">
.formConInner {
    background: url("images/formcon_new-bg.png") repeat scroll 0 0 #f8fafb;
    border: 1px solid #edbc3a;
    width: auto;
}

.formCon {
    background: url("images/formcon-bg.png") repeat scroll 0 0 #f8fafb;
    border: 0 solid #f68575;
    border-radius: 3px;
    margin: 0 0 20px;
    padding: 0;
    width: 100%;
}

.formConInner_frst {
    border: 1px solid #d1e0ea;
    border-radius: 3px;
    padding: 15px;
    position: relative;
    width: auto;
}
</style>

<script language="javascript">
function getday()
{
		
		var dep_id = document.getElementById('department_id').value;
		var emp_id=  document.getElementById('employee_id').value;
		var day=  document.getElementById('day_id').value;
		if(dep_id != '' && emp_id != '' && day_id != '') // Some year is selected
		{
			window.location= 'index.php?r=/timetable/teachersTimetable/index&department_id='+dep_id+'&employee_id='+emp_id+'&day_id='+day;
		}
}
function getemployee()
{
		
		var dep_id = document.getElementById('department_id').value;
		var emp_id=  document.getElementById('employee_id').value;
		var day=  document.getElementById('day_id').value;
		if(dep_id != '' && emp_id != '' && day_id != '') // Some year is selected
		{
			window.location= 'index.php?r=/timetable/teachersTimetable/index&department_id='+dep_id+'&employee_id='+emp_id+'&day_id='+day;
		}
}

</script>
<?php
$this->breadcrumbs=array(
Yii::t('app','Timetable')=>array('/timetable'),
Yii::t('app','Teachers Timetable'),
);?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
            <?php $this->renderPartial('/default/left_side');?>
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">
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
                        <div class="clear"></div>
                        <div class="emp_cntntbx" style="padding-top:10px;">
                        
                        	<h1><?php echo Yii::t('app','View Teacher Timetable');?> </h1>
                            
                            
                            <!-- Options Form -->
                           	<div class="formCon">
								<div class="formConInner_frst">
                                
                                
                                
                                	<table style=" font-weight:normal;">
                                    	<!-- Row to select department -->
                                        
                                        <tr>
                                        	<td>&nbsp;</td>
                                            <td style="width:200px;"><strong><?php echo Yii::t('app','Select Department');?></strong></td>
                                            <td>&nbsp;</td>
                                            <td>
                                            <?php
											$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
											
											$ac_year=$current_academic_yr->config_value;
											
											$departments = EmployeeDepartments::model()->findAll("status =:x", array(':x'=>1));
											
											$departments_options = CHtml::listData($departments,'id','name');
											
											
											?>
											<?php
											echo CHtml::dropDownList('department_id','',$departments_options,array('prompt'=>Yii::t('app','Select Department'),'style'=>'width:190px;',
											'ajax' => array(
											'type'=>'POST',
											'url'=>CController::createUrl('/timetable/teachersTimetable/employeename'),
											'update'=>'#employee_id',
											'data'=>'js:{department_id:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',
											),'options'=>array($_REQUEST['department_id']=>array('selected'=>true))));
											?>
											</td>
										</tr>
                                        <tr>
                                        	<td colspan="4">&nbsp;</td>
                                        </tr>
                                          <!-- END Row to select Departments -->
                                           <!-- Row to select employee -->
                                          <tr id="employee_dropdown"  >
                                        	<td>&nbsp;</td>
                                            
                                            <td style="width:200px;"><strong><?php echo Yii::t('app','Select Teacher');?></strong></td>
                                            <td>&nbsp;</td>
                                           
                                            <td>
                                                <?php 
												if($_REQUEST['department_id'] != NULL and $_REQUEST['department_id'] != '0')
												{
													$employee_names = CHtml::listData(Employees::model()->findAllByAttributes(array('employee_department_id'=>$_REQUEST['department_id'],'is_deleted'=>0),array('order'=>'id DESC')),'id','fullname'); 
													$employee_list = CMap::mergeArray(array(0=>Yii::t('app','All Teacher')),$employee_names);
													echo CHtml::dropDownList('employee_id','',$employee_list,array('prompt'=>Yii::t('app','Select Teacher'),'style'=>'width:190px;','onchange'=>'getemployee()','options'=>array($_REQUEST['employee_id']=>array('selected'=>true))));
												}
												else
												{
													$employee_names = CHtml::listData(Employees::model()->findAllByAttributes(array('employee_department_id'=>$_REQUEST['department_id'],'is_deleted'=>0),array('order'=>'id DESC')),'id','fullname'); 
												
													$employee_list = CMap::mergeArray(array(0=>Yii::t('app','All Teachers')),$employee_names);
													echo CHtml::dropDownList('employee_id','',$employee_list,array('prompt'=>Yii::t('app','Select Teacher'),'style'=>'width:190px;','onchange'=>'getemployee()','options'=>array($_REQUEST['employee_id']=>array('selected'=>true))));
												}
                                                ?>
                                            </td>  
                                        </tr> 
                                        <tr>
                                        	<td colspan="4">&nbsp;</td>
                                        </tr>
                                        <!-- END Row to select employee -->
                                        <tr> <!--  Row to select Day -->
                                        <td>&nbsp;</td>
                                         <td style="width:200px;"><strong><?php echo Yii::t('app','Select Day');?></strong></td>                                            <td>&nbsp;</td>
                                          <td>
											   <?php
											   if($_REQUEST['day_id'] != '0' and $_REQUEST['day_id'] != NULL)
												{
											 
                                                echo CHtml::dropDownList('day_id','',array('1'=>'Sunday','2'=>'Monday','3'=>'Tuesday','4'=>'Wednesday','5'=>'Thursday','6'=>'Friday','7'=>'Saturday','8'=>'All'),array('prompt'=>Yii::t('app','Select day'),'style'=>'width:190px;','onchange'=>'getday()','id'=>'day_id','options'=>array($_REQUEST['day_id']=>array('selected'=>true))));	
													
												}
												else
												{
												 echo CHtml::dropDownList('day_id','',array('1'=>'Sunday','2'=>'Monday','3'=>'Tuesday','4'=>'Wednesday','5'=>'Thursday','6'=>'Friday','7'=>'Saturday','8'=>'All'),array('prompt'=>Yii::t('app','Select day'),'style'=>'width:190px;','onchange'=>'getday()','id'=>'day_id'));	
												}
												
                                              ?>
                                           </td>
                  						 </tr> <!-- END Row to select Day -->
                                        <tr>
                                        <td>
										
                                        </td>
                                        </tr>
                                      
                                            </table>
                                            
                                             
                                
                                
                                                  </div> <!-- END div class="formConInner" -->
                                          </div> <!-- END div class="formCon" -->
                                           <!-- END  Options form -->
                            
                            <!-- Search Result -->
                   <div style="position:relative">
                   
                       <?php
		                   if(isset($_REQUEST['department_id']) and isset($_REQUEST['employee_id']) and isset($_REQUEST['day_id']) and $_REQUEST['day_id']!=NULL)
							  {
							    if($_REQUEST['employee_id']=='0')  // All employee
									 {
									    $employee = Employees::model()->findAllByAttributes(array('employee_department_id'=>$_REQUEST['department_id']));                 
										echo CHtml::link(Yii::t('app','Generate PDF'), array('teachersTimetable/fullteacherpdf','department_id'=>$_REQUEST['department_id'],'employee_id'=>$_REQUEST['employee_id'],'day_id'=>$_REQUEST['day_id']),array('class'=>'pdf_but','target'=>'_blank')); 
										
					
									    foreach($employee as $employee_1) // Each employee
										    { 
										
									
						?>
                        <div class="clear"></div>	
                        <br />	
                  <div class="formCon"> 
					<div class="formConInner">
						<table style="text-align:center;,bgcol">
							<tr>
								<td style="width:auto; min-width:200px;">
									<?php echo Yii::t('app','Name').' : '.Employees::model()->getTeachername($employee_1->id);?>
											</td>
											<td width="20px">&nbsp&nbsp;
                                             <td width="20px">&nbsp;</td>
																	
											 
											<td style="width:auto; min-width:200px;">
											<?php echo Yii::t('app','Teacher Number').' : '.ucfirst($employee_1->employee_number);?>
											</td>
											<td width="20px">&nbsp;
																	
											</td>
																
											</tr>
			                  </table>
						</div>
				</div>  
                                            <?php
																								
										   $this->renderPartial('/teachersTimetable/viewtable',array('employee_id'=>$employee_1->id,'weekday_id'=>$_REQUEST['day_id'],'employee_department_id'=>$_REQUEST['department_id']));
										 }
									
								  }   // End All employee
							
							 else
								 {
								   $this->renderPartial('/teachersTimetable/viewtable',array('employee_id'=>$_REQUEST['employee_id'],'weekday_id'=>$_REQUEST['day_id']));
									
								 }
									
							}
	                                     ?>
                  </div>
                                          
                                          
                                          
                                          
                                                                                     
                                       </div> <!-- END div class="emp_cntntbx" --> 
                                    </div> <!-- END div class="emp_tabwrapper" -->
                                 </div> <!-- END div class="emp_right_contner" -->
                             </div> <!-- END div class="cont_right formWrapper" -->
                                       

