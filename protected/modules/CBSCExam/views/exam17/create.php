<?php
$this->breadcrumbs=array(
	Yii::t('app','Exams') =>array('/examination'),
	Yii::t('app','Create'),
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
          <?php $this->renderPartial('examination.views.default.left_side');?>    
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">           
               	<div class="page-header"> 
                    <div class="header-box">
                        <div class="header-box-one"> <h1><?php echo Yii::t('app','Exams');?></h1></div>
                    </div>               	
                </div>                
                <?php $this->renderPartial('/default/tab');?>
                
                
                <div class="clear"></div>
                <div class="emp_right_contner">
                    <div class="emp_tabwrapper">
						
                        <div class="clear"></div>
                        <div class="emp_cntntbx" style="padding-top:0px;">
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
							$is_create = PreviousYearSettings::model()->findByAttributes(array('id'=>1));
							$is_insert = PreviousYearSettings::model()->findByAttributes(array('id'=>2));
							$is_edit = PreviousYearSettings::model()->findByAttributes(array('id'=>3));
							$is_delete = PreviousYearSettings::model()->findByAttributes(array('id'=>4));				
							if($year != $current_academic_yr->config_value and ($is_insert->settings_value==0 or $is_edit->settings_value==0 or $is_delete->settings_value==0))
							{
							?>
								<div>
									<div class="yellow_bx" style="background-image:none;width:680px;padding-bottom:45px;">
										<div class="y_bx_head" style="width:650px;">
										<?php 
											echo Yii::t('app','You are not viewing the current active year. ');
											if($is_insert->settings_value==0 and $is_edit->settings_value!=0 and $is_delete->settings_value!=0)
											{ 
												echo Yii::t('app','To schedule exams, enable Insert option in Previous Academic Year Settings.');
											}
											elseif($is_insert->settings_value!=0 and $is_edit->settings_value==0 and $is_delete->settings_value!=0)
											{
												echo Yii::t('app','To edit exams, enable Edit option in Previous Academic Year Settings.');
											}
											elseif($is_insert->settings_value!=0 and $is_edit->settings_value!=0 and $is_delete->settings_value==0)
											{
												echo Yii::t('app','To delete exams, enable Delete option in Previous Academic Year Settings.');
											}
											else
											{
												echo Yii::t('app','To manage exams, enable the required options in Previous Academic Year Settings.');	
											}
										?>
										</div>
										<div class="y_bx_list" style="width:650px;">
											<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
										</div>
									</div>
								</div><br/>
							<?php
							}
							?>
                            <?php echo $this->renderPartial('_form', array('model'=>$model,'model_1'=>$model_1)); ?>
                        </div> <!-- END div class="emp_cntntbx" -->
                    </div> <!-- END div class="emp_tabwrapper" -->
                </div> <!-- END div class="emp_right_contner" -->
            </div>
        </td>
    </tr>
</table>