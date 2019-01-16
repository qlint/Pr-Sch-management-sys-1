<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('settings_key')); ?>:</b>
	<?php echo CHtml::encode($data->settings_key); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sms_enabled')); ?>:</b>
	<?php echo CHtml::encode($data->sms_enabled); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mail_enabled')); ?>:</b>
	<?php echo CHtml::encode($data->mail_enabled); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('msg_enabled')); ?>:</b>
	<?php echo CHtml::encode($data->msg_enabled); ?>
	<br />


</div>