
<style type="text/css">
.tableinnerlist td{ text-align:left;}
</style>

<h1><?php echo Yii::t('app','View Class Timing');?></h1>

<div class="tableinnerlist" style="padding-right:15px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><?php echo Yii::t('app','Name'); ?></td>
		<td><?php echo ucfirst($model->name); ?></td>
	</tr>
	<tr>
		<td><?php echo Yii::t('app','Start Time'); ?></td>
		<td><?php echo $model->start_time; ?></td>
	</tr>
	<tr>
		<td><?php echo Yii::t('app','End Time'); ?></td>
		<td><?php echo $model->end_time; ?></td>
	</tr>
    <tr>
		<td><?php echo Yii::t('app','Is Break'); ?></td>
		<td>
			<?php
				if($model->is_break == 1){
					echo Yii::t('app','Yes');
				}else{
					echo Yii::t('app','No');
				}
			?>
        </td>
	</tr>
</table>
</div>