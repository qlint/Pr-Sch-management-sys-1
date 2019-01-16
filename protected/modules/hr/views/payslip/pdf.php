<style>
.pay-table {
	border-collapse: collapse;
}
.pay-table th {
	border: 1px solid #ccc;
	padding: 5px;
	font-size: 12px;
	text-align: left!important;
}
.pay-table td {
	border: 1px solid #ccc;
	padding: 5px;
	font-size: 12px;
}
.payblock th {
	background-color: #dee7f8;
	color: #364869;
	font-size: 13px;
	border: none;
	border: 1px solid #dee7f8;
}
</style>
<?php
$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL)
{
	$date=$settings->displaydate;
}
else
	$date = 'd-m-Y';
if(isset($_REQUEST['id']))
{
	 $employee	=	Staff::model()->findByPk($_REQUEST['id']);
?>
<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td  align="left" style="font-size:35px; color:#3498db; font-weight:bold; ">Pay Slip</td>
      </td>
    <td   align="right" width="50%" ><?php $filename=  Logo::model()->getLogo();
			if($filename!=NULL)
			{
		echo '<img src="uploadedfiles/school_logo/'.$filename[2].'" alt="'.$filename[2].'" class="imgbrder" height="100" />';
			}
	?></td>
  </tr>
</table>
<br />
<table class="pay-table" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <th><?php echo Yii::t('app','Name');	?></th>
      <td colspan="3"><?php echo $employee->fullname; ?></td>
      <th><?php echo $model->getAttributeLabel('salary_date');?></th>      
      <td><?php echo date($date,strtotime($model->salary_date)); ?></td>
    </tr>
    <tr>
    	<th><?php echo $employee->getAttributeLabel('email');?> </th>
		<td colspan="3"><?php echo $employee->email; ?></td>
     	<th><?php echo $employee->getAttributeLabel('employee_department_id');?></th>
		<td ><?php $department	=	EmployeeDepartments::model()->findByPk($employee->employee_department_id);
							echo ($department) ? $department->name: '-'; ?></td>
		
		
    </tr>
    
    <tr>
    <th><?php echo $employee->getAttributeLabel('staff_type');?></th>
		<td colspan="3"><?php $role	=	UserRoles::model()->findByPk($employee->staff_type);
						echo ($role) ? $role->name: '-'; ?></td>
     	<th><?php echo $employee->getAttributeLabel('bank_name');?></th>
		<td colspan="1"><?php echo $employee->bank_name; ?></td>
    </tr>    
        <tr>
            <th><?php echo $employee->getAttributeLabel('bank_acc_no');?></th>
            <td colspan="5"><?php echo ($employee->bank_acc_no!=0)? $employee->bank_acc_no : '-'; ?></td>
    	</tr>
  </tbody>
</table>
<br />
<table class="pay-table payblock" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <th><?php echo Yii::t('app','EARNINGS');?></th>
    </tr>
  </tbody>
</table>
<br />
<table class="pay-table" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <td><?php echo $model->getAttributeLabel('basic_pay');?></td>
      <td><?php echo $model->basic_pay; ?></td>
    </tr>
    <tr>
        <td><?php echo $model->getAttributeLabel('incentive');?></td>
        <td><?php echo ($model->incentive>0) ? $model->incentive : ''; ?></td>
      </tr>
       <tr>
        <td><?php echo $model->getAttributeLabel('over_time');?></td>
        <td><?php echo ($model->over_time>0) ? $model->over_time : ''; ?></td>
      </tr>
       <tr>
        <td><?php echo $model->getAttributeLabel('hike');?></td>
        <td><?php echo ($model->hike>0) ? $model->hike : ''; ?></td>
      </tr>
      <tr>
		  	<td><?php echo $model->getAttributeLabel('earn_total');?></td>
			<td><?php echo ($model->earn_total>0) ? $model->earn_total : ''; ?></td>
      </tr>
  </tbody>
</table>
<br />
<table class="pay-table payblock" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <th><?php echo Yii::t('app','DEDUCTIONS');?></th>
    </tr>
  </tbody>
</table>
<br />
<table class="pay-table" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tbody>
    <tr>
        <td><?php echo $model->getAttributeLabel('tds');?></td>
        <td><?php echo ($model->tds>0) ? $model->tds : ''; ?></td>
      </tr>
      <tr>
        <td><?php echo $model->getAttributeLabel('lop');?></td>
        <td><?php echo ($model->lop>0) ? $model->lop : ''; ?></td>
      </tr>          
      <tr>
        <td><?php echo $model->getAttributeLabel('loan');?></td>
        <td><?php echo ($model->loan>0) ? $model->loan : ''; ?></td>
      </tr>
     <tr>
        <td><?php echo $model->getAttributeLabel('festival_bonus');?></td>
        <td><?php echo ($model->festival_bonus>0) ? $model->festival_bonus : ''; ?></td>
      </tr>
      <tr>
        <td><?php echo $model->getAttributeLabel('esi');?></td>
        <td><?php echo ($model->esi>0) ? $model->esi : ''; ?></td>
      </tr>
      <tr>
        <td><?php echo $model->getAttributeLabel('epf');?></td>
        <td><?php echo ($model->epf>0) ? $model->epf : ''; ?></td>
      </tr>
      
    <tr>
        <td><?php echo $model->getAttributeLabel('deduction_total');?></td>
        <td><?php echo ($model->deduction_total>0) ? $model->deduction_total : ''; ?></td>
    </tr>
    <?php
    if($model->note!=""){ ?>        
     <tr>
        <td><?php echo $model->getAttributeLabel('note');?></td>
        <td><?php echo ($model->note!="") ? $model->note : ''; ?></td>
    </tr>
    <?php } ?>
    <tr>
        <td><?php echo $model->getAttributeLabel('net_salary');?></td>
        <td><?php echo  $model->net_salary; ?></td>
    </tr>
  </tbody>
</table>
<?php } ?>
