<?php
$this->breadcrumbs=array(
	Yii::t('app','Purchase')=>array('admin'),
	Yii::t('app','Manage Items'),
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('employee-departments-grid', {
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
                <h1><?php echo Yii::t('app','Manage Items');?></h1>
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

<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li><?php echo CHtml::link('<span>'.Yii::t('app','New item').'</span>', array('create'),array('class'=>'a_tag-btn')); ?></li>                                    
</ul>
</div> 
</div>
                <?php
                Yii::app()->clientScript->registerScript(
                'myHideEffect',
                '$(".flash-success").animate({opacity: 1.0}, 3000).fadeOut("slow");',
                CClientScript::POS_READY
                );
                ?>
                
                <?php if(Yii::app()->user->hasFlash('notification')):?>
                    <div class="flash-success" style="color:#F00; padding-left:150px; font-size:12px">
                    	<?php echo Yii::app()->user->getFlash('notification'); ?>
                    </div>
                <?php endif; ?>
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
                
                <?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'purchase-items-grid',
					'dataProvider'=>$model->search(),
					'filter'=>$model,
					'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
					'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
					'columns'=>array(
						array(
						'name'=>'name',
						'value'=>array($model,'itemName'),
						),
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