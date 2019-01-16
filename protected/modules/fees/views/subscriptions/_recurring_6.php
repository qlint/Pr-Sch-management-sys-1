<?php
$settings=UserSettings::model()->findByAttributes(array('user_id'=>Yii::app()->user->id));
if($settings!=NULL){
	$dateformat	= $settings->dateformat;
}
else
	$dateformat = 'dd-mm-yy';

$timeid	= time();
?>
<div id="custom_due_dates" >
<br />
    <div>
        <td><label><?php echo Yii::t('app','Due Dates'); ?> <span class="required">*</span></label></td>
   </div>
    <div class="duedates">
    	<div class="cust_duedate">    	
    		<?php $this->renderPartial('_due_date', array('timeid'=>$timeid));?>
        </div>
		
   </div>
   <div class="clear"></div>
</div>
<div style="padding-top:10px;"><a href="javascript:void(0);" id="add_custom_due_date"><?php echo Yii::t("app", "+ Add custom due date");?></a></div>
<script>
	var timeid	= <?php echo $timeid;?>;
	$("#add_custom_due_date").unbind('click').click(function(e) {
		timeid++;
		$.ajax({
			url:'<?php echo Yii::app()->createUrl("/fees/subscriptions/addDueDate");?>',
			type:'GET',
			data:{timeid:timeid},
			dataType:"json",
			success: function(response){
				if(response.status=="success"){
					var data	= $(response.data);
					var row		= $("<div class='cust_duedate'/>");					
					$("#custom_due_dates .duedates").append(row);
					row.html(data);
				}
				else{
					alert("<?php echo Yii::t("app", "Some error found !!");?>");
				}
			}
		});  
    });
</script>