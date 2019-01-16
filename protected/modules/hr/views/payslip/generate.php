<?php
$this->breadcrumbs=array(
	Yii::t('app','HR')=>array('index'),
	Yii::t('app','Generate Payslip'),
);


?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
     <?php $this->renderPartial('/default/leftside');?>
    
    </td>
    <td valign="top">
    <div class="cont_right formWrapper">
    <h1><?php echo Yii::t('app','Generate Payslip');?></h1>
    <div class="formCon" >
            <div class="formConInner">
                <div class="form">
                	<?php $form=$this->beginWidget('CActiveForm', array(
								'id'=>'staff-form',
								'enableAjaxValidation'=>false,
								'htmlOptions' => array('onsubmit' => 'return calc_earn_total()',),
							)); ?>
                       <?php
					   		$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
							if($settings!=NULL)
							{
								$date=$settings->dateformat;								
							}
							else
								$date = 'dd-mm-yy';
					   		$department	=	EmployeeDepartments::model()->findByPk($employee->employee_department_id);
							$role		=	UserRoles::model()->findByPk($employee->staff_type);
							
							if($model->basic_pay!=""){
								$basic_pay	=	$model->basic_pay;
							}else{
								$basic_pay	=	$employee->basic_pay;
							}
							
							if($model->esi!=""){
								$esi	=	$model->esi;
							}else{
								$esi	=	$employee->ESI;
							}
							
							if($model->epf!=""){
								$epf	=	$model->epf;
							}else{
								$epf	=	$employee->EPF;
							}							
								
					   ?>
                       <table width="100%" border="0" cellspacing="0" cellpadding="0">
                       	  <tr>
                          	<td valign="bottom" style="padding-bottom:3px;"><?php echo Yii::t('app','Name'); ?></td>
                            <td valign="bottom" style="padding-bottom:3px;"><?php echo $employee->fullname; ?></td>
                            <td valign="bottom" style="padding-bottom:3px;"><?php echo $employee->getAttributeLabel('employee_number'); ?></td>
                           	<td valign="bottom" style="padding-bottom:3px;"><?php echo ($employee->employee_number) ? $employee->employee_number : '-'; ?></td>
                          </tr>
                          <tr>
                          	<td valign="bottom" style="padding-bottom:3px;"><?php echo $employee->getAttributeLabel('employee_department_id'); ?></td>
                            <td valign="bottom" style="padding-bottom:3px;"><?php echo ($department) ? $department->name: '-';  ?></td>
                            <td valign="bottom" style="padding-bottom:3px;"><?php echo $employee->getAttributeLabel('staff_type'); ?></td>
                           	<td valign="bottom" style="padding-bottom:3px;"><?php echo ($role) ? $role->name: '-'; ?></td>
                          </tr>
                          <tr>
                          	<td valign="top" style="padding-bottom:3px;"><?php echo $employee->getAttributeLabel('email'); ?></td>
                            <td valign="top" style="padding-bottom:3px;"><?php echo $employee->email;  ?></td>
                            <td valign="middle" style="padding-bottom:3px;"></td>
                           	<td valign="middle" style="padding-bottom:3px;"></td>
                          </tr>
                           <tr>
                          	<td valign="middle" style="padding-bottom:3px;"><?php echo $form->labelEx($model,'salary_date'); ?></td>
                           	<td valign="middle" style="padding-bottom:3px;">
								<?php
									$this->widget('zii.widgets.jui.CJuiDatePicker', array(
												//'name'=>'Employees[joining_date]',
												'attribute'=>'salary_date',
												'model'=>$model,
												
												// additional javascript options for the date picker plugin
												'options'=>array(
													'showAnim'=>'fold',
													'dateFormat'=>$date,
													'changeMonth'=> true,
														'changeYear'=>true,
														'yearRange'=>'1970:'
												),
												'htmlOptions'=>array(
													//'style'=>'height:20px;'
													//'value' => date('m-d-y'),
													'readonly'=>"readonly",
													'onfocus'=>'calc_earn_total()',
												),
											));
                                ?>
                                 <?php echo $form->error($model,'salary_date'); ?>
                                 <?php echo $form->hiddenField($model,'employee_id',array('value'=>$employee->id)); ?>
                            </td>
                            <td valign="top" style="padding-bottom:3px;"></td>
                            <td valign="top" style="padding-bottom:3px;"></td>
                           
                          </tr>
                       </table>
                       <h3><?php echo Yii::t('app','Earnings'); ?></h3>
                       <div class="text-fild-bg-block">           
                            <div class="text-fild-block inputstyle">
                                <?php echo $form->labelEx($model,'basic_pay'); ?>
                                <?php echo $form->textField($model,'basic_pay',array('size'=>10,'maxlength'=>255,'value'=>number_format($basic_pay, 2, '.', ''),'onblur'=>'calc_earn_total()')); ?>
                                <?php echo $form->error($model,'basic_pay'); ?>
                            </div>
                            <div class="text-fild-block inputstyle">
                                <?php echo $form->labelEx($model,'incentive'); ?>
                                <?php echo $form->textField($model,'incentive',array('size'=>10,'maxlength'=>5,'onblur'=>'calc_earn_total()')); ?>
                                <?php echo $form->error($model,'incentive'); ?>
                            </div>
                        </div>
                        
                        <div class="text-fild-bg-block">           
                            <div class="text-fild-block inputstyle">
                                <?php echo $form->labelEx($model,'over_time'); ?>
                                <?php echo $form->textField($model,'over_time',array('size'=>10,'maxlength'=>5,'onblur'=>'calc_earn_total()')); ?>
                                <?php echo $form->error($model,'over_time'); ?>
                            </div>
                            <div class="text-fild-block inputstyle">
                                <?php echo $form->labelEx($model,'hike'); ?>
                                <?php echo $form->textField($model,'hike',array('size'=>10,'maxlength'=>6,'onblur'=>'calc_earn_total()')); ?>
                                <?php echo $form->error($model,'hike'); ?>
                            </div>
                        </div>
                        
                        <div class="text-fild-bg-block">           
                            <div class="text-fild-block inputstyle">
                                <?php echo $form->labelEx($model,'earn_total'); ?>
                                <?php echo $form->textField($model,'earn_total',array('size'=>10,'maxlength'=>255,'readonly'=>true)); ?>
                                <?php echo $form->error($model,'earn_total'); ?>
                            </div>
                        </div>
                        
                        <h3><?php echo Yii::t('app','Deductions'); ?></h3>
                        <div class="text-fild-bg-block">           
                            <div class="text-fild-block inputstyle">
                                <?php
								if($model->tds==""){
									if($employee->TDS>0){
										if($employee->tds_type==1){
											$tds	=	( $employee->basic_pay * $employee->TDS ) / 100 ;
										}else{
											$tds	=	$employee->TDS;
										}
									}else{
										$tds	=	'';
									}
								}else{
									$tds	=	$model->tds;
								}
								
								?>
                                <?php echo $form->labelEx($model,'tds'); ?>
                                <?php echo $form->textField($model,'tds',array('size'=>10,'maxlength'=>5,'value'=>$tds,'onblur'=>'calc_earn_total()')); ?>
                                <?php echo $form->error($model,'tds'); ?>
                            </div>
                            <div class="text-fild-block inputstyle">
                                <?php echo $form->labelEx($model,'lop'); ?>
                                <?php echo $form->textField($model,'lop',array('size'=>10,'maxlength'=>5,'onblur'=>'calc_earn_total()')); ?>
                                <?php echo $form->error($model,'lop'); ?>
                            </div>
                            <div class="text-fild-block inputstyle">
                                <?php echo $form->labelEx($model,'loan'); ?>
                                <?php echo $form->textField($model,'loan',array('size'=>10,'maxlength'=>5,'onblur'=>'calc_earn_total()')); ?>
                                <?php echo $form->error($model,'loan'); ?>
                            </div>
                        </div>
                        
                        <div class="text-fild-bg-block">           
                            <div class="text-fild-block inputstyle">
                                <?php echo $form->labelEx($model,'festival_bonus'); ?>
                                <?php echo $form->textField($model,'festival_bonus',array('size'=>10,'maxlength'=>5,'onblur'=>'calc_earn_total()')); ?>
                                <?php echo $form->error($model,'festival_bonus'); ?>
                            </div>
                            <div class="text-fild-block inputstyle">
                                <?php echo $form->labelEx($model,'esi'); ?>
                                <?php echo $form->textField($model,'esi',array('size'=>10,'maxlength'=>10,'value'=>number_format($esi, 2, '.', ''),'onblur'=>'calc_earn_total()')); ?>
                                <?php echo $form->error($model,'esi'); ?>
                            </div>
                            <div class="text-fild-block inputstyle">
                                <?php echo $form->labelEx($model,'epf'); ?>
                                <?php echo $form->textField($model,'epf',array('size'=>10,'maxlength'=>10,'value'=>number_format($epf, 2, '.', ''),'onblur'=>'calc_earn_total()')); ?>
                                <?php echo $form->error($model,'epf'); ?>
                            </div>
                        </div>
                        
                        <div class="text-fild-bg-block">           
                            <div class="text-fild-block inputstyle">
                                <?php echo $form->labelEx($model,'deduction_total'); ?>
                                <?php echo $form->textField($model,'deduction_total',array('size'=>10,'maxlength'=>100,'readonly'=>true)); ?>
                                <?php echo $form->error($model,'deduction_total'); ?>
                            </div>
                            <div class="text-fild-block inputstyle">
                                <?php echo $form->labelEx($model,'net_salary'); ?>
                                <?php echo $form->textField($model,'net_salary',array('size'=>10,'maxlength'=>255,'readonly'=>true,'onblur'=>'calc_earn_total()')); ?>
                                <?php echo $form->error($model,'net_salary'); ?>
                            </div>
                        </div>
                        
                        <div class="text-fild-bg-block">           
                            <div class="text-fild-block inputstyle" style="width:98% !important">
                                <?php echo $form->labelEx($model,'note'); ?>
                                <?php echo $form->textArea($model,'note',array('cols'=>50,'rows'=>5)); ?>
                                <?php echo $form->error($model,'note'); ?>
                            </div>
                        </div>
                        
                        <div class="text-fild-bg-block">           
                            <div class="text-fild-block inputstyle" style="width:98% !important">
                                <?php echo Yii::t('app','Note: After payslip generation hike amount will be added to basic salary'); ?>
                            </div>
                        </div>
                        <div class="row buttons">
                            <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Generate') : Yii::t('app','Save')); ?>
                        </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
	</div>
    </td>
  </tr>
</table>
<script>
function calc_earn_total(){
	var arrears,basic_salary,incentivearrears,incentivecurrent,earntotal,tds,leave,loan,esi,epf,festival,deductiontotal,earntotal=0;
	var hike_arrears=hike_current=ot_arrears=ot_current=earntotal=0;
	//calc_earn_total.earntotal=0;
	if(!isNaN($("#SalaryDetails_basic_pay").val())){
	 basic_salary=parseInt($("#SalaryDetails_basic_pay").val());
	}
	
	if($("#SalaryDetails_incentive").val().trim()!=""){
		if(!isNaN($("#SalaryDetails_incentive").val())){
		  incentivecurrent=parseInt($("#SalaryDetails_incentive").val());
		}
	}else{
		incentivecurrent=0;
	}
	
	if($("#SalaryDetails_over_time").val().trim()!=""){
		if(!isNaN($("#SalaryDetails_over_time").val())){
		  ot_current=parseInt($("#SalaryDetails_over_time").val());
		}
	}else{
		ot_current=0;
	}
	
	if($("#SalaryDetails_hike").val().trim()!=""){
		if(!isNaN($("#SalaryDetails_hike").val())){
		  hike_current=parseInt($("#SalaryDetails_hike").val());
		}
	}else{
		hike_current=0;
	}
	
	earntotal= basic_salary + incentivecurrent + ot_current + hike_current;
		
	if(!isNaN(earntotal))
	{
	   $("#SalaryDetails_earn_total").val(earntotal.toFixed(2));
	}
	
	if($("#SalaryDetails_tds").val().trim()!=""){
		if(!isNaN($("#SalaryDetails_tds").val())){
		  tds=parseFloat($("#SalaryDetails_tds").val());
		}
	}else{
		tds=0;
	}
	
	if($("#SalaryDetails_lop").val().trim()!=""){
		if(!isNaN($("#SalaryDetails_lop").val())){
		  leave=parseFloat($("#SalaryDetails_lop").val());
		}
	}else{
		leave=0;
	}
	
	if($("#SalaryDetails_loan").val().trim()!=""){
		if(!isNaN($("#SalaryDetails_loan").val())){
		  loan=parseFloat($("#SalaryDetails_loan").val());
		}
	}else{
		loan=0;
	}
	
	if($("#SalaryDetails_esi").val().trim()!=""){
		if(!isNaN($("#SalaryDetails_esi").val())){
		  esi=parseFloat($("#SalaryDetails_esi").val());
		}
	}else{
		esi=0;
	}
	
	if($("#SalaryDetails_epf").val().trim()!=""){
		if(!isNaN($("#SalaryDetails_epf").val())){
		  epf=parseFloat($("#SalaryDetails_epf").val());
		}
	}else{
		epf=0;
	}
	
	if($("#SalaryDetails_festival_bonus").val().trim()!=""){
		if(!isNaN($("#SalaryDetails_festival_bonus").val())){
		  festival=parseFloat($("#SalaryDetails_festival_bonus").val());
		}
	}else{	
		festival=0;
	}
	var deductiontotal= tds +leave+ esi + epf + festival + loan;
	
	if(!isNaN(deductiontotal)){
		$("#SalaryDetails_deduction_total").val(deductiontotal.toFixed(2));
	}
	
	var earntotal=parseInt($("#SalaryDetails_earn_total").val());
	var netsalary= earntotal - deductiontotal;

	if(!isNaN(netsalary)){
	 $("#SalaryDetails_net_salary").val(netsalary.toFixed(2));
	}
	
}

</script>

