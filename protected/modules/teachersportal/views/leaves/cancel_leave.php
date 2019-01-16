<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'leave-form',
)); ?>

	<?php if(isset($_REQUEST['id']) and $_REQUEST['id']){ ?>
		<input type="hidden" value="<?php echo $_REQUEST['id']; ?>" name="id" />
    <?php } ?>  
    
    <div class="row">
        <div class="col-md-12">
            <div class="model-popup-form">
                <?php echo $form->labelEx($model,'cancel_reason'); ?>
                <?php echo $form->textField($model,'cancel_reason',array('maxlength'=>120, 'class'=>'form-control')); ?>
                <?php echo $form->error($model,'cancel_reason'); ?>
            </div>
        </div>
    </div>
    <div class="row buttons">
        <div class="col-md-12">
            <div class="model-popup-form-btn">
            	<?php echo CHtml::ajaxSubmitButton(Yii::t('app','Save'),CHtml::normalizeUrl(array('/teachersportal/leaves/cancel','render'=>false)),array('dataType'=>'json','success'=>'js: function(data) {
				 if (data.status == "success")
				 {
				  	window.location.reload();
				 }
				 else
				 {
					 var errors = JSON.parse(data.errors);
								  $(".errorMessage").remove();
								  $.each(errors, function(index, value){
								   var id  = index + "_em_";
								   var error = $("<div class=\"errorMessage\" />");
								   error.attr({
									id:id,
								   });
								   error.html(value[0]);
								   error.insertAfter($("#"+ index));
								  });
				 }
				 }'),array('id'=>'closeJobDialog'.$day.$emp_id,'name'=>'save','class'=>'btn model-save-btn',)); ?>
            </div>
        </div>
    </div>
	

<?php $this->endWidget(); ?>