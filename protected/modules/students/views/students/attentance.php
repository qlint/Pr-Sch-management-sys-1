<?php
$this->breadcrumbs=array(
	Yii::t('app','Students')=>array('index'),
	Yii::t('app','Attendance'),
);
?>
<script language="javascript">
function getmode(type){
	var student_id	= <?php echo $_REQUEST['id']; ?>;
	var batch_id	= $('#batch_id').val();
		if(student_id != '' && batch_id != ''){
			window.location= 'index.php?r=students/students/attentance&id='+student_id+'&bid='+batch_id;
		}
		else if(student_id != ''){
			window.location= 'index.php?r=students/students/attentance&id='+student_id;
		}
		else{
			window.location= 'index.php?r=students/students/attentance';
		}
}
</script>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    <div class="emp_cont_left">
   <?php $this->renderPartial('profileleft');?>
    
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
<h1><?php echo Yii::t('app','Student Profile');?></h1>     
    <div class="clear"></div>
    <div class="emp_right_contner">
    <div class="emp_tabwrapper">
     <?php $this->renderPartial('application.modules.students.views.students.tab');?>
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
				echo Yii::t('app','You are not viewing the current active year.');
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

$batches    = 	BatchStudents::model()->studentBatch($_REQUEST['id']); 
if($batches){
	foreach($batches as $batch){
		$batch_list[$batch->id]	= ucfirst($batch->name);
	}
}
if(count($batches) == 1){
	$batch    	= 	BatchStudents::model()->findByAttributes(array('student_id'=>$_REQUEST['id'], 'result_status'=>0));
	$bid 		=  $batch->batch_id;		
}
elseif(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL){
	$bid 		=  $_REQUEST['bid'];	
}
elseif(count($batches)>1){ 
	$batch    	= 	BatchStudents::model()->findByAttributes(array('student_id'=>$_REQUEST['id'], 'result_status'=>0, 'batch_id'=>$batches[0]->id));
	$bid 		=  $batch->batch_id;
}
		

echo '<div class="selectbox-student">'.CHtml::dropDownList('bid','',$batch_list,array('id'=>'batch_id','style'=>'width:200px;display: inline; margin-left: 7px;','class'=>'form-control input-sm mb14','options'=>array($bid=>array('selected'=>true)),'encode'=>false,'onchange'=>'getmode();')).'</div>';?>


<div style="position:relative">

<?php
$subjects=Subjects::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id']));
$model = new EmployeeAttendances;
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
		$mon = $model->getMonthname($_REQUEST['mon']);
		$mon_num = $_REQUEST['mon'];
		$curr_year = $_REQUEST['year'];
		
	}
	$num = cal_days_in_month(CAL_GREGORIAN, $mon_num, $curr_year); // 31
	?>
	<?php
		$studentdetails=Students::model()->findByPk($_REQUEST['id']);
		$batch=Batches::model()->findByPk($bid);
		$begin = date('Y-m',strtotime($batch->start_date)); 
		$end = date('Y-m',strtotime($batch->end_date));
		$curr_mon_yr = date("Y-m",strtotime($curr_year."-".$mon_num));
	?>

     
<div class="opnsl_headerBox">
    <div class="opnsl_actn_box">

        <div align="center" class="opnsl_atnd_calender">
			<?php 
            if($curr_mon_yr > $begin)
            {   
            echo CHtml::link('<div class="atnd_arow_l"><img src="images/attnd-arow-left.png" width="7" border="0"  height="13" /></div>',
            array('students/attentance', 'mon'=>date("m",strtotime($curr_year."-".$mon_num."-01 -1 months")),'year'=>date("Y",strtotime($curr_year."-".$mon_num."-01 -1 months")),
            'id'=>$_REQUEST['id'], 'bid'=>$bid)); 
            }
            echo Yii::t('app',$mon).'&nbsp;&nbsp;&nbsp; '.$curr_year; 
            if($curr_mon_yr < $end)
            {  
            echo CHtml::link('<div class="atnd_arow_r"><img src="images/attnd-arow-right.png" width="7" border="0"  height="13" /></div>',
            array('students/attentance', 'mon'=>date("m",strtotime($curr_year."-".$mon_num."-01 +1 months")),'year'=>date("Y",strtotime($curr_year."-".$mon_num."-01 +1 months")),
            'id'=>$_REQUEST['id'], 'bid'=>$bid));
            }
            
            /*echo CHtml::link('<div class="atnd_arow_l"><img src="images/atnd_arrow-l.png" width="7" border="0"  height="13" /></div>', array('attentance', 'mon'=>date("m",strtotime($curr_year."-".$mon_num."-01 -1 months")),'year'=>date("Y",strtotime($curr_year."-".$mon_num."-01 -1 months")),'id'=>$_REQUEST['id'])); 
            echo $mon.'&nbsp;&nbsp;&nbsp; '.$curr_year; echo CHtml::link('<div class="atnd_arow_r"><img src="images/atnd_arrow.png" width="7" border="0"  height="13" /></div>', array('attentance', 'mon'=>date("m",strtotime($curr_year."-".$mon_num."-01 +1 months")),'year'=>date("Y",strtotime($curr_year."-".$mon_num."-01 +1 months")),'id'=>$_REQUEST['id']));*/?>
            </div>



            
    </div>
    <div class="opnsl_actn_box">
    <div class="opnsl_actn_box1">
<?php 
		if(Configurations::model()->studentAttendanceMode() != 1){
			echo CHtml::link(Yii::t('app','Subject Wise Attendance'), array('/students/studentAttentance/subwiseattentance','id'=>$_REQUEST['id']),array('class'=>'formbut-hm')); 
		}
            ?>
    </div>
    <div class="opnsl_actn_box1">
 <?php  if($_REQUEST['mon']&&$_REQUEST['year']){
            echo CHtml::link('Generate PDF', array('/students/StudentAttentance/pdf1','mon'=>$_REQUEST['mon'],'year'=>$_REQUEST['year'],'id'=>$_REQUEST['id'], 'bid'=>$bid),array('target'=>'_blank','class'=>'pdf_but')); 
            }
            else{
            echo CHtml::link(Yii::t('app','Generate PDF'), array('/students/StudentAttentance/pdf1','mon'=>date("m"),'year'=>date("Y"),'id'=>$_REQUEST['id'], 'bid'=>$bid),array('target'=>'_blank','class'=>'pdf_but'));  
            } ?>
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
	 /********************** GET BATCH DAYS *********************/
	$student_model= Students::model()->findByPk($_REQUEST['id']);
        $s_date= $student_model->admission_date;
        if(strtotime($s_date) > strtotime($batch->start_date))
        {
            $batch_start = date('Y-m-d',strtotime($s_date));
        }
        else
        {
            $batch_start = date('Y-m-d',strtotime($batch->start_date));
        }
        
        	
	$batch_end = date('Y-m-d',strtotime($batch->end_date));
	
	/*$temp_begin = date('Y-m',strtotime($batch->start_date));
	$temp_end = date('Y-m',strtotime($batch->end_date));*/
	$days = array();
	$batch_days = array();
	$batch_range = StudentAttentance::model()->createDateRangeArray($batch_start,$batch_end);
	$batch_days = array_merge($batch_days,$batch_range);
	
	
	
	/********** End Subject range ***********/                            
	$weekArray = array();
	$weekdays = Weekdays::model()->findAll("batch_id=:x AND weekday<>:y", array(':x'=>$batch->id,':y'=>"0"));
	//echo count($weekdays);
	//echo $batch->id;
	if(count($weekdays)==0)
	{
	?>
		<span style="color:#F00; font-weight:bold">*<?php Yii::t('app','Batch Weekdays not set. System default weekdays will be selected.'); ?></span>
		<?php	
		$weekdays = Weekdays::model()->findAll("batch_id IS NULL AND weekday<>:y",array(':y'=>"0"));
	}
	foreach($weekdays as $weekday)
	{
		
		$weekday->weekday = $weekday->weekday - 1;
		if($weekday->weekday <= 0)
		{
			$weekday->weekday = 7;
		}
		$weekArray[] = $weekday->weekday;
	}
	//var_dump($weekArray);
	
	foreach($batch_days as $batch_day)
	{
		$week_number = date('N', strtotime($batch_day));
					
		//echo $day.'='.$week_number.'<br/>';
		if(in_array($week_number,$weekArray)) // If checking if it is a working day
		{
			array_push($days,$batch_day);
		}
	}
$posts=Students::model()->findAll("id=:x", array(':x'=>$_REQUEST['id']));
$j=0;
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

foreach($posts as $posts_1)
{
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
		echo  $this->renderPartial('ajax',array('day'=>$i,'month'=>$mon_num,'year'=>$curr_year,'emp_id'=>$posts_1->id,'days'=>$days,'holiday_arr'=>$holiday_arr, 'bid'=>$bid));
		echo '</span><div  id="jobDialog123'.$i.$posts_1->id.'"></div></td>';
		echo '</span><div  id="jobDialogupdate'.$i.$posts_1->id.'"></div></td>';
    }
    ?>
</tr>
<?php $j++; }?>
</table>
<?php } ?>
</div>
<!------------------------pdf-buttn-->
 
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
