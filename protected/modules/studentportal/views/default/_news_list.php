 <div class="widget-messaging vertical-timeline-block">
 <div class="vertical-timeline-icon navy-bg"><i class="fa fa-calendar"></i></div>
 <div class="vertical-timeline-content">
 <ul>
             
            
    <?php 
	if($index%2)
	$class='mail_blue';
	else
	$class='mail_orange';
	
	$settings = UserSettings::model()->findByAttributes(array('user_id'=>1));
	if($settings!=NULL){ 
		$date1 = date($settings->displaydate.' H:i a',$data->modified);
	}else{
		$date1 = date("Y-m-d H:i a",$data->modified);
	}
	
	echo '<li><small class="pull-right">'.$date1.'</small>'.CHtml::link('<h4  class="sender">'.$data->subject.'</h4>', array('/portalmailbox/message/view','id'=>$data->conversation_id)).'<p>
               '.$data->text.'
                </p>';?>
                
                  </ul>  
                  </div>
</div>
               
                 
    
  
   
   
   





