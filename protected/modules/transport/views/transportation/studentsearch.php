<?php
$this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Search Students'),
);?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'book-form',
	'enableAjaxValidation'=>false,
)); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/transportation/trans_left');?>
        </td>
        <td valign="top"> 
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Search Students');?></h1>
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
				$is_delete = PreviousYearSettings::model()->findByAttributes(array('id'=>4));
				if($year != $current_academic_yr->config_value and ($is_create->settings_value==0 or $is_edit->settings_value==0 or $is_delete->settings_value==0))
				{
				?>
                	<div>
						<div class="yellow_bx" style="background-image:none;width:680px;padding-bottom:45px;">
							<div class="y_bx_head" style="width:650px;">
							<?php 
								echo Yii::t('app','You are not viewing the current active year. ');
								if($is_edit->settings_value==0 and $is_delete->settings_value!=0)
								{
									echo Yii::t('app','To edit student transportation details, enable Edit option in Previous Academic Year Settings.');
								}
								elseif($is_edit->settings_value!=0 and $is_delete->settings_value==0)
								{
									echo Yii::t('app','To delete student transportation details, enable Delete option in Previous Academic Year Settings.');
								}
								else
								{
									echo Yii::t('app','To manage student transportation details, enable the required options in Previous Academic Year Settings.');	
								}
							?>
							</div>
							<div class="y_bx_list" style="width:650px;">
								<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
							</div>
						</div>
					</div><br />
                <?php
				}
				$edit_n_delete = 0;
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 and $is_delete->settings_value!=0)))
				{
					$edit_n_delete = 1;
				}
				
				$edit_or_delete = 0;
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 or $is_delete->settings_value!=0)))
				{
					$edit_or_delete = 1;
				}
				?>
                <div class="formCon">
                    <div class="formConInner">
<div class="text-fild-bg-block">           
<div class="text-fild-block inputstyle">
<?php echo Yii::t('app','Name');?>
<div style="position:relative;"> 
								<?php  $this->widget('zii.widgets.jui.CJuiAutoComplete',
											array(
											'name'=>'name',
											'id'=>'name_widget',
											'source'=>$this->createUrl('/site/autocomplete'),
											'htmlOptions'=>array('style'=>'width:148px;','placeholder'=>Yii::t('app','Student Name')),
											'options'=>
											array(
											'showAnim'=>'fold',
											'select'=>"js:function(student, ui) {
											$('#id_widget').val(ui.item.id);
											
											}"
											),
										
										));
                                ?>
                                <?php echo CHtml::hiddenField('student_id','',array('id'=>'id_widget')); ?>
                                <?php echo CHtml::ajaxLink('',array('/site/explorer','widget'=>'1'),array('update'=>'#explorer_handler'),array('id'=>'explorer_student_name','class'=>'exp_but-n'));?></div>
</div>
<div class="text-fild-block inputstyle">
<?php echo Yii::t('app','Route');?>
<?php echo CHtml::dropDownList('route','',CHtml::listData(RouteDetails::model()->findAll(),'id','route_name'),array('prompt'=>Yii::t('app','Select'),
                                'ajax' => array(
                                'type'=>'POST',
                                'url'=>CController::createUrl('/transport/transportation/routes'),
                                'update'=>'#stop_id',
                                'data'=>'js:{route:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',)));?>
</div>
<div class="text-fild-block inputstyle">
<?php echo Yii::t('app','Stop');?>
<?php echo CHtml::activeDropDownList($model,'stop_id',array(),array('prompt'=>Yii::t('app','Select'),'id'=>'stop_id')); ?>
</div>

</div>
<div class="text-fild-bg-block"> 
<?php echo CHtml::submitButton(Yii::t('app', 'Search'),array('name'=>'search','class'=>'formbut')); ?>
</div>
                    
                        <?php /*?><table width="100%" border="0" cellspacing="0" cellpadding="0" class="s_search">
                            <tr>
                                <td width="35%"><strong><?php echo Yii::t('app','Name');?></strong></td>
                                <td width="2%">&nbsp;</td>
                                <td width="11%"><strong><?php echo Yii::t('app','Route');?></strong></td>
                                <td width="3%">&nbsp;</td>
                                <td width="55%"><strong><?php echo Yii::t('app','Stop');?></strong></td>
                                
                            </tr>
                            <tr>
                                <td><div style="position:relative;"> 
								<?php  $this->widget('zii.widgets.jui.CJuiAutoComplete',
											array(
											'name'=>'name',
											'id'=>'name_widget',
											'source'=>$this->createUrl('/site/autocomplete'),
											'htmlOptions'=>array('style'=>'width:148px;','placeholder'=>Yii::t('app','Student Name')),
											'options'=>
											array(
											'showAnim'=>'fold',
											'select'=>"js:function(student, ui) {
											$('#id_widget').val(ui.item.id);
											
											}"
											),
										
										));
                                ?>
                                <?php echo CHtml::hiddenField('student_id','',array('id'=>'id_widget')); ?>
                                <?php echo CHtml::ajaxLink('',array('/site/explorer','widget'=>'1'),array('update'=>'#explorer_handler'),array('id'=>'explorer_student_name','class'=>'exp_but'));?></div></td>

                                <td>&nbsp;</td>
                                
                                <td>
                                <?php echo CHtml::dropDownList('route','',CHtml::listData(RouteDetails::model()->findAll(),'id','route_name'),array('prompt'=>Yii::t('app','Select'),
                                'ajax' => array(
                                'type'=>'POST',
                                'url'=>CController::createUrl('/transport/transportation/routes'),
                                'update'=>'#stop_id',
                                'data'=>'js:{route:$(this).val(), "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"}',)));?></td>
                                <td>&nbsp;</td>
                                <td> 
								<?php echo CHtml::activeDropDownList($model,'stop_id',array(),array('prompt'=>Yii::t('app','Select'),'id'=>'stop_id')); ?></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                </tr>
                                <tr>
                                <td><?php echo CHtml::submitButton(Yii::t('app', 'Search'),array('name'=>'search','class'=>'formbut')); ?></td>  
                            </tr>
                        </table><?php */?>
                    </div> <!-- END div class="formConInner" -->
                </div> <!-- END div class="formCon" -->
                <?php
                if(isset($list))
                {
					echo ' <h3>'.Yii::t('app','Search Results').'</h3>';
					?>
					<div class="pdtab_Con" style="padding:0px;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr class="pdtab-h">
                            	<?php
									if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){						
							  ?> 
                                	<td align="center"><?php echo Yii::t('app','Name');?></td>
                                <?php } ?>    
                                <td align="center"><?php echo Yii::t('app','Route');?></td>
                                <td align="center"><?php echo Yii::t('app','Stop');?></td>
                                <?php 
								if($edit_or_delete == 1)
								{
								?>
								<td align="center">
									<?php echo Yii::t('app','Action');?>
								</td>
								<?php
								}
								?> 
                            </tr>
                            <?php
                            if($list==NULL)
                            {
                            	echo '<tr><td align="center" colspan="4"><strong>'.Yii::t('app','No such student is using the transport facility.').'</strong></td></tr>';
                            }
                            else
                            {
								foreach($list as $list_1)
								{
									$student=Students::model()->findByAttributes(array('id'=>$list_1->student_id));
									$stopdetails=StopDetails::model()->findByAttributes(array('id'=>$list_1->stop_id));
									$routedetails=RouteDetails::model()->findByAttributes(array('id'=>$stopdetails->route_id));
									?>
									<tr>
                                    	<?php
											if(FormFields::model()->isVisible("fullname", "Students", "forStudentProfile")){						
									  ?> 
											<td align="center"><?php echo $student->studentFullName('forStudentProfile');?> </td>
                                       <?php } ?>     
										<td align="center"><?php echo $routedetails->route_name;?></td>
										<td align="center"><?php echo $stopdetails->stop_name;?></td>
                                        <?php 
										if($edit_or_delete == 1)
										{
										?>
										<td align="center">
											<?php
											if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
											{
												echo CHtml::link(Yii::t('app','Reallot'),array('/transport/transportation/reallot','id'=>$list_1->id));
											}
											if($edit_n_delete ==1)
											{
												echo ' | ';
											}
											if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
											{
												echo CHtml::link(Yii::t('app','Remove'), "#", array('submit'=>array('remove','id'=>$list_1->id,), 'confirm'=>Yii::t('app','Are you sure?'), 'csrf'=>true)); 
											}
											?>
										</td>
										<?php
										}
										?>
										
									</tr>
								<?php
								}
							}
                            ?>
                        </table>
					</div> <!-- END div class="pdtab_Con" -->
					<?php
                } // END if(isset($list))
                ?> 
            </div> <!-- END div class="cont_right" -->        
        </td>
    </tr>
</table>

 <?php $this->endWidget(); ?>