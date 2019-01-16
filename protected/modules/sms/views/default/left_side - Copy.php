<?php 
$roles = Rights::getAssignedRoles(Yii::app()->user->Id); // check for single role
foreach($roles as $role)
	if(sizeof($roles)==1 and $role->name == 'parent')
	{
		$this->renderPartial('/default/parentleft');
	}
	else if(sizeof($roles)==1 and $role->name == 'student')
	{
		$this->renderPartial('/default/studentleft');
	}
	else if(sizeof($roles)==1 and $role->name == 'teacher')
	{
		$this->renderPartial('/default/teacherleft');
	}
	else
	{
	?>
	<div id="othleft-sidebar">
        <!--<div class="lsearch_bar">
        <input name="" type="text" class="lsearch_bar_left" value="Search" />
        <input name="" type="button" class="sbut" />
        <div class="clear"></div>
        </div>-->
        <?php
		function t($message, $category = 'cms', $params = array(), $source = null, $language = null) 
		{
			return $message;
		}
        $this->widget('zii.widgets.CMenu',array(
        'encodeLabel'=>false,
        'activateItems'=>true,
        'activeCssClass'=>'list_active',
        'items'=>array(
			//The Welcome Link
			//array('label'=>''.t('Welcome'),  'url'=>array('/message/index') ,'linkOptions'=>array('class'=>'menu_1' ), 'itemOptions'=>array('id'=>'menu_1') 
			//),
			
			//Email
			array('label'=>''.'<h1>'.Yii::t('app', 'Email').'</h1>',
			'active'=> ((Yii::app()->controller->module->id=='notifications') ? true : false)),
			
			array('label'=>Yii::t('app', 'Compose').'<span>'.Yii::t('app', 'Send an email').'</span>', 'url'=>array('/notifications/default/sendmail'),'linkOptions'=>array('class'=>'compose_1'),'active'=> (Yii::app()->controller->action->id=='sendmail')),
			array('label'=>Yii::t('app', 'Drafts').'<span>'.Yii::t('app', 'View drafts').'</span>', 'url'=>array('/notifications/default/drafts'),'linkOptions'=>array('class'=>'draft'),'active'=> (in_array(Yii::app()->controller->action->id,array('drafts','senddraft')))? true : false),
			array('label'=>Yii::t('app', 'Mailshots').'<span>'.Yii::t('app', 'View Mailshots').'</span>', 'url'=>array('/notifications/default/mailshots'),'linkOptions'=>array('class'=>'mailshots'),'active'=> (Yii::app()->controller->action->id=='mailshots')),
			array('label'=>Yii::t('app', 'Sent Emails').'<span>'.Yii::t('app', 'Sent Emails').'</span>', 'url'=>array('/notifications/default/sentmail'),'linkOptions'=>array('class'=>'send_mail'),'active'=> (in_array(Yii::app()->controller->action->id,array('sentmail','viewsent')))? true : false),
			array('label'=>Yii::t('app', 'Templates').'<span>'.Yii::t('app', 'All Email templates').'</span>', 'url'=>array('/emailtemplates'),'linkOptions'=>array('class'=>'evntlist_ico')),
			
			//SMS
			array('label'=>''.'<h1>'.Yii::t('app', 'SMS').'</h1>',
			'active'=> ((Yii::app()->controller->module->id=='sms' and Yii::app()->controller->id=='sms') ? true : false)),
			
			array('label'=>Yii::t('app', 'Send SMS').'<span>'.Yii::t('app', 'Send an sms').'</span>', 'url'=>array('/sms/send'),
			'active'=> ((Yii::app()->controller->module->id=='sms' and Yii::app()->controller->id=='send' and Yii::app()->controller->action->id=='index') ? true : false),'linkOptions'=>array('class'=>'sent_sms')),
			array('label'=>Yii::t('app', 'Templates').'<span>'.Yii::t('app', 'All SMS templates').'</span>', 'url'=>array('/sms/templates'),
			'active'=> ((Yii::app()->controller->module->id=='sms' and Yii::app()->controller->id=='templates') ? true : false),'linkOptions'=>array('class'=>'evntlist_ico')),
			
			//Contacts
			/*array('label'=>''.'<h1>'.t('Contacts').'</h1>',
			'active'=> ((Yii::app()->controller->module->id=='mailbox' and Yii::app()->controller->id=='contacts') ? true : false)),*/
			
			array('label'=>Yii::t('app', 'All Contacts').'<span>'.Yii::t('app', 'All contacts').'</span>', 'url'=>array('/sms/contacts'),
			'active'=> ((Yii::app()->controller->module->id=='sms' and Yii::app()->controller->id=='contacts' and Yii::app()->controller->action->id!='import') ? true : false),'linkOptions'=>array('class'=>'sent_contact')),
			array('label'=>Yii::t('app', 'Groups').'<span>'.Yii::t('app', 'Contact groups').'</span>', 'url'=>array('/sms/contactgroups'),
			'active'=> ((Yii::app()->controller->module->id=='sms' and Yii::app()->controller->id=='contactgroups') ? true : false),'linkOptions'=>array('class'=>'sent_group')),
			array('label'=>Yii::t('app', 'Import').'<span>'.Yii::t('app', 'Import contacts').'</span>', 'url'=>array('/sms/contacts/import'),
			'active'=> ((Yii::app()->controller->module->id=='sms' and Yii::app()->controller->id=='contacts' and Yii::app()->controller->action->id=='import') ? true : false),'linkOptions'=>array('class'=>'sent_import')),
			
                        array('label'=>Yii::t('app', 'Gateway').'<span>'.Yii::t('app', 'SMS Gateway Settings').'</span>', 'url'=>array('/sms/gateway'),
			'active'=> ((Yii::app()->controller->module->id=='sms' and Yii::app()->controller->id=='gateway' and Yii::app()->controller->action->id=='index') ? true : false),'linkOptions'=>array('class'=>'set_dw_ico')),
			),
        )); ?>
        
	</div>
	
	<?php 
	}
	?>
<script type="text/javascript">

$(document).ready(function () {
	//Hide the second level menu
	$('#othleft-sidebar ul li ul').hide();            
	//Show the second level menu if an item inside it active
	$('li.list_active').parent("ul").show();
	
	$('#othleft-sidebar').children('ul').children('li').children('a').click(function () {                    
	
	if($(this).parent().children('ul').length>0){                  
		$(this).parent().children('ul').toggle();    
	}
	
	});
});
</script>