<?php
$this->breadcrumbs=array(
    Yii::t('app','Students'),
	Yii::t('app','Student Leave Types'),
);

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('left_side');?>
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">
                <h1><?php echo Yii::t('app','Student Leave Types');?></h1>
                
                <?php
				$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
				if(Yii::app()->user->year)
				{
					$year = Yii::app()->user->year;
					//echo Yii::app()->user->year;
				}
				else
				{
					$year = $current_academic_yr->config_value;
				}
				$is_create = PreviousYearSettings::model()->findByAttributes(array('id'=>1));
				$is_edit = PreviousYearSettings::model()->findByAttributes(array('id'=>3));
				$is_delete = PreviousYearSettings::model()->findByAttributes(array('id'=>4));
				if($year != $current_academic_yr->config_value and ($is_create->settings_value==0 or $is_edit->settings_value==0 or $is_delete->settings_value==0))
				{
					
				?>
                
                	<div>
						<div class="yellow_bx" style="background-image:none;width:680px;padding-bottom:45px;">
							<div class="y_bx_head" style="width:650px;">
							<?php 
								echo Yii::t('app','You are not viewing the current active year. ');
								if($is_create->settings_value==0 and $is_edit->settings_value!=0 and $is_delete->settings_value!=0)
								{ 
									echo Yii::t('app','To add a new leave type, enable Create option in Previous Academic Year Settings.');
								}
								elseif($is_create->settings_value!=0 and $is_edit->settings_value==0 and $is_delete->settings_value!=0)
								{
									echo Yii::t('app','To edit a leave type, enable Edit option in Previous Academic Year Settings.');
								}
								elseif($is_create->settings_value!=0 and $is_edit->settings_value!=0 and $is_delete->settings_value==0)
								{
									echo Yii::t('app','To delete a leave type, enable Delete option in Previous Academic Year Settings.');
								}
								else
								{
									echo Yii::t('app','To manage the leave type, enable the required options in Previous Academic Year Settings.');	
								}
							?>
							</div>
							<div class="y_bx_list" style="width:650px;">
								<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
							</div>
						</div>
					</div>
                
                
                <?php	
				}
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
				{
				?>
					<!-- Leave Types Form -->
					<p class="note"><?php echo Yii::t('app','Fields with'); ?><span class="required">*</span> <?php echo Yii::t('app','are required.') ;?></p>
					<div class="formCon">
						<div class="formConInner">
							<div class="form">
							
								<?php $form=$this->beginWidget('CActiveForm', array(
								'id'=>'employee-leave-types-form',
								'enableAjaxValidation'=>false,
								'enableClientValidation'=>true,
								)); ?>
								
								
								
								<?php /*?><?php echo $form->errorSummary($model); ?><?php */?>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="80"><?php echo $form->labelEx($model,'name',array('style'=>'float:left')); ?></td>
										<td><?php echo $form->textField($model,'name',array('size'=>30,'maxlength'=>255)); ?>
										<?php echo $form->error($model,'name'); ?></td>
										<td><?php echo $form->labelEx($model,'code',array('style'=>'float:left')); ?></td>
										<td><?php echo $form->textField($model,'code',array('size'=>30,'maxlength'=>255)); ?>
										<?php echo $form->error($model,'code'); ?></td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td><?php echo $form->labelEx($model,'label',array('style'=>'float:left')); ?></td>
										<td><?php echo $form->textField($model,'label',array('size'=>30,'maxlength'=>255)); ?>
										<?php echo $form->error($model,'label'); ?></td>
										
										<td><?php echo $form->labelEx($model,'colour_code',array('style'=>'float:left')); ?></td>
										<td>
                                        
										<?php
                                        $this->widget('ext.SMiniColors.SActiveColorPicker', array(
                                        'model' => $model,
                                        'attribute' => 'colour_code',
                                        'hidden'=>false, // defaults to false - can be set to hide the textarea with the hex
                                        'options' => array(), // jQuery plugin options
                                        'htmlOptions' => array(), // html attributes
                                        ));
                                        ?>
                                        
										<?php echo $form->error($model,'colour_code'); ?></td>
									
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td><?php echo $form->labelEx($model,'status',array('style'=>'')); ?>
										</td>
										<td class="cr_align"><?php echo $form->radioButtonList($model,'status',array('1'=>Yii::t('app','Active'),'2'=>Yii::t('app','Inactive')),array('separator'=>' ','dfault'=>1)); ?>
										<?php echo $form->error($model,'status'); ?></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        
                                      
                                    </tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    
                                    <tr>
                                       <td colspan="2"><?php echo $form->labelEx($model,'is_excluded',array('style'=>'')); ?>  <div style="display: inline-block; margin: 0 0 -6px;"> <?php echo $form->checkBox($model,'is_excluded'); ?></div></td>
                                  <td colspan="2">
                                    
                                     
                                <?php echo $form->error($model,'is_excluded'); ?></td>
									</tr>
								</table>
								
								<div class="clear"></div>
								<br />
								<div class="row buttons">
									<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
								</div>
								
								<?php $this->endWidget(); ?>
							
							</div><!-- form -->
						</div> <!-- END div class="formConInner" -->
					</div> <!-- END div class="formCon" -->
					<!-- END Leave Types Form -->
				<?php
				}
				?>   
                
                <h3><?php echo Yii::t('app','Active Leave types');?></h3>
                <div class="tableinnerlist">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="pdtab-h">
                            <td style="text-align:left;"><?php echo Yii::t('app','Leave Type'); ?></td>
                            <td style="text-align:left;"><?php echo Yii::t('app','Code'); ?></td>
                            <td style="text-align:left;"><?php echo Yii::t('app','Label'); ?></td>
                            <td style="text-align:left;"><?php echo Yii::t('app','Colour Code'); ?></td>
                            <td style="text-align:left;"><?php echo Yii::t('app','Is Excluded'); ?></td>
                            <?php 
							if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
							{
							?>
                            	<td><?php echo Yii::t('app','Edit'); ?></td>
                            <?php
							}
							 
							if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
							{
							?>
                            <td><?php echo Yii::t('app','Delete'); ?></td>
                            <?php
							}
							?>
                        </tr>
                        
                        
                        <?php
                        $active=StudentLeaveTypes::model()->findAll("status=:x", array(':x'=>1));
						if($active !=NULL)
						{
                        foreach($active as $active_1)
                        {
							echo '<tr><td style="padding-left:10px; text-align:left;">'.$active_1->name.'</td>';
							echo '<td style="padding-left:10px; text-align:left;">'.$active_1->code.'</td>';
							echo '<td style="padding-left:10px; text-align:left;">'.$active_1->label.'</td>';
							echo '<td style="padding-left:10px; text-align:left;">'.$active_1->colour_code.'  <span style="width:10px; height:10px; background-color:'.$active_1->colour_code.';">&nbsp;&nbsp;&nbsp;<span></td>';
							if($active_1->is_excluded==1)
							$excluded='Yes';
							elseif($active_1->is_excluded==0)
							$excluded='No';
							echo '<td style="padding-left:10px; text-align:left;">'.$excluded.'</td>';
							if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
							{	
								echo '<td>'.CHtml::link(Yii::t('app','Edit'), array('update', 'id'=>$active_1->id)).'</td>';
							}
							if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
							{
							echo '<td>'.CHtml::link('<span>'.Yii::t('app','Delete').'</span>', "#", array('submit'=>array('delete','id'=>$active_1->id,),'confirm'=>Yii::t('app','Are you sure you want to delete this?'), 'csrf'=>true)).'</td></tr>';
							}
                        }
						}
						else
						{
						?>
                        <tr>
                            <td colspan="7">
                               <?php echo "No Results";?>
                            </td>
                        </tr>
                        <?php
						}
                        ?>
                    </table>
                </div> <!-- END div class="tableinnerlist" -->
                <br />
                
                
                <h3><?php echo Yii::t('app','Inactive Leave types');?></h3>
                <div class="tableinnerlist">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="pdtab-h">
                            <td style="text-align:left;"><?php echo Yii::t('app','Leave Type'); ?></td>
                            <td style="text-align:left;"><?php echo Yii::t('app','Code'); ?></td>
                            <td style="text-align:left;"><?php echo Yii::t('app','Label'); ?></td>
                            <td style="text-align:left;"><?php echo Yii::t('app','Colour Code'); ?></td>
                            <td style="text-align:left;"><?php echo Yii::t('app','Is Excluded'); ?></td>
                            <?php 
							if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
							{
							?>
                            	<td><?php echo Yii::t('app','Edit'); ?></td>
                            <?php
							}
							 
							if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
							{
							?>
                            <td><?php echo Yii::t('app','Delete'); ?></td>
                            <?php
							}
							?>
                        </tr>
                        
                        <?php
                        $inactive=StudentLeaveTypes::model()->findAll("status=:x", array(':x'=>2));
						if($inactive!=NULL)
						{
                        foreach($inactive as $inactive_1)
                        {
							echo '<tr><td style="padding-left:10px; text-align:left;">'.$inactive_1->name.'</td>';
							echo '<td style="padding-left:10px; text-align:left;">'.$inactive_1->code.'</td>';
							echo '<td style="padding-left:10px; text-align:left;">'.$inactive_1->label.'</td>';
							echo '<td style="padding-left:10px; text-align:left;">'.$inactive_1->colour_code.'  <span style="width:10px; height:10px; background-color:'.$inactive_1->colour_code.';">&nbsp;&nbsp;&nbsp;<span></td>';
							if($inactive_1->is_excluded==1)
							$excluded='Yes';
							elseif($inactive_1->is_excluded==0)
							$excluded='No';
							echo '<td style="padding-left:10px; text-align:left;">'.$excluded.'</td>';	
							if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
							{
								echo '<td>'.CHtml::link(Yii::t('app','Edit'), array('update', 'id'=>$inactive_1->id)).'</td>';
							}
							if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
							{
								echo '<td>'.CHtml::link('<span>'.Yii::t('app','Delete').'</span>', "#", array('submit'=>array('delete','id'=>$inactive_1->id,),'confirm'=>Yii::t('app','Are you sure you want to delete this?'), 'csrf'=>true)).'</td></tr>';
							}
                        }
						}
						else
						{?>
                        <tr>
                            <td colspan="7">
                               <?php echo "No Results";?>
                            </td>
                        </tr>
                        <?php
						}
                        ?>
                    </table>
                </div> <!-- END div class="tableinnerlist" -->
            
            </div> <!-- div class="cont_right formWrapper" -->
        </td>
    </tr>
</table>