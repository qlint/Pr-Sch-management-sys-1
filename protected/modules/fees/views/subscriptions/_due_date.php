<?php
$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL){
	$dateformat	= $settings->dateformat;
}
else
	$dateformat = 'dd-mm-yy';
$subscription	= new FeeSubscriptions;

$this->widget('zii.widgets.jui.CJuiDatePicker', array(
	'model'=>$subscription,
	'attribute'=>'due_date['.$timeid.']',
	// additional javascript options for the date picker plugin
	'options'=>array(
		'showAnim'=>'fold',
		'dateFormat'=>$dateformat,
		'changeMonth'=> true,
		'changeYear'=>true,
		'yearRange'=>'1900:'.(date('Y')+5)
	),
	'htmlOptions'=>array(
		'class'=>'custom-date',
		'data-timeid'=>$timeid,
		'readonly'=>true
	),
));
?>
<a href="javascript:void(0);" class="fees-trash" id="remove-due-date<?php echo $timeid;?>"><?php echo Yii::t("app", "");?></a>
<script>
	$("#remove-due-date<?php echo $timeid;?>").click(function(e) {
		var c	= $("#custom_due_dates").find("input.custom-date");
		if(c.length>1){
			var that	= this;
			if(confirm("<?php echo Yii::t("app", "Are you sure remove this due date ?");?>")){
				$(that).closest(".cust_duedate").remove();
			}
		}
		else{
			alert("<?php echo Yii::t("app", "Can't remove. Atleast one date required !!");?>");
		}
    });
</script>