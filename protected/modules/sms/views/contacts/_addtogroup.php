<?php
$groups		= ContactGroups::model()->findAll();
?>
<div class="clear"></div>
<div class="content-box sent_table" id="groups-box">
    <form id="select-group-form">
        <table>
        <tr>
        <td>
			<?php
            foreach($groups as $key=>$group){
            ?>
            <div class="popup_contact">
            <ul>
                <li><input type="checkbox" value="<?php echo $group->id;?>" name="group[]" id="group-<?php echo $group->id;?>" class="select_group" /></li>
                <li><label for="group-<?php echo $group->id;?>"><?php echo $group->group_name;?></label></li>
            </ul>
            </div>
            <?php
            }
            ?>
            </td></tr>
            <tr>
                <td colspan="2"><input type="button" class="popup_contact_but"  id="add-contacts-to-groups" value="<?php echo Yii::t('app', 'Add');?>" /></td>
            </tr>
        </table>
    </form>
</div>

<script>
$('#add-contacts-to-groups').click(function(e) {
    var selectedContactIds	= [];
    $('.contact_box').each(function(index, element) {
		if($(element).hasClass('selected')){
        	selectedContactIds.push($(element).attr('data-contact-id'));
		}
    });
	
	if(selectedContactIds.length==0){
		alert('<?php echo Yii::t('app', 'Select contacts first');?>');
		return;
	}
	
	var sel_groups	= $('#select-group-form .select_group:checked');
	if(sel_groups.length==0){
		alert('<?php echo Yii::t('app', 'Select group');?>');
		return;
	}	
	
	var groups	= [];
	sel_groups.each(function(index, element) {
		groups.push($(element).val());
    });
	
	//ajax
	$.ajax({
		type:"POST",
		url:'<?php echo Yii::app()->createUrl('/sms/contacts/addtogroups');?>',
		data:{contacts:selectedContactIds, groups:groups, "<?php echo Yii::app()->request->csrfTokenName;?>":"<?php echo Yii::app()->request->csrfToken;?>"},
		beforeSend: function(){
			
		},
		success: function(response){
			document.location.reload();
		}
	});
});
</script>