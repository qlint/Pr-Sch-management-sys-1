<style>
table.attendance_table{ border-collapse:collapse}

.attendance_table{
	margin:30px 0px;
	font-size:8px;
	text-align:center;
	width:auto;
	/*max-width:600px;*/
	border-top:1px #CCC solid;
	border-right:1px solid #CCC;
}
.attendance_table td{
	border:1px solid #CCC;
	padding-top:10px; 
	padding-bottom:10px;
	width:auto;
	font-size:13px;
	
}

.attendance_table th{
	font-size:14px;
	padding:10px;
	border-left:1px #CCC solid;
	border-bottom:1px #CCC solid;
}

hr{ border-bottom:1px solid #C5CED9; border-top:0px solid #fff;}

</style>

	<!-- Header -->
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td class="first" width="100">
                           <?php $filename=  Logo::model()->getLogo();
                            if($filename!=NULL)
                            { 
                                //Yii::app()->runController('Configurations/displayLogoImage/id/'.$logo[0]->primaryKey);
                                echo '<img src="uploadedfiles/school_logo/'.$filename[2].'" alt="'.$filename[2].'" class="imgbrder" height="100" />';
                            }
                            ?>
                </td>
                <td valign="middle">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:22px; width:300px;  padding-left:10px;">
                                <?php $college=Configurations::model()->findAll(); ?>
                                <?php echo $college[0]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo $college[1]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first" style="text-align:left; font-size:14px; padding-left:10px;">
                                <?php echo Yii::t('app','Phone: ').$college[2]->config_value; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
<hr />
<br />
	<!-- End Header -->

	<?php
    if(isset($_REQUEST['id']))
    {  
   ?>
   
    <div align="center" style="text-align:center; display:block"><?php echo Yii::t('app','MONTHLY TEACHER ATTENDANCE REPORT'); ?></div><br />
    <?php 
	$employees = Employees::model()->findAll("employee_department_id=:x and is_deleted=:y", array(':x'=>$_REQUEST['id'],':y'=>0)); 
	$department_name = EmployeeDepartments::model()->findByAttributes(array('id'=>$_REQUEST['id']));
	?>
    <!-- Department details -->
     <table width="685" style="font-size:13px; background:#DCE6F1; padding:10px 10px;border:#C5CED9 1px solid;">
        	<tr>
            	<td width="120"><?php echo Yii::t('app','Department');?></td>
                <td width="10">:</td>
                <td width="212"><?php echo ucfirst($department_name->name);?></td>
                
                <td width="120"><?php echo Yii::t('app','Department Code');?></td>
                <td width="10">:</td>
                <td width="212"><?php echo $department_name->code;?></td>
            </tr>
            <tr>
            	<td><?php echo Yii::t('app','Total Teacher');?></td>
                <td>:</td>
                <td><?php echo count($employees);?></td>
                
                <td><?php echo Yii::t('app','Month');?></td>
                <td>:</td>
                <td><?php echo $_REQUEST['month'];?></td>
			</tr>                
                
        </table>

    <!-- END Department details -->
    
    <!-- Monthly Attendance Table -->
    
         <table width="100%" cellspacing="0" cellpadding="0" class="attendance_table">
            <tr class="tablebx_topbg" style="background-color:#DCE6F1;">
                <td width="80"><?php echo Yii::t('app','Sl No');?></td>
                <td width="93"><?php echo Yii::t('app','Teacher No');?></td>
                <td width="250"><?php echo Yii::t('app','Name');?></td>
                <td width="230"><?php echo Yii::t('app','Job Title');?></td>
                <td width="60"><?php echo Yii::t('app','Leaves');?></td>
            </tr>
              <?php
				$monthly_sl = 1;
				foreach($employees as $employee) // Displaying each employee row.
				{
				?>
				<tr>
					<td style="padding-top:10px; padding-bottom:10px;"><?php echo $monthly_sl; $monthly_sl++;?></td>
					<td><?php echo $employee->employee_number; ?></td>
					<td><?php echo $employee_name = Employees::model()->getTeachername($employee->id);?></td>
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
    
    <!-- END Monthly Attendance Table -->
   
   <?php
    }
	else
	{
    ?>
    
    		<?php echo Yii::t('app','No data available!'); ?>
       
	<?php
    }
?>
