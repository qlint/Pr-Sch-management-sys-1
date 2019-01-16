<link rel='stylesheet' type='text/css' href='js/fullcalendar/fullcalendar.css' />
<link rel='stylesheet' type='text/css' href='js/fullcalendar/fullcalendar.print.css' media='print' />
<script type='text/javascript' src='js/jquery-1.5.2.min.js'></script>
<script type='text/javascript' src='js/jquery-ui-1.8.11.custom.min.js'></script>
<script type='text/javascript' src='js/fullcalendar/fullcalendar.min.js'></script>
<script type='text/javascript'>

	$(document).ready(function() {
	
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		$('#calendar').fullCalendar({
			editable: true,
			events: [
				{
					title: '<?php echo Yii::t('app','All Day Event'); ?>',
					start: new Date(y, m, 15)
				},
				{
					title: '<?php echo Yii::t('app','Long Event'); ?>',
					start: new Date(y, m, d-5),
					end: new Date(y, m, d-2)
				},
				{
					id: 999,
					title: '<?php echo Yii::t('app','Repeating Event'); ?>',
					start: new Date(y, m, d-3, 16, 0),
					allDay: false
				},
				{
					id: 999,
					title: '<?php echo Yii::t('app','Repeating Event'); ?>',
					start: new Date(y, m, d+4, 16, 0),
					allDay: false
				},
				{
					title: '<?php echo Yii::t('app','Meeting'); ?>',
					start: new Date(y, m, d, 10, 30),
					allDay: false
				},
				{
					title: '<?php echo Yii::t('app','Lunch'); ?>',
					start: new Date(y, m, d, 12, 0),
					end: new Date(y, m, d, 14, 0),
					allDay: false
				},
				{
					title: '<?php echo Yii::t('app','Birthday Party'); ?> ',
					start: new Date(y, m, d+1, 19, 0),
					end: new Date(y, m, d+1, 22, 30),
					allDay: false
				},
				{
					title: '<?php echo Yii::t('app','Click for Google'); ?>',
					start: new Date(y, m, 28),
					end: new Date(y, m, 29),
					url: 'http://google.com/'
				}
			]
		});
		
	});

</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top">
    
    <?php $this->renderPartial('/default/left_side');?>
    
    </td>
    <td valign="top">
    <div class="cont_right formWrapper">
    <h1><?php echo Yii::t('app','Events');?></h1><br />
<div class="formCon">
<div class="formConInner">


<div id='calendar'></div>
</div>
</div>

</div>
    </td>
  </tr>
</table>