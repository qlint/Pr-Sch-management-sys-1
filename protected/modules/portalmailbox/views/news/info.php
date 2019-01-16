<?php
$this->breadcrumbs=array(
	Yii::t('app','Site News')=>array('news/'),
	Yii::t('app','News Update'),
);

?>

 <?php $this->renderPartial('/default/left_side');?>
 
 <div class="pageheader">
      <h2><i class="fa fa-newspaper-o"></i> <?php echo Yii::t('app','Site News'); ?>  <span><?php echo Yii::t('app','Latest news listed here'); ?></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label"><?php echo Yii::t('app','You are here:'); ?></span>
        <ol class="breadcrumb">
          <!--<li><a href="index.html">Home</a></li>-->
          <li class="active"><?php echo Yii::t('app','News'); ?></li>
        </ol>
      </div>
    </div>
    
    <div class="contentpanel">
<div class="col-sm-9 col-lg-12">

<div class="panel-heading">
                          <!-- panel-btns -->
                          <h3 class="panel-title"><?php echo Yii::t('app','Site News'); ?></h3>
                        </div>
                        
                        <div class="people-item">
                       
                        
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
<?php	echo Yii::t('app','Author &nbsp;&nbsp;: &nbsp;&nbsp;') . ucfirst($sender); ?></div>
<div class="clearfix"></div>
		<div class="message-date"><?php echo Yii::t('app','Last Updated:'); ?> 
        <?php  
				$date_time		=	Configurations::model()->convertDateTime($msg->created);
				echo $date_time;
			?>
        </div>
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

</div>
</div>

