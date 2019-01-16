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
                            <td class="listbxtop_hdng first">
                                <?php $college=Configurations::model()->findAll(); ?>
                                <?php echo $college[0]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first">
                                <?php echo $college[1]->config_value; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="listbxtop_hdng first">
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
   
    <div align="center" style="text-align:center; display:block;"><?php echo Yii::t('app','OVERALL TEACHER ATTENDANCE REPORT'); ?></div><br />
    <?php 
	$employees = Employees::model()->findAll("employee_department_id=:x and is_deleted=:y", array(':x'=>$_REQUEST['id'],':y'=>0)); 
	$department_name = EmployeeDepartments::model()->findByAttributes(array('id'=>$_REQUEST['id']));
	?>
    <!-- Department details -->
     <table width="685" style="font-size:13px; background:#DCE6F1; padding:10px 10px;border:#C5CED9 1px solid;">
        	<tr>
            	<td width="120"  height="40"><?php echo Yii::t('app','Department');?></td>
                <td width="10" >:</td>
                <td  width="212"><?php echo ucfirst($department_name->name);?></td>
                
                <td width="120"><?php echo Yii::t('app','Department Code');?></td>
                <td width="10">:</td>
                <td width="212"><?php echo $department_name->code;?></td>
            </tr>
            <tr>
            	<td height="40"><?php echo Yii::t('app','Total Teachers');?></td>
                <td>:</td>
                <td><?php echo count($employees);?></td>
			</tr>                
                
        </table>
    <!-- END Department details -->
    
    <!-- Overall Attendance Table -->
         <table width="100%" cellspacing="0" cellpadding="0" class="attendance_table">
            <tr class="tablebx_topbg" style="background-color:#DCE6F1;">
               <td width="50"><?php echo Yii::t('app','Sl No');?></td>
               <td width="90"><?php echo Yii::t('app','Teacher No');?></td>
               <td width="140"><?php echo Yii::t('app','Joining Date');?></td>
               <td width="210"><?php echo Yii::t('app','Name');?></td>
               <td width="120"><?php echo Yii::t('app','Job Title');?></td>
               <td width="100"><?php echo Yii::t('app','Leaves');?></td>
            </tr>
             <?php
				$overall_sl = 1;
				foreach($employees as $employee) // Displaying each employee row.
				{
				?>
				<tr>
					<td style="padding-top:10px; padding-bottom:10px;" width="30"><?php echo $overall_sl; $overall_sl++;?></td>
					<td width="40"><?php echo $employee->employee_number; ?></td>
					 <td>
						<?php 
						if($employee->joining_date!=NULL)
						{
							$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
							if($settings!=NULL)
							{	
								$employee->joining_date=date($settings->displaydate,strtotime($employee->joining_date));
							}
							echo $employee->joining_date; 
						}
						else
						{
							echo '-';
						}
						?>
					</td>
					<td width="210"><?php echo Employees::model()->getTeachername($employee->id);?></td>
					<td width="120">
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
					<td width="40">
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

    <!-- END Overall Attendance Table -->
   
   <?php
    }
	else
	{
    ?>
    		<?php echo Yii::t('app','No data available!'); ?>
       
	<?php
    }
?>