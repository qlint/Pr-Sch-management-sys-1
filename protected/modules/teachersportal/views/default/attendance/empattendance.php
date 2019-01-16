<!-- Begin Coda Stylesheets -->
		<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/coda-slider-2.0.css" type="text/css" media="screen" />
<script language="javascript">
function showsearch()
{
	if ($("#seachdiv").is(':hidden')){
	$("#seachdiv").show();
	}
	else{
		$("#seachdiv").hide();
	}
}

</script>
       
 <?php //echo Yii::app()->user->agency_id ?>

<?php Yii::app()->clientScript->registerCoreScript('jquery');?>

<!--<link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->request->baseUrl; ?>/js/portal/fullcalendar/dbfullcalendar.css' />
<link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->request->baseUrl; ?>/js/portal/fullcalendar/fullcalendar.print.css' media='print' />-->
<script type='text/javascript' src='<?php echo Yii::app()->request->baseUrl; ?>/js/portal/fullcalendar/fullcalendar.js'></script>
<div id="parent_Sect">
      <?php $this->renderPartial('leftside');?> 
<?php
	

$cal ='{
					title: "All Day Event",
					start: new Date(y, m, 1)
				},';
$m='';
$d='';
$y='';
//$result=TaskAssignToPatients::model()->findAll(('status=:t1 OR status=:t2 OR status=:t3 OR status=:t4 OR  status=:t5 OR status IS NULL group by target_date'),array(':t1'=>'C',':t2'=>'S',':t3'=>'A',':t4'=>'E',':t5'=>'R'));

$employee = Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
$student  = EmployeeAttendances::model()->findAll('employee_id=:x group by attendance_date',array(':x'=>$employee->id));




foreach($student as $student_1)
{
		$m=date('m',strtotime($student_1['attendance_date']))-1;
		
		$d=date('d',strtotime($student_1['attendance_date']));
		$y=date('Y',strtotime($student_1['attendance_date']));
		if($student_1['half']==1){
			$cal .= "{
						title: '".'<div align="center"><img src="images/morning_half.png" width="26" border="0"  height="25" title="'.$student_1['reason'].'"/></div>'."',
						start: new Date('".$y."', '".$m."', '".$d."')
					},"; 
		}
		else if($student_1['half']==2){
			$cal .= "{
						title: '".'<div align="center"><img src="images/afternoon_half.png" width="26" border="0"  height="25" title="'.$student_1['reason'].'"/></div>'."',
						start: new Date('".$y."', '".$m."', '".$d."')
					},"; 
		}
		else{		
			$cal .= "{
						title: '".'<div align="center"><img src="images/portal/atend_cross.png" width="26" border="0"  height="25" title="'.$student_1['reason'].'"/></div>'."',
						start: new Date('".$y."', '".$m."', '".$d."')
					},"; 
		}
}

	$holidays = Holidays::model()->findAll();
	
	$holiday_arr=array();
	foreach($holidays as $key=>$holiday)
	{
		if(date('Y-m-d',$holiday->start)!=date('Y-m-d',$holiday->end))
		{
			$date_range = EmployeeAttendances::model()->createDateRangeArray(date('Y-m-d',$holiday->start),date('Y-m-d',$holiday->end));
			foreach ($date_range as $value) {
				
				$m=date('m',strtotime($value))-1;
				$d=date('d',strtotime($value));
				$y=date('Y',strtotime($value));
				$cal .= "{
				title: '".'<div align="center" title="'.Yii::t('app', 'Reason:').' '.$holiday->title.'"><img src="images/portal/holiday.png" width="40" border="0"  height="40" /></div>'."',
				start: new Date('".$y."', '".$m."', '".$d."')
				},";
				
				
			}
		}
		else
		{
			
				$m=date('m',strtotime(date('Y-m-d',$holiday->start)))-1;
				$d=date('d',strtotime(date('Y-m-d',$holiday->start)));
				$y=date('Y',strtotime(date('Y-m-d',$holiday->start)));
				$cal .= "{
				title: '".'<div align="center" title="'.Yii::t('app', 'Reason:').' '.$holiday->title.'"><img src="images/portal/holiday.png" width="40" border="0"  height="40" /></div>'."',
				start: new Date('".$y."', '".$m."', '".$d."')
				},";	
		}
	}


/*foreach($attendance as $attendance_1)
{       
		$dif=strtotime($attendance_1['start_date'])-strtotime($attendance_1['end_date']);
		
         if($dif!=0)
		{ 
		$begin     = new DateTime($attendance_1['start_date']);
		$end       = new DateTime($attendance_1['end_date']);
		$end       = $end->modify( '+1 day' ); 
		$interval  = new DateInterval('P1D');
		$daterange = new DatePeriod($begin, $interval ,$end);
		
		foreach($daterange as $date){
				
				$m=date('m',strtotime($date->format("Y-m-d")))-1;
				
				$d=date('d',strtotime($date->format("Y-m-d")));
				
		        $y=date('Y',strtotime($date->format("Y-m-d")));
				
				
				$cal .= "{
					
					title: '".'<div align="center"><img src="images/portal/atend_cross.png" width="26" border="0"  height="25" title="'.$attendance_1['reason'].'"/></div>'."',
					start: new Date('".$y."', '".$m."', '".$d."')
					
				},";
		}
		
		}
		else
		{
		$m=date('m',strtotime($attendance_1['start_date']))-1;
		
		$d=date('d',strtotime($attendance_1['start_date']));
		
		$y=date('Y',strtotime($attendance_1['start_date']));
		
		$cal .= "{
					title: '".'<div align="center"><img src="images/portal/atend_cross.png" width="26" border="0"  height="25" title="'.$attendance_1['reason'].'"/></div>'."',
					start: new Date('".$y."', '".$m."', '".$d."')
				},";
		}

}*/

?>
<div class="right_col"  id="req_res123">         
<script type='text/javascript'>


	$(document).ready(function() {
	
		var date = new Date();
		
		var d = date.getDate();
		
		var m = date.getMonth();
		var y = date.getFullYear();
	
		
		var calendar = $('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			selectable: false,
			selectHelper: true,
			dayNames:["sun","mon","tue","wed","thu","fri","sat"],
			select: function(start, end, allDay) {
				var title = prompt('Event Title:');
				if (title) {
					calendar.fullCalendar('renderEvent',
						{
							title: title,
							start: start,
							end: end,
							allDay: allDay
						},
						true // make the event "stick"
					);
				}
				calendar.fullCalendar('unselect');
			},
			editable: false,
			events: [ <?php echo $cal; ?>]
		});
		
	});
	

</script>

<script type="text/javascript">

$(document).ready(function(){
	
	 $("#shbar").click(function(){
		 
       $('#tpanel').toggle();
	 
	
        });

     
});
</script>
  <!--contentArea starts Here-->
  <div class="pageheader">
      <h2><i class="fa fa-file-text"></i> <?php echo Yii::t('app', 'Attendance');?> <span><?php echo Yii::t('app', 'Attendance here...');?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app', 'You are here:');?></span>
        <ol class="breadcrumb">
          <li class="active"><?php echo Yii::t('app', 'Attendance');?></li>
        </ol>
      </div>
</div>
  
 <div class="contentpanel">
	<div class="panel-heading" style="position:relative;">
    
    	<h3 class="panel-title"><?php echo Yii::t('app','My Attendance'); ?></h3>
	</div><div class="people-item">
    <?php $this->renderPartial('/default/employee_tab');?>
 
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td colspan="3">
    	
    </td>
</tr>
  <tr>
    <td width="95%"  valign="top">
    <div>
     	<div  style="cursor:pointer" id="shbar"></div>
            <!--Visits Tio Bar-->
            <br/>
            <div id="req_res">
                <div id='calendar' style="padding-right:20px;"></div>
            </div>
		</div>
	</td>
	<td width="3%" valign="top" id="tpanel"  >
	</td>
  </tr>
</table>

</div>
</div>
</div>
 <div class="clear"></div>
      </div>
