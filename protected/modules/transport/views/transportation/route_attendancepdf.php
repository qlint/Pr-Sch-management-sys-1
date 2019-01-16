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
    <?php 
    if(isset($_REQUEST['date']) && $_REQUEST['date']!=NULL)
    {
        $default_date= $_REQUEST['date'];
    }
    else
    {
        $default_date=date("j M Y");
    }
    
    $route_id="";
    $route_name= "";
    if(isset($_REQUEST['route_id']) && $_REQUEST['route_id']!=NULL)
    {
        $route_id= $_REQUEST['route_id'];
        $route_model= RouteDetails::model()->findByPk($route_id);
        if($route_model!=NULL)
        {
            $route_name= $route_model->route_name;
        }
    }
    ?>
    
    
    <?php 
    if($model)
    {
        
        ?>
         <div align="center" style="text-align:center; display:block;"><?php echo Yii::t('app','Route Attendance'); ?></div><br />
         
         
         <table width="640" style="font-size:13px; background:#DCE6F1; padding:10px 10px;border:#C5CED9 1px solid;">
        	<tr>
            	<td width="120" height="30"><?php echo Yii::t('app','Route');?></td>
                <td width="20">:</td>
                <td width="190"><?php echo $route_name; ?></td>
                
                <td width="120"><?php echo Yii::t('app','Date'); ?></td>
                <td width="20">:</td>
                <td width="190"><?php echo $default_date;?></td>
            </tr>
                        
                
        </table>
         
         
         <table width="100%" border="0" cellspacing="0" cellpadding="0" class="attendance_table">
                            <tr class="tablebx_topbg" style="background:#DCE6F1;">
                                <td align="center" rowspan="2" >
                            	<?php echo Yii::t('app','Sl No');?>
                                </td>
                                <?php
                                if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){						
                                ?> 
                                <td align="center" rowspan="2" >
                                    <?php echo Yii::t('app','Student Name');?>
                                </td>
                                 <?php } ?>  
                                <td align="center" rowspan="2">
                                    <?php echo Yii::t('app','Date');?>
                                </td>
                                <td align="center" colspan="2">
                                    <?php echo Yii::t('app','Morning');?>
                                </td>
                                <td align="center" colspan="2" >
                                    <?php echo Yii::t('app','Evening');?>
                                </td>
                            </tr>
                            <tr class="tablebx_topbg" style="background:#DCE6F1;">
                            <td align="center" width="10%">
                            	<?php echo Yii::t('app','IN');?>
                            </td>
                            <td align="center" width="10%">
                            	<?php echo Yii::t('app','OUT');?>
                            </td>
                           <td align="center" width="10%">
                            	<?php echo Yii::t('app','IN');?>
                            </td>
                            <td align="center" width="10%">
                            	<?php echo Yii::t('app','OUT');?>
                            </td>
                        </tr>
                        
                        <?php 
                        $no=1;
                        foreach ($model as $data)
                        {
                        ?>
                        <tr>
                                <td align="center" width="10%"><?php echo $no; ?></td>
                                <?php
                                    if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){						
					$student = Students::model()->findByAttributes(array('id'=>$data->student_id));			?> 
                                <td style="text-align:center" width="15%"><?php echo $student->studentFullName('forStudentProfile'); ?></td>
                                <?php } ?> 
                                <td align="center" width="10%"><?php echo $default_date; ?></td>
                                <td align="center" width="10%">
                                    <?php 
                                        $morning_in= RouteAttendance::checkStatus($data->student_id, 1, 0, $default_date, $route_id);
                                        echo $morning_in;
                                    ?>
                                </td>
                                <td align="center" width="10%">
                                    <?php 
                                        $morning_out= RouteAttendance::checkStatus($data->student_id, 1, 1, $default_date, $route_id);
                                        echo $morning_out;
                                    ?>
                                </td>
                                <td align="center" width="10%">
                                    <?php 
                                        $evening_in= RouteAttendance::checkStatus($data->student_id, 2, 0, $default_date, $route_id);
                                        echo $evening_in;
                                    ?>
                                </td>
                                <td align="center" width="10%">
                                    <?php 
                                        $evening_out= RouteAttendance::checkStatus($data->student_id, 2, 1, $default_date, $route_id);
                                        echo $evening_out;
                                    ?>
                                </td>
                            </tr>
                        <?php 
                        $no++;
                                    } ?>
                        
         </table>
    <?php
    }
    ?>