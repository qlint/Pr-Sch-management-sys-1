<?php
$this->breadcrumbs=array(
	Yii::t('app','Attendances')=>array('/attendance'),
	Yii::t('app','Teacher Leave Types')=>array('index'),
	//Yii::t('app','Manage'),
);


?>
<div style="background:#fff; min-height:800px;">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td valign="top">
                <div style="padding:20px; position:relative;" >
                    <h1><?php echo Yii::t('app','Teacher Leave Types');?></h1>
                    <?php $this->renderPartial('/default/employee_tab');?>
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
							<div class="yellow_bx" style="background-image:none;width:95%;padding-bottom:45px;">
								<div class="y_bx_head" style="width:95%;">
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
								<div class="y_bx_list" style="width:95%;">
									<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
								</div>
							</div>
						</div>
					
					
					<?php	
					}
					if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
					{
					?>
                    
                   
                    <p class="note"><?php echo Yii::t('app','Fields with'); ?><span class="required">*</span> <?php echo Yii::t('app','are required.') ;?></p>
                    
                    <?php $form=$this->beginWidget('CActiveForm', array(
                                'id'=>'employee-leave-types-form',
                                'enableAjaxValidation'=>false,
                                )); ?>
                                
                                
                                
                                <?php echo $form->errorSummary($model); ?>
                                <br />
                    <div class="formCon">
                        <div class="formConInner">
                            <div class="form">
                            
							
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td><?php echo $form->labelEx($model,'name'); ?></td>
                                        <td><?php echo $form->textField($model,'name',array('size'=>30,'maxlength'=>25)); ?>
                                        <?php echo $form->error($model,'name'); ?></td>
                                        
                                        <td><?php echo $form->labelEx($model,'code'); ?></td>
                                        <td><?php echo $form->textField($model,'code',array('size'=>30,'maxlength'=>25)); ?>
                                        <?php echo $form->error($model,'code'); ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><?php echo $form->labelEx($model,'max_leave_count'); ?></td>
                                        <td><?php echo $form->textField($model,'max_leave_count',array('size'=>30,'maxlength'=>25)); ?>
                                        <?php echo $form->error($model,'max_leave_count'); ?></td>
                                        
                                        <?php /*?><td colspan="2" class="cr_align">
                                        <?php echo $form->checkBox($model,'carry_forward'); ?>
                                        <?php echo $form->error($model,'carry_forward'); ?>
                                        <?php echo $form->labelEx($model,'carry_forward'); ?>
                                        </td><?php */?>
                                    
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                                
                                
                                <div class="cr_align" >
									<?php echo $form->labelEx($model,'status'); ?>
                                    <?php echo $form->radioButtonList($model,'status',array('1'=>Yii::t('app','Active'),'2'=>Yii::t('app','Inactive')),array('separator'=>' ')); ?>
                                    <?php echo $form->error($model,'status'); ?>
                                </div>
                                
                                <div class="clear"></div>
                                <br />
                                <div class="row buttons">
                                	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'),array('class'=>'formbut')); ?>
                                </div>
                                
                                <?php $this->endWidget(); ?>
                            
                            </div> <!-- END div class="form" -->
                        </div> <!-- END div class="formConInner" -->
                    </div> <!-- END div class="formCon" -->
                    
                    <?php
					}
					?>
                    
                    
                    <h3><?php echo Yii::t('app','Active Leave types');?></h3>
                    <div class="tableinnerlist">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr class="pdtab-h">
                                <td  width="50%"><?php echo Yii::t('app','Leave Type'); ?></td>
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
                            $active=EmployeeLeaveTypes::model()->findAll("status=:x", array(':x'=>1));
                            if($active)
                            {
                                foreach($active as $active_1)
                                {
                                                                    echo '<tr><td>'.$active_1->name.'</td>';
                                                                    if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
                                                                    {	
                                                                    echo '<td>'.CHtml::link(Yii::t('app','Edit'), array('update', 'id'=>$active_1->id)).'</td>';
                                                                    }
                                                                    if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
                                                                    {	
                                                                    echo '<td>'.CHtml::link(Yii::t('app','Delete'), "#", array('submit'=>array('delete','id'=>$active_1->id), 'confirm'=>Yii::t('app','Are you sure?'), 'csrf'=>true)).'</td></tr>';
                                                                    }
                                }
                            }
                            else
                            {
                                ?><tr><td colspan="3"><center>No Results</center></td></tr><?php
                            }
                            ?>
                        </table>
                    </div> <!-- END div class="tableinnerlist" -->
                    <br />
                    
                    
                    
                    <h3><?php echo Yii::t('app','Inactive Leave types');?></h3>
                    <div class="tableinnerlist">
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr class="pdtab-h">
                            <td width="50%"><?php echo Yii::t('app','Leave Type'); ?></td>
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
                            $inactive=EmployeeLeaveTypes::model()->findAll("status=:x", array(':x'=>2));
                            if($inactive)
                            {
                                foreach($inactive as $inactive_1)
                                {
                                            echo '<tr><td>'.$inactive_1->name.'</td>';	
                                                                    if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
                                                                    {	
                                    echo '<td>'.CHtml::link(Yii::t('app','Edit'), array('update', 'id'=>$inactive_1->id)).'</td>';
                                                                    }
                                                                    if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
                                                                    {
                                    echo '<td>'.CHtml::link(Yii::t('app','Delete'), "#", array('submit'=>array('delete','id'=>$inactive_1->id), 'confirm'=>Yii::t('app','Are you sure?'), 'csrf'=>true)).'</td></tr>';
                                                                    }

                                }
                            }
                            else
                            {
                                ?><tr><td colspan="3"><center>No Results</center></td></tr><?php
                            }
                            ?>
                        </table>
                    </div> <!-- END div class="tableinnerlist" -->
                    
                </div>
            </td>
        </tr>
    </table>
</div>