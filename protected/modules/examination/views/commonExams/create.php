<style>
.grid-view table.items th{
	padding: 8px 4px !important;
	width: 98px !important;
}
.grid-view table.items th a {
	font-size: 12px !important;
}
</style>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Examination')=>array('/examination'),
	Yii::t('app','Common Exams')=>array('index'),
	Yii::t('app','Create')
);
?>
<?php
	$current_academic_yr = Configurations::model()->findByAttributes(array('id'=>35));
	if(Yii::app()->user->year){
		$year = Yii::app()->user->year;
	}
	else{
		$year = $current_academic_yr->config_value;
	}
	$is_create 	= PreviousYearSettings::model()->findByAttributes(array('id'=>1));
	$is_insert 	= PreviousYearSettings::model()->findByAttributes(array('id'=>2));
	$is_edit 	= PreviousYearSettings::model()->findByAttributes(array('id'=>3));
	$is_delete 	= PreviousYearSettings::model()->findByAttributes(array('id'=>4));
	
	$template 	= '{exam-groups_view}';
	if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0)){
		$template = $template.'{exam-groups_update}';
	}
	
	if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0)){
		$template = $template.'{exam-groups_delete}';
	}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/default/left_side');?>        
        </td>        
        <td valign="top">
            <div class="cont_right formWrapper">
                <h1><?php echo Yii::t('app','Create Common Exam');?></h1>
                
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li><?php echo CHtml::link('<span>'.Yii::t('app','Manage Exam').'</span>', array('index'),array('id'=>'add_exam-groups','class'=>'a_tag-btn'));?></li>                                   
</ul>
</div> 

</div>
                
                <div class="clear"></div>                
                <div class="emp_right_contner">
                    <div class="emp_tabwrapper">
                        <div class="clear"></div>
                        <div>
                            <?php if($year != $current_academic_yr->config_value and ($is_create->settings_value==0 or $is_edit->settings_value==0 or $is_delete->settings_value==0)){ ?>
								<div>
									<div class="yellow_bx" style="background-image:none;width:680px;padding-bottom:45px;">
										<div class="y_bx_head" style="width:650px;">
										<?php 
											echo Yii::t('app','You are not viewing the current active year. ');
											echo Yii::t('app','To manage examss, enable the required options in Previous Academic Year Settings.');	
										?>
										</div>
										<div class="y_bx_list" style="width:650px;">
											<h1><?php echo CHtml::link(Yii::t('app','Previous Academic Year Settings'),array('/previousYearSettings/create')) ?></h1>
										</div>
									</div>
								</div><br />
							<?php }?>
                            
                            <?php
								 Yii::app()->clientScript->registerScript(
									 'myHideEffect',
									 '$(".flashMessage").animate({opacity: 1.0}, 3000).fadeOut("slow");',
									 CClientScript::POS_READY
								 );
                            ?>
                            
                            <?php $this->renderPartial('_form', array('model'=>$model)); ?>
                            
                        </div>
                    </div> <!-- END div class="emp_tabwrapper" -->
                </div> <!-- END div class="emp_right_contner" -->
            </div> <!-- END div class="cont_right formWrapper" -->
        </td>
    </tr>
</table>