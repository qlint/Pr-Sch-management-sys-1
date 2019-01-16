<!-- Begin Coda Stylesheets -->
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/coda-slider-2.0.css" type="text/css" media="screen" />
<script type='text/javascript' src='<?php echo Yii::app()->request->baseUrl; ?>/js/portal/fullcalendar/fullcalendar.js'></script>
<script language="javascript">
	function showsearch()
	{
		if ($("#seachdiv").is(':hidden'))
		{
			$("#seachdiv").show();
		}
		else
		{
			$("#seachdiv").hide();
		}
	}

	function getstudent() // Function to see student profile
	{
		var studentid = document.getElementById('studentid').value;
		var yearid = document.getElementById('yearid').value;
		if(studentid!='' && yearid!='')
		{
			window.location= 'index.php?r=parentportal/default/attendance&id='+studentid+'&yid='+yearid;	
		}
		else
		{
			window.location= 'index.php?r=parentportal/default/attendance';
		}
	}
	function getyear()
	{
		var studentid = document.getElementById('studentid').value;
		var yearid = document.getElementById('yearid').value;
		if(yearid!='')
		{
			window.location= 'index.php?r=parentportal/default/attendance&id='+studentid+'&yid='+yearid;	
		}
		else
		{
			window.location= 'index.php?r=parentportal/default/attendance';
		}
	}

</script>

<?php Yii::app()->clientScript->registerCoreScript('jquery');?>



<?php $this->renderPartial('leftside');?> 
    <?php
    $cal ='{
    title: "'.Yii::t('app','All Day Event').'",
    start: new Date(y, m, 1)
    },';
    $m='';
    $d='';
    $y='';
    
    $guardian = Guardians::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
	$criteria = new CDbCriteria;		
	$criteria->join = 'LEFT JOIN guardian_list t1 ON t.id = t1.student_id'; 
	$criteria->condition = 't1.guardian_id=:guardian_id and t.is_active=:is_active and is_deleted=:is_deleted';
	$criteria->params = array(':guardian_id'=>$guardian->id,':is_active'=>1,'is_deleted'=>0);
	$students = Students::model()->findAll($criteria); 
	
	if(count($students)==1) // Single Student 
	{
		$attendances = StudentAttentance::model()->findAll('student_id=:x group by date',array(':x'=>$students[0]->id));
	}
	elseif(isset($_REQUEST['id']) and $_REQUEST['id']!=NULL) // If Student ID is set
	{
		$attendances = StudentAttentance::model()->findAll('student_id=:x group by date',array(':x'=>$_REQUEST['id']));
		
	}
	elseif(count($students)>1) // Multiple Student
	{
    	$attendances = StudentAttentance::model()->findAll('student_id=:x group by date',array(':x'=>$students[0]->id));
	}
    foreach($attendances as $attendance)
    {
		$m=date('m',strtotime($attendance['date']))-1;
		$d=date('d',strtotime($attendance['date']));
		$y=date('Y',strtotime($attendance['date']));
		$leave_types = StudentLeaveTypes::model()->findByAttributes(array('id'=>$attendance->leave_type_id));
		if($leave_types!=NULL)
		{
		$cal .= "{
		title: '".'<div align="center" title="Reason: '.$attendance->reason.'"><span class="abs1" style="color:'.$leave_types->colour_code.';text-align:center;padding-top:1px;font-size:15px">'.$leave_types->label.'</span>'.'</div>'."',
		start: new Date('".$y."', '".$m."', '".$d."')
		},";
		}
		else
		{
			$cal .= "{
				title: '".'<div align="center" title="Reason: '.$holiday->title.'"><img src="images/portal/atend_cross.png" width="40" border="0"  height="40" /></div>'."',
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
				title: '".'<div align="center" title="Reason: '.$holiday->title.'"><img src="images/portal/holiday.png" width="40" border="0"  height="40" /></div>'."',
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
				title: '".'<div align="center" title="Reason: '.$holiday->title.'"><img src="images/portal/holiday.png" width="40" border="0"  height="40" /></div>'."',
				start: new Date('".$y."', '".$m."', '".$d."')
				},";	
		}
	}
    ?>
 
<div class="pageheader">
    <div class="col-lg-8">
     <h2><i class="fa fa-file-text"></i> <?php echo Yii::t('app','Attendance'); ?> <span><?php echo Yii::t('app','View your attendance here'); ?></span></h2>
    </div>
    
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
         
          <li class="active"><?php echo Yii::t('app','Attendance'); ?></li>
        </ol>
      </div>
     
     <div class="clearfix"></div>
      
    </div>
    
<script type='text/javascript'>
$.noConflict();
jQuery( document ).ready(function( $ ) {
        $(document).ready(function(){
			//var studid = document.getElementById('studentid').value;
			//if(studid!='')
			//{
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
			//}
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
        <div class="contentpanel">
    	<!--<div class="col-sm-9 col-lg-12">-->
		
		
		
			
			 <?php
                if(count($students)>1)
				{
					 $student_list = CHtml::listData($students,'id','studentnameforparentportal');
					 $acadyear = AcademicYears::model()->findAllByAttributes(array('status'=>1));
					 $yearlist = CHtml::listData($acadyear,'id','name');
				?>
                <div class="people-item">
                    <div class="col-lg-3">
                            <?php
                            if($_REQUEST['yid']!=NULL)
                                $yearid = $_REQUEST['yid'];
                            else
                                $yearid = $acadyear[0]->id;
                            if($_REQUEST['id']!=NULL)
                                $stdid = $_REQUEST['id'];
                            else
                                $stdid = $students[0]->id;
                            echo Yii::t('app','Academic Year').CHtml::dropDownList('yid','',$yearlist,array('prompt'=>Yii::t('app','Select Year'),'id'=>'yearid','style'=>'width:auto;display: inline; margin-left: 7px;','class'=>'form-control input-sm mb14','options'=>array($yearid=>array('selected'=>true))));
                            ?>
                             <!-- END div class="academic year" -->
                     </div>
                <div class="col-lg-5">
                            <?php
                            echo Yii::t('app','Viewing Attendance of ').CHtml::dropDownList('sid','',$student_list,array('prompt'=>Yii::t('app','Select'),'id'=>'studentid','style'=>'width:auto;display: inline; margin-left: 7px;','class'=>'form-control input-sm mb14','options'=>array($stdid=>array('selected'=>true)),'onchange'=>'getstudent();'));
                            ?> 
                            <!-- END div class="student_dropdown" -->
                        
                 </div>
                 <div class="clearfix"></div>
             </div>   	
                	
                <?php
				}
				?>
		
		
        <div class="">
		
		<?php 
			if($_REQUEST['id']!=NULL)
			{
		?>	
		<div class="panel-heading">
              <!-- panel-btns -->
              <h3 class="panel-title">Calendar </h3>
			   <?php
		 	echo CHtml::link(Yii::t('app','View Absence Details'), array('Default/AbsenceDetails','id'=>$_REQUEST['id'],'yid'=>$_REQUEST['yid']),array('class'=>'btn btn-danger pull-right','style'=>'margin-top:-25px;'));
		?>
            </div>
			
			
        	<div class="people-item">
     	 <div  style="cursor:pointer" id="shbar"></div>
      		<div id="req_res">
       
               <div id='calendar' style="padding:20px 0 0 0;"></div>
            </div> 
            </div> 
       <?php
			}
			elseif(count($students)==1 or $_REQUEST['id']==NULL)
			{
				
			?>
			
			<div class="panel-heading">
              <!-- panel-btns -->
              <h3 class="panel-title">Calendar </h3>
			  <?php
		 	echo CHtml::link(Yii::t('app','View Absence Details'), array('Default/AbsenceDetails','id'=>$students[0]->id),array('class'=>'btn btn-danger pull-right','style'=>'margin-top:-25px;'));
		?>
            </div>
        	<div class="people-item">
     	 <div  style="cursor:pointer" id="shbar"></div>
      		<div id="req_res">
        
               <div id='calendar' style="padding:20px 0 0 0;"></div>
            </div> 
            </div> 
       <?php
			}
		?>

</div>
</div>