<?php
$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
if(Yii::app()->user->year)
{
	$year = Yii::app()->user->year;
}
else
{
	$year = $current_academic_yr->config_value;
}
$is_edit = PreviousYearSettings::model()->findByAttributes(array('id'=>3));
$yes_edit = 0;
if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
{
	$yes_edit = 1;
}

?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'exams-form',
	'enableAjaxValidation'=>false,
)); ?>

<?php /*?><?php echo $form->errorSummary($model); ?><?php */?>
<br />

<div class="formCon" style="width:100%">

<div class="formConInner" >


	<p class="note"><?php echo Yii::t('app','Fields with');?><span class="required">*</span><?php echo Yii::t('app',' are required.');?></p>

	

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo $form->labelEx($model,'start_time'); ?></td>
    <td>
		<?php  
			$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
			if($settings!=NULL)
			{
				$date=$settings->dateformat;
			}
			else
			{
				$date = 'dd-mm-yy';	
			}
			if($yes_edit == 1)
			{ 
					$this->widget('application.extensions.timepicker.timepicker', array(
							'model' => $model,
							'name'=>'start_time',
							'tabularLevel' => "[]",					
							'options'=>array(
								'dateFormat'=>$date,																															
							),	
							'htmlOptions'=>array(
								'readonly'=>true,																															
							),
									
						));
				
			}
			else
			{
				echo $form->textField($model,'start_time',array('size'=>10,'maxlength'=>10,'disabled'=>'disabled'));
			}
        ?>
    
		<?php //echo $form->textField($model,'start_time'); ?>
		<?php echo $form->error($model,'start_time'); ?>
	</td>
    <td><?php echo $form->labelEx($model,'end_time'); ?></td>
    <td>
    	<?php
		if($yes_edit == 1)
		{
			
			
				$this->widget('application.extensions.timepicker.timepicker', array(
					'model' => $model,
					'name'=>'end_time',
					'tabularLevel' => "[]",
					'options'=>array(
						'dateFormat'=>$date,
						
						),
						'htmlOptions'=>array(
							'readonly'=>true,																															
						),
							
				));
			
		}
		else
		{
			echo $form->textField($model,'end_time',array('size'=>10,'maxlength'=>10,'disabled'=>'disabled'));
		}
		?>
    
		<?php //echo $form->textField($model,'end_time'); ?>
		<?php echo $form->error($model,'end_time'); ?>
	</td>
  </tr>
  <tr>
  	<td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $form->labelEx($model,'maximum_marks'); ?></td>
    <td>
		<?php 
		if($yes_edit == 1)
		{
			echo $form->textField($model,'maximum_marks',array('size'=>10,'maxlength'=>3)); 
		}
		else
		{
			echo $form->textField($model,'maximum_marks',array('size'=>10,'maxlength'=>10,'disabled'=>'disabled'));	
		}
		?>
		<?php echo $form->error($model,'maximum_marks'); ?>
	</td>
    <td><?php echo $form->labelEx($model,'minimum_marks'); ?></td>
    <td>
		<?php 
		if($yes_edit == 1)
		{
			echo $form->textField($model,'minimum_marks',array('size'=>10,'maxlength'=>3));
		}
		else
		{
			echo $form->textField($model,'minimum_marks',array('size'=>10,'maxlength'=>10,'disabled'=>'disabled'));
		}
		
		?>
		<?php echo $form->error($model,'minimum_marks'); ?></td>
  </tr>
  
</table>


	<div class="row">
		<?php //echo $form->labelEx($model,'grading_level_id'); ?>
		<?php echo $form->hiddenField($model,'grading_level_id'); ?>
		<?php echo $form->error($model,'grading_level_id'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'weightage'); ?>
		<?php echo $form->hiddenField($model,'weightage'); ?>
		<?php echo $form->error($model,'weightage'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'event_id'); ?>
		<?php echo $form->hiddenField($model,'event_id'); ?>
		<?php echo $form->error($model,'event_id'); ?>
	</div>

	
	<div class="row">
		<?php //echo $form->labelEx($model,'updated_at'); ?>
		<?php echo $form->hiddenField($model,'updated_at',array('value'=>date('Y-m-d'))); ?>
		<?php echo $form->error($model,'updated_at'); ?>
	</div>
	<?php
	if($yes_edit == 1)
	{
	?>
        <div class="row buttons">
            <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
        </div>
	<?php
	}
	?>        

<?php $this->endWidget(); ?>
<script>
$('input[type="text"][name="Exams[maximum_marks]"], input[type="text"][name="Exams[minimum_marks]"]').blur(function(e) {
    if(isNaN($(this).val())){
  $(this).val('');
 }
});
</script>

</div></div><!-- form -->
