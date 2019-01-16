<style>
.fee-particular-head{
	padding:10px 15px;  background-color:#c5ced9;
	color:#405875;
	font-weight:bold;
	position:relative;
}
.feeParticular{
	border:1px solid #c5ced9; padding:15px;background-color:#fff; margin-bottom:20px;
	
}


.applicable-to{
	border:1px solid #c5ced9; padding:10px; margin-bottom:10px; background-color:#F9F9FD; margin-bottom:15px; margin-top:15px;
}
.error-brd{
	border-color:#F30 !important;
}
</style>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'fee-categories-form',
	'enableAjaxValidation'=>false,
)); ?>
<div class="formCon">
	<div class="formConInner" style="width:95%;">
		<h3><?php echo Yii::t('app','Fee Category');?></h3>   
		<table width="100%">
        	<tr>
            	<td width="10%"><?php echo $form->labelEx($category,'name'); ?></td>
            </tr>
            <tr>
                <td>
					<?php echo $form->textField($category,'name',array('class'=>'FeeCategories_name', 'style'=>'width:100% !important;')); ?>
                </td>
            </tr>
            <tr>
            	<td>&nbsp;</td>
            </tr>
            <tr>
            	<td><?php echo $form->labelEx($category,'description'); ?></td>
            </tr>
            <tr>
                <td>
					<?php echo $form->textArea($category,'description',array('class'=>'FeeCategories_description', 'style'=>'width:100% !important; height:120px;')); ?>
                </td>
            </tr>
        </table>
        <div class="clear"></div>
        <br />
        <br />
        <h3 style="width:100%;"><?php echo Yii::t('app','Fee Particulars');?></h3>
		
        
        
        <!-- Fee particulars here -->
        <div id="fee-particulars" >
        	<?php $this->renderPartial('_particular',array('particular'=>$particular, 'ptrow'=>0));?>
        </div>
        <!-- Fee particulars here -->
        
        <div>
            <table width="100%">
                <tr>
                    <td colspan="3">
                        <a href="javascript:void(0);" title="<?php echo Yii::t("app", "Click to add another particular");?>" id="add-fee-particular" style="font-size:14px;"><strong><?php echo Yii::t("app", "+ Add particular");?></strong></a> <strong>/</strong> <a title="<?php echo Yii::t("app", "Press `Ctrl + Enter` to add another particular");?>"><strong>{ <?php echo Yii::t("app", "Press Ctrl + Enter");?> }</strong></a>
                    </td>
                </tr>
            </table>
       	</div>
		
	</div>
</div>
<div class="row buttons">
	<?php echo CHtml::submitButton(Yii::t("app", 'Setup Subscriptions').' >>', array('class'=>'formbut')); ?>
</div>
<?php $this->endWidget(); ?>

<script>
var add_particular	= function(){
	var ptrow	= parseInt($("#fee-particulars .fee-particular").last().attr("data-row")) + 1;
	$.ajax({
		url:'<?php echo Yii::app()->createUrl("/fees/create/addParticular");?>',
		type:'GET',
		data:{ptrow:ptrow},
		dataType:"json",
		success: function(response){
			if(response.status=="success"){
				var data	= $(response.data);
				$("#fee-particulars").append(data);
				
				//scroll to new particualr
				$('html,body').animate({
					scrollTop: data.offset().top
				}, 'fast');
				
				setup_actions();
			}
			else{
				alert("<?php echo Yii::t("app", "Can't add particular");?>");
			}
		}
	});
};

var remove_particular	= function(that){
	var particular	= "";
	if($(that).closest('.fee-particular').find('input.particular-name[type="text"]').length>0 && $(that).closest('.fee-particular').find('input.particular-name[type="text"]').val()!=""){
		particular	= "`" + $(that).closest('.fee-particular').find('input.particular-name[type="text"]').val() + "`";
	}
	
	if($("#fee-particulars .fee-particular").length>1){
		if(confirm("<?php echo Yii::t("app", "Are you sure remove this particular");?> " + particular + " ?")){
			$(that).closest(".fee-particular").remove();
		}
	}
	else{
		alert("<?php echo Yii::t("app", "Can't remove. You need to create atleast one particular !!");?>");
	}	
};

var add_access	= function(that){
	var ptrow	= parseInt($(that).attr("data-row"));
	var acrow	= parseInt($("#particular-accesses-" + ptrow + " .particular-access").last().attr("data-row")) + 1;
	$.ajax({
		url:'<?php echo Yii::app()->createUrl("/fees/create/addParticularAccess");?>',
		type:'GET',
		data:{ptrow:ptrow, acrow:acrow},
		dataType:"json",
		success: function(response){
			if(response.status=="success"){
				var data	= $(response.data);
				$("#particular-accesses-" + ptrow).append(data);
				
				//scroll to new particualr
				$('html,body').animate({
					scrollTop: data.offset().top
				}, 'fast');
				
				setup_actions();
			}
			else{
				alert("<?php echo Yii::t("app", "Can't add access");?>");
			}
		}
	});
};

var remove_access	= function(that){
	var row	= parseInt($(that).attr("data-row"));
	if($("#particular-accesses-" + row + " .particular-access").length>1){
		if(confirm("<?php echo Yii::t("app", "Are you sure remove access this group ?");?>")){
			$(that).closest(".particular-access").remove();
		}
	}
	else{
		alert("<?php echo Yii::t("app", "Can't remove. You need to give access to atleast one group for this particular !!");?>");
	}	
};

var change_access_type	= function(that){
	var type	= $(that).val();
	var ptrow	= parseInt($(that).closest(".fee-particular").attr("data-row"));
	var acrow	= parseInt($(that).closest(".particular-access").attr("data-row"));
	$.ajax({
		url:'<?php echo Yii::app()->createUrl("/fees/create/addParticularAccessType");?>',
		type:'GET',
		data:{type:type, ptrow:ptrow, acrow:acrow},
		dataType:"json",
		success: function(response){
			if(response.status=="success"){
				var data	= $(response.data);
				$(that).closest(".particular-access").find('.access-datas').html(data);
				
				if(type==1){
					//load bacthes
					load_batches();
				}
			}
			else{
				alert("<?php echo Yii::t("app", "Can't add access");?>");
			}
		}
	});
};

var setup_actions	= function(){
	//remove link for all particular
	$(".remove-particular").unbind('click').click(function(e) {
		var that	= this;
		remove_particular(that);
    });
	
	//add access
	$(".add-particular-access").unbind('click').click(function(e) {
		var that	= this;
		add_access(that);
    });
	
	//remove access
	$(".remove-access").unbind('click').click(function(e) {
		var that	= this;
		remove_access(that);        
    });
	
	//change access type
	$(".particular-access-type").unbind('change').change(function(e) {
		var that	= this;
		change_access_type(that);
    });
	
	//load bacthes
	load_batches();
};

var load_batches	= function(){
	$(".access-course").unbind('change').change(function(e) {
		var that	= this;
		if($(that).val!=""){
			var course	= $(that).val();
			$.ajax({
				url:'<?php echo Yii::app()->createUrl("/fees/create/getBatches");?>',
				type:'GET',
				data:{course:course},
				success: function(response){
					$(that).closest(".particular-access").find('.access-batch').html(response);
				}
			});
		}
	});
};

$('#add-fee-particular').click(function(e) {
	add_particular();
});

$(document).keypress(function(e) {	
	if(e.ctrlKey){
		if(e.keyCode==13){
			add_particular();
			return false;
		}
	}
});

$("form#fee-categories-form").submit(function(e) {
	var that	= this;
	var data	= $(that).serialize();
	data	+= "&yid=" + $("#system_yid").val();
	$.ajax({
		url:'<?php echo Yii::app()->createUrl("/fees/create");?>',
		type:'POST',
		data:data,
		dataType:"json",
		success: function(response){
			$(that).find("input, select").attr('title', '');
			$(that).find("*").removeClass("error-brd");
			if(response.status=="success"){
				window.location.href	= response.redirect;
			}
			else if(response.hasOwnProperty("errors")){
				var errors	= response.errors;
				$.each(errors, function(attribute, earray){
					$.each(earray, function(index, error){
						$('#' + attribute).attr('title', error).addClass("error-brd");
					});										
				});				
			}
			else if(response.hasOwnProperty("message")){
				alert(response.message);
			}
			else{
				alert("<?php echo Yii::t("app", "Some problem found while saving data !!");?>");
			}
		}
	});
	
    return false;
});

setup_actions();
</script>