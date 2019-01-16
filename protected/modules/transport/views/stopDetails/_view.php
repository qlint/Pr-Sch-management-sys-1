<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('route_id')); ?>:</b>
	<?php echo CHtml::encode($data->route_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stop_name')); ?>:</b>
	<?php echo CHtml::encode($data->stop_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fare')); ?>:</b>
	<?php echo CHtml::encode($data->fare); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('arrival_mrng')); ?>:</b>
	<?php echo CHtml::encode($data->arrival_mrng); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('departure_mrng')); ?>:</b>
	<?php echo CHtml::encode($data->departure_mrng); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('arrival_evng')); ?>:</b>
	<?php echo CHtml::encode($data->arrival_evng); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('departure_evng')); ?>:</b>
	<?php echo CHtml::encode($data->departure_evng); ?>
	<br />

	*/ ?>

</div>