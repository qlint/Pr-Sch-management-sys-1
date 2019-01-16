<?php
$criteria				= new CDbCriteria;
$criteria->condition	= '`group_id`='.$_GET['group'];
$alllists		= ContactsList::model()->findAll($criteria);
$contact_ids	= array();
foreach($alllists as $list){
	$contact_ids[]	=  $list->contact_id;
}

$criteria				= new CDbCriteria;
$criteria->addNotInCondition('id', $contact_ids);
$remaining				= Contacts::model()->findAll($criteria);
if(count($remaining)>0){
	?>
	<div class="clear"></div>
	<h2><?php echo Yii::t('app', 'Move these also');?></h2>
	<div class="remaining_contacts">
	<?php
	foreach($remaining as $contact){
	?>
	<div class="remain-contact-bx" data-contact-id="<?php echo $contact->id;?>">
		<?php echo '<b>'.$contact->fullname.'</b> : '.$contact->mobile;?>
	</div>
	<?php
	}
	
	if(count($remaining)>0){
	?>
	<div class="clear"></div>
    <div class="extra_options_con" style="margin-top:10px;">
    	<ul>
        	<li>
	<a href="javascript:void(0);" id="add_to_this_group"><?php echo Yii::t('app', 'Add to this group');?></a>
	<?php
	}
}
?>
			</li>
        </ul>
    </div>
</div>