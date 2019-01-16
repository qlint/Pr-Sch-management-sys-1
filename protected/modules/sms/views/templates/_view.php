<div class="temp_view">
<div class=" temp_div-rht-img">
	<div class="temp_image-cnt">
    <h2><strong><?php echo CHtml::link(CHtml::encode(($data->name)?$data->name:Yii::t('app', 'No name')), array('view', 'id'=>$data->id)); ?></strong></h2>
    <p><?php echo CHtml::encode($data->template); ?></p>
    <span class="sms-templt">			
        <?php echo CHtml::link('', array('update', 'id'=>$data->id),array('class'=>'temp_edit'));?>
        
        <?php echo CHtml::link('', "#", array('submit'=>array('delete','id'=>$data->id), 'class'=>'temp_dlt','confirm'=>Yii::t('app','Are you sure ?'), 'csrf'=>true));?>
    </span>
<div class="created_box1">
<?php 
	$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
	$timezone = Timezone::model()->findByAttributes(array('id'=>$settings->timezone));		
	date_default_timezone_set($timezone->timezone);
	$date = date($settings->displaydate,strtotime($data->created_at));	
	$time = date($settings->timeformat,strtotime($data->created_at));    
?> 
<div class="created_box_r"><h3><b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo $date.' '.$time; ?></h3></div>
</div>

<div class="clear"></div>
</div>
</div>
</div>