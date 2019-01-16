<?php
$this->breadcrumbs=array(
	Yii::t('app','Library')=>array('/library'),
	Yii::t('app','Manage Book Category')=>array('/library/Category/admin'),
	Yii::t('app','Manage'),
);



Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('category-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>



<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	 <?php $this->renderPartial('/settings/library_left');?>    
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">
            	<h1><?php echo Yii::t('app','Manage Book Categories');?></h1>
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
				
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
				{
				?>

<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li> <?php echo CHtml::link(Yii::t('app','Create Category'), array('create'),array('id'=>'add_category','class'=>'a_tag-btn')) ?></li>                                  
</ul>
</div> 

</div>
                <?php
				}
				
				
				if($year != $current_academic_yr->config_value and ($is_create->settings_value==0 or $is_edit->settings_value==0 or $is_delete->settings_value==0))
				{
				?>
					<div>
						<div class="yellow_bx" style="background-image:none;width:680px;padding-bottom:45px;">
							<div class="y_bx_head" style="width:650px;">
							<?php 
								echo Yii::t('app','You are not viewing the current active year. ');
								if($is_create->settings_value==0 and $is_edit->settings_value!=0 and $is_delete->settings_value!=0)
								{ 
									echo Yii::t('app','To add a new category, enable Create option in Previous Academic Year Settings.');
								}
								elseif($is_create->settings_value!=0 and $is_edit->settings_value==0 and $is_delete->settings_value!=0)
								{
									echo Yii::t('app','To edit the category, enable Edit option in Previous Academic Year Settings.');
								}
								elseif($is_create->settings_value!=0 and $is_edit->settings_value!=0 and $is_delete->settings_value==0)
								{
									echo Yii::t('app','To delete the category, enable Delete option in Previous Academic Year Settings.');
								}
								else
								{
									echo Yii::t('app','To manage the categories, enable the required options in Previous Academic Year Settings.');	
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
				
				$template = '';
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_edit->settings_value!=0))
				{
					$template = $template.'{update}';
				}
				
				if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_delete->settings_value!=0))
				{
					$template = $template.'{delete}';
				}
				?>
                <?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'category-grid',
					'dataProvider'=>$model->search(),
					'filter'=>$model,
					'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css','header'=>''),
					'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
					'columns'=>array(
					'cat_name',
					array(
						'class'=>'CButtonColumn',
						'header'=>Yii::t('app','Action'),
						'template' => $template,
						'headerHtmlOptions'=>array('style'=>'font-size:12px; font-weight:bold;'),
						'visible'=>($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and ($is_edit->settings_value!=0 or $is_delete->settings_value!=0)),
						),
					),
                )); ?>
			</div>
		</td>
	</tr>
</table>    

