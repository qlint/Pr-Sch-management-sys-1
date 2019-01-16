<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('room_no')); ?>:</b>
	<?php echo CHtml::encode($data->room_no); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('floor')); ?>:</b>
	<?php echo CHtml::encode($data->floor); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_full')); ?>:</b>
	<?php echo CHtml::encode($data->is_full); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('no_of_bed')); ?>:</b>
	<?php echo CHtml::encode($data->no_of_bed); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />


</div>