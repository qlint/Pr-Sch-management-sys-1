<?php
$this->breadcrumbs=array(
	Yii::t('app','Library')=>array('/library'),
	Yii::t('app','Borrow Book'),	
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/settings/library_left');?>
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">
                <h1><?php echo Yii::t('app','Borrow Book');?></h1>
                <?php
                /*Yii::app()->clientScript->registerScript(
                   'myHideEffect',
                   '$(".error").animate({opacity: 1.0}, 3000).fadeOut("slow");',
                   CClientScript::POS_READY
                );*/
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
				$is_insert = PreviousYearSettings::model()->findByAttributes(array('id'=>2));
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_insert->settings_value!=0))
				{ 
						$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
								if($settings!=NULL)
								{	
									$date1=date($settings->displaydate,strtotime($model->issue_date));
									$date2=date($settings->displaydate,strtotime($model->due_date));
									$format=$settings->dateformat;
		
								}
								else
								{
								$date1 = $model->issue_date;
								$date2 = $model->due_date;
								$format = 'dd-mm-yy';
								}
								$model->issue_date	= $date1;
								$model->due_date	= $date2;
								$student = Students::model()->findByAttributes(array('id'=>$model->student_id,'is_deleted'=>0,'is_active'=>1));
								$model->student_id	= $student->admission_no;
								
 
					echo $this->renderPartial('_formup', array('model'=>$model));
				}
				else
				{
				?>
				<div>
					<div class="yellow_bx" style="background-image:none;width:95%;padding-bottom:45px;">
						<div class="y_bx_head" style="width:95%;">
						<?php 
							echo Yii::t('app','You are not viewing the current active year. ');
							echo Yii::t('app','To borrow book, enable the Insert option in Previous Academic Year Settings.');	
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
