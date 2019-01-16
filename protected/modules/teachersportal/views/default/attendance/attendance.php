<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets/ad2b9968/jquery.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/dash_board.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/listnav.css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/highchart/highcharts.js"></script>
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

<link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->request->baseUrl; ?>/js/portal/fullcalendar/dbfullcalendar.css' />
<link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->request->baseUrl; ?>/js/portal/fullcalendar/fullcalendar.print.css' media='print' />
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
    
    /*$guard=Guardians::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
    $student=StudentAttentance::model()->findAll('student_id=:x group by date',array(':x'=>$guard->ward_id));
    
    foreach($student as $student_1)
    {
            $m=date('m',strtotime($student_1['date']))-1;
            $d=date('d',strtotime($student_1['date']));
            $y=date('Y',strtotime($student_1['date']));
    $cal .= "{
                        title: '".'<div align="center"><img src="images/portal/atend_cross.png" width="26" border="0"  height="25" /></div>'."',
                        start: new Date('".$y."', '".$m."', '".$d."')
                    },";
    
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
          <?php
		  	$employee=Employees::model()->findByAttributes(array('uid'=>Yii::app()->user->id));
			$is_classteacher = Batches::model()->findAllByAttributes(array('employee_id'=>$employee->id));
			$stud_flag = 0;
			if($is_classteacher!=NULL){
				$stud_flag = 1;
			}
		  ?>
          <div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-file-text"></i><?php echo Yii::t('app', 'Attendance');?><span><?php echo Yii::t('app', 'View your attendance here');?> </span></h2>
        </div>
        <div class="col-lg-2">
        
                </div>
    
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t('app', 'You are here:');?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t('app', 'Attendance');?></li>
            </ol>
        </div>    
        <div class="clearfix"></div>    
    </div>
        <div class="contentpanel">
    
    
<div class="panel-heading">

<h3 class="panel-title"><?php echo Yii::t('app','View Attendance'); ?></h3></div>


<div class="people-item">

 <?php $this->renderPartial('/default/employee_tab');?>

    
        <div id="parent_rightSect">
            
                    
                </div>
                <div class="yellow_bx" style="background-image:none;width:90%;padding-bottom:45px;">
                    <?php /*?><div class="y_bx_head" style="font-size:14px;">
                       &nbsp;
                    </div><?php */?>
                    <div class="subtitle-prtl">
                    	<h5><?php echo Yii::t('app','View My Attendance'); ?> / <?php echo '<span>'.Yii::t('app','Displays your attendance status.').'</span>'; ?></h5>
                    </div> 
                    <?php 
					if($stud_flag == 1)
					{
					?>
                    <div class="subtitle-prtl">
                    	<h5><?php echo Yii::t('app','Manage Student Attendance'); ?> / <?php echo '<span>'.Yii::t('app','View/Mark/Edit/Delete the attendance of the students of your.').' '.Yii::app()->getModule('students')->fieldLabel("Students", "batch_id").'</span>'; ?></h5>
                    </div>
                      
                    <?php
					}
					?>

        		</div>
        	</div>
        </div>
        <div class="clear"></div>
    </div>
</div>
