
<style>
    .ui-dialog{ margin: 0 auto!important;
        width:520px!important}
	
</style>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'timetable-entries-form',
	//'enableAjaxValidation'=>true,
	'clientOptions'=>array('validateOnSubmit'=>TRUE),

)); ?>

<p style="padding-left:20px;"><?php echo Yii::t('app','Fields with');?><span class="required">*</span><?php echo Yii::t('app','are required.');?></p>

<div class="errorSummary" id="error_employee" style="display:none; width:360px; height:20px; padding-top:10px;">
<?php echo '<span>'.Yii::t('app','Maximum weekly classes of this subject is exceeded!').' !!</span>';?>
	
</div> <br />
   
<div class="formCon" style="width:430px; height:auto;">
<div class="formConInner" style="width:400px;">
<div  style="background:none">
	<?php echo $form->hiddenField($model,'batch_id',array('value'=>$batch_id,'id'=>batch_id)); ?>
    <?php echo $form->hiddenField($model,'weekday_id',array('value'=>$weekday_id,'id'=>weekday_id)); ?>
    <?php echo $form->hiddenField($model,'class_timing_id',array('value'=>$class_timing_id,'id'=>class_timing_id)); ?>
    <?php echo $form->hiddenField($model,'id',array('value'=>$id)); ?>
    <?php echo $form->hiddenField($model,'is_elective'); ?>
<div id="duplicate_error" style="color:#F00; padding:10px 10px 10px 10px; width:380px;" align="center"></div>	  
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="base_table">

	<tr>
  	<td  width="30%"><?php echo $form->labelEx($model,Yii::t('app','subject_id'));  ?></td>
	<?php
	if($model->is_elective!=0){
		$elective			= Electives::model()->findByPk($model->subject_id);
		if($elective){
			$subject 			= Subjects::model()->findByAttributes(array('elective_group_id'=>$elective->elective_group_id));
			if($subject)
				$model->subject_id 	= $subject->id;
		}
	}
	?>
    <td width="70%">
		<?php
        	echo $form->dropDownList(
				$model,
				'subject_id',
				CHtml::listData(Subjects::model()->findAll('batch_id=:x',array(':x'=>$batch_id)),'id', 'name'),
				array(
					'prompt'=>Yii::t('app','Select Subject'),
					'style'=>'width:200px;',
					'class'=>'change-dropdown',
					'ajax' => array('type'=>'POST','url'=>CController::createUrl('TimetableEntries/dynamicsubjects'),
					'success'=>'function(data){
						var data = jQuery.parseJSON(data);
						$("#base_table").after(data.data);
						if(data.status == "elective"){
						$("#TimetableEntries_is_elective").val(2);		
						}
						else{
							$("#TimetableEntries_is_elective").val(0);
						}					
					}')
				)
			);
		?>
 	</td>
  </tr>
  <tr>
  	<td>&nbsp;
    
    </td>
 </tr>
  <tr>
  <td colspan="2">
  <?php
  if($model->is_elective==0)
  {
	  echo $this->renderPartial('_update_ajax_dropdown', array('model'=>$model,'batch_id'=>$batch_id,'weekday_id'=>$weekday_id,'class_timing_id'=>$class_timing_id,'subject_id'=>$model->subject_id)); 
	 
  }
  elseif($model->is_elective!=0)
  {
	  $models = TimetableEntries::model()->findAllByAttributes(array('batch_id'=>$model->batch_id,'weekday_id'=>$model->weekday_id,'class_timing_id'=>$model->class_timing_id));
	  
	 
	 
	  echo $this->renderPartial('_update_ajax_form', array('models'=>$models,'batch_id'=>$batch_id,'weekday_id'=>$weekday_id,'class_timing_id'=>$class_timing_id)); 
  }
  ?>
  </td>
  </tr>
  
</table>

<div style="padding:20px 0 0 0px; text-align:left">
		<?php echo CHtml::ajaxSubmitButton(Yii::t('app','Save'),CHtml::normalizeUrl(array('TimetableEntries/updatetime','render'=>false)),array('dataType'=>'json','success'=>'js: function(data) {
						$(".errorMessage").remove();
						if (data.status == "success")
						{
							$("#JobDialog'.$class_timing_id.$weekday_id.'").dialog("close");
							window.location.reload();
						}
						else
						{							
							$(".errorMessage").remove();
							var errors	= data.errors;
							$.each(errors, function(index, value){
								var err	= $("<div class=\"errorMessage\" />").html(value[0]);
								err.insertAfter($("#" + index));
							});
							
							$("input:checkbox.classtime").change(function(){
								if($(this).is(":checked")){
									if($(this).closest(".classtime_blk").length>0){
										$(this).closest(".classtime_blk").insertAfter($(this).closest("div.errorMessage"));
									}
									else{
										$(this).closest(".errorMessage").attr("class", "classtime_blk");
									}
								}
								else{
									$(this).closest(".classtime_blk").attr("class", "errorMessage");
								}
							});
						}
                    }',
),array('id'=>'closeJobDialog')); ?>
	</div>
	

<?php $this->endWidget(); ?>
</div>
</div>
</div><!-- form -->
<script>
$('.change-dropdown').change(function(){
	$('#elective_table').remove();	
});
$('#closeJobDialog').click(function(){
	
	$('#error_emp_sub,#error_emp').html("");
	var emplength= $('#emp_id').length;
	var eleclength=$("select.elective-drop").length;
	if($('#emp_id').length>0 && $('#emp_id').val()=='')
	{
		$('#error_emp_sub').html("<?php echo Yii::t('app','Please choose an Teacher');?>");
		return false;
	}
	if($("select.elective-drop").length>0)
	{
		var elective	= "";
		var counter=0;
		$("select.elective-drop").each(function() {
			if($(this).val()=="")
			{
				counter=counter+1;
			}
				elective += $(this).val();
		});
		if(counter>0){
			$('#error_emp').html("<?php echo Yii::t('app','Please choose a Teacher');?>");
			return false;
		}
	}
		//alert("Please select a employee");
    	//$('#emp_id').focus();
	if($('#TimetableEntries_is_elective').val()==2 && $("select.elective-drop").length<=0){
            $('#error_emp').html("<?php echo Yii::t('app','Electives not found');?>");
            return false;
        }   
});
$('#closeUpdateJobDialog').click(function(){
	if($("select.classroom-drop").length>0)
	{
		
		var electivefirst	= "";
		var counterclass=0;
		$("select.classroom-drop").each(function() {
			if($(this).val()=="")
			{
				counterclass=counterclass+1;
			}
				electivefirst += $(this).val();
		});
		if(counterclass>0){
			$('#error_class').html("<?php echo Yii::t('app','Please choose a Classroom');?>");
			return false;
		}
	}
		//alert("Please select a employee");
    	//$('#emp_id').focus();
    	
	
});

</script>