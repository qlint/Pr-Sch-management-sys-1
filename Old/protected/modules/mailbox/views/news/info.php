<?php
$this->breadcrumbs=array(
	Yii::t('app','Site News')=>array('news/'),
	Yii::t('app','News Update'),
);

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="247" valign="top" id="port-left">
    
     <?php $this->renderPartial('/default/left_side');?>
    
    </td>
    <td valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top" width="75%">
<div class="cont_right formWrapper" style="padding:0px; width:753px;">
      <div id="parent_rightSect">
      <div class="parentright_innercon">
     <div class="mail_head"><?php echo Yii::t('app','Site News'); ?><span><?php echo Yii::t('app','Latest news listed here'); ?></span></div>


<?php
    foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
    }
?>

<div class="mailbox-message-subject">
<?php echo ($conv->subject)? $conv->subject : $this->module->defaultSubject; ?></div>
<br />
<?php
$first_message=1;
foreach($conv->messages as $msg): 
	$sender = $this->module->getUserName($msg->sender_id);
	if(!$sender)
		$sender = $this->module->deletedUser;
		?>
<div class="mailbox-message-header">
		<div class="message-sender">
<?php	echo Yii::t('app','Author:'). ucfirst($sender); ?></div>

<?php

$settings=UserSettings::model()->findByAttributes(array('user_id'=>1));
		
		if($settings!=NULL)
		{	
			$timezone = Timezone::model()->findByAttributes(array('id'=>$settings->timezone));
			date_default_timezone_set($timezone->timezone);
		}
?>
		<div class="message-date"><?php echo Yii::t('app','Last Updated:');?> <?php echo date("Y-m-d H:i a",$msg->created); ?></div>
		<br />
	</div>

	<div class="mailbox-message-text"><?php echo $msg->text; ?></div>
	
	
	
	
	
	<br />

	
<?php $first_message=0;
endforeach; 

if($this->module->authManager)
	$authReply = Yii::app()->user->checkAccess("Mailbox.Message.Reply");
else
	$authReply = $this->module->sendMsgs;
?>
</div>
</div>
</div>
</td>
        
      </tr>
    </table>
    </td>
  </tr>
</table>