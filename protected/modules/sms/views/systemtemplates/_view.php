<div class="temp_view">
<div class=" temp_div-rht-img">
	<div class="temp_image-cnt">
    <h2><strong><?php 
	
	$name=NotificationSettings::model()->findByAttributes(array('id'=>$data->cat_id));
	echo CHtml::link(CHtml::encode(($name->settings_key)?Yii::t('app',$name->settings_key):Yii::t('app', 'No name')), array('view', 'id'=>$data->id)); ?></strong></h2>
    <div>			
        <?php echo CHtml::link(Yii::t('app',''), array('update', 'id'=>$data->id),array('class'=>'temp_edit'));?>
        
    </div>

    <p><?php echo CHtml::encode($data->template); ?></p>

<div class="created_box">
<?php 
	$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
	$timezone = Timezone::model()->findByAttributes(array('id'=>$settings->timezone));		
	date_default_timezone_set($timezone->timezone);
	$date = date($settings->displaydate,strtotime($data->created_at));	
	$time = date($settings->timeformat,strtotime($data->created_at));    
?>
<div class="created_box_r"><b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo $date.' '.$time; ?></div>
</div>

<div class="clear"></div>
</div>
</div>
</div>