<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vehicle_no')); ?>:</b>
	<?php echo CHtml::encode($data->vehicle_no); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vehicle_code')); ?>:</b>
	<?php echo CHtml::encode($data->vehicle_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('no_of_seats')); ?>:</b>
	<?php echo CHtml::encode($data->no_of_seats); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('maximum_capacity')); ?>:</b>
	<?php echo CHtml::encode($data->maximum_capacity); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('insurance')); ?>:</b>
	<?php echo CHtml::encode($data->insurance); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tax_remitted')); ?>:</b>
	<?php echo CHtml::encode($data->tax_remitted); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('permit')); ?>:</b>
	<?php echo CHtml::encode($data->permit); ?>
	<br />

	*/ ?>

</div>