<?php
$this->breadcrumbs=array(
	Yii::t('app','Downloads')=>array('/downloads'),
	Yii::t('app','File Uploads')=>array('index'),
	Yii::t('app','Manage'),
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('file-uploads-grid', {
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
    
<div class="cont_right"><h1><?php echo Yii::t('app','Manage File Uploads');?></h1>

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
<?php /*?> <?php
	if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
	{ 
		echo CHtml::link(Yii::t('app','New Upload'),array('create'),array('class'=>'mailbox-menu-newup'));
	}
 	echo CHtml::link(Yii::t('app','All Uploads'),array('index'),array('class'=>'mailbox-menu-mangeup'));
 ?><?php */?>

<div class="button-bg">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>                                    
<li> <?php
	if(($year == $current_academic_yr->config_value) or ($year != $current_academic_yr->config_value and $is_create->settings_value!=0))
	{ 
		echo CHtml::link(Yii::t('app','New Upload'),array('create'),array('class'=>'mailbox-menu-newup'));
	}
 ?>	</li>
<li> <?php
 	echo CHtml::link(Yii::t('app','All Uploads'),array('index'),array('class'=>'mailbox-menu-mangeup'));
 ?></li>
                                   
</ul>
</div> 

</div> 
 <div>
  				<?php 				
				if($year != $current_academic_yr->config_value and ($is_create->settings_value==0 or $is_edit->settings_value==0 or $is_delete->settings_value==0))
				{
				?>
                exit;
					<div>
						<div class="yellow_bx" style="background-image:none;width:690px;padding-bottom:45px;">
							<div class="y_bx_head" style="width:650px;">
							<?php 
								echo Yii::t('app','You are not viewing the current active year. ');
								if($is_create->settings_value==0 and $is_edit->settings_value!=0 and $is_delete->settings_value!=0)
								{ 
									echo Yii::t('app','To upload a new file, enable Create option in Previous Academic Year Settings.');
								}
								elseif($is_create->settings_value!=0 and $is_edit->settings_value==0 and $is_delete->settings_value!=0)
								{
									echo Yii::t('app','To edit the file, enable Edit option in Previous Academic Year Settings.');
								}
								elseif($is_create->settings_value!=0 and $is_edit->settings_value!=0 and $is_delete->settings_value==0)
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
 
<?php 
	
	//var_dump($model->attributes);exit;
	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'file-uploads-grid',
	'dataProvider'=>$model->searchs(),
	'pager'=>array('cssFile'=>Yii::app()->baseUrl.'/css/formstyle.css'),
    'cssFile' => Yii::app()->baseUrl . '/css/formstyle.css',
	'filter'=>$model,
	'columns'=>array(
		'title',		
		array('header'=>Yii::t('app','Place Holder'),
                    'value'=>array($model,'getplaceholder'),
                    'name'=> 'placeholder',
                ),
		array('header'=>Yii::t('app','Course'),
                    'value'=>array($model,'getcourse'),
                    'name'=> 'course',
					'filter' => false,
					'htmlOptions' => array('style'=>'width:300px;')
                ),
		array('header'=>Yii::app()->getModule('students')->fieldLabel("Students", "batch_id"),
                    'value'=>array($model,'getbatch'),
                    'name'=> 'batch',
					'filter' => false,
					'htmlOptions' => array('style'=>'width:300px;')
                ),
		/*array('header'=>'Year',
                    'value'=>array($model,'getyear'),
                    'name'=> 'batch',
					'filter' => false,
					'htmlOptions' => array('style'=>'width:300px;')
                ),*/
		array(
			'class'=>'CButtonColumn',
			'header'=>Yii::t('app','Action'),
			'template' => $template,
			'headerHtmlOptions'=>array('style'=>'font-size:12px; font-weight:bold;'),
			
		),
	),
)); ?>

</div>
</div>
</td>
</tr>
</table>