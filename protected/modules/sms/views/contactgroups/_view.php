<div class="contact-main-box">

<div class="contact_box" data-group-id="<?php echo $data->id;?>">
<div class="contact_box_group"></div>
	
	<h3><?php echo CHtml::encode($data->group_name); ?></h3>
    <div class="cont_set">
    <p class="phone_icon_co"><?php 
		//total contacts
		$criteria	= new CDbCriteria;
		$criteria->condition	= '`group_id`=:group_id';
		$criteria->params		= array(':group_id'=>$data->id);
		$contacts	= ContactsList::model()->findAll($criteria);
		echo CHtml::encode(count($contacts)).' contacts'; 
	?></p>
    <?php 
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
		$timezone = Timezone::model()->findByAttributes(array('id'=>$settings->timezone));		
		date_default_timezone_set($timezone->timezone);
		$date = date($settings->displaydate,strtotime($data->created_at));	
		$time = date($settings->timeformat,strtotime($data->created_at));    
    ?>    
    <p class="time_icon_co"><?php echo $date.' '.$time; ?></p>
   
    </div>
    <div class="con_bottom">    
    	<div>			
            <?php echo CHtml::link(Yii::t('app', 'Edit'), array('update', 'id'=>$data->id),array('class'=>'contacts_yellow'));?>
            <?php echo CHtml::link('<span>'.Yii::t('app','Delete').'</span>', "#", array('submit'=>array('/sms/contactgroups/delete','id'=>$data->id), 'confirm'=>Yii::t('app','Are you sure?'),'class'=>'contacts_yellow', 'csrf'=>true)); ?>			
            <?php echo CHtml::link(Yii::t('app', 'Contacts'), array('/sms/contacts', 'group'=>$data->id),array('class'=>'contacts_yellow'));?>
        </div>
    </div>
</div>
 </div>   

