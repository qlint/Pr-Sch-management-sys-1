<style>
.scrollbox1 {
   overflow: auto;
    width: auto !important;
    height: 440px;
}
.scrollbox2 {
   overflow: auto;
    width: auto !important;
    height:400px;
    padding: 0 5px;
}

.scrollbox5 {
   overflow: auto;
    width: auto !important;
    height:359px;
    padding: 0 5px;
}

button, input[type="submit"]{ border: 0px solid #cbcbcb !important;
    border-radius: 0px !important;
	padding: 12px 6px !important;}

.ui-dialog .ui-dialog-titlebar{ padding:0px;
	position:inherit;}
	
.ui-widget-content{ box-shadow:0px;
	padding:0px !important;}
	
.e_pop_bttm{ min-height:40px !important; }

.e_pop_top{ min-height:122px !important}

.ui-widget-header{ border:0px !important;
	background:none !important;}

</style>
<script language="javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/enscroll-0.6.1.min.js"></script>
 <?php $settings=UserSettings::model()->findByAttributes(array('user_id'=>1)); ?>
 <?php $this->renderPartial('leftside');?> 
 
 <div class="pageheader">
      <h2><i class="fa fa-home"></i> <?php echo Yii::t('app','Dashboard');?> <span><?php echo Yii::t('app','View your Dashboard here'); ?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:');?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
          <li class="active"><?php echo Yii::t('app','Dashboard');?></li>
        </ol>
      </div>
    </div>
    
    
     <div class="contentpanel">
    	<div class="col-sm-9 col-lg-12 col-4-reqst">
               
            
          
          
            <div class="row">
            <div class="col-md-6 col-4-reqst">
            <div class="panel-heading">
              <h4 class="panel-title dashbord_icon"><i class="fa fa-bullhorn"></i><?php echo Yii::t('app','News'); ?></h4>
            </div>
            <div class="people-item" style="height:517px; overflow:hidden;">
            <div class="table-responsive">
<div class="main_box"> 
     		<div class="scrollbox1">
    <?php 
	//$newss = DashboardMessage::model()->findAllByAttributes(array('recipient_id'=>Yii::app()->getModule('mailbox')->newsUserId));
        $newss = DashboardMessage::model()->findAll(array("condition"=>"recipient_id='".Yii::app()->getModule('mailbox')->newsUserId."'",'order'=>'message_id DESC'));
	if($newss and $newss!=NULL)
	{ 
		 foreach($newss as $news)
		 { ?>
           <div class="main_box1">         
           <div id="home3" class="tab-pane tab-pane_dashboard active">
            <h4 class="dark"><?php echo html_entity_decode(ucfirst(@Mailbox::model()->findByAttributes(array('conversation_id'=>$news->conversation_id))->subject)) ;?></h4>
            <p><?php echo html_entity_decode(ucfirst($news->text)); ?></p>
          </div>
           </div>
					
					
	<?php }
	}
	else
	{?>
    
    		<div id="home3" class="tab-pane active">
            <h4 class="dark"><?php echo Yii::t('app','No News'); ?></h4>
            <p>. . . .</p>
          </div>
               
    <?php } ?>
      </div>
      </div>
          </div>
          
          
                <!-- panel -->
                
            </div>
            </div>
            
            <div class="col-md-6 col-4-reqst">
              <div class="panel-heading">
              <h4 class="panel-title dashbord_icon"><i class="fa fa-calendar"></i><?php echo Yii::t('app','Events'); ?></h4>
            </div>
            <div class="people-item" style=" overflow:hidden;">
            <div class="table-responsive">
          
           
            <ul class="nav nav-tabs nav-dark">
          <li class="active"><a data-toggle="tab" href="#home2"><strong><?php echo Yii::t('app','Today');?></strong></a></li>
          <li><a data-toggle="tab" href="#profile2"><strong><?php echo Yii::t('app','Current Week');?></strong></a></li>
          <li><a data-toggle="tab" href="#about2"><strong><?php echo Yii::t('app','Next Week');?></strong></a></li>
          <li><a data-toggle="tab" href="#contact2"><strong><?php echo Yii::t('app','Next month');?></strong></a></li>
        </ul>
        
        <?php 
		$roles = Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role
        foreach($roles as $role)
        {
            $rolename = $role->name;
        }
        
        $criteria = new CDbCriteria;
		$criteria->order = 'start DESC';
			if($rolename!= 'Admin')
			{
			
			$criteria->condition = 'placeholder = :default or placeholder=:placeholder';
			$criteria->params[':placeholder'] = $rolename;
			$criteria->params[':default'] = '0';
			}
		$events = Events::model()->findAll($criteria);
		
		if($events and $events!=NULL)
		{
		foreach($events as $event)
        {
			
			
			$today              = strtotime("00:00:00");
			$next_monday = strtotime('Next Monday', $today);
			$second_next_monday = strtotime('+1 week',$next_monday);
			$next_month = strtotime('+1 month',$today);
			$next_month_start = strtotime('first day of this month',$next_month);
			$next_month_end = strtotime('first day of next month',$next_month);
			
			
			
			
			if(date("Y-m-d",$event->start) == date('Y-m-d') )
			{
			$events_sameday[] = $event ; 
			}
			elseif($event->start >= $today and $event->start < $next_monday)
			{
			$events_sameweek[] = $event ; 
			}
			elseif($event->start >= $next_monday and $event->start < $second_next_monday)
			{
			$events_nextweek[] = $event ; 	
			}
			elseif($event->start >= $next_month_start and $event->start < $next_month_end)
			{
			$events_nextmonth[] = $event ; 	
			}
		
			
		}
	
		
		}
		
		
		?>
        
        
        <div class="tab-content mb30 scrollbox2" >
          <div id="home2" class="tab-pane active">
         <div class="widget-messaging">
          <ul>
          <?php 
		if($events_sameday and $events_sameday!=NULL)
		{
		foreach($events_sameday as $event_sameday)
		{
			if($settings!=NULL)
			{	
				$time=Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
				date_default_timezone_set($time->timezone);
				$date_1 = date($settings->displaydate,$event_sameday->start);
				$time=date($settings->timeformat,$event_sameday->start);
				
			}
			echo '<li>';
			echo '<small class="pull-right">'.$date_1.'&nbsp;&nbsp;  '.$time.'</small>';
			echo CHtml::ajaxLink('<h4 class="sender">'.substr($event_sameday->title,0,25).'</h4>'
				,$this->createUrl('default/view',array('event_id'=>$event_sameday->id)),array('update'=>'#jobDialog'),array('id'=>'showJobDialog1'.$event_sameday->id,'class'=>'add'));
				echo '<small>'.substr($event_sameday->desc,0,50).'</small>';
				
				echo '</li>';
		}
		}
		else
		{
			echo '<p style="padding:40px; text-align:center;">'.Yii::t('app','No Events Today').'</p>';
		}
		?>
          </ul>
         </div> 
          
          
          </div>
          <div id="profile2" class="tab-pane">
          	 <div class="widget-messaging">
          <ul>
            <?php 
				if($events_sameweek and $events_sameweek!=NULL)
				{
				foreach($events_sameweek as $event_sameweek)
				{
					if($settings!=NULL)
					{	
						$time=Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
						date_default_timezone_set($time->timezone);
						
						$date_1 = date($settings->displaydate,$event_sameweek->start);
						$time=date($settings->timeformat,$event_sameweek->start);
					}			
					echo '<li>';
					echo '<small class="pull-right">'.$date_1.'&nbsp;&nbsp;'.$time.'</small>';
					echo CHtml::ajaxLink('<h4 class="sender">'.substr($event_sameweek->title,0,25).'</h4>'
						,$this->createUrl('default/view',array('event_id'=>$event_sameweek->id)),array('update'=>'#jobDialog'),array('id'=>'showJobDialog1'.$event_sameweek->id,'class'=>'add'));
						echo '<small>'.substr($event_sameweek->desc,0,50).'</small>';
						
						echo '</li>';
				
				
				
				
				
				}
				}
				else
				{
					echo '<p style="padding:40px; text-align:center;">'.Yii::t('app','No Upcoming Events This week').'</p>';
				}
				?>
                </ul></div>
          </div>
          <div id="about2" class="tab-pane">
           <div class="widget-messaging">
          <ul>
            <?php 
				if($events_nextweek and $events_nextweek!=NULL)
				{
				foreach($events_nextweek as $event_nextweek)
				{ 
					if($settings!=NULL)
					{	
						$time=Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
						date_default_timezone_set($time->timezone);
						
						$date_1 = date($settings->displaydate,$event_nextweek->start);
						$time=date($settings->timeformat,$event_nextweek->start);	
					}	
					echo '<li>';
					echo '<small class="pull-right">'.$date_1.'&nbsp;&nbsp;'.$time.'</small>';
					echo CHtml::ajaxLink('<h4 class="sender">'.substr($event_nextweek->title,0,25).'</h4>'
					,$this->createUrl('default/view',array('event_id'=>$event_nextweek->id)),array('update'=>'#jobDialog'),array('id'=>'showJobDialog1'.$event_nextweek->id,'class'=>'add'));
					echo '<small>'.substr($event_nextweek->desc,0,50).'</small>';
				
				echo '</li>';
				}
				}
				else
				{
					echo '<p style="padding:40px; text-align:center;">'.Yii::t('app','No Upcoming Events Next Week').'</p>';
				}
				?>
                </ul></div>
          </div>
          <div id="contact2" class="tab-pane">
           <div class="widget-messaging">
          <ul>
            <?php 
				if($events_nextmonth and $events_nextmonth!=NULL)
				{
				foreach($events_nextmonth as $event_nextmonth)
				{	
					if($settings!=NULL)
					{	
						$time=Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
						date_default_timezone_set($time->timezone);
					
						$date_1 = date($settings->displaydate,$event_nextmonth->start);
						$time=date($settings->timeformat,$event_nextmonth->start);
					}	
					echo '<li>';
					echo '<small class="pull-right">'.$date_1.'&nbsp;&nbsp;'.$time.'</small>';
					echo CHtml::ajaxLink('<h4 class="sender">'.substr($event_nextmonth->title,0,25).'</h4>'
					,$this->createUrl('default/view',array('event_id'=>$event_nextmonth->id)),array('update'=>'#jobDialog'),array('id'=>'showJobDialog1'.$event_nextmonth->id,'class'=>'add'));
					echo '<small>'.substr($event_nextmonth->desc,0,50).'</small>';
					
				}
				}
				else
				{
					echo '<p style="padding:40px; text-align:center;">'.Yii::t('app','No Upcoming Events Next Month').'</p>';
				}
				?>
                </ul></div>
                
          </div>
        </div>
          
          </div>
         
          
                <!-- panel -->
                
            </div>
            <div id="jobDialog"></div>
            </div>
            
            
            
            
            
             </div>
             <div class="row">
            
            
            
       
       
       <div class="col-sm-9 col-md-12">
                        <div class="panel-heading">
             
              <h4 class="panel-title dashbord_icon"><i class="fa fa-envelope"></i><?php echo Yii::t('app','Mailbox');?></h4>
              
            </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                    
                        
                        <!-- pull-right -->

                       <div class="scrollbox5">
                       <div class="v-timeline"> 
                        <?php 
						$mailbox_messages = new CActiveDataProvider(Mailbox::model()->inbox(Yii::app()->user->Id)); 
						$this->widget('zii.widgets.CListView', array(
						'id'=>'mailbox',
						'dataProvider'=>$mailbox_messages,
						'itemView'=>'_news_list',
						'template'=>'{items}',
						
					)); ?>
                    </div>
											<!-- table-responsive -->
                       </div> 
                    </div><!-- panel-body -->
                </div><!-- panel -->
                
            </div>
      
    </div>

 <script>
     
$('.scrollbox1 .scrollbox2 .scrollbox5').enscroll({
    showOnHover: true,
    verticalTrackClass: 'track3',
    verticalHandleClass: 'handle3'
}); 
</script>

