<?php
$this->breadcrumbs=array(
	Yii::t('app','Hostel')=>array('/hostel'),	
	Yii::t('app','Allot Room'),
);

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
            <?php $this->renderPartial('/settings/hostel_left');?>
        </td>
        <td valign="top"> 
            <div class="cont_right">
                <h1><?php echo Yii::t('app','Registration');?></h1>
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
                if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
                {
                    echo $this->renderPartial('_form', array('model'=>$model));
                }
                else
                {
                ?>
                <div>
                    <div class="yellow_bx" style="background-image:none;width:95%;padding-bottom:45px;">
                        <div class="y_bx_head" style="width:95%;">
                        <?php 
                            echo Yii::t('app','You are not viewing the current active year. ');
                            echo Yii::t('app','To register, enable the Create option in Previous Academic Year Settings.');	
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
            </div>
        </td>
    </tr>
</table>
