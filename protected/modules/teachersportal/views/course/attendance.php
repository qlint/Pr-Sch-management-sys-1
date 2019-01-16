<link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->request->baseUrl; ?>/js/portal/fullcalendar/dbfullcalendar.css' />
<link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->request->baseUrl; ?>/js/portal/fullcalendar/fullcalendar.print.css' media='print' />
<script type='text/javascript' src='<?php echo Yii::app()->request->baseUrl; ?>/js/portal/fullcalendar/fullcalendar.js'></script>
<div id="parent_Sect">
	<?php $this->renderPartial('/default/leftside');?> 
    <?php    
	
    /*$guard = Guardians::model()->findByAttributes(array('id'=>$student->parent_id));
    $settings=UserSettings::model()->findByAttributes(array('user_id'=>1));*/
	?>
    
    <?php
	$cal = '{title: "All Day Event",
			start: new Date(y, m, 1)},';
	$m='';
	$d='';
	$y='';
	$student = Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));	
	$batch = Batches::model()->findByAttributes(array('id'=>$_REQUEST['id']));
		
	
	/*
	* Get start and end date of the batch in Y-m-d format
	*/
	
	$batch_start = date('Y-m-d',strtotime($batch->start_date));
	$batch_end = date('Y-m-d',strtotime($batch->end_date));
	
	$begin_yr = date('Y',strtotime($batch->start_date));
	$begin_mon = date('m',strtotime($batch->start_date));
	
	$end_yr = date('Y',strtotime($batch->end_date));
	$end_mon = date('m',strtotime($batch->end_date));
	
	$cur_yr = date('Y');
	$cur_mon = date('m');
	
	$begin_diff = (($begin_yr - $cur_yr) * 12) + ($begin_mon - $cur_mon);
	$end_diff = (($end_yr - $cur_yr) * 12) + ($end_mon - $cur_mon);
	
	
	$today = date('Y-m-d');
	$days = StudentAttentance::model()->createDateRangeArray($batch_start,$batch_end);
	
	
	foreach($days as $day)
	{
		$attendance = StudentAttentance::model()->findByAttributes(array('student_id'=>$student->id,'date'=>$day));
		if($attendance)
		{
			$m=date('m',strtotime($attendance['date']))-1;
			$d=date('d',strtotime($attendance['date']));
			$y=date('Y',strtotime($attendance['date']));
			$cal .= "{
				title: '".'<div align="center" title="Reason: '.$attendance->reason.'"><img src="images/portal/atend_cross.png" width="26" border="0"  height="25" /></div>'."',
				start: new Date('".$y."', '".$m."', '".$d."')
			},";
		}
		else
		{
			if($day < $today)
			{
				$m=date('m',strtotime($day))-1;
				$d=date('d',strtotime($day));
				$y=date('Y',strtotime($day));
				$cal .= "{
					title: '".'<div align="center"><img src="images/portal/atend_tick.png" width="26" border="0"  height="25" /></div>'."',
					start: new Date('".$y."', '".$m."', '".$d."')
				},";
			}
			
		}
		
	}
	?>
	<div id="parent_rightSect">
        <div class="parentright_innercon">
            <?php $this->renderPartial('batch');?>
            <div class="edit_bttns" style="top:100px; right:25px">
                <ul>
                    <li>
                    <?php //echo CHtml::link('<span>'.Yii::t('studentportal','My Courses').'</span>', array('/studentportal/course'),array('class'=>'addbttn last'));?>
                    </li>
                </ul>
            </div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            	<tr>
					<td width="3.5%" valign="top"></td>
                    <td width="95%" valign="top">
                        <div>
                            <div style="cursor:pointer" id="shbar"></div>
                                <!--Visits Tio Bar-->
                                <br/>
                                <div id="req_res">
                                	<div id='calendar' style="padding-right:20px;"></div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td width="3%" valign="top" id="tpanel">
                    </td>
                </tr>
            </table>
        </div> <!-- END div class="parentright_innercon" -->
    </div> <!-- END div id="parent_rightSect" -->
    <div class="clear"></div>
</div> <!-- END div id="parent_Sect" -->
<div class="clear"></div>
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
		events: [ <?php echo $cal; ?>],
		viewDisplay   : function(view) {
			var now = new Date(); 
			var end = new Date();
			var begin =  new Date ();

		   
			begin.setMonth(now.getMonth() - <?php echo $begin_diff; ?>); //Adjust as needed
			end.setMonth(now.getMonth() + <?php echo $end_diff; ?>); //Adjust as needed
			var cal_date_string = view.start.getMonth()+'/'+view.start.getFullYear();
			var cur_date_string = now.getMonth()+'/'+now.getFullYear();
			var end_date_string = end.getMonth()+'/'+end.getFullYear();
			var begin_date_string = begin.getMonth()+'/'+begin.getFullYear();

			if(cal_date_string == begin_date_string) { jQuery('.fc-button-prev').addClass("fc-state-disabled"); }
			else { jQuery('.fc-button-prev').removeClass("fc-state-disabled"); }

			if(end_date_string == cal_date_string) { jQuery('.fc-button-next').addClass("fc-state-disabled"); }
			else { jQuery('.fc-button-next').removeClass("fc-state-disabled"); }
		}
	});
});	

$(document).ready(function(){
	 $("#shbar").click(function(){
       $('#tpanel').toggle();
     });
});

</script>