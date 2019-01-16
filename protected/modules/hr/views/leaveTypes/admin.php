<?php
$this->breadcrumbs=array(
	'Hr Leave Types'=>array('index'),
	'Manage',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('hr-leave-types-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="247" valign="top">
        	<?php $this->renderPartial('/default/leftside');?>
        </td>
        <td valign="top">
            <div class="cont_right formWrapper">
                <h1><?php echo Yii::t('app','Manage Leave Types');?></h1>
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
				
				
				?>
                <div class="edit_bttns" style="top:20px; right:20px;">
                    <ul>
                        <li><?php echo CHtml::link('<span>'.Yii::t('app','Add Leavetype').'</span>', array('create'),array('class'=>'addbttn last ')); ?></li>
                    </ul>
                </div>
                
                <?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
                <div class="search-form" style="display:none">
					<?php $this->renderPartial('_search',array(
                    'model'=>$model,
                    )); ?>
                </div><!-- search-form -->
                
                <?php 				
				
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
              <div class="hr-gridyable">  
                <?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'hr-leave-types-grid',
					'dataProvider'=>$model->search(),
					'filter'=>$model,
					'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
					'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
					'columns'=>array(
					'type',
					/*'category',*/
					'gender',
					'count',
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
                
            </div>
        </td>
    </tr>
</table>
