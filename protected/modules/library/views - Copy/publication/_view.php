<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('publication_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->publication_id), array('view', 'id'=>$data->publication_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('location')); ?>:</b>
	<?php echo CHtml::encode($data->location); ?>
	<br />


</div>