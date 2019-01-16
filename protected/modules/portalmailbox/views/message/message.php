<style>
.mailbox-menu-newmsg {
    background:#428bca !important;
    border: 1px solid #357ebd !important;
    border-radius: 3px !important;
    box-shadow: 0 0 0 0 #ffffff inset !important;
    color: #ffffff !important;
    display: inline-block;
    font-family: arial;
    font-size: 12px;
    font-weight: bold;
    padding: 6px 14px !important;
    position: absolute;
    right: 20px;
    text-decoration: none;
    top: 13px;
}
.mailbox-message-text p{
	text-align:justify;
}
</style>


<?php
//disable jquery autoload
Yii::app()->clientScript->scriptMap=array(
	'jquery.js'=>false,
);
$this->breadcrumbs=array(
	Yii::t('app',ucfirst($this->module->id))=>array('message/inbox'),
	Yii::t('app','Message'),
);
?>
 <?php $this->renderPartial('/default/left_side');?>
 
 <div class="pageheader">
      <h2><i class="fa fa-envelope-o"></i><?php echo Yii::t('app','Mailbox');?><span><?php echo Yii::t('app','Check your mails here.');?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
          <li class="active"><?php echo Yii::t('app','Mailbox'); ?></li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
    	<div class="people-item" style=" min-height:550px;">
        <h5 class="subtitle mb5"><?php echo Yii::t('app','Reply Mail'); ?></h5>
        <div class="table-responsive mail_toparea">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    
    <td valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top" width="75%">
        <div class="cont_right formWrapper">
      <div id="parent_rightSect">
      <div class="parentright_innercon">
      
<?php

$this->renderPartial('_menu'); 

$subject = ($conv->subject)? $conv->subject : $this->module->defaultSubject;

if(strlen($subject) > 100)
{
	$subject = substr($subject,0,100);
}

?>
<div >	

		<div >
<?php
    foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
    }
?>
<div class="mailbox-message-subject  mailbox-ellipsis"><?php echo $subject; ?></div>

<br />
<?php
$first_message=1;
foreach($conv->messages as $msg): 
	$sender = $this->module->getUserName($msg->sender_id);
	if(!$sender)
		$sender = $this->module->deletedUser;
	?>
	<div class="msgfeed">
	<div class="mailbox-message-header">
		<div class="message-sender">
<?php	echo ($msg->sender_id == Yii::app()->user->id)? Yii::t('app','You') : ucfirst($sender);
	echo ($first_message)? ' '.Yii::t('app','said') : ' '.Yii::t('app','replied'); ?></div>
		<div class="message-date">
			<?php  
				$date_time		=	Configurations::model()->convertDateTime($msg->created);
				echo $date_time;
			?>
        </div>
		<br />
	</div>
	<div class="mailbox-message-text" style="text-align:justify;"><?php echo $msg->text; ?></div>
    </div>
	<br />
<?php $first_message=0;
endforeach; 

if($this->module->authManager)
	$authReply = Yii::app()->user->checkAccess("Mailbox.Message.Reply");
else
	$authReply = $this->module->sendMsgs;

if($authReply)
{
	//echo $this->getAction()->getId();
	
	if($this->getAction()->getId()!='trash'){	
$form=$this->beginWidget('CActiveForm', array(
    'action'=>$this->createUrl('message/reply',array('id'=>$_GET['id'])),
    'id'=>'message-reply-form',
    'enableAjaxValidation'=>false,
)); ?>
	
	<?php /* echo $form->errorSummary(array($reply,$conv));*/ ?>
	<?php echo $form->error($reply,'text'); ?>
		
			<textarea name="text" cols="50" rows="7" placeholder="<?php echo Yii::t('app','Reply here'); ?>...." class="form-control"></textarea>
		
        <div class="clear"></div>
	
	

<input type="submit" value="<?php echo Yii::t('app','Send Reply'); ?>"  class="btn btn-danger"/>

<?php $this->endWidget(); }
}
?>


</div>
</div>
</div>
</div>
</div>
<div class="clear"></div>
</td>
        
      </tr>
    </table>
    </td>
  </tr>
</table>
</div>

<div class="clear"></div>
</div>

</div>