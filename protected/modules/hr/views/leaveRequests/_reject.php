<style>
#jobDialog_comment{
	height:auto !important;
}
.seen-by{    margin: 0px 0px 17px 4px; padding:0px;}
.seen-by li{ list-style:none; display:block; background:url(images/bread-arrow.png) no-repeat left 3px; color:#868686;    padding: 0px 10px;}
.name-icon1{ background:url(images/bread-arrow.png) no-repeat left}
.seen-h4{     border-bottom: 1px solid#ececec;margin-bottom: 5px;}
.seen-h4 h4{ font-size:12px; font-family:Tahoma, Geneva, sans-serif; font-weight:600; color:#444; margin: 0px 0px 5px 4px;}

.ui-dialog{ width:500px !important; height:auto !important;}
.table-border-new td{ border:1px solid #C0DEF3; padding:7px 5px !important;border-collapse: collapse;}
.table-border-new{ margin-top:15px;}

</style>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui-style.css" />
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'jobDialog_outer',
	'options'=>array(
		'title'=>Yii::t('app','Reject Leave Request'),
		'autoOpen'=>true,
		'modal'=>'true',
		'width'=>'323',
		'height'=>'auto',
		'resizable'=>false,
	),
));
?>

<?php $this->renderPartial('_form', array('model'=>$model));?>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>