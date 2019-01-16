
<style>
table{
	border-top:1px #CCC solid;
	margin:30px 0px;
	font-size:9px;
	border-right:1px #CCC solid;
}
td{
	border-left:1px #CCC solid;
	padding:5px 6px;
	border-bottom:1px #CCC solid;
}
</style>
<div class="atnd_Con">
<?php $batch=Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
 ?>
    
<?php
function getweek($date,$month,$year)
{
$date = mktime(0, 0, 0,$month,$date,$year); 
$week = date('w', $date); 
switch($week) {
case 0: 
return 'S<br>';
break;
case 1: 
return 'M<br>';
break;
case 2: 
return 'T<br>';
break;
case 3: 
return 'W<br>';
break;
case 4: 
return 'T<br>';
break;
case 5: 
return 'F<br>';
break;
case 6: 
return 'S<br>';
break;
}
}
?>
<?php
$subjects=Subjects::model()->findAll("batch_id=:x", array(':x'=>$_REQUEST['id']));

//echo CHtml::dropDownList('batch_id','',CHtml::listData(Subjects::model()->findAll("batch_id=:x",array(':x'=>$_REQUEST['id'])), 'id', 'name'), array('empty'=>'Select Type'));

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
		$curr_year = date('Y');
	}
	$num = cal_days_in_month(CAL_GREGORIAN, $mon_num, $curr_year); // 31
	?>
 <?php $college=Configurations::model()->findByPk(1); ?><?php echo $college->config_value ; echo Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").$name=$batch->name;?>
 
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr style="background:#dfdfdf;">
    <td><?php echo Yii::t('app','Name');?></td>
    <?php
    for($i=1;$i<=$num;$i++)
    {
        echo '<td>'.getweek($i,$mon_num,$curr_year).'<span>'.$i.'</span></td>';
    }
    ?>
</tr>
<?php $posts=Students::model()->findByAttributes(array('id'=>$_REQUEST['id']));

	$class = 'class="even"';	
	
 ?>
<tr <?php echo $class; ?> >
    <td class="name"><?php echo $posts->first_name; ?></td>
    <?php
    for($i=1;$i<=$num;$i++)
    {
        echo '<td>';
$find = StudentAttentance::model()->findAll("date=:x AND student_id=:y", array(':x'=>$curr_year.$mon_num.'-'.$i,':y'=>$posts->id));
if(count($find)==0)
{
echo '';
}
else
echo "<span style='color:#ce0606'><strong>X</strong></span>";
		
		echo '</td>';
    }
    ?>
</tr>
</table>
<?php } ?>
</div>
