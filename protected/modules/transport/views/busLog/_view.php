<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vehicle_id')); ?>:</b>
	<?php echo CHtml::encode($data->vehicle_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_time_reading')); ?>:</b>
	<?php echo CHtml::encode($data->start_time_reading); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('end_time_reading')); ?>:</b>
	<?php echo CHtml::encode($data->end_time_reading); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fuel_consumption')); ?>:</b>
	<?php echo CHtml::encode($data->fuel_consumption); ?>
	<br />


</div>