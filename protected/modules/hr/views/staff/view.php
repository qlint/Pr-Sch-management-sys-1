<?php
$this->breadcrumbs=array(
	Yii::t('app','HR')=>array('index'),
	Yii::t('app','Manage'),
);


?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
     <?php $this->renderPartial('/default/leftside');?>
    
    </td>
    <td valign="top">
    <div class="cont_right formWrapper">

    	<?php
			$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
			if($settings!=NULL)
			{
				$date=$settings->displaydate;
			}
			else
				$date = 'd-m-Y';
		?>
        <h1><?php echo Yii::t('app','Details of Staff ').$model->fullname;?></h1>
            <div class="formCon">
    <div class="formConInner-block">
        <div class="pdtab_Con-table">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
        	<tr>
            	<th><?php echo Yii::t('app','Name'); ?></th>
                <td><?php echo $model->fullname; ?></td>
            </tr>
            <tr>
            	<th><?php echo $model->getAttributeLabel('employee_number'); ?></th>
                <td><?php echo ($model->employee_number) ? $model->employee_number : '-'; ?></td>
            </tr>
             <tr>
            	<th><?php echo $model->getAttributeLabel('joining_date'); ?></th>
                <td><?php echo date($date,strtotime($model->joining_date)); ?></td>
            </tr>
             <tr>
            	<th><?php echo $model->getAttributeLabel('date_of_birth'); ?></th>
                <td><?php echo date($date,strtotime($model->date_of_birth)); ?></td>
            </tr>
            <tr>
            	<th><?php echo $model->getAttributeLabel('qualification'); ?></th>
                <td><?php echo ($model->qualification) ? $model->qualification: '-'; ?></td>
            </tr>
            <tr>
            	<th><?php echo $model->getAttributeLabel('gender'); ?></th>
                <td><?php echo ($model->gender) ? $model->gender: '-'; ?></td>
            </tr>
            <tr>
            	<th><?php echo $model->getAttributeLabel('job_title'); ?></th>
                <th><?php echo ($model->job_title) ? $model->job_title: '-'; ?></th>
            </tr>
             <tr>
            	<th><?php echo $model->getAttributeLabel('employee_department_id'); ?></th>
                <td><?php 
							$department	=	EmployeeDepartments::model()->findByPk($model->employee_department_id);
							echo ($department) ? $department->name: '-'; ?></td>
            </tr>
             <tr>
            	<th><?php echo $model->getAttributeLabel('email'); ?></th>
                <td><?php echo ($model->email) ? $model->email: '-'; ?></td>
            </tr>
            <tr>
            	<th><?php echo $model->getAttributeLabel('mobile_phone'); ?></th>
                <td><?php echo ($model->mobile_phone) ? $model->mobile_phone: '-'; ?></td>
            </tr>
             <tr>
            	<th><?php echo $model->getAttributeLabel('staff_type'); ?></th>
                <td><?php 
						$role	=	UserRoles::model()->findByPk($model->staff_type);
						echo ($role) ? $role->name: '-'; ?></td>
            </tr>
            <tr>
            	<th><?php echo Yii::t('app','Experience'); ?></th>
                <td><?php echo ($model->experience_year) ? $model->experience_year." ".Yii::t('app','Year(s)') : '-';
						  echo ($model->experience_month) ? $model->experience_month." ".Yii::t('app','Month(s)') : '-'; ?></td>
            </tr>
            <tr>
            	<th><?php echo $model->getAttributeLabel('experience_detail'); ?></th>
                <td><?php echo ($model->experience_detail) ? $model->experience_detail: '-'; ?></td>
            </tr>
            <tr>
            	<th><?php echo $model->getAttributeLabel('salary_date'); ?></th>
                <td><?php echo ($model->salary_date!=NULL and $model->salary_date!="0000-00-00") ? date($date,strtotime($model->salary_date)) : '-'; ?></td>
            </tr>
             <tr>
            	<th><?php echo $model->getAttributeLabel('basic_pay'); ?></th>
                <td><?php echo ($model->basic_pay) ? $model->basic_pay: '-'; ?></td>
            </tr>
            <tr>
            	<th><?php echo $model->getAttributeLabel('TDS'); ?></th>
                <td><?php echo ($model->tds_type==1) ? number_format($model->TDS).'%' : $model->TDS; ?></td>
            </tr>
            <tr>
            	<th><?php echo $model->getAttributeLabel('passport_no'); ?></th>
                <td><?php echo ($model->passport_no) ? $model->passport_no: '-'; ?></td>
            </tr>
            <tr>
            	<th><?php echo $model->getAttributeLabel('passport_expiry'); ?></th>
                <td><?php echo ($model->passport_expiry!=NULL and $model->passport_expiry!="0000-00-00") ? date($date,strtotime($model->passport_expiry)) : '-'; ?></td>
            </tr>
        </table>
        </div>
        </div>
        </div>
    </div>
    </td>
  </tr>
</table>
 

