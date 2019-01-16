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

.ui-dialog .ui-dialog-titlebar {
    padding: 2px 0px 2px 10px !important;
}

.student-popup-table{
	border-collapse:collapse;
	 margin-top:25px;	
}
.student-popup-table th{
	border: 1px solid#ccc;
	font-size: 13px;
	text-transform: uppercase;
	color: #7d7d2c;
	background-color: #fbfbee;
}
.student-popup-table td{
	border: 1px solid#ccc;
	font-size: 12px;
	text-transform: uppercase;
	#9a9a9a
	padding:8px;
}

.student-popup-table .rsn-table{
	border: 1px solid#ccc;
	font-size: 12px;
	text-transform: uppercase;
	color: #717171;
	padding:15px;
}

</style>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui-style.css" />

<?php
$elective_group 	= ElectiveGroups::model()->findByAttributes(array('id'=>$_REQUEST['id']));
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'jobDialog_view',
	'options'=>array(
		'title'=>Yii::t('app','Elective Group').' : '.ucfirst($elective_group->name),
		'autoOpen'=>true,
		'modal'=>'true',
		'width'=>'auto',
		'height'=>'auto',
		'resizable'=>false,
				
   ),
));	


$time_tables	= TimetableEntries::model()->findAllByAttributes(array('class_timing_id'=>$_REQUEST['timing_id'], 'weekday_id'=>$_REQUEST['weekday_id'], 'batch_id'=>$_REQUEST['batch_id'], 'is_elective'=>2));

if($time_tables!=NULL){
?>
    <div class="view-dialog-popup">
        <table width="400" border="0" cellpadding="0" cellspacing="0" class="student-popup-table">
            <thead>
                <tr>
                    <th width="100px;" align="center"><?php echo Yii::t('app','Elective Name'); ?></th>
                    <th align="center"><?php echo Yii::t('app','Teacher'); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($time_tables as $time_table){
				$elective	= Electives::model()->findByAttributes(array('id'=>$time_table->subject_id));
				$employee	= Employees::model()->findByAttributes(array('id'=>$time_table->employee_id)); ?>
            	<tr>
                    <td width="100px;" align="center" class="rsn-table"><?php echo $elective->name; ?></td>
                     <td align="center"><?php echo Employees::model()->getTeachername($employee->id); ?></td>
                </tr>
            <?php } ?>
            </tbody>
            
        </table>    
    </div>
<?php
}
else{
	echo Yii::t("app","Not found");
}
 $this->endWidget('zii.widgets.jui.CJuiDialog');?>
</div>

