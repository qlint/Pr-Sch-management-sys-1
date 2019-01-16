<!--tags input-->
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/css/tagsinput/jquery.tagsinput.css" />
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/tagsinput/jquery.tagsinput.min.js"></script>
<style>


</style>
<div  class="cont_right formWrapper">
    <h1><?php echo Yii::t('app','Send SMS');?></h1> 
    <div class="formCon">
    <div class="formConInner">
    <div class="form left" style=" padding-left:20px">
    
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'sms-form',
        'enableAjaxValidation'=>false,
    )); 
	echo CHtml::hiddenField('sms', time());
	?>
    <table cellpadding="0">
    	<tr>
        	<td><h4><?php echo Yii::t('app','Enter the phone number');?></h4>            
            	<input name="recipients" id="recipients" style="width:700px" />
                <div class="sent_clear" id="clear_number_box"><a href="javascript:void(0);"><?php echo Yii::t('app', 'Clear');?></a></div>
                <div class="sent_file_bg">
                
                <ul>
               		<li>
               		 <?php echo Yii::t('app','Upload Contact');?>
               		     <span class="sub_text">.csv / .xls <?php echo Yii::t('app', 'file');?></span>
           		     
               		</li>
                 	<li><a href="javascript:void(0);" id="browse_from_file"><span><?php echo Yii::t('app','File');?></span></a></li>
                </ul>
                <div class="clear"></div>
                <div id="browse_resp" class="upload_con"></div>
                </div>
                
                
                 <div class="sent_file_bg" style=" margin-left:17px;">
                
                <ul>
               		<li><?php echo Yii::t('app','Add Contacts');?>
                    	<span class="sub_text"><?php echo Yii::t('app','Add your contacts');?></span>
                    </li>
                    
                 	<li><a href="javascript:void(0);" class="browse_assets" data-target="#add_contacts" id="browse_from_contacts"><span><?php echo Yii::t('app','Contacts');?></span></a></li>
                </ul>
                <div class="clear"></div>
                </div>
                <div class="clear"></div>
                <div id="add_contacts" class="assets_contents" style="display:none;"></div>
                
                <div class="sent_file_bg">
                
                <ul>
               		<li><?php echo Yii::t('app', 'Select Group');?>
                    	<span class="sub_text"><?php echo Yii::t('app', 'Select your group');?></span>
                    </li>
                 	<li><a href="javascript:void(0);" class="browse_assets" data-target="#add_groups" id="browse_from_groups"><span><?php echo Yii::t('app','Group');?></span></a></li>
                </ul>
                <div class="clear"></div>
                </div>
              
                
                <div class="sent_file_bg" style=" margin-left:17px;">
                
                <ul>
               		<li><?php echo Yii::t('app','Use Templates');?>
                    	<span class="sub_text"><?php echo Yii::t('app','Sms templates');?></span>
                    </li>
                 	<li><a href="javascript:void(0);" class="browse_assets" data-target="#add_templates" id="select_templates"><span><?php echo Yii::t('app','Templates');?></span></a></li>
                </ul>
                <div class="clear"></div>
                </div>
                <div class="clear"></div>
                <div id="add_templates" style="display:none; padding:15px 20px 6px;" class="sent_table_temp assets_contents"></div>
                <div id="add_groups" class="assets_contents" style="display:none;"></div>
                <div></div>
            </td>
        </tr>
        <tr>
        	<td>
            	
            	
            </td>
        </tr>
        <tr>
        	<td>
            	<span style="font-size:12px; font-weight:bold;">
            	<?php echo Yii::t('app','Hi');?> 
            </span></td>
        </tr>
        <tr>
        	<td>
            	<textarea placeholder="<?php echo Yii::t('app','Message here...');?>" name="message" id="message" style="width:506px !important; height:120px;"></textarea>
                 <div class="sent_clear" id="clear_message_box"><a href="javascript:void(0);"><?php echo Yii::t('app','Clear');?></a></div>
            </td>
        </tr>
        <tr>
        	<td>
            	<span class="msg" id="sms_msg"></span>                
            </td>
        </tr>
        <tr>
        	<td>
            	<input class="formbut" type="button" name="" id="sendsms" value="<?php echo Yii::t('app','send sms');?>" />
            </td>
        </tr>
        
    </table>
        
    <?php $this->endWidget(); ?>
    
    
    	
    </div>
    </div>
    </div>
    
    <div class="clear"></div>
</div>
<script>
//tags input
$('#recipients').tagsInput({
	defaultText:'<?php echo Yii::t('app', 'Add numbers');?>',
});

$('#sendsms').click(function(e) {
	var numbers = $('#recipients').val(),
		message	= $('#message').val();
		
	if(numbers==""){
		set_sms_message('error', '<?php echo Yii::t('app', 'Enter numbers to send SMS');?>');
		$('#recipients_tag').focus();
		return false;
	}
	else if(message==""){
		set_sms_message('error', '<?php echo Yii::t('app', 'Enter message to send SMS');?>');
		$('#message').focus();
		return false;
	}
	
    $.ajax({
		url:'',
		type:'POST',
		dataType:"json",
		data:$('form#sms-form').serialize(),
		beforeSend: function(){
			set_sms_message('ok', '<?php echo Yii::t('app', 'Sending...');?>', false);
		},
		success: function(response){
			if(response.status=="success"){
				set_sms_message('ok', response.message);				
				$('#recipients_tag, #recipients, #message').val('');
				$('#recipients_tagsinput span.tag').remove();
				set_sms_message('ok', '<?php echo Yii::t('app', 'Message sent');?>', false);
			}
			else{
				set_sms_message('error', response.message);
			}
		},
	});
});

var sms_message_timeout;
function set_sms_message(type, msg, hideafter){
	if(typeof hideafter=='undefined')	hideafter=true;
	
	if(type=="error")
		$('#sms_msg').removeClass('ok').addClass('error');
	else if(type=="ok")
		$('#sms_msg').removeClass('error').addClass('ok');
	
	$('#sms_msg').text(msg).css({opacity:1});
	
	if(hideafter){
		if(sms_message_timeout)
			window.clearTimeout(sms_message_timeout);
			
		sms_message_timeout	= window.setTimeout(function(){
			$('#sms_msg').animate({
				opacity:0,
			},
			1000,
			function(){
				$( this ).removeClass('ok').removeClass('error').text('');
			});
		}, 5000);
	}
}

$('#browse_from_contacts').click(function(e) {
	if($('#add_contacts').is(':visible')){
		$('#add_contacts').slideUp(function(){
			$(this).html('');
		});
	}
	else{
		$.ajax({
			url:'<?php echo Yii::app()->createUrl('/sms/contacts/search');?>',
			cache:false,
			beforeSend: function(){
				var img	= $('<img />');
				img.attr({
					src:'<?php echo Yii::app()->request->baseUrl;?>/images/loadinfo.gif',
				});
				$('#add_contacts').html(img);
			},
			success: function(response){
				$('#add_contacts').html( response ).slideDown();
			}
		});
	}
});

$('#browse_from_groups').click(function(e) {
	if($('#add_groups').is(':visible')){
		$('#add_groups').slideUp(function(){
			$(this).html('');
		});
	}
	else{
		$.ajax({
			url:'<?php echo Yii::app()->createUrl('/sms/contacts/groups');?>',
			cache:false,
			beforeSend: function(){
				var img	= $('<img />');
				img.attr({
					src:'<?php echo Yii::app()->request->baseUrl;?>/images/loadinfo.gif',
				});
				$('#add_groups').html(img);
			},
			success: function(response){
				$('#add_groups').html( response ).slideDown();
			}
		});
	}
});

$('#select_templates').click(function(e) {
	if($('#add_templates').is(':visible')){
		$('#add_templates').slideUp(function(){
			$(this).html('');
		});
	}
	else{
		$.ajax({
			url:'<?php echo Yii::app()->createUrl('/sms/templates/list');?>',
			cache:false,
			beforeSend: function(){
				var img	= $('<img />');
				img.attr({
					src:'<?php echo Yii::app()->request->baseUrl;?>/images/loadinfo.gif',
				});
				$('#add_templates').html(img);
			},
			success: function(response){
				$('#add_templates').html( response ).slideDown();
			}
		});
	}
});

$('.browse_assets').click(function(e) {	
	var targets	= ["#add_contacts", "#add_groups", "#add_templates"];
	if($(this).attr('data-target')){
		var target	= $(this).attr('data-target');
		var index = targets.indexOf(target);
		targets.splice(index, 1);
	}
	
	$.each(targets, function(index, element){
		$(element).slideUp();
	});
});

$('#clear_number_box').click(function(e) {
	$('#recipients_tag, #recipients').val('');
	$('#recipients_tagsinput span.tag').remove(); 
});

$('#clear_message_box').click(function(e) {
    $('#message').val('');
});
</script>
