<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('room_no')); ?>:</b>
	<?php echo CHtml::encode($data->room_no); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('no_of_bed')); ?>:</b>
	<?php echo CHtml::encode($data->no_of_bed); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('no_of_floors')); ?>:</b>
	<?php echo CHtml::encode($data->no_of_floors); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mode_of_allotment')); ?>:</b>
	<?php echo CHtml::encode($data->mode_of_allotment); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />


</div>