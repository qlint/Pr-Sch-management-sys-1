<?php
$this->breadcrumbs=array(
	Yii::t('app','Transport')=>array('/transport'),
	Yii::t('app','Driver-Vehicle Association')=>array('/transport/driverDetails/reallot', 'id'=>$_REQUEST['id']),
	Yii::t('app','Reallot'),
);

?>
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
                <h1><?php echo Yii::t('app','Driver - Vehicle Association');?></h1>
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
				$is_insert = PreviousYearSettings::model()->findByAttributes(array('id'=>2));
				if($year != $current_academic_yr->config_value and $is_insert->settings_value==0)
				{
				?>
                    <div>
                        <div class="yellow_bx" style="background-image:none;width:95%;padding-bottom:45px;">
                            <div class="y_bx_head" style="width:95%;">
                            <?php 
                                echo Yii::t('app','You are not viewing the current active year. ');
                                echo Yii::t('app','To assign the driver to a vehicle, enable the Insert option in Previous Academic Year Settings.');	
                            ?>
                            </div>
                            <div class="y_bx_list" style="width:95%;">
                                <h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
                            </div>
                        </div>
                    </div>
                <?php	
				}
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))
				{
					$sel = '';
					if(isset($_REQUEST['id']) and ($_REQUEST['id']!=NULL))
					{
						$sel = $_REQUEST['id'];
				?> 
                <div class="formCon">
                    <div class="formConInner">
                        <table width="90%" border="0" cellspacing="0" cellpadding="0" class="s_search">
                            <tr>
                               <?php /*?> <td><strong><?php echo Yii::t('transport','Select Driver');?></strong></td>
                                <td>&nbsp;</td><?php */?>
                                <td><strong><?php echo Yii::t('app','Select Vehicle');?></strong></td>
                                <td>&nbsp;</td>
                            </tr>
                            
                            <tr>
                                <?php /*?><td><?php echo CHtml::dropDownList('driver_id','',CHtml::listData(DriverDetails::model()->findAll('status=:x',array(':x'=>'C')),'id','first_name'),array('prompt'=>'Select','options'=>array($sel=>array('selected'=>true))));?></td>
                                <td>&nbsp;</td><?php */?>
                                <td><?php echo CHtml::dropDownList('vehicle_id','',CHtml::listData(VehicleDetails::model()->findAll('status IS NULL or status=:x',array(':x'=>'')),'id','vehicle_code'),array('prompt'=>Yii::t('app','Select')));?></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                            	<td colspan="4">&nbsp;</td>
                            </tr>
                            <tr>
                            	<td><?php echo CHtml::submitButton( Yii::t('app','Assign'),array('name'=>'search','class'=>'formbut')); ?></td>
                            </tr>
                        </table>
                    </div> <!-- END div class="formConInner" -->
                </div> <!-- END div class="formCon" -->
                <?php
					}
				}
				?>  
            </div> <!-- END div class="cont_right" -->
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>