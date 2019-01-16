<?php
$this->breadcrumbs=array(
	Yii::t('app','Downloads')=>array('/downloads'),
	Yii::t('app','File Category')=>array('admin'),
	Yii::t('app','Manage'),
);



Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('file-category-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="80" valign="top" id="port-left">
        	<?php $this->renderPartial('/default/left_side');?>
        </td>
        <td valign="top">
        	
            <div class="cont_right"><h1><?php echo Yii::t('app','Manage File Category');?></h1>
            
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
			?>
            
            <?php 				
			if($year != $current_academic_yr->config_value and ($is_edit->settings_value==0 or $is_delete->settings_value==0))
			{
			?>
				<div style="padding-left:10px;">
					<div class="yellow_bx" style="background-image:none;width:690px;padding-bottom:45px;">
						<div class="y_bx_head" style="width:650px;">
						<?php 
							echo Yii::t('app','You are not viewing the current active year. ');
							if($is_edit->settings_value==0 and $is_delete->settings_value!=0)
							{
								echo Yii::t('app','To edit the file, enable Edit option in Previous Academic Year Settings.');
							}
							elseif($is_edit->settings_value!=0 and $is_delete->settings_value==0)
							{
								echo Yii::t('app','To delete the file, enable Delete option in Previous Academic Year Settings.');
							}
							else
							{
								echo Yii::t('app','To manage the file, enable the required options in Previous Academic Year Settings.');	
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
			
			$template = '{view}';
			if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
			{
				$template = $template.'{update}';
			}
			
			if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
			{
				$template = $template.'{delete}';
			}
			?>
            
            
            <div >
            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'file-category-grid',
                'dataProvider'=>$model->search(),
                'filter'=>$model,
                'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
                'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
                'columns'=>array(                    
                    'category',
                    array(
						'header'=>Yii::t('app','Actions'),
                        'class'=>'CButtonColumn',
						'template' => $template,
                    ),
                ),
            )); ?>
            </div>
			</div>
        </td>
    </tr>
</table>