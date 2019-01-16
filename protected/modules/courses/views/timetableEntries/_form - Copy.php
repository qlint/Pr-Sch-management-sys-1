<script>
jQuery('body').undelegate('#TimetableEntries_subject_id','change');
</script>
<style>
    .ui-dialog{ margin: 0 auto!important;
        width:520px!important}
</style>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'timetable-entries-form',
	'enableAjaxValidation'=>true,
	'clientOptions'=>array('validateOnSubmit'=>true),

)); ?>

<p style="padding-left:20px;"><?php echo Yii::t('app','Fields with');?><span class="required"> * </span><?php echo Yii::t('app','are required').'.';?></p>

<div class="errorSummary" id="error_employee" style="display:none; width:360px; height:20px; padding-top:10px;">
	<?php echo '<span>'.Yii::t('app','Maximum weekly classes of this subject is exeeded!').'</span>';?>
</div> <br />
   
<div class="formCon" style="width:430px; height:auto;">
<div class="formConInner" style="width:400px;">
<div  style="background:none">

    <?php //echo $form->labelEx($model,'batch_id'); ?>
    <?php echo $form->hiddenField($model,'batch_id',array('value'=>$batch_id)); ?>
		<?php //echo $form->error($model,'batch_id'); ?>
  <?php //echo $form->labelEx($model,'weekday_id'); ?>
    <?php echo $form->hiddenField($model,'weekday_id',array('value'=>$weekday_id)); ?>
		<?php //echo $form->error($model,'weekday_id'); ?>

    <?php //echo $form->labelEx($model,'class_timing_id'); ?>
   <?php echo $form->hiddenField($model,'class_timing_id',array('value'=>$class_timing_id)); ?>
		<?php //echo $form->error($model,'class_timing_id'); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="base_table">  
<tr>
  	<td><?php echo $form->labelEx($model,'subject_id');  ?></td>
	<?php
	$criteria=new CDbCriteria;
	$criteria->condition='batch_id=:batch_id';
	$criteria->params=array(':batch_id'=>$batch_id);
	$subjects = Subjects::model()->findAll($criteria); 
	?>
    <td><?php echo $form->dropDownList($model,'subject_id',
				CHtml::listData($subjects,'id', 'name'),
				array('encode'=>false,'prompt'=>Yii::t('app','Select Subject'),'style'=>'width:200px;',
				'ajax' => array('type'=>'POST','url'=>CController::createUrl('TimetableEntries/dynamicsubjects'),
				'data'=>array('batch_id'=>$batch_id,'sub_id'=>'js:this.value','week_id'=>$weekday_id,'timing_id'=>$class_timing_id, Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken),
				'success'=>'function(data){					
					var data = jQuery.parseJSON(data);
					if($("#elective_table").length==0){
						$("#base_table").after(data.data);
					}
					else{
						$("#elective_table").replaceWith(data.data);
					}
					
					}'))); ?>
		<?php //echo $form->error($model,'subject_id'); ?></td>
  </tr>
<tr>
  	<td>&nbsp;
    
    </td>
 </tr>
</table>


	<div style="padding:20px 0 0 0px; text-align:left">
		<?php echo CHtml::ajaxSubmitButton(Yii::t('app','Save'),CHtml::normalizeUrl(array('TimetableEntries/settime','render'=>false)),array('dataType'=>'json','success'=>'js: function(data) {
						$(".errorMessage").remove();
						if (data.status == "success")
						{
							$("#jobDialog'.$class_timing_id.$weekday_id.'").dialog("close");
							window.location.reload();
						}
						else
						{
							//$("#error_employee").show();
							
							$(".errorMessage").remove();
							var errors	= data.errors;
							$.each(errors, function(index, value){
								var err	= $("<div class=\"errorMessage\" />").text(value[0]);
								err.insertAfter($("#" + index));
							});
							/*var errors	= data.errors;
							$.each(errors, function(index, value) {
							 //display the key and value pair
							
							 $("<div class=\"errorMessage\" />").html(value).insertAfter($("#" + index));
							});*/
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
		$('#error_emp_sub').html("<?php echo Yii::t('app','Please choose a Teacher');?>");
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
    	
	
});
</script>