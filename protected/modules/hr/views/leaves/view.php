<style>
#jobDialog_comment{
	height:auto !important;
}
.seen-by{    margin: 0px 0px 17px 4px; padding:0px;}
.seen-by li{ list-style:none; display:block; background:url(images/bread-arrow.png) no-repeat left 3px; color:#868686;    padding: 0px 10px;}
.name-icon1{ background:url(images/bread-arrow.png) no-repeat left}
.seen-h4{     border-bottom: 1px solid#ececec;margin-bottom: 5px;}
.seen-h4 h4{ font-size:12px; font-family:Tahoma, Geneva, sans-serif; font-weight:600; color:#444; margin: 0px 0px 5px 4px;}
.ui-dialog .ui-dialog-title {
    float: left;
    color: #585858;
	font-weight: 300;
    background:url(images/info-icon.png) no-repeat left;
	    padding: 3px 31px;
}
.ui-dialog-titlebar {
    background: #c0def3!important;
    color: #000 !important;
}
.ui-dialog .ui-dialog-titlebar {
    padding: 2px 0px 2px 10px !important;
}
.ui-dialog{ width:500px !important; height:400px !important;}
.table-border-new td{  border:1px solid #C0DEF3; padding:7px 5px !important;border-collapse: collapse;}
.table-border-new{ margin-top:15px;}
.ui-dialog-content{ height:300px !important;}
</style>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui-style.css" />

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'jobDialog_view',
	'options'=>array(
		'title'=>Yii::t('app','View'),
		'autoOpen'=>true,
		'modal'=>'true',
		'width'=>'323',
		'height'=>'auto',
		'resizable'=>false,
   ),
));	
$leave 	= LeaveTypes::model()->findByAttributes(array('id'=>$_REQUEST['id']));
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table-border-new">
	<tr>
        <td width="200px;" align="center"><strong><?php echo Yii::t('app','Type'); ?></strong></td>
        <td align="center"><?php echo ucfirst($leave->type); ?></td>
    </tr>
    <tr>
        <td width="200px;" align="center"><strong><?php echo Yii::t('app','Description'); ?></strong></td>
        <td align="center"><?php echo $leave->description; ?></td>
    </tr>
    <tr>
        <td width="200px;" align="center"><strong><?php echo Yii::t('app','Category'); ?></strong></td>
        <td align="center"><?php if($leave->category == 1){echo Yii::t('app','Per Quarter');}
								 if($leave->category == 2){echo Yii::t('app','Per Year');}
								 if($leave->category == 3){echo Yii::t('app','Whole Carrer');  }?></td>
    </tr>
     <tr>
        <td width="200px;" align="center"><strong><?php echo Yii::t('app','Applicable For'); ?></strong></td>
        <td align="center"><?php if($leave->gender == 0){echo Yii::t('app','All');}
								 if($leave->gender == 1){echo Yii::t('app','Male');}
								 if($leave->gender == 2){echo Yii::t('app','Female');  }?></td>
    </tr>
     <tr>
        <td width="200px;" align="center"><strong><?php echo Yii::t('app','Count'); ?></strong></td>
        <td align="center"><?php echo $leave->count; ?></td>
    </tr>
   
</table>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>