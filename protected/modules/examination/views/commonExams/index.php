<style>
.grid-view table.items th{
	padding: 8px 4px !important;

}
.grid-view table.items th a {
	font-size: 12px !important;
}
</style>
<?php
$this->breadcrumbs=array(
	Yii::t('app','Examination')=>array('/examination'),
	Yii::t('app','Common Exams')=>array('index'),
	Yii::t('app','Manage')
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
	
	$template 	= '';
	if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0)){
		$template = $template.'{update}';
	}
	
	if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0)){
		$template .= (($template!="")?' ':'').'{delete}';
	}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/default/left_side');?>        
        </td>        
        <td valign="top">
            <div class="cont_right formWrapper">
                <h1><?php echo Yii::t('app','Manage Common Exams');?></h1>
                
<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
                            <li>
                            <?php 
                            if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
                            {
                            echo CHtml::link('<span>'.Yii::t('app','Create Exam').'</span>', array('create'),array('id'=>'add_exam-groups','class'=>'a_tag-btn')); 
                            }
                            ?>
                            </li>                                   
</ul>
</div> 

</div>  
                
				<?php
                Yii::app()->clientScript->registerScript(
                	'myHideEffect',
                	'$(".success").animate({opacity: 1.0}, 3000).fadeOut("slow");',
                	CClientScript::POS_READY
                );
                ?>
                <?php
                /* Success Message */
                if(Yii::app()->user->hasFlash('success')): 
				?>
                    <div class="success" style="background:#FFF; color:#C00; padding-left:200px; font-size:16px">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                    </div>
                <?php endif;
                 /* End Success Message */
                ?>
                
                <div class="clear"></div>                
                <div class="emp_right_contner">
                    <div class="emp_tabwrapper">
                        <div class="clear"></div>
                        <div>
                            <?php 				
							if($year != $current_academic_yr->config_value and ($is_create->settings_value==0 or $is_edit->settings_value==0 or $is_delete->settings_value==0)){
							?>
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
							<?php
							}
							?>
                            
                            <?php							
								$this->widget('zii.widgets.grid.CGridView', array(
									'id' => 'exam-groups-grid',
									'dataProvider' => $model->search(),
									'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
									'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',								
									'htmlOptions'=>array('class'=>'grid-view clear'),
									'columns' => array(								
										array(							
											'name'=>'name',
											'headerHtmlOptions'=>array('style'=>'width:80px;'),
										),
										array(							
											'name'=>'exam_type',
											'headerHtmlOptions'=>array('style'=>'width:80px;'),
										),
										array(
											'name'=>'is_published',
											'value'=>'$data->is_published ? Yii::t("app","Yes") : Yii::t("app","No")',
											'headerHtmlOptions'=>array('style'=>'width:110px;'),
										),
										array(
											'name'=>'result_published',
											'value'=>'$data->result_published ? Yii::t("app","Yes") : Yii::t("app","No")',
											'headerHtmlOptions'=>array('style'=>'width:110px;'),
										),
										array(
											'name'=>'exam_date',
											'value'=>'$data->examDate',
											'headerHtmlOptions'=>array('style'=>'width:80px;'),
										),
										array(
											'class' => 'CButtonColumn',
											'header' => Yii::t('app', 'Action'),
											'headerHtmlOptions'=>array('style'=>'font-size:12px; font-weight:bold; width:110px;'),
											'deleteConfirmation'=>Yii::t('app','Are you sure you want to delete this common exam?'),
											'afterDelete'=>'function(link, success, data){window.location.reload();}',
											'template'=>$template.'{manage}',
											'buttons' => array(											
												'manage' => array(
													'label' => Yii::t('app','Manage'), // text label of the button											
													'url'=>'Yii::app()->createUrl("/examination/commonExams/manage", array("id"=>$data->id))', // a PHP expression for generating the URL of the button
												)
											),
										)
									)
								));
                            ?>
                        </div>
                    </div> <!-- END div class="emp_tabwrapper" -->
                </div> <!-- END div class="emp_right_contner" -->
            </div> <!-- END div class="cont_right formWrapper" -->
        </td>
    </tr>
</table>