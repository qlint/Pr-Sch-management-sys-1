<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('settings_key')); ?>:</b>
	<?php echo CHtml::encode($data->settings_key); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('settings_value')); ?>:</b>
	<?php echo CHtml::encode($data->settings_value); ?>
	<br />


</div>