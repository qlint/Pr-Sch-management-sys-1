<div class="panel-body">
	<ul>
<?php
		if($events){
			 $settings	= UserSettings::model()->findByAttributes(array('user_id'=>1));
			if($settings!=NULL){	
				$time	= Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
				date_default_timezone_set($time->timezone);						
			}
			foreach($events as $event){
				if($settings!=NULL){
					$time			= date($settings->displaydate.' '.$settings->timeformat,$event->start);
					$date			= date($settings->displaydate,$event->start);
					$event->start 	= date($settings->timeformat,$event->start);
					$event->end 	= date($settings->timeformat,$event->end);
				}
				$m			= date('m',strtotime($date));						 
				$d			= date('d',strtotime($date));
				$y			= date('Y',strtotime($date));	
				$event_type	= EventsType::model()->findByPk($event->type);
				$desc 		= preg_replace( "/\r|\n/", "", substr($event->desc,0,40));
?>
                <li>                    
                    <div class="events_hed">
                        <div style="position:relative;" class="stripbx"><div style="position:absolute; background-color:<?php echo $event_type->colour_code; ?>;left:0px; width:3px; height:47px;"></div><?php echo $d; ?> <span><?php echo date('M', mktime(0, 0, 0, $m, 10));; ?></span></div>                   
                    </div>
                    <div class="news_sub">
                        <span><?php echo $event->title; ?></span><br>
                        <small class="text-muted"><span class="more"><?php echo $event->desc; ?></span></small><br />
                        <small class="text-muted"><span class="more"><?php echo Yii::t('app','Type'); ?> : <?php echo $event_type->name; ?></span></small><br />									 
                        <small class="text-muted"><span class="more"><?php echo Yii::t('app','Time'); ?> : <?php echo $event->start.' - '.$event->end; ?></span></small>
                    </div> 
                    <div id="jobDialog"></div>
                    <div class="clearfix"></div>                
                </li>
<?php			
			}
		}
		else{
?>
			<li><?php echo Yii::t('app','No Events'); ?></li>
<?php			
		}
?>    	
    </ul>
</div>