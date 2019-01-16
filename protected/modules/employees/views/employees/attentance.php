

<?php
$this->breadcrumbs=array(
	Yii::t('app','Teacher')=>array('index'),
	Yii::t('app','Attendance'),
);


?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <div class="emp_cont_left">
   <?php $this->renderPartial('application.modules.employees.views.employees.profileleft');?>
    
    </div>
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
                <h1><?php echo Yii::t('app','Teacher Profile');?></h1> 
                <div class="button-bg">
                <div class="top-hed-btn-left"></div>
                <div class="top-hed-btn-right">
                <ul>                                    
                <li><?php echo CHtml::link('<span>'.Yii::t('app','Edit').'</span>', array('update', 'id'=>$_REQUEST['id']),array('class'=>'a_tag-btn')); ?><!--<a class=" edit last" href="">Edit</a>--></li>
                <li><?php echo CHtml::link('<span>'.Yii::t('app','Teachers').'</span>', array('employees/manage'),array('class'=>'a_tag-btn')); ?><!--<a class=" edit last" href="">Edit</a>--></li>                                  
                </ul>
                </div>
                </div>
   
    <div class="clear"></div>
    <div class="emp_right_contner">
    <div class="emp_tabwrapper">

	<?php $this->renderPartial('application.modules.employees.views.employees.tab');?>

    <div class="clear"></div>
    <div class="emp_cntntbx" >
    
    
        <?php

function getweek($date,$month,$year)
{
$date = mktime(0, 0, 0,$month,$date,$year); 
$week = date('w', $date); 
switch($week) {
case 0: 
return 'S';
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
return 'S';
break;
}
}
?>


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
//echo $current_academic_yr->config_value.$year;

if($year != $current_academic_yr->config_value and ($is_insert->settings_value==0 or $is_edit->settings_value==0 or $is_delete->settings_value==0))
{
?>
	<div>
        <div class="yellow_bx" style="background-image:none;width:680px;padding-bottom:45px;">
            <div class="y_bx_head" style="width:650px;">
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




<div style="position:relative">

<?php

  if(isset($_REQUEST['id']))
  {

	if(!isset($_REQUEST['mon']))
	{
		$mon = date('F');
		$mon_num = date('n');
		$curr_year = date('Y');
	}
	else
	{
		$mon = EmployeeAttendances::model()->getMonthname($_REQUEST['mon']);
		$mon_num = $_REQUEST['mon'];
		$curr_year = $_REQUEST['year'];
	}
	$num = cal_days_in_month(CAL_GREGORIAN, $mon_num, $curr_year);
	?>
	



   <div class="pdf-box">
        <div class="box-one">
        			<div align="center" class="atnd_tnav-date">
	<?php 
		echo CHtml::link('<div class="atnd_arow_l"><img src="images/atnd_arrow-l.png" width="7" border="0"  height="13" /></div>', array('attendance', 'mon'=>date("m",strtotime($curr_year."-".$mon_num."-01 -1 months")),'year'=>date("Y",strtotime($curr_year."-".$mon_num."-01 -1 months")),'id'=>$_REQUEST['id']));  
		
		echo $mon.'&nbsp;&nbsp;&nbsp; '.$curr_year; echo CHtml::link('<div class="atnd_arow_r"><img src="images/atnd_arrow.png" border="0" width="7"  height="13" /></div>', array('attendance',  'mon'=>date("m",strtotime($curr_year."-".$mon_num."-01 +1 months")),'year'=>date("Y",strtotime($curr_year."-".$mon_num."-01 +1 months")),'id'=>$_REQUEST['id']));
	?>
        </div>
        </div>        
        <div class="box-two"> 
            <div class="bttns_addstudent-n">
            <ul>
            <li>
                <div>
                    <?php 
						if(Configurations::model()->teacherAttendanceMode() != 1){
							echo CHtml::link('Subject Wise Attendance', array('/employees/teacherSubjectAttendance', 'id'=>$_REQUEST['id']),array('class'=>'formbut-n')); 
						}
					?>        	
                </div>
            </li>
            <li>
				<?php /*?> <?php echo CHtml::link('<img src="images/pdf-but.png" border="0">', array('/courses/StudentAttentance/pdf1','id'=>$_REQUEST['id']),array('target'=>'_blank')); ?><?php */?>
				<?php
                    if($_REQUEST['mon']&&$_REQUEST['year']){
                    echo CHtml::link('Generate PDF', array('/employees/employeeAttendances/pdf1','mon'=>$_REQUEST['mon'],'year'=>$_REQUEST['year'],'id'=>$_REQUEST['id']),array('target'=>'_blank','class'=>'pdf_but')); 
                    }
                    else{
                    echo CHtml::link('Generate PDF', array('/employees/employeeAttendances/pdf1','mon'=>date("m"),'year'=>date("Y"),'id'=>$_REQUEST['id']),array('target'=>'_blank','class'=>'pdf_but')); 
                }
                ?>
            </li>
            </ul>
            </div>       	

        </div>
</div>



<div class="atnd_Con"  style="overflow-x:scroll;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
   
    <?php
    for($i=1;$i<=$num;$i++)
    {
        echo '<th>'.getweek($i,$mon_num,$curr_year).'<span>'.$i.'</span></th>';
    }
    ?>
</tr>
<?php 
		
$posts=Employees::model()->findAll("id=:x", array(':x'=>$_REQUEST['id']));
$j=0;
foreach($posts as $posts_1)
{
	/*************** Get Employee Start and End *****************/
	
	
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
	$holidays = Holidays::model()->findAll();
	$holiday_arr=array();
	foreach($holidays as $key=>$holiday)
	{
		if(date('Y-m-d',$holiday->start)!=date('Y-m-d',$holiday->end))
		{
			$date_range = StudentAttentance::model()->createDateRangeArray(date('Y-m-d',$holiday->start),date('Y-m-d',$holiday->end));
			foreach ($date_range as $value) {
    			$holiday_arr[$value] = $holiday->id;
			}
		}
		else
		{
			$holiday_arr[date('Y-m-d',$holiday->start)] = $holiday->id;
		}
	}
	if($j%2==0)
	$class = 'class="odd"';	
	else
	$class = 'class="even"';	
	
 ?>
<tr <?php echo $class; ?> >
    
    <?php
    for($i=1;$i<=$num;$i++)
    {
       echo '<td class="abs"><span  id="td'.$i.$posts_1->id.'">';
		echo  $this->renderPartial('ajax',array('day'=>$i,'month'=>$mon_num,'year'=>$curr_year,'emp_id'=>$posts_1->id,'days'=>$days,'holiday_arr'=>$holiday_arr,'employee'=>$posts_1));
		/*echo CHtml::ajaxLink(Yii::t('job','ll'),$this->createUrl('EmployeeAttendances/addnew'),array(
        'onclick'=>'$("#jobDialog").dialog("open"); return false;',
        'update'=>'#jobDialog','type' =>'GET','data'=>array('day' =>$i,'month'=>$mon_num,'year'=>'2012','emp_id'=>$posts_1->id),
        ),array('id'=>'showJobDialog'));
		echo '<div id="jobDialog"></div>';*/
		
		echo '</span><div  id="jobDialog123'.$i.$posts_1->id.'"></div></td>';
		echo '</span><div  id="jobDialogupdate'.$i.$posts_1->id.'"></div></td>';
    }
    ?>
</tr>
<?php $j++; }?>
</table>
<?php } ?>
</div>

 
</div>
    </div>
    </div>
    
    </div>
    </div>
   
    </td>
  </tr>
</table>
<script>
$('.abs').click(function(e) {
    $('form#student-attentance-form').remove();
});
</script>
