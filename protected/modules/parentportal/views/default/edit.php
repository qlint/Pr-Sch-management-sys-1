<style>
form .form-group{
	margin-bottom:15px !important;
}
</style>

<?php $this->renderPartial('leftside'); ?>
<div class="pageheader">
    <h2><i class="fa fa-user"></i> <?php echo Yii::t('app','Profile'); ?> <span><?php echo Yii::t('app','View your profile here'); ?></span></h2>
    <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
        <ol class="breadcrumb">        
        	<li class="active"><?php echo Yii::t('app','Profile'); ?></li>
        </ol>
    </div>
</div>
<div class="contentpanel">
	<div class="panel panel-default">
    	<div class="panel-heading"><h3 class="panel-title"><?php echo Yii::t('app','Edit Profile');?></h3></div>
        <div class="panel-body">
			<?php 
                $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'guardians-form',
                    'enableAjaxValidation'=>false,
                )); 
            ?>
                <h4><?php echo Yii::t('app','Personal Details'); ?></h4> 
                <div class="row">
                	<?php if(FormFields::model()->isVisible('first_name','Guardians','forParentPortal')){ ?>
                    	<div class="col-sm-4 colm-4min col-4-reqst">
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'first_name',array('class'=>'control-label')); ?>
								<?php echo $form->textField($model,'first_name',array('class'=>'form-control','size'=>30,'maxlength'=>255)); ?>
                                <?php echo $form->error($model,'first_name'); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(FormFields::model()->isVisible('last_name','Guardians','forParentPortal')){ ?>
                    	<div class="col-sm-4 colm-4min col-4-reqst">
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'last_name',array('class'=>'control-label')); ?>
								<?php echo $form->textField($model,'last_name',array('class'=>'form-control','size'=>30,'maxlength'=>255)); ?>
                                <?php echo $form->error($model,'last_name'); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(FormFields::model()->isVisible('dob','Guardians','forParentPortal')){ ?>
                          <div class="col-sm-4 colm-4min col-4-reqst">
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'dob',array('class'=>'control-label')); ?>
                              <?php 									
								$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
								if($settings!=NULL){									
									$date = $settings->dateformat;
									$model->dob=date($settings->displaydate,strtotime($model->dob));
								}
								else{
									$date = 'dd-mm-yy';
								}
								$this->widget('zii.widgets.jui.CJuiDatePicker', array(								
									'attribute'=>'dob',
									'model'=>$model,
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
										'readonly'=>true
									),
								));
					
								?>
								<?php echo $form->error($model,'dob'); ?>
                            </div>
                        </div>
                      <?php } ?>
                      <?php if(FormFields::model()->isVisible('education','Guardians','forParentPortal')){ ?>
                    	<div class="col-sm-4 colm-4min col-4-reqst">
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'education',array('class'=>'control-label')); ?>
								<?php echo $form->textField($model,'education',array('class'=>'form-control','size'=>30,'maxlength'=>255)); ?>
                                <?php echo $form->error($model,'education'); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(FormFields::model()->isVisible('occupation','Guardians','forParentPortal')){ ?>
                    	<div class="col-sm-4 colm-4min col-4-reqst">
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'occupation',array('class'=>'control-label')); ?>
								<?php echo $form->textField($model,'occupation',array('class'=>'form-control','size'=>30,'maxlength'=>255)); ?>
                                <?php echo $form->error($model,'occupation'); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(FormFields::model()->isVisible('income','Guardians','forParentPortal')){ ?>
                    	<div class="col-sm-4 colm-4min col-4-reqst">
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'income',array('class'=>'control-label')); ?>
								<?php echo $form->textField($model,'income',array('class'=>'form-control','size'=>30,'maxlength'=>255)); ?>
                                <?php echo $form->error($model,'income'); ?>
                            </div>
                        </div>
                    <?php } ?>
                    
                    <!-- dynamic fields -->
					  <?php
						$fields     = FormFields::model()->getDynamicFields(2, 1, "forParentPortal");
						if($fields){
							foreach ($fields as $key => $field) {
								if($field->form_field_type!=NULL){
									$this->renderPartial("application.modules.dynamicform.views.fields.portal-form._field_".$field->form_field_type, array('model'=>$model, 'field'=>$field));                                                
								}                                               
							}
						}
                      ?>
                      <!-- dynamic fields -->
                </div> 
                <h4><?php echo Yii::t('app', 'Contact Details'); ?></h4>
          		<div class="row"> 
                	<?php if(FormFields::model()->isVisible('email','Guardians','forParentPortal')){ ?>
                    	<div class="col-sm-4 colm-4min col-4-reqst">
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'email',array('class'=>'control-label')); ?>
								<?php echo $form->textField($model,'email',array('class'=>'form-control','size'=>30,'maxlength'=>255)); ?>
                                <?php echo $form->error($model,'email'); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(FormFields::model()->isVisible('mobile_phone','Guardians','forParentPortal')){ ?>
                    	<div class="col-sm-4 colm-4min col-4-reqst">
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'mobile_phone',array('class'=>'control-label')); ?>
								<?php echo $form->textField($model,'mobile_phone',array('class'=>'form-control','size'=>30,'maxlength'=>255)); ?>
                                <?php echo $form->error($model,'mobile_phone'); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(FormFields::model()->isVisible('office_phone1','Guardians','forParentPortal')){ ?>
                    	<div class="col-sm-4 colm-4min col-4-reqst">
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'office_phone1',array('class'=>'control-label')); ?>
								<?php echo $form->textField($model,'office_phone1',array('class'=>'form-control','size'=>30,'maxlength'=>255)); ?>
                                <?php echo $form->error($model,'office_phone1'); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(FormFields::model()->isVisible('office_phone2','Guardians','forParentPortal')){ ?>
                    	<div class="col-sm-4 colm-4min col-4-reqst">
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'office_phone2',array('class'=>'control-label')); ?>
								<?php echo $form->textField($model,'office_phone2',array('class'=>'form-control','size'=>30,'maxlength'=>255)); ?>
                                <?php echo $form->error($model,'office_phone2'); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(FormFields::model()->isVisible('office_address_line1','Guardians','forParentPortal')){ ?>
                    	<div class="col-sm-4 colm-4min col-4-reqst">
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'office_address_line1',array('class'=>'control-label')); ?>
								<?php echo $form->textField($model,'office_address_line1',array('class'=>'form-control','size'=>30,'maxlength'=>255)); ?>
                                <?php echo $form->error($model,'office_address_line1'); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(FormFields::model()->isVisible('office_address_line2','Guardians','forParentPortal')){ ?>
                    	<div class="col-sm-4 colm-4min col-4-reqst">
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'office_address_line2',array('class'=>'control-label')); ?>
								<?php echo $form->textField($model,'office_address_line2',array('class'=>'form-control','size'=>30,'maxlength'=>255)); ?>
                                <?php echo $form->error($model,'office_address_line2'); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(FormFields::model()->isVisible('city','Guardians','forParentPortal')){ ?>
                    	<div class="col-sm-4 colm-4min col-4-reqst">
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'city',array('class'=>'control-label')); ?>
								<?php echo $form->textField($model,'city',array('class'=>'form-control','size'=>30,'maxlength'=>255)); ?>
                                <?php echo $form->error($model,'city'); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(FormFields::model()->isVisible('state','Guardians','forParentPortal')){ ?>
                    	<div class="col-sm-4 colm-4min col-4-reqst">
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'state',array('class'=>'control-label')); ?>
								<?php echo $form->textField($model,'state',array('class'=>'form-control','size'=>30,'maxlength'=>255)); ?>
                                <?php echo $form->error($model,'state'); ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(FormFields::model()->isVisible('country_id','Guardians','forParentPortal')){ ?>
                    	<div class="col-sm-4 colm-4min col-4-reqst">
                            <div class="form-group">
                                <?php echo $form->labelEx($model,'country_id',array('class'=>'control-label')); ?>
								<?php echo $form->dropDownList($model,'country_id',CHtml::listData(Countries::model()->findAll(),'id','name'),array(
                                    'class'=>'form-control mb15','empty'=>Yii::t('app','Select Country')
                                    )); ?>
                                <?php echo $form->error($model,'country_id'); ?>
                            </div>
                        </div>
                    <?php } ?>
                    
                    <?php
						$fields_1     = FormFields::model()->getDynamicFields(2, 2, "forParentPortal");
						if($fields_1){
							foreach ($fields_1 as $key => $field) {
								if($field->form_field_type!=NULL){
									$this->renderPartial("application.modules.dynamicform.views.fields.portal-form._field_".$field->form_field_type, array('model'=>$model, 'field'=>$field));                                                
								}                                               
							}
						}
					?>
                </div>
                <div class="panel-footer">
                	<?php echo CHtml::submitButton(Yii::t('app','Save'),array('class'=>'btn btn-primary')); ?>
                </div>
            
            <?php $this->endWidget(); ?>
        </div>
    </div>    
</div>