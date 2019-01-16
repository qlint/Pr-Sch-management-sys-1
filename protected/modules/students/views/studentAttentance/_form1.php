<?php
$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
if(Yii::app()->user->year)
{
	$ac_year = Yii::app()->user->year;
}
else
{
	$ac_year = $current_academic_yr->config_value;
}
$is_edit = PreviousYearSettings::model()->findByAttributes(array('id'=>3));
$is_delete = PreviousYearSettings::model()->findByAttributes(array('id'=>4));
?>




<div class="formCon">
<div class="formConInner" style="width:50%; height:auto; min-height:150px;">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student-attentance-form',
	//'enableAjaxValidation'=>true,
)); ?>

	<?php echo $form->errorSummary($model); ?>
    <?php echo  CHtml::hiddenField('id',$_REQUEST['id']); ?>

	<div class="row">
		<?php
		 //echo $form->labelEx($model,'student_id'); ?>
		<?php echo $form->hiddenField($model,'student_id',array('value'=>$emp_id)); ?>
		<?php echo $form->error($model,'student_id'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'date'); ?>
		<?php echo $form->hiddenField($model,'date',array('value'=>$year.'-'.$month.'-'.$day)); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>
    

	<div class="row">
		<?php echo $form->labelEx($model,'reason'); ?>
        <?php 
		if(($ac_year == $current_academic_yr->config_value) or ($ac_year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
		{
			echo $form->textField($model,'reason',array('size'=>60,'maxlength'=>120)); 
		}
		elseif($ac_year != $current_academic_yr->config_value and $is_edit->settings_value==0)
		{
			echo $form->textField($model,'reason',array('size'=>60,'maxlength'=>120,'disabled'=>true)); 
		}
		?>
		<?php echo $form->error($model,'reason'); ?>
	</div>
    <div id="msg" style="color:#F00"></div>
    <div class="row">
		<?php echo $form->labelEx($model,'leave_type_id'); ?>
        <?php 
		$leave_type=CHtml::listData(StudentLeaveTypes::model()->findAllByAttributes(array('status'=>1)),'id','name');
		if(($ac_year == $current_academic_yr->config_value) or ($ac_year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
		{
		
		 echo $form->dropDownList($model,'leave_type_id',$leave_type,array('empty' => Yii::t('app','Select Leave Type'))); 
		}
		elseif($ac_year != $current_academic_yr->config_value and $is_edit->settings_value==0)
		{
			echo $form->dropDownList($model,'leave_type_id',$leave_type,array('empty' => Yii::t('app','Select Leave Type'),'disabled'=>true)); 
			
		}
		?>
		<?php echo $form->error($model,'leave_type_id'); ?>
	</div>
    <div id="leave" style="color:#F00"></div>
	<br /><br />
	<div class="row buttons">
		<?php //echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
        
        <?php 
		if(($ac_year == $current_academic_yr->config_value) or ($ac_year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
		{
		
		echo CHtml::ajaxSubmitButton(Yii::t('app','Save'),CHtml::normalizeUrl(array('StudentAttentance/EditLeave','render'=>false)),array('dataType'=>'json','success'=>'js: function(data) {
			 		if (data.status == "success")
                	{
						$("#td'.$day.$emp_id.'").text("");
						$("#jobDialog123'.$day.$emp_id.'").html("<span class=\"abs\"></span>","");
						$("#jobDialog'.$day.$emp_id.'").dialog("close"); window.location.reload();
					}
					else
					{
						if(data.reason=="")
						{
							$("#msg").html("'.Yii::t("app","Reason cannot be blank").'");
						}
						if(data.leave_type=="")
						{
							$("#leave").html("'.Yii::t("app","Leave type cannot be blank").'");
						}
					}
                    }'),array('id'=>'closeJobDialog'.$day.$emp_id,'name'=>'save')); 
		}
		?>
       


      <?php 
	  
	if(($ac_year == $current_academic_yr->config_value) or ($ac_year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
	{
	  echo CHtml::ajaxSubmitButton(Yii::t('app','Delete'),CHtml::normalizeUrl(array('StudentAttentance/DeleteLeave','render'=>false)),array('success'=>'js: function(data) {
		                $("#td'.$day.$emp_id.'").text(" ");
		                $("#jobDialog'.$day.$emp_id.'").dialog("close"); window.location.reload();
                    }'),array('confirm'=>Yii::t('app',"Are you sure you want to delete this attendance?"),'id'=>'closeJobDialog1'.$day.$emp_id,'name'=>'delete')); 
	}
	?>
	</div>
<br />

<?php $this->endWidget(); ?>
</div>
</div><!-- form -->