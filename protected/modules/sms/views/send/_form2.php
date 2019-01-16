<?php
	$criteria	= new CDbCriteria;
	$criteria->condition	= '`created_by`=:created_by';
	$criteria->params		= array(':created_by'=>Yii::app()->user->id);
	$criteria->order		= '`created_at` DESC, `id` DESC';
	$smstemplates	= SmsTemplates::model()->findAll($criteria);
?>
<!--tags input-->
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/css/tagsinput/jquery.tagsinput.css" />
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/tagsinput/jquery.tagsinput.min.js"></script>
<style>
.sms-block .left{
	width:400px;
	float:left;	
}
.sms-block .right{
	width:300px;
	float:right;
	height:200px;
	margin-right:10px;
}
.sms-templates .sms-template{
	background-color:#09F;
	margin-bottom:10px;
	padding:5px;
	color:#FFF;
	border-radius:6px;
	cursor:pointer;
}
.msg{
	margin:10px 0px 10px;
	display:block;
}
.msg.error{
	color:#FB021B;
}
.msg.ok{
	color:#393;
}
</style>
<div style="padding-left:20px;" class="sms-block">
    <h1><?php echo Yii::t('app','Create Sms');?></h1> 
    <div class="form left" style="">
    
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'sms-form',
        'enableAjaxValidation'=>false,
    )); 
	echo CHtml::hiddenField('sms', time());
	?>
    <table cellpadding="0">
    	<tr>
        	<td>
            	<input name="recipients" id="recipients" style="width:390px" />
                <?php echo Yii::t('app', 'Browse from');?> <a href="javascript:void(0);" id="browse_from_file"><?php echo Yii::t('app', 'file');?></a> / <a href="javascript:void(0);" id="browse_from_contacts"><?php echo Yii::t('app', 'contacts');?></a>
            </td>
        </tr>
        <tr>
        	<td>
            	<div id="browse_resp"></div>
            	<div id="add_contacts" style="display:none;"></div>
            </td>
        </tr>
        <tr>
        	<td>
            	<br />
            	<?php echo Yii::t('app', 'Hi');?> ****,
            </td>
        </tr>
        <tr>
        	<td>
            	<textarea placeholder="<?php echo Yii::t('app', 'Message here...');?>" name="message" id="message" style="width:390px !important; height:120px;"></textarea>
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
    
    </div><!-- form -->
    
    <div class="right" style="">
    	<h2><?php echo Yii::t('app','Use Templates');?></h2>
        <div class="sms-templates">
        	<?php
            if(count($smstemplates)>0){
				foreach($smstemplates as $smstemplate){
				?>
                <div class="sms-template"><?php echo /*str_replace(array("\n", "\r" , "\s"), array("<br/>", "", "&nbsp;"), */$smstemplate->template/*)*/;?></div>
                <?php
				}
			}
			else{
			?>
            <div class="sms-template"><?php echo Yii::t('app', 'No templates found, create a template').' '.CHtml::link(Yii::t('app', 'now'), array('/mailbox/smstemplates/create'));?></div>
			<?php
			}
			?>
        </div>
    </div>
    
    <div class="clear"></div>
</div>
<script>
//tags input
$('#recipients').tagsInput({
	defaultText:'<?php echo Yii::t('app', 'Add numbers');?>',
});

$('.sms-template').click(function(){
	var template	= $(this).text();
	$('#message').val(template);
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
			set_sms_message('ok', '<?php echo Yii::t('app', 'Sending...');?>');
		},
		success: function(response){
			if(response.status=="success"){
				set_sms_message('ok', response.message);				
				$('#recipients_tag, #recipients, #message').val('');
				$('#recipients_tagsinput span.tag').remove();
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
		$('#add_contacts').hide(function(){
			$(this).html('');
		});
	}
	else{
		$.ajax({
			url:'<?php echo Yii::app()->createUrl('/mailbox/contacts/search');?>',
			cache:false,
			beforeSend: function(){
				var img	= $('<img />');
				img.attr({
					src:'<?php echo Yii::app()->request->baseUrl;?>/images/loadinfo.gif',
				});
				$('#add_contacts').html(img).show();
			},
			success: function(response){
				$('#add_contacts').html( response );
			}
		});
	}
});
</script>
