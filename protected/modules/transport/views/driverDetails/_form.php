<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'driver-details-form',
	'enableAjaxValidation'=>false,
)); ?>

<p><?php echo Yii::t('app','Fields with');?><span class="required">*</span><?php echo Yii::t('app','are required.');?></p>

<?php //echo $form->errorSummary($model); ?>
<br />
    
<div class="formCon">
	<div class="formConInner">
  <div class="txtfld-col-box">
  <div class="txtfld-col">
  <label> <?php echo Yii::t('app','First Name');?><span class="required"> *</span></label>
                    <?php echo $form->textField($model,'first_name',array('size'=>20)); ?>
                    <?php echo $form->error($model,'first_name'); ?> 
  </div>
  <div class="txtfld-col">
                     <?php echo $form->labelEx($model,'last_name'); ?> 
                    <?php echo $form->textField($model,'last_name',array('size'=>20)); ?>
                    <?php echo $form->error($model,'last_name'); ?>
  </div>
  <div class="txtfld-col">
   <?php echo $form->labelEx($model,'address'); ?> 
            <?php echo $form->textField($model,'address',array('size'=>20)); ?>
                    <?php echo $form->error($model,'address'); ?>
  </div>
  </div>
  <div class="txtfld-col-box">
  <div class="txtfld-col">
                   <label>  <?php echo Yii::t('app','Phone Number');?><span class="required"> *</span></label>
                    <?php echo $form->textField($model,'phn_no',array('size'=>20)); ?>
                    <?php echo $form->error($model,'phn_no'); ?>
  </div>
  <div class="txtfld-col">
                <?php echo $form->labelEx($model,'dob'); ?> 
                    <?php //echo $form->textField($model,'admission_date');
					
					 $settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                        if($settings!=NULL)
                        {
                            $date=$settings->dateformat;
                        }
                        else
                            $date = 'dd-mm-yy';
                        
                        //set default date
                        if(!(isset($model->dob)))
                        {
                            $model->dob='';
                        }
						else
						{
							$model->dob = date($settings->displaydate,strtotime($model->dob));
						}
					
					
						
						
                    /*$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                    if($settings!=NULL){
                        $date			= $settings->dateformat;
						//$displaydate	= $settings->displaydate;
					}
                    else{
                    	$date 			= 'dd-mm-yy';
						//$displaydate 	= 'd M Y';
					}
					
					$model->dob		= date($displaydate, strtotime($model->dob));*/
					
                    $msg = 'changed';
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						//'name'=>'Students[admission_date]',
						'model'=>$model,
						'id'=>'dateob',
						'attribute'=>'dob',
						// additional javascript options for the date picker plugin
						'options'=>array(
							'showAnim'=>'fold',
							'dateFormat'=>$date,
							'changeMonth'=> true,
							'changeYear'=>true,
							'yearRange'=>'1900:',
						),
						'htmlOptions'=>array(
							'style'=>'',
							
						),
					));
             ?>
                    <?php echo $form->error($model,'dob'); ?>
  </div>
  <div class="txtfld-col">
                <?php echo $form->labelEx($model,'license_no'); ?> 
                    <?php echo $form->textField($model,'license_no',array('size'=>20)); ?>
                    <?php echo $form->error($model,'license_no'); ?>
  </div>
  </div>
  <div class="txtfld-col-box">
  <div class="txtfld-col">
 <?php echo $form->labelEx($model,'expiry_date'); ?> 
                <?php
				
				$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
                        if($settings!=NULL)
                        {
                            $date=$settings->dateformat;
                        }
                        else
                            $date = 'dd-mm-yy';
                        
                        //set default date
                        if(!(isset($model->expiry_date)))
                        {
                            $model->expiry_date='';
                        }
						else
						{
							$model->expiry_date = date($settings->displaydate,strtotime($model->expiry_date));
						}
						
                $daterange=date('Y');
               $daterange_1=$daterange+20;
                ?>
                    <?php //echo $form->textField($model,'admission_date');
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                    //'name'=>'Students[admission_date]',
                                    'model'=>$model,
                                    'attribute'=>'expiry_date',
                                    // additional javascript options for the date picker plugin
                                    'options'=>array(
                                        'showAnim'=>'fold',
                                        'dateFormat'=>$date,
                                        'changeMonth'=> true,
                                        'changeYear'=>true,
                                        'yearRange'=>'1900:'.$daterange_1,
                                    ),
                                    'htmlOptions'=>array(
                                        'style'=>'',
										'readonly'=>true
                                    ),
                                ));
             ?>
                    <?php echo $form->error($model,'expiry_date'); ?>
  </div>
  </div>  
  
  
        
	</div>
</div>
	

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->