
<script language="javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/enscroll-0.6.1.min.js">
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
<script>
	function getType()
	{
		var eventid = document.getElementById('eventid').value;
		if(eventid == '')
		{
			window.location= 'index.php?r=parentportal/default/eventlist';
		}
		else
		{
			window.location= 'index.php?r=parentportal/default/eventlist&type='+eventid;
		}
	}
</script>

<?php Yii::app()->clientScript->registerCoreScript('jquery');?>

<!--<link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->request->baseUrl; ?>/js/portal/fullcalendar/dbfullcalendar.css' />
<link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->request->baseUrl; ?>/js/portal/fullcalendar/fullcalendar.print.css' media='print' />-->
<script type='text/javascript' src='<?php echo Yii::app()->request->baseUrl; ?>/js/portal/fullcalendar/fullcalendar.js'></script>

      <?php $this->renderPartial('leftside');?> 
      <div class="pageheader">
        <div class="col-lg-8">
        <h2><i class="fa fa-calendar"></i><?php echo Yii::t('app','Calendar'); ?><span><?php echo Yii::t('app','View Event Calendar'); ?> </span></h2>
        </div>
        <div class="col-lg-2">
        
                </div>
    
        <div class="breadcrumb-wrapper">
            <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
                <ol class="breadcrumb">
                <!--<li><a href="index.html">Home</a></li>-->
                
                <li class="active"><?php echo Yii::t('app','Calendar'); ?></li>
            </ol>
        </div>
    
        <div class="clearfix"></div>
    
    </div>
     <div class="contentpanel">
     	<div class="row">
          <div class="col-lg-7 col-4-reqst" style="padding-bottom:20px;">
          
          <div class="panel-heading">
                          <!-- panel-btns -->
                          <h3 class="panel-title"><?php echo Yii::t('app','Event Calendar'); ?></h3>
                        </div>
    	<div class="people-item">
           <div style="position:absolute; width:auto; z-index:10; top:0px; right:25px; font-size:14px;">
                        	<?php
								echo Yii::t('app', 'Show');
								$data = EventsType::model()->findAll();
								$events_type = CHtml::listData($data,'id','name');
								foreach($data as $datum)
								{
									$options["options"][$datum->id] = array("style" => "background-color:".$datum->colour_code);
									
								}
								$options["prompt"] = Yii::t('app','All Events');
								$options["style"] = 'margin:10px';
								$options["onchange"] = 'getType();';
								$options["id"] = 'eventid';
								echo CHtml::dropDownList("Event_type",$_REQUEST['type'], $events_type,$options);
							?>
            </div>
            <?php
	        /*$cal ='{
							title: "All Day Event",
							start: new Date(y, m, 1)
						},';*/
			$cal ='';
			$m='';
			$d=''; 
			$y='';
			$roles = Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role
		     foreach($roles as $role)
				{
					$rolename = $role->name;
				}
				
				$criteria = new CDbCriteria;
				$criteria->order = 'start ASC';
				if($_REQUEST['type'])
				{
					$criteria->condition = 'type=:type';
					$criteria->params[':type'] = $_REQUEST['type'];
					if($rolename!= 'Admin')
					{
					
					$criteria->condition = $criteria->condition.' AND (placeholder= :default or placeholder=:placeholder)';
					$criteria->params[':placeholder'] = $rolename;
					$criteria->params[':default'] = '0';
					}
				}
				else
				{
					if($rolename!= 'Admin')
					{
					
					$criteria->condition = 'placeholder = :default or placeholder=:placeholder';
					$criteria->params[':placeholder'] = $rolename;
					$criteria->params[':default'] = '0';
					}
				}
				
				$events = Events::model()->findAll($criteria);
				if($settings!=NULL)
				{	
						$time=Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
						date_default_timezone_set($time->timezone);
						
				}
				foreach($events as $event)
				{	
					$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
					if($settings!=NULL)
					 {	
						
						$time=date($settings->displaydate.' '.$settings->timeformat,$event->start);
						$date=date($settings->displaydate,$event->start);
						$event->start = date($settings->timeformat,$event->start);
						$event->end = date($settings->timeformat,$event->end);
					 }
					 $m=date('m',strtotime($date))-1;
					 $d=date('d',strtotime($date));
					 $y=date('Y',strtotime($date));	
					 $event_type=EventsType::model()->findByPk($event->type);
					 $desc = preg_replace( "/\r|\n/", "", substr($event->desc,0,40));
		             $link = CHtml::ajaxLink(substr($event->title,0,25),$this->createUrl("default/view",array("event_id"=>$event->id)),array("update"=>"#jobDialog"),array("id"=>"showJobDialog1".$event->id,"class"=>"add"));
		

		
		      $cal .= '{
						title: "<span style=\" display:block;width:10px;margin:0 auto;height:10px;background-color:'.$event_type->colour_code.'\"></span>",
						start: new Date("'.$y.'", "'.$m.'", "'.$d.'")
					},';	
				
			}
			
				?>
              <div id="jobDialog"></div>
   
                 <div  id="req_res123">
                                           
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
				var title = prompt("<?php echo Yii::t('app','Event Title:'); ?>");
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

 
<div id="req_res">
<div id='calendar' style="padding:20px 0 0 0;"></div>
</div>
</div>
</div>
</div>
	



    <div class="col-lg-5 col-4-reqst" >
         <div class="panel panel-default panel-alt widget-messaging" >
              <div class="panel-heading">
               <h3 class="panel-title"><?php echo Yii::t('app','Events'); ?></h3>
              </div>
              <div id="scrollbox3">
              
              <div class="panel-body">
                  <ul>
                            <?php 
                             
                             $criteria->addCondition('DATE_FORMAT(FROM_UNIXTIME(start), "%Y-%m") =:eventdate');
                             $criteria->params[':eventdate'] = date('Y').'-'.date('m');
                            
                            $events = Events::model()->findAll($criteria);
                            $settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
                            if($settings!=NULL)

                            {	
                                        $time=Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
                                        date_default_timezone_set($time->timezone);
                                        
                            }
                            if($events){
                            foreach($events as $event) { 
                            
                                    
                                    if($settings!=NULL)
                                     {
                                        $time=date($settings->displaydate.' '.$settings->timeformat,$event->start);
                                        $date=date($settings->displaydate,$event->start);
                                        $event->start = date($settings->timeformat,$event->start);
                                        $event->end = date($settings->timeformat,$event->end);
                                     }
                                     $m=date('m',strtotime($date));
                                     
                                     $d=date('d',strtotime($date));
                                     $y=date('Y',strtotime($date));	
                                     $event_type=EventsType::model()->findByPk($event->type);
                                     $desc = preg_replace( "/\r|\n/", "", substr($event->desc,0,40));
                                     $link = CHtml::ajaxLink(substr($event->title,0,25),$this->createUrl("default/view",array("event_id"=>$event->id)),array("update"=>"#jobDialog"),array("id"=>"showJobDialog1".$event->id,"class"=>"add"));
                        
                            
                            
                            
                            
                            
                            ?>
                               <li>                                  
                                    <div class="events_hed">
                                    <div style="position:relative;" class="stripbx">
                                    <div class="strip-rtl" style="background-color:<?php echo $event_type->colour_code; ?>;"></div><?php echo $d; ?> <span><?php echo Yii::t('app',date('M', mktime(0, 0, 0, $m, 10)));; ?></span></div>                   
                                     </div>
                                    <div class="news_sub"><span><?php echo $event->title; ?></span><br>
                                    <small class="text-muted"><span class="more"><?php echo $event->desc; ?></span></small>
                                    </div> <div id="jobDialog"></div>
                                    <div class="clearfix"></div>
                              
                                </li>
                              <?php } }else{ ?>
								  
								  <li>
                                  <p><?php echo Yii::t('app','No Events'); ?></p>
                              
                                </li>
								  
								  
								 <?php }?>
                   </ul>
                </div>
             
              
              </div>  
       
         </div>
    </div>
	</div>
</div>
<script>
//full calendar additional events

$(document).on('click', "span.fc-button-next,span.fc-button-prev,span.fc-button-today", function() {
	
	var date = $("#calendar").fullCalendar('getDate');
  	var month_int = ('0' + (date.getMonth()+1)).slice(-2);
	var year_int = date.getFullYear();
     $.ajax({
		 url:"<?php echo Yii::app()->createUrl('/parentportal/default/currentEvents'); ?>",
	 	data:{month:month_int,year:year_int,type:"<?php if($_REQUEST['type'])echo $_REQUEST['type'];else echo 0;?>"},
	 	success:function(result){		 
   			$('#scrollbox3').html(result);
			showmore();
  		}
	});
});

$('#scrollbox3').enscroll({
    showOnHover: true,
    verticalTrackClass: 'track3',
    verticalHandleClass: 'handle3'
});
function showmore() {
    // Configure/customize these variables.
    var showChar = 100;  // How many characters are shown by default
    var ellipsestext = "...";
    var moretext = "<?php echo Yii::t('app','Show more >'); ?>";
    var lesstext = "<?php echo Yii::t('app','< Show less'); ?>";
    

    $('.more').each(function() {
        var content = $(this).html();
 
        if(content.length > showChar) {
 
            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);
 
            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
 
            $(this).html(html);
        }
 
    });
	
	$(".morelink").unbind('click'); 
    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
}
showmore();
</script>