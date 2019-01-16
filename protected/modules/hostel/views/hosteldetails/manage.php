
<?php
$this->breadcrumbs=array(        
	Yii::t('app','Hostel')=>array('/hostel'),
	Yii::t('app','Hostel Details')=>array('/hostel/Hosteldetails/manage'),
	Yii::t('app','Manage'),
);
?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'vacate-form',
	'enableAjaxValidation'=>false,
)); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        <?php $this->renderPartial('/settings/hostel_left');?>
        </td>
        <td valign="top">
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Manage Hostel Details');?></h1>
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
                                echo Yii::t('app','To add the hostel details, enable Create option in Previous Academic Year Settings.');
                            }
                            elseif($is_create->settings_value!=0 and $is_edit->settings_value==0 and $is_delete->settings_value!=0)
                            {
                                echo Yii::t('app','To edit the hostel details, enable Edit option in Previous Academic Year Settings.');
                            }
                            elseif($is_create->settings_value!=0 and $is_edit->settings_value!=0 and $is_delete->settings_value==0)
                            {
                                echo Yii::t('app','To delete the hostel details, enable Delete option in Previous Academic Year Settings.');
                            }
                            else
                            {
                                echo Yii::t('app','To manage the hostel details, enable the required options in Previous Academic Year Settings.');	
                            }
                        ?>
						</div>
						<div class="y_bx_list" style="width:95%;">
							<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
						</div>
					</div>
				</div> <br />
				<?php	
				}
				$separator = 0;
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0 and $is_delete->settings_value!=0))
				{
					$separator = 1;
				}
				?>
                <?php
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
				{
				?>
 <!-- END div class="edit_bttns" -->
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li> <?php echo CHtml::link('<span>'.Yii::t('app','Enter Hostel Details').'</span>', array('/hostel/Hosteldetails/create'),array('class'=>'a_tag-btn')); ?></li>                                   
</ul>
</div> 
</div>
                <?php
				}
				?>
                <?php $hst=HostelDetails::model()->findAll('is_deleted=:x',array(':x'=>'0'));
                
                ?>
                <div class="pdtab_Con table-padding" style="padding-top:0px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr class="pdtab-h">
                        <td align="center" width="30%"><?php echo Yii::t('app','Name');?></td>
                        <td align="center" width="60%"><?php echo Yii::t('app','Address');?></td>
						<?php
                        if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 or $is_delete->settings_value!=0)))
                        {
                        ?>
                        <td align="center" width="10%"><?php echo Yii::t('app','Action');?></td>
						<?php
                        }
                        ?>
                        </tr>
                    <?php
                    if($hst!=NULL)
                    {
						foreach($hst as $hostel)
						{
						
						
						?>
                            <tr>
                                <td align="center"><?php echo $hostel->hostel_name;?></td>
                                <td align="center"><?php echo $hostel->address ;?></td>
                                <?php
                                if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 or $is_delete->settings_value!=0)))
								{
								?>
                                    <td align="center">
                                    <?php
                                    if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
                                    {
                                        echo CHtml::link(Yii::t('app','Edit'),array('/hostel/Hosteldetails/update','id'=>$hostel->id)); 
                                    }
									
                                    if($separator == 1)
									{
										echo ' | ';
									}
                                    
                                    if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
                                    {
										
										echo CHtml::link(Yii::t('app','Delete'), "#", array("submit"=>array('/hostel/Hosteldetails/deleteall','id'=>$hostel->id),'confirm' => Yii::t('app', 'Are you sure ?'), 'csrf'=>true));
										
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
                    else
                    {
                    	echo '<tr><td align="center" colspan="3"><strong>'.Yii::t('app','No data available').'</strong></td></tr>';
                    }
                    ?>
                    </table>
                </div>
            </div> <!-- END div class="cont_right" -->
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>