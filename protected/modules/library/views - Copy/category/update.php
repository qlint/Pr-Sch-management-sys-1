<?php
$this->breadcrumbs=array(
	Yii::t('app','Library')=>array('/library'),
	Yii::t('app','Book Category')=>array('/library/Category/admin'),
	Yii::t('app','Update'),
);

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/settings/library_left');?>
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">
                <h1><?php echo Yii::t('app','Update Category');?></h1>
                <?php
                Yii::app()->clientScript->registerScript(
                   'myHideEffect',
                   '$(".error").animate({opacity: 1.0}, 3000).fadeOut("slow");',
                   CClientScript::POS_READY
                );
                ?>
                <?php
                    //////////////////////////////////
                    if(Yii::app()->user->hasFlash('errorMessage')): ?>
                <div class="error" style="background:#FFF; color:#C00; padding-left:200px;">
                    <?php echo Yii::app()->user->getFlash('errorMessage'); ?>
                </div>
                <?php endif;
                    
                    /////////////////////////////////
                ?>
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
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
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
							echo Yii::t('app','To edit book category, enable the Edit option in Previous Academic Year Settings.');	
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