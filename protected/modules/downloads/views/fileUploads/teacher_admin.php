
<style>
.mailbox-menu-newup{
	-moz-box-shadow:inset 0px 0px 0px 0px #ffffff !important;
	-webkit-box-shadow:inset 0px 0px 0px 0px #ffffff !important ;
	box-shadow:inset 0px 0px 0px 0px #ffffff !important;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #1bb4fa), color-stop(1, #0994f0) ) !important;
	background:-moz-linear-gradient( center top, #1bb4fa 5%, #0994f0 100% ) !important;
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#1bb4fa', endColorstr='#0994f0') !important;
	background-color:#1bb4fa !important;
	-moz-border-radius:3px !important;
	-webkit-border-radius:3px !important;
	border-radius:3px !important;
	border:1px solid #0c93d1 !important;
	display:inline-block;
	color:#ffffff !important;
	font-family:arial;
	font-size:12px;
	font-weight:bold;
	padding:8px 14px !important;
	text-decoration:none;
	margin:0px 10px;
	
	/*text-shadow:1px 0px 0px #0664a3;*/
}
.mailbox-menu-newup a{color:#fff !important; text-decoration:none !important; display:block;}

.mailbox-message-subject{
	padding:10px;
}

.mailbox-menu-mangeup{
	-moz-box-shadow:inset 0px 0px 0px 0px #ffffff !important;
	-webkit-box-shadow:inset 0px 0px 0px 0px #ffffff !important ;
	box-shadow:inset 0px 0px 0px 0px #ffffff !important;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #1bb4fa), color-stop(1, #0994f0) ) !important;
	background:-moz-linear-gradient( center top, #1bb4fa 5%, #0994f0 100% ) !important;
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#1bb4fa', endColorstr='#0994f0') !important;
	background-color:#1bb4fa !important;
	-moz-border-radius:3px !important;
	-webkit-border-radius:3px !important;
	border-radius:3px !important;
	border:1px solid #0c93d1 !important;
	display:inline-block;
	color:#ffffff !important;
	font-family:arial;
	font-size:12px;
	font-weight:bold;
	padding:8px 14px !important;
	text-decoration:none;

	/*text-shadow:1px 0px 0px #0664a3;*/
}
.mailbox-menu-mangeup a{color:#fff !important; text-decoration:none !important; display:block;}


</style>
<?php $this->renderPartial('/default/left_side');?>
<div class="pageheader">
    <h2><i class="fa fa-download"></i> <?php echo Yii::t('app','Downloads').'<span>'.Yii::t('app','Downloads here').'</span>';?></h2>
      <div class="breadcrumb-wrapper">
        <?php echo '<span class="label">'.Yii::t('app','You are here:').'</span>';?>
        <ol class="breadcrumb">
          <li class="active"><?php echo Yii::t('app','Manage Uploads')?></li>
        </ol>
      </div>
</div>
<?php
$this->breadcrumbs=array(
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

<div class="contentpanel">
	<div class="panel-heading" style="position:relative;">

 <h3 class="panel-title"><?php echo Yii::t('app','Manage File Uploads');?></h3>
 </div>
 
 <div class="people-item">
 <div class="opnsl_headerBox">
    <div class="opnsl_actn_box"> </div>
        <div class="opnsl_actn_box">
            <div class="opnsl_actn_box1">
				<?php 
                 echo CHtml::link(Yii::t('app','New Upload'),array('create'),array('class'=>'btn btn-primary'));
                ?>
            </div>
            <div class="opnsl_actn_box1">    
				<?php
                echo CHtml::link(Yii::t('app','All Uploads'),array('index'),array('class'=>'btn btn-primary'));
                ?>
            </div>
        </div>
            </div>

            
            <div class="table-responsive">
    
<table width="100%" border="0" cellspacing="0" cellpadding="0" >
  <tr>
    <td  valign="top" id="port-left">
     
    
    </td>
    <td valign="top">
    


 
<?php 
	
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
		
		array(
			'class'=>'CButtonColumn',
			'header'=>Yii::t('app','Action'),
			'headerHtmlOptions'=>array('style'=>'font-size:13px; font-weight:bold;')
		),
	),
)); ?>

</td>
</tr>
</table>
</div>
</div>
</div>