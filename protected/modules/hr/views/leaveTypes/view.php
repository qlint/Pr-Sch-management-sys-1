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
.table-border-new td {
    border: 1px solid #bdc3cb;
    padding: 7px 5px !important;
    border-collapse: collapse;

}


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