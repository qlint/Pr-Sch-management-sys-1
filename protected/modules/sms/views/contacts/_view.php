<div class="contact-main-box">
<div class="contact_box" data-contact-id="<?php echo $data->id; ?>">
	<div class="contact_box_image"></div>
    <h3><?php echo CHtml::encode($data->fullname); ?></h3>
    <div class="cont_set">
    <p class="phone_icon_co"><?php echo CHtml::encode($data->mobile); ?></p>
    <p class="mail_icon_co"><?php echo CHtml::encode($data->email); ?></p>
    <?php 
		$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
		$timezone = Timezone::model()->findByAttributes(array('id'=>$settings->timezone));		
		date_default_timezone_set($timezone->timezone);
		$date = date($settings->displaydate,strtotime($data->created_at));	
		$time = date($settings->timeformat,strtotime($data->created_at));    
    ?>    
    <p class="time_icon_co"><?php echo $date.' '.$time; ?></p>
    <?php /*?><p class="time_icon_co"><?php echo CHtml::encode($data->created_at); ?></p><?php */?>
    </div>
    <div class="con_bottom">    
    	<div>         
    	 <?php echo CHtml::link(Yii::t('app','Edit'), array('update', 'id'=>$data->id),array('class'=>'contacts_yellow'));?>
      
         <?php echo CHtml::link(Yii::t('app','Delete'), "#", array('submit'=>array('delete','id'=>$data->id), 'class'=>'contacts_yellow','confirm'=>Yii::t('app','Are you sure ?'), 'csrf'=>true));?>
         
         <?php
		 if(isset($_GET['group']) and $_GET['group']!=""){		//for group contacts only
		 ?>
		 <?php echo CHtml::link(Yii::t('app','Remove from group'), '#', array('data-contact-id'=>$data->id, 'class'=>'contacts_yellow remove_from_group'));?>
		 <?php
		 }
		 ?>
        </div>
    </div>
</div>
</div>
