<?php
$this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','List All Routs')=>array('/transport/routeDetails/manage'),
	Yii::t('app','Stop Details'),
	Yii::t('app','Update'),
);?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'stop-edit-form',
	'enableAjaxValidation'=>false,
)); 
?>

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="247" valign="top">
                    <?php $this->renderPartial('/transportation/trans_left');?>
                </td>
                <td valign="top"> 
                    <div class="cont_right">
                    <?php echo $form->errorSummary($model); ?>

                        <h1><?php echo Yii::t('app','Edit Stop Details');?></h1>
                        
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
						if($year != $current_academic_yr->config_value and $is_edit->settings_value==0)
						{
						
						?>
						<div>
							<div class="yellow_bx" style="background-image:none;width:95%;padding-bottom:45px;">
								<div class="y_bx_head" style="width:95%;">
								<?php 
									echo Yii::t('app','You are not viewing the current active year. ');
									echo Yii::t('app','To edit stop details, enable the Edit option in Previous Academic Year Settings.');	
								?>
								</div>
								<div class="y_bx_list" style="width:95%;">
									<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
								</div>
							</div>
						</div>
						<?php
						}
						?>
                        <?php
						if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
						{
						?>
                        <div class="formCon">
                            <div class="formConInner">
                                <table width="80%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>
                                       <?php echo $form->labelEx($model,'stop_name',array('style'=>'float:left;')); ?>
                                    </td>
                                    <td>&nbsp;</td>
                                    <td>
                                        <?php echo $form->textField($model,'stop_name',array('size'=>20)); ?>
                                        <?php echo $form->error($model,'stop_name'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                       <?php echo $form->labelEx($model,'fare',array('style'=>'float:left;')); ?> 
                                    </td>
                                    <td>&nbsp;
                                    </td>
                                    <td>
                                        <?php echo $form->textField($model,'fare',array('size'=>20)); ?>
                                        <?php echo $form->error($model,'fare'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $form->labelEx($model,'arrival_mrng',array('style'=>'float:left;')); ?> <span class="required">*</span> 
                                        
                                    </td>
                                    <td>&nbsp;</td>
                                    <td>
                                         <?php
										$this->widget('application.extensions.jui_timepicker.JTimePicker', array(
										 'model'=>$model,
										 'id'=>'arrival_mrng',
										 'attribute'=>'arrival_mrng',
										 'name'=>'StopDetails[arrival_mrng]',
										 'options'=>array(
											 'showPeriod'=>true,
											 'showPeriodLabels'=> true,
											 'showCloseButton'=> true,       
											'closeButtonText'=> 'Done',     
											'showNowButton'=> true,        
											'nowButtonText'=> 'Now',        
											'showDeselectButton'=> true,   
											'deselectButtonText'=> 'Deselect' 
											 ),
										 ));
                						?>
                                 
                                        <?php echo $form->error($model,'arrival_mrng'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                 <tr>
                                    <td>
                                         <?php echo $form->labelEx($model,'arrival_evng',array('style'=>'float:left;')); ?> <span class="required">*</span> 
                                         
                                    </td>
                                    <td>&nbsp;</td>
                                    <td>
                                        <?php
										$this->widget('application.extensions.jui_timepicker.JTimePicker', array(
										 'model'=>$model,
										 //'id'=>'arrival_evng',
										 'attribute'=>'arrival_evng',
										 'name'=>'StopDetails[arrival_evng]',
										 'options'=>array(
											 'showPeriod'=>true,
											 'showPeriodLabels'=> true,
											 'showCloseButton'=> true,       
											'closeButtonText'=> 'Done',     
											'showNowButton'=> true,        
											'nowButtonText'=> 'Now',        
											'showDeselectButton'=> true,   
											'deselectButtonText'=> 'Deselect' 
											 ),
										 ));
                						?>
                                        <?php echo $form->error($model,'arrival_evng'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                </table>
                                            
                            </div> <!-- END div class="formConInner" -->
                        </div> <!-- END div class="formCon" -->  
                       
                        <div class="row buttons">
							<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
                        </div>     
						<?php
						}
						?> 
                    </div> <!-- END div class="cont_right" -->
                </td>
            </tr>
        </table>
       

<?php $this->endWidget(); ?>