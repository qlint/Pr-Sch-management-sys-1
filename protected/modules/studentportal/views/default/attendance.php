<?php $student=Students::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	  $batches    = 	BatchStudents::model()->studentBatch($student->id);
	  if(count($batches) == 1){
			$batch    	= 	BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'result_status'=>0));
			$bid 		=  $batch->batch_id;		
		}
		elseif(isset($_REQUEST['bid']) and $_REQUEST['bid']!=NULL){
			$bid 		=  $_REQUEST['bid'];	
		}
		elseif(count($batches)>1  or $_REQUEST['bid'] == NULL){
			$batch    	= 	BatchStudents::model()->findByAttributes(array('student_id'=>$student->id, 'result_status'=>0, 'batch_id'=>$batches[0]->id));
			$bid 		=  $batch->batch_id;	
		}
?> 
<script language="javascript">
function getmode(){
	//var student_id	= <?php //echo $student->id; ?>;
	var batch_id	= $('#batch_id').val();
		if(batch_id != ''){
			window.location= 'index.php?r=studentportal/default/attendance&bid='+batch_id;
		}
		else{
			window.location= 'index.php?r=studentportal/default/attendance';
		}
};
</script>
<!-- Begin Coda Stylesheets -->
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/coda-slider-2.0.css" type="text/css" media="screen" />
<?php Yii::app()->clientScript->registerCoreScript('jquery');?>

<!--<link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->request->baseUrl; ?>/js/portal/fullcalendar/dbfullcalendar.css' />
<link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->request->baseUrl; ?>/js/portal/fullcalendar/fullcalendar.print.css' media='print' />-->
<script type='text/javascript' src='<?php echo Yii::app()->request->baseUrl; ?>/js/portal/fullcalendar/fullcalendar.js'></script>
<?php $this->renderPartial('leftside');?> 
      <div class="pageheader">
			<div class="col-lg-8">
				<h2><i class="fa fa-file-text"></i><?php echo Yii::t('app','Attendance'); ?><span><?php echo Yii::t('app','View Attendance'); ?> </span></h2>
			</div>
			<div class="col-lg-2"></div>
			<div class="breadcrumb-wrapper">
				<span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
					<ol class="breadcrumb">
						<li class="active"><?php echo Yii::t('app','Calender'); ?></li>
					</ol>
			</div>
			<div class="clearfix"></div>
	</div>
    <div class="contentpanel">
		<div class="panel-heading">
		  <h3 class="panel-title"><?php echo Yii::t('app','Calendar'); ?></h3>
		</div>
		<div class="people-item">
			<?php if($batches!=NULL){ ?>
			 <div class="attendance-ul-block">
				<ul>
					<?php
					   if(Configurations::model()->studentAttendanceMode() != 2){ 
							echo '<li>'.CHtml::link(Yii::t('app','View Absence Details'), array('Default/AbsenceDetails','bid'=>$bid),array('class'=>'btn btn-primary pull-right')).'</li>';
					   }
					   ?>
					
					  <?php
					  if(Configurations::model()->studentAttendanceMode() != 1){
					   echo '<li>'.CHtml::link(Yii::t('app','Subject Wise Attendance'), array('Default/subwiseattendance', 'bid'=>$bid),array('class'=>'btn btn-primary pull-right')).'</li>';
					}
					?>
				</ul> 	
			 </div>
<?php
if($batches){
	foreach($batches as $batch){
		$batch_list[$batch->id]	= ucfirst($batch->name);
	}
}
?>
<div class="contentpanel">
	<div class="people-item">
    	<div class="row">
            <div class="col-md-4">
			 <label>
		<?php
        echo Yii::t('app','Viewing Attendance of').' '.Students::model()->getAttributeLabel('batch_id');?>
		</label>
		 <br />
		 <?php
        echo CHtml::dropDownList('bid','',$batch_list,array('encode'=>false,'id'=>'batch_id','style'=>'width:100%;display: inline;','class'=>'input-form-control','options'=>array($bid=>array('selected'=>true)),'onchange'=>'getmode();'));
        $cal ='{title: "'.Yii::t('app','All Day Event').'",
        start: new Date(y, m, 1)
        },';
        ?>
     </div>
        </div>
        <div class="clearfix"></div>
    </div>               
                <?php
$m='';
$d='';
$y='';

		$criteria = new CDbCriteria;
		$criteria->condition = 'student_id=:student_id and batch_id = :batch_id';
		$criteria->params = array(':student_id'=>$student->id,'batch_id'=>$bid);
		$criteria->group ='date';
		$student=StudentAttentance::model()->findAll($criteria);
foreach($student as $student_1)
{
		$m=date('m',strtotime($student_1['date']))-1;
		$d=date('d',strtotime($student_1['date']));
		$y=date('Y',strtotime($student_1['date']));
		
		$leave_types = StudentLeaveTypes::model()->findByAttributes(array('id'=>$student_1->leave_type_id));
		if($leave_types!=NULL)
		{
$cal .= "{
					title: '".'<div align="center" title="'.Yii::t('app','Reason:').$student_1->reason.'"><span class="abs1" style="color:'.$leave_types->colour_code.';text-align:center;padding-top:1px;font-size:15px">'.$leave_types->label.'</span>'.'</div>'."',
					start: new Date('".$y."', '".$m."', '".$d."')
				},";
		}
		else
		{
			$cal .= "{
				title: '".'<div align="center" title="'.Yii::t('app','Reason:').$holiday->title.'"><img src="images/portal/atend_cross.png" width="40" border="0"  height="40" /></div>'."',
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
			$date_range = StudentAttentance::model()->createDateRangeArray(date('Y-m-d',$holiday->start),date('Y-m-d',$holiday->end));
			foreach ($date_range as $value) {
				
				$m=date('m',strtotime($value))-1;
				$d=date('d',strtotime($value));
				$y=date('Y',strtotime($value));
				$cal .= "{
				title: '".'<div align="center" title="'.Yii::t('app','Reason:').$holiday->title.'"><img src="images/portal/holiday.png" width="40" border="0"  height="40" /></div>'."',
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
				title: '".'<div align="center" title="'.Yii::t('app','Reason:').$holiday->title.'"><img src="images/portal/holiday.png" width="40" border="0"  height="40" /></div>'."',
				start: new Date('".$y."', '".$m."', '".$d."')
				},";	
		}
	}
	?>              
<div   id="req_res123">
<?php if(isset($bid) and $bid!=NULL){?>
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
				dayNames:["<?php echo Yii::t('app','sun'); ?>","<?php echo Yii::t('app','mon'); ?>","<?php echo Yii::t('app','tue'); ?>","<?php echo Yii::t('app','wed'); ?>","<?php echo Yii::t('app','thu'); ?>","<?php echo Yii::t('app','fri'); ?>","<?php echo Yii::t('app','sat'); ?>"],
				select: function(start, end, allDay) {
					var title = prompt('<?php echo Yii::t('app','Event Title:'); ?>');
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
<?php } ?>
	<script type="text/javascript">
	$(document).ready(function(){
		
		 $("#shbar").click(function(){
		   $('#tpanel').toggle();
			});
	});
	</script>
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
<div id="req_res">
<div id='calendar' style="padding-right:20px;"></div>
	</div>
	<?php }
		else{
			echo Yii::t('app','No').' '.Students::model()->getAttributeLabel('batch_id').''.Yii::t('app','es are in Progress');
		}?>
	</div>
</div>
