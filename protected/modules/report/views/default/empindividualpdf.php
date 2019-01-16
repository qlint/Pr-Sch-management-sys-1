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
   
    <div align="center" style="text-align:center; display:block;"><?php echo Yii::t('app','TEACHER ATTENDANCE REPORT'); ?></div><br />
    <?php 
	$individual = Employees::model()->findByAttributes(array('id'=>$_REQUEST['employee'],'employee_department_id'=>$_REQUEST['id']));
	$department_name = EmployeeDepartments::model()->findByAttributes(array('id'=>$_REQUEST['id']));
	?>
   
    <!-- Individual Details -->
     <table width="685" style="font-size:13px; background:#DCE6F1; padding:10px 10px;border:#C5CED9 1px solid;">
        		<tr>
                    <td width="120">
                        <?php echo Yii::t('app','Name'); ?>
                    </td>
                    <td width="10">
                        <strong>:</strong>
                    </td>
                    <td width="212">
                        <?php echo Employees::model()->getTeachername($individual->id);?>
                    </td>
                    <td width="120">
                        <?php echo Yii::t('app','Teacher Number'); ?>
                    </td>
                   <td width="10">
                        <strong>:</strong>
                    </td>
                    <td width="212">
                        <?php echo $individual->employee_number; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php echo Yii::t('app','Job Title'); ?>
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
                        <?php echo Yii::t('app','Joining Date'); ?>
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
                                $individual->joining_date = date($settings->displaydate,strtotime($individual->joining_date));
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
                    <td>
                        <?php echo Yii::t('app','Leaves Taken'); ?>
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
  
    <!-- END Individual Details -->                            
    
    <!-- Individual Report Table -->
         <table width="100%" cellspacing="0" cellpadding="0" class="attendance_table">
            <tr class="tablebx_topbg" style="background-color:#DCE6F1;">
                <td width="65"><?php echo Yii::t('app','Sl No');?></td>
                <td width="200"><?php echo Yii::t('app','Leave Date');?></td>
                <td width="200" ><?php echo Yii::t('app','Half Day');?></td>
                <td width="254"><?php echo Yii::t('app','Reason');?></td>
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
						<?php echo Yii::t('app','No leaves taken!'); ?>
					</td>
				</tr>
			<?php
			}
			?>
        </table>
    
    <!-- END Individual Report Table -->
    
   
   <?php
    }
	else
	{
    ?>
    		<?php echo Yii::t('app','No data available!'); ?>
       
	<?php
    }
?>