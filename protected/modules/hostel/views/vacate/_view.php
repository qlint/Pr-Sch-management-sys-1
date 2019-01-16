<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('student_id')); ?>:</b>
	<?php echo CHtml::encode($data->student_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('room_no')); ?>:</b>
	<?php echo CHtml::encode($data->room_no); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('allot_id')); ?>:</b>
	<?php echo CHtml::encode($data->allot_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vacate_date')); ?>:</b>
	<?php echo CHtml::encode($data->vacate_date); ?>
	<br />


</div>