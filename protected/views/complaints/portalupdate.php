<style type="text/css">
.ui-widget-content{ height:auto !important}
</style>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'course_status_form',
	'enableAjaxValidation'=>false,
)); ?>
	
    <div class="row">
        <div class="col-md-12">
            <div class="model-popup-form">
				<?php 
					echo $form->labelEx($model, 'feedback'); 
					echo $form->textArea($model,'feedback',array('class'=>'form-control', 'rows'=>4,'value'=>ucfirst($model->feedback),'id'=>'feedback'.$model->id)); 
					echo $form->hiddenField($model,'id',array('size'=>20,'value'=>$model->id,)); 					
				?>
                <div id="feedback_error<?php echo $model->id; ?>" style="color:#F00"></div>
            </div>
        </div>
    </div>
    <div class="row buttons">
        <div class="col-md-12">
        	<div class="model-popup-form-btn">
            	<?php
					echo CHtml::ajaxSubmitButton(Yii::t('app','Save'),CHtml::normalizeUrl(array('complaints/display')),array('dataType'=>'json','success'=>'js: 				
					function(data) { 
							$(".errorMessage").remove();									
							if(data.status == "success")
							{
									//$("#course_status'.$model->id.'").dialog("close");
									window.location.reload();
	
							}
							else if(data.status=="error")
							{
									var errors	= JSON.parse(data.errors);
	
									 $.each(errors, function(index, value){
											var err	= $("<div class=\"errorMessage\" />").text(value[0]);
											err.insertAfter($("#" + index));
									});										
	
	
							}
							  //window.location.reload();
					}'),array('id'=>'closeDialog'.$model->id,'name'=>'save', 'class'=>'btn model-save-btn')); 
				?>
            </div>
        </div>
    </div>        

<?php $this->endWidget(); ?>

<script type="text/javascript">

$('#closeDialog<?php echo $model->id; ?>').click(function(ev) {
	var comment = $('#feedback<?php echo $model->id; ?>').val(); 		
	if(comment == '')
	{		   
		$('#feedback_error<?php echo $model->id; ?>').html('<?php echo Yii::t('app','Comment cannot be blank'); ?>');
		return false;
	}
});
</script>








