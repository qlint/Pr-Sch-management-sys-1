<style type="text/css">
.col-sm-4{
	height:81px;
}
.col-sm-5{
	height:50px;
}

.note{
	 margin-top:15px;	
}
</style>
<div class="se_panel_formwrap">
    <div class="wiz_right">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'parent-details-_step2-form',
	'enableAjaxValidation'=>false,
)); ?>
	<?php echo $form->errorSummary($model); ?>
	<p class="note"><?php echo Yii::t('app','Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app','are required.'); ?></p>
    
        <?php
			$roles=Rights::getAssignedRoles(Yii::app()->user->Id); 
			
		?>
     <div id="disable">
        <h4 class="text-success"><?php echo Yii::t('app','Personal Details'); ?></h4>
        <div class="row mb10">
        	<?php if(FormFields::model()->isVisible('first_name','Guardians','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'first_name',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'first_name', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('first_name'))); ?>                    
                </div>
            <?php } ?>    
    		
            <?php if(FormFields::model()->isVisible('last_name','Guardians','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'last_name',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'last_name', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('last_name'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('relation','Guardians','forOnlineRegistration')){	?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'relation',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'relation', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('relation'))); ?>                    
                </div>
            <?php } ?>    
        	
            <?php if(FormFields::model()->isVisible('dob','Guardians','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'dob',array('class'=>'control-label')); ?>
                    <?php
                    $settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
                    if($settings!=NULL){
                        $date=$settings->dateformat;
						if($model->dob!=NULL)
						{	
							if($model->dob == '0000-00-00'){
								$model->dob = '';
							}else{
								$date_of_birth=date($settings->displaydate,strtotime($model->dob));
								$model->dob = $date_of_birth;								
							}
						}
                    }else{
                        $date = 'dd-mm-yy';
                    }
                    
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model'=>$model,
                        'attribute'=>'dob',
                        // additional javascript options for the date picker plugin
                        'options'=>array(
                            'showAnim'=>'fold',
                            'dateFormat'=>$date,
                            'changeMonth'=> true,
                            'changeYear'=>true,
                            'yearRange'=>'1900:'
                            
                        ),
                        'htmlOptions'=>array(
                            'class'=>'form-control',
                            'placeholder'=>$model->getAttributeLabel('dob')
                        ),
                    ));
                    ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('education','Guardians','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'education',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'education', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('education'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('occupation','Guardians','forOnlineRegistration')){	?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'occupation',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'occupation', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('occupation'))); ?>                    
                </div>
            <?php } ?>    
        	
            <?php if(FormFields::model()->isVisible('income','Guardians','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'income',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'income', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('income'))); ?>                    
                </div>
            <?php } ?> 
            
            <?php
				$fields     = FormFields::model()->getDynamicFields(2, 1, "forOnlineRegistration");
				foreach ($fields as $key => $field) {
					if($field->form_field_type!=NULL){
						$this->renderPartial("application.modules.dynamicform.views.fields.online-form._field_".$field->form_field_type, array('model'=>$model, 'field'=>$field));                                                
					}                                               
				}
			?>   
        </div>
        <h4 class="text-success"><?php echo Yii::t('app','Contact Details'); ?></h4>
        
        <?php if($model->isNewRecord){ ?>
            <div class="row">
                <div class="col-sm-5">
                    <?php echo $form->checkBox($model,'same_address');?>
                    <?php echo $form->labelEx($model,'same_address',array('class'=>'control-label'));?>
                </div>
            </div>
        <?php } ?>    
        <div class="row mb10">
        	<?php if(FormFields::model()->isVisible('email','Guardians','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'email',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'email', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('email'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('mobile_phone','Guardians','forOnlineRegistration')){	?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'mobile_phone',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'mobile_phone', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('mobile_phone'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('office_phone1','Guardians','forOnlineRegistration')){	?>    
                <div class="col-sm-4" id="hide_1">
                    <?php echo $form->labelEx($model,'office_phone1',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'office_phone1', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('office_phone1'))); ?>                    
                </div>
            <?php } ?>    
            
        
        <div id="hide_2">
            <?php if(FormFields::model()->isVisible('office_phone2','Guardians','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'office_phone2',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'office_phone2', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('office_phone2'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('office_address_line1','Guardians','forOnlineRegistration')){	?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'office_address_line1',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'office_address_line1', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('office_address_line1'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('office_address_line2','Guardians','forOnlineRegistration')){	?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'office_address_line2',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'office_address_line2', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('office_address_line2'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('city','Guardians','forOnlineRegistration')){	?>
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'city',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'city', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('city'))); ?>                    
                </div>
            <?php } ?>
            
            <?php if(FormFields::model()->isVisible('state','Guardians','forOnlineRegistration')){	?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'state',array('class'=>'control-label')); ?>
                    <?php echo $form->textField($model,'state', array('class'=>'form-control', 'placeholder'=>$model->getAttributeLabel('state'))); ?>                    
                </div>
            <?php } ?>    
                <?php
                    if(Yii::app()->session['parent_id']!=0 or (Yii::app()->user->id!=NULL and key($roles)!=NULL and (key($roles) == 'parent'))){
                        $disabled = true;
                    }
                    else{
                        $disabled = false;
                    }
                ?>
            <?php if(FormFields::model()->isVisible('country_id','Guardians','forOnlineRegistration')){	?>    
                <div class="col-sm-4">
                    <?php echo $form->labelEx($model,'country_id',array('class'=>'control-label')); ?>
                    <?php echo $form->dropDownList($model,'country_id',CHtml::listData(Countries::model()->findAll(), 'id', 'name'), array('class'=>'form-control','prompt'=>Yii::t('app','Select').' '.$model->getAttributeLabel('country_id'),'disabled'=>$disabled)); ?>                    
                </div>
            <?php } ?>
               
            </div>
            <?php
				$fields     = FormFields::model()->getDynamicFields(2, 2, "forOnlineRegistration");
				foreach ($fields as $key => $field) {
					if($field->form_field_type!=NULL){
						$this->renderPartial("application.modules.dynamicform.views.fields.online-form._field_".$field->form_field_type, array('model'=>$model, 'field'=>$field));                                                
					}                                               
				}
			?> 
        </div>    
     </div> 
        <br />
         <div class="row mb10">
         	<div class="col-sm-4">
            <div class="row buttons">
                <?php echo CHtml::submitButton(Yii::t('app','Save').' & '.Yii::t('app','Continue'),array('class'=>"btn btn-success btn-block")); ?>
            </div>
            </div>
        </div>

	
<?php $this->endWidget(); ?>
	</div>
</div><!-- form -->
<?php
		
	if(Yii::app()->session['parent_id']!=0 or (Yii::app()->user->id!=NULL and key($roles)!=NULL and (key($roles) == 'parent'))) { ?>
	<script type="text/javascript">
    
       $("#disable input").prop('disabled',true); 
    
    </script>
<?php } ?> 
<?php if($model->same_address == 1){ ?>
	<script type="text/javascript">
		$('#Guardians_same_address').prop('checked',true);
		$('#hide_1').hide();
		$('#hide_2').hide();
	</script>		
<?php } ?>

<script type="text/javascript">
$('input[type="checkbox"]#Guardians_same_address').change(function(e) {
	var that	= this;
    if($(that).is(':checked')){
		$('#hide_1').hide();
		$('#hide_2').hide();
	}else{
		$('#hide_1').show();
		$('#hide_2').show();
		$('#hide_1').find('select,input').val('');
		$('#hide_2').find('select,input').val('');
	}
});
</script>