<style type="text/css">
.btn-danger {
    background-color: #d9534f;
    border-color: #d43f3a;
    color: #fff !important;
	
}
.mailbox-menu-item:hover{ border-right: 1px solid #d0d7e5 !important;
	 border-top: 0px solid #d0d7e5 !important;
	 border-bottom: 0px solid #d0d7e5 !important;
	 border-left: 0px solid #d0d7e5 !important;}

</style>

<?php

$newMsgs = $this->module->getNewMsgs();
$action = $this->getAction()->getId();

if($this->module->authManager)
{
	$authNew = Yii::app()->user->checkAccess("Mailbox.Message.New");
	$authInbox = Yii::app()->user->checkAccess("Mailbox.Message.Inbox");
	$authSent = Yii::app()->user->checkAccess("Mailbox.Message.Sent");
	$authTrash = Yii::app()->user->checkAccess("Mailbox.Message.Trash");
}
else
{
	$authNew = $this->module->sendMsgs && (!$this->module->readOnly || $this->module->isAdmin());
	$authInbox = ( !$this->module->readOnly || $this->module->isAdmin() );
	$authTrash = $this->module->trashbox && (!$this->module->readOnly || $this->module->isAdmin());
	$authSent = $this->module->sentbox && (!$this->module->readOnly || $this->module->isAdmin());
}
?>

    
<div class="panel-body">
<?php
if($authNew) :
	?>
	<a href="<?php echo $this->createUrl('message/new'); ?>">
<div class="button-bg button-bg-none">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>
<li>
    <div class="ui-helper-clearfix" align="center">
    
		<span class="btn btn-danger "><?php echo Yii::t('app','New Message'); ?></span>
	</div>
    </li>
    </ul>
    </div>
    </div>
    </a>
    
   <?php  $roles = Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role
			foreach($roles as $role)
			{
				$rolename = $role->name;
			}
		  if($rolename == 'Admin') {?>
    <a href="<?php echo $this->createUrl('message/newgroup'); ?>"><div class="mailbox-menu-newgrpmsg  ui-helper-clearfix" align="center">
    
		<span><?php echo Yii::t('app','Group Message'); ?></span>
	</div></a>
    </div>
    </div>
<?php }
endif;
    ?>
<div class="portal_mailBox">

<div class="mailbox-menu  ui-helper-clearfix">

<div class="mailbox-menu-folders ui-helper-clearfix">

		<?php
		if($authInbox):?>
		<div id="mailbox-inbox" class="mailbox-menu-item <?php echo ($action=='inbox')? 'mailbox-menu-current' : '' ; ?>">
			<a href="<?php echo $this->createUrl('message/inbox'); ?>" onclick="js:return false;"><?php echo Yii::t('app','Inbox'); ?> </a>
		</div>
		<?php endif;
		if($authSent) : ?>
		<div  id="mailbox-sent" class="mailbox-menu-item <?php if($action=='sent') echo 'mailbox-menu-current '; ?>">
			<a href="<?php echo $this->createUrl('message/sent'); ?>" onclick="js:return false;"><?php echo Yii::t('app','Sent Mail'); ?></a>
		</div>
		<?php endif;
		if($authTrash) : ?>
		<div id="mailbox-trash" class="mailbox-menu-item <?php if($action=='trash') echo 'mailbox-menu-current '; ?>">
			<a href="<?php echo $this->createUrl('message/trash'); ?>"  onclick="js:return false;"><?php echo Yii::t('app','Trash'); ?>  </a> 
		</div>
		<?php endif; ?>
        
	</div>
    </div>
<?php /*?><?php
if($authNew) :
	?>
	<a href="<?php echo $this->createUrl('message/new'); ?>">
<div class="button-bg button-bg-none">
<div class="top-hed-btn-left"> </div>
<div class="top-hed-btn-right">
<ul>
<li>
    <div class="ui-helper-clearfix" align="center">
    
		<span class="btn btn-danger "><?php echo Yii::t('app','New Message'); ?></span>
	</div>
    </li>
    </ul>
    </div>
    </div>
    </a>
    
   <?php  $roles = Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role
			foreach($roles as $role)
			{
				$rolename = $role->name;
			}
		  if($rolename == 'Admin') {?>
    <a href="<?php echo $this->createUrl('message/newgroup'); ?>"><div class="mailbox-menu-newgrpmsg  ui-helper-clearfix" align="center">
    
		<span><?php echo Yii::t('app','Group Message'); ?></span>
	</div></a>
    </div>
    </div>
<?php }
endif;
    ?><?php */?>

