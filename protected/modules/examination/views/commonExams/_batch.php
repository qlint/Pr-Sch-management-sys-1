<?php
foreach($batches as $batch){
?>
	<div class="batch-block" data-id="<?php echo $batch->id;?>">
    	<?php
        	if(isset($model) and isset($model->batches) and $model->batches!=NULL){
				echo CHtml::hiddenField('batch_id[]', $batch->id);
			}
		?>
        <?php
        	$label	= $batch->course123->course_name;
			$semester_enabled	= Configurations::model()->isSemesterEnabledForCourse($batch->course123->id);
			if($semester_enabled and $batch->semester_id!=NULL){	// enabled
				$semester	= Semester::model()->findByPk($batch->semester_id);
				if($semester!=NULL and $semester->name!=NULL){
					$label	.= ' / '.$semester->name;
				}
			}
			$label	.= ' / '.$batch->name;
		?>
    	<span><?php echo $label;?></span>
        <a href="javascript:void(0);" class="move_action" title="<?php echo Yii::t('app', ((isset($model) and isset($model->batches) and $model->batches!=NULL)?'Remove':'Add'));?>">
        	<?php if(isset($model) and isset($model->batches) and $model->batches!=NULL){?>
				<i class="fa fa-times" aria-hidden="true"></i>
          	<?php }else{?>
            	<i class="fa fa-arrow-right" aria-hidden="true"></i>
            <?php }?>
      	</a>
    </div>
<?php
}
?>