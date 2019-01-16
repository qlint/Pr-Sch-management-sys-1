
      <div id="parent_Sect">
        <?php $this->renderPartial('leftside');?> 
        <div id="parent_rightSect">
        	<div class="parentright_innercon">
            	<h1><?php echo Yii::t('app','Inbox'); ?></h1>
                <?php 
				$msg=Message::model()->findAll('receiver_id=:x',array(':x'=>12));
				?>
                <div class="inbox_filter">
                	<ul>
                    	<li style="margin:3px 0 0 0px;"><input type="checkbox" id="checkbox-1-1" class="regular-checkbox" /><label for="checkbox-1-1"></label></li>
                        <li><a href="#"><?php echo Yii::t('app','Mark as Read'); ?></a></li>
                        <li><a href="#"><?php echo Yii::t('app','Mark as Unread'); ?></a></li>
                        <li><a href="#"><?php echo Yii::t('app','Delete'); ?></a></li>
                        <li><a href="#"><?php echo Yii::t('app','Archive'); ?></a></li>
                    </ul>
                    <div class="clear"></div>
                </div>
                <?php
				if($msg!=NULL)
				{
					foreach($msg as $msg_1)
					{
						$user=Profile::model()->findByAttributes(array('user_id'=>12));
						?>
                         <div class="mail_list_row">
                         <ul>
                         	<li class="rfirst"><input type="checkbox" id="checkbox-1-2" class="regular-checkbox" /><label for="checkbox-1-2"></label></li>
                            <li class="rscnd"><img src="images/portal/m_001.png" width="55" height="55">
                           <strong> <?php echo ucfirst($user->lastname.' '.$user->firstname);?></strong>
                           <?php
						  		 $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
								if($settings!=NULL)
								{	
									$data=explode(" ",$msg_1->created_at);
									
									$date1=date($settings->displaydate,strtotime($data[0]));
									$time=date($settings->timeformat,strtotime($data[1]));
									
		
								}
								?>
							<?php echo '<span>'.$date1.' '.$time.'</span>';
						   ?>
                            </li>
                            	<li class="rthrd">
                                <?php echo CHtml::link($msg_1->subject,array('viewmessage','msg_id'=>$msg_1->id));?>
                                
                                <strong><?php //echo $msg_1->subject;?></strong></li>
                         </ul>
                        	 <div class="clear"></div>
                         </div>
                        <?php
					}
				}
				?>
                 
            </div>
        </div>
        <div class="clear"></div>
      </div>
      <!--innersection ends here-->

