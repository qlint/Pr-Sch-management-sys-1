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
$material = PurchaseMaterialRequistion::model()->findByAttributes(array('id'=>$_REQUEST['id']));
?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table-border-new">
	<tr>
        <td width="200px;" align="center"><strong><?php echo Yii::t('app','Department'); ?></strong></td>
        <td align="center"><?php $department = EmployeeDepartments::model()->findByAttributes(array('id'=>$material->department_id));
									echo $department->name;  ?></td>
    </tr>
    <tr>
        <td width="200px;" align="center"><strong><?php echo Yii::t('app','Item Name'); ?></strong></td>
        <td align="center"><?php $item = PurchaseStock::model()->findByAttributes(array('id'=>$material->material_id));
									echo $item->name;  ?></td>
    </tr>
     <tr>
        <td width="200px;" align="center"><strong><?php echo Yii::t('app','Item Code'); ?></strong></td>
        <td align="center"><?php echo $item->item_code; ?></td>
    </tr>
     <tr>
        <td width="200px;" align="center"><strong><?php echo Yii::t('app','Quantity'); ?></strong></td>
        <td align="center"><?php echo $material->quantity; ?></td>
    </tr>
    <tr>
        <td width="200px;" align="center"><strong><?php echo Yii::t('app','Hod status'); ?></strong></td>
        <td align="center"><?php if($material->status_hod == 0){ echo 'Pending'; }
								 if($material->status_hod == 1){ echo 'Approved';}
								 if($material->status_hod == 2){ echo 'Cancelled';}?></td>
    </tr>
     <tr>
        <td width="200px;" align="center"><strong><?php echo Yii::t('app','PM status'); ?></strong></td>
        <td align="center"><?php if($material->status_pm == 0){ echo 'Pending'; }
								 if($material->status_pm == 1){ echo 'Approved';}
								 if($material->status_pm == 2){ echo 'Cancelled';}
								 if($material->status_pm == 3){ echo '-';}?></td>
    </tr>
    <tr>
        <td width="200px;" align="center"><strong><?php echo Yii::t('app','Is Issued'); ?></strong></td>
        <td align="center"><?php if($material->is_issued == 0){ echo 'No'; }
								 if($material->is_issued == 1){ echo 'Yes';}
								?></td>
    </tr>
    
   
   
</table>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>