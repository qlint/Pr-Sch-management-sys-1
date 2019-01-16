<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('groupstaff_id')); ?>:</b>
	<?php echo CHtml::encode($data->groupstaff_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pepartment_id')); ?>:</b>
	<?php echo CHtml::encode($data->pepartment_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('material_id')); ?>:</b>
	<?php echo CHtml::encode($data->material_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('quantity')); ?>:</b>
	<?php echo CHtml::encode($data->quantity); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status_hod')); ?>:</b>
	<?php echo CHtml::encode($data->status_hod); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status_pm')); ?>:</b>
	<?php echo CHtml::encode($data->status_pm); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('is_issued')); ?>:</b>
	<?php echo CHtml::encode($data->is_issued); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('is_deleted')); ?>:</b>
	<?php echo CHtml::encode($data->is_deleted); ?>
	<br />

	*/ ?>

</div>